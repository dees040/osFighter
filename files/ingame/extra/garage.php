<?php
echo $validator->getVal('repair');
echo $validator->getVal('sell-car');
?>
<table width="100%">
    <tr>
        <td colspan="2" align="center">
            <strong>Car</strong>
        </td>
        <td colspan="1" align="center" width="15%">
            <strong>Damage</strong>
        </td>
        <td colspan="2" align="center" width="13%">
            <strong>Worth</strong>
        </td>
        <td colspan="2" align="center">
            <strong>Options</strong>
        </td>
    </tr>
    <?php
    foreach($user->cars() as $car) {
        $car_info = $database->query("SELECT image_one, image_two, name, worth FROM ".TBL_CARS." WHERE id = :cid", array(':cid' => $car->cid))->fetchObject();
    ?>
        <tr>
            <td width="20%">
                <img src="files/images/cars/<?=($car->image == 1) ? $car_info->image_one : $car_info->image_two; ?>" width="100%">
            </td>
            <td width="18%">
                <?=$car_info->name; ?>
            </td>
            <td>
                <div style="width: 100%; background-color: #008000;"><div style="width: <?=$car->damage; ?>%; height: 100%; background-color: red;">&nbsp;<?=$car->damage; ?>%</div></div>
            </td>
            <td width="1%">
                <img src="files/images/icons/money.png">
            </td>
            <td>
                <?=$settings->currencySymbol().$settings->createFormat($car_info->worth - ($car->damage * ($car_info->worth / 100))); ?>
            </td>
            <?php if ($car->damage != 0) { ?>
            <td width="1%" align="center">
                <a href="extra/garage?repair=<?=$car->id; ?>"><img src="files/images/icons/wrench.png"></a>
            </td>
            <?php } ?>
            <td width="1%" align="center" colspan="<?=($car->damage == 0) ? '2' : '1'; ?>">
                <a href="extra/garage?sell-car=<?=$car->id; ?>"><img src="files/images/icons/coins_add.png"></a>
            </td>
        </tr>
    <?php } ?>
</table>