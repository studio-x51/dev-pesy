<?
######### hlavni fce ############
// saveInvoice - vytvoreni/ulozeni faltury


####################
### pomocne fce ####
####################
/**
* fce pro opakovanou platbu - NEPOUZIVAM! - pouziju primo paramter "typ_platby" z formulare 
*/
function recurrence_cycle()
{
//	return "MONTH";
//	return "DAY";
}

/**
* fce pro opakovanou platbu
*/
function recurrence_period()
{
	return 1;
}



/**
* STAV platby 
*/
function getPaymentState($id_platby)
{
	global $CONF, $CONF_BASE;
	logit("log", "PLATBA - getPaymentState ID=: ".$id_platby, $CONF["logs"]["gopay_notify_state"]);
	$CONF = setConfig();
	$getPaymentToken = getPaymentToken();
	$ch = curl_init();
	// set URL and other appropriate options
	curl_setopt($ch, CURLOPT_URL, $CONF["gopay-payment_url"]."/".$id_platby);
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
	logit("log", "PLATBA - getPaymentState CURL: ".$str, $CONF["logs"]["gopay_notify_state"]);
	return json_decode($str);
} 


/**
* Vytvoření standardní platby na nové platební bráně
* - vytvori gw_url, ktere je pouzito pro platebni branu
*/
function createPayment($args)
{
	global $CONF, $CONF_BASE, $CONF_BASE_DIR;
	$getPaymentToken = getPaymentToken();
	$OwnerData = OwnerData();
	// TODO: smazat tyto 2 radky!
/*	
	$args["druh_platby"] = "premium";
	$args["spec_slev_kod"] = "test_code";
*/	
	$data = array(
		"payer" => array(
//			"default_payment_instrument"=>"BANK_ACCOUNT",
//			"default_payment_instrument"=>"PAYMENT_CARD", // defaulten nastavene, ale lze prepnout platebni metodu - nahore nad oknem!
//			"allowed_payment_instruments"=> array("BANK_ACCOUNT","PAYMENT_CARD","MPAYMENT"), // POZOR cpou se tam pouze banky, a karta nelze!!!
			"allowed_payment_instruments"=> array("PAYMENT_CARD", "MPAYMENT"), // vsechny platebni metody
//			"allowed_payment_instruments"=> array("PAYMENT_CARD"),
			"contact" => array(
				"first_name"=>$OwnerData["jmeno"],
				"last_name"=>$OwnerData["prijmeni"],
				"email"=>$OwnerData["email"],
//				"phone_number"=>"+420777456123",
//				"city"=>"C.Budejovice",
//				"street"=>"Plana 67",
//				"postal_code"=>"373 01",
//				"country_code"=>"CZE"
				)
		),
		"target" => array(
			"type"=>"ACCOUNT",
			"goid"=>$CONF["gopay-GoID"]
		),
		"amount"=> $args["amount"],
		"currency"=> currency_code_gopay(),
		"order_number"=> $args["druh_platby"] == "premium" ? $_SESSION["user"][APLIKACE_UNIQ_ID] : $args["aplikace_id"],
		"order_description" => $args["druh_platby"] == "premium" ? txt("setting-platba_description-ss_premium_members") : getAppInfoName($args["aplikace_id"]),
		"items"=> array(
//			array("name"=>"item01","amount"=>"75000"),
//			array("name"=>"item02","amount"=>"75000")
		),
		// !!! POZOR nemenit poradi, zapisuji podle indexu !!!
		"additional_params" => array(
			array("name"=>"from","value"=>$args["from"]),
			array("name"=>"to","value"=>$args["to"]),
			array("name"=>"months","value"=>$args["delka_trvani"]), // delka trvani v mesicich
			array("name"=>"druh_platby","value"=> $args["druh_platby"].($args["spec_slev_kod"] ? "|".$args["spec_slev_kod"] : "")), // druh platby standard, premium, .../ defaultne standard
// max. 4 parametry, jinak nemaka :-(			
//			array("name"=>"spec_slev_kod","value"=>$args["spec_slev_kod"] ? $args["spec_slev_kod"] : "0"), // druh platby standard, premium, .../ defaultne standard
//			array("name"=>"druh_platby","value"=>"0"), // druh platby standard, premium, .../ defaultne standard
		), 
		"callback"=> array(
//			"return_url"=> $CONF["gopay-return_url"],
			"return_url"=> $args["return_url"],
			"notification_url"=> $CONF["gopay-notification_url"]."?notifikace=standard&aplikace_id=".$args["aplikace_id"],
		),
		"lang"=>get_lang()
	);

	logit("debug","GOPAY createPayment gopay-payment_url: ".$CONF["gopay-payment_url"],$CONF_BASE_DIR."logs/gopay_create_payment.log");
	logit("debug","GOPAY createPayment data: ".serialize($data),$CONF_BASE_DIR."logs/gopay_create_payment.log");

	$data_string = json_encode($data);
	// create a new cURL resource
	$ch = curl_init();
	// set URL and other appropriate options
	curl_setopt($ch, CURLOPT_URL, $CONF["gopay-payment_url"]);
	curl_setopt($ch, CURLOPT_POST, 1); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Accept: application/json',
		'Content-Type: application/json',                                                                                
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
//	echo $str;
	logit("debug","GOPAY createPayment syrove: fb_id=".$_SESSION["user"][APLIKACE_UNIQ_ID].",".$str,$CONF_BASE_DIR."logs/gopay_create_payment.log");
	return json_decode($str);
} 


