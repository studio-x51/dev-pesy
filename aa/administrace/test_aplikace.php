<?
require_once("../inc/inc.php");
require_once("../inc/fce_admin.php");

if(!$_SESSION["access_admin_ss"]) {
	header("location:./");
}


require_once("../inc/header.php");

if($_POST) {
/*
	echo "<p>.</p>";
	echo "<p>.</p>";
	echo "<p>.</p>";
	echo "<p>.</p>";
	echo "<p>.</p>";
*/	
	foreach($_POST as $k => $v) {
//		if(substr($k, 0, 6) == "zastup") {
		if(substr($k, 0, 6) == "zastup" && (mujpc() || substr($k, 7, 2) != 'cs')) {
			$zastup = substr($k, 10);
	//		echo substr($k, 0, 6)." | ".substr($k, 7, 2)." | ".substr($k, 10)." | ".$v." : ".$aplikace_typ_id."<br>";
			$lg = substr($k, 7, 2);
			$zastup = substr($k, 10);
			if($v)
			    dbQuery("REPLACE texty SET `lg`=#1, `zastup`=#2, `txt`=#3", $lg, $zastup, trim($v));
		}
	}

	unset($_SESSION["texty"]);
}

?>
<link href="../css/admin.css" rel="stylesheet" media="all" type="text/css">

<div id="admin">
<?
	menu_admin();
?>
	<div>
	<h1>Přímé linky na test (detail) aplikace</h1>
<?
	foreach($CONF_XTRA["nahled_aplikace"] as $k => $v) {
		echo "<h3>".txt("reset_app_".$k."_title")."</h3><p><a href=\"".$CONF_BASE."?try_app=".$k."\">".$CONF_BASE."?try_app=".$k."</a></p>";
	}
?>
	</div>

</div><!--/id="admin"-->
<?

require_once("../inc/footer.php");

?>

