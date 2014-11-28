<?php
    if (isset($_POST['add_shoutbox'])) {
        echo $user->addToShoutBox($_POST['message']);
    }
    $messages = $database->query("SELECT * FROM ".TBL_SHOUTBOX." ORDER BY date DESC LIMIT 15")->fetchAll(PDO::FETCH_OBJ);
?>
<table width="100%">
    <tr>
        <td align="center" colspan="2" width="20%">
            <strong>By</strong>
        </td>
        <td align="center">
            <strong>Message</strong>
        </td>
    </tr>
    <?php
    foreach($messages as $message) {
        $username = $database->getUserInfoById($message->uid)->username;
    ?>
        <tr>
            <td width="5%">
                <img src="files/images/icons/<?=($database->userOnline($username)) ? 'status_online' : 'status_offline'; ?>.png">
            </td>
            <td width="15%">
                <a href="personal/user-info?user=<?=$username; ?>"><?=$username; ?></a>
            </td>
            <td width="80%">
                <span style="width: 100%; word-wrap: break-word;"><?=$message->message; ?></span>
            </td>
        </tr>
    <?php
    }
    ?>
</table>
<br><br>
<form method="post">
    <textarea name="message" maxlength="300" placeholder="message!"></textarea>
    <input type="submit" value="Add message!" name="add_shoutbox">
</form>