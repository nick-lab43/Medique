<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | MediQueue SA</title>
    <meta name="description" content="Contact MediQueue SA — for questions, issues or feedback, reach us at mediqueue.org@gmail.com.">
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
        .page-header .breadcrumb a:hover { text-decoration: underline; }
        .page-header h1 {
            font-size: clamp(30px, 5vw, 52px);
            font-weight: 800;
            margin-bottom: 16px;
            line-height: 1.1;
        }
        .page-header p {
            max-width: 600px;
            margin: 0 auto;
            font-size: 16px;
            opacity: 0.82;
            line-height: 1.75;
        }

        .content-wrap {
            max-width: 900px;
            margin: 0 auto;
            padding: 64px 6% 80px;
        }

        /* MAIN CONTACT CARD */
        .contact-main {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 28px;
            margin-bottom: 52px;
        }

        .contact-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 36px 30px;
        }

        /* EMAIL HERO */
        .email-hero {
            grid-column: span 2;
            background: var(--primary);
            color: #eae6e3;
            border-radius: 24px;
            padding: 52px 44px;
            text-align: center;
            border: none;
        }

        .email-hero .email-icon {
            font-size: 48px;
            margin-bottom: 18px;
        }

        .email-hero h2 {
            font-size: clamp(20px, 3vw, 28px);
            font-weight: 700;
            margin-bottom: 12px;
        }

        .email-hero p {
            font-size: 15px;
            opacity: 0.82;
            max-width: 520px;
            margin: 0 auto 28px;
            line-height: 1.75;
        }

        .email-link-wrap {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.28);
            border-radius: 14px;
            padding: 14px 24px;
            transition: background 0.2s;
        }

        .email-link-wrap:hover {
            background: rgba(255,255,255,0.20);
        }

        .email-link-wrap a {
            color: #eae6e3;
            text-decoration: none;
            font-size: 17px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .email-copy-btn {
            background: rgba(255,255,255,0.18);
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 8px;
            color: #eae6e3;
            padding: 5px 12px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.18s;
            font-family: 'Inter', Arial, sans-serif;
        }

        .email-copy-btn:hover {
            background: rgba(255,255,255,0.28);
        }

        .email-copy-btn.copied {
            background: var(--green);
            border-color: var(--green);
        }

        /* CONTACT INFO CARDS */
        .contact-card h3 {
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .contact-card p {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.7;
        }

        .response-time {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 14px;
            padding: 5px 12px;
            background: #dce5d7;
            color: var(--green);
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        /* WHAT TO INCLUDE */
        .include-section {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 36px 32px;
            margin-bottom: 44px;
        }

        .include-section h2 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 18px;
            color: var(--primary);
        }

        .include-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
        }

        .include-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-size: 14px;
            color: var(--muted);
            line-height: 1.6;
        }

        .include-item .inc-icon {
            flex-shrink: 0;
            font-size: 20px;
            margin-top: 1px;
        }

        .include-item strong { color: var(--primary); font-weight: 600; }

        /* DISCLAIMER */
        .disclaimer {
            background: #fdf5e4;
            border: 1px solid rgba(185,145,35,0.2);
            border-radius: 14px;
            padding: 22px 24px;
            margin-bottom: 44px;
            display: flex;
            gap: 14px;
            align-items: flex-start;
        }

        .disclaimer .disc-icon { font-size: 22px; flex-shrink: 0; }
        .disclaimer p { font-size: 13.5px; color: #7a5e10; line-height: 1.65; }
        .disclaimer p strong { color: #5a4408; }

        /* BACK HOME */
        .back-home {
            text-align: center;
        }

        .back-home a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 28px;
            border-radius: 12px;
            background: var(--primary);
            color: #eae6e3;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: opacity 0.2s, transform 0.2s;
        }

        .back-home a:hover { opacity: 0.9; transform: translateY(-2px); }

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
            .contact-main { grid-template-columns: 1fr; }
            .email-hero { grid-column: span 1; padding: 36px 24px; }
            .include-grid { grid-template-columns: 1fr; }
            .email-link-wrap { flex-direction: column; gap: 8px; }
        }
    </style>
</head>
<body>

<?php $activePage = 'contact'; include 'navbar.php'; ?>

<header class="page-header">
    <div class="breadcrumb">
        <a href="main.php">Home</a> &nbsp;/&nbsp; Contact Us
    </div>
    <h1>Contact Us</h1>
    <p>Have a question, issue or feedback? We would love to hear from you. Reach out and we will get back to you as soon as possible.</p>
