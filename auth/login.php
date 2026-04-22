<?php
session_start();
require_once '../config/db.php';

// If already logged in → redirect by role
if (isset($_SESSION['user_id'])) {

    if ($_SESSION['role'] == 'admin') {
        header("Location: ../admin/dashboard.php");
    } elseif ($_SESSION['role'] == 'teacher') {
        header("Location: ../teacher/dashboard.php");
    } else {
        header("Location: ../student/dashboard.php");
    }
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {

        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['full_name'];

            // Redirect by role
            if ($user['role'] == 'admin') {
                header("Location: ../admin/dashboard.php");
            } elseif ($user['role'] == 'teacher') {
                header("Location: ../teacher/dashboard.php");
            } else {
                header("Location: ../student/dashboard.php");
            }
            exit();

        } else {
            $error = "Incorrect password.";
        }

    } else {
        $error = "Email not found.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Online Quiz System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --bg: #f1f5f9;
            --text: #1e293b;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
            gap: 2em;
        }

        .login-container {
            background: #ffffff;
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h1 {
            font-size: 1.75rem;
            color: var(--primary);
            margin: 0;
        }

        .login-header p {
            color: #64748b;
            margin-top: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text);
        }

        input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            box-sizing: border-box;
            transition: 0.3s;
        }

        input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }

        .btn-login {
            width: 100%;
            background: var(--primary);
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-login:hover {
            background: #374fc7;
        }

        .error-msg {
            background: #fee2e2;
            color: #b91c1c;
            padding: 0.75rem;
            border-radius: 8px;
            font-size: 0.85rem;
            margin-bottom: 1rem;
            text-align: center;
        }

        .footer-links {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: #64748b;
        }

        .footer-links a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .logo{
            font-size: 3em;
            text-decoration: none;
            color: blue;
            font-weight: 2em;
        }
        .logo:hover{
            color: red;
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
  --bg-gradient: linear-gradient(-45deg, #6bc3e9, #203a43, #3ba9d8, #1c1c1c);
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

    <div class="login-container">
        <div class="login-header">
            <h1>Welcome Back</h1>
            <p>Please enter your details</p>
        </div>

        <?php if ($error): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" placeholder="admin@quiz.com" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-login">Sign In</button>
        </form>

        <div class="footer-links">
            <p>Are you a student? <a href="register.php">Create an account</a></p>
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