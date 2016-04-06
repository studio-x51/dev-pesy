<?php
include_once('Logger.class.php');
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
  
  
  public static $payment_state = array('CREATED'=>'Platba založena',
																			'PAYMENT_METHOD_CHOSEN'=>'Platební metoda vybrána',
																			'PAID'=>'Platba zaplacena',
																			'AUTHORIZED'=>'Platba předautorizována',
																			'CANCELED'=>'Platba zrušena',
																			'TIMEOUTED'=>'Vypršelá platnost platby',
																			'REFUNDED'=>'Platba refundována',
																			'PARTIALLY_REFUNDED'=>'Platba částečně refundována');
																			
	private $payment_data;
  
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
  
  private $log;
  
  public function __construct($isSandbox = false) {
  	$path_file = __DIR__.'/logs';
  	$this->log = new Logger($path_file);
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


	public function setPaymentData(array $paymentData) {
		if (!$paymentData) return;
		$this->payment_data = $paymentData;
	}
	
	public function getPaymentData() {
		if (!is_array($this->payment_data)) return;
		return $this->payment_data;
	}

	
	/**
	 * TODO - argumenty funkce, prenaset definici pole dat (contact apod.)
	 * @param array $paymentData - array of important data to create payment	 	
	 */
	public function createPayment() {
		//if (!$paymentData) return;
		$path_file = __DIR__.'/logs/gopay_create_payment.log';
		$paymentToken = $this->getPaymentToken();
		$pd = $this->getPaymentData();
		$data = array(
			"payer" => array(
				"default_payment_instrument"=>"PAYMENT_CARD", // defaulten nastavene, ale lze prepnout platebni metodu - nahore nad oknem!
				"allowed_payment_instruments"=>array("PAYMENT_CARD"), // vsechny platebni metody mohou byt v poli - array("PAYMENT_CARD", "MPAYMENT")
				"contact" => array(
					"first_name"=>$pd['contact']['first_name'],
					"last_name"=>$pd['contact']['last_name'],
					"email"=>$pd['contact']['email'],
					"phone_number"=>$pd['contact']['phone_number'],
					"city"=>$pd['contact']['city'],
					"street"=>$pd['contact']['street'],
					"postal_code"=>$pd['contact']['postal_code'],
					"country_code"=>$pd['contact']['country_code']
					)
			),
			"target" => array(
				"type"=>"ACCOUNT",
				"goid"=>$this->goId
			),
			"amount"=> '166600', // cena v halerich
			"currency"=> 'CZK',
			"order_number"=> '10000004',
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
				"return_url"=> 'http://www.petrsyrny.cz/utils/gopay/return.php',
				"notification_url"=> 'http://www.petrsyrny.cz/utils/gopay/notify.php',
			),
			"lang"=>'cs'
		);
		$this->log->logit("debug","GOPAY createPayment gopay-payment_url: ".$this->sandbox_gopay_payment_url, $path_file);
		$this->log->logit("debug","GOPAY createPayment data: ".serialize($data), $path_file);
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
		$this->log->logit("debug","GOPAY createPayment syrove : $str", $path_file);
		return json_decode($str);
	}
  
  
	/**
	* Inline gateway
	* - to show gateway, do echo of this function (show button)	
	*/
	public function gateWayInline() {
		ob_start();
		$createPayment = $this->createPayment();
		?>
		<!-- platebni brana -->
		<form action="<?echo $createPayment->gw_url;?>" method="post" id="gopay-payment-button">
			<button name="pay" type="submit">Zaplatit</button>
			<script type="text/javascript" src="<?php echo $this->sandbox_gopay_js_embed?>"></script>
		</form>
		<!-- /platebni brana -->
		<?
		return ob_get_clean();
	}
	
	/**
	* Redirect gateway
	*/
	public function gateWayRedirect() {
		ob_start();
		$createPayment = $this->createPayment();
		?>
		<!-- platebni brana -->
		<form action="<?echo $createPayment->gw_url;?>" method="post">
			<button name="pay" type="submit">Zaplatit</button>
		</form>
		<!-- /platebni brana -->
		<?
		return ob_get_clean();
	}		  


	public function getPaymentState($idpayment) {
		$path_file = __DIR__.'/logs/gopay_notify_state.log';
		$this->log->logit("log", "PLATBA - GoPay->getPaymentState ID=: ".$idpayment, $path_file);
		$getPaymentToken = $this->getPaymentToken();
		$ch = curl_init();
		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_URL, $this->sandbox_gopay_payment_url."/".$idpayment);
		curl_setopt($ch, CURLOPT_HTTPGET, 1); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Accept: application/json',
			'Content-Type: application/x-www-form-urlencoded',
			'Authorization: Bearer '.$getPaymentToken->access_token
	//		'Content-Length: ' . strlen($data_string)
			)
		);
		ob_start(); 
		// grab URL and pass it to the browser
		curl_exec($ch);
		// close cURL resource, and free up system resources
		curl_close($ch);
		$str = ob_get_clean(); 
		$this->log->logit("log", "PLATBA - GoPay->getPaymentState CURL: ".$str, $path_file);
		return json_decode($str);
	} 
  
}
