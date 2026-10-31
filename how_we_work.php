<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>How We Work | MediQueue SA</title>
    <meta name="description" content="Learn how MediQueue SA's virtual healthcare queue operates — from patient registration through AI triage to doctor consultation.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --primary: #083d49;
            --green:   #597948;
            --bg:      #eae6e3;
            --card-bg: #f8f6f3;
            --muted:   #4f6368;
            --border:  rgba(8,61,73,0.14);
        }
        body {
            font-family: 'Inter', Arial, sans-serif;
            background: var(--bg);
            color: var(--primary);
            min-height: 100vh;
        }

        /* PAGE HEADER */
        .page-header {
            background: var(--primary);
            color: #eae6e3;
            padding: 64px 6% 56px;
            text-align: center;
        }
        .page-header .breadcrumb {
            font-size: 12px;
            opacity: 0.6;
            margin-bottom: 14px;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }
        .page-header .breadcrumb a { color: inherit; text-decoration: none; }
        .page-header .breadcrumb a:hover { opacity: 0.9; text-decoration: underline; }
        .page-header h1 {
            font-size: clamp(30px, 5vw, 52px);
            font-weight: 800;
            margin-bottom: 16px;
            line-height: 1.1;
        }
        .page-header p {
            max-width: 620px;
            margin: 0 auto;
            font-size: 16px;
            opacity: 0.82;
            line-height: 1.75;
        }

        /* MAIN CONTENT */
        .content-wrap {
            max-width: 1060px;
            margin: 0 auto;
            padding: 60px 6% 80px;
        }

        /* PROCESS TIMELINE */
        .process-label {
            text-align: center;
            margin-bottom: 48px;
        }
        .process-label h2 {
            font-size: clamp(22px, 3vw, 30px);
            font-weight: 700;
            margin-bottom: 10px;
        }
        .process-label p {
            color: var(--muted);
            font-size: 15px;
            max-width: 560px;
            margin: 0 auto;
            line-height: 1.65;
        }

        .timeline {
            display: flex;
            flex-direction: column;
            gap: 0;
            position: relative;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 38px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, var(--primary) 0%, rgba(8,61,73,0.2) 100%);
        }

        .timeline-step {
            display: flex;
            gap: 28px;
            align-items: flex-start;
            padding: 0 0 40px;
            position: relative;
        }

        .step-node {
            flex-shrink: 0;
            width: 78px;
            height: 78px;
            border-radius: 50%;
            background: var(--primary);
            color: #eae6e3;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            position: relative;
            z-index: 1;
            box-shadow: 0 4px 20px rgba(8,61,73,0.25);
        }

        .step-node.green {
            background: var(--green);
        }

        .step-num {
            font-size: 10px;
            font-weight: 700;
            opacity: 0.7;
            letter-spacing: 0.5px;
        }

        .step-body {
            flex: 1;
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 26px 28px;
            margin-top: 8px;
        }

        .step-body h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 8px;
        }

        .step-body p {
            color: var(--muted);
            font-size: 14.5px;
            line-height: 1.7;
        }

        .step-tag {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .tag-patient { background: #dbe7e8; color: var(--primary); }
        .tag-doctor  { background: #dce5d7; color: var(--green); }
        .tag-system  { background: rgba(8,61,73,0.10); color: var(--primary); }

        /* PHASES SECTION */
        .phases-section {
            margin-top: 70px;
        }
        .phases-section h2 {
            font-size: clamp(22px, 3vw, 28px);
            font-weight: 700;
            text-align: center;
            margin-bottom: 36px;
        }

        .phases-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 22px;
        }

        .phase-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 28px 26px;
            border-left: 4px solid var(--primary);
            transition: box-shadow 0.2s, transform 0.2s;
        }

        .phase-card:nth-child(even) {
            border-left-color: var(--green);
        }

        .phase-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 36px rgba(8,61,73,0.11);
        }

        .phase-card .phase-num {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 8px;
        }

        .phase-card h3 {
            font-size: 17px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 10px;
        }

        .phase-card p {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.65;
        }

        /* CTA */
        .cta-strip {
            margin-top: 64px;
            background: var(--primary);
            color: #eae6e3;
            border-radius: 20px;
            padding: 44px 36px;
            text-align: center;
        }
        .cta-strip h2 { font-size: 24px; font-weight: 700; margin-bottom: 12px; }
        .cta-strip p  { font-size: 15px; opacity: 0.82; margin-bottom: 26px; line-height: 1.65; }
        .cta-btns { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; }
        .cta-btn {
            display: inline-block;
            padding: 13px 28px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: opacity 0.2s, transform 0.2s;
        }
        .cta-btn:hover { opacity: 0.9; transform: translateY(-2px); }
        .cta-btn-light { background: #eae6e3; color: var(--primary); }
        .cta-btn-outline { background: rgba(255,255,255,0.14); color: #eae6e3; border: 1px solid rgba(255,255,255,0.3); }

        footer {
            text-align: center;
            padding: 24px;
            color: var(--muted);
            font-size: 12.5px;
            border-top: 1px solid var(--border);
            background: var(--card-bg);
        }
        footer a { color: var(--primary); text-decoration: none; font-weight: 600; }

        @media (max-width: 700px) {
            .phases-grid { grid-template-columns: 1fr; }
            .timeline::before { left: 29px; }
            .step-node { width: 60px; height: 60px; font-size: 20px; }
            .step-body { padding: 20px; }
            .cta-strip { padding: 32px 22px; }
        }
    </style>
</head>
<body>

<?php $activePage = 'how_we_work'; include 'navbar.php'; ?>

<header class="page-header">
    <div class="breadcrumb">
        <a href="main.php">Home</a> &nbsp;/&nbsp; How It Works &nbsp;/&nbsp; How We Work
    </div>
    <h1>How We Work</h1>
    <p>A step-by-step look at how MediQueue SA connects patients to doctors through a smart, structured virtual queue.</p>
</header>

<main class="content-wrap">

    <!-- TIMELINE -->
    <div class="process-label">
        <h2>The MediQueue Process</h2>
        <p>From a patient's first symptom submission to a completed consultation — here is how the full journey works.</p>
    </div>

    <div class="timeline">

        <div class="timeline-step">
            <div class="step-node">
                <span>👤</span>
                <span class="step-num">01</span>
            </div>
            <div class="step-body">
                <span class="step-tag tag-patient">Patient</span>
                <h3>Patient Registration &amp; Symptom Submission</h3>
                <p>The patient visits MediQueue SA on any device. They enter their name and describe their symptoms in their own words — no medical knowledge required. The platform accepts free-text descriptions, making it accessible to everyone.</p>
            </div>
        </div>

        <div class="timeline-step">
            <div class="step-node green">
                <span>🧠</span>
                <span class="step-num">02</span>
            </div>
            <div class="step-body">
                <span class="step-tag tag-system">System</span>
                <h3>AI-Powered Triage Classification</h3>
                <p>MediQueue's intelligent triage engine — built on TF-IDF text analysis and Logistic Regression — reads the symptom description and classifies the patient's urgency as <strong>Low</strong>, <strong>Moderate</strong>, or <strong>High</strong>. This classification happens in milliseconds and determines queue priority. If the AI service is unavailable, a keyword-based fallback system activates automatically.</p>
            </div>
        </div>

        <div class="timeline-step">
            <div class="step-node">
                <span>📋</span>
                <span class="step-num">03</span>
            </div>
            <div class="step-body">
                <span class="step-tag tag-system">System</span>
                <h3>Queue Entry &amp; Wait-Time Estimation</h3>
                <p>The patient is assigned a queue position based on their urgency level and time of entry. A separate predictive wait-time service calculates an estimated consultation time and keeps it updated as the queue moves. The patient can see this live from any device — there is no need to sit in a waiting room.</p>
            </div>
        </div>

        <div class="timeline-step">
            <div class="step-node green">
                <span>👨‍⚕️</span>
                <span class="step-num">04</span>
            </div>
            <div class="step-body">
                <span class="step-tag tag-doctor">Doctor</span>
                <h3>Doctor Reviews the Queue</h3>
                <p>The doctor logs into their dedicated interface and sees a real-time list of all waiting patients, sorted by urgency. Each patient entry displays their name, symptoms and triage classification — giving the doctor a head start on understanding the case before the consultation even begins.</p>
            </div>
        </div>

        <div class="timeline-step">
            <div class="step-node">
                <span>💬</span>
                <span class="step-num">05</span>
            </div>
            <div class="step-body">
                <span class="step-tag tag-doctor">Doctor</span>
                <h3>Consultation Is Started</h3>
                <p>When ready, the doctor clicks "Start Consultation" for the next patient. This removes the patient from the active queue and opens the consultation screen. The doctor can conduct the appointment, record notes and make clinical decisions from within the same interface.</p>
            </div>
        </div>

        <div class="timeline-step">
            <div class="step-node green">
                <span>✅</span>
                <span class="step-num">06</span>
            </div>
            <div class="step-body">
                <span class="step-tag tag-doctor">Doctor</span>
                <h3>Completion or Referral</h3>
                <p>At the end of the consultation, the doctor marks the case as complete or refers the patient to a specialist. The record is saved to the database, the queue updates automatically and the next patient is ready to be called. The cycle continues efficiently with minimal manual administration.</p>
            </div>
        </div>

    </div>

    <!-- PROJECT PHASES -->
    <div class="phases-section">
        <h2>Project Development Phases</h2>
        <div class="phases-grid">
            <div class="phase-card">
                <div class="phase-num">Phase 1</div>
                <h3>Core Queue Management</h3>
                <p>Building the foundational patient registration system, virtual queue database, and basic doctor interface. Establishing the PHP backend, MySQL schema and the core API routes.</p>
            </div>
            <div class="phase-card">
                <div class="phase-num">Phase 2</div>
                <h3>AI Triage Integration</h3>
                <p>Developing and training the TF-IDF + Logistic Regression triage model on 360 synthetic symptom cases. Integrating the Flask-based AI microservice with the PHP API via HTTP, with keyword-based fallback for reliability.</p>
            </div>
            <div class="phase-card">
                <div class="phase-num">Phase 3</div>
                <h3>Wait-Time Prediction</h3>
                <p>Adding a separate Python/Flask wait-time estimation service on port 5001. Real-time queue position tracking and dynamic estimated wait calculations displayed on the patient interface.</p>
            </div>
            <div class="phase-card">
                <div class="phase-num">Phase 4</div>
                <h3>UI Polish &amp; Deployment</h3>
                <p>Refining both the patient and doctor interfaces for clarity and ease of use. Deploying the full stack on XAMPP with documentation, testing and final validation of all API routes and services.</p>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="cta-strip">
        <h2>Ready to experience MediQueue?</h2>
        <p>Whether you are a patient waiting for care or a doctor managing a clinic queue, MediQueue SA is designed for you.</p>
        <div class="cta-btns">
            <a href="patient.php" class="cta-btn cta-btn-light">I'm a Patient →</a>
            <a href="doctor.php" class="cta-btn cta-btn-outline">I'm a Doctor →</a>
        </div>
    </div>

</main>

<footer>
    MediQueue SA &nbsp;•&nbsp; Virtual Healthcare Queue &nbsp;•&nbsp;
    <a href="contact.php">Contact Us</a>
    &nbsp;•&nbsp; &copy; 2024 MediQueue SA
</footer>

</body>
</html>
