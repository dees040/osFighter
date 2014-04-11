<?php

class admin
{
    var $errorArray;
    var $reportArray;

    function admin() {
        $this->errorArray  = array();
        $this->reportArray = array();
    }

    /**
     * Form checker for form file-system create page
     * @param $title
     * @param $link
     * @param $file
     * @param $category
     * @return array returns array with error's, returns empty array when 0 errors
     */
    function fileSystemCreateForm($title, $link, $file, $category) {
        global $database;

        $this->checkFileSystemForm($title, $link, $file, $category, true);

        if (!empty($this->errorArray)) {
            return true;
        }

        $items = array(':title' => $title, ':link' => $link, ':file' => "ingame/".$category."/".$file);
        $database->insert("INSERT INTO ".TBL_PAGES." set title = :title, link = :link, file = :file", $items);

        $items = array(':link' => $link);
        $query = $database->select("SELECT id FROM ".TBL_PAGES." WHERE link = :link", $items);
        $pid   = $query->fetchObject()->id;

        $items = array(':pid' => $pid, ':link' => $link, ':menu' => $category);
        $database->insert("INSERT INTO ".TBL_MENUS." SET pid = :pid, link = :link, menu = :menu", $items);

        return false;
    }

    /**
     * Form checker for form file-system edit page
     * @param $title
     * @param $link
     * @param $file
     * @param $category
     * @return array returns array with error's, returns empty array when 0 errors
     */
    function fileSystemEditForm($title, $link, $file, $category) {
        global $database;

        $this->checkFileSystemForm($title, $link, $file, $category, false);

        if (!empty($this->errorArray)) {
            return true;
        }

        $items = array(':title' => $title, ':link' => $link, ':file' => "ingame/".$category."/".$file, 'id' => $_SESSION['get-page-id']);
        $database->insert("UPDATE ".TBL_PAGES." set title = :title, link = :link, file = :file WHERE id = :id", $items);

        $items = array(':pid' => $_SESSION['get-page-id'], ':link' => $link, ':menu' => $category);
        $database->insert("UPDATE ".TBL_MENUS." SET link = :link, menu = :menu WHERE pid = :pid", $items);

        unset($_SESSION['get-page-id']);

        return false;
    }

    /**
     * Check form values
     * @param $title
     * @param $link
     * @param $file
     * @param $category
     * @param $createPage returns array with error's, returns empty array when 0 errors
     */
    function checkFileSystemForm($title, $link, $file, $category, $createPage) {
        global $database;

        // Error checking for title
        if (!$title) {
            $this->errorArray[] = " - Please fill in a title.";
        } else {
            if ($createPage) {
                $items = array(':title' => $title);
                $query = $database->select("SELECT id FROM ".TBL_PAGES." WHERE title = :title", $items);
            } else {
                $items = array(':title' => $title, ':id' => $_SESSION['get-page-id']);
                $query = $database->select("SELECT id FROM ".TBL_PAGES." WHERE title = :title AND id != :id", $items);
            }

            if ($query->rowCount()) $this->reportArray[] = " - Title already exists.";
        }

        if (!$link) {
            $this->errorArray[] = " - Please fill in a link.";
        } else {
            if ($createPage) {
                $items = array(':link' => $link);
                $query = $database->select("SELECT id FROM ".TBL_PAGES." WHERE link = :link", $items);
            } else {
                $items = array(':link' => $link, ':id' => $_SESSION['get-page-id']);
                $query = $database->select("SELECT * FROM ".TBL_PAGES." WHERE link = :link AND id != :id", $items);
            }

            if ($query->rowCount()) $this->errorArray[] = " - Link already exists.";
        }

        if (!$file) {
            $this->errorArray[] = " - Please fill in a file.";
        } else {
            if ($createPage) {
                $items = array(':file' => $file);
                $query = $database->select("SELECT id FROM ".TBL_PAGES." WHERE file = :file", $items);
            } else {
                $items = array(':file' => $file, ':id' => $_SESSION['get-page-id']);
                $query = $database->select("SELECT * FROM ".TBL_PAGES." WHERE file = :file AND id != :id", $items);
            }

            if ($query->rowCount()) {
                $this->reportArray[] = " - Another page already use this file.";
            } else {
                if (!file_exists("files/ingame/".$category."/".$file)) {
                    $this->reportArray[] = " - Because the file not exists yet, i created it for you.";
                    touch("files/ingame/".$category."/".$file);
                }
            }
        }
    }
}

$admin = new admin;