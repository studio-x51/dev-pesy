<?php
/**
 * Description of Logger class
 * - logger class can log actions into file or database
 * @author pesy
 */
class Logger extends Base {
  
  /*@var $tbl_logger - table logger*/
  private $tbl_logger = 'logger';
  
  public function __construct() {
    // myslq connect?
  }

  public static function logit() {
    echo 'LogIt';
  }
  
  public function logitDb() {
    parent::createDbConnection(); 
    $query = "SELECT * 
                FROM  owner
             ";
    $test = $this->getQuery($query);
    echo count($test);
  }
  
}