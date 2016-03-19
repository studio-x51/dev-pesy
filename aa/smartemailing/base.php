<?php

/**
 * @package:
 * @subpackage:
 * @author: Martin Strouhal <martin@martinstrouhal.cz>, <martin@smartemailing.cz>
 * Created on: 10.05.13 11:46
 */


function v($result) {
	echo $result;
	//print_r($result);
	die();
}

function sendRequest($xml) {
	if(!$xml) return false;
//	logit("debug","smartmailing sendRequest xml:".$xml);	
	$ch = curl_init('https://app.smartemailing.cz/api/v2');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
	$result = curl_exec($ch);


	if ($result === false) {
		echo "Chyba v zasilani XML requestu!";
	}
	else {

//		header ("Content-Type:text/xml");
	//	v($result);

		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		$xml_doc = @simplexml_load_string($result); // intentionally @
		logit("debug","smartmailing sendRequest:".error_log($xml_doc));	

		if (!$xml_doc) {
			logit("debug","smartmailing ERR in request");	
			return "ERR in request";
			v($result);
		}
/*
		echo "<br>".PHP_EOL . $result . PHP_EOL . '------------------' . PHP_EOL;

		echo 'Status is ' . $xml_doc->status . PHP_EOL;
*/
		if ($xml_doc->status == 'SUCCESS') {
			logit("debug","smartmailing SUCCESS");	
//			echo "SUcccccccc<br>";
			return "SUCCESS";
		}
		else {
			logit("debug","smartmailing err:".$xml_doc->errormessage);	
			return $xml_doc->errormessage;
		}
	}
}
