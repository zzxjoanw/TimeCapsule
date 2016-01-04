<?php
/**
 * Created by PhpStorm.
 * User: Laura 4
 * Date: 11/10/2015
 * Time: 9:24 PM
 */

function openDBConnection()
{
    //these are overwritten in auth-info.php
    $host = "";
    $username = "";
    $password = "";
    $database = "";

    include("auth-info.php");

    $connection = new mysqli($host,$username,$password,$database) or die($connection->error);

    return $connection;
}

function closeDBConnection($connection)
{
    $connection->close();
}

function closeQuery($preparedStatement)
{
    $preparedStatement->close();
}

function doLogin($connection, $email,$password)
{
    $sql = "SELECT studentID, firstname,lastname,country FROM studentTable WHERE email = ? AND password = ?"; //check column names
    $preparedStatement = $connection->prepare($sql) or die("error: " . $connection->error);
    $preparedStatement->bind_param("ss",$email,$password) or die("error in doLogin()");
    $preparedStatement->execute();
    $preparedStatement->store_result();
    $preparedStatement->bind_result($studentID,$firstname,$lastname,$country);

    $list = array();

    if($preparedStatement->num_rows() == 1)
    {
        while($preparedStatement->fetch())
        {
            array_push($list,$studentID);
            array_push($list,$firstname);
            array_push($list,$lastname);
            array_push($list,$country);
        }
        return $list;
    }
    return false;
}

function logout()
{
    unset($_SESSION['firstname']);
    unset($_SESSION['lastname']);
    unset($_SESSION['country']);
    session_destroy();
}

function getSchoolList($connection,$country)
{
    //gets the names and ids of all schools in a given country. these will be displayed in a dropdown list
    $sql = "SELECT schoolID,name FROM schoolTable WHERE country = ?";
    $preparedStatement = $connection->prepare($sql) or die("error: ".$connection->error);
    $preparedStatement->bind_param("s",$country) or die("error39");
    $preparedStatement->execute();
    $preparedStatement->bind_result($id, $name);

    if($country == "Northern Ireland")
    //the value of country is used as an id in the reg form. it needs to be one word.
    //the reg form will submit the country name to the db as one word now.
    {
        $country = "NorthernIreland";
    }

    $idString = "schools".$country;
    $output = "<select class='form-control schools' name='schools' id='" . $idString . "'>";
    while ($preparedStatement->fetch())
    {
        $output .= "<option value='" . $id . "'>" . $name . "</option>";
    }
    $output .= "</select>";
    return $output;
}

function insertStudent($sql, $connection, $firstname, $lastname, $email, $password)
{
    $preparedStatement = $connection->prepare($sql) or die("error: ".$preparedStatement->error());
    $preparedStatement->bind_param("ssss",$firstname,$lastname,$email,$password) or die("error: ".$preparedStatement->error());
    $result = $preparedStatement->execute();
}
?>