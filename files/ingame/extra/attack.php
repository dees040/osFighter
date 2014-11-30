<?php
    if (isset($_POST['attack_player'])) {
        echo $user->attackPlayer($_POST['user'], $_POST['bullets']);
    }
?>
<form method="post">
    <table>
        <tr>
            <td>
                Player to attack:
            </td>
            <td>
                <input type="text" name="user" value="<?=$_GET['player']; ?>" placeholder="Username">
            </td>
        </tr>
        <tr>
            <td>
                Bullets:
            </td>
            <td>
                <input type="number" name="bullets" max="<?=$user->stats->bullets; ?>" placeholder="Bullets to use">
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