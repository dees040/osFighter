<?php
echo $validator->getVal('attack_player');
?>
<form method="post">
    <table>
        <tr>
            <td>
                Player to attack:
            </td>
            <td>
                <input type="text" name="user" value="<?=$_GET['target']; ?>" placeholder="Username">
            </td>
        </tr>
        <tr>
            <td>
                Bullets:
            </td>
            <td>
                <input type="number" name="bullets" min="0" placeholder="Bullets to use">
            </td>
        </tr>
        <tr>
            <td>
                Attack:
            </td>
            <td>
                <input type="submit" value="Go!" name="attack_player">
            </td>
        </tr>
    </table>
</form>