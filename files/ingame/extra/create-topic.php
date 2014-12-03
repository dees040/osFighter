<?php
    echo $validator->getVal('create_topic');
?>
<form method="post">
    <table width="100%">
        <tr>
            <td>
                Title:
            </td>
            <td>
                <input type="text" name="topic_title">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <textarea name="topic_content"></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" value="Create topic" name="create_topic">
            </td>
        </tr>
    </table>
</form>