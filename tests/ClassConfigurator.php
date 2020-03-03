<?php
namespace Icc;

class ClassConfigurator
{
    private static $configurations = array();

    public function get() {
        return "Str";
    }

    public static function set($class) {
        array_push(self::$configurations, $class);
    }

    /**
     * @return array
     */
    public static function getConfigurations(): array
    {
        return self::$configurations;
    }
}