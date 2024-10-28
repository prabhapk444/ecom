<?php
session_start();
session_regenerate_id(true);
include("db.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Credentials Update Page</title>
    <link rel="stylesheet" href="./../assets/css/preloader.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
    <style>
        * {
            font-family: 'Roboto', sans-serif;
        }
        .container {
            display: flex;
            justify-content: center;
            opacity: 0;
        }
        body {
            background-color: #fffffe;
        }
        .form {
            max-width: 320px;
            width: 100%;
            height: auto;
            padding: 20px;
            box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            border-radius: 10px;
            text-align: center;
            align-self: center;
            transition: background-color 0.5s ease-in-out, border 0.5s ease-in-out;
            border: 2px solid white;
        }
        .label {
            color: #2b2c34;
            margin-bottom: 4px;
            text-align: left;
            font-weight: bold;
        }
        .input {
            padding: 10px;
            margin-bottom: 20px;
            width: 90%;
            font-size: 1rem;
            color: #4a5568;
            outline: none;
            border: 1px solid transparent;
            border-radius: 4px;
            transition: all 0.2s ease-in-out;
        }
        .input:valid {
            border: 1px solid green;
        }
        .input:invalid {
            border: 1px solid rgba(14, 14, 14, 0.205);
        }
        .submit {
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
        .submit:hover {
            background-color: #1ed760;
        }
        .form p {
            color: #2b2c34;
            margin-top: 10px;
            font-weight: bold;
        }
        .main {
            font-size: 1.4rem;
            text-align: center;
            opacity: 0;
        }
        .toggle-password {
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 20px; 
            z-index: 1;
        }
        @media screen and (max-width: 992px) {
            .container {
                display: flex;
                flex-direction: column;
                margin-left: 20px;
                width: 80%;
            }
            img {
                width: 100%;
            }
            .form {
                align-self: center;
            }
        }
    </style>
</head>
<body>

    <div id="preloader" class="preloader">
        <div class="wave"><i class="fas fa-music"></i></div>
        <div class="wave"><i class="fas fa-music"></i></div>
        <div class="wave"><i class="fas fa-music"></i></div>
        <div class="wave"><i class="fas fa-music"></i></div>
    </div>

    <h1 class="main">Admin Credentials Update Page</h1>
    <div class="container">
        <img src="./../assets/img/Buffer.gif" alt="my" class="image"> 
        <form class="form" method="post" action="" autocomplete="off">
            <label for="username" class="label">Username</label>
            <input type="text" id="username" name="username" required class="input">
            <label for="new_password" class="label">New Password</label>
            <div style="position: relative;">
                <input type="password" id="new_password" name="new_password" required class="input">
                <i class="fas fa-eye toggle-password" id="togglePassword" onclick="togglePasswordVisibility()"></i>
            </div>
            <input type="submit" class="submit" value="Update Password">
        </form>
    </div>

    <?php
    include("db.php");
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $new_password = $_POST['new_password'];
        $username = $conn->real_escape_string($username);
        $new_password = $conn->real_escape_string($new_password);
    
        $check_sql = "SELECT * FROM admin WHERE username = '$username'";
        $check_result = $conn->query($check_sql);
    
        if ($check_result->num_rows > 0) {
            $sql = "UPDATE admin SET password = '$new_password' WHERE username = '$username'";
            if ($conn->query($sql) === TRUE) {
                echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Password Updated!',
                        text: 'Your password has been updated successfully.',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        window.location.href = 'dashboard.php';
                    });
                </script>";
            } else {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        text: 'An error occurred while updating the password.'
                    });
                </script>";
            }
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'User Not Found',
                    text: 'The specified username does not exist.'
                });
            </script>";
        }
    }
    ?>
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('new_password');
            const toggleIcon = document.getElementById('togglePassword');
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.getElementById('preloader').style.display = 'none';
                document.querySelector('.container').style.opacity = '1';
                document.querySelector('.main').style.opacity = '1';
            }, 4000); 
        });
    </script>
</body>
</html>
