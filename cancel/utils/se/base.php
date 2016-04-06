<?php
/**
 * @package:
 * @subpackage:
 * @author: Martin Strouhal <martin@martinstrouhal.cz>, <martin@smartemailing.cz>
 * Created on: 10.05.13 11:46
 */

$pesy_test_list_id = 94;
$premium_list_id = 69;
$premium_cancel_id = 95;

$token = 'TApH2gLh2cKKf00ehlcAFPMHZ6w1OpjocvYXCeDO';
$username = 'tomas.vans@seznam.cz';

function v($result) {
	echo $result;
	//print_r($result);
	die();
}

function sendRequest($xml) {
	$ch = curl_init('https://app.smartemailing.cz/api/v2');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
	$result = curl_exec($ch);

	if ($result === false) {
		echo "Chyba v zasilani XML requestu!";
	} else {

		header ("Content-Type:text/xml");
		v($result);

		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		$xml_doc = @simplexml_load_string($result); // intentionally @

		if (!$xml_doc) {
			echo "ERR in request" . PHP_EOL;
			v($result);
		}

		echo PHP_EOL . $result . PHP_EOL . '------------------' . PHP_EOL;

		echo 'Status is ' . $xml_doc->status . PHP_EOL;

		if ($xml_doc->status == 'SUCCESS') {
			print_r($xml_doc->data);
		} else {
			echo $xml_doc->errormessage . PHP_EOL;
		}
	}
}