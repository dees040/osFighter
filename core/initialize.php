<?php

include("session.php");

class initialize
{
    var $url;
    var $home_dir;

    /**
     * Class constructor
     * Check if the given url exsits and the user has permission to see it.
     * @param $infoSets: url and path info
     */
    function initialize($infoSets) {
        global $database;

        $this->url      = $infoSets['url'];
        $this->home_dir = $infoSets['path'];

        if (is_object($linkInfo = $this->pageExsits())) {
            if ($this->hasPermissions($linkInfo->groups)) {
                $info = array(
                    'link'  => $linkInfo,
                    'theme' => $database->getConfigs()['ACTIVE_THEME'],
                    'base'  => $infoSets['base']
                );

                global $session;
                include 'themes/'.$info['theme'].'/ingame.php';
            } else {
                // Include 403
            }
        } else {
            // Include 404
        }
    }

    /**
     * Functions to check if page exsits
     * @return bool|object
     */
    function pageExsits() {
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
}

$initialize = new initialize($info);