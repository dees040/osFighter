<?php
$items = $database->query("SELECT * FROM ".TBL_ITEMS_CC." ORDER BY price")->fetchAll(PDO::FETCH_OBJ);
?>
<form>
    <table width="100%">
        <tr>
            <td colspan="3" align="center" width="50%">
                <strong>Your call credits</strong>
            </td>
            <td colspan="2" align="center">
                <a href="call-credits"><strong><?=$settings->createFormat($user->stats->credits); ?></strong></a>
            </td>
        </tr>
        <tr>
            <td colspan="3" align="center">
                <strong>Item</strong>
            </td>
            <td colspan="2" align="center">
                <strong>Price (in credits)</strong>
            </td>
        </tr>
        <?php
            foreach($items as $item) {
        ?>
            <tr>
                <td width="5%">
                    <input type="radio" name="cc_items">
                </td>
                <td width="5%">
                    <img src="files/images/icons/information.png">
                </td>
                <td>
                    <?=$settings->createFormat($item->give)." ".ucfirst($item->item); ?>
                </td>
                <td width="5%">
                    <img src="files/images/icons/coins.png">
                </td>
                <td>
                    <?=$item->price; ?>
                </td>
            </tr>
        <?php
            }
        ?>
        <tr>
            <td colspan="5">
                <input type="submit" value="Buy item!" name="cc_buy">
            </td>
        </tr>
    </table>
</form>