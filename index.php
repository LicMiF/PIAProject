<!DOCTYPE html>
<html lang="en">
<?php
    require_once "./core/utilities.php";
    include_once "./includes/head.php";
?>
<body>
    <?php
        include_once "./includes/topnav.php";
        include_once "./includes/header.html";
        if(isset($_SESSION['uID']))
        {
            $user=new User();
            $data=$user->getUserData($_SESSION['uID']);
            $_SESSION['userType']=$data['userType'];
            $container=new Container();
            fillTheContainer($container);
            $_SESSION['container']=&$container;
        }
    ?>

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
                <h2>Pocetna</h2>
                <?php
                    if(isset($_SESSION['uID']))
                    {
                        echo '<h2> Hello '.$data['firstName'].'</h2>';
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