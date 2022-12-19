<?php
    session_start();

    if(!isset($_SESSION['username']))
    {
        $_SESSION['msg'] = 'You must login first';
        header('location : login.php');
    }
    if(isset($_GET['logout']))
    {
        session_destroy();
        unset($_SESSION['username']);
        header('location:login.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME PAGE</title>
</head>
<body>
    <h1> WELCOME <?php
        echo $_SESSION['username'];
        ?>  
    </h1>
    <?php echo $_SESSION['success'];
        ?>
    <br>
    <br>
    <br>
    <form action="logout.php" action="get">
        <input type="submit" value="LOGOUT" name="logout">
    </form>
    <!-- <button action="logout.php" method="get" name="logout">LOGOUT</button> -->
</body>
</html>