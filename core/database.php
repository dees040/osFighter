<?php
/**
 * Database.php
 *
 * The Database class is used to pull/push info
 * from and to database.
 *
 * By dees040
 * On 08-02-2014
 */
include("constants.php");
      
class Database
{
   private $connection;         //The MySQL database connection
   public $num_active_users;   //Number of active users viewing site
   public $num_active_guests;  //Number of active guests viewing site
   public $num_members;        //Number of signed-up users
   /* Note: call getNumMembers() to access $num_members! */
   
    /* Class constructor */
    public function __construct() {
        /* Make connection to database */
        try {
            # MySQL with PDO_MYSQL
            $this->connection = new PDO('mysql:host='.DB_SERVER.';dbname='.DB_NAME, DB_USER, DB_PASS);
            $this->connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        }
        catch(PDOException $e) {
            echo "Error connecting to database.";
        }

        $this->MySQLDB();
    }

    private function MySQLDB(){
        /**
         * Only query database to find out number of members
         * when getNumMembers() is called for the first time,
         * until then, default value set.
         */
        $this->num_members = -1;
        $config = $this->getConfigs();
        if($config['TRACK_VISITORS']){
            /* Calculate number of users at site */
            $this->calcNumActiveUsers();
            /* Calculate number of guests at site */
            $this->calcNumActiveGuests();
        }
    } // MySQLDB function
   
    /**
     * Gather together the configs from the database configuration table.
     */
    public function getConfigs(){
        $config = array();
        $sql = $this->connection->query("SELECT * FROM ".TBL_CONFIGURATION);
        while($row = $sql->fetch()) {
            $config[$row['config_name']] = $row['config_value'];
        }
        return $config;
    }

    /**
     * Update Configs - updates the configuration table in the database
     *
     */
    public function updateConfigs($value,$configname){
        $query = "UPDATE ".TBL_CONFIGURATION." SET config_value = :value WHERE config_name = :configname";
        $stmt = $this->connection->prepare($query);
        return $stmt->execute(array(':value' => $value, ':configname' => $configname));
    }

    /**
     * confirmUserPass - Checks whether or not the given username is in the database,
     * if so it checks if the given password is the same password in the database
     * for that user. If the user doesn't exist or if the passwords don't match up,
     * it returns an error code (1 or 2). On success it returns 0.
     */
    public function confirmUserPass($username, $password){
        /* Add slashes if necessary (for query) */
        if (!get_magic_quotes_gpc()) {
            $username = addslashes($username);
        }

        /* Verify that user is in database */
        $query = "SELECT password, userlevel, usersalt FROM ".TBL_USERS." WHERE username = :username";
        $stmt = $this->connection->prepare($query);
        $stmt->execute(array(':username' => $username));
        $count = $stmt->rowCount();

        if (!$stmt || $count < 1) {
            return 1; //Indicates username failure
        }

        /* Retrieve password and userlevel from result, strip slashes */
        $dbarray = $stmt->fetch();

        $dbarray['userlevel'] = stripslashes($dbarray['userlevel']);
        $dbarray['usersalt'] = stripslashes($dbarray['usersalt']);
        $password = stripslashes($password);

        $sqlpass = sha1($dbarray['usersalt'].$password);

        /* Validate that password matches and check if userlevel is equal to 1 */
        if (($dbarray['password'] == $sqlpass)&&($dbarray['userlevel'] == 1)) {
            return 3; //Indicates account has not been activated
        }

        /* Validate that password matches and check if userlevel is equal to 2 */
        if (($dbarray['password'] == $sqlpass)&&($dbarray['userlevel'] == 2)) {
            return 4; //Indicates admin has not activated account
        }

        /* Validate that password is correct */
        if ($dbarray['password'] == $sqlpass) {
            return 0; //Success! Username and password confirmed
        } else {
            return 2; //Indicates password failure
        }
    }

