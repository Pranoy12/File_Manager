<?php
    session_start();

    //initializing variables
    $username="";
    $email="";
    $password="";
    $pass_confirm="";
    $pass="";
    $errors = array();

    //connecting to database
    $conn = new mysqli('localhost','root','','File_Manager');

    //checking if connection succesfull
    if($conn -> connect_error)
    {
        die("Connection Error");
    }

    //Registering user
    if(isset($_POST['reg']))    //checking if form is submitted
    {
        $username = $_POST['user'];
        $email = $_POST['email'];
        $password = $_POST['pass'];
        $pass_confirm = $_POST['confirm_pass'];

        //form validation
        if(empty($username))
        {
            array_push($errors,"Username Required!");
        }
        if(empty($email))
        {
            array_push($errors,"Email Required!");
        }
        if(empty($password))
        {
            array_push($errors,"Password Required!");
        }
        if(empty($pass_confirm))
        {
            array_push($errors,"Confirm Password Required!");
        }
        if($password != $pass_confirm)
        {
            array_push($errors,"Passwords do not match!");
        }

        //checking if user already exits
        $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
        $user_check_result = $conn ->query($user_check_query);
        if($user_check_result ->num_rows > 0)
        {
            while($row = $user_check_result ->fetch_assoc())
            {
                if($row['username'] == $username)
                {
                    array_push($errors,"Username already exists");
                }
                if($row['email'] == $email)
                {
                    array_push($errors,"Email already exists");
                }
            }
        }

        //If no errors , Registering User
        if(count($errors)==0)
        {
            $pass = md5($password); //encrypting password
            $reg_query = "INSERT INTO users (username,email,pass) VALUES ('$username','$email','$pass')";
            $reg_query_result = $conn ->query($reg_query);
            $in_query = "SELECT * FROM users WHERE username='$username' AND pass='$pass'";
            $in_query_result = $conn ->query($in_query);
            while($row = $in_query_result ->fetch_assoc())
                {
                    $id = $row['id'];
                }
            $_SESSION['userid']=$id;
            //initializing session variables
            $_SESSION['username'] = $username;
            $_SESSION['success'] = "You are now logged in";
            header('location: filemanager.php');
        }
        else
        {
            print_r($errors);
        }
    }


    //LOGGING IN USER
    if(isset($_POST['login']))
    {
        $username=$_POST['user'];
        $pass=$_POST['pass'];

        if(empty($username))
        {
            array_push($errors,"Username Required!");
        }
        if(empty($pass))
        {
            array_push($errors,"Password Required!");
        }

        if(count($errors)==0)
        {
            $password=md5($pass);
            // print($password);
            $login_qeury = "SELECT * FROM users WHERE username='$username' AND pass='$password'";
            // print($login_qeury);
            $login_qeury_result = $conn ->query($login_qeury);
            if($login_qeury_result ->num_rows == 1)
            {
                $_SESSION['username']=$username;
                $_SESSION['success']="You are now logged in";
                while($row = $login_qeury_result ->fetch_assoc())
                {
                    $id = $row['id'];
                }
                $_SESSION['userid']=$id;
                header('location: filemanager.php');
            }
            else
            {
                array_push($errors,"Wrong username/password");
                print_r($errors);
            }
        }
        else
        {
            print_r($errors);
        }
    }
?>