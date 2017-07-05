<?php

class User_Model extends Model {

    private static $object;

    public static function getInstance() {
        if (!self::$object)
            self::$object = new User_Model ();
        return self::$object;
    }

    public function addMessageLog($gsname, $username, $tel, $email, $qq, $msn, $subject, $content) {
        $result = false;
        $db = Database::load('local.database');
        if ($db->insert('message', array('gsname' => $gsname, 'username' => $username, 'tel' => $tel, 'email' => $email, 'qq' => $qq, 'msn' => $msn, 'subject' => $subject, 'content' => $content,'time'=>  date('Y-m-d H:i:s', time()))) && $db->rowcount()) {
            $result = true;
        }
        return $result;
    }

    public function getNews($page, $count, $language) {
        $result = array();
        $a = ($page - 1) * $count;
        $b = $count + 1;
        $db = Database::load('local.database');
        if ($db->select('news', '`id`,`subject`,`time`', '`language`=:language', array('language' => $language), "order by `time` desc limit $a,$b") && $db->rowcount()) {
            $result = $db->fetch_all();
        }

        return $result;
    }

    public function getNewsCount() {
        $result = 0;
        $db = Database::load('local.database');
        if ($db->select('news', 'count(id) as count1') && $db->rowcount()) {
            $sqlresult = $db->fetch_row();
            if ($sqlresult) {
                $result = $sqlresult['count1'];
            }
        }
        return $result;
    }

    public function getNewsByID($id) {
        $result = array();
        $db = Database::load('local.database');
        if ($db->select('news', '`subject`,`content`', '`id`=:id', array(':id' => $id)) && $db->rowcount()) {
            $result = $db->fetch_row();
        }
        return $result;
    }

    public function getConfig($variable){
        $result = array();
        $db = Database::load('local.database');
        if ($db->select('config', '*', '`variable`=:variable', array(':variable' => $variable)) && $db->rowcount()) {
            $result = $db->fetch_row();
        }
        return $result;
    }
}

?>
