<?php

/*
|--------------------------------------------------------------------------
| MediQueue SA API
|
|
| API + Queueing + Live Updates
|--------------------------------------------------------------------------
*/


ini_set("display_errors", "0");
error_reporting(E_ALL);
date_default_timezone_set("Africa/Johannesburg");

session_start();
require_once __DIR__ . "/db.php";

// UI and API share an origin; do not enable arbitrary cross-origin access.
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit;
}


/*
|--------------------------------------------------------------------------
| Response helper
|--------------------------------------------------------------------------
*/

function jsonResponse($data, $status = 200)
{
    http_response_code($status);

    header("Content-Type: application/json");

    echo json_encode(
        $data,
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Read JSON
|--------------------------------------------------------------------------
*/

set_exception_handler(function (Throwable $e) {
    error_log(sprintf("[MediQueue API] Unhandled exception: %s in %s:%d",
        $e->getMessage(), $e->getFile(), $e->getLine()));
    http_response_code(500);
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode([
        "success" => false,
        "error" => "An internal server error occurred."
    ]);
    exit;
});

set_error_handler(function ($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return false;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
});

function getInput()
{
    static $cached = null;
    if ($cached !== null) {
        return $cached;
    }
    $body = file_get_contents("php://input");
    if (!$body) {
        $cached = [];
        return $cached;
    }
    $data = json_decode($body, true);
    $cached = is_array($data) ? $data : [];
    return $cached;
}

function requirePatientApi()
{
    global $pdo;

    if (!empty($_SESSION["user_id"]) && ($_SESSION["role"] ?? "") === "patient") {
        $stmt = $pdo->prepare("SELECT p.id, p.user_id, p.full_name FROM patients p WHERE p.user_id = ? LIMIT 1");
        $stmt->execute([(int)$_SESSION["user_id"]]);
        $patient = $stmt->fetch();
        if ($patient) {
            $_SESSION["patient_id"] = (int)$patient["id"];
            return $patient;
        }
    }

    // Support direct API requests from the main.php landing page
    $data = getInput();
    $rawId = trim((string)($data["patient_id"] ?? $data["national_id"] ?? $data["patient_identifier"] ?? ""));

    if ($rawId !== "") {
        if (ctype_digit($rawId)) {
            $stmt = $pdo->prepare("SELECT p.id, p.user_id, p.full_name FROM patients p WHERE p.id = ? LIMIT 1");
            $stmt->execute([(int)$rawId]);
            $patient = $stmt->fetch();
            if ($patient) {
                $_SESSION["user_id"] = (int)$patient["user_id"];
                $_SESSION["role"] = "patient";
                $_SESSION["patient_id"] = (int)$patient["id"];
                return $patient;
            }
        }

        $stmt = $pdo->prepare("SELECT p.id, p.user_id, p.full_name FROM patients p WHERE p.contact_number = ? OR p.full_name = ? LIMIT 1");
        $stmt->execute([$rawId, "Patient " . $rawId]);
        $patient = $stmt->fetch();
        if ($patient) {
            $_SESSION["user_id"] = (int)$patient["user_id"];
            $_SESSION["role"] = "patient";
            $_SESSION["patient_id"] = (int)$patient["id"];
            return $patient;
        }

        // Auto-provision patient record so front-end portal works instantly
        $cleanId = preg_replace('/[^a-zA-Z0-9]/', '', $rawId);
        $guestEmail = "patient_" . ($cleanId ?: uniqid()) . "@mediqueue.local";
        $stmt = $pdo->prepare("INSERT INTO users (email, password_hash, role) VALUES (?, ?, 'patient') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)");
        $stmt->execute([$guestEmail, password_hash("guestpass", PASSWORD_DEFAULT)]);
        $userId = (int)$pdo->lastInsertId();

        $fullName = "Patient " . substr($rawId, 0, 20);
        $stmt = $pdo->prepare("INSERT INTO patients (user_id, full_name, contact_number) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)");
        $stmt->execute([$userId, $fullName, substr($rawId, 0, 30)]);
        $patientId = (int)$pdo->lastInsertId();

        $_SESSION["user_id"] = $userId;
        $_SESSION["role"] = "patient";
        $_SESSION["patient_id"] = $patientId;

        return ["id" => $patientId, "user_id" => $userId, "full_name" => $fullName];
    }

    // Default demo patient fallback (Patient 1) if testing without login
    $stmt = $pdo->query("SELECT p.id, p.user_id, p.full_name FROM patients p ORDER BY p.id ASC LIMIT 1");
    $patient = $stmt->fetch();
    if ($patient) {
        $_SESSION["user_id"] = (int)$patient["user_id"];
        $_SESSION["role"] = "patient";
        $_SESSION["patient_id"] = (int)$patient["id"];
        return $patient;
    }

    jsonResponse(["error" => "Patient login is required."], 401);
}


/*
|--------------------------------------------------------------------------
| Wait time prediction
|--------------------------------------------------------------------------
|
| Calls the wait-time prediction service (see /wait-service in the repo,
| owned by Nickson Matsambira). This is a separate Python/Flask process
| running on localhost:5001 — see wait-service/README.md to run it.
|
| If the service is down or errors, falls back to a simple formula so the
| queue view degrades gracefully instead of breaking.
|
|--------------------------------------------------------------------------
*/

function get_estimated_wait($payload)
{
    global $mediqueueConfig;
    $base = trim((string)($mediqueueConfig["wait_service_url"] ?? ""));
    if ($base !== "" && function_exists("curl_init")) {
        $ch = curl_init(rtrim($base, "/") . "/predict_wait");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        $response = curl_exec($ch);
        $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($response !== false && $status >= 200 && $status < 300) {
            $data = json_decode($response, true);
            if (is_array($data) && isset($data["estimated_wait_minutes"]) && is_numeric($data["estimated_wait_minutes"])) {
                return max(1.0, (float)$data["estimated_wait_minutes"]);
            }
        }
    }
    // No Python URL configured or service unavailable: use the PHP fallback.
    $doctors = max(1, (int)($payload["doctors_on_duty"] ?? 1));
    $avgMinutes = (float)($payload["doctor_avg_consultation_minutes"] ?? 15);
    $ahead = (int)($payload["patients_ahead_same_or_higher_urgency"] ?? 0);
    return max(1.0, round(($ahead / $doctors) * $avgMinutes, 1));
}

/*
|--------------------------------------------------------------------------
| Symptom-text urgency prediction
|--------------------------------------------------------------------------
|
| Calls the symptom classification service on localhost:5002.
| If the service is unavailable, the existing keyword rules are used.
| Input explicitly rejected by the service is returned as a validation error.
|
|--------------------------------------------------------------------------
*/

function fallback_triage_urgency($symptoms)
{
    $text = strtolower($symptoms);

    $highSymptoms = [
        "chest pain",
        "severe breathing difficulty",
        "difficulty breathing",
        "unconscious",
        "severe bleeding",
        "heavy bleeding",
        "stroke",
        "seizure"
    ];

    $moderateSymptoms = [
        "shortness of breath",
        "high fever",
        "persistent vomiting",
        "severe pain",
        "infection",
        "dizziness",
        "dehydration"
    ];

    foreach ($highSymptoms as $symptom) {
        if (strpos($text, $symptom) !== false) {
            return "High";
        }
    }

    foreach ($moderateSymptoms as $symptom) {
        if (strpos($text, $symptom) !== false) {
            return "Moderate";
        }
    }

    return "Low";
}

function get_triage_prediction($symptoms)
{
    global $mediqueueConfig;
    $base = trim((string)($mediqueueConfig["triage_service_url"] ?? ""));
    if ($base !== "" && function_exists("curl_init")) {
        $ch = curl_init(rtrim($base, "/") . "/predict_urgency");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["symptoms" => $symptoms]));
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        $response = curl_exec($ch);
        $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($response !== false) {
            $data = json_decode($response, true);
            if ($status === 422 && is_array($data) && ($data["code"] ?? "") === "invalid_symptom_description") {
                return [
                    "urgency" => null, "source" => "input_rejected",
                    "confidence" => null, "needs_review" => false,
                    "error" => $data["error"] ?? "Please enter a clear symptom description."
                ];
            }
            if ($status >= 200 && $status < 300 && is_array($data)
                && isset($data["urgency"])
                && in_array($data["urgency"], ["Low", "Moderate", "High"], true)) {
                return [
                    "urgency" => $data["urgency"], "source" => "ai_service",
                    "confidence" => isset($data["confidence"]) ? (float)$data["confidence"] : null,
                    "needs_review" => (bool)($data["needs_review"] ?? false),
                    "error" => null
                ];
            }
        }
    }
    return [
        "urgency" => fallback_triage_urgency($symptoms),
        "source" => "keyword_fallback",
        "confidence" => null,
        "needs_review" => false,
        "error" => null
    ];
}

