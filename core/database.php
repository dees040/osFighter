<?php
/**
 * Database.php
 *
 * The Database class is used to pull/push info
 * from en to database.
 *
 * By dees040
 * On 08-02-2014
 */
include("constants.php");

class MySQLDB
{
    public $connection;         // The MySQL database connection

    // Class constructor
    function MySQLDB(){
        // Create connection with the Database
        try {
            $this->connection = new PDO('mysql:host='.DB_SERVER.';dbname='.DB_NAME, DB_USER, DB_PASS);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(Exception $e) {
            echo "Error connecting to the DB.";
        }
    }

    /**
     * Select from database
     *
     * @param-1: The query that has to be excuted
     * @param-2: The placeholders (Default = empty array)
     * @return:  Query result
     */
    function select($query, $items = array()) {
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
     * @param-1: The query that has to be excuted
     * @param-2: The placeholders (Default = empty array)
     */
    function update($query, $items = array()) {
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
     * @param-1: The query that has to be excuted
     * @param-2: The placeholders (Default = empty array)
     */
    function insert($query, $items = array()) {
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
     * @param-1: The query that has to be excuted
     * @param-2: The placeholders (Default = empty array)
     */
    function delete($query, $items = array()) {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($items);
        } catch(PDOException $e) {
            echo $e;
        }
    }
}

$database = new MySQLDB;

?>