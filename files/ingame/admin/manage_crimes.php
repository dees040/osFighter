<ul class="tabs" id="tab"  data-persist="true">
    <li><a href="#tab1">Manage</a></li>
    <li><a href="#tab2">Create Crime</a></li>
</ul>

<div class="tabcontents inhoud">

    <!-- manage crimes page tab1: -->
    <div id="tab1">
        <?php
            $crimes = $database->select("SELECT * FROM ".TBL_CRIMES." ORDER BY name");
        ?>
        <form method="post">
            <select name="get-crime">
                <?php
                    foreach($crimes as $crime) {
                        $checked = "";
                        if ($crime['id'] == $_POST['get-crime']) $checked = "checked";
                        echo "<option value='".$crime['id']."' ".$checked.">".$crime['name']."</option>";
                    }
                ?>
            </select>
            <input type="submit" value="Get Crime" name="get-crime-item">
        </form>

        <?php
            if (isset($_POST['change-crime-form'])) {
                array_pop($_POST);
                $retval = $admin->updateCrime($_POST, $_FILES);

                if ($retval) {
                    foreach($admin->errorArray as $error) {
                        echo $error."<br>";
                    }
                } else {
                    echo "<strong>Crime updated</strong><br>";
                    if (!empty($admin->reportArray)) foreach($admin->reportArray as $error) echo $error."<br>";
                }
            }
            if (isset($_POST['get-crime-item'])) {
                $_SESSION['get-crime-id'] = $_POST['get-crime'];
                $items = array(':id' => $_POST['get-crime']);
                $crime = $database->select("SELECT * FROM ".TBL_CRIMES." WHERE id = :id", $items)->fetchObject();
        ?>

        <form method="post" enctype="multipart/form-data">
            <table>
                <tr>
                    <td>
                        Name:
                    </td>
                    <td>
                        <input type="text" name="name" value="<?=$crime->name; ?>" placeholder="Crime name">
                    </td>
                </tr>
                <tr>
                    <td>
                        Min payout:
                    </td>
                    <td>
                        <input type="number" name="min-payout" value="<?=$crime->min_payout; ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        Max payout:
                    </td>
                    <td>
                        <input type="number" name="max-payout" value="<?=$crime->max_payout; ?>">
                    </td>
                </tr>
                <tr>
                    <td title="Change = level / change">
                        Change(?):
                    </td>
                    <td>
                        <input type="range" name="change" min="1" max="200" value="<?=$crime->change; ?>" class="input-change" title="Click and/or use arrow keys"> <em class="crime-change-output"></em>
                    </td>
                </tr>
                <tr>
                    <td>
                        Image:
                    </td>
                    <td>
                        <input type="file" name="file" id="file">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" value="Save changes!" name="change-crime-form">
                    </td>
                </tr>
            </table>
        </form>

        <?php
            }
        ?>
    </div>

    <!-- manage crimes page tab2: Create-->
    <div id="tab2">
        <?php
            if (isset($_POST['create-crime-form'])) {
                array_pop($_POST);
                $retval = $admin->createCrime($_POST, $_FILES);

                if ($retval) {
                    foreach($admin->errorArray as $error) {
                        echo $error."<br>";
                    }
                } else {
                    echo "<strong>Crime created!</strong><br>";
                    if (!empty($admin->reportArray)) foreach($admin->reportArray as $error) echo $error."<br>";
                }
            }
        ?>
        <form method="post" enctype="multipart/form-data">
            <table width="100%">
                <tr>
                    <td>
                        Name:
                    </td>
                    <td>
                        <input type="text" name="name" placeholder="Crime name">
                    </td>
                </tr>
                <tr>
                    <td>
                        Min payout:
                    </td>
                    <td>
                        <input type="number" name="min-payout">
                    </td>
                </tr>
                <tr>
                    <td>
                        Max payout:
                    </td>
                    <td>
                        <input type="number" name="max-payout">
                    </td>
                </tr>
                <tr>
                    <td title="Change = level / change">
                        Change(?):
                    </td>
                    <td>
                        <input type="range" name="change" min="1" max="200" class="input-change" title="Click and/or use arrow keys"> <em class="crime-change-output"></em>
                    </td>
                </tr>
                <tr>
                    <td>
                        Image:
                    </td>
                    <td>
                        <input type="file" name="file" id="file">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" value="Create crime" name="create-crime-form">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>