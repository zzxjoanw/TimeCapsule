<?php
/**
 * Created by PhpStorm.
 * User: Laura 4
 * Date: 11/2/2015
 * Time: 8:48 PM
 */

    include("includes/db-functions.php");
    $connection = openDBConnection();

    $errorFirstNameDisplay = "none";
    $errorLastNameDisplay = "none";
    $errorPasswordDisplay = "none";
    $errorEmailDisplay = "none";
    $errorConfirmPasswordDisplay = "none";
    $errorPasswordMatchDisplay = "none";

    if(isset($_POST['bttnSubmit']))
    {
        $errorFree = 1;

        if($_POST['firstName'] == "")
        {
            $errorFirstNameDisplay = "inline-block";
            $errorFree = 0;
        }

        if($_POST['lastName'] == "")
        {
            $errorLastNameDisplay = "inline-block";
            $errorFree = 0;
        }

        if($_POST['password'] == "")
        {
            $errorPasswordDisplay = "inline-block";
            $errorFree = 0;
        }

        if($_POST['confirmPassword'] == "")
        {
            $errorConfirmPasswordDisplay = "inline-block";
            $errorFree = 0;
        }

        if($_POST['password'] != $_POST['confirmPassword'])
        {
            $errorPasswordMatchDisplay = "inline-block";
            $errorFree = 0;
        }

        if($_POST['email'] == "")
        {
            $errorEmailDisplay = "inline-block";
            $errorFree = 0;
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
</head>
<body>
    <?
        include('includes/nav.php');
        if($errorFree == 1)
        {
            //include("includes/nav.php");
            $firstname = htmlspecialchars($_POST['firstName']);
            $lastname = htmlspecialchars($_POST['lastName']);
            $email = htmlspecialchars($_POST['email']);
            //$password = password_hash(htmlspecialchars($_POST['password']),PASSWORD_DEFAULT);
            $password = htmlspecialchars($_POST['password']);

            //move to separate file
            insertStudent($connection, $firstname, $lastname, $email, $password);

            $connection->close();
        }
        else
        {
            ?>
            <div id="main">
                <form class="" action="register.php" method="post">
                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <input type="text" name="firstName" id="firstName" value="<? echo $_POST['firstName']; ?>">
                        <span class="error" id="errorFirstName" style="display:<? echo $errorFirstNameDisplay; ?>">
                            Type your first name.</span>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <input type="text" name="lastName" id="lastName" value="<? echo $_POST['lastName']; ?>">
                        <span class="error" id="errorLastName" style="display:<? echo $errorLastNameDisplay; ?>">
                            Type your last name.</span>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" value="<? echo $_POST['password']; ?>">
                        <span class="error" id="errorPassword" style="display:<? echo $errorPasswordDisplay; ?>">
                            Type your password.</span>
                        <span class="error" id="errorPasswordMatch" style="display:<? echo $errorPasswordMatchDisplay; ?>">
                            Passwords do not match</span>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Confirm Password</label>
                        <input type="password" name="confirmPassword" id="confirmPassword">
                        <span class="error" id="errorConfirmPassword" style="display:<? echo $errorConfirmPasswordDisplay; ?>">
                            Type your password again.</span>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email">
                        <span class="error" id="errorEmail" style="display:<? echo $errorEmailDisplay; ?>">
                            Type your email.</span>
                    </div>
                    <div class="form-group">
                        <label for="age">Age Range</label>
                        <input type
                    </div>
                    <button class="btn btn-submit" name="bttnSubmit">Register</button>
                </form>
            </div>
            <?
        }
    ?>
</body>
</html>