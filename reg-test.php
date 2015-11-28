<?php
/**
 * Created by PhpStorm.
 * User: Laura 4
 * Date: 11/24/2015
 * Time: 9:58 PM
 */

include("includes/db-functions.php");

$connection = doConnect();

$firstname = "Laura";
$lastname = "Mayer-Sommer";
$email = "zzxjoanw@gmail.com";
$password = "test";

$sql = "INSERT INTO studentTable(firstname,lastname,email,password) VALUES(?,?,?,?)";
insertStudent($sql,$connection,$firstname,$lastname,$email,$password);