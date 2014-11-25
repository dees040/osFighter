<?php
    $users = $database->query("SELECT * FROM ".TBL_ACTIVE_USERS." ORDER BY timestamp DESC");
?>

<table width="100%">
    <tr>
        <td colspan="2" align="center">
            <strong>User</strong>
        </td>
        <td colspan="2" align="center">
            <strong>Since</strong>
        </td>
    </tr>
    <?php
        foreach($users as $item) {
            echo "<tr>";
            echo "<td><img src='files/images/icons/status_online.png'></td>";
            echo "<td><a href='personal/user-info?user=".$item['username']."'>".$item['username']."</a></td>";
            echo "<td><img src='files/images/icons/clock.png'></td>";
            echo "<td>".date("Y-m-d H:i", $item['timestamp'])."</td>";
            echo "</tr>";
        }
    ?>
</table>