/*
|--------------------------------------------------------------------------
| GET /api/health
|--------------------------------------------------------------------------
*/

if (
    $_SERVER["REQUEST_METHOD"] === "GET"
    &&
    ($_GET["route"] ?? "") === "health"
) {

    jsonResponse([
        "success" => true,
        "service" => "MediQueue SA API",
        "owner" => "Ethan Naidoo",
        "database" => "mediqueue_sa",
        "live_updates" => "Server-Sent Events"
    ]);
}


/*
|--------------------------------------------------------------------------
| POST /api/triage
|--------------------------------------------------------------------------
|
| Saves triage directly into triage_records.
|
|--------------------------------------------------------------------------
*/

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    &&
    ($_GET["route"] ?? "") === "triage"
) {

    $data = getInput();

    $patient = requirePatientApi();
    $patientId = (int)$patient["id"];

    $symptoms =
        trim(
            $data["symptoms"] ?? ""
        );

    if ($symptoms === "") {

        jsonResponse([
            "error" =>
                "At least one symptom is required"
        ], 422);
    }


    /*
    |--------------------------------------------------------------------------
    | Symptom urgency classification
    |--------------------------------------------------------------------------
    */

    $triagePrediction = get_triage_prediction($symptoms);

    if ($triagePrediction["source"] === "input_rejected") {
        jsonResponse([
            "error" => $triagePrediction["error"],
            "code" => "invalid_symptom_description"
        ], 422);
    }

    $urgency = $triagePrediction["urgency"];


    /*
    |--------------------------------------------------------------------------
    | Save triage record
    |--------------------------------------------------------------------------
    */

    $stmt =
        $pdo->prepare(
            "INSERT INTO triage_records
            (
                patient_id,
                symptoms,
                urgency
            )
            VALUES
            (
                ?,
                ?,
                ?
            )"
        );

    $stmt->execute([
        $patientId,
        $symptoms,
        $urgency
    ]);

    $triageId =
        (int)$pdo->lastInsertId();


    jsonResponse([
        "success" => true,
        "triage_id" => $triageId,
        "patient_id" => $patientId,
        "symptoms" => $symptoms,
        "urgency" => $urgency,
        "triage_source" => $triagePrediction["source"],
        "confidence" => $triagePrediction["confidence"],
        "needs_review" => $triagePrediction["needs_review"]
    ], 201);
}



/*
|--------------------------------------------------------------------------
| GET /api/hospitals
|--------------------------------------------------------------------------
*/
if ($_SERVER["REQUEST_METHOD"] === "GET" && ($_GET["route"] ?? "") === "hospitals") {
    $stmt = $pdo->query(
        "SELECT id, name, city, latitude, longitude
         FROM hospitals ORDER BY city, name"
    );
    jsonResponse(["success" => true, "hospitals" => $stmt->fetchAll()]);
}

/*
|--------------------------------------------------------------------------
| GET /api/hospital_doctors
|--------------------------------------------------------------------------
*/
if ($_SERVER["REQUEST_METHOD"] === "GET" && ($_GET["route"] ?? "") === "hospital_doctors") {
    $hospitalId = (int)($_GET["hospital_id"] ?? 0);
    if ($hospitalId <= 0) {
        jsonResponse(["error" => "hospital_id is required"], 422);
    }

    $stmt = $pdo->prepare(
        "SELECT id, full_name, available, average_consultation_minutes
         FROM doctors
         WHERE hospital_id = ?
         ORDER BY full_name"
    );
    $stmt->execute([$hospitalId]);
    jsonResponse(["success" => true, "doctors" => $stmt->fetchAll()]);
}

/*
|--------------------------------------------------------------------------
| POST /api/queue/join
|--------------------------------------------------------------------------
*/

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    &&
    ($_GET["route"] ?? "") === "queue_join"
) {

    $data = getInput();

    $patient = requirePatientApi();
    $patientId = (int)$patient["id"];

    $triageId =
        (int)($data["triage_id"] ?? 0);

    $hospitalId =
        (int)($data["hospital_id"] ?? 0);

    $doctorId =
        (int)($data["doctor_id"] ?? 0);


    if ($triageId <= 0) {

        jsonResponse([
            "error" =>
                "triage_id is required"
        ], 422);
    }

    if ($hospitalId <= 0) {

        jsonResponse([
            "error" =>
                "hospital_id is required"
        ], 422);
    }


    /*
    |--------------------------------------------------------------------------
    | Check hospital exists
    |--------------------------------------------------------------------------
    */

    $hospitalStmt = $pdo->prepare(
        "SELECT id FROM hospitals WHERE id = ? LIMIT 1"
    );
    $hospitalStmt->execute([$hospitalId]);

    if (!$hospitalStmt->fetch()) {
        jsonResponse([
            "error" => "Hospital ID {$hospitalId} does not exist."
        ], 404);
    }

    if ($doctorId <= 0) {
        $docAuto = $pdo->prepare("SELECT id, full_name, available FROM doctors WHERE hospital_id = ? AND available = 1 LIMIT 1");
        $docAuto->execute([$hospitalId]);
        $selectedDoctor = $docAuto->fetch();
        if ($selectedDoctor) {
            $doctorId = (int)$selectedDoctor["id"];
        } else {
            $docAny = $pdo->prepare("SELECT id, full_name, available FROM doctors WHERE hospital_id = ? LIMIT 1");
            $docAny->execute([$hospitalId]);
            $selectedDoctor = $docAny->fetch();
            if ($selectedDoctor) {
                $doctorId = (int)$selectedDoctor["id"];
            }
        }
    }

    if ($doctorId <= 0) {
        jsonResponse([
            "error" => "Please choose a doctor before joining the queue."
        ], 422);
    }

    $doctorStmt = $pdo->prepare(
        "SELECT id, full_name, available FROM doctors
         WHERE id = ? AND hospital_id = ? LIMIT 1"
    );
    $doctorStmt->execute([$doctorId, $hospitalId]);
    $selectedDoctor = $doctorStmt->fetch();

    if (!$selectedDoctor) {
        jsonResponse([
            "error" => "The selected doctor is not assigned to this hospital."
        ], 409);
    }

    if (!(bool)$selectedDoctor["available"]) {
        jsonResponse([
            "error" => "The selected doctor is currently unavailable."
        ], 409);
    }


    /*
    |--------------------------------------------------------------------------
    | Check patient exists
    |--------------------------------------------------------------------------
    */

    $stmt =
        $pdo->prepare(
            "SELECT
                id,
                full_name
             FROM patients
             WHERE id = ?"
        );

    $stmt->execute([
        $patientId
    ]);

    $patient =
        $stmt->fetch();


    if (!$patient) {

        jsonResponse([
            "error" =>
                "Patient not found"
        ], 404);
    }


    /*
    |--------------------------------------------------------------------------
    | Get triage
    |--------------------------------------------------------------------------
    */

    $stmt =
        $pdo->prepare(
            "SELECT
                id,
                urgency
             FROM triage_records
             WHERE id = ?
             AND patient_id = ?"
        );

    $stmt->execute([
        $triageId,
        $patientId
    ]);

    $triage =
        $stmt->fetch();


    if (!$triage) {

        jsonResponse([
            "error" =>
                "Triage record not found"
        ], 404);
    }


    /*
    |--------------------------------------------------------------------------
    | Prevent duplicate active queue entries
    |--------------------------------------------------------------------------
    */

    $stmt =
        $pdo->prepare(
            "SELECT id, queue_number, status
             FROM queue_entries
             WHERE patient_id = ?
             AND hospital_id = ?
             AND status IN
             (
                'Waiting',
                'In Consultation'
             )
             LIMIT 1"
        );

    $stmt->execute([
        $patientId,
        $hospitalId
    ]);

    $existing =
        $stmt->fetch();


    if ($existing) {

        jsonResponse([
            "error" =>
                "Patient already has an active queue entry",

            "queue_id" =>
                $existing["id"],

            "queue_number" =>
                $existing["queue_number"],

            "status" =>
                $existing["status"]
        ], 409);
    }


    /*
    |--------------------------------------------------------------------------
    | Generate unique queue number
    |--------------------------------------------------------------------------
    */

    $todayPrefix = "MQ-" . date("Ymd") . "-";
    $stmt = $pdo->prepare(
        "SELECT queue_number FROM queue_entries
         WHERE queue_number LIKE ?
         ORDER BY id DESC LIMIT 1"
    );
    $stmt->execute([$todayPrefix . "%"]);
    $lastEntry = $stmt->fetch();
    $next = 1;
    if ($lastEntry && preg_match('/-(\d+)$/', $lastEntry["queue_number"], $m)) {
        $next = ((int)$m[1]) + 1;
    }

    $queueNumber = $todayPrefix . str_pad($next, 3, "0", STR_PAD_LEFT);


    /*
    |--------------------------------------------------------------------------
    | Insert queue entry
    |--------------------------------------------------------------------------
    */

    $stmt =
        $pdo->prepare(
            "INSERT INTO queue_entries
            (
                queue_number,
                hospital_id,
                patient_id,
                triage_id,
                doctor_id,
                urgency,
                status
            )
            VALUES
            (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                'Waiting'
            )"
        );

    $stmt->execute([
        $queueNumber,
        $hospitalId,
        $patientId,
        $triageId,
        $doctorId,
        $triage["urgency"]
    ]);


    $queueId =
        (int)$pdo->lastInsertId();


    jsonResponse([
        "success" => true,
        "queue_id" => $queueId,
        "queue_number" => $queueNumber,
        "patient_id" => $patientId,
        "doctor_id" => $doctorId,
        "doctor_name" => $selectedDoctor["full_name"],
        "status" => "Waiting",
        "urgency" => $triage["urgency"]
    ], 201);
}


