<?php
    if (isset($_POST['deposit'])) {
        echo $user->deposit($_POST['money_value']);
    } else if (isset($_POST['withdraw'])) {
        echo $user->withdraw($_POST['money_value']);
    }
?>
<table cellspacing="2" cellpadding="2" class="mod_list">
    <tr>
        <td width="6%" style="text-align:center;"><img src="files/images/icons/coins.png" alt="Cash money symbol" border="0"></td>
        <td width="20%">Money (in cash)</td>
        <td><strong><?=$settings->currencySymbol().$settings->createFormat($user->stats->money); ?></strong></td>
    </tr>
    <tr>
        <td width="6%" style="text-align:center;"><img src="files/images/icons/bank.png" alt="Bank money symbol" border="0"></td>
        <td width="20%">Money (on bank)</td>
        <td><strong><?=$settings->currencySymbol().$settings->createFormat($user->stats->bank); ?></strong></td>
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
                <input type="submit" name="deposit" value="Deposit" class="submit">
                <input type="submit" name="withdraw" value="Withdraw" class="submit">
            </form>
        </td>
    </tr>
</table>