/**
* Vytvoření opakované platby na nové platební bráně
* - vytvori gw_url, ktere je pouzito pro platebni branu
*/
function createRecurrencePayment($args)
{
	global $CONF, $CONF_BASE, $CONF_BASE_DIR;
	$getPaymentToken = getPaymentToken();
	$OwnerData = OwnerData();
	if($args["druh_platby_detail"])
		$order_description = txt("setting-platba_description-ss_".$args["druh_platby_detail"]);
	else
		$order_description = $args["druh_platby"] == "premium" ? txt("setting-platba_description-ss_premium_members") : getAppInfoName($args["aplikace_id"]);
	$data = array(
		"payer" => array(
			"default_payment_instrument"=>"PAYMENT_CARD",
			"allowed_payment_instruments"=> array("PAYMENT_CARD"), // u opakovane platby lze nastavit pouze CC - jina metoda nelze kupodivu! :-)
			"contact" => array(
				"first_name"=>$OwnerData["jmeno"],
				"last_name"=>$OwnerData["prijmeni"],
				"email"=>$OwnerData["email"],
//				"phone_number"=>"+420777456123",
//				"city"=>"C.Budejovice",
//				"street"=>"Plana 67",
//				"postal_code"=>"373 01",
//				"country_code"=>"CZE"
				)
		),
		"target" => array(
			"type"=>"ACCOUNT",
			"goid"=>$CONF["gopay-GoID"]
		),
		"amount"=> $args["amount"],
		"currency"=> currency_code_gopay(),
		"order_number"=> $args["druh_platby"] == "premium" ? $_SESSION["user"][APLIKACE_UNIQ_ID] : $args["aplikace_id"],
		"order_description" => $order_description,
		"items"=> array(
//			array("name"=>"item01","amount"=>"75000"),
//			array("name"=>"item02","amount"=>"75000")
		),
		// !!! POZOR nemenit poradi, zapisuji podle indexu !!!
		"additional_params" => array(
			array("name"=>"from","value"=>$args["from"]),
			array("name"=>"to","value"=>$args["to"]),
			array("name"=>"months","value"=>$args["delka_trvani"]), // delka trvani v mesicich
			array("name"=>"druh_platby","value"=> $args["druh_platby"].($args["spec_slev_kod"] ? "|".$args["spec_slev_kod"].($args["druh_platby_detail"] ? "|".$args["druh_platby_detail"] : "") : "")), // druh platby standard, premium, .../ defaultne standard
// max. 4 parametry, jinak nemaka :-(			
//			array("name"=>"druh_platby","value"=>$args["druh_platby"] ? $args["druh_platby"]."|".$args["spec_slev_kod"] : "standard"), // druh platby standard, premium, .../ defaultne standard
//			array("name"=>"druh_platby","value"=>"0"), // druh platby standard, premium, .../ defaultne standard
		), 
		"recurrence" => array(
//			"recurrence_cycle" => recurrence_cycle(), // MONTH, WEEK, DAY, ON_DEMAND | ON_DEMAND
			"recurrence_cycle" => $args["typ_platby"], // MONTH, WEEK, DAY, ON_DEMAND | ON_DEMAND
			"recurrence_period" => recurrence_period(), // 1 - kazdy mesic, tyden, den | 2 - kazdy 2. mesic, 2. tyden, 2. den!
			"recurrence_date_to"=> $args["recurrence_date_to"] // do kdy se plati {format: 2015-12-31}
		),
		"callback"=> array(
//			"return_url"=> $CONF["gopay-return_url"],
			"return_url"=> $args["return_url"],
			"notification_url"=> $CONF["gopay-notification_url"]."?notifikace=recurrence&aplikace_id=".$args["aplikace_id"],
		),
		"lang"=>get_lang()
	);

	logit("debug","GOPAY createRecurrencePayment gopay-payment_url: ".$CONF["gopay-payment_url"],$CONF_BASE_DIR."logs/gopay_create_payment.log");
	logit("debug","GOPAY createRecurrencePayment data: ".serialize($data),$CONF_BASE_DIR."logs/gopay_create_payment.log");

	$data_string = json_encode($data);
	// create a new cURL resource
	$ch = curl_init();
	// set URL and other appropriate options
	curl_setopt($ch, CURLOPT_URL, $CONF["gopay-payment_url"]);
	curl_setopt($ch, CURLOPT_POST, 1); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Accept: application/json',
		'Content-Type: application/json',                                                                                
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
	logit("debug","GOPAY createRecurrencePayment syrove: fb_id=".$_SESSION["user"][APLIKACE_UNIQ_ID].",".$str,$CONF_BASE_DIR."logs/gopay_create_payment.log");
	return json_decode($str);
} 

