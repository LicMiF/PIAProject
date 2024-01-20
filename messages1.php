
<?php
    require_once "./core/userDB.php";
    require_once "./core/utilities.php";
?>

<html>
    <head>
        <title>Chat</title>
        <link rel="stylesheet" href="css/styleMes.css">
    </head>
    <body>
        <div class="container main-section">
            <div class="row">
                <div class="col-md-3 col-sm-3 cols-xs-12 left-side">
                    <div class="input-group searchbox">
                        <div class="input-group-btn">
                            <center><a href="include/find_friends.php"><button class="btn btn-default search-icon" name="search_user" type="submit">Add new user</button></a></center>
                        </div>
                    </div>
                    <div class="left-chat">
                        <ul>
                            <?php include("includes/get_users_data.php"); ?>
                        </ul>
                    </div>
                </div>
                <div class="col-md-9 col-sm-9 col-xs-12 right-sidebar">
                    <div class="row">
                        <!--getting the user information who is logged in -->
                        <?php
                            $user=$_SESSION['mail'];
                            $get_user="SELECT * FROM users WHERE mail='$user'";
                            $run_user=mysqli_query($conn,$get_user);
                            $row=mysqli_fetch_array($run_user);

                            $user_id=$row['userId'];
                            $user_name=$row['username'];
                        ?>

                        <?php
                            if(isset($_POST['user_name'])){
                                global $con;

                                $get_username=$_GET['user_name'];
                                $get_user="SELECT * FROM users WHERE user_name='$get_username'";
                                $run_user=mysqli_query($conn,$get_user);

                                $row_user=mysqli_fetch_array($run_user);

                                $username=$row_user['username'];

                            }

                            $total_messages="SELECT * FROM users_chats WHERE (sender_username='$user_name'
                            AND receiver_username='$username' OR (receiver_username='$user_name' 
                            AND sender_username='$username') ";
                            $run_messages=mysqli_query($conn,$total_messages);
                            $total=mysqli_num_rows($run_messages);
                        ?>
                        <div class="col-md-12 right-header">
                            <div class="right-header-img">
                                <!---profile picture-->
                            </div>
                            <div class="right-header-detail">
                                
                                <form method="post" >
                                    <p><?php echo "$username"; ?></p>
                                    <span><?php echo $total; ?>messages</span>&nbsp &nbsp
                                    <button name="logout" class="btn btn-danger">Logout</button>
                                </form>
                                <?php
                                    if(isset($_POST["logout"])){
                                        header("Location: logout.php");
                                        exit();
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div id="scrolling_to_bottom" class="col-md-12 right-header-contentChat">
                            <?php
                                $update_msg=mysqli_query($con,"UPDATE users_chat SET msg_status='read'
                                WHERE sender_username='$username' AND receiver_username='$user_name'");

                                $sel_msg="SELECT * FROM user_chats WHERE (sender_username='$user_name'
                                AND reveiver_username='$username') OR (receiver_username='$user_name'
                                AND sender_username='$username') ORDER BY 1 ASC";
                                $run_msg=mysqli_query($conn,$sel_msg); 

                                while( $row=mysqli_fetch_array($run_msg) ){
                                    $sender_username=$row['sender_username'];
                                    $receiver_username=$row['receiver_username'];
                                    $msg_content=$row['msg_content'];
                                    $msg_date=$row['msg_date'];
                                
                            ?>
                            <ul>
                                <?php
                                if($user_name==$sender_username AND $username==$receiver_username){
                                    echo "
                                        <li>
                                            <div class='rightside-chat'>
                                                <span>$username<small>$msg_date</small></span>
                                                <p>$msg_content</p>
                                            </div>
                                        </li>
                                    ";
                                }
                                else if($user_name==$receiver_username AND $username==$sender_username){
                                    echo "
                                        <li>
                                            <div class='rightside-chat'>
                                                <span>$username<small>$msg_date</small></span>
                                                <p>$msg_content</p>
                                            </div>
                                        </li>
                                    ";
                                }
                                ?>
                            </ul>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="coll-md-12 right-chat-textbox">
                            <form method="post">
                                <input type="text" name="msg_content" autocomplete="off"
                                placeholder="Write your message...">
                                <button class="btn" name="submit"><i class="fa fa-telegram" 
                                aria-hidden="true"></i></button>

                        </div>
                    </div>
                </div>
            </div>   
        </div> 
        <?php
            if(isset($_POST["submit"])){
                $msg=htmlentities($_POST["msg_content"]);
                if($msg==""){
                    echo "
                    <div class='alert alert-danger'>
                        <strong><center>Message was unable to send</center></strong>
                    </div>
                    ";
                }
                else if(strlen($msg)>100){
                    echo "
                        <div class='alert alert-danger'>
                            <strong><center>Message is too long</center></strong>
                        </div>
                    ";
                }
                else{
                    $insert="insert into users_chats(sender_username,receiver_username,msg_content,msg_status,msg_date) values('$user_name','$username','$msg','unread',NOW())";
                    $run_insert=mysqli_query($conn,$insert);
                    
                }
            }
        ?>
    </body>
</html> 