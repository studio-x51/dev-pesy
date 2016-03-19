<?
require_once("../inc/inc.php");
require_once("../inc/fce_admin.php");

if(!$_SESSION["access_admin_ss"]) {
	header("location:./");
	exit;
}


if(fetch_uri("pocet_kodu", "p")) {
	$kody = "Kampaň;Kód;Sleva v %;Typ;Platnost od;Platnost do\n";
	$j = 0;
	for($i = 1; $i <= fetch_uri("pocet_kodu", "p"); $i++) {
		$kod = makeSlevCode(fetch_uri("kampan", "p"), fetch_uri("sleva", "p"), fetch_uri("typ", "p"), 1, strtotime(fetch_uri("platnost_od","p")), strtotime(fetch_uri("platnost_do","p")));
		$kody .= fetch_uri("kampan", "p").";".$kod.";".fetch_uri("sleva", "p").";".fetch_uri("typ", "p").";".fetch_uri("platnost_od", "p").";".fetch_uri("platnost_do", "p")."\n";
	}
	download_send_headers("slev_code_export_" . date("Y-m-d,H.i.s") . ".csv");
	echo $kody;
//	header("location:slevove_kody");
	exit;
	die();
}





require_once("../inc/header.php");
?>
<link href="../css/admin.css" rel="stylesheet" media="all" type="text/css">

<script>
$(function() {
	var txt_alert = "";
	$('#platnost_od').datetimepicker();
	$('#platnost_do').datetimepicker();
	$("#slev_kog_generator button").click(function() {
		if($.trim($("#kampan").val()).length < 1)
			txt_alert += "Zadejte název kampaně.\n";
//		if($('#platnost_od').val() >= $('#platnost_do').val()) 
		console.log(convertDateTime2Number($('#platnost_od').val()));
		console.log(convertDateTime2Number($('#platnost_do').val()));
		console.log(convertDateTime2Number($('#platnost_do').val()) - convertDateTime2Number($('#platnost_od').val()));
		if(convertDateTime2Number($('#platnost_do').val()) - convertDateTime2Number($('#platnost_od').val()) <= 0) {
			txt_alert += "Platnost od musí být menší než platnost do!\n";
		}
		if(txt_alert) {
			alert(txt_alert);
			txt_alert = "";
			return false;
		}
	});

});

function convertDateTime2Number(cs_datetime) {
	var split_date = cs_datetime.split("."); 
	var split_date2 = split_date[2].split(" "); 
	return split_date2[0]+split_date[1]+split_date[0]+split_date2[1].replace(":","");
}

</script>
<?
//pre($_POST);

for($i = 1; $i<=1000; $i++)
	$str_pocet_kodu .= "$i;";

for($i = 5; $i<=100; $i = $i+5)
	$str_sleva .= "$i;";

if(mujpc()) {
	$premium_type = ";3~3 - premium";
}

?>


<div id="admin">
	<?
	menu_admin();
	?>

	<h1>Slevové kódy</h1>
	<form id="slev_kog_generator" action="./slevove_kody" method="post">
	<label>Kampaň</label>
	<input type="text" name="kampan" id="kampan" />
	<br />
	<label>Počet kódů:</label>
	<select name="pocet_kodu">
	<?renderOptionsFromStr(false,1,substr($str_pocet_kodu, 0, -1));
	?>
	</select>
	<br />
	<label>Sleva v %:</label>
	<select name="sleva">
	<?renderOptionsFromStr(false,1,substr($str_sleva, 0, -1));
	?>
	</select>
	<br />
	<label>Typ:</label>
	<select name="typ">
	<?renderOptionsFromStr(false,1,"1~1 - unikátní;2~2 - opakovatelná".$premium_type);
	?>
	</select>
	<br />
	<label>Platnost od:</label>
	<input type="text" name="platnost_od" id="platnost_od" readonly="readonly" />
	<br />
	<label>Platnost do:</label>
	<input type="text" name="platnost_do"  id="platnost_do" readonly="readonly" />
	<button type="submit">GENERUJ</button>
	</form>

		<p>Pozn: typ (1 = unikatni, 2 = opakovane pro vice uzivatelu)</p>
		<table class="prehled_table">
			<th>kampaň</th>
			<th>kód</th>
			<th>sleva v %</th>
			<th>typ</th>
			<th>uplatněno</th>
			<th>založeno</th>
			<th>Platnost od</th>
			<th>Platnost do</th>
			<?
			dbQuery("SELECT * FROM slev_kody ORDER BY zalozeno");
			while($row = dbArr()) {
				echo "<tr>";
				echo "<td>".$row["kampan"]."</td>";
				echo "<td>".$row["kod"]."</td>";
				echo "<td>".$row["sleva"]."</td>";
				echo "<td>".$row["typ"]."</td>";
				echo "<td>".$row["uplatneno"]."</td>";
				echo "<td>".$row["zalozeno"]."</td>";
				echo "<td>".$row["platnost_od"]."</td>";
				echo "<td>".$row["platnost_do"]."</td>";
				echo "</tr>";
			}
?>			
		</table>

</div><!--/id="admin"-->
<?

require_once("../inc/footer.php");





?>

