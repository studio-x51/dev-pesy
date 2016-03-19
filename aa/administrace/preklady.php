<?
require_once("../inc/inc.php");
require_once("../inc/fce_admin.php");

if(!$_SESSION["access_admin_ss"]) {
	header("location:./");
}



if($_POST) {
	

	echo "<p>.</p>";
	echo "<p>.</p>";
	echo "<p>.</p>";
	echo "<p>.</p>";
	echo "<p>.</p>";

//	pre($_POST, count($_POST));
	foreach($_POST as $k => $v) {
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
	header("location: preklady");
	exit;
}

require_once("../inc/header.php");
//		if(substr($k, 0, 6) == "zastup") {
?>
<link href="../css/admin.css" rel="stylesheet" media="all" type="text/css">

<div id="admin">
<?
	menu_admin();
?>
	<h1>Překlady aplikací</h1>
	<p>
	<a href="../make_export_data.php?type=all_texts">CVS all texty</a>
	</p>
<?
	
	ob_start();
	$str = "";
	$rs = dbQuery("SELECT * FROM texty ORDER BY zastup, lg");
	while($row = dbArr2($rs)) {
		$data[$row["lg"]][$row["zastup"]] = $row;
//		pre($row, txt("all-app_config_".$row["aplikace_typ_id"]."_name"));	
	}

	foreach($data["cs"] as $zastup => $v) {
?>
		<tr>
			<td><?=$v["zastup"]?></td>
			<td>
				<span>cs:</span>
<?		if(mujpc()) {	?>				
				<textarea name="zastup_cs_<?=$v["zastup"]?>"><?=htmlspecialchars($v["txt"])?></textarea>
<?		} else {	?>				
				<textarea name="zastup_cs_<?=$v["zastup"]?>" readonly="readonly"><?=htmlspecialchars($v["txt"])?></textarea>
<?		}			?>				
				<span>sk:</span>
				<textarea name="zastup_sk_<?=$v["zastup"]?>"><?=htmlspecialchars($data["sk"][$v["zastup"]]["txt"])?></textarea>
				<span>en:</span>
				<textarea name="zastup_en_<?=$v["zastup"]?>"><?=htmlspecialchars($data["en"][$v["zastup"]]["txt"])?></textarea>
				<span>de:</span>
				<textarea name="zastup_de_<?=$v["zastup"]?>"><?=htmlspecialchars($data["de"][$v["zastup"]]["txt"])?></textarea>
			</td>
		</tr>
<?
	}
	$str = ob_get_clean();

//	pre($_POST);
?>
	<form action="<?=$_SERVER["SCRIPT_URI"]?>" method="post">
	<table class="prehled_table">
		<tr>
			<th>Zastupny text</th>
			<th>Lang: text</th>
		</tr>
		<?=$str?>
	</table>
	<input type="submit" value="Ulozit">
	</form>


</div><!--/id="admin"-->
<?

require_once("../inc/footer.php");

?>

