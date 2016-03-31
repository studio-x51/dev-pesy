<?php
namespace Inc;
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
  protected function createDbConnection() {
    global $database;
    $this->mysql = new mysql($database['server'], $database['user'],$database['password'], $database['database']);
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