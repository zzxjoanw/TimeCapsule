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

function getGCSEList($connection,$country)
{
    $sql = "SELECT name FROM gcseTable WHERE country LIKE %?%"; //check table name
    $preparedStatement = $connection->prepare($sql) or die("error: ".$connection->error);
    $preparedStatement->bind_param("s",$country) or die("getGCSEList bindparam error");
    $preparedStatement->execute();
    $preparedStatement->bind_result($name);

    return $preparedStatement->fetch_array(MYSQLI_NUM);
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
    $sql = "SELECT interestID FROM studentInterestsTable WHERE studentID = '" . $id . "'";
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