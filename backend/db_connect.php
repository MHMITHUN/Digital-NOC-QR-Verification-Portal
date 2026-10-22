<?php
/**
 * Database Connection File
 * BSREA Digital NOC & Certificate Verification Portal
 * Author: MD. Mahamudul Hasan
 */

header('Content-Type: application/json; charset=UTF-8');

$host     = 'localhost';
$db_name  = 'bsrea_digital_portal';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host={$host};dbname={$db_name};charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    // If DB connection fails, return formatted JSON error
    echo json_encode([
        'status' => 'error',
        'message' => 'Database connection failed: ' . $e->getMessage()
    ]);
    exit;
}
