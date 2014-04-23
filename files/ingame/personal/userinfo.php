<?php
    $req_user = $session->username;

    if (isset($_GET['user'])) {
        $req_user = trim($_GET['user']);
    }

    /* Requested Username error checking */
    if (!$req_user || strlen($req_user) == 0 ||
       !preg_match("/^[a-z0-9]([0-9a-z_-\s])+$/i", $req_user) ||
       !$database->usernameTaken($req_user)) {
       echo "Username not registered";
    } else {
        /* Display requested user information - add/delete as applicable */
        $user_info = (object)$database->getUserInfo($req_user);
        $items = array(':user' => $user_info->username);
        $stats = $database->select("SELECT * FROM ".TBL_INFO." WHERE uid = :user", $items)->fetchObject();
?>
        <img src="files/images/icons/bomb.png"> <a href="attack?target=<?=$user_info->username; ?>"><b>Aanvallen</b></a>
        <img src="files/images/icons/vcard.png" style="margin-left:30px;"> <a href="messages?box=new&to=<?=$user_info->username; ?>"><b>PB sturen</b></a>
        <img src="files/images/icons/key.png" style="margin-left:30px;"> <a href="click?to=<?=$user_info->username; ?>" target="_blank"><b>Secret link</b></a>
        <img src="files/images/icons/ruby_add.png" style="margin-left:30px;"> <a href="respect?to=<?=$user_info->username; ?>"><b>Respect</b></a>
        <img src="files/images/icons/group_link.png" style="margin-left:30px;"><a href="friends-enemies?add=<?=$user_info->username; ?>"><b>Vriend</b></a>


        <table width="100%" border="0" cellspacing="2" cellpadding="2" class="mod_list">
            <tr>
                <td width="35%" class="first">Username:</td>
                <?php
                    $status = "status_offline.png";

                    if ($database->select("SELECT username FROM ".TBL_ACTIVE_USERS." WHERE username = :user", $items)->rowCount()) {
                        $status = "status_online.png";
                    }
                ?>
                <td width="6%" align=center><img src="files/images/icons/<?=$status; ?>" title="Online"></td>
                <td width="69%"><?=$user_info->username; ?></td>
            </tr>
            <tr>
                <td width="35%" class="first">Health:</td>
                <td width="6%" align=center><img src="files/images/icons/heart.png" border="0px"></td>
                <td width="69%"><?=$stats->health; ?>%</td>
            </tr>
            <tr>
                <td width="35%" class="first">Power:</td>
                <td width="6%" align=center><img src="files/images/icons/star.png" border="0px"></td>
                <td width="69%"><? echo $power_profiel; ?></td>
            </tr>
            <tr>
                <td width="35%" class="first">Family power:</td>
                <td width="6%" align=center><img src="files/images/icons/star.png" border="0px"></td>
                <td width="69%"><? echo $powerfamilie_profiel; ?></td>
            </tr>
            <tr>
                <td width="35%" class="first">Power total:</td>
                <td width="6%" align=center><img src="files/images/icons/star.png" border="0px"></td>
                <td width="69%"><? echo $power_totaal; ?></td>
            </tr>
            <tr>
                <td width="35%" class="first">Respect:</td>
                <td width="6%" align=center><img src="files/images/icons/ruby.png" border="0px"></td>
                <td width="69%"><? echo $stats->respect; ?></td>
            </tr>
            <tr>
                <td width="35%" class="first">Home:</td>
                <td width="6%" align=center><img src="files/images/icons/woning-icon.png" border="0px"></td>
                <td width="69%"><? echo $stats->woning?>
                </td>
            <tr>
                <td width="35%" class="first">City:</td>
                <td width="6%" align=center><img src="files/images/icons/world.png" border="0px"></td>
                <td width="69%"></td>
            </tr>
            <tr>
                <td width="35%" class="first">Safe:</td>
                <td width="6%" align=center><img src="files/images/icons/shield.png" border="0px"></td>
                <td width="69%"><? echo $stats->safe; ?> uur</td>
            </tr>
            <tr>
                <td width="35%" class="first">Protection:</td>
                <td width="6%" align=center><img src="files/images/icons/shield.png" border="0px"></td>
                <td width="69%"><? echo $stats->maffia; ?> uur</td>
            </tr>
            <tr>
                <td width="35%" class="first">Penalties:</td>
                <td width="6%" align=center><img src="files/images/icons/warning.png" border="0px"></td>
                <td width="69%"></td>
            </tr>
            <tr>
                <td width="35%" class="first">Money (cash):</td>
                <td width="6%" align=center><img src="files/images/icons/money.png" border="0px"></td>
                <td width="69%">&euro; <?=$stats->money; ?></td>
            </tr>
            <tr>
                <td width="35%" class="first">Money (bank):</td>
                <td width="6%" align=center><img src="files/images/icons/bank.png" border="0px"></td>
                <td width="69%">&euro; <?=$stats->bank; ?></td>
            </tr>
            <tr>
                <td width="35%" class="first">Family:</td>
                <td width="6%" align=center><img src="files/images/icons/group.png" border="0px"></td>
                <td width="69%"></td>
            </tr>
            <tr>
                <td width="35%" class="first">Rank:</td>
                <td width="6%" align=center><img src="files/images/icons/lightning.png" border="0px"></td>
                <td width="69%"><?=$info['ranks'][$stats->rank]; ?></td>
            </tr>
            <tr>
                <td width="35%" class="first">Rank process:</td>
                <td width="6%" align=center><img src="files/images/icons/wand.png" border="0px"></td>
                <td width="69%"><?=$stats->rank_process; ?>%</td>
            </tr>
            <tr>
                <td width="35%" class="first">Trained killers:</td>
                <td width="6%" align=center><img src="files/images/icons/bricks.png" border="0px"></td>
                <td width="69%"><?=$stats->killers; ?></td>
            </tr>
            <tr>
                <td width="35%" class="first">Attack coins:</td>
                <td width="6%" align=center><img src="files/images/icons/brick.png" border="0px"></td>
                <td width="69%"><?=$stats->kogels; ?></td>
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