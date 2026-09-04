<?php
/**
 * Admin Index Route
 * Enforces session verification and loads the dashboard.
 */
require_once __DIR__ . '/session_check.php';
header('Location: dashboard.php');
exit;
