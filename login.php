<?php include('server.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>
<body>
    <form action="login.php" method="post">
        Name: <input type="text" name="user">
        <br>
        <br>
        <br>
        Password: <input type="password" name="pass">
        <br>
        <br>
        <br>
        <input type="submit" value="LOGIN" name="login">
    </form>
</body>
</html>