/**
* Vytvoření standardní platby na nové platební bráně
* - vytvori gw_url, ktere je pouzito pro platebni branu
*/
function createRecurrenceOnDemand($args)
{
	global $CONF, $CONF_BASE, $CONF_BASE_DIR;
	$getPaymentToken = getPaymentToken();
	$OwnerData = OwnerData();
	if($args["druh_platby_detail"])
		$order_description = txt("setting-platba_description-ss_".$args["druh_platby_detail"]);
	else
		$order_description = $args["druh_platby"] == "premium" ? txt("setting-platba_description-ss_premium_members") : getAppInfoName($args["aplikace_id"]);

	$data = array(
		"amount"=> $args["amount"],
		"currency"=> currency_code_gopay(),
		"order_number"=> $args["order_number"],
		"order_description" => $args["order_description"],
/*		
		"items"=> array(
			array("name"=>"item01","amount"=>"75000"),
//			array("name"=>"item02","amount"=>"75000")
		),
*/	
		// !!! POZOR nemenit poradi, zapisuji podle indexu !!!
		"additional_params" => array(
			array("name"=>"from","value"=>$args["from"]),
			array("name"=>"to","value"=>$args["to"]),
			array("name"=>"months","value"=>$args["delka_trvani"]), // delka trvani v mesicich
			array("name"=>"druh_platby","value"=> $args["druh_platby"].($args["spec_slev_kod"] ? "|".$args["spec_slev_kod"].($args["druh_platby_detail"] ? "|".$args["druh_platby_detail"] : "") : "")), // druh platby standard, premium, .../ defaultne standard
// max. 4 parametry, jinak nemaka :-(			
//			array("name"=>"druh_platby","value"=>$args["druh_platby"] ? $args["druh_platby"]."|".$args["spec_slev_kod"] : "standard"), // druh platby standard, premium, .../ defaultne standard
//			array("name"=>"druh_platby","value"=>"0"), // druh platby standard, premium, .../ defaultne standard
		), 
	);

	logit("debug","GOPAY createRecurrenceOnDemand gopay-payment_url: ".$CONF["gopay-payment_url"],$CONF_BASE_DIR."logs/gopay_create_payment.log");
	logit("debug","GOPAY createRecurrenceOnDemand data: ".serialize($data),$CONF_BASE_DIR."logs/gopay_create_payment.log");
	$data_string = json_encode($data);
	// create a new cURL resource
	$ch = curl_init();
	// set URL and other appropriate options
	curl_setopt($ch, CURLOPT_URL, $CONF["gopay-payment_url"]."/".$args["id_platby"]."/create-recurrence");
	curl_setopt($ch, CURLOPT_POST, 1); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Accept: application/json',
		'Content-Type: application/json',                                                                                
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
//	echo $str;
	logit("debug","GOPAY createPayment syrove: fb_id=".$_SESSION["user"][APLIKACE_UNIQ_ID].",".$str,$CONF_BASE_DIR."logs/gopay_create_payment.log");
	return json_decode($str);
//	pre(json_decode($str), "curl_output");
} 





/**
* Proces vytvoření tokenu
*/
function getStandardToken() 
{
	global $CONF;
	// create a new cURL resource
	$ch = curl_init();
	$credentials = $CONF["gopay-Client_ID"].":".$CONF["gopay-Client_secret"];
	$data = "grant_type=client_credentials&scope=payment-create";
	// set URL and other appropriate options
	curl_setopt($ch, CURLOPT_URL, $CONF["gopay-torent_url"]);
	curl_setopt($ch, CURLOPT_POST, 1); 
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-form-urlencoded', 'Accept: application/json', "Authorization: Basic " . base64_encode($credentials)));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 

	ob_start(); 
	// grab URL and pass it to the browser
	curl_exec($ch);
	// close cURL resource, and free up system resources
	curl_close($ch);
	$str = ob_get_clean(); 

	return json_decode($str);
}

