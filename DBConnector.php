<?php


class DBConnector
{
    private $mysqli;
    public function __construct($host, $user, $password, $database)
    {
        $this -> mysqli = new mysqli($host, $user, $password, $database);
        if ($this -> mysqli -> connect_errno) {
            echo "Error during connecting to the database! Error: " . $this -> mysqli -> connect_error;
        }
    }

    public function execute_query($query) {
        return $this -> mysqli -> query($query);
    }
}

