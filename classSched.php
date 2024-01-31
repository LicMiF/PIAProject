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
        $classSchedData=NULL;
        $errorStr=NULL;
        $user=new User();
        if (isset($_POST['scheduleClass']))
        {
            $date = $_POST['classDate'];
            $hours = $_POST['classHours'];
            $minutes = $_POST['classMinutes'];
            $class= $_POST['class'];
            $classDescr= $_POST['classDescr'];

            if(!($classId=validateClassScheduling($date,$hours,$minutes,$class,$classDescr,$_SESSION['uID'],$user)))
                $errorStr=$user->displayErrors();
            else
            {
                sendClassScheduledNotifications($_SESSION['uID'],$classId);
                header("Location: ".$_SERVER['PHP_SELF']);
            }
        }
        $classSchedData=$user->selectDataGeneric('classes',array('creatorId'),array($_SESSION['uID']));

        usort($classSchedData,'compareClassesTimestamps');
    ?>

    
    <div class="row">
        <div class="leftcolumn">
            <div class="card">
                <?php

                    if(empty($classSchedData))
                    {
                        echo "<h1>Nismo pronašli nijedan zakazan cas...</h1>";
                    }
                    else
                    {
                        foreach ($classSchedData as $row)
                        {
                            echo "<div class='scheduled-classes-container'>";

                                echo "<div class='scheduled-class-name '>";
                                    echo "<h3>".$row['className']."</h3>";
                                echo "</div>";

                                echo "<div class='short-descr'>";
                                    echo "<h4> Opis: </h4>";
                                    echo "<p>".$row['classDescription']."</p>";
                                echo "</div>";


                                $timeSched=explode('-',$row['classDate']);
                                $timeHeld=$timeSched[2].".".$timeSched[1].".".$timeSched[0].". u ".$timeSched[3]."h i ".$timeSched[4]."m";

                                echo "<div class='short-descr'>";
                                    echo "<h4> Vreme održavanja: </h4>";
                                    echo "<p>".$timeHeld."</p>";
                                echo "</div>";

                            
                                echo "<div class='request-view-buttons'>";
                                    echo "<input type='button' id=".$row['classId']." value='Otkaži' onclick='cancelClassCheck(this.id)' class='dangerButton'>";
                                echo "</div>";

                            echo "</div>";
                        }
                    }
                ?> 
            </div>
        </div>
        <div class="rightcolumn">
            <div class="card">
                <div class="availability-container" id="schedForm">
                    <div class='time-selection-header'>
                        <h2>Odaberite datum i vreme održavanja časa:</h2>
                    </div>
                    <?php
                        if(!$user->isEmptyErrors())
                            echo $errorStr;
                    ?>
                    <form action="<?=$_SERVER['PHP_SELF']?>" method='post'>
                        <input type="date" id="datepicker" name="classDate">
                        <div class="time-selection" id="classSched">
                                <select id="hourSelect" name="classHours">

                                </select>
                                <span>:</span>
                                <select id="minuteSelect" name="classMinutes">

                                </select>
                        </div>
                        <div class="input">
                            <div class='label'><strong>Predmet*:</strong></div>
                            <input type="text" name="class" placeholder="Predmet.." class='textField'>
                        </div>
                        <div class="input">
                            <div class='label'><strong>Opis*:</strong></div>
                            <textarea name='classDescr' style='height:70px;' placeholder='Unesite kratak opis časa..' class='textField'></textarea>
                        </div>
                        <input type="submit" name="scheduleClass" value="Zakaži čas" class="button">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="./core/classSched.js" defer ></script>
    <?php
        include_once "./includes/footer.html";
    ?>
</body>
</html>