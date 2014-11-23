<ul class="tabs" id="tab"  data-persist="true">
    <li><a href="#tab1">Main Settings</a></li>
    <li><a href="#tab2">Ranks</a></li>
    <li><a href="#tab3">City's</a></li>
    <li><a href="#tab4">Themes</a></li>
</ul>

<div class="tabcontents inhoud">
    <!-- settings page tab1: Main Settings-->
    <div id="tab1">
    <?php
        if (isset($_POST['update-settings-form'])) {
            global $admin;
            array_pop($_POST);
            $retval = $admin->saveSettings($_POST);

            if ($retval) {
                echo "<strong>Settings saved!</strong>";
            } else {
                foreach($admin->errorArray as $item) echo $item."<br>";
            }
        }

        $config = $database->getConfigs();
    ?>
        <form action="core/process.php" method="POST">
            <fieldset>
                <legend>Site Settings</legend>
                <dl>
                    <dt><label for="sitename">Site Name:</label></dt>
                    <dd><input id="sitename" type="text" size="40" maxlength="255" name="sitename" value="<?php echo $config['SITE_NAME']; ?>" /><br><?php echo $form->error("sitename"); ?></dd>
                    <dt><label for="sitedesc">Site Description:</label></dt>
                    <dd><input id="sitedesc" type="text" size="40" maxlength="255" name="sitedesc" value="<?php echo $config['SITE_DESC']; ?>" /><br><?php echo $form->error("sitedesc"); ?></dd>
                    <dt><label for="emailfromname">E-mail From Name:</label><br><span>The From Field of any outgoing e-mails.</span></dt>
                    <dd><input id="emailfromname" type="text" size="40" maxlength="255" name="emailfromname" value="<?php echo $config['EMAIL_FROM_NAME']; ?>" /><br><?php echo $form->error("emailfromname"); ?></dd>
                    <dt><label for="adminemail">Site/Admin E-mail Address:</label><br><span>The outgoing e-mail address.</span></dt>
                    <dd><input id="adminemail" type="text" size="40" maxlength="255" name="adminemail" value="<?php echo $config['EMAIL_FROM_ADDR']; ?>" /><br><?php echo $form->error("adminemail"); ?></dd>
                    <dt><label for="webroot">Site Root:</label><br><span>Please include full URL and sub folder (with trailing forward-slash) eg, http://www.mysite.com/login/</span></dt>
                    <dd><input id="webroot" type="text" size="40" maxlength="255" name="webroot" value="<?php echo $config['WEB_ROOT']; ?>" /><br><?php echo $form->error("webroot"); ?></dd>
                    <dt><label for="home_page">Home Page:</label><br><span>Eg, index.php - This page among other things will be the page users are redirected to after logging off.</span></dt>
                    <dd><input id="home_page" type="text" size="40" maxlength="255" name="home_page" value="<?php echo $config['home_page']; ?>" /><br><?php echo $form->error("home_page"); ?></dd>
                    <dt><label for="currency">Currency:</label><br><span>The currency of the site, default is &#36; Dollar.</span></dt>
                    <dd>
                        <label><input name="currency" value="1" class="radio" type="radio" <?php if($config['CURRENCY'] == "&#36;") { echo "checked='checked'"; } ?>> &#36; Dollar</label><br>
                        <label><input name="currency" value="2" class="radio" type="radio" <?php if($config['CURRENCY'] == "&#128;") { echo "checked='checked'"; } ?>> &#128; Euro</label><br>
                        <label><input name="currency" value="3" class="radio" type="radio" <?php if($config['CURRENCY'] == "&#165;") { echo "checked='checked'"; } ?>> &#165; Yen</label><br>
                        <label><input name="currency" value="4" class="radio" type="radio" <?php if($config['CURRENCY'] == "&#163;") { echo "checked='checked'"; } ?>> &#163; Pound</label><br>
                    </dd>
                    <dt><label for="number_format">Number format:</label><br><span>Define the way numbers display. eg, for English format choose option 2 for ',' as thousand seperator.</span></dt>
                    <dd>
                        <label><input name="number_format" value="1" class="radio" type="radio" <?php if($config['NUMBER_FORMAT'] == "1") { echo "checked='checked'"; } ?>> <?=$config['CURRENCY'];?>1.000.000</label>
                        <label><input name="number_format" value="2" class="radio" type="radio" <?php if($config['NUMBER_FORMAT'] == "2") { echo "checked='checked'"; } ?>> <?=$config['CURRENCY'];?>1,000,000</label>
                    </dd>
                </dl>
            </fieldset>

            <fieldset>
                <legend>Registration Settings</legend>
                <dl>
                    <dt><label for="accountactivation">Account Activation:</label><br /><span>This determines whether users have immediate access to the board or if confirmation is required. You can also completely disable new registrations. "Board-wide e-mail" must be enabled in order to use user or admin activation.</span></dt>
                    <dd>
                        <label><input name="activation" value="4" class="radio" type="radio" <?php if($config['ACCOUNT_ACTIVATION'] == 4) { echo "checked='checked'"; } ?>> Disable registration</label><br>
                        <label><input name="activation" value="1" class="radio" type="radio" <?php if($config['ACCOUNT_ACTIVATION'] == 1) { echo "checked='checked'"; } ?>> No activation (immediate access)</label><br>
                    <label><input name="activation" value="2" class="radio" type="radio" <?php if($config['ACCOUNT_ACTIVATION'] == 2) { echo "checked='checked'"; } ?>> By user (e-mail verification)</label><br>
                        <label><input name="activation" value="3" class="radio" type="radio" <?php if($config['ACCOUNT_ACTIVATION'] == 3) { echo "checked='checked'"; } ?>> By admin</label></dd>
                </dl>

                <dl>
                    <dt><label for="min_name_chars">Username length:</label><br><span>Minimum and maximum number of characters in usernames.</span></dt>
                    <dd><input name="min_user_chars" type="text" size="3" maxlength="5" value="<?php echo $config['min_user_chars']; ?>" /> Min&nbsp;&nbsp;<input type="text" size="3" maxlength="5" name="max_user_chars" value="<?php echo $config['max_user_chars']; ?>" /> Max<br /><?php echo $form->error("max_user_chars"); ?><?php echo $form->error("min_user_chars"); ?></dd>
                </dl>

                <dl>
                    <dt><label for="min_pass_chars">Password length:</label><br><span>Minimum and maximum number of characters in passwords.</span></dt>
                    <dd><input id="min_pass_chars" size="3" maxlength="3" name="min_pass_chars" value="<?php echo $config['min_pass_chars']; ?>" type="text"> Min&nbsp;&nbsp;<input id="max_pass_chars" size="3" maxlength="3" name="max_pass_chars" value="<?php echo $config['max_pass_chars']; ?>" type="text"> Max<br /><?php echo $form->error("max_pass_chars"); ?><?php echo $form->error("min_pass_chars"); ?></dd>
                </dl>

                <dl>
                    <dt><label for="send_welcome">Send Welcome E-mail:</label><br><span>Whether or not to send a welcome e-mail to newly registered users.</span></dt>
                    <dd><select name="send_welcome" id="send_welcome"><option value="1" <?php if ($config['EMAIL_WELCOME'] == 1) { echo "selected='selected'"; }?>>Yes</option><option value="0" <?php if ($config['EMAIL_WELCOME'] == 0) { echo "selected='selected'"; }?>>No</option></select></dd>
                </dl>

                <dl>
                    <dt><label for="enable_capthca">Enable Captcha:</label><br><span>Enables or disables captcha system at registration.</span></dt>
                    <dd><select name="enable_capthca" id="enable_capthca"><option value="1" <?php if ($config['ENABLE_CAPTCHA'] == 1) { echo "selected='selected'"; }?>>Yes</option><option value="0" <?php if ($config['ENABLE_CAPTCHA'] == 0) { echo "selected='selected'"; }?>>No</option></select></dd>
                </dl>

                <dl>
                    <dt><label for="all_lowercase">Username Lowercase:</label><br><span>Makes submitted username all lowercase.</span></dt>
                    <dd><select name="all_lowercase" id="all_lowercase"><option value="1" <?php if ($config['ALL_LOWERCASE'] == 1) { echo "selected='selected'"; }?>>Yes</option><option value="0" <?php if ($config['ALL_LOWERCASE'] == 0) { echo "selected='selected'"; }?>>No</option></select></dd>
                </dl>

            </fieldset>

            <fieldset>
                <legend>Session Settings</legend>
                <dl>
                    <dt><label for="user_timeout">User Inactivity Timeout:</label><br><span>Amount of inactvity user is allowed in minutes before he/she is logged off. Also period of time before user is no longer considered an active or online user.</span></dt>
                    <dd><input id="user_timeout" type="text" size="5" maxlength="10" name="user_timeout" value="<?php echo $config['USER_TIMEOUT']; ?>" /><br><?php echo $form->error("user_timeout"); ?></dd>
                </dl>

                <dl>
                    <dt><label for="guest_timeout">Guest Timeout:</label><br><span>Timeout for Active Guests.</span></dt>
                    <dd><input id="guest_timeout" type="text" size="5" maxlength="10" name="guest_timeout" value="<?php echo $config['GUEST_TIMEOUT']; ?>" /><br><?php echo $form->error("guest_timeout"); ?></dd>
                </dl>

                <dl>
                    <dt><label for="cookie_expiry">Cookie Expiry:</label><br><span>Amount of time the 'remember me' cookie remains active on a visitor's machine in days.</span></dt>
                    <dd><input id="cookie_expiry" type="text" size="5" maxlength="10" name="cookie_expiry" value="<?php echo $config['COOKIE_EXPIRE']; ?>" /><br><?php echo $form->error("cookie_expiry"); ?></dd>
                </dl>

                <dl>
                    <dt><label for="min_name_chars">Cookie path:</label><br><span>Use '/' to include the whole domain. Best to leave it as that if not sure.</span></dt>
                    <dd><input id="cookie_path" type="text" size="5" maxlength="50" name="cookie_path" value="<?php echo $config['COOKIE_PATH']; ?>" /><br><?php echo $form->error("cookie_path"); ?></dd>
                </dl>
            </fieldset>

            <fieldset>
                <legend>Submit changes</legend>
                <p class="submit-buttons">
                    <input class="button1" type="submit" id="submit" name="submit" value="Submit" />&nbsp;
                    <input class="button2" type="reset" id="reset" name="reset" value="Reset" />
                </p>
            </fieldset>
            <input type="hidden" name="configedit" value="1">
        </form>
    </div>

    <!-- settings page tab2: Rank Settings-->
    <div id="tab2">
        <?php
            if (isset($_POST['save-ranks-form'])) {
                array_pop($_POST);

                $admin->saveRanksCities($_POST, 'RANKS');
            }

            $ranks = $database->getRanks();
        ?>
        Leave empty if rank needs to be deleted!
        <form method="post">
            <table>
                <?php
                    foreach($ranks as $key => $rank) {
                        echo '<tr>';
                        echo '<td>'.$key.': </td>';
                        echo '<td><input type="text" name="'.$key.'" value="'.$rank.'"></td>';
                        echo '</tr>';
                    }
                ?>
                <tr>
                    <td>
                        <?=count($ranks) ;?>:
                    </td>
                    <td>
                        <input type="text" placeholder="New rank" name="new-rank">
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" value="Save ranks!" name="save-ranks-form"></td>
                </tr>
            </table>
        </form>
    </div>

    <!-- settings page tab3: City's Settings-->
    <div id="tab3">
        <?php
        if (isset($_POST['save-cities-form'])) {
            array_pop($_POST);

            $admin->saveRanksCities($_POST, 'CITIES');
        }

        $configs = $database->getConfigs();
        ?>
        Leave empty if city needs to be deleted!
        <form method="post">
            <table>
                <?php
                foreach(unserialize($configs['CITIES']) as $key => $city) {
                    echo '<tr>';
                    echo '<td>'.$key.': </td>';
                    echo '<td><input type="text" name="'.$key.'" value="'.$city.'"></td>';
                    echo '</tr>';
                }
                ?>
                <tr>
                    <td>
                        <?=count(unserialize($configs['CITIES'])) ;?>:
                    </td>
                    <td>
                        <input type="text" placeholder="New city" name="new-city">
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" value="Save cities!" name="save-cities-form"></td>
                </tr>
            </table>
        </form>
    </div>

    <!-- settings page tab4: Themes Settings-->
    <div id="tab4">
        <?php
            $possibleThemes = scandir("views");
            $themes = array();

            foreach($possibleThemes as $theme) {
                if ($theme == "." || $theme == "..") continue;
                if (!is_dir("views/".$theme)) continue;

                $themes[] = $theme;
            }

            if (isset($_POST['theme_submit'])) {
                $newTheme = $_POST['theme'];
                if (in_array($newTheme, $themes)) {
                    $database->updateConfigs($newTheme, "ACTIVE_THEME");
                    header("Location: admin/settings");
                } else {
                    echo "This view does not exists.";
                }
            }
        ?>

        <form method="post">
            <table>
                <?php
                    foreach($themes as $theme) {
                    $themeInfo = scandir("views/".$theme);
                    if (!in_array("ingame.php", $themeInfo) || !in_array("outgame.php", $themeInfo)) continue;
                ?>
                    <tr>
                        <td>
                            <input type="radio" name="theme" value="<?=$theme; ?>" <?=($info['theme'] == $theme) ? 'checked="checked"' : '' ?>>
                        </td>
                        <td>
                            <?php
                                if (in_array("screenshot.png", $themeInfo)) {
                                    echo "<img src='views/".$theme."/screenshot.png' width='100%'>";
                                } else {
                                    echo "<img src='views/no-preview.png' width='100%'>";
                                }
                            ?>
                        </td>
                        <td>
                            <?=$theme;?>
                        </td>
                    </tr>
                <?php
                    }
                ?>
                <tr>
                    <td colspan="3">
                        <input type="submit" value="Save!" name="theme_submit">
                    </td>
                </tr>
            </table>
        </form>

    </div>

</div>