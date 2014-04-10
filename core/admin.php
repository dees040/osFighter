<?php

class admin
{
    function admin() {

    }

    function fileSystemCreateForm($title, $link, $file, $category) {
        global $database;
        $errorArray = array();
        $reportArray = array();

        // Error checking for title
        if (!$title) {
            $errorArray[] = " - Please fill in a title.";
        } else {
            $items = array(':title' => $title);
            $query = $database->select("SELECT * FROM ".TBL_PAGES." WHERE title = :title", $items);

            if ($query->rowCount()) $reportArray[] = " - Title already exists.";
        }

        if (!$link) {
            $errorArray[] = " - Please fill in a link.";
        } else {
            $items = array(':link' => $link);
            $query = $database->select("SELECT id FROM ".TBL_PAGES." WHERE link = :link", $items);

            //if ($query->rowCount()) $errorArray[] = " - Link already exists.";
        }

        if (!$file) {
            $errorArray[] = " - Please fill in a file.";
        } else {
            $items = array(':file' => $file);
            $query = $database->select("SELECT id FROM ".TBL_PAGES." WHERE file = :file", $items);

            if ($query->rowCount()) {
                $reportArray[] = " - Another page already use this file.";
            } else {
                if (!file_exists("files/ingame/".$category."/".$file)) {
                    $reportArray[] = " - Because the file not exists yet, i created it for you.";
                    touch("files/ingame/".$category."/".$file);
                }
            }
        }

        if (!empty($errorArray)) {
            return $errorArray;
        }

        $items = array(':title' => $title, ':link' => $link, ':file' => "ingame/".$category."/".$file);
        $database->insert("INSERT INTO ".TBL_PAGES." set title = :title, link = :link, file = :file", $items);

        $items = array(':link' => $link);
        $query = $database->select("SELECT id FROM ".TBL_PAGES." WHERE link = :link", $items);
        $pid   = $query->fetchObject()->id;

        $items = array(':pid' => $pid, ':link' => $link, ':menu' => $category);
        $database->insert("INSERT INTO ".TBL_MENUS." SET pid = :pid, link = :link, menu = :menu", $items);

        return $reportArray;
    }
}

$admin = new admin;