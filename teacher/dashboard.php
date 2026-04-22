<?php
session_start();
require_once '../config/db.php';

/* ---------- ROLE SECURITY CHECK FIRST ---------- */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../auth/login.php");
    exit();
}

/* ---------- AFTER SECURITY LOAD HEADER ---------- */
include 'teacher-header.php';

/* ---------- DASHBOARD DATA ---------- */
$users   = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='student'")->fetch_assoc()['count'];
$quizzes = $conn->query("SELECT COUNT(*) as count FROM quizzes")->fetch_assoc()['count'];
$attempts= $conn->query("SELECT COUNT(*) as count FROM attempts")->fetch_assoc()['count'];
?>

<h2>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?> 👋</h2>
<hr>

<div class="stat-card">
    <h3>Students</h3>
    <p style="font-size:24px;font-weight:bold;color:var(--primary);">
        <?php echo $users; ?>
    </p>
</div>

<div class="stat-card">
    <h3>Quizzes</h3>
    <p style="font-size:24px;font-weight:bold;color:var(--primary);">
        <?php echo $quizzes; ?>
    </p>
</div>

<div class="stat-card">
    <h3>Attempts</h3>
    <p style="font-size:24px;font-weight:bold;color:var(--primary);">
        <?php echo $attempts; ?>
    </p>
</div>

<div style="margin-top:40px;">
    <h3>Recent Attempts</h3>
    <table class="table">
        <tr>
            <th>Student</th>
            <th>Quiz</th>
            <th>Score</th>
            <th>Date</th>
        </tr>

        <?php
        $res = $conn->query("
            SELECT users.full_name, quizzes.quiz_title, attempts.score, attempts.attempt_date 
            FROM attempts 
            JOIN users ON attempts.user_id = users.id 
            JOIN quizzes ON attempts.quiz_id = quizzes.id 
            ORDER BY attempts.attempt_date DESC 
            LIMIT 5
        ");

        while ($row = $res->fetch_assoc()) {
            echo "<tr>
                    <td>".htmlspecialchars($row['full_name'])."</td>
                    <td>".htmlspecialchars($row['quiz_title'])."</td>
                    <td>{$row['score']}</td>
                    <td>{$row['attempt_date']}</td>
                  </tr>";
        }
        ?>
    </table>
</div>

</body>
</html>
