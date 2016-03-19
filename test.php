<?
// tohle vloz do <body>
require_once("aa/basic_login.php");

?>
<p class="login_test" id="login" onclick="Login('<?=$CONF["scope"]?>', '<?=session_id()?>', 'dashboard')">LOGIN!</p>
