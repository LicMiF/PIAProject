<?php

class User{

    const host='localhost';
    const user='filvik';
    const pass='Filvik123!';
    const db='project';


    private $conn;
    private $errors=array();

    function __construct()
    {
        try{
            $conString='mysql:host='.self::host.';dbname='.self::db;
            $this->conn=new PDO($conString,self::user, self::pass);
        }
        catch(PDOException $e)
        {
            echo "Error";
            echo $e->getMessage();
        }
    }

    function __destruct()
    {
        $this->conn=NULL;
    }   

    public function validateUser($username,$password)
    {
        try{
            $username=trim($username);
            $statement=$this->conn->prepare("SELECT * FROM users WHERE username=?");
            $statement->execute([$username]);
            $res=$statement->fetchALL(PDO::FETCH_ASSOC)[0];
            if (empty($res))
                return 0;
            if (!password_verify($password, $res['password']))
                return 0;
            return $res['userId'];
        }
        catch(PDOException $e)
        {
            echo "Error";
            echo $e->getMessage();
            return 0;
        }

    }   
    public function checkCurrentPass($password,$uID)
    {
        try{
            $statement=$this->conn->prepare("SELECT * FROM users WHERE userId=?");
            $statement->execute([$uID]);
            $res=$statement->fetchALL(PDO::FETCH_ASSOC)[0];

            return password_verify($password, $res['password']);
        }
        catch(PDOException $e)
        {
            echo "Error";
            echo $e->getMessage();
            return false;
        }
    }

    public function changePass($password,$uID)
    {
        try{
            $pass=$this->hashPassword($password);
            $statement=$this->conn->prepare("UPDATE users SET password=? WHERE userId=?");
            $statement->execute([$pass,$uID]);
            return true;
        }
        catch(PDOException $e)
        {
            echo "Error";
            echo $e->getMessage();
            return false;
        }
    }

    public function updateInfo($mail,$firstName ,$lastName,$uID)
    {
        try{
            $statement=$this->conn->prepare("UPDATE users SET mail=?, firstName=?,lastName=? WHERE userId=?");
            $statement->execute([$mail,$firstName ,$lastName,$uID]);
            return true;
        }
        catch(PDOException $e)
        {
            echo "Error";
            echo $e->getMessage();
            return false;
        }
    }

    public function getUserMail($uID)
    {
        try{
            $statement=$this->conn->prepare("SELECT * FROM users WHERE userId=?");
            $statement->execute([$uID]);
            $res=$statement->fetchALL(PDO::FETCH_ASSOC)[0];
            return $res['mail'];
        }
        catch(PDOException $e)
        {
            echo "Error";
            echo $e->getMessage();
            return false;
        }
    }

    public function getUserData($uID)
    {   
        try{
            $statement=$this->conn->prepare("SELECT * FROM users WHERE userId=?");
            $statement->execute([$uID]);
            $res=$statement->fetchALL(PDO::FETCH_ASSOC)[0];
            return $res;
        }
        catch(PDOException $e)
        {
            echo "Error";
            echo $e->getMessage();
            return false;
        }
    }

