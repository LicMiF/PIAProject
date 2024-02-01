<?php
    session_start();
    require_once "./core/utilities.php";
    $requests=new Request();

    sendRequestApprovedNotification($_POST['id'],$_SESSION['uID']);
    
    if($requests->approveRequest($_POST['id'],$_SESSION['uID']))
        echo "Success!";
    else 
        echo "Failed!";


?>