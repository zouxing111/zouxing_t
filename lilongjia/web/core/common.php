<?php

/**
 *
 * Haypi Inc,.
 *
 */
function init() {
    define('MODEL_PATH', APP_PATH . 'models' . DIRECTORY_SEPARATOR);
    define('CONTROLLER_PATH', APP_PATH . 'controllers' . DIRECTORY_SEPARATOR);
    define('TEMPLATE_PATH', APP_PATH . 'templates' . DIRECTORY_SEPARATOR);
    define('LIBRARY_PATH', APP_PATH . 'libraries' . DIRECTORY_SEPARATOR);
    define('EVENT_PATH', APP_PATH . 'events' . DIRECTORY_SEPARATOR);
    define('CONFIG_PATH', APP_PATH . 'config' . DIRECTORY_SEPARATOR);
    define('INTERNATIONAL_PATH', APP_PATH . 'international' . DIRECTORY_SEPARATOR);
    define('VIEW_PATH', APP_PATH . 'views' . DIRECTORY_SEPARATOR);
    require CORE_PATH . 'framework.php';
    set_error_handler('error_handler');
    //error_reporting(E_ALL & ~E_NOTICE);
    ini_set("error_log", LOG_PATH . 'phperror_' . date('Y-m-d') . '.txt');
}

function quit() {
    $errormsg = Registry::get('errormsg');
    if ($errormsg) {
        //Report_Model::errorReport($errormsg, '000000', true);
    }
}

function error_handler($errno, $errstr, $errfile, $errline) {
    //if( E_NOTICE == $errno) return;
    $errormsg = "[$errno] $errstr<br /> $errline in file $errfile \n <br />";
    //Log_Model::logToFile('common1', $errormsg, 'error');
    return true;
}

function genguid() {
    if (function_exists('com_create_guid')) {
        return str_replace('{', '', str_replace('}', '', str_replace('-', '', com_create_guid())));
    } else {
        $chars = md5(uniqid(mt_rand(), true));
        return substr($chars, 0, 8) . substr($chars, 8, 4) . substr($chars, 12, 4) . substr($chars, 16, 4) . substr($chars, 20, 12);
    }
}

function cryptstring($string, $ise) {
    $key = md5(md5('a550fahEcOY74hnw'));
    $key_length = strlen($key);

    $string = $ise ? substr(md5($string . $key), 0, 8) . $string : base64_decode($string);
    $string_length = strlen($string);

    $rndkey = $box = array();
    $result = '';

    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($key[$i % $key_length]);
        $box[$i] = $i;
    }
    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }

    if ($ise) {
        return str_replace('=', '', base64_encode($result));
    } else {
        if (substr($result, 0, 8) == substr(md5(substr($result, 8) . $key), 0, 8)) {
            return substr($result, 8);
        } else {
            return '';
        }
    }
}

?>