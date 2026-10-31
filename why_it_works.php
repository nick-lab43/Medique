<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Why It Works | MediQueue SA</title>
    <meta name="description" content="Discover why MediQueue SA's approach to virtual healthcare queuing is effective, evidence-informed and built for South Africa's healthcare needs.">
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
            background: var(--green);
            color: #eae6e3;
            padding: 64px 6% 56px;
            text-align: center;
        }
        .page-header .breadcrumb {
            font-size: 12px;
            opacity: 0.65;
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
            max-width: 640px;
            margin: 0 auto;
            font-size: 16px;
            opacity: 0.85;
            line-height: 1.75;
        }

        .content-wrap {
            max-width: 1060px;
            margin: 0 auto;
            padding: 64px 6% 80px;
        }

        /* PROBLEM vs SOLUTION */
        .compare-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 70px;
        }

        .compare-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 32px;
        }

        .compare-card h2 {
            font-size: 19px;
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .compare-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .compare-list li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 14px;
            color: var(--muted);
            line-height: 1.6;
        }

        .compare-list li .icon {
            flex-shrink: 0;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            margin-top: 2px;
        }

        .icon-bad  { background: #f5ddd8; color: #b33; }
        .icon-good { background: #dce5d7; color: var(--green); }

        /* PILLARS OF EFFECTIVENESS */
        .section-h2 {
            font-size: clamp(22px, 3vw, 30px);
            font-weight: 700;
            text-align: center;
            margin-bottom: 36px;
        }

        .effectiveness-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 70px;
        }

        .eff-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 28px 22px;
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .eff-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 40px rgba(8,61,73,0.11);
        }

        .eff-icon {
            font-size: 36px;
            margin-bottom: 14px;
        }

        .eff-card h3 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 9px;
            color: var(--primary);
        }

        .eff-card p {
            font-size: 13.5px;
            color: var(--muted);
            line-height: 1.65;
        }

        /* AI SECTION */
        .ai-section {
            background: var(--primary);
            color: #eae6e3;
            border-radius: 20px;
            padding: 48px 40px;
            margin-bottom: 70px;
        }

        .ai-section h2 {
            font-size: clamp(20px, 3vw, 28px);
            font-weight: 700;
            margin-bottom: 16px;
        }

        .ai-section p {
            font-size: 15px;
            opacity: 0.85;
            line-height: 1.8;
            max-width: 700px;
            margin-bottom: 18px;
        }

        .ai-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 22px;
        }

        .ai-badge {
            display: inline-block;
            padding: 7px 16px;
            border-radius: 20px;
            background: rgba(255,255,255,0.14);
            border: 1px solid rgba(255,255,255,0.22);
            color: #eae6e3;
            font-size: 13px;
            font-weight: 600;
        }

        /* URGENCY LEVELS */
        .urgency-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-bottom: 70px;
        }

        .urgency-card {
            border-radius: 14px;
            padding: 26px 20px;
            text-align: center;
            border: 1px solid var(--border);
        }

        .u-low    { background: #eaf2e6; }
        .u-mod    { background: #fdf5e4; }
        .u-high   { background: #fde8e4; }

        .urgency-card .u-icon { font-size: 32px; margin-bottom: 12px; }
        .urgency-card h3 { font-size: 17px; font-weight: 700; margin-bottom: 8px; color: var(--primary); }
        .urgency-card p  { font-size: 13.5px; color: var(--muted); line-height: 1.6; }

        /* STATS BANNER */
        .stats-banner {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 70px;
        }

        .stat-item {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 22px 16px;
            text-align: center;
        }

        .stat-item .num {
            font-size: 28px;
            font-weight: 800;
            color: var(--primary);
            line-height: 1.1;
        }

        .stat-item .label {
            font-size: 12px;
            color: var(--muted);
            margin-top: 6px;
            font-weight: 500;
            line-height: 1.4;
        }

        /* CTA */
        .cta-strip {
            background: var(--green);
            color: #eae6e3;
            border-radius: 20px;
            padding: 44px 36px;
            text-align: center;
        }
        .cta-strip h2 { font-size: 24px; font-weight: 700; margin-bottom: 12px; }
        .cta-strip p  { font-size: 15px; opacity: 0.85; margin-bottom: 26px; line-height: 1.65; }
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
        .cta-btn-light   { background: #eae6e3; color: var(--green); }
        .cta-btn-outline { background: rgba(255,255,255,0.16); color: #eae6e3; border: 1px solid rgba(255,255,255,0.3); }

        footer {
            text-align: center;
            padding: 24px;
            color: var(--muted);
            font-size: 12.5px;
            border-top: 1px solid var(--border);
            background: var(--card-bg);
        }
        footer a { color: var(--primary); text-decoration: none; font-weight: 600; }

        @media (max-width: 800px) {
            .compare-grid,
            .effectiveness-grid,
            .urgency-grid { grid-template-columns: 1fr; }
            .stats-banner { grid-template-columns: repeat(2, 1fr); }
            .ai-section { padding: 32px 22px; }
        }
    </style>
</head>
<body>

<?php $activePage = 'why_it_works'; include 'navbar.php'; ?>

<header class="page-header">
    <div class="breadcrumb">
        <a href="main.php">Home</a> &nbsp;/&nbsp; How It Works &nbsp;/&nbsp; Why It Works
    </div>
    <h1>Why It Works</h1>
    <p>MediQueue SA is built on evidence-informed principles. Here is why our approach is effective for patients, doctors and South Africa's healthcare system.</p>
</header>

<main class="content-wrap">

    <!-- PROBLEM vs SOLUTION -->
    <div class="compare-grid">
        <div class="compare-card">
            <h2>😓 The Problem</h2>
            <ul class="compare-list">
                <li><span class="icon icon-bad">✕</span>Patients wait 4+ hours in crowded clinic waiting rooms</li>
                <li><span class="icon icon-bad">✕</span>Doctors have no structured, prioritised patient list</li>
                <li><span class="icon icon-bad">✕</span>High-urgency cases are not always seen first</li>
                <li><span class="icon icon-bad">✕</span>Patients leave before being seen — no accountability</li>
                <li><span class="icon icon-bad">✕</span>No transparency on wait times or queue position</li>
                <li><span class="icon icon-bad">✕</span>Paperwork creates administrative delays and errors</li>
            </ul>
        </div>
        <div class="compare-card">
            <h2>✅ The MediQueue Solution</h2>
            <ul class="compare-list">
                <li><span class="icon icon-good">✓</span>Patients join the queue remotely and arrive when nearly their turn</li>
                <li><span class="icon icon-good">✓</span>Doctors see a structured, urgency-sorted live patient list</li>
                <li><span class="icon icon-good">✓</span>AI triage ensures critical patients are always prioritised</li>
                <li><span class="icon icon-good">✓</span>Queue system provides a clear, recorded audit trail</li>
                <li><span class="icon icon-good">✓</span>Real-time wait estimates visible to every patient</li>
                <li><span class="icon icon-good">✓</span>Digital symptom submission replaces paper intake forms</li>
            </ul>
        </div>
    </div>

    <!-- EFFECTIVENESS PILLARS -->
    <h2 class="section-h2">Why This Approach Is Effective</h2>
    <div class="effectiveness-grid">
        <div class="eff-card">
            <div class="eff-icon">📍</div>
            <h3>Location Independence</h3>
            <p>Patients don't need to sit in a physical space to hold their place in the queue. Any device with a browser is enough — phone, tablet or computer.</p>
        </div>
        <div class="eff-card">
            <div class="eff-icon">⚖️</div>
            <h3>Urgency-Based Fairness</h3>
            <p>Triage prevents the unfair "first come, first served" model. A patient with chest pain is always prioritised above a routine checkup, automatically.</p>
        </div>
        <div class="eff-card">
            <div class="eff-icon">📊</div>
            <h3>Data-Driven Decisions</h3>
            <p>Every queue entry, triage classification and consultation is recorded. This data helps clinics understand patient loads and improve service delivery over time.</p>
        </div>
        <div class="eff-card">
            <div class="eff-icon">🔄</div>
            <h3>Graceful Reliability</h3>
            <p>If the AI triage service is offline, the system automatically falls back to keyword-based classification. The clinic never stops operating due to a technology failure.</p>
        </div>
        <div class="eff-card">
            <div class="eff-icon">🧩</div>
            <h3>Modular Architecture</h3>
            <p>The queue service, AI triage service and wait-time estimator are separate microservices. Each can be upgraded or replaced independently without disrupting the whole system.</p>
        </div>
        <div class="eff-card">
            <div class="eff-icon">🌍</div>
            <h3>Built for South Africa</h3>
            <p>The system is designed for the South African public healthcare context — simple, accessible, and able to handle high patient volumes without expensive hardware.</p>
        </div>
    </div>

    <!-- AI EXPLAINER -->
    <div class="ai-section">
        <h2>🧠 The Science Behind the Triage</h2>
        <p>MediQueue's AI triage engine uses <strong>TF-IDF (Term Frequency–Inverse Document Frequency)</strong> to convert a patient's symptom description into a numerical feature vector. This captures the importance of each word in the medical context.</p>
        <p>A <strong>Logistic Regression</strong> classifier then uses this vector to predict urgency level. The model was trained on 360 balanced synthetic symptom cases across three urgency classes. A stratified 75/25 train-test split ensures reliable evaluation.</p>
        <p>The result is a system that classifies urgency in milliseconds — far faster than manual assessment — and that improves with additional real-world data.</p>
        <div class="ai-badges">
            <span class="ai-badge">TF-IDF Text Features</span>
            <span class="ai-badge">Logistic Regression</span>
            <span class="ai-badge">3-Class Classification</span>
            <span class="ai-badge">360 Training Cases</span>
            <span class="ai-badge">Keyword Fallback</span>
            <span class="ai-badge">Flask Microservice</span>
        </div>
    </div>

    <!-- URGENCY LEVELS -->
    <h2 class="section-h2">Understanding Urgency Levels</h2>
    <div class="urgency-grid">
        <div class="urgency-card u-low">
            <div class="u-icon">🟢</div>
            <h3>Low Urgency</h3>
            <p>Minor symptoms such as mild headaches, slight coughs or routine checkups. Patients are seen in order and after higher-priority cases.</p>
        </div>
        <div class="urgency-card u-mod">
            <div class="u-icon">🟡</div>
            <h3>Moderate Urgency</h3>
            <p>Symptoms that need attention but are not immediately life-threatening — fever, ongoing pain or vomiting. Moved ahead of Low urgency patients.</p>
        </div>
        <div class="urgency-card u-high">
            <div class="u-icon">🔴</div>
            <h3>High Urgency</h3>
            <p>Serious and potentially life-threatening symptoms such as chest pain, difficulty breathing or severe bleeding. Immediate doctor attention is prioritised.</p>
        </div>
    </div>

    <!-- STATS -->
    <div class="stats-banner">
        <div class="stat-item">
            <div class="num">4h+</div>
            <div class="label">Typical public clinic wait time in SA</div>
        </div>
        <div class="stat-item">
            <div class="num">3</div>
            <div class="label">Urgency tiers ensuring fair prioritisation</div>
        </div>
        <div class="stat-item">
            <div class="num">360</div>
            <div class="label">Symptom cases in AI training dataset</div>
        </div>
        <div class="stat-item">
            <div class="num">100%</div>
            <div class="label">Uptime via keyword-based fallback system</div>
        </div>
    </div>

    <!-- CTA -->
    <div class="cta-strip">
        <h2>See it in action</h2>
        <p>Explore the step-by-step process guide or jump straight into MediQueue as a patient or doctor.</p>
        <div class="cta-btns">
            <a href="guide.php" class="cta-btn cta-btn-light">View the Guide →</a>
            <a href="main.php" class="cta-btn cta-btn-outline">Back to Home</a>
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
