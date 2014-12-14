<?php

echo $validator->getVal('number_family_shop');

$shopItems = $database->query("SELECT * FROM ".TBL_SHOP_ITEMS." ORDER BY price ASC")->fetchAll(PDO::FETCH_OBJ);
foreach ($shopItems as $item) {
    if ($item->id == 5) continue;
    $items = array(':fid' => $user->family->id, ':sid' => $item->id);
    $amount = $database->query("SELECT amount FROM ".TBL_FAMILY_ITEMS." WHERE fid = :fid AND sid = :sid", $items)->fetchObject()->amount;
    ?>
    <form method="post">
        <table width=100% cellspacing="2px" cellpadding="2px" class="mod_list">
            <tr>
                <td colspan=3 style="font-size:14px; font-weight:bold; color:#402810; padding-left:8px;"><?=$item->name; ?></td>
            </tr>
            <tr>
                <td rowspan="6" width="110px" height="110px" valign="middle" align="center">
                    <img src="files/images/shop/<?=$item->image; ?>">
                </td>
            </tr>
            <tr>
                <td width="18px" align="center" valign="middle"><img src="files/images/icons/money.png"></td>
                <td>Price: <strong> <?=$settings->currencySymbol().$settings->createFormat($item->price); ?></strong></td>
            </tr>
            <tr>
                <td width="18px" align="center" valign="middle"><img src="files/images/icons/lightning.png"></td>
                <td>Power: <strong><?=$item->power; ?></strong></td>
            </tr>
            <tr>
                <td width="18px" align="center" valign="middle"><img src="files/images/icons/wand.png"></td>
                <td valign="middle"><form method="post" style="display: inline;">Amount: <input type="number" name="number_family_shop" class="input" size="5">&nbsp;<input type="submit" value="Buy!" name="<?=$item->id; ?>"></form></td>
            </tr>
            <tr>
                <td width="18px" align="center" valign="middle"><img src="files/images/icons/information.png"></td>
                <td><?=$item->description; ?></td>
            </tr>
            <tr>
                <td width="18px" align="center" valign="middle"><img src="files/images/icons/asterisk_orange.png"></td>
                <td valign="middle">
                    Unlimited amount allowed,
                    you have <strong><?=($amount == NULL) ? '0' : $amount; ?></strong>
                </td>
            </tr>
        </table>
    </form>
<?php
}
?>