    /**
     * confirmUserID - Checks whether or not the given username is in the database,
     * if so it checks if the given userid is the same userid in the database
     * for that user. If the user doesn't exist or if the userids don't match up,
     * it returns an error code (1 or 2). On success it returns 0.
     */
    public function confirmUserID($username, $userid){
        /* Add slashes if necessary (for query) */
        if (!get_magic_quotes_gpc()) {
            $username = addslashes($username);
        }

        /* Verify that user is in database */
        $query = "SELECT userid FROM ".TBL_USERS." WHERE username = :username";
        $stmt = $this->connection->prepare($query);
        $stmt->execute(array(':username' => $username));
        $count = $stmt->rowCount();

        if (!$stmt || $count < 1) {
            return 1; //Indicates username failure
        }

        $dbarray = $stmt->fetch();

        /* Retrieve userid from result, strip slashes */
        $dbarray['userid'] = stripslashes($dbarray['userid']);
        $userid = stripslashes($userid);

        /* Validate that userid is correct */
        if ($userid == $dbarray['userid']) {
         return 0; //Success! Username and userid confirmed
        } else {
            return 2; //Indicates userid invalid
        }
    }

    /**
    * usernameTaken - Returns true if the username has been taken by another user, false otherwise.
    */
    public function usernameTaken($username){
        if (!get_magic_quotes_gpc()) {
            $username = addslashes($username);
        }
        $query = "SELECT username FROM ".TBL_USERS." WHERE username = :username";
        $stmt = $this->connection->prepare($query);
        $stmt->execute(array(':username' => $username));
        $count = $stmt->rowCount();
        return ($count > 0);
    }

    /**
     * usernameBanned - Returns true if the username has been banned by the administrator.
     */
    public function usernameBanned($username){
        if (!get_magic_quotes_gpc()) {
            $username = addslashes($username);
        }
        $query = "SELECT username FROM ".TBL_BANNED_USERS." WHERE username = :username";
        $stmt = $this->connection->prepare($query);
        $stmt->execute(array(':username' => $username));
        $count = $stmt->rowCount();
        return ($count > 0);
    }

    /**
     * addNewUser - Inserts the given (username, password, email) info into the database.
     * Appropriate user level is set. Returns true on success, false otherwise.
     */
    public function addNewUser($username, $password, $email, $token, $usersalt){
        $time = time();
        $config = $this->getConfigs();
        /* If admin sign up, give admin user level */
        if (strcasecmp($username, ADMIN_NAME) == 0) {
            $ulevel = ADMIN_LEVEL;
            /* Which validation is on? */
        } else if ($config['ACCOUNT_ACTIVATION'] == 1) {
            $ulevel = REGUSER_LEVEL; /* No activation required */
        } else if ($config['ACCOUNT_ACTIVATION'] == 2) {
            $ulevel = ACT_EMAIL; /* Activation e-mail will be sent */
        } else if ($config['ACCOUNT_ACTIVATION'] == 3) {
            $ulevel = ADMIN_ACT; /* Admin will activate account */
        }

        $password = sha1($usersalt.$password);
        $userip = $_SERVER['REMOTE_ADDR'];

        $query = "INSERT INTO ".TBL_USERS." SET username = :username, password = :password, usersalt = :usersalt, userid = 0, userlevel = $ulevel, email = :email, timestamp = $time, actkey = :token, ip = '$userip', regdate = $time";
        $stmt = $this->connection->prepare($query);
        if ($stmt->execute(array(':username' => $username, ':password' => $password, ':usersalt' => $usersalt, ':email' => $email, ':token' => $token))) {
            $items = array(':user' => $this->getLastUserRegisteredName());
            $this->insert("INSERT INTO ".TBL_INFO." SET uid = :user", $items);
            $this->insert("INSERT INTO ".TBL_CRIMES." SET uid = :user", $items);
        }

        return false;
    }

    /**
     * updateUserField - Updates a field, specified by the field
     * parameter, in the user's row of the database.
     */
    public function updateUserField($username, $field, $value){
        $query = "UPDATE ".TBL_USERS." SET ".$field." = :value WHERE username = :username";
        $stmt = $this->connection->prepare($query);
        return $stmt->execute(array(':username' => $username, ':value' => $value));
    }

