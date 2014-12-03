<table width="100%">
    <?php if($session->isAdmin()) { ?>
    <tr>
        <td colspan="4" align="center">
            <a href="extra/forum/create-form"><strong><img src="files/images/icons/comment_add.png"> Create Forum</strong></a>
        </td>
    </tr>
    <?php } ?>
    <tr>
        <td align="center">
            <strong>Forum</strong>
        </td>
        <td align="center">
            <strong>Description</strong>
        </td>
        <td align="center" colspan="2">
            <strong>Latest Topic</strong>
        </td>
    </tr>
<?php
foreach($forum->getForums() as $_forum) {
    $latestTopic = $forum->getLatestTopic($_forum->id);
    $userProfile = $database->getUserInfoById($latestTopic->creator);
?>
    <tr>
        <td rowspan="2">
            <a href="extra/forum/topics?id=<?=$_forum->id; ?>"><?=$_forum->title; ?></a>
        </td>
        <td rowspan="2">
            <?=$_forum->description; ?>
        </td>
        <td width="5%">
            <a href="personal/user-info?user=<?=$userProfile->username; ?>"><img src="files/images/user_profile/<?=$userProfile->profile_picture; ?>" width="20px"></a>
        </td>
        <td>
            <a href="extra/forum/topic?id=<?=$latestTopic->id; ?>"><?=$latestTopic->title; ?></a>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <a href="extra/forum/topic?id=<?=$latestTopic->id; ?>"><?=date("Y-m-d H:i", $latestTopic->date); ?></a>
        </td>
    </tr>
<?php
}
?>
</table>