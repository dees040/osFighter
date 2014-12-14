<table width="100%">
<?php
$i = 0;
foreach($info['ranks'] as $rank) {
    $i++;
?>
    <tr>
        <td>
            #<?=$i; ?>
        </td>
        <td>
            <?=$rank; ?>
        </td>
    </tr>
<?php } ?>
</table>