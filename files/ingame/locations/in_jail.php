<?php
    echo $error->errorBig("Your in jail for <time class='timer'>".($user->time->jail - time())."</time> seconds.");