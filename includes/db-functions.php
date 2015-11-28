<?php
/**
 * Created by PhpStorm.
 * User: Laura 4
 * Date: 11/10/2015
 * Time: 9:24 PM
 */

function doConnect()
{
    $username = "dcwecddg_TimeCap";
    $password = "F&#wsAZp_Lap";
    $host = "server157.web-hosting.com:21098";
    $correctFingerprint = "";

    $connection = ssh2_connect($host);
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
    }
}

function doQuery($SQL, $connection)
{
    $result = ssh2_exec($connection,$SQL);

    if(!$result)
    {
        die("query failed");
    }

    return $result;
}
?>