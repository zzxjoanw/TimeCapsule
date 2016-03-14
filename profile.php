<?php
/**
 * Created by PhpStorm.
 * User: Laura 4
 * Date: 11/29/2015
 * Time: 8:36 PM
 */

session_start();
if(!isset($_SESSION['studentID']))
{
    header('Location: main.php') ;
}

include("includes/db-functions.php");
include("includes/user-functions.php");
include("includes/misc-functions.php");

$connection = openDBConnection();

?>

<html>
<head>
    <title>Profile</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.2.0.js"></script>
</head>
<body>
    <?
        include("includes/nav.php");
        $gcseList = getMyGCSEs($connection,$_SESSION['studentID']);
        $aLevelList = getMyALevels($connection, $_SESSION['studentID']);
    ?>
    <div id="main">
        <section>Your chosen career: <? echo $_SESSION['careerName']; ?></section>
        <section>Possible courses:</section>
        <section>Your interests: <? echo $_SESSION['interestList']; ?></section>
        <section>
            GCSEs:
            <?
                for($i=0;$i<count($gcseList);$i++)
                {
                    echo $gcseList[$i][1] . "<br/>";
                }
            ?>
        </section>
        <section>
            A-Levels:
            <?
                for($i=0;$i<count($aLevelList);$i++)
                {
                    echo $aLevelList[$i][1] . "<br/>";
                }
            ?>
        </section>
        <section>
            <svg>
                <circle cx="10" cy="10" r="10" style="stroke-width:1;stroke:lightgrey; fill:none"/>
                <circle cx="40" cy="10" r="10" style="stroke-width:4;stroke:blue;fill:none"/>
                <circle cx="70" cy="10" r="10" style="fill:blue"/>
            </svg>
        </section>
    </div>
</body>
</html>