/*
|--------------------------------------------------------------------------
| GET /api/queue
|--------------------------------------------------------------------------
|
| Correct priority queue.
|
| 75% urgency
| 25% waiting time
|
|--------------------------------------------------------------------------
*/

if (
    $_SERVER["REQUEST_METHOD"] === "GET"
    &&
    ($_GET["route"] ?? "") === "queue"
) {

    $hospitalId =
        (int)($_GET["hospital_id"] ?? 0);


    if ($hospitalId <= 0) {

        jsonResponse([
            "error" =>
                "hospital_id is required"
        ], 422);
    }


    /*
    |--------------------------------------------------------------------------
    | Priority calculation
    |--------------------------------------------------------------------------
    |
    | High    = 75
    | Moderate = 50
    | Low      = 25
    |
    | Waiting time adds up to 25 points.
    |
    | After 60 minutes:
    | Low -> Moderate
    |
    | After 90 minutes:
    | Any waiting patient -> High
    |
    |--------------------------------------------------------------------------
    */

    $sql = "
        SELECT

            q.id,
            q.queue_number,
            q.patient_id,
            p.full_name,
            q.doctor_id,
            COALESCE(d.full_name, 'Unassigned') AS doctor_name,
            h.name AS hospital_name,
            (SELECT pl.location FROM patient_locations pl WHERE pl.patient_id = p.id LIMIT 1) AS location,
            (SELECT pl.latitude FROM patient_locations pl WHERE pl.patient_id = p.id LIMIT 1) AS latitude,
            (SELECT pl.longitude FROM patient_locations pl WHERE pl.patient_id = p.id LIMIT 1) AS longitude,
            (SELECT pl.accuracy_meters FROM patient_locations pl WHERE pl.patient_id = p.id LIMIT 1) AS location_accuracy,
            q.urgency,
            q.status,
            q.joined_at,
            q.started_at,
            q.completed_at,
            q.referral_hospital_id,
            rh.name AS referral_hospital_name,
            rh.city AS referral_hospital_city,

            TIMESTAMPDIFF(
                MINUTE,
                q.joined_at,
                NOW()
            ) AS waiting_minutes,

            CASE

                WHEN
                    TIMESTAMPDIFF(
                        MINUTE,
                        q.joined_at,
                        NOW()
                    ) >= 90
                THEN 'High'

                WHEN
                    q.urgency = 'Low'
                    AND
                    TIMESTAMPDIFF(
                        MINUTE,
                        q.joined_at,
                        NOW()
                    ) >= 60
                THEN 'Moderate'

                ELSE q.urgency

            END AS effective_urgency,

            CASE

                WHEN
                    (
                        CASE
                            WHEN
                                (
                                    CASE
                                        WHEN TIMESTAMPDIFF(
                                            MINUTE,
                                            q.joined_at,
                                            NOW()
                                        ) >= 90
                                        THEN 'High'
                                        WHEN q.urgency = 'Low'
                                            AND TIMESTAMPDIFF(
                                                MINUTE,
                                                q.joined_at,
                                                NOW()
                                            ) >= 60
                                        THEN 'Moderate'
                                        ELSE q.urgency
                                    END
                                ) = 'High'
                                THEN 75
                            WHEN
                                (
                                    CASE
                                        WHEN TIMESTAMPDIFF(
                                            MINUTE,
                                            q.joined_at,
                                            NOW()
                                        ) >= 90
                                        THEN 'High'
                                        WHEN q.urgency = 'Low'
                                            AND TIMESTAMPDIFF(
                                                MINUTE,
                                                q.joined_at,
                                                NOW()
                                            ) >= 60
                                        THEN 'Moderate'
                                        ELSE q.urgency
                                    END
                                ) = 'Moderate'
                                THEN 50
                            ELSE 25
                        END
                    )
                    +
                    LEAST(
                        25,
                        (
                            TIMESTAMPDIFF(
                                MINUTE,
                                q.joined_at,
                                NOW()
                            ) / 90
                        ) * 25
                    )
                    > 100
                THEN 100

                ELSE

                    (
                        CASE
                            WHEN
                                (
                                    CASE
                                        WHEN TIMESTAMPDIFF(
                                            MINUTE,
                                            q.joined_at,
                                            NOW()
                                        ) >= 90
                                        THEN 'High'
                                        WHEN q.urgency = 'Low'
                                            AND TIMESTAMPDIFF(
                                                MINUTE,
                                                q.joined_at,
                                                NOW()
                                            ) >= 60
                                        THEN 'Moderate'
                                        ELSE q.urgency
                                    END
                                ) = 'High'
                                THEN 75
                            WHEN
                                (
                                    CASE
                                        WHEN TIMESTAMPDIFF(
                                            MINUTE,
                                            q.joined_at,
                                            NOW()
                                        ) >= 90
                                        THEN 'High'
                                        WHEN q.urgency = 'Low'
                                            AND TIMESTAMPDIFF(
                                                MINUTE,
                                                q.joined_at,
                                                NOW()
                                            ) >= 60
                                        THEN 'Moderate'
                                        ELSE q.urgency
                                    END
                                ) = 'Moderate'
                                THEN 50
                            ELSE 25
                        END
                    )
                    +
                    LEAST(
                        25,
                        (
                            TIMESTAMPDIFF(
                                MINUTE,
                                q.joined_at,
                                NOW()
                            ) / 90
                        ) * 25
                    )

            END AS priority_score

        FROM queue_entries q

        INNER JOIN patients p
            ON p.id = q.patient_id
        LEFT JOIN doctors d
            ON d.id = q.doctor_id
        INNER JOIN hospitals h
            ON h.id = q.hospital_id
        LEFT JOIN hospitals rh
            ON rh.id = q.referral_hospital_id

        WHERE
            q.hospital_id = ?
            AND q.status IN ('Waiting', 'In Consultation')

        ORDER BY

            CASE
                WHEN q.status = 'Waiting'
                    THEN 1
                WHEN q.status = 'In Consultation'
                    THEN 2
                ELSE 3
            END,

            priority_score DESC,

            q.joined_at ASC
    ";


    $stmt =
        $pdo->prepare($sql);

    $viewerPatientId = (($_SESSION['role'] ?? '') === 'patient')
        ? (int)($_SESSION['patient_id'] ?? 0)
        : 0;

    $stmt->execute([
        $hospitalId
    ]);

    $queue =
        $stmt->fetchAll();


    /*
    |--------------------------------------------------------------------------
    | Doctor capacity for this hospital (needed for wait-time prediction)
    |--------------------------------------------------------------------------
    */

    $doctorStmt = $pdo->prepare(
        "SELECT
            COUNT(*) AS doctors_on_duty,
            COALESCE(AVG(average_consultation_minutes), 15) AS avg_consultation_minutes
         FROM doctors
         WHERE hospital_id = ? AND available = 1"
    );
    $doctorStmt->execute([$hospitalId]);
    $doctorInfo = $doctorStmt->fetch();

    $doctorsOnDuty = max(1, (int)$doctorInfo["doctors_on_duty"]);
    $avgConsultMinutes = (float)$doctorInfo["avg_consultation_minutes"];

    $hospitalNameStmt = $pdo->prepare("SELECT name FROM hospitals WHERE id = ?");
    $hospitalNameStmt->execute([$hospitalId]);
    $hospitalName = $hospitalNameStmt->fetch()["name"] ?? "Unknown";

    $urgencyRank = ["Low" => 1, "Moderate" => 2, "High" => 3];


    /*
    |--------------------------------------------------------------------------
    | Add position + estimated wait time
    |--------------------------------------------------------------------------
    */

    $position = 1;
    $urgenciesAhead = []; // effective_urgency of each Waiting patient already passed

    foreach (
        $queue
        as &$patient
    ) {

        if (
            $patient["status"]
            ===
            "Waiting"
        ) {

            $patient["position"] =
                $position++;

            $patientsAheadTotal = count($urgenciesAhead);

            $myRank = $urgencyRank[$patient["effective_urgency"]] ?? 1;
            $patientsAheadSameOrHigher = 0;

            foreach ($urgenciesAhead as $u) {
                if (($urgencyRank[$u] ?? 1) >= $myRank) {
                    $patientsAheadSameOrHigher++;
                }
            }

            $patient["estimated_wait_minutes"] = get_estimated_wait([
                "hospital" => $hospitalName,
                "day_of_week" => date("D"),
                "hour_of_day" => (int)date("G"),
                "patient_urgency" => $patient["effective_urgency"],
                "patients_ahead_total" => $patientsAheadTotal,
                "patients_ahead_same_or_higher_urgency" => $patientsAheadSameOrHigher,
                "doctors_on_duty" => $doctorsOnDuty,
                "doctor_avg_consultation_minutes" => $avgConsultMinutes
            ]);

            $urgenciesAhead[] = $patient["effective_urgency"];

        } else {

            $patient["position"] =
                null;

            $patient["estimated_wait_minutes"] =
                null;
        }


        $patient["priority_score"] =
            round(
                (float)$patient["priority_score"],
                2
            );
    }


    if (($_SESSION["role"] ?? "") === "patient") {
        foreach ($queue as &$queuePatient) {
            // Patients can see the live queue, but never another patient's GPS.
            if ((int)$queuePatient["patient_id"] !== (int)($_SESSION["patient_id"] ?? 0)) {
                $queuePatient["location"] = null;
                $queuePatient["latitude"] = null;
                $queuePatient["longitude"] = null;
                $queuePatient["location_accuracy"] = null;
            }
        }
        unset($queuePatient);
    }

    jsonResponse([
        "success" => true,
        "queue" => $queue
    ]);
}



