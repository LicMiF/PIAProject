<div class="topnav">
        <?php
                if(isset($_SESSION['uID']))
                {
        ?>
        <a href="#" onclick="viewProfile(<?=$_SESSION['uID']?>,<?=$_SESSION['userType']?>)">Profile</a>
        <a href="../notifications.php">Notifikacije</a>
        <?php
                }
        ?>
        <a href="../test.php">test</a>
        <a href="../comments.php">Komentari</a>
        <a href="../chat.php">chat</a>
        <a href="../notifications.php" style="float:right"><strong>Razmena vestina i znanja</strong></a>
        <div class="searcharea">
                <input placeholder="search here...." type="text" class="textField" />
        </div>
        <?php
                if(isset($_SESSION['uID']))
                        echo "<a href='./logout.php' style='float:right'>Odjava</a>" 
        ?>
</div>