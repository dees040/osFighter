<?php
$i = ($_GET['page'] < 0) ? 0 : $_GET['page'] * 10;
?>
<table width='100%' border='0' cellspacing='2' cellpadding='2' align='center' class="mod_list">
    <tr>
        <td colspan="2"><div align="center"><strong>Family</strong></div></td>
        <td colspan="2"><div align="center"><strong>Creator</strong></div></td>
        <td colspan="2"><div align="center"><strong>Money</strong></div></td>
        <td colspan="2"><div align="center"><strong>Family Power</strong></div></td>
        <td colspan="2"><div align="center"><strong>Members</strong></div></td>
    </tr>
    <?php
    foreach($database->paginate(TBL_FAMILY, "power", ($_GET['page'] * 10), (($_GET['page'] * 10) + 10)) as $family) {
        $i++;
        $creator = $database->query("SELECT username FROM ".TBL_USERS." WHERE id = :id", array(':id' => $family->creator))->fetchObject()->username;
        ?>
        <tr class="top">
            <td>
                <strong><?=$i; ?></strong>
            </td>
            <td>
                <div align="center">
                    <a href="family/profile?id=<?=$family->id; ?>"><?=$family->name; ?></a>
                </div>
            </td>
            <td align="center">
                <img src="files/images/icons/user-gray.png">
            </td>
            <td>
                <div align="center">
                    <a href="personal/user-info?user=<?=$creator; ?>"><?=$creator; ?></a>
                </div>
            </td>
            <td>
                <div align="center">
                    <img src="files/images/icons/coins.png">
                </div>
            </td>
            <td>
                <div align="center">
                    <?=$settings->currencySymbol().$settings->createFormat($family->bank); ?>
                </div>
            </td>
            <td>
                <div align="center">
                    <img src="files/images/icons/lightning.png">
                </div>
            </td>
            <td>
                <div align="center">
                    <?=$settings->createFormat($family->power); ?>
                </div>
            </td>
            <td>
                <div align="center">
                    <img src="files/images/icons/group.png">
                </div>
            </td>
            <td>
                <div align="center">
                    <?=$database->query("SELECT uid FROM ".TBL_INFO." WHERE fid = :fid", array(':fid' => $family->id))->rowCount(); ?>
                </div>
            </td>
        </tr>
    <?php
    }
    ?>
    <tr>
        <td colspan="10">
            <strong style="text-align: center;">
                <?php if ($_GET['page'] != 0) { ?>
                <a href="family?page=<?=$_GET['page'] - 1; ?>" style="display: inline-block;">&laquo;</a>
                <?php } ?>
                <form method="get" style="display: inline-block;">
                    <input type="number" value="<?=$_GET['page']; ?>" name="page" style="width: 50px; display: inline;">
                </form>
                <a href="family?page=<?=$_GET['page'] + 1; ?>" style="display: inline-block;">&raquo;</a>
            </strong>
        </td>
    </tr>
</table>