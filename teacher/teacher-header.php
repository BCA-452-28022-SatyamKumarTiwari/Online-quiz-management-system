<?php
// Ensure session is active and check admin permissions
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "../config/db.php";

// Basic Security: Redirect if not logged in as admin
if($_SESSION['role'] != 'teacher'){
header("Location: ../auth/login.php");
exit();
}

$admin_id = $_SESSION['user_id'];
$admin_name = $_SESSION['name'] ?? "Administrator";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Panel | QuizHub</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --accent: #4cc9f0;
            --sidebar-bg: #1e1e2d;
            --main-bg: #f4f7fe;
            --text-main: #2b3674;
            --text-muted: #a3aed0;
            --white: #ffffff;
            --glass: rgba(255, 255, 255, 0.8);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }

        body { background-color: var(--main-bg); display: flex; color: var(--text-main); }

        /* --- Premium Sidebar --- */
        .sidebar { 
            width: 280px; 
            height: 100vh; 
            background: var(--sidebar-bg); 
            position: fixed; 
            left: 0; 
            top: 0; 
            color: white; 
            padding: 30px 20px;
            box-shadow: 4px 0 10px rgba(214, 183, 175, 0.1);
            z-index: 1000;
        }

        .sidebar-brand { 
            font-size: 24px; 
            font-weight: 700; 
            margin-bottom: 40px; 
            display: flex; 
            align-items: center; 
            gap: 10px;
            color: var(--accent);
        }

        .sidebar-nav { list-style: none; }
        .nav-item { margin-bottom: 8px; }

        .nav-link { 
            display: flex; 
            align-items: center; 
            gap: 15px; 
            color: var(--text-muted); 
            padding: 12px 18px; 
            text-decoration: none; 
            border-radius: 12px; 
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-link i { font-size: 18px; width: 25px; text-align: center; }

        .nav-link:hover, .nav-link.active { 
            background: rgba(255,255,255,0.1); 
            color: white; 
            transform: translateX(5px);
        }

        .nav-link.active { background: var(--primary); box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3); }

        .logout-link { color: #ff5252 !important; margin-top: 50px; border: 1px solid rgba(255, 82, 82, 0.2); }
        .logout-link:hover { background: rgba(255, 82, 82, 0.1); }

        /* --- Main Content Area --- */
        .main-content { margin-left: 280px; width: calc(100% - 280px); padding: 40px; }

        /* Header Bar */
        .top-bar { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 40px; 
            background: var(--glass);
            backdrop-filter: blur(10px);
            padding: 20px 30px;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }

        .admin-profile { display: flex; align-items: center; gap: 15px; }
        .admin-avatar { width: 45px; height: 45px; border-radius: 12px; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; }

        /* Table & Components Improvements */
        .premium-card { background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); }
        
        .table { width: 100%; border-collapse: separate; border-spacing: 0 10px; margin-top: 20px; }
        .table th { background: transparent; color: var(--text-muted); padding: 15px; text-align: left; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; }
        .table td { padding: 18px 15px; background: var(--white); border-top: 1px solid #f1f4f9; border-bottom: 1px solid #f1f4f9; }
        .table tr td:first-child { border-left: 1px solid #2c96d7; border-radius: 12px 0 0 12px; }
        .table tr td:last-child { border-right: 1px solid #f1f4f9; border-radius: 0 12px 12px 0; }
        
        .btn-action { padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600; transition: 0.2s; }
        .btn-edit { background: #eef2ff; color: var(--primary); }
        .btn-delete { background: #fff5f5; color: #ff5252; }

    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-graduation-cap"></i>
            <span>QuizHub </span>
        </div>
        
        <ul class="sidebar-nav">
            <li class="nav-item">
                <a href="dashboard.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="manage-subjects.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'manage-subjects.php' ? 'active' : '' ?>">
                    <i class="fas fa-book"></i> Manage Subjects
                </a>
            </li>
            <li class="nav-item">
                <a href="manage-quizzes.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'manage-quizzes.php' ? 'active' : '' ?>">
                    <i class="fas fa-tasks"></i> Manage Quizzes
                </a>
            </li>
            <li class="nav-item">
                <a href="manage-students.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'manage-students.php' ? 'active' : '' ?>">
                    <i class="fas fa-user-graduate"></i> Manage Students
                </a>
            </li>
            <li class="nav-item">
                <a href="view-results.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'view-results.php' ? 'active' : '' ?>">
                    <i class="fas fa-poll"></i> View Results
                </a>
            </li>
            <li class="nav-item">
                <a href="../auth/logout.php" class="nav-link logout-link">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <h2><?= ucwords(str_replace(['-', '.php'], [' ', ''], basename($_SERVER['PHP_SELF']))) ?></h2>
            <div class="admin-profile">
                <div style="text-align: right">
                    <p style="font-weight: 600; font-size: 14px;"><?= $admin_name ?></p>
                    <p style="font-size: 12px; color: var(--text-muted)">Teacher</p>
                </div>
                <div class="admin-avatar">A</div>
            </div>
        </div>