<?php
    if (isset($_POST['buy_ticket'])) {
        echo $user->buyTicket($_POST['location']);
    }
?>
<table width='100%'>
    <tr>
        <td valign='top'>
            Welcome to <strong><?=$info['cities'][$user->stats->city]; ?></strong>!<br /><br />
            From this airport you can fly through all the airports in <?=$info['title']; ?>.<br /><br />
            The benifts of flying through other cities is that you can kill other player who are in that city.
        </td>
        <td>
            <img src='files/images/extra/world.png' align='right' border='1' width="200px" height="125px">
        </td>
    </tr>
</table>

<form method="post">
    <table width='100%' class="mod_list" cellspacing='2' cellpadding='2'>
        <tr>
            <td align='center' colspan="3"><b>City</b></td>
            <td align='center' colspan="2"><b>Costs</b></td>
            <td align='center' colspan="2"><b>Population</b></td>
            <td align='center' colspan="2"><b>Owner</b></td>
        </tr>
        <?php
        foreach($info['cities'] as $id => $city) {
            $population = $database->query("SELECT city FROM ".TBL_INFO." WHERE city = :city", array(':city' => $id))->rowCount();
            ?>
            <tr class='top'>
                <td align='center' width='5%'>
                    <?php
                        if ($id != $user->stats->city) {
                            echo '<input type="radio" name="location" value="'.$id.'">';
                        }
                    ?>
                </td>
                <td align='center' width='5%'><img src='files/images/icons/world.png'></td>
                <td><?=$city; ?></td>
                <td align='center' width='5%'><img src='files/images/icons/coins.png'></td>
                <td align='center'><?=$settings->currencySymbol().$settings->createFormat($settings->config['FLY_TICKET_COST']); ?></td>
                <td align='center' width='5%'><img src='files/images/icons/chart_bar.png'></td>
                <td align='center'><?=$population; ?></td>
                    <td align='center' width='5%'><img src='files/images/icons/status_offline.png'></td>
                <td align='center'>
                    Not available
                </td>
            </tr>
        <?php
        }
        ?>

        <tr height='20px'>
            <td colspan='9'>
                <input type='submit' name='buy_ticket' value='Buy ticket!'>
            </td>
        </tr>
    </table>
</form>