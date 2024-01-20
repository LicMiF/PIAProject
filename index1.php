<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
</head>
<body>

<?php

    function generateUpdateQuery($tableName,$columns,$idNotation)
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


    function generateInsertionQuery($tableName,$columns)
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

    function generateAllSelectionQuery($tableName,$idNotation=NULL)
    {
        $query="SELECT * FROM $tableName";
        if($idNotation)
            $query.= " WHERE $idNotation=?";
        return $query;
    }

    $toGen=array('sifra');
    echo generateUpdateQuery('userSpecific',$toGen, 'userId');
    echo "<br>";
    echo generateInsertionQuery('userSpecific',$toGen, 'userId');
    echo "<br>";
    echo generateAllSelectionQuery('userSpecific');

?>

</body>
</html>