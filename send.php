<?php
    session_start();
    require_once "./core/utilities.php";
    $requests=new Request();
    if($requests->addRequest($_SESSION['uID'],$_POST['id'])) 
        echo "Success!";
    else 
        echo "Failed!";

?>