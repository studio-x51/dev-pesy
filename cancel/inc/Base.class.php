<?php
/**
 * Description of Base abstract class
 *
 * @author pesy
 */
abstract class Base extends Config {
  
  /*@var for definition mysql instance*/
  private $mysql;
  
  /**
   * Method to make connection to DB
   * @return object instance of mysql (parameters from parent class Config)
   * @author pesy
   */
  protected function createDbConnection() {
    $conn_params = parent::getDbCredentials();
    $this->mysql = new Mysql($conn_params['s'], $conn_params['u'], $conn_params['p'], $conn_params['d']);
    return $this->mysql;
  }
  
  /** 
   * Escape string, from input, insert to database etc.
   * @param string $string value to escape
   * @return string escaped string
   * @author pesy petr.syrny@x51.cz
   */
  public function toSafeData($string) {
    if (get_magic_quotes_gpc()) {
      $string_conv = stripslashes($string);
    }
    $string_conv = mysql_real_escape_string(htmlspecialchars($string));
    return trim($string_conv);
  }     
  
	/**
	 * Keep value of form input, mainly for POST
   * @param string $form_element - value (name) of element $_POST['']
   * @return string value of input, only for type = 'text' || area
	 * @author pesy petr.syrny@x51.cz
	*/
  public function keepValueRet($form_element) {
    if (isset($form_element)) { return (string)$form_element; }
  }  
  
}