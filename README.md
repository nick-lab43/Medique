# MediQueue SA

Student/demo project for healthcare triage support and virtual queue management.

## Components
- PHP website and API
- MySQL/MariaDB database
- Optional Flask triage and wait-time prediction services
- Built-in PHP fallback logic, so the main application runs without Python services

The included model data is synthetic prototype material. This project is not clinically validated and must not be used for real clinical decisions or real patient records.

## Run locally with XAMPP
1. Copy the project folder into XAMPP's `htdocs` directory.
2. Start Apache and MySQL.
3. In phpMyAdmin, create a database named `mediqueue_sa` and select it.
4. Import `schema.sql` into that selected database.
5. Default local database settings are `localhost`, database `mediqueue_sa`, user `root`, blank password. If your setup differs, copy `config.example.php` to `config.local.php` and edit it.
6. Register a patient account or sign in, then open `http://localhost/MEDIQUEUE%20FINAL%20PROJECT/` or `main.php`.

Import the schema once through phpMyAdmin. The app no longer creates a database or reruns schema changes on every request.

## Free hosting
Follow [DEPLOY_FREE.md](DEPLOY_FREE.md) for a zero-cost demonstration deployment using PHP/MySQL hosting. Python services are optional and remain disabled until their URLs are configured.

## Optional Python services
From the corresponding service directory, install its `requirements.txt` and run `python app.py`. The triage service listens on port 5002 locally; the wait-time service listens on port 5001 locally.

If hosted separately, set `triage_service_url` and/or `wait_service_url` in the untracked `config.local.php` file to the service's base HTTPS URL, without an endpoint path. The PHP API appends `/predict_urgency` and `/predict_wait`.

## Demo and security
- The SQL schema seeds demonstration records. Demo credentials are public and are not secure accounts.
- Patient API guest mode is disabled by default because looking up a patient by ID can impersonate that patient. Keep it disabled on any internet-accessible site.
- The doctor/admin workspaces and some staff API actions still lack complete role-based authentication. Do not use this public demo with real patient data.
- Never commit `config.local.php`, passwords, API keys, or real patient information.
- Use synthetic data only. This project is not production-ready for sensitive health data.
