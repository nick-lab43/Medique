<?php
require_once __DIR__ . '/auth_common.php';
if (current_patient()) auth_redirect('patient.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $error = 'Enter a valid email address and password.';
    } else {
        $stmt = $pdo->prepare("SELECT id, email, password_hash, role FROM users WHERE email = ? AND role = 'patient' LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['role'] = 'patient';
            auth_redirect('patient.php');
        }
        $error = 'Incorrect email or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediQueue SA | Patient Portal Authentication</title>
    <link rel="stylesheet" href="style.css">
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
                    <li><a href="main.php" class="nav-item-btn">Home</a></li>
                    <li><a href="doctor.php" class="nav-item-btn">Doctor Workspace</a></li>
                    <li><a href="admin.php" class="nav-item-btn">Admin Analytics</a></li>
                </ul>
            </nav>
            <div class="header-actions-group">
                <a href="register.php" class="btn-header-doctor">Register Account</a>
            </div>
        </div>
    </header>

    <main class="landing">
        <section class="card auth-card">
            <span class="eyebrow">Patient Identity Gateway</span>
            <h2>Patient Sign-In</h2>
            <p>Access your live virtual ticket, consultation history, prescriptions, and referral choices.</p>

            <?php if ($error !== ''): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="post" autocomplete="on" style="margin-top: 18px;">
                <div>
                    <label for="inputEmail">National Email Address</label>
                    <input type="email" id="inputEmail" name="email" autocomplete="email" placeholder="patient@domain.co.za" required autofocus>
                </div>

                <div>
                    <label for="inputPassword">Account Password</label>
                    <input type="password" id="inputPassword" name="password" autocomplete="current-password" placeholder="••••••••" required minlength="6">
                </div>

                <div style="margin-top: 20px;">
                    <button class="primary" type="submit" style="width: 100%;">
                        <svg class="icon" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <span>Sign In to Patient Portal</span>
                    </button>
                </div>
            </form>

            <div class="auth-links">
                <div>Don’t have an account? <a href="register.php">Register for free</a></div>
                <div style="margin-top: 6px;"><a href="main.php">Return to Main Overview</a></div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="footer-inner-flex">
            <div><strong>MediQueue SA</strong> &mdash; Clinical Queuing &amp; Virtual Triage Platform (South Africa)</div>
            <ul class="footer-links-row">
                <li><a href="main.php">Home</a></li>
                <li><a href="doctor.php">Doctor Workspace</a></li>
                <li><a href="admin.php">Admin Analytics</a></li>
                <li><a href="mailto:mediqueue.org@gmail.com">Contact Support</a></li>
            </ul>
        </div>
    </footer>

</body>
</html>
