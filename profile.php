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

$connection = openDBConnection();
?>

<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/style.css">

    <script language="JavaScript">
        $(document).ready(function(){
            $("#login-open").hide();
            $("#register-open").hide();

            $("#login span").click(function() {
                $("#login-open").toggle();
                var isLoginShown = $("#login-open").is(":visible");
            });
        });
    </script>
</head>
<body>
    <?
        include("includes/nav.php");

        if(isset($_POST['bttnSubmit']))
        {
            addInterests($connection,$_POST['addInterests']);
            removeInterests($connection,$_POST['removeInterests']);
        }

        $myInterestsList = getMyInterests($connection, $_SESSION['studentID']);
        $otherInterestsList = getOtherInterests($connection, $_SESSION['studentID']);
        $allInterestsList = getAllInterests($connection);

        $allGCSEList = getAllGCSEsList($connection,$_SESSION['country']);
    ?>
    <div id="main">
        <hr>
        Interests <br/>
        <form action="profile.php" method="post" id="formShowInterests">
            <table border="1">
                <tr>
                    <td>Name</td><td>Add</td><td>Remove</td>
                </tr>
                <?
                    for($i=0; $i<count($allInterestsList);$i++)
                    {
                        $values = explode("|",$allInterestsList[$i]);
                        $id = $values[0];
                        $name = $values[1];
                        ?>
                            <tr>
                                <td><? echo $name . ", " . $id ?></td>
                                <?
                                    //array_search returns an index, or 0 if item not found :/
                                    $aSearch = array_search($id,$otherInterestsList);
                                    if(($aSearch)||(gettype($aSearch)=="integer"))
                                    {
                                        echo "<td>";
                                        echo "<div class='cboxWrapper'>";
                                        echo "<input type='checkbox' class='cbox cboxAddInterests' name='addInterests[]' value='".$id."' id='a".$id."'>";
                                        echo "<label for='a" . $id . "'></label>";
                                        echo "</div>";
                                        echo "</td><td></td>";
                                    }

                                    $rSearch = array_search($id,$myInterestsList);
                                    if(($rSearch)||(gettype($rSearch)=="integer"))
                                    {
                                        echo "<td></td>";
                                        echo "<td><div class='cboxWrapper'>";
                                        echo "<input type='checkbox' class='cbox cboxRemoveInterests' name='removeInterests[]' value='".$id."' id='r".$id."'>";
                                        echo "<label for='r" . $id . "'></label>";
                                        echo "</div>";
                                        echo "</td>";
                                    }
                                ?>
                            </tr>
                         <?
                    }
                ?>
            </table>
            <button class="btn btn-submit" name="bttnSubmit">Add/Remove Interests</button>
        </form>
        <hr>
        GCSEs <br/>
        <?
            $country = $_SESSION['country'];
            $gcseList = getAllGCSEsList($connection,$country);
        ?>
    </div>
</body>
</html>