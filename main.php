<?php
/**
 * Created by PhpStorm.
 * User: Laura 4
 * Date: 11/2/2015
 * Time: 8:48 PM
 */

include("includes/db-functions.php");
include("includes/user-functions.php");

$connection = openDBConnection();

if(isset($_SESSION['firstname']))
{
    echo "already logged in";
}

if(isset($_POST['bttnLogin']))
{
    $user = doLogin($connection,$_POST['email'],$_POST['password']);

    if($user != false)
    {
        session_start();
        $_SESSION['studentID'] = $user[0];
        $_SESSION['firstname'] = $user[1];
        $_SESSION['lastname'] = $user[2];
        $_SESSION['country'] = $user[3];
        $_SESSION['firstRunComplete'] = $user[4];

        if($_SESSION['firstRunComplete'] == 0)
        {
            header("Location: firstrun.php");
        }
    }
    else
    {
        echo "login failed";
    }
}
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
        <? include("includes/nav.php"); ?>
        <div id="main">
            <section>
                <header>So what is this site?</header>
            </section>
            <section>
                <header>How does it work?</header>
            </section>
        </div>
    </body>
</html>