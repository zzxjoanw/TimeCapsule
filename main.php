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
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <div id="nav">
            <div id="logo">logo</div>
            <?
                if(!isset($_SESSION))
                {
                    ?>
                        <div id="login">login</div>
                        <div id="register">register</div>
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