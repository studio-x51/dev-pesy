<?
/**
* menu administrace
*/
function menu_admin()
{
		$menu = array(
			"slevove_kody" => "Slevové kódy",
			"prehled_aplikaci" => "Přehled obsazených aplikací",
			"pocty_aplikaci" => "Počty aplikací",
			"uspesnost_aplikaci" => "Úspěšnost aplikací",
			"prehled_uzivatelu" => "Přehled uživatelů",
			"preklady" => "Překlady",
			"test_aplikace" => "Přímé linky na test (detail) aplikace",
			"premium_members" => "Premium members",
      "premium_cancel" => "Premium - žádosti o zrušení členství",
		);

		foreach($menu as $php => $title) {
			$str .= "<li".(strpos($_SERVER["PHP_SELF"],$php) ? " class='current'" : "")."><a href=\"".$php."\">".$title."</a></li>\n";
//			$str .= "<li><a href=\"".$php."\">".$title."</a></li>\n";
		}
?>
		<ul id="menu_admin"><?=$str?></ul>
<?		
}

/**
* fce pro download soubboru
*/
function download_send_headers($filename) {
	  // disable caching
	  $now = gmdate("D, d M Y H:i:s");
	  header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
	  header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
	  header("Last-Modified: {$now} GMT");

	  // force download  
	  header("Content-Type: application/force-download");
	  header("Content-Type: application/octet-stream");
	  header("Content-Type: application/download");

	  // disposition / encoding on response body
	  header("Content-Disposition: attachment;filename={$filename}");
	  header("Content-Transfer-Encoding: binary");
}

/**
* zobrazi vsechny aplikace
*/
function showAllApps() {
	// hash plateb!
	dbQuery("SELECT * FROM platba WHERE state=#1", "PAID");
	while($row = dbArrTiny()) {
		$hash_platba[$row["aplikace_id"]][] = $row;
	}
//  hash slevove kody!
	dbQuery("SELECT * FROM slev_kody");
	while($row = dbArrTiny()) {
		$hash_slev_kody[$row["kod"]] = $row;
	}
//	pre($hash_slev_kody,"hash_slev_kody");



//	pre($hash_platba, "platby");
	$order = fetch_uri("order","g") ? fetch_uri("order","g") : "zalozeno DESC";
	// prijmeni = "prijmeni COLLATE utf8_czech_ci";
	dbQuery("SELECT * FROM owner_x_app oa, owner o, aplikace a WHERE fb_id=owner_id AND a.aplikace_id=oa.aplikace_id ORDER BY $order, a.aplikace_id");
	// hack na zobrazeni aplikace dle QS aplikace_id, pouze pro mujpc()
	ob_start();
	$i = 0;
	while($row = dbArr()) {
		if($row["prijmeni"] == "undefined" && $row["jmeno"] == "undefined") continue;
/*		if($i == 0)
			pre($row);
		$i++;
*/		?>
		<tr>
			<td><?=$row["aplikace_id"]?></td>
			<td><?=$row["og:title"]?></td>
			<td><?=$row["prijmeni"]." ".$row["jmeno"]?></td>
			<td><?=$row["zalozeno"]?></td>

			<td><?
			if($hash_platba[$row["aplikace_id"]]) {
				foreach($hash_platba[$row["aplikace_id"]] as $k => $data) {
					echo $data["zalozeno"]."<br>";
				}
			}
			?></td>
			<td><?
			if($hash_platba[$row["aplikace_id"]]) {
				foreach($hash_platba[$row["aplikace_id"]] as $k => $data) {
					echo castkaZGopay($data["amount"])." ".$data["currency"]."<br>";
				}
			}
			?></td>
			<td><?
			if($hash_slev_kody[$row["slev_kod"]]) {
				echo $hash_slev_kody[$row["slev_kod"]]["kod"]."<br>";
				echo $hash_slev_kody[$row["slev_kod"]]["sleva"]."%<br>";
			}
			else if($row["slev_kod"]) {
				echo $row["slev_kod"]."<br>";
			}
			?></td>

			<td><?
			if($hash_platba[$row["aplikace_id"]]) {
				foreach($hash_platba[$row["aplikace_id"]] as $k => $data) {
					echo $data["delka_trvani"]."<br>";
				}
			}
			
			?></td>
			<td><?
			if($hash_platba[$row["aplikace_id"]]) {
				foreach($hash_platba[$row["aplikace_id"]] as $k => $data) {
					echo $data["typ_platby"]."<br>";
				}
			}
			?></td>


			<td><?
			if($hash_platba[$row["aplikace_id"]]) {
				foreach($hash_platba[$row["aplikace_id"]] as $k => $data) {
					echo $data["zaplaceno_do"]."<br>";
				}
			}
			
			?></td>

			<td>
				<? if($row["canvas"]) {?>
					<a href="http://sprinte.rs/<?=$row["app_short_code"]?>" target="_blank">link</a>
				<? }?>
			</td>
		</tr>
		<?
	}
	$str = ob_get_clean();
	?>
	<table class="prehled_table">
		<tr>
			<th>App ID</th>
			<th>Title</th>
			<th>Provozovatel</th>
			<th>Založeno</th>
			<th>Datumy plateb</th>
			<th>Platby</th>
			<th>Slev. kod<br>Sleva</th>
			<th>Počet<br> měsíců</th>
			<th>Platba<br><span>celková ALL<br>měsíční MONTH</span></th>
			<th>Uhrazeno do</th>
			<th>Link</th>
		</tr>
		<?=$str?>
	</table>
<?	
}

