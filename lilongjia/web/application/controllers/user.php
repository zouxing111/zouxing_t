<?php

class User_Controller extends Controller {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 首页
     * @return type 
     */
    public function action000() {
        View::$layoutFile = 'user.php';
        View::$templateFile = 'home.html';
        $userModel = User_Model::getInstance();
        $page = 1;
        $a = 10;
        $html_news = $userModel->getNews($page, $a, International::$languageType);
        if (International::$languageType == 'zh') {
            $html_data = $userModel->getConfig('aboutus_zh');
        } else {
            $html_data = $userModel->getConfig('aboutus_en');
        }
        $aboutus = $html_data ? $html_data['value'] : '';
        View::addDisplayContent(array('html_data' => $aboutus, 'html_news' => $html_news));
        return;
    }

    /**
     * 公司简介
     * @return type 
     */
    public function action001() {
        View::$layoutFile = 'user.php';
        View::$templateFile = 'gsjj.html';
        $userModel = User_Model::getInstance();
        if (International::$languageType == 'zh') {
            $html_data = $userModel->getConfig('aboutus_zh');
        } else {
            $html_data = $userModel->getConfig('aboutus_en');
        }
        $aboutus = $html_data ? $html_data['value'] : '';
        View::addDisplayContent(array('html_data' => $aboutus));
        return;
    }

    /**
     * 	业务范围 
     */
    public function action002() {
        View::$layoutFile = 'user.php';
        View::$templateFile = 'ywfw.html';
        $userModel = User_Model::getInstance();
        if (International::$languageType == 'zh') {
            $html_data = $userModel->getConfig('business_zh');
        } else {
            $html_data = $userModel->getConfig('business_en');
        }
        $business = $html_data ? $html_data['value'] : '';
        View::addDisplayContent(array('html_data' => $business));
        return;
    }

    /**
     * 联系方式 
     */
    public function action003() {
        View::$layoutFile = 'user.php';
        View::$templateFile = 'lxfs.html';
        $userModel = User_Model::getInstance();
        if (International::$languageType == 'zh') {
            $html_data = $userModel->getConfig('contact_zh');
        } else {
            $html_data = $userModel->getConfig('contact_en');
        }
        $contact = $html_data ? $html_data['value'] : '';
        View::addDisplayContent(array('html_data' => $contact));
        return;
    }

    /**
     * 在线留言 
     */
    public function action004($gsname, $username, $tel, $email, $qq, $msn, $subject, $content) {
        View::$layoutFile = 'user.php';
        View::$templateFile = 'zxly.html';
        $gsname = trim($gsname);
        $username = trim($username);
        $tel = trim($tel);
        $qq = trim($qq);
        $msn = trim($msn);
        $subject = trim($subject);
        $content = trim($content);
        $email = trim($email);
        if (!$username && !$content && (!$tel || !$qq || !$email || !$msn)) {
            return;
        }
        if (strlen($subject) > 180) {
            if (International::$languageType == 'zh') {
                echo '<script type="text/javascript">' . 'alert("您的主题不能超过180个字符！");' . '</script>';
            } else {
                echo '<script type="text/javascript">' . 'alert("Your subject should not exceed 180 characters.");' . '</script>';
            }
            return;
        }
        if (strlen($content) > 2000) {
            if (International::$languageType == 'zh') {
                echo '<script type="text/javascript">' . 'alert("您的留言不能超过2000个字符！");' . '</script>';
            } else {
                echo '<script type="text/javascript">' . 'alert("Your message should not exceed 2000 characters.");' . '</script>';
            }
            return;
        }
        $userModel = User_Model::getInstance();
        if ($userModel->addMessageLog($gsname, $username, $tel, $email, $qq, $msn, $subject, $content)) {
            if (International::$languageType == 'zh') {
                echo '<script type="text/javascript">' . 'alert("您的留言提交成功！");' . '</script>';
            } else {
                echo '<script type="text/javascript">' . 'alert("Your message submitted successfully!");' . '</script>';
            }
        } else {
            if (International::$languageType == 'zh') {
                echo '<script type="text/javascript">' . 'alert("您的留言提交失败，请联系我们！");' . '</script>';
            } else {
                echo '<script type="text/javascript">' . 'alert("Your message to submit failure, please contact us!");' . '</script>';
            }
        }
        return;
    }

    /**
     * 新闻中心 
     */
    public function action005($page) {
        View::$layoutFile = 'user.php';
        View::$templateFile = 'xwzx.html';
        $userModel = User_Model::getInstance();
        $page = $page ? $page : 1;
        $a = 15;
        $html_data = $userModel->getNews($page, $a, International::$languageType);
        unset($html_data[$a - 1]);
        $tolePage = 0;
        $count = $userModel->getNewsCount();
        $tolePage = $count / $a;
        $tolePage = ceil($tolePage);
        View::addDisplayContent(array('html_data' => $html_data, 'html_page1' => $tolePage, 'html_page2' => $page, 'html_count' => $a));
        return;
    }

    public function action006($id) {
        View::$layoutFile = 'user.php';
        View::$templateFile = 'xwzx1.html';
        $userModel = User_Model::getInstance();
        $html_data = $userModel->getNewsByID($id);
        View::addDisplayContent(array('html_data' => $html_data));
        return;
    }

}

?>
