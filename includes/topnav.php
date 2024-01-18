<div class="topnav">
        <a href="#">Profil</a>
        <a href="#">Stranica</a>
        <a href="#">Kontakt</a>
        <div class="searcharea">
                <input placeholder="search here...." type="text" class="searchbox" />
        </div>
        <?php
                if(isset($_SESSION['uID']))
                        echo "<a href='./logout.php' style='float:right'>Odjava</a>" 
        ?>
</div>