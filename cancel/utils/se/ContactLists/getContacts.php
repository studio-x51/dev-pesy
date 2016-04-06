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
    <requestmethod>getContacts</requestmethod>
      <details>
       <id>{$pesy_test_list_id}</id>
      </details>
</xmlrequest>
";

sendRequest($xml);