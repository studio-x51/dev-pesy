<?

// nechat zakomentovane, dela neplechu pri loginu FB
//error_reporting(E_ALL);
//ini_set('display_errors', 1);


require_once("../inc/inc.php");
require_once("../inc/fce_admin.php");
if($_SESSION["aplikace_typ_id"]) {
	// musim nacist lokalni nastaveni a fce
	require_once("../".$_SESSION["aplikace_typ_id"]."/inc/siteconf.php");
	require_once("../".$_SESSION["aplikace_typ_id"]."/inc/fce.php");
}

$redirect = 1;

logit("debug", "GET:".serialize($_GET).", sess user=".$_SESSION["user"][APLIKACE_UNIQ_ID]);

// kontrola validni SESSION, pokud neni presmeruji vratim a v JS presmeruji na vstup!
if(!$_SESSION["user"][APLIKACE_UNIQ_ID] && fetch_uri("type","g")!="login" && fetch_uri("type","g")!="nahled_app") {
	if(fetch_uri("type","g")=="nahled_app") {
		echo json_encode(array("session" => "expired", "try_app" => fetch_uri("aplikace_typ_id","g")));
		exit;
	}
	echo json_encode(array("session" => "expired"));
	exit;
}


switch(fetch_uri("type","gp")) {
	// ULOZENI UZIVATELE
	case "login": 
		unset($_SESSION["premium"]);
		echo Login2app();
		TestPremiumMember();
		break;

	case "logout": 
		if(isset($_SESSION["user"][APLIKACE_UNIQ_ID])) {
			logit("debug","LOGOUT: owner ".$_SESSION["user"][APLIKACE_UNIQ_ID].", qs:".$_SERVER["QUERY_STRING"]);
			unset($_SESSION);
		}
		else
			logit("debug","LOGIN: cancel");
		break;

	case "mysql_month_diff":
		echo json_encode(mysql_month_diff(fetch_uri("from","g"), fetch_uri("to","g")));
		break;
	case "switch_app_on_off":
		echo json_encode(switch_app_on_off(fetch_uri("aplikace_id","g")));
		break;
	case "platba":
		echo json_encode(array("html" => show_platba(fetch_uri("aplikace_id","g") ? fetch_uri("aplikace_id","g") : $_SESSION["aplikace_id"], "popup")));
		break;
	case "platba_premium":
		echo json_encode(array("html" => show_platba_premium("popup")));
		break;
	case "vote":
		echo vote_new_app(fetch_uri("app_vote","g"));
		break;

	case "checkSlevKod":
		echo json_encode(checkSlevKod(fetch_uri("slev_kupon","g"), fetch_uri("aplikace_id","g")));
		break;

	case "deleteapp":
		echo json_encode(deleteApp(fetch_uri("aplikace_id","g")));
		break;
	case "saveContactEmail":
//		echo json_encode(array("isValidEmail" => isValidEmail(trim(fetch_uri("contact_email","g")))));

		echo json_encode(SaveContactEmail(trim(fetch_uri("contact_email","g")), fetch_uri("smartmailing_id","g")));
		break;


	// slider vyhery (ceny v soutezi)
	case "reloadSliderVyhry": 
//			echo slider_slick_vyhry();
		echo json_encode(array("html" => slider_slick_vyhry()));
		break;

	case "delete_price": 
//			echo slider_slick_vyhry();
		if(administrace_vyhry_stop() == "stop") {
			echo json_encode(array("stav_spusteno" => "stop"));
			break;
		}
		echo json_encode(array("html" => delete_price(fetch_uri("vyhra_id","g"))));
		break;


	
	// PopVyhra - pop okno na zadavani vyher
	case "reloadPopVyhra": 
		$popvyhra = PopVyhra(fetch_uri("vyhra_id","g"));
		echo json_encode($popvyhra);
//			echo PopVyhra(fetch_uri("vyhra_id","g"));
		break;
	// PopBaner - pop okno na zadavani baneru
	case "reloadPopBaner": 
		echo json_encode(array("html" => PopBaner(fetch_uri("baner_id","g"))));
		break;
	// pop okno na zadavani FB OG
	case "reloadPopFbOg": 
		echo json_encode(array("html" => PopFbOg()));			
		break;

	// pop okno na zavani FB OG
	case "reloadPopFbOgDashboard": 
		echo json_encode(array("html" => PopFbOg("dashboard", fetch_uri("aplikace_id","g"))));			
		break;

	//	ShowPopRules- pop okno pro administraci pravidel souteze (editor tynimce)
	case "reloadShowPopRules": 
		echo json_encode(array("html" => ShowPopRules()));
//			echo ShowPopRules();
		break;
	//	ShowRules- pop okno zobrazujici pravidla souteze
	case "reloadShowRules": 
		echo json_encode(array("html" => ShowRules()));
//			echo ShowRules();
		break;
	case "getDefaultRules": 
//		resetRules();
		echo json_encode(array("reset" => resetRules(), "html" => ShowRules()));
//			echo ShowRules();
		break;


	case "saveRules": 
		logit("debug","action saveRules auto coze?");
		$str = fetch_uri("pravidla","p");
//		$str = preg_replace ('/<span class="hl">([^<]+)<\/span>/', '$1', $str);
//		<span\s+style='background:yellow'>([^<]+)</span>
//		$1
		echo saveRules($str);
		break;
	case "adress_set": 
		echo adress_set();
		break;

	case "nahled_app": 
		echo json_encode(array("nahled" => nahled_app(fetch_uri("aplikace_typ_id","g")), "status_login" => $_SESSION["user"][APLIKACE_UNIQ_ID] ? "sign_on" : "sign_off"));
//		echo nahled_app(fetch_uri("aplikace_typ_id","g"));
		break;
	case "head_help_off": 
		$_SESSION["head_help_off"][$_SESSION["aplikace_typ_id"]] = true;
		dbQuery("REPLACE head_help_off SET off=1, aplikace_typ_id=#1, fb_id=#2",$_SESSION["aplikace_typ_id"], $_SESSION["user"][APLIKACE_UNIQ_ID]);
		echo json_encode(array());
//		echo nahled_app(fetch_uri("aplikace_typ_id","g"));
		break;
	case "save_screenshot":
		$img = $_POST["img_val"];
		//Get the base-64 string from data
		$filteredData=substr($_POST['img_val'], strpos($_POST['img_val'], ",")+1);
		//Decode the string
		$unencodedData=base64_decode($filteredData);
		$base_dir = $CONF_BASE_DIR."users_data/".$_SESSION["aplikace_id"]."/";
		$from = $base_dir.'screen_shot_big.png';
		logit("debug","save_screenshot:".$from);
		file_put_contents($from, $unencodedData);

		$args = "-strip -resize \"111x128\" -colorspace RGB -quality 80";
		$cmd = $CONF_XTRA["IMAGE_MAGICK"]["CMD_CONVERT"] ? $CONF_XTRA["IMAGE_MAGICK"]["CMD_CONVERT"] : "convert";
		$convert = "$cmd $args '$from' '".$base_dir."/screen_shot.jpg'";
		passthru($convert, $output);

		logit("debug","make screenshot: ".$convert." | output:".$output);
		if($output == 0) {
//			unlink($from);
		}

//		$args = "-strip -resize \"146x204\" -colorspace RGB -quality 80";
//	    $cmd = $CONF_XTRA["IMAGE_MAGICK"]["CMD_CONVERT"] ? $CONF_XTRA["IMAGE_MAGICK"]["CMD_CONVERT"] : "convert";
//	    passthru("$cmd $args \"$unencodedData\" \"$to\"", $output);
		//Save the image
//		echo json_encode(array("img" => $output, "cmd" => "$cmd $args \"$unencodedData\" \"$to\""));
		echo json_encode(array("img" => $output, "cmd" => "$cmd $args '$unencodedData' '$to'"));
		break;

		// ulozi id FB stranky po prideleni aplikace do TAB teto stranky
	case "saveFBPage":
		logit("debug", "saveFBPage session, ".serialize($_SESSION).", GET=".serialize($_GET));
		saveFBPage();
		break;
	case "save_app_title":
		logit("debug", "save_app_title, ".serialize($_SESSION).", GET=".serialize($_GET));
		dbQuery("UPDATE aplikace SET title=#1, `og:title`=#1 WHERE aplikace_id=#2", fetch_uri("title","g"), fetch_uri("aplikace_id","g"));
		echo json_encode(array("dbaff" => dbAff()));
		break;
	case "showSetLanguage":
		echo json_encode(array("html" => PopSetLanguage()));
		break;
	case "setLanguage":
		$lang = split("_", fetch_uri("lang","g"))[1];
		logit("debug", "setLanguage owner: ".$_SESSION["user"][APLIKACE_UNIQ_ID].", ".$lang);
		echo json_encode(set_lang($lang, $_SESSION["user"][APLIKACE_UNIQ_ID]));
		break;

	// ulozime fakturacni udaje - odberatele!
	case "setFakturace":
		logit("debug", "setFakturace: ".serialize($_GET));
		// ulozim do smartmailing!
		if(fetch_uri("what","g") == "premium_academy") {
			
		}
		echo json_encode(setFakturace(fetch_uri("fb_id","g")));
		break;
	case "showSetFakturace":
		// po provedene platbe beru fb_id ze SESSION (parametr "session_fb_id" ve JS fci nastaveni_fakturace("session_fb_id"))
		echo json_encode(array("html" => PopSetFakturace(fetch_uri("fb_id","g") == "session_fb_id" ? $_SESSION["user"][APLIKACE_UNIQ_ID] : fetch_uri("fb_id","g"), fetch_uri("action","g"))));
		break;
	case "showFaktury":
		// zobrazi faktury v pdf
		echo json_encode(array("html" => PopShowFaktury(fetch_uri("fb_id","g"))));
		break;

	case "showGratulace":
		// po provedene platbe beru fb_id ze SESSION (parametr "session_fb_id" ve JS fci nastaveni_fakturace("session_fb_id"))
		echo json_encode(showGratulace());
		break;

	case "adminDashboard":
		echo json_encode(adminDashboard(fetch_uri("aplikace_id","g")));
		break;
	case "resetpicEdit":
		echo json_encode(picEdit());
		break;
	case "refreshNahrano":
		echo json_encode(array("html" => refreshNahrano()));
		break;

	// ulozi vlastni prvky (fortografie)
	case "saveOwnObjects":
		echo json_encode(saveOwnObjects());
		break;
	// nacte okno v dashboard na upravy FB OG
	case "showOgAdmin":
		echo json_encode(showOgAdmin(fetch_uri("aplikace_id","g")));
		break;
	// zmeni statis uzivatele
	case "changeStatusOwner":
		echo json_encode(changeStatusOwner(fetch_uri("fb_id","g"), fetch_uri("status","g")));
		break;

	default:
		echo "def ??";
		logit("debug","action undefined [".fetch_uri("type","pg")."]");
}


?>
