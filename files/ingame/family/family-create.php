<?php
if ($user->stats->fid != 0) {
    echo $error->errorBig("You already are in a family!");
} else {
    if (isset($_POST['create_family'])) {
        echo $useractions->createFamily($_POST['family_name']);
    }
?>
<form method="post">
    <table width="100%">
        <tr>
            <td>
                Family name:
            </td>
            <td>
                <input type="text" name="family_name" placeholder="" maxlength="20">
            </td>
            <td>
                <input type="submit" value="Create family" name="create_family">
            </td>
        </tr>
    </table>
</form>
<?php
}