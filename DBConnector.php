<?php
namespace Icc;

use mysqli;
use mysqli_result;

class DBConnector
{
    public static $mysqli;
    public function __construct($host="localhost", $user="root", $password="", $database="icc")
    {
        if (self::$mysqli == null) {
            self::$mysqli = new mysqli($host, $user, $password, $database);
            if (self::$mysqli->connect_errno) {
                echo "Error during connecting to the database! Error: " . self::$mysqli -> connect_error;
            }
        }
    }


    /**
     * Wrapper for the @link mysqli::query() function.
     *
     * @param $query
     * @return bool|mysqli_result
     */
    public function execute_query($query) {
        return self::$mysqli -> query($query);
    }

    public function close() {
        self::$mysqli -> close();
    }
}

