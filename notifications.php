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
    ?>
    <script src="./core/maintainingPosition.js" defer></script>
    <script>updateViewedNotifications();</script>
    <?php
        $notifications=NULL;
        $user=new User();
       
        if(isset($_POST['deleteAll']))
            $user->deleteDataGeneric('notifications',array('recieverId'),array($_SESSION['uID']));

        $notificationsNotSeen=$user->selectDataGeneric('notifications',array('recieverId','viewed'),array($_SESSION['uID'],0));
        $notificationsSeen=$user->selectDataGeneric('notifications',array('recieverId','viewed'),array($_SESSION['uID'],1));

        usort($notificationsNotSeen,'compareNotifTimestamps');
        usort($notificationsSeen,'compareNotifTimestamps');

        $notifications=array_merge($notificationsNotSeen,$notificationsSeen);
        
    ?>

    
    <div class="row">
        <div class="leftcolumn">
            <div class="card">
                <?php
                    $user=new User();

                    if(empty($notifications))
                    {
                        echo "<h1>Trenunto nemate obeveštenja...</h1>";
                    }
                    else
                    {
                        ?>
                            <form action="<?=$_SERVER['PHP_SELF']?>" method='post'>
                            <div class="notification-delete-all">
                                <h1>Obaveštenja</h1>
                                <input type="submit" name="deleteAll" value="Obriši sve notifikacije" class='dangerButton'>
                            </div>
                            </form>

                        <?php
                        foreach ($notifications as $row)
                        {
                            $viewedColor='';

                            if ($row['viewed']==1)
                                $viewedColor="style='background-color: #aaa;'";

                            echo "<div class='searching-profiles-container' $viewedColor>";
                                echo "<div class='firstLastName'>";
                                    echo "<h2>".$row['notificationHeader']."</h2>";
                                echo "</div>";

                                echo "<div class='short-descr'>";
                                    echo "<p>".$row['notificationBody']."</p>";
                                echo "</div>";

                                echo "<div class='request-view-buttons'>";
                                    echo "<input type='button' id=".$row['notificationId']." value='Ukloni' onclick='deleteNotification(this.id)' class='dangerButton'>";
                                echo "</div>";

                            echo "</div>";

                        }
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
        //Update the notifications to status viewed
        markNotificationsAsRead();
        include_once "./includes/footer.html";
    ?>
</body>
</html>