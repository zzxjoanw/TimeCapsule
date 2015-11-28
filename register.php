<?php
/**
 * Created by PhpStorm.
 * User: Laura 4
 * Date: 11/2/2015
 * Time: 8:48 PM
 */

    include("includes/db-functions.php");

    $firstName = $_POST['firstName'] || "";
    $firstName = $_POST['lastName'] || "";
    function isValid()
    {
        $flag = 1;
        if(!isset($_POST['firstName']) || ($_POST['firstName'] == ""))
        {
            $flag = 0;
        }

        if(!isset($_POST['lastName']) || ($_POST['lastName'] == ""))
        {
            $flag = 0;
        }

        if(!isset($_POST['password']) || ($_POST['password'] == ""))
        {
            $flag = 0;
        }

        if(!isset($_POST['role']) || ($_POST['role'] == ""))
        {
            $flag = 0;
        }

        if(!isset($_POST['schoolname']) || ($_POST['schoolname'] == ""))
        {
            $flag = 0;
        }

        return $flag;
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
    <? if(isValid() == 0) { ?>
        <div id="main">
            <form class="" action="register.php" method="post">
                <div class="form-group">
                    <label for="firstName">First Name</label>
                    <input type="text" name="firstName" id="firstName" value="<? echo $firstName ?>">
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name</label>
                    <input type="text" name="lastName" id="lastName" value="<? echo $lastName ?>">
                </div>
                <div class="form-group">
                    <label for="password">Password</label><input type="password" name="password" id="password">
                </div>
                <div class="form-group">
                    <label for="email">Email</label><input type="text" name="email" id="email">
                </div>
                <label for="role">Role</label>
                <select class="form-control" name="role" id="role">
                    <option>Student</option>
                    <option>Parent</option>
                    <option>School</option>
                </select>
                <label for="country">Country</label>
                <select class="form-control" name="country" id="country">
                    <option>England</option>
                    <option>Northern Ireland</option>
                    <option>Scotland</option>
                    <option>Wales</option>
                </select>
                <div class="form-group">
                    <label for="schoolname">School Name</label>
                    <input type="text" name="schoolname" id="schoolname">
                </div>
                <button class="btn btn-submit">Register</button>
            </form>
        </div>
    <? }
        else
        {
            $firstname = htmlspecialchars($_POST['firstName']);
            $lastname = htmlspecialchars($_POST['lastName']);
            $email = htmlspecialchars($_POST['email']);
            $password = htmlspecialchars($_POST['password']);
            $school = htmlspecialchars($_POST['school']);

            $connection = doConnect();

            $sql = "INSERT INTO studentTable(firstname,lastname,email,password) VALUES(?,?,?,?)";
            insertStudent($sql,$connection,$firstname,$lastname,$email,$password);
        }
    ?>
</body>
</html>