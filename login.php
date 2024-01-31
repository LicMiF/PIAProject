<!DOCTYPE html>
<html lang="en">
<?php
    require_once "./core/utilities.php";
    include_once "./includes/head.php";

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
    if(isset($_POST['loggin']))
    {
        $username=$_POST['username'];
        $password=$_POST['password'];
        if($uID=validateLogin($username,$password ,$user))
        {
            $_SESSION['uID']=$uID;
            header("Location: ./index.php");
            exit();
        }
        else
            $errorstr=$user->displayErrors();
    }
    echo "<h2>Prijava</h2>";
    if(!$user->isEmptyErrors())
        echo $errorstr;
    $fields=array('Korisničko ime'=>'username','Šifra'=>'password');
    $types=array('text','password');
    $submit=array('Prijavi se'=>'loggin');
    $action=$_SERVER['PHP_SELF']; $method='post';
    renderForm($fields,$types,$submit,$action,$method);
?>    
            <p>Nemas kreiran nalog? <a href="./register.php">Registruj se</a></p>
            </div>
        </div>
    </div>
    <?php
        include_once "./includes/footer.html";
    ?>
</body>
</html>