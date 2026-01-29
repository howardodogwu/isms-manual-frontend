<?php
session_start();
require '../vendor/autoload.php';
require '../db.php';

use Dompdf\Dompdf;

$userId = $_SESSION['user_id'];
$clauses = include('../config/clauses.php');

// 1. Fetch Answers
$stmt = $pdo->prepare("SELECT ma.question_key, ma.answer_text FROM manual_answers ma 
                       JOIN manuals m ON ma.manual_id = m.id 
                       WHERE m.user_identifier = ?");
$stmt->execute([$userId]);
$answers = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

// 2. Build HTML
$html = "
    <html>
    <head>
        <style>
            body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.6; }
            h1 { color: #5521B5; border-bottom: 2px solid #5521B5; padding-bottom: 10px; }
            h3 { margin-top: 30px; color: #222; background-color: #f3f3f3; padding: 10px; }
            h4 { color: #5521B5; margin-top: 20px; }
            p { margin-bottom: 15px; }
            .cover { text-align: center; margin-top: 200px; }
            .page-break { page-break-after: always; }
        </style>
    </head>
    <body>
        <div class='cover'>
            <h1>ISMS Manual</h1>
            <h2>ISO 27001:2022</h2>
            <p>Prepared for: <strong>" . ($answers['company_name'] ?? 'The Organization') . "</strong></p>
            <p>Date: " . date("Y-m-d") . "</p>
        </div>
        <div class='page-break'></div>
";

foreach ($clauses as $clause) {
    $text = $clause['template'];
    
    // Replace variables
    foreach ($answers as $key => $val) {
        // Decode HTML entities so they look normal in PDF
        $val = htmlspecialchars_decode($val);
        $text = str_replace("{{" . $key . "}}", $val, $text);
    }
    
    // Clean up unfilled variables with a red placeholders
    $text = preg_replace('/\{\{.*?\}\}/', '<span style="color:red;">[NOT DEFINED]</span>', $text);
    
    $html .= $text;
}

$html .= "</body></html>";

// 3. Render PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// 4. Output
$dompdf->stream("PurpleWasp_ISMS_Manual.pdf", ["Attachment" => true]);