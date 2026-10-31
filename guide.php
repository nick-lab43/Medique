<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A Guide | MediQueue SA</title>
    <meta name="description" content="Step-by-step guide to using MediQueue SA — for both patients joining the virtual queue and doctors managing consultations.">
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

        .page-header {
            background: linear-gradient(135deg, var(--primary) 0%, #0c5464 100%);
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
        .page-header .breadcrumb a:hover { text-decoration: underline; }
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

        /* TAB SWITCHER */
        .guide-tabs {
            display: flex;
            justify-content: center;
            gap: 0;
            margin: 44px auto 0;
            max-width: 480px;
            background: rgba(255,255,255,0.12);
            border-radius: 14px;
            padding: 5px;
        }

        .guide-tab {
            flex: 1;
            padding: 11px 20px;
            border: none;
            border-radius: 10px;
            background: transparent;
            color: rgba(234,230,227,0.75);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
            font-family: 'Inter', Arial, sans-serif;
        }

        .guide-tab.active {
            background: #eae6e3;
            color: var(--primary);
        }

        .content-wrap {
            max-width: 900px;
            margin: 0 auto;
            padding: 56px 6% 80px;
        }

        /* GUIDE PANEL */
        .guide-panel { display: none; }
        .guide-panel.active { display: block; }

        .panel-intro {
            text-align: center;
            margin-bottom: 44px;
        }
        .panel-intro h2 {
            font-size: clamp(22px, 3vw, 28px);
            font-weight: 700;
            margin-bottom: 10px;
        }
        .panel-intro p {
            color: var(--muted);
            font-size: 15px;
            max-width: 540px;
            margin: 0 auto;
            line-height: 1.65;
        }

        /* GUIDE STEPS */
        .guide-steps {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-bottom: 50px;
        }

        .guide-step {
            display: flex;
            gap: 20px;
            align-items: flex-start;
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 26px 24px;
            transition: box-shadow 0.2s, transform 0.2s;
        }

        .guide-step:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(8,61,73,0.10);
        }

        .guide-step-num {
            flex-shrink: 0;
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 800;
            color: #eae6e3;
        }

        .num-patient { background: var(--primary); }
        .num-doctor  { background: var(--green); }

        .guide-step-content h3 {
            font-size: 16px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 7px;
        }

        .guide-step-content p {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.65;
        }

        .step-tip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 10px;
            padding: 5px 12px;
            border-radius: 20px;
            background: rgba(8,61,73,0.07);
            color: var(--primary);
            font-size: 12px;
            font-weight: 600;
        }

        /* FAQ SECTION */
        .faq-section {
            margin-top: 56px;
        }

        .faq-section h2 {
            font-size: clamp(20px, 2.5vw, 26px);
            font-weight: 700;
            text-align: center;
            margin-bottom: 28px;
        }

        .faq-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .faq-item {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
        }

        .faq-q {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 20px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            color: var(--primary);
            gap: 12px;
        }

        .faq-q:hover { background: rgba(8,61,73,0.04); }

        .faq-arrow {
            flex-shrink: 0;
            font-size: 18px;
            transition: transform 0.22s;
            color: var(--muted);
        }

        .faq-item.open .faq-arrow { transform: rotate(180deg); }

        .faq-a {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
        }

        .faq-item.open .faq-a {
            max-height: 300px;
        }

        .faq-a-inner {
            padding: 0 20px 18px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.7;
            border-top: 1px solid var(--border);
            padding-top: 14px;
        }

        /* CTA */
        .cta-dual {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 56px;
        }

        .cta-card {
            border-radius: 18px;
            padding: 32px 26px;
            text-align: center;
            color: #eae6e3;
        }

        .cta-patient { background: var(--primary); }
        .cta-doctor  { background: var(--green); }

        .cta-card h3 { font-size: 20px; font-weight: 700; margin-bottom: 10px; }
        .cta-card p  { font-size: 14px; opacity: 0.85; margin-bottom: 20px; line-height: 1.6; }
        .cta-card a {
            display: inline-block;
            padding: 11px 24px;
            border-radius: 10px;
            background: rgba(255,255,255,0.18);
            border: 1px solid rgba(255,255,255,0.3);
            color: #eae6e3;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: background 0.2s;
        }
        .cta-card a:hover { background: rgba(255,255,255,0.26); }

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
            .cta-dual { grid-template-columns: 1fr; }
            .guide-step { flex-direction: column; gap: 14px; }
        }
    </style>
