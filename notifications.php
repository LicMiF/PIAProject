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

        updateContainer($_SESSION['container']);
    ?>
    
    <div class="row">
        <div class="leftcolumn">
            <div class="card">
                <?php
                    $user=new User();
                    if($_SESSION['userType'] === 1)
                    {
                        $dataApproved=array();
                        $dataWaiting=array();
                        $requests=$_SESSION['container']->getRequests();
                        foreach($requests as $row)
                        {
                            if($row['approvedReciever'])
                                $dataApproved[]=$user->getUserData($row['senderId']);
                            else
                                $dataWaiting[]=$user->getUserData($row['senderId']);
                        }


                        displayNotificationsForMentor($dataWaiting,$dataApproved);
                    }
                    else
                    {
                        $dataApproved=array();
                        $dataWaiting=array();
                        $requests=$_SESSION['container']->getRequests();
                        var_dump($requests);
                        foreach($requests as $row)
                        {
                            if($row['approvedReciever'])
                                $dataApproved[]=$user->getUserData($row['recieverId']);
                            else
                                $dataWaiting[]=$user->getUserData($row['recieverId']);
                        }
                        
                        displayNotificationsForUser($dataWaiting,$dataApproved);
                    }

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