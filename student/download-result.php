<?php
include "../includes/session.php";
include "../config/db.php";

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=result.csv");

$user_id=$_SESSION['user_id'];

$res=$conn->query("
SELECT quizzes.quiz_title,score,total_questions,attempt_date
FROM attempts
JOIN quizzes ON quizzes.id=attempts.quiz_id
WHERE user_id='$user_id'
");

echo "Quiz,Score,Total,Date\n";

while($r=$res->fetch_assoc()){
echo "{$r['quiz_title']},{$r['score']},{$r['total_questions']},{$r['attempt_date']}\n";
}
?>