/*
|--------------------------------------------------------------------------
| POST /api/queue/call_patient
|--------------------------------------------------------------------------
| A doctor may call any waiting patient in their hospital.
|--------------------------------------------------------------------------
*/
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_GET["route"] ?? "") === "queue_call_patient") {
    $data = getInput();
    $doctorId = (int)($data["doctor_id"] ?? 0);
    $hospitalId = (int)($data["hospital_id"] ?? 0);
    $queueId = (int)($data["queue_id"] ?? 0);

    if ($doctorId <= 0 || $hospitalId <= 0 || $queueId <= 0) {
        jsonResponse(["error" => "doctor_id, hospital_id and queue_id are required"], 422);
    }

    $doctorStmt = $pdo->prepare(
        "SELECT id, hospital_id, full_name, available
         FROM doctors WHERE id = ? LIMIT 1"
    );
    $doctorStmt->execute([$doctorId]);
    $doctor = $doctorStmt->fetch();

    if (!$doctor) jsonResponse(["error" => "Doctor not found"], 404);
    if ((int)$doctor["hospital_id"] !== $hospitalId) {
        jsonResponse(["error" => "Doctor does not belong to this hospital"], 409);
    }
    if (!(bool)$doctor["available"]) {
        jsonResponse(["error" => "Doctor is currently unavailable"], 409);
    }

    $activeStmt = $pdo->prepare(
        "SELECT id FROM queue_entries
         WHERE doctor_id = ? AND status = 'In Consultation' LIMIT 1"
    );
    $activeStmt->execute([$doctorId]);
    if ($activeStmt->fetch()) {
        jsonResponse(["error" => "This doctor already has a patient in consultation. Complete that consultation first."], 409);
    }

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare(
            "SELECT q.id, q.queue_number, q.patient_id, q.status
             FROM queue_entries q
             WHERE q.id = ? AND q.hospital_id = ? AND q.status = 'Waiting'
             FOR UPDATE"
        );
        $stmt->execute([$queueId, $hospitalId]);
        $patient = $stmt->fetch();

        if (!$patient) {
            $pdo->rollBack();
            jsonResponse(["error" => "That patient is no longer waiting in this hospital queue."], 409);
        }

        $stmt = $pdo->prepare(
            "UPDATE queue_entries
             SET doctor_id = ?, status = 'In Consultation', started_at = NOW()
             WHERE id = ? AND status = 'Waiting'"
        );
        $stmt->execute([$doctorId, $queueId]);

        if ($stmt->rowCount() !== 1) {
            $pdo->rollBack();
            jsonResponse(["error" => "Patient was already taken by another doctor."], 409);
        }

        $stmt = $pdo->prepare(
            "INSERT INTO consultations
             (queue_entry_id, patient_id, doctor_id, notes, outcome)
             VALUES (?, ?, ?, '', 'In Progress')"
        );
        $stmt->execute([$queueId, $patient["patient_id"], $doctorId]);

        $consultationId = (int)$pdo->lastInsertId();
        $pdo->commit();

        jsonResponse([
            "success" => true,
            "queue_id" => $queueId,
            "queue_number" => $patient["queue_number"],
            "patient_id" => $patient["patient_id"],
            "doctor_id" => $doctorId,
            "doctor_name" => $doctor["full_name"],
            "consultation_id" => $consultationId,
            "status" => "In Consultation"
        ]);
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        jsonResponse(["error" => $e->getMessage()], 500);
    }
}

