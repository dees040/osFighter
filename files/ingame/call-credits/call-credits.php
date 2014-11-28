<?php
    if (isset($_GET['buy'])) {
        echo "Loading...";
        $payclass->buyCredits(intval($_GET['buy']));
    }
?>

<table width="100%">
    <tr>
        <td>
            1 Credit for <?=$settings->currencySymbol(); ?> 0.2
        </td>
        <td>
            <a href="call-credits?buy=1">Buy</a>
        </td>
    </tr>
    <tr>
        <td>
            5 Credits for <?=$settings->currencySymbol(); ?> 1
        </td>
        <td>
            <a href="call-credits?buy=5">Buy</a>
        </td>
    </tr>
    <tr>
        <td>
            10 Credits for <?=$settings->currencySymbol(); ?> 2
        </td>
        <td>
            <a href="call-credits?buy=10">Buy</a>
        </td>
    </tr>
    <tr>
        <td>
            50 Credits for <?=$settings->currencySymbol(); ?> 10
        </td>
        <td>
            <a href="call-credits?buy=50">Buy</a>
        </td>
    </tr>
    <tr>
        <td>
            100 Credits for <?=$settings->currencySymbol(); ?> 20
        </td>
        <td>
            <a href="call-credits?buy=100">Buy</a>
        </td>
    </tr>
</table>