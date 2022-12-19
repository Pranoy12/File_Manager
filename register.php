<?php include('server.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
</head>
<body>
    <form action="register.php" method="post">
        User Name: <input type="text" name="user">
        <br>
        <br>
        <br>
        Email: <input type="email" name="email">
        <br>
        <br>
        <br>
        Password: <input type="password" name="pass">
        <br>
        <br>
        <br>
        Confirm Password: <input type="password" name="confirm_pass">
        <br>
        <br>
        <br>
        <input type="submit" name="reg" value="REGISTER">
        <p> Already a member? <a href="login.php">Login</a>
    </form>
</body>
</html>