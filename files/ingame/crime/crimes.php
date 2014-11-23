<?php
$crimes = $database->query("SELECT * FROM ".TBL_CRIMES." ORDER BY `change` ASC")->fetchAll();

$_aMinPayout = array(); // All minimum payouts
$_aMaxPayout = array(); // All maximum payouts
$_aChange    = array(); // Alle changes
foreach ($crimes as $crime) {
    $_aMinPayout[$crime['id']] = $crime['min_payout'];
    $_aMaxPayout[$crime['id']] = $crime['max_payout'];
    $change                    = intval(floor($user->stats->crime_process / $crime['change']));
    $_aChange[$crime['id']]    = ($change > 90) ? 90 : $change;
}

if (isset($_POST['crime'])) {
    if ($user->time->crime_time <= time()) {
        $crime = $database->query("SELECT id FROM ".TBL_CRIMES." WHERE id = :id", array(':id' => $_POST["crime"]))->fetchObject();

        $change = $_aChange[$crime->id];
        $crimeSuccess = mt_rand(0, 100);

        if ($crimeSuccess <= $change) {
            $payout = mt_rand($_aMinPayout[$crime->id], $_aMaxPayout[$crime->id]);
            $newCash = $user->stats->money + $payout;
            $newRankProcess = $user->stats->rank_process + 7;
            $newCrimeProcess = $user->stats->crime_process + 5;

            $items = array(':rank' => $newRankProcess, ':money' => $newCash, ':crime' => $newCrimeProcess, ':uid' => $user->info->id);
            $database->query("UPDATE " . TBL_INFO . " SET rank_process = :rank, money = :money, crime_process = :crime WHERE uid = :uid", $items);

            $items = array(':time' => (time() + 60), ':uid' => $user->info->id);
            $database->query("UPDATE " . TBL_TIME . " SET crime_time = :time WHERE uid = :uid", $items);

            echo $error->successBig("You completed the crime with success and earned " . $info['currency'] . $payout . " with it!");
        } else if ($crimeSuccess < ($change + 30)) {
            $newCrimeProcess = $user->stats->crime_process + 2;
            $items = array(':crime' => $newCrimeProcess, ':uid' => $user->info->id);
            $database->query("UPDATE " . TBL_INFO . " SET crime_process = :crime WHERE uid = :uid", $items);

            $items = array(':time' => (time() + 60), ':uid' => $user->info->id);
            $database->query("UPDATE " . TBL_TIME . " SET crime_time = :time WHERE uid = :uid", $items);
            echo $error->errorBig("Crime failed, but you escaped the police. Cooldown for 60 seconds.");
        } else {
            $items = array(':time' => (time() + 60), ':jail' => (time() + 120), ':uid' => $user->info->id);
            $database->query("UPDATE " . TBL_TIME . " SET crime_time = :time, jail = :jail WHERE uid = :uid", $items);
            echo $error->errorBig("Crime failed and the police took you to jail. You're in jail for 120 seconds.");
        }
    }
}

if ($user->time->crime_time > time()) {
    echo $error->errorBig("You can do the next crime in <time class='timer'>".($user->time->crime_time - time())."</time> seconds.");
} else {
    ?>
    <form method="POST">
        <table align="center" width="100%" border="0" cellspacing="2" cellpadding="2" class="mod_list">
            <?php foreach ($crimes as $crime) { ?>
                <tr>
                    <td rowspan="4" width="10">
                        <input type="radio" name="crime" value="<?= $crime['id']; ?>"
                               onclick="document.getElementById('sel').value = 'true'">
                    </td>
                    <td rowspan="4" width="100">
                        <img src="files/images/crimes/<?= $crime['icon']; ?>" alt="Crime ICON" width="100%">
                    </td>
                    <td colspan="2">
                        <strong><?= $crime['name']; ?></strong>
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
                        Payout is between <?= $crime['min_payout']; ?> and <?= $crime['max_payout']; ?>
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
        <input type="submit" value="Go!">
    </form>
<?php
}
?>