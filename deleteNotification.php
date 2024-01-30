<?php
    session_start();
    require_once "./core/utilities.php";
    $user=new User();
    if($user->deleteDataGeneric('notifications',array('notificationId'),array($_POST['id'])))
        echo "Success!";
    else 
        echo "Failed!";
?>