<?php

/**
 *
 * Company Inc,.
 *
 */
header('Content-Type:text/html;charset=UTF-8');
ini_set('date.timezone', 'PRC');

define('CORE_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR);
define('APP_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR);
define('LOG_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR);
require CORE_PATH . 'common.php';
init();

$action = (array_key_exists('action', $_POST) ? $_POST['action'] : (array_key_exists('action', $_GET) ? $_GET['action'] : 'loginform'));
$base64Authkey = (array_key_exists('aut', $_POST) && $_POST['aut'] ? $_POST['aut'] : (array_key_exists('aut', $_GET) && $_GET['aut'] ? $_GET['aut'] : (array_key_exists('aut', $_COOKIE) && $_COOKIE['aut'] ? $_COOKIE['aut'] : '')));

$argumentArray = array();
for ($valueIndex = 1; $valueIndex <= 20; $valueIndex++) {
    $valueName = 'value' . $valueIndex;
    $$valueName = array_key_exists($valueName, $_POST) ? $_POST[$valueName] : (array_key_exists($valueName, $_GET) ? $_GET[$valueName] : NULL);
    if (isset($$valueName)) {
        $argumentArray[$valueName] = $$valueName;
    }
}

$framework = FrameWork::getInstance();

Registry::set('servertime', time());
$eventsOutput = array('output_web');

$configActions = array('loginform' => 'login', 'login' => 'login', 'getmessage' => 'admin', 'addnews' => 'admin', 'getadmininfo' => 'admin', 'changeadmininfo' => 'admin', 'getnewslist' => 'admin',
    'deletenews' => 'admin', 'updateaboutus' => 'admin', 'updatebusiness' => 'admin', 'updatecontact' => 'admin','updatenewsshow'=>'admin','updaenews'=>'admin','updateaboutusshow'=>'admin',
    'updatebusinessshow' => 'admin', 'updatecontactshow' => 'admin');

$action = strtolower($action);
if (in_array($action, array('loginform', 'login'))) {
    $eventsAuth = array('');
} else if (array_key_exists($action, $configActions)) {
    $eventsAuth = array('auth_web');
} else {
    exit;
}

Registry::set('action', $action);
Registry::set('authkey', base64_decode($base64Authkey));

International::setLanguageType('zh');

View::addDisplayContent(array('display1' => '', 'html_data' => ''), true);
$framework->registerEvents($eventsAuth, $eventsOutput);
$framework->run($configActions[$action], $action, $argumentArray);
quit();
?>
