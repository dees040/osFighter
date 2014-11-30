<?php

    if ($_GET['join'] == "true") {
        echo $user->joinFamily($_GET['id']);
    } else if ($_GET['leave'] == "true") {
        echo $user->leaveFamily();
    }

    $family = $database->query("SELECT * FROM ".TBL_FAMILY." WHERE id = :uid", array(':uid' => $_GET['id']))->fetchObject();
    $owner = $database->query("SELECT username FROM ".TBL_USERS." WHERE id = :uid", array(':uid' => $family->creator))->fetchObject();
    $members = $database->query("SELECT uid FROM ".TBL_INFO." WHERE fid = :fid", array(':fid' => $family->id));

    if ($_GET['id'] != $user->family->id) {
        echo '<div align="center"><img src="files/images/icons/group_add.png"> <a href="family/profile?id='.$family->id.'&join=true"><strong>Join family</strong></a></div>';
    } else {
        echo '<div align="center"><img src="files/images/icons/group_delete.png"> <a href="family/profile?id='.$family->id.'&leave=true"><strong>Leave family</strong></a></div>';
    }
?>

<table border="0" cellspacing="2" cellpadding="2" width="100%" class="mod_list">
    <tr class="top">
        <td width="28%"><strong>Creator:</strong></td>
        <td width="4%"><img src="files/images/icons/<?=($database->userOnline($owner->username)) ? 'status_online' : 'status_offline'; ?>.png" title="Online"></td>
        <td width="68%"><a href="personal/user-info?user=<?=$owner->username; ?>"><?=$owner->username; ?></a></td>
    </tr>
    <tr class="top">
        <td><strong>Attack coins:</strong></td>
        <td align="center"><img src="files/images/icons/brick.png"></td>
        <td><?echo$family->bullits;?></td>
    </tr>
    <tr class="top">
        <td><strong>Members:</strong></td>
        <td align="center"><img src="files/images/icons/group.png"></td>
        <td><?=$members->rowCount(); ?> (max <?=$family->max_members; ?>)</td>
    </tr>
    <tr class="top">
        <td><strong>Power:</strong></td>
        <td align="center"><img src="files/images/icons/lightning.png"></td>
        <td><?=$settings->createFormat($family->power); ?></td>
    </tr>
    <tr class="top">
        <td><strong>Money (in cash):</strong></td>
        <td align="center"><img src="files/images/icons/money.png"></td>
        <td><?=$settings->currencySymbol()." ".$settings->createFormat($family->cash); ?></td>
    </tr>
    <tr class="top">
        <td><strong>Money (bank):</strong></td>
        <td align="center"><img src="files/images/icons/bank.png"></td>
        <td><?=$settings->currencySymbol()." ".$settings->createFormat($family->bank); ?></td>
    </tr>
    <tr class="top">
        <td><strong>Fights won:</strong></td>
        <td align="center"><img src="files/images/icons/brick_add.png"></td>
        <td><?echo$familie->attwins;?></td>
    </tr>
    <tr class="top">
        <td><strong>Fights lost:</strong></td>
        <td align="center"><img src="files/images/icons/brick_delete.png"></td>
        <td><?echo$familie->attlost;?></td>
    </tr>
</table>

</div>

<div class="content-titel">Family members</div>
<div class="content-inhoud">
    <?php
    $family_members = $database->query("SELECT uid, power, rank FROM ".TBL_INFO." WHERE fid = :fid", array(':fid' => $family->id))->fetchAll(PDO::FETCH_OBJ);
    ?>
    <table border="0" cellspacing="2" cellpadding="2" width="100%" class="mod_list">
        <tr>
            <td colspan="2" align="center"><strong>Name</strong></td>
            <td colspan="2" align="center"><strong>Power</strong></td>
            <td colspan="2" align="center"><strong>Fam. rank</strong></td>
            <td colspan="2" align="center"><strong>Rank</strong></td>
        </tr>
        <?php
            foreach($family_members as $member) {
            $user = $database->getUserInfoById($member->uid);
        ?>
            <tr>
                <td>
                    <img src="files/images/icons/<?=($database->userOnline($user->username)) ? 'status_online' : 'status_offline'; ?>.png" title="Offline">
                </td>
                <td>
                    <a href="personal/user-info?user=<?=$user->username; ?>"><?=$user->username; ?></a>
                </td>
                <td align='center'>
                    <img src='files/images/icons/lightning.png' alt='Power'>
                </td>
                <td><?=$settings->createFormat($member->power); ?></td>
                <td align='center'><img src='files/images/icons/rank.png' alt='Fam. rang'></td>
                <td>None yet</td>
                <td align='center'><img src='files/images/icons/rank.png' alt='Rang'></td>
                <td><?=$info['ranks'][$member->rank]; ?></td>
            </tr>
        <?php } ?>
    </table>