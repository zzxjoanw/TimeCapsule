<?php
/**
 * Created by PhpStorm.
 * User: Laura 5
 * Date: 2/11/2016
 * Time: 7:13 PM
 */

session_start();
if(!isset($_SESSION['firstname']))
{
    header('Location: main.php') ;
}

include("includes/db-functions.php");
include("includes/user-functions.php");
include("includes/misc-functions.php");
$connection = openDBConnection();

if(isset($_POST['bttnInterests']))
{
    echo "x".$_SESSION['studentID'] . "x";
    echo setFirstRunValue($connection,1,$_SESSION['studentID']);
    addInterests($connection,$_POST['addInterests'],$_SESSION['studentID']);

    var_dump($_POST['addInterests']);
    //header('Location:profile.php');
}
?>

<html>
<head>
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

        $allInterestsList = getAllInterests($connection);
        $careerInterestList = getCareerInterests($connection);
        $studentArray = getMyInterests($connection,$_SESSION['studentID']);
        $careerNameList = getCareerNames($connection);
    ?>
<div id="main">
    <a name="top"></a>
    <a href="#interests">Interests</a>
    <a href="#careers">careers</a>

    <section>
        <a name="interests">Interests</a><a href="#top">Top</a><br/>
        <form action="firstrun.php" method="post" id="formShowInterestsFirstRun">
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
                            echo "<td class='cellBoxSpacer'>";
                            echo "<div class='cboxWrapper'>";
                            echo "<input type='checkbox' class='cbox cboxAdd' name='addInterests[]' value='".$id."' id='ia".$id."'>";
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

    <hr>
    <? if(isset($_POST['bttnInterests']))
    { ?>
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
    <? } ?>
</div>
</body>
</html>