<!DOCTYPE html>
<html lang="en">
<?php
    require_once "./core/utilities.php";
    include_once "./includes/head.php";
    forbidAccesNonLogged();
?>
<body>
    <?php
        include_once "./includes/topnav.php";
        include_once "./includes/header.html";
    ?>
    
    <div class="row">
        <div class="leftcolumn">
            <div class="card">
                <?php
                    $user=new User();
                    if($_SESSION['userType'] === 1)
                    {
                        $data=$user->getAllUserData();
                        echo "<h1>Users:</h1>";
                    }
                    else
                    {
                        $data=$user->getAllMentorData();
                        echo "<h1>Mentors:</h1>";
                    }

                    echo '<table border="1" cellpadding="5" style="border-collapse:collapse">';
                    
                    echo "<tr><th>Korisnicko ime</th><th>Ime</th><th>prezime</th><th>mejl</th><th></th></tr>";
                    foreach($data as $row)
                    {
                        echo "<tr>";
                        echo "<td>".$row['username']."</td>";
                        echo "<td>".$row['firstName']."</td>";
                        echo "<td>".$row['lastName']."</td>";
                        echo "<td>".$row['mail']."</td>";
                        if($_SESSION['userType'])
                        {
                            echo "<td> <input type='button' id=".$row['userId']." value='Vidi profil onclick='viewProfile(this.id,0)' class='button' ></td>";
                        }
                        else
                        {
                            echo "<td> <input type='button' id=".$row['userId']." value='Vidi profil' onclick='viewProfile(this.id,1)' class='button' ></td>";
                        }
                        echo "</tr>";
                    }
                    echo '</table>';
                ?>
            </div>
        </div>
    
        <div class="rightcolumn">
            <div class="card">
                <h2>Pocetna</h2>
                <?php
                    $user=new User();
                    if(isset($_SESSION['uID']))
                    {
                        $data=$user->getUserData($_SESSION['uID']);
                        $_SESSION['userType']=$data['userType'];
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