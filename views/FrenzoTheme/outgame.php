<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $info['title']." | ".$info['link']->title; ?></title>
        <link href="themes/FrenzoTheme/css/style-outgame.css" rel="stylesheet" />
        <link href="themes/FrenzoTheme/js/themes/8/js-image-slider.css" rel="stylesheet"/>
        <script src="themes/FrenzoTheme/js/themes/8/js-image-slider.js"></script>
        <link href="themes/FrenzoTheme/css/slider/generic.css" rel="stylesheet" />
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
                            <a href="http://www.osFighter.com" target="_blank">
                                <img src="themes/FrenzoTheme/images/layout/outgame/slider/slider1.png" alt="Welcome to osFighter" />
                            </a>
                            <img src="themes/FrenzoTheme/images/layout/outgame/slider/image-slider-2.jpg" alt="Lorem ipsum dolor sit amet" />
                            <img src="themes/FrenzoTheme/images/layout/outgame/slider/image-slider-3.jpg" alt="Pure Javascript. No jQuery. No flash." />
                            <img src="themes/FrenzoTheme/images/layout/outgame/slider/image-slider-4.jpg" alt="#cap2" />
                            <img src="themes/FrenzoTheme/images/layout/outgame/slider/image-slider-5.jpg" alt="Excepteur sint occaecat cupidatat" />
                        </div>
                    </div>
                </div>

                <div id="content">
                    <div id="inhoud">
                        <h1><?php echo $info['link']->title; ?></h1>
                        <?php include 'files/outgame/'.$info['link']->file; ?>
                    </div>
                </div>

                <ul id="menu">
                    <li><a href="home">Startpagina</a></li>
                    <li><a href="register">Aanmelden</a></li>
                    <li><a href="forgot-pass">Wachtwoord vergeten</a></li>
                    <li><a href="#">Algemene voorwaarden</a></li>
                </ul>

                <div id="login">
                    <form action="core/process.php" method="POST">
                        <input type="text" name="user" maxlength="30" value="<?php echo $form->value("user"); ?>">
                        <input type="password" name="pass" maxlength="30" value="<?php echo $form->value("pass"); ?>">
                        <input type="hidden" name="sublogin" value="1">
                        <input type="submit" value="Login">
                    </form>
                </div>

                <img src="themes/FrenzoTheme/images/layout/outgame/aanmelden.png" alt="Register" id="aanmelden" />
            </div>
        </div>
        <div id="footer">
            &copy; Copyrights osFighter | All rights reserved | Theme by FrenzoBrouwer
        </div>
    </body>
</html>
