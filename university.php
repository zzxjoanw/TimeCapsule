<?php
/**
 * Created by PhpStorm.
 * User: Laura 5
 * Date: 4/15/2016
 * Time: 1:48 PM
 */

session_start();
if(!isset($_SESSION['studentID']))
{
    header('Location: main.php') ;
}

include("includes/db-functions.php");
include("includes/user-functions.php");
include("includes/university-functions.php");
include("includes/misc-functions.php");

$connection = openDBConnection();
$courseList = getUniversities($connection);
$firstRunComplete = isFirstRunComplete($connection, $_SESSION['studentID']);

if(isset($_POST['frButton']))
{
    addUniversities($connection,$_POST['addCourses'], $_POST['courseProgress']);
    header("Location:firstrun.php");
}

if(isset($_POST['profileButton']))
{
    addUniversities($connection,$_POST['addCourses'], $_POST['courseProgress']);
    addUniversities($connection,$_POST['addCourses'],'x');
    header("Location:profile.php");
}

closeDBConnection($connection);

?>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <title>Time Capsule - Choose GCSE Options</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.2.0.js"></script>
    <script type="text/javascript" src="js/jquery.tablesorter.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="js/themes/blue/style.css">
</head>
<body>
<? include("includes/nav.php"); ?>
<div id="main">
    <section>
        <form action="university.php" method="post" id="formShowUniversities">
            <table>
                <th></th>
                <th>Course Name</th>
                <?
                for($i=0;$i<count($courseList);$i++)
                {
                    echo "<tr>";
                    echo "<td class='cellBoxSpacer'>";
                    echo "<div class='cboxWrapper'>";
                    $value = $courseList[$i][2] . $courseList[$i][1];

                    echo "<input type='checkbox' class='cbox cboxAdd' name='addCourses[]' value='" . $value . "' id='ca" . $courseList[$i][0] . "'>";
                    echo "<label for='ca" . $courseList[$i][0] . "'>+</label>";
                    echo "</div>";
                    echo "</td>";
                    echo "<td>" . $courseList[$i][1] . "</td>";

                    echo "<td>";
                    echo "<select name='courseProgress[]'>";
                    echo "<option value='0'>Not started yet</option>";
                    echo "<option value='1'>In Progress</option>";
                    echo "<option value='Finished'>Finished</option>";
                    echo "</tr>";
                }
                ?>
            </table>
            <?
            if(!$firstRunComplete)
            {
                echo "<button name='frButton'>Save and continue registration</button>";
            }
            else
            {
                echo "<button name='profileButton'>Save and return to profile</button>";
            }
            ?>
        </form>
    </section>
</div>
</body>
</html>