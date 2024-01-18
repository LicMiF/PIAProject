<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
    require_once "./core/userDB.php";   
    $user=new User();
    $hashedPass=$user->hashPassword('password');
    $hashedMail=$user->hashPassword('john@example.com');
    if($user->insertData('john_doe',$hashedPass,'john@example.com','John','Doe',$hashedMail))
        echo  "All good";
?>
</body>
</html>