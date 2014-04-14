<ul class="tabs" id="tab"  data-persist="true">
    <li><a href="#tab1">Main Settings</a></li>
    <li><a href="#tab2">Ranks</a></li>
    <li><a href="#tab3">Edit</a></li>
</ul>

<div class="tabcontents inhoud">
    <!-- settings page tab1: Main Settings-->
    <div id="tab1">
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
    </div>

    <!-- settings page tab2: Rank Settings-->
    <div id="tab2">
        <?php
            if (isset($_POST['save-ranks-form'])) {
                array_pop($_POST);

                $admin->saveRanks($_POST);
            }

            $configs = $database->getConfigs();
        ?>
        <form method="post">
            <table>
                <?php
                    foreach(unserialize($configs['RANKS']) as $key => $rank) {
                        echo '<tr>';
                        echo '<td>'.$key.': </td>';
                        echo '<td><input type="text" name="'.$key.'" value="'.$rank.'"></td>';
                        echo '</tr>';
                    }
                ?>
                <tr>
                    <td>
                        <?=count(unserialize($configs['RANKS'])) ;?>:
                    </td>
                    <td>
                        <input type="text" placeholder="New rank" name="new-rank">
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" value="Save ranks!" name="save-ranks-form"></td>
                </tr>
            </table>
        </form>
    </div>
</div>