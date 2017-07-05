<?php

/**
 *
 * Haypi Inc,.
 *
 */
class GameData {

    public static $datacache = array();

    /**
     * memcache
     */
    public static function getgamedata($varname, $index1 = '', $index2 = '', $index3 = '', $skipCache = false) {
        $key = $varname . (($index1 !== '') ? ($index1 . '-') : '') . (($index2 !== '') ? ($index2 . '-') : '') . (($index3 !== '') ? ($index3 . '-') : '');
        if (!$skipCache && array_key_exists($key, self::$datacache)) {
            return self::$datacache[$key];
        }
        if (!$skipCache && ($result = Memcached::get($key)) !== false) {
            self::$datacache[$key] = $result;
            return $result;
        } else {
            require(APP_PATH . 'gamedata' . DIRECTORY_SEPARATOR . 'gamedata.php');
            $array = $$varname;
            $result = ($index1 !== '') ? (($index2 !== '') ? (($index3 !== '') ? ((isset($array[$index1][$index2][$index3])) ? ($array[$index1][$index2][$index3]) : (false)) : ((isset($array[$index1][$index2])) ? ($array[$index1][$index2]) : (false))) : ((isset($array[$index1])) ? ($array[$index1]) : (false))) : ((isset($array)) ? ($array) : (false));
            if ($result !== false) {
                Memcached::set($key, $result, 0);
                self::$datacache[$key] = $result;
                return $result;
            } else {
                Log_Model::logToFile(__CLASS__ . __FUNCTION__ . '0', 'gamedata: ' . "{$varname},{$index1},{$index2},{$index3}");
            }
        }
    }

    /**
     * XCache
    public static function getgamedata($varname, $index1 = '', $index2 = '', $index3 = '', $skipCache = false) {
        $key = $varname . (($index1 !== '') ? ($index1 . '-') : '') . (($index2 !== '') ? ($index2 . '-') : '') . (($index3 !== '') ? ($index3 . '-') : '');
        if (!$skipCache && array_key_exists($key, self::$datacache)) {
            return self::$datacache[$key];
        }
        if (!$skipCache && ($result = xcache_get($key)) !== null) {
            self::$datacache[$key] = $result;
            return $result;
        } else {
            require(APP_PATH . 'gamedata' . DIRECTORY_SEPARATOR . 'gamedata.php');
            $array = $$varname;
            $result = ($index1 !== '') ? (($index2 !== '') ? (($index3 !== '') ? ((isset($array[$index1][$index2][$index3])) ? ($array[$index1][$index2][$index3]) : (false)) : ((isset($array[$index1][$index2])) ? ($array[$index1][$index2]) : (false))) : ((isset($array[$index1])) ? ($array[$index1]) : (false))) : ((isset($array)) ? ($array) : (false));
            if ($result !== false) {
                xcache_set($key, $result, 0);
                self::$datacache[$key] = $result;
                return $result;
            } else {
                Log_Model::logToFile(__CLASS__ . __FUNCTION__ . '0', 'gamedata: ' . "{$varname},{$index1},{$index2},{$index3}");
            }
        }
    }
     */

}

?>