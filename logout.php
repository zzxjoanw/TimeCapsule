<?php
/**
 * Created by PhpStorm.
 * User: Laura 4
 * Date: 12/13/2015
 * Time: 11:00 PM
 */

include("includes/db-functions.php");
session_start();
logout();

header("location:main.php");

?>