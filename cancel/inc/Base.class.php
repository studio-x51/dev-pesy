<?php
namespace Inc;
//include_once ('lib.php');
include_once ('database.php');
include_once ('Mysql.class.php');

/**
 * Description of Base abstract class
 *
 * @author pesy
 */
abstract class Base {
  
  /*@var for definition mysql instance*/
  private $mysql;
  
  /**
   * Method to make connection to DB
   * @return object instance of mysql
   * @author pesy
   */
  protected function makeDbConnection() {
    global $database;
    $this->mysql = new mysql($database['server'], $database['user'],$database['password'], $database['database']);
    return $this->mysql;
  }
  
}