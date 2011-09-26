<?php

class Autoloader_Simple_MapAutoloader {

    /**
     * @internal
     * @var array
     */
    static $_map = array();

    /**
     * @param string $className
     * @return boolean
     */
    static function load($className) {
        if (array_key_exists($className, self::$_map)) {
            require self::$_map[$className];
            return class_exists($className) || interface_exists($className);
        }
        return false;
    }

    /**
     * @param array $map
     */
    static function add(array $map) {
        self::$_map = array_merge(self::$_map, $map);
    }

}
