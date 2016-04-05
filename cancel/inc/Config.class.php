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
  
  /*CONSTANTS section*/
  
  /*@const CONF_BASE_DIR - base directory of project*/
  const CONF_BASE_DIR = "e:/xampp/htdocs/x51/dev-pesy/"; // localhost 
  /*@const CONF_HOSTNAME - root hostname url*/
  const CONF_HOSTNAME = "http://localhost/x51/"; // localhost
  /*@const CONF_HOSTFOLDER - folder hostname url*/
  const CONF_HOSTFOLDER = 'dev-pesy/'; // localhost 
  /*@const LOGS_DIR_NAME - folder name for logger*/
  const LOGS_DIR_NAME = "logs/"; // localhost
  /*@const SITE_NAME - site name*/
  const SITE_NAME = '';
  /*@const VERSION - version number*/
  const VERSION = '1.0';

  /* socialsprinters - deployment test, pesy*/
  /*const CONF_BASE_DIR = "/web/www.socialsprinters.cz/dev-pesy/";
  const CONF_HOSTNAME = 'http://www.socialsprinters.cz/';*/
  
  /* /CONSTANTS section*/
  
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
  
  /*GOPAY section*/
  
  /*/GOPAY section*/
  
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
  
  protected function getGopayConfig() {
    
  }
  
}