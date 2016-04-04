<?php
/**
 * Description of Config class
 * - content of important parameters and setting, using inside child classes 
 * - child and its method can use config methods to gain right parameters and work with 
 * - give me what I need (no global)
 * @author pesy
 */
class Config {
  
  //----------------------------------------------------------------------------
  
  /*DB section*/
  
  private $cf_db_server = 'localhost';
  private $cf_db_user = 'root';
  private $cf_db_password = '';
  private $cf_db_database = 'socialsp2_test';
 
  /* DB - socialsprinters - deployment test*/
  /*private $cf_db_server = '127.0.0.1';
  private $cf_db_user = 'socialsp';
  private $cf_db_password = 'fhahfkg';
  private $cf_db_database = 'socialsp2_test';*/
  /* /DB - socialsprinters - test*/
  
  /*/DB section*/
  
  //----------------------------------------------------------------------------
  
  /*SMARTEMAILING section*/
  
  /*@var $cf_se_token - token to access API SE, credentials*/
  private $cf_se_token = 'TApH2gLh2cKKf00ehlcAFPMHZ6w1OpjocvYXCeDO';
  /*@var $cf_se_username - username to access API SE, credentials*/
  private $cf_se_username = 'tomas.vans@seznam.cz';  
  
  /*/SMARTEMAILING section*/
  
  //----------------------------------------------------------------------------
  //----------------------------------------------------------------------------
  
  /**
   * Get database credentials data
   * @return array array of connection values (server, user, password, database)
   * @author pesy
   */
  protected function getDbCredentials() {
    return array("s"=>$this->cf_db_server,"u"=>$this->cf_db_user,"p"=>$this->cf_db_password,"d"=>$this->cf_db_database);
  }

  /**
   * Get SmartEmailing credentials data
   * @return array array of credential values (token, username)
   * @author pesy
   */  
  protected function getSECredentials() {
    return array("token"=>$this->cf_se_token,"username"=>$this->cf_se_username);
  }  
  
}