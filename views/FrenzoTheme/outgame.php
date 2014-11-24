<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $info['title']." | ".$info['link']->title; ?></title>
        <link href="views/FrenzoTheme/css/style-outgame.css" rel="stylesheet" />
        <link href="views/FrenzoTheme/js/themes/8/js-image-slider.css" rel="stylesheet"/>
        <script src="views/FrenzoTheme/js/themes/8/js-image-slider.js"></script>
        <link href="views/FrenzoTheme/css/slider/generic.css" rel="stylesheet" />
        <script>
            imageSlider.thumbnailPreview(function (thumbIndex) { return "<img src='images/thumb" + (thumbIndex + 1) + ".jpg' style='width:70px;height:44px;' />"; });
        </script>
    </head>
    <body>
        <div id="frame">
            <div id="frame-bg">
                <div id="header">
                    <div id="logo"></div>

                    <div id="sliderFrame">
                        <div id="slider">
                            <a href="http://www.ultimate-survival.net/login">
                                <img src="views/FrenzoTheme/images/layout/outgame/slider/slider1.png" alt="Welcome to osFighter" />
                            </a>
                            <a href="https://github.com/dees040/osFighter" target="_blank">
                                <img src="views/FrenzoTheme/images/layout/outgame/slider/blog-github.png" alt="Follow our code on Github" />
                            </a>
                        </div>
                    </div>
                </div>

                <div id="content">
                    <div id="inhoud">
                        <?php include 'files/outgame/'.$info['link']->file; ?>
                    </div>
                </div>

                <ul id="menu">
                    <li><a href="home">Home</a></li>
                    <li><a href="register">Register</a></li>
                    <li><a href="forgot-pass">Forgot password</a></li>
                    <li><a href="#">Terms and Conditions</a></li>
                </ul>

                <div id="login">
                    <form action="core/process.php" method="POST">
                        <input type="text" name="user" maxlength="30" value="<?php echo $form->value("user"); ?>">
                        <input type="password" name="pass" maxlength="30" value="<?php echo $form->value("pass"); ?>">
                        <input type="hidden" name="sublogin" value="1">
                        <input type="submit" value="Login">
                    </form>
                </div>

                <img src="views/FrenzoTheme/images/layout/outgame/aanmelden.png" alt="Register" id="aanmelden" />
            </div>
        </div>
        <div id="footer">
            &copy; Copyrights osFighter | All rights reserved | Theme by FrenzoBrouwer
        </div>
    </body>
</html>
