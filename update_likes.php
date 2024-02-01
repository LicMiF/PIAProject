<?php

    if(!isset($_SESSION['likes']))
        $_SESSION['likes']=0;
    if(!isset ($_GET['newLikeCount']))
        echo $_GET['newLikeCount'];

        echo $_POST['id'];
        echo $_POST['something'];
?>