<?php
if (isset($_GET['id'])) {
    $message = $database->query(
        "SELECT * FROM " . TBL_MESSAGE . " WHERE id = :id AND (from_id = :uid OR to_id = :uid)"
        , array(':id' => $_GET['id'], ':uid' => $user->id)
    )->fetchObject();
    if ($message === false) {
        echo "You don't have permissions to view this message.";
    } else {
        if ($message->status == 0 && $message->to_id == $user->id) {
            $items = array(':status' => 1, ':id' => $message->id);
            $database->query("UPDATE ".TBL_MESSAGE." SET status = :status WHERE id = :id", $items);
        }
        if ($message->from_id == 0) {
            $from = " * SYSTEM * ";
        } else {
            $from = $database->getUserInfoById($message->from_id)->username;
        }
        ?>
        <table width="100%">
            <tr>
                <td width="5%" align="center">
                    <img src="files/images/icons/<?=($message->status) ? 'email_open' : 'email'; ?>.png">
                </td>
                <td colspan="6" align="center">
                    <strong><?= $message->subject; ?></strong>
                </td>
            </tr>
            <tr>
                <td rowspan="2" width="13%">
                    <strong>Message:</strong>
                </td>
                <td width="10%">
                    From:
                </td>
                <td width="5%">
                    <img
                        src="files/images/icons/<?= ($database->userOnline($from)) ? 'status_online' : 'status_offline'; ?>.png">
                </td>
                <td>
                    <a href="personal/user-info?user=<?= $from; ?>"><?= $from; ?></a>
                </td>
                <td width="10%">
                    Send on:
                </td>
                <td width="5%">
                    <img src="files/images/icons/calendar.png">
                </td>
                <td>
                    <?= date("Y-m-d H:i", $message->date); ?>
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <?= $message->content; ?>
                </td>
            </tr>
            <tr>
                <td>
                    Action's
                </td>
                <td>
                    <img src="files/images/icons/email_delete.png">
                </td>
                <td colspan="2">
                    <a href="personal/messages?delete=<?=$message->id; ?>">Delete</a>
                </td>
                <td>
                    <img src="files/images/icons/email_add.png">
                </td>
                <td colspan="2">
                    <a href="personal/messages?reply=<?=$from; ?>&subject=<?=$message->subject; ?>">Reply</a>
                </td>
            </tr>
        </table>
    <?php
    }
} else {
    echo "No message to load.";
}
