<?php
    session_start();
    require_once "./core/utilities.php";
    $requests=new Request();
    if($requests->approveRequest($_GET['id'],$_SESSION['uID']))
    {   
        echo "Success!";
        header('Location: ./notifications.php');
        exit();
    }
    else 
        echo "Failed!";


?>