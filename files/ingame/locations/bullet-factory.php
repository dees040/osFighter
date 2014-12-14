<?php
echo $validator->getVal('buy_bullets');
?>
<table width='100%'>
    <tr>
        <td valign='top'>
            Welcome in bullet factory  <?=$info['title']; ?> located in <?=$info['cities'][$user->stats->city]; ?>!<br /><br />
        </td>
        <td>
            <img src='files/images/extra/bullet_factory.jpg' align='right' border='1' width="200px" height="125px">
        </td>
    </tr>
</table>

<form method="post">
    <table>
        <tr>
            <td>
                Bullets:
            </td>
            <td>
                <input type="number" name="bullets">
            </td>
        </tr>
        <tr>
            <td>
                Price:
            </td>
            <td>
                <?=$settings->currencySymbol(); ?>200
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" value="Buy" name="buy_bullets">
            </td>
        </tr>
    </table>
</form>