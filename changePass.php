<!DOCTYPE html>
<html lang="en">
<?php

    require_once "./core/utilities.php";
    include_once "./includes/head.php";

    forbidAccesNonLogged();

    include_once "./includes/topnav.php";
    include_once "./includes/header.html";
?>
<body>
    <div class="row">
        <div class="leftcolumn">
            <div class="card">
                <h2>TITLE HEADING</h2>
                <h5>Title description, Dec 7, 2017</h5>
                <div class="fakeimg" style="height:200px;">Image</div>
                <p>Some text..</p>
                <p>Sunt in culpa qui officia deserunt mollit anim id est laborum consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.</p>
            </div>
        </div>
    
        <div class="rightcolumn">
            <div class="card">
<?php
    $user = new User();
    if(isset($_POST['change']))
    {
        $currPass=$_POST['currentPass'];
        $newPass=$_POST['checkPass'];
        $newPassAgain=$_POST['newPassAgain'];
        if($uID=validatePassChange($currPass,$newPass ,$newPassAgain,$_SESSION['uID'],$user))
        {
            header("Location: ./index.php");
            exit();
        }
        else
            $errorstr=$user->displayErrors();
    }
    echo "<h2>Promena sifre</h2>";
    if(!$user->isEmptyErrors())
        echo $errorstr;
    $fields=array('Trenutna sifra*'=>'currentPass','Nova sifra'=>'checkPass','Nova sifra ponovo*'=>'newPassAgain');
    $types=array('password','password','password');
    $submit=array('Promeni'=>'change');
    $action=$_SERVER['PHP_SELF']; $method='post';
    renderFormWithPasswordStrengthCheckAndRadios($fields,$types,$submit,$action,$method,NULL);
    echo "<script src='./core/passwordChecker.js'></script>";
?>    
            </div>
        </div>
    </div>
    <?php
        include_once "./includes/footer.html";
    ?>
</body>
</html>