</header>

<main class="content-wrap">

    <!-- EMAIL HERO + CONTACT CARDS -->
    <div class="contact-main">

        <div class="contact-card email-hero">
            <div class="email-icon">✉️</div>
            <h2>Get in Touch with MediQueue SA</h2>
            <p>
                Whether you have a question about how the system works, a technical issue,
                or feedback on your experience — send us an email and our team will respond promptly.
            </p>
            <div class="email-link-wrap">
                <a href="mailto:mediqueue.org@gmail.com" id="emailAddr">mediqueue.org@gmail.com</a>
                <button class="email-copy-btn" id="copyBtn" onclick="copyEmail()">Copy</button>
            </div>
        </div>

        <div class="contact-card">
            <h3>⏱️ Response Time</h3>
            <p>We aim to respond to all email enquiries within <strong>1–2 business days</strong>. For urgent technical issues please include as much detail as possible about the problem you are experiencing so we can resolve it quickly.</p>
            <span class="response-time">● 1–2 Business Days</span>
        </div>

        <div class="contact-card">
            <h3>🏥 About MediQueue SA</h3>
            <p>MediQueue SA is a virtual healthcare queue platform developed to improve access to healthcare in South Africa. It is currently a student prototype system. For clinical emergencies, please contact emergency services at <strong>10177</strong> or <strong>112</strong>.</p>
        </div>

    </div>

    <!-- WHAT TO INCLUDE -->
    <div class="include-section">
        <h2>📋 What to Include in Your Email</h2>
        <div class="include-grid">
            <div class="include-item">
                <span class="inc-icon">👤</span>
                <div><strong>Your role:</strong><br> Let us know whether you are a patient, doctor, clinic administrator or general visitor.</div>
            </div>
            <div class="include-item">
                <span class="inc-icon">📝</span>
                <div><strong>Description of your issue:</strong><br> Describe what you were trying to do and what went wrong or what question you have.</div>
            </div>
            <div class="include-item">
                <span class="inc-icon">📸</span>
                <div><strong>Screenshots (if applicable):</strong><br> Attaching a screenshot of any error messages or unusual behaviour greatly speeds up our response.</div>
            </div>
            <div class="include-item">
                <span class="inc-icon">🌐</span>
                <div><strong>Device and browser:</strong><br> Let us know which device and browser you are using (e.g., iPhone, Chrome) so we can reproduce the issue.</div>
            </div>
            <div class="include-item">
                <span class="inc-icon">💡</span>
                <div><strong>Feature suggestions:</strong><br> We welcome ideas for improvements. Tell us what feature would make MediQueue more useful for you.</div>
            </div>
            <div class="include-item">
                <span class="inc-icon">⭐</span>
                <div><strong>General feedback:</strong><br> Your experience using MediQueue SA helps us improve. Any feedback — positive or critical — is appreciated.</div>
            </div>
        </div>
    </div>

    <!-- DISCLAIMER -->
    <div class="disclaimer">
        <span class="disc-icon">⚠️</span>
        <p>
            <strong>Important:</strong> MediQueue SA is an academic prototype and is <strong>not a registered medical service</strong>.
            Do not use email to report medical emergencies. For emergencies, please call <strong>10177 (ambulance)</strong> or
            <strong>112 (general emergency)</strong> immediately.
        </p>
    </div>

    <!-- BACK HOME -->
    <div class="back-home">
        <a href="main.php">← Back to Home</a>
    </div>

</main>

<footer>
    MediQueue SA &nbsp;•&nbsp; Virtual Healthcare Queue &nbsp;•&nbsp;
    <a href="mailto:mediqueue.org@gmail.com">mediqueue.org@gmail.com</a>
    &nbsp;•&nbsp; &copy; 2024 MediQueue SA
</footer>

<script>
function copyEmail() {
    var email = 'mediqueue.org@gmail.com';
    var btn = document.getElementById('copyBtn');
    if (navigator.clipboard) {
        navigator.clipboard.writeText(email).then(function () {
            btn.textContent = '✓ Copied!';
            btn.classList.add('copied');
            setTimeout(function () {
                btn.textContent = 'Copy';
                btn.classList.remove('copied');
            }, 2200);
        });
    } else {
        // Fallback
        var el = document.createElement('textarea');
        el.value = email;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        btn.textContent = '✓ Copied!';
        btn.classList.add('copied');
        setTimeout(function () {
            btn.textContent = 'Copy';
            btn.classList.remove('copied');
        }, 2200);
    }
}
</script>

</body>
</html>
