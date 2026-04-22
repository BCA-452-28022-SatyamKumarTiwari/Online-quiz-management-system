<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once "../config/db.php";

// Only admin allowed
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Logic: Add Teacher
if (isset($_POST['add_teacher'])) {
    $name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $conn->query("INSERT INTO users (full_name, email, password, role) VALUES ('$name', '$email', '$pass', 'teacher')");
    header("Location: manage-teachers.php?success=1");
    exit();
}

// Logic: Delete teacher
if (isset($_GET['delete'])) {
    $tid = (int)$_GET['delete'];
    $conn->query("DELETE FROM users WHERE id = $tid AND role='teacher'");
    header("Location: manage-teachers.php");
    exit();
}

// Logic: Reset password to default "123456"
if (isset($_GET['reset_pw'])) {
    $tid = (int)$_GET['reset_pw'];
    $hashed = password_hash("123456", PASSWORD_DEFAULT);
    $conn->query("UPDATE users SET password='$hashed' WHERE id=$tid AND role='teacher'");
    header("Location: manage-teachers.php?reset=1");
    exit();
}

include 'admin-header.php';
?>

<div class="content-header">
    <h2>Manage Teachers</h2>
    <button class="btn-primary" onclick="document.getElementById('addTeacherModal').style.display='block'">+ Add Teacher</button>
</div>

<div class="premium-card">
    <table class="premium-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>NAME</th>
                <th>EMAIL</th>
                <th style="text-align: center;">ACTION</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $res = $conn->query("SELECT * FROM users WHERE role='teacher' ORDER BY id DESC");
            if($res->num_rows > 0):
                while($row = $res->fetch_assoc()):
            ?>
            <tr>
                <td>#<?= $row['id'] ?></td>
                <td><strong><?= htmlspecialchars($row['full_name']) ?></strong></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td style="text-align: center;">
                    <a class="btn-action btn-reset" href="?reset_pw=<?= $row['id'] ?>">Reset PW</a>
                    <a class="btn-action btn-delete" onclick="return confirm('Delete this teacher?')" href="?delete=<?= $row['id'] ?>">Delete</a>
                </td>
            </tr>
            <?php endwhile; else: ?>
            <tr><td colspan="4" style="text-align:center;">No teachers found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div id="addTeacherModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('addTeacherModal').style.display='none'">&times;</span>
        <h3>Add New Teacher</h3>
        <form method="POST">
            <div class="input-group">
                <label>Full Name</label>
                <input type="text" name="full_name" required placeholder="Enter full name">
            </div>
            <div class="input-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="email@example.com">
            </div>
            <div class="input-group">
                <label>Default Password</label>
                <input type="password" name="password" required placeholder="Create password">
            </div>
            <button type="submit" name="add_teacher" class="btn-save">Save Teacher</button>
        </form>
    </div>
</div>


