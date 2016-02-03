<?php
/**
 * Created by PhpStorm.
 * User: Laura 4
 * Date: 11/29/2015
 * Time: 8:36 PM
 */

session_start();
if(!isset($_SESSION['firstname']))
{
    header('Location: main.php') ;
}

include("includes/db-functions.php");
include("includes/user-functions.php");
include("includes/misc-functions.php");

//move section to own file
$connection = openDBConnection();

$id = $_SESSION['studentID'];
$sql = "SELECT interestList FROM studentTable WHERE studentID = $id";
$preparedStatement = $connection->prepare($sql) or die($connection->error);
$preparedStatement->execute();
$preparedStatement->bind_result($interestList);
$studentInterestList = array();
while ($preparedStatement->fetch())
{
    $row = $interestList;
    $studentArray = explode(",",$row);
}

closeDBConnection($connection);

$connection = openDBConnection();
$sql = "SELECT careerName,interestList FROM careersTable";
$preparedStatement = $connection->prepare($sql) or die($connection->error);
$preparedStatement->execute();
$preparedStatement->bind_result($name,$interests);
$careerNameList = array();
$careerInterestList = array();
while($preparedStatement->fetch())
{
    array_push($careerNameList,$name);
    array_push($careerInterestList,explode(",",$interests));
}
function intCheck($array1,$array2)
{
    $arrayIntersection = array_intersect($array1,$array2);
    return (count($arrayIntersection)/count($array1))*100;
}
//end section to move
?>

<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.2.0.js"></script>
    <script type="text/javascript" src="js/jquery.tablesorter.js"></script>
    <link rel="stylesheet" type="text/css" href="js/themes/blue/style.css">

    <script language="JavaScript">
        $(document).ready(function(){
            $("#login-open").hide();
            $("#register-open").hide();

            $("#login span").click(function() {
                $("#login-open").toggle();
                var isLoginShown = $("#login-open").is(":visible");
            });

            $('#careerTable').tablesorter({ sortList: [[1,1]] });
        });
    </script>
