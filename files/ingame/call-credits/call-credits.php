<?php
    if (isset($_GET['buy'])) {
        echo "Loading...";
        $payclass->buyCredits(intval($_GET['buy']));
    }
?>

<table width="100%">
    <tr>
        <td>
            1 Credit
        </td>
        <td>
            <a href="call-credits?buy=1">Buy</a>
        </td>
    </tr>
    <tr>
        <td>
            5 Credits
        </td>
        <td>
            <a href="call-credits?buy=5">Buy</a>
        </td>
    </tr>
    <tr>
        <td>
            10 Credits
        </td>
        <td>
            <a href="call-credits?buy=10">Buy</a>
        </td>
    </tr>
    <tr>
        <td>
            50 Credits
        </td>
        <td>
            <a href="call-credits?buy=50">Buy</a>
        </td>
    </tr>
    <tr>
        <td>
            100 Credits
        </td>
        <td>
            <a href="call-credits?buy=100">Buy</a>
        </td>
    </tr>
</table>