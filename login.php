<?php
session_start();
include 'db.php'; 

$alertType = '';
$alertTitle = '';
$alertMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT full_name, password FROM users WHERE email = ?");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($full_name, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['email'] = $email;
                $_SESSION['username'] = $full_name;
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
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            font-family: 'Roboto', sans-serif;
        }
        
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            height: 100vh;
            overflow: auto;
            background-color: #f0f0f0;
        }

        .main {
            text-align: center;
            color: black;
            font-size: 1.7rem;
            position: absolute;
            top: 20px; 
            width: 100%; 
            z-index: 1;
        }

        .left-panel {
            flex: 1;
            background-image: url('./assets/img/Lo-fi%20concept.gif'); 
            background-size: cover;
            background-position: center; 
            background-repeat: no-repeat; 
            min-height: 100vh; 
        }

        .right-panel {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.8); 
            padding: 20px; 
            flex-direction: column; 
        }

        .form-container {
            background: rgba(255, 255, 255, 0.9); 
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 400px;
            box-sizing: border-box; 
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

        input[type="email"],
        input[type="password"] {
            width: 100%; 
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            background: rgba(255, 255, 255, 0.2);
            color: black; 
            transition: background 0.3s;
            border-radius: 8px; 
        }

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
            text-align: center;
            color: black;
        }

        a {
            color: #1db954;
            text-decoration: none;
        }

        @media (max-width: 992px) {
            body {
                flex-direction: column; 
                height: auto; 
            }
            .left-panel {
                min-height: 50vh; 
                width: 100%;
            }
            .right-panel {
                min-height: 50vh; 
                padding: 20px 10px; 
            }
            .form-container {
                padding: 20px;
                width: 100%;
                max-width: 350px; 
            }
            h1 {
                font-size: 1.4rem; 
            }
            input[type="email"],
            input[type="password"],
            button {
                padding: 8px; 
            }
        }
    </style>
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
