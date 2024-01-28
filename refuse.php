<?php
    session_start();
    require_once "./core/utilities.php";
    $requests=new Request();
    if($requests->refuseRequest($_POST['id'],$_SESSION['uID']))
        echo "Success!";
    else 
        echo "Failed!";


?>