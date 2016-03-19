<?

// nechat zakomentovane, dela neplechu pri loginu FB
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

require_once("../inc/inc.php");
require_once("../inc/gopay_fce.php");

// kontrola validni SESSION, pokud neni presmeruji vratim a v JS presmeruji na vstup!
if((!$_SESSION["user"][APLIKACE_UNIQ_ID] || $_SESSION["user"][APLIKACE_UNIQ_ID] < 100) && fetch_uri("type","g")!="login" && fetch_uri("type","g")!="nahled_app") {
	if(fetch_uri("type","g")=="nahled_app") {
		echo json_encode(array("session" => "expired", "try_app" => fetch_uri("aplikace_typ_id","g")));
		exit;
	}
	echo json_encode(array("session" => "expired"));
	exit;
}

$CONF = setConfig();

$druh_platby = fetch_uri("druh_platby","p");


switch(fetch_uri("type","p")) {
	case "gateWayPaypal": 
		if($druh_platby != "premium") {
			$_SESSION["paid_aplikace_id"] = fetch_uri("aplikace_id","p");
			// A) opakovany test slevoveho kuponu (co kdyby ho nekdo mezitim zadal?)!
			if(fetch_uri("slev_kupon","p")) {
				$checkSlevKod = checkSlevKod(fetch_uri("slev_kupon","p"), fetch_uri("aplikace_id","g"));
				// neplatny kod!
				if($checkSlevKod["state"]) {
					echo json_encode($checkSlevKod);
					break;
				}
				// slev_kod je ok zapisu do db tab aplikace
				dbQuery("UPDATE owner_x_app SET slev_kod=#2 WHERE aplikace_id=#1", fetch_uri("aplikace_id","p"), fetch_uri("slev_kupon","p"));
				if($checkSlevKod["sleva"] == "100") {
						// update tabulky  aplikace
					$to = mysql_date_add_month(date("Y-m-d"), fetch_uri("delka_trvani","p"));
					$od = date("Y-m-d");
					dbQuery("UPDATE aplikace SET od=#2, do=#3, spusteno=#4 WHERE aplikace_id=#1", fetch_uri("aplikace_id","p"), $od, $to, 1);

					// a rescrapnu !!!
					ob_start();
					rescrapeFbOg(fetch_uri("aplikace_id","p"));
					ob_end_clean();

	//				echo json_encode($checkSlevKod);
					echo json_encode(array("slev_kod" => "100%"));
					break;
				}
			}
			// B) test zda jsou ceny zaslane v url spravne
			dbQuery("SELECT * FROM aplikace WHERE aplikace_id=#1", fetch_uri("aplikace_id","p"));
			$row = dbArr();
			$price_by_date = $CONF_XTRA["price"][$row["aplikace_typ_id"]]['MONTH'] * fetch_uri("delka_trvani","p");
			$amount = $price_by_date;
			if(fetch_uri("slev_kupon","p")) 
				$amount = $amount - $amount * $checkSlevKod["sleva"] / 100;
			// B1) platba najednou
			if(fetch_uri("delka_trvani","p") > 1 && fetch_uri("typ_platby","p") == "ALL") {
				$amount = $CONF_XTRA["price"][$row["aplikace_typ_id"]]['MONTH'] * fetch_uri("delka_trvani","p") * (1 - $CONF_XTRA["price"][$row["aplikace_typ_id"]][fetch_uri("delka_trvani","p")."M_DISCOUNT"]);
				if(fetch_uri("slev_kupon","p")) 
					$amount = $amount - $amount * $checkSlevKod["sleva"] / 100;
				// nastaveni standardni castky platby
			}
			// B2) platba mesicni (3, 6, 12 mesicu)!!!!
			elseif(fetch_uri("delka_trvani","p") > 1 && (fetch_uri("typ_platby","p") == "MONTH" || fetch_uri("typ_platby","p") == "DAY")) {
				$amount = $amount / fetch_uri("delka_trvani","p");
				// nastaveni standardni castky platby
			}

	/*		// jen kontrolni vypis pro DEBUG !!!!!!!!!!!
			echo json_encode(array("POST" => $_POST, "js" => fetch_uri("amount","p"), "php" => $amount));
			break;
	*/

			// a samotne porovnani cen!!!
			if($amount != fetch_uri("amount","p")) {
				echo json_encode(array("state" => "Bad price!", "js" => fetch_uri("amount","p"), "php" => $amount, "get" => $_POST));
				break;
			}				
		}

//		$createPayment = createPayment(); // vytvoreni standardni platby primo ve fci
//		echo json_encode(array("html" => gateWayPaypalStandard(createPayment(array("price" => $price)))));
//      "price=" + price_by_date + "&type=gateWayPaypalStandard&return_url=" + window.location.href + encodeURIComponent("?action=gopay") + "&from=" + $("#from").val() + "&to=" + $("#to").val() + "&timezone=" + n + "&session_id=" + getSession()
		$to = mysql_date_add_month(date("Y-m-d"), fetch_uri("delka_trvani","p"));

		// test pro denni reccurency - zkratim dobu na 3 dny! (vlastne na 2, pak jeste u "recurrence_date_to" 1 den odectu :-))
		if(mujpc() && fetch_uri("typ_platby","p") == "DAY") 
			$to = date("Y-m-d", strtotime(date("Y-m-d"). ' + 3 days'));

		$args = array(
			"druh_platby" => fetch_uri("druh_platby","p"),
			"druh_platby_detail" => fetch_uri("druh_platby_detail","p"),
			"aplikace_id" => fetch_uri("aplikace_id","p"),
			"amount" => fetch_uri("amount","p") * 100,
			"amount_together" => fetch_uri("amount_together","p") * 100,
			"return_url" => urldecode(fetch_uri("return_url","p")),
			"from" => date("Y-m-d"),
			"to" => $to,
			"delka_trvani" => fetch_uri("delka_trvani","p"),
			// odectu posledni den, aby nedoslo jeste k platbe navic v poslednim mesici, pac stahuji dopredu!
			"recurrence_date_to" => date("Y-m-d", strtotime(" -1 day", strtotime($to))), // v manuale asi chyba! {20151231} musi byt iso oddeleno -
//			"recurrence_date_to" => "2015-12-13",
			"typ_platby" => fetch_uri("typ_platby","p"),
		);

		// pridam 2 argumenty
		if($druh_platby == "premium") {
			$args["druh_platby"] = "premium";
			$args["delka_trvani"] = $CONF_XTRA["premium_delka_trvani"];
			$args["from"] = date("Y-m-d");
			$args["to"] = date("Y-m-d",strtotime('+'.$CONF_XTRA["premium_delka_trvani"].' months'));
			if($args["typ_platby"] != "ON_DEMAND") {
				$args["typ_platby"] = "MONTH";
			}
			if($_SESSION["user"][APLIKACE_UNIQ_ID] > 1000) {
				$args["spec_slev_kod"] = makeSlevCode("premium", "100", 3, 1, time(), strtotime('+1 year'),$_SESSION["user"][APLIKACE_UNIQ_ID]) ;
			}
			$args["recurrence_date_to"] = date("Y-m-d", strtotime(" -1 day", strtotime($args["to"]))); // v manuale asi chyba! {20151231} musi byt iso oddeleno -
		}
//		echo json_encode($args);
//		$from = date("Y-m-d", strtotime(from));
		logit("debug","GOPAY createPayment? POST: ".serialize($_POST),$CONF_BASE_DIR."logs/gopay_create_payment.log");
		if(!$args["typ_platby"] || $args["typ_platby"] == "ALL") {
			$createPayment = createPayment($args); // vytvoreni standardni platby primo ve fci
			logit("debug","GOPAY createPayment args: ".serialize($args),$CONF_BASE_DIR."logs/gopay_create_payment.log");
			logit("debug","GOPAY createPayment : ".json_encode($createPayment),$CONF_BASE_DIR."logs/gopay_create_payment.log");
		}
		else {
			$createPayment = createRecurrencePayment($args); // vytvoreni standardni platby primo ve fci
			logit("debug","GOPAY createRecurrencePayment args: ".serialize($args),$CONF_BASE_DIR."logs/gopay_create_payment.log");
			logit("debug","GOPAY createRecurrencePayment : ".json_encode($createPayment),$CONF_BASE_DIR."logs/gopay_create_payment.log");
		}
//		pre($args, "createPayment, createRecurrencePayment");
//		echo json_encode($createPayment);
//		break;
		saveCreatePaymentState($createPayment, $args); // ulozeni do logu
//		echo json_encode(array("html" => gateWayPaypalStandard($createPayment), "json" => $createPayment));
		echo json_encode(array("gw_url" => $createPayment->gw_url, "test" => "neco" ));
		break;
	default:
		echo "def ??";
		logit("debug","action undefined [".fetch_uri("type","p")."]");
}
