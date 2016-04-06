<?php

/**
 * @package: 
 * @subpackage:
 * @author: Martin Strouhal <martin@martinstrouhal.cz>, <martin@smartemailing.cz>
 * Created on: 11.04.14 09:49
 */


require '../base.php';

$xml = '
<xmlrequest>
    <username>'.$username.'</username>
    <usertoken>'.$token.'</usertoken>
    <requesttype>Contacts</requesttype>
	<requestmethod>getAllUnsubscribed</requestmethod>
	<details>
	</details>
</xmlrequest>
';

sendRequest($xml);



