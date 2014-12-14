<?php
if (!isset($_SESSION['higher-lower'])) {
    $_SESSION['higher-lower'] = mt_rand(0, 10);
}

echo $validator->getVal('bet_higher_lower');

?>
<table width='100%'>
    <tr>
        <td valign='top'>
            Welcome to <strong>Higher / Lower</strong>!<br /><br />
            In this game you need to guess if the next number will be higher or lower then the you have. You can choose how much you bet each turn. The minimum is <?=$settings->currencySymbol().$settings->createFormat('500'); ?> and the maximum is <?=$settings->currencySymbol().$settings->createFormat('5000'); ?>. If you guessed it correctly you will get your bet multiplied with 2, if you guessed it wrong you will lose your bet.<br><br>
            * If the new number is the same as the previous number you will lose your bet multiplied by 4.
        </td>
        <td>
            <img src='files/images/extra/stones.jpg' align='right' border='1' width="200px" height="125px">
        </td>
    </tr>
</table>
<br>
<form method="post">
    <table width="100%">
        <tr>
            <td align="center">
                <?=$_SESSION['higher-lower']; ?>
            </td>
        </tr>
        <tr>
            <td align="center">
                <input type="text" name="higher_lower_bet" value="<?=$_SESSION['higher-lower-bet']; ?>" size="4" maxlength="4">
            </td>
        </tr>
        <tr>
            <td align="center">
                <input type="submit" value="Lower" name="bet_lower"> / <input type="submit" value="Higher" name="bet_higher">
            </td>
        </tr>
    </table>
</form>