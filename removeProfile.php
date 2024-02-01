<?php
    session_start();
    require_once "./core/utilities.php";
    $user=new User();
    if($_POST['userType']==1)
    {
        if($user->deleteDataGeneric('mentorSpecific',array('userId'),array($_POST['id'])))
            echo "Success!";
        else
            echo "Failed!";

        if($user->deleteDataGeneric('requests',array('recieverId'),array($_POST['id'])))
            echo "Success!";
        else
            echo "Failed!";



        $comments=$user->selectDataGeneric('comments',array('recieverId'),array($_POST['id']));

        foreach($comments as $comment)
        {
            if($user->deleteDataGeneric('likes',array('commentId'),array($comment['commentId'])))
                echo "Success!";
            else
                echo "Failed!";
        }

        if($user->deleteDataGeneric('comments',array('recieverId'),array($_POST['id'])))
            echo "Success!";
        else
            echo "Failed!";


        if($user->deleteDataGeneric('classes',array('creatorId'),array($_POST['id'])))
            echo "Success!";
        else
            echo "Failed!";

        if($user->deleteDataGeneric('ratings',array('ratedId'),array($_POST['id'])))
            echo "Success!";
        else
            echo "Failed!";
    }
    else
    {
        if($user->deleteDataGeneric('userSpecific',array('userId'),array($_POST['id'])))
            echo "Success!";
        else
            echo "Failed!";

        if($user->deleteDataGeneric('requests',array('senderId'),array($_POST['id'])))
            echo "Success!";
        else
            echo "Failed!";

        if($user->deleteDataGeneric('likes',array('senderId'),array($_POST['id'])))
            echo "Success!";
        else
            echo "Failed!";

        if($user->deleteDataGeneric('comments',array('senderId'),array($_POST['id'])))
            echo "Success!";
        else
            echo "Failed!";

        if($user->deleteDataGeneric('ratings',array('criticId'),array($_POST['id'])))
            echo "Success!";
        else
            echo "Failed!";
    }


    if($user->deleteDataGeneric('notifications',array('recieverId'),array($_POST['id'])))
        echo "Success!";
    else
        echo "Failed!";

    if($user->deleteDataGeneric('messages',array('recieverId'),array($_POST['id'])))
        echo "Success!";
    else
        echo "Failed!";

    if($user->deleteDataGeneric('messages',array('senderId'),array($_POST['id'])))
        echo "Success!";
    else
        echo "Failed!";

    if($user->deleteDataGeneric('users',array('userId'),array($_POST['id'])))
        echo "Success!";
    else
        echo "Failed!";


?>