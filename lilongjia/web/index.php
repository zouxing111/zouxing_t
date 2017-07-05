<?php

/**
 *
 * Company Inc,.
 *
 */
//www.afschina.com
header('Content-Type:text/html;charset=UTF-8');
ini_set('date.timezone', 'PRC');

define('CORE_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR);
define('APP_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR);
define('LOG_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR);
require CORE_PATH . 'common.php';

init();

$action = (array_key_exists('action', $_POST) ? $_POST['action'] : (array_key_exists('action', $_GET) ? $_GET['action'] : '000'));
$language = (array_key_exists('language', $_POST) ? $_POST['language'] : (array_key_exists('language', $_GET) ? $_GET['language'] : 'zh'));


$argumentArray = array();
for ($valueIndex = 1; $valueIndex <= 8; $valueIndex++) {
    $valueName = 'value' . $valueIndex;
    $$valueName = array_key_exists($valueName, $_POST) ? $_POST[$valueName] : (array_key_exists($valueName, $_GET) ? $_GET[$valueName] : '');
    $argumentArray[$valueName] = $$valueName;
}

$controllers = array('000' => 'user', '001' => 'user', '002' => 'user', '003' => 'user', '004' => 'user', '005' => 'user', '006' => 'user');

$framework = FrameWork::getInstance();



Registry::set('action', $action);
Registry::set('servertime', time());

International::setLanguageType($language);

$title = International::getLanguage('common', 0);
View::addDisplayContent(array('html_title' => $title), true);
$framework->registerEvents(array('auth_user'), array('output_user'));
$framework->run($controllers[$action], 'action' . $action, $argumentArray);

quit();
?>
