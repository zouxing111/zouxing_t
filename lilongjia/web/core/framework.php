<?php

/**
 *
 * Haypi Inc,.
 *
 */
class FrameWork {

    private static $events = array('start' => array(), 'shutdown' => array());
    private $controllername = '';
    private $methodname = '';
    private $argumentarray = array();

    static public function getInstance() {
        static $obj;
        if (!is_object($obj)) {
            $obj = new FrameWork();
        }
        return $obj;
    }

    private function __construct() {
        spl_autoload_register(array($this, 'autoload'));
    }

    public function registerEvents($startevents, $shutdownevents) {
        self::$events['start'] = $startevents;
        self::$events['shutdown'] = $shutdownevents;
    }

    /**
     * return bool
     */
    private function start() {
        return $this->runevents('start');
    }

    private function dispatch() {
        $class = ucfirst($this->controllername) . '_Controller';
        if (!class_exists($class)) {
            require CONTROLLER_PATH . strtolower($this->controllername) . '.php';
        }
        $obj = new $class;
        call_user_func_array(array($obj, $this->methodname), $this->argumentarray);
    }

    private function shutdown() {
        $this->runevents('shutdown');
    }

    /**
     * return bool
     */
    private function runevents($eventname) {
        if (self::$events[$eventname]) {
            foreach (self::$events[$eventname] as $event) {
                if (!$event) {
                    continue;
                }
                $class_name = $event . '_Event';
                require EVENT_PATH . strtolower($event) . '.php';
                $object = new $class_name;
                if (!call_user_func(array($object, 'run'))) {
                    return false;
                }
            }
        }
        return true;
    }

    private function autoload($class) {
        if (class_exists($class))
            return;
        if ($pos = strrpos($class, '_')) {
            $name = substr($class, 0, $pos);
            switch (strtolower(substr($class, $pos + 1))) {
                case 'controller':
                    require CONTROLLER_PATH . strtolower($name) . '.php';
                    break;
                case 'event':
                    require EVENT_PATH . strtolower($name) . '.php';
                    break;
                case 'interface' :
                    require CORE_PATH . strtolower($name) . '.php';
                    break;
                case 'library':
                    require LIBRARY_PATH . strtolower($name) . '.php';
                    break;
                case 'model' :
                    require MODEL_PATH . strtolower($name) . '.php';
                    break;
                case 'view' :
                    require VIEW_PATH . strtolower($name) . '.php';
                    break;
            }
        } else {
            require CORE_PATH . strtolower($class) . '.php';
        }
    }

    public function run($controllerName, $methodName, $argumentArray) {
        $this->controllername = $controllerName;
        $this->methodname = $methodName;
        $this->argumentarray = $argumentArray;

        if ($this->start()) {
            $this->dispatch();
        }
        $this->shutdown();
    }

}

?>