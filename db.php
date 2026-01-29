<?php
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Load .env variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Get Credentials
$host = $_ENV['DB_HOST'] ?? '127.0.0.1';
$db   = $_ENV['DB_NAME'] ?? 'defaultdb';
$user = $_ENV['DB_USER'] ?? 'root';
$pass = $_ENV['DB_PASS'] ?? '';
$port = $_ENV['DB_PORT'] ?? '3306';

// 1. Define the Connection String (DSN)
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

// 2. Define Options
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
    // Fix for PHP 8.5+ Deprecation Warning (SSL Verify)
    1014 => false, 
];

// 3. Connect
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Clean error message for production
    error_log($e->getMessage()); // Log the real error to server logs
    die("System Error: Database connection could not be established. Please check your configuration.");
}