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
    <title>Admin Login</title>
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
            opacity:0;
        }

        body {
            background-color:#fffffe;
        }

        .form {
            max-width: 320px;
            width: 100%;
            height:230px;
            padding: 20px;
            box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            border-radius: 10px;
            text-align: center;
            align-self:center;
            transition: background-color 0.5s ease-in-out, border 0.5s ease-in-out;
            border: 2px solid white;
        }

        .label {
            color: #2b2c34;
            margin-bottom: 4px;
            text-align:left;
            font-weight:bold;
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

        .submit:hover{
            background-color: #1ed760; 
        }

        .form p {
            color:#2b2c34; 
            margin-top: 10px; 
            font-weight:bold;
        }
        
        .main {
            font-size:1.4rem;
            text-align:center;
            opacity:0;
        }

        @media screen and (max-width: 992px) {
            .container {
                display:flex;
                flex-direction:column;
                margin-left:20px;
                width:80%;
            }

            img {
                width:100%;
            }

            .form {
                align-self:center;
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

     <h1 class="main">Admin Login page</h1>
    <div class="container">
        <img src="./../assets/img/Buffer.gif" alt="my" class="image"> 
        <form class="form" method="post" action="" autocomplete="off">
            <label for="username" class="label">Username</label>
            <input type="text" id="username" name="username" required class="input">
            <label for="password" class="label">Password</label>
            <input type="password" id="password" name="password" required class="input">
            <input type="submit" class="submit">
        </form>
    </div>

    <?php

    include("db.php");
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $username = $conn->real_escape_string($username);
        $password = $conn->real_escape_string($password);
        $sql = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'admin';
            echo "<script>
                Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Redirecting to dashboard...',
            timer: 1500,
            showConfirmButton: false
        });
        setTimeout(function() {
            window.location.href = 'dashboard.php';
        }, 1600);
            </script>";
            exit;
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Something Went Wrong',
                    text: 'Invalid username or password!'
                });
            </script>";
        }
    }
    ?>
</body>
<script>

    
    document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        document.getElementById('preloader').style.display = 'none';
        document.querySelector('.container').style.opacity = '1';
        document.querySelector('.main').style.opacity = '1';
    }, 4000); 
});

</script>

</html>
