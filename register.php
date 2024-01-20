<!DOCTYPE html>
<html lang="en">
<?php
    require_once "./core/utilities.php";
    include_once "./includes/head.php";

    forbidAccesLogged();

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
    if(isset($_POST['registerStudent']) || isset($_POST['registerMentor']))
    {
        $username=$_POST['username'];
        $password=$_POST['checkPass'];
        $passwordAgain=$_POST['passwordAgain'];
        $mail=$_POST['mail'];
        $firstName=$_POST['firstName'];
        $lastName=$_POST['lastName'];
        $skills=$_POST['skills'];

        if(isset($_POST['registerStudent']))
        {
            $userType=0;
            $interests=$_POST['interests'];
            $education=$_POST['education'];
        }
        if(isset($_POST['registerMentor']))
        {
            $userType=1;
            $knowledge=$_POST['knowledge'];
            $yearExp=$_POST['yearExp'];
        }
        if($uID=validateRegister($username,$password,$passwordAgain,$mail,$firstName,$lastName,$userType,$skills,$user))
        {
            $_SESSION['uID']=$uID;
            //PREPROCESS SPECIFIC DATA?
            if($userType===0)
            {
                $columns=array('userId','education','interests');
                $values=array($uID,$interests,$education);
                $user->insertDataTest($columns,$values,'userSpecific');
            }
            if($userType===1)
            {
                $columns=array('userId','yearExp','knowledge');
                $values=array($uID,$yearExp,$knowledge);
                $user->insertDataTest($columns,$values,'mentorSpecific');
            }
            /*Add mail verif notification*/
            header ("Location: index.php");
            exit();
        }
        else
            $errorstr=$user->displayErrors();
    }
    echo "<h2>Registracija</h2>";
    if(!$user->isEmptyErrors())
        echo $errorstr;

    $fields=array('Korisničko ime*'=>'username','Šifra*'=>'checkPass','Šifra ponovo*'=>'passwordAgain','Mejl adresa*'=>'mail','Ime*'=>'firstName','Prezime'=>'lastName','Vestine*'=>'skills');
    $types=array('text','password','password','text','text','text');

    $studentSpecific=array('Interesovanja'=>'interests','Obrazovanje'=>'Education');
    $mentorSpecific=array('Znanje'=>'knowledge','Godne iskustva'=>'yearExp');

    $submit=array('registerStudent','registerMentor');

    $action=$_SERVER['PHP_SELF']; $method='post';
    renderFormRegistrationSpecific($fields,$types,$studentSpecific,$mentorSpecific,$submit,$action,$method);

    echo "<script src='./core/passwordChecker.js'></script>";
?>    
            <p>Posedujes registrovan nalog? <a href="./login.php">Uloguj se</a></p>
            </div>
        </div>
    </div>
    <?php
        include_once "./includes/footer.html";
    ?>
</body>
</html>