    public function insertData($username, $password, $mail, $firstName, $lastName)
    {
        try {
            $pass=$this->hashPassword($password);
            $emailHash=$this->hashMail($mail);
            $statement = $this->conn->prepare("INSERT INTO users (username, password, mail, firstName, lastName, emailHash) VALUES (?, ?, ?, ?, ?, ?)");
            $statement->execute([$username, $pass, $mail, $firstName, $lastName, $emailHash]);
            return $this->conn->lastInsertID();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public function userExists($username)
    {
    try {
        $statement = $this->conn->prepare("SELECT * FROM users WHERE username=?");

        $statement->execute([$username]);

        $res=($statement->fetchALL(PDO::FETCH_ASSOC));

        
        if (!empty($res[0])) {
            return true;  
        }

        return false;     
    } catch (PDOException $e) {
        
        echo "Error";
        echo $e->getMessage();
        return false;  
    }
    }

    public function mailExists($mail)
    {
        try {
            $statement = $this->conn->prepare("SELECT * FROM users WHERE mail=?");

            $statement->execute([$mail]);

            // Fetch all rows from the result set as an associative array
            $res=($statement->fetchALL(PDO::FETCH_ASSOC));
            // Check if the result set is not empty
            if (!empty($res[0])) {
                return true;  // Email exists
            }

            return false;     // Email does not exist
        } catch (PDOException $e) {
            // If there's an exception (error) during the execution of the database operation, catch it and display an error message
            echo "Error";
            echo $e->getMessage();
            return false;  // Return false to indicate an error or failure
        }
    }

    public function appendError($err)
    {
        $this->errors[]=$err;
    }
    public function isEmptyErrors()
    {
        return empty($this->errors);
    }
    public function displayErrors()
    {
        $str= "<ul>";
        foreach ($this->errors as $error)
            $str.= "<li>$error</li>";
        $str.= "</ul>";
        return $str;
    }
    /*public function hashPassword($password)
    {
        echo "entered";
        $salt= generateUniqueSalt();
        echo "entered";
        $combined=$password.$salt;

        $hash=password_hash($password,PASSWORD_ARGON2ID);
        return array($hash, $salt);
    }*/

    public function hashPassword($password)
    {
        return password_hash($password,PASSWORD_ARGON2ID);
    }

    public function hashMail($mail)
    {
        return password_hash($mail.microtime(),PASSWORD_ARGON2ID);
    }

    public function showMyMessages($id,$target){
        try{
            $statement = $this->conn->prepare("SELECT * FROM users_chat WHERE sender_id=? AND receiver_id=?");
            $statement->execute([$id,$target]);

            $res=($statement->fetchALL(PDO::FETCH_ASSOC));
            if (!empty($res[0])){
                return $res;
            }
        }catch(PDOException $e) {
            echo "Error";
            echo $e->getMessage();
            return false;
        }

    }
    public function showYourMessages($id,$tvojId){
        try{
            $statement = $this->conn->prepare("SELECT * FROM users_chat WHERE receiver_id=? AND sender_id=?");
            $statement->execute([$id,$tvojId]);

            $res=($statement->fetchALL(PDO::FETCH_ASSOC));
            if (!empty($res[0])){
                return $res;
            }
        }catch(PDOException $e) {
            echo "Error";
            echo $e->getMessage();
            return false;
        }
    }

    public function showOtherUsers($id){
        try{
            $statement=$this->conn->prepare("SELECT * FROM users WHERE userId!=?");
            $statement->execute([$id]);
            $res=$statement->fetchALL(PDO::FETCH_ASSOC);
            if (!empty($res[0])){
                return $res;
            }
        }catch(PDOException $e) {
            echo "Error";
            echo $e->getMessage();
            return false;
        }
    }
    public function updateMessage($senderId,$receiverId,$messageContent,$status,$timestamp){
        try {
            $statement = $this->conn->prepare("INSERT INTO users_chat (sender_id, receiver_id, msg_content, msg_status, msg_date) VALUES ( ?, ?, ?, ?, ?)");
            $statement->execute([$senderId, $receiverId, $messageContent, $status, $timestamp]);
            return $this->conn->lastInsertID();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function getUsernameById($id){
        try{    
            $statement=$this->conn->prepare("SELECT * FROM users WHERE userId=?");
            $statement->execute([$id]);
            $res=$statement->fetchALL(PDO::FETCH_ASSOC);
            if(!empty($res[0])){
                return $res[0];
            }

        }catch(PDOException $e) {
            echo "Error: ". $e->getMessage();
        }
    }

    public function getUserIdByUsername($username){
        try{
            $statement=$this->conn->prepare("SELECT * FROM users WHERE username=?");
            $statement->execute([$username]);
            $res=$statement->fetchALL(PDO::FETCH_ASSOC);
            if(!empty($res[0])){
                return $res[0];
            }

        }catch(PDOException $e) {
            echo "Error". $e->getMessage();
        }
    }
}

?>