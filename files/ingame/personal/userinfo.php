<?php
    $req_user = $session->username;

    if (isset($_GET['user'])) {
        $req_user = trim($_GET['user']);
    }

    /* Requested Username error checking */
    if (!$req_user || strlen($req_user) == 0 ||
       !$database->usernameTaken($req_user)) {
       echo "Username not registered";
    } else {
        /* Display requested user information - add/delete as applicable */
        $user_info = $database->getUserInfo($req_user);
        $items = array(':user' => $user_info->id);
        $stats = $database->query("SELECT * FROM ".TBL_INFO." WHERE uid = :user", $items)->fetchObject();
        $family = $database->query("SELECT * FROM ".TBL_FAMILY." WHERE id = :fid", array(':fid' => $stats->fid))->fetchObject();
        $online = $database->userOnline($user_info->username);
        $house = $database->query("SELECT name FROM ".TBL_HOUSE_ITEMS." WHERE id = :id", array(':id' => $stats->house))->fetchObject()->name;
        $attackCoins = $database->query("SELECT amount FROM ".TBL_USERS_ITEMS." WHERE uid = :uid AND sid = 5", array(':uid' => $user_info->id))->fetchObject()->amount;
?>
        <img src="files/images/icons/bomb.png"> <a href="extra/attack?target=<?=$user_info->username; ?>"><strong>Attack</strong></a>
        <img src="files/images/icons/vcard.png" style="margin-left:30px;"> <a href="personal/messages?reply=<?=$user_info->username; ?>"><strong>PM</strong></a>
        <img src="files/images/icons/key.png" style="margin-left:30px;"> <a href="click?to=<?=$user_info->username; ?>" target="_blank"><strong>Secret link</strong></a>
        <img src="files/images/icons/ruby_add.png" style="margin-left:30px;"> <a href="personal/respect?respect-to=<?=$user_info->username; ?>"><strong>Respect</strong></a>
        <img src="files/images/icons/group_link.png" style="margin-left:30px;"><a href="friends-enemies?add=<?=$user_info->username; ?>"><strong>Friend</strong></a>


        <table width="100%" border="0" cellspacing="2" cellpadding="2" class="mod_list">
            <tr>
                <td width="35%" class="first">Username:</td>
                <td width="6%" align=center><img src="files/images/icons/<?=($online) ? 'status_online' : 'status_offline'; ?>.png" title="Online"></td>
                <td width="69%"><?=$user_info->username; ?></td>
            </tr>
            <tr>
                <td width="35%" class="first">Health:</td>
                <td width="6%" align=center><img src="files/images/icons/heart.png" border="0px"></td>
                <td width="69%"><?=$stats->health; ?>%</td>
            </tr>
            <tr>
                <td width="35%" class="first">Power:</td>
                <td width="6%" align=center><img src="files/images/icons/lightning.png" border="0px"></td>
                <td width="69%"><?=$settings->createFormat($stats->power); ?></td>
            </tr>
            <tr>
                <td width="35%" class="first">Family power:</td>
                <td width="6%" align=center><img src="files/images/icons/lightning.png" border="0px"></td>
                <td width="69%"><?=$settings->createFormat($family->power); ?></td>
            </tr>
            <tr>
                <td width="35%" class="first">Power total:</td>
                <td width="6%" align=center><img src="files/images/icons/lightning.png" border="0px"></td>
                <td width="69%"><?=$settings->createFormat($stats->power + $family->power); ?></td>
            </tr>
            <tr>
                <td width="35%" class="first">Respect:</td>
                <td width="6%" align=center><img src="files/images/icons/ruby.png" border="0px"></td>
                <td width="69%"><?=$settings->createFormat($stats->respect); ?></td>
            </tr>
            <tr>
                <td width="35%" class="first">Home:</td>
                <td width="6%" align=center><img src="files/images/icons/house.png" border="0px"></td>
                <td width="69%"><?=$house?>
                </td>
            <tr>
                <td width="35%" class="first">City:</td>
                <td width="6%" align=center><img src="files/images/icons/world.png" border="0px"></td>
                <td width="69%"><?=$info['cities'][$stats->city]; ?></td>
            </tr>
            <tr>
                <td width="35%" class="first">Protection:</td>
                <td width="6%" align=center><img src="files/images/icons/shield.png" border="0px"></td>
                <td width="69%"><?=($stats->protection > time()) ? date("Y-m-d H:i", $stats->protection) : 'None protection'; ?></td>
            </tr>
            <tr>
                <td width="35%" class="first">Money (cash):</td>
                <td width="6%" align=center><img src="files/images/icons/money.png" border="0px"></td>
                <td width="69%"><?=$settings->currencySymbol()." ".$settings->createFormat($stats->money); ?></td>
            </tr>
            <tr>
                <td width="35%" class="first">Money (bank):</td>
                <td width="6%" align=center><img src="files/images/icons/bank.png" border="0px"></td>
                <td width="69%"><?=$settings->currencySymbol()." ".$settings->createFormat($stats->bank); ?></td>
            </tr>
            <tr>
                <td width="35%" class="first">Family:</td>
                <td width="6%" align=center><img src="files/images/icons/group.png" border="0px"></td>
                <td width="69%"><a href="family/profile?id=<?=$family->id; ?>"><?=$family->name; ?></a></td>
            </tr>
            <tr>
                <td width="35%" class="first">Rank:</td>
                <td width="6%" align=center><img src="files/images/icons/rank.png" border="0px"></td>
                <td width="69%"><?=$info['ranks'][$stats->rank]; ?></td>
            </tr>
            <tr>
                <td width="35%" class="first">Rank process:</td>
                <td width="6%" align=center><img src="files/images/icons/wand.png" border="0px"></td>
                <td width="69%"><?=$stats->rank_process; ?>%</td>
            </tr>
            <tr>
                <td width="35%" class="first">Bullets:</td>
                <td width="6%" align=center><img src="files/images/icons/help.png" border="0px"></td>
                <td width="69%"><?=$stats->bullets; ?></td>
            </tr>
            <tr>
                <td width="35%" class="first">Trained killers:</td>
                <td width="6%" align=center><img src="files/images/icons/bricks.png" border="0px"></td>
                <td width="69%"><?=$stats->killers; ?></td>
            </tr>
            <tr>
                <td width="35%" class="first">Attack coins:</td>
                <td width="6%" align=center><img src="files/images/icons/brick.png" border="0px"></td>
                <td width="69%"><?=$attackCoins; ?></td>
            </tr>
            <tr>
                <td width="35%" class="first">Attack wins:</td>
                <td width="6%" align=center><img src="files/images/icons/brick_add.png" border="0px"></td>
                <td width="69%"><?=$stats->attwins; ?></td>
            </tr>
            <tr>
                <td width="35%" class="first">Attack losses:</td>
                <td width="6%" align=center><img src="files/images/icons/brick_delete.png" border="0px"></td>
                <td width="69%"><?=$stats->attlost; ?></td>
            </tr>
        </table>
<?php
    }
?>