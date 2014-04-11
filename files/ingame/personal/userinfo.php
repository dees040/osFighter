<?php
    $req_user = $session->username;

    if (isset($_GET['user'])) {
        $req_user = trim($_GET['user']);
    }

    /* Requested Username error checking */
    if (!$req_user || strlen($req_user) == 0 ||
       !preg_match("/^[a-z0-9]([0-9a-z_-\s])+$/i", $req_user) ||
       !$database->usernameTaken($req_user)) {
       echo "Username not registered";
    } else {

        /* Logged in user viewing own account */
        if (strcmp($session->username,$req_user) == 0) {
           echo "<h1>My Account</h1>";
        } else { /* Visitor not viewing own account */
           echo "<h1>User Info</h1>";
        }

        /* Display requested user information - add/delete as applicable */
        $req_user_info = $database->getUserInfo($req_user);

        /* Username */
        echo "<b>Username: ".$req_user_info['username']."</b><br>";

        /* Email */
        echo "<b>Email:</b> ".$req_user_info['email']."<br>";

        /**
         * Note: when you add your own fields to the users table
         * to hold more information, like homepage, location, etc.
         * they can be easily accessed by the user info array.
         *
         * $session->user_info['location']; (for logged in users)
         *
         * $req_user_info['location']; (for any user)
         */

        /* If logged in user viewing own account, give link to edit */
        if (strcmp($session->username,$req_user) == 0) {
           echo '<br><a href="edit-account">Edit Account Information</a><br>';
        }

        /* Link back to main */
        echo "<br>Back To [<a href='home'>Home</a>]<br>";
    }