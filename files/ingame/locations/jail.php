<?php
    if (isset($_POST['buy_free'])) {
        echo $user->buyFree($_POST['person']);
    }
?>
<form method="post">
    Op deze pagina bevinden zich de gangsters die op dit moment in de gevangenis zitten. Deze kun je uitkopen voor de borg die wordt weergeven.<br \>
    <br \>
    <table width="100%" cellspacing="2" cellpadding="2" class="mod_list">
        <tr>
            <td width="6%">&nbsp;</td>
            <td width="6%">&nbsp;</td>
            <td><b>User</b></td>
            <td width="6%">&nbsp;</td>
            <td><b>Rank</b></td>
            <td width="6%">&nbsp;</td>
            <td><b>Time</b></td>
            <td width="6%">&nbsp;</td>
            <td><b>Borg</b></td>
        </tr>
        <?php
            $users = $database->query("SELECT uid, jail FROM ".TBL_TIME." WHERE jail > UNIX_TIMESTAMP(NOW()) ORDER BY jail DESC")->fetchAll(PDO::FETCH_OBJ);
            foreach($users as $person) {
                $username = $database->query("SELECT username FROM ".TBL_USERS." WHERE id = :uid", array(':uid' => $person->uid))->fetchObject()->username;
                $rank = $database->query("SELECT rank FROM ".TBL_INFO." WHERE uid = :uid", array(':uid' => $person->uid))->fetchObject()->rank;
                ?>
                <tr class="top">
                    <td>
                        <input type="radio" name="person" value="<?=$person->uid; ?>">
                    </td>
                    <td align="center" width="6%">
                        <?php
                        if ($database->userOnline($username)) {
                            echo '<img src="files/images/icons/status_online.png">';
                        } else {
                            echo '<img src="files/images/icons/status_offline.png">';
                        }
                        ?>
                    </td>
                    <td>
                        <a href="personal/user-info?user=<?=$username; ?>">
                            <?=$username; ?>
                        </a>
                    </td>
                    <td align="center" width="6%">
                        <img src="files/images/icons/rank.png">
                    </td>
                    <td>
                        <?=$info['ranks'][$rank]; ?>
                    </td>
                    <td align="center" width="6%">
                        <img src="files/images/icons/clock.gif">
                    </td>
                    <td>
                        <time class='multiple-timer'><?=$person->jail - time(); ?></time>
                    </td>
                    <td align="center" width="6%">
                        <img src="files/images/icons/coins.png">
                    </td>
                    <td class="cost_td">
                        <?=$settings->currencySymbol(); ?><time class="costs"><?=($person->jail - time()) * 180; ?></time>
                    </td>
                </tr>
            <?php
            }
        ?>
    </table>
    <br>
    <input type="submit" name="buy_free" value="Buy free" class="mod_submit">
</form>