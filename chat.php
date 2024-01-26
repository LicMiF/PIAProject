<?php
require_once "./core/userDB.php";
require_once "./core/utilities.php";
include_once "./includes/head.php";

forbidAccesNonLogged();

include_once "./includes/topnav.php";

$user = new User();
$me= $user->selectDataGeneric('users',array('userId'),array($_SESSION['uID']))[0]; 
$chatPartner;

if (isset($_POST["sendMess"])) {

    $senderId = $_SESSION['uID'];
    $receiverId = $_POST['receiverId'];
    $messageBody = $_POST['messageBody'];

    $_GET['userId']=$receiverId;

    $cols=array('senderId','recieverId','body');
    $values=array($senderId,$receiverId,$messageBody);

    $user->insertDataGeneric( $cols, $values,'messages');
}

if (isset($_GET['userId'])) {
    $targetUserId = $_GET['userId'];
    $user->markMessageAsRead($_SESSION['uID'], $targetUserId);
}

if (isset($_POST["pretrazi"])) {
    $username = $_POST['searchUser'];
    $chatPartner = $user->selectDataGeneric('users',array('username'),array($username))[0];

    if ($chatPartner && $chatPartner['userId'] != $_SESSION['uID']) {
        $targetUserId = $chatPartner['userId'];
        echo "<script>window.location.href = '?userId=$targetUserId';</script>";
        exit();
    }
}

$usersData = $user->showOtherUsers($_SESSION['uID']);

if (!is_array($usersData)) {
    $usersData = array();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
</head>
<body>
<div class='row'>
    <div class="users-container">
        <div class="card">
            <form method="post" action="">
                <input type="text" name="searchUser" placeholder="Pretrazite korisnika..." class="textField">
                <input type="submit" name="pretrazi" value="Pretrazi">
            </form>
            <?php
                displayDmUsers($usersData,$user);
            ?>
        </div>
    </div>
    <div class="chat-container">    
        <div class="card">
            <?php
            if (isset($targetUserId)) {
                displayUserDm($targetUserId,$user);
            ?>
                <form action='<?=$_SERVER["PHP_SELF"]?>' method='post' class="message-form">
                    <input type='hidden' name='receiverId' value='<?php echo $targetUserId; ?>'>
                    <div class='row'>
                        <textarea name='messageBody' placeholder='Napisite poruku...' class='textField'></textarea>
                        <input type='submit' name='sendMess' value='Posalji' class="button">
                    </div>
                </form>

            <?php    
                } 
            ?>
        </div>
    </div>
</div>

</body>
</html>