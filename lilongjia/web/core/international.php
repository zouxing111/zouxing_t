<?php

/**
 *
 * Haypi Inc,.
 *
 */
class International {

    private static $object;
    public static $languageType = 'en';
    private static $languages = array();

    public static function getInstance() {
        if (!self::$object)
            self::$object = new International();
        return self::$object;
    }

    /**
     * 设置显示语言
     */
    public static function setLanguageType($languageType) {
        self::$languageType = $languageType;
    }

    /**
     * 获取多语言内容
     * memcache
     */
    public static function getLanguage($module, $id, $args = array()) {
        $module = strtolower($module);
        if (!array_key_exists($module, self::$languages)) {
            require INTERNATIONAL_PATH . (self::$languageType) . DIRECTORY_SEPARATOR . $module . '.php';
            self::$languages[$module] = $languages;
        }
        if (!isset(self::$languages[$module][$id])) {
            require INTERNATIONAL_PATH . (self::$languageType) . DIRECTORY_SEPARATOR . $module . '.php';
            self::$languages[$module] = $languages;
        }
        return $args ? vsprintf(self::$languages[$module][$id], $args) : self::$languages[$module][$id];
    }

    /**
     * 获取多语言内容
     * XCache
      public static function getLanguage($module, $id, $args = array()) {
      $module = strtolower($module);
      if (!array_key_exists($module, self::$languages)) {
      $moduleLanguages = xcache_get('kingdomlan'.self::$languageType.$module);
      if ($moduleLanguages && array_key_exists($id, $moduleLanguages)) {
      self::$languages[$module] = $moduleLanguages;
      } else {
      require INTERNATIONAL_PATH . (self::$languageType) . DIRECTORY_SEPARATOR . $module . '.php';
      xcache_set('kingdomlan'.self::$languageType.$module, $languages, 0);
      self::$languages[$module] = $languages;
      }
      }
      if (!isset(self::$languages[$module][$id])) {
      require INTERNATIONAL_PATH . (self::$languageType) . DIRECTORY_SEPARATOR . $module . '.php';
      xcache_set('kingdomlan'.self::$languageType.$module, $languages, 0);
      self::$languages[$module] = $languages;
      }
      return $args ? vsprintf(self::$languages[$module][$id], $args) : self::$languages[$module][$id] ;
      }
     */
}

?>