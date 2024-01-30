<!DOCTYPE html>
<html lang="en">
<?php
    require_once "./core/utilities.php";
    include_once "./includes/head.php";
?>
<body>
    <?php
        allowAdminOnly();
        include_once "./includes/topnav.php";
        include_once "./includes/header.html";
    ?>

    <script src="./core/maintainingPosition.js" defer></script>
    <?php

        $mentorsActiveData=NULL;
        $usersActiveData=NULL;
        $mentorsNonActivatedData=NULL;
        $usersNonActivatedData=NULL;
        $user=new User();
        if (isset($_POST['searchUsers']))
        {
                $searchStr=$_POST['searchUsers'];
                if($searchStr!='')
                {
                    $topNavSearchData=$user->searchAll($searchStr);


                    $mentorsActiveData = array_filter($topNavSearchData, function($element) {
                        return ($element['userType'] == 1) && ($element['activate'] == 1);
                    });

                    $usersActiveData = array_filter($topNavSearchData, function($element) {
                        return ($element['userType'] == 0) && ($element['activate'] == 1);
                    });

                    $mentorsNonActivatedData = array_filter($topNavSearchData, function($element) {
                        return ($element['userType'] == 1) && ($element['activate'] == 0);
                    });

                    $usersNonActivatedData = array_filter($topNavSearchData, function($element) {
                        return ($element['userType'] == 0) && ($element['activate'] == 0);
                    });

                }
        }
        else{
            $mentorsActiveData=$user->getAllMentorData(1);
            $usersActiveData=$user->getAllUserData(1);

            $mentorsNonActivatedData=$user->getAllMentorData(0);
            $usersNonActivatedData=$user->getAllUserData(0);
        }

    ?>

    
    <div class="row">
        <div class="custom-column">
            <div class="card">
                <div class='additional-info'>
                <?php
                    $user=new User();
                    if(empty($mentorsActiveData))
                    {

                        echo "<h1>Nismo pronašli nijedog mentora...</h1>";
                        echo "<p>Mentore možete pretraživati po imenu, prezimenu, veštinama ili znanju.</p>";
                    }
                    else
                    {
                        echo "<h2>Mentori</h2>";
                        foreach ($mentorsActiveData as $row)
                            listAllPanelMentorProfiles($row);
                    }
                ?>
                </div>
            </div>
        </div>
        <div class="custom-column">
            <div class="card">
                <div class='additional-info'>
                    <?php
                        $user=new User();
                        if(empty($usersActiveData))
                        {

                            echo "<h1>Nismo pronašli nijedog korisnika...</h1>";
                            echo "<p>Korisnike možete pretraživati po imenu, prezimenu, veštinama ili interesovanjima.</p>";
                        }
                        else
                        {
                            echo "<h2>Korisnici</h2>";
                            foreach ($usersActiveData as $row)
                                listAllPanelUserProfiles($row);
                        }
                    ?>
                </div>
            </div>
        </div>
        <div class="custom-column">
            <div class="card">
                <div class='additional-info'>
                <?php
                        $user=new User();
                        if(empty($mentorsNonActivatedData))
                        {

                            echo "<h1>Nismo pronašli nijedog mentora koji čeka odobrenje profila...</h1>";
                            echo "<p>Mentore možete pretraživati po imenu, prezimenu, veštinama ili znanju.</p>";
                        }
                        else
                        {
                            echo "<h2>Mentori koji čekaju na aktiviranje profila</h2>";
                            foreach ($mentorsNonActivatedData as $row)
                                listAllPanelMentorProfiles($row);
                        }
                        ?>
                </div>
            </div>
        </div>
        <div class="custom-column">
            <div class="card">
                <div class='additional-info'>
                <?php
                        $user=new User();
                        if(empty($usersNonActivatedData))
                        {

                            echo "<h1>Nismo pronašli nijedog korisnika koji čeka na odobrenje profila...</h1>";
                            echo "<p>Korisnike možete pretraživati po imenu, prezimenu, veštinama ili interesovanjima.</p>";
                        }
                        else
                        {
                            echo '<h2>Korisnici koji čekaju na aktiviranje profila</h2>';
                            foreach ($usersNonActivatedData as $row)
                                listAllPanelUserProfiles($row);
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