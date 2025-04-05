<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - La Frontera</title>
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
            const password = document.getElementById("password").value;
            const retypePassword = document.getElementById("retype_password").value;

            if (password !== retypePassword) {
                alert("Passwords do not match. Please try again.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="wrapper">
        <div class="title">Signup</div>
        <form action="" method="POST" onsubmit="return validatePasswords()">
            <div class="row">
                <i class="fas fa-user"></i>
                <input type="text" name="username" placeholder="Username" required />
            </div>
            <div class="row">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Email ID" required />
            </div>
            <div class="row">
                <i class="fas fa-lock"></i>
                <input type="password" id="password" name="password" placeholder="Password" required />
            </div>
            <div class="row">
                <i class="fas fa-lock"></i>
                <input type="password" id="retype_password" name="retype_password" placeholder="Retype Password" required />
            </div>
            <div class="row">
                <i class="fas fa-question-circle"></i>
                <select name="security_question" required>
                    <option value="" disabled selected>Select your security question</option>
                    <option value="What is your favorite color?">What is your favorite color?</option>
                    <option value="What is your favorite movie?">What is your favorite movie?</option>
                    <option value="What is your mother's maiden name?">What is your mother's maiden name?</option>
                    <option value="What was the name of your first pet?">What was the name of your first pet?</option>
                    <option value="What was the name of your elementary school?">What was the name of your elementary school?</option>
                </select>
            </div>
            <div class="row">
                <i class="fas fa-question-circle"></i>
                <input type="text" name="security_answer" placeholder="Answer" required />
            </div>
            <div class="row button">
                <input type="submit" value="Signup" name="signup" />
            </div>
        </form>
    </div>
    <?php
    include 'config.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $retype_password = $_POST['retype_password'];
        $security_question = $_POST['security_question'];
        $security_answer = $_POST['security_answer']; 

        if ($password !== $retype_password) {
            echo "<script>alert('Passwords do not match');</script>";
        } else {
            $sql = "INSERT INTO users (username, email, password, security_question, security_answer) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            
            if ($stmt === false) {
                die("Error preparing statement: " . $conn->error);
            }
            
            $stmt->bind_param("sssss", $username, $email, $password, $security_question, $security_answer);

            if ($stmt->execute()) {
                echo "<script>alert('Signup successful!'); window.location='login.php';</script>";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        }
    }
    $conn->close();
    ?>
</body>
</html>