<?php
/**
 * Created by PhpStorm.
 * User: Laura 4
 * Date: 12/7/2015
 * Time: 2:16 PM
 */

function getUserInfo($connection,$email)
{
    $sql = "SELECT firstname,lastname,country FROM studentTable WHERE email = ?";
    $preparedStatement = $connection->prepare($sql) or die("error: ".$connection->error);
    $preparedStatement->bind_param("s",$email);
    $preparedStatement->execute();
    $preparedStatement->bind_result($firstname,$lastname,$country);

    return $preparedStatement->fetch_array(MYSQLI_NUM);
}

function setUserInfo($connection,$list)
{
    //$list is a key-value array containing the columns to be updated for each user

    $sql = "";
    $preparedStatement = $connection->prepare($sql) or die("error: ".$connection->error);
    //can I have multiple bind_param statements?
    return $preparedStatement->execute();
}

function getAllGCSEsList($connection,$country)
{
    $initial = substr($country,0,1);
    $sql = "SELECT gcseID, gcseName FROM gcseTable WHERE gcseCountryList LIKE '%" . $initial . "%'";
    $preparedStatement = $connection->prepare($sql) or die("error: ".$connection->error);
    $preparedStatement->execute();
    $preparedStatement->bind_result($gcseID, $gcseName);

    $list = array();

    while($preparedStatement->fetch())
    {
        $row = $gcseID . "|" . $gcseName;
        array_push($list,$row);
    }

    return $list;
}

function getMyGCSEsList($connection,$id)
{
    $sql = "SELECT s.gcseID FROM studentGCSETable s, gcseTable g WHERE s.studentID = ? AND s.gcseID = g.gcseID";
    $preparedStatement = $connection->prepare($sql) or die($connection->error);
    $preparedStatement->bind_param("i",$id);
    $preparedStatement->execute();
    $preparedStatement->bind_result($id);

    $list = array();

    while($preparedStatement->fetch())
    {
        array_push($list,$id);
    }

    return $list;
}

function getOtherGCSEs($connection, $id)
{
    $sql = "SELECT gcseID FROM gcseTable WHERE gcseID NOT IN (SELECT gcseID FROM studentGCSETable WHERE studentID = ?)";

    $preparedStatement = $connection->prepare($sql) or die("error2: ".$connection->error);
    $preparedStatement->bind_param("i",$id);
    $preparedStatement->execute();
    $preparedStatement->bind_result($gcseID);

    $list = array();
    while($preparedStatement->fetch())
    {
        array_push($list,$gcseID);
    }
    return $list;
}

function addGCSEs($connection, $list)
{
    $sql = "INSERT INTO studentGCSETable (studentID,gcseID) VALUES(?,?)";
    $preparedStatement = $connection->prepare($sql) or die($connection->error);

    for($i=0;$i<count($list);$i++)
    {
        $listVal = $list[$i];
        $preparedStatement->bind_param("ii",$_SESSION['studentID'],$listVal) or die("error1");
        $preparedStatement->execute() or die($connection->error);
    }
}

function removeGCSEs($connection, $list)
{
    $sql = "DELETE FROM studentGCSETable WHERE studentID = ? AND gcseID = ?";
    $preparedStatement = $connection->prepare($sql) or die($connection->error);

    for($i=0;$i<count($list);$i++)
    {
        $preparedStatement->bind_param("ii",$_SESSION['studentID'],$list[$i]);
        $preparedStatement->execute();
    }
}

function getAllALevels($connection,$country)
{
    $initial = substr($country,0,1);
    $sql = "SELECT alevelID, alevelName FROM aLevelTable WHERE alevelCountryList LIKE '%" . $initial . "%'";
    $preparedStatement = $connection->prepare($sql) or die("error: ".$connection->error);
    $preparedStatement->execute();
    $preparedStatement->bind_result($alevelID, $alevelName);

    $list = array();

    while($preparedStatement->fetch())
    {
        $row = $alevelID . "|" . $alevelName;
        array_push($list,$row);
    }

    return $list;
}

function getMyALevels($connection, $id)
{
    $sql = "SELECT s.aLevelID FROM studentALevelTable s, aLevelTable a WHERE s.studentID = ? AND s.aLevelID = a.aLevelID";
    $preparedStatement = $connection->prepare($sql) or die($connection->error);
    $preparedStatement->bind_param("i",$id);
    $preparedStatement->execute();
    $preparedStatement->bind_result($id);

    $list = array();

    while($preparedStatement->fetch())
    {
        array_push($list,$id);
    }

    return $list;
}

