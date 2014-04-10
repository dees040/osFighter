<?php

include("session.php");

class initialize
{
    var $url;
    var $home_dir;
    var $link_info;
    var $base;

    /**
     * Class constructor
     * Check if the given url exsits and the user has permission to see it.
     * @param $infoSets: url and path info
     */
    function initialize($infoSets) {
        $this->url      = $infoSets['url'];
        $this->home_dir = $infoSets['path'];
        $this->base     = $infoSets['base'];

        if (is_object($this->link_info = $this->pageExists())) {
            if ($this->hasPermissions($this->link_info->groups)) {
                $info = $this->getInfoArray();

                global $session, $form, $database, $admin;
                include 'themes/'.$info['theme'].'/'.$info['file'];
            } else {
                include 'files/http/403.php';
            }
        } else {
            include 'files/http/404.php';
        }
    }

    /**
     * Functions to check if page exsits
     * @return bool|object
     */
    function pageExists() {
        global $database;

        if (substr($this->url, -1) == "/") {
            $this->url = substr($this->url, 0, -1);
        }

        $items = array(':url' => $this->url);
        $link = $database
            ->select("SELECT * FROM ".TBL_PAGES." WHERE link = :url", $items)
            ->fetchObject();

        if (is_object($link) && $this->checkFileExsits($link->file)) return $link;

        return false;
    }

    /**
     * Function to check if user has permissions to watch the page
     * @param $groups serialized array of groups
     * @return bool
     */
    function hasPermissions($groups) {
        global $session;

        $groupsArray = unserialize($groups);

        if (empty($groupsArray)) return true;

        foreach($groupsArray as $groupID) {
            if ($session->isUserGroup($groupID)) return true;
        }

        return false;
    }

    /**
     * Function to check if file exsits in files system
     * @param $path path to file which need to be checked
     * @return bool
     */
    function checkFileExsits($path) {
        global $session;

        if ($session->logged_in && file_exists("files/".$path)) {
            return true;
        } else if (file_exists("files/".$path)) {
            return true;
        }

        return false;
    }

    /**
     * Function that return theme file by user loggin_in status
     * @return string
     */
    function getThemeFile() {
        global $session;

        if ($session->logged_in) return "ingame.php";

        return "outgame.php";
    }

    /**
     * Function that returns all menu items from the database
     * and checks if user has permissions to get on the page
     * that menu link goes to
     * @return array
     */
    function getMenus() {
        global $database;

        $menuItems = array();
        $query = $database->select("SELECT * FROM ".TBL_MENUS);

        foreach($query as $menuItem) {
            $items = array('pid' => $menuItem['pid']);
            $page = $database->select("SELECT groups, title, link FROM ".TBL_PAGES." WHERE id = :pid", $items);

            if ($page->rowCount() == 0) continue;

            $pageInfo = $page->fetch();

            if (!$this->hasPermissions($pageInfo['groups'])) continue;

            $menuItems[$menuItem['menu']][] = $pageInfo;
        }

        return $menuItems;
    }

    /**
     * Function that returns all page info needed for rendering a page
     * @return array with (page) info
     */
    function getInfoArray() {
        global $database;
        $configs = $database->getConfigs();

        return array(
            'link'     => $this->link_info,
            'theme'    => $configs['ACTIVE_THEME'],
            'title'    => $configs['SITE_NAME'],
            'file'     => $this->getThemeFile(),
            'menu'     => $this->getMenus(),
            'base'     => $this->base
        );
    }
}

$initialize = new initialize($info);