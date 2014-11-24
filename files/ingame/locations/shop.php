<?php

if (isset($_POST['number'])) {
    echo $user->buyFromShop($_POST);
}

$shopItems = $database->query("SELECT * FROM ".TBL_SHOP_ITEMS." ORDER BY price ASC")->fetchAll(PDO::FETCH_OBJ);
foreach ($shopItems as $item) {
    $items = array(':uid' => $user->id, ':sid' => $item->id);
    $amount = $database->query("SELECT amount FROM ".TBL_USERS_ITEMS." WHERE uid = :uid AND sid = :sid", $items)->fetchObject()->amount;
?>
    <table width=100% cellspacing="2px" cellpadding="2px" class="mod_list">
        <form method="post">
            <tr>
                <td colspan=3 style="font-size:14px; font-weight:bold; color:#402810; padding-left:8px;"><?=$item->name; ?></td>
            </tr>
            <tr>
                <td rowspan=6 width="110px" height="110px" valign="middle" align="center">
                    <img src="files/images/shop/<?=$item->image; ?>">
                </td>
            </tr>
            <tr>
                <td width="18px" align="center" valign="middle"><img src="files/images/icons/money.png"></td>
                <td>Price: <b> <?=$settings->currencySymbol().$settings->createFormat($item->price); ?></b></td>
            </tr>
            <tr>
                <td width="18px" align="center" valign="middle"><img src="files/images/icons/lightning.png"></td>
                <td>Power: <b><?=$item->power; ?></b></td>
            </tr>
            <tr>
                <td width="18px" align="center" valign="middle"><img src="files/images/icons/wand.png"></td>
                <td valign="middle"><form method="post" style="display: inline;">Amount: <input type="number" name="number" class="input" size="5">&nbsp;<input type="submit" value="Buy!" name="<?=$item->id; ?>"></form></td>
            </tr>
            <tr>
                <td width="18px" align="center" valign="middle"><img src="files/images/icons/information.png"></td>
                <td><?=$item->description; ?></td>
            </tr>
            <tr>
                <td width="18px" align="center" valign="middle"><img src="files/images/icons/asterisk_orange.png"></td>
                <td valign="middle" >
                    Unlimited amount allowed,
                    you have <b><?=($amount == NULL) ? '0' : $amount; ?></b>
                </td>
            </tr>
        </form>
    </table>
<?php
}
?>