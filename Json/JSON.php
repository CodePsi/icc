<?php


namespace Icc\Json;


class JSON
{
    public static $_JSON = array();

    public static function initializeJson()
    {
        JSON::$_JSON = json_decode(file_get_contents('php://input'), true);
    }

    public static function isStrRepresentJson(string $strJson) {
        $json = json_decode($strJson);
        return $json && $strJson != $json;
    }

    public static function isJson($json) {
        return !empty($json) && is_string($json) && is_array(json_decode($json, true)) && json_last_error() == 0;
    }


}

function json(string $var) {
    return JSON::$_JSON[$var];
}
