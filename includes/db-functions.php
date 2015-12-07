<?php
/**
 * Created by PhpStorm.
 * User: Laura 4
 * Date: 11/10/2015
 * Time: 9:24 PM
 */

include("user-functions.php");

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

/*
function sendSelectQuery($connection,$table,$parameters, $types, $values, $result)
{
    $where = "";
    for($i=0; i<$parameters->length; $i++)
    {
        $where .= $parameters[i] . "=" . $values[i];

        if($i != $parameters->length-1)
        {
            $where .= "AND ";
        }
    }
    $where .= ";";
    $sql = "SELECT * FROM " . $table . " WHERE " . $where;
    call_user_func_array("bind_param",array($parameters));
    $preparedStatement->bind_param($types,
    $preparedStatement->execute();
}
*/

function doLogin($connection, $username,$password)
{
    $sql = "SELECT * FROM studentTable WHERE username = ? AND password = ?"; //check column names
    $preparedStatement = $connection->prepare($sql) or die("error: " . $connection->error);
    $preparedStatement->bind_param("ss",$username,$password) or die("error in doLogin()");
    $preparedStatement->execute();
    $preparedStatement->bind_result($username,$firstname,$lastname,$country);

    while($preparedStatement->fetch())
    {
        if($preparedStatement->num_rows() == 1)
        {
            $user = new user($firstname,$lastname,$country);
            return $user;
        }
        else
        {
            return false;
        }
    }
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

function getGCSEList($connection,$country)
{
    $sql = "SELECT * FROM gcseTable WHERE country = ?";
    $preparedStatement = $connection->prepare($sql) or die("error: ".$connection->error);
    $preparedStatement->bind_param("s",$country) or die("getGCSEList bindparam error");
    $preparedStatement->execute();
    $preparedStatement->bind_result($name, $id);

    $output = "";
    while($preparedStatement->fetch())
    {
        $output .= "<input type='checkbox' name='' value='".  $id."'>" . $name . "<br/>";
    }

    return $output;
}

function insertStudent($sql, $connection, $firstname, $lastname, $email, $password)
{
    $preparedStatement = $connection->prepare($sql) or die("error: ".$preparedStatement->error());
    $preparedStatement->bind_param("ssss",$firstname,$lastname,$email,$password) or die("error: ".$preparedStatement->error());
    $result = $preparedStatement->execute();
}
?>