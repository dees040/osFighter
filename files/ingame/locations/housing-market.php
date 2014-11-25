<?php
    if ($_POST['buy_house']) {
        echo $user->buyHouse($_POST['house-id']);
    }
    $houses = $database->query("SELECT * FROM ".TBL_HOUSE_ITEMS." ORDER BY price")->fetchAll(PDO::FETCH_OBJ);
?>
<form method="post">
    <table width='100%' class="mod_list" cellspacing='2' cellpadding='2'>
        <tr>
            <td align='center' colspan="3"><strong>House</strong></td>
            <td align='center' colspan="2"><strong>Price</strong></td>
            <td align="center" colspan="2"><strong>Image</strong></td>
            <td align="center" colspan="2"><strong>Sell for</strong></td>
        </tr>
        <?php
        foreach($houses as $house) {
            ?>
            <tr class='top'>
                <td align='center' width='5%'>
                    <?php
                    if ($house->id != $user->stats->house) {
                        echo '<input type="radio" name="house-id" value="'.$house->id.'">';
                    }
                    ?>
                </td>
                <td align='center' width='5%'><img src='files/images/icons/information.png'></td>
                <td><?=$house->name; ?></td>
                <td align='center' width='5%'><img src='files/images/icons/coins.png'></td>
                <td align='center'><?=$settings->currencySymbol().$settings->createFormat($house->price); ?></td>
                <td align='center' width='5%'><img src='files/images/icons/house.png'></td>
                <td align='center'><img src="files/images/house-market/<?=$house->image; ?>" width="80%"></td>
                <td align='center' width='5%'><img src='files/images/icons/coins.png'></td>
                <td align='center'><?=$settings->currencySymbol().$settings->createFormat($house->price * 0.75); ?></td>
            </tr>
        <?php
        }
        ?>

        <tr>
            <td colspan='9'>
                <input type='submit' name='buy_house' value='Buy property!'>
            </td>
        </tr>
    </table>
</form>