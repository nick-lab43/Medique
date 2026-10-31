# Free hosting guide (R0 budget)

This is for a demonstration deployment, not a production healthcare system.

## 1. Create free PHP/MySQL hosting
1. Create a free account at InfinityFree and create a free hosting account/subdomain.
2. In the hosting control panel, create a MySQL database.
3. Record the exact database hostname, database name, username and password shown by the host.
4. Open phpMyAdmin, select the new database, and import `schema.sql`.

The SQL file must be imported into the database already created by the host. Do not create a database named `mediqueue_sa` if the host assigned a prefixed name.

## 2. Upload the website
1. Upload the project files to the website document root, usually `htdocs`.
2. Do not upload any `.venv` folder.
3. Keep `index.php`, `main.php`, `api.php`, the other PHP files, `style.css`, and `assets` at the root with their relative paths intact.
4. Copy `config.example.php` to a new file named `config.local.php` using the host's file manager.
5. Edit `config.local.php` with the exact database settings from the hosting control panel. Do not use local XAMPP credentials.
6. Never upload `config.local.php` to GitHub; `.gitignore` excludes it.
7. Open the free subdomain. `index.php` redirects to `main.php`.

If a database error appears, verify the database settings and confirm that `schema.sql` was imported into the same database. Do not publish credentials in screenshots or chat.

## 3. Leave Python services disabled initially
The PHP API includes fallback logic for triage and wait-time estimates. Leave both service URLs blank in `config.local.php` at first. This avoids failed localhost requests and removes the need for separate Python hosting.

If you later deploy Flask services separately, set `triage_service_url` and `wait_service_url` to their base HTTPS URLs. Do not include endpoint paths. Free Python services may sleep when idle, so fallback behaviour remains important.

## 4. Test the deployment
- Homepage opens.
- Registration creates a user.
- Login and logout work.
- Patient portal loads.
- Triage submission saves a record.
- Queue join and queue view work.
- Doctor workspace and admin dashboard load.
- Assets and navigation work on mobile.

If a test fails, capture the exact visible error and inspect the hosting PHP error log. Never share hosting credentials.

## Important limitations
- Free hosting has quotas and may change its policies. Do not start a paid trial, enter payment details, or upgrade.
- The schema seeds demonstration users and hospital data. Demo credentials are public.
- The training datasets are synthetic and the models are not clinically validated.
- Do not collect or store real patient records on this public student/demo deployment.
