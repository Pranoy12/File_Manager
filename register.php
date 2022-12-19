
<!DOCTYPE html>
<html lang="en">
<head>
   
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <style type="text/css" >
        .form-signup{
        margin: 0 auto;
        max-width: 400px;
        text-align: left;
        padding-top: 100px;
        }
        body{
            
            background:url('/bg2.jpg');
            background-size: cover;
        }
        input {
            float:right;
            display:inline-block;
        }
        h1{
          text-color: black;  
        }
        .register_button{
            background-color: lightblue;
        }
        </style>
</head>
<body>
    <form action="register.php" method="post" class="form-signup">
        <h1>REGISTER</h1>
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
        <input type="submit" name="reg" value="REGISTER" class="register_button">
        <p> Already a member? <a href="login.php">Login</a>
    </form>
</body>
</html>