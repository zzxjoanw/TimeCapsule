<?php
/**
 * Created by PhpStorm.
 * User: Laura 5
 * Date: 4/11/2016
 * Time: 9:03 AM
 */

function getGCSEs($connection, $id=0)
{
    $gcseID = NULL;
    $gcseName = NULL;
    $gcseCode = NULL;

    if($id==0)
    {
        $sql = "SELECT gcseID, gcseName,gcseCode FROM gcseTable";
        $preparedStatement = $connection->prepare($sql) or die($connection->error);
    }
    else
    {
        $sql = "SELECT g.gcseID, g.gcseName, g.gcseCode FROM studentGCSETable s, gcseTable g WHERE s.studentID = ? AND s.gcseID = g.gcseID";
        $preparedStatement = $connection->prepare($sql) or die($connection->error);
        $preparedStatement->bind_param("i",$id);
    }

    $preparedStatement->execute();
    $preparedStatement->bind_result($gcseID, $gcseName, $gcseCode);
    
    $list = array();
    while($preparedStatement->fetch())
    {
        $row = array();
        array_push($row,$gcseID);
        array_push($row,$gcseName);
        array_push($row,$gcseCode);
        array_push($list,$row);
    }
    
    return $list;
}

//adds or updates the GCSEpgress column in pathTable
/**
 * @param $connection
 * @param $list
 */
function addGCSEs($connection, $list, $progressList, $newValue=0)
{
    $id = $_SESSION['studentID'];

    for($i=0;$i<count($list);$i++)
    {
        $code = substr($list[$i],0,4);
        $pathName = substr($list[$i],4);
        $progress = $progressList[$i];

        $sql = "SELECT count(*) FROM pathTable WHERE studentID = ? AND JACSCode=?";
        $preparedStatement = $connection->prepare($sql);
        $preparedStatement->bind_param("is",$id,$code);
        $preparedStatement->execute();
        $preparedStatement->bind_result($count);
        $preparedStatement->close();

        if($count==0)
        {
            $sql = "INSERT INTO pathTable (JACSCode,studentID,gcseProgress,pathName) VALUES(?,?,?,?)";
            $preparedStatement = $connection->prepare($sql);
            $preparedStatement->bind_param("sis",$code,$id,$progress, $pathName);
            $preparedStatement->execute();
        }
        else
        {
            $sql = "UPDATE pathTable SET gcseProgress = ? WHERE JACSCode=? AND studentID=?";
            $preparedStatement = $connection->prepare($sql);
            $preparedStatement->bind_param("isi",$progress,$code,$id);
            $preparedStatement->execute();
        }
    }
}

/**
 * @param $connection
 * @param $code
 * @param $newValue
 */
function updateGCSE($connection, $code, $newValue)
{
    $id=$_SESSION['studentID'];
    $sql = "UPDATE pathTable SET gcseProgress=? WHERE JACSCode=? AND studentID = ?";
    $preparedStatement = $connection->prepare($sql);
    $preparedStatement->bind_param("isi",$newValue,$code, $id);
    $preparedStatement->execute();
}