<!-- login modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Login</h4>
            </div>
            <form action="main.php" method="post">
                <div class="modal-body">
                    <input type="text" class="form-control" name="email" placeholder="Email">
                    <input type="password" class="form-control" name="password" placeholder="Password">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="bttnLogin">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- registration modal -->
<div class="modal fade" id="regModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <form  action="../main.php" method="post">
                <div class="modal-body">
                    <input type="text" class="form-control" name="email" placeholder="Email">
                    <input type="password" class="form-control" name="password" placeholder="Password">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="bttnReg">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="nav">
    <div id="logo">Time Capsule</div>
    <div id="navButtons">
            <?
            if(!isset($_SESSION))
            {
                ?>
                <a href="#" id="loginBttn">
                    <svg height="100%" viewBox="0 0 50 50" id="loginSVG">
                        <circle cx="25" cy="25" r="25" stroke="none" fill="blue" class="background" />
                        <polyline points="15,10, 35,10, 35,40, 15,40" style="stroke:white; stroke-width:4; fill:none" class="door"/>
                        <g id="arrow">
                            <line x1="25" y1="25" x2="10" y2="25" style="stroke:white; stroke-width:4" class="arrowSpine"/>
                            <polyline points="15,20,10,25,15,30" style="stroke:white; stroke-width:4; fill:none" class="arrowPoint" />
                        </g>
                    </svg>
                </a>
                <!--<a href="#" id="registerBttn">-->
                <a href="register.php">
                    <svg height="100%" viewBox="0 0 50 50" id="loginSVG">
                        <circle cx="25" cy="25" r="25" stroke="none" fill="blue" class="background" />
                        <line x1="10" x2="40" y1="25" y2="25" style="stroke:white; stroke-width:4" />
                        <line x1="25" x2="25" y1="10" y2="40" style="stroke:white; stroke-width:4" />
                    </svg>
                </a>
            <?
        }
        else
        {
            ?>
            <a href="profile.php"><img src="images/profile.png" alt="Profile"></a>
            <a href="logout.php"><img src="images/logout.png" alt="Logout"></a>
            <?
        }
        ?>
     </div>
</div>