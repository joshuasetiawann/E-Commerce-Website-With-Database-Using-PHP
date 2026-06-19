<?php
/**
 * Database connection.
 *
 * Update these credentials to match your environment. For real deployments,
 * prefer loading them from environment variables instead of hard-coding them.
 */

$DB_HOST = getenv('DB_HOST') ?: 'localhost';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') ?: '';
$DB_NAME = getenv('DB_NAME') ?: 'company_shop';

// Keep the classic procedural error handling (return values) instead of
// throwing exceptions, so the connection check below behaves predictably.
mysqli_report(MYSQLI_REPORT_OFF);

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}
$conn->set_charset('utf8mb4');

/**
 * Escape a value for safe output inside HTML (prevents XSS).
 */
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
