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
    <requestmethod>delete</requestmethod>
    <details>
        <id>23970</id>
        <!-- OR <emailaddress>martin@smartemailing.cz</emailaddress> -->
    </details>
</xmlrequest>
';

//sendRequest($xml);