function getOtherALevels($connection, $id)
{
    $sql = "SELECT aLevelID FROM aLevelTable WHERE aLevelID NOT IN (SELECT aLevelID FROM studentALevelTable WHERE studentID = ?)";

    $preparedStatement = $connection->prepare($sql) or die("error2: ".$connection->error);
    $preparedStatement->bind_param("i",$id);
    $preparedStatement->execute();
    $preparedStatement->bind_result($aLevelID);

    $list = array();
    while($preparedStatement->fetch())
    {
        array_push($list,$aLevelID);
    }
    return $list;
}

function addALevels($connection,$list)
{
    echo "userfunctions:165 addALevels()";
    $sql = "INSERT INTO studentALevelTable (studentID,aLevelID) VALUES(?,?)";
    $preparedStatement = $connection->prepare($sql) or die($connection->error);

    for($i=0;$i<count($list);$i++)
    {
        $listVal = $list[$i];
        $preparedStatement->bind_param("ii",$_SESSION['studentID'],$listVal) or die("error1");
        $preparedStatement->execute() or die($connection->error);
    }
}

function removeALevels($connection,$list)
{
    echo "userfunctions:179 removeALevels()";
    $sql = "DELETE FROM studentALevelTable WHERE studentID = ? AND aLevelID = ?";
    $preparedStatement = $connection->prepare($sql) or die($connection->error);

    for($i=0;$i<count($list);$i++)
    {
        $preparedStatement->bind_param("ii",$_SESSION['studentID'],$list[$i]);
        $preparedStatement->execute();
    }
}

//do all interests appear in myInterests or OtherInterests?
function getOtherInterests($connection, $id)
//gets all interests not associated with the current student
{
    $sql = "SELECT interestID FROM interestTable WHERE interestID NOT IN " .
           "(SELECT interestID FROM studentInterestsTable WHERE studentID = " . $id . ")";
    $preparedStatement = $connection->prepare($sql) or die("error: ".$connection->error);
    $preparedStatement->execute();
    $preparedStatement->bind_result($interestID);

    $list = array();
    while($preparedStatement->fetch())
    {
        array_push($list,$interestID);
    }
    return $list;
}

function getMyInterests($connection, $id)
//gets all the current student's interests
{
    $sql = "SELECT interestList FROM studentTable WHERE studentID = $id";
    $preparedStatement = $connection->prepare($sql) or die($connection->error);
    $preparedStatement->execute();
    $preparedStatement->bind_result($interestList);
    $studentInterestList = array();
    while ($preparedStatement->fetch())
    {
        $row = $interestList;
        $studentArray = explode(",",$row);
    }
    return $studentArray;
}

function getCareerInterests($connection)
//returns a 2d array of interests for each career
{
    $sql = "SELECT interestList FROM careersTable";
    $preparedStatement = $connection->prepare($sql) or die($connection->error);
    $preparedStatement->execute();
    $preparedStatement->bind_result($interests);
    $careerNameList = array();
    $careerInterestList = array();
    while($preparedStatement->fetch())
    {
        array_push($careerInterestList,explode(",",$interests));
    }

    return $careerInterestList;
}

function getCareerNames($connection)
{
    $sql = "SELECT careerName FROM careersTable";
    $preparedStatement = $connection->prepare($sql) or die($connection->error);
    $preparedStatement->execute();
    $preparedStatement->bind_result($name);
    $careerNameList = array();
    $careerInterestList = array();
    while($preparedStatement->fetch())
    {
        array_push($careerNameList,$name);
    }

    return $careerNameList;
}

function intCheck($array1,$array2)
{
    $arrayIntersection = array_intersect($array1,$array2);
    return (count($arrayIntersection)/count($array1))*100;
}

function getAllInterests($connection)
{
    //gets IDs and names from the INterestTable
    $sql = "SELECT interestID, interestName FROM interestTable";
    $preparedStatement = $connection->prepare($sql) or die("error: " . $connection->error);
    $preparedStatement->execute() or die("execution error");
    $preparedStatement->bind_result($interestID, $interestName) or die("result error");

    $list = array();

    while ($preparedStatement->fetch())
    {
        $row = $interestID . "|" . $interestName;
        array_push($list,$row);
    }

    return $list;
}

function addInterests($connection, $list)
{
    $sql = "INSERT INTO studentInterestsTable (studentID,interestID) VALUES(?,?)";
    $preparedStatement = $connection->prepare($sql) or die($connection->error);

    for($i=0;$i<count($list);$i++)
    {
        $listVal = $list[$i];
        $preparedStatement->bind_param("ii",$_SESSION['studentID'],$listVal) or die("error1");
        $preparedStatement->execute() or die($connection->error);
    }
}

function removeInterests($connection,$list)
{
    $sql = "DELETE FROM studentInterestsTable WHERE studentID = ? AND interestID = ?";
    $preparedStatement = $connection->prepare($sql) or die($connection->error);

    for($i=0;$i<count($list);$i++)
    {
        $preparedStatement->bind_param("ii",$_SESSION['studentID'],$list[$i]);
        $preparedStatement->execute();
    }
}