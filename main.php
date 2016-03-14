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
        $_SESSION['careerName'] = $user[5];
        $_SESSION['interestList'] = $user[6];
        $_SESSION['gcseList'] = $user[7];

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
        <script>
            $(document).ready(function() {
                $('#loginBttn').click(function () {
                    $('#loginModal').modal();
                });

                /*$('#registerBttn').click(function(){
                 $('#regModal').modal();
                 });*/
            });
        </script>
    </head>
    <body>
        <? include("includes/nav.php"); ?>
        <div id="main">
            <section>
                <header>So what is this site?</header>
                <span>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum interdum luctus ipsum ac
                    varius. Vestibulum eget diam tempus leo auctor venenatis. Sed eget felis vel magna porta
                    ullamcorper. Curabitur eu sagittis magna. Donec vel condimentum nulla. Nullam et posuere quam.
                    Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Sed
                    congue lorem nec risus mollis, ac condimentum tellus blandit. Nulla consectetur leo in velit
                    egestas tempor. In hac habitasse platea dictumst. Proin tincidunt dapibus lectus ut commodo. Donec
                    ac faucibus quam. Integer sit amet finibus dolor. Pellentesque habitant morbi tristique senectus et
                    netus et malesuada fames ac turpis egestas. Fusce viverra sed lorem eleifend tincidunt.
                </span>
            </section>
            <hr>
            <section>
                <header>How does it work?</header>
                <span>
                    Quisque et nunc gravida, mattis justo vel, cursus ex. Proin sit amet tellus in ligula hendrerit
                    gravida eget ut ligula. Praesent ac felis porttitor elit sagittis dignissim. Nulla pulvinar congue
                    egestas. Duis ante tortor, sollicitudin et nisl ultrices, varius ultricies sapien. Etiam sit amet
                    magna erat. Curabitur dignissim nunc nulla, eu dictum mauris congue eget. Duis in lorem et magna
                    iaculis fringilla. Sed non rhoncus massa. Mauris pellentesque elit ut ante tristique cursus eget et
                    orci. Nullam dapibus, dui eget dictum pretium, turpis sapien viverra eros, sit amet facilisis ipsum
                    sapien eu orci. In mattis, mi sed facilisis mattis, diam magna tincidunt velit, in rutrum diam leo
                    quis elit.
                </span>
            </section>
        </div>
    </body>
</html>