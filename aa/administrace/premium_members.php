<?
require_once("../inc/inc.php");
require_once("../inc/fce_admin.php");

if(!$_SESSION["access_admin_ss"]) {
	header("location:./");
}


require_once("../inc/header.php");
?>
<script type="text/javascript" src="../js/admin.js?time=<?=$CONF_XTRA["TIME_FILES"]?>"></script>
<?
/*
	echo "<p>.</p>";
	echo "<p>.</p>";
	echo "<p>.</p>";
	echo "<p>.</p>";
	echo "<p>.</p>";
*/

if($_POST) {
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
}

?>
<link href="../css/admin.css" rel="stylesheet" media="all" type="text/css">

<div id="admin">
<?
	menu_admin();
?>
	<div>
	<h1><?=txt("dashboard-description_licence-premium")?></h1>
<?

?>	
	<h2>Příjem za období</h2>
	<table class="prehled_table">
		<tr>
			<th>Období</th>
			<th>Sum</th>
			<th>Měna</th>
		</tr>
		<?echo statistika_prijmu_obdobi();?>
	</table>	

	<h2>Délka trvání členství</h2>
	<?echo statistika_delky_clenstvi();?>

	<h2>Vstup do členství Premium Members</h2>
	<?=statistika_mesicni_clenstvi()?>

	<h2>Přehled</h2>
	<table class="prehled_table">
		<?=statistika_celkovy_prehled()?>
	</table>


	</div>

</div><!--/id="admin"-->
<?

require_once("../inc/footer.php");

/**
* statistika prijmu v obdobi a mene
* pripravi radky tabulky!
*/
function statistika_prijmu_obdobi()
{
/* statistiky */
	ob_start();
	dbQuery("SELECT sum(amount) celkem_mesic, currency, MONTH(p.zalozeno) month, YEAR(p.zalozeno) year FROM platba p, owner o, slev_kody k WHERE state = 'PAID' AND spec_slev_kod IS NOT NULL $wh AND spec_slev_kod=kod AND owner_fb_id=fb_id GROUP BY YEAR(p.zalozeno), MONTH(p.zalozeno), currency ORDER BY YEAR(p.zalozeno), MONTH(p.zalozeno), currency;");
	while($row = dbArrTiny()) {
//		$members_mothh[$row["fb_id"]][] = $row;
//		pre($row, "members_mothh");
?>
		<tr>
			<td><?=$row["month"]."/".$row["year"]?></td><td><?=($row["celkem_mesic"]/100)?></td><td><?=$row["currency"]?></td>
		</tr>
<?

	}
	return ob_get_clean();
}


/**
* statistika delky trvani
* pripravi radky tabulky!
*/
function statistika_delka_trvani()
{
/* statistiky */
	ob_start();
	dbQuery("SELECT sum(amount) celkem_mesic, currency, MONTH(p.zalozeno) month, YEAR(p.zalozeno) year FROM platba p, owner o, slev_kody k WHERE state = 'PAID' AND spec_slev_kod IS NOT NULL $wh AND spec_slev_kod=kod AND owner_fb_id=fb_id GROUP BY YEAR(p.zalozeno), MONTH(p.zalozeno), currency ORDER BY YEAR(p.zalozeno), MONTH(p.zalozeno), currency;");
	while($row = dbArrTiny()) {
//		$members_mothh[$row["fb_id"]][] = $row;
//		pre($row, "members_mothh");
?>
		<tr>
			<td><?=$row["month"]."/".$row["year"]?></td><td><?=($row["celkem_mesic"]/100)?></td><td><?=$row["currency"]?></td>
		</tr>
<?

	}
	return ob_get_clean();
}


