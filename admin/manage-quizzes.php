<?php 
// 1. Include the Header (This should already include session.php and db.php)
include 'admin-header.php'; 

// 2. Handle Form Submission
if(isset($_POST['add_quiz'])) {
    // Using the sanitize function we defined in db.php
    $subj = sanitize($_POST['subject_id']);
    $title = sanitize($_POST['quiz_title']);
    $dur = sanitize($_POST['duration']);
    $marks = sanitize($_POST['total_marks']);
    
    $sql = "INSERT INTO quizzes (subject_id, quiz_title, duration, total_marks, is_active) 
            VALUES ('$subj', '$title', '$dur', '$marks', 1)";
            
    if($conn->query($sql)) {
        echo "<script>alert('Quiz Created Successfully!'); window.location.href='manage-quizzes.php';</script>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}
?>

<div class="main-content">
    <h2>Manage Quizzes</h2>

    <div style="background: white; padding: 25px; border-radius: 12px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <h4 style="margin-bottom: 20px; color: #333;">Create New Quiz</h4>
        <form method="POST" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
            
            <div style="flex: 1; min-width: 150px;">
                <label style="font-weight: 600; font-size: 0.9rem;">Select Subject</label>
                <select name="subject_id" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd; margin-top: 5px;">
                    <option value="">-- Choose Subject --</option>
                    <?php
                    $subs = $conn->query("SELECT * FROM subjects ORDER BY name ASC");
                    while($s = $subs->fetch_assoc()) {
                        echo "<option value='{$s['id']}'>{$s['name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div style="flex: 2; min-width: 250px;">
                <label style="font-weight: 600; font-size: 0.9rem;">Quiz Title</label>
                <input type="text" name="quiz_title" required placeholder="e.g. Final Term Exam" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd; margin-top: 5px;">
            </div>

            <div style="width: 120px;">
                <label style="font-weight: 600; font-size: 0.9rem;">Time (Mins)</label>
                <input type="number" name="duration" required min="1" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd; margin-top: 5px;">
            </div>

            <div style="width: 100px;">
                <label style="font-weight: 600; font-size: 0.9rem;">Total Marks</label>
                <input type="number" name="total_marks" required min="1" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd; margin-top: 5px;">
            </div>

            <button type="submit" name="add_quiz" class="btn btn-primary" style="padding: 11px 25px; height: 43px;">
                ➕ Create Quiz
            </button>
        </form>
    </div>

    <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <h4 style="margin-bottom: 20px;">Existing Quizzes</h4>
        <table class="table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8f9fa; text-align: left;">
                    <th style="padding: 12px;">ID</th>
                    <th style="padding: 12px;">Title</th>
                    <th style="padding: 12px;">Subject</th>
                    <th style="padding: 12px;">Duration</th>
                    <th style="padding: 12px;">Status</th>
                    <th style="padding: 12px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch quizzes with their subject names using a JOIN
                $res = $conn->query("
                    SELECT q.*, s.name as subject_name 
                    FROM quizzes q
                    JOIN subjects s ON q.subject_id = s.id
                    ORDER BY q.id DESC
                ");

                if($res->num_rows > 0) {
                    while($row = $res->fetch_assoc()) {
                        $status_color = $row['is_active'] ? 'green' : 'red';
                        $status_text = $row['is_active'] ? 'Active' : 'Inactive';
                        
                        echo "<tr style='border-bottom: 1px solid #eee;'>
                                <td style='padding: 12px;'>#{$row['id']}</td>
                                <td style='padding: 12px;'><strong>{$row['quiz_title']}</strong></td>
                                <td style='padding: 12px;'>{$row['subject_name']}</td>
                                <td style='padding: 12px;'>{$row['duration']} Mins</td>
                                <td style='padding: 12px;'>
                                    <span style='color: $status_color; font-weight: bold;'>$status_text</span>
                                </td>
                                <td style='padding: 12px;'>
                                    <a href='manage-questions.php?quiz_id={$row['id']}' class='btn' style='background: #e7f0ff; color: #4361ee; text-decoration: none; padding: 5px 10px; border-radius: 5px; font-size: 0.85rem;'>
                                        ⚙️ Manage Questions
                                    </a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' style='padding: 20px; text-align: center; color: #888;'>No quizzes found. Create one above!</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'admin-footer.php'; ?>