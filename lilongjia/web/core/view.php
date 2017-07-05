
<?php

/**
 *
 * Haypi Inc,.
 *
 */
class View {
    /* 当前准备显示的布局文件 */

    static public $layoutFile;
    /* 当前准备显示的模板文件 */
    static public $templateFile;
    /* 存放在模板中用来显示的变量，
     * key：模板的名称
     * key: common：通用变量
     */
    static public $displayContent = array();
    static public $redirectUrl;

    public function __construct() {
        
    }

    /**
     * 返回显示内容
     */
    static public function render() {
        if (!self::$layoutFile) {
            return '';
        }
        ob_start();
        extract(array_key_exists('common', self::$displayContent) ? self::$displayContent['common'] : array());
        extract(array_key_exists(self::$templateFile, self::$displayContent) ? self::$displayContent[self::$templateFile] : array());
        $html_templateFile = self::$templateFile;
        include TEMPLATE_PATH . 'layouts/' . self::$layoutFile;
        self::clearDisplayContent();
        return ob_get_clean();
    }

    /**
     * 增加 Body 显示变量
     */
    static public function addDisplayContent($newdisplayContent, $isCommonContent = false) {
        if ($isCommonContent) {
            self::$displayContent['common'] = array_merge(array_key_exists('common', self::$displayContent) ? self::$displayContent['common'] : array(), $newdisplayContent);
        } else {
            self::$displayContent[self::$templateFile] = array_merge(array_key_exists(self::$templateFile, self::$displayContent) ? self::$displayContent[self::$templateFile] : array(), $newdisplayContent);
        }
    }

    /**
     * 清除显示内容
     */
    static public function clearDisplayContent() {
        unset(self::$displayContent[self::$templateFile]);
    }

    /**
     * 设置布局文件和模板文件
     */
    static public function setLayoutAndTemplateFile($layoutFile, $templateFile) {
        self::$layoutFile = $layoutFile;
        self::$templateFile = $templateFile;
    }

}

?>