<?php
    echo $validator->getVal('buy_free');
?>
<table width='100%'>
    <tr>
        <td valign='top'>
            Welcome to the <?=$info['title']; ?> jail. Here you can find the most wanted Gangsters of <?=$info['title']; ?>. Help them out by buying them free and earn there respect!
        </td>
        <td>
            <img src='files/images/extra/jail.jpg' align='right' border='1' width="200px" height="125px">
        </td>
    </tr>
</table>
<form method="post">
    <table width="100%" cellspacing="2" cellpadding="2" class="mod_list">
        <tr>
            <td colspan="3" align="center"><strong>User</strong></td>
            <td colspan="2" align="center"><strong>Rank</strong></td>
            <td colspan="2" align="center"><strong>Time</strong></td>
            <td colspan="2" align="center"><strong>Borg</strong></td>
        </tr>
        <?php
            $users = $database->query("SELECT uid, jail FROM ".TBL_TIME." WHERE jail > UNIX_TIMESTAMP(NOW()) ORDER BY jail DESC")->fetchAll(PDO::FETCH_OBJ);
            foreach($users as $person) {
                $username = $database->query("SELECT username FROM ".TBL_USERS." WHERE id = :uid", array(':uid' => $person->uid))->fetchObject()->username;
                $rank = $database->query("SELECT rank FROM ".TBL_INFO." WHERE uid = :uid", array(':uid' => $person->uid))->fetchObject()->rank;
                ?>
                <tr class="top">
                    <td width="3%">
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
                        <img src="files/images/icons/clock.png">
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