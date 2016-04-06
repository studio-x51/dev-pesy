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
    <username>' . $username . '</username>
    <usertoken>' . $token . '</usertoken>
    <requesttype>ContactLists</requesttype>
    <requestmethod>delete</requestmethod>
    <details>
	    <id>30</id>
	    <removecontacts>1</removecontacts><!-- optional -->
    </details>
</xmlrequest>
';

sendRequest($xml);

/**
<?xml version="1.0" encoding="UTF-8" ?>
<response>
<status>SUCCESS</status>
<data>
1
</data>
</response>
 */