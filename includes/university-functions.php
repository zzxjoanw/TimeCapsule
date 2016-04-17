<?php
/**
 * Created by PhpStorm.
 * User: Laura 5
 * Date: 4/15/2016
 * Time: 1:48 PM
 */


function getUniversities($connection, $id=0)
{
    $universityID = NULL;
    $universityName = NULL;
    $universityCode = NULL;

    if($id==0)
    {
        $sql = "SELECT id, courseName, JACSCode FROM courseTable";
        $preparedStatement = $connection->prepare($sql) or die($connection->error);
    }
    else
    {
        $sql = "SELECT g.id, g.courseName, g.JACSCode FROM studentCourseTable s, courseTable g WHERE s.studentID = ? AND s.courseID = g.courseID";
        $preparedStatement = $connection->prepare($sql) or die($connection->error);
        $preparedStatement->bind_param("i",$id);
    }

    $preparedStatement->execute();
    $preparedStatement->bind_result($universityID, $universityName, $universityCode);

    $list = array();
    while($preparedStatement->fetch())
    {
        $row = array();
        array_push($row,$universityID);
        array_push($row,$universityName);
        array_push($row,$universityCode);
        array_push($list,$row);
    }

    return $list;
}

//adds or updates the universityprpgress column in pathTable
/**
 * @param $connection
 * @param $list
 */
function addUniversities($connection, $list, $progressList, $newValue=0)
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
            $sql = "INSERT INTO pathTable (JACSCode,studentID,universityProgress,pathName) VALUES(?,?,?,?)";
            $preparedStatement = $connection->prepare($sql);
            $preparedStatement->bind_param("siss",$code,$id,$progress,$pathName);
            $preparedStatement->execute();
        }
        else
        {
            $sql = "UPDATE pathTable SET universityProgress = ? WHERE JACSCode=? AND studentID=?";
            $preparedStatement = $connection->prepare($sql);
            $preparedStatement->bind_param("isi",$newValue,$code,$id);
            $preparedStatement->execute();
        }
    }
}

/**
 * @param $connection
 * @param $code
 * @param $newValue
 */
function updateUniversity($connection, $code, $newValue)
{
    $id=$_SESSION['studentID'];
    $sql = "UPDATE pathTable SET gcseProgress=? WHERE JACSCode=? AND studentID = ?";
    $preparedStatement = $connection->prepare($sql);
    $preparedStatement->bind_param("isi",$newValue,$code, $id);
    $preparedStatement->execute();
}