    /**
     * getUserInfo - Returns the result array from a mysql
     * query asking for all information stored regarding
     * the given username. If query fails, NULL is returned.
     */
    public function getUserInfo($username){
        $query = "SELECT * FROM ".TBL_USERS." WHERE username = :username";
        $stmt = $this->connection->prepare($query);
        $stmt->execute(array(':username' => $username));
        $dbarray = $stmt->fetch();
        /* Error occurred, return given name by default */
        $result = count($dbarray);
        if (!$dbarray || $result < 1) {
            return NULL;
        }
        /* Return result array */
        return $dbarray;
    }

    /**
     * checkUserEmailMatch - Checks whether username
     * and email match in forget password form.
     */
    public function checkUserEmailMatch($username, $email){

        $query = "SELECT username FROM ".TBL_USERS." WHERE username = :username AND email = :email";
        $stmt = $this->connection->prepare($query);
        $stmt->execute(array(':username' => $username, ':email' => $email));
        $number_of_rows = $stmt->rowCount();

        if (!$stmt || $number_of_rows < 1){
            return 0;
        } else {
            return 1;
        }
    }

    /**
     * getNumMembers - Returns the number of signed-up users
     * of the website, banned members not included. The first
     * time the function is called on page load, the database
     * is queried, on subsequent calls, the stored result
     * is returned. This is to improve efficiency, effectively
     * not querying the database when no call is made.
     */
    public function getNumMembers(){
        if($this->num_members < 0){
            $result =  $this->connection->query("SELECT username FROM ".TBL_USERS);
            $this->num_members = $result->rowCount();
        }
        return $this->num_members;
    }

    /**
     * getLastUserRegistered - Returns the username of the last
     * member to sign up and the date.
     */
    public function getLastUserRegisteredName() {
        $result = $this->connection->query("SELECT username, regdate FROM ".TBL_USERS." ORDER BY regdate DESC LIMIT 0,1");
        $this->lastuser_reg = $result->fetchColumn();
        return $this->lastuser_reg;
    }

    /**
     * getLastUserRegistered - Returns the username of the last
     * member to sign up and the date.
     */
    public function getLastUserRegisteredDate() {
         $result = $this->connection->query("SELECT username, regdate FROM ".TBL_USERS." ORDER BY regdate DESC LIMIT 0,1");
         $this->lastuser_reg = $result->fetchColumn(1);
         return $this->lastuser_reg;
    }

    public function getRanks() {
        $ranks = $this->getConfigs();
        return unserialize($ranks['RANKS']);
    }

    /**
     * calcNumActiveUsers - Finds out how many active users
     * are viewing site and sets class variable accordingly.
     */
    private function calcNumActiveUsers(){
        /* Calculate number of USERS at site */
        $sql = $this->connection->query("SELECT * FROM ".TBL_ACTIVE_USERS);
        $this->num_active_users = $sql->rowCount();
    }

    /**
     * calcNumActiveGuests - Finds out how many active guests
     * are viewing site and sets class variable accordingly.
     */
    private function calcNumActiveGuests(){
        /* Calculate number of GUESTS at site */
        $sql = $this->connection->query("SELECT * FROM ".TBL_ACTIVE_GUESTS);
        $this->num_active_guests = $sql->rowCount();
    }

