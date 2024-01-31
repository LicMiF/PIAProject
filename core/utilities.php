<?php
    require_once "userDB.php";
    require_once "containerClass.php";
    require_once 'requestsDB.php';
    require_once 'userMentorDB.php';

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
        <input type="password" placeholder="Šifra" class="password-input" name="checkPass">
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
        else if($user->checkActivate($username)['activate']==='0'){
            $user->appendError('Vas nalog nije aktiviran');
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

    function validateRegisterUser($username,$password,$passwordAgain,$mail,$firstName,$lastName,$userType,$skills,$education,$interests,&$user)
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
        
        if (strlen($skills)>4000)
            $user->appendError('Prekoracili ste dozvoljen limit karaktera za opis vestina');
        
        if (strlen($education)>2000)
            $user->appendError('Prekoracili ste dozvoljen limit karaktera za opis obrazovanja');

        if (strlen($interests)>4000)
            $user->appendError('Prekoracili ste dozvoljen limit karaktera za opis interesovanja');
        
        paswordsChecker($password,$passwordAgain,$user);
        
        if(!$user->isEmptyErrors())
            return false;

        $columns=array('username', 'password', 'mail', 'firstName', 'lastName', 'emailHash', 'userType','skills');
        $pass=$user->hashPassword($password);
        $emailHash=$user->hashMail($mail);
        $values=array(strip_tags($username),$pass,strip_tags($mail),strip_tags($firstName),strip_tags($lastName),$emailHash,$userType,strip_tags($skills));
        
        if(!($uID=$user->insertDataGeneric($columns,$values,'users')))
        {
            $user->appendError('Greska na serveru, pokusajte ponovo :(');
            return false;
        }

        $columns=array('userId','education','interests');
        $values=array($uID,$education,$interests);
        
        
        if(!($user->insertDataSpecific($columns,$values,'userSpecific')))
        {
            $user->appendError('Greska na serveru, pokusajte ponovo :(');
            return false;
        }

        return true;
    }

    function validateRegisterMentor($username,$password,$passwordAgain,$mail,$firstName,$lastName,$userType,$skills,$knowledge,$yearExp,&$user)
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
        
        if (strlen($skills)>4000)
            $user->appendError('Prekoracili ste dozvoljeni limit karaktera za opis vestina');


        if (!preg_match('/^[0-9-]+$/', $yearExp)) 
            $user->appendError('Godine iskustva moraju sadržati samo brojeve i karakter -');
        
        if (strlen($yearExp)>10)
            $user->appendError('Prekoracili ste dozvoljen limit karaktera za opis godina iskustva');

        if (strlen($knowledge)>4000)
            $user->appendError('Prekoracili ste dozvoljen limit karaktera za opis znanja');
        
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

        $columns=array('userId','knowledge','yearExp');
        $values=array($uID,$knowledge,$yearExp);
        
        if(!($user->insertDataSpecific($columns,$values,'mentorSpecific')))
        {
            $user->appendError('Greska na serveru, pokusajte ponovo :(');
            return false;
        }

        return true;
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

    function validateSettingsChangeUser($mail,$firstName ,$lastName,$skills,$education,$interests,$uID,&$user)
    {

        //DODATO
        $mail=strip_tags($mail);
        $firstName=strip_tags($firstName);
        $lastName=strip_tags($lastName);
        $skills=strip_tags($skills);
        $education=strip_tags($education);
        $interests=strip_tags($interests);
        //DODATO

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

        
        if (strlen($skills)>4000)
            $user->appendError('Prekoracili ste dozvoljeni limit karaktera za opis vestina');

        if (strlen($education)>2000)
            $user->appendError('Prekoracili ste dozvoljen limit karaktera za opis obrazovanja');

        if (strlen($interests)>4000)
            $user->appendError('Prekoracili ste dozvoljen limit karaktera za opis interesovanja');

        if(!$user->isEmptyErrors())
            return false;
        $cols=array("education","interests");
        $vals=array($education,$interests);

        $columns=array('mail','firstName','lastName','skills');
        $values=array($mail,$firstName ,$lastName,$skills);
        if(!($user->updateDataGeneric('users',$columns,$values,array('userId'),array($uID))))
        {
            $user->appendError('Greska na serveru, pokusajte ponovo :(');
            return false;
        }

        $cols=array("education","interests");
        $vals=array($education,$interests);
        if(!($user->updateDataGeneric('userSpecific',$cols,$vals,array('userId'),array($uID))))
        {
            $user->appendError('Greska na serveru, pokusajte ponovo :(');
            return false;
        }

        return true;

    }

    function validateSettingsChangeMentor($mail,$firstName ,$lastName,$skills,$yearExp,$knowledge,$uID,&$user)
    {
        //DODATI DEO
        $mail=strip_tags($mail);
        $firstName=strip_tags($firstName);
        $lastName=strip_tags($lastName);
        $skills=strip_tags($skills);
        $yearExp=strip_tags($yearExp);
        $knowledge=strip_tags($knowledge);
        //DODATI DEO
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

        
        if (strlen($skills)>4000)
            $user->appendError('Prekoracili ste dozvoljeni limit karaktera za opis vestina');

        if (!preg_match('/^[0-9-]+$/', $yearExp)) 
            $user->appendError('Godine iskustva moraju sadržati samo brojeve i karakter -');
        
        if (strlen($yearExp)>10)
            $user->appendError('Prekoracili ste dozvoljen limit karaktera za opis godina iskustva');

        if (strlen($knowledge)>4000)
            $user->appendError('Prekoracili ste dozvoljen limit karaktera za opis znanja');

        if(!$user->isEmptyErrors())
            return false;

        $columns=array('mail','firstName','lastName','skills');
        $values=array($mail,$firstName ,$lastName,$skills);
        if(!($user->updateDataGeneric('users',$columns,$values,array('userId'),array($uID))))
        {
            $user->appendError('Greska na serveru, pokusajte ponovo :(');
            return false;
        }

        $columns=array('yearExp','knowledge');
        $values=array($yearExp,$knowledge);

        if(!($user->updateDataGeneric('mentorSpecific',$columns,$values,array('userId'),array($uID))))
        {
            $user->appendError('Greska na serveru, pokusajte ponovo :(');
            return false;
        }
        return true;

    }


    function validateComments($body,$senderId,$recieverId,&$user)
    {
        
        $comment = trim($body);
        $comment= htmlspecialchars($comment);

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

    function forbidAccesMentors()
    {
        if($_SESSION['userType']==1)
        {
            header("Location: ./index.php");
            exit();
        }
    }

    function allowAdminOnly()
    {
        if($_SESSION['userType']!=2)
        {
            header("Location: ./index.php");
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
            echo "<h1>Nemate poslatih zahteva!</h1>";
        else
        {
            if(!empty($dataWaiting))
            {
                echo "<h1>Zahtevi koji čekaju na odobrenje:</h1>";
                displayBasicUserInfoNotificationsNoButtons($dataWaiting);
            }
            if(!empty($dataApproved))
            {
                echo "<h1>Odobreni zahtevi:</h1>";
                displayBasicUserInfoNotificationsNoButtons($dataApproved);
            }
        }
    }

    function displayNotificationsForMentor($dataWaiting,$dataApproved)
    {
        if (empty($dataWaiting) && empty($dataApproved))
            echo "<h1>Nemate upućenih zahteva</h1>";
        else
        {
            if(!empty($dataWaiting))
            {
                echo "<h1>Zahtevi koji čekaju na vaše odobrenje:</h1>";
                displayBasicUserInfoNotificationsButtons($dataWaiting,1);
            }
            if(!empty($dataApproved))
            {
                echo "<h1>Zahtevi koje ste prihvatili:</h1>";
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
                            <img src='./uploads/".$senderData['profileImagePath']."' alt='User Icon'>
                        </div>
                        <span>".$senderData['firstName']." ".$senderData['lastName']."</span>";
                    

                    if ($_SESSION['userType']==2)
                    {
                        echo '<a class="comment-delete-link" " href="" id="'. $comment['commentId'] .'" onclick="deleteComment(this.id)">Ukloni</a>';
                    }
                            
                    echo "</div>";

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
                <textarea name='commentSection' style='height:70px;' placeholder='Unesite vaše mišljenje o mentoru...' class='textField'></textarea>
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
        $skillsArray = explode(',', $userData['skills']);
        $skills = implode(' | ', $skillsArray);
        echo "  <div class='profile-header'>
                    <div class='profile-image'>
                        <img src='./uploads/".$userData['profileImagePath']."' alt='Profile Picture'>
                    </div>
                    <div class='profile-username'>".$userData['firstName']." ".$userData['lastName']."</div>
                    <div class='profile-bio'>".$skills."</div>
                </div>";

        echo "  <div class='profile-section'>
                    <h2>Informacije o korisniku</h2>
                    <div class='profile-details'>
                        <div class='detail-item'>
                            <div class='detail-item-header'>Korisničko Ime</div>
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
                            <div class='detail-item-header'></div>
                            <div>".$userSpecific['interests']."</div>
                        </div>
                    </div>
                </div>                          ";
        echo "  <div class='profile-section'>
                    <h2>Obrazovanje</h2>
                    <div class='profile-details'>
                        <div class='detail-item'>
                            <div class='detail-item-header'></div>
                            <div>".$userSpecific['education']."</div>
                        </div>
                    </div>
                </div>                          ";
        
    }

    function displayUserProfileDataMentor($profileId,&$user)
    {

        $userData=$user->getUserData($profileId);
        $mentorSpecific=$user->selectDataGeneric('mentorSpecific',array('userId'),array($profileId))[0];
        $skillsArray = explode(',', $userData['skills']);
        $skills = implode(' | ', $skillsArray);
        echo "  <div class='profile-header'>
                    <div class='profile-image'>
                        <img src='./uploads/".$userData['profileImagePath']."' alt='Profile Picture'>
                    </div>
                    <div class='profile-username'>".$userData['firstName']." ".$userData['lastName']."</div>
                    <div class='profile-bio'>".$skills."</div>
                </div>";

        echo "  <div class='profile-section'>
                    <h2>Informacije o korisniku</h2>
                    <div class='profile-details'>
                        <div class='detail-item'>
                            <div class='detail-item-header'>Korisničko Ime</div>
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


        $timeAvail=explode('-',$mentorSpecific['timeAvailability']);
        echo "  <div class='profile-section'>
                <h2>Mentor je dostupan u periodu:</h2>
                <div class='profile-details'>
                    <div class='detail-item'>
                        <div class='detail-item-header' style='font-size:1.5em;'>".$timeAvail[0]." : ".$timeAvail[1]." ".$timeAvail[2]." - ".$timeAvail[3]." : ".$timeAvail[4]." ".$timeAvail[5]."</div>
                    </div>
                </div>
            </div> <br>                         ";
        
    }

    /*Messages displaying */

    function displayDmUsers($usersData,&$user)
    {
        if (!empty($usersData)) {
            echo '<div class="user-list">';
            usort($usersData, function ($a, $b) {
                return $b['unreadCount'] - $a['unreadCount'];
            });
            foreach ($usersData as $userData) {
                echo "<div class='user-container'>";
                echo "  <div class='user-image'>
                            <img src='./uploads/".$userData['profileImagePath']."' alt='User Icon'>
                        </div>";
                echo '<a class="user-link" href="?userId=' . $userData['userId'] . '">' . $notificationIndicator . " " . $userData['firstName']." ".$userData['lastName'] . '</a><br>';
                echo "</div>";
            }
            echo '</div>';
        } else {
            echo "Nemate nijednu uspostavljenu razmenu..";
        }
    }

    function displayUserDm($targetUserId, &$user)
    {
        $chatPartner= $user->selectDataGeneric('users',array('userId'),array($targetUserId))[0];
        echo "<h2>".$chatPartner['firstName']." ".$chatPartner['lastName']."</h2>";
        $myMessages = $user->showMyMessages($_SESSION['uID'], $targetUserId);
        $yourMessages = $user->showYourMessages($_SESSION["uID"], $targetUserId);
        
        if (!is_array($myMessages)) {
            $myMessages = [];
        }

        if (!is_array($yourMessages)) {
            $yourMessages = [];
        }

        $allMessages = array_merge($myMessages, $yourMessages);
        usort($allMessages, function ($a, $b) {
            return strtotime($a['timestamp']) - strtotime($b['timestamp']);
        });

        if (!empty($allMessages)) {
            $firstIter=true;
            foreach ($allMessages as $message) {
                $messageBody = $message['body'];
                $messageDate = $message['timestamp'];
                $messageClass = ($message['senderId'] == $_SESSION['uID']) ? 'sent' : 'received';

                if ($messageClass=='received' && (($lastClass!='received') || $firstIter)){
                    echo "<div class='message-row'>";
                    echo "  <div class='message-image'>
                                <img src='./uploads/".$chatPartner['profileImagePath']."' alt='User Icon'>
                            </div>";
                }
                else if($messageClass=='sent' && (($lastClass=='received')))
                    echo "</div>";

                echo "<div class='message $messageClass'>";
                echo "<span class='message-content'>$messageBody</span>";
                echo "<span class='message-date'>$messageDate</span>";
                echo "</div>";
                $lastClass=$messageClass;
                $firstIter=false;
            }
            if ($lastClass=='received')
                echo "</div>";
        } else {
            echo "<p>Inicirajte konverzaciju..</p>";
        }
    }


    /*Image files validation*/
    function verifyImageAndSaveImage($userId,&$user)
    {

        switch ($_FILES['image']['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                $user->appendError('Fajl nije poslat');
                break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $user->appendError('Preveliki fajl');
                break;
            default:
                $user->appendError('Greska prilikom postavljanja slike');
            }

        if ($_FILES['image']['size'] > 1000000) {
            $user->appendError('Preveliki fajl');
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (!($ext=array_search($finfo->file($_FILES['image']['tmp_name']),
            array(
                'jpg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
            ), true)))
        {
            $user->appendError('Molimo vas da postavite jpg,png ili gif sliku.');
        }
        $fileShaEnc= sha1_file($_FILES['image']['tmp_name']).'.'.$ext;
        $fileSavePath="./uploads/".$fileShaEnc;

        if (!move_uploaded_file($_FILES['image']['tmp_name'],$fileSavePath))
        {
            $user->appendError('Slika nije uspesno sacuvana, pokusajte ponovo.');
        }

        if(!$user->isEmptyErrors())
            return false;

        
        if(!$user->updateDataGeneric('users',array('profileImagePath'),array($fileShaEnc),array('userId'),array($userId)))
        {
            $user->appendError('Slika nije uspesno sacuvana zbog greske na serveru pokusajte ponovo.');
            return false;
        }
        return true;

        }

        function getAverageRating($ratedId,&$user)
        {
            $userRatings = $user->selectDataGeneric('ratings', array('ratedId'), array($ratedId));
            if($userRatings)
            {
                $totalRating=0;
                foreach($userRatings as $userRating)
                    $totalRating+=$userRating['rating'];
                $averageRating=$totalRating/count($userRatings);
            }
            else
                $averageRating=NULL;
            return $averageRating;
        }

        function assertNotPast($date,$hours,$minutes,&$user)
        {
            $currentTime = new DateTime();
            $targetTime = new DateTime($date);
            $targetTime->setTime($hours, $minutes);

            if($targetTime < $currentTime)
            {
                $user->appendError('Izabrano vreme je u prošlosti, molimo vas da odaberete prikladno vreme.');
                return false;
            }
            return true;
        }

        function validateClassScheduling($date,$hours,$minutes,$class,$classDescr,$userId, &$user)
        {
            if(empty($date) || empty($hours) || empty($minutes) || empty($class) || empty($classDescr))
            {
                $user->appendError('Molimo vas da popunite sva polja pored kojih stoji *');
                return false;
            }

            if(strlen($class)>100)
                $user->appendError('Prekoračen je limit od 100 karaktera za naziv predmeta');

            if(strlen($classDescr)>5000)
                $user->appendError('Prekoračen je limit od 5000 karaktera za opis časa');

            assertNotPast($date,$hours,$minutes,$user);

            if(!$user->isEmptyErrors())
                return false;


            $classDate= implode('-',array($date, $hours,$minutes));
            $columns=array('creatorId','className','classDescription','classDate');
            $values=array($userId,$class,$classDescr,$classDate);
            if(!($uID=$user->insertDataGeneric($columns,$values,'classes')))
            {
                $user->appendError('Greska na serveru, pokusajte ponovo :(');
                return false;
            }

            return $uID;
        }

        function sendClassScheduledNotifications($senderId,$classId)
        {
            $requests=new Request();
            $user= new User();
            $res=$requests->fetchRequestsMentor($senderId);

            $classInfo=$user->selectDataGeneric('classes',array('classId'),array($classId))[0];
            $mentorInfo=$user->selectDataGeneric('users',array('userId'),array($senderId))[0];

            $notifHeader="Меntor ".$mentorInfo['firstName']." ".$mentorInfo['lastName']." "." je zakazao onlajn čas";
            
            $timeSched=explode('-',$classInfo['classDate']);

            $notifHeader.=" ".$timeSched[2].".".$timeSched[1].".".$timeSched[0].". u ".$timeSched[3]."h i ".$timeSched[4]."m";

            $notifBody="Obaveštavamo vas da je zakazan onljan čas iz predmeta ".$classInfo['className']." sa opisom: ".$classInfo['classDescription'];

            foreach($res as $row)
            {
                if($row['approvedReciever'])
                {
                    $columns=array('recieverId','notificationHeader','notificationBody');
                    $values=array($row['senderId'],$notifHeader,$notifBody);
                    $user->insertDataGeneric($columns,$values,'notifications');
                }
            }

            $notifHeader="Uspešno ste zakazali čas za predmet: ".$classInfo['className'];
            $notifHeader.=" dana: ".$timeSched[2].".".$timeSched[1].".".$timeSched[0].". u ".$timeSched[3]." : ".$timeSched[4]." sati.";
            $notifBody="Obaveštavamo vas da ste uspešno zakazali onljan čas iz predmeta ".$classInfo['className'] ." koji je trebalo da se održi ";


            $columns=array('recieverId','notificationHeader','notificationBody');
            $values=array($senderId,$notifHeader,$notifBody);
            $user->insertDataGeneric($columns,$values,'notifications');

        }

        function sendClassCanceledNotifications($senderId,$classId)
        {
            $requests=new Request();
            $user= new User();
            $res=$requests->fetchRequestsMentor($senderId);

            $classInfo=$user->selectDataGeneric('classes',array('classId'),array($classId))[0];
            $mentorInfo=$user->selectDataGeneric('users',array('userId'),array($senderId))[0];

            $notifHeader="Меntor ".$mentorInfo['firstName']." ".$mentorInfo['lastName']." "." je otkazao onlajn čas koji je trebalo da se održi:";
            
            $timeSched=explode('-',$classInfo['classDate']);

            $notifHeader.=" ".$timeSched[2].".".$timeSched[1].".".$timeSched[0]." u ".$timeSched[3]."h i ".$timeSched[4]."m";

            $notifBody="Obaveštavamo vas da je otkazan onljan čas iz predmeta ".$classInfo['className']." sa opisom: ".$classInfo['classDescription'];

            foreach($res as $row)
            {
                if($row['approvedReciever'])
                {
                    $columns=array('recieverId','notificationHeader','notificationBody');
                    $values=array($row['senderId'],$notifHeader,$notifBody);
                    $user->insertDataGeneric($columns,$values,'notifications');
                }
            }

            $notifHeader="Uspešno ste otkazali čas za predmet: ".$classInfo['className'];
            $notifBody="Obaveštavamo vas da ste otkazali onljan čas iz predmeta ".$classInfo['className'] ." koji je trebalo da se održi ";
            $notifBody.=" ".$timeSched[2].".".$timeSched[1].".".$timeSched[0].". u ".$timeSched[3]."h i ".$timeSched[4]."m";


            $columns=array('recieverId','notificationHeader','notificationBody');
            $values=array($senderId,$notifHeader,$notifBody);
            $user->insertDataGeneric($columns,$values,'notifications');

        }

        function compareNotifTimestamps($a,$b)
        {
            $time1=strtotime($a['timestamp']);
            $time2=strtotime($b['timestamp']);

            return ($time2-$time1);
        }


        function compareClassesTimestamps($a,$b)
        {
            $time1 = strtotime(str_replace('-', '', $a['classDate']));
            $time2 = strtotime(str_replace('-', '', $b['classDate']));

            return ($time1 - $time2);
        }

        function markNotificationsAsRead()
        {
            $user=new User();
            if($user->updateDataGeneric('notifications',array('viewed'),array(1),array('recieverId'),array($_SESSION['uID'])))
                return true;
            return false;
        }


        function listAllUserProfiles($row)
        {
            echo "<div class='searching-profiles-container'>";
                echo "  <div class='profile-image'>
                            <img src='./uploads/".$row['profileImagePath']."' alt='User Icon'>
                        </div>";

                echo "<div class='firstLastName'>";
                    echo "<h2>".$row['firstName']." ".$row['lastName']."</h2>";
                echo "</div>";

                echo "<div class='short-descr'>";
                    echo "<h4> Interesovanja </h4>";
                    echo "<p>".$row['interests']."</p>";
                echo "</div>";

                echo "<div class='short-descr'>";
                    echo "<h4> Obrazovanje </h4>";
                    echo "<p>".$row['education']."</p>";
                echo "</div>";

            
                echo "<div class='request-view-buttons'>";
                    echo "<input type='button' id=".$row['userId']." value='Vidi profil' onclick='viewProfile(this.id,0)' class='button'>";
                    if(isset($_SESSION['uID']))
                    {
                        $requests=new Request();
                        if($requests->requestExists($row['userId'],$_SESSION['uID']))
                        {
                            if($requests->isApproved($row['userId'],$_SESSION['uID']))
                                echo "<p>Odobrili ste zahtev</p>";
                            else
                            {
                                echo "<input type='button' id=".$row['userId']." value='Prihvati' onclick='approve(this.id)' class='button' >";
                                echo "<input type='button' id=".$row['userId']." value='Odbij' onclick='refuse(this.id)' class='dangerButton' >";
                            }
                        }
                    }
                echo "</div>";
            echo "</div>";
        }

        function listAllMentorProfiles($row)
        {

            echo "<div class='searching-profiles-container'>";
                echo "  <div class='profile-image'>
                            <img src='./uploads/".$row['profileImagePath']."' alt='User Icon'>
                        </div>";

                echo "<div class='firstLastName'>";
                    echo "<h2>".$row['firstName']." ".$row['lastName']."</h2>";
                echo "</div>";

                echo "<div class='short-descr'>";
                    echo "<h4> Znanje </h4>";
                    echo "<p>".$row['knowledge']."</p>";
                echo "</div>";

                echo "<div class='short-descr'>";
                    echo "<h4> Veštine </h4>";
                    echo "<p>".$row['skills']."</p>";
                echo "</div>";

            
                echo "<div class='request-view-buttons'>";
                    echo "<input type='button' id=".$row['userId']." value='Vidi profil' onclick='viewProfile(this.id,1)' class='button'>";
                    if(isset($_SESSION['uID']))
                    {
                        $requests=new Request();
                        if($requests->requestExists($_SESSION['uID'],$row['userId']))
                        {
                            if($requests->isApproved($_SESSION['uID'],$row['userId']))
                                echo "<p>Uspostavljena razmena</p>";
                            else
                                echo "<p>Zahtev ceka odobrenje</p>";
                        }
                        else
                            echo "<input type='button' id=".$row['userId']." value='Posalji zahtev' onclick='send(this.id)' class='button' >";
                    }
                echo "</div>";
                if(isset($_SESSION['uID']))
                {
                    echo "<div class='request-view-buttons'>";
                            echo '<a class="user-link" href="./chat.php?userId=' . $row['userId'] . '">Pošalji poruku</a>';                                
                    echo "</div>";
                }

            echo "</div>";
        }


        function listAllPanelMentorProfiles($row)
        {

            echo "<div class='searching-profiles-container'>";
                echo "  <div class='profile-image'>
                            <img src='./uploads/".$row['profileImagePath']."' alt='User Icon'>
                        </div>";

                echo "<div class='firstLastName'>";
                    echo "<h2>".$row['firstName']." ".$row['lastName']."</h2>";
                echo "</div>";

                echo "<div class='short-descr'>";
                    echo "<h4> Znanje </h4>";
                    echo "<p>".$row['knowledge']."</p>";
                echo "</div>";

                echo "<div class='short-descr'>";
                    echo "<h4> Veštine </h4>";
                    echo "<p>".$row['skills']."</p>";
                echo "</div>";

                // if($row['activate'])
                //     echo '<a class="comment-delete-link" " href="" id="'. $row['userId'] .'" onclick="removeProfile(this.id,'.$row['userType'].')">Ukloni profil</a>';

            
                echo "<div class='request-view-buttons'>";
                    if($row['activate'])
                    {
                        echo "<input type='button' id=".$row['userId']." value='Vidi profil' onclick='viewProfile(this.id,1)' class='button'>";
                        echo "<input type='button' id=".$row['userId']." value='Uredi profil' onclick='editProfile(this.id,1)' class='button' style='background-color:orange;'>";
                        echo "<input type='button' id=".$row['userId']." value='Ukloni nalog' onclick='removeProfile(this.id,".$row['userType'].")' class='dangerButton' >";
                    }
                    else
                    {
                        echo "<input type='button' id=".$row['userId']." value='Aktiviraj nalog' onclick='activateProfile(this.id)' class='button' >";
                        echo "<input type='button' id=".$row['userId']." value='Ukloni nalog' onclick='removeProfile(this.id,".$row['userType'].")' class='dangerButton' >";
                    }
                echo "</div>";
            echo "</div>";
        }


        function listAllPanelUserProfiles($row)
        {
            echo "<div class='searching-profiles-container'>";
                echo "  <div class='profile-image'>
                            <img src='./uploads/".$row['profileImagePath']."' alt='User Icon'>
                        </div>";

                echo "<div class='firstLastName'>";
                    echo "<h2>".$row['firstName']." ".$row['lastName']."</h2>";
                echo "</div>";

                echo "<div class='short-descr'>";
                    echo "<h4> Interesovanja </h4>";
                    echo "<p>".$row['interests']."</p>";
                echo "</div>";

                echo "<div class='short-descr'>";
                    echo "<h4> Obrazovanje </h4>";
                    echo "<p>".$row['education']."</p>";
                echo "</div>";

            
                echo "<div class='request-view-buttons'>";
                    if($row['activate'])
                    {
                        echo "<input type='button' id=".$row['userId']." value='Vidi profil' onclick='viewProfile(this.id,0)' class='button'>";
                        echo "<input type='button' id=".$row['userId']." value='Uredi profil' onclick='editProfile(this.id,0)' class='button' style='background-color:orange;'>";
                        echo "<input type='button' id=".$row['userId']." value='Ukloni nalog' onclick='removeProfile(this.id,".$row['userType'].")' class='dangerButton' >";
                    }
                    else
                    {
                        echo "<input type='button' id=".$row['userId']." value='Aktiviraj nalog' onclick='activateProfile(this.id)' class='button' >";
                        echo "<input type='button' id=".$row['userId']." value='Ukloni nalog' onclick='removeProfile(this.id,".$row['userType'].")' class='dangerButton' >";
                    }
                echo "</div>";
            echo "</div>";
        }


        function displayUsersApprovedAndWaiting($data,&$user)
        {
            echo '<div class="user-list">';
                foreach ($data as $row) {
                    $userData=$user->getUserData($row['senderId']);
                    echo "<div class='user-container'>";
                    echo "  <div class='user-image'>
                                <img src='./uploads/".$userData['profileImagePath']."' alt='User Icon'>
                            </div>";
                    echo '<a class="user-link" href="" style="pointer-events: none;">'. $userData['firstName']." ".$userData['lastName'] . '</a><br>';
                    echo "</div>";
                }
                echo "</div>";
        }

        function displayMentorsApprovedAndWaiting($data,&$user)
        {
            echo '<div class="user-list">';
                foreach ($data as $row) {
                    $userData=$user->getUserData($row['recieverId']);
                    echo "<div class='user-container'>";
                    echo "  <div class='user-image'>
                                <img src='./uploads/".$userData['profileImagePath']."' alt='User Icon'>
                            </div>";
                    echo '<a class="user-link" href="" style="pointer-events: none;">' . $userData['firstName']." ".$userData['lastName'] . '</a><br>';
                    echo "</div>";
                }
                echo "</div>";
        }

?>

