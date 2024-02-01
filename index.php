<!DOCTYPE html>
<html lang="en">
<?php
    require_once "./core/utilities.php";
    include_once "./includes/head.php";
?>
<body>
    <?php
        if(isset($_SESSION['uID']))
        {
            $user=new User();
            $data=$user->getUserData($_SESSION['uID']);
            $_SESSION['userType']=$data['userType'];
            $container=new Container();
            fillTheContainer($container);
            $_SESSION['container']=&$container;
        }
        include_once "./includes/topnav.php";
        include_once "./includes/header.html";
    ?>

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
                <h2>Pocetna</h2>
                <?php
                    if(isset($_SESSION['uID']))
                    {
                        echo '<h2> Pozdrav, '.$data['firstName'].'.</h2>';
                    ?>
                    <a href="./changePass.php">Promeni sifru</a> </br>
                    <a href="./settings.php">Podesavanja</a>
                <?php
                    }
                else{
                ?>
                <form action="./login.php" method="get">
                    <input type="submit" value="Prijavi se" class="button">
                </form>
                <p>Nemas kreiran nalog? <a href="./register.php">Registruj se</a></p>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php
        include_once "./includes/footer.html";
    ?>
</body>
</html>