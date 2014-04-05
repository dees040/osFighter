<!DOCTYPE html>
<html>
    <head>
        <title>osBanditi v3.0 | Door Frenzo</title>
        <link rel="stylesheet" href="themes/FrenzoTheme/css/style-outgame.css" type="text/css" />
        <link href="themes/FrenzoTheme/js/themes/8/js-image-slider.css" rel="stylesheet" type="text/css" />
        <script src="themes/FrenzoTheme/js/themes/8/js-image-slider.js" type="text/javascript"></script>
        <link href="themes/FrenzoTheme/css/slider/generic.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript">
            imageSlider.thumbnailPreview(function (thumbIndex) { return "<img src='images/thumb" + (thumbIndex + 1) + ".jpg' style='width:70px;height:44px;' />"; });
        </script>
    </head>
    <body>
    <div id="frame">
        <div id="frame-bg">
            <div id="header">
                <div id="logo"><a href="index.php"><img src="themes/FrenzoTheme/images/layout/logo.png" alt="" /></a></div>

                <div id="sliderFrame">
                    <div id="slider">
                        <a href="http://www.menucool.com/javascript-image-slider" target="_blank">
                            <img src="images/layout/outgame/slider/slider1.png" alt="#cap1" />
                        </a>
                        <img src="images/layout/outgame/slider/slider2.png" alt="Met een geheel nieuw design!" />
                        <img src="images/layout/outgame/slider/slider3.png" alt="Vernieuwde opties!!" />
                        <img src="images/layout/outgame/slider/image-slider-4.jpg" alt="#cap2" />
                        <img src="images/layout/outgame/slider/image-slider-5.jpg" alt="Excepteur sint occaecat cupidatat" />
                    </div>
                    <div style="display: none;">
                        <div id="cap1">
                            Welkom op osBanditi
                        </div>
                        <div id="cap2">
                            <em>HTML</em> caption. Link to <a href="http://www.google.com/">Google</a>.
                        </div>
                    </div>
                </div>
            </div>

            <div id="content">
                <div id="inhoud">
                    <?php include 'files/'.$info['link']->file; ?>
                </div>
            </div>

            <ul id="menu">
                <li><a href="index.php?p=register">Aanmelden</a></li>
                <li><a href="?p=wachtwoord">Wachtwoord vergeten</a></li>
                <li><a href="index.php?p=activation">Account activeren</a></li>
                <li><a href="#">Algemene voorwaarden</a></li>
            </ul>

            <div id="login">
                <form method="POST" action="index.php?p=login" >
                    <input type="text" name="username" class="input-outgame" placeholder="Gebruikersnaam" />
                    <input type="password" name="password" class="input-outgame" placeholder="wachtwoord" />
                    <input type="submit" class="submit-outgame" name="login" value="Inloggen" />
                </form>
            </div>

            <a href="index.php?p=register"><img src="images/layout/outgame/aanmelden.png" alt="" id="aanmelden" /></a>
        </div>
    </div>
    <div id="footer">
        &copy; Copyrights Frenzo Brouwer | Alle rechten voorbehouden!
    </div>

    </body>
</html>
