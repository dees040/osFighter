<?php
    $crimes = $database->select("SELECT * FROM ".TBL_CRIMES." ORDER BY `change` ASC")->fetchAll();

    $_aMinPayout = array(); // All minimum payouts
    $_aMaxPayout = array(); // All maximum payouts
    $_aChange    = array(); // Alle changes
    foreach ($crimes as $crime) {
        $_aMinPayout[$crime['id']] = $crime['min_payout'];
        $_aMaxPayout[$crime['id']] = $crime['max_payout'];
        $_aChange[$crime['id']]    = floor($user->stats->crime_process / $crime['change']);
    }

    if (isset($_POST['crime'])) {

    }

?>

<form method="POST">
    <table align="center" width="100%" border="0" cellspacing="2" cellpadding="2" class="mod_list">
        <?php foreach($crimes as $crime) { ?>
        <tr>
            <td rowspan="4" width="10">
                <input type="radio" name="crime" value="<?=$crime['id']; ?>" onclick="document.getElementById('sel').value = 'true'">
            </td>
            <td rowspan="4" width="100">
                <img src="files/images/crimes/<?=$crime['icon']; ?>" alt="Crime ICON" width="100%">
            </td>
            <td colspan="2">
                <strong><?=$crime['name']; ?></strong>
            </td>
        </tr>
        <tr>
            <td width="16">
                <label for="1">
                    <img src="files/images/icons/chart_bar.gif" alt="Change for crime">
                </label>
            </td>
            <td>
                <strong><?php
                    if ($_aChange[$crime['id']] > 100) {
                        $_aChange[$crime['id']] = 100;
                    }
                    echo $_aChange[$crime['id']]; ?>%</strong> change of success.
            </td>
        </tr>
        <tr>
            <td>
                <img src="files/images/icons/money.gif" alt="Payout">
            </td>
            <td>
                Payout is between <?=$crime['min_payout']; ?> and <?=$crime['max_payout']; ?>
            </td>
        </tr>
        <tr>
            <td>
                <img src="files/images/icons/clock.gif" alt="Punishment for crime">
            </td>
            <td>
                <strong>1 minute</strong> jail time if you get caught.
            </td>
        </tr>
        <tr height="5px">

        </tr>
        <?php } ?>
    </table>
</form>