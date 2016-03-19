<?
require_once("../inc/inc.php");
require_once("../inc/fce_admin.php");

if(!$_SESSION["access_admin_ss"]) {
	header("location:./");
}


require_once("../inc/header.php");
?>
<script type="text/javascript" src="../js/admin.js?time=<?=$CONF_XTRA["TIME_FILES"]?>"></script>
<link href="../css/admin.css" rel="stylesheet" media="all" type="text/css">

<div id="admin">
<?
	menu_admin();
?>
	<h1>Úspěšnost aplikací</h1>
<?
	showAllUspesnostApps();
?>
</div><!--/id="admin"-->
<?

require_once("../inc/footer.php");


/**
* zobrazi vsechny aplikace
*/
function showAllUspesnostApps() {

//	pre($hash_platba, "platby");
	$order = fetch_uri("order","g") ? fetch_uri("order","g") : "zalozeno DESC";
	// prijmeni = "prijmeni COLLATE utf8_czech_ci";
	dbQuery("SELECT * FROM owner_x_app oa, owner o, aplikace a WHERE fb_id=owner_id AND a.aplikace_id=oa.aplikace_id ORDER BY $order, a.aplikace_id");
	// hack na zobrazeni aplikace dle QS aplikace_id, pouze pro mujpc()
	ob_start();
	$i = 0;
	while($row = dbArr()) {
		$apps[$row["aplikace_id"]] = $row;
	}

	$i = 1;
	dbQuery("SELECT COUNT(*) s, aplikace_id FROM uzivatel where prijmeni <> 'undefined' GROUP BY aplikace_id ORDER BY s DESC");
	while($row2 = dbArr()) {

		if($apps[$row2["aplikace_id"]]["prijmeni"] == "undefined" && $apps[$row2["aplikace_id"]]["jmeno"] == "undefined") continue;
		?>
		<tr>
			<td><?=$i++?>.</td>
			<td><?=$apps[$row2["aplikace_id"]]["aplikace_id"]?></td>
			<td><a href="#dashboard" class="adminDashboard" rel="<?=$row2["aplikace_id"]?>"><?=$apps[$row2["aplikace_id"]]["og:title"]?></a></td>
			<td><?=$apps[$row2["aplikace_id"]]["prijmeni"]." ".$apps[$row2["aplikace_id"]]["jmeno"]?></td>
			<td><?=$apps[$row2["aplikace_id"]]["zalozeno"]?></td>
			<td><?=$row2["s"]?></td>


			<td>
				<? if($apps[$row2["aplikace_id"]]["canvas"]) {?>
					<a href="http://sprinte.rs/<?=$apps[$row2["aplikace_id"]]["app_short_code"]?>" target="_blank">link</a>
				<? }?>
			</td>
		</tr>
		<?
	}
	$str = ob_get_clean();

	$printDashoardFce = "printDashBoardTypApp1";
?>	<div id="dashboard">
		<div class="aplikace">	
<?		echo call_user_func($printDashoardFce, $apps[776]);
	?>	</div>
		<div class="cl"></div>
	</div>
	<div class="cl"></div>
	<table class="prehled_table">
		<tr>
			<th>Pořadí</th>
			<th>App ID</th>
			<th>Title</th>
			<th>Provozovatel</th>
			<th>Založeno</th>
			<th>Počet už.</th>
			<th>Link</th>
		</tr>
		<?=$str?>
	</table>
<?	
}


?>

