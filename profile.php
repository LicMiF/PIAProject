<!DOCTYPE html>
<html lang="en">
<?php
    require_once "./core/utilities.php";
    include_once "./includes/head.php";
    forbidAccesNonLogged();
?>
<body>


<!-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script defer>
$(document).ready(function() {
    $('#commentFormId').submit(function(event) {
        const scrollPosition = window.scrollY || window.pageYOffset;
        sessionStorage.setItem('scrollPosition', scrollPosition.toString());
    });

    const storedScrollPosition = sessionStorage.getItem('scrollPosition');
    if (storedScrollPosition !== null) {
        window.scrollTo(0, parseInt(storedScrollPosition, 10));
    }
});
</script> -->
<script src="../core/maintainingPosition.js" defer></script>
    <?php
        include_once "./includes/topnav.php";
        include_once "./includes/header.html";
    ?>

    <div class="row">
        <div class="leftcolumn">
            <div class="card">
                <div class="profile-container">
                    <?php 
                        $user= new User();
                        $profileId=$_POST['profileId'];
                        $errorstr=NULL;
                        if($_POST['userType']===1)
                            displayUserProfileDataUser($profileId,$user);
                        else
                            displayUserProfileDataMentor($profileId,$user);
                        if(isset($_POST['postComment']))
                        {
                            $body=$_POST['commentSection'];
                            if(!validateComments($body,$_SESSION['uID'],$profileId,$user))
                                $errorstr=$user->displayErrors();
                        }
                        
                    ?>
            
                    <!-- <h1 style="color:#04473e; text-align:center">Iskustva ostalih korisnika :</h1> -->

                    <?php 
                        displayCommentsSection($profileId,$user);
                        if($errorstr)
                            echo $errorstr;
                        if($_SESSION['uID']!=$profileId)
                            displayCommentForm($profileId,$_SERVER['PHP_SELF'],'post',$_POST['userType'],$user);
                    ?>
                </div>
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