<?php
/**
 * Database Setup Script
 * Run this once to initialize the database tables
 */

require 'vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Get database credentials
$host = $_ENV['DB_HOST'] ?? '127.0.0.1';
$dbname = $_ENV['DB_NAME'] ?? 'isms_builder';
$user = $_ENV['DB_USER'] ?? 'root';
$pass = $_ENV['DB_PASS'] ?? '';
$port = $_ENV['DB_PORT'] ?? '3306';

echo "🚀 Setting up ISMS Builder Database...\n\n";

try {
    // Connect without database first to create it if needed
    $pdo = new PDO("mysql:host=$host;port=$port;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    echo "📦 Creating database '$dbname' if it doesn't exist...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Database ready!\n\n";
    
    // Connect to the database
    $pdo->exec("USE `$dbname`");
    
    // Create manuals table
    echo "📋 Creating 'manuals' table...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `manuals` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_identifier` VARCHAR(255) NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX `idx_user_identifier` (`user_identifier`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✅ 'manuals' table created!\n\n";
    
    // Create manual_answers table
    echo "📝 Creating 'manual_answers' table...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `manual_answers` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `manual_id` INT NOT NULL,
            `question_key` VARCHAR(255) NOT NULL,
            `answer_text` TEXT NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (`manual_id`) REFERENCES `manuals`(`id`) ON DELETE CASCADE,
            UNIQUE KEY `unique_answer` (`manual_id`, `question_key`),
            INDEX `idx_question_key` (`question_key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✅ 'manual_answers' table created!\n\n";
    
    echo "🎉 Database setup complete!\n";
    echo "\nYou can now start the development server with:\n";
    echo "  php -S localhost:8000 -t public\n\n";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\nPlease check your .env file and ensure MySQL is running.\n";
    exit(1);
}
