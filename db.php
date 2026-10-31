<?php
/*
 * Database configuration for local development and shared PHP hosting.
 * Install schema.sql once through phpMyAdmin; never run schema changes on every request.
 */
$localConfigPath = __DIR__ . DIRECTORY_SEPARATOR . "config.local.php";
$localConfig = [];
if (is_file($localConfigPath)) {
    $loadedConfig = require $localConfigPath;
    if (is_array($loadedConfig)) $localConfig = $loadedConfig;
}
$mediqueueConfig = array_merge([
    "db_host" => "localhost",
    "db_port" => 3306,
    "db_name" => "mediqueue_sa",
    "db_user" => "root",
    "db_password" => "",
    "wait_service_url" => "",
    "triage_service_url" => "",
    // Identity-by-number guest mode is unsafe; enable only for isolated local demos.
    "allow_guest_patient_api" => false,
], $localConfig);
$environmentMap = [
    "MEDIQUEUE_DB_HOST" => "db_host",
    "MEDIQUEUE_DB_PORT" => "db_port",
    "MEDIQUEUE_DB_NAME" => "db_name",
    "MEDIQUEUE_DB_USER" => "db_user",
    "MEDIQUEUE_DB_PASSWORD" => "db_password",
    "MEDIQUEUE_WAIT_SERVICE_URL" => "wait_service_url",
    "MEDIQUEUE_TRIAGE_SERVICE_URL" => "triage_service_url",
    "MEDIQUEUE_ALLOW_GUEST_PATIENT_API" => "allow_guest_patient_api",
];
foreach ($environmentMap as $environmentName => $configKey) {
    $environmentValue = getenv($environmentName);
    if ($environmentValue !== false) $mediqueueConfig[$configKey] = $environmentValue;
}
$charset = "utf8mb4";
$dsn = sprintf("mysql:host=%s;port=%d;dbname=%s;charset=%s",
    $mediqueueConfig["db_host"],
    (int)$mediqueueConfig["db_port"],
    $mediqueueConfig["db_name"],
    $charset
);
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
try {
    $pdo = new PDO($dsn, (string)$mediqueueConfig["db_user"],
        (string)$mediqueueConfig["db_password"], $options);
} catch (PDOException $e) {
    error_log("[MediQueue] Database connection failed: " . $e->getMessage());
    http_response_code(500);
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode([
        "success" => false,
        "error" => "Database connection failed. Check the hosting database settings."
    ]);
    exit;
}
