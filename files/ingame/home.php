<?php
    echo "<h1>Logged In</h1>";
    echo "Welcome <b>$session->username</b>, you are logged in. <br><br>"
        ."[<a href=\"userinfo.php?user=$session->username\">My Account</a>] &nbsp;&nbsp;"
        ."[<a href=\"useredit.php\">Edit Account</a>] &nbsp;&nbsp;";
    if($session->isAdmin()){
        echo "[<a href=\"admin/index.php\">Admin Center</a>] &nbsp;&nbsp;";
    }
    echo "[<a href=\"process.php\">Logout</a>]";