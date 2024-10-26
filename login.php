<?php
session_start();
include 'db.php';


$alertType = '';
$alertTitle = '';
$alertMessage = '';
$maxAttempts = 3; 
$lockoutTime = 15 * 60; 


if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= $maxAttempts) {
    $remainingTime = $_SESSION['lockout_time'] - time();
    if ($remainingTime > 0) {
        $alertType = 'error';
        $alertTitle = 'Too Many Attempts';
        $alertMessage = 'You have been locked out for ' . ceil($remainingTime / 60) . ' minutes. Please try again later.';
    } else {
    
        unset($_SESSION['login_attempts']);
        unset($_SESSION['lockout_time']);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($alertMessage)) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $alertType = 'error';
        $alertTitle = 'Invalid Email';
        $alertMessage = 'Please enter a valid email address.';
    } else {
        $stmt = $conn->prepare("SELECT id, full_name, password FROM users WHERE email = ?");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id, $full_name, $hashed_password);
                $stmt->fetch();

                if (password_verify($password, $hashed_password)) {
                    
                    session_regenerate_id(true);
                    $_SESSION['loggedin'] = true;
                    $_SESSION['email'] = $email;
                    $_SESSION['username'] = $full_name;
                    $_SESSION['id'] = $id;
                    $_SESSION['login_attempts'] = 0; 
                    
                    $alertType = 'success';
                    $alertTitle = 'Login Successful!';
                    $alertMessage = 'You have been logged in successfully.';
                } else {
                    
                    $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
                    if ($_SESSION['login_attempts'] >= $maxAttempts) {
                        $_SESSION['lockout_time'] = time() + $lockoutTime; 
                        $alertType = 'error';
                        $alertTitle = 'Too Many Attempts';
                        $alertMessage = 'You have reached the maximum number of login attempts. Please try again later.';
                    } else {
                        $alertType = 'error';
                        $alertTitle = 'Invalid Password';
                        $alertMessage = 'The password you entered is incorrect. Please try again.';
                    }
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
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./assets/css/preloader.css">
    <link rel="stylesheet" href="./assets/css/login.css">
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
                    showConfirmButton: false, 
                    timer: 3000,
                }).then((result) => {
                    <?php if ($alertType === 'success'): ?>
                        window.location.href = 'home.php';
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
        document.querySelector('.left-panel').style.opacity = '1';
        document.querySelector('.main').style.opacity = '1';
    }, 4000); 
});

    </script>
</body>
</html>
