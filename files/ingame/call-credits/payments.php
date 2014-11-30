<?php

$payments = $database->query("SELECT * FROM ".TBL_PAYMENTS." ORDER BY date DESC")->fetchAll(PDO::FETCH_OBJ);
?>
<table width="100%">
    <tr>
        <td colspan="2" align="center">
            <strong>Username</strong>
        </td>
        <td colspan="2" align="center">
            <strong>Payment ID</strong>
        </td>
        <td align="center">
            <strong>Status</strong>
        </td>
        <td align="center" colspan="2">
            <strong>Price</strong>
        </td>
        <td align="center" colspan="2">
            <strong>Created on</strong>
        </td>
    </tr>
    <?php
    foreach($payments as $payment) {
        $username = $database->getUserInfoById($payment->uid)->username;
    ?>
        <tr>
            <td>
                <img src="files/images/icons/<?=$database->userOnline($username) ? 'status_online' : 'status_offline'; ?>.png">
            </td>
            <td>
                <a href="personal/user-info?user=<?=$username; ?>"><?=$username; ?></a>
            </td>
            <td>
                <img src="files/images/icons/information.png">
            </td>
            <td>
                <?=$payment->payment_id; ?>
            </td>
            <td align="center">
                <img src="files/images/icons/<?=($payment->complete == 1) ? 'accept' : 'cross'; ?>.png" title="<?=($payment->complete == 1) ? 'Payment completed' : 'Payment canceled'; ?>">
            </td>
            <td>
                <img src="files/images/icons/coins.png">
            </td>
            <td>
                <?=$settings->currencySymbol().$payment->price; ?>
            </td>
            <td>
                <img src="files/images/icons/calendar.png">
            </td>
            <td>
                <?=date("Y-m-d H:i", $payment->date); ?>
            </td>
        </tr>
    <?php
    }
    ?>
</table>