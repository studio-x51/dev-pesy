<?php

/**
 * @package:
 * @subpackage:
 * @author: Martin Strouhal <martin@martinstrouhal.cz>, <martin@smartemailing.cz>
 * Created on: 10.05.13 11:49
 */


require '../base.php';

$xml = "
<xmlrequest>
    <username>{$username}</username>
    <usertoken>{$token}</usertoken>
    <requesttype>Contacts</requesttype>
    <requestmethod>createupdate</requestmethod>
    <details>
    <!--
    only emailaddress is required
    only fields contained in XML are updated
    -->
		<emailaddress>petr.syrny@centrum.cz</emailaddress>
		<language>cs_CZ</language> <!-- cs_CZ | sk_SK | en_GB -->
		<blacklisted>0</blacklisted>
		<name>Petr</name>
		<surname>Syrný</surname>
    
    <contactliststatuses>
    <!-- statuses in lists not contained in this xml will be untouched
      if any if items is not valid (bad id or status) - it is ignored without throwing an error
    -->
    <item>
      <id>94</id>
      <status>confirmed</status>
      <!-- unconfirmed | confirmed | unsubscribed | banned | removed -->
    </item>
    </contactliststatuses>
    
    </details>
</xmlrequest>
";

sendRequest($xml);

