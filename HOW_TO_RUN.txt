========================================================================
                     MEDIQUEUE SA - FINAL PROJECT
          Intelligent Healthcare Triage & Virtual Queuing Platform
========================================================================

QUICK START INSTRUCTIONS (FOR YOU & YOUR FRIENDS)
------------------------------------------------------------------------

REQUIREMENTS:
- XAMPP (Apache + MySQL) installed on Windows, macOS, or Linux.
  Download from: https://www.apachefriends.org/

------------------------------------------------------------------------
STEP-BY-STEP SETUP:
------------------------------------------------------------------------

1. PLACE FOLDER IN XAMPP HTDOCS:
   Copy or move this entire "MEDIQUEUE FINAL PROJECT" folder into your 
   XAMPP web directory:
   - On Windows: C:\xampp\htdocs\MEDIQUEUE FINAL PROJECT
   - On Mac: /Applications/XAMPP/htdocs/MEDIQUEUE FINAL PROJECT
   - On Linux: /opt/lampp/htdocs/MEDIQUEUE FINAL PROJECT

2. START APACHE & MYSQL:
   - Open the XAMPP Control Panel.
   - Click "Start" next to Apache.
   - Click "Start" next to MySQL.

3. AUTOMATIC DATABASE SETUP:
   - You DO NOT need to manually configure MySQL!
   - MediQueue SA automatically detects if the database exists on first load.
   - When you open the application, db.php will automatically create the 
     "mediqueue_sa" database, generate all 10 relational tables, and seed 
     the 12 Durban regional hospitals and 24 medical officers.
   - (Optional manual import): If you prefer using phpMyAdmin, you can import
     the included "schema.sql" file directly into MySQL.

4. OPEN THE APPLICATION IN YOUR BROWSER:
   Navigate to any of the following URLs:

   * MAIN LANDING PAGE & VIRTUAL GATEWAY:
     http://localhost/MEDIQUEUE%20FINAL%20PROJECT/main.php

   * PATIENT PORTAL (Sign In):
     http://localhost/MEDIQUEUE%20FINAL%20PROJECT/login.php

   * PATIENT REGISTRATION:
     http://localhost/MEDIQUEUE%20FINAL%20PROJECT/register.php

   * DOCTOR CLINICAL WORKSPACE:
     http://localhost/MEDIQUEUE%20FINAL%20PROJECT/doctor.php

   * ADMIN HEALTH ANALYTICS:
     http://localhost/MEDIQUEUE%20FINAL%20PROJECT/admin.php

   * ROLE SELECTOR:
     http://localhost/MEDIQUEUE%20FINAL%20PROJECT/indexmedi.php

------------------------------------------------------------------------
DEMO CREDENTIALS:
------------------------------------------------------------------------

1. PATIENT ACCOUNT:
   - Email: patient1@mediqueue.local
   - Password: password
   - Or click "Register" on register.php to create your own account instantly.

2. DOCTOR WORKSPACE:
   - Go to: http://localhost/MEDIQUEUE%20FINAL%20PROJECT/doctor.php
   - Select a facility (e.g., Addington Hospital) and your practitioner name
     (e.g., Dr. Thabo Mokoena) from the dropdowns to start consulting.

3. ADMIN ANALYTICS:
   - Go to: http://localhost/MEDIQUEUE%20FINAL%20PROJECT/admin.php
   - View real-time KPI metrics, triage breakdowns, and case distributions.

------------------------------------------------------------------------
OPTIONAL PYTHON AI MICROSERVICES:
------------------------------------------------------------------------
The core PHP API (api.php) has robust built-in algorithmic fallback logic 
for symptom triage scoring and queue wait times, so the app runs 100% 
out-of-the-box without Python.

If you also wish to launch the optional machine learning Flask microservices:
1. Open a terminal in the "triage-service" folder:
   pip install -r requirements.txt
   python app.py  (runs on port 5002)

2. Open a terminal in the "wait-service" folder:
   pip install -r requirements.txt
   python app.py  (runs on port 5001)

========================================================================
Developed for MediQueue SA - Republic of South Africa
========================================================================
