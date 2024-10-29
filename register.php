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
    $profile_image = $_FILES['profile_image'];
    if (empty($full_name) || empty($email) || empty($password) || empty($confirm_password) || empty($profile_image['name'])) {
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
        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        $file_extension = strtolower(pathinfo($profile_image['name'], PATHINFO_EXTENSION));

        if (!in_array($file_extension, $allowed_extensions)) {
            $alertType = 'error';
            $alertTitle = 'Invalid Image Format';
            $alertMessage = 'Only JPG, JPEG, and PNG files are allowed.';
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
                    $upload_dir = 'uploads/';
                    $image_path = $upload_dir . uniqid() . '.' . $file_extension;

                    if (move_uploaded_file($profile_image['tmp_name'], $image_path)) {
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, profile_image) VALUES (?, ?, ?, ?)");
                        if ($stmt) {
                            $stmt->bind_param("ssss", $full_name, $email, $hashed_password, $image_path);
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
                                    $mail->Subject = 'Welcome to Melody Hub - Your Musical Journey Begins!';
                                    $mail->Body    = '
                                      <div style="font-family: Arial, sans-serif; text-align: center; color: #333;">
                                        <img src="./assets/img/logo-Photoroom.png" alt="Welcome to Melody Hub" style="width: 100%; max-width: 600px; height: auto; border-radius: 8px; margin-bottom: 20px;">
                                          <h1 style="color: #4CAF50;">Welcome to Melody Hub, ' . htmlspecialchars($full_name) . '!</h1>
                                            <p style="font-size: 16px; line-height: 1.6;text-align:justify;">
                                            We’re thrilled to have you join our community! Get ready to dive into a world of music, creativity, and inspiration.
                                            </p>
                                            <p style="font-size: 14px; color: #555;text-align:justify;">
                                            If you have any questions, feel free to reach out to us. Welcome aboard!
                                            </p>
                                            <p style="font-size: 16px; color: #4CAF50;text-align:justify;">
                                            Happy Listening!<br>
                                            The Melody Hub Team
                                            </p>
                                    </div>';


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
                    } else {
                        $alertType = 'error';
                        $alertTitle = 'Image Upload Failed';
                        $alertMessage = 'Failed to upload the profile image.';
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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="./assets/css/preloader.css">
    <link rel="stylesheet" href="./assets/css/register.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    
    <div id="preloader" class="preloader">
        <div class="wave"><i class="fas fa-music"></i></div>
        <div class="wave"><i class="fas fa-music"></i></div>
        <div class="wave"><i class="fas fa-music"></i></div>
        <div class="wave"><i class="fas fa-music"></i></div>
    </div>

    <div class="left"></div>
    <div class="right">
        <div class="form-container">
            <h1>Create an Account</h1>
            <form action="" method="POST" autocomplete="off" enctype="multipart/form-data">
                <div class="input-container">
                  <input type="text" name="full_name" placeholder="Full Name" required>
                </div>
                <div class="input-container">
                  <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-container">
                  <input type="password" name="password" placeholder="Password" id="password" required>
                  <i class="fas fa-eye toggle-password" onclick="togglePassword('password', this)"></i>
                </div>
                <div class="input-container">
                  <input type="password" name="confirm_password" placeholder="Confirm Password" id="confirm_password" required onkeyup="checkPassword()">
                  <i class="fas fa-eye toggle-password" onclick="togglePassword('confirm_password', this)"></i>
                </div>
                <div class="input-container">
                  <input type="file" name="profile_image" placeholde="Profile Picture"   accept=".jpg, .jpeg, .png" required>
                </div>
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
                    showConfirmButton: false, 
                    timer: 3000,
                }).then((result) => {
                    <?php if ($alertType === 'success') : ?>
                        window.location.href = "login.php";
                    <?php endif; ?>
                });
            });
        </script>
    <?php endif; ?>

    <script>

document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        document.getElementById('preloader').style.display = 'none';
        document.querySelector('.form-container').style.opacity = '1';
        document.querySelector('.left').style.opacity = '1';
    }, 4000);
});



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

        function togglePassword(fieldId, icon) {
            var field = document.getElementById(fieldId);
            if (field.type === "password") {
                field.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>

