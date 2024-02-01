<?php
    session_start();
    require_once "./core/utilities.php";
    $user=new User();
    if($user->updateDataGeneric('messages',array('viewed'),array(1),array('recieverId','senderId'),array($_SESSION['uID'],$_POST['id'])))
        echo "Success!";
    else 
        echo "Failed!";
?>