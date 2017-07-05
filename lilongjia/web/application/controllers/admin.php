<?php

class Admin_Controller extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function getMessage($startTime, $endTime) {
        View::$layoutFile = 'admin.php';
        View::$templateFile = 'getmessage.html';
        if (!$startTime or !$endTime) {
            View::addDisplayContent(array('html_data' => ''));
            return;
        }
        $adminModel = Admin_Model::getInstance();
        $message = $adminModel->getMessage($startTime, $endTime);
        if (!$message) {
            echo '<script type="text/javascript">' . 'alert("这段时间没有人留言");' . '</script>';
            return;
        }
        View::addDisplayContent(array('html_data' => $message));
        return;
    }

    public function addNews($subject, $content, $time, $language) {
        View::$layoutFile = 'admin.php';
        View::$templateFile = 'addnews.html';
        $subject = trim($subject);
        $content = trim($content);
        $language = trim($language);
        if (!$subject or !$content or !$time or !$language) {
            return;
        }
        $adminModel = Admin_Model::getInstance();
        if ($adminModel->addNews($subject, $content, $time, $language)) {
            echo '<script type="text/javascript">' . 'alert("新闻发布成功");' . '</script>';
        } else {
            echo '<script type="text/javascript">' . 'alert("新闻发布失败，请联系开发者");' . '</script>';
        }
        return;
    }

    public function updateNewsShow($id) {
        View::$layoutFile = 'admin.php';
        View::$templateFile = 'updatenewsshow.html';
        $adminModel = Admin_Model::getInstance();
        $html_data = $adminModel->getNewsByID($id);
        View::addDisplayContent(array('html_data' => $html_data));
        return;
    }

    public function updaeNews($subject, $content, $time, $language, $id) {
        if (!$subject or !$content or !$time or !$language) {
            echo '<script type="text/javascript">' . 'alert("标题、内容、时间、语言种类都不能为空");' . '</script>';
            return;
        }
        View::$layoutFile = 'admin.php';
        View::$templateFile = 'updatenewsshow.html';
        $adminModel = Admin_Model::getInstance();
        if ($adminModel->updateNewsByID($id, $subject, $content, $time, $language)) {
            echo '<script type="text/javascript">' . 'alert("修改新闻成功");' . '</script>';
        }
        $html_data = $adminModel->getNewsByID($id);
        View::addDisplayContent(array('html_data' => $html_data));
        return;
    }

    public function getAdminInfo() {
        View::$layoutFile = 'admin.php';
        View::$templateFile = 'admininfo.html';
        $oldName = Config::get('localcommon.username');
        $oldPassword = Config::get('localcommon.password');
        View::addDisplayContent(array('html_username' => $oldName, 'html_password' => $oldPassword));
        return;
    }

    public function changeAdminInfo($username, $password) {
        View::$layoutFile = 'admin.php';
        View::$templateFile = 'admininfo.html';
        $oldName = Config::get('localcommon.username');
        $oldPassword = Config::get('localcommon.password');
        View::addDisplayContent(array('html_username' => $oldName, 'html_password' => $oldPassword));
        if (!$username and !$password) {
            return;
        }
        if (!$username or !$password) {
            echo '<script type="text/javascript">' . 'alert("管理员的用户名和密码不能为空");' . '</script>';
            return;
        }
        $config = array();
        //$config['localcommon.username']=  Config::get('localcommon.username');
        //$config['localcommon.password']= Config::get('localcommon.password');
        $config['localcommon.username'] = $username;
        $config['localcommon.password'] = $password;
        if (Filewriter_Model::phpVariablesWriter('config', $config, CONFIG_PATH . 'localcommon.php')) {
            $autKey = cryptstring($username . "\r\n" . $password . "\r\n", true);
            $aut = base64_encode($autKey);
            setcookie('aut', $aut, Registry::get('servertime') + 3600 * 24 * 30, '/');
            Registry::set('authkey', $autKey);
            echo '<script type="text/javascript">' . 'alert("您修改管理员用户和密码成功");' . '</script>';
            View::addDisplayContent(array('html_username' => $username, 'html_password' => $password));
            return;
        }
        echo '<script type="text/javascript">' . 'alert("您修改管理员用户和密码失败");' . '</script>';
        View::addDisplayContent(array('html_username' => $oldName, 'html_password' => $oldPassword));
        return;
    }

    public function getNewsList() {
        $adminModel = Admin_Model::getInstance();
        $news = $adminModel->getNewsList();
        View::$layoutFile = 'admin.php';
        View::$templateFile = 'getnewslist.html';
        if (!$news) {
            echo '<script type="text/javascript">' . 'alert("没有新闻");' . '</script>';
            return;
        }
        View::addDisplayContent(array('html_news' => $news));
        return;
    }

    public function deleteNews($id) {
        View::$layoutFile = 'admin.php';
        View::$templateFile = 'getnewslist.html';
        $adminModel = Admin_Model::getInstance();
        if ($adminModel->deleteNews($id)) {
            echo '<script type="text/javascript">' . 'alert("你删除新闻成功");' . '</script>';
        } else {
            echo '<script type="text/javascript">' . 'alert("你删除新闻失败");' . '</script>';
        }
        $news = $adminModel->getNewsList();
        View::addDisplayContent(array('html_news' => $news));
        return;
    }

    public function updateAboutUsShow($language = 'zh') {
        View::$layoutFile = 'admin.php';
        View::$templateFile = 'updateaboutus.html';
        $userModel = User_Model::getInstance();
        if ($language == 'zh') {
            $html_data = $userModel->getConfig('aboutus_zh');
            International::setLanguageType('zh');
        } else {
            $html_data = $userModel->getConfig('aboutus_en');
            International::setLanguageType('en');
        }
        $aboutus = $html_data ? $html_data['value'] : International::getLanguage('gsjj', 1);
        View::addDisplayContent(array('html_aboutus' => $aboutus, 'html_language' => $language));
        return;
    }

    public function updateAboutUs($data, $language) {
        if (!$data and !$language) {
            $this->updateAboutUsShow($language);
        }
        if ($language == 'zh' and $data) {
            $variable = 'aboutus_zh';
            International::setLanguageType('zh');
        } else {
            $variable = 'aboutus_en';
            International::setLanguageType('en');
        }
        $adminModel = Admin_Model::getInstance();
        if ($adminModel->updateConfig($variable, $data)) {
            echo '<script type="text/javascript">' . 'alert("您修改公司简介成功");' . '</script>';
        } else {
            echo '<script type="text/javascript">' . 'alert("您修改公司简介失败");' . '</script>';
        }
        $this->updateAboutUsShow($language);
        return;
    }

    public function updateBusinessShow($language = 'zh') {
        View::$layoutFile = 'admin.php';
        View::$templateFile = 'updatebusiness.html';
        $userModel = User_Model::getInstance();
        if ($language == 'zh') {
            $html_data = $userModel->getConfig('business_zh');
            International::setLanguageType('zh');
        } else {
            $html_data = $userModel->getConfig('business_en');
            International::setLanguageType('en');
        }
        $business = $html_data ? $html_data['value'] : International::getLanguage('ywfw', 0);
        View::addDisplayContent(array('html_business' => $business, 'html_language' => $language));
        return;
    }

    public function updateBusiness($data, $language) {
        if (!$data and !$language) {
            $this->updateBusinessShow();
        }
        if ($language == 'zh' and $data) {
            $variable = 'business_zh';
            International::setLanguageType('zh');
        } else {
            $variable = 'business_en';
            International::setLanguageType('en');
        }
        $adminModel = Admin_Model::getInstance();
        if ($adminModel->updateConfig($variable, $data)) {
            echo '<script type="text/javascript">' . 'alert("您修改业务范围成功");' . '</script>';
        } else {
            echo '<script type="text/javascript">' . 'alert("您修改业务范围失败");' . '</script>';
        }
        $this->updateBusinessShow($language);
        return;
    }

    public function updateContactShow($language = 'zh') {
        View::$layoutFile = 'admin.php';
        View::$templateFile = 'updatecontact.html';
        $userModel = User_Model::getInstance();
        if ($language == 'zh') {
            $html_data = $userModel->getConfig('contact_zh');
            International::setLanguageType('zh');
        } else {
            $html_data = $userModel->getConfig('contact_en');
            International::setLanguageType('en');
        }
        $contact = $html_data ? $html_data['value'] : International::getLanguage('lxfs', 0);
        View::addDisplayContent(array('html_contact' => $contact, 'html_language' => $language));
        return;
    }

    public function updateContact($data, $language) {
        if (!$data and !$language) {
            $this->updateContactShow();
        }
        if ($language == 'zh' and $data) {
            $variable = 'contact_zh';
            International::setLanguageType('zh');
        } else {
            $variable = 'contact_en';
            International::setLanguageType('en');
        }
        $adminModel = Admin_Model::getInstance();
        if ($adminModel->updateConfig($variable, $data)) {
            echo '<script type="text/javascript">' . 'alert("您修改联系方式成功");' . '</script>';
        } else {
            echo '<script type="text/javascript">' . 'alert("您修改联系方式失败");' . '</script>';
        }
        $this->updateContactShow($language);
        return;
    }

}

?>
