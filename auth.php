<?php
/**
 * Admin authentication guard.
 *
 * Include this at the very top of every admin-only page. Visitors who are not
 * logged in are redirected to the login screen.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['login'])) {
    header('Location: login.php');
    exit;
}