/*
|--------------------------------------------------------------------------
| POST /api/queue/next
|--------------------------------------------------------------------------
|
| Doctor takes the correct next patient.
|
| Uses a transaction + FOR UPDATE so two doctors
| cannot take the same patient simultaneously.
|
|--------------------------------------------------------------------------
*/

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    &&
    ($_GET["route"] ?? "") === "queue_next"
) {

    $data = getInput();

    $doctorId =
        (int)($data["doctor_id"] ?? 0);

    $hospitalId =
        (int)($data["hospital_id"] ?? 0);


    if ($doctorId <= 0) {

        jsonResponse([
            "error" =>
                "doctor_id is required"
        ], 422);
    }

    if ($hospitalId <= 0) {

        jsonResponse([
            "error" =>
                "hospital_id is required"
        ], 422);
    }

    /* Verify that the doctor exists and belongs to the selected hospital. */
    $doctorStmt = $pdo->prepare(
        "SELECT id, hospital_id, available, full_name
         FROM doctors
         WHERE id = ?
         LIMIT 1"
    );
    $doctorStmt->execute([$doctorId]);
    $doctor = $doctorStmt->fetch();

    if (!$doctor) {
        jsonResponse([
            "error" => "Doctor not found"
        ], 404);
    }

    if ((int)$doctor["hospital_id"] !== $hospitalId) {
        jsonResponse([
            "error" => "Doctor does not belong to this hospital"
        ], 409);
    }

    if (!(bool)$doctor["available"]) {
        jsonResponse([
            "error" => "Doctor is currently unavailable"
        ], 409);
    }


    try {

        $pdo->beginTransaction();


        /*
        |--------------------------------------------------------------------------
        | Lock the highest priority waiting patient
        |--------------------------------------------------------------------------
        */

        $sql = "

            SELECT

                q.id,
                q.queue_number,
                q.patient_id,
                q.triage_id,
                q.urgency,
                q.status,
                q.joined_at

            FROM queue_entries q

            WHERE

                q.hospital_id = ?

                AND

                q.status = 'Waiting'

            ORDER BY

                CASE

                    WHEN
                        TIMESTAMPDIFF(
                            MINUTE,
                            q.joined_at,
                            NOW()
                        ) >= 90
                    THEN 3

                    WHEN
                        q.urgency = 'High'
                    THEN 3

                    WHEN
                        q.urgency = 'Moderate'
                    THEN 2

                    ELSE 1

                END DESC,

                CASE

                    WHEN
                        q.urgency = 'High'
                    THEN 75

                    WHEN
                        q.urgency = 'Moderate'
                    THEN 50

                    ELSE 25

                END

                +

                LEAST(
                    25,
                    (
                        TIMESTAMPDIFF(
                            MINUTE,
                            q.joined_at,
                            NOW()
                        ) / 90
                    ) * 25
                ) DESC,

                q.joined_at ASC

            LIMIT 1

            FOR UPDATE

        ";


        $stmt =
            $pdo->prepare($sql);

        $stmt->execute([
            $hospitalId
        ]);

        $patient =
            $stmt->fetch();


        if (!$patient) {

            $pdo->rollBack();

            jsonResponse([
                "error" =>
                    "No patients are currently waiting"
            ], 404);
        }


        /*
        |--------------------------------------------------------------------------
        | Update queue
        |--------------------------------------------------------------------------
        */

        $stmt =
            $pdo->prepare(
                "UPDATE queue_entries

                 SET
                    doctor_id = ?,
                    status = 'In Consultation',
                    started_at = NOW()

                 WHERE id = ?

                 AND status = 'Waiting'"
            );

        $stmt->execute([
            $doctorId,
            $patient["id"]
        ]);


        /*
        |--------------------------------------------------------------------------
        | Check that update actually happened
        |--------------------------------------------------------------------------
        */

        if (
            $stmt->rowCount() !== 1
        ) {

            $pdo->rollBack();

            jsonResponse([
                "error" =>
                    "Patient was already taken"
            ], 409);
        }


        /*
        |--------------------------------------------------------------------------
        | Create consultation record
        |--------------------------------------------------------------------------
        |
        | Notes can be filled in later by the doctor.
        |
        */

        $stmt =
            $pdo->prepare(
                "INSERT INTO consultations
                (
                    queue_entry_id,
                    patient_id,
                    doctor_id,
                    notes,
                    outcome
                )
                VALUES
                (
                    ?,
                    ?,
                    ?,
                    '',
                    'In Progress'
                )"
            );

        $stmt->execute([
            $patient["id"],
            $patient["patient_id"],
            $doctorId
        ]);


        $consultationId =
            (int)$pdo->lastInsertId();


        $pdo->commit();


        jsonResponse([
            "success" => true,

            "queue_id" =>
                $patient["id"],

            "queue_number" =>
                $patient["queue_number"],

            "patient_id" =>
                $patient["patient_id"],

            "doctor_id" =>
                $doctorId,

            "consultation_id" =>
                $consultationId,

            "status" =>
                "In Consultation"
        ]);
    }

    catch (Throwable $e) {

        if (
            $pdo->inTransaction()
        ) {

            $pdo->rollBack();
        }

        jsonResponse([
            "error" =>
                $e->getMessage()
        ], 500);
    }
}


