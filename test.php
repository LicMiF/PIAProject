<!DOCTYPE html>
<html lang="en">
<?php
    require_once "./core/utilities.php";
    include_once "./includes/head.php";
    forbidAccesNonLogged();
?>
<head>
<style>
    .user-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .user-info {
        display: flex;
        align-items: center;
    }

    .user-image img {
        width: 50px; /* Adjust the width as needed */
        height: 50px; /* Adjust the height as needed */
        margin-right: 10px; /* Add some spacing between image and text */
        border-radius: 50%; /* Make it a circular image */
    }

    .button-container {
        display: flex;
        flex-direction: column;
    }
</style>

</head>
<body>
    <?php
        include_once "./includes/topnav.php";
        include_once "./includes/header.html";
    ?>
    
    <div class="row">
        <div class="leftcolumn">
            <div class="card">
                <?php
                    if($_SESSION['userType']){
                        echo "<h1>Prihvaceni zahtevi</h1>";
                        $request=new Request();
                        $sentRequests=$request->fetchApprovedRequests($_SESSION['uID']);
                        
                        if(empty($sentRequests)){
                            echo "Niste pihvatili nijedan zahtev";
                        }else{
                            echo '<table cellpadding="5" style="border-collapse:collapse">';
                            foreach($sentRequests as $sentRequest){
                                $id=$sentRequest['senderId'];
                                $mentorData=$request->getUserData($id);
                                echo "<tr class='sent-requests-row'>";
                                echo "<td>".$mentorData['firstName']."</td>";
                                echo "<td>".$mentorData['lastName']."</td>";
                                echo "<td>".$mentorData['username']."</td>";
                                echo "<td>".$mentorData['mail']."</td>";
                                echo "</tr>";
                            }
                            echo "</table>";
                        }

                    }
                    else{
                        echo "<h1>Mentori kojima ste poslali zahtev</h1>";
                        $request=new Request();
                        $sentRequests= $request->fetchRequestsUser($_SESSION['uID']);
                        
                        
                        if(empty($sentRequests)){
                            echo "Niste slali zahteve";
                        }else{
                            echo '<table cellpadding="5" style="border-collapse:collapse">';
                            echo '<tr><th>Ime</th><th>Prezime</th><th>Korisnicko ime</th><th>E-mail</th>';
                            foreach($sentRequests as $sentRequest){
                                $id=$sentRequest['recieverId'];
                                $mentorData=$request->getUserData($id);
                                echo "<tr class='sent-requests-row'>";
                                echo "<td>".$mentorData['firstName']."</td>";
                                echo "<td>".$mentorData['lastName']."</td>";
                                echo "<td>".$mentorData['username']."</td>";
                                echo "<td>".$mentorData['mail']."</td>";
                                echo "</tr>";
                            }
                            echo "</table>";
                        }
                    }
                ?>
            </div>
            <div class="card">
                <?php
                    $user=new User();
                    if($_SESSION['userType'])
                    {
                        $data=$user->getUsersWithRequestsToMentor($_SESSION['uID']);
                        if(empty($data)){
                            echo 'Nijedan korisnik vam nije poslao zahtev';
                        }        
                        else{
                            echo "<h1>Korisnici:</h1>";
                            echo '<div>';
                            foreach ($data as $row) {
                                $row1 = $user->getDataFromRequest($row['senderId']);
                                foreach ($row1 as $row2) {
                                    echo "<div class='user-row' data-userid='" . $row2['userId'] . "' style='background-color: #04473e; margin-bottom: 20px; padding: 10px;'>";
                                    
                                    // Left side with user information
                                    echo "<div class='user-info'>";
                                    echo "<div class='user-image'>
                                            <img src='./uploads/" . $row2['profileImagePath'] . "' alt='User Icon'>
                                            </div>";
                                    echo "<span>" . $row2['firstName'] . " " . $row2['lastName'] . "</span>";
                                    echo "</div>";

                                    // Right side with buttons
                                    echo "<div class='button-container'>";
                                    echo "<input type='button' id=" . $row2['userId'] . " value='Odobri zahtev' onclick='approve(this.id)' class='button'>";
                                    echo "<input type='button' id=" . $row2['userId'] . " value='Odbij zahtev' onclick='refuse(this.id)' class='button'>";
                                    echo "</div>";

                                    echo "</div>";
                                }
                            }
                            echo '</div>';

                            }
                    }
                    else{
                        $data=$user->getAvailableMentors($_SESSION['uID']);
                        echo "<h1>Mentori kojima mozete poslati zahtev:</h1>";

                        if(empty($data)){
                            echo 'Ne postoji mentor kome bi mogli da posaljete zahtev';
                        }
                        else{
                            
                            foreach($data as $row)
                            {
                                echo "<div class='mentor-row' data-userid='" . $row['userId'] . "' style='background-color: #04473e; margin-bottom: 20px; padding: 10px;'>";
                                echo "<div class='user-image'>
                                        <img src='./uploads/".$row['profileImagePath']."' alt='User Icon'>
                                        </div>";
                                echo "<span>".$row['firstName']." ".$row['lastName']."  "."</span>";
                                echo "<input type='button' id=" . $row['userId'] . " value='Posalji zahtev' onclick='send(this.id)' class='button'>";

                                echo "</div>";
                            }
                            
                        }
                    }
                ?>
            </div>
        </div>
    
        <div class="rightcolumn">
            <div class="card">
                <h2>Pocetna</h2>
                <?php
                    $user=new User();
                    if(isset($_SESSION['uID']))
                    {
                        $data=$user->getUserData($_SESSION['uID']);
                        $_SESSION['userType']=$data['userType'];
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