<?php
    session_start();
    require_once "./core/utilities.php";
    $user=new User();
    if($user->updateDataGeneric('notifications',array('viewed'),array(1),array('recieverId'),array($_POST['id'])))
        echo "Success!";
    else 
        echo "Failed!";
?>