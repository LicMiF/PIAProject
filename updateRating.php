<?php
    require_once './core/utilities.php';

    $data = json_decode(file_get_contents('php://input'), true);

if ($data && isset($data['ratedId'], $data['criticId'],$data['rating'])) {
    $user=new User();
    $ratedId = $data['ratedId'];
    $criticId = $data['criticId'];
    $newRating = $data['rating'];


    if($user->isAlreadyRated($ratedId,$criticId))
        $success = $user->updateDataGeneric('ratings',array('rating'),array($newRating),array('ratedId','criticId'),array($ratedId,$criticId));
    else
        $success = $user->insertDataSpecific(array('ratedId','criticId','rating'),array($ratedId,$criticId,$newRating),'ratings');

    if ($success) {
    {
        sendNewRatingNotification($ratedId,$criticId,$newRating);
        echo json_encode(['success' => true]);
    }
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}