<?php
/**
 * Description of cancelForm class
 *
 * @author pesy petr.syrny@x51.cz
 */
class CancelForm extends Base {

  /*@const OWN_FOLDER - variable foldername, definition logger path*/
  const OWN_FOLDER = 'cancel/';
  
  /*@var $tbl_owner - table owner*/
  public $tbl_owner = 'owner';
  /*@var $owner_data - data of owner - selected by email*/
  public $owner_data = array();
  /*@var $errorMessage - value of error message*/
  public $errorMessage = null;
  /*@var $successMessage - value of success message*/
  public $successMessage = null;
  /*@var $log - instance of logger object*/
  private $log;
  /*@var $log_path - variable definition of logger directory path, where save file*/
  private $log_path;
  /*@var $log_filename - variable definition of logger save filename*/
  private $log_filename = 'premium_cancel.log';

  
  /** Class constructor, initialization of mysql object
   * - access to mysql methods via $this->db in parent
   * - initialize logger class - own save path definition
   */
  public function __construct() {
    parent::createDbConnection();
    $this->log_path = Config::CONF_BASE_DIR.self::OWN_FOLDER.Config::LOGS_DIR_NAME;
    $this->log = new Logger($this->log_path, $this->log_filename);
  }
  
  /**
   * Try to find premium owner in database via email address
   * @param string $email email
   * @return array owner data if success, else nothing
   */  
  private function ownerEmailExists($email) {
    $query = "SELECT * 
                FROM  ".$this->tbl_owner."
               WHERE typ = 'premium'
                 AND ((email = '".$email."' OR email_contact = '".$email."'))
                 AND dt_request_cancel IS NULL
                 AND status = 'active'
             ";
		$this->db->query($query, $result_user);
		$row = $this->db->fetchArrayResult($result_user, MYSQL_ASSOC);
    $this->owner_data = $row;
    if (is_array($row)&&(count($row)>0)) return true;
  }

  /**
   * Get owner array
   * @return array owner data if success, else is empty array
   * @see ownerEmailExists
   */
  public function getOwnerData() {
    return $this->owner_data;
  }
  
  /**
   * Submit action for cancel form
   * - check input values, set error message, header if success
   */
  public function processCancelForm($vals) {
    $firstname = trim($vals['cancel_firstname']);
    $lastname = trim($vals['cancel_lastname']);
    $email = trim($vals['cancel_email']);
    $cancel_reason = trim($vals['cancel_reason']);
    $cancel_notice = trim($vals['cancel_notice']);
    $submit = $vals['cancel_send'];
    
    if (isset($submit)) {
      if (empty($firstname)||(empty($lastname))||(empty($email))) {
        $this->errorMessage = 'Zadejte všechna pole označená <strong>*</strong>';
      } elseif (!empty($email)&&(!filter_var($email,FILTER_VALIDATE_EMAIL))) {
        $this->errorMessage = 'Zadejte platnou emailovou adresu - xxxx@xxx.xx';
      } elseif (!$this->ownerEmailExists($email)) {
        $this->errorMessage = 'Vámi <strong>zadaný e-mail neznáme</strong>.<br /><br /> Zadejte prosím e-mail, který je <strong>propojený s Vaším osobním facebookovým účtem, pod kterým se přihlašujete do SocialSprinters</strong>.';
        $this->log->logit('error','neznamy email, email: '.$email.', uzivatel: '.$lastname.' '.$firstname.'');
      }
      // no error - continue to process request
      // set timestamp in column DT_REQUEST_CANCEL
      if ($this->getErrorMessage() == null) {
        $fb_id = strval($this->owner_data['fb_id']);
        // update owner column DT_REQUEST_CANCEL, CANCEL_REASON, CANCEL_NOTICE
        if ($this->setOwnerCancelRequest($fb_id, $cancel_reason, $cancel_notice)) {
          $this->log->logit('debug','zadost ulozena, email: '.$email.', uzivatel: '.$lastname.' '.$firstname.'');
          $se = new SmartEmailing();
          if ($se->removeEmailFromTo($email, SmartEmailing::$premium_list_id, SmartEmailing::$premium_cancel_id) == true) {
            $this->log->logit('debug','smartemailing prevod kontaktu, email: '.$email.', uzivatel: '.$lastname.' '.$firstname.', from: '.SmartEmailing::$premium_list_id.' do: '.SmartEmailing::$premium_cancel_id.'');
            $_SESSION['cancel_send_success'] = true;
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
            header('Location: '.$protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
            exit();                      
          }
        }
      }  
    }
    if ($_SESSION['cancel_send_success']) {
      $this->setSuccessMessage("<strong>Vaše žádost byla zaznamenána.</strong>");
      $this->errorMessage = null;
      $_SESSION['cancel_send_success'] = false;
		}	      
	}  	 
  
  /**
   * Process owner cancel request, set - update column DT_REQUEST_CANCEL 
   * @param string $fb_id primary key of owner, identification
   * @return boolean true if success
   */
  public function setOwnerCancelRequest($fb_id, $cancel_reason, $cancel_notice) {
    if ($fb_id != null) {
      $query_upd = "UPDATE ".$this->tbl_owner."
                       SET dt_request_cancel = CURRENT_TIMESTAMP,
                           cancel_reason = '".intval($cancel_reason)."',
                           cancel_notice = '".parent::toSafeData($cancel_notice)."'
                     WHERE fb_id = '".trim($fb_id)."'
                       AND status = 'active'
                       AND typ = 'premium'
                    "; 
      $this->db->query($query_upd, $result_upd);
      if ($result_upd) {
        return true;
      }
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
      return '<div class="alert alert-success tac" role="alert">'.$this->successMessage.'</div>';
    }
  }  
  
}
