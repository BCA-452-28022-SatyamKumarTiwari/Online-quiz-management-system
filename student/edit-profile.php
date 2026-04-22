<?php
// 1. ALL LOGIC AT THE VERY TOP
session_start();
require_once '../config/db.php';

$id = $_SESSION['user_id'];
$error = "";

if (isset($_POST['update'])) {
    $name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    $profile_pic = $_POST['current_pic']; 
    if (!empty($_FILES['profile_pic']['name'])) {
        $target_dir = "../uploads/";
        $file_name = time() . "_" . basename($_FILES["profile_pic"]["name"]);
        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_dir . $file_name)) {
            $profile_pic = $file_name;
        }
    }

    $sql = "UPDATE users SET full_name='$name', phone='$phone', dob='$dob', address='$address', profile_pic='$profile_pic' WHERE id='$id'";
    if ($conn->query($sql)) {
        $_SESSION['full_name'] = $name; 
        echo "<script>window.location.href='profile.php';</script>";
        exit();
    } else {
        $error = "Update failed: " . $conn->error;
    }
}

$user = $conn->query("SELECT * FROM users WHERE id='$id'")->fetch_assoc();
$user_img = !empty($user['profile_pic']) ? "../uploads/".$user['profile_pic'] : "../assets/default-avatar.png";

include 'student-header.php';
?>

<style>
    /* Main Layout Grid */
    .edit-grid { 
        display: grid; 
        grid-template-columns: 320px 1fr; 
        gap: 30px; 
        padding: 20px;
        animation: fadeIn 0.8s ease;
    }

    /* Professional Glass Cards */
    .glass-card { 
        background: rgba(255, 255, 255, 0.05); 
        backdrop-filter: blur(15px); 
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1); 
        border-radius: 20px; 
        padding: 40px; 
        box-shadow: 0 8px 32px rgba(0, 229, 255, 0.1);
        transition: transform 0.3s ease;
    }

    /* Light Mode Overrides */
    body.light .glass-card { 
        background: rgba(255, 255, 255, 0.9); 
        color: #111; 
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
    }

    /* Sidebar Avatar Styling */
    .edit-sidebar { text-align: center; height: fit-content; }
    .avatar-preview {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #00e5ff;
        padding: 6px;
        margin-bottom: 25px;
        box-shadow: 0 0 25px rgba(0, 229, 255, 0.3);
        transition: 0.4s;
    }
    .avatar-preview:hover { transform: rotate(5deg) scale(1.05); }

    /* Form Typography & Elements */
    .info-card h3 { 
        color: #00e5ff; 
        font-size: 1.5rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.2); 
        padding-bottom: 15px; 
        margin-bottom: 30px; 
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .form-group { margin-bottom: 25px; }
    .form-group label { 
        display: block; 
        font-weight: 500; 
        color: #00e5ff; 
        margin-bottom: 10px; 
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    /* Styled Input Fields */
    .form-group input, .form-group textarea { 
        width: 100%; 
        padding: 14px; 
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1); 
        border-radius: 12px; 
        color: white; 
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    body.light .form-group input, body.light .form-group textarea {
        background: #f8f9fa;
        color: #111;
        border: 1px solid #dee2e6;
    }

    input:focus, textarea:focus { 
        outline: none; 
        border-color: #00e5ff; 
        background: rgba(0, 229, 255, 0.05);
        box-shadow: 0 0 15px rgba(0, 229, 255, 0.2); 
    }

    /* Custom Stylish Upload Area */
    .custom-file-upload {
        display: block;
        padding: 15px;
        border: 2px dashed rgba(0, 229, 255, 0.4);
        border-radius: 12px;
        cursor: pointer;
        text-align: center;
        transition: 0.3s;
        color: #8892b0;
        font-size: 0.9rem;
    }
    .custom-file-upload:hover {
        background: rgba(0, 229, 255, 0.05);
        border-color: #00e5ff;
        color: #00e5ff;
    }

    /* Action Buttons */
    .btn-container { display: flex; gap: 20px; margin-top: 20px; }
    .save-btn { flex: 2; padding: 15px; font-size: 1rem; }
    .cancel-btn { 
        flex: 1; 
        padding: 15px; 
        background: rgba(255, 255, 255, 0.05); 
        color: white; 
        text-decoration: none; 
        text-align: center; 
        border-radius: 8px; 
        font-weight: bold; 
        transition: 0.3s;
    }
    .cancel-btn:hover { background: rgba(255, 0, 0, 0.15); color: #ff4d4d; }

    body.light .cancel-btn { color: #111; border: 1px solid #ddd; }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<form method="POST" enctype="multipart/form-data">
    <div class="edit-grid">
        <div class="glass-card edit-sidebar">
            <h4 style="color: #8892b0; font-size: 0.8rem; margin-bottom: 20px;">PROFILE IMAGE</h4>
            <img src="<?php echo $user_img; ?>" alt="Preview" class="avatar-preview" id="previewImg">
            
            <div class="form-group">
                <label class="custom-file-upload">
                    <input type="file" name="profile_pic" id="profile_pic" accept="image/*" style="display:none;" onchange="previewFile()">
                    ✨ Change Profile Photo
                </label>
                <input type="hidden" name="current_pic" value="<?php echo htmlspecialchars($user['profile_pic'] ?? ''); ?>">
            </div>
            <p style="font-size: 0.7rem; color: #8892b0; margin-top: 15px;">Supported formats: JPG, PNG, GIF. <br>Max size: 2MB</p>
        </div>

        <div class="glass-card info-card">
            <h3>Update Details</h3>
            
            <?php if($error): ?>
                <div style="background: rgba(255, 77, 77, 0.1); color: #ff4d4d; padding: 15px; border-radius: 12px; margin-bottom: 25px; border: 1px solid rgba(255, 77, 77, 0.2); font-size: 0.9rem;">
                    ❌ <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label>FULL NAME</label>
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required placeholder="Enter your full name">
            </div>

            <div class="form-group">
                <label>PHONE NUMBER</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" placeholder="e.g. +91 00000 00000">
            </div>

            <div class="form-group">
                <label>DATE OF BIRTH</label>
                <input type="date" name="dob" value="<?php echo htmlspecialchars($user['dob'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label>RESIDENTIAL ADDRESS</label>
                <textarea name="address" rows="4" placeholder="Street name, City, State, Country"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
            </div>

            <div class="btn-container">
                <button type="submit" name="update" class="btn save-btn">🚀 Save Changes</button>
                <a href="profile.php" class="cancel-btn">Cancel</a>
            </div>
        </div>
    </div>
</form>

<script>
// Live Image Preview for a better User Experience
function previewFile() {
    const preview = document.getElementById('previewImg');
    const file = document.getElementById('profile_pic').files[0];
    const reader = new FileReader();

    reader.onloadend = function () {
        preview.src = reader.result;
    }

    if (file) {
        reader.readAsDataURL(file);
    } else {
        preview.src = "<?php echo $user_img; ?>";
    }
}
</script>

<?php include 'student-footer.php'; ?>