<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') exit();
require_once '../config/db.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=quiz_results.csv');

$output = fopen('php://output', 'w');
// Set CSV Headers
fputcsv($output, array('Student Name', 'Email', 'Quiz Title', 'Score', 'Total Questions', 'Date'));

$res = $conn->query("SELECT u.full_name, u.email, q.quiz_title, a.score, a.total_questions, a.attempt_date 
                     FROM attempts a 
                     JOIN users u ON a.user_id = u.id 
                     JOIN quizzes q ON a.quiz_id = q.id");

while($row = $res->fetch_assoc()) {
    fputcsv($output, $row);
}
fclose($output);
exit();
?>