<?php

class admin
{
    public $errorArray;
    public $reportArray;

    /**
     * Class constructer
     */
    function __construct() {
        $this->errorArray  = array();
        $this->reportArray = array();
    }

    /**
     * Form checker for form file-system create page
     * @param $info
     * @return array returns array with error's, returns empty array when 0 errors
     */
    public function fileSystemCreateForm($info) {
        global $database;

        $this->checkFileSystemForm($info['title'], $info['link'], $info['file'], $info['category'], true);

        if (!empty($this->errorArray)) {
            return true;
        }

        $groupsArray = $this->updateArray($info);

        $items = array(
            ':title'  => $info['title'],
            ':link'   => $info['link'],
            ':file'   => $info['file'],
            ':groups' => serialize($groupsArray),
            ':jail'   => isset($_POST['jail']) ? '0' : '1'
        );

        $database->insert("INSERT INTO ".TBL_PAGES." set title = :title, link = :link, file = :file, groups = :groups, jail = :jail", $items);

        $items = array(':link' => $info['link']);
        $query = $database->select("SELECT id FROM ".TBL_PAGES." WHERE link = :link", $items);
        $pid   = $query->fetchObject()->id;

        $items = array(':pid' => $pid, ':link' => $info['link'], ':menu' => $info['category']);
        $database->insert("INSERT INTO ".TBL_MENUS." SET pid = :pid, link = :link, menu = :menu", $items);

        return false;
    }

    /**
     * Form checker for form file-system edit page
     * @param $info
     * @return array returns array with error's, returns empty array when 0 errors
     */
    public function fileSystemEditForm($info) {
        global $database;

        $this->checkFileSystemForm($info['title'], $info['link'], $info['file'], $info['category'], false);

        if (!empty($this->errorArray)) {
            return true;
        }

        $groupsArray = $this->updateArray($info);

        $items = array(
            ':title'  => $info['title'],
            ':link'   => $info['link'],
            ':file'   => $info['file'],
            'id'      => $_SESSION['get-page-id'],
            ':groups' => serialize($groupsArray),
            ':jail'   => isset($_POST['jail']) ? '0' : '1'
        );
        $database->update("UPDATE ".TBL_PAGES." set title = :title, link = :link, file = :file, groups = :groups, jail = :jail WHERE id = :id", $items);

        $items = array(':pid' => $_SESSION['get-page-id'], ':link' => $info['link'], ':menu' => $info['category']);
        $database->update("UPDATE ".TBL_MENUS." SET link = :link, menu = :menu WHERE pid = :pid", $items);

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
    private function checkFileSystemForm($title, $link, $file, $category, $createPage) {
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

    private function updateArray($array) {
        array_shift($array);
        array_shift($array);
        array_shift($array);
        array_shift($array);
        array_pop($array);
        if (isset($_POST['jail'])) array_pop($array);

        return $array;
    }

    /**
     * @param $menuItems
     */
    public function saveMenuItems($menuItems) {
        global $database;

        $weight = 0;
        foreach($menuItems as $key => $val) {
            $items = array(':id' => $key, ':weight' => $weight);
            $database->update("UPDATE ".TBL_MENUS." SET weight = :weight WHERE id = :id", $items);
            $weight++;
        }
    }

    /**
     * Function that save settings
     * @param $items array of setting items
     * @return bool
     */
    public function saveSettings($items) {
        global $database;

        foreach($items as $key => $item) {
            if (!$item) $this->errorArray[] = " - ".$key." may not be empty!";
        }

        if (!empty($this->errorArray)) return false;

        foreach($items as $key => $val) {
            $database->updateConfigs($val, $key);
        }

        return true;
    }

    /**
     * Function that updates ranks or cities
     * @param $items
     * @param $field
     * @internal param array $ranks of new ranks
     */
    public function saveRanksCities($items, $field) {
        global $database;

        $newItems   = array();
        $itemNumber = 0;

        foreach($items as $item) {
            if (empty($item) || !$item) continue;

            $newItems[$itemNumber] = $item;
            $itemNumber++;
        }

        $database->updateConfigs(serialize($newItems), $field);
    }
}