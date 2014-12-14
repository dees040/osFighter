<?php
echo $validator->getVal('create_race');
echo $validator->getVal('start_race');
echo $validator->getVal('delete-race');

$streetraces = $database->query("SELECT * FROM ".TBL_RACES." ORDER BY bet DESC")->fetchAll(PDO::FETCH_OBJ);
$i = 0;
?>

<form method="post">
    <table width="100%">
        <tr>
            <td colspan="3" align="center">
                <strong>Starter</strong>
            </td>
            <td colspan="2" align="center">
                <strong>Bet</strong>
            </td>
            <td colspan="2" align="center">
                <strong>Options</strong>
            </td>
        </tr>
        <?php
        foreach($streetraces as $race) {
            $i++;
            $username = $database->getUserInfoById($race->uid)->username;
            $online = $database->userOnline($username);
        ?>
            <tr>
                <td width="5%">
                    #<?=$i; ?>
                </td>
                <td width="5%">
                    <img src="files/images/icons/<?=($online) ? 'status_online' : 'status_offline'; ?>.png">
                </td>
                <td>
                    <a href="personal/user-info?user=<?=$username; ?>"><?=$username; ?></a>
                </td>
                <td width="5%">
                    <img src="files/images/icons/money.png">
                </td>
                <td>
                    <?=$settings->currencySymbol().$settings->createFormat($race->bet); ?>
                </td>
                <td colspan="<?=($race->uid == $user->id) ? '1' : '2'; ?>" width="40%">
                    <form method="post">
                        <select name="race_car">
                            <?php
                            foreach($user->cars() as $car) {
                                $carName = $database->query("SELECT name FROM ".TBL_CARS." WHERE id = :cid", array(':cid' => $car->cid))->fetchObject()->name;
                                ?>
                                <option value="<?=$car->id; ?>"><?=$carName; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                        <input type="hidden" name="rid" value="<?=$race->id; ?>">
                        <input type="submit" value="Start" name="start_race">
                    </form>
                </td>
                <?php if ($race->uid == $user->id) { ?>
                    <td>
                        <a href="crime/streetrace?delete-race=<?=$race->id; ?>">Delete</a>
                    </td>
                <?php } ?>
            </tr>
        <?php
        }
        ?>
    </table>
</form>
<br>
<form method="post">
    <table width="100%">
        <tr>
            <td>
                Car:
            </td>
            <td>
                <select name="race_car">
                    <?php
                    foreach($user->cars() as $car) {
                        $carName = $database->query("SELECT name FROM ".TBL_CARS." WHERE id = :cid", array(':cid' => $car->cid))->fetchObject()->name;
                    ?>
                    <option value="<?=$car->id; ?>"><?=$carName; ?></option>
                    <?php
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                Bet:
            </td>
            <td>
                <input type="number" name="race_bet" placeholder="Bet in <?=$settings->currencySymbol(); ?>">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" value="Create race" name="create_race">
            </td>
        </tr>
    </table>
</form>