/*
|--------------------------------------------------------------------------
| POST /api/queue/complete
|--------------------------------------------------------------------------
*/

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    &&
    ($_GET["route"] ?? "") === "queue_complete"
) {

    $data = getInput();

    $queueId =
        (int)($data["queue_id"] ?? 0);

    $notes =
        trim(
            $data["notes"] ?? ""
        );


    if ($queueId <= 0) {

        jsonResponse([
            "error" =>
                "queue_id is required"
        ], 422);
    }


    $pdo->beginTransaction();


    try {

        /*
        |--------------------------------------------------------------------------
        | Lock queue entry
        |--------------------------------------------------------------------------
        */

        $stmt =
            $pdo->prepare(
                "SELECT *
                 FROM queue_entries
                 WHERE id = ?
                 FOR UPDATE"
            );

        $stmt->execute([
            $queueId
        ]);

        $queue =
            $stmt->fetch();


        if (!$queue) {

            throw new Exception(
                "Queue entry not found"
            );
        }


        if (
            $queue["status"]
            !==
            "In Consultation"
        ) {

            throw new Exception(
                "Patient is not currently in consultation"
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Update queue
        |--------------------------------------------------------------------------
        */

        $stmt =
            $pdo->prepare(
                "UPDATE queue_entries

                 SET
                    status = 'Completed',
                    completed_at = NOW()

                 WHERE id = ?"
            );

        $stmt->execute([
            $queueId
        ]);


        /*
        |--------------------------------------------------------------------------
        | Update consultation
        |--------------------------------------------------------------------------
        */

        $stmt =
            $pdo->prepare(
                "UPDATE consultations

                 SET
                    notes = ?,
                    outcome = 'Completed'

                 WHERE queue_entry_id = ?"
            );

        $stmt->execute([
            $notes,
            $queueId
        ]);


        $pdo->commit();


        jsonResponse([
            "success" => true,
            "queue_id" => $queueId,
            "status" => "Completed"
        ]);
    }

    catch (Throwable $e) {

        $pdo->rollBack();

        jsonResponse([
            "error" =>
                $e->getMessage()
        ], 409);
    }
}


/*
|--------------------------------------------------------------------------
| POST /api/queue/refer
|--------------------------------------------------------------------------
*/

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    &&
    ($_GET["route"] ?? "") === "queue_refer"
) {

    $data = getInput();

    $queueId =
        (int)($data["queue_id"] ?? 0);

    $notes =
        trim(
            $data["notes"] ?? ""
        );


    if ($queueId <= 0) {

        jsonResponse([
            "error" =>
                "queue_id is required"
        ], 422);
    }


    $pdo->beginTransaction();


    try {

        $stmt =
            $pdo->prepare(
                "SELECT *
                 FROM queue_entries
                 WHERE id = ?
                 FOR UPDATE"
            );

        $stmt->execute([
            $queueId
        ]);

        $queue =
            $stmt->fetch();


        if (!$queue) {

            throw new Exception(
                "Queue entry not found"
            );
        }


        if (
            $queue["status"]
            !==
            "In Consultation"
        ) {

            throw new Exception(
                "Patient is not currently in consultation"
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Update queue
        |--------------------------------------------------------------------------
        */

        $stmt =
            $pdo->prepare(
                "UPDATE queue_entries

                 SET
                    status = 'Referral',
                    completed_at = NOW()

                 WHERE id = ?"
            );

        $stmt->execute([
            $queueId
        ]);


        /*
        |--------------------------------------------------------------------------
        | Update consultation
        |--------------------------------------------------------------------------
        */

        $stmt =
            $pdo->prepare(
                "UPDATE consultations

                 SET
                    notes = ?,
                    outcome = 'Referral'

                 WHERE queue_entry_id = ?"
            );

        $stmt->execute([
            $notes,
            $queueId
        ]);


        $pdo->commit();


        jsonResponse([
            "success" => true,
            "queue_id" => $queueId,
            "status" => "Referral"
        ]);
    }

    catch (Throwable $e) {

        $pdo->rollBack();

        jsonResponse([
            "error" =>
                $e->getMessage()
        ], 409);
    }
}



/*
|--------------------------------------------------------------------------
| GET /api/patient_records
|--------------------------------------------------------------------------
| Previous consultation sessions for a patient.
*/
if (
    $_SERVER["REQUEST_METHOD"] === "GET"
    && ($_GET["route"] ?? "") === "patient_records"
) {
    $patient = requirePatientApi();
    $patientId = (int)$patient["id"];

    $stmt = $pdo->prepare(
        "SELECT
            c.id AS consultation_id,
            c.created_at,
            c.notes,
            c.outcome,
            q.queue_number,
            q.urgency,
            q.status,
            q.started_at,
            q.completed_at,
            d.full_name AS doctor_name,
            t.symptoms
         FROM consultations c
         INNER JOIN queue_entries q ON q.id = c.queue_entry_id
         INNER JOIN doctors d ON d.id = c.doctor_id
         LEFT JOIN triage_records t ON t.id = q.triage_id
         WHERE c.patient_id = ?
         ORDER BY c.created_at DESC"
    );
    $stmt->execute([$patientId]);
    jsonResponse(["success" => true, "records" => $stmt->fetchAll()]);
}

/*
|--------------------------------------------------------------------------
| GET /api/prescriptions
|--------------------------------------------------------------------------
*/
if (
    $_SERVER["REQUEST_METHOD"] === "GET"
    && ($_GET["route"] ?? "") === "prescriptions"
) {
    $patient = requirePatientApi();
    $patientId = (int)$patient["id"];

    $stmt = $pdo->prepare(
        "SELECT
            p.id,
            p.medication,
            p.instructions,
            p.quantity,
            p.quantity_unit,
            p.duration_value,
            p.duration_unit,
            p.times_per_day,
            p.dose_amount,
            p.dose_unit,
            p.medication_times,
            p.start_date,
            p.end_date,
            p.refill_required,
            p.refill_date,
            p.checkup_required,
            p.checkup_date,
            p.followup_type,
            p.followup_notes,
            p.created_at,
            d.full_name AS doctor_name,
            c.id AS consultation_id
         FROM prescriptions p
         INNER JOIN doctors d ON d.id = p.doctor_id
         INNER JOIN consultations c ON c.id = p.consultation_id
         WHERE p.patient_id = ?
         ORDER BY p.created_at DESC"
    );
    $stmt->execute([$patientId]);
    jsonResponse(["success" => true, "prescriptions" => $stmt->fetchAll()]);
}

/*
|--------------------------------------------------------------------------
| GET/POST /api/patient_location
|--------------------------------------------------------------------------
*/
if (
    ($_SERVER["REQUEST_METHOD"] === "GET" || $_SERVER["REQUEST_METHOD"] === "POST")
    && ($_GET["route"] ?? "") === "patient_location"
) {
    $patient = requirePatientApi();
    $patientId = (int)$patient["id"];
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $input = getInput();
    }

    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        $stmt = $pdo->prepare(
            "SELECT location, latitude, longitude, accuracy_meters, updated_at
             FROM patient_locations WHERE patient_id = ?"
        );
        $stmt->execute([$patientId]);
        $row = $stmt->fetch();
        jsonResponse([
            "success" => true,
            "location" => $row["location"] ?? "",
            "latitude" => $row["latitude"] !== null ? (float)$row["latitude"] : null,
            "longitude" => $row["longitude"] !== null ? (float)$row["longitude"] : null,
            "accuracy_meters" => $row["accuracy_meters"] !== null ? (float)$row["accuracy_meters"] : null,
            "updated_at" => $row["updated_at"] ?? null
        ]);
    }

    $latitude = isset($input["latitude"]) && $input["latitude"] !== "" ? (float)$input["latitude"] : null;
    $longitude = isset($input["longitude"]) && $input["longitude"] !== "" ? (float)$input["longitude"] : null;
    $accuracy = isset($input["accuracy_meters"]) && $input["accuracy_meters"] !== "" ? (float)$input["accuracy_meters"] : null;
    $location = trim($input["location"] ?? "");

    if ($latitude === null || $longitude === null) {
        if ($location === "") jsonResponse(["error" => "GPS coordinates are required"], 422);
    } else {
        if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
            jsonResponse(["error" => "Invalid GPS coordinates"], 422);
        }
        // The browser can supply a human-readable reverse-geocoded place name.
        // Never expose raw GPS coordinates as the patient's displayed location.
        if ($location === "") {
            $location = "Current location";
        }
    }

    $stmt = $pdo->prepare(
        "INSERT INTO patient_locations (patient_id, location, latitude, longitude, accuracy_meters)
         VALUES (?, ?, ?, ?, ?)
         ON DUPLICATE KEY UPDATE
            location = VALUES(location),
            latitude = VALUES(latitude),
            longitude = VALUES(longitude),
            accuracy_meters = VALUES(accuracy_meters)"
    );
    $stmt->execute([$patientId, $location, $latitude, $longitude, $accuracy]);

    jsonResponse([
        "success" => true,
        "patient_id" => $patientId,
        "location" => $location,
        "latitude" => $latitude,
        "longitude" => $longitude,
        "accuracy_meters" => $accuracy
    ]);
}

/*
|--------------------------------------------------------------------------
| GET /api/doctor_stats
|--------------------------------------------------------------------------
| Today's workload for a doctor.
*/
if (
    $_SERVER["REQUEST_METHOD"] === "GET"
    && ($_GET["route"] ?? "") === "doctor_stats"
) {
    $doctorId = (int)($_GET["doctor_id"] ?? 0);
    if ($doctorId <= 0) jsonResponse(["error" => "doctor_id is required"], 422);

    $stmt = $pdo->prepare(
        "SELECT
            COUNT(*) AS patients_today,
            SUM(CASE WHEN q.status IN ('Completed','Referral') THEN 1 ELSE 0 END) AS dealt_with,
            SUM(CASE WHEN q.status = 'In Consultation' THEN 1 ELSE 0 END) AS in_consultation,
            SUM(CASE WHEN q.status = 'Waiting' THEN 1 ELSE 0 END) AS waiting
         FROM queue_entries q
         WHERE q.doctor_id = ?
           AND DATE(q.joined_at) = CURDATE()"
    );
    $stmt->execute([$doctorId]);
    $stats = $stmt->fetch() ?: [];

    // Also count today's consultations assigned to this doctor, including records
    // where doctor_id was only set when the consultation started.
    $stmt = $pdo->prepare(
        "SELECT COUNT(*) FROM consultations
         WHERE doctor_id = ? AND DATE(created_at) = CURDATE()"
    );
    $stmt->execute([$doctorId]);
    $consultationsToday = (int)$stmt->fetchColumn();

    jsonResponse([
        "success" => true,
        "date" => date("Y-m-d"),
        "patients_today" => (int)($stats["patients_today"] ?? 0),
        "dealt_with" => (int)($stats["dealt_with"] ?? 0),
        "in_consultation" => (int)($stats["in_consultation"] ?? 0),
        "waiting" => (int)($stats["waiting"] ?? 0),
        "consultations_started_today" => $consultationsToday
    ]);
}

