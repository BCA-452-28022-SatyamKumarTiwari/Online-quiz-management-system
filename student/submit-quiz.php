<?php
include "../includes/session.php";
include "../config/db.php";

$user_id = $_SESSION['user_id'];
$quiz_id = $_POST['quiz_id'];
$answers = $_POST['ans'];

$score=0;
$total=0;

foreach($answers as $qid=>$ans){
$q=$conn->query("SELECT correct_option FROM questions WHERE id='$qid'")->fetch_assoc();

if($ans==$q['correct_option']) $score++;
$total++;
}

$conn->query("INSERT INTO attempts(user_id,quiz_id,score,total_questions,attempt_date)
VALUES('$user_id','$quiz_id','$score','$total',NOW())");

header("Location: analysis.php");
?>
