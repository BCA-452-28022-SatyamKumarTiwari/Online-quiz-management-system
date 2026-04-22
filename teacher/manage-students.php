<?php
// 1. LOGIC FIRST (Must be before admin-header.php)
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once "../config/db.php";

// --- FEATURE: Delete Student ---
if (isset($_GET['delete'])) {
    $sid = (int)$_GET['delete'];
    $conn->query("DELETE FROM users WHERE id = $sid AND role = 'student'");
    header("Location: manage-students.php?msg=Student Account Removed");
    exit();
}

// --- FEATURE: Toggle Status ---
if (isset($_GET['toggle'])) {
    $sid = (int)$_GET['toggle'];
    $current = (int)$_GET['status'];
    $new_status = ($current == 1) ? 0 : 1;
    $conn->query("UPDATE users SET is_active = $new_status WHERE id = $sid");
    header("Location: manage-students.php");
    exit();
}

// --- FEATURE: Reset Password ---
if (isset($_GET['reset_pw'])) {
    $sid = (int)$_GET['reset_pw'];
    $hashed = password_hash("123456", PASSWORD_DEFAULT);
    $conn->query("UPDATE users SET password = '$hashed' WHERE id = $sid");
    header("Location: manage-students.php?msg=Password reset to 123456");
    exit();
}

include 'teacher-header.php';
?>

<style>
    /* Premium UI Overrides */
    .student-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.03);
    }
    
    .student-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 12px;
    }
    
    .student-table thead th {
        padding: 0 15px 10px;
        color: #a3aed0;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: none;
    }
    
    .student-table tbody tr {
        background: #fbfcfe;
        transition: all 0.3s ease;
    }
    
    .student-table tbody tr:hover {
        transform: translateY(-2px);
        background: #f4f7fe;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .student-table td {
        padding: 15px;
        border-top: 1px solid #f1f4f9;
        border-bottom: 1px solid #f1f4f9;
    }
    
    .student-table td:first-child { border-left: 1px solid #f1f4f9; border-radius: 12px 0 0 12px; }
    .student-table td:last-child { border-right: 1px solid #f1f4f9; border-radius: 0 12px 12px 0; }
    
    .status-badge {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
    }
    
    .action-btn {
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        margin-left: 5px;
        text-decoration: none;
        transition: 0.2s;
    }
</style>

<div class="main-content">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2 style="font-weight: 700; color: #2b3674;">Manage Students</h2>
        <div style="background: #fff; padding: 10px 20px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.03);">
            <span style="color: #a3aed0; font-size: 14px;">Total Registered:</span> 
            <strong style="color: #4361ee;"><?php echo $conn->query("SELECT id FROM users WHERE role='student'")->num_rows; ?></strong>
        </div>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <div style="background: #e7fbf3; color: #00ab66; padding: 15px; border-radius: 12px; margin-bottom: 25px; border: 1px solid rgba(0, 171, 102, 0.2); animation: fadeIn 0.5s;">
            <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_GET['msg']); ?>
        </div>
    <?php endif; ?>

    <div class="student-card">
        <table class="student-table">
            <thead>
                <tr>
                    <th>Student Profile</th>
                    <th>Contact Information</th>
                    <th>Status</th>
                    <th style="text-align: right;">Administrative Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $res = $conn->query("SELECT * FROM users WHERE role = 'student' ORDER BY id DESC");
                while ($row = $res->fetch_assoc()) {
                    $img = !empty($row['profile_pic']) ? "../uploads/".$row['profile_pic'] : "../assets/default-avatar.png";
                    $status_color = $row['is_active'] ? '#00ab66' : '#ee5d5d';
                    $status_bg = $row['is_active'] ? '#e7fbf3' : '#fff5f5';
                ?>
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <img src="<?php echo $img; ?>" style="width: 45px; height: 45px; border-radius: 12px; object-fit: cover; border: 2px solid #f1f4f9;">
                            <div>
                                <div style="font-weight: 700; color: #2b3674;"><?php echo htmlspecialchars($row['full_name']); ?></div>
                                <div style="font-size: 11px; color: #a3aed0;">Student ID: #<?php echo $row['id']; ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-size: 13px; color: #2b3674;"><i class="fas fa-envelope" style="width: 20px; color: #4361ee;"></i> <?php echo $row['email']; ?></div>
                        <div style="font-size: 13px; color: #a3aed0;"><i class="fas fa-phone" style="width: 20px; color: #4361ee;"></i> <?php echo $row['phone'] ?? 'Not Provided'; ?></div>
                    </td>
                    <td>
                        <span class="status-badge" style="background: <?php echo $status_bg; ?>; color: <?php echo $status_color; ?>;">
                            <i class="fas fa-circle" style="font-size: 8px; margin-right: 5px;"></i>
                            <?php echo $row['is_active'] ? 'ACTIVE' : 'DEACTIVATED'; ?>
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <a href="?reset_pw=<?php echo $row['id']; ?>" class="action-btn" style="background: #eef2ff; color: #4361ee;" title="Reset Password" onclick="return confirm('Reset password to 123456?')">
                            <i class="fas fa-key"></i>
                        </a>

                        <a href="?toggle=<?php echo $row['id']; ?>&status=<?php echo $row['is_active']; ?>" class="action-btn" style="background: #f4f7fe; color: #2b3674;" title="Toggle Access">
                            <i class="fas <?php echo $row['is_active'] ? 'fa-user-check' : 'fa-user-slash'; ?>"></i>
                        </a>

                        <a href="?delete=<?php echo $row['id']; ?>" class="action-btn" style="background: #fff5f5; color: #ee5d5d;" title="Delete Student" onclick="return confirm('Permanent action! Are you sure?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'teacher-footer.php'; ?>