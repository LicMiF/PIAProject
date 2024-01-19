<div class="topnav">
        <a href="../test.php">Profil</a>
        <a href="#">Stranica</a>
        <a href="#">Kontakt</a>
        <a href="../notifications.php">Notifikacije</a>
        <a href="../notifications.php" style="float:right"><strong>Razmena vestina i znanja</strong></a>
        <div class="searcharea">
                <input placeholder="search here...." type="text" class="searchbox" />
        </div>
        <?php
                if(isset($_SESSION['uID']))
                        echo "<a href='./logout.php' style='float:right'>Odjava</a>" 
        ?>
</div>