/*
|--------------------------------------------------------------------------
| POST /api/prescription_create
|--------------------------------------------------------------------------
| Doctor can attach a prescription to the active consultation.
*/
if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && ($_GET["route"] ?? "") === "prescription_create"
) {
    $input = getInput();
    $queueId = (int)($input["queue_id"] ?? 0);
    $doctorId = (int)($input["doctor_id"] ?? 0);
    $medication = trim($input["medication"] ?? "");
    $instructions = trim($input["instructions"] ?? "");
    $quantity = ($input["quantity"] ?? "") !== "" ? (float)$input["quantity"] : null;
    $quantityUnit = trim($input["quantity_unit"] ?? "");
    $durationValue = ($input["duration_value"] ?? "") !== "" ? (int)$input["duration_value"] : null;
    $durationUnit = trim($input["duration_unit"] ?? "");
    $timesPerDay = ($input["times_per_day"] ?? "") !== "" ? (int)$input["times_per_day"] : null;
    $doseAmount = trim($input["dose_amount"] ?? "");
    $doseUnit = trim($input["dose_unit"] ?? "");
    $medicationTimes = trim($input["medication_times"] ?? "");
    $startDate = trim($input["start_date"] ?? "");
    $endDate = trim($input["end_date"] ?? "");
    $refillRequired = !empty($input["refill_required"]) ? 1 : 0;
    $refillDate = trim($input["refill_date"] ?? "");
    $checkupRequired = !empty($input["checkup_required"]) ? 1 : 0;
    $checkupDate = trim($input["checkup_date"] ?? "");
    $followupType = trim($input["followup_type"] ?? "");
    $followupNotes = trim($input["followup_notes"] ?? "");

    if ($queueId <= 0 || $doctorId <= 0 || $medication === "") {
        jsonResponse(["error" => "queue_id, doctor_id and medication are required"], 422);
    }

    $stmt = $pdo->prepare(
        "SELECT q.id, q.patient_id, c.id AS consultation_id
         FROM queue_entries q
         INNER JOIN consultations c ON c.queue_entry_id = q.id
         WHERE q.id = ? AND q.doctor_id = ? AND q.status = 'In Consultation'
         LIMIT 1"
    );
    $stmt->execute([$queueId, $doctorId]);
    $active = $stmt->fetch();

    if (!$active) {
        jsonResponse(["error" => "Active consultation not found for this doctor"], 409);
    }

    $stmt = $pdo->prepare(
        "INSERT INTO prescriptions
         (consultation_id, patient_id, doctor_id, medication, instructions, quantity, quantity_unit, duration_value, duration_unit, times_per_day, dose_amount, dose_unit, medication_times, start_date, end_date, refill_required, refill_date, checkup_required, checkup_date, followup_type, followup_notes)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULLIF(?, ''), NULLIF(?, ''), ?, NULLIF(?, ''), ?, NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''))"
    );
    $stmt->execute([
        $active["consultation_id"],
        $active["patient_id"],
        $doctorId,
        $medication,
        $instructions,
        $quantity, $quantityUnit ?: null, $durationValue, $durationUnit ?: null, $timesPerDay,
        $doseAmount ?: null, $doseUnit ?: null, $medicationTimes ?: null,
        $startDate, $endDate, $refillRequired, $refillDate, $checkupRequired, $checkupDate,
        $followupType, $followupNotes
    ]);

    jsonResponse([
        "success" => true,
        "prescription_id" => (int)$pdo->lastInsertId(),
        "message" => "Prescription saved"
    ], 201);
}


/*
|--------------------------------------------------------------------------
| POST /api/choose_referral_hospital
|--------------------------------------------------------------------------
| Patient chooses one of the live-GPS hospital recommendations.
*/
if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && ($_GET["route"] ?? "") === "choose_referral_hospital"
) {
    $patient = requirePatientApi();
    $data = getInput();
    $queueId = (int)($data["queue_id"] ?? 0);
    $hospitalId = (int)($data["hospital_id"] ?? 0);

    if ($queueId <= 0 || $hospitalId <= 0) {
        jsonResponse(["error" => "queue_id and hospital_id are required"], 422);
    }

    $stmt = $pdo->prepare(
        "SELECT q.id, q.patient_id, q.status, h.id AS hospital_id, h.name, h.city
         FROM queue_entries q
         JOIN hospitals h ON h.id = ?
         WHERE q.id = ? AND q.patient_id = ? AND q.status = 'Referral'
         LIMIT 1"
    );
    $stmt->execute([$hospitalId, $queueId, (int)$patient["id"]]);
    $row = $stmt->fetch();

    if (!$row) {
        jsonResponse(["error" => "Referral or hospital selection is invalid"], 404);
    }

    if ($row["city"] !== "Durban") {
        jsonResponse(["error" => "Only configured Durban hospitals can be selected for this referral"], 422);
    }

    $stmt = $pdo->prepare(
        "UPDATE queue_entries SET referral_hospital_id = ? WHERE id = ? AND patient_id = ?"
    );
    $stmt->execute([$hospitalId, $queueId, (int)$patient["id"]]);

    jsonResponse([
        "success" => true,
        "hospital" => [
            "id" => (int)$hospitalId,
            "name" => $row["name"],
            "city" => $row["city"]
        ],
        "message" => "Referral hospital selected successfully"
    ]);
}

/*
|--------------------------------------------------------------------------
| GET /api/referral_hospitals
|--------------------------------------------------------------------------
| Return the nearest Durban hospitals to a patient's latest live GPS point.
| Used after a doctor marks a consultation as a hospital referral.
*/
if (
    $_SERVER["REQUEST_METHOD"] === "GET"
    && ($_GET["route"] ?? "") === "referral_hospitals"
) {
    $patient = requirePatientApi();
    $patientId = (int)$patient["id"];
    $queueId = (int)($_GET["queue_id"] ?? 0);

    if ($queueId <= 0) {
        jsonResponse(["error" => "queue_id is required"], 422);
    }

    $stmt = $pdo->prepare(
        "SELECT q.id, q.status, q.patient_id
         FROM queue_entries q
         WHERE q.id = ? AND q.patient_id = ? AND q.status = 'Referral'
         LIMIT 1"
    );
    $stmt->execute([$queueId, $patientId]);
    $referral = $stmt->fetch();

    if (!$referral) {
        jsonResponse(["error" => "Referral not found"], 404);
    }

    $stmt = $pdo->prepare(
        "SELECT latitude, longitude, accuracy_meters, updated_at
         FROM patient_locations
         WHERE patient_id = ?
         LIMIT 1"
    );
    $stmt->execute([$patientId]);
    $location = $stmt->fetch();

    if (!$location || $location["latitude"] === null || $location["longitude"] === null) {
        jsonResponse([
            "success" => true,
            "gps_available" => false,
            "hospitals" => [],
            "message" => "Live GPS is required to suggest the nearest Durban hospitals."
        ]);
    }

    $patientLat = (float)$location["latitude"];
    $patientLon = (float)$location["longitude"];

    /*
     * Calculate great-circle distance in PHP so this works on MySQL/MariaDB
     * versions without relying on spatial functions.
     */
    $stmt = $pdo->query(
        "SELECT id, name, city, latitude, longitude
         FROM hospitals
         WHERE city = 'Durban'
           AND id <> 1
           AND latitude IS NOT NULL
           AND longitude IS NOT NULL"
    );
    $hospitals = $stmt->fetchAll();

    $toRadians = function ($degrees) {
        return $degrees * M_PI / 180;
    };

    $results = [];
    foreach ($hospitals as $hospital) {
        $lat1 = $toRadians($patientLat);
        $lat2 = $toRadians((float)$hospital["latitude"]);
        $deltaLat = $toRadians((float)$hospital["latitude"] - $patientLat);
        $deltaLon = $toRadians((float)$hospital["longitude"] - $patientLon);

        $a = sin($deltaLat / 2) ** 2
           + cos($lat1) * cos($lat2) * sin($deltaLon / 2) ** 2;
        $distanceKm = 6371 * 2 * atan2(sqrt($a), sqrt(max(0.0, 1 - $a)));

        if ($distanceKm <= 60) {
            $hospital["distance_km"] = round($distanceKm, 2);
            $hospital["directions_url"] =
                "https://www.google.com/maps/dir/?api=1&origin="
                . rawurlencode($patientLat . "," . $patientLon)
                . "&destination="
                . rawurlencode($hospital["latitude"] . "," . $hospital["longitude"]);
            $results[] = $hospital;
        }
    }

    usort($results, function ($a, $b) {
        return $a["distance_km"] <=> $b["distance_km"];
    });

    jsonResponse([
        "success" => true,
        "gps_available" => true,
        "gps_accuracy_meters" => $location["accuracy_meters"] !== null
            ? (float)$location["accuracy_meters"]
            : null,
        "hospitals" => array_slice($results, 0, 10)
    ]);
}


