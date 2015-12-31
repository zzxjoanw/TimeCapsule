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

function getOtherInterests($connection)
//gets all interests not associated with the current student
{
    $studentID = $_SESSION['studentID']; //can't have arrays in SQL statements
    $sql = "SELECT name FROM interestsTable WHERE interestID NOT IN
              SELECT * FROM studentInterestTable WHERE studentID = $studentID";
    $preparedStatement = $connection->prepare($sql) or die("error: ".$connection->error);
    $preparedStatement->execute();
    $preparedStatement->bind_result($name);

    return $preparedStatement->fetch_array(MYSQLI_NUM);
}

function getMyInterests($connection)
//gets all the current student's interests
{
    $sql = "SELECT interestID FROM studentInterestsTable WHERE studentID";
    $preparedStatement = $connection->prepare($sql) or die("error: ".$connection->error);
    $preparedStatement->execute();
    $preparedStatement->bind_result($interestID);

    return $preparedStatement->fetch_array(MYSQLI_NUM);
}