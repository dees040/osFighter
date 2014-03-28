<?php

include("session.php");

class initialize
{
    var $url;
    var $home_dir;

    /**
     * Class constructor
     * Check if the given url exsits and the user has permission to see it.
     * @param $info: url and path info
     */
    function initialize($info) {
        $this->url      = $info['url'];
        $this->home_dir = $info['path'];

        if (is_object($linkInfo = $this->pageExsits())) {
            if ($this->hasPermissions($linkInfo->groups)) {
                $info = $linkInfo;

            } else {
                // Include 403
            }
        } else {
            // Include 404
        }
    }

    /**
     * Functions to check if page exsits
     * @return bool|mixed
     */
    function pageExsits() {
        global $database;

        $items = array(':url' => $this->url);
        $link = $database
                    ->select("SELECT * FROM ".TBL_PAGES." WHERE link = :url", $items)
                    ->fetchObject();

        if (is_object($link)) {
            return $link;
        }

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

        foreach($groupsArray as $groupID) {
            if ($session->isUserGroup($groupID)) {
                return true;
            }
        }

        return false;
    }
}

$initialize = new initialize($info);