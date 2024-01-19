<?php
    session_start();
    require_once "./core/utilities.php";
    $requests=new Request();
    if($requests->addRequest($_SESSION['uID'],$_GET['id']))
    {   
        echo "Success!";
        header('Location: ./test.php');
        exit();
    }
    else 
        echo "Failed!";

?>