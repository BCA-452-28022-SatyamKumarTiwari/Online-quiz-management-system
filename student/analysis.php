<?php include 'student-header.php'; ?>

<h2>My Performance</h2>

<?php
$user_id=$_SESSION['user_id'];
$res=$conn->query("
SELECT quizzes.quiz_title, attempts.score, attempts.total_questions,attempt_date
FROM attempts
JOIN quizzes ON quizzes.id=attempts.quiz_id
WHERE attempts.user_id='$user_id'
ORDER BY attempt_date DESC
");

while($r=$res->fetch_assoc()){
echo "
<div class='card'>
<h3>{$r['quiz_title']}</h3>
<p>Score: {$r['score']} / {$r['total_questions']}</p>
<p>Date: {$r['attempt_date']}</p>
</div>
";
}
?>

<?php include 'student-footer.php'; ?>
