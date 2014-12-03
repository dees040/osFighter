<?php
echo $validator->getVal('new_forum');
?>
<form method="post">
    <table>
        <tr>
            <td>
                Title:
            </td>
            <td>
                <input type="text" name="title">
            </td>
        </tr>
        <tr>
            <td>
                Description:
            </td>
            <td>
                <input type="text" name="desc">
            </td>
        </tr>
        <tr>
            <td colspan="2  ">
                <input type="submit" value="Create forum" name="new_forum">
            </td>
        </tr>
    </table>
</form>