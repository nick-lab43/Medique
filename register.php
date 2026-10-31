<?php
require_once __DIR__ . '/auth_common.php';
if (current_patient()) auth_redirect('patient.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $dob = trim($_POST['date_of_birth'] ?? '');
    $contact = trim($_POST['contact_number'] ?? '');

    if ($fullName === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
        $error = 'Enter your full name, a valid email and a password of at least 6 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        try {
            $pdo->beginTransaction();
            $check = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
            $check->execute([$email]);
            if ($check->fetch()) throw new RuntimeException('An account with that email already exists. Please log in.');

            $stmt = $pdo->prepare("INSERT INTO users (email, password_hash, role) VALUES (?, ?, 'patient')");
            $stmt->execute([$email, password_hash($password, PASSWORD_DEFAULT)]);
            $userId = (int)$pdo->lastInsertId();

            $stmt = $pdo->prepare('INSERT INTO patients (user_id, full_name, date_of_birth, contact_number) VALUES (?, ?, ?, ?)');
            $stmt->execute([$userId, $fullName, ($dob !== '' ? $dob : null), ($contact !== '' ? $contact : null)]);
            $pdo->commit();

            session_regenerate_id(true);
            $_SESSION['user_id'] = $userId;
            $_SESSION['role'] = 'patient';
            auth_redirect('patient.php');
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $error = $e instanceof RuntimeException ? $e->getMessage() : 'Registration could not be completed. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediQueue SA | Patient Registration</title>
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
                <a href="login.php" class="btn-header-doctor">Sign In</a>
            </div>
        </div>
    </header>

    <main class="landing">
        <section class="card auth-card" style="max-width: 540px;">
            <span class="eyebrow">Patient Identity Gateway</span>
            <h2>Create Patient Account</h2>
            <p>Register your national patient profile to maintain consultation history, automated queue updates, and digital prescriptions.</p>

            <?php if ($error !== ''): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="post" autocomplete="on" style="margin-top: 18px;">
                <div>
                    <label for="inputFullName">Full Legal Name</label>
                    <input type="text" id="inputFullName" name="full_name" autocomplete="name" placeholder="First and last names" required autofocus>
                </div>

                <div>
                    <label for="inputEmail">Email Address</label>
                    <input type="email" id="inputEmail" name="email" autocomplete="email" placeholder="patient@domain.co.za" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label for="inputContact">Contact Mobile Number</label>
                        <input type="tel" id="inputContact" name="contact_number" autocomplete="tel" placeholder="082 123 4567">
                    </div>
                    <div>
                        <label for="inputDob">Date of Birth</label>
                        <input type="date" id="inputDob" name="date_of_birth" autocomplete="bday">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label for="inputPassword">Account Password</label>
                        <input type="password" id="inputPassword" name="password" autocomplete="new-password" placeholder="Min. 6 chars" required minlength="6">
                    </div>
                    <div>
                        <label for="inputConfirmPassword">Confirm Password</label>
                        <input type="password" id="inputConfirmPassword" name="confirm_password" autocomplete="new-password" placeholder="Repeat password" required minlength="6">
                    </div>
                </div>

                <div style="margin-top: 22px;">
                    <button class="primary" type="submit" style="width: 100%;">
                        <svg class="icon" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                        <span>Complete Patient Registration</span>
                    </button>
                </div>
            </form>

            <div class="auth-links">
                <div>Already have an account? <a href="login.php">Log in here</a></div>
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
