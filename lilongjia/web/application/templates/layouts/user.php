<?php

if (International::$languageType == 'zh') {
    require TEMPLATE_PATH . 'header_zh.html';
} else {
    require TEMPLATE_PATH . 'header_en.html';
}
include TEMPLATE_PATH . $html_templateFile;
if (International::$languageType == 'zh') {
    require TEMPLATE_PATH . 'footer_zh.html';
} else {
    require TEMPLATE_PATH . 'footer_en.html';
}
?>
