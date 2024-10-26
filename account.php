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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($full_name) || empty($email) || empty($password) || empty($confirm_password)) {
        $alertType = 'error';
        $alertTitle = 'All Fields Required';
        $alertMessage = 'Please fill in all the required fields.';
    } else if (!preg_match("/^[a-zA-Z\s]+$/", $full_name)) {
        $alertType = 'error';
        $alertTitle = 'Invalid Name';
        $alertMessage = 'Name should contain only letters and spaces.';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $alertType = 'error';
        $alertTitle = 'Invalid Email';
        $alertMessage = 'Please enter a valid email address.';
    } else if ($password !== $confirm_password) {
        $alertType = 'error';
        $alertTitle = 'Password Mismatch';
        $alertMessage = 'Password and Confirm Password do not match.';
    } else if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        $alertType = 'error';
        $alertTitle = 'Invalid Password';
        $alertMessage = 'Password must contain at least one uppercase letter, one number, and one special character.';
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

      
        $stmt = $conn->prepare("UPDATE users SET full_name = ?, password = ? WHERE email = ?");
        if ($stmt) {
            $stmt->bind_param("sss", $full_name, $hashed_password, $email);
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
                    $mail->Subject = 'Account Update Successful';
                    $mail->Body = '<h1>Hello, ' . $full_name . '!</h1><p>Your account details have been successfully updated.</p>';

                    $mail->send();

                    $alertType = 'success';
                    $alertTitle = 'Update Successful';
                    $alertMessage = 'Your account has been updated successfully, and a confirmation email has been sent to your email address.';
                } catch (Exception $e) {
                    $alertType = 'success';
                    $alertTitle = 'Update Successful';
                    $alertMessage = 'Your account has been updated successfully, but the confirmation email could not be sent.';
                }
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
    <form action="" method="POST" autocomplete="off">
        <div class="input-container">
            <input type="text" name="full_name" placeholder="Full Name" required>
        </div>
        <div class="input-container">
            <input type="email" name="email" placeholder="Email" required>
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