/*
|--------------------------------------------------------------------------
| GET /api/doctor_referral_hospitals
|--------------------------------------------------------------------------
| Same distance calculation as the patient referral route, but accessible
| to the doctor dashboard for the active referred queue entry.
*/
if (
    $_SERVER["REQUEST_METHOD"] === "GET"
    && ($_GET["route"] ?? "") === "doctor_referral_hospitals"
) {
    $queueId = (int)($_GET["queue_id"] ?? 0);
    if ($queueId <= 0) {
        jsonResponse(["error" => "queue_id is required"], 422);
    }

    $stmt = $pdo->prepare(
        "SELECT q.patient_id
         FROM queue_entries q
         WHERE q.id = ? AND q.status = 'Referral'
         LIMIT 1"
    );
    $stmt->execute([$queueId]);
    $referral = $stmt->fetch();

    if (!$referral) {
        jsonResponse(["error" => "Referral not found"], 404);
    }

    $stmt = $pdo->prepare(
        "SELECT latitude, longitude, accuracy_meters
         FROM patient_locations
         WHERE patient_id = ? LIMIT 1"
    );
    $stmt->execute([(int)$referral["patient_id"]]);
    $location = $stmt->fetch();

    if (!$location || $location["latitude"] === null || $location["longitude"] === null) {
        jsonResponse(["success" => true, "gps_available" => false, "hospitals" => []]);
    }

    $patientLat = (float)$location["latitude"];
    $patientLon = (float)$location["longitude"];

    $hospitals = $pdo->query(
        "SELECT id, name, city, latitude, longitude
         FROM hospitals
         WHERE city = 'Durban'
           AND id <> 1
           AND latitude IS NOT NULL
           AND longitude IS NOT NULL"
    )->fetchAll();

    $results = [];
    foreach ($hospitals as $hospital) {
        $lat1 = deg2rad($patientLat);
        $lat2 = deg2rad((float)$hospital["latitude"]);
        $deltaLat = deg2rad((float)$hospital["latitude"] - $patientLat);
        $deltaLon = deg2rad((float)$hospital["longitude"] - $patientLon);
        $a = sin($deltaLat / 2) ** 2
           + cos($lat1) * cos($lat2) * sin($deltaLon / 2) ** 2;
        $distanceKm = 6371 * 2 * atan2(sqrt($a), sqrt(max(0.0, 1 - $a)));

        if ($distanceKm <= 60) {
            $hospital["distance_km"] = round($distanceKm, 2);
            $hospital["directions_url"] =
                "https://www.google.com/maps/dir/?api=1&origin="
                . rawurlencode($patientLat . "," . $patientLon)
                . "&destination="
                . rawurlencode($hospital["latitude"] . "," . $hospital["longitude"]);
            $results[] = $hospital;
        }
    }

    usort($results, fn($a, $b) => $a["distance_km"] <=> $b["distance_km"]);

    jsonResponse([
        "success" => true,
        "gps_available" => true,
        "hospitals" => array_slice($results, 0, 10)
    ]);
}


/*
|--------------------------------------------------------------------------
| GET /api/admin_stats
|--------------------------------------------------------------------------
| Read-only analytics used by the admin dashboard.
*/
if ($_SERVER["REQUEST_METHOD"] === "GET" && ($_GET["route"] ?? "") === "admin_stats") {
    $monthStart = date('Y-m-01');

    $stmt = $pdo->prepare(
        "SELECT
            SUM(CASE WHEN q.status IN ('Completed','Referral') THEN 1 ELSE 0 END) AS consultations,
            SUM(CASE WHEN q.status IN ('Completed','Referral') THEN 1 ELSE 0 END) AS dealt_with,
            SUM(CASE WHEN q.status = 'Referral' THEN 1 ELSE 0 END) AS referrals,
            AVG(CASE WHEN q.started_at IS NOT NULL AND q.completed_at IS NOT NULL
                     THEN TIMESTAMPDIFF(SECOND, q.started_at, q.completed_at) / 60 ELSE NULL END) AS avg_consult
         FROM queue_entries q
         WHERE q.joined_at >= ?"
    );
    $stmt->execute([$monthStart]);
    $summary = $stmt->fetch() ?: [];

    $consultations = (int)($summary['consultations'] ?? 0);
    $dealtWith = (int)($summary['dealt_with'] ?? 0);
    $referrals = (int)($summary['referrals'] ?? 0);

    $stmt = $pdo->prepare(
        "SELECT urgency, COUNT(*) AS total
         FROM triage_records
         WHERE created_at >= ?
         GROUP BY urgency"
    );
    $stmt->execute([$monthStart]);
    $triage = ['Low'=>0,'Moderate'=>0,'High'=>0];
    foreach ($stmt->fetchAll() as $row) {
        $triage[$row['urgency']] = (int)$row['total'];
    }

    $conditions = [
        'Respiratory Infections' => ['cough','flu','cold','respiratory','sore throat','breathing'],
        'Hypertension (Chronic)' => ['hypertension','high blood pressure','blood pressure'],
        'Skin Conditions' => ['rash','skin','itch','eczema','acne']
    ];
    $conditionCounts = [];
    foreach ($conditions as $name => $terms) {
        $parts = [];
        $params = [$monthStart];
        foreach ($terms as $term) {
            $parts[] = "LOWER(symptoms) LIKE ?";
            $params[] = '%' . strtolower($term) . '%';
        }
        $sql = "SELECT COUNT(*) FROM triage_records WHERE created_at >= ? AND (" . implode(" OR ", $parts) . ")";
        $s = $pdo->prepare($sql);
        $s->execute($params);
        $conditionCounts[$name] = (int)$s->fetchColumn();
    }
    arsort($conditionCounts);

    // A practical proxy for visits avoided: completed/referral virtual cases
    // that did not require an explicit hospital referral.
    $avoidedPct = $consultations > 0 ? round(max(0, $consultations - $referrals) / $consultations * 100) : 0;
    $avgConsult = (float)($summary['avg_consult'] ?? 0);

    jsonResponse([
        'success' => true,
        'month' => date('F Y'),
        'consultations_this_month' => $consultations,
        'hospital_visits_avoided_pct' => $avoidedPct,
        'avg_consult_wait_minutes' => round($avgConsult, 1),
        'hospital_referrals' => $referrals,
        'triage' => $triage,
        'top_conditions' => array_slice($conditionCounts, 0, 3, true)
    ]);
}

/*
|--------------------------------------------------------------------------
| GET /api/events
|--------------------------------------------------------------------------
|
| Live Server-Sent Events.
|
| The browser stays connected and the server checks the
| database for queue changes.
|
|--------------------------------------------------------------------------
*/

if (
    $_SERVER["REQUEST_METHOD"] === "GET"
    &&
    ($_GET["route"] ?? "") === "events"
) {

    $hospitalId =
        (int)($_GET["hospital_id"] ?? 0);


    if ($hospitalId <= 0) {

        http_response_code(422);

        echo "hospital_id is required";

        exit;
    }


    header(
        "Content-Type: text/event-stream"
    );

    header(
        "Cache-Control: no-cache"
    );

    header(
        "Connection: keep-alive"
    );

    header(
        "X-Accel-Buffering: no"
    );


    /*
    |--------------------------------------------------------------------------
    | Get current queue update timestamp
    |--------------------------------------------------------------------------
    */

    $lastSignature = "";


    $startTime =
        time();


    /*
    |--------------------------------------------------------------------------
    | Keep connection open for 30 seconds.
    |
    | Browser automatically reconnects.
    |--------------------------------------------------------------------------
    */

    while (
        time() - $startTime < 30
    ) {

        $stmt =
            $pdo->prepare(
                "SELECT

                    COUNT(*) AS total,

                    COALESCE(
                        MAX(
                            updated_at
                        ),
                        '1970-01-01 00:00:00'
                    ) AS latest_update

                 FROM queue_entries

                 WHERE hospital_id = ?"
            );

        $stmt->execute([
            $hospitalId
        ]);

        $state =
            $stmt->fetch();


        $signature =
            $state["total"]
            .
            "|"
            .
            $state["latest_update"];


        /*
        |--------------------------------------------------------------------------
        | Send event when database changed
        |--------------------------------------------------------------------------
        */

        if (
            $signature !==
            $lastSignature
        ) {

            echo "event: queue.updated\n";

            echo
                "data: "
                .
                json_encode([
                    "hospital_id" =>
                        $hospitalId,

                    "total" =>
                        (int)$state["total"],

                    "updated_at" =>
                        $state["latest_update"]
                ])
                .
                "\n\n";


            $lastSignature =
                $signature;


            @ob_flush();

            @flush();
        }


        /*
        |--------------------------------------------------------------------------
        | Heartbeat
        |--------------------------------------------------------------------------
        */

        echo ": heartbeat\n\n";

        @ob_flush();

        @flush();


        sleep(1);
    }


    exit;
}


/*
|--------------------------------------------------------------------------
| Unknown route
|--------------------------------------------------------------------------
*/

jsonResponse([
    "error" =>
        "Route not found"
], 404);
?>