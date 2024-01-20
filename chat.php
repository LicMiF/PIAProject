<?php
require_once "./core/userDB.php";
require_once "./core/utilities.php";
include_once "./includes/head.php";

$user = new User();
$targetUserId = isset($_GET['userId']) ? $_GET['userId'] : null;
$me= $user->getUsernameById($_SESSION['uID']); 
$chatPartner;

if (isset($_POST["submit"])) {
    $senderId = $_SESSION['uID'];
    $receiverId = $_POST['receiverId'];
    $messageContent = $_POST['messageContent'];
    $timestamp = date('Y-m-d H:i:s');
    $status = "unread";
    $user->updateMessage($senderId, $receiverId, $messageContent, $status, $timestamp);
    header("Location: {$_SERVER['REQUEST_URI']}");
    exit();
}

if (isset($_POST["pretrazi"])) {
    $username = $_POST['searchUser'];
    $chatPartner = $user->getUserIdByUsername($username);

    if ($chatPartner && $chatPartner['userId'] != $_SESSION['uID']) {
        $targetUserId = $chatPartner['userId'];
        echo "<script>window.location.href = '?userId=$targetUserId';</script>";
        exit();
    }
}

$data = $user->showOtherUsers($_SESSION['uID']);

if (!is_array($data)) {
    $data = array();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="stylesheet" href="css/styleMes.css">
</head>
<body>
<div id="users-container">
    <?php echo $me['username'];?>
    <form method="post" action="">
        <input type="text" name="searchUser" placeholder="Pretrazite korisnika...">
        <input type="submit" name="pretrazi" value="Pretrazi">
    </form>
    <?php
    echo '<p><center>Korisnici</center></p>';
    if (!empty($data)) {
        echo '<div class="user-list">';
        foreach ($data as $userData) {
            echo '<a class="user-link" href="?userId=' . $userData['userId'] . '">' . $userData['username'] . '</a><br>';
        }
        echo '</div>';
    } else {
        echo "No other users found.";
    }
    echo '<a href="index.php">Vratite se na glavnu strani</a>';
    ?>
</div>

<div id="chat-container">
    <?php
    if (isset($targetUserId)) {
        $chatPartner= $user->getUsernameById($targetUserId); 
        echo "<h2> Razgovor sa ". $chatPartner["username"] ."</h2>";
        $myMessages = $user->showMyMessages($_SESSION['uID'], $targetUserId);
        $yourMessages = $user->showYourMessages($_SESSION["uID"], $targetUserId);

        if (!is_array($myMessages)) {
            $myMessages = [];
        }

        if (!is_array($yourMessages)) {
            $yourMessages = [];
        }

        $allMessages = array_merge($myMessages, $yourMessages);
        usort($allMessages, function ($a, $b) {
            return strtotime($a['msg_date']) - strtotime($b['msg_date']);
        });

        if (!empty($allMessages)) {
            foreach ($allMessages as $message) {
                $messageContent = $message['msg_content'];
                $messageDate = $message['msg_date'];
                $messageClass = ($message['sender_id'] == $_SESSION['uID']) ? 'sent' : 'received';

                echo "<div class='message $messageClass'>";
                echo "<span class='message-content'>$messageContent</span>";
                echo "<span class='message-date'>$messageDate</span>";
                echo "</div>";
            }
        } else {
            echo "<p>No messages found.</p>";
        }
        ?>
        
        <form action='' method='post' id="messageForm">
            <input type='hidden' name='receiverId' value='<?php echo $targetUserId; ?>'>
            <textarea name='messageContent' placeholder='Napisite poruku...'></textarea>
            <input type='submit' name='submit' value='Posalji'>
            <?php 
                echo "<h2> Razgovor sa ". $chatPartner["username"] ."</h2>";
            ?>
        </form>
        
    <?php    
    } ?>
</div>
<script>
    function scrollToBottom() {
        window.scrollTo(0, document.body.scrollHeight);
    }

    window.onload = function() {
        scrollToBottom();
    };

</script>

</body>
</html>
