<?php
if ($user->stats->fid == 0) {
    echo $error->errorBig("You're not in a family.");
} else {
    echo $validator->getVal('deposit_family');
    echo $validator->getVal('withdraw_family');
?>
<table cellspacing="2" cellpadding="2" class="mod_list">
    <tr>
        <td width="6%" style="text-align:center;"><img src="files/images/icons/bank.png" alt="Bank money symbol" border="0"></td>
        <td width="20%">Money (on bank)</td>
        <td><strong><?=$settings->currencySymbol().$settings->createFormat($user->family->bank); ?></strong></td>
    </tr>
    <tr>
        <td width="6%" style="text-align:center;"><img src="files/images/icons/information.png" alt="Information symbol" border="0"></td>
        <td colspan="2">You deposit cash <strong>unlimited</strong> time(s) today.</td>

    </tr>
    <tr>
        <td width="6%" style="text-align:center;"><img src="<?=$settings->currencySymbol(true); ?>" alt="Currency symbol" border="0"></td>
        <td colspan="2">
            <form method="post">
                <input type="number" id="money_value" name="money_value" maxlength="25" style="width: 200px" class="input">
                <input type="submit" name="deposit_family" value="Deposit" class="submit">
                <input type="submit" name="withdraw_family" value="Withdraw" class="submit">
            </form>
        </td>
    </tr>
</table>
<br>
<table width="100%">
    <tr>
        <td colspan="4" align="center">
            <strong>Bank Transactions</strong>
        </td>
    </tr>
    <tr>
        <td colspan="2" align="center" width="50%">
            <strong>User</strong>
        </td>
        <td colspan="2" align="center">
            <strong>Amount</strong> (in total)
        </td>
    </tr>
    <?php
    $deposits = $database->query("SELECT uid, amount FROM ".TBL_FAMILY_TRAN." WHERE fid = :fid ORDER BY amount DESC", array(':fid' => $user->family->id))->fetchAll(PDO::FETCH_OBJ);
    foreach($deposits as $item) {
        $username = $database->getUserInfoById($item->uid)->username;
        ?>
        <tr>
            <td width="5%">
                <img src="files/images/icons/<?=($database->userOnline($username)) ? 'status_online' : 'status_offline'; ?>.png">
            </td>
            <td>
                <a href="personal/user-info?user=<?=$username; ?>"><?=$username; ?></a>
            </td>
            <td width="5%">
                <img src="files/images/icons/bank.png">
            </td>
            <td>
                <?=$settings->currencySymbol().$settings->createFormat($item->amount); ?>
            </td>
        </tr>
    <?php
    }
    ?>
</table>
<?php } ?>