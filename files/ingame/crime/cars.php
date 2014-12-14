<?php

echo $validator->getVal('car_hijacking');

if ($user->time->car_time > time()) {
    echo $error->errorBig("You can do the next car hijacking in <time class='timer'>".($user->time->car_time - time())."</time> seconds.");
} else {
    ?>
    <form method="post" name="captcha-form">
        <table align="center" width="100%" border="0">
            <tr>
                <td width="5%">
                    <input type="radio" name="type" value="1">
                </td>
                <td>
                    Steal the keys in a restaurant.
                </td>
            </tr>
            <tr>
                <td width="5%">
                    <input type="radio" name="type" value="2">
                </td>
                <td>
                    Smash a window in a parking lot.
                </td>
            </tr>
            <tr>
                <td width="5%">
                    <input type="radio" name="type" value="3">
                </td>
                <td>
                    Steal the keys from a vehicle from a bag.
                </td>
            </tr>
            <tr>
                <td width="5%">
                    <input type="radio" name="type" value="4">
                </td>
                <td>
                    Steal a parked vehicle.
                </td>
            </tr>
            <tr>
                <td width="5%">
                    <input type="radio" name="type" value="5">
                </td>
                <td>
                    Steal a vehicle from a boat.
                </td>
            </tr>
        </table>
        <?php
        echo $settings->createCaptcha();
        ?>
        <input type="hidden" name="car_hijacking" value="true">
    </form>
<?php
}