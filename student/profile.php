<?php 
include 'student-header.php'; 

$id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id='$id'")->fetch_assoc();
$user_img = !empty($user['profile_pic']) ? "../uploads/".$user['profile_pic'] : "../assets/default-avatar.png";
?>

<style>
    /* Profile Specific Glassmorphism */
    .profile-container {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 25px;
        margin-top: 10px;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 8px 32px rgba(0, 229, 255, 0.1);
        transition: 0.3s ease;
    }

    /* Support for Light Mode from your header */
    body.light .glass-card {
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(0, 0, 0, 0.05);
        color: #333;
    }

    .profile-pic-container {
        text-align: center;
    }

    .profile-pic-container img {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #00e5ff;
        padding: 5px;
        margin-bottom: 20px;
        box-shadow: 0 0 20px rgba(0, 229, 255, 0.3);
    }

    .info-section h3 {
        color: #00e5ff;
        margin-bottom: 20px;
        font-size: 1.4rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.2);
        padding-bottom: 10px;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 15px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    body.light .detail-row { border-bottom: 1px solid rgba(0, 0, 0, 0.05); }

    .label { font-weight: 600; color: #00e5ff; }
    
    .btn-action {
        width: 100%;
        margin-top: 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
    }

    @media (max-width: 992px) {
        .profile-container { grid-template-columns: 1fr; }
    }
</style>

<div class="profile-container">
    <div class="glass-card profile-pic-container">
        <img src="<?php echo $user_img; ?>" alt="Profile">
        <h2 style="margin-bottom: 5px;"><?php echo htmlspecialchars($user['full_name']); ?></h2>
        <p style="opacity: 0.7; font-size: 0.9rem;">Student Account</p>
        <a href="edit-profile.php" class="btn btn-action">✏️ Edit Profile</a>
    </div>

    <div class="main-info">
        <div class="glass-card info-section">
            <h3>Personal Information</h3>
            <div class="detail-row">
                <span class="label">Full Name</span>
                <span><?php echo htmlspecialchars($user['full_name']); ?></span>
            </div>
            <div class="detail-row">
                <span class="label">Email Address</span>
                <span><?php echo htmlspecialchars($user['email']); ?></span>
            </div>
            <div class="detail-row">
                <span class="label">Phone Number</span>
                <span><?php echo htmlspecialchars($user['phone'] ?? 'Not set'); ?></span>
            </div>
            <div class="detail-row">
                <span class="label">Date of Birth</span>
                <span><?php echo htmlspecialchars($user['dob'] ?? 'Not set'); ?></span>
            </div>
            <div class="detail-row">
                <span class="label">Home Address</span>
                <span><?php echo htmlspecialchars($user['address'] ?? 'Not set'); ?></span>
            </div>
        </div>

        <div class="glass-card info-section" style="margin-top:25px;">
            <h3>Quiz Statistics</h3>
            <p style="opacity: 0.6;">Your quiz history and performance analysis will appear here soon.</p>
        </div>
    </div>
</div>

<?php include 'student-footer.php'; ?>