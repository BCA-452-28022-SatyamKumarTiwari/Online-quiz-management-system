<?php
require_once '../config/db.php';

$msg = "";
$msg_type = ""; // success or error

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitize($_POST['full_name']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    
    // Check if email exists
    $checkEmail = $conn->query("SELECT id FROM users WHERE email = '$email'");
    
    if($checkEmail->num_rows > 0) {
        $msg = "This email is already registered!";
        $msg_type = "error";
    } else {
        // Hash password
        $hashed_pass = password_hash($password, PASSWORD_BCRYPT);
        
        $sql = "INSERT INTO users (full_name, email, password, role) VALUES ('$name', '$email', '$hashed_pass', 'student')";
        
        if($conn->query($sql)) {
            $msg = "Registration successful! You can now login.";
            $msg_type = "success";
        } else {
            $msg = "Registration failed. Please try again.";
            $msg_type = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Online Quiz System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #4361ee; --bg: #f1f5f9; --text: #1e293b; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg); display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0;
            flex-direction: column;
            gap: 2em; }
        .card { background: white; padding: 2.5rem; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); width: 100%; max-width: 400px; }
        h1 { font-size: 1.75rem; color: var(--primary); text-align: center; margin-bottom: 0.5rem; }
        p.subtitle { text-align: center; color: #64748b; margin-bottom: 2rem; }
        .form-group { margin-bottom: 1.25rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem; }
        input { width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; box-sizing: border-box; }
        input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(67,97,238,0.15); }
        .btn { width: 100%; background: var(--primary); color: white; padding: 0.75rem; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; transition: 0.3s; }
        .btn:hover { background: #374fc7; }
        .alert { padding: 0.75rem; border-radius: 8px; margin-bottom: 1rem; text-align: center; font-size: 0.85rem; }
        .alert-error { background: #fee2e2; color: #b91c1c; }
        .alert-success { background: #dcfce7; color: #15803d; }
        .footer { text-align: center; margin-top: 1.5rem; font-size: 0.9rem; }
        .footer a { color: var(--primary); text-decoration: none; font-weight: 600; }
              .logo{
            font-size: 3em;
            text-decoration: none;
            color: blue;
            font-weight: 2em;
        }
        .logo:hover{
            color: red;
        }
        /* --- 1. Global Variables & Themes --- */
:root {
    /* Dark Mode (Default) - Matching Student Panel */
    --bg-gradient: linear-gradient(-45deg, #0f2027, #203a43, #2c5364, #1c1c1c);
    --primary: #00e5ff;
    --accent: #4cc9f0;
    --text-color: #ffffff;
    --glass: rgba(255, 255, 255, 0.08);
    --glass-border: rgba(255, 255, 255, 0.1);
    --shadow: 0 0 15px rgba(0, 229, 255, 0.15);
}

/* Light Mode Overrides */
body.light {
    --bg-gradient: linear-gradient(-45deg, #f7f9fc, #e3f2fd, #ffffff, #ddefff);
    --text-color: #111111;
    --glass: rgba(255, 255, 255, 0.9);
    --glass-border: rgba(0, 0, 0, 0.08);
    --shadow: 0 0 15px rgba(0, 0, 0, 0.05);
}

/* --- 2. Core Layout --- */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
    transition: background 0.4s ease, color 0.4s ease, transform 0.3s ease;
}

body {
    background: var(--bg-gradient);
    background-size: 400% 400%;
    animation: gradientBG 12s ease infinite; /* From student-header.php */
    color: var(--text-color);
    min-height: 100vh;
    overflow-x: hidden;
}

@keyframes gradientBG {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* --- 3. Navigation Bar --- */
nav {
    padding: 1.2rem 10%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--glass);
    backdrop-filter: blur(15px);
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 1000;
    border-bottom: 1px solid var(--glass-border);
}

.logo {
    font-size: 26px;
    font-weight: 700;
    color: blue(212,188)
    text-decoration: none;
    text-shadow: 10 0 10px var(--primary);
}

.nav-btns {
    display: flex;
    align-items: center;
    gap: 20px;
}

/* --- 4. Premium Buttons --- */
.btn-cta {
    background: var(--primary);
    color: #000 !important;
    padding: 12px 24px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 700;
    box-shadow: var(--shadow);
    border: none;
    cursor: pointer;
}

.btn-cta:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 0 25px rgba(0, 229, 255, 0.4);
}

.btn-mode {
    padding: 10px 18px;
    background: var(--primary);
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    color: #000;
}

/* --- 5. Hero & Features Section --- */
.hero {
    padding: 12rem 10% 5rem 10%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    animation: fadeIn 1s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.hero h1 {
    font-size: 3.5rem;
    line-height: 1.2;
    margin-bottom: 20px;
}

.hero h1 span {
    color: var(--primary);
}

.f-card {
    background: var(--glass);
    padding: 40px;
    border-radius: 24px;
    backdrop-filter: blur(12px);
    border: 1px solid var(--glass-border);
    text-align: center;
    box-shadow: var(--shadow);
}

.f-card:hover {
    transform: translateY(-10px);
    background: rgba(255, 255, 255, 0.12);
}

/* --- 6. Responsive Design --- */
@media (max-width: 968px) {
    .hero { flex-direction: column; text-align: center; }
    .hero h1 { font-size: 2.5rem; }
}
 :root {
  /* Dark Mode (Default) */
  --bg-gradient: linear-gradient(-45deg, #0f2027, #203a43, #2c5364, #1c1c1c);
  --text-color: #ffffff;
  --glass: rgba(255, 255, 255, 0.08);
  --glass-border: rgba(255, 255, 255, 0.1);
  --primary: #00e5ff;
}

/* Light Mode Overrides */
body.light {
  --bg-gradient: linear-gradient(-45deg, #350a1c, #092c44, #3c0808, #3b0505);
  --text-color: #111111;
  --glass: rgba(156, 227, 209, 0.8);
  --glass-border: rgba(0, 0, 0, 0.08);
}

body {
  background: var(--bg-gradient);
  background-size: 400% 400%;
  animation: gradientBG 12s ease infinite;
  color: var(--text-color);
  transition: background 0.4s, color 0.4s;
}

@keyframes gradientBG {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

.btn-mode {
  padding: 10px 18px;
  background: var(--primary);
  border: none;
  border-radius: 8px;
  font-weight: bold;
  cursor: pointer;
  transition: 0.3s;
}
.dark{
    position: absolute;
    top: 80px;
    margin-top:-56px;
    right: 10px;
    padding: 2px 6px;
    background: #00e5ff; /* Matches your cyan theme */
    border: none;
    border-radius: 20px;
    font-weight: bold;
    color: #111;
    cursor: pointer;
    transition: 0.3s;
    z-index: 1001; /* Keeps it above other elements */
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 4px 15px rgba(0, 229, 255, 0.3);
}

    </style>
</head>
<body>
    <nav>
        <a href="../index.php" class="logo">QuizHub</a>
    </nav>
         <div class="dark">
             <button onclick="toggleTheme()" class="btn-mode">
    🌙 / ☀️ Mode
        </div>


<div class="card">
    <h1>Create Account</h1>
    <p class="subtitle">Join as a student to start quizzes</p>

    <?php if($msg): ?>
        <div class="alert alert-<?php echo $msg_type; ?>"><?php echo $msg; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="full_name" placeholder="your full name" required>
        </div>
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="abc@example.com" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn">Register Now</button>
    </form>

    <div class="footer">
        Already have an account? <a href="login.php">Login</a>
    </div>
</div>
<script>
        // Place the logic here so it loads after the HTML elements
        function toggleTheme() {
            document.body.classList.toggle("light");

            // Save preference to localStorage
            if (document.body.classList.contains("light")) {
                localStorage.setItem("theme", "light");
            } else {
                localStorage.setItem("theme", "dark");
            }
        }

        // Load the saved theme on page load
        window.onload = function() {
            if (localStorage.getItem("theme") === "light") {
                document.body.classList.add("light");
            }
        }
    </script>

</body>
</html>