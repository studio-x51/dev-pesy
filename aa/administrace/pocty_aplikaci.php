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
<?  menu_admin();?>
	<h1>Počty aplikací</h1>
	<h2>Počty zbývajících aplikací</h2>
<?
	ob_start();
	$str = "";
	$rs = dbQuery("SELECT count(*) pocet, a.aplikace_typ_id FROM aplikace a LEFT JOIN owner_x_app oa ON a.aplikace_id=oa.aplikace_id WHERE oa.aplikace_id IS NULL GROUP BY aplikace_typ_id ORDER BY a.aplikace_typ_id");
	while($row = dbArr2($rs)) {
		$row["title"] = txt("reset_app_".$row["aplikace_typ_id"]."_title");
//		pre($row);
?>
		<tr>
			<td><?=$row["title"]." (".$row["aplikace_typ_id"].")"?></td>
			<td><?=$row["pocet"]?></td>
		</tr>
<?
	}
	$str = ob_get_clean();


?>
	<table class="prehled_table">
		<tr>
			<th>Title (SS aplikace_typ_id)</th>
			<th>Počet</th>
		</tr>
		<?=$str?>
	</table>

	<h2>Počty obsazených aplikací</h2>
<?
	$str = "";
	$rs = dbQuery("SELECT count(*) pocet, a.aplikace_typ_id FROM aplikace a ,owner_x_app oa WHERE a.aplikace_id=oa.aplikace_id GROUP BY aplikace_typ_id ORDER BY a.aplikace_typ_id");
	ob_start();
	while($row = dbArr2($rs)) {
		$row["title"] = txt("reset_app_".$row["aplikace_typ_id"]."_title");
//		pre($row);
?>
		<tr>
			<td><?=$row["title"]." (".$row["aplikace_typ_id"].")"?></td>
			<td><?=$row["pocet"]?></td>
		</tr>
<?
	}
	$str = ob_get_clean();


?>
	<table class="prehled_table">
		<tr>
			<th>Title (SS aplikace_typ_id)</th>
			<th>Počet</th>
		</tr>
		<?=$str?>
	</table>

</div><!--/id="admin"-->
<?

require_once("../inc/footer.php");

?>

