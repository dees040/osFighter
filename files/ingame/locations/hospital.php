<?php
    $healCost = (100 - $user->stats->health) * 100;

    if (isset($_POST['heal'])) {
        if ($user->healInHospital()) {
            echo $error->succesSmall("You health yourself!");
        } else {
            echo $error->errorSmall("You don't have enough money in cash.");
        }
    }
?>
Heal yourself! 1% health costs <?=$info['currency']; ?>100.

<form method="post">
    <table>
        <tr>
            <td>
                Heal yourself for: <?=$info['currency'].$healCost; ?>
            </td>
            <td>
                <input type="submit" value="Heal yourself!" name="heal">
            </td>
        </tr>
    </table>
</form>