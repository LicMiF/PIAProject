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
    <?php
        $topNavSearchData=NULL;
        $userType=-1;
        if(isset($_SESSION['userType']))
            $userType=$_SESSION['userType'];
        $user=new User();
        if (isset($_POST['searchUsers']))
        {
                $searchStr=$_POST['searchUsers'];
                if($searchStr!='')

                    switch($userType)
                    {
                        case 0:
                            $topNavSearchData=$user->searchForOtherMentors($searchStr);
                            break;
                        case 1:
                            $topNavSearchData=$user->searchForOtherUsers($searchStr);
                            break;
                        default:
                            $topNavSearchData=$user->searchAll($searchStr);
                            break;
                    }
        }
        else{
            switch($userType)
            {
                case 0:
                    $topNavSearchData=$user->getAllMentorData();
                    break;
                case 1:
                    $topNavSearchData=$user->getAllUserData();
                    break;
                default:
                    $topNavSearchData=$user->getAllData();
                    break;

            }
        }

    ?>

    
    <div class="row">
        <div class="leftcolumn">
            <div class="card">
                <?php
                    $user=new User();
                    if(empty($topNavSearchData))
                    {
                        switch($userType)
                            {
                                case 0:
                                    echo "<h1>Nismo pronašli nijedog mentora...</h1>";
                                    echo "<p>Mentore možete pretraživati po imenu, prezimenu, veštinama ili znanju.</p>";
                                    break;
                                case 1:
                                    echo "<h1>Nismo pronašli nijedog korisnika...</h1>";
                                    echo "<p>Korisnike možete pretraživati po imenu, prezimenu, veštinama ili interesovanjima.</p>";
                                    break;
                                default:
                                    echo "<h1>Nismo pronašli nijedog korisnika ili mentora...</h1>";
                                    echo "<p>Mentore i korisnike možete pretraživati po imenu, prezimenu, veštinama, interesovanju,znanju.</p>";
                                    break;

                            }
                    }
                    else
                    {

                        $topNavSearchData=array_reverse($topNavSearchData);

                        foreach ($topNavSearchData as $row)
                        {
                            switch($userType)
                            {
                                case 0:
                                    listAllMentorProfiles($row);
                                    break;
                                case 1:
                                    listAllUserProfiles($row);
                                    break;
                                default:
                                    if($row['userType']==1)
                                        listAllMentorProfiles($row);
                                    else
                                        listAllUserProfiles($row);
                                    break;

                            }

                        }
                    }
                ?>
            </div>
        </div>
        <div class="rightcolumn">
            <div class="card">
                <div class='additional-info'>
                <?php

                switch($userType)
                {
                    case 0:
                        $dataWaitingForApproval=$user->selectDataGeneric('requests',array('senderId','approvedReciever'),array($_SESSION['uID'],0));
                        $dataMentorHasApproved=$user->selectDataGeneric('requests',array('senderId','approvedReciever'),array($_SESSION['uID'],1));
                        if($dataWaitingForApproval)
                        {  
                            echo"<h2>Zahtevi koji čekaju na odobrenje:</h2>";
                            displayMentorsApprovedAndWaiting($dataWaitingForApproval,$user);
                        }
                        if($dataMentorHasApproved)
                        {  
                            echo"<h2>Mentori sa kojima ste uspostavili razmenu:</h2>";
                            displayMentorsApprovedAndWaiting($dataMentorHasApproved,$user);
                        }
                        break;
                    case 1:
                        $dataWaitingForApproval=$user->selectDataGeneric('requests',array('recieverId','approvedReciever'),array($_SESSION['uID'],0));
                        $dataIHaveApproved=$user->selectDataGeneric('requests',array('recieverId','approvedReciever'),array($_SESSION['uID'],1));
                        if($dataWaitingForApproval)
                        {  
                            echo"<h2>Koisnici koji čekaju na vaše odobrenje:</h2>";
                            displayUsersApprovedAndWaiting($dataWaitingForApproval,$user);
                        }
                        if($dataIHaveApproved)
                        {  
                            echo"<h2>Koisnici sa kojima ste uspostavili razmenu:</h2>";
                            displayUsersApprovedAndWaiting($dataIHaveApproved,$user);
                        }
                        break;
                    default:
                        ?>
                            <h2>Za više opcija:</h2>
                            <form action="./login.php" method="get">
                                <input type="submit" value="Prijavi se" class="button">
                            </form>
                            <p>Nemas kreiran nalog? <a href="./register.php">Registruj se</a></p>
                        <?php
                        break;
                }
                        ?>

                

                </div>
            </div>
        </div>
    </div>
    <?php
        include_once "./includes/footer.html";
    ?>
</body>
</html>