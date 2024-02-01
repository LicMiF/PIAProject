<?php
    session_start();
    require_once "./core/utilities.php";
    $user=new User();
    $commentId=$_POST['commentId'];
    $senderId=$_POST['senderId'];
    $metaData=$user->selectDataGeneric('likes',array('commentId','senderId'),array($commentId,$senderId));
    if(!$metaData)
    {
        if($user->insertDataGeneric(array('commentId','senderId','disliked'),array($commentId,$senderId,1),'likes'))
            echo "Success!";
        else 
            echo "Failed!";
    }
    else{
        if($metaData[0]['disliked'])
        {
            if($user->updateDataGeneric('likes',array('disliked'),array(0),array('commentId','senderId'),array($commentId,$senderId)))
                echo "Success!";
            else 
                echo "Failed!";
        }

        else
        {
            if($user->updateDataGeneric('likes',array('liked'),array(0),array('commentId','senderId'),array($commentId,$senderId)))
                echo "Success!";
            else 
                echo "Failed!";
    
            if($user->updateDataGeneric('likes',array('disliked'),array(1),array('commentId','senderId'),array($commentId,$senderId)))
                echo "Success!";
            else 
                echo "Failed!";
        }
    }


?>