<?php

/**
 * @package:
 * @subpackage:
 * @author: Martin Strouhal <martin@martinstrouhal.cz>, <martin@smartemailing.cz>
 * Created on: 10.05.13 11:49
 */


//require 'base.php';
/*
$row = array();
$row["email"] = "rs@cdi.cz";
*/

function make_add_smartmailing_xml($user, $smartmailing_id =  false) {
	global $CONF_XTRA;
	if(!$smartmailing_id) return false;
//	pre($user,$CONF_XTRA["smartemailing_username"]."|".$CONF_XTRA["smartemailing_token"]);
	$xml = "
	<xmlrequest>
		<username>{$CONF_XTRA["smartemailing_username"]}</username>
		<usertoken>{$CONF_XTRA["smartemailing_token"]}</usertoken>
		<requesttype>Contacts</requesttype>
		<requestmethod>createupdate</requestmethod>
		<details>
		<!--
		only emailaddress is required
		only fields contained in XML are updated
		-->
			<emailaddress>".$user["email"]."</emailaddress>
			<language>cs_CZ</language> <!-- cs_CZ | sk_SK | en_GB -->
			<blacklisted>0</blacklisted>
			<name>".$user["jmeno"]."</name>
			<surname>".$user["prijmeni"]."</surname>

			<customfields>
				<item>
					<id>8</id>
					<value>
						".$user["fb_id"]."
					</value>
				</item>
				<item>
					<id>9</id>
					<value>
						".$user["kod"]."
					</value>
				</item>
				<item>
					<id>10</id>
					<value>
						".$user["zaplaceno_do"]."
					</value>
				</item>
				<item>
					<id>11</id>
					<value>
						".$user["zalozeno"]."
					</value>
				</item>
			</customfields>

			<contactliststatuses>
			<!--
			statuses in lists not contained in this xml will be untouched
			if any if items is not valid (bad id or status) - it is ignored without thusering an error
			-->
				<item>
					<id>".$smartmailing_id."</id>
					<status>confirmed</status>
					<!-- unconfirmed | confirmed | unsubscribed | banned | removed -->
				</item>
			</contactliststatuses>
		</details>
	</xmlrequest>
	";
	return $xml;
//	return sendRequest($xml);
}

//sendRequest($xml);

