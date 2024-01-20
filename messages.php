<?php
require_once "./core/userDB.php";
require_once "./core/utilities.php";
include_once "./includes/head.php";
$user = new User();
if(isset($_POST["submit"])){
    $senderId=$_SESSION['uID'];
    $receiverId = $_POST['receiverId'];
    $messageContent = $_POST['messageContent'];
    $timestamp = date('Y-m-d H:i:s');
    $status="unread";
    $user->updateMessage($senderId,$receiverId,$messageContent,$status, $timestamp);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .chat-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .message {
            margin-bottom: 10px;
            padding: 8px;
            border-radius: 5px;
        }

        .sent {
            background-color: #4CAF50;
            color: #fff;
            text-align: right;
        }

        .received {
            background-color: #ddd;
            text-align: left;
        }

        .message-content {
            display: inline-block;
            max-width: 70%;
            word-wrap: break-word;
        }

        .message-date {
            font-size: 12px;
            color: #888;
            margin-left: 5px;
        }

        form {
            margin-top: 20px;
        }

        textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="chat-container">

        <?php
        $targetUserId;

        if(isset($_GET['userId'])) {
            $targetUserId = $_GET['userId'];
        }
        
        $myMessages = $user->showMyMessages($_SESSION['uID'],$targetUserId); 
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

        <form action='' method='post'>
            <input type='hidden' name='receiverId' value='<?php echo $targetUserId; ?>'>
            <textarea name='messageContent' placeholder='Type your message here...'></textarea>
            <input type='submit' name='submit' value='Posalji'>
        </form>
    </div>
</body>
</html>
