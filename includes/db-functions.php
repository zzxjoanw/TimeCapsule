<?php
/**
 * Created by PhpStorm.
 * User: Laura 4
 * Date: 11/10/2015
 * Time: 9:24 PM
 */

function connect()
{
    $servername = "server157.web-hosting.com:21098";
    $username = "dcwecddg_TimeCap";
    $password = "F&#wsAZp_Lap";

    // Create connection
    $conn = new mysqli($servername, $username, $password);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    echo "Connected successfully";
}

function doQuery($SQL)
{

}
?>