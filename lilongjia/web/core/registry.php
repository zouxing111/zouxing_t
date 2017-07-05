<?php

/**
 *
 * Haypi Inc,.
 *
 */
class Registry {

    private static $object;

    public static function getInstance() {
        if (!self::$object)
            self::$object = new Registry();
        return self::$object;
    }

    /**
     * 设置tag值
     */
    public static function set($tag, $value) {
        $obj = self::getInstance();
        $obj->$tag = $value;
    }

    /**
     * 取tag值
     */
    public static function get($tag, $default = NULL) {
        $obj = self::getInstance();
        if (isset($obj->$tag)) {
            return $obj->$tag;
        }
        return $default;
    }

    public function __set($tag, $value) {
        $this->$tag = $value;
    }

    public function __get($tag) {
        return $this->$tag;
    }

}

?>