    /**
     * addActiveUser - Updates username's last active timestamp
     * in the database, and also adds him to the table of
     * active users, or updates timestamp if already there.
     */
    public function addActiveUser($username, $time){
        $config = $this->getConfigs();

        // new - this checks how long someone has been inactive and logs them off if neccessary unless
        // they have cookies (remember me) set.

        $query = "SELECT * FROM ".TBL_USERS." WHERE username = :username";
        $stmt = $this->connection->prepare($query);
        $stmt->execute(array(':username' => $username));

        $dbarray = $stmt->fetch();
        $db_timestamp = $dbarray['timestamp'];
        $timeout = time()-$config['USER_TIMEOUT']*60;
        if($db_timestamp < $timeout && !isset($_COOKIE['cookname']) && !isset($_COOKIE['cookid'])) header("Location:".$config['WEB_ROOT']."core/process.php");

        $query = "UPDATE ".TBL_USERS." SET timestamp = :time WHERE username = :username";
        $stmt = $this->connection->prepare($query);
        $stmt->execute(array(':username' => $username, ':time' => $time));

        if(!$config['TRACK_VISITORS']) return;
        $query = "REPLACE INTO ".TBL_ACTIVE_USERS." VALUES (:username, :time)";
        $stmt = $this->connection->prepare($query);
        $stmt->execute(array(':username' => $username, ':time' => $time));

        $this->calcNumActiveUsers();
    }

    /* addActiveGuest - Adds guest to active guests table */
    public function addActiveGuest($ip, $time){
        $config = $this->getConfigs();
        if(!$config['TRACK_VISITORS']) return;
        $sql =  $this->connection->prepare("REPLACE INTO ".TBL_ACTIVE_GUESTS." VALUES ('$ip', '$time')");
        $sql->execute();
        $this->calcNumActiveGuests();
    }

    /* These functions are self explanatory, no need for comments */

    /* removeActiveUser */
    public function removeActiveUser($username){
        $config = $this->getConfigs();
        if (!$config['TRACK_VISITORS']) return;
        $sql = $this->connection->prepare("DELETE FROM ".TBL_ACTIVE_USERS." WHERE username = '$username'");
        $sql->execute();
        $this->calcNumActiveUsers();
    }

    /* removeActiveGuest */
    public function removeActiveGuest($ip){
        $config = $this->getConfigs();
        if (!$config['TRACK_VISITORS']) return;
        $sql = $this->connection->prepare("DELETE FROM ".TBL_ACTIVE_GUESTS." WHERE ip = '$ip'");
        $sql->execute();
        $this->calcNumActiveGuests();
    }

    /* removeInactiveUsers */
    public function removeInactiveUsers(){
        $config = $this->getConfigs();
        if (!$config['TRACK_VISITORS']) return;
        $timeout = time()-$config['USER_TIMEOUT']*60;
        $stmt = $this->connection->prepare("DELETE FROM ".TBL_ACTIVE_USERS." WHERE timestamp < $timeout");
        $stmt->execute();
        $this->calcNumActiveUsers();
    }

    /* removeInactiveGuests */
    public function removeInactiveGuests(){
        $config = $this->getConfigs();
        if (!$config['TRACK_VISITORS']) return;
        $timeout = time()-$config['GUEST_TIMEOUT']*60;
        $stmt = $this->connection->prepare("DELETE FROM ".TBL_ACTIVE_GUESTS." WHERE timestamp < $timeout");
        $stmt->execute();
        $this->calcNumActiveGuests();
    }

    /**
     * Select from database
     *
     * @param-1: The query that has to be executed
     * @param-2: The placeholders (Default = empty array)
     * @return:  Query result
     */
    public function select($query, $items = array()) {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($items);
        } catch(PDOException $e) {
            echo $e;
        }

        return $stmt;
    }

    /**
     * Update the database
     *
     * @param-1: The query that has to be executed
     * @param-2: The placeholders (Default = empty array)
     */
    public function update($query, $items = array()) {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($items);
        } catch(PDOException $e) {
            echo $e;
        }
    }

    /**
     * Insert into the database
     *
     * @param-1: The query that has to be executed
     * @param-2: The placeholders (Default = empty array)
     */
    public function insert($query, $items = array()) {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($items);
        } catch(PDOException $e) {
            echo $e;
        }
    }

    /**
     * Delete from database
     *
     * @param-1: The query that has to be executed
     * @param-2: The placeholders (Default = empty array)
     */
    public function delete($query, $items = array()) {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($items);
        } catch(PDOException $e) {
            echo $e;
        }
    }
   
};