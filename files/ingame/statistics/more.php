<table width="100%">
    <tr>
        <td colspan="2" align="center">
            <strong>Top 10 power</strong>
        </td>
    </tr>
    <tr>
        <td align="center" width="50%">
            <strong>User</strong>
        </td>
        <td align="center">
            <strong>Power</strong>
        </td>
    </tr>
<?php
foreach($database->paginateField(TBL_INFO, 'power', 0, 10) as $info_a) {
    $username = $database->getUserInfoById($info_a->uid)->username;
?>
<tr>
    <td>
        <a href="personal/user-info?user=<?=$username; ?>"><?=$username; ?></a>
    </td>
    <td>
        <?=$settings->createFormat($info_a->power); ?>
    </td>
</tr>
<?php
}
?>
</table>

<table width="100%">
    <tr>
        <td colspan="2" align="center">
            <strong>Top 10 bank</strong>
        </td>
    </tr>
    <tr>
        <td align="center" width="50%">
            <strong>User</strong>
        </td>
        <td align="center">
            <strong>Bank</strong>
        </td>
    </tr>
    <?php
    foreach($database->paginateField(TBL_INFO, 'bank', 0, 10) as $info_a) {
        $username = $database->getUserInfoById($info_a->uid)->username;
        ?>
        <tr>
            <td>
                <a href="personal/user-info?user=<?=$username; ?>"><?=$username; ?></a>
            </td>
            <td>
                <?=$settings->currencySymbol().$settings->createFormat($info_a->bank); ?>
            </td>
        </tr>
    <?php
    }
    ?>
</table>

<table width="100%">
    <tr>
        <td colspan="2" align="center">
            <strong>Top 10 cash</strong>
        </td>
    </tr>
    <tr>
        <td align="center" width="50%">
            <strong>User</strong>
        </td>
        <td align="center">
            <strong>Cash</strong>
        </td>
    </tr>
    <?php
    foreach($database->paginateField(TBL_INFO, 'money', 0, 10) as $info_a) {
        $username = $database->getUserInfoById($info_a->uid)->username;
        ?>
        <tr>
            <td>
                <a href="personal/user-info?user=<?=$username; ?>"><?=$username; ?></a>
            </td>
            <td>
                <?=$settings->currencySymbol().$settings->createFormat($info_a->money); ?>
            </td>
        </tr>
    <?php
    }
    ?>
</table>

<table width="100%">
    <tr>
        <td colspan="2" align="center">
            <strong>Top 10 credits</strong>
        </td>
    </tr>
    <tr>
        <td align="center" width="50%">
            <strong>User</strong>
        </td>
        <td align="center">
            <strong>Credits</strong>
        </td>
    </tr>
    <?php
    foreach($database->paginateField(TBL_INFO, 'credits', 0, 10) as $info_a) {
        $username = $database->getUserInfoById($info_a->uid)->username;
        ?>
        <tr>
            <td>
                <a href="personal/user-info?user=<?=$username; ?>"><?=$username; ?></a>
            </td>
            <td>
                <?=$settings->createFormat($info_a->credits); ?>
            </td>
        </tr>
    <?php
    }
    ?>
</table>

<table width="100%">
    <tr>
        <td colspan="2" align="center">
            <strong>Top 10 highest rank</strong>
        </td>
    </tr>
    <tr>
        <td align="center" width="50%">
            <strong>User</strong>
        </td>
        <td align="center">
            <strong>Rank</strong>
        </td>
    </tr>
    <?php
    $users = $database->query("SELECT uid, rank FROM ".TBL_INFO." ORDER BY rank DESC, rank_process DESC LIMIT 10")->fetchAll(PDO::FETCH_OBJ);
    foreach($users as $info_a) {
        $username = $database->getUserInfoById($info_a->uid)->username;
        ?>
        <tr>
            <td>
                <a href="personal/user-info?user=<?=$username; ?>"><?=$username; ?></a>
            </td>
            <td>
                <?=$info['ranks'][$info_a->rank]; ?>
            </td>
        </tr>
    <?php
    }
    ?>
</table>

<table width="100%">
    <tr>
        <td colspan="2" align="center">
            <strong>Top 10 ho's</strong>
        </td>
    </tr>
    <tr>
        <td align="center" width="50%">
            <strong>User</strong>
        </td>
        <td align="center">
            <strong>Ho's</strong>
        </td>
    </tr>
    <?php
    foreach($database->paginateField(TBL_INFO, 'ho_glass', 0, 10) as $info_a) {
        $username = $database->getUserInfoById($info_a->uid)->username;
        ?>
        <tr>
            <td>
                <a href="personal/user-info?user=<?=$username; ?>"><?=$username; ?></a>
            </td>
            <td>
                <?=$settings->createFormat($info_a->ho_glass); ?>
            </td>
        </tr>
    <?php
    }
    ?>
</table>

<table width="100%">
    <tr>
        <td colspan="2" align="center">
            <strong>Top 10 Respect</strong>
        </td>
    </tr>
    <tr>
        <td align="center" width="50%">
            <strong>User</strong>
        </td>
        <td align="center">
            <strong>Respect points</strong>
        </td>
    </tr>
    <?php
    foreach($database->paginateField(TBL_INFO, 'respect', 0, 10) as $info_a) {
        $username = $database->getUserInfoById($info_a->uid)->username;
        ?>
        <tr>
            <td>
                <a href="personal/user-info?user=<?=$username; ?>"><?=$username; ?></a>
            </td>
            <td>
                <?=$settings->createFormat($info_a->respect); ?>
            </td>
        </tr>
    <?php
    }
    ?>
</table>