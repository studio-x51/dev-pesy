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
	<requesttype>ContactLists</requesttype>
	<requestmethod>getStatusChanges</requestmethod>
		<details>
			<id>ID_LIST</id>
			 <from>2015-01-01 13:50:00</from><!-- optional -->
        	 <to>2015-01-01 13:50:00</to><!-- optional -->

		</details>
</xmlrequest>
";

//sendRequest($xml);
