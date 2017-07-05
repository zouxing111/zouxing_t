<?php

/**
 *
 * Haypi Inc,.
 *
 */
class Log_Model extends Model {

    private static $object;

    public static function getInstance() {
        if (!self::$object)
            self::$object = new Log_Model ();
        return self::$object;
    }

    /**
     * 记录日志
     * @param <type> $log 记录的内容
     * @param <type> $logPolicy 日志重要性 1：重要 一个小时记录一个文件 2：次重要 一天记录一个文件 3：不重要 一个月记录一个文件
     * @param <type> $logID 
     * @param <type> $logType 日志类型
     * @param <type> $logFileName 日志文件名
     * @param <type> $logTime 日志记录的时间
     */
    public static function logToFile($logID, $log, $logType = 'debug', $logPolicy = 1, $logFileName='', $logTime=0) {
        $actionID = Registry::get('actionid') ? Registry::get('actionid') : 'noactionid';
        $logDate = date('Y-m-d H:i:s', $logTime ? $logTime : Registry::get('servertime'));
        $logUsername = '';// Online_Model::$selfUsername;
        $log = (is_array($log)) ? print_r($log, true) : $log;
        if ($logPolicy == 1) {
            $logFileName = $logFileName ? (LOG_PATH . $logFileName . '_' . date('Y-m-d H') . '_' . $logType . '.txt') : (LOG_PATH . date('Y-m-d H') . '_' . $logType . '.txt');
        } elseif ($logPolicy == 2) {
            $logFileName = $logFileName ? (LOG_PATH . $logFileName . '_' . date('Y-m-d') . '_' . $logType . '.txt') : (LOG_PATH . date('Y-m-d') . '_' . $logType . '.txt');
        } elseif ($logPolicy == 3) {
            $logFileName = $logFileName ? (LOG_PATH . $logFileName . '_' . date('Y-m') . '_' . $logType . '.txt') : (LOG_PATH . date('Y-m') . '_' . $logType . '.txt');
        } else {
            $logFileName = $logFileName ? (LOG_PATH . $logFileName . '_' . date('Y') . '_' . $logType . '.txt') : (LOG_PATH . date('Y') . '_' . $logType . '.txt');
        }
        $file = fopen($logFileName, 'ab');
        fwrite($file, $logDate . "\r\n" . $logID . "\r\n" . $logUsername . "\r\n" . $actionID . "\r\n" . $_SERVER['REMOTE_ADDR'] . "\r\n" . $log . "\r\n\r\n");
        fclose($file);
    }

}
?>
