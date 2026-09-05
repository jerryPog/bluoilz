<?php
/**
 * Admin Login Page
 * 
 * Secure authentication using password_verify() and PDO prepared statements.
 * Stores admin ID and username in session upon successful verification.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect already authenticated admins to dashboard
if (!empty($_SESSION['admin_id']) && !empty($_SESSION['admin_username'])) {
    header('Location: dashboard.php');
    exit;
}

require_once __DIR__ . '/db.php';

$error = '';
$success = '';

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Check for logout message
if (isset($_GET['msg']) && $_GET['msg'] === 'logged_out') {
    $success = 'You have been safely logged out.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $csrfToken = $_POST['csrf_token'] ?? '';

    // Verify CSRF token
    if (!hash_equals($_SESSION['csrf_token'], $csrfToken)) {
        $error = 'Security validation failed (invalid CSRF token). Please try again.';
    } elseif (empty($username) || empty($password)) {
        $error = 'Please provide both your username and password.';
    } elseif (mb_strlen($username) < 2 || mb_strlen($username) > 100) {
        $error = 'Username must be between 2 and 100 characters.';
    } elseif (strlen($password) > 255) {
        $error = 'Password exceeds maximum permitted length.';
    } else {
        try {
            $pdo = getDBConnection();

            // Fetch user by username using prepared statement
            $stmt = $pdo->prepare('SELECT id, username, password_hash FROM admin_users WHERE username = :username LIMIT 1');
            $stmt->execute([':username' => $username]);
            $admin = $stmt->fetch();

            // Verify password hash - strictly no plaintext comparison
            if ($admin && password_verify($password, $admin['password_hash'])) {
                // Prevent session fixation attack
                session_regenerate_id(true);

                // Store verified credentials in session
                $_SESSION['admin_id'] = (int)$admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_logged_at'] = time();

                // Check if password hash algorithm needs an update
                if (password_needs_rehash($admin['password_hash'], PASSWORD_DEFAULT)) {
                    $newHash = password_hash($password, PASSWORD_DEFAULT);
                    $updateStmt = $pdo->prepare('UPDATE admin_users SET password_hash = :hash WHERE id = :id');
                    $updateStmt->execute([':hash' => $newHash, ':id' => $admin['id']]);
                }

                // Determine redirect target
                $redirectUrl = 'dashboard.php';
                if (!empty($_SESSION['redirect_after_login'])) {
                    $redirectUrl = $_SESSION['redirect_after_login'];
                    unset($_SESSION['redirect_after_login']);
                }

                header('Location: ' . $redirectUrl);
                exit;
            } else {
                // Generic error prevents username enumeration
                $error = 'Invalid username or password.';
            }
        } catch (Exception $e) {
            error_log('Login Error: ' . $e->getMessage());
            $error = 'An unexpected database error occurred. Please check database configuration.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="color-scheme" content="light">
  <title>Admin Portal Login &bull; Bluoilz</title>
  <meta name="robots" content="noindex, nofollow">
  
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    :root,
    html,
    body {
      color-scheme: light;
    }

    :root {
      color-scheme: light;
      --color-canvas: #faf6f2;
      --color-primary: #1f191b;
      --color-accent: #b85d6b;
      --color-accent-hover: #a34e5b;
      --color-text-main: #231c1e;
      --color-text-muted: #6e6065;
      --color-border: #ede4de;
      --font-serif: 'Cormorant Garamond', Georgia, serif;
      --font-sans: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    input,
    button {
      color-scheme: light;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: var(--font-sans);
      background-color: var(--color-canvas);
      color: var(--color-text-main);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 24px;
      position: relative;
    }

    /* Subtle background texture */
    body::before {
      content: '';
      position: absolute;
      inset: 0;
      background: radial-gradient(circle at center 25%, rgba(184, 93, 107, 0.08) 0%, transparent 60%);
      pointer-events: none;
    }

    .login-container {
      width: 100%;
      max-width: 440px;
      position: relative;
      z-index: 2;
    }

    .brand-header {
      text-align: center;
      margin-bottom: 28px;
    }

    .brand-logo {
      font-family: var(--font-serif);
      font-size: 2.4rem;
      font-weight: 700;
      color: var(--color-primary);
      text-transform: uppercase;
      letter-spacing: 0.12em;
      line-height: 1;
      text-decoration: none;
    }

    .brand-sub {
      display: block;
      font-size: 0.72rem;
      text-transform: uppercase;
      letter-spacing: 0.18em;
      color: var(--color-accent);
      font-weight: 700;
      margin-top: 6px;
    }

    .login-card {
      background: #ffffff;
      border: 1px solid var(--color-border);
      border-radius: 18px;
      padding: 38px 32px;
      box-shadow: 0 16px 40px rgba(31, 25, 27, 0.06);
    }

    .login-card h1 {
      font-family: var(--font-serif);
      font-size: 1.8rem;
      color: var(--color-primary);
      margin-bottom: 6px;
      font-weight: 600;
    }

    .login-card p.subtitle {
      font-size: 0.88rem;
      color: var(--color-text-muted);
      margin-bottom: 24px;
    }

    .alert {
      padding: 12px 16px;
      border-radius: 10px;
      font-size: 0.86rem;
      line-height: 1.5;
      margin-bottom: 20px;
    }

    .alert-error {
      background-color: #fdf2f2;
      color: #b91c1c;
      border: 1px solid #fecaca;
    }

    .alert-success {
      background-color: #f0fdf4;
      color: #15803d;
      border: 1px solid #bbf7d0;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      font-size: 0.82rem;
      font-weight: 600;
      color: var(--color-primary);
      text-transform: uppercase;
      letter-spacing: 0.05em;
      margin-bottom: 8px;
    }

    .form-control {
      width: 100%;
      padding: 13px 16px;
      border: 1.5px solid var(--color-border);
      border-radius: 10px;
      font-size: 0.95rem;
      font-family: inherit;
      color: var(--color-text-main);
      background-color: #faf8f5;
      outline: none;
      transition: all 0.2s ease;
    }

    .form-control:focus {
      background-color: #ffffff;
      border-color: var(--color-accent);
      box-shadow: 0 0 0 4px rgba(184, 93, 107, 0.12);
    }

    .btn-submit {
      width: 100%;
      padding: 14px;
      background: var(--color-primary);
      color: #ffffff;
      border: none;
      border-radius: 10px;
      font-size: 0.95rem;
      font-weight: 600;
      font-family: inherit;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: all 0.25s ease;
      margin-top: 6px;
    }

    .btn-submit:hover {
      background: var(--color-accent);
      transform: translateY(-1px);
      box-shadow: 0 6px 18px rgba(184, 93, 107, 0.25);
    }

    .btn-submit:active {
      transform: scale(0.99);
    }

    .login-footer {
      text-align: center;
      margin-top: 24px;
      font-size: 0.82rem;
      color: var(--color-text-muted);
    }

    .login-footer a {
      color: var(--color-primary);
      text-decoration: none;
      font-weight: 600;
      transition: color 0.2s;
    }

    .login-footer a:hover {
      color: var(--color-accent);
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="login-container">
    <div class="brand-header">
      <a href="../index.html" class="brand-logo">Bluoilz</a>
      <span class="brand-sub">Therapeutic Ancient Alchemy</span>
    </div>

    <div class="login-card">
      <h1>Admin Authentication</h1>
      <p class="subtitle">Enter your verified credentials to access the management portal.</p>

      <?php if (!empty($error)): ?>
        <div class="alert alert-error" role="alert">
          <strong>Error:</strong> <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($success)): ?>
        <div class="alert alert-success" role="status">
          <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="login.php" autocomplete="off">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

        <div class="form-group">
          <label for="username">Username</label>
          <input 
            type="text" 
            id="username" 
            name="username" 
            class="form-control" 
            value="<?= htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
            required 
            minlength="2"
            maxlength="100"
            autofocus 
            autocomplete="username"
            placeholder="e.g. admin"
          >
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input 
            type="password" 
            id="password" 
            name="password" 
            class="form-control" 
            required 
            minlength="1"
            maxlength="255"
            autocomplete="current-password"
            placeholder="••••••••••••"
          >
        </div>

        <button type="submit" class="btn-submit">
          <span>Secure Sign In</span>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
          </svg>
        </button>
      </form>
    </div>

    <div class="login-footer">
      <p>&copy; <?= date('Y') ?> Bluoilz. Protected Administrative Area.</p>
      <p style="margin-top: 6px;"><a href="../index.html">&larr; Return to Storefront</a></p>
    </div>
  </div>

</body>
</html>
