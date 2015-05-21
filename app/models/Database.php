<?php
/**
 * This is a singleton class;
 */
class Database {

  static private $_instance = null;

  private $_params;
  private $_db;

  static function getInstance(){ 
    if(self::$_instance == null){
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  private function __construct() {
    $this->_params = parse_ini_file('../config.ini');
    $this->_db = new PDO(
                $this->_params['db.connect'],
                $this->_params['db.user'],
                $this->_params['db.password']
                );
    //utf-8
    $this->_db->exec("SET NAMES utf8");
    $this->_db->exec("SET CHARACTER SET utf8");
    $this->_db->exec("SET SESSION collation_connection = utf8_general_ci");
  }

  private function __clone() {}

  public function __call ( $method, $args ) {
    if ( is_callable(array($this->_db, $method)) ) {
      return call_user_func_array(array($this->_db, $method), $args);
    }
    else {
      throw new BadMethodCallException('Undefined method Database::' . $method);
    }
  }

}