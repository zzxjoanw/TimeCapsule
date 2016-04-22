<?php
/**
 * Created by PhpStorm.
 * User: Laura 5
 * Date: 4/11/2016
 * Time: 3:54 PM
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

$careerInfo = getCareerInfo($connection);
$careerInterestList = getCareerInterests($connection);
$studentArray = getInterests($connection,$_SESSION['studentID']);
$firstRunComplete = isFirstRunComplete($connection, $_SESSION['studentID']);


$studentInterestList = array();
for($i=0;$i<count($studentArray);$i++)
{
    array_push($studentInterestList,$studentArray[$i][0]);
}

if(isset($_POST['frButton']))
{
    setCareer($connection,$_POST['careerChoice'],$_SESSION['studentID']);
    header("Location:firstrun.php");
}

if(isset($_POST['profileButton']))
{
    setCareer($connection,$_POST['careerChoice'],$_SESSION['studentID']);
    header("Location:profile.php");
}

closeDBConnection($connection);

?>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <title>Time Capsule - Choose your Career</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.2.0.js"></script>
    <script type="text/javascript" src="js/jquery.tablesorter.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="css/style.css">

    <script language="JavaScript">
        $(document).ready(function(){
            $('#careerTable').tablesorter({
                sortList: [[1,1]],
                headers: {
                    0: {sorter: false}
                }
            });
        });
    </script>
</head>
<body>
    <? include("includes/nav.php"); ?>
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <section>
                    Based on your answers from the previous section
                </section>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <section>
                    <form action="career.php" method="post">
                        <?
                        echo "<table id='careerTable' class='table table-striped tablesorter'>";
                        echo "<thead>";
                        echo "<th data-sorter='false'></th><th>Name</th><th>% Match</th><th>Description</th>";
                        echo "</thead>";
                        echo "<tbody>";

                        $maxValue = 0;
                        for($i=0;$i<count($careerInfo);$i++)
                        {
                            $width = intCheck(explode(",",$careerInterestList[$i][1]),$studentInterestList);
                            if($width > $maxValue)
                            {
                                $maxValue = $width;
                            }
                        }
                        for($i=0;$i<count($careerInfo);$i++)
                        {
                            $width = intCheck(explode(",",$careerInterestList[$i][1]),$studentInterestList);
                            $width = ($width/$maxValue)*100;
                            echo "<tr>";
                            echo "<td>";
                            echo "<span>";
                            echo "<input type='radio' name='careerChoice' value='" . $careerInfo[$i][0] . "'>";
                            echo "<span>";
                            echo "</td>";
                            echo "<td><span>".$careerInfo[$i][1]."</span></td>";
                            echo "<td>";
                            echo "<div style='width:100px; border:1px solid lightgrey; border-radius:3px; float:left;'>";
                            echo "<div style='background-color:blue;width:".$width."'>&nbsp;</div>";
                            echo "</div>";
                            echo "</td>";
                            echo "<td><a href='#' data-toggle='modal' data-target='#careerDescModal'>".$careerInfo[$i][2]."</a></td>";
                            echo "</tr>";
                        }

                        echo "</tbody>";
                        echo "</table>";

                        if(!$firstRunComplete)
                        {
                            echo "<button class='btn btn-success' name='frButton'>Save and continue registration</button>";
                        }
                        else
                        {
                            echo "<button class='btn btn-success' name='profileButton'>Save and return to profile</button>";
                        }
                        ?>
                    </form>
                </section>
            </div>
        </div>
    </div>
</body>
</html>