<?php
require_once "./core/userDB.php";
require_once "./core/utilities.php";
include_once "./includes/head.php";

forbidAccesNonLogged();

include_once "./includes/topnav.php";

$user = new User();
$me= $user->selectDataGeneric('users',array('userId'),array($_SESSION['uID']))[0]; 
$chatPartner;

if (isset($_POST["messageBody"]) || isset($_POST["sendMess"])) {

    $senderId = $_SESSION['uID'];
    $receiverId = $_POST['receiverId'];
    $messageBody = $_POST['messageBody'];
    $messageBody=strip_tags($messageBody);
    $_GET['userId']=$receiverId;

    $cols=array('senderId','recieverId','body');
    $values=array($senderId,$receiverId,$messageBody);

    $user->insertDataGeneric( $cols, $values,'messages');
}

if (isset($_GET['userId'])) {
    $targetUserId = $_GET['userId'];
    $user->markMessageAsRead($_SESSION['uID'], $targetUserId);
}


$usersData = $user->showOtherUsersTest($_SESSION['uID'],$_SESSION['userType']);
var_dump($usersData);

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
            <div class='row'>
                <div class='chat-container-image'>
                    <img src="./uploads/<?= $me['profileImagePath']?>" alt='User Icon'>
                </div>
                <div class='chat-container-username'>
                    <h2><?= $me['firstName']." ".$me['lastName'] ?></h2>
                </div>
            </div>
            <form method="post" action="<?=$_SERVER['PHP_SELF']?>" class="topNav-search">
                <div class="search-input">
                    <input type="text" name="searchUser" placeholder="Pretrazite korisnika..." class="textField">
                    <i class="fas fa-search"></i>
                </div>
                    <input type="submit" name="performSearch" value="Pretrazi">
            </form>
            <?php
                if (isset($_POST['performSearch']))
                {
                    $searchString=$_POST['searchUser'];
                    $usersData=$user->searchForChatUsers($searchString,$_SESSION['uID'],$_SESSION['userType']);
                }
                displayDmUsers($me,$usersData,$user);
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
                        <textarea name='messageBody' id='messageArea' placeholder='Napisite poruku...' class='textField'></textarea>
                        <input type='submit' name='sendMess' value='Posalji' class="button">
                    </div>
                </form>

            <?php    
                } 
            ?>
        </div>
    </div>
</div>
<script>

    function scrollToBottom() {
        window.scrollTo(0, document.body.scrollHeight);
    }

    window.onload = function() {
        scrollToBottom();
    };

    document.getElementById("messageArea").addEventListener("keydown", function(event) {
        if (event.key === "Enter" && event.shiftKey) {

        } else if (event.key === "Enter") {

            event.preventDefault();

            document.querySelector(".message-form").submit();
        }
    });
</script>
</body>
</html>