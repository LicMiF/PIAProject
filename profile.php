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
    <div class="row">
        <div class="leftcolumn">
            <div class="card">
                <div class="profile-container">
                    <?php 
                        $user= new User();
                        $requests= new Request();
                        $profileId=$_POST['profileId'];
                        $errorstr=NULL;
                        $data=$user->getUserData($profileId);

                        if($_POST['userType']==0)
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
                        <?php 
                        if(($_SESSION['userType']==1  &&  ($_SESSION['uID']==$profileId)) || $user->getUserType($profileId)==1 )
                        {
                            if(isset($_SESSION['uID']) && ($_SESSION['uID']!=$profileId) && ($requests->isApproved($_SESSION['uID'],$profileId)))
                            {
                                $myRating=$user->getMyRating($_SESSION['uID'],$profileId);
                            ?>
                                <h2>Oceni mentora:</h2>
                                <div id="userRating">
                                    <span class="star-rating" data-rating="1">&#9733;</span>
                                    <span class="star-rating" data-rating="2">&#9733;</span>
                                    <span class="star-rating" data-rating="3">&#9733;</span>
                                    <span class="star-rating" data-rating="4">&#9733;</span>
                                    <span class="star-rating" data-rating="5">&#9733;</span>
                                </div>
                            <?php 
                                }
                            ?>
                            <h2>Prosečna ocena:</h2>
                            <div id="averageRating" class="average-rating">
                                <p>Mentor još uvek nema nijednu recenziju</p>
                            </div>

                            <?php 
                                    $averageRating=getAverageRating($profileId,$user);
                                    if($averageRating)
                                    {
                                        ?>
                                            <script defer>const averageRatingElementTemp = document.getElementById('averageRating'); averageRatingElementTemp.innerHTML = `<div class="average-rating-float"> ${parseFloat(<?=$averageRating?>).toFixed(1)} </div>${generateStarRatingDuplicate(parseFloat(<?=$averageRating?>))}`;</script>
                                        <?php
                                    }
                                    ?>

                
                        <h1 style="color:#04473e; text-align:center">Iskustva ostalih korisnika :</h1>

                        <?php 
                            displayCommentsSection($profileId,$user);
                            if($errorstr)
                                echo $errorstr;
                            if(isset($_SESSION['uID']) && ($_SESSION['uID']!=$profileId) && ($requests->isApproved($_SESSION['uID'],$profileId)))
                                displayCommentForm($profileId,$_SERVER['PHP_SELF'],'post',$_POST['userType'],$user);
                        }
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


    <script defer>
        const ratedId=<?php echo $profileId?>;
        const criticId=<?php echo $_SESSION['uID']?>;
        let userRating = <?php echo $myRating?>;
    </script>
    <script src="core/rating.js" defer></script>
    <?php
        include_once "./includes/footer.html";
    ?>

</body>
</html>