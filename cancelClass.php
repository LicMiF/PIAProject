<?php
    session_start();
    require_once "./core/utilities.php";
    $user=new User();
    sendClassCanceledNotifications($_SESSION['uID'],$_POST['id']);
    if($user->deleteDataGeneric('classes',array('classId'),array($_POST['id'])))
        echo "Success!";
    else 
        echo "Failed!";
?>