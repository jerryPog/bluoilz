<?php
/**
 * Admin Session Validation Check
 * 
 * Must be included at the top of every protected admin page.
 * If no valid session exists, immediately redirects to login.php.
 */

if (session_status() === PHP_SESSION_NONE) {
    // Enforce secure cookie parameters when applicable
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params([
        'lifetime' => $cookieParams['lifetime'],
        'path'     => '/',
        'domain'   => $cookieParams['domain'],
        'secure'   => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

// Verify that the session contains valid admin credentials
if (empty($_SESSION['admin_id']) || empty($_SESSION['admin_username'])) {
    // Preserve intended URL for post-login redirect if needed
    $currentPage = basename($_SERVER['PHP_SELF']);
    if ($currentPage !== 'login.php' && $currentPage !== 'logout.php') {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    }

    header('Location: login.php');
    exit;
}
