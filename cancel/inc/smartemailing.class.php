<?php
/**
 * Description of SmartEmailing class
 *
 * @author pesy petr.syrny@x51.cz
 * @todo LOGOVANI CHYB
 */
class SmartEmailing {

  /*@var static value - id of testing contact list in SE account*/
  private static $pesy_test_list_id = 94;
  /*@var static value - id of premium contact list - users with premium*/
  private static $premium_list_id = 69;
  /*@var static value - id of premium cancel contact list - premium cancel request*/
  private static $premium_cancel_id = 95;
  /*@var static value - token to access API SE, credentials*/
  private $token = 'TApH2gLh2cKKf00ehlcAFPMHZ6w1OpjocvYXCeDO';
  /*@var static value - username to access API SE, credentials*/
  private $username = 'tomas.vans@seznam.cz';

  /**
   * Show result
   */
  private function v($result) {
    echo $result;
    //print_r($result);
    die();
  }  
  
  /**
   * Function to set user email into new contact list {$premium_cancel_id} and remove from orifinal {$premium_list_id}
   * @param string $email - user email
   * @return boolean|string - false if email is not set, string when success via sendRequest
   * @see sendRequest()
   */
  public function createCancelAction($email) {
    if (!$email) {return false;}
    $xml = "
      <xmlrequest>
        <username>{$this->username}</username>
        <usertoken>{$this->token}</usertoken>
        <requesttype>Contacts</requesttype>
        <requestmethod>createupdate</requestmethod>
        <details>
          <emailaddress>".$email."</emailaddress>
          <language>cs_CZ</language> <!-- cs_CZ | sk_SK | en_GB -->
          <contactliststatuses>
            <item>
              <id>".self::$pesy_test_list_id."</id>
              <status>removed</status>
            </item>
            <item>
              <id>".self::$premium_cancel_id."</id>
              <status>confirmed</status>
              <!-- unconfirmed | confirmed | unsubscribed | banned | removed -->              
            </item>            
          </contactliststatuses>
        </details>
      </xmlrequest>
      ";   
     return $this->sendRequest($xml);   
  }
  
  /**
   * Function that process SE API requests
   * @param string $xml - xml type request
   * @return boolean|string
   * @todo Logging error, exception etc.
   */
  protected function sendRequest($xml) {
    if(!$xml) {return false;}
    $ch = curl_init('https://app.smartemailing.cz/api/v2');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
    $result = curl_exec($ch);
    if ($result === false) {
      echo "Chyba v zasilani XML requestu!";
    } else {
      header ("Content-Type:text/xml");
      //$this->v($result);
      /** @noinspection PhpUsageOfSilenceOperatorInspection */
      $xml_doc = @simplexml_load_string($result); // intentionally @
      if (!$xml_doc) {
        //echo "ERR in request" . PHP_EOL;
        return "ERR in request";
        //$this->v($result);
      }
      //echo PHP_EOL . $result . PHP_EOL . '------------------' . PHP_EOL;
      //echo 'Status is ' . $xml_doc->status . PHP_EOL;
      if ($xml_doc->status == 'SUCCESS') {
        //print_r($xml_doc->data);
        //return "SUCCESS";
        return true;			
      } else {
        return false;
        //return "ERROR";
        //log - SE ERROR
        //echo $xml_doc->errormessage;
      }
    }
  }  
  
}