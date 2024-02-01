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
                <h2>Dobrodošli</h2>
                <div class="fakeimg" style="height: 200px; background-image: url('css/znanje.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                </div>
                <p>U svetu gde se tehnologija neprestano razvija, stvaranje prostora za povezivanje ljudi i deljenje znanja postaje ključno. Cilj ove aplikacije je da omoguci efikasnu razmenu znanja i vestina.</p>
                <p>Nudimo prostor kako za mentore tako i za one koji žele učiti. Različite potrebe korisnika odražene su kroz funkcionalnosti koje omogućavaju mentorstvo, razmenu resursa i umrežavanje.</p>
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

        $allOk=false;

        if(isset($_POST['registerStudent']))
        {
            $userType=0;

            if(empty($_POST['interests']))
                $interests='Nije navedeno';
            else
                $interests=$_POST['interests'];

            if(empty($_POST['education']))
                $education='Nije navedeno';
            else
                $education=$_POST['education'];

            $allOk=validateRegisterUser($username,$password,$passwordAgain,$mail,$firstName,$lastName,$userType,$skills,$education,$interests,$user);

        }
        if(isset($_POST['registerMentor']))
        {
            $userType=1;
            if(empty($_POST['knowledge']))
                $knowledge='Nije navedeno';
            else
                $knowledge=$_POST['knowledge'];

            if(empty($_POST['yearExp']))
                $yearExp=0;
            else
                $yearExp=$_POST['yearExp'];

            $allOk=validateRegisterMentor($username,$password,$passwordAgain,$mail,$firstName,$lastName,$userType,$skills,$knowledge,$yearExp,$user);

        }
        if($allOk)
        {
            header ("Location: index.php");
            exit();
        }
    }
    echo "<h2>Registracija</h2>";
    if(!$user->isEmptyErrors())
        echo $user->displayErrors();

    $fields=array('Korisničko ime*'=>'username','Šifra*'=>'checkPass','Šifra ponovo*'=>'passwordAgain','Mejl adresa*'=>'mail','Ime*'=>'firstName','Prezime'=>'lastName','Vestine*'=>'skills');
    $types=array('text','password','password','text','text','text','text');

    $studentSpecific=array('Interesovanja'=>'interests','Obrazovanje'=>'education');
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