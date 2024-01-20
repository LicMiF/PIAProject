<?php
    function renderForm($fields,$types,$submit,$action,$method)
    {
        echo "<form action=$action method=$method>";
        $counter=0;
        foreach($fields as $key => $val)
        {
            echo "<div class=input>";
            echo "<strong>".$key.": "."</strong>";
            echo "<input type='".$types[$counter]."' name=$val class='textField'> <br/>";
            echo "</div>";
            $counter++;
        }
        echo '<br/>';
        foreach($submit as $key => $val)
            echo "<input type='submit' name=$val value='$key' class='button'><br/>";
        echo "</form>";
    }

    function renderFormRequired($fields,$submit,$action,$method)
    {
        echo "<form action=$action method=$method>";
        foreach($fields as $key => $val)
        {
            echo "<strong>".$key."*: "."</strong>";
            echo "<input type='text' name=$val class='textField'> <br/>";
        }
        echo '<br/>';
        foreach($submit as $key => $val)
            echo "<input type='submit' name=$val value='$key' class='button'><br/>";
        echo "</form>";
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

    function validateRegister($username,$password,$passwordAgain,$mail,$firstName,$lastName, &$user)
    {   
        if(empty($username) || empty($password) || empty($passwordAgain) || empty($mail) || empty($firstName))
        {
            $user->appendError('Molimo vas da popunite sva polja pored kojih stoji *');
            return false;
        }
        if ($user->userExists($username))
            $user->appendError('Korisnicko ime je zauzeto :(');

        if (strlen($username)>31)
            $user->appendError('Korisnicko ime je predugo, dozvoljeni maksimum je 32 karaktera');

        if (strlen($password)<6)
            $user->appendError('Nedovoljno dugacka sifra');

        if ($password !== $passwordAgain)
            $user->appendError('Sifra i ponovljena sifra se razlikuju');

        if (!filter_var($mail,FILTER_VALIDATE_EMAIL))
            $user->appendError('Uneta mejl adresa nije u ispravnom formatu');

        if ($user->mailExists($mail))
            $user->appendError('Email adresa je vec registrovana :(');

        if (strlen($firstName)>31)
            $user->appendError('Ime je predugo, dozvoljeni maksimum je 32 karaktera');

        if (strlen($lastName)>31)
            $user->appendError('Prezime je predugo, nadamo se da nisi ti: Wolfeschlegelsteinhausenbergerdorff');

        if(!$user->isEmptyErrors())
            return false;

        if(!($uID=$user->insertData($username,$password,$mail,$firstName,$lastName)))
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


        if (strlen($newPass)<6)
            $user->appendError('Nedovoljno dugacka sifra');

        if ($newPass !== $newPassAgain)
            $user->appendError('Sifra i ponovljena sifra se razlikuju');

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
    
?>