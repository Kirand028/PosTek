<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    $servername = "YourServerName";
    $username = "YourUserName";
    $password = "YourPassword";
    $dbname = "YourDbName";
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $encode_email = $_GET['user'];
    
    // Decode the email
    $decode_email = base64_decode($encode_email);
    
    //To fetch self username 
    $fetch_username = "select username from chat where email='$decode_email'";
    $rslt_username = $conn->query($fetch_username);
    
    if($rslt_username->num_rows > 0) {    
        $r_username = $rslt_username->fetch_assoc();
        $self_userNameToAdd = $r_username['username'];
    }
    else {
        echo '<script>window.location.href = "./index.html";</script>';
    }
    // If he accepts his friend request
    if(isset($_POST['yes'])){
        
        $self_email = $decode_email;
        $requested_friend_username = $_POST['yes'];
         
        $fetch_query1 = "select friend_accept from chat where email='$self_email'";
        $fetch_rslt1 = $conn->query($fetch_query1);
        $acceptedFriends_list = "";
        
        $r1 = $fetch_rslt1->fetch_assoc();
        $acceptedFriends_list = $r1['friend_accept'];
        
        $fetch_query2 = "select friend_accept from chat where username='$requested_friend_username'";
        $fetch_rslt2 = $conn->query($fetch_query2);
        $acceptedFriends_list_rf = "";
        
        $r2 = $fetch_rslt2->fetch_assoc();
        $acceptedFriends_list_rf = $r2['friend_accept'];
        
        if($acceptedFriends_list != '') {
            $acceptedFriends_list = $acceptedFriends_list . "," . $requested_friend_username;
            
            // echo '<script>alert("'.$acceptedFriends_list.'");</script>';    
        }
        else{
            $acceptedFriends_list = $requested_friend_username;
            
            // echo '<script>alert("'.$acceptedFriends_list.'");</script>';
        }
        if($acceptedFriends_list_rf != '') {
            $acceptedFriends_list_rf = $acceptedFriends_list_rf . ",". $self_userNameToAdd ;
        }
        else {
            $acceptedFriends_list_rf = $self_userNameToAdd;
        }
        //query to update friend accept after accepting for both user
        $update_query1 = "update chat set friend_accept='$acceptedFriends_list' where email='$self_email'";
        $a = $conn->query($update_query1);
        
        $update_query2 = "update chat set friend_accept='$acceptedFriends_list_rf' where username='$requested_friend_username'";
        
        $b = $conn->query($update_query2);
        
        
        $valueToRemove = $requested_friend_username; 
        
        $update_query3 = "UPDATE chat SET friend_request = REPLACE(friend_request, ?, '')
                  WHERE FIND_IN_SET(?, friend_request) and email='$self_email'";

        $stmt = $conn->prepare($update_query3);
        $stmt->bind_param("ss", $valueToRemove, $valueToRemove);
        $stmt->execute();
        $stmt->close();
        
        $fetch_query4 = "select friend_request from chat where email='$self_email'";
        $fetch_rslt4 = $conn->query($fetch_query4);
        $requestedFriends_list2 = "";
        
        $r4 = $fetch_rslt4->fetch_assoc();
        $requestedFriends_list2 = $r4['friend_request'];
        
        $friend_accept_cleaned = implode(',', array_filter(explode(',', trim($requestedFriends_list2, ','))));
        
        $update_query4 = "update chat set friend_request='$friend_accept_cleaned' where email='$self_email'";
        $d = $conn->query($update_query4);
        
        if($a && $b && $d) {
            echo '<script>window.location.href = "friends_page.php?user=' .$encode_email. '";</script>';
            echo '<h5 style="text-align:center;">Friend Request Accepted</h5>';
        }
    }
    elseif(isset($_POST['no'])){
        
        $requested_friend_username2 = $_POST['no'];
        
        $update_query5 = "UPDATE chat SET friend_request = REPLACE(friend_request, ?, '')
                  WHERE FIND_IN_SET(?, friend_request) and email='$decode_email'";

        $stmt5 = $conn->prepare($update_query5);
        $stmt5->bind_param("ss", $requested_friend_username2, $requested_friend_username2);
        $stmt5->execute();
        $stmt5->close();
        
        $fetch_query5 = "select friend_request from chat where email='$decode_email'";
        $fetch_rslt5 = $conn->query($fetch_query5);
        $requestedFriends_list5 = "";
        
        $r5 = $fetch_rslt5->fetch_assoc();
        $requestedFriends_list5 = $r5['friend_request'];
        
        $friend_accept_cleaned2 = implode(',', array_filter(explode(',', trim($requestedFriends_list5, ','))));
        
        $update_query5 = "update chat set friend_request='$friend_accept_cleaned2' where email='$decode_email'";
        $e = $conn->query($update_query5);
        
        if($e) {
            echo '<script>window.location.href = "friends_page.php?user=' .$encode_email. '";</script>';
            echo '<h5 style="text-align:center;">Friend Request Declined</h5>';
        }
    }
    elseif(isset($_POST['add'])){
        
        $user_NameToAdd = $_POST['add'];
        
        // Get the list of accepted friends
        $queryFriendRequest_list = "SELECT friend_request FROM chat WHERE username = '$user_NameToAdd'";
        $resultFriendRequest_list = $conn->query($queryFriendRequest_list);
                        
        if (!$resultFriendRequest_list) {
            die("Error: " . $conn->error);
        }
                        
        $rowFriendRequest_list = $resultFriendRequest_list->fetch_assoc();
        $friendRequest_list = $rowFriendRequest_list['friend_request'];
        
        if(!empty($friendRequest_list) && $friendRequest_list[0] !== '') {
            $friendRequest_list = $friendRequest_list . "," . $self_userNameToAdd;
        } else {
            $friendRequest_list = $self_userNameToAdd;
        }
        
        $u_query = "UPDATE chat SET friend_request='$friendRequest_list' where username='$user_NameToAdd'";
        $result_updateRequest_list = $conn->query($u_query);
                        
        if (!$result_updateRequest_list) {
            die("Error: " . $conn->error); 
        } else {
            // echo '<script>alert("Friend request sent to ' . $user_NameToAdd . '");</script>';
            echo '<script>window.location.href = "friends_page.php?user=' .$encode_email. '";</script>';
            echo '<h5 style="text-align:center;">Friend Request Sent</h5>';
        }
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friends</title>
    <link rel="stylesheet" href="friends_page.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/fontawesome.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/solid.min.css" rel="stylesheet">
    
</head>
<body>
    <div class="form-container">
        <h1 class="form-head">Friends</h1>
        
        <!-- Navigation bar -->
        <div class="nav-bar">
            <ul>
                <li class="user"><a href="user_profile.php?user=<?php echo $encode_email;?>"><i class="fa fa-user"></i>&nbsp; &nbsp;<?php echo $self_userNameToAdd; ?></a></li>
                <li class="friends"><i class="fas fa-user-friends"></i> Friends</li>
                <li class="logout"><a href="index.html"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        <form method="POST" action="">
            <div class="main_container">
                <!--requested friends list section-->
                <div class="friend_requests">
                    <h5>Friend Requests</h5>
                    
                    <?php
                        // Requested friends list
                        $query1 = "SELECT friend_request FROM chat WHERE email='$decode_email'";
                        $rslt1 = $conn->query($query1);
                    
                        if ($rslt1->num_rows > 0) {
                            $row1 = $rslt1->fetch_assoc();
                            @$friendRequestList = explode(",", $row1["friend_request"]);
                    
                            if (!empty($friendRequestList) && $friendRequestList[0] !== '') {
                                foreach ($friendRequestList as $friend_requests) {
                                    echo '<div class="friend_requests_list">';
                                    echo '<input type="text" value="' . $friend_requests . '" readonly>';
                                    echo '<button name="yes" value="' . $friend_requests . '">Yes</button>';
                                    echo '<button name="no" value="' . $friend_requests . '">No</button>';
                                    echo '</div>';
                                }
                            } else {
                                echo '<h5>No Requests from friends</h5>';
                            }
                        }
                    ?>

                    
                </div>

                <!--Accepted friends list section-->
                <div class="friend_accepts">
                    <h5>Friends</h5>
                    
                    <div class="friend_accepts_list">
                        <?php
                            // Accepted friends list
                            $query2 = "SELECT friend_accept FROM chat WHERE email='$decode_email'";
                            $rslt2 = $conn->query($query2);
                            
                            if ($rslt2->num_rows > 0) {
                                $row2 = $rslt2->fetch_assoc();
                                @$friendAcceptedList = explode(",", $row2["friend_accept"]);
                                
                                if (!empty($friendAcceptedList) && $friendAcceptedList[0] !== '') {
                                    foreach ($friendAcceptedList as $friend) {
                                        echo '<input type="text" value="' . $friend . '" readonly>';
                                    }
                                } else {
                                    echo '<h5>No friends, Send request now</h5>';
                                }
                            }
                        ?>
                    </div>
                    
                </div>

                <!--All users list section-->
                <div class="all_users">
                    <h5>All Users</h5>
                    
                    <?php
                        // To display all users excepts his accepted friends
                        
                        // Get the list of accepted friends
                        $queryFriendAccept = "SELECT friend_accept FROM chat WHERE email = '$decode_email'";
                        $resultFriendAccept = $conn->query($queryFriendAccept);
                        
                        if (!$resultFriendAccept) {
                            die("Error: " . $conn->error); // Check for SQL errors
                        }
                        
                        $rowFriendAccept = $resultFriendAccept->fetch_assoc();
                        @$friendAcceptList = explode(',', $rowFriendAccept['friend_accept']);
                        
                        // Generate the query excluding accepted friends
                        $excludeList = "'" . implode("','", $friendAcceptList) . "'";
                        

                        $queryAllUsers = "SELECT username FROM chat WHERE username NOT IN ($excludeList) AND username<> '$self_userNameToAdd' AND username not in (select username from chat where FIND_IN_SET('$self_userNameToAdd', friend_request))";


                        $resultAllUsers = $conn->query($queryAllUsers);
                        
                        if (!$resultAllUsers) {
                            die("Error: " . $conn->error);
                        }
                        
                        if ($resultAllUsers->num_rows > 0) {
                            while ($rowAllUsers = $resultAllUsers->fetch_assoc()) {
                                echo '<div class="all_users_list">';
                                echo '<input type="text" value="' . $rowAllUsers["username"] . '" readonly>';
                                echo '<button name="add" value="' . $rowAllUsers["username"] . '">Add</button>';
                                echo '</div>';
                            }
                        } else {
                            echo "<h5>No users found</h5>";
                        }


                   ?>

                </div>

            </div>

        </form>
    
    </div>
</body>
</html>
