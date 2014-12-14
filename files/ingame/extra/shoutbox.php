<?php
echo $validator->getVal('add_shoutbox');
$messages = $database->query("SELECT * FROM ".TBL_SHOUTBOX." ORDER BY date DESC LIMIT 15")->fetchAll(PDO::FETCH_OBJ);
$user->setShoutbox($messages[0]->id);
?>
<script>
    tinymce.init({
        selector: "textarea",
        plugins: ["emoticons"],
        toolbar: [
            "undo redo | styleselect | bold italic | link image | alignleft aligncenter alignright",
            "emoticons"
        ]
    });
</script>
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
    <textarea name="message" maxlength="300" placeholder="message!" class="text"></textarea>
    <input type="submit" value="Add message!" name="add_shoutbox">
</form>