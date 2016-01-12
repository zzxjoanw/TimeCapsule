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
    $sql = "SELECT gcseName FROM gcseTable WHERE gcseCountryList LIKE '%" . $initial . "%'"; //check table name
    $preparedStatement = $connection->prepare($sql) or die("error: ".$connection->error);
    $preparedStatement->execute();
    $preparedStatement->bind_result($name);

    $list = array();

    while($preparedStatement->fetch())
    {
        array_push($list,$name);
    }

    return $list;
}

function getMyGCSEsList($connection,$id)
{

}

function addGCSEs($connection, $list)
{

}

function removeGCSEs($connection, $list)
{

}

function getAllALevels($connection,$country)
{

}

function getMyALevels($connection, $id)
{

}

function addALevels($connection,$list)
{

}

function removeALevels($connection,$list)
{

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
    echo "other:";
    while($preparedStatement->fetch())
    {
        //echo $interestID . ",";
        array_push($list,$interestID);
    }
    var_dump($list);
    echo "<br/>";
 //   echo gettype($list[0]);
    return $list;
}

function getMyInterests($connection, $id)
//gets all the current student's interests
{
    $sql = "SELECT interestID FROM studentInterestsTable s WHERE studentID = " . $_SESSION['studentID'];

    $preparedStatement = $connection->prepare($sql) or die("error: " . $connection->error);
    $preparedStatement->execute() or die("execution error");
    $preparedStatement->bind_result($interestID) or die("result error");

    $list = array();
    echo "mine:";
    while ($preparedStatement->fetch())
    {
        echo $interestID . ",";
        array_push($list,$interestID);
    }

    echo gettype($list[0]);
    echo "<br/>";
    return $list;
}

function getAllInterests($connection)
{
    //gets IDs and names from the INterestTable
    $sql = "SELECT interestID, interestName FROM interestTable";
    $preparedStatement = $connection->prepare($sql) or die("error: " . $connection->error);
    $preparedStatement->execute() or die("execution error");
    $preparedStatement->bind_result($interestID, $interestName) or die("result error");

    $list = array();

    echo "all:";
    while ($preparedStatement->fetch())
    {
        echo $interestID . ",";
        $row = $interestID . "|" . $interestName;
        array_push($list,$row);
    }

    echo gettype($list[0]);
    echo "<br/>";
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