<?php


namespace Euro\Logger;


class Logger
{
    private $login;
    private $name;
    protected $fileName = "";
    protected $fp = null;
    const INFO = 1;
    const WARNING = 2;
    public static $instance = null;

    private function __construct($login, $name, $fileName = "user_log.txt")
    {
        $this->login = $login;
        $this->name = $name;
        $this->openFile();
    }

    public static function getInstance($login, $name)
    {
        if (!isset(self::$instance)) {
            self::$instance = new Logger($login, $name);
        }
    }

    function log($level, $message) {
        $level_str = "";
        if ($level == self::INFO) {
            $level_str = "[INFO]";
        } else if ($level == self::WARNING) {
            $level_str = "[WARNING]";
        }
        if ($this -> login != "" && $this -> name)
            $result_str = $level_str . " " . date("Y-m-d H:i:s") . ' (' . $this -> name . ':' . $this -> login . ')' . " : " . $message . "\n";
        else
            $result_str = $level_str . " " . date("Y-m-d H:i:s") . " : " . $message . "\n";
        $this -> write($result_str);
    }

    public static function getFileData($fileName = "user_log.txt") {
        $fp = fopen($fileName, "r");
        $file_data = "";
        while ($line = fgets($fp)) {
            $file_data = $file_data . $line;
        }

        return $file_data;
    }

    public function logInfo($message)
    {
        $this -> log(self::INFO, $message);
    }

    public function logWarning($message)
    {
        $this -> log(self::WARNING, $message);
    }

    protected function write($text)
    {
        if ($this -> fp != null) {
            fwrite($this->fp, $text);
        }
    }

    protected function openFile() {
        $this -> fp = fopen($this -> fileName, "a+");
    }
}