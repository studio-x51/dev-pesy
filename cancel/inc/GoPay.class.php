<?php
/**
 * Description of GoPay
 *
 * @author pesy
 * 
 * Zruseni opakovane platby - vypne se premium clenstvi v momente, kdy se mela ztrhavat dalsi platba
 * https://doc.gopay.com/cs/?_ga=1.232229151.1006017523.1456391276#zru%C5%A1en%C3%AD-opakov%C3%A1n%C3%AD-platby
 * 
 * Vraceni platby - vypne se premium clenstvi okamzite
 * https://doc.gopay.com/cs/?_ga=1.232229151.1006017523.1456391276#refundace-platby-(storno)
 * 
 * Testovaci platby a nastaveni
 * https://help.gopay.com/cs/tema/integrace-platebni-brany/integrace-nova-platebni-brany/provadeni-plateb-v-testovacim-prostredi
 */


class GoPay extends Base {
  
  /*Kulturne.com - test gateway */
  private $client_id = 1694553993;
  private $client_secret = '5GTJzZkw';
  private $goId = 8480919755;
  
	/*Kulturne.com - production gateway */
  //private $client_id = 1129978971; // kulturne.com
  //private $client_secret = 'Ykvd2E7y'; // kulturne.com
  //private $goId = 8455597172
  
	/*SS - test gateway */
  //private $client_id = 1317115481; 
  //private $client_secret = 'cvy27sbf';  
  
  /*sandbox urls*/
  private $sandbox_gopay_url = 'https://gw.sandbox.gopay.com/api/';
  private $sandbox_gopay_token_url = "https://testgw.gopay.cz/api/oauth2/token";
  private $sandbox_gopay_payment_url = "https://testgw.gopay.cz/api/payments/payment";
  private $sandbox_gopay_js_embed = "https://testgw.gopay.cz/gp-gw/js/embed.js";
  
  /*production urls*/
  private $gopay_torent_url = "https://gate.gopay.cz/api/oauth2/token";
  private $gopay_payment_url = "https://gate.gopay.cz/api/payments/payment";
  private $gopay_js_embed = "https://gate.gopay.cz/gp-gw/js/embed.js";

  private $config;
  
  public function __construct($isSandbox = false) {
  	if ($isSandbox) {
  		// TODO - logger
  	 	// TODO - definition config in Base
  		// TOTO - definition of url token order isSandbox value
  		//$this->config = parent::getGopayConfig(production);
		} 
  	//$this->config = parent::getGopayConfig(sandbox);
	}
  
  /**
   * Standard token - payment-create only token
   * @return array - json data array of result   
   */
  public function getStandardToken() {
    $ch = curl_init();
    $credentials = $this->client_id.':'.$this->client_secret;
    $data = 'grant_type=client_credentials&scope=payment-create';
    curl_setopt($ch, CURLOPT_URL, $this->sandbox_gopay_token_url);
    curl_setopt($ch, CURLOPT_POST, 1); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-form-urlencoded', 'Accept: application/json', "Authorization: Basic " . base64_encode($credentials)));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);     
		ob_start(); 
		// grab URL and pass it to the browser
		$result = curl_exec($ch);
    if ($result === false) {
      echo "Chyba v zasilani GoPay requestu!";
    } else {
    	// close cURL resource, and free up system resources
			curl_close($ch);
			$str = ob_get_clean();
			//echo $str; 
			return json_decode($str);
    }			
  }
  
  
	/**
	* Utility token - payment-all - for other operation (state, refund, payment)
	* @return array - json data array of result	
	*/
	public function getPaymentToken() {
		$ch = curl_init();
		$credentials = $this->client_id.':'.$this->client_secret;
		$data = "grant_type=client_credentials&scope=payment-all";
		curl_setopt($ch, CURLOPT_URL, $this->sandbox_gopay_token_url);
		curl_setopt($ch, CURLOPT_POST, 1); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-form-urlencoded', 'Accept: application/json', "Authorization: Basic " . base64_encode($credentials)));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		ob_start(); 
		// grab URL and pass it to the browser
		$result = curl_exec($ch);
    if ($result === false) {
      echo "Chyba v zasilani GoPay requestu!";
    } else {
    	// close cURL resource, and free up system resources
			curl_close($ch);
			$str = ob_get_clean();
			//echo $str; 
			return json_decode($str);
    }			
	}  
	
	
	public function createPayment() {
		$paymentToken = $this->getPaymentToken();
		$data = array(
			"payer" => array(
				"default_payment_instrument"=>"PAYMENT_CARD", // defaulten nastavene, ale lze prepnout platebni metodu - nahore nad oknem!
				"allowed_payment_instruments"=> array("PAYMENT_CARD", "MPAYMENT"), // vsechny platebni metody
				"contact" => array(
					"first_name"=>'Jan',
					"last_name"=>'Nepomuk',
					"email"=>'info@kulturne.com',
					"phone_number"=>"+420724528287",
					"city"=>"Tábor",
					"street"=>"Kpt.Jaroše 2381",
					"postal_code"=>"390 01",
					"country_code"=>"CZE"
					)
			),
			"target" => array(
				"type"=>"ACCOUNT",
				"goid"=>$this->goId
			),
			"amount"=> '1666',
			"currency"=> 'CZK',
			"order_number"=> '10000002',
			"order_description" => 'testovaci platba z API',
			"items"=> array(
				//array("name"=>"item01","amount"=>"1000"),
				//array("name"=>"item02","amount"=>"666")
			),
			// !!! POZOR nemenit poradi, zapisuji podle indexu !!!
			"additional_params" => array(
				array("name"=>"from","value"=>'5.4.2016'),
				array("name"=>"to","value"=>'5.5.2016'),
				array("name"=>"months","value"=>'1'), // delka trvani v mesicich
				array("name"=>"druh_platby","value"=>'standard'), // druh platby standard, premium, .../ defaultne standard
				// max. 4 parametry, jinak nemaka :-(			
			), 
			"callback"=> array(
				"return_url"=> 'http://www.petrsyrny.cz/return',
				"notification_url"=> 'http://www.petrsyrny.cz/notify',
			),
			"lang"=>'cs'
		);
	
		//logit("debug","GOPAY createPayment gopay-payment_url: ".$CONF["gopay-payment_url"],$CONF_BASE_DIR."logs/gopay_create_payment.log");
		//logit("debug","GOPAY createPayment data: ".serialize($data),$CONF_BASE_DIR."logs/gopay_create_payment.log");
	
		$data_string = json_encode($data);
		// create a new cURL resource
		$ch = curl_init();
		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_URL, $this->sandbox_gopay_payment_url);
		curl_setopt($ch, CURLOPT_POST, 1); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Accept: application/json',
			'Content-Type: application/json',                                                                                
			'Authorization: Bearer '.$paymentToken->access_token
	//		'Content-Length: ' . strlen($data_string)
			)
		);
	
		ob_start(); 
		// grab URL and pass it to the browser
		curl_exec($ch);
		// close cURL resource, and free up system resources
		curl_close($ch);
		$str = ob_get_clean(); 
		//echo $str;
		//logit("debug","GOPAY createPayment syrove: fb_id=".$_SESSION["user"][APLIKACE_UNIQ_ID].",".$str,$CONF_BASE_DIR."logs/gopay_create_payment.log");
		return json_decode($str);
	}
  
  
}
