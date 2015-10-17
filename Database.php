<?php

/**
 * PDO Database class
 *
 * @author Светлин Боболанов
 * 
 */
 define('DBHOST','localhost');
 define('DBUSER','root');
 define('DBPASS','');
 define('DBDBD','mydb');
 
 
class Database {

    private $dbconn = NULL;
    private static $instance = NULL;
    private $stmt = NULL;

    private function __construct() {
        $dsn = 'mysql:host=' . DBHOST . ';dbname=' . DBDB;
        $options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            PDO::ATTR_PERSISTENT => false
        );
        $this->dbconn = new PDO($dsn, DBUSER, DBPASS, $options);
        if (!$this->dbconn) {
            throw new \Exception('Database::__construct() - There was an error while connecting to MySQL!');
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function query($sql)
    {
        if (!$sql) {
            throw new \Exception('Database::query() - You must provide a valid query!');
        }
        return $this->dbconn->query($sql);
    }

    public function prepare($sql) {
        if (!$sql) {
            throw new \Exception('Database::prepare() - You must provide a valid query!');
        }
        $this->stmt = $this->dbconn->prepare($sql);
    }

    public function bindParam($pos, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($pos, $value, $type);
    }

    public function execute() {
        return $this->stmt->execute();
    }
    
    public function getResults()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRow()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function __clone() {
        throw new Exception('Database::__clone() - The class could not be cloned!');
    }
    
    

}


