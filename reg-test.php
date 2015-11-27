<?php
/**
 * Created by PhpStorm.
 * User: Laura 4
 * Date: 11/24/2015
 * Time: 9:58 PM
 */

include("includes/db-functions.php");

$connection = doConnect();
$sql = "INSERT INTO studentTable (firstname,lastname,email,password) VALUES('Laura','Mayer-Sommer','zzxjoanw@gmail.com','test')";
doQuery($sql,$connection);