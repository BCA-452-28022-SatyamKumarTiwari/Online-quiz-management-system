<?php
include "../includes/session.php";
include "../config/db.php";

$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT full_name FROM users WHERE id='$user_id'")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Panel</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">

<style>

*{
  margin:0;
  padding:0;
  box-sizing:border-box;
  font-family:'Poppins',sans-serif;
}

/* Background animation */
body{
  background: linear-gradient(-45deg,#0f2027,#203a43,#2c5364,#1c1c1c);
  background-size: 400% 400%;
  animation: gradientBG 12s ease infinite;
  color:white;
  display:flex;
}

/* Background moving effect */
@keyframes gradientBG{
  0%{background-position:0% 50%;}
  50%{background-position:100% 50%;}
  100%{background-position:0% 50%;}
}

/* Sidebar */
.sidebar{
  width:240px;
  height:100vh;
  background: rgba(255,255,255,0.05);
  backdrop-filter: blur(15px);
  padding:25px;
  position:fixed;
  border-right:1px solid rgba(255,255,255,0.1);
  animation: slideIn 0.6s ease;
}

/* Slide animation */
@keyframes slideIn{
  from{transform:translateX(-100%);}
  to{transform:translateX(0);}
}

.logo{
  font-size:34px;
  font-weight:bold;
  margin-bottom:30px;
  color:#00e5ff;
  text-shadow:10 0 10px #00e5ff;
}

/* Sidebar links */
.sidebar a{
  display:block;
  color:white;
  text-decoration:none;
  padding:12px;
  margin:10px 0;
  border-radius:10px;
  transition:0.3s;
  position:relative;
}

.sidebar a:hover{
  background:#00e5ff;
  color:black;
  transform:translateX(6px);
}

/* Main content */
.main{
  margin-left:260px;
  padding:30px;
  width:100%;
  animation: fadeIn 1s ease;
}

/* Fade animation */
@keyframes fadeIn{
  from{opacity:0;}
  to{opacity:1;}
}

/* Glass cards */
.card{
  background:rgba(255,255,255,0.08);
  padding:12px;
  border-radius:16px;
  margin-bottom:20px;
  margin-top:20px;
  backdrop-filter: blur(10px);
  transition:0.3s;
  box-shadow:0 0 15px rgba(0,229,255,0.15);
}

/* Floating hover effect */
.card:hover{
  transform:translateY(-6px);
  box-shadow:0 0 25px rgba(0,229,255,0.4);
}

/* Animated buttons */
.btn{
  padding:10px 18px;
  background:#00e5ff;
  border:none;
  border-radius:8px;
  font-weight:bold;
  cursor:pointer;
  transition:0.3s;
  position:relative;
  overflow:hidden;
  display: inline-block;
  margin-top: 5px;
}

.btn:hover{
  background:#00bcd4;
  transform:scale(1.05);
}

/* Ripple click effect */
.btn::after{
  content:"";
  position:absolute;
  width:0;
  height:0;
  border-radius:50%;
  background:rgba(255,255,255,0.6);
  top:50%;
  left:50%;
  transform:translate(-50%,-50%);
  transition:0.5s;
}

.btn:active::after{
  width:200px;
  height:200px;
}

/* Table animation */
table{
  width:100%;
  border-collapse:collapse;
}

th,td{
  padding:12px;
  border-bottom:1px solid rgba(255,255,255,0.1);
}

tr:hover{
  background:rgba(0,229,255,0.1);
  transition:0.3s;
}
/* Light mode colors */
body.light{
  background: linear-gradient(-45deg,#f7f9fc,#e3f2fd,#ffffff,#ddefff);
  color:#111;
}

body.light .sidebar{
  background: rgba(255,255,255,0.9);
  border-right:1px solid rgba(0,0,0,0.08);
}

body.light .sidebar a{
  color:#111;
}

body.light .card{
  background:rgba(255,255,255,0.9);
  color:#111;
}

body.light table{
  color:#111;
}

body.light th,
body.light td{
  border-bottom:1px solid rgba(0,0,0,0.1);
}

</style>
</head>
<script>
function toggleTheme(){
  document.body.classList.toggle("light");

  // save mode
  if(document.body.classList.contains("light")){
    localStorage.setItem("theme","light");
  }else{
    localStorage.setItem("theme","dark");
  }
}

// load saved mode
window.onload = function(){
  if(localStorage.getItem("theme") === "light"){
    document.body.classList.add("light");
  }
}
</script>


<body>

<div class="sidebar">
    <div style="margin-bottom:20px;font-size:14px;">
👋 Welcome,<br><b><?php echo $user['full_name']; ?></b>
</div>

  <div class="logo">🎓 QuizHub</div>

  <a href="dashboard.php">🏠 Dashboard</a>
  <a href="profile.php">👤 Profile</a>
  <a href="leaderboard.php">🏆 Leaderboard</a>
  <a href="analysis.php">📊 Analysis</a>
  <a href="take-quiz.php">📝 Take Quiz</a>
  <a href="../auth/logout.php" style="margin-top: 50px; color: #ff4d4d; border: 1px solid rgba(255, 77, 77, 0.2);">🚪 Logout</a>
</div>

<div class="main">
    <button onclick="toggleTheme()" class="btn" style="float:right;margin-bottom:1px;">
🌙 / ☀ Mode
</button>

