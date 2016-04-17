<?php
/**
 * Created by PhpStorm.
 * User: Laura 5
 * Date: 4/15/2016
 * Time: 1:30 PM
 */

function getALevels($connection, $id=0)
{
    $aLevelID = NULL;
    $aLevelName = NULL;
    $aLevelCode = NULL;

    if($id==0)
    {
        $sql = "SELECT aLevelID, aLevelName,aLevelCode FROM aLevelTable";
        $preparedStatement = $connection->prepare($sql) or die($connection->error);
    }
    else
    {
        $sql = "SELECT a.aLevelID, a.aLevelName, a.aLevelCode FROM studentALevelTable s, aLevelTable a WHERE s.studentID = ? AND s.aLevelID = a.aLevelID";
        $preparedStatement = $connection->prepare($sql) or die($connection->error);
        $preparedStatement->bind_param("i",$id);
    }

    $preparedStatement->execute();
    $preparedStatement->bind_result($aLevelID, $aLevelName, $aLevelCode);

    $list = array();
    while($preparedStatement->fetch())
    {
        $row = array();
        array_push($row,$aLevelID);
        array_push($row,$aLevelName);
        array_push($row,$aLevelCode);
        array_push($list,$row);
    }

    return $list;
}

//adds or updates the ALevelprogress column in pathTable
/**
 * @param $connection
 * @param $list
 */
function addALevels($connection, $list, $progressList, $newValue=0)
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
            $sql = "INSERT INTO pathTable (JACSCode,studentID,aLevelProgress,pathName) VALUES(?,?,0,?)";
            $preparedStatement = $connection->prepare($sql);
            $preparedStatement->bind_param("sis",$code,$id,$progress,$pathName);
            $preparedStatement->execute();
        }
        else
        {
            $sql = "UPDATE pathTable SET aLevelProgress = ? WHERE JACSCode=? AND studentID=?";
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
function updateALevel($connection, $code, $newValue)
{
    $id=$_SESSION['studentID'];
    $sql = "UPDATE pathTable SET gcseProgress=? WHERE JACSCode=? AND studentID = ?";
    $preparedStatement = $connection->prepare($sql);
    $preparedStatement->bind_param("isi",$newValue,$code, $id);
    $preparedStatement->execute();
}