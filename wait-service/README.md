# MediQueue SA — Wait Time Prediction Service

**Owner:** Nickson Matsambira (Back-End Business Logic)
**Purpose:** Predicts `estimated_wait_time` for a patient joining the queue,
replacing the hardcoded "+25 points over 90 minutes" heuristic in `api.php`
with a learned estimate based on queue state.

## How it fits into MediQueue SA

1. Patient submits symptoms → triage classifier (Kai's component) assigns
   `urgency` (Low / Moderate / High). This is unchanged by this service.
2. Patient joins the queue → `api.php` now has everything needed to ask
   this service for a wait-time estimate: the patient's urgency, how many
   people are ahead, how many doctors are on duty, and that doctor's
   historical average consultation length.
3. This service returns `estimated_wait_minutes`, which `api.php` stores
   and/or shows to the patient.

This service only needs the patient's `urgency` label as an input — it does
not depend on *how* that label was produced, so it works today against the
existing keyword-based triage and will keep working unchanged once Kai's
classifier replaces it.

## Files

| File | Purpose |
|---|---|
| `nick_wait_time_training_data.csv` | Synthetic training data (500 rows) |
| `train_model.py` | Trains the model, prints accuracy, saves `wait_model.joblib` |
| `wait_model.joblib` | The trained model (regenerate by re-running `train_model.py`) |
| `app.py` | Flask service exposing `POST /predict_wait` |

## Model

- **Type:** Random Forest Regressor (`scikit-learn`), chosen over linear
  regression because wait time is not linear in queue position — urgency
  causes big step-changes (a High-urgency patient's wait barely grows with
  queue depth, a Low-urgency patient's wait grows roughly proportionally).
- **Features:** hospital, day of week, hour of day, patient urgency, patients
  ahead (total and same-or-higher urgency), doctors on duty, doctor's average
  consultation time.
- **Performance on held-out test data:** MAE ≈ 21 minutes, R² ≈ 0.93.

## ⚠️ Known limitation — synthetic data

The training data is **generated, not real hospital data**. It was built to
follow a plausible, documented rule (wait scales with same-or-higher-urgency
patients ahead ÷ doctors on duty × consultation time, discounted by urgency,
plus noise) — see `gen_wait_data.py` generation logic for the exact formula.
This is sufficient to demonstrate the architecture end-to-end for this
project, but the model has **not** learned real-world South African public
hospital wait patterns and should not be presented as validated. This
limitation should be stated explicitly in the project report's methodology
section, not left implicit.

## Running it

### Windows / PowerShell

The commands below are written for the Windows environment used by the MediQueue
development setup. Do not use the Linux/macOS `python3`, `\` line continuation,
or `--break-system-packages` commands from older versions of this README.

From the wait-service directory:

```powershell
# Optional but recommended: create and activate a virtual environment
py -m venv .venv
.\.venv\Scripts\Activate.ps1

# Install dependencies
py -m pip install --upgrade pip
py -m pip install scikit-learn pandas flask joblib

# Train/regenerate the model
py train_model.py

# Start the wait-time service
py app.py
```

The service should listen on:

```text
http://localhost:5001
```

Keep this PowerShell window running.

### Verify the wait-time service

Open a second PowerShell window and run:

```powershell
curl.exe "http://localhost:5001/health"
```

A successful health response should indicate that the service is running, for
example:

```json
{"status":"ok"}
```

### Start the PHP API

The PHP API is a separate service. From the MediQueue `Final Project`
directory, use the PHP executable installed on the development machine.

For the current Windows/XAMPP-style setup:

```powershell
C:\x\php\php.exe -S localhost:8000
```

Keep this PowerShell window running.

The project uses `api.php` as its API entry point; therefore, a request to
`http://localhost:8000/` may return 404 because there is no `index.php`. This
does not indicate that the API is down.

Verify the API with:

```powershell
curl.exe "http://localhost:8000/api.php?route=health"
```

A successful response should identify the MediQueue API and report a successful
health check.

## End-to-end verification

The most important test is not just calling the Python service directly. The
purpose of this component is to prove that the PHP queue flow can obtain a
wait-time estimate from the Python service.

The complete flow is:

```text
Patient symptoms
      ↓
POST /api.php?route=triage
      ↓
triage_id
      ↓
POST /api.php?route=queue_join
      ↓
Patient enters hospital queue
      ↓
GET /api.php?route=queue&hospital_id=1
      ↓
PHP obtains wait-time estimate from Python service
      ↓
estimated_wait_minutes
```

### 1. Submit triage

PowerShell-safe command:

```powershell
$body = @{
    patient_id = 1
    symptoms = "high fever for 2 days"
} | ConvertTo-Json -Compress

$response = Invoke-RestMethod `
    -Uri "http://localhost:8000/api.php?route=triage" `
    -Method POST `
    -ContentType "application/json" `
    -Body $body

$response | ConvertTo-Json -Depth 10
```

Record the returned `triage_id`.

### 2. Join the queue

Use the actual `triage_id` returned above:

```powershell
$triageId = 3   # Replace 3 with the actual returned triage_id

$body = @{
    patient_id = 1
    triage_id = $triageId
    hospital_id = 1
} | ConvertTo-Json -Compress

$response = Invoke-RestMethod `
    -Uri "http://localhost:8000/api.php?route=queue_join" `
    -Method POST `
    -ContentType "application/json" `
    -Body $body

$response | ConvertTo-Json -Depth 10
```

A `409 Conflict` can be expected if the patient is already waiting in that
hospital's queue. In that case, do not submit duplicate queue entries; proceed
to the queue retrieval test.

### 3. Retrieve the queue

```powershell
$response = Invoke-RestMethod `
    -Uri "http://localhost:8000/api.php?route=queue&hospital_id=1" `
    -Method GET

$response | ConvertTo-Json -Depth 20
```

### 4. Acceptance criteria

The queue response should contain, for each waiting patient:

```text
priority_score
position
estimated_wait_minutes
```

The critical field is:

```text
estimated_wait_minutes
```

Its presence in the integrated queue response demonstrates that the queue flow
is producing a wait-time estimate through the wait-time component.

For the tested MediQueue flow, a successful result has the following form:

```json
{
    "success": true,
    "queue": [
        {
            "patient_id": 1,
            "priority_score": 50,
            "position": 2,
            "estimated_wait_minutes": 4.9
        }
    ]
}
```

The exact values will vary with the current queue state and model inputs.

### Direct Python API test

The Python service can also be tested independently with `POST /predict_wait`:

```powershell
$body = @{
    hospital = "Groote Schuur"
    day_of_week = "Tue"
    hour_of_day = 14
    patient_urgency = "Moderate"
    patients_ahead_total = 12
    patients_ahead_same_or_higher_urgency = 4
    doctors_on_duty = 2
    doctor_avg_consultation_minutes = 15.0
} | ConvertTo-Json -Compress

Invoke-RestMethod `
    -Uri "http://localhost:5001/predict_wait" `
    -Method POST `
    -ContentType "application/json" `
    -Body $body
```

A successful response contains:

```json
{
    "estimated_wait_minutes": 22.5
}
```

The exact value depends on the loaded model and supplied inputs.

## Windows command notes

- In PowerShell, use `curl.exe` if you specifically want real cURL syntax.
  `curl` may resolve to PowerShell's `Invoke-WebRequest` alias.
- For JSON POST requests, `Invoke-RestMethod` with `ConvertTo-Json` is
  recommended because it avoids PowerShell quoting problems.
- PowerShell uses the backtick `` ` `` for line continuation, not the Bash
  backslash `\`.
- `--break-system-packages` is not required for a normal Windows Python
  installation.
- Keep the Python service and PHP development server running in their own
  PowerShell windows while performing the integration test.

## API contract

**POST** `/predict_wait`

Request body:
```json
{
  "hospital": "Groote Schuur",
  "day_of_week": "Tue",
  "hour_of_day": 14,
  "patient_urgency": "Moderate",
  "patients_ahead_total": 12,
  "patients_ahead_same_or_higher_urgency": 4,
  "doctors_on_duty": 2,
  "doctor_avg_consultation_minutes": 15.0
}
```

Response:
```json
{ "estimated_wait_minutes": 22.5 }
```

Missing fields return HTTP 400 with an `error` message rather than crashing.

## Calling it from api.php

```php
function get_estimated_wait($payload) {
    $ch = curl_init("http://localhost:5001/predict_wait");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_TIMEOUT, 3); // fail fast, don't block the queue

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
        return null; // caller should fall back to the existing formula
    }
    $data = json_decode($response, true);
    return $data['estimated_wait_minutes'] ?? null;
}
```

Recommend wrapping the call so if this service is down, `api.php` falls
back to the original hardcoded formula rather than failing the request —
keeps the system degrading gracefully.
