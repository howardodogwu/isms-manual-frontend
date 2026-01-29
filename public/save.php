<?php
session_start();
require '../db.php';
$clauses = include('../config/clauses.php');

$userId = $_SESSION['user_id'];
$currentStep = $_POST['current_step'];
$nextStep = $_POST['next_step'];

// 1. Validation Logic
$errors = [];
$config = $clauses[$currentStep];

foreach ($config['questions'] as $q) {
    $key = $q['key'];
    $val = trim($_POST[$key] ?? '');
    
    // Check Required
    if (!empty($q['required']) && empty($val)) {
        $errors[] = "The field '{$q['label']}' is required.";
    }

    // Check Min Length (Prevent lazy answers)
    if (!empty($q['min_length']) && strlen($val) < $q['min_length']) {
        $errors[] = "The field '{$q['label']}' is too short. Please provide more detail (Min {$q['min_length']} chars).";
    }
}

// 2. If Errors, Redirect Back
if (!empty($errors)) {
    $_SESSION['flash_errors'] = $errors;
    $_SESSION['flash_inputs'] = $_POST; // Keep their typed text
    header("Location: index.php?step=" . $currentStep);
    exit;
}

// 3. Data is Clean - Save to DB
// Sanitize inputs to prevent XSS/SQL Injection
foreach ($_POST as $key => $value) {
    if ($key !== 'current_step' && $key !== 'next_step') {
        
        $cleanValue = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

        // Get or Create Manual ID
        $stmt = $pdo->prepare("SELECT id FROM manuals WHERE user_identifier = ?");
        $stmt->execute([$userId]);
        $manualId = $stmt->fetchColumn();

        if (!$manualId) {
            $stmt = $pdo->prepare("INSERT INTO manuals (user_identifier) VALUES (?)");
            $stmt->execute([$userId]);
            $manualId = $pdo->lastInsertId();
        }

        // Upsert Answer
        $del = $pdo->prepare("DELETE FROM manual_answers WHERE manual_id = ? AND question_key = ?");
        $del->execute([$manualId, $key]);

        $ins = $pdo->prepare("INSERT INTO manual_answers (manual_id, question_key, answer_text) VALUES (?, ?, ?)");
        $ins->execute([$manualId, $key, $cleanValue]);
    }
}

// 4. Success! Move on.
header("Location: index.php?step=" . $nextStep);