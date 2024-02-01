<!DOCTYPE html>
<html lang="en">
<?php
    require_once "./core/utilities.php";
    include_once "./includes/head.php";

    forbidAccesNonLogged();

    include_once "./includes/topnav.php";
    include_once "./includes/header.html";
?>
<body>
    <script src="./core/maintainingPosition.js" defer></script>
    <?php
        $user = new User();
        if (isset($_POST['uploadImg'])) 
        {
            if (!verifyImageAndSaveImage($_SESSION['uID'], $user))
                $errorstr = $user->displayErrors();
        }
        if(isset($_POST['changeAvailability']))
        {
            $fromHour=$_POST['fromHour'];
            $fromMinutes=$_POST['fromMinutes'];
            $fromAmPm=$_POST['fromAmPm'];
            $toHour=$_POST['toHour'];
            $toMinutes=$_POST['toMinutes'];
            $toAmPm=$_POST['toAmPm'];
            $value=implode('-',array($fromHour,$fromMinutes,$fromAmPm,$toHour,$toMinutes,$toAmPm));
            $column=array('timeAvailability');

            $user->updateDataGeneric('mentorSpecific',$column,array($value),array('userId'),array($_SESSION['uID']));
        }
    ?>

    <div class="row">
        <div class="leftcolumn">
            <div class="card">
                <div class="profile-container">
                    <?php 
                        $user= new User();
                        $profileId=$_SESSION['uID'];
                        $errorstr=NULL;
                        if($_SESSION['userType']==0)
                            displayUserProfileDataUser($profileId,$user);
                        else
                            displayUserProfileDataMentor($profileId,$user);
                        ?>
                </div>
            </div>
        </div>
    
        <div class="rightcolumn">
            <div class="card">
                <?php
                    $user = new User();
                    if(isset($_POST['pass']))
                    {
                        $mail=$_POST['mail'];
                        $firstName=$_POST['firstName'];
                        $lastName=$_POST['lastName'];
                        $skills=$_POST['skills'];

                        $userData=$user->selectDataGeneric('users',array('userId'),array($profileId))[0];

                        if(empty($mail))
                            $mail=$userData['mail'];

                        if(empty($firstName))
                            $firstName=$userData['firstName'];
                    
                        if(empty($lastName))
                            $lastName=$userData['lastName'];

                        if(empty($skills))
                            $skills=$userData['skills'];

                        $allOk;

                        if($_SESSION['userType']==0)
                        {
                            $userSpecificData=$user->selectDataGeneric('userSpecific',array('userId'),array($profileId))[0];
                            $education=$_POST['education'];
                            $interests=$_POST['interests'];
                            if(empty($_POST['education']))
                                $education=$userSpecificData['education'];

                            if(empty($_POST['interests']))
                                $interests=$userSpecificData['interests'];
                            
                            
                            $allOk=validateSettingsChangeUser($mail,$firstName,$lastName,$skills,$education,$interests,$_SESSION['uID'],$user);             
                        }
                        else
                        {
                            $mentorSpecificData=$user->selectDataGeneric('mentorSpecific',array('userId'),array($profileId))[0];
                            
                            if(empty($_POST['knowledge']))
                                $knowledge=$mentorSpecificData['knowledge'];
                            else
                                $knowledge=$_POST['knowledge'];

                            if(empty($_POST['yearExp']))
                                $yearExp=$mentorSpecificData['yearExp'];
                            else
                                $yearExp=$_POST['yearExp'];

                            $allOk=validateSettingsChangeMentor($mail,$firstName,$lastName,$skills,$yearExp,$knowledge,$_SESSION['uID'],$user);

                        }
                        if(!$allOk)
                            $errorstr=$user->displayErrors();
                        else
                        {
                            echo "<script> window.location.href = 'settings.php';</script>";
                            exit(); 
                        }

                    }
                    echo "<h2>Podešavanja</h2>";
                    if(!$user->isEmptyErrors())
                        echo $errorstr;

                    if($_SESSION['userType']==0)
                    {
                        $fields=array('Email'=>'mail','Ime'=>'firstName','Prezime'=>'lastName','Veštine'=>'skills','Obrazovanje'=>'education','Interesovanja'=>'interests');
                        $types=array('text','text','text','text','text','text');
                    }
                    else
                    {
                        $fields=array('Email'=>'mail','Ime'=>'firstName','Prezime'=>'lastName','Veštine'=>'skills','Godine iskustva'=>'yearExp','Znanje'=>'knowledge');
                        $types=array('text','text','text','text','text','text');
                    }
                    $submit=array('Promeni'=>'pass');
                    $action=$_SERVER['PHP_SELF']; $method='post';
                    renderForm($fields,$types,$submit,$action,$method);
                ?>    
            </div>

            <?php 
            

            if($_SESSION['userType']==1){

                echo '<div class="card">';
                    include_once './includes/timeSelection.php';
                echo '</div>';
            } 
            ?>

            <div class='card'>
                <div class="image-selection">
                    <h2>Promenite profilnu sliku:</h2>
                    <form action="<?=$_SERVER['PHP_SELF']?>" method='post' enctype="multipart/form-data">
                            <label for="image" class="file-label">
                            Izaberi sliku
                            <input type="file" name="image" id="image" accept="image/*" required>
                            </label>
                            <br>
                            <input type="submit" name='uploadImg' value="Potvrdi izbor" class="button">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
        include_once "./includes/footer.html";
    ?>
</body>
</html>