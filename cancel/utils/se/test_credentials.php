<?php
error_reporting(0);
include_once 'base.php';

$xml = '
	<xmlrequest>
		<username>'.$username.'</username>
		<usertoken>'.$token.'</usertoken>
    <requesttype>Users</requesttype>
    <requestmethod>testCredentials</requestmethod>
    <details>
    </details>
</xmlrequest>
';
?>
<!DOCTYPE html>
<html lang="cs-CZ">
<head>
<meta charset="utf-8">
</head>
  <body>
    <?php sendRequest($xml);?>
  </body>
</html>