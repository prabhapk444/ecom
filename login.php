<?php
session_start();
include 'db.php'; 

$alertType = '';
$alertTitle = '';
$alertMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT id, full_name, password FROM users WHERE email = ?");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id,$full_name, $hashed_password);
            $stmt->fetch();
            
            if (password_verify($password, $hashed_password)) {
                $_SESSION['email'] = $email;
                $_SESSION['username'] = $full_name;
                $_SESSION['id']=$id;
                $alertType = 'success';
                $alertTitle = 'Login Successful!';
                $alertMessage = 'You have been logged in successfully.';
            } else {
                $alertType = 'error';
                $alertTitle = 'Invalid Password';
                $alertMessage = 'The password you entered is incorrect. Please try again.';
            }
        } else {
            $alertType = 'error';
            $alertTitle = 'No Account Found';
            $alertMessage = 'No account exists with the provided email.';
        }

        $stmt->close();
    } else {
        $alertType = 'error';
        $alertTitle = 'Error';
        $alertMessage = 'An unexpected error occurred. Please try again later.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./assets/css/login.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <h1 class="main">Melody Hub</h1>
    <div class="left-panel"></div>
    <div class="right-panel">
        <div class="form-container">
            <h1>Login to Your Account</h1>
            <form action="" method="POST" autocomplete="off">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="register.php">Register</a></p>
        </div>
    </div>

    <?php if (!empty($alertType) && !empty($alertTitle) && !empty($alertMessage)): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: '<?php echo htmlspecialchars($alertType, ENT_QUOTES, 'UTF-8'); ?>',
                    title: '<?php echo htmlspecialchars($alertTitle, ENT_QUOTES, 'UTF-8'); ?>',
                    text: '<?php echo htmlspecialchars($alertMessage, ENT_QUOTES, 'UTF-8'); ?>',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    <?php if ($alertType === 'success'): ?>
                        window.location.href = 'home.php';
                    <?php endif; ?>
                });
            });
        </script>
    <?php endif; ?>
</body>
</html>
