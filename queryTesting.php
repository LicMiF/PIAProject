<?php

    function generateDeleteAndWhereQuery($tableName,$idNotation=NULL)
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

    echo generateDeleteAndWhereQuery('classes',array("creatorId","classId"));
?>