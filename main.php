<?php
/**
 * Created by PhpStorm.
 * User: Laura 4
 * Date: 11/2/2015
 * Time: 8:48 PM
 */
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
            });

            $("login").click(function() {
                $("login-open").toggle();
            });
        </script>
    </head>
    <body>
        <div id="nav">
            <div id="logo">logo</div>
            <?
                if(!isset($_SESSION))
                {
                    ?>
                        <div id="login" class="nav-bttn">login</div>
                        <div id="login-open" class="nav-bttn">
                            <form>
                                <input type="text" name="username" value="">
                                <input type="password" name="password" value="">
                                <input type="submit" name="submit" value="submit">
                            </form>
                        </div>
                        <div id="register" class="nav-bttn">register</div>
                    <?
                }
                elseif($role == "student")
                {
                    ?>

                    <?
                }
                elseif($role=="parent")
                {
                    ?>
                    <?
                }
                elseif($role=="school")
                {
                    ?>
                    <?
                }
                elseif($role=="admin")
                {
                    ?>
                    <?
                }
            ?>
        </div>
        <div id="main"></div>
    </body>
</html>