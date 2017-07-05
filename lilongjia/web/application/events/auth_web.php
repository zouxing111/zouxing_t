<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Auth_Web_Event implements Event_Interface {

    public function run() {
        $authKey = Registry::get('authkey');
        $str = cryptstring($authKey, false);
        if (!$str) {
            echo '<script type="text/javascript">'
            . 'location.href = "./admin.php";'
            . '</script>';
            return false;
        }
        $arr = explode("\r\n", $str);
        if (strcasecmp($arr[0], Config::get('localcommon.username')) != 0 or strcasecmp($arr[1], Config::get('localcommon.password')) != 0) {
            echo '<script type="text/javascript">'
            . 'location.href = "./admin.php";'
            . '</script>';
            return false;
        }
        return true;
    }

}

?>
