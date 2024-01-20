<?php
    session_start();
    require_once "./core/utilities.php";
    $requests=new Request();
    if($requests->approveRequest($_POST['id'],$_SESSION['uID']))
        echo "Success!";
    else 
        echo "Failed!";


?>