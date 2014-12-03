<?php
    echo $validator->getVal('pimp_ho');
    echo $validator->getVal('put_from_street');
    echo $validator->getVal('get_ho_cash');
?>
<table width='100%'>
    <tr>
        <td valign='top'>
            Welcome in the Red Light District of <?=$info['title']; ?>!<br /><br />
            Every 10 minutes you can pimp ho's from the street. Foreach ho working back the glass on the RLD you will get <?=$settings->currencySymbol(); ?>60 per hour. So for 10 ho's you will get <?=$settings->currencySymbol(); ?>600 per hour.
        </td>
        <td>
            <img src='files/images/extra/redlightdistrict.jpg' align='right' border='1' width="200px" height="125px">
        </td>
    </tr>
</table>

<table width="100%">
    <tr>
        <td width="50%">
            Ho's on the street
        </td>
        <td colspan="2">
            <?=$user->stats->ho_street; ?>
        </td>
    </tr>
    <tr>
        <td>
            Ho's on the red light district
        </td>
        <td colspan="2">
            <?=$user->stats->ho_glass; ?>
        </td>
    </tr>
    <tr>
        <td>
            Pimp ho's from street
        </td>
        <td colspan="2">
            <form method="post">
                <input type="submit" name="pimp_ho" value="Pimp ho's">
            </form>
        </td>
    </tr>
    <tr>
        <td>
            Put ho's on Red Light District
        </td>
        <td colspan="2">
            <form method="post">
                <input type="submit" name="put_from_street" value="Put ho's on RLS">
            </form>
        </td>
    </tr>
    <tr>
        <td>
            Cash earned:
        </td>
        <td>
            <?=$settings->currencySymbol().$settings->createFormat($user->moneyEarnedFromHo()); ?>
        </td>
        <td>
            <form method="post">
                <input type="submit" name="get_ho_cash" value="Get cash">
            </form>
        </td>
    </tr>
</table>
