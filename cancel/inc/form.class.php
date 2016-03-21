<?php
/**
 * Description of form class
 *
 * @author pesy
 */
class Form {

  /*@var array of errors*/
  public $error = array(); 
  /*@var value of error message*/
  public $errorMessage = null;
  /*@var value of success message*/
  public $successMessage = null;
  
  /** Funkce oescapuje string z inputu, pro vlozeni do db
   * @param string $string value to escape
   * @return string escaped string
   * @author pesy petr.syrny@x51.cz
   */
  static function toSafeData($string) {
    if (get_magic_quotes_gpc()) {
      $string = stripslashes($string);
    }
    $string = mysql_real_escape_string(htmlspecialchars($string));
    return $string;
  }    
  
	/**
	* Fuknce se stara o drzeni hodnoty v danem elementu formulare, odeslani POSTu
   * @param string $form_element - hodnota (name) elementu $_POST['']
   * @return string value of input, only for type = 'text' || area
	 * @author pesy petr.syrny@x51.cz
	*/
	public function keepValueRet($form_element) {
		if (isset($form_element)) return (string)$form_element;
	}	  
  
  /** Funkce vraci pole chyb
   */
  public function getError() {
    if (isset($this->error)&&(is_array($this->error))&&(count($this->error)>0)) {
      return $this->error;
    }
    return;
  }
  
	public function sendCancelForm($vals) {
    $firstname = self::toSafeData($vals['cancel_firstname']);
    $lastname = self::toSafeData($vals['cancel_lastname']);
	  $email = self::toSafeData($vals['cancel_email']);
    $submit = $vals['cancel_send'];
    
    if (isset($submit)) {
      if (empty($firstname)||(empty($lastname))||(empty($email))) {
        $this->errorMessage = 'Zadejte všechna pole označená <strong>*</strong>';
      }
      if (!empty($email)&&(!filter_var($email,FILTER_VALIDATE_EMAIL))) {
        $this->errorMessage = 'Zadejte platnou emailovou adresu - xxxx@xxx.xx';
      }      
      if ($this->getErrorMessage() == null) {
        $_SESSION['cancel_send_success'] = true;
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
        header('Location: '.$protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
        exit();          
      } else {
        return false;
      }
    }
		if ($_SESSION['cancel_send_success']) {
      $this->setSuccessMessage("<strong>Vaše žádost byla zaznamenána.</strong>");
      $this->errorMessage = null;
      $_SESSION['cancel_send_success'] = false;
		}	      
	}  	  
  
  /** SETter for error message
   * @param string - message
   */
  public function setErrorMessage($text) {
    $this->errorMessage = $text;
  }    

  /** GETter of error message
   * @return string value of variable $errorMessage
   * @see setErrorMessage
   * @todo CASE for style of message - naturally text or alert type {<div>}
   * @author pesy
   */
  public function getErrorMessage() {
    if (isset($this->errorMessage)&&(!empty($this->errorMessage))) {
      return '<div class="alert alert-danger" role="alert">'.$this->errorMessage.'</div>';
    }
  }   
  
  /** SETter for succes message
   * @param string - message
   */
  public function setSuccessMessage($text) {
    $this->successMessage = $text;
  }  
  
  /** GETter of success message
   * @return string value of variable $successMessage
   * @see setSuccessMessage
   * @todo CASE for style of message - naturally text or success type {<div>}
   * @author pesy
   */
  public function getSuccessMessage() {
    if (isset($this->successMessage)&&(!empty($this->successMessage))) {
      return '<div class="alert alert-success text-center" role="alert">'.$this->successMessage.'</div>';
    }
  }  
  
}