<!DOCTYPE html>
<html>
    <head>
        <link href="css/style.css" rel="stylesheet" type="text/css"></link>
        <title><?php echo $info['title']." | ".$info['link']->title; ?></title>
        <base href="<?= $info['base']; ?>">
        <link href="views/osFighterBrown/css/style.css" rel="stylesheet" />
        <link href="views/FrenzoTheme/js/tabs/template5/tabcontent.css" rel="stylesheet" />
        <script src="files/js/jquery.min.js"></script>
        <script src="files/js/jquery-ui.min.js"></script>
        <script src="views/FrenzoTheme/js/tabs/tabcontent.js"></script>
        <script src="files/js/functions.js"></script>
    </head>
    <body>
        
        <div class="container">
            <div class="header">
                <div class="logo">
                    <img src="views/osFighterBrown/images/logo.png" alt="StarOGame">
                </div>
                <div class="infoBox">
                    <div class="left">
                        <div class="item">
                            <div class="itemImg"><img src="views/osFighterBrown/images/userIcon.png" alt="name"></div>
                            <div class="itemText"><?=$user->info->username; ?></div>
                        </div>
                        <div class="item">
                            <div class="itemImg"><img src="views/osFighterBrown/images/medalIcon.png" alt="rank"></div>
                            <div class="itemText"><?=$info['ranks'][$user->stats->rank]; ?></div>
                        </div>
                        <div class="item">
                            <div class="itemImg"><img src="views/osFighterBrown/images/groupIcon.png" alt="rank"></div>
                            <div class="itemText"><?=$info['cities'][$user->stats->city]; ?></div>
                        </div>
                    </div>
                    
                    <div class="left">
                        <div class="item">
                            <div class="itemImg"><img src="views/osFighterBrown/images/emailIcon.png" alt="Inbox"></div>
                            <div class="itemText">Inbox: <b>3</b> nieuwe berichten</div>
                        </div>
                        <div class="item">
                            <div class="itemImg"><img src="views/osFighterBrown/images/heartIcon.png" alt="health"></div>
                            <div class="itemText"><?=$user->stats->health; ?>%</div>
                        </div>
                        <div class="item">
                            <div class="itemImg"><img src="views/osFighterBrown/images/awardIcon.png" alt="respect"></div>
                            <div class="itemText">Respect: 1337</div>
                        </div>
                    </div>
                    
                    <div class="left">
                        <div class="item">
                            <div class="itemImg"><img src="views/osFighterBrown/images/bombIcon.png" alt="attackpower"></div>
                            <div class="itemText">Attackpower: 3976</div>
                        </div> 
                        <div class="item">
                            <div class="itemImg"><img src="views/osFighterBrown/images/shieldIcon.png" alt="defendpower"></div>
                            <div class="itemText">Defensepower: 4976</div>
                        </div> 
                    </div>
                </div>
            </div>
            <div class="navigation">
                <ul>
                    <li><a href="#">Index</a></li>
                    <li><a href="#">Nieuws</a></li>
                    <li><a href="#">Verhaal</a></li>
                    <li><a href="#">Hall of fame</a></li>
                    <li><a href="#">Top 100</a></li>
                    <li><a href="#">Forum</a></li>
                    <li><a href="#">Leden</a></li>
                    <li><a href="#">Callcredits</a></li>
                </ul>
            </div>
            <div class="content">
                <div class="left">
                    <div class="menu">
                        <div class="menuHeader">
                            <p>Personal</p>
                        </div>
                        <ul>
                            <?php
                            if (isset($info['menu']['personal'])) {
                                foreach($info['menu']['personal'] as $menu) {
                                    echo '<li><a href="'.$menu['link'].'">'.$menu['title'].'</a></li>';
                                }
                            }
                            ?>
                            <li style="border: 0;"><a href="core/process.php">Log out</a></li>
                        </ul>
                    </div>
                    
                    <div class="menu">
                        <div class="menuHeader">
                            <p>Call Credits</p>
                        </div>
                        <ul>
                            <?php
                            if (isset($info['menu']['call-credits'])) {
                                foreach($info['menu']['call-credits'] as $menu) {
                                    echo '<li><a href="'.$menu['link'].'">'.$menu['title'].'</a></li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                    
                    <div class="menu">
                        <div class="menuHeader">
                            <p>Family</p>
                        </div>
                        <ul>
                            <?php
                            if (isset($info['menu']['family'])) {
                                foreach($info['menu']['family'] as $menu) {
                                    echo '<li><a href="'.$menu['link'].'">'.$menu['title'].'</a></li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>

                    <div class="menu">
                        <div class="menuHeader">
                            <p>Extra</p>
                        </div>
                        <ul>
                            <?php
                            if (isset($info['menu']['extra'])) {
                                foreach($info['menu']['extra'] as $menu) {
                                    echo '<li><a href="'.$menu['link'].'">'.$menu['title'].'</a></li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>

                </div>
                
                <div class="left">
                    <div class="box">
                        <div class="boxHeader">
                            <p><?=$info['link']->title; ?></p>
                        </div>
                        <div class="boxContent">
                            <?php include 'files/ingame/'.$info['link']->menu.'/'.$info['link']->file; ?>
                       </div>
                    </div>
                </div>
                
                <div class="right">
                    <div class="menu">
                        <div class="menuHeader">
                            <p>Crime</p>
                        </div>
                        <ul>
                            <?php
                            if (isset($info['menu']['crime'])) {
                                foreach($info['menu']['crime'] as $menu) {
                                    echo '<li><a href="'.$menu['link'].'">'.$menu['title'].'</a></li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="menu">
                        <div class="menuHeader">
                            <p>Locations</p>
                        </div>
                        <ul>
                            <?php
                            if (isset($info['menu']['locations'])) {
                                foreach($info['menu']['locations'] as $menu) {
                                    echo '<li><a href="'.$menu['link'].'">'.$menu['title'].'</a></li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="menu">
                        <div class="menuHeader">
                            <p>Casino</p>
                        </div>
                        <ul>
                            <?php
                            if (isset($info['menu']['casino'])) {
                                foreach($info['menu']['casino'] as $menu) {
                                    echo '<li><a href="'.$menu['link'].'">'.$menu['title'].'</a></li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="menu">
                        <div class="menuHeader">
                            <p>Statistics</p>
                        </div>
                        <ul>
                            <li><a href="#">Server time: <?php echo date("H:i:s"); ?></a></li>
                            <li><a href="#">Gangsters: <?php echo $database->getNumMembers(); ?></a></li>
                            <li><a href="#">Ziekenhuis</a></li>
                            <li><a href="#">Online: <?php echo $database->num_active_users; ?></a></li>
                            <li style="border: 0;"><a href="#">Meer statistieken</a></li>
                        </ul>
                    </div>

                    <?php if ($session->isAdmin()) { ?>
                    <div class="menu">
                        <div class="menuHeader">
                            <p>Admin</p>
                        </div>
                        <ul>
                            <?php
                            if (isset($info['menu']['admin'])) {
                                foreach($info['menu']['admin'] as $menu) {
                                    echo '<li><a href="'.$menu['link'].'">'.$menu['title'].'</a></li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <div class="footer">
                <?php $config = $database->getConfigs();
                $version = $config['Version']; ?>
                <p>osFighter V<?=$version; ?></p>
            </div>
        </div>
      
    </body>
</html>
