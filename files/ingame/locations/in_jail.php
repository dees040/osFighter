<?php
    echo $error->errorBig("You're in jail for <time class='timer'>".($user->time->jail - time())."</time> seconds. <div class=reload'></div>");