<?php
    require_once "userDB.php";
    require_once "containerClass.php";
    require_once 'requestsDB.php';

    function renderForm($fields,$types,$submit,$action,$method)
    {
        echo "<form action=$action method=$method>";
        $counter=0;
        foreach($fields as $key => $val)
        {
            echo "<div class=input>";
            echo "<div class='label'><strong>".$key.": "."</strong></div>";
            echo "<input type='".$types[$counter]."' name=$val placeholder='$key' class='textField'> <br/>";
            echo "</div>";
            $counter++;
        }
        echo '<br/>';
        foreach($submit as $key => $val)
            echo "<input type='submit' name=$val value='$key' class='button'><br/>";
        echo "</form>";
    }


    /* Values of radio are by default stored in radio[] array*/
    /*In order to work properly, name of the field that will be checeked should be checkPass*/ 
    function renderFormWithPasswordStrengthCheckAndRadios($fields,$types,$submit,$action,$method,$radios)
    {

        echo "<form action=$action method=$method>";
        $counter=0;

        foreach($fields as $key => $val)
        {
            if($val=='checkPass')
            {
                displayCheck($key);
                $counter++;
                continue;
            }
            echo "<div class=input>";
            echo "<div class='label'><strong>".$key.": "."</strong></div>";
            echo "<input type='".$types[$counter]."' name=$val placeholder='$key' class='textField'> <br/>";
            echo "</div>";
            $counter++;
        }

        if($radios)
            foreach($radios as $key => $val)
            {
                echo "<div class=input>";
                echo "<div class='label'><strong>".$key.": "."</strong></div>";
                echo "<input type='radio' name='radio[]' class='radio' value=$val>";
                echo "</div>";
            }
    
        echo '<br/>';
        foreach($submit as $key => $val)
            echo "<input type='submit' name=$val value='$key' class='button'><br/>";
        echo "</form>";
    }

    function displayCheck($value)
    {
        echo '<div class="input">
        <div class="label"><strong>'.$value.'</strong></div>
        <input type="password" placeholder="Password" class="password-input" name="checkPass">
        <i class="fa-solid fa-eye show-password"></i>

        <div class="password-checklist">
            <h3 class="checklist-title">Password should be</h3>
            <ul class="checklist">
                <li class="list-item">At least 8 character long</li>
                <li class="list-item">At least 1 number</li>
                <li class="list-item">At least 1 lowercase letter</li>
                <li class="list-item">At least 1 uppercase letter</li>
                <li class="list-item">At least 1 special character</li>
            </ul>

        </div>
        </div>
        ';
    }


    /* Values of radio are by default stored in radio[] array*/
    /*In order to work properly, name of the field that will be checeked should be checkPass*/ 
    function renderFormRegistrationSpecific($fields,$types,$studentSpecific,$mentorSpecific,$submit,$action,$method)
    {

        echo '<div class="choice">';
        
        echo '<button id="studentBtn" class="user-type-btn" onclick="showForm(\'student\')">Student</button>';
        echo '<button id="mentorBtn" class="user-type-btn" onclick="showForm(\'mentor\')">Mentor</button>';

        echo  '</div>';
        
        echo "<form action=$action method=$method>";

        registrationFormRenderingHelper($fields,$types);

        echo '<div id="studentForm" class="form-section show-form">';

        $studentTypes=array();
        for($i=0; $i<count($studentSpecific); $i++)
            $studentTypes[$i]='text';

        registrationFormRenderingHelper($studentSpecific,$studentTypes,'Registruj se',$submit[0]);


        echo  '</div>';
        
        echo '<div id="mentorForm" class="form-section">';

        $mentorTypes=array();
        for($i=0; $i<count($mentorSpecific); $i++)
            $mentorTypes[$i]='text';
    
        registrationFormRenderingHelper($mentorSpecific,$mentorTypes,'Registruj se',$submit[1]);

        echo  '</div>';

        echo "</form>";
        
    }

    function registrationFormRenderingHelper($fields,$types,$submitKey=NULL,$submitValue=NULL)
    {
        $counter=0;
        foreach($fields as $key => $val)
        {
            if($val=='checkPass')
            {
                displayCheck($key);
                $counter++;
                continue;
            }
            echo "<div class=input>";
            echo "<div class='label'><strong>".$key.": "."</strong></div>";
            echo "<input type='".$types[$counter]."' name=$val placeholder='$key' class='textField'> <br/>";
            echo "</div>";
            $counter++;
        }
        if($submitKey && $submitValue)
        {
            echo '<br/>';
            echo "<input type='submit' name=$submitValue value='$submitKey' class='button'><br/>";
        }
    }

    function validateLogin($username,$password, &$user)
    {   
        if(empty($username) || empty($password))
        {
            $user->appendError('Molimo vas da popunite oba polja');
            return false;
        }
        else if(!($uID=$user->validateUser($username,$password)))
        {
            $user->appendError('Neispravna kombinacija Korisničko ime/ Šifra');
            return false;
        }
        return $uID;
    }

    function paswordsChecker($password,$passwordAgain,&$user)
    {
        if (strlen($password)<8)
            $user->appendError('Nedovoljno dugacka sifra');

        if ($password !== $passwordAgain)
            $user->appendError('Sifra i ponovljena sifra se razlikuju');

  
        $containsNumber = preg_match('/\d/', $password);
        $containsLowercase = preg_match('/[a-z]/', $password);
        $containsUppercase = preg_match('/[A-Z]/', $password);
        $containsSpecialChar = preg_match('/[^A-Za-z0-9]/', $password);

        if($containsNumber && $containsLowercase && $containsUppercase && $containsSpecialChar)
            return ;

        $user->appendError('Sifra nije dovoljno jaka, molimo vas da ispunite sve zahteve!');
        
    }

    function validateRegister($username,$password,$passwordAgain,$mail,$firstName,$lastName,$userType,$skills,&$user)
    {   
        if(empty($username) || empty($password) || empty($passwordAgain) || empty($mail) || empty($firstName) || empty($skills))
        {
            $user->appendError('Molimo vas da popunite sva polja pored kojih stoji *');
            return false;
        }
        if ($user->userExists($username))
            $user->appendError('Korisnicko ime je zauzeto :(');

        if (strlen($username)>31)
            $user->appendError('Korisnicko ime je predugo, dozvoljeni maksimum je 32 karaktera');

        if (!filter_var($mail,FILTER_VALIDATE_EMAIL))
            $user->appendError('Uneta mejl adresa nije u ispravnom formatu');

        if ($user->mailExists($mail))
            $user->appendError('Email adresa je vec registrovana :(');

        if (strlen($firstName)>31)
            $user->appendError('Ime je predugo, dozvoljeni maksimum je 32 karaktera');

        if (strlen($lastName)>31)
            $user->appendError('Prezime je predugo, nadamo se da nisi ti: Wolfeschlegelsteinhausenbergerdorff');
        

        paswordsChecker($password,$passwordAgain,$user);
        
        if(!$user->isEmptyErrors())
            return false;

        $columns=array('username', 'password', 'mail', 'firstName', 'lastName', 'emailHash', 'userType','skills');
        $pass=$user->hashPassword($password);
        $emailHash=$user->hashMail($mail);
        $values=array($username,$pass,$mail,$firstName,$lastName,$emailHash,$userType,$skills);
        
        if(!($uID=$user->insertDataGeneric($columns,$values,'users')))
        {
            $user->appendError('Greska na serveru, pokusajte ponovo :(');
            return false;
        }
        return $uID;
    }
    
    function validatePassChange($currPass,$newPass ,$newPassAgain,$uID,&$user)
    {
        if(empty($currPass) || empty($newPass) || empty($newPassAgain))
        {
            $user->appendError('Molimo vas da popunite sva polja pored kojih stoji *');
            return false;
        }
        if (!$user->checkCurrentPass($currPass,$uID))
            $user->appendError('Neispravna trenutna sifra');

        paswordsChecker($newPass,$newPassAgain,$user);

        if(!$user->isEmptyErrors())
            return false;
        
        if(!($user->changePass($newPass,$uID)))
        {
            $user->appendError('Greska na serveru, pokusajte ponovo :(');
            return false;
        }

        return true;

    }

    function validateSettingsChange($mail,$firstName ,$lastName,$uID,&$user)
    {
        if(empty($mail) || empty($firstName) || empty($lastName))
        {
            $user->appendError('Molimo vas da popunite sva polja pored kojih stoji *');
            return false;
        }
        if ($mail !== $user->getUserMail($uID))
        {   
            if (!filter_var($mail,FILTER_VALIDATE_EMAIL))
                $user->appendError('Uneta mejl adresa nije u ispravnom formatu');

            if ($user->mailExists($mail))
                $user->appendError('Email adresa je vec registrovana :(');
        }
        if (strlen($firstName)>31)
            $user->appendError('Ime je predugo, dozvoljeni maksimum je 32 karaktera');

        if (strlen($lastName)>31)
            $user->appendError('Prezime je predugo, nadamo se da nisi ti: Wolfeschlegelsteinhausenbergerdorff');

        if(!$user->isEmptyErrors())
            return false;

        if(!($uID=$user->updateInfo($mail,$firstName ,$lastName,$uID)))
        {
            $user->appendError('Greska na serveru, pokusajte ponovo :(');
            return false;
        }
        return true;

    }


    function validateComments($body,$senderId,$recieverId,&$user)
    {
        
        $comment = trim($body);

        if (empty($comment)) 
            $user->appendError('Komentari ne mogu biti prazni!');
        
        $minLength = 10;
        $maxLength = 2000;
    
        $commentLength = strlen($comment);
    
        if ($commentLength < $minLength || $commentLength > $maxLength) 
            $user->appendError("Komentari moraju biti izmedju $minLength i $maxLength karaktera dugi");
    
        if(!$user->isEmptyErrors())
            return false;

        $columns=array('senderId','recieverId','body');
        $values=array($senderId,$recieverId,$comment);

        if(!($uID=$user->insertDataGeneric($columns,$values,'comments')))
        {
            $user->appendError('Greska na serveru, pokusajte ponovo :(');
            return false;
        }
        return true;

    }


    function forbidAccesLogged()
    {
        if(isset($_SESSION['uID']))
        {
            header("Location: ./index.php");
            exit();
        }
    } 

    function forbidAccesNonLogged()
    {
        if(!isset($_SESSION['uID']))
        {
            echo "<h1>Acces forbbiden!</h1>";
            //header("Location: ./index.php");
            exit();
        }
    }


    function fillTheContainer(&$container)
    {
        $requests= new Request();
        if ($_SESSION['userType'] === 1)
        {
            $container->setRequests($requests->fetchRequestsMentor($_SESSION['uID']));
        }
        else
        {
            $container->setRequests($requests->fetchRequestsUser($_SESSION['uID']));
        }
    }

    function updateContainer(&$container)
    {
        $requests= new Request();
        if ($_SESSION['userType'] === 1)
        {
            $container->setRequests($requests->fetchRequestsMentor($_SESSION['uID']));
        }
        else
        {
            $container->setRequests($requests->fetchRequestsUser($_SESSION['uID']));
        }
    }

    function displayBasicUserInfoNotificationsButtons($data,$userType)
    {
        echo '<table border="1" cellpadding="5" style="border-collapse:collapse">';
                    
        echo "<tr><th>Korisnicko ime</th><th>Ime</th><th>prezime</th><th>mejl</th><th></th></tr>";
        foreach($data as $row)
        {
            echo "<tr>";
            echo "<td>".$row['username']."</td>";
            echo "<td>".$row['firstName']."</td>";
            echo "<td>".$row['lastName']."</td>";
            echo "<td>".$row['mail']."</td>";
            if($userType ===1 )
            {
                echo "<td> <input type='button' id=".$row['userId']." value='Odobri zahtev' onclick='approve(this.id)' class='button' ></td>";
            }
            else
            {
                echo "<td> <input type='button' id=".$row['userId']." value='Posalji zahtev' onclick='send(this.id)' class='button' ></td>";
            }
            echo "</tr>";
        }
        echo '</table>';
    }

    function displayBasicUserInfoNotificationsNoButtons($data)
    {
        echo '<table border="1" cellpadding="5" style="border-collapse:collapse">';
                    
        echo "<tr><th>Korisnicko ime</th><th>Ime</th><th>prezime</th><th>mejl</th></tr>";
        foreach($data as $row)
        {
            echo "<tr>";
            echo "<td>".$row['username']."</td>";
            echo "<td>".$row['firstName']."</td>";
            echo "<td>".$row['lastName']."</td>";
            echo "<td>".$row['mail']."</td>";
            echo "</tr>";
        }
        echo '</table>';
    }

    function displayNotificationsForUser($dataWaiting,$dataApproved)
    {
        if (empty($dataWaiting) && empty($dataApproved))
            echo "<h1>There are no sent requests!</h1>";
        else
        {
            if(!empty($dataWaiting))
            {
                echo "<h1>Sent requests waiting for approval:</h1>";
                displayBasicUserInfoNotificationsNoButtons($dataWaiting);
            }
            if(!empty($dataApproved))
            {
                echo "<h1>Approved sent requests:</h1>";
                displayBasicUserInfoNotificationsNoButtons($dataApproved);
            }
        }
    }

    function displayNotificationsForMentor($dataWaiting,$dataApproved)
    {
        if (empty($dataWaiting) && empty($dataApproved))
            echo "<h1>There are no recieved requests :(</h1>";
        else
        {
            if(!empty($dataWaiting))
            {
                echo "<h1>Requests waiting for your approval:</h1>";
                displayBasicUserInfoNotificationsButtons($dataWaiting,1);
            }
            if(!empty($dataApproved))
            {
                echo "<h1>Approved requests:</h1>";
                displayBasicUserInfoNotificationsNoButtons($dataApproved);
            }
        }
    }

    function displayCommentsSection($profileId,&$user)
    {
        $comments=$user->selectDataGeneric('comments',array('recieverId'),array($profileId));

        foreach ($comments as $comment)
        {
            echo '<div class="comment">';

            $senderData=$user->getUserData($comment['senderId']);

            echo "  <div class='comment-header'>
                        <div class='user-image'>
                            <img src='./uploads/img2.jpg' alt='User Icon'>
                        </div>
                        <span>".$senderData['firstName']." ".$senderData['lastName']."</span>
                    </div>";//Add $senderData image

            echo "  <div class='comment-body'>
                        ".$comment['body']."
                    </div>";


            $dateTime = new DateTime($comment['timestamp']);
            $formattedDate = $dateTime->format('Y-m-d H:i:s');

            echo "  <div class='comment-footer'>
                        <div class='like-btns'>
                            <i class='fas fa-thumbs-up like-btn'></i>
                            <i class='fas fa-thumbs-down dislike-btn'></i>
                        </div>
                        <span>Posted on: ".$formattedDate."</span>
                    </div>";
            
            echo ' </div>';
        }
    }

    function displayCommentForm($profileId,$action,$method,$userType,&$user)
    {
        echo "
        <div class='comment-form'>
            <form action='$action' method='$method' id='commentFormId'>
                <textarea name='commentSection' style='height:70px;' placeholder='Unesite vas komentar...' class='textField'></textarea>
                <input type='hidden' name='profileId' value='$profileId'>
                <input type='hidden' name='userType' value='$userType'>
                <input type='submit' name='postComment' value='Komentarisi' class='button'>
            
            </form>
        </div>";
    }

    function displayUserProfileDataUser($profileId,&$user)
    {

        $userData=$user->getUserData($profileId);
        $userSpecific=$user->selectDataGeneric('userSpecific',array('userId'),array($profileId))[0];

        echo "  <div class='profile-header'>
                    <div class='profile-image'>
                        <img src='./uploads/img1.jpg' alt='Profile Picture'>
                    </div>
                    <div class='profile-username'>".$userData['firstName']." ".$userData['lastName']."</div>
                    <div class='profile-bio'>Web Developer | Nature Lover | Coffee Enthusiast</div>
                </div>";

        echo "  <div class='profile-section'>
                    <h2>Informacije o korisniku</h2>
                    <div class='profile-details'>
                        <div class='detail-item'>
                            <div class='detail-item-header'>Korisnicko Ime</div>
                            <div>".$userData['username']."</div>
                        </div>
                        <div class='detail-item'>
                            <div class='detail-item-header'>Ime</div>
                            <div>".$userData['firstName']."</div>
                        </div>
                        <div class='detail-item'>
                            <div class='detail-item-header'>Prezime</div>
                            <div>".$userData['lastName']."</div>
                        </div>
                        <div class='detail-item'>
                            <div class='detail-item-header'>Email</div>
                            <div>".$userData['mail']."</div>
                        </div>
                    </div>
                </div>                                      ";
        echo "  <div class='profile-section'>
                    <h2>Interesovanja</h2>
                    <div class='profile-details'>
                        <div class='detail-item'>
                            <div class='detail-item-header'> Interesovanja</div>
                            <div>".$userSpecific['interests']."</div>
                        </div>
                    </div>
                </div>                          ";
        echo "  <div class='profile-section'>
                    <h2>Obrazovanje</h2>
                    <div class='profile-details'>
                        <div class='detail-item'>
                            <div class='detail-item-header'>Obrazovanje</div>
                            <div>".$userSpecific['education']."</div>
                        </div>
                    </div>
                </div>                          ";
        
    }

    function displayUserProfileDataMentor($profileId,&$user)
    {

        $userData=$user->getUserData($profileId);
        $mentorSpecific=$user->selectDataGeneric('mentorSpecific',array('userId'),array($profileId))[0];

        echo "  <div class='profile-header'>
                    <div class='profile-image'>
                        <img src='./uploads/img1.jpg' alt='Profile Picture'>
                    </div>
                    <div class='profile-username'>".$userData['firstName']." ".$userData['lastName']."</div>
                    <div class='profile-bio'>Web Developer | Nature Lover | Coffee Enthusiast</div>
                </div>";

        echo "  <div class='profile-section'>
                    <h2>Informacije o korisniku</h2>
                    <div class='profile-details'>
                        <div class='detail-item'>
                            <div class='detail-item-header'>Korisnicko Ime</div>
                            <div>".$userData['username']."</div>
                        </div>
                        <div class='detail-item'>
                            <div class='detail-item-header'>Ime</div>
                            <div>".$userData['firstName']."</div>
                        </div>
                        <div class='detail-item'>
                            <div class='detail-item-header'>Prezime</div>
                            <div>".$userData['lastName']."</div>
                        </div>
                        <div class='detail-item'>
                            <div class='detail-item-header'>Email</div>
                            <div>".$userData['mail']."</div>
                        </div>
                    </div>
                </div>                                      ";
        echo "  <div class='profile-section'>
                    <h2>Znanje</h2>
                    <div class='profile-details'>
                        <div class='detail-item'>
                            <div class='detail-item-header'> Znanje</div>
                            <div>".$mentorSpecific['knowledge']."</div>
                        </div>
                    </div>
                </div>                          ";
        echo "  <div class='profile-section'>
                    <h2>Godine iskustva</h2>
                    <div class='profile-details'>
                        <div class='detail-item'>
                            <div class='detail-item-header'>Godine iskustva</div>
                            <div>".$mentorSpecific['yearExp']."</div>
                        </div>
                    </div>
                </div>                          ";
        
    }
?>