function castkaZGopay($castka) {
	if(!$castka) return;
	return $castka / 100;
}


/**
* zobrazi vsechny uzivatele
*/
function showAllUsers($export = false) {

	// hash dat odberatele
	dbQuery("SELECT nazev, fb_id FROM odberatel");
	while($row = dbArr())
		$hash_odb[$row["fb_id"]] = $row["nazev"];


	// hash plateb!

	$order = fetch_uri("order","g") ? fetch_uri("order","g") : "datum_zalozeni DESC";
	// prijmeni = "prijmeni COLLATE utf8_czech_ci";
	dbQuery("SELECT * FROM owner ORDER BY $order");
	// hack na zobrazeni aplikace dle QS aplikace_id, pouze pro mujpc()
	ob_start();
	$i = 0;
	while($row = dbArr()) {
		if($row["prijmeni"] == "undefined" && $row["jmeno"] == "undefined") continue;
		$i++;

		if($export) {
			$rows .= 
			"'".$row["fb_id"].";".
			$row["prijmeni"]." ".$row["jmeno"].";".
			$row["datum_zalozeni"].";".
			$row["pohlavi"].";".
			$row["email"].";".
			$row["email_contact"]."\n";

		}
		else {
		?>
		<tr>
			<td><?=$row["prijmeni"]." ".$row["jmeno"]?></td>
			<td class="edit_fakturace<?=($hash_odb[$row["fb_id"]] ? " done " : "")?>" rel="<?=$row["fb_id"]?>"><?=$row["fb_id"]?></td>
			<td><?=$row["datum_zalozeni"]?></td>
			<td><?=$row["pohlavi"]?></td>
			<td><?=($row["email"] == "undefined" ? "" : $row["email"])?></td>
			<td><?=$row["email_contact"]?></td>

		</tr>
		<?
		}
	}
	if($export) {
		$rows = "FB ID;Příjmení jméno;Založeno;Sex;Email FB;Email kontakt\n".$rows;

		return $rows;
	}
	$str = ob_get_clean();
	?>
	<p>Počet uživatelů SS: <?=$i?></p>
	<table class="prehled_table">
		<tr>
			<th>Příjmení jméno</th>
			<th>FB ID</th>
			<th>Založeno</th>
			<th>Sex</th>
			<th>Email FB</th>
			<th>Email kontakt</th>
		</tr>
		<?=$str?>
	</table>
<?	
}


/**
* zmeni status majitele
*/
function changeStatusOwner($fb_id, $status) {
	if(!$_SESSION["x51admin"] || !$fb_id || $fb_id == "undefined" || !$status) return;
	dbQuery("UPDATE owner SET status=#2 where fb_id=#1", $fb_id, $status);
	return array("dbaff" => dbAff(), "status" => $status);
}

?>