<?
require_once("../inc/inc.php");
require_once("../inc/fce_admin.php");

if(!$_SESSION["access_admin_ss"]) {
	header("location:./");
}

if(fetch_uri("export_mails", "g")) {
	download_send_headers("mails_export_" . date("Y-m-d,H.i.s") . ".csv");
	echo showAllUsers("export");
//	header("location:prehled_uzivatelu");
	exit;
	die();
}
require_once("../inc/header.php");
?>
<link href="../css/admin.css" rel="stylesheet" media="all" type="text/css">
<div id="admin">
<? menu_admin();?>
	<p>
	<a href="?export_mails=1">Export mails</a>
	</p>
	<h1>Přehled uživatelů</h1>
<? showAllUsers();?>
</div><!--/id="admin"-->
<? require_once("../inc/footer.php");?>