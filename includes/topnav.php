<div class="topnav">
        <?php
                if(isset($_SESSION['uID']))
                {
        ?>
        <a href="#" onclick="viewProfile(<?=$_SESSION['uID']?>,<?=$_SESSION['userType']?>)">Profil</a>
        <a href="../notifications.php">
                Notifikacije
                <?php
                        $user=new User();
                        $unreadCount = count($user->selectDataGeneric('notifications',array('recieverId','viewed'),array($_SESSION['uID'],0)));
                        if ($unreadCount > 0) {
                                echo '<div class="notification-circle" id="notificationCount" > ' . $unreadCount . '</div>';
                        }
                ?>
        </a>
        <a href="../test.php">test</a>
        <a href="../comments.php">Komentari</a>
        <a href="../chat.php">Prepiske</a>
        <?php
                }
                if($_SESSION['userType']==0 || $_SESSION['uID']==null)
                {
        ?>
                        <a href="../profileListing.php">Mentori</a>
        <?php
                }
                else if($_SESSION['userType']==1)
                {
        ?>
                        <a href="../classSched.php">Časovi</a>
        <?php
                }
        ?>
        <a href="../notifications.php" style="float:right"><strong>Razmena vestina i znanja</strong></a>
        <div class="searcharea">
                <form action="../profileListing.php" method='post' class='topNav-search'>
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