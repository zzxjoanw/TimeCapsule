<?php
/**
 * Created by PhpStorm.
 * User: Laura 4
 * Date: 12/7/2015
 * Time: 2:16 PM
 */

function getUserInfo($connection,$email)
{
    $sql = "SELECT firstname,lastname,country FROM studentTable WHERE email = '" . $email . "'";
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

function getOtherInterests($connection, $id)
//gets all interests not associated with the current student
{
    $sql = "SELECT interestName FROM interestTable WHERE interestID NOT IN " .
           "(SELECT interestID FROM studentInterestsTable WHERE studentID = " . $id . ")";
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

function getMyInterests($connection, $id)
//gets all the current student's interests
{
    $sql = "SELECT i.interestName FROM interestTable i,studentInterestsTable s WHERE i.interestID = s.interestID
            AND s.studentID = " . $_SESSION['studentID'];

    $preparedStatement = $connection->prepare($sql) or die("error: " . $connection->error);
    $preparedStatement->execute() or die("execution error");
    $preparedStatement->bind_result($interestID) or die("result error");

    $list = array();

    while ($preparedStatement->fetch())
    {
        array_push($list,$interestID);
    }

    return $list;
}

function getAllInterests($connection)
{
    $sql = "SELECT interestName FROM interestTable";
    $preparedStatement = $connection->prepare($sql) or die("error: " . $connection->error);
    $preparedStatement->execute() or die("execution error");
    $preparedStatement->bind_result($interestID) or die("result error");

    $list = array();

    while ($preparedStatement->fetch())
    {
        array_push($list,$interestID);
    }

    return $list;
}

function addInterests($connection, $list)
{
    //loops thru numeric array of interest names, adding them to the studentInterestTable
}

function removeInterests($connection,$list)
{

}