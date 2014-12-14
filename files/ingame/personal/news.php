<?php

$news = $database->query("SELECT * FROM ".TBL_NEWS." ORDER BY date DESC")->fetchAll(PDO::FETCH_OBJ);

foreach($news as $new) {

    echo "<h3>".$new->title."</h3>";
    if ($new->show_creator == 1) {
        $username = $database->getUserInfoById($new->uid)->username;
        echo "Created by <a href='personal/user-info?user=".$username."'>".$username."</a> ";
    }
    echo "on ".date("Y-m-d H:i", $new->date)."<br><br>";
    echo substr($new->content, 0, 200)."...";
}
