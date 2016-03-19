<?

// nechat zakomentovane, dela neplechu pri loginu FB
//error_reporting(E_ALL);
//ini_set('display_errors', 1);


require_once("../inc/inc.php");
require_once("../inc/gopay_fce.php");

$CONF = setConfig();

// OSTRA
$rs = dbQuery("?SELECT p.*, sk.owner_fb_id FROM platba p, slev_kody sk WHERE sk.kod=p.spec_slev_kod AND (p.what_platby='premium_academy' OR p.what_platby='pdf26napadu') AND p.state='PAID' AND zaplaceno_do < NOW() AND zaplaceno_do > NOW() - INTERVAL 1 DAY");


// TEST DLE PARENT ID PLATBY
/*
$test_gopay_id = 3028466168;
$test_gopay_id = 3229653791;
$test_gopay_id = 3028456730; // moje test platba
$rs = dbQuery("?SELECT p.*, sk.owner_fb_id FROM platba p, slev_kody sk WHERE sk.kod=p.spec_slev_kod AND p.what_platby='premium_academy' AND p.state='PAID' AND (gopay_id=#1 OR gopay_parent_id=#1) ORDER BY zalozeno DESC limit 1", $test_gopay_id);
*/
//exit;

// selectu vsechny platby dnes koncici | toby asi melo stacit!
//dbQuery("SELECT * FROM platba WHERE what_platby='premium_academy' AND state='PAID' AND zaplaceno_do < NOW() AND zaplaceno_do > NOW() - INTERVAL 1 DAY");
echo dbRows();
while($row = dbArr2($rs)) {
	pre($row);

	// pro test ucely, prime volani 
			$args = array(
//				"id_platby" => 3026846199, // moje test 1. on_demand platby! na aa/
//				"id_platby" => 3028456730, // moje test platba - on_demand platby! na aa-test
	//			"id_platby" => 3230947889, // moje ostra 1. on_demand platby! na aa/
				"id_platby" => $row["gopay_parent_id"] ? $row["gopay_parent_id"] : $row["gopay_id"], // OSTRA DLE SELECT Z DB!
				"druh_platby" => "premium_academy",
				"typ_platby" => "ON_DEMAND",
				"aplikace_id" => 0,
//				"amount" => (mujpc() ? 1 : $CONF_XTRA["premium_cena_mesic"]) * 100, // pro mujpc 1 kc jinak premium 590!
				"amount" => $CONF_XTRA["premium_cena_mesic"] * 100, // cena musi se nasobit 100 (je v halerich!)
				"amount_together" => $row["amount_together"],
				"order_number" => $row["owner_fb_id"], // zde fb_id!
				"order_description" => "x51academy upis",
				"return_url" => urldecode(fetch_uri("return_url","p")),
				"from" => date("Y-m-d"),
				"to" => $row["do"],
				"delka_trvani" => $row["delka_trvani"],
				// odectu posledni den, aby nedoslo jeste k platbe navic v poslednim mesici, pac stahuji dopredu!
	//			"recurrence_date_to" => "2015-12-13",
			);

	pre($args,"args");


	### vytvoreni gopay platby ###
	$createPayment = createRecurrenceOnDemand($args); // vytvoreni standardni platby primo ve fci
	pre($createPayment,"createPayment");
	logit("debug","GOPAY createRecurrenceOnDemand args: ".serialize($args),$CONF_BASE_DIR."logs/gopay_create_payment.log");
	logit("debug","GOPAY createRecurrenceOnDemand : ".json_encode($createPayment),$CONF_BASE_DIR."logs/gopay_create_payment.log");
	echo json_encode(array("gw_url" => $createPayment->gw_url, $createPayment->id));
	### /vytvoreni gopay platby ###

	sleep(5);
}

logit("debug","ONDEMAND: row=".serialize($row)." | artgs=".serialize($args),$CONF_BASE_DIR."logs/gopay_on_demand.log");

exit;



?>
