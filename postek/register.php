<?php

    $servername = "YourServerName";
    $username = "YourUserName";
    $password = "YourPassword";
    $dbname = "YourDbName";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

        $name = $_POST['name'];
        $email = $_POST['email'];
        $pass = $_POST['pass'];
        $cpass = $_POST['cpass'];
    
        if(!($pass==$cpass)){
            echo"<script>alert('Both Password should match');</script>";
            echo"<script>window.location.href='./register.html';</script>";
        }
        //existence check
        $e_sql = "SELECT email,username FROM chat WHERE email='$email' or username='$name'";
    
        $rslt=$conn->query($e_sql);
        $num_rows = $rslt->num_rows;
        
        if($num_rows == 1){
            echo"<script>alert('Username is taken or Already Registered with this Email');</script>";
            echo"<script>window.location.href='./index.html';</script>";
        }
        else{
        
            $sql = "INSERT INTO chat (username,email, password) 
                    VALUES ('$name', '$email', '$pass')";
    
            if ($conn->query($sql)) {
                echo"<script>alert('Successfully Registered');</script>";
                echo"<script>window.location.href='./index.html';</script>";
            } 
            else {
                echo"<script>alert('Some Error Occurred, Please try again later');</script>";
                echo"<script>window.location.href='./register.html';</script>";
            }
        }
?>
