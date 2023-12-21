<?php
    
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    $servername = "sql112.infinityfree.com";
    $username = "if0_35222739";
    $password = "orhd0KabLU";
    $dbname = "if0_35222739_ktechy";
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $encode_email = $_GET['user'];
    
    // Decode the email
    $decode_email = base64_decode($encode_email);
    
    // Prepared statement to get friend_accept list
    $query1 = "SELECT username, friend_accept FROM chat WHERE email=?";
    $stmt = $conn->prepare($query1);
    
    if ($stmt === false) {
        die("Error in SQL query: " . $conn->error);
    }
    
    $stmt->bind_param("s", $decode_email);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $friend_accepted_list = $row['friend_accept'];
        $self_user_name = $row['username'];
    }
    else {
        echo '<script>window.location.href = "./index.html";</script>';
    }
    // Explode friend_accept list to get individual usernames
    @$usernames = explode(",", $friend_accepted_list);
    
    
    // inserting post
    
    if(isset($_POST['post'])){
        
        $post_username = $self_user_name;
        $post_email = $decode_email;
        $post_tweet = $_POST['post_tweet'];
        
        $ins_query = "INSERT INTO post(p_username, p_email, tweet) values('$post_username', '$post_email', '$post_tweet')";
        
        $a = $conn->query($ins_query);
        if($a){
            echo"<script>alert('Successfully Posted an Update');</script>";
            echo '<script>window.location.href = "user_profile.php?user=' .$encode_email. '";</script>';
            //redirecting to avoid resubmission
        }
        else {
            echo"<script>alert('Some Error Occurred, Please try again later');</script>";
            echo '<script>window.location.href = "user_profile.php?user=' .$encode_email. '";</script>';
        }
        
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="user_profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/fontawesome.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/solid.min.css" rel="stylesheet">

</head>
<body>
    <div class="form-container">
        <h1 class="form-head">Profile</h1>
        
        <!-- Navigation bar -->
        <div class="nav-bar">
            <ul>
                <li class="user"><i class="fa fa-user"></i>&nbsp; &nbsp;<?php echo $decode_email; ?></li>
                <li class="friends"><a href="friends_page.php?user=<?php echo $encode_email; ?>">
                                    <i class="fas fa-user-friends"></i> Friends</a></li>
                <li class="logout"><a href="index.html"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

        
        <!-- Updates section -->
        <div class="updates">
            <div class="friend_updates">
                <h4>Latest Updates From Friends &nbsp;<i class="fas fa-bullhorn"></i></h4>
                <?php
                    // Fetch and display updates from friends, excluding the user's own posts
                    foreach ($usernames as $friendUsername) {
                        if ($friendUsername !== $self_user_name) {
                                $query2 = "SELECT p_username, tweet FROM post WHERE p_username=? ORDER BY post_id DESC";
                                $stmt2 = $conn->prepare($query2);
                        
                                if ($stmt2 === false) {
                                    die("Error in SQL query: " . $conn->error);
                                }
                        
                                $stmt2->bind_param("s", $friendUsername);
                                $stmt2->execute();
                                $result2 = $stmt2->get_result();
                        
                                if ($result2->num_rows > 0) {
                                    echo "<div class='update_lists'>";
                        
                                    // Display each friend's posts 
                                    while ($row2 = $result2->fetch_assoc()) {
                                        $username = $row2['p_username'];
                                        $tweet = $row2['tweet'];
                                        echo '<h5><i class="fas fa-globe"></i> Updates from '.$username.'</h5>';
                                        echo '<p class="tweets">'.$tweet.'</p>';
                                    }
                        
                                    echo "</div>";
                                }
                        
                                $stmt2->close();
                        }
                    }
                ?>
            </div>
            
            <!-- Post a new update section -->
            <div class="post_update">
                <h4>Post a new update &nbsp;<i class="fas fa-comment"></i></h4>
                <form method="post" action="">
                    <div class="inputs">
                        <textarea name="post_tweet" required onkeypress="restrictCharacters(event)" placeholder=""></textarea>
                        <button name="post">Post &nbsp;<i class="fas fa-share"></i></button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
    <script>
        function restrictCharacters(event) {
            // Get the character code for the pressed key
            var charCode = event.which || event.keyCode;

            // Check if the character is one of the restricted characters (`," or ')
            if (charCode === 96 || charCode === 34 || charCode === 39) {
                // Prevent the default action for these characters
                event.preventDefault();
            }
        }
    </script>
</body>
</html>

<?php
// Close the main statement and the database connection
    $stmt->close();
    $conn->close();
?>