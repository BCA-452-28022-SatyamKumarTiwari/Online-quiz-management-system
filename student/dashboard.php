<?php include 'student-header.php'; ?>

<h2>Dashboard</h2>

<div class="card">
<h3>Available Quizzes</h3>

<?php
$q = $conn->query("SELECT * FROM quizzes WHERE is_active=1");

while($row=$q->fetch_assoc()){
echo "
<div class='card'>
<h4>{$row['quiz_title']}</h4>
<p>Duration: {$row['duration']} mins</p>
<a class='btn' href='take-quiz.php?id={$row['id']}'>Start Quiz</a>
</div>
";
}
?>
</div>

<?php include 'student-footer.php'; ?>
