<div id="nav">
    <div id="logo">logo</div>
    <?
    if(!isset($_SESSION))
    {
        ?>
        <div id="login">
            <div id="login-open">
                <form action="main.php" method="post">
                    <input type="text" class="form-control" name="email" placeholder="Email">
                    <input type="password" class="form-control" name="password" placeholder="Password">
                    <button type="submit" class="btn btn-submit" name="bttnLogin">Submit</button>
                </form>
            </div>
            <span>login</span>
        </div>
        <div id="register">
            <a href="register.php">register</a>
        </div>
        <?
    }
    else
    {
        echo "logged in. <a href='profile.php'>go to profile</a> or <a href='logout.php'>log out</a>";
    }
    ?>
</div>