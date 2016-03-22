<?
if($_POST['heslo']) {
	session_start();
	$heslo = trim($_POST['heslo']);
	if ($heslo=="aaa123") {
		$_SESSION["access_admin_ss"] = true;
		header("location:./?action=logon");
		exit;
	}
}

require_once("../inc/inc.php");
require_once("../inc/fce_admin.php");

if(!$_SESSION["access_admin_ss"]) {
	require_once("../inc/header.php");
?>
<link href="../css/admin.css" rel="stylesheet" media="all" type="text/css">
	<div id="admin_login">
		<form action="./" method="post">
		<p><?=txt("setting-vstup-placeholder_zadejte_heslo")?></p>
		<input class="text" placeholder="<?=txt("setting-vstup-placeholder_zadejte_heslo")?>" name="heslo" type="password"><br />
		<button type="submit"><?=txt("setting-button_vstup")?></button>
		</form>
	</div>	
<?
	require_once("../inc/footer.php");
	exit;
} else {
	require_once("../inc/header.php");
?>
<link href="../css/admin.css" rel="stylesheet" media="all" type="text/css">
<div id="admin">
  <? menu_admin();?>
</div><!--/id="admin"-->
<?
	require_once("../inc/footer.php");
}
?>