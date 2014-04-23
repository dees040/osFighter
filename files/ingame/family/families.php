<?php
    foreach($database->paginate(TBL_FAMILY, "power", 0, 10) as $family) {
        echo $family['name'];
    }