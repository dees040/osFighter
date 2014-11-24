<ul class="tabs" id="tab"  data-persist="true">
    <li><a href="#tab1">Files</a></li>
    <li><a href="#tab2">Create</a></li>
    <li><a href="#tab3">Edit</a></li>
</ul>

<div class="tabcontents inhoud">
    <!-- file-sytem page tab1: Files-->
    <div id="tab1">
        Edit and create files here
    </div>

    <!-- file-sytem page tab2: Create -->
    <div id="tab2">
        <?php
            $groups = $database->query("SELECT * FROM ".TBL_GROUPS." ORDER BY name")->fetchAll();

            if (isset($_POST['submit-create-page'])) {
                $retval = $admin->fileSystemCreateForm($_POST);

                if ($retval) {
                    foreach($admin->errorArray as $error) {
                        echo $error."<br>";
                    }
                } else {
                    echo "<strong>New page created</strong><br>";
                    if (!empty($admin->reportArray)) foreach($admin->reportArray as $error) echo $error."<br>";
                }
            }
        ?>
        <form method="post">
            <table>
                <tr>
                    <td>
                        Title:
                    </td>
                    <td>
                        <input type="text" name="title" maxlength="100" size="30" class="title">
                    </td>
                </tr>
                <tr>
                    <td>
                        Link:
                    </td>
                    <td>
                        <?= $info['base']; ?> <input type="text" name="link" size="30" class="link">
                    </td>
                </tr>
                <tr>
                    <td>
                        File:
                    </td>
                    <td>
                        <input type="text" name="file" size="30" class="file" placeholder="The new filename">
                    </td>
                </tr>
                <tr>
                    <td>
                        Category:
                    </td>
                    <td>
                        <select name="category" class="category">
                            <option value="personal">Personal</option>
                            <option value="call-credits">Call Credits</option>
                            <option value="family">Family</option>
                            <option value="extra">Extra</option>
                            <option value="crime">Crime</option>
                            <option value="locations">Locations</option>
                            <option value="casino">Casino</option>
                            <option value="statistics">Statistics</option>
                            <option value="admin">Admin</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        Permissions:
                    </td>
                    <td>
                        <?php
                            foreach($groups as $group) {
                                echo '<input type="checkbox" value="'.$group['id'].'" name="'.$group['id'].'"> '.$group['name'].'<br>';
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td title="Select this checkbox when user may view this page when in jail">
                        Jail (?):
                    </td>
                    <td>
                        <input type="checkbox" name="jail" value="1">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" value="Create page" style="float: right" name="submit-create-page">
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <!-- file-sytem page tab1: Edit -->
    <div id="tab3">
        <?php
            $pages = $database->query("SELECT * FROM ".TBL_PAGES." ORDER BY title ASC");

            if (isset($_POST['submit-get-page'])) {
                $items = array(':id' => $_POST['page']);
                $query = $database->query("SELECT pages.*, menus.menu FROM ".TBL_PAGES." INNER JOIN menus ON menus.pid = pages.id WHERE pages.id = :id", $items);
                $page  = $query->fetchObject();
                $page->groups = unserialize($page->groups);

                $_SESSION['get-page-id'] = $_POST['page'];
            }

            if (isset($_POST['submit-edit-page'])) {
                $retval = $admin->fileSystemEditForm($_POST);

                if ($retval) {
                    foreach($admin->errorArray as $error) echo $error."<br>";
                } else {
                    echo "<strong>Page edited</strong><br>";
                    if (!empty($admin->reportArray)) foreach($admin->reportArray as $error) echo $error."<br>";
                }
            }
        ?>
        <form method="post">
            <select name="page">
            <?php
                foreach($pages->fetchAll() as $item) {
                    echo "<option value='".$item['id']."'>".$item['title']."</option>";
                }
            ?>
            </select>
            <input type="submit" value="Get page" name="submit-get-page">
        </form>
        <?php if (isset($_POST['submit-get-page'])) { ?>

            <form method="post">
                <table>
                    <tr>
                        <td>
                            Title:
                        </td>
                        <td>
                            <input type="text" name="title" maxlength="100" size="30" class="title" value="<?= $page->title; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Link:
                        </td>
                        <td>
                            <?= $info['base']; ?> <input type="text" name="link" size="30" class="link" value="<?= $page->link; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            File:
                        </td>
                        <td>
                            <input type="text" name="file" size="30" class="file" placeholder="The filename" value="<?= $page->file; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Category:
                        </td>
                        <td>
                            <select name="category" class="category">
                                <?php
                                    $cats = array(
                                        'personal' => 'Personal',
                                        'call-credits' => 'Call Credits',
                                        'family' => 'Family',
                                        'extra' => 'Extra',
                                        'crime' => 'Crime',
                                        'locations' => 'Locations',
                                        'casino' => 'Casino',
                                        'admin' => 'Admin',
                                        'statistics' => 'Statistics'
                                    );

                                    foreach($cats as $key => $cat) {
                                        $active = "";
                                        if ($page->menu == $key) $active = 'selected';
                                        echo '<option value="'.$key.'" '.$active.'>'.$cat.'</option>';
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Permissions:
                        </td>
                        <td>
                            <?php
                            foreach($groups as $group) {
                                $checked = "";
                                if (in_array($group['id'], $page->groups)) {
                                    $checked = "checked";
                                }
                                echo '<input type="checkbox" value="'.$group['id'].'" name="'.$group['id'].'" '.$checked.'> '.$group['name'].'<br>';
                            }
                            ?>
                        </td>
                    </tr>
                    <td title="Select this checkbox when user may view this page when in jail">
                        Jail (?):
                    </td>
                    <td>
                        <input type="checkbox" name="jail" value="1" <?php if(!$page->jail) echo "checked"; ?>>
                    </td>
                    <tr>
                        <td colspan="2">
                            <input type="submit" value="Edit page" style="float: right" name="submit-edit-page">
                        </td>
                    </tr>
                </table>
            </form>

        <?php } ?>
    </div>
</div>