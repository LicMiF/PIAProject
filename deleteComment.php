<?php
    session_start();
    require_once "./core/utilities.php";
    allowAdminOnly();
    $user=new User();
    if($user->deleteDataGeneric('comments',array('commentId'),array($_POST['id'])))
        echo "Success!";
    else 
        echo "Failed!";
?>