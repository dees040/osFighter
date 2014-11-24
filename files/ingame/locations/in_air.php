<?php
    echo $error->errorBig("You're flying for <time class='timer'>".($user->time->fly_time - time())."</time> seconds. <div class=reload'></div>");