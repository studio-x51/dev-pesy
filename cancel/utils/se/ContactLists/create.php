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
    <requestmethod>create</requestmethod>
    <details>
	    <name>SocialSprinters Premium - cancel</name>
	    <trackedDefaultFields>a:0:{}</trackedDefaultFields>
      <sendername>Tomáš Mužík</sendername>
      <senderemail>hello@x51.cz</senderemail>
      <replyto>hello@x51.cz</replyto>
    </details>
</xmlrequest>
';

//sendRequest($xml);