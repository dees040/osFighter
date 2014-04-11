<?php
    echo "<h1>Logged In</h1>";
    echo "Welcome <b>$session->username</b>, you are logged in. <br><br>"
        ."[<a href=\"account?user=$session->username\">My Account</a>] &nbsp;&nbsp;"
        ."[<a href=\"edit-account\">Edit Account</a>] &nbsp;&nbsp;"
        ."[<a href=\"core/process.php\">Logout</a>]";