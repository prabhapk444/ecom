<?php
session_start();
include 'db.php'; 

require '././PHPMailer/src/Exception.php';
require '././PHPMailer/src/PHPMailer.php';
require '././PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$alertType = '';
$alertTitle = '';
$alertMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    if (empty($full_name) || empty($email) || empty($password) || empty($confirm_password)) {
        $alertType = 'error';
        $alertTitle = 'All Fields Required';
        $alertMessage = 'Please fill in all the required fields.';
    } else if ($password !== $confirm_password) {
        $alertType = 'error';
        $alertTitle = 'Password Mismatch';
        $alertMessage = 'Password and Confirm Password do not match.';
    } else if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        $alertType = 'error';
        $alertTitle = 'Invalid Password';
        $alertMessage = 'Password must contain at least one uppercase letter, one number, and one special character.';
    } else {
        $check_email_stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        if ($check_email_stmt) {
            $check_email_stmt->bind_param("s", $email);
            $check_email_stmt->execute();
            $result = $check_email_stmt->get_result();

            if ($result->num_rows > 0) {
                $alertType = 'error';
                $alertTitle = 'Email Exists';
                $alertMessage = 'Email already exists. Please use a different email.';
            } else {
               
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
                if ($stmt) {
                    $stmt->bind_param("sss", $full_name, $email, $hashed_password);
                    if ($stmt->execute()) {
                       
                        $mail = new PHPMailer(true);
                        try {
                           
                            $mail->isSMTP();
                            $mail->Host = 'smtp.gmail.com'; 
                            $mail->SMTPAuth = true;
                            $mail->Username = 'karanprabha22668@gmail.com'; 
                            $mail->Password = 'hrmq uoyw zory obcg';
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                            $mail->Port = 587;

                            $mail->setFrom('karanprabha22668@gmail.com', 'Admin');
                            $mail->addAddress($email, $full_name);

                        
                            $mail->isHTML(true);
                            $mail->Subject = 'Welcome to Melody Hub';
                            $mail->Body    = '<h1>Welcome to Our Website, ' . $full_name . '!</h1><p>Your account has been successfully created.</p>';

                            $mail->send();

                           
                            $alertType = 'success';
                            $alertTitle = 'Registration Successful';
                            $alertMessage = 'Your account has been created successfully, and a confirmation email has been sent to your email address.';
                        } catch (Exception $e) {
                            $alertType = 'success';
                            $alertTitle = 'Registration Successful';
                            $alertMessage = 'Your account has been created successfully, but the confirmation email could not be sent.';
                        }
                    } else {
                        $alertType = 'error';
                        $alertTitle = 'Registration Failed';
                        $alertMessage = 'An error occurred. Please try again later.';
                    }
                    $stmt->close();
                } else {
                    $alertType = 'error';
                    $alertTitle = 'Registration Failed';
                    $alertMessage = 'An error occurred. Please try again later.';
                }
            }
            $check_email_stmt->close();
        } else {
            $alertType = 'error';
            $alertTitle = 'Registration Failed';
            $alertMessage = 'An error occurred. Please try again later.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="./assets/css/style.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            font-family: 'Roboto', sans-serif;
        }
        body {
            display: flex;
            margin: 0;
            height: 100vh;
            font-family: Arial, sans-serif;
            color: black;
            overflow: auto; 
        }

        .left {
            flex: 1;
            background-image: url('./assets/img/Music.gif'); 
            background-size: cover;
            background-position: center;
        }

        .right {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(10px);
        }

        .form-container {
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 400px;
            box-sizing: border-box; 
            background: rgba(255, 255, 255, 0.8);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.6rem;
            color: black;
        }

        h1:hover {
            color: green;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 90%;
            padding: 10px;
            margin-bottom: 15px;
            border: 2px solid black;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.2);
            color: black; 
            transition: background 0.3s;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            background: rgba(255, 255, 255, 0.4);
            outline: none;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #1db954; 
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #1ed760; 
        }

        p {
            color: black;
            text-align: center;
        }

        @media (max-width: 992px) {
            body {
                flex-direction: column; 
                height: auto; 
            }
            .left {
                width: 90%;
            }

            .left, .right {
                height: auto; 
                min-height: 50vh; 
            }

            .form-container {
                padding: 20px;
                width: 90%;
                max-width: 350px; 
            }

            h1 {
                font-size: 1.4rem; 
            }

            input[type="text"],
            input[type="email"],
            input[type="password"],
            button {
                padding: 8px; 
            }
        }
    </style>
</head>
<body>
    <div class="left"></div>
    <div class="right">
        <div class="form-container">
            <h1>Create an Account</h1>
            <form action="" method="POST" autocomplete="off">
                <input type="text" name="full_name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" id="password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" id="confirm_password" required onkeyup="checkPassword()">
                <small id="password_error" style="color:red;"></small>
                <button type="submit">Register</button>
            </form>
            <p>Already have an account? <a href="login.php" style="color: #1db954;text-decoration:none;">Log in</a></p>
        </div>
    </div>

    <?php if (!empty($alertType) && !empty($alertTitle) && !empty($alertMessage)): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: '<?php echo $alertType; ?>',
                    title: '<?php echo addslashes($alertTitle); ?>',
                    text: '<?php echo addslashes($alertMessage); ?>',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    <?php if ($alertType === 'success') : ?>
                        window.location.href = "login.php";
                    <?php endif; ?>
                });
            });
        </script>
    <?php endif; ?>

    <script>
        function checkPassword() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirm_password").value;
            var passwordError = document.getElementById("password_error");

            if (password !== confirmPassword) {
                passwordError.textContent = "Passwords do not match";
            } else {
                passwordError.textContent = "";
            }
        }
    </script>
</body>
</html>
