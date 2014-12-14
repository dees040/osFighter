<?php
echo $validator->getVal('new_reaction');

$topic = $forum->getTopic($_GET['id']);
$user_creator = $database->getUserInfoById($topic->creator);
?>
<table width="100%">
    <tr>
        <td align="center">
            <a href="personal/user-info?user=<?=$user_creator->username; ?>"><?=$user_creator->username; ?></a>
        </td>
        <td align="center">
            <strong><?=date("Y-m-d H:i", $topic->date); ?></strong>
        </td>
    </tr>
    <tr>
        <td width="100px">
            <a href="personal/user-info?user=<?=$user_creator->username; ?>"><img src="files/images/user_profile/<?=$user_creator->profile_picture; ?>" width="100px"></a>
        </td>
        <td rowspan="2" style="vertical-align: top;">
            <?=$topic->content; ?>
        </td>
    </tr>
    <tr>
        <td align="center">
            Posts <?=$forum->postAmount(); ?>
        </td>
    </tr>
</table>
<table width="100%">
    <?php foreach($forum->getReactions($_GET['id']) as $reactions) {
        $user_info = $database->getUserInfoById($reactions->uid);
    ?>
        <tr>
            <td align="center">
                <a href="personal/user-info?user=<?=$user_info->username; ?>"><?=$user_info->username; ?></a>
            </td>
            <td>
                <?=date("Y-m-d H:i", $reactions->date); ?>
            </td>
        </tr>
        <tr>
            <td width="100px">
                <a href="personal/user-info?user=<?=$user_info->username; ?>"><img src="files/images/user_profile/<?=$user_info->profile_picture; ?>" width="100px"></a>
            </td>
            <td rowspan="2" style="vertical-align: top;">
                <?=$reactions->content; ?>
            </td>
        </tr>
        <tr>
            <td align="center">
                Posts <?=$forum->postAmount($user_info->id); ?>
            </td>
        </tr>
    <?php } ?>
    <form method="post">
        <tr>
            <td colspan="2">
                <textarea name="reaction_content"></textarea>
            </td>
        </tr>
        <tr>
            <td align="right" colspan="2">
                <input type="submit" value="Comment" name="new_reaction">
            </td>
        </tr>
    </form>
</table>