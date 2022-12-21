<?php include('server.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <style type="text/css" >
        .form-login{
        margin: 0 auto;
        max-width: 400px;
        text-align: left;
        padding-top: 100px;
        }
        body{
            
            background:url('/bg1.jpg');
            background-size: cover;
        }
        input {
            float:right;
            display:inline-block;
        }
        h1{
          text-color: black;  
        }
        .login-button{
            background-color:lightblue;
        }
        </style>
</head>
<body>
    <form action="login.php" method="post" class="form-login">
        <h1>LOGIN</h1>
        Name: <input type="text" name="user">
        <br>
        <br>
        <br>
        Password: <input type="password" name="pass">
        <br>
        <br>
        <br>
        <input type="submit" value="LOGIN" name="login" class="login-button">
        <p> Not a member? <a href="register.php">Register</a>
    </form>
</body>
</html>