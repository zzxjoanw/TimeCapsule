<?php
/**
 * Created by PhpStorm.
 * User: Laura 5
 * Date: 2/11/2016
 * Time: 7:13 PM
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

//first pass
$allInterestsList = getAllInterests($connection);
$title = "Time Capsule - Choose your Interests";

//second
if(isset($_POST['bttnInterests']))
{
    $title = "Time Capsule - Choose a Career";

    setFirstRunValue($connection,1,$_SESSION['studentID']);
    addInterests($connection,$_POST['addInterests'],$_SESSION['studentID']);
    getCareerInfo($connection);
    $careerInterestList = getCareerInterests($connection);
    $studentArray = getMyInterests($connection,$_SESSION['studentID']);

    //combine these three into one function
    $careerNameList = getCareerNames($connection);
    $careerIDList = getCareerIDs($connection);
    $careerDescriptionList = getCareerDescriptions($connection);
}

//third
else if(isset($_POST['bttnCareer']))
{
    $title = "Time Capsule - Choose A-Level Options";
    setCareer($connection,$_POST['careerChoice'],$_SESSION['studentID']);
    $aLevelsList = getAllALevels($connection,$_SESSION['country']);
}

//fourth
else if(isset($_POST['bttnALevels']))
{
    $title = "Time Capsule - Choose GCSE Options";
    addALevels($connection, $_POST['addALevels']);
    $gcseList = getAllGCSEs($connection,$_SESSION['country']);
}

//fifth
else if(isset($_POST['bttnGCSEs']))
{
    addGCSEs($connection,$_POST['addGCSEs']);
    setFirstRunValue($connection,1,$_SESSION['studentID']);
    header('Location: profile.php');
}
?>

<html>
<head>
    <title><? echo $title; ?></title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.2.0.js"></script>
    <script type="text/javascript" src="js/jquery.tablesorter.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="js/themes/blue/style.css">

    <script language="JavaScript">
        $(document).ready(function(){
            $('#careerTable').tablesorter({ sortList: [[1,1]] });
        });
    </script>
</head>
<body>
    <?
        include("includes/nav.php");
    ?>
    <!-- career desc placeholder modal -->
    <div class="modal fade" id="careerDescModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Career Name</h4>
                </div>
                <form action="main.php" method="post">
                    <div class="modal-body">
                        Career description goes here<br/>
                        Career requirements, suggested classes
                    </div>
                </form>
            </div>
        </div>
    </div>
<div id="main">
    <?
        if(isset($_POST['bttnALevels']))
        {
            ?>
            <section>
                <form action="firstrun.php" method="post" id="formShowGCSEs">
                    <table>
                        <th></th>
                        <th>Name</th>
                        <?
                        foreach ($gcseList as $gcse) {
                            $gcseArray = explode("|", $gcse);
                            echo "<tr>";
                            echo "<td class='cellBoxSpacer'>";
                            echo "<div class='cboxWrapper'>";
                            echo "<input type='checkbox' class='cbox cboxAdd' name='addGCSEs[]' value='".$gcseArray[0]."' id='ga".$gcseArray[0]."'>";
                            echo "<label for='ga" . $gcseArray[0] . "'>+</label>";
                            echo "</div>";
                            echo "</td>";
                            echo "<td>$gcseArray[1]</td>";
                            echo "</tr>";
                        }
                        ?>
                    </table>
                    <button class="btn btn-submit" name="bttnGCSEs">Submit</button>
                </form>
            </section>
            <?
        }
        else if(isset($_POST['bttnCareer'])) {
            ?>
            <section>
                <form action="firstrun.php" method="post" id="formShowALevels">
                    <table>
                        <th></th>
                        <th>ID</th>
                        <th>Name</th>
                        <?
                            foreach ($aLevelsList as $aLevel) { //this line works fine on all possible paths thru code
                                $aLevelArray = explode("|", $aLevel);
                                echo "<tr>";
                                echo "<td class='cellBoxSpacer'>";
                                echo "<div class='cboxWrapper'>";
                                echo "<input type='checkbox' class='cbox cboxAdd' name='addALevels[]' value='".$aLevelArray[0]."' id='aa".$aLevelArray[0]."'>";
                                echo "<label for='aa" . $aLevelArray[0] . "'>+</label>";
                                echo "</div>";
                                echo "</td>";
                                echo "<td>$aLevelArray[0]</td>";
                                echo "<td>$aLevelArray[1]</td>";
                                echo "</tr>";
                            }
                        ?>
                    </table>
                    <button class="btn btn-submit" name="bttnALevels">Submit</button>
                </form>
            </section>
            <section>
            <?
        }
     else
    {
    if(isset($_POST['bttnInterests']))
    { ?>
        <section>
            <form action="firstrun.php" method="post">
                <?
                echo "<table border='1' id='careerTable' class='tablesorter'>";
                echo "<thead>";
                echo "<th></th><th>Name</th><th>% Match</th><th>Description</th>";
                echo "</thead>";
                echo "<tbody>";

                //for($i=0;$i<count($_SESSION['careerArray'];$i++)
                for($i=0;$i<count($careerInterestList);$i++)
                {
                    echo "<tr>";
                    echo "<td><input type='radio' name='careerChoice' value='" . $careerIDList[$i] . "'></td>";
                    echo "<td>".$careerNameList[$i]."</td>";
                    echo "<td>".intCheck($studentArray,$careerInterestList[$i])."</td>";
                    echo "<td><a href='#' data-toggle='modal' data-target='#careerDescModal'>".$careerDescriptionList[$i]."</a></td>";
                    //echo "<td><input type='radio' name='careerChoice' value='" . $_SESSION['careerArray'][$i]->getID() . "'></td>";
                    //echo "<td>".$_SESSION['careerArray'][$i]->getName()."</td>";
                    //echo "<td>".$_SESSION['careerArray'][$i]->getInterestList()."</td>";
                    //echo "<td>".$_SESSION['careerArray'][$i]->getDescription()."'></td>";
                    echo "</tr>";
                }

                echo "</tbody>";
                echo "</table>";
                ?>
                <button class="btn btn-submit" name="bttnCareer">Choose this career</button>
            </form>
        </section>
    <? }
    else
    {
    ?>
        <form action="firstrun.php" method="post" id="formShowInterestsFirstRun">
            <table>
                <?
                for ($i = 0; $i < count($allInterestsList); $i++) {
                    $values = explode("|", $allInterestsList[$i]);
                    $id = $values[0];
                    $name = $values[1];
                    ?>
                    <tr>
                        <?
                        echo "<td class='cellBoxSpacer'>";
                        echo "<div class='cboxWrapper'>";
                        echo "<input type='checkbox' class='cbox cboxAdd' name='addInterests[]' value='" . $id . "' id='ia" . $id . "'>";
                        echo "<label for='ia" . $id . "'>+</label>";
                        echo "</div>";
                        echo "</td><td class='cboxCellSpacer'></td>";
                        ?>
                        <td><? echo $name; ?></td>
                    </tr>
                    <?
                }
                ?>
            </table>
            <button class="btn btn-submit" name="bttnInterests">Add Interests</button>
        </form>
    </section>
    <?
    }
    }
    ?>
</div>
</body>
</html>