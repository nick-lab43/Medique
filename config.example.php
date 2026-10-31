<?php
/* Copy to config.local.php on the host. Never commit config.local.php. */
return [
    "db_host" => "replace_with_host_database_hostname",
    "db_port" => 3306,
    "db_name" => "replace_with_host_database_name",
    "db_user" => "replace_with_host_database_username",
    "db_password" => "replace_with_host_database_password",
    // Optional: leave blank to use built-in PHP fallback algorithms.
    "wait_service_url" => "",
    "triage_service_url" => "",
    // Keep false for any internet-accessible deployment.
    "allow_guest_patient_api" => false,
];
