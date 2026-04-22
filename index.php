<?php
session_start();
require_once 'config/db.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    $redirect = ($_SESSION['role'] == 'admin') ? 'admin/dashboard.php' : 'student/dashboard.php';
    header("Location: $redirect");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuizHub | Smart Online Assessment Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #00e5ff;
            --accent: #4cc9f0;
            --glass: rgba(255, 255, 255, 0.08);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        /* Matches student header background animation */
        body {
            background: linear-gradient(-45deg, #0f2027, #203a43, #2c5364, #1c1c1c);
            background-size: 400% 400%;
            animation: gradientBG 12s ease infinite;
            color: white;
            min-height: 100vh;
            overflow-x: hidden;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Navigation */
        nav {
            padding: 1.5rem 10%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid var(--glass-border);
        }

        .logo {
            font-size: 40px;
            font-weight: bold;
            color: var(--primary);
            text-decoration: none;
            text-shadow: 0 0 10px var(--primary);
        }

        .nav-btns .btn-login {
            text-decoration: none;
            color: blue;
            font-weight: 500;
            margin-right: 2rem;
            transition: 0.3s;
        }

        .nav-btns .btn-login:hover {
            color: var(--primary);
        }

        /* Glass Buttons */
        .btn-cta {
            background: var(--primary);
            color: black;
            padding: 0.8rem 1.8rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
            display: inline-block;
            box-shadow: 0 0 15px rgba(0, 229, 255, 0.3);
        }

        .btn-cta:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 0 25px rgba(0, 229, 255, 0.5);
        }

        /* Hero Section */
        .hero {
            padding: 10rem 10% 5rem 10%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 90vh;
        }

        .hero-content {
            flex: 1;
            max-width: 600px;
            animation: fadeIn 1s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hero-tag {
            background: rgba(0, 229, 255, 0.1);
            color: var(--primary);
            padding: 0.5rem 1rem;
            border-radius: 100px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(0, 229, 255, 0.2);
        }

        .hero h1 {
            font-size: 3.5rem;
            line-height: 1.1;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .hero h1 span {
            color: var(--primary);
            text-shadow: 0 0 20px rgba(0, 229, 255, 0.3);
        }

        .hero p {
            font-size: 1.1rem;
            color: rgba(255,255,255,0.7);
            line-height: 1.6;
            margin-bottom: 2.5rem;
        }

        /* Features Section */
        .features {
            padding: 5rem 10%;
            background: rgba(0,0,0,0.2);
        }

        .section-title {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title h2 {
            font-size: 2rem;
            color: var(--primary);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        /* Matches student header .card style */
        .f-card {
            background: var(--glass);
            padding: 2.5rem;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            transition: 0.3s;
            text-align: center;
        }

        .f-card:hover {
            transform: translateY(-10px);
            background: rgba(255,255,255,0.12);
            box-shadow: 0 0 25px rgba(0,229,255,0.2);
        }

        .icon {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            display: block;
        }

        .f-card h3 {
            margin-bottom: 1rem;
            font-size: 1.3rem;
            color: var(--primary);
        }

        .f-card p {
            color: rgba(255,255,255,0.6);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        footer {
            text-align: center;
            padding: 4rem;
            background: rgba(0,0,0,0.4);
            border-top: 1px solid var(--glass-border);
            color: rgba(255,255,255,0.4);
        }

        @media (max-width: 968px) {
            .hero {
                flex-direction: column;
                text-align: center;
                padding-top: 8rem;
            }
            .hero h1 { font-size: 2.5rem; }
            .hero-content { margin-bottom: 3rem; }
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
    margin-top:-50px;
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
        <a href="#" class="logo">QuizHub</a>
        <div class="nav-btns">
            <a href="auth/login.php" class="btn-login">Login</a>
            <a href="auth/register.php" class="btn-cta">Register</a>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-content">
            <span class="hero-tag">✨ The Future of Learning</span>
            <h1>Enhance Your Skills with <span>Smart Quizzes</span></h1>
            <p>A comprehensive platform for educators to create, manage, and analyze assessments, and for students to excel in their academic journey.</p>
            
            <div class="hero-btns">
                <a href="auth/register.php" class="btn-cta" style="padding: 1.2rem 2.5rem; font-size: 1.1rem;">Get Started Free</a>
            </div>
        </div>

        <div class="hero-image">
            <svg width="400" height="350" viewBox="0 0 500 400" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="500" height="400" rx="24" fill="white" fill-opacity="0.05"/>
                <circle cx="250" cy="200" r="100" fill="#00e5ff" fill-opacity="0.1"/>
                <rect x="150" y="120" width="200" height="20" rx="10" fill="#00e5ff" fill-opacity="0.8"/>
                <rect x="150" y="160" width="150" height="20" rx="10" fill="#00e5ff" fill-opacity="0.4"/>
                <rect x="150" y="200" width="180" height="20" rx="10" fill="#00e5ff" fill-opacity="0.2"/>
            </svg>
        </div>
    </section>

    <section class="features">
        <div class="section-title">
            <h2>Why Choose QuizHub?</h2>
        </div>
        <div class="grid">
            <div class="f-card">
                <span class="icon">⏱️</span>
                <h3>Timed Exams</h3>
                <p>Strict time-management features with auto-submission capabilities for fair testing.</p>
            </div>
            <div class="f-card">
                <span class="icon">📊</span>
                <h3>Real-time Analytics</h3>
                <p>Detailed performance reports and CSV exports for administrators and teachers.</p>
            </div>
            <div class="f-card">
                <span class="icon">🛡️</span>
                <h3>Secure & Robust</h3>
                <p>Advanced anti-cheating mechanisms including navigation tracking and right-click disable.</p>
            </div>
        </div>
        <div class="dark">
             <button onclick="toggleTheme()" class="btn-mode">
    🌙 / ☀️ Mode
        </div>
    </section>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> QuizHub. All rights reserved.</p>
    </footer>
</button>
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