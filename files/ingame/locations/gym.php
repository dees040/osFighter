<?php
echo $validator->getVal('train_gym');
?>
<form method="post">
    <table width="100%">
        <tr>
            <td colspan="5" align="center" width="100%">
                <strong>
                    Gym process<br>
                    <div style="width: 100%; background-color: #008000;"><div style="width: <?=$user->stats->gym; ?>%; height: 100%; background-color: #00ff00;">&nbsp;<?=$user->stats->gym; ?>%</div></div>
                </strong>
            </td>
        </tr>
        <?php if ($user->time->gym_time > time()) { ?>
            <tr>
                <td colspan="5" align="center">
                    <strong>Wait <time class='timer'><?=($user->time->gym_time - time()); ?></time> seconds before you can train.</strong>
                </td>
            </tr>
        <?php } else { ?>
        <tr>
            <td align="center" colspan="3">
                <strong>Train</strong>
            </td>
            <td align="center" colspan="2">
                <strong>Time</strong>
            </td>
        </tr>
        <tr>
            <td width="5%">
                <input type="radio" name="train_type" value="1">
            </td>
            <td width="5%">
                <img src="files/images/icons/information.png">
            </td>
            <td>
                Legs
            </td>
            <td width="5%">
                <img src="files/images/icons/clock.png">
            </td>
            <td>
                2 minutes
            </td>
        </tr>
        <tr>
            <td width="5%">
                <input type="radio" name="train_type" value="2">
            </td>
            <td width="5%">
                <img src="files/images/icons/information.png">
            </td>
            <td>
                Biceps
            </td>
            <td width="5%">
                <img src="files/images/icons/clock.png">
            </td>
            <td>
                5 minutes
            </td>
        </tr>
        <tr>
            <td width="5%">
                <input type="radio" name="train_type" value="3">
            </td>
            <td width="5%">
                <img src="files/images/icons/information.png">
            </td>
            <td>
                Chest
            </td>
            <td width="5%">
                <img src="files/images/icons/clock.png">
            </td>
            <td>
                10 minutes
            </td>
        </tr>
        <tr>
            <td colspan="5">
                <input type="submit" value="Train" name="train_gym">
            </td>
        </tr>
        <?php } ?>
    </table>
</form>