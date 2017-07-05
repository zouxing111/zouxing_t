<?php

/**
 *
 * Haypi Inc,.
 *
 */
class Database {

    private static $dbObject;
    private $dbPdo;
    private $dbStatement;

    static public function load($dbsetting = 'local.database') {
        if (!self::$dbObject || !array_key_exists($dbsetting, self::$dbObject))
            self::$dbObject[$dbsetting] = new Database($dbsetting);
        return self::$dbObject[$dbsetting];
    }

    private function __construct($dbsetting) {
        $this->connect($dbsetting);
    }

    private function connect($dbsetting) {
        if ($this->dbPdo)
            return true;
        $dbconfig = Config::get($dbsetting, '');
        $dsn = "mysql:dbname={$dbconfig['dbname']};host={$dbconfig['host']}";
        $this->dbPdo = new PDO($dsn, $dbconfig['user'], $dbconfig['pass']);
        $this->dbPdo->query('set names utf8;');
        return true;
    }

    public function query($sql, $prepare_array = array()) {
        return $this->_query($sql, $prepare_array);
    }

    /**
     * 条件查询
     * @param string $table 表名
     * @param string $select select字段
     * @param string $where where 语句
     * @param array $prepare
     * @param string $condition 附加条件, order by , limit 等
     * @return bool on execute result
     */
    public function select($table, $select = '*', $where = '', $prepare = array(), $condition = '') {
        $where = $where ? ("WHERE {$where}") : '';
        $sql = " SELECT {$select} FROM {$table} $where {$condition}";
        return $this->_query($sql, $prepare);
    }

    /**
     * 删除数据
     * @param string $table 表名
     * @param string $where where 语句，不能为空
     * @param array $prepare
     * @return Bool
     */
    public function delete($table, $where = '', $prepare = array()) {
        if (!$where)
            return false;
        $sql = "DELETE FROM {$table} WHERE {$where}";
        return $this->_query($sql, $prepare);
    }

    /**
     * 插入数据库
     * @param string $table
     * @param array $array
     * @return bool on execute result
     */
    public function insert($table, $array = array()) {
        $sql = " INSERT INTO {$table} ";
        $fields = array_keys($array);
        $values = array_values($array);
        $condition = array_fill(1, count($fields), '?');
        $sql .= "(`" . implode('`,`', $fields) . "`) VALUES (" . implode(',', $condition) . ")";
        return $this->_query($sql, $values);
    }

    /**
     * 更新操作
     * @param string $table 表名
     * @param array $array 更新的数据，键 值对
     * @param string $condition 条件
     * @return bool false on execute fail or rowcount on success;
     */
    function update($table, $set = '', $where = '', $prepare = array()) {
        if (!$where)
            return false;
        $sql = " UPDATE {$table} SET {$set} WHERE {$where}";
        return $this->_query($sql, $prepare);
    }

    /**
     * 取得多行记录集
     * @return array 结果集
     */
    function fetch_all() {
        return $this->dbStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 取得单行记录
     * @return array
     */
    function fetch_row() {
        return $this->dbStatement->fetch(PDO::FETCH_ASSOC);
    }

    function rowcount() {
        return $this->dbStatement->rowCount();
    }

    /**
     * 查询数据表,所有的数据表的查询操作，最终都到这里处理
     * @param string $sql
     * @param array $prepare
     * @return $this
     */
    private function _query($sql, $prepare = array()) {
        $statement = $this->dbPdo->prepare($sql);
        if (!$statement->execute($prepare)) {
            Log_Model::logToFile(__CLASS__ . __FUNCTION__ . '0', $sql, 'error');
            Log_Model::logToFile(__CLASS__ . __FUNCTION__ . '1', $statement->errorInfo(), 'error');
            return false;
        }
        $this->dbStatement = $statement;
        return true;
    }

    function beginTransaction() {
        $this->dbPdo->beginTransaction();
    }

    function commitTransaction() {
        $this->dbPdo->commit();
    }

    function rollBackTransaction() {
        $this->dbPdo->rollBack();
    }

    function lastInsertId() {
        return $this->dbPdo->lastInsertId();
    }

}

?>