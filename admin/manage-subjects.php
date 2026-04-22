<?php 
include "../config/db.php";
include 'admin-header.php'; 


// Handle Adding Subject
if(isset($_POST['add_subject'])) {
    $name = sanitize($_POST['subject_name']);
    if(!empty($name)) {
        $conn->query("INSERT INTO subjects (name) VALUES ('$name')");
        echo "<script>alert('Subject added!'); window.location='manage-subjects.php';</script>";
    }
}

// Handle Deleting Subject
if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM subjects WHERE id = $id");
    header("Location: manage-subjects.php");
    exit();
}
?>

<h2>Manage Subjects</h2>
<hr>

<div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; max-width: 500px;">
    <h4>Add New Subject</h4>
    <form method="POST">
        <div class="form-group">
            <input type="text" name="subject_name" placeholder="Enter subject name (e.g. PHP, Java)" required>
        </div>
        <button type="submit" name="add_subject" class="btn btn-primary" style="width: auto;">Add Subject</button>
    </form>
</div>

<h3>Existing Subjects</h3>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Subject Name</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $res = $conn->query("SELECT * FROM subjects ORDER BY id DESC");
        if($res->num_rows > 0) {
            while($row = $res->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>
                            <a href='manage-subjects.php?delete={$row['id']}'
                               onclick='return confirm(\"Delete this subject?\")'
                               style='color:red;'>Delete</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='3' style='text-align:center;'>No subjects found.</td></tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>
