<?php
session_start(); 
require 'config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']); 
    if (!empty($email) && !empty($password)) {
        $sql = "SELECT id, email, password FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result && $result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if ($password === $user['password']) {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['email'] = $user['email'];

                    header("Location: MenuPage.php");
                    exit();
                } else {
                    $_SESSION['error'] = "Incorrect password. Please try again.";
                }
            } else {
                $_SESSION['error'] = "No account found with this email.";
            }
            $stmt->close();
        } else {
            $_SESSION['error'] = "Database query failed. Check your SQL syntax.";
        }
    } else {
        $_SESSION['error'] = "All fields are required.";
    }
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - La Frontera</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap");
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #FAD707, #F5A623);
            padding: 15px;
        }
        .wrapper {
            max-width: 980px;
            width: 100%;
            height: 500px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            overflow: hidden;
        }
        .image-container {
            flex: 1;
            background: url('la_frontera_image.jpg') no-repeat center center;
            background-size: cover;
        }
        .form-container {
            flex: 1;
            padding: 40px 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .title {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
        }
        .row {
            margin-bottom: 20px;
            position: relative;
            width: 100%;
        }
        .row input {
            width: 100%;
            padding: 12px 15px 12px 40px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .row input:focus {
            border-color: #FAD707;
            box-shadow: 0 0 5px rgba(245, 166, 35, 0.5);
        }
        .row i {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            color: #FAD707;
            font-size: 18px;
        }
        .pass, .signup-link {
            text-align: center;
            margin-bottom: 15px;
            width: 100%;
        }
        .pass a, .signup-link a {
            color: #FAD707;
            text-decoration: none;
            font-size: 14px;
        }
        .pass a:hover, .signup-link a:hover {
            text-decoration: underline;
        }
        .button input {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            background: #FAD707;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .button input:hover {
            background: #e6b800;
        }
        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Image Section -->
        <div class="image-container"></div>

        <!-- Form Section -->
        <div class="form-container">
            <div class="title">Login</div>
            <!-- Display Error Messages -->
            <?php if (isset($_SESSION['error'])): ?>
                <p class="error-message"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
            <?php endif; ?>
            <form action="login.php" method="POST">
                <div class="row">
                    <i class="fas fa-user"></i>
                    <input type="email" name="email" placeholder="Email ID" required />
                </div>
                <div class="row">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required />
                </div>
                <div class="pass"><a href="forget_password.php">Forgot Password?</a></div>
                <div class="row button">
                    <input type="submit" value="Login" name="login" />
                </div>
                <div class="signup-link">Don't have an account? <a href="signup.php">Signup now</a></div>
            </form>
        </div>
    </div>
</body>
</html>