/**
* statistika celkoveho prehledu vsech plateb
* pripravi radky tabulky!
*/
function statistika_celkovy_prehled()
{
	// 1. hash dat odberatele
	dbQuery("SELECT nazev, fb_id FROM odberatel");
	while($row = dbArr())
		$hash_odb[$row["fb_id"]] = $row["nazev"];

	$wh = " AND gopay_parent_id IS NULL";
//	dbQuery("SELECT * FROM platba p, owner o, slev_kody k, kod WHERE state = 'PAID' AND spec_slev_kod IS NOT NULL $wh AND spec_slev_kod=kod AND owner_fb_id=fb_id ORDER BY p.zalozeno;");
	// 2. nactu vsechny najitele, kteru maji zaplacene premium!
	dbQuery("SELECT p.zalozeno, gopay_id, amount, currency, CONCAT(prijmeni, ' ', jmeno) cele_jmeno, kod, fb_id, o.status status_owner FROM platba p, owner o, slev_kody k WHERE state = 'PAID' AND spec_slev_kod IS NOT NULL $wh AND spec_slev_kod=kod AND owner_fb_id=fb_id ORDER BY p.zalozeno;");
	while($row = dbArrTiny()) {
		$members[$row["fb_id"]]= $row;
	}
//	pre($members, "members");

//	$wh = " AND gopay_parent_id IS NOT NULL";
	$wh = "";
	// 3. nactu vsechny platby!
	dbQuery("SELECT p.zalozeno, UNIX_TIMESTAMP(p.zalozeno) utime, gopay_id, gopay_parent_id, amount, currency, kod, fb_id FROM platba p, owner o, slev_kody k WHERE state = 'PAID' AND spec_slev_kod IS NOT NULL $wh AND spec_slev_kod=kod AND owner_fb_id=fb_id ORDER BY zalozeno;");
	while($row = dbArrTiny()) {
		$members_platby[$row["fb_id"]][] = $row;
//		pre($row);
	}

//	pre($members_platby[10204707402780719], "members_platby	10204707402780719");
//	pre($members_platby, "members_platby all");
//	pre($members, "members all");
	
	// zobnu maximalni pocet plateb, pro doplneni volnych sloupecku v tabulce!
	foreach ($members_platby as $key=>$value) {
	    $max_cols = max(count($members_platby[$key]),$max_cols);
	}

	$j = 1;
	foreach($members as $gopay_id => $data) {
		$class = "status_".$data["status_owner"];

		ob_start();
		renderOptionsFromStr($data["status_owner"], "active", "active~aktivní;end~ukončeno;pending~v řešení;refund~refund");
		$stav = ob_get_clean();
		$table .= "<tr class='".$class."'>";
		$table .= "<td>".$j++."</td>";
//		$table .= "<td>".$data["cele_jmeno"]." | ".TestPremiumMember($data["fb_id"])."</td>";
		$table .= "<td>".$data["cele_jmeno"]."</td>";
		$table .= "<td class=\"edit_fakturace".($hash_odb[$data["fb_id"]] ? " done " : "")."\" rel=\"".$data["fb_id"]."\">".$data["fb_id"]."</td>";
		$table .= "<td>".($data["amount"]/100)." ".$data["currency"]."</td>";
		$table .= "<td>";
		if($members_platby[$gopay_id]) {
			foreach($members_platby[$gopay_id] as $k => $data_platba) {
				if($data_platba["kod"] != $old_kod)
					$table .= $data_platba["kod"]."<br>";
				$old_kod = $data_platba["kod"];
			}
		}
		$table .= "</td>";
		$i = 1;
		if($members_platby[$gopay_id]) {
			foreach($members_platby[$gopay_id] as $k => $data_platba) {
				$class_po_terminu = "ok";
				if($i == count($members_platby[$gopay_id]))
					$class_po_terminu = strtotime("+1 month", $data_platba["utime"]) < time() ? "po_terminu" : "";
//					$class_po_terminu = strtotime("+1 month +2 days", $data_platba["utime"]) < time() ? "po_terminu" : "";
				$table .= "\n<td class=\"".$class_po_terminu."\">".$data_platba["zalozeno"]."</td>\n";
				$i++;
			}
		}
		for($i; $i <= $max_cols; $i++) {
			$table .= "<td> - </td>";
		}
		$table .=  "<td>
		<select name='status_owner' class='change_status_owner' rel='".$data["fb_id"]."'>
".		$stav
."
		</select>
		</td>";
	}		
	$table .=  "</tr>";
	ob_start();
?>		<tr>
			<th>#</th>
			<th>Jméno</th>
			<th>FB ID</th>
			<th>Částka</th>
			<th>Slev. kód</th>
<?			for($i = 1; $i <= $max_cols; $i++) {
?>			<th>Platba</th>
<?			}
?>			
			<th>Status</th>
		</tr>
<?	$table = ob_get_clean().$table;

	return $table;
}		

