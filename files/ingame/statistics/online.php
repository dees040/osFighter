<?php
    $users = $database->query("SELECT * FROM ".TBL_ACTIVE_USERS." ORDER BY timestamp");
?>

<table>
    <?php
        foreach($users as $item) {
            echo "<tr>";
            echo "<td>User: </td><td>".$item['username']."</td>";
            echo "<td>Since: </td><td>".date("Y-m-d H:i", $item['timestamp'])."</td>";
            echo "</tr>";
        }
    ?>
</table>