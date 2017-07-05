<?php

class Admin_Model extends Model {

    private static $object;

    public static function getInstance() {
        if (!self::$object)
            self::$object = new Admin_Model ();
        return self::$object;
    }

    public function getMessage($startTime, $endTime) {
        $result = array();
        $db = Database::load('local.database');
        if ($db->select('message', '*', "DATE_FORMAT(`time`,'%Y-%m-%d')>=:starttime and DATE_FORMAT(`time`,'%Y-%m-%d')<=:endtime", array(':starttime' => $startTime, ':endtime' => $endTime), 'order by `time` desc') && $db->rowcount()) {
            $result = $db->fetch_all();
        }
        return $result;
    }

    public function addNews($subject, $content, $time, $language) {
        $result = false;
        $db = Database::load('local.database');
        if ($db->insert('news', array('subject' => $subject, 'content' => $content, 'time' => $time, 'language' => $language)) && $db->rowcount()) {
            $result = true;
        }
        return $result;
    }

    public function getNewsList() {
        $result = array();
        $db = Database::load('local.database');
        if ($db->select('news', 'id,`subject`,`time`', '', array(), 'order by `time` desc') && $db->rowcount()) {
            $result = $db->fetch_all();
        }
        return $result;
    }

    public function deleteNews($id) {
        $result = false;
        $db = Database::load('local.database');
        if ($db->delete('news', 'id=:id', array(':id' => $id)) && $db->rowcount()) {
            $result = true;
        }
        return $result;
    }

    public function getNewsByID($id) {
        $result = array();
        $db = Database::load('local.database');
        if ($db->select('news', '*', '`id`=:id', array(':id' => $id)) && $db->rowcount()) {
            $result = $db->fetch_row();
        }
        return $result;
    }

    public function updateNewsByID($id, $subject, $content, $time, $language) {
        $result = false;
        $db = Database::load('local.database');
        if ($db->update('news', '`subject`=:subject,`content`=:content,`language`=:language,`time`=:time', 'id=:id', array(':subject' => $subject, ':content' => $content, ':language' => $language, ':time' => $time, ':id' => $id)) && $db->rowcount()) {
            $result = true;
        }
        return $result;
    }

    public function updateConfig($variable, $value) {
        $result = false;
        $db = Database::load('local.database');
        $sqlResult = array();
        if ($db->select('config', '*', 'variable=:variable', array(':variable' => $variable)) && $db->rowcount()) {
            $sqlResult = $db->fetch_row();
        }

        if ($sqlResult) {
            if ($db->update('config', '`value`=:value', 'variable=:variable', array(':value' => $value, ':variable' => $variable))) {
                $result = true;
            }
        } else {
            if ($db->insert('config', array('variable' => $variable, 'value' => $value)) && $db->rowcount()) {
                $result = true;
            }
        }
        return $result;
    }

}

?>
