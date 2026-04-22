<?php
// 1. LOGIC FIRST
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once "../config/db.php";

$msg = "";
$error = "";

// Handle CSV Upload
if (isset($_POST['upload_csv'])) {
    $quiz_id = (int)$_POST['quiz_id'];
    $filename = $_FILES["csv_file"]["tmp_name"];

    if ($_FILES["csv_file"]["size"] > 0) {
        $file = fopen($filename, "r");
        
        // Skip the first line (header)
        fgetcsv($file); 

        $count = 0;
        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
            // Ensure we have at least 6 columns (Question + 4 opts + Correct)
            if (count($column) < 6) continue;

            $q_text = sanitize($column[0]);
            $a = sanitize($column[1]);
            $b = sanitize($column[2]);
            $c = sanitize($column[3]);
            $d = sanitize($column[4]);
            $correct = strtoupper(trim(sanitize($column[5])));

            $sql = "INSERT INTO questions (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option) 
                    VALUES ('$quiz_id', '$q_text', '$a', '$b', '$c', '$d', '$correct')";
            
            if ($conn->query($sql)) {
                $count++;
            }
        }
        fclose($file);
        $msg = "Successfully uploaded $count questions!";
    } else {
        $error = "Please select a valid CSV file.";
    }
}

include 'teacher-header.php';
?>

<style>
    .upload-box {
        max-width: 800px;
        margin: 20px auto;
        padding: 40px;
        background: white;
        border-radius: 24px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.03);
        text-align: center;
    }
    .csv-format-box {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 12px;
        margin-top: 30px;
        text-align: left;
        border: 1px dashed #d1d9e6;
    }
    code { background: #eef2ff; color: #4361ee; padding: 2px 6px; border-radius: 4px; font-family: monospace; }
</style>

<div class="main-content">
    <div style="margin-bottom: 40px;">
        <h2 style="font-weight: 800; color: #2b3674;">Bulk Upload Questions</h2>
        <p style="color: #a3aed0;">Import multiple Multiple Choice Questions instantly using a CSV file.</p>
    </div>

    <?php if($msg): ?>
        <div style="background: #e7fbf3; color: #00ab66; padding: 15px; border-radius: 12px; margin-bottom: 25px; border: 1px solid #00ab66;">
            <i class="fas fa-check-circle"></i> <?php echo $msg; ?>
        </div>
    <?php endif; ?>

    <div class="upload-box">
        <form method="POST" enctype="multipart/form-data">
            <div style="margin-bottom: 25px; text-align: left;">
                <label style="font-weight: 600; color: #2b3674; display: block; margin-bottom: 10px;">Select Quiz</label>
                <select name="quiz_id" required style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #e0e5f2;">
                    <option value="">-- Choose Target Quiz --</option>
                    <?php
                    $quizzes = $conn->query("SELECT id, quiz_title FROM quizzes ORDER BY id DESC");
                    while($q = $quizzes->fetch_assoc()) {
                        echo "<option value='{$q['id']}'>{$q['quiz_title']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div style="border: 2px dashed #4361ee; padding: 40px; border-radius: 20px; background: rgba(67, 97, 238, 0.02); margin-bottom: 25px;">
                <i class="fas fa-file-csv" style="font-size: 50px; color: #4361ee; margin-bottom: 15px;"></i>
                <input type="file" name="csv_file" accept=".csv" required style="display: block; margin: 0 auto;">
                <p style="margin-top: 10px; color: #a3aed0; font-size: 0.9rem;">Only .csv files are supported</p>
            </div>

            <button type="submit" name="upload_csv" class="btn btn-primary" style="width: 100%; padding: 15px;">
                🚀 Start Import Process
            </button>
        </form>

        <div class="csv-format-box">
            <h5 style="margin-bottom: 10px; color: #2b3674;"><i class="fas fa-info-circle"></i> CSV Format Guide</h5>
            <p style="font-size: 0.85rem; color: #64748b; line-height: 1.6;">
                Your CSV should have 6 columns in this exact order:<br>
                <code>Question Text</code>, <code>Option A</code>, <code>Option B</code>, <code>Option C</code>, <code>Option D</code>, <code>Correct Option (A, B, C, or D)</code>
            </p>
            <p style="font-size: 0.8rem; color: #ff5252; margin-top: 10px; font-weight: 600;">* Do not include a header row in your data.</p>
        </div>
    </div>
</div>

<?php include 'admin-footer.php'; ?>