/**
* statistika celkoveho prehledu vsech plateb
* pripravi radky tabulky!
*/
function statistika_delky_clenstvi()
{
	$ok_platba = array();
	$ko_platba = array();
	dbQuery("SELECT count(*) pocet_plateb, UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 1 MONTH)) AS sub_month, UNIX_TIMESTAMP(MAX(p.zalozeno)) AS max_date, UNIX_TIMESTAMP(p.zalozeno) utime, fb_id FROM platba p, owner o, slev_kody k WHERE state = 'PAID' AND spec_slev_kod IS NOT NULL $wh AND spec_slev_kod=kod AND owner_fb_id=fb_id GROUP BY fb_id ORDER BY p.zalozeno");
	while($row = dbArrTiny()) {
		$members_platby[$row["fb_id"]][] = $row;
		if($row["max_date"] > $row["sub_month"])
			$ok_platba[$row["pocet_plateb"]]++;
		else
			$ko_platba[$row["pocet_plateb"]]++;
	}
	ksort($ok_platba);
	ksort($ko_platba);
//	pre($ok_platba,"ok");
//	pre($ko_platba,"ko");
//	pre($members_platby[10204707402780719], "members_platby	10204707402780719");
//	pre($members_platby, "members_platby all");
//	pre($members, "members all");

	$j = 1;
	ob_start();
?>	<h3>Aktivní členství</h3>
	<table class="prehled_table">
		<tr>
			<th>Počet měsíců</th>
			<th>Počet Premium Members</th>
		</tr>
<?	if($ok_platba) {
		foreach($ok_platba as $months => $users) {
	?>		<tr>
				<td><?=$months?></td>
				<td><?=$users?></td>
			</tr>
	<?
		}
?>		<tr>
			<th>Celkem:</td>
			<th><?=array_sum($ok_platba)?></td>
		</tr>
<?
	}	
?>	</table>
	

	<h3>Ukončené členství</h3>
	<table class="prehled_table">
		<tr>
			<th>Počet měsíců</th>
			<th>Počet Premium Members</th>
		</tr>
<?	if($ko_platba) {
		foreach($ko_platba as $months => $users) {
	?>		<tr>
				<td><?=$months?></td>
				<td><?=$users?></td>
			</tr>
<?			
		}
?>		<tr>
			<th>Celkem:</td>
			<th><?=array_sum($ko_platba)?></td>
		</tr>
<?
	}	
?>	</table>
<?

	$table = ob_get_clean();

	return $table;
}		

/**
* statistika prehledu 1. platby dle mesicu/roku
* pripravi radky tabulky!
*/
function statistika_mesicni_clenstvi()
{
	dbQuery("SELECT count(*) pocet_plateb, MIN(MONTH(p.zalozeno)) AS min_date, YEAR(p.zalozeno) year, MONTH(p.zalozeno) month FROM platba p, owner o, slev_kody k WHERE state = 'PAID' AND spec_slev_kod IS NOT NULL AND spec_slev_kod=kod AND owner_fb_id=fb_id GROUP BY fb_id ORDER by year DESC, month DESC");
	while($row = dbArrTiny()) {
		$members_platby[$row["year"]." / ".$row["month"]]++;
	}
//	pre($members_platby, "members_platby year/month");

	$j = 1;
	ob_start();
?>	
	<table class="prehled_table">
		<tr>
			<th>Rok / měsíc</th>
			<th>Počet nových Premium Members</th>
		</tr>
<?	if($members_platby) {
		foreach($members_platby as $date => $pocet) {
	?>		<tr>
				<td><?=$date?></td>
				<td><?=$pocet?></td>
			</tr>
	<?
		}
	}	
?>	</table>
<?
	
	$table = ob_get_clean();

	return $table;
}		


?>
