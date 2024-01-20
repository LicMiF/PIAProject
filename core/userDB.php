<?php

class User{

    const host='localhost';
    const user='filvik';
    const pass='Filvik123!';
    const db='project';


    protected $conn;
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
            $statement=$this->conn->prepare($this->generateAllSelectionQuery('users','username'));
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
            $statement=$this->conn->prepare($this->generateAllSelectionQuery('users','userId'));
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
            $statement=$this->conn->prepare($this->generateUpdateQuery('users','password','userId'));
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

            $columns=array("mail","firstName","lastName");
            $statement=$this->conn->prepare($this->generateUpdateQuery('users',$columns,'userId'));
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
            $statement=$this->conn->prepare($this->generateAllSelectionQuery('users','userId'));
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
            $statement=$this->conn->prepare($this->generateAllSelectionQuery('users','userId'));
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


    public function insertDataGeneric($columns,$values,$table)
    {
        try {
            $statement = $this->conn->prepare($this->generateInsertionQuery($table,$columns));
            $statement->execute($values);
            return $this->conn->lastInsertID();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function selectDataGeneric($table,$cols=NULL,$whereConds=NULL)
    {
        try {
            if(!$whereConds)
            {
                $statement = $this->conn->prepare($this->generateAllSelectionQueryAndWhere($table));
                $statement->execute();
            }
            else
            {
                $statement = $this->conn->prepare($this->generateAllSelectionQueryAndWhere($table,$cols));
                $statement->execute($whereConds);
            }
            $res=$statement->fetchALL(PDO::FETCH_ASSOC);
            return $res;

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
        try{
            $statement=$this->conn->prepare($this->generateAllSelectionQuery('users','username'));
            $statement->execute([$username]);
            $res=$statement->fetchALL(PDO::FETCH_ASSOC)[0];
            if(!$res)
                return false;
            return true;
        }
        catch(PDOException $e)
        {
            echo "Error";
            echo $e->getMessage();
            return false;
        }
    }

    public function mailExists($mail)
    {
        try{
            $statement=$this->conn->prepare($this->generateAllSelectionQuery('users','mail'));
            $statement->execute([$mail]);
            $res=$statement->fetchALL(PDO::FETCH_ASSOC)[0];
            if(is_null($res))
                return false;
            return true;
        }
        catch(PDOException $e)
        {
            echo "Error";
            echo $e->getMessage();
            return false;
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

    public function hashPassword($password)
    {
        return password_hash($password,PASSWORD_ARGON2ID);
    }

    public function hashMail($mail)
    {
        return password_hash($mail.microtime(),PASSWORD_ARGON2ID);
    }

    public function getAllUserData()
    {
        try{
            $statement=$this->conn->prepare("SELECT * FROM users WHERE userType=0");
            $statement->execute();
            $res=$statement->fetchALL(PDO::FETCH_ASSOC);
            return $res;
        }
        catch(PDOException $e)
        {
            echo "Error";
            echo $e->getMessage();
            return false;
        }
    }

    public function getAllMentorData()
    {
        try{
            $statement=$this->conn->prepare("SELECT * FROM users WHERE userType=1");
            $statement->execute();
            $res=$statement->fetchALL(PDO::FETCH_ASSOC);
            return $res;
        }
        catch(PDOException $e)
        {
            echo "Error";
            echo $e->getMessage();
            return false;
        }
    }


    /*Helper functions*/

    public function generateUpdateQuery($tableName,$columns,$idNotation)
    {
        $columnsStr='';
        for($i=0; $i<count($columns); $i++)
        {
            if ($i==(count($columns)-1))
            {
                $columnsStr.=$columns[$i]."=?";
                continue;
            }
            $columnsStr.=$columns[$i]."=?, ";
        }

        return "UPDATE $tableName SET " .$columnsStr. " WHERE $idNotation=?";

    }


    public function generateInsertionQuery($tableName,$columns)
    {
        $columnsStr=implode(", ",$columns);
        $valuesStr='';
        for($i=0; $i<count($columns); $i++)
        {
            if ($i==(count($columns)-1))
            {
                $valuesStr.="?";
                continue;
            }
            $valuesStr.="?, ";
        }
        return "INSERT INTO $tableName (".$columnsStr.") VALUES(".$valuesStr.")";

    }

    public function generateAllSelectionQuery($tableName,$idNotation=NULL)
    {
        $query="SELECT * FROM $tableName";

        if($idNotation)
            $query.= " WHERE $idNotation=?";
        
        return $query;
    }

    public function generateAllSelectionQueryAndWhere($tableName,$idNotation=NULL)
    {
        $query="SELECT * FROM $tableName";

        if($idNotation)
        {
            $query.= " WHERE";
            for($i=0; $i<count($idNotation ); $i++)
            {
                if ($i==(count($idNotation )-1))
                {
                    $query.= " ". $idNotation[$i]."=?";
                    continue;
                }
                $query.= " ". $idNotation[$i]."=? and";
            }
        }
        return $query;
    }
}

?>