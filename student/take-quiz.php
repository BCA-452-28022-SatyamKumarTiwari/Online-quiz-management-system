<?php 
include 'student-header.php'; 
// No need to require db.php again if it is already in student-header.php

// 1. Check if 'id' exists in the URL before using it
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div class='error-msg' style='color: red; padding: 20px;'>Error: No quiz selected. Please go back to the dashboard.</div>";
    include 'student-footer.php';
    exit();
}

$quiz_id = (int)$_GET['id'];

// 2. Fetch questions for this specific quiz
$questions = $conn->query("SELECT * FROM questions WHERE quiz_id='$quiz_id'");

// 3. Optional: Check if the quiz actually has questions
if ($questions->num_rows == 0) {
    echo "<div class='info-msg' style='padding: 20px;'>This quiz doesn't have any questions yet.</div>";
    include 'student-footer.php';
    exit();
}
?>

<div class="container">
    <h2>Take Quiz</h2>

    <form action="submit-quiz.php" method="POST">
        <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">

        <?php
        $i = 1;
        while($q = $questions->fetch_assoc()){
            echo "<div class='card' style='margin-bottom: 20px; border: 1px solid #ddd; padding: 15px; border-radius: 8px;'>
                <p><b>Q$i:</b> " . htmlspecialchars($q['question_text']) . "</p>

                <label><input type='radio' name='ans[{$q['id']}]' value='A' required> " . htmlspecialchars($q['option_a']) . "</label><br>
                <label><input type='radio' name='ans[{$q['id']}]' value='B'> " . htmlspecialchars($q['option_b']) . "</label><br>
                <label><input type='radio' name='ans[{$q['id']}]' value='C'> " . htmlspecialchars($q['option_c']) . "</label><br>
                <label><input type='radio' name='ans[{$q['id']}]' value='D'> " . htmlspecialchars($q['option_d']) . "</label>
            </div>";
            $i++;
        }
        ?>

        <button type="submit" class="btn" style="padding: 10px 20px; cursor: pointer;">Submit Quiz</button>
    </form>
</div>

<?php include 'student-footer.php'; ?>
<?php
// Fetch quiz details including duration (in minutes)
$quiz_res = $conn->query("SELECT * FROM quizzes WHERE id = $quiz_id");
$quiz_data = $quiz_res->fetch_assoc();
$duration_minutes = $quiz_data['duration'];
?>

<div id="quiz-timer-container" style="
    position: fixed; 
    top: 20px; 
    right: 150px; 
    background: rgba(255, 255, 255, 0.1); 
    backdrop-filter: blur(10px); 
    padding: 10px 20px; 
    border-radius: 12px; 
    border: 1px solid rgba(255,255,255,0.2); 
    color: white; 
    z-index: 9999;
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 700;
">
    <i class="fas fa-clock" id="timer-icon"></i>
    <span id="timer-display">00:00</span>
</div>
<script>
    // Initialize time from PHP
    let totalSeconds = <?php echo $duration_minutes; ?> * 60;
    const timerDisplay = document.getElementById('timer-display');
    const timerIcon = document.getElementById('timer-icon');

    function updateTimer() {
        let minutes = Math.floor(totalSeconds / 60);
        let seconds = totalSeconds % 60;

        // Formatting with leading zeros
        minutes = minutes < 10 ? '0' + minutes : minutes;
        seconds = seconds < 10 ? '0' + seconds : seconds;

        timerDisplay.innerHTML = `${minutes}:${seconds}`;

        // Visual warning when under 1 minute
        if (totalSeconds <= 60) {
            document.getElementById('quiz-timer-container').style.color = '#ff5252';
            timerIcon.classList.add('fa-beat');
        }

        if (totalSeconds <= 0) {
            clearInterval(timerInterval);
            alert("Time's up! Your quiz will be submitted automatically.");
            document.getElementById('quiz-form').submit(); // Ensure your form has id="quiz-form"
        }
        
        totalSeconds--;
    }

    // Start timer immediately
    const timerInterval = setInterval(updateTimer, 1000);
</script>