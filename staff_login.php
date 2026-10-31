<?php
require_once __DIR__ . '/auth_common.php';
if (!empty($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'doctor') auth_redirect('doctor.php');
if (!empty($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'admin') auth_redirect('admin.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $error = 'Enter a valid email address and password.';
    } else {
        $stmt = $pdo->prepare("SELECT id, email, password_hash, role FROM users WHERE email = ? AND role IN ('doctor', 'admin') LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password_hash'])) {
            if ($user['role'] === 'doctor') {
                $doctorStmt = $pdo->prepare('SELECT id FROM doctors WHERE user_id = ? LIMIT 1');
                $doctorStmt->execute([(int)$user['id']]);
                if (!$doctorStmt->fetch()) $error = 'This doctor account is not configured.';
            }
            if ($error === '') {
                session_regenerate_id(true);
                $_SESSION['user_id'] = (int)$user['id'];
                $_SESSION['role'] = $user['role'];
                auth_redirect($user['role'] === 'doctor' ? 'doctor.php' : 'admin.php');
            }
        } else {
            $error = 'Incorrect staff email or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>MediQueue | Staff Sign-In</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<main class="landing"><section class="card auth-card">
<span class="eyebrow">Restricted access</span><h2>Staff Sign-In</h2>
<p>Only registered doctor and administrator accounts can access staff workspaces.</p>
<?php if ($error !== ''): ?><div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
<form method="post" autocomplete="on">
<label for="email">Staff email</label><input id="email" name="email" type="email" autocomplete="username" required>
<label for="password">Password</label><input id="password" name="password" type="password" autocomplete="current-password" required>
<button class="primary" type="submit">Sign in</button>
</form>
<div class="auth-links"><a href="logout.php">Sign out</a> · <a href="main.php">Return home</a></div>
</section></main>
</body>
</html>
