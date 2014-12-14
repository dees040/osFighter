<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $info['title']." | ".$info['link']->title; ?></title>
        <base href="<?= $info['base']; ?>">
        <link href="views/FrenzoTheme/css/ingame.css" rel="stylesheet" />
        <link href="views/FrenzoTheme/js/tabs/template5/tabcontent.css" rel="stylesheet" />
        <link rel="icon" type="image/png" href="files/images/favicon.png">
    </head>
    <body>
        <div id="header">
            <div id="logo"><img src="views/FrenzoTheme/images/layout/logo-new.png"/></div>
            <div id="status">
                <table>
                    <tr>
                        <td width="35%" class="first">Health</td>
                        <td>
                            <div class="bar">
                                <div class="bar" style="width: <?=$user->stats->health; ?>%; background: #dd5252; color: #3c2a23;">
                                    <?=$user->stats->health; ?>%
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="first">Process</td>
                        <td>
                            <div class="bar">
                                <div class="bar" style="width: <?=$user->stats->rank_process; ?>%; background: #0097a9; color: #3c2a23;">
                                    <?=$user->stats->rank_process; ?>%
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="first">Rank</td>
                        <td><a href="statistics/ranks" style="color: white;"><?=$info['ranks'][$user->stats->rank]; ?></a></td>
                    </tr>

                    <tr>
                        <td class="first">Country</td>
                        <td><?=$info['cities'][$user->stats->city]; ?></td>
                    </tr>

                    <tr>
                        <td class="first">Money</td>
                        <td><?=$info['currency'].$settings->createFormat($user->stats->money); ?></td>
                    </tr>

                    <tr>
                        <td class="first">Bank</td>
                        <td><?=$info['currency'].$settings->createFormat($user->stats->bank); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div id="faux">

            <div class="menus" style="float: left;">
                <ul class="menu">
                    <h1>Personal</h1> <img src="views/FrenzoTheme/images/icons/1391648113_administrator.png" class="menu-icon" />

                    <?php
                    if (isset($info['menu']['personal'])) {
                        foreach($info['menu']['personal'] as $menu) {
                            echo '<li><a href="'.$menu['link'].'">'.$menu['title'];
                            if ($menu['title'] == "Messages") {
                                echo $user->getUnreadInbox();
                            }
                            echo '</a></li>';
                        }
                    }
                    ?>
                    <li style="border: 0;"><a href="core/process.php">Log out</a></li>
                </ul>

                <ul class="menu">
                    <h1>Call Credits</h1> <img src="views/FrenzoTheme/images/icons/1391731203_bookmark_toolbar.png" class="menu-icon" />

                    <?php
                    if (isset($info['menu']['call-credits'])) {
                        foreach($info['menu']['call-credits'] as $menu) {
                            echo '<li><a href="'.$menu['link'].'">'.$menu['title'].'</a></li>';
                        }
                    }
                    ?>
                </ul>

                <ul class="menu">
                    <h1>Family</h1> <img src="views/FrenzoTheme/images/icons/1391731263_family.png" class="menu-icon" />

                    <?php
                    if (isset($info['menu']['family'])) {
                        foreach($info['menu']['family'] as $menu) {
                            echo '<li><a href="'.$menu['link'].'">'.$menu['title'].'</a></li>';
                        }
                    }
                    ?>
                </ul>

                <ul class="menu">
                    <h1>Extra</h1> <img src="views/FrenzoTheme/images/icons/1391731349_search.png" class="menu-icon" />

                    <?php
                    if (isset($info['menu']['extra'])) {
                        foreach($info['menu']['extra'] as $menu) {
                            echo '<li><a href="'.$menu['link'].'">'.$menu['title'];
                            if (strtolower($menu['title']) == "shoutbox") {
                                echo $user->newShoutBoxMessage();
                            }
                            echo '</a></li>';
                        }
                    }
                    ?>
                </ul>
            </div>

            <div id="content">
                <div class="content-titel"><?php echo $info['link']->title; ?></div>
                    <div class="content-inhoud" class="inhoud">
                        <?php include 'files/ingame/'.$info['link']->menu.'/'.$info['link']->file; ?>
                    </div>
                <div class="content-footer"></div>


            </div>

            <div class="menus right" style="float: right;">
                <ul class="menu">
                    <h1>Crime</h1> <img src="views/FrenzoTheme/images/icons/1391731384_Police_officer.png" class="menu-icon" />

                    <?php
                    if (isset($info['menu']['crime'])) {
                        foreach($info['menu']['crime'] as $menu) {
                            echo '<li><a href="'.$menu['link'].'">'.$menu['title'].'</a></li>';
                        }
                    }
                    ?>
                </ul>

                <ul class="menu">
                    <h1>Locations</h1> <img src="views/FrenzoTheme/images/icons/1391731369_push_pin.png" class="menu-icon" />

                    <?php
                    if (isset($info['menu']['locations'])) {
                        foreach($info['menu']['locations'] as $menu) {
                            echo '<li><a href="'.$menu['link'].'">'.$menu['title'].'</a></li>';
                        }
                    }
                    ?>
                </ul>

                <ul class="menu">
                    <h1>Casino</h1> <img src="views/FrenzoTheme/images/icons/1391731460_Game-casino.png" class="menu-icon" />

                    <?php
                    if (isset($info['menu']['casino'])) {
                        foreach($info['menu']['casino'] as $menu) {
                            echo '<li><a href="'.$menu['link'].'">'.$menu['title'].'</a></li>';
                        }
                    }
                    ?>
                </ul>

                <ul class="menu">
                    <h1>Statistics</h1> <img src="views/FrenzoTheme/images/icons/1391731439_web-space-px-png.png" class="menu-icon" />

                    <li><a href="">Server time: <?php echo date("H:i:s"); ?></a></li>
                    <li><a href="statistics/members">Gangsters: <?php echo $database->getNumMembers(); ?></a></li>
                    <li><a href="online">Online: <?php echo $database->num_active_users; ?></a></li>
                    <li style="border: 0;"><a href="statistics/more">More statistic's</a></li>
                </ul>

                <?php if ($session->isAdmin()) { ?>
                <ul class="menu">
                    <h1>Admin</h1> <img src="views/FrenzoTheme/images/icons/1391648113_administrator.png" class="menu-icon" />

                    <?php
                    if (isset($info['menu']['admin'])) {
                        foreach($info['menu']['admin'] as $menu) {
                            echo '<li><a href="'.$menu['link'].'">'.$menu['title'].'</a></li>';
                        }
                    }
                    ?>
                </ul>
                <?php } ?>
            </div>

        </div>

        <div id="footer">

        </div>
        <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
        <script src="//code.jquery.com/ui/1.11.2/jquery-ui.min.js"></script>
        <script src="views/FrenzoTheme/js/tabs/tabcontent.js"></script>
        <script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>
        <script src="files/js/functions.js"></script>
    </body>
</html>