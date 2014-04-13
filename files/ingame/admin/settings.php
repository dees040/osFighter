<?php
    if (isset($_POST['update-settings-form'])) {
        global $admin;
        array_pop($_POST);
        $retval = $admin->saveSettings($_POST);

        if ($retval) {
            echo "<strong>Settings saved!</strong>";
        } else {
            foreach($admin->errorArray as $item) echo $item."<br>";
        }
    }

    $configs = $database->getConfigs();
?>

<form method="post">
    <table>
        <tr>
            <th colspan="2">Settings Table</th>
        </tr>
        <tr>
            <td>
                Site name
            </td>
            <td>
                <input type="text" value="<?=$configs['SITE_NAME'] ?>" name="SITE_NAME">
            </td>
        </tr>
        <tr>
            <td>
                Email name
            </td>
            <td>
                <input type="text" value="<?=$configs['EMAIL_FROM_NAME'] ?>" name="EMAIL_FROM_NAME">
            </td>
        </tr>
        <tr>
            <td>
                Email address
            </td>
            <td>
                <input type="text" value="<?=$configs['EMAIL_FROM_ADDR'] ?>" name="EMAIL_FROM_ADDR">
            </td>
        </tr>
        <tr>
            <td>
                User timeout
            </td>
            <td>
                <input type="number" value="<?=$configs['USER_TIMEOUT'] ?>" name="USER_TIMEOUT"> minutes
            </td>
        </tr>
        <tr>
            <td>
                <?=GUEST_NAME; ?> timeout
            </td>
            <td>
                <input type="number" value="<?=$configs['GUEST_TIMEOUT'] ?>" name="GUEST_TIMEOUT"> minutes
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" value="Save settings" name="update-settings-form">
            </td>
        </tr>
    </table>
</form>