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
        *, ::after, ::before {
  box-sizing: border-box;
}
.p1{
    text-align: center;
}

body {
    background: rgb(71,145,149);
background: linear-gradient(90deg, rgba(71,145,149,1) 0%, rgba(22,57,69,1) 61%, rgba(18,9,41,1) 100%);
  color: #fff;
  font-family: monospace, serif;
  letter-spacing: 0.05em;
}

h1 {
  font-size: 23px;
}

.form {
  width: 300px;
  padding: 250px 15px 24px;
  margin: 0 auto;
}
.form .control {
  margin: 0 0 24px;
}
.form .control input {
  width: 100%;
  padding: 14px 16px;
  border: 0;
  background: transparent;
  color: #fff;
  font-family: monospace, serif;
  letter-spacing: 0.05em;
  font-size: 16px;
}
.form .control input:hover, .form .control input:focus {
  outline: none;
  border: 0;
}
.form .btn {
  width: 100%;
  display: block;
  padding: 14px 16px;
  background: transparent;
  outline: none;
  border: 0;
  color: #fff;
  letter-spacing: 0.1em;
  font-weight: bold;
  font-family: monospace;
  font-size: 16px;
}

.block-cube {
  position: relative;
}
.block-cube .bg-top {
  position: absolute;
  height: 10px;
  background: #020024;
  background: linear-gradient(90deg, #020024 0%, #340979 37%, #00d4ff 94%);
  bottom: 100%;
  left: 5px;
  right: -5px;
  transform: skew(-45deg, 0);
  margin: 0;
}
.block-cube .bg-top .bg-inner {
  bottom: 0;
}
.block-cube .bg {
  position: absolute;
  left: 0;
  top: 0;
  right: 0;
  bottom: 0;
  background: #020024;
  background: linear-gradient(90deg, #020024 0%, #340979 37%, #00d4ff 94%);
}
.block-cube .bg-right {
  position: absolute;
  background: #020024;
  background: #00d4ff;
  top: -5px;
  z-index: 0;
  bottom: 5px;
  width: 10px;
  left: 100%;
  transform: skew(0, -45deg);
}
.block-cube .bg-right .bg-inner {
  left: 0;
}
.block-cube .bg .bg-inner {
  transition: all 0.2s ease-in-out;
}
.block-cube .bg-inner {
  background: #212121;
  position: absolute;
  left: 2px;
  top: 2px;
  right: 2px;
  bottom: 2px;
}
.block-cube .text {
  position: relative;
  z-index: 2;
}
.block-cube.block-input input {
  position: relative;
  z-index: 2;
}
.block-cube.block-input input:focus ~ .bg-right .bg-inner, .block-cube.block-input input:focus ~ .bg-top .bg-inner, .block-cube.block-input input:focus ~ .bg-inner .bg-inner {
  top: 100%;
  background: rgba(255, 255, 255, 0.5);
}
.block-cube.block-input .bg-top,
.block-cube.block-input .bg-right,
.block-cube.block-input .bg {
  background: rgba(255, 255, 255, 0.5);
  transition: background 0.2s ease-in-out;
}
.block-cube.block-input .bg-right .bg-inner,
.block-cube.block-input .bg-top .bg-inner {
  transition: all 0.2s ease-in-out;
}
.block-cube.block-input:focus .bg-top,
.block-cube.block-input:focus .bg-right,
.block-cube.block-input:focus .bg, .block-cube.block-input:hover .bg-top,
.block-cube.block-input:hover .bg-right,
.block-cube.block-input:hover .bg {
  background: rgba(255, 255, 255, 0.8);
}
.block-cube.block-cube-hover:focus .bg .bg-inner, .block-cube.block-cube-hover:hover .bg .bg-inner {
  top: 100%;
}

.credits {
  position: fixed;
  left: 0;
  bottom: 0;
  padding: 15px 15px;
  width: 100%;
  z-index: 111;
}
.credits a {
  opacity: 0.6;
  color: #fff;
  font-size: 11px;
  text-decoration: none;
}
.credits a:hover {
  opacity: 1;
}
.head1{
    font-size:50px;
    padding-left:30px;
    background: #21CFCF;
background: linear-gradient(to right, #21CFCF 0%, #CF1512 100%);
-webkit-background-clip: text;
-webkit-text-fill-color: transparent;
}
        </style>
</head>
<body>
    <form action="login.php" method="post" autocomplete='off' class='form'>
    <div class='control'>
        <h1 class="head1">
        Sign In
        </h1>
    </div>
    <div class='control block-cube block-input'>
    <input name="user" placeholder='Username' type='text'>
    <div class='bg-top'>
      <div class='bg-inner'></div>
    </div>
    <div class='bg-right'>
      <div class='bg-inner'></div>
    </div>
    <div class='bg'>
      <div class='bg-inner'></div>
    </div>
  </div>
  <div class='control block-cube block-input'>
    <input name="pass" placeholder='Password' type='password'>
    <div class='bg-top'>
      <div class='bg-inner'></div>
    </div>
    <div class='bg-right'>
      <div class='bg-inner'></div>
    </div>
    <div class='bg'>
      <div class='bg-inner'></div>
    </div>
  </div>
  <button class='btn block-cube block-cube-hover' type='submit' name="login">
    <div class='bg-top'>
      <div class='bg-inner'></div>
    </div>
    <div class='bg-right'>
      <div class='bg-inner'></div>
    </div>
    <div class='bg'>
      <div class='bg-inner'></div>
    </div>
    <div class='text'>
      Log In
    </div>
  </button>
    </form>
    <p class="p1"> Not a member? <a href="register.php">Register</a>
</body>
</html>