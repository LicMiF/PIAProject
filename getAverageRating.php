<?php
    require_once './core/utilities.php';

    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['ratedId'])) {
        $user = new User();
        $averageRating=getAverageRating($data['ratedId'],$user);
        echo json_encode(['success' => true, 'averageRating' => $averageRating]);
    }
    else
    {
        echo json_encode(['success' => false]);
    }
        
?>