</head>
<body>

<?php $activePage = 'guide'; include 'navbar.php'; ?>

<header class="page-header">
    <div class="breadcrumb">
        <a href="main.php">Home</a> &nbsp;/&nbsp; How It Works &nbsp;/&nbsp; A Guide
    </div>
    <h1>A Guide to MediQueue SA</h1>
    <p>Everything you need to know to get started — whether you are a patient joining the queue or a doctor managing consultations.</p>

    <div class="guide-tabs" id="guideTabs">
        <button class="guide-tab active" data-panel="patient" onclick="switchTab('patient')">👤 For Patients</button>
        <button class="guide-tab" data-panel="doctor" onclick="switchTab('doctor')">👨‍⚕️ For Doctors</button>
    </div>
</header>

<main class="content-wrap">

    <!-- PATIENT GUIDE -->
    <div class="guide-panel active" id="panel-patient">
        <div class="panel-intro">
            <h2>Patient Guide</h2>
            <p>Follow these steps to join the MediQueue SA virtual healthcare queue and get the care you need, faster.</p>
        </div>

        <div class="guide-steps">
            <div class="guide-step">
                <div class="guide-step-num num-patient">1</div>
                <div class="guide-step-content">
                    <h3>Open MediQueue SA</h3>
                    <p>Visit the MediQueue SA website on any device — your phone, tablet or computer. No app download is required. Open your browser and navigate to the MediQueue SA link provided by your clinic.</p>
                    <span class="step-tip">💡 Works on any modern browser — Chrome, Safari, Firefox</span>
                </div>
            </div>
            <div class="guide-step">
                <div class="guide-step-num num-patient">2</div>
                <div class="guide-step-content">
                    <h3>Select "I'm a Patient"</h3>
                    <p>On the home page you will see two options: "I'm a Patient" and "I'm a Doctor." Tap or click the blue <strong>"Continue as Patient"</strong> button to open the patient portal.</p>
                </div>
            </div>
            <div class="guide-step">
                <div class="guide-step-num num-patient">3</div>
                <div class="guide-step-content">
                    <h3>Enter Your Details</h3>
                    <p>Enter your name and describe your symptoms in the text box provided. Write in plain language — you do not need to use medical terms. Describe what you are feeling, for how long, and how severe it is.</p>
                    <span class="step-tip">📝 Example: "I have had a high fever and headache for 2 days"</span>
                </div>
            </div>
            <div class="guide-step">
                <div class="guide-step-num num-patient">4</div>
                <div class="guide-step-content">
                    <h3>Submit and Join the Queue</h3>
                    <p>Tap <strong>"Submit"</strong>. MediQueue's AI will automatically assess your symptoms and assign you an urgency level. You will be added to the virtual queue and given your position and estimated wait time.</p>
                </div>
            </div>
            <div class="guide-step">
                <div class="guide-step-num num-patient">5</div>
                <div class="guide-step-content">
                    <h3>Monitor Your Queue Position</h3>
                    <p>You can see your position in the queue in real time. There is no need to sit in the waiting room — stay at home or nearby and check back regularly. The wait time estimate updates automatically as the queue moves.</p>
                    <span class="step-tip">📱 Keep this page open on your phone for live updates</span>
                </div>
            </div>
            <div class="guide-step">
                <div class="guide-step-num num-patient">6</div>
                <div class="guide-step-content">
                    <h3>Arrive for Your Consultation</h3>
                    <p>When your queue position reaches the top, make your way to the clinic. The doctor will already have your symptom details on their screen — your consultation can begin immediately without lengthy paperwork.</p>
                </div>
            </div>
        </div>

        <div class="faq-section">
            <h2>Patient FAQs</h2>
            <div class="faq-list">
                <div class="faq-item">
                    <div class="faq-q" onclick="toggleFaq(this)">
                        What happens if my symptoms are serious?
                        <span class="faq-arrow">⌄</span>
                    </div>
                    <div class="faq-a">
                        <div class="faq-a-inner">If the AI classifies your symptoms as High urgency, you will be moved to the front of the queue and the doctor will be alerted to see you as soon as possible. Always call emergency services (10177 or 112) in a life-threatening emergency instead of using MediQueue.</div>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-q" onclick="toggleFaq(this)">
                        Do I need to create an account?
                        <span class="faq-arrow">⌄</span>
                    </div>
                    <div class="faq-a">
                        <div class="faq-a-inner">No account is required for patients. Simply enter your name and symptoms and you are in the queue. Your information is only used for your current visit.</div>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-q" onclick="toggleFaq(this)">
                        Can I use MediQueue on my phone?
                        <span class="faq-arrow">⌄</span>
                    </div>
                    <div class="faq-a">
                        <div class="faq-a-inner">Yes. MediQueue SA is fully responsive and works on any smartphone, tablet or computer with a modern browser. No app download is needed.</div>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-q" onclick="toggleFaq(this)">
                        What if the wait time seems wrong?
                        <span class="faq-arrow">⌄</span>
                    </div>
                    <div class="faq-a">
                        <div class="faq-a-inner">Wait times are estimates based on current queue length and average consultation durations. They will update automatically as the queue moves. If you have a concern, please speak to the clinic reception desk.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DOCTOR GUIDE -->
    <div class="guide-panel" id="panel-doctor">
        <div class="panel-intro">
            <h2>Doctor Guide</h2>
            <p>Follow these steps to manage your patient queue efficiently using MediQueue SA's doctor interface.</p>
        </div>

        <div class="guide-steps">
            <div class="guide-step">
                <div class="guide-step-num num-doctor">1</div>
                <div class="guide-step-content">
                    <h3>Open the Doctor Interface</h3>
                    <p>From the MediQueue SA home page, click <strong>"Continue as Doctor"</strong>. This opens the doctor portal, which is separate from the patient-facing interface.</p>
                </div>
            </div>
            <div class="guide-step">
                <div class="guide-step-num num-doctor">2</div>
                <div class="guide-step-content">
                    <h3>Enter Your Doctor ID</h3>
                    <p>Enter your assigned Doctor ID to access the queue dashboard. This ensures that only authorised medical staff can view patient information and manage consultations.</p>
                    <span class="step-tip">🔒 Each doctor has a unique ID for accountability</span>
                </div>
            </div>
            <div class="guide-step">
                <div class="guide-step-num num-doctor">3</div>
                <div class="guide-step-content">
                    <h3>View the Live Queue</h3>
                    <p>Your dashboard shows all currently waiting patients, sorted by urgency level (High → Moderate → Low) and then by time of arrival. Each entry shows the patient's name, their symptom description and their AI-assigned urgency class.</p>
                </div>
            </div>
            <div class="guide-step">
                <div class="guide-step-num num-doctor">4</div>
                <div class="guide-step-content">
                    <h3>Review Patient Symptoms</h3>
                    <p>Before calling a patient in, review their symptom submission and triage classification on the dashboard. This gives you a head start on understanding the case and prepares you for the consultation.</p>
                    <span class="step-tip">🧠 AI confidence score is also shown when the AI service is active</span>
                </div>
            </div>
            <div class="guide-step">
                <div class="guide-step-num num-doctor">5</div>
                <div class="guide-step-content">
                    <h3>Start the Consultation</h3>
                    <p>When ready, click <strong>"Start Consultation"</strong> on the patient's entry. This removes the patient from the waiting queue and opens the consultation record. The patient's details remain visible throughout the consultation.</p>
                </div>
            </div>
            <div class="guide-step">
                <div class="guide-step-num num-doctor">6</div>
                <div class="guide-step-content">
                    <h3>Complete or Refer</h3>
                    <p>At the end of the consultation, mark the case as <strong>Complete</strong> or flag it as a <strong>Referral</strong> to a specialist. This closes the record and automatically pulls the next patient from the queue for your review.</p>
                </div>
            </div>
        </div>

        <div class="faq-section">
            <h2>Doctor FAQs</h2>
            <div class="faq-list">
                <div class="faq-item">
                    <div class="faq-q" onclick="toggleFaq(this)">
                        How accurate is the AI triage?
                        <span class="faq-arrow">⌄</span>
                    </div>
                    <div class="faq-a">
                        <div class="faq-a-inner">The AI triage is a decision-support tool, not a clinical diagnosis. It uses TF-IDF text features and Logistic Regression trained on synthetic symptom data. Always apply your own clinical judgment — the triage classification is a starting point, not a final determination.</div>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-q" onclick="toggleFaq(this)">
                        What happens if the AI service is offline?
                        <span class="faq-arrow">⌄</span>
                    </div>
                    <div class="faq-a">
                        <div class="faq-a-inner">MediQueue automatically falls back to a keyword-based triage system when the AI microservice is unavailable. The queue continues to operate without interruption, and each triage record indicates whether AI or the fallback system was used.</div>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-q" onclick="toggleFaq(this)">
                        Can I see patients from another doctor's queue?
                        <span class="faq-arrow">⌄</span>
                    </div>
                    <div class="faq-a">
                        <div class="faq-a-inner">In the current implementation, all doctors share a single queue for the clinic. This allows any available doctor to see and assist the next patient in the queue, improving efficiency during busy periods.</div>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-q" onclick="toggleFaq(this)">
                        Is patient data stored securely?
                        <span class="faq-arrow">⌄</span>
                    </div>
                    <div class="faq-a">
                        <div class="faq-a-inner">Patient data is stored in a MySQL database on the clinic's local server. The patient and doctor interfaces are completely separate, with no patient able to access doctor-only information or other patients' records.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA DUAL -->
    <div class="cta-dual">
        <div class="cta-card cta-patient">
            <h3>👤 I'm a Patient</h3>
            <p>Ready to join the virtual queue? Submit your symptoms and get your place now.</p>
            <a href="patient.php">Continue as Patient →</a>
        </div>
        <div class="cta-card cta-doctor">
            <h3>👨‍⚕️ I'm a Doctor</h3>
            <p>Access the doctor dashboard to view and manage your patient queue.</p>
            <a href="doctor.php">Continue as Doctor →</a>
        </div>
    </div>

</main>

<footer>
    MediQueue SA &nbsp;•&nbsp; Virtual Healthcare Queue &nbsp;•&nbsp;
    <a href="contact.php">Contact Us</a>
    &nbsp;•&nbsp; &copy; 2024 MediQueue SA
</footer>

<script>
function switchTab(panel) {
    document.querySelectorAll('.guide-tab').forEach(function(t) {
        t.classList.toggle('active', t.dataset.panel === panel);
    });
    document.querySelectorAll('.guide-panel').forEach(function(p) {
        p.classList.toggle('active', p.id === 'panel-' + panel);
    });
}

function toggleFaq(el) {
    var item = el.closest('.faq-item');
    var wasOpen = item.classList.contains('open');
    // Close all
    document.querySelectorAll('.faq-item').forEach(function(i) { i.classList.remove('open'); });
    if (!wasOpen) item.classList.add('open');
}
</script>

</body>
</html>
