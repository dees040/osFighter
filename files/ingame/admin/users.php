<ul class="tabs" id="tab"  data-persist="true">
    <li><a href="#tab1">Stats</a></li>
    <li><a href="#tab2">Ban</a></li>
    <li><a href="#tab3">Block IP</a></li>
</ul>

<div class="tabcontents inhoud">
    <!-- file-sytem page tab1: Stats-->
    <div id="tab1">
        <form method="get">
            <input type="text" name="username" value="<?=(isset($_POST['username'])) ? $_POST['username'] : ''; ?>">
            <input type="submit" value="Search">
        </form><br>
        <?php

        if (isset($_POST['save-user'])) {
            array_pop($_POST);
            $admin->updateUser($_POST, $_GET['username']);
            echo "<strong>User updated!</strong>";
        }

        if (isset($_GET['username'])) {
            $user_info = $database->getUserInfo($_GET['username']);

            if (empty($user_info)) {
                echo "User not found";
            } else {
                $items = array(':user' => $user_info->id);
                $stats = $database->query("SELECT * FROM " . TBL_INFO . " WHERE uid = :user", $items)->fetchObject();
                $groups = $database->query("SELECT * FROM ".TBL_GROUPS." ORDER BY name")->fetchAll();
                $banned = $database->query("SELECT * FROM ".TBL_BANNED_USERS." WHERE username = :user", array(':user' => $user_info->username))->rowCount();
        ?>
        <form method="post">
            <table>
                <tr>
                    <td width="35%">
                        IP:
                    </td>
                    <td width="6%">
                        <img src="files/images/icons/computer.png">
                    </td>
                    <td width="69%">
                        <?=$user_info->ip; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Last seen:
                    </td>
                    <td>
                        <img src="files/images/icons/calendar.png">
                    </td>
                    <td>
                        <?=date("Y-m-d H:i", $user_info->timestamp); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Status:
                    </td>
                    <td>
                        <img src="files/images/icons/status_<?=($database->userOnline($user_info->username)) ? 'online' : 'offline'; ?>.png">
                    </td>
                    <td>
                        <?=($database->userOnline($user_info->username)) ? 'Online' : 'Offline'; ?>
                    </td>
                </tr>
                <?php
                if ($banned) {
                ?>
                <tr>
                    <td>
                        Banned:
                    </td>
                    <td>
                        <img src="files/images/icons/cross.png">
                    </td>
                    <td>
                        True
                    </td>
                </tr>
                <?php
                }
                ?>
                <tr>
                    <td>
                        Rank
                    </td>

                    <td>
                        <img src="files/images/icons/rank.png">
                    </td>
                    <td>
                        <select name="rank">
                        <?php

                        foreach ($database->getRanks() as $key => $rank) {
                            if ($key == $stats->rank) {
                                echo "<option value='" . $key . "' selected>" . $rank . "</option>";
                            } else {
                                echo "<option value='" . $key . "'>" . $rank . "</option>";
                            }
                        }

                        ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        Rank process:
                    </td>
                    <td>
                        <img src="files/images/icons/chart_bar.png">
                    </td>
                    <td>
                        <input type="text" name="rank_process" value="<?=$stats->rank_process; ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        Health:
                    </td>
                    <td>
                        <img src="files/images/icons/heart.png">
                    </td>
                    <td>
                        <input type="text" name="health" value="<?=$stats->health; ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        Credits:
                    </td>
                    <td>
                        <img src="files/images/icons/coins.png">
                    </td>
                    <td>
                        <input type="text" name="credits" value="<?=$stats->credits; ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        Money:
                    </td>
                    <td>
                        <img src="files/images/icons/money.png">
                    </td>
                    <td>
                        <input type="text" name="money" value="<?=$stats->money; ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        Bank:
                    </td>
                    <td>
                        <img src="files/images/icons/bank.png">
                    </td>
                    <td>
                        <input type="text" name="bank" value="<?=$stats->bank; ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        Power:
                    </td>
                    <td>
                        <img src="files/images/icons/lightning.png">
                    </td>
                    <td>
                        <input type="text" name="power" value="<?=$stats->power; ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        Bullets
                    </td>
                    <td>
                        <img src="files/images/icons/information.png">
                    </td>
                    <td>
                        <input type="text" name="bullets" value="<?=$stats->bullets; ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        Gym
                    </td>
                    <td>
                        <img src="files/images/icons/information.png">
                    </td>
                    <td>
                        <input type="text" name="gym" value="<?=$stats->gym; ?>">%
                    </td>
                </tr>
                <tr>
                    <td>
                        City:
                    </td>
                    <td>
                        <img src="files/images/icons/world.png">
                    </td>
                    <td>
                        <select name="city">
                            <?php

                            foreach (unserialize($database->getConfigs()['CITIES']) as $key => $city) {
                                if ($key == $stats->city) {
                                    echo "<option value='" . $key . "' selected>" . $city . "</option>";
                                } else {
                                    echo "<option value='" . $key . "'>" . $city . "</option>";
                                }
                            }

                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        Groups:
                    </td>
                    <td>
                        <img src="files/images/icons/group.png">
                    </td>
                    <td>
                        <?php
                        foreach($groups as $group) {
                            if (in_array($group['id'], unserialize($user_info->groups))) {
                                echo '<input type="checkbox" value="' . $group['id'] . '" name="groups[]" checked> ' . $group['name'] . '<br>';
                            } else {
                                echo '<input type="checkbox" value="' . $group['id'] . '" name="groups[]"> ' . $group['name'] . '<br>';
                            }
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <input type="submit" value="Save" name="save-user">
                    </td>
                </tr>
            </table>
        </form>
        <?php
            }
        }

        ?>
    </div>

    <!-- file-sytem page tab2: Ban -->
    <div id="tab2">
        <?php
            if (isset($_POST['ban-user'])) {
                $admin->banUser($_POST['ban-user']);
                echo "<strong>User is banned.</strong>";
            }
        ?>
        <form method="post">
            If you ban a user who already is banned you will unban this user.<br>
            <input type="text" name="ban-user" placeholder="username"><input type="submit" value="(un)Ban user">
        </form><br>
        <div class="banned-users">
            <table width="100%" class="table">
            <?php
                foreach($database->query("SELECT * FROM ".TBL_BANNED_USERS." ORDER BY timestamp DESC")->fetchAll() as $user) {
                    echo "<tr>";
                    echo "<td>Username:</td>";
                    echo "<td>".$user['username']."</td>";
                    echo "<td>Since:</td>";
                    echo "<td>".date('Y-m-d H:i', $user['timestamp'])."</td>";
                    echo "</tr>";
                }
            ?>
            </table>
        </div>
    </div>

    <!-- file-sytem page tab1: - -->
    <div id="tab3">
        <?php
        echo $validator->getVal('ban_ip');
        ?>
        <form method="post">
            <input type="text" placeholder="IP address" name="ip">
            <input type="submit" value="(un)Block" name="ban_ip">
        </form>
        <br>
        <div class="banned-ips">
            <table width="100%" class="table">
                <?php
                foreach($database->query("SELECT * FROM ".TBL_BANNED_IP." ORDER BY timestamp DESC")->fetchAll() as $items) {
                    echo "<tr>";
                    echo "<td>IP:</td>";
                    echo "<td>".$items['ip']."</td>";
                    echo "<td>Since:</td>";
                    echo "<td>".date('Y-m-d H:i', $items['timestamp'])."</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    </div>
</div>