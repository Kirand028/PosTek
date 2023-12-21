<?php
    $servername = "YourServerName";
    $username = "YourUserName";
    $password = "YourPassword";
    $dbname = "YourDbName";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = $_POST['login-username'];
    $password = $_POST['login-password'];

    // Prepared statement to prevent SQL injection
    $e_sql = "SELECT email, password FROM chat WHERE email=?";
    $stmt = $conn->prepare($e_sql);

    if ($stmt === false) {
        die("Error in SQL query: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "<script>alert('This Username is not registered. Want to register?');</script>";
        echo "<script>window.location.href='./register.html';</script>";
    } else {
        $row = $result->fetch_assoc();
        $email = $row['email'];
        $pass = $row['password'];

        if ($email == $email && $pass == $password) {
            $encode_email = base64_encode($email);
            echo '<script>window.location.href="./user_profile.php?user='.$encode_email.'";</script>';
        } else {
            echo "<script>alert('Looks like wrong Email or Password');</script>";
            echo "<script>window.location.href='./index.html';</script>";
        }
    }

    $stmt->close();
    $conn->close();
?>
