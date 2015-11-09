<?php
/**
 * Created by PhpStorm.
 * User: Laura 4
 * Date: 11/2/2015
 * Time: 8:48 PM
 */
?>

<?php /*
$servername = "localhost";
$username = "username";
$password = "password";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";*/
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
        <div id="main"></div>
    </body>
</html>