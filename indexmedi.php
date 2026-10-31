<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediQueue SA | Healthcare Gateway</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .role-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }
        @media (max-width: 768px) {
            .role-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <div class="top-system-strip">
        <div class="system-status-indicator">
            <span class="status-dot-pulse"></span>
            <span>SYSTEM ACTIVE &bull; PROVINCIAL HEALTHCARE GATEWAY (SOUTH AFRICA)</span>
        </div>
        <div class="top-bar-emergency">
            Emergency Dispatch: <strong>112 (Mobile)</strong> &bull; <strong>10177 (Ambulance)</strong>
        </div>
    </div>

    <header class="site-header">
        <div class="header-content-inner">
            <a href="main.php" class="brand-logo-container">
                <img src="assets/logo-transparent.png" alt="MediQueue SA Official Logo" class="official-logo-graphic" onerror="this.onerror=null; this.src='assets/logo.png';">
                <div class="brand-text-subtitle">
                    Republic of South Africa
                    <span>Virtual Healthcare Triage</span>
                </div>
            </a>
            <nav>
                <ul class="nav-links-cluster">
                    <li><a href="main.php" class="nav-item-btn">Home Overview</a></li>
                    <li><a href="admin.php" class="nav-item-btn">Admin Analytics</a></li>
                </ul>
            </nav>
            <div class="header-actions-group">
                <span class="live-pill">
                    <span class="status-dot-pulse"></span>
                    <span>SYSTEM ACTIVE</span>
                </span>
            </div>
        </div>
    </header>

    <main class="landing" style="margin-top: 40px;">
        <section class="card" style="text-align: center; padding: 40px 24px;">
            <span class="eyebrow">Enterprise Healthcare Gateway</span>
            <h1 style="font-size: 28px; font-weight: 800; color: var(--mq-primary); margin-bottom: 10px;">Select Your Institutional Access Role</h1>
            <p style="max-width: 620px; margin: 0 auto;">Connect directly to virtual queuing, automated clinical triage routing, and outpatient records.</p>
        </section>

        <section class="role-grid">
            <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; padding: 32px 28px;">
                <div>
                    <div class="avatar" style="width: 44px; height: 44px; margin-bottom: 16px;">
                        <svg class="icon" style="width: 22px; height: 22px;" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <h2>Patient Care Portal</h2>
                    <p style="margin-top: 8px;">Check primary symptoms, join facility virtual waiting lines, and receive live position updates.</p>
                </div>
                <div style="margin-top: 24px;">
                    <a href="login.php" class="btn-action-primary" style="display: flex; width: 100%; text-decoration: none; justify-content: center; padding: 12px;">
                        <span>Access Patient Portal</span>
                    </a>
                </div>
            </div>

            <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; padding: 32px 28px;">
                <div>
                    <div class="avatar" style="width: 44px; height: 44px; margin-bottom: 16px;">
                        <svg class="icon" style="width: 22px; height: 22px;" viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                    </div>
                    <h2>Clinical Practitioner Station</h2>
                    <p style="margin-top: 8px;">Authenticate with institutional credentials to review prioritized queues, consult patients, and issue electronic prescriptions.</p>
                </div>
                <div style="margin-top: 24px;">
                    <a href="doctor.php" class="btn-action-secondary" style="display: flex; width: 100%; text-decoration: none; justify-content: center; padding: 12px;">
                        <span>Access Doctor Workspace</span>
                    </a>
                </div>
            </div>
        </section>

        <section class="card" style="text-align: center; padding: 24px; margin-top: 20px;">
            <div style="font-size: 13.5px; color: var(--mq-text-muted);">
                Administrative supervisor? <a href="admin.php" style="color: var(--mq-teal); font-weight: 700;">Open Provincial Admin Analytics</a> &bull; <a href="main.php" style="color: var(--mq-text-body);">Return to Overview</a>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="footer-inner-flex">
            <div><strong>MediQueue SA</strong> &mdash; Clinical Queuing &amp; Virtual Triage Platform (South Africa)</div>
            <ul class="footer-links-row">
                <li><a href="main.php">Home</a></li>
                <li><a href="login.php">Patient Portal</a></li>
                <li><a href="doctor.php">Doctor Workspace</a></li>
                <li><a href="admin.php">Admin Analytics</a></li>
                <li><a href="mailto:mediqueue.org@gmail.com">Contact Support</a></li>
            </ul>
        </div>
    </footer>

</body>
</html>
