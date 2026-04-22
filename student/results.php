<?php include 'student-header.php'; ?>

<h2>My Performance History</h2>
<hr>

<table class="table">
    <tr>
        <th>Quiz Title</th>
        <th>Score</th>
        <th>Percentage</th>
        <th>Date</th>
    </tr>
    <?php
    $user_id = $_SESSION['user_id'];
    $res = $conn->query("SELECT q.quiz_title, a.score, a.total_questions, a.attempt_date 
                         FROM attempts a 
                         JOIN quizzes q ON a.quiz_id = q.id 
                         WHERE a.user_id = $user_id 
                         ORDER BY a.attempt_date DESC");

    while($row = $res->fetch_assoc()) {
        $percent = ($row['score'] / $row['total_questions']) * 100;
        echo "<tr>
                <td>{$row['quiz_title']}</td>
                <td>{$row['score']} / {$row['total_questions']}</td>
                <td>" . round($percent, 2) . "%</td>
                <td>{$row['attempt_date']}</td>
              </tr>";
    }
    ?>
</table>

</div></body></html>