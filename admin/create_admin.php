<?php
/**
 * Admin Creation & Password Hash Utility
 * 
 * Utility to register or reset an administrator account using PHP's native password_hash().
 * Run via browser or CLI during initial project setup.
 */

require_once __DIR__ . '/db.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $message = 'Please provide both username and password.';
        $messageType = 'error';
    } elseif (strlen($password) < 6) {
        $message = 'Password must be at least 6 characters long.';
        $messageType = 'error';
    } else {
        try {
            $pdo = getDBConnection();

            // Hash password using current best practice algorithm (bcrypt / argon2)
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Check if user already exists
            $stmt = $pdo->prepare('SELECT id FROM admin_users WHERE username = :username LIMIT 1');
            $stmt->execute([':username' => $username]);
            $existing = $stmt->fetch();

            if ($existing) {
                // Update existing user's password
                $update = $pdo->prepare('UPDATE admin_users SET password_hash = :hash WHERE id = :id');
                $update->execute([':hash' => $passwordHash, ':id' => $existing['id']]);
                $message = "Admin user '{$username}' password has been successfully updated with password_hash()!";
                $messageType = 'success';
            } else {
                // Insert new admin user
                $insert = $pdo->prepare('INSERT INTO admin_users (username, password_hash, created_at) VALUES (:username, :hash, NOW())');
                $insert->execute([':username' => $username, ':hash' => $passwordHash]);
                $message = "Admin user '{$username}' created successfully! You may now sign in.";
                $messageType = 'success';
            }
        } catch (Exception $e) {
            $message = 'Database error: ' . $e->getMessage();
            $messageType = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Setup Admin Account &bull; Bluoilz</title>
  <style>
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: #faf6f2;
      color: #231c1e;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 20px;
    }
    .card {
      background: #ffffff;
      padding: 32px;
      border-radius: 14px;
      box-shadow: 0 8px 30px rgba(0,0,0,0.06);
      max-width: 440px;
      width: 100%;
      border: 1px solid #ede4de;
    }
    h2 { margin-bottom: 8px; font-size: 1.4rem; }
    p.desc { font-size: 0.88rem; color: #6e6065; margin-bottom: 20px; }
    .alert { padding: 12px; border-radius: 8px; margin-bottom: 18px; font-size: 0.88rem; }
    .alert-error { background: #fdf2f2; color: #991b1b; }
    .alert-success { background: #f0fdf4; color: #166534; }
    .form-group { margin-bottom: 16px; }
    label { display: block; font-size: 0.82rem; font-weight: 600; margin-bottom: 6px; }
    input { width: 100%; padding: 11px; border: 1px solid #ede4de; border-radius: 8px; font-size: 0.95rem; }
    input:focus { outline: none; border-color: #b85d6b; }
    button { width: 100%; padding: 12px; background: #1f191b; color: #ffffff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; }
    button:hover { background: #b85d6b; }
    .links { margin-top: 20px; text-align: center; font-size: 0.85rem; }
    .links a { color: #b85d6b; font-weight: 600; text-decoration: none; }
  </style>
</head>
<body>
  <div class="card">
    <h2>Admin Account Setup</h2>
    <p class="desc">Uses PHP's native <code>password_hash()</code> to securely insert or update an admin credential.</p>

    <?php if ($message): ?>
      <div class="alert alert-<?= $messageType ?>">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label for="username">Admin Username</label>
        <input type="text" id="username" name="username" value="admin" required>
      </div>

      <div class="form-group">
        <label for="password">Password to Hash & Save</label>
        <input type="password" id="password" name="password" placeholder="Enter secure password" required>
      </div>

      <button type="submit">Create / Update Admin</button>
    </form>

    <div class="links">
      <a href="login.php">&larr; Go to Admin Login Page</a>
    </div>
  </div>
</body>
</html>
