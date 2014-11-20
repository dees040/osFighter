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
        }

        if (isset($_GET['username'])) {
            $user_info = $database->getUserInfo($_GET['username']);

            if (empty($user_info)) {
                echo "User not found";
            } else {
                $items = array(':user' => $user_info['id']);
                $stats = $database->select("SELECT * FROM " . TBL_INFO . " WHERE uid = :user", $items)->fetchObject();
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
                        <?=$user_info['ip']; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Last seen:
                    </td>
                    <td>
                        <img src="files/images/icons/clock.gif">
                    </td>
                    <td>
                        <?=date("Y-m-d H:i", $user_info['timestamp']); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Status:
                    </td>
                    <td>
                        <img src="files/images/icons/status_<?=($database->userOnline($user_info['username'])) ? 'online' : 'offline'; ?>.png">
                    </td>
                    <td>
                        <?=($database->userOnline($user_info['username'])) ? 'Online' : 'Offline'; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Rank
                    </td>

                    <td>
                        <img src="files/images/icons/star.png">
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
                        <img src="files/images/icons/chart_bar.gif">
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

    </div>

    <!-- file-sytem page tab1: - -->
    <div id="tab3">

    </div>
</div>