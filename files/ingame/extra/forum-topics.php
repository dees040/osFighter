<table width="100%">
    <tr">
        <td colspan="5" align="center">
            <a href="extra/forum/create-topic?id=<?=$_GET['id']; ?>"><img src="files/images/icons/comment_add.png"><strong>Add topic</strong></a>
        </td>
    </tr>
    <tr>
        <td align="center">
            Title
        </td>
        <td colspan="2" align="center">
            <strong>User</strong>
        </td>
        <td colspan="2" align="center">
            <strong>Date</strong>
        </td>
    </tr>
    <?php
    foreach($forum->getTopics($_GET['id']) as $topic) {
        $username = $database->getUserInfoById($topic->creator)->username;
    ?>
    <tr>
        <td>
            <a href="extra/forum/topic?id=<?=$topic->id; ?>"><?=$topic->title; ?></a>
        </td>
        <td width="5%">
            <img src="files/images/icons/<?=($database->userOnline($username)) ? 'status_online' : 'status_offline'; ?>.png">
        </td>
        <td>
            <a href="personal/user-info?user=<?=$username; ?>"><?=$username; ?></a>
        </td>
        <td width="5%">
            <img src="files/images/icons/calendar.png">
        </td>
        <td>
            <?=date("Y-m-d H:i", $topic->date); ?>
        </td>
    </tr>
    <?php } ?>
</table>