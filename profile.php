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
        $myInterestList = getMyInterests($connection, $_SESSION['studentID']);
        $otherInterestsList = getOtherInterests($connection, $_SESSION['studentID']);

        var_dump($myInterestList);
        var_dump($otherInterestsList);
    ?>
    <div id="main">
        <hr>
        Interests <br/>
        <table>
            <tr>
                <td>
                    <form action="profile.php" method="post">
                    <?
                        for($i=0;$i<count($myInterestList);$i++)
                        {
                            echo "<input type='checkbox' name='addList' value='<? $myInterestList[$i] ?>'>" . $myInterestList[$i];
                        }
                    ?>
                    </form>
                </td>
                <td>
                    <?
                        for($i=0;$i<count($otherInterestsList);$i++)
                        {
                            echo $otherInterestsList[$i];
                            echo "<a href='#' style='float:right'>add</a><br/>";
                        }
                    ?>
                </td>
            </tr>
        </table>
        <hr>
        GCSEs <br/>
        <?
            $country = $_SESSION['country'];
            $gcseList = getGCSEList($connection,$country);
        ?>
    </div>
</body>
</html>