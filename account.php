<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

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
$email = $_SESSION['email'];


$stmt = $conn->prepare("SELECT full_name, email, profile_image FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($full_name, $user_email, $profile_image);
$stmt->fetch();
$stmt->close();

function sendConfirmationEmail($email, $full_name) {
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
        $mail->isHTML(true);
        $mail->Subject = 'Your Account Has Been Successfully Updated!';
        $mail->Body = '
            <div style="font-family: Arial, sans-serif; text-align: center; color: #333;">
                <img src="./assets/img/logo-Photoroom.png" alt="Account Update Successful" style="width: 80px; height: 80px; margin-bottom: 20px;">
                <h1 style="color: #4CAF50;">Hello, ' . htmlspecialchars($full_name) . '!</h1>
                <p style="font-size: 16px; line-height: 1.6; text-align:justify;">
                    We wanted to let you know that your account details have been updated successfully. 
                    If you didn’t make this change, please contact our support team immediately.
                </p>
                <p style="font-size: 16px; color: #4CAF50;text-align:justify;">
                    Thank you for being with us!<br>
                    The Melody Hub Team
                </p>
            </div>';
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $profile_image = $_FILES['profile'];

    if (empty($full_name) || empty($email) || empty($password) || empty($confirm_password) || $profile_image['error'] != 0) {
        $alertType = 'error';
        $alertTitle = 'All Fields Required';
        $alertMessage = 'Please fill in all the required fields.';
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $full_name)) {
        $alertType = 'error';
        $alertTitle = 'Invalid Name';
        $alertMessage = 'Name should contain only letters and spaces.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $alertType = 'error';
        $alertTitle = 'Invalid Email';
        $alertMessage = 'Please enter a valid email address.';
    } elseif ($password !== $confirm_password) {
        $alertType = 'error';
        $alertTitle = 'Password Mismatch';
        $alertMessage = 'Password and Confirm Password do not match.';
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        $alertType = 'error';
        $alertTitle = 'Invalid Password';
        $alertMessage = 'Password must contain at least one uppercase letter, one number, and one special character.';
    } else {
        $upload_dir = 'uploads/';
        $file_extension = pathinfo($profile_image['name'], PATHINFO_EXTENSION);
        $allowed_extensions = ['jpeg', 'jpg'];
        $new_image_path = $upload_dir . uniqid() . '.' . $file_extension;

        if (in_array($file_extension, $allowed_extensions) && move_uploaded_file($profile_image['tmp_name'], $new_image_path)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("UPDATE users SET full_name = ?, password = ?, profile_image = ? WHERE email = ?");
            if ($stmt) {
                $stmt->bind_param("ssss", $full_name, $hashed_password, $new_image_path, $email);
                if ($stmt->execute()) {
                    $emailSent = sendConfirmationEmail($email, $full_name);
                    $alertType = 'success';
                    $alertTitle = 'Update Successful';
                    $alertMessage = 'Your account has been updated successfully.' . ($emailSent ? ' A confirmation email has been sent to your email address.' : ' However, the confirmation email could not be sent.');
                } else {
                    $alertType = 'error';
                    $alertTitle = 'Update Failed';
                    $alertMessage = 'An error occurred while updating. Please try again later.';
                }
                $stmt->close();
            } else {
                $alertType = 'error';
                $alertTitle = 'Update Failed';
                $alertMessage = 'An error occurred. Please try again later.';
            }
        } else {
            $alertType = 'error';
            $alertTitle = 'Invalid Image Format';
            $alertMessage = 'Please upload a valid image file (JPEG, JPG, or PNG).';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Account</title>
    <link rel="stylesheet" href="./assets/css/preloader.css">
    <link rel="stylesheet" href="./assets/css/account.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    
<div id="preloader" class="preloader">
    <div class="wave"><i class="fas fa-music"></i></div>
    <div class="wave"><i class="fas fa-music"></i></div>
    <div class="wave"><i class="fas fa-music"></i></div>
    <div class="wave"><i class="fas fa-music"></i></div>
</div>
    
<div class="form-container">
    <h1>Update Account Details</h1>
    <form action="" method="POST" autocomplete="off"  enctype="multipart/form-data">
        <div class="input-container">
            <img src="<?php echo htmlspecialchars($profile_image);?>" alt="" srcset="" loading="lazy" >
        </div>
        <br>
        <div class="input-container">
    <label for="profile" class="upload-label">Edit Profile</label>
    <input type="file" name="profile" id="profile" loading="lazy" accept="image/jpeg, image/jpg" onchange="validateImage()" required>
    <small id="image_error" style="color:red;"></small>
</div>


        <div class="input-container">
            <input type="text" name="full_name" placeholder="Full Name" value="<?php echo htmlspecialchars($full_name); ?>" required>
        </div>
        <div class="input-container">
            <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($user_email); ?>" readonly>
        </div>
        <div class="input-container">
            <input type="password" name="password" placeholder="New Password" id="password" required>
            <i class="fas fa-eye toggle-password" onclick="togglePassword('password', this)"></i>
        </div>
        <div class="input-container">
            <input type="password" name="confirm_password" placeholder="Confirm New Password" id="confirm_password" required onkeyup="checkPassword()">
            <i class="fas fa-eye toggle-password" onclick="togglePassword('confirm_password', this)"></i>
        </div>
        <small id="password_error" style="color:red;"></small>
        <button type="submit">Update</button>
    </form>     
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        document.getElementById('preloader').style.display = 'none';
        document.querySelector('.form-container').style.opacity = '1';
    }, 4000); 
});
</script>

<script>

function validateImage() {
    var fileInput = document.getElementById('profile');
    var filePath = fileInput.value;
    var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
    var imageError = document.getElementById('image_error');

    if (!allowedExtensions.exec(filePath)) {
        imageError.textContent = "Only JPG, JPEG, File Formats are allowed.";
        fileInput.value = ''; 
        return false;
    } else {
        imageError.textContent = ""; 
        return true;
    }
}


function checkPassword() {
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirm_password").value;
    var passwordError = document.getElementById("password_error");

    if (password && confirmPassword) {
        if (password !== confirmPassword) {
            passwordError.textContent = "Passwords do not match";
        } else {
            passwordError.textContent = "";
        }
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

document.addEventListener('DOMContentLoaded', function () {
    var alertType = "<?php echo $alertType; ?>";
    var alertTitle = "<?php echo $alertTitle; ?>";
    var alertMessage = "<?php echo $alertMessage; ?>";

    if (alertType) {
        Swal.fire({
            icon: alertType,
            title: alertTitle,
            text: alertMessage,
            showConfirmButton: false, 
            timer: 3000,
        }).then((result) => {
        
            <?php if ($alertType === 'success') : ?>
                window.location.href = "login.php";
            <?php endif; ?>
        });
    }
});
</script>

</body>
</html>
