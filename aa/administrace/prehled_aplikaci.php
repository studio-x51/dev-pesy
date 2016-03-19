<?
require_once("../inc/inc.php");
require_once("../inc/fce_admin.php");

if(!$_SESSION["access_admin_ss"]) {
	header("location:./");
}


require_once("../inc/header.php");
?>
<link href="../css/admin.css" rel="stylesheet" media="all" type="text/css">

<div id="admin">
<?
	menu_admin();
?>
	<h1>Přehled aplikací</h1>
<?
	showAllApps();
?>
</div><!--/id="admin"-->
<?

require_once("../inc/footer.php");

?>

