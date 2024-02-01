<!DOCTYPE html>
<html lang="en">
<?php
    require_once "./core/utilities.php";
    include_once "./includes/head.php";

    allowAdminOnly();

    include_once "./includes/topnav.php";
    include_once "./includes/header.html";
?>
<body>
    <script src="./core/maintainingPosition.js" defer></script>
    <?php
        $user = new User();
        if (isset($_POST['profileId']))
        {
            $userId=$_POST['profileId'];
            $userType=$_POST['userType'];
            $_SESSION['adminEditingProfileId']=$userId;
            $_SESSION['adminEditingProfileType']=$userType;
        }
        else
        {
            $userId=$_SESSION['adminEditingProfileId'];
            $userType=$_SESSION['adminEditingProfileType'];
        }
    ?>

    <div class="row">
        <div class="leftcolumn">
            <div class="card">
                <div class="profile-container">
                    <?php 
                        $user= new User();
                        $profileId=$userId;
                        $errorstr=NULL;
                        if($userType==0)
                            displayUserProfileDataUser($profileId,$user);
                        else
                        {
                            displayUserProfileDataMentor($profileId,$user);
                            displayCommentsSection($profileId,$user);
                        }
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

                        if($userType==0)
                        {
                            $userSpecificData=$user->selectDataGeneric('userSpecific',array('userId'),array($profileId))[0];

                            $education=$_POST['education'];
                            $interests=$_POST['interests'];                            
                            if(empty($_POST['education']))
                                $education=$userSpecificData['education'];

                            if(empty($_POST['interests']))
                                $interests=$userSpecificData['interests'];
 
                                
                            $allOk=validateSettingsChangeUser($mail,$firstName ,$lastName,$skills,$education,$interests,$profileId,$user);
                            
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

                            $allOk=validateSettingsChangeMentor($mail,$firstName ,$lastName,$skills,$yearExp,$knowledge,$profileId,$user);
                        }
                        if(!$allOk)
                            $errorstr=$user->displayErrors();
                        else
                        {
                            echo "<script> window.location.href = 'editProfile.php';</script>";
                            exit(); 
                        }

                    }
                    echo "<h2>Podešavanja</h2>";
                    if(!$user->isEmptyErrors())
                        echo $errorstr;

                    if($userType==0)
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
        </div>
    </div>
    <?php
        include_once "./includes/footer.html";
    ?>
</body>
</html>