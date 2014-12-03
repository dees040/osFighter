<?php
if (isset($_POST['crack_the_vault'])) {
    array_pop($_POST);
    echo $casino->checkTheCode($_POST);
}
?>
<table width='100%'>
    <tr>
        <td valign='top'>
            Welcome to <strong>Crack the Vault</strong>!<br /><br />
            You need to crack the code which combines 4 letters and 2 digits.
            So for an example your code could be: 0000AA. Cracking the code will give around <?=$settings->currencySymbol().$settings->createFormat(50000); ?> and <?=$settings->currencySymbol().$settings->createFormat(200000); ?>.
        </td>
        <td>
            <?php
                $image = "vault";
                if ($_SESSION['vault_is_open'] === true) {
                    $image = "vault_open";
                    unset($_SESSION['vault_is_open']);
                }
            ?>
            <img src='files/images/extra/<?=$image; ?>.jpg' align='right' border='1' width="200px" height="125px">
        </td>
    </tr>
</table>

<form method="post">
    <table class="mod_list" cellspacing='2' cellpadding='2' align="center">
        <tr>
            <td>
                <input type="text" name="number_0" maxlength="1" size="1" placeholder="A" class="crack_the_vault" value="<?=$casino->goodCode(0); ?>">
                <input type="text" name="number_1" maxlength="1" size="1" placeholder="A" class="crack_the_vault" value="<?=$casino->goodCode(1); ?>">
                <input type="text" name="number_2" maxlength="1" size="1" placeholder="A" class="crack_the_vault" value="<?=$casino->goodCode(2); ?>">
                <input type="text" name="number_3" maxlength="1" size="1" placeholder="A" class="crack_the_vault" value="<?=$casino->goodCode(3); ?>">
                <input type="number" name="number_4" maxlength="1" size="1" placeholder="0" min="0" max="9" class="crack_the_vault" value="<?=$casino->goodCode(4); ?>">
                <input type="number" name="number_5" maxlength="1" size="1" placeholder="0" min="0" max="9" class="crack_the_vault" value="<?=$casino->goodCode(5); ?>">
            </td>
        </tr>
        <tr>
            <td>
                <input type='submit' name='crack_the_vault' value='Crack!' align="center">
            </td>
        </tr>
    </table>
</form>