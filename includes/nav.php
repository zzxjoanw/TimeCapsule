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
            <a href="register.php">register</a>
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