</head>
<body>
    <?
        include("includes/nav.php");

        if(isset($_POST['bttnInterests']))
        {
            addInterests($connection,$_POST['addInterests']);
            removeInterests($connection,$_POST['removeInterests']);
        }

        if(isset($_POST['bttnGCSEs']))
        {
            addGCSEs($connection,$_POST['addGCSEs']);
            removeGCSEs($connection,$_POST['removeGCSEs']);
        }

        if(isset($_POST['bttnALevels']))
        {
            echo "profile:61 trying to add a-levels";
            addALevels($connection,$_POST['addALevels']);
            removeALevels($connection,$_POST['removeALevels']);
        }

        $myInterestsList = getMyInterests($connection, $_SESSION['studentID']);
        $otherInterestsList = getOtherInterests($connection, $_SESSION['studentID']);
        $allInterestsList = getAllInterests($connection);

        $allGCSEList = getAllGCSEsList($connection,$_SESSION['country']);
        $myGCSEList = getMyGCSEsList($connection,$_SESSION['studentID']);
        $otherGCSEList = getOtherGCSEs($connection,$_SESSION['studentID']);

        $allALevelList = getAllALevels($connection,$_SESSION['country']);
        $myALevelList = getMyALevels($connection,$_SESSION['studentID']);
        $otherALevelList = getOtherAlevels($connection,$_SESSION['studentID']);
    ?>
    <div id="main">
        <a name="top"></a>
        <a href="#interests">Interests</a>
        <a href="#gcses">gcses</a>
        <a href="#alevels">alevels</a>
        <a href="#careers">careers</a>

        <section>
            <a name="interests">Interests</a><a href="#top">Top</a><br/>
            <form action="profile.php" method="post" id="formShowInterests">
                <table>
                    <?
                        for($i=0; $i<count($allInterestsList);$i++)
                        {
                            $values = explode("|",$allInterestsList[$i]);
                            $id = $values[0];
                            $name = $values[1];
                            ?>
                                <tr>
                                    <?
                                        //array_search returns an index, or 0 if item not found :/
                                        $aSearch = array_search($id,$otherInterestsList);
                                        if(($aSearch)||(gettype($aSearch)=="integer"))
                                        {
                                            echo "<td class='cellBoxSpacer'>";
                                            echo "<div class='cboxWrapper'>";
                                            echo "<input type='checkbox' class='cbox cboxAdd' name='addInterests[]' value='".$id."' id='ia".$id."'>";
                                            echo "<label for='ia" . $id . "'>+</label>";
                                            echo "</div>";
                                            echo "</td><td class='cboxCellSpacer'></td>";
                                        }

                                        $rSearch = array_search($id,$myInterestsList);
                                        if(($rSearch)||(gettype($rSearch)=="integer"))
                                        {
                                            echo "<td class='cellBoxSpacerllSpacer'></td>";
                                            echo "<td class='cellBoxSpacer'><div class='cboxWrapper'>";
                                            echo "<input type='checkbox' class='cbox cboxRemove' name='removeInterests[]' value='".$id."' id='ir".$id."'>";
                                            echo "<label for='ir" . $id . "'>-</label>";
                                            echo "</div>";
                                            echo "</td>";
                                        }
                                    ?>
                                    <td><? echo $name ?></td>
                                </tr>
                             <?
                        }
                    ?>
                </table>
                <button class="btn btn-submit" name="bttnInterests">Add/Remove Interests</button>
            </form>
        </section>

        <hr>
        <section>
            <a name="gcses">GCSEs</a><a href="#top">Top</a><br/>
            <form action="profile.php" method="post" id="formShowGCSEs">
                <table>
                    <?
                    for($i=0; $i<count($allGCSEList);$i++)
                    {
                        $values = explode("|",$allGCSEList[$i]);
                        $id = $values[0];
                        $name = $values[1];
                        ?>
                        <tr>
                            <?
                            //array_search returns an index, or 0 if item not found :/
                            $aSearch = array_search($id,$otherGCSEList);
                            if(($aSearch)||(gettype($aSearch)=="integer"))
                            {
                                echo "<td class='cellBoxSpacer'>";
                                echo "<div class='cboxWrapper'>";
                                echo "<input type='checkbox' class='cbox cboxAdd' name='addGCSEs[]' value='".$id."' id='ga".$id."'>";
                                echo "<label for='ga" . $id . "'>+</label>";
                                echo "</div>";
                                echo "</td><td class='cellBoxSpacer'></td>";
                            }
                            else {
                                //$rSearch = array_search($id, $myInterestsList);
                                //if (($rSearch) || (gettype($rSearch) == "integer")) {
                                    echo "<td class='cellBoxSpacer'></td>";
                                    echo "<td class='cellBoxSpacer'><div class='cboxWrapper'>";
                                    echo "<input type='checkbox' class='cbox cboxRemove' name='removeGCSEs[]' value='" . $id . "' id='gr" . $id . "'>";
                                    echo "<label for='gr" . $id . "'>-</label>";
                                    echo "</div>";
                                    echo "</td>";
                                //}
                            }
                            ?>
                            <td><? echo $name ?></td>
                        </tr>
                        <?
                    }
                    ?>
                </table>
                <button class="btn btn-submit" name="bttnGCSEs">Add/Remove Interests</button>
            </form>
        </section>
        <hr>
        <section>
            <a name="alevels">A levels</a><a href="#top">Top</a><br/>
            <form action="profile.php" method="post" id="formShowALevels">
                <table>
                    <?
                    for($i=0; $i<count($allALevelList);$i++)
                    {
                        $values = explode("|",$allALevelList[$i]);
                        $id = $values[0];
                        $name = $values[1];
                        ?>
                        <tr>
                            <?
                            //array_search returns an index, or 0 if item not found :/
                            $aSearch = array_search($id,$otherALevelList);
                            //echo "aSearch = ".$aSearch.",".$name."<br/>";
                            if($aSearch!== false)
                            {
                                echo "<td class='cellBoxSpacer'>";
                                echo "<div class='cboxWrapper'>";
                                echo "<input type='checkbox' class='cbox cboxAdd' name='addALevels[]' value='".$id."' id='aa".$id."'>";
                                echo "<label for='aa" . $id . "'>+</label>";
                                echo "</div>";
                                echo "</td><td class='cellBoxSpacer'></td>";
                            }
                            else {
                                $rSearch = array_search($id, $myALevelList);
                                //echo "rSearch = " . $rSearch . "<br/>";
                                if (($rSearch) || (gettype($rSearch) == "integer")) {
                                    echo "<td class='cellBoxSpacer'></td>";
                                    echo "<td class='cellBoxSpacer'><div class='cboxWrapper'>";
                                    echo "<input type='checkbox' class='cbox cboxRemove' name='removeALevels[]' value='" . $id . "' id='ar" . $id . "'>";
                                    echo "<label for='ar" . $id . "'>-</label>";
                                    echo "</div>";
                                    echo "</td>";
                                }
                            }
                            ?>
                            <td><? echo $name ?></td>
                        </tr>
                        <?
                    }
                    ?>
                </table>
                <button class="btn btn-submit" name="bttnALevels">Add/Remove A Levels</button>
            </form>
        </section>
        <section>
            <a name="careers">Careers</a><a href="#top">Top</a><br/>
            <?
                echo "<table border='1' id='careerTable' class='tablesorter'>";
                echo "<thead>";
                echo "<th>Name</th><th>% Match</th>";
                echo "</thead>";
                echo "<tbody>";

                for($i=0;$i<count($careerInterestList);$i++)
                {
                    echo "<tr>";
                    echo "<td>".$careerNameList[$i]."</td>";
                    echo "<td>".intCheck($studentArray,$careerInterestList[$i])."</td>";
                    echo "</tr>";
                }

                echo "</tbody>";
                echo "</table>";
            ?>
        </section>
    </div>
</body>
</html>