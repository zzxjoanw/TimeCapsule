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
                $("#register-open").hide();

                $("#login span").click(function() {
                    $("#login-open").toggle();
                    var isLoginShown = $("#login-open").is(":visible");

                });

                $("#register span").click(function() {
                    $("#register-open").toggle();
                    var isRegShown = $("#register-open").is(":visible");
                });
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
                        <div id="login">
                            <div id="login-open">
                                <form>
                                    <input type="text" class="form-control" placeholder="Username">
                                    <input type="password" class="form-control" placeholder="Password">
                                    <button type="submit" class="btn btn-submit">Submit</button>
                                </form>
                            </div>
                            <span>login</span>
                        </div>
                        <div id="register">
                            <div id="register-open">
                                <form>
                                    <input type="text" class="form-control" placeholder="Username">
                                    <input type="password" class="form-control" placeholder="Password">
                                    <input type="email" class="form-control" placeholder="Email">
                                    <select>
                                        <option>Student</option>
                                        <option>Parent</option>
                                        <option>School</option>
                                    </select>
                                </form>
                            </div>
                            <span>register</span>
                        </div>
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