<?php include 'admin-header.php'; ?>

<div style="display: flex; justify-content: space-between; align-items: center;">
    <h2>All Student Results</h2>
    <a href="export-results.php" class="btn btn-primary" style="width: auto;">Download CSV Report</a>
</div>
<hr>

<table class="table">
    <tr>
        <th>Student Name</th>
        <th>Quiz Title</th>
        <th>Score</th>
        <th>Date</th>
    </tr>
    <?php
    $res = $conn->query("SELECT u.full_name, q.quiz_title, a.score, a.total_questions, a.attempt_date 
                         FROM attempts a 
                         JOIN users u ON a.user_id = u.id 
                         JOIN quizzes q ON a.quiz_id = q.id 
                         ORDER BY a.attempt_date DESC");

    while($row = $res->fetch_assoc()) {
        echo "<tr>
                <td>{$row['full_name']}</td>
                <td>{$row['quiz_title']}</td>
                <td>{$row['score']} / {$row['total_questions']}</td>
                <td>{$row['attempt_date']}</td>
              </tr>";
    }
    ?>
    
</table>
</body></html>