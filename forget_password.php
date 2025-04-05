<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $security_question = $_POST['security_question'];
    $security_answer = strtolower(trim($_POST['security_answer'])); // Normalize case
    $new_password = $_POST['new_password'];

    // Check if the user exists and validate the security question and answer
    $sql = "SELECT security_question, security_answer FROM users WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($stored_question, $stored_answer);
        $stmt->fetch();

        // Compare security question and answer
        if ($security_question === $stored_question && strtolower($stored_answer) === $security_answer) {
            // Update the password
            $update_sql = "UPDATE users SET password=? WHERE email=?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ss", $new_password, $email);

            if ($update_stmt->execute()) {
                echo "<p style='color: green; text-align: center;'>Password reset successful!</p>";
            } else {
                echo "<p style='color: red; text-align: center;'>Error updating password. Please try again later.</p>";
            }
            $update_stmt->close();
        } else {
            echo "<p style='color: red; text-align: center;'>Incorrect security question or answer.</p>";
        }
    } else {
        echo "<p style='color: red; text-align: center;'>User not found. Please check your email.</p>";
    }
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
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
            max-width: 500px;
            width: 100%;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            padding: 40px;
            text-align: center;
        }
        .title {
            font-size: 26px;
            font-weight: 600;
            color: #333;
            margin-bottom: 25px;
        }
        .row {
            margin-bottom: 20px;
            position: relative;
        }
        .row input, .row select {
            width: 100%;
            padding: 12px 15px 12px 40px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .row input:focus, .row select:focus {
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
        }
    </style>
    <script>
        function validatePasswords() {
            const newPassword = document.getElementById("newPassword").value;
            const confirmPassword = document.getElementById("confirmPassword").value;

            if (newPassword !== confirmPassword) {
                alert("Passwords do not match. Please try again.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="wrapper">
        <div class="title">Forgot Password</div>
        <form action="" method="POST" onsubmit="return validatePasswords()">
            <div class="row">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Email ID" value="<?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>" required>
            </div>
            <div class="row">
                <i class="fas fa-question-circle"></i>
                <select name="security_question" required>
                    <option value="" disabled selected>Select your security question</option>
                    <option value="What is your favorite color?">What is your favorite color?</option>
                    <option value="What was the name of your first pet?">What was the name of your first pet?</option>
                </select>
            </div>
            <div class="row">
                <i class="fas fa-question-circle"></i>
                <input type="text" name="security_answer" placeholder="Answer" required>
            </div>
            <div class="row">
                <i class="fas fa-lock"></i>
                <input type="password" id="newPassword" name="new_password" placeholder="New Password" required>
            </div>
            <div class="row">
                <i class="fas fa-lock"></i>
                <input type="password" id="confirmPassword" placeholder="Retype New Password" required>
            </div>
            <div class="row button">
                <input type="submit" value="Reset Password">
            </div>
        </form>
    </div>
</body>
</html>