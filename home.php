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
<style>
    * {
            margin: 0px;
            padding: 0px;
        }
        body {
            background: #1a9eab;
            position: relative;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            font-family: sans-serif;
        }
        body::before {
            content: "";
            position: absolute;
            top: -50%;
            left: 0;
            background: url(https://rvs-profile-card-component-main.netlify.app/images/bg-pattern-top.svg);
            background-size: contain;
            background-repeat: no-repeat;
            width: 100%;
            height: 100%;
        }
        body::after {
            content: "";
            position: absolute;
            bottom: -65%;
            right: -52%;
            background: url(https://rvs-profile-card-component-main.netlify.app/images/bg-pattern-bottom.svg);
            background-size: contain;
            background-repeat: no-repeat;
            width: 100%;
            height: 120%;
        }
        .container {
            width: 310px;
            height: 400px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border-radius: 10px;
            z-index: 2;
            overflow: hidden;
        }
        .card {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .card .card-header {
            background: url(https://rvs-profile-card-component-main.netlify.app/images/bg-pattern-card.svg);
            background-size: cover;
            background-repeat: no-repeat;
            height: 40%;
        }
        .card .card-body {
            height: 40%;
            position: relative;
            width: 100%;
            border-bottom: 1px solid #80808042;
        }
        .card .card-body::before {
            content: "";
            position: absolute;
            top: -40px;
            left: 50%;
            background: white;
            transform: translate(-50%, 0);
            background-size: cover;
            background-repeat: no-repeat;
            width: 80px;
            height: 80px;
            border-radius: 50%;
        }
        .card .card-body::after {
            content: "";
            position: absolute;
            top: -35px;
            left: 50%;
            background: url(./user-solid.svg) white;
            transform: translate(-50%, 0);
            background-size: cover;
            background-repeat: no-repeat;
            width: 70px;
            height: 70px;
            border-radius: 50%;
        }
        .card .card-body .inner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, 0);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .card .card-footer {
            height: 20%;
            display: flex;
            flex-direction: row;
            justify-content: space-around;
            align-items: center;
        }
        .card .card-footer .inner {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .card .card-footer .inner div:first-child {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .card .card-footer .inner div:last-child {
            font-size: 10px;
            letter-spacing: 2px;
        }

        .color__gray {
            color: gray;
        }

        @media only screen and (max-width: 568px) {
            body::before {
                top: -25%;
                left: -60%;
                width: 120%;
            }
            body::after {
                bottom: -85%;
                right: -60%;
                width: 120%;
            }
        }
</style>
<body>
<div class="container">
        <div class="card">
            <div class="card-header"></div>
            <div class="card-body">
                <div class="inner">
                    <div style="font-size: 18px;letter-spacing: .5px;margin-bottom: 10px;"><?php
        echo $_SESSION['username'];
        ?>  </div>
                    <div class="color__gray" style="font-size: 13px;letter-spacing: .5px;">India</div>
                </div>
            </div>
            <div class="card-footer">
                <div class="inner">
                    <!-- <div>80K</div> -->
                    <div class="color__gray"><a href="#">About</a></div>
                </div>
                <div class="inner">
                    <!-- <div>803K</div> -->
                    <div class="color__gray"><a href="#">Settings</a></div>
                </div>
                <div class="inner">
                    <!-- <div>1.4K</div> -->
                    <div class="color__gray"><a href="logout.php">Logout</a></div>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- <h1> WELCOME 
        <?php
        // echo $_SESSION['username'];
        ?>  
    </h1>   
    <?php 
    // echo $_SESSION['success'];
        ?>
    <br>
    <br>
    <br>
    <form action="logout.php" action="get">
        <input type="submit" value="LOGOUT" name="logout">
    </form> -->
</body>
</html>