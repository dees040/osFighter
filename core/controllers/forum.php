<?php

class Forum
{

    public function getForums()
    {
        global $database;

        return $database
            ->query("SELECT * FROM ".TBL_FORUMS." ORDER BY title")
            ->fetchAll(PDO::FETCH_OBJ);
    }

    public function getTopics($fid)
    {
        global $database;

        $items = array(':fid' => $fid);
        return $database
            ->query("SELECT * FROM ".TBL_TOPICS." WHERE fid = :fid ORDER BY date DESC", $items)
            ->fetchAll(PDO::FETCH_OBJ);
    }

    public function getReactions($tid)
    {
        global $database;

        $items = array(':tid' => $tid);
        return $database
            ->query("SELECT * FROM ".TBL_REACTIONS." WHERE tid = :tid ORDER BY date ASC", $items)
            ->fetchAll(PDO::FETCH_OBJ);
    }

    public function getTopic($tid)
    {
        global $database;

        $items = array(':tid' => $tid);
        return $database
            ->query("SELECT * FROM ".TBL_TOPICS." WHERE id = :tid", $items)
            ->fetchObject();
    }

    public function createTopic($title, $content)
    {
        global $database, $user, $error;

        if (strlen($title) < 3) {
            return $error->errorSmall("Title need to have a minimum of 3 characters.");
        }

        if (strlen($title) > 50) {
            return $error->errorSmall("Title has a maximum of 50 characters.");
        }

        if (!ctype_alnum($title)) {
            return $error->errorSmall("Title may only contain a-z, A-Z and 0-9");
        }

        if (strlen($content) < 20) {
            return $error->errorSmall("Message need to have a minimum of 20 characters.");
        }

        if (strlen($content) > 3000) {
            return $error->errorSmall("Message has a maximum of 3000 characters.");
        }

        $items = array(':uid' => $user->id, ':fid' => $_GET['id'], ':date' => time(), ':title' => $title, ':con' => $content);
        $database->query("INSERT INTO ".TBL_TOPICS." SET creator = :uid, fid = :fid, date = :date, title = :title, content = :con", $items);

        return $error->succesSmall("Your topic has been created with success.");
    }

    public function createReaction($content)
    {
        global $error, $database, $user;

        if ($this->getLatestReactionUid($_GET['id']) == $user->id) {
            return $error->errorSmall("You may not create 2 reaction's next to each other.");
        }

        if (strlen($content) < 20) {
            return $error->errorSmall("Message need to have a minimum of 20 characters.");
        }

        if (strlen($content) > 2000) {
            return $error->errorSmall("Message has a maximum of 3000 characters.");
        }

        $items = array(':tid' => $_GET['id'], ':uid' => $user->id, ':content' => $content, ':date' => time());
        $database->query("INSERT INTO ".TBL_REACTIONS." SET tid = :tid, uid = :uid, content = :content, date = :date", $items);

        return $error->succesSmall("Your reaction has been created.");
    }

    public function createForum($title, $description)
    {
        global $error, $database;

        if (strlen($title) < 3) {
            return $error->errorSmall("Title need to have a minimum of 3 characters.");
        }

        if (strlen($title) > 50) {
            return $error->errorSmall("Title has a maximum of 50 characters.");
        }

        if (strlen($description) < 3) {
            return $error->errorSmall("Description need to have a minimum of 3 characters.");
        }

        if (strlen($description) > 200) {
            return $error->errorSmall("Description has a maximum of 200 characters.");
        }

        $items = array(':title' => $title, ':desc' => $description);
        $database->query("INSERT INTO ".TBL_FORUMS." SET title = :title, description = :desc", $items);

        return $error->succesSmall("Forum has been created.");
    }

    public function getLatestTopic($fid)
    {
        global $database;

        return $database->query("SELECT * FROM ".TBL_TOPICS." WHERE fid = :fid ORDER BY date DESC", array(':fid' => $fid))->fetchObject();
    }

    public function getLatestReactionUid($tid)
    {
        global $database;

        return $database->query("SELECT uid FROM ".TBL_REACTIONS." WHERE tid = :tid ORDER BY date DESC", array(':tid' => $tid))->fetchObject()->uid;
    }

    public function postAmount($uid = null)
    {
        global $database, $user;

        $items = array(':uid' => ($uid == null) ? $user->id : $uid);
        $reactions = $database->query("SELECT id FROM ".TBL_REACTIONS." WHERE uid = :uid", $items)->rowCount();
        $topics    = $database->query("SELECT id FROM ".TBL_TOPICS." WHERE creator = :uid", $items)->rowCount();

        return ($reactions + $topics);
    }

}