/**
* Přístupový token pro další platební operace
*/
function getPaymentToken() 
{
	$CONF = setConfig();
	// create a new cURL resource
	$ch = curl_init();
	$credentials = $CONF["gopay-Client_ID"].":".$CONF["gopay-Client_secret"];
	$data = "grant_type=client_credentials&scope=payment-all";
	// set URL and other appropriate options
	curl_setopt($ch, CURLOPT_URL, $CONF["gopay-torent_url"]);
	curl_setopt($ch, CURLOPT_POST, 1); 
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-form-urlencoded', 'Accept: application/json', "Authorization: Basic " . base64_encode($credentials)));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 

	ob_start(); 
	// grab URL and pass it to the browser
	curl_exec($ch);
	// close cURL resource, and free up system resources
	curl_close($ch);
	$str = ob_get_clean(); 
//	echo $str;

	return json_decode($str);
}

/**
* Zapis stavu platby po vytvoreni platby
* TODO: v pripade opakovane platby jeste ulozit do tabulky aplikace `typ_platby` enum('YEAR','MONTH')
*/
function saveCreatePaymentState($createPayment, $args = array()) 
{
	$CONF = setConfig();

	unset($_SESSION["premium"]);

	$aplikace_id = $args["aplikace_id"];
	// log terminu
	dbQuery("INSERT termin_log SET aplikace_id=#1, od=#2, do=#3",
	$aplikace_id, $args["from"], $args["to"]);

	// log platby
	dbQuery("INSERT platba_log SET gopay_id=#1, aplikace_id=#2, state=#3",
	$createPayment->id, $aplikace_id, $createPayment->state);
	
	$tmp = explode("|",$createPayment->additional_params["3"]->value);
	$druh_platby = $tmp[0];
	$spec_slev_kod = $tmp[1];
	$druh_platby_detail = $tmp[2];

	// update tabulky  aplikace / krome premium platby, ktera je pro vice aplikaci
	if($druh_platby != 'premium') {
		dbQuery("UPDATE aplikace SET od=#2, do=#3, timezone=#4 WHERE aplikace_id=#1",
			$aplikace_id, $args["from"], $args["to"], $args["timezone"]);
	}
	// pokud je premium, updatnu od a do u vsech aplikaci s premium slevovym kodem spec_slev_kod!
	if($druh_platby == 'premium') {
		dbQuery("UPDATE owner SET typ=#1 WHERE fb_id=#2", $druh_platby, $_SESSION["user"][APLIKACE_UNIQ_ID]);
		$rs = dbQuery("SELECT * FROM owner_x_app WHERE slev_kod = #1", $spec_slev_kod);
		while($row = dbArr2($rs)) {
			dbQuery("UPDATE aplikace SET od=now(), do=DATE_ADD(now(), INTERVAL 1 YEAR), timezone=#2 WHERE aplikace_id=#1",
				$row["aplikace_id"], $args["timezone"]);
		}
	}
	if($druh_platby == 'x51academy') {
		logit("debug","saveCreatePaymentState: druh_platby=x51academy");
	}
	

	// zalozeni platby
	// Do tabulky "platba" -> "do" ulozim nejzassi mozny placeny termin na cele mesice !
	// - do mesicni (MONTH) pouze jeden mesic a pak musim kontrolovat child platby
	// - do celkove ALL 
	$zaplaceno_do = $args["typ_platby"] == "ALL" ? $args["to"] : mysql_date_add_month($args["from"], 1);
	// premium academy za 1Kc
	if($druh_platby_detail == "premium_academy" || $druh_platby_detail == "pdf26napadu") {
		$zaplaceno_do = date("Y-m-d",strtotime("+14 days"));
	}
	
	if($druh_platby_detail) {
		$wh = ", what_platby=#15";
	}

	dbQuery("INSERT platba SET gopay_id=#1, aplikace_id=#2, gopay_parent_id=#3, od=#4, do=#5, delka_trvani=#6, zaplaceno_do=#7, amount=#8, amount_together=#9, currency=#10, state=#11, typ_platby=#12, gw_url=#13, spec_slev_kod=#14$wh",
	$createPayment->id, $aplikace_id, $createPayment->parent_id, $createPayment->additional_params[0]->value, $createPayment->additional_params[1]->value, $createPayment->additional_params[2]->value, $zaplaceno_do, $createPayment->amount, $args["amount_together"],
	$createPayment->currency, $createPayment->state, $args["typ_platby"], $createPayment->gw_url, $spec_slev_kod, $druh_platby_detail);

	return "";
}

/**
* Zapis stavu platby po notifikaci
* POZOR!	- platba 3196951784 (ower_id = 10205237213273173, kod=0ff94ba52f)
*			- byla zaplacena v test vetvi http://www.socialsprinters.cz/aa-test/premium?x=xtra
*			- musim dat spravnou ostrou db!
*		volam z gopay_notify.php a ze stranek plateb (dashboard, stranka platby u kazde SS aplikace)
*/
function savePaymentState($gopay_id, $gopay_parent_id, $gopay_type = false, $aplikace_id = false) 
{
	global $CONF_XTRA;
	$CONF = setConfig();
	logit("debug","savePaymentState: gopay_id=".$gopay_id.", gopay_parent_id=".$gopay_parent_id.", gopay_type=".$gopay_type.", aplikace_id=".$aplikace_id);
	// pokud neni notifikace, jedna se o primou platbu 1. opakovanou nebo platba za aplikaci!
	if(!$gopay_type) {
		logit("log", "PLATBA - mimo notifikaci ".$gopay_type." - ".$gopay_id, $CONF["logs"]["gopay_notify_state"]);
	}
	$db = "";
	// TODO: at tu platbu zrusi az zalozi v ostre verzi, aby se to nemuselo hlidat. navic test nemuzi byt funkcni zrovna!
	if($gopay_parent_id == 3196951784) // premium zaplatil v aa-test takze v testovaci db proto musim zmenit db! (fb_id=10205237213273173 | Jozef Benko) 
		$db = "socialsp2.";

	// pouze pro kontrolu, zda je to nase platba, ci nikoliv :-)
	dbQuery("SELECT * FROM ".$db."platba WHERE gopay_id=#1 OR gopay_id=#2 OR gopay_parent_id=#2 ORDER BY zalozeno DESC limit 1", $gopay_id, $gopay_parent_id);
	if(dbRows() != 1) {
		logit("log", "PLATBA - Neidentifikovatelna platba ".$gopay_type." - ".$gopay_id, $CONF["logs"]["gopay_notify_state"]);
		return;
	}
	$row = dbArr();
	logit("debug","savePaymentState row: ".serialize($row));

	$spec_slev_kod = $row["spec_slev_kod"];


/*	// nepotrebuju vlastne - dostanu z getPaymentState!!!
	$gopay_parent_id = "";
	if($gopay_id != $row["gopay_id"]) {
		$gopay_parent_id = $row["gopay_id"];
	}
*/
	// log platby
	logit("log", "PLATBA - Nalezena platba ".$gopay_type." - ".$gopay_id." | aplikace_id:".$row["aplikace_id"]."|".$aplikace_id, $CONF["logs"]["gopay_notify_state"]);

//	pre($getPaymentState = getPaymentState($gopay_id),"Stav platby");
	$getPaymentState = getPaymentState($gopay_id);

	// smaznuti kuponu z owner_x_app v pripade neuspesne platby
//	if($getPaymentState->state != "PAID")
//		dbQuery("UPDATE owner_x_app SET slev_kod='' WHERE aplikace_id=#1", $row["aplikace_id"]);
	
	logit("log", "PLATBA - SAVE ".serialize($getPaymentState), $CONF["logs"]["gopay_notify_state"]);

	// log platby
	dbQuery("REPLACE ".$db."platba_log SET gopay_id=#1, aplikace_id=#2, gopay_parent_id=#3, state=#4",
	$gopay_id, $row["aplikace_id"], $gopay_parent_id, $getPaymentState->state);

	// u opakovane platby jiz notifikace nevraci additional parameters!!! spec_slev_kod a dalsi si musim nacist z db vyse!
//	if($gopay_parent_id == $row["gopay_id"] || $gopay_parent_id == $row["gopay_parent_id"]) 
	if($gopay_parent_id) {
		$druh_platby_detail = $row["what_platby"];
		logit("debug","savePaymentState add_params: spec_slev_kod=$spec_slev_kod, druh_platby_detail=$druh_platby_detail");
	}
	else {
///	if(!$gopay_parent_id || ($gopay_parent_id != $row["gopay_id"] && $gopay_parent_id != $row["gopay_parent_id"])) 
		$tmp = explode("|",$getPaymentState->additional_params["3"]->value);
		$druh_platby = $tmp[0];
		// notifikace nevraci additional parameters!!! spec_slev_kod si musim nacist z db vyse!
		//	$spec_slev_kod = $tmp[1];
		$druh_platby_detail = $tmp[2];
		logit("debug","savePaymentStateadd_params zobnu z additional_params: ");
	}

	// nactu si owner fb_id 
	if($spec_slev_kod) {
		dbQuery("SELECT owner_fb_id FROM ".$db."slev_kody WHERE kod=#1", $spec_slev_kod);
//		dbQuery("SELECT * FROM owner_x_app WHERE aplikace_id=#1", $row["aplikace_id"]);
		$row_owner = dbArr();
		$owner_id = $row_owner["owner_fb_id"];
	}
	elseif($row["aplikace_id"]) {
//		dbQuery("SELECT owner_fb_id FROM slev_kody WHERE kod=#1", $spec_slev_kod);
		dbQuery("SELECT owner_id FROM ".$db."owner_x_app WHERE aplikace_id=#1", $row["aplikace_id"]);
		$row_owner = dbArr();
		$owner_id = $row_owner["owner_id"];
	}


	logit("debug","savePaymentState: gopay_parent_id=$gopay_parent_id, druh_platby_detail=$druh_platby_detail");
	// automaticka recurrence platba je na 
	// 1) premium_academy nebo pdf26napadu on_demand varianta - 1Kc (pouze placena primo, po vyvylani z cronu zaplaceno_do = +1 month!!!!)
	// $gopay_id != $row["gopay_id"] - znamena, ze se jedna o 1.platbu! Pro dalsi platby je cena 590 a zaplaceno do + 1 mesic!!!
	if(!$gopay_parent_id && ($druh_platby_detail == "premium_academy" || $druh_platby_detail == "pdf26napadu")) {
		$zaplaceno_do = date("Y-m-d",strtotime("+14 days"));
		$smartmailing_id = $CONF_XTRA["smartmailing"][$druh_platby_detail];
	}
	// premium opakovana varianta
	else {
		### pro mesice s mene dny v mesici nez je nasledujici hledam zda jde o posledni den v mesici, aby posunul datum zaplaceno_do take na posledni den v mesici!
		$time = strtotime($row["zaplaceno_do"]);
		$next_time_month = strtotime(mysql_date_add_month($row["zaplaceno_do"], 1));
		/*		
			echo "<p>mysql from:".date("Y-m-d", $time)."<br>";
			echo "<p>mysql next:".date("Y-m-d", $next_time_month)."<br>";
			echo date("t", $time)." | ".date("t", $next_time_month)."<br>";
		*/
		if(date("t", $time) == date("j", $time) && date("t", $time) < date("t", $next_time_month)) {
			$time_diff = (date("t", $next_time_month) - date("t", $time)) * 86400;
//			$zaplaceno_do = mysql_date_add_month(date("Y-m-d"), 1);
			$zaplaceno_do = date("Y-m-d", strtotime(mysql_date_add_month(date("Y-m-d", $time), 1)) + $time_diff);
		/*
			echo "$time_diff . next_month:".date("Y-m-d", strtotime(mysql_date_add_month(date("Y-m-d", $time), 1)) + $time_diff)."<br>";
			echo "zaplaceno_do=".$zaplaceno_do."<br>";
			echo "</p>";
		*/	
		}
		else
			$zaplaceno_do = mysql_date_add_month(date("Y-m-d"), 1);
		$smartmailing_id = $CONF_XTRA["smartmailing"]["premium"];
	}
		
	// standardni platba najednou! - urcim, zda je standard nebo recurrence
	// 1) standard
	if($gopay_id == $row["gopay_id"]) {
		// ulozeni stavu platby / zde jiz neukladam zaplaceno_do je jiz ulozeno rovnou po vykonani platby - pred notifikaci!
		dbQuery("UPDATE ".$db."platba SET aplikace_id=#2, gopay_parent_id=#3, amount=#4, currency=#5, state=#6 WHERE gopay_id=#1", 
		$getPaymentState->id, $row["aplikace_id"], $getPaymentState->parent_id, $getPaymentState->amount, $getPaymentState->currency, $getPaymentState->state);
	}
	// 2) automaticka recurrence opakovana platba / nebo on_demand vyvolana z cronu! (u 1. platby se pouze provede UPDATE! jelikoz je jiz zappsana v db)
	else {
		if($druh_platby_detail) {
			$wh = ", what_platby=#15";
		}

		// u recurrence jiz opakovne nevraci additional_params, takze musim masypat z parent z tabulky 'platba'
//		INSERT platba SET gopay_id=3200766920, aplikace_id='0', gopay_parent_id=3193361015, od=NULL, do=NULL, delka_trvani=NULL, zaplaceno_do='2015-12-16', amount=59000, amount_together='708000', currency='CZK', state='PAID', typ_platby='MONTH', gw_url='https://gate.gopay.cz/gw/v3/4dbb1a7c3a8fe617235eee1b1ea6d5a2', spec_slev_kod=NULL
		dbQuery("INSERT ".$db."platba SET gopay_id=#1, aplikace_id=#2, gopay_parent_id=#3, od=#4, do=#5, delka_trvani=#6, zaplaceno_do=#7, amount=#8, amount_together=#9, currency=#10, state=#11, typ_platby=#12, gw_url=#13, spec_slev_kod=#14$wh",
		$getPaymentState->id, $row["aplikace_id"], $getPaymentState->parent_id, $row["od"], $row["do"],
		$row["delka_trvani"], $zaplaceno_do, $getPaymentState->amount, $row["amount_together"],
		$getPaymentState->currency, $getPaymentState->state, $row["typ_platby"], $getPaymentState->gw_url, $spec_slev_kod, $druh_platby_detail);
		$dbaff = dbAff();
	}
	
	logit("debug","druh_platby:".serialize($getPaymentState->additional_params)."!");
	logit("debug","druh_platby:".$druh_platby."!");

	// STANDARD VERZE - plati se primo konkretni aplikace! - (neni $spec_slev_kod!)
	if(!$spec_slev_kod && $getPaymentState->state == "PAID") {
		dbQuery("SELECT aplikace_typ_id FROM ".$db."aplikace WHERE aplikace_id = #1", $row["aplikace_id"]);
		$row_app_typ_id = dbArr();
		// zapnu apku
		dbQuery("UPDATE ".$db."aplikace SET spusteno = #2 WHERE aplikace_id=#1", $row["aplikace_id"], 1);
		saveInvoice(array("gopay_id" => $getPaymentState->id, "fb_id" => $owner_id , "zpusob_platby" => "převodem", "druh_platby" => "standard", "popis" => txt("SocialSprinters")." - ".txt("reset_app_".$row_app_typ_id["aplikace_typ_id"]."_title"), "cena" => $getPaymentState->amount, "currency" => $getPaymentState->currency, "db" => $db));
//		$args["gopay_id"], $new_vs, $args["zpusob_platby"], $args["druh_platby"], time(), time(), $args["popis"]);
		$_SESSION["paid_aplikace_id"] = $row["aplikace_id"];
		// a rescrapnu !!!
		ob_start();
		rescrapeFbOg($row["aplikace_id"]);
		ob_end_clean();
	}
	// PREMIUM verze - je $spec_slev_kod!
	elseif($getPaymentState->state == "PAID") {
//		$CONF_XTRA["smartmailing"]["premium_academy"]
		$owner = OwnerData($owner_id);

		if($druh_platby_detail == "premium_academy") {
			// zalozim usera: http://x51.cz/member/new_member.php?email=useremail vytvoří uživatele dle zadaného emailu v parametru “email” ..
			$ch = curl_init();
			// set URL and other appropriate options
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, "https://x51.cz/member/new_member.php?email=".$owner["email"]);
			curl_setopt($ch, CURLOPT_HTTPGET, 1); 

			ob_start(); 
			curl_exec($ch);
			// Check for errors and such.
			$info = curl_getinfo($ch);
			$errno = curl_errno($ch);

			logit("debug","create user info:".serialize($info));
			logit("debug","create user err:".serialize($errno));
			if( $output === false || $errno != 0 ) {
			    // Do error checking
			} else if($info['http_code'] != 200) {
			    // Got a non-200 error code.
			    // Do more error checking
			}	
			// close cURL resource, and free up system resources
			curl_close($ch);
			$str = ob_get_clean(); 
			logit("debug","create user:".$str);

		}
		saveInvoice(array("gopay_id" => $getPaymentState->id, "fb_id" => $owner_id , "zpusob_platby" => "převodem", "druh_platby" => "premium", "popis" => txt("dashboard-description_licence-premium"), "cena" => $getPaymentState->amount, "currency" => $getPaymentState->currency, "db" => $db));

		dbQuery("SELECT *, sm.fb_id sm_fb_id FROM ".$db."slev_kody k LEFT JOIN ".$db."smartemailing sm ON owner_fb_id=sm.fb_id AND contactlist=#2 WHERE kod=#1", $spec_slev_kod, $smartmailing_id);
		$row_kod = dbArr();
		if(!$row_kod["sm_fb_id"]) {
			$owner["email"] = $owner["email_contact"] ? $owner["email_contact"] : $owner["email"];
			$owner["kod"] = $spec_slev_kod;
			$owner["zaplaceno_do"] = $zaplaceno_do;
			$owner["zalozeno"] = $row["zalozeno"];
			logit("debug", "smartemailing id=$smartmailing_id zatim neni - fb_id:".$owner["fb_id"].", email:".$owner["email"].", owner:".serialize($owner));
			// vrazim do smartemailingu!
			if(sendRequest(make_add_smartmailing_xml($owner, $smartmailing_id)) == "SUCCESS") {
				logit("debug", "add_premium_2_smartemailing fb_id:".$owner["fb_id"].", email:".$owner["email"]);
				dbQuery("INSERT ".$db."smartemailing SET fb_id=#1, contactlist=#2", $owner["fb_id"], $smartmailing_id);
			}
		}

		// nactu si owner_id a vsem jeho aplikacim krome FREE priradim spec_slev_kod!!! A rescrapnu! A je to!
		$rs = dbQuery("SELECT * FROM ".$db."owner_x_app oa, ".$db."slev_kody WHERE owner_id=owner_fb_id AND kod=#1 AND slev_kod!=#2", $spec_slev_kod, "FREEAPP");
		while($row = dbArr2($rs)) {
			dbQuery("UPDATE ".$db."owner_x_app SET slev_kod=#2 WHERE aplikace_id=#1", $row["aplikace_id"], $spec_slev_kod);
			dbQuery("UPDATE ".$db."aplikace SET od=now(), do=DATE_ADD(now(), INTERVAL 1 YEAR) WHERE aplikace_id=#1", $row["aplikace_id"], 1);
			// a rescrapnu !!!
			ob_start();
			rescrapeFbOg($row["aplikace_id"]);
			ob_end_clean();
		}
	}

	return array("state" => $getPaymentState->state, "dbaff" => $dbaff);
}

/**
* zapise novou fakturu po uspesne gopay platbe
* $druh_platby: standard, premium 
* saveInvoice($args)
* $gopay_id, $popis, $druh_platby, $zpusob_platby = "převodem"
*/
function saveInvoice($args) 
{
	global $CONF_XTRA;
		$db = $args["db"];
		// zapisu novou fakturu do tabulky faktury!
		dbQuery("LOCK TABLES ".$db."faktury WRITE");
		dbQuery("SELECT max(vs) last_vs FROM ".$db."faktury WHERE YEAR(CURDATE()) = YEAR(datum_vystaveni)");
		$row = dbArr();
		$new_vs = $row["last_vs"] ? ($row["last_vs"] + 1) : $CONF_XTRA["VS"];
		dbQuery("INSERT ".$db."faktury SET gopay_id=#1, vs=#2, fb_id=#3, zpusob_platby=#4, druh_platby=#5, datum_zdan_plneni=#!6, datum_splatnosti=#!7, popis=#8, cena=#9, currency=#10", $args["gopay_id"], $new_vs, $args["fb_id"], $args["zpusob_platby"], $args["druh_platby"], time(), time(), $args["popis"], $args["cena"], $args["currency"]);
		dbQuery("UNLOCK TABLES");
}


/**
* plateni brana standard
*/
function gateWayPaypal($createPayment)
{
	global $CONF, $CONF_XTRA;
	ob_start();
	?>
	<!-- platebni brana -->
	<form action="<?echo $createPayment->gw_url;?>" method="post" id="gopay-payment-button">
		<button name="pay" type="submit"><?=$CONF_XTRA["texty"][get_lang()]["setting-platba_provest_platbu"];?></button>
		<script type="text/javascript" src="<?=$CONF["gopay-js_embed"]?>"></script>
	</form>
	<!-- /platebni brana -->
	<?
	return ob_get_clean();
}

/**
* plateni brana standard bez attr action, ktery doplnim pres JS@
*/
function gateWayPaypalEmpty()
{
	global $CONF, $CONF_XTRA;
	$CONF = setConfig();
	ob_start();
	?>
	<!-- platebni brana -->
	<form action="" method="post" id="gopay-payment-button">
		<button id="pay_submit" name="pay" type="submit"><?=$CONF_XTRA["texty"][get_lang()]["setting-platba_provest_platbu"];?></button>
		<script type="text/javascript" src="<?=$CONF["gopay-js_embed"]?>"></script>
	</form>
	<!-- /platebni brana -->
	<?
	return ob_get_clean();
}
?>
