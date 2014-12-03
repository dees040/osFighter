<?php
$i = ($_GET['page'] < 0) ? 0 : $_GET['page'] * 30;
?>
<table width='100%' border='0' cellspacing='2' cellpadding='2' align='center' class="mod_list">
    <tr>
        <td colspan="3"><div align="center"><strong>User</strong></div></td>
        <td colspan="2"><div align="center"><strong>Power</strong></div></td>
        <td colspan="2"><div align="center"><strong>Money (total)</strong></div></td>
        <td colspan="2"><div align="center"><strong>Family</strong></div></td>
    </tr>
    <?php
    foreach($database->paginate(TBL_INFO, "power", ($_GET['page'] * 30), (($_GET['page'] * 30) + 30)) as $user_info) {
        $i++;
        $username = $database->getUserInfoById($user_info->uid)->username;
        $familyName = $database->query("SELECT name FROM ".TBL_FAMILY." WHERE id = :fid", array(':fid' => $user_info->fid))->fetchObject()->name;
        ?>
        <tr class="top">
            <td>
                <strong><?=$i; ?></strong>
            </td>
            <td>
                <img src="files/images/icons/<?=($database->userOnline($username)) ? 'status_online' : 'status_offline'; ?>.png">
            </td>
            <td>
                <div align="center">
                    <a href="personal/user-info?user=<?=$username; ?>"><?=$username; ?></a>
                </div>
            </td>
            <td align="center">
                <img src="files/images/icons/lightning.png">
            </td>
            <td>
                <?=$settings->createFormat($user_info->power); ?>
            </td>
            <td>
                <div align="center">
                    <img src="files/images/icons/coins.png">
                </div>
            </td>
            <td>
                <?=$settings->currencySymbol().$settings->createFormat(($user_info->money + $user_info->bank)); ?>
            </td>
            <td>
                <div align="center">
                    <img src="files/images/icons/group.png">
                </div>
            </td>
            <td>
                <a href="family/profile?id=<?=$user_info->fid; ?>"><?=$familyName; ?></a>
            </td>
        </tr>
    <?php
    }
    ?>
    <tr>
        <td colspan="9">
            <strong style="text-align: center;">
                <?php if ($_GET['page'] != 0) { ?>
                    <a href="statistics/members?page=<?=$_GET['page'] - 1; ?>" style="display: inline-block;">&laquo;</a>
                <?php } ?>
                <form method="get" style="display: inline-block;">
                    <input type="number" value="<?=$_GET['page']; ?>" name="page" style="width: 50px; display: inline;">
                </form>
                <a href="statistics/members?page=<?=$_GET['page'] + 1; ?>" style="display: inline-block;">&raquo;</a>
            </strong>
        </td>
    </tr>
</table>