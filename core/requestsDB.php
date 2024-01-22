<?php
require_once "./core/userDB.php";
class Request extends User{

    function __construct()
    {
        parent::__construct();
    }

    public function fetchRequestsUser($uId)
    {
        try{
            $statement=$this->conn->prepare("SELECT * FROM requests WHERE senderId=?");
            $statement->execute([$uId]);
            $res=$statement->fetchALL(PDO::FETCH_ASSOC);
            if (empty($res))
                return 0;
            return $res;
        }
        catch(PDOException $e)
        {
            echo "Error";
            echo $e->getMessage();
            return 0;
        }
    }

    public function fetchRequestsMentor($uId)
    {
        try{
            $statement=$this->conn->prepare("SELECT * FROM requests WHERE recieverId=?");
            $statement->execute([$uId]);
            $res=$statement->fetchALL(PDO::FETCH_ASSOC);
            if (empty($res))
                return 0;
            return $res;
        }
        catch(PDOException $e)
        {
            echo "Error";
            echo $e->getMessage();
            return 0;
        }
    }

    public function addRequest($uId,$mId)
    {
        try{
            $whereConditions=array('senderId','recieverId');
            $statement=$statement=$this->conn->prepare($this->generateAllSelectionQueryAndWhere('requests',$whereConditions));
            $statement->execute([$uId,$mId]);
            $row=$statement->fetchALL(PDO::FETCH_ASSOC);

            if($row)
                return true;

            $columns=array('senderId','recieverId');
            $statement=$this->conn->prepare($this->generateInsertionQuery('requests',$columns));
            $statement->execute([$uId,$mId]);
            return true;
        }
        catch(PDOException $e)
        {
            echo "Error";
            echo $e->getMessage();
            return false;
        }
    }


    public function viewedRequest($uId,$mId)
    {
        try{
            $statement=$this->conn->prepare("UPDATE requests SET viewedReciever = ? WHERE senderId = ? AND recieverId = ?");
            $statement->execute([1,$uId,$mId]);
            return true;
        }
        catch(PDOException $e)
        {
            echo "Error";
            echo $e->getMessage();
            return false;
        }
    }

    public function approveRequest($uId,$mId)
    {
        try{
            $statement=$this->conn->prepare("UPDATE requests SET approvedReciever = ? WHERE senderId = ? AND recieverId = ?");
            $statement->execute([1,$uId,$mId]);
            return true;
        }
        catch(PDOException $e)
        {
            echo "Error";
            echo $e->getMessage();
            return false;
        }
    }

    public function isViewed($uId,$mId)
    {
        try{
            $statement=$this->conn->prepare("SELECT * from requests where senderId=? and recieverId=?");
            $statement->execute([$uId,$mId]);
            $res=$statement->fetchALL(PDO::FETCH_ASSOC)[0];
            if($res['viewedReciever'])
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

    public function isApproved($uId,$mId)
    {
        try{
            $statement=$this->conn->prepare("SELECT * from requests where senderId=? and recieverId=?");
            $statement->execute([$uId,$mId]);
            $res=$statement->fetchALL(PDO::FETCH_ASSOC)[0];
            if($res['approvedReciever'])
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




    public function getRequests()
    {
        return $this->requests;
    }
}


?>