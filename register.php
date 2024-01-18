<!DOCTYPE html>
<html lang="en">
<?php
    require_once "./core/userDB.php";
    require_once "./core/utilities.php";
    include_once "./includes/head.php";

    forbidAccesLogged();

    include_once "./includes/header.html";
    include_once "./includes/topnav.php";
?>
<body>
    <div class="row">
        <div class="leftcolumn">
            <div class="card">
                <h2>TITLE HEADING</h2>
                <h5>Title description, Dec 7, 2017</h5>
                <div class="fakeimg" style="height:200px;">Image</div>
                <p>Some text..</p>
                <p>Sunt in culpa qui officia deserunt mollit anim id est laborum consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.</p>
            </div>
        </div>
    
        <div class="rightcolumn">
            <div class="card">
<?php
    $user = new User();
    if(isset($_POST['register']))
    {
        $username=$_POST['username'];
        $password=$_POST['password'];
        $passwordAgain=$_POST['passwordAgain'];
        $mail=$_POST['mail'];
        $firstName=$_POST['firstName'];
        $lastName=$_POST['lastName'];

        if($uID=validateRegister($username,$password,$passwordAgain,$mail,$firstName,$lastName,$user))
        {
            echo "Here";    
            $_SESSION['uID']=$data['userId'];
            header ("Location: index.php");
            exit();
        }
        else
            $errorstr=$user->displayErrors();
    }
    echo "<h2>Registracija</h2>";
    if(!$user->isEmptyErrors())
        echo $errorstr;
    $fields=array('Korisničko ime*'=>'username','Šifra*'=>'password','Šifra ponovo*'=>'passwordAgain','Mejl adresa*'=>'mail','Ime*'=>'firstName','Prezime'=>'lastName');
    $types=array('text','password','password','text','text','text');
    $submit=array('Registruj se'=>'register');
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