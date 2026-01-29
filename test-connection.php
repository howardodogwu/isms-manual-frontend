<?php
/**
 * Quick test to verify database connection
 */

require 'vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$host = $_ENV['DB_HOST'] ?? '127.0.0.1';
$dbname = $_ENV['DB_NAME'] ?? 'isms_builder';
$user = $_ENV['DB_USER'] ?? 'root';
$pass = $_ENV['DB_PASS'] ?? '';
$port = $_ENV['DB_PORT'] ?? '3306';

echo "Testing database connection...\n";
echo "Host: $host\n";
echo "Database: $dbname\n";
echo "User: $user\n\n";

try {
    $pdo = new PDO("mysql:host=$host;port=$port;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Successfully connected to MySQL server!\n";
    
    // Try to use database
    $pdo->exec("USE `$dbname`");
    echo "✅ Database '$dbname' is accessible!\n";
    
    // Check tables
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    if (empty($tables)) {
        echo "⚠️  No tables found. Run 'php setup.php' to create them.\n";
    } else {
        echo "✅ Found tables: " . implode(', ', $tables) . "\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage() . "\n";
    echo "\nTroubleshooting:\n";
    echo "1. Is MySQL running? Try: brew services start mysql\n";
    echo "2. Check your .env file credentials\n";
    echo "3. Create database manually: mysql -u root -p -e 'CREATE DATABASE $dbname;'\n";
    exit(1);
}
