<?php
/**
 * Created by PhpStorm.
 * User: Laura 4
 * Date: 11/10/2015
 * Time: 9:24 PM
 */

function doConnect()
{
    //these are overwritten in auth-info.php
    $host = "";
    $correctFingerprint = "";
    $port = 0;
    $username = "";
    $password = "";
    $database = "";

    include("auth-info.php");

    $connection = mysqli_connect($host,$username,$password,$database);

    if(!$connection)
    {
        die("connection failed: " . mysqli_connect_error());
    }
    else
    {
        return $connection;
    }

    /* don't need remote connection
    $connection = ssh2_connect($host,$port);
    if(!$connection)
    {
        die("Connection failed");
    }
    else
    {
        $fingerprint = ssh2_fingerprint($connection);
        if($fingerprint != $correctFingerprint)
        {
            die("hostkey mismatch");
        }

        $auth = ssh2_auth_password($connection,$username,$password);
        if(!$auth)
        {
            die("authentication failed");
        }

        return $connection;
    }*/
}

function insertStudent($sql, $connection, $firstname, $lastname, $email, $password)
{
    $preparedStatement = mysqli_prepare($connection,$sql);
    $preparedStatement->bind_param("ssss",$firstname,$lastname,$email,$password);
    $result = mysqli_stmt_exec($preparedStatement);
}

function doQuery($sql, $connection)
{


    /*
    $result = ssh2_exec($connection,$SQL);

    if(!$result)
    {
        die("query failed");
    }

    return $result;*/
}
?>