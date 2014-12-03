<?php
    $healCost = (100 - $user->stats->health) * 100;

    echo $validator->getVal('heal');
?>
<table width='100%'>
    <tr>
        <td valign='top'>
            Welcome in hospital <?=$info['title']; ?> located in <?=$info['cities'][$user->stats->city]; ?>!<br /><br />
            We in hospital <?=$info['title']; ?> are specialized in gunshot and stab wounds.
            We will help you heal for : <?=$info['currency']; ?>100 per percent. So for you it will cost <?=$info['currency'].$healCost; ?>.
        </td>
        <td>
            <img src='files/images/extra/hospital.jpg' align='right' border='1' width="200px" height="125px">
        </td>
    </tr>
</table>

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