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
            $statement=$this->conn->prepare($this->generateUpdateAndWhereQuery('users',array('password'),array('userId')));
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
            $statement=$this->conn->prepare($this->generateUpdateAndWhereQuery('users',$columns,array('userId')));
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

    public function insertDataSpecific($columns,$values,$table)
    {
        try {
            $statement = $this->conn->prepare($this->generateInsertionQuery($table,$columns));
            $statement->execute($values);
            return true;
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

    public function deleteDataGeneric($table,$cols=NULL,$whereConds=NULL)
    {
        try {
            if($whereConds)
            {
                $statement = $this->conn->prepare($this->generateDeleteAndWhereQuery($table,$cols));
                $statement->execute($whereConds);
            }
            else
                return false;
            return true;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }



    /*Inside values */
    public function updateDataGeneric($table,$cols,$vals,$whereCols=NULL,$whereConds=NULL)
    {
        try {
            $statement = $this->conn->prepare($this->generateUpdateAndWhereQuery($table,$cols,$whereCols));
    
            if($whereConds)
                $vals = array_merge($vals, $whereConds);;

            $statement->execute($vals);
            return true;
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


    public function generateUpdateAndWhereQuery($tableName,$columns,$whereCols=NULL)
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

        $query="UPDATE $tableName SET " .$columnsStr;

        if($whereCols)
        {
            $query.= " WHERE";
            for($i=0; $i<count($whereCols ); $i++)
            {
                if ($i==(count($whereCols )-1))
                {
                    $query.= " ". $whereCols[$i]."=?";
                    continue;
                }
                $query.= " ". $whereCols[$i]."=? and";
            }
        }
        return $query;
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

    public function generateDeleteAndWhereQuery($tableName,$idNotation=NULL)
    {
        $query="DELETE FROM $tableName";

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

/* Messages related */

    public function showMyMessages($id,$target){
        try{
            $statement = $this->conn->prepare("SELECT * FROM messages WHERE senderId=? AND recieverId=?");
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
    public function showYourMessages($id,$target){
        try{
            $statement = $this->conn->prepare("SELECT * FROM messages WHERE recieverId=? AND senderId=?");
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


    public function showOtherUsers($userId,$userType)
    {
        try {
            if($userType)
                $statement = $this->conn->prepare("SELECT users.*, requests.*
                FROM users
                INNER JOIN requests ON users.userId = requests.senderId
                WHERE requests.recieverId = ? AND requests.approvedReciever = ?;");
            else
                $statement = $this->conn->prepare("SELECT users.*, requests.*
                FROM users
                INNER JOIN requests ON users.userId = requests.recieverId
                WHERE requests.senderId = ? AND requests.approvedReciever = ?;");
            $statement->execute([$userId,1]);
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    
            return $result;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function showOtherUsersTest($userId,$userType)
    {
        try {
            if($userType)
            {
                $statement = $this->conn->prepare("SELECT *
                FROM users u
                WHERE EXISTS (
                    SELECT 1
                    FROM messages m
                    WHERE (m.recieverId = u.userId OR m.senderId = u.userId)
                       AND (m.recieverId = :userId OR m.senderId = :userId) AND u.userType!=1
                )");
                $statement->bindParam(':userId', $userId);
                $statement->execute();
            }
            else
            {
                $statement = $this->conn->prepare("SELECT * FROM users where userType=1");
                $statement->execute();
            }
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    
    public function markMessageAsRead($receiverId, $senderId) {
        try {
            $statement = $this->conn->prepare("UPDATE messages SET viewedReciever = 1 WHERE recieverId = ? AND senderId = ? AND viewedReciever = 0");
            $statement->execute([$receiverId, $senderId]);
            return true;
        } catch (PDOException $e) {
            echo "Error";
            echo $e->getMessage();
            return false;
        }
    }

    public function countUnreadMessages($receiverId, $senderId) {
        try {
            $statement = $this->conn->prepare("SELECT COUNT(*) AS unread_count FROM messages WHERE recieverId=? AND senderId=? AND viewedReciever=0");
            $statement->execute([$receiverId, $senderId]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
    
            // Return the count of unread messages
            return $result['unread_count'];
    
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return 0; // Return 0 in case of an error
        }
    }

    /*Search related code*/
    public function searchForChatUsers($searchString,$userId,$userType)
    {
        try {
            $searchCondition = "users.username LIKE :search OR users.firstName LIKE :search OR users.lastName LIKE :search";
            if ($userType) {
                $statement = $this->conn->prepare("
                    SELECT *
                    FROM users
                    WHERE EXISTS (
                        SELECT 1
                        FROM messages
                        WHERE (recieverId = users.userId OR senderId = users.userId)
                            AND recieverId = :userId AND NOT recieverId=senderId AND ($searchCondition) AND users.userType!=1
                )");
                $statement->bindParam(':userId', $userId);
            } else {
                $statement = $this->conn->prepare("SELECT * FROM users where userType=1 and ($searchCondition)");
            }

            $searchString = "%$searchString%";
            $statement->bindParam(':search', $searchString);

            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }


    public function searchForOtherMentors($searchString)
    {
        try {
            $searchCondition = "users.username LIKE :search OR users.firstName LIKE :search OR users.lastName LIKE :search OR users.skills LIKE :search OR mentorSpecific.knowledge LIKE :search";
    
            $statement = $this->conn->prepare("
                SELECT users.*, mentorSpecific.*
                FROM users
                INNER JOIN mentorSpecific ON users.userId = mentorSpecific.userId
                WHERE users.userType = 1 AND ($searchCondition)
            ");
    
            $searchString = "%$searchString%";
            $statement->bindParam(':search', $searchString);
    
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    
            return $result;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function isAlreadyRated($ratedId,$criticId)
    {
        try{            
            $statement=$this->conn->prepare($this->generateAllSelectionQueryAndWhere('ratings',array('ratedId','criticId')));
            $statement->execute([$ratedId,$criticId]);
            $res=$statement->fetchALL(PDO::FETCH_ASSOC);
            if($res)
                return true;
            return false;
        }
        catch(PDOException $e)
        {
            echo "Error";
            echo $e->getMessage();
            return false;
        }
    }


    public function getMyRating($criticId,$ratedId)
    {
        try{            
            $statement=$this->conn->prepare($this->generateAllSelectionQueryAndWhere('ratings',array('ratedId','criticId')));
            $statement->execute([$ratedId,$criticId]);
            $res=$statement->fetchALL(PDO::FETCH_ASSOC)[0];
            if($res)
                return $res['rating'];
            return 0;
        }
        catch(PDOException $e)
        {
            echo "Error";
            echo $e->getMessage();
            return 0;
        }
    }

    public function getUserType($userId)
    {
        try{            
            $statement=$this->conn->prepare($this->generateAllSelectionQueryAndWhere('users',array('userId')));
            $statement->execute([$userId]);
            $res=$statement->fetchALL(PDO::FETCH_ASSOC)[0];
            if($res)
                return $res['userType'];
            return 0;
        }
        catch(PDOException $e)
        {
            echo "Error";
            echo $e->getMessage();
            return 0;
        }
    }
}

?>