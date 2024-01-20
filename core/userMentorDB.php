<?php
require_once "./core/userDB.php";
class userMentor extends User{

    function __construct()
    {
        parent::__construct();
    }

    public function addValuesUser($userId,$education, $interests)
    {
        try {
            $statement = $this->conn->prepare("INSERT INTO userSpecific (userId,education,interests) VALUES (?, ?, ?)");
            $statement->execute([$userId,$education, $skills]);
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function addValuesMentor($userId,$yearExp, $knowledge)
    {
        try {
            $statement = $this->conn->prepare("INSERT INTO mentorSpecific (userId,yearExp,knowledge) VALUES (?, ?, ?)");
            $statement->execute([$userId,$yearExp, $knowledge]);
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }


    public function getValuesMentor($userId)
    {
        try {
            $statement = $this->conn->prepare("SELECT * FROM mentorSpecific where userId=?");
            $statement->execute([$userId]);
            $res=$statement->fetchALL(PDO::FETCH_ASSOC)[0];
            return $res;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return 0;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return 0;
        }
    }


    public function getValuesUser($userId)
    {
        try {
            $statement = $this->conn->prepare("SELECT * FROM userSpecific where userId=?");
            $statement->execute([$userId]);
            $res=$statement->fetchALL(PDO::FETCH_ASSOC)[0];
            return $res;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return 0;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return 0;
        }
    }


    public function updateValuesUser($userId,$columns,$values)
    {
        try {
            $columnsStr='';
            for($i=0; $i<count($columns); $i++)
            {
                if ($i==(count($columns)-1))
                {
                    $columnsStr.=$colName."=?";
                    continue;
                }
                $columnsStr.=$colName."=?, ";
            }
            array_push($values,$userId);
            $statement = $this->conn->prepare("UPDATE userSpecific SET " .$columnsStr. " WHERE userId=?");
            $statement->execute($values);
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return 0;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return 0;
        }
    }

    public function updateValuesMentor($userId,$columns,$values)
    {
        try {
            $columnsStr='';
            for($i=0; $i<count($columns); $i++)
            {
                if ($i==(count($columns)-1))
                {
                    $columnsStr.=$colName."=?";
                    continue;
                }
                $columnsStr.=$colName."=?, ";
            }
            array_push($values,$userId);
            $statement = $this->conn->prepare("UPDATE mentorSpecific SET " .$columnsStr. " WHERE userId=?");
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

    /*ADD DELETING FUNCS*/



    
}

?>