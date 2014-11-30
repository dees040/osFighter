<?php
    if ($user->family->id == 0) {
        echo $error->errorBig("You're not in a family!");
    } else {
        if (isset($_GET['accept'])) {
            $invite = $database->query("SELECT uid FROM ".TBL_FAMILY_JOIN." WHERE id = :id", array(':id' => $_GET['accept']))->fetchObject();
            if ($invite == false) {
                echo $error->errorSmall("This invite doesn't exists.");
            } else {
                $database->query("UPDATE " . TBL_INFO . " SET fid = :fid WHERE uid = :uid", array(':fid' => $user->family->id, ':uid' => $invite->uid));
                $settings->sendMessage("Family invite accepted.", "Your family invite has been accepted.", $invite->uid);
                $database->query("DELETE FROM " . TBL_FAMILY_JOIN . " WHERE id = :id", array(':id' => $_GET['accept']));
                $database->query("DELETE FROM " . TBL_FAMILY_JOIN . " WHERE uid = :uid", array(':uid' => $invite->uid));
                echo $error->succesSmall("Invite accepted.");
            }
        } else if (isset($_GET['decline'])) {
            if ($invite == false) {
                echo $error->errorSmall("This invite doesn't exists.");
            } else {
                $invite = $database->query("SELECT uid FROM " . TBL_FAMILY_JOIN . " WHERE id = :id", array(':id' => $_GET['decline']))->fetchObject();
                $settings->sendMessage("Family invite refused.", "Your family invite has been refused.", $invite->uid);
                $database->query("DELETE FROM " . TBL_FAMILY_JOIN . " WHERE id = :id", array(':id' => $_GET['decline']));
                $database->query("DELETE FROM " . TBL_FAMILY_JOIN . " WHERE uid = :uid", array(':uid' => $invite->uid));
                echo $error->succesSmall("Invite refused.");
            }
        }
?>
<table width="100%">
    <tr>
        <td colspan="7" align="center">
            <strong>Family player join invites</strong>
        </td>
    </tr>
    <tr>
        <td align="center" colspan="2">
            <strong>Player</strong>
        </td>
        <td align="center" colspan="4">
            <strong>Options</strong>
        </td>
    </tr>
    <?php
    $invites = $database->query("SELECT * FROM ".TBL_FAMILY_JOIN." WHERE fid = :fid", array(':fid' => $user->family->id))->fetchAll(PDO::FETCH_OBJ);
    foreach($invites as $invite) {
        $username = $database->getUserInfoById($invite->uid)->username;
    ?>
        <tr>
            <td width="5%">
                <img src="files/images/icons/<?=($database->userOnline($username)) ? 'status_online' : 'status_offline'; ?>.png">
            </td>
            <td>
                <a href="personal/user-info?user=<?=$username; ?>"><?=$username; ?></a>
            </td>
            <td width="5%">
                <img src="files/images/icons/group_add.png">
            </td>
            <td>
                <a href="family/settings?accept=<?=$invite->id; ?>">Accept</a>
            </td>
            <td width="5%">
                <img src="files/images/icons/group_delete.png">
            </td>
            <td>
                <a href="family/settings?decline=<?=$invite->id; ?>">Decline</a>
            </td>
        </tr>
    <?php
    }
    ?>
</table>
<?php }
