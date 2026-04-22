<?php 
include 'admin-header.php'; 

if(!isset($_GET['quiz_id'])) {
    die("<div class='glass-card'>Quiz ID missing.</div>");
}

$quiz_id = (int)$_GET['quiz_id'];
$quiz_info = $conn->query("SELECT quiz_title FROM quizzes WHERE id = $quiz_id")->fetch_assoc();
$msg = "";

// --- 1. MANUAL ADD LOGIC ---
if(isset($_POST['add_manual'])) {
    $q_text = sanitize($_POST['question_text']);
    $a = sanitize($_POST['opt_a']);
    $b = sanitize($_POST['opt_b']);
    $c = sanitize($_POST['opt_c']);
    $d = sanitize($_POST['opt_d']);
    $correct = strtoupper(sanitize($_POST['correct']));

    $conn->query("INSERT INTO questions (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option) 
                  VALUES ('$quiz_id', '$q_text', '$a', '$b', '$c', '$d', '$correct')");
    $msg = "Question added successfully!";
}

// --- 2. CSV UPLOAD LOGIC ---
if(isset($_POST['upload_csv'])) {
    $filename = $_FILES["csv_file"]["tmp_name"];
    if($_FILES["csv_file"]["size"] > 0) {
        $file = fopen($filename, "r");
        fgetcsv($file); // Skip header row
        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
            if(count($column) < 6) continue;
            $conn->query("INSERT INTO questions (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option) 
                          VALUES ('$quiz_id', '".sanitize($column[0])."', '".sanitize($column[1])."', '".sanitize($column[2])."', 
                          '".sanitize($column[3])."', '".sanitize($column[4])."', '".strtoupper(trim(sanitize($column[5])))."')");
        }
        fclose($file);
        $msg = "CSV Data Imported!";
    }
}

// --- 3. COPY/PASTE LOGIC ---
if(isset($_POST['paste_submit'])) {
    $lines = explode("\n", trim($_POST['pasted_data']));
    foreach($lines as $line) {
        $data = str_getcsv($line); // Parses comma-separated lines
        if(count($data) >= 6) {
            $conn->query("INSERT INTO questions (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option) 
                          VALUES ('$quiz_id', '".sanitize($data[0])."', '".sanitize($data[1])."', '".sanitize($data[2])."', 
                          '".sanitize($data[3])."', '".sanitize($data[4])."', '".strtoupper(trim(sanitize($data[5])))."')");
        }
    }
    $msg = "Pasted data processed!";
}
?>

<style>
    .manage-grid { display: grid; grid-template-columns: 450px 1fr; gap: 30px; }
    .glass-panel { background: #fff; padding: 30px; border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.04); border: 1px solid rgba(0,0,0,0.02); }
    
    .tab-nav { display: flex; gap: 10px; background: #f1f4f9; padding: 6px; border-radius: 15px; margin-bottom: 25px; }
    .tab-btn { flex: 1; padding: 10px; border: none; border-radius: 10px; cursor: pointer; font-weight: 600; color: #64748b; transition: 0.3s; }
    .tab-btn.active { background: #4361ee; color: #fff; box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2); }
    
    .form-pane { display: none; animation: slideUp 0.4s ease; }
    .form-pane.active { display: block; }
    @keyframes slideUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    .input-box { margin-bottom: 20px; }
    .input-box label { display: block; font-weight: 700; font-size: 0.8rem; color: #2b3674; margin-bottom: 8px; text-transform: uppercase; }
    .input-box input, .input-box textarea, .input-box select { 
        width: 100%; padding: 12px; border: 1px solid #e0e5f2; border-radius: 12px; transition: 0.3s;
    }
    .input-box input:focus { border-color: #4361ee; outline: none; box-shadow: 0 0 0 4px rgba(67,97,238,0.05); }

    .ai-magic-btn { background: linear-gradient(45deg, #4361ee, #4cc9f0); color: white; border: none; padding: 15px; border-radius: 12px; width: 100%; font-weight: 700; cursor: pointer; }
</style>

<div class="main-content">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 35px;">
        <div>
            <h2 style="font-weight: 800; color: #2b3674;">Manage Questions</h2>
            <p style="color: #a3aed0;">Quiz: <span style="color: #4361ee; font-weight: 700;"><?= $quiz_info['quiz_title'] ?></span></p>
        </div>
        <a href="manage-quizzes.php" class="btn" style="background: #f1f4f9; color: #64748b; text-decoration: none; padding: 12px 25px; border-radius: 12px; font-weight: 600;">← Back</a>
    </div>

    <?php if($msg): ?>
        <div style="background: #e7fbf3; color: #00ab66; padding: 15px; border-radius: 12px; margin-bottom: 25px; border: 1px solid #00ab66;">
            <i class="fas fa-check-circle"></i> <?= $msg ?>
        </div>
    <?php endif; ?>

    <div class="manage-grid">
        <div class="glass-panel">
            <div class="tab-nav">
                <button class="tab-btn active" onclick="showTab('manual', this)">Manual</button>
                <button class="tab-btn" onclick="showTab('csv', this)">CSV</button>
                <button class="tab-btn" onclick="showTab('paste', this)">Paste</button>
                <button class="tab-btn" onclick="showTab('ai', this)">🤖 AI</button>
            </div>

            <div id="manual" class="form-pane active">
                <form method="POST">
                    <div class="input-box"><label>Question Content</label><textarea name="question_text" rows="3" required></textarea></div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="input-box"><label>Option A</label><input type="text" name="opt_a" required></div>
                        <div class="input-box"><label>Option B</label><input type="text" name="opt_b" required></div>
                        <div class="input-box"><label>Option C</label><input type="text" name="opt_c" required></div>
                        <div class="input-box"><label>Option D</label><input type="text" name="opt_d" required></div>
                    </div>
                    <div class="input-box">
                        <label>Correct Answer</label>
                        <select name="correct"><option value="A">A</option><option value="B">B</option><option value="C">C</option><option value="D">D</option></select>
                    </div>
                    <button type="submit" name="add_manual" class="btn btn-primary" style="width: 100%;">Add Question</button>
                </form>
            </div>

            <div id="csv" class="form-pane">
                <form method="POST" enctype="multipart/form-data">
                    <div style="border: 2px dashed #4361ee; padding: 40px; border-radius: 20px; text-align: center; margin-bottom: 20px;">
                        <i class="fas fa-file-csv" style="font-size: 40px; color: #4361ee; margin-bottom: 10px;"></i>
                        <input type="file" name="csv_file" accept=".csv" required style="display: block; margin: 0 auto;">
                    </div>
                    <button type="submit" name="upload_csv" class="btn btn-primary" style="width: 100%;">Upload CSV</button>
                </form>
            </div>

            <div id="paste" class="form-pane">
                <form method="POST">
                    <div class="input-box">
                        <label>Paste Comma Separated Lines</label>
                        <textarea name="pasted_data" rows="8" placeholder="Question,OptA,OptB,OptC,OptD,Correct"></textarea>
                    </div>
                    <button type="submit" name="paste_submit" class="btn btn-primary" style="width: 100%;">Process Paste</button>
                </form>
            </div>

            <div id="ai" class="form-pane">
                <div style="text-align: center; padding: 20px;">
                    <i class="fas fa-robot" style="font-size: 40px; color: #4361ee; margin-bottom: 15px;"></i>
                    <h4>AI Question Generator</h4>
                    <p style="color: #a3aed0; font-size: 0.8rem; margin: 15px 0;">Generate questions automatically based on your Quiz Title using the QuizHub AI Engine.</p>
                    <button type="button" class="ai-magic-btn" onclick="alert('Integrating with OpenAI API...')">Generate with AI</button>
                </div>
            </div>
        </div>

        <div class="glass-panel">
            <h4 style="margin-bottom: 20px;">Existing Questions</h4>
            <table class="table">
                <thead>
                    <tr><th>Question</th><th style="text-align: center;">Correct</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php
                    $res = $conn->query("SELECT * FROM questions WHERE quiz_id = $quiz_id ORDER BY id DESC");
                    while($row = $res->fetch_assoc()):
                    ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($row['question_text']) ?></strong></td>
                        <td style="text-align: center;"><span style="background: #e7fbf3; color: #00ab66; padding: 4px 10px; border-radius: 6px; font-weight: 800;"><?= $row['correct_option'] ?></span></td>
                        <td>
                            
                                
                            </a>
                            <a href="delete-question.php?id=<?= $row['id'] ?>&quiz_id=<?= $quiz_id ?>" style="color: #ff5252;" onclick="return confirm('Delete permanently?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function showTab(tabId, btn) {
    document.querySelectorAll('.form-pane').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById(tabId).classList.add('active');
    btn.classList.add('active');
    
}
</script>

<?php include 'admin-footer.php'; ?>