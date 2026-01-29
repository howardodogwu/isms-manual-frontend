<?php
session_start();
require '../db.php';
$clauses = include('../config/clauses.php');

// User Identification
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = session_id();
}
$userId = $_SESSION['user_id'];

// Logic: Determine Steps
$stepKeys = array_keys($clauses);
$currentStepKey = $_GET['step'] ?? $stepKeys[0];
$currentStepIndex = array_search($currentStepKey, $stepKeys);
$currentConfig = $clauses[$currentStepKey] ?? null;

// Logic: Calculate Next Step
$nextStepKey = $stepKeys[$currentStepIndex + 1] ?? 'finish';
$progressPercent = round((($currentStepIndex) / count($stepKeys)) * 100);

// Logic: Fetch Saved Answers
$stmt = $pdo->prepare("SELECT ma.question_key, ma.answer_text FROM manual_answers ma 
                       JOIN manuals m ON ma.manual_id = m.id 
                       WHERE m.user_identifier = ?");
$stmt->execute([$userId]);
$existingAnswers = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
?>

<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PurpleWasp | ISMS Builder</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="h-full flex overflow-hidden">

    <!-- SIDEBAR -->
    <div class="hidden md:flex w-72 flex-col bg-slate-900 border-r border-slate-800 text-white">
        <!-- Logo Area -->
        <div class="h-16 flex items-center px-6 border-b border-slate-800">
            <div class="flex items-center gap-3">
                <img src="assets/purplewasp-logo.png" alt="PurpleWasp" class="h-10 w-auto" />
            </div>
        </div>

        <!-- Navigation Steps -->
        <div class="flex-1 overflow-y-auto no-scrollbar py-6 px-4 space-y-1">
            <p class="px-2 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Build Your ISMS</p>
            
            <?php foreach($stepKeys as $index => $key): ?>
                <?php 
                    $isActive = ($key === $currentStepKey);
                    $isPast = ($index < $currentStepIndex);
                ?>
                <a href="?step=<?= $key ?>" 
                   class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all
                   <?= $isActive ? 'bg-purple-600 text-white shadow-lg shadow-purple-900/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' ?>">
                    
                    <?php if($isActive): ?>
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full border border-purple-400 bg-purple-500 text-[10px]">
                            <span class="animate-pulse">●</span>
                        </span>
                    <?php elseif($isPast): ?>
                        <i data-lucide="check-circle-2" class="w-5 h-5 text-green-500"></i>
                    <?php else: ?>
                        <i data-lucide="circle" class="w-5 h-5 text-slate-600 group-hover:text-slate-500"></i>
                    <?php endif; ?>

                    <span class="truncate"><?= $clauses[$key]['title'] ?></span>
                </a>
            <?php endforeach; ?>

            <div class="mt-6 pt-6 border-t border-slate-800">
                <a href="download.php" class="<?= $currentStepKey === 'finish' ? 'bg-green-600 text-white' : 'text-slate-400 hover:text-white' ?> flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium">
                    <i data-lucide="download" class="w-5 h-5"></i>
                    Download Manual
                </a>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="flex-1 flex flex-col h-full bg-gray-50 overflow-hidden relative">
        
        <!-- Mobile Header -->
        <div class="md:hidden h-16 bg-slate-900 text-white flex items-center px-4 justify-between">
            <img src="assets/purplewasp-logo.png" alt="PurpleWasp" class="h-8 w-auto" />
            <span class="text-xs bg-purple-600 px-2 py-1 rounded"><?= $progressPercent ?>%</span>
        </div>

        <!-- Top Bar -->
        <div class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 shadow-sm z-10">
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <span>Dashboard</span>
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                <span class="text-gray-900 font-medium">Editor</span>
            </div>
            <div class="text-xs font-medium text-gray-400 uppercase tracking-wide">
                Auto-saved
            </div>
        </div>

        <!-- Scrollable Form Area -->
        <div class="flex-1 overflow-y-auto p-4 md:p-10">
            <div class="max-w-5xl mx-auto">
                
                <!-- Progress Bar -->
                <div class="mb-8">
                    <div class="flex justify-between text-xs font-medium text-gray-500 mb-2">
                        <span>Progress</span>
                        <span><?= $progressPercent ?>% Completed</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-600 h-2 rounded-full transition-all duration-500" style="width: <?= $progressPercent ?>%"></div>
                    </div>
                </div>

                <!-- ERROR MESSAGE BANNER -->
                <?php if(isset($_SESSION['flash_errors'])): ?>
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i data-lucide="alert-circle" class="h-5 w-5 text-red-500"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        <?php foreach($_SESSION['flash_errors'] as $err): ?>
                                            <li><?= $err ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php unset($_SESSION['flash_errors']); ?>
                <?php endif; ?>

                <?php if ($currentStepKey === 'finish'): ?>
                    <!-- FINISH SCREEN -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
                        <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="check" class="w-10 h-10"></i>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">You are Audit Ready!</h2>
                        <p class="text-gray-500 mb-8 max-w-md mx-auto">Your ISMS Manual has been generated in accordance with ISO 27001:2022 standards.</p>
                        
                        <div class="flex justify-center gap-4">
                            <a href="?step=clause_4" class="px-6 py-3 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50">Review Answers</a>
                            <a href="download.php" class="px-6 py-3 bg-purple-600 text-white rounded-lg font-medium hover:bg-purple-700 shadow-lg shadow-purple-200 flex items-center gap-2">
                                <i data-lucide="file-down" class="w-4 h-4"></i> Download PDF
                            </a>
                        </div>
                    </div>

                <?php else: ?>
                    <!-- SPLIT LAYOUT -->
                    <div class="flex flex-col lg:flex-row gap-8">
                        
                        <!-- LEFT: Form -->
                        <div class="flex-1">
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                                <div class="p-8 border-b border-gray-100">
                                    <h1 class="text-2xl font-bold text-gray-900 mb-2"><?= $currentConfig['title'] ?></h1>
                                    <p class="text-gray-500"><?= $currentConfig['intro'] ?></p>
                                </div>

                                <form action="save.php" method="POST" class="p-8 space-y-8">
                                    <input type="hidden" name="current_step" value="<?= $currentStepKey ?>">
                                    <input type="hidden" name="next_step" value="<?= $nextStepKey ?>">

                                    <?php foreach($currentConfig['questions'] as $q): ?>
                                        <div class="space-y-2">
                                            <label class="block text-sm font-semibold text-gray-700 flex justify-between">
                                                <?= $q['label'] ?>
                                                <?php if(!empty($q['required'])): ?>
                                                    <span class="text-red-400 text-xs">* Required</span>
                                                <?php endif; ?>
                                            </label>
                                            
                                            <?php 
                                                // Priority: 1. Input from Error (Session), 2. Saved DB Answer, 3. Empty
                                                $val = $_SESSION['flash_inputs'][$q['key']] ?? $existingAnswers[$q['key']] ?? ''; 
                                            ?>

                                            <?php if($q['type'] === 'textarea'): ?>
                                                <textarea name="<?= $q['key'] ?>" rows="4" 
                                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 p-3 text-gray-700 border transition-colors"
                                                    placeholder="<?= $q['placeholder'] ?>"><?= $val ?></textarea>
                                                <?php if(!empty($q['min_length'])): ?>
                                                    <p class="text-xs text-gray-400 text-right">Min <?= $q['min_length'] ?> characters</p>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <input type="text" name="<?= $q['key'] ?>" 
                                                    value="<?= $val ?>" 
                                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 p-3 text-gray-700 border transition-colors" 
                                                    placeholder="<?= $q['placeholder'] ?>">
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                    
                                    <!-- Clear Flash Inputs after displaying -->
                                    <?php unset($_SESSION['flash_inputs']); ?>

                                    <div class="pt-6 flex items-center justify-between border-t border-gray-50">
                                        <?php if($currentStepIndex > 0): ?>
                                            <a href="?step=<?= $stepKeys[$currentStepIndex - 1] ?>" class="text-sm text-gray-500 hover:text-gray-900 font-medium">&larr; Back</a>
                                        <?php else: ?>
                                            <div></div>
                                        <?php endif; ?>

                                        <button type="submit" class="bg-purple-600 text-white px-8 py-3 rounded-lg font-medium hover:bg-purple-700 shadow-lg shadow-purple-200 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                                            Save & Continue <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- RIGHT: Guidance -->
                        <div class="lg:w-80 shrink-0">
                            <div class="bg-blue-50 border border-blue-100 rounded-xl p-6 sticky top-6">
                                <div class="flex items-center gap-2 text-blue-700 font-bold mb-4">
                                    <i data-lucide="book-open" class="w-5 h-5"></i>
                                    <span>Expert Guidance</span>
                                </div>
                                <div class="text-blue-900 prose prose-sm prose-blue">
                                    <?= $currentConfig['explanation'] ?? '<p>Fill out this section to ensure compliance.</p>' ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="h-10"></div>
            </div>
        </div>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>