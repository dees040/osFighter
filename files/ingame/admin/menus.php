<form method="post">
    Menu :
    <select name="category" class="category">
        <option value="personal">Personal</option>
        <option value="call-credits">Call Credits</option>
        <option value="family">Family</option>
        <option value="extra">Extra</option>
        <option value="crime">Crime</option>
        <option value="locations">Locations</option>
        <option value="casino">Casino</option>
        <option value="admin">Admin</option>
    </select>
    <input type="submit" value="Get menu">
</form>

<?php
    if (isset($_POST['save-menu-items'])) {
        array_pop($_POST);
        $admin->saveMenuItems($_POST);
    }
    if (isset($_POST['category'])) {
        $items = array(':menu' => $_POST['category']);
        $query = $database->query(
            "SELECT menus.weight, pages.title, menus.id FROM ".TBL_MENUS." INNER JOIN pages ON menus.pid = pages.id WHERE menus.menu = :menu ORDER BY menus.weight", $items
        );
        $items = $query->fetchAll();
?>
    You can drag the menu items into diffrent orders.
    <form method="post">
        <?php
            if ($query->rowCount()) {
                echo "<ul class='sortable'>";
                foreach($items as $menu) {
                    echo "<li class='sort'>".$menu['weight'].": <input type='text' name='".$menu['id']."' value='".$menu['title']."' readonly></li>";
                }
                echo "</ul>";

                echo '<input type="submit" value="Save" name="save-menu-items">';
            } else {
                echo "<strong>Empty menu</strong>";
            }
        ?>
    </form>
    <style>
        .sort {
            padding: 5px;
            display: block;
            width: 300px;
            background: #dddddd;
        }
    </style>
    <script>
        $('.sortable').sortable();
    </script>
<?php
    }
?>