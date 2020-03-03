<?php


namespace Icc\Json;


class JSON
{
    public static $_JSON = array();

    public static function initializeJson()
    {
        JSON::$_JSON = json_decode(file_get_contents('php://input'), true);
    }


}

function json(string $var) {
    return JSON::$_JSON[$var];
}
