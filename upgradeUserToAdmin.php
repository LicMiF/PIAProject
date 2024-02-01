<?php
    session_start();
    require_once "./core/utilities.php";
    $user=new User();
    if($user->updateDataGeneric('users',array('userType'),array(2),array('userId'),array($_POST['id'])))
        echo "Success!";
    else 
        echo "Failed!";


?>