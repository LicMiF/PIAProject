<div class="topnav">
        <?php
                if(isset($_SESSION['uID']) && ($_SESSION['userType']!='2'))
                {
        ?>
                        <a href="#" onclick="viewProfile(<?=$_SESSION['uID']?>,<?=$_SESSION['userType']?>)">Profil</a>
                        <a href="notifications.php">
                                Notifikacije
                                <?php
                                        $user=new User();
                                        $unreadCount = count($user->selectDataGeneric('notifications',array('recieverId','viewed'),array($_SESSION['uID'],0)));
                                        if ($unreadCount > 0) {
                                                echo '<div class="notification-circle" id="notificationCount" > ' . $unreadCount . '</div>';
                                        }
                                ?>
                        </a>
                        <a href="chat.php">Prepiske
                                <?php
                                        $unreadCount = count($user->selectDataGeneric('messages',array('recieverId','viewedReciever'),array($_SESSION['uID'],0)));
                                        if ($unreadCount > 0) {
                                                echo '<div class="notification-circle" id="notificationCount" > ' . $unreadCount . '</div>';
                                        }
                                ?>
                        </a>
        <?php
                }
                if(isset($_SESSION['userType']))
                {
                        if($_SESSION['userType']==='0')
                                echo'<a href="profileListing.php">Mentori</a>';

                        else if($_SESSION['userType']==='1')
                                echo'<a href="profileListing.php">Korisnici</a>';

                        else if($_SESSION['userType']==='2')
                                echo'<a href="controlPanel.php">Kontrolni panel</a>';
                }
                else
                        echo'<a href="profileListing.php">Profili</a>';

                if(isset($_SESSION['userType']) && $_SESSION['userType']==='1')
                {
        ?>
                        <a href="classSched.php">Časovi</a>
        <?php
                }
        ?>
        <a href="../index.php" style="float:right"><strong>Razmena vestina i znanja</strong></a>
        <div class="searcharea">
                <form action="<?php echo (isset($_SESSION['userType']) && $_SESSION['userType']==2) ? "../controlPanel.php" : "../profileListing.php"; ?>" method='post' class='topNav-search'>
                        <div class="search-input">
                                <input type="text" class="textField" id='searchBox' name='searchUsers' placeholder="Pretrazite korisnika...">
                                <i class="fas fa-search"></i>
                        </div>
                </form>
        </div>  
        <?php
                if(isset($_SESSION['uID']))
                        echo "<a href='./logout.php' style='float:right'>Odjava</a>" 
        ?>
</div>