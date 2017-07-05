<?php

class Login_Controller extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function loginform() {
        View::$layoutFile = 'login.php';
        View::$templateFile = 'login.html';
        View::addDisplayContent(array('display1' => ''));
        setcookie("aut", '', 0, '/');
        return;
    }

    public function login($username, $password) {
        $username = trim($username);
        $password = trim($password);
        if (!$username) {
            View::$layoutFile = 'login.php';
            View::$templateFile = 'login.html';
            View::addDisplayContent(array('display1' => International::getLanguage('login', 0)));
            setcookie("aut", '', 0, '/');
            return;
        }
        if (!$password) {
            View::$layoutFile = 'login.php';
            View::$templateFile = 'login.html';
            View::addDisplayContent(array('display1' => International::getLanguage('login', 1)));
            setcookie("aut", '', 0, '/');
            return;
        }
        if (strcasecmp($username, Config::get('localcommon.username')) != 0 or strcasecmp($password, Config::get('localcommon.password')) != 0) {
            View::$layoutFile = 'login.php';
            View::$templateFile = 'login.html';
            View::addDisplayContent(array('display1' => International::getLanguage('login', 2)));
            setcookie("aut", '', 0, '/');
            return;
        }
        $autKey = cryptstring($username . "\r\n" . $password . "\r\n", true);
        $aut = base64_encode($autKey);
        setcookie('aut', $aut, Registry::get('servertime') + 3600 * 24 * 30, '/');
        Registry::set('authkey', $autKey);
        View::$layoutFile = 'admin.php';
        View::$templateFile = 'addnews.html';
        return;
    }

}

?>
