<?php

/**
 * @package: 
 * @subpackage:
 * @author: Martin Strouhal <martin@martinstrouhal.cz>, <martin@smartemailing.cz>
 * Created on: 10.05.13 11:49
 */


require '../base.php';

$xml = '
<xmlrequest>
    <username>'.$username.'</username>
    <usertoken>'.$token.'</usertoken>
    <requesttype>Contacts</requesttype>
    <requestmethod>getOne</requestmethod>
    <details>
        <!-- <id>25237</id> -->
        <emailaddress>petr.syrny@centrum.cz</emailaddress>
    </details>
</xmlrequest>
';

sendRequest($xml);

/**
<response>
<status>SUCCESS</status>
	<data>
		<item>
			<language/>
			<created>2013-04-26 12:37:44</created>
			<updated>2013-04-26 12:37:44</updated>
			<blacklisted>0</blacklisted>
			<emailaddress>martin@smartemailing.cz</emailaddress>
			<name/>
			<surname/>
			<titlesbefore/>
			<titlesafter/>
			<birthday/>
			<nameday/>
			<salution/>
			<company/>
			<street/>
			<town/>
			<country/>
			<postalcode/>
			<notes/>
			<phone/>
			<cellphone/>
			<softbounced>0</softbounced>
			<hardbounced>0</hardbounced>
			<id>18946</id>
		</item>
	</data>
</response>
 */

