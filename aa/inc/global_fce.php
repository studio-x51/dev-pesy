<?php
/**
*	fce AppDashBoard	-	maly dashboard u aplikace
*	getFbOgImage - nastaveni spravneho og:image!
*	PopBaner - pop okno na zadavani baneru
*	administrace_vyhry_stop - vraci "stop" pokud je aplikace spustena (v /inc/global_fce.php)
*	PopVyhra - pop okno na zadavani vyher (presunuto do /inc/global_fce.php)
*	PopBaner - pop okno na zadavani baneru (presunuto do /inc/global_fce.php)
*	GetDataAdressNames  - vrati array($adress_name, $adress_required, $adress_mandatory, $adress_user);
*						- $adress_name: nazvy pole, $adress_required: zda je povinne, $adress_mandatory: zda je povinne systemem, $adress_user: zadane hodnoty uzivatelem 
*	GetDataAdress - vrati dormular na zadavni adres, pro administraci i pro zadani uzivatele pri vyhre, ci jine akci (zadani emailu v budovani db)!
*	jmenoPrijmeniShort - vrati jmeno a 1. pismeno z prijmeni
*	UserData - vrati data o uzivateli
*	OwnerDataByApp  - vrati data o majiteli aplikace (fce OwnerData vraci pouze pro platby v inc/fce.php)
*	getStavApp - vrati stav aplikace
*	rescrapeFbOg - FB rescrabe OG paramteru!
*	getAppRun - testuj zda aplikace neskocila (date sloupec end v tabulce aplikace)
*	getCountdownTime - vrati spravny datum a cas pro Countdown
*	Countdown - vykresli Countdown
*	set_privileges - nastavi pripadna viceprava, napr pro x51admin (pouzivam i na mazani fotek ve fotosoutezi!)
*	save_time_to_end - nastaveni konce souteze, ulozi datum a cas konce souteze
*	makeSlevCode - v inc/global_fce.php - vyrobi slevovy kod!
*/              

// pro nacteni lokalnich funkci aplikace napriklad pri mazani vyher fci "delete_price" - potrebuji lokalni fci "setKodyVyhry:
/*
if(is_file($CONF_BASE_DIR.$_SESSION["aplikace_typ_id"]."/inc/fce.php"))
	require_once($CONF_BASE_DIR.$_SESSION["aplikace_typ_id"]."/inc/fce.php");
pre($_GET);
*/

/**
* fce nacte promenne CONF z databaze a ulozi do SESSION
*/
function setAppConfig($aplikace_id)
{
	global $CONF_STATIC, $CONF_XTRA;
	static $STATIC_CONF;
//	logit("debug","start fce setAppConfig($aplikace_id), GET[a]=".fetch_uri("a","g").", APLIKACE_ID=".APLIKACE_ID.", SESSION[aplikace_id] = ".$_SESSION["aplikace_id"]);
	if(isset($STATIC_CONF[$aplikace_id])) {
//		logit("debug","config CONF nacten ze STATIC_CONF");
		return $STATIC_CONF[$aplikace_id];
	}
	dbQuery("SELECT a.*, pa.page_id, pa.page_owner_id FROM aplikace a LEFT JOIN page_x_app pa ON a.aplikace_id = pa.aplikace_id WHERE a.aplikace_id=#1", $aplikace_id);
	while($row = dbArrTiny()) {
		foreach($row as $k => $v)
			$CONF[$k] = $v;
	}
	$CONF["scope"] = $CONF_STATIC["scope"];

	// 2) dle typu aplikace doplnim spravne url aplikace -> pouziju, pokud neni aplikace jeste na "FB tab" nebo jde o mobilni zarizeni!!!
	// dle /web/sprinte.rs/index.php
	switch($CONF["aplikace_typ_id"]) {
		// trezor - vyjimka! 
		case 2:
			$base_url = $CONF_XTRA["reset_app"]["2"]["url"].$CONF["aplikace_id"]."/?aplikace_id=".$CONF["aplikace_id"]; // musi byt adresa po presmerovani!
			break;
		default:
//			$base_url = $CONF_XTRA["reset_app"][$CONF["aplikace_typ_id"]]["url"].$CONF["aplikace_id"]."&type_zalozka=group";  // musi byt adresa po presmerovani!
			$base_url = $CONF_XTRA["reset_app"][$CONF["aplikace_typ_id"]]["url"].$CONF["aplikace_id"];  // musi byt adresa po presmerovani!
			break;
	}
	$CONF["og:url"] = $base_url;
	$STATIC_CONF[$aplikace_id] = $CONF;
//	logit("debug","config CONF nacten z MySQL");
	return $STATIC_CONF[$aplikace_id];
}



/**
* fce vrati text v aktualnim jazyce
* I: $z ... zastupny text
*/
function txt($z)
{
	global $CONF_XTRA;
	if(fetch_uri("action","g") == "refresh_texty")
		unset($_SESSION["texty"]);

	// automaticka obnova session texty po 10 minutach!
	if(!$_SESSION["texty_created_time"] || ($_SESSION["texty_created_time"] + 60*10) < time()) {
		logit("debug", "fce txt - automaticka obnova session texty!");
		unset($_SESSION["texty"]);
	}

/*	
	if(get_lang()== 'cs' && isset($CONF_XTRA["texty"][get_lang()][$z])) {
		if($_SESSION["x51admin"] && !strpos($_SERVER["SCRIPT_URI"], "administrace")) insert2table($z);
		return $CONF_XTRA["texty"][get_lang()][$z];
	}
*/
	// 1) pokud existuje u skinu soubor default-texts.php a je v nic zastup pro tento skin, vracim vzdy tento!
	//	  file default-texts.php nacitam ve fci setSkinTextPrvky()
	if($CONF_XTRA["texty-skin"][get_lang()][$z])
		return $CONF_XTRA["texty-skin"][get_lang()][$z];

	// 2) podivam se zda jiz nemam tento text v SESSION (samo i v DB!)
	if($_SESSION["texty"][get_lang()][$z]) {
//		logit("debug", "Text ze SESSION:".$z);
		return $_SESSION["texty"][get_lang()][$z];
	}

	// 3) text jeste neni v SESSION (nebo jeste neni SESSION zalozena)
//	logit("debug", "Text neni v SESSION:".$z);
	dbQuery("SELECT * FROM texty WHERE lg=#1", get_lang());
	while($row = dbArr()) {
		// nasypu do SESSION
		$_SESSION["texty"][get_lang()][$row["zastup"]] = $row["txt"];
	}

	// automaticka obnova - cas vytvoreni session texty!
	$_SESSION["texty_created_time"] = time();

	// 4) pokud jiz je v DB a i v SESSION vracim
	if($_SESSION["texty"][get_lang()][$z])
		return $_SESSION["texty"][get_lang()][$z];

	// 5) kdyz neni jeste v databazi pouziju default se souboru a ulozim do DB! (u cs verze)
	if(get_lang()== 'cs' && isset($CONF_XTRA["texty"][get_lang()][$z])) {
		// ulozim do DB texty!
		insert2table($z);
		return $CONF_XTRA["texty"][get_lang()][$z];
	}	

	// 6) mimo verzi cs vracim upraveno, aby bylo videt, ze neni prelozeno!
	if(isset($CONF_XTRA["texty"]["cs"][$z])) {
		// 6a) mimo verzi cs vracim surove cs!
		return $CONF_XTRA["texty"]["cs"][$z];
		// 6b) mimo verzi cs vracim upraveno, aby bylo videt, ze neni prelozeno!
//		return $aplikace_typ_id."|".strip_tags($CONF_XTRA["texty"]["cs"][$z])."|";
	}
	return "($z NENÍ DEFINOVÁNO)";
}

function insert2table($z)
{
	global $CONF_XTRA;
	if(get_lang() != 'cs')
		return;
	dbQuery("REPLACE texty SET `lg`=#1, `zastup`=#2, `txt`=#3", get_lang(), $z, $CONF_XTRA["texty"][get_lang()][$z]);
	
}

/**
* fce otestuje zda existuje text v aktualnim jazyce
* I: $z ... zastupny text
*/
function exists_txt($z)
{
	global $CONF_XTRA;
	if(isset($CONF_XTRA["texty"][get_lang()][$z]))
		return true;
	return false;
}


/**
* vrati datum z mysql ve spravnem formatu
*/
function dateFromSql($date)
{
	global $CONF_XTRA;
	return date($CONF_XTRA["dateformat"][get_lang()], dbDate($date));
}

/**
* fce vrati "stav" (spusteno, nespusteno) aplikace na zaklade platby a "termin od, do O", "kolik zbyva dni" a "licence" (placena/neplacena)
*	I: $data: (int)aplikace_id
*	O: $stav, $termin, $zbyva_dni, $licence
* TODO: fce getStavApp dodelat stav, kdy dostane placenou aplikaci za kod zdarma???
* Q: 
	1) free, uplne zdarma, kolikrat chce?
	2) placena zdarma? Nejaky voucher (kod, natuka a je to? - pridat do db tab "aplikace" zdarma)!
*	Zde neresim uzivatele
*/
function getStavApp($aplikace_id)
{
	global $CONF_XTRA;
	$CONF = setAppConfig($aplikace_id);
//	pre($_CONF, $aplikace_id);
	logit("debug", "getStavApp aplikace_id=$aplikace_id");
	// NEJDRIVE - KONTROLA, ZDA SE NEJEDNA o PREMIUM ucet!
	// A0) aplikace predplacene pres premium ucet
	//		1) nactu si slev_kod aplikace
	//		2) podivam se zda je zaplaceno
	// A) placena aplikace
	// overeni zda muze aplikace aktualne bezet, tzn, ze je radne zaplacena v tomto terminu (tj. ted :-)
	if($CONF_XTRA["price"][$CONF["aplikace_typ_id"]]["STAV"] == "placena") {
		dbQuery("SELECT p.*, p2.*, px.spec_slev_kod spec_slev_kod_px, p.od platba_od, p.od platba_do, p2.od platba_od2, p2.do platba_do2, px.od platba_od_px, px.do platba_do_px, oa.slev_kod, a.od AS app_od, a.do AS app_do, a.aplikace_id, a.spusteno, slev_kod, p.zaplaceno_do, p2.zaplaceno_do, o.typ AS typ_clena,
			DATEDIFF(a.do, now()) AS free_zbyva_dni,
			DATEDIFF(p.zaplaceno_do, now()) AS zaplaceno_zbyva_dni,
			DATEDIFF(p2.zaplaceno_do, now()) AS zaplaceno_zbyva_dni2,
			DATEDIFF(px.zaplaceno_do, now()) AS zaplaceno_zbyva_dni_px,
			DATEDIFF(p.do, now()) AS zbyva_dni,
			DATEDIFF(p2.do, now()) AS zbyva_dni2,
			DATEDIFF(px.do, now()) AS zbyva_dni_px
			FROM owner_x_app oa
			LEFT JOIN owner o ON oa.owner_id = o.fb_id
			LEFT JOIN platba p2 ON oa.slev_kod = p2.spec_slev_kod AND p2.state=#2
			LEFT JOIN platba_extra px ON oa.slev_kod = px.spec_slev_kod 
			, aplikace a
			LEFT JOIN platba p ON a.aplikace_id = p.aplikace_id AND p.state=#2
			WHERE a.aplikace_id = #1 AND oa.aplikace_id = a.aplikace_id ORDER BY p.zaplaceno_do, p2.zaplaceno_do DESC LIMIT 1",
				$aplikace_id, "PAID");
		$row = dbArrTiny(); 
		$row["od"] = $row["platba_od"];
		$row["do"] = $row["platba_do"];
		$row["spec_slev_kod"] = $row["spec_slev_kod_px"] ? $row["spec_slev_kod_px"] : $row["spec_slev_kod"];

//		pre($row, "data z getStavApp (db)");
		##### VYJIMKY #############
		// 1) vyjimka: POZOR vyjimka pro slevy 100%
		if($row["slev_kod"]) {
			dbQuery("SELECT sleva FROM slev_kody WHERE kod=#1", $row["slev_kod"]);
			$row2 = dbArrTiny(); 
			if($sleva = $row2["sleva"] == "100") {
				if($row["app_od"] && $row["app_do"])
					$termin = txt("dashboard-description_termin-od")." ".dateFromSql($row["app_od"])." ".txt("dashboard-description_termin-do")." ".dateFromSql($row["app_do"]);
				$row["zaplaceno_zbyva_dni"] = $row["zbyva_dni"] = $row["free_zbyva_dni"]; 
			}

//			pre($row2, "data z getStavApp sleva");
		}
//		pre($row, "data z getStavApp");
		// 2) vyjimka: POZOR vyjimka pro PREMIUM cleny
		if($row["typ_clena"] == "premium" && $row["spec_slev_kod"] && $row["slev_kod"] == $row["spec_slev_kod"]) {
			$licence = "premium";
			$row["zaplaceno_zbyva_dni"] = $row["zaplaceno_zbyva_dni_px"] ? $row["zaplaceno_zbyva_dni_px"] : $row["zaplaceno_zbyva_dni2"];
//			$row["zbyva_dni"] = $row["zbyva_dni2"]; 
			$row["zbyva_dni"] = $row["zbyva_dni_px"] ? $row["zbyva_dni_px"] : $row["zbyva_dni2"]; 
			$row["od"] = $row["platba_od_px"] ? $row["platba_od_px"] : $row["platba_od2"];
			$row["do"] = $row["platba_do_px"] ? $row["platba_do_px"] : $row["platba_do2"];
			$termin = txt("dashboard-description_termin-od")." ".dateFromSql($row["od"])." ".txt("dashboard-description_termin-do")." ".dateFromSql($row["do"]);
		}
		else
			$licence = "placena";
		if(is_array($row)) {
			if(!$termin && $row["od"] && $row["do"])
				$termin = txt("dashboard-description_termin-od")." ".dateFromSql($row["od"])." ".txt("dashboard-description_termin-do")." ".dateFromSql($row["do"]);
			$zbyva_dni = $row["zbyva_dni"];
			if($row["slev_kod"] == "FREEAPP") {
				$licence = "free";
				if($row["spusteno"] == 1) 
					$stav = "spusteno";
				else
					$stav = "stopnuto";
				$termin = txt("dashboard-description_termin-neomezeno");
			}
			// pro recurrence musi byt aktualne zaplaceno - rozdilne od zbyva_dni (1 den fora, kvuli recurrence platby, ktere probehne behem dne)
			elseif ($row["zaplaceno_zbyva_dni"] > -1) {
				$stav = "zaplaceno"; // zaplaceno a muze bezet
	//			pre($row, dateFromSql($row["od"]));
				if($row["spusteno"] == 1) {
					$stav = "zaplaceno_spusteno";	// je nastaveno na ON
				}
				else
					$stav = "zaplaceno_stopnuto";	// je nastaveno na OFF
			}
			// nezaplaceno
			else {
				if($row["zbyva_dni"] >= 0) {
					$stav = "nezaplaceno"; // termin existuje, ale neni zaplaceno, nejspise recurrence platba neprobehla!
/*					
					if($row["spusteno"] == 1) {
						$stav = "nezaplaceno_spusteno";	// je nastaveno na ON
					}
					else
						$stav = "nezaplaceno_stopnuto";	// je nastaveno na OFF
*/						
				}
				else {
					$stav = "ukonceno";
				}
			}
		}
		else {
			dbQuery("SELECT * FROM aplikace WHERE aplikace_id = #1",
			$aplikace_id);
			$row = dbArrTiny(); 

			$stav = "nezaplaceno"; // ani nenastaven termin, pac to souvisi!
			$termin = "";
		}
		if($licence != "free") {
			if($stav == "nezaplaceno") 
				$platba = txt("dashboard-description_platba-link");	
			elseif($stav == "ukonceno") 
				$platba = txt("dashboard-description_platba-link-prodlouzit");	
			elseif($zbyva_dni <= 3) 
				$platba = txt("dashboard-description_platba-link-prodlouzit");	
		}

		return array("stav"=>$stav, "termin"=>$termin, "zbyva_dni"=>$zbyva_dni, "licence"=>$licence, "spusteno"=>$row["spusteno"], "platba" => $platba); // txt("dashboard-description_licence-placena"))
	}
	// B) aplikace zdarma ???
	else {
		dbQuery("SELECT * FROM aplikace WHERE aplikace_id = #1",
				$aplikace_id);
		$row = dbArrTiny(); 
		if($row["spusteno"] == 1) {
			$stav = "spusteno";	// je nastaveno na ON
		}
		else $stav = "stopnuto";
		$termin = txt("dashboard-description_termin-neomezeno");

		return array("stav"=>$stav, "termin"=>$termin, "zbyva_dni"=>"", "licence"=>"free", "spusteno"=>$row["spusteno"]); // txt("dashboard-description_licence-placena"))
//		return array($stav, $termin, "", "free", $row["spusteno"]); // txt("dashboard-description_licence-placena"))
			
//		return array(true, "", txt("dashboard-description_licence-free"));		
	}
}

/**
* fce vraci css class "stop" pokud je soutez spoustena
*/
function administrace_vyhry_stop() {
	$getStavApp = getStavApp($_SESSION["aplikace_id"]);
	if(substr($getStavApp["stav"],-8) == "spusteno") {
		return "stop";
	}
	return false;
}



/**
* switchne aplikace ON /OFF a vrati pole (stav, spusteno, zbyva dni) v textove podobe rovnou pro zobrazeni 
* zpracuje se v js a nastavi!!
*/
function switch_app_on_off_inside_app($aplikace_id, $owner_id) {
	// kontrola, zda je aplikace uzivatele
	global $CONF;
	dbQuery("SELECT a.aplikace_id, aplikace_typ_id, spusteno FROM aplikace a, page_x_app pa WHERE a.aplikace_id=pa.aplikace_id AND a.aplikace_id=#1 AND page_owner_id=#2", $aplikace_id, $owner_id);
	$row = dbArr();
	if($row["aplikace_id"] == $aplikace_id) {
//		dbQuery("UPDATE aplikace SET spusteno = CASE WHEN spusteno = 0 THEN 1 WHEN spusteno = 1 THEN 0 END WHERE aplikace_id=#1", $aplikace_id);
		dbQuery("UPDATE aplikace SET spusteno = #2 WHERE aplikace_id=#1", $aplikace_id, $row["spusteno"] == 1 ? 0 : 1);
		if(dbAff() == 1) {
			$spusteno = $row["spusteno"] == 1 ? 0 : 1;
			logit("debug", "Preputi stavu switch_app_on_off_inside_app aplikace_id=".$aplikace_id.",stav=".$row["spusteno"] == 1 ? 0 : 1);
		}
		$stav_app = getStavApp($aplikace_id);
		return array(
			"stav" => txt("dashboard-description_stav-".$stav_app["stav"]),
			"termin" => $stav_app["termin"],
//			"spusteno" => $stav_app["spusteno"],
			"spusteno" => $spusteno,
			"zbyva_dni" => $stav_app["zbyva_dni"],
			"licence" => txt("dashboard-description_licence-".$stav_app["licence"]));
	}
	return array();
}

/**
* minidashboard primo u aplikace!
*/
function AppDashBoard($aplikace_id)
{
	global $CONF_BASE_SSP_APP;
	$CONF = setAppConfig($aplikace_id);
//	pre($CONF, "CONF V AppDashBoard");
	logit("debug", "fce AppDashBoard, aplikace_id=".$aplikace_id.", _SESSION[user][aplikace_id]=".$_SESSION["user"][$aplikace_id]);

	// todle tu nemuze byti, pac pokud neznam uzivatele, zobrazil bych aplikaci, pokud neni nastavena!!!
//	if(!isset($_SESSION["user"][$aplikace_id]))
//		return array("reload" => true,"stop" => false, "app_dashboard" => "", "class_owner" => "");

//	list($stav, $termin, $zbyva_dni, $licence, $spusteno) = getStavApp($aplikace_id); // trezor :-)

	// zobnu si stav aplikace nezavisle na uzivateli
	$stav_app = getStavApp($aplikace_id);

//	pre($stav_app, "getStavApp v AppDashBoard aplikace_id=".$aplikace_id);
	$class_owner = false;
	$app_dashboard = false;
	$stop = false;
	//		pre($CONF, $aplikace_id);
	//		pre($_SESSION["user"]);
	ob_start();
	// aplikace patri majiteli!
//	pre($_SESSION["user"], $_SESSION["user"][$aplikace_id]."|".$aplikace_id."|".$CONF["page_owner_id"]);
	if($CONF["page_owner_id"] && $_SESSION["user"][$aplikace_id] == $CONF["page_owner_id"]) {
	  $class_owner = " tab_admin";
	  ?>		
		<div id="dashboard" class="appdashboard">
		<div class="switch-app-on-off<?=$stav_app["spusteno"] ? " on" : ""?>" rel="<?=$aplikace_id?>"></div>
		<!-- snow switcher // JS snezeni - snow flakes - prozatim vypnuto -->
<!--  
		<div class="switch-snow-on-off<?=$CONF["snow"] == 1 ? " on" : ""?>" rel="<?=$aplikace_id?>"></div>
-->
		<a href="<?=$CONF_BASE_SSP_APP?>" target="_blank"><?=txt("tab-admin-aplikace_vstup-administrace_link-na-dashboard")?></a>
		<p id="stav"><?=txt("dashboard-description_stav")?> <span><?=txt("dashboard-description_stav-".$stav_app["stav"])?></span></p>
		</div>
		<?		
	}
	// aplikace neni majitele nebo jeste nema svoji FB page!
	else {
	  // kontrola stavu stop!
	  switch ($stav_app["stav"]) {
			case "stopnuto": 
			case "zaplaceno_stopnuto": 
			case "nezaplaceno": 
				$stop = true;
				break;
	  }
	}
	$app_dashboard = ob_get_clean();		
	return array("stop" => $stop, "app_dashboard" => $app_dashboard, "class_owner" => $class_owner);
}

function short_url($aplikace_id) {
	global $CONF_XTRA;
	$CONF = setAppConfig($aplikace_id);
	return "http://".$CONF_XTRA["SHORT_HOST"]."/".$CONF["app_short_code"];
}

/**
* fce pro inc/addtab.php - ulozeni feedback
*/
function saveFBTabFeedback()
{
	global $CONF_XTRA;
	dbQuery("REPLACE feedback SET owner_id=#1, aplikace_id=#2, spokojenost=#3, text=#4", $_SESSION["user"][APLIKACE_UNIQ_ID], APLIKACE_ID, fetch_uri("spokojenost","g"), fetch_uri("what","g"));
}

/**
* fce pro inc/addtab.php - ulozonei fb tb
* uklada se z inc/addtab.php, resp. php/actions.php (v kazde aplikaci zvlast)
*/
function saveFBTab($CONF, $uid)
{
	$fbid = $_SESSION["user"][APLIKACE_ID];
	// overeni totoznosti majitele aplikace
	logit("debug","saveFBTab: aplikace_id=".APLIKACE_ID." | page_id=".fetch_uri("page_id","pg")." | aplikace_user_id (SSP)=".$uid." | fbid aplikace=".$fbid);

	// naliznu si info o fb strance
	$dataobj = getPageName(fetch_uri("page_id","pg"), "saveFBTab"); 

//	$dataobj->id."<br>".$dataobj->picture->data->url	
	dbQuery("SELECT * FROM owner_x_app WHERE owner_id=#1 AND aplikace_id=#2 AND 2=2", $uid, APLIKACE_ID);
	if(dbRows() == 1 && fetch_uri("page_id","pg")) {
		dbQuery("REPLACE page_x_app SET aplikace_id=#1, page_id=#2, page_name=#3, page_url=#4, page_picture=#5, page_owner_id=#6", APLIKACE_ID, fetch_uri("page_id","pg"), $dataobj->name, $dataobj->link, $dataobj->picture->data->url, $fbid);
		if(dbAff() >= 1) {
			echo "OK";
			dbQuery("UPDATE aplikace SET canvas=#2 WHERE aplikace_id=#1", APLIKACE_ID, "https://www.facebook.com/".fetch_uri("page_id","pg")."?sk=app_".$CONF["app_id"]);
			// a zde musim rescrape!!! stejne ako po platbe!!!:
			ob_start();
			rescrapeFbOg(APLIKACE_ID);
			ob_end_clean();
		}
	}
}	

/**
* rescrape FB og parameters
* I:	aplikace_id
*		widget: id polozky, napr u fotosouteze id fotografie! (primo za aplikace!
*/
function rescrapeFbOg($aplikace_id, $widget = false)
{
	global $CONF_BASE, $CONF_XTRA, $CONF_BASE_DIR, $CONF_BASE_SSP_DIR;
	$log_rescrape = $CONF_BASE_SSP_DIR."logs/rescrape.log";

	// primo za aplikace!
	if($widget)
		$log_rescrape = $CONF_BASE_DIR."logs/rescrape.log";

	// todo rozsirit o select + majitel! owner_x_app!
	dbQuery("SELECT app_id, aplikace_id, aplikace_typ_id, app_short_code FROM aplikace WHERE aplikace_id=#1", $aplikace_id);
	$row = dbArr();
	if(!$row) {
		logit("error", "rescrapeFbOg neprovedeno, neni aplikace_id=".$aplikace_id);
		logit("error", "rescrapeFbOg neprovedeno, neni aplikace_id=".$aplikace_id, $log_rescrape);
		return;
	}

	if(!$CONF_XTRA["reset_app"][$row["aplikace_typ_id"]]["url"]) {
		logit("error", "rescrapeFbOg neprovedeno, neni nastaveno url v root/inc/global_parameters.php CONF_XTRA[reset_app][".$row["aplikace_typ_id"]."][url]");
		logit("error", "rescrapeFbOg neprovedeno, neni nastaveno url v root/inc/global_parameters.php CONF_XTRA[reset_app][".$row["aplikace_typ_id"]."][url]", $log_rescrape);
		return;
	}


	$url = $CONF_XTRA["reset_app"][$row["aplikace_typ_id"]]["url"];
	if($row["aplikace_typ_id"] == "2")
		$url = $CONF_XTRA["reset_app"][$row["aplikace_typ_id"]]["url"].$aplikace_id."/?aplikace_id=".$aplikace_id;
	else
		$url = $CONF_XTRA["reset_app"][$row["aplikace_typ_id"]]["url"].$aplikace_id;
	
	// pokud je widget, resim jen short_url
	if(!$widget) {
		// 1. adresa aplikace
		logit("debug", "rescrapeFbOg: ".$url);
		logit("debug", "rescrapeFbOg: ".$url, $log_rescrape);
		$url = "https://graph.facebook.com/?id=".urlencode($url)."&scrape=true&method=post";
		$ch = curl_init();
		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPGET, 1); 

		// grab URL and pass it to the browser
		ob_start();
		$str = curl_exec($ch);
		// close cURL resource, and free up system resources
		curl_close($ch);
		ob_clean();
	}

	// 2. short adresa aplikace
	$url = "http://".$CONF_XTRA["SHORT_HOST"]."/".$row["app_short_code"];
	if($widget)
		$url .= "/".$widget; 
//	$url = "https://graph.facebook.com/?id=".urlencode("http://".$CONF_XTRA["SHORT_HOST"]."/".$row["app_short_code"])."&scrape=true&method=post";

	logit("debug", "rescrapeFbOg: ".$url);
	logit("debug", "rescrapeFbOg: ".$url, $log_rescrape);
	$url = "https://graph.facebook.com/?id=".urlencode($url)."&scrape=true&method=post";
	$ch = curl_init();
	// set URL and other appropriate options
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPGET, 1); 

	// grab URL and pass it to the browser
	ob_start();
	$str .= " | ".curl_exec($ch);
	// close cURL resource, and free up system resources
	curl_close($ch);
	ob_clean();
	logit("debug", "rescrapeFbOg - result: ".$str);
	logit("debug", "rescrapeFbOg - result: ".$str, $log_rescrape);
	return ;
} 


/**
* fce ulozi nazev FB stranky  
*/
function saveFBPage()
{
	// overeni totoznosti majitele aplikace
	logit("debug","saveFBPage: page_id=".fetch_uri("page_id","pg"));
	dbQuery("UPDATE page_x_app SET page_name=#2, page_url=#3, page_picture=#4 WHERE page_id=#1", fetch_uri("page_id","pg"), fetch_uri("page_name","pg"), fetch_uri("page_url","pg"), fetch_uri("page_picture","pg"));
}		



/**
* pop okno na administraci policek kontaktni adresy
*/
function PopAdress() {
	global $CONF_XTRA;
	list($set_row_adress, $show_row_adress) = GetDataAdress();
	ob_start();

?>
					<img src="../img/ajax_preloader.gif" id="loading-img" style="display:none;" alt="Please Wait"/>
					<p><?=txt("setting-adress_upravte_pole")?></p>
					<form action="<?=$_SERVER["SCRIPT_URI"]?>" method="post" id="f_adress_set">
					<input type="hidden" name="type" value="adress_set" />
					<input type="hidden" name="session_id" value="<?=session_id()?>">
<?					echo $set_row_adress; ?>
					<div id="addadress"><?=txt("setting-adress_add_field")?></div>
					<div class="cl"></div>
					<button type="submit">Uložit</button>
					</form>
					<div id="PopVyhra_sipka"></div>
<?					
	return ob_get_clean();
}


/**
* pop okno zobrazujici formular s policky na zadavani adresy uzivatelem
*/
function ShowPopAdress($readonly = false, $getUserAdress = false) {
	global $CONF_XTRA;
	list($set_row_adress, $show_row_adress) = GetDataAdress($readonly, $getUserAdress);
	ob_start();
	echo $show_row_adress;
/*	
	// dano primo k aplikaci, pac pouzivano i u zisku a tam je na buttonu jiny txt!
	<button class="btn_send_adress btn"><?=txt("setting-adress_button_na_jako_adresu_mame_zaslat_vyhru")?></button>
*/

	return ob_get_clean();
}

/**
* uprava formulare adresy
* select z DB, pokud jeste neni v DB vezmu default z txt("setting-adress")
*/
function GetDataAdressNames($readonly = false, $getUserAdress = false, $aplikace_id = false) {
	global $CONF_XTRA;
	$adress_name = array();
	$adress_required = array();
	$set_row_adress = "";
	$show_row_adress = "";

	$aplikace_id = $aplikace_id ? $aplikace_id : $_SESSION["aplikace_id"];

	dbQuery("SELECT aplikace_typ_id FROM aplikace WHERE aplikace_id=#1", $aplikace_id);
	$row = dbArr();
	$aplikace_typ_id = $row["aplikace_typ_id"];
	
	// pokud je pozadavek na nacteni uzivatelske adresy ($getUserAdress = "getUserAdress") nactu jiz zadanou adresu uzivatelem
	if($getUserAdress == "getUserAdress" && $_SESSION["user"][$aplikace_id]) {
		$k = 0;
		dbQuery("SELECT * FROM uzivatel_adress WHERE aplikace_id=#1 AND fb_id=#2 ORDER BY id", $aplikace_id, $_SESSION["user"][$aplikace_id]);
		while($row = dbArr()) {
			$adress_user[$k++] = $row["hodnota"];
		}
	}

	// 1) nactu pole formulare z databaze
	dbQuery("SELECT * FROM uzivatel_adress_set WHERE aplikace_id=#1 ORDER BY id", $aplikace_id);
	$k = 0;
	while($row = dbArr()) {
		$adress_name[$k] = $row["name"];
		$adress_required[$k] = $row["required"];
		if(is_array($CONF_XTRA["setting-adress-mandatory"][$aplikace_typ_id]) && in_array($k, $CONF_XTRA["setting-adress-mandatory"][$aplikace_typ_id]))
			$adress_mandatory[$k] = "y";
		$k++;
	}
	
//	pre($CONF_XTRA["setting-adress-mandatory"][$aplikace_typ_id], $aplikace_typ_id);
	
	// 2) pokud neni vlozeno do databaze vezmu defaultni adresy z $CONF_XTRA["setting-adress"][$aplikace_typ_id]
	if(!$adress_name && $CONF_XTRA["setting-adress"][$aplikace_typ_id]) {
		$k = 0;
		foreach($CONF_XTRA["setting-adress"][$aplikace_typ_id] as $txt_key) {
			$adress_name[$k] = txt("setting-adress_".$txt_key);
			$adress_required[$k] = "y";
			if(is_array($CONF_XTRA["setting-adress-mandatory"][$aplikace_typ_id]) && in_array($txt_key, $CONF_XTRA["setting-adress-mandatory"][$aplikace_typ_id]))
				$adress_mandatory[$k] = "y";
			$k++;
		}
	}
/*
	pre($adress_mandatory, "HH adress_mandatory");
	pre($adress_required, "HH adress_required");
	pre($adress_name, "HH adress_name");
*/
	return array($adress_name, $adress_required, $adress_mandatory, $adress_user);
}

/**
* uprava formulare adresy
* select z DB, pokud jeste neni v DB vezmu default z txt("setting-adress")
*/
function GetDataAdress($readonly = false, $getUserAdress = false) {

	list($adress_name, $adress_required, $adress_mandatory, $adress_user) = GetDataAdressNames($readonly, $getUserAdress);
//	pre($adress_name);
	// vyrobim pole formlulare:
	//	a) $set_row_adress ... slouzi administraci
	//	b) $set_row_adress ... pole primo v soutezni aplikaci
	foreach($adress_name as $k => $v) {
		$set_row_adress .= "<div class=\"set_adress\">";
		$set_row_adress .= "<input type=\"text\" name=\"adress[]\" placeholder=\"".$v."\" class=\"set_adress text\" value=\"".$v."\" ".($adress_mandatory[$k] ? "readonly='readonly'" : "")." />";
		$set_row_adress .= "<input type=\"checkbox\" name=\"required[]\"".get_checked($adress_required[$k], "y", false)." id=\"req_$k\" ".($adress_mandatory[$k] ? "readonly='readonly' disabled='disabled'" : "")." />";
		$set_row_adress .= "<label for=\"req_$k\">".txt("setting-adress_vyzadovat")."</label>";
		if($adress_mandatory[$k]) 
			$set_row_adress .= "<input type=\"hidden\" name=\"required[]\" value=\"on\" />";
		if(!$adress_mandatory[$k]) 
			$set_row_adress .= "<span class=\"delreq\"></span>";
		
		$set_row_adress .= "</div>";

		// pole primo v soutezni aplikaci
		$show_row_adress .= "<input type=\"text\" rel=\"".($adress_required[$k] == "y" ? "y" : "")."\" name=\"adress[]\" placeholder=\"".$v."\" class=\"show_adress text\" value=\"".$adress_user[$k]."\"".($readonly ? " readonly=\"readonly\"" : "")." />";
//		$show_row_adress .= "<input type=\"text\" name=\"adress[]\" placeholder=\"".$v."\" class=\"show_adress text\" value=\"\" />";

	}

	$set_row_adress .= "<div class=\"set_adress set_adress_new\">";
	$set_row_adress .= "<input type=\"text\" name=\"adress[]\" placeholder=\"\" class=\"set_adress text\" />";
	$set_row_adress .= "<input type=\"checkbox\" name=\"required[]\" id=\"req_new\" checked=\"checked\" />";
	$set_row_adress .= "<label for=\"req_new\">".txt("setting-adress_vyzadovat")."</label>";
	$set_row_adress .= "<span class=\"delreq\"></span>";
	$set_row_adress .= "</div>";

	return array($set_row_adress, $show_row_adress);
}

/**
* fce ulozi nastaveni adres z formulare administrace kontaktu (pole pro zadani adres vyhercu)
*/
function adress_set() {
	$qs = "";
	dbQuery("DELETE FROM uzivatel_adress_set WHERE aplikace_id=#1", $_SESSION["aplikace_id"]);
	foreach($_GET["adress"] as $k => $v) {
		// preskocim prazdne inputy!
		if($v)
			$qs .= "(".$k.",".$_SESSION["aplikace_id"].",'".$v."', '".($_GET["required"][$k] == "on" ? "y" : "n")."', ''),";
	}
	dbQuery("INSERT uzivatel_adress_set VALUES ".substr($qs, 0, -1));
//			pre($_GET);
	return dbAff();
}

/**
* input checkbox - otestuje, zda existuje a checked
*/
function get_checked($check_field, $true_value, $default = false)
{
    return $check_field == $true_value ? " checked=\"checked\"" : ($default ? " checked=\"checked\"" : false);
}

/**
* input radio - otestuje, zda existuje a checked
*/
function get_radio_checked($check_field, $true_value, $default = false)
{
    return $check_field == $true_value ? " checked=\"checked\"" : ($default && empty($check_field) ? " checked=\"checked\"" : false);
}

function replace_prvek($string, $find)
{
	global $CONF_XTRA;
	if(!$CONF_XTRA["style"][$find])
		return $string;
	return str_replace("%".$find."%", $CONF_XTRA["style"][$find], $string);
}

/**
* nacteni obrazku z "polozka_uni"
*/
function setPolozkaUni($aplikace_id = false) {
	$aplikace_id = $aplikace_id ? $aplikace_id : $_SESSION["aplikace_id"];
	dbQuery("SELECT * FROM polozka_uni WHERE aplikace_id=#1", $aplikace_id);
	while($row = dbArr())
		$polozka_uni[$row["table"]][$row["id"]] = $row["hodnota"];
	return $polozka_uni;
}

function setSkinTextPrvky($app = false, $aplikace_id = false) {
	global $CONF_BASE, $CONF_BASE_DIR, $CONF_BASE_SSP, $CONF_BASE_SSP_DIR, $CONF_XTRA;
	$prvek = array();
	$aplikace_id = $aplikace_id ? $aplikace_id : $_SESSION["aplikace_id"];

	$base_dir = $CONF_BASE_SSP_DIR ? $CONF_BASE_SSP_DIR : $CONF_BASE_DIR;

	$dir_tema = 'tema/';

	dbQuery("SELECT * FROM tema_x_skin WHERE aplikace_id=#1", $aplikace_id);
	$row = dbArr();
	$tema_id = $row["tema_id"];
	$skin_id = $row["skin_id"];

	$dir_skin = $dir_tema.$tema_id."/skiny/";
	
//	if(isset($skin_id) && is_file($config_skin = "../".$_SESSION["aplikace_typ_id"]."/".$dir_skin.$skin_id."/default-texts.php")) {
	if(isset($skin_id) && is_file($config_skin = $base_dir.$_SESSION["aplikace_typ_id"]."/".$dir_skin.$skin_id."/default-texts.php")) {
		require($config_skin);
//		pre($CONF_XTRA["class"], "nacteno ze skinu:".$config_skin);
//		pre($CONF_XTRA["texty"], "nacteno ze skinu:".$config_skin);
	}
	
	

	// nactu obrazky z tabulek css a texty z html
	dbQuery("SELECT * FROM css WHERE aplikace_id=#1",$aplikace_id);
	while($row = dbArr()) {
		if($row["skin_id"]) {
			// kontrola jazykove verze prvku skinu!!
//			pre(array(), $base_dir.$_SESSION["aplikace_typ_id"]."/".$dir_skin.$row["skin_id"]."/".get_lang()."/".$row["prvek_id"]);
			if(is_file($base_dir.$_SESSION["aplikace_typ_id"]."/".$dir_skin.$row["skin_id"]."/".get_lang()."/".$row["prvek_id"]))
				$prvek[$row["prvek_id"]] = $row["skin_id"]."/".get_lang();
			else
				$prvek[$row["prvek_id"]] = $row["skin_id"];
		}
	}
	// nactu obrazky z tabulek css a texty z html
	dbQuery("SELECT * FROM html WHERE aplikace_id=#1",$aplikace_id);
	while($row = dbArr()) {
		$prvek[$row["prvek_id"]] = $row["html"];
	}

//	pre($prvek,"prvky");

	// je-li zobrazovana zalozka (aplikace, trezor, zalozka, atd...)
	if($app) {
		$dir_skin = $CONF_BASE_SSP.$_SESSION["aplikace_typ_id"]."/".$dir_skin;
	}
	return array($tema_id, $dir_skin, $prvek);
}


/**
* pop okno na zadavani baneru
*/
// TODO" nacist z databaze
function PopBaner($baner_id = false) {
	global $CONF_XTRA, $CONF_BASE_DIR, $CONF_BASE;
	// TODO: dodeleat nacteni s MySQL
	$url_baner = ""; // nazev baneru s MySQL
	logit("debug","start fce PopBaner, session_id=".session_id());
	if($baner_id != "undefined") {
		dbQuery("SELECT * FROM banery WHERE baner_id=#1 AND aplikace_id=#2", $baner_id, $_SESSION["aplikace_id"]);	
		$row = dbArr();
	//	pre($row, "tady");
		$baner_id = $row["baner_id"];
		$img = $row["img"]; // pocet kusu dane banery s MySQL
		$url_baner = $row["url"]; // nazev banery s MySQL
	}
	else $baner_id = "";


	ob_start();

?>
	<div class="cl"></div>
<? 	include_once($CONF_BASE_DIR."ajax-image-upload/admin_banery.php");
//	ajaxImageIndex();
	return ob_get_clean();
}




/**
* pop okno na zadavani FB OG
*/
// TODO" nacist z databaze
function PopFbOg($dashboard = false, $aplikace_id = false) {
	global $CONF_XTRA, $CONF_BASE_DIR, $CONF_BASE;
	// TODO: dodeleat nacteni z MySQL

	$aplikace_id  = $aplikace_id  ? $aplikace_id  : $_SESSION["aplikace_id"];
	$og_image_dir_link = $CONF_BASE."users_data/".$aplikace_id."/";
	$og_image_dir = $CONF_BASE_DIR."users_data/".$aplikace_id."/";
	$og_image_tag =  "";
	dbQuery("SELECT * FROM aplikace WHERE aplikace_id=#1", $aplikace_id);	
	$row = dbArr();
	$og_title = htmlspecialchars($row["og:title"]);
	$thumb_image_own = "thumb_".$row["og:image"];
	$thumb_image_default = "img/ogimage_thumb.png";
	if($dashboard) {
		$thumb_image_own = $row["og:image"];
		$thumb_image_default = "img/ogimage.png";
	}
	if(is_file($og_image_dir.$thumb_image_own)) {
		$og_image_tag = "<img src=\"".$og_image_dir_link.$thumb_image_own."\" />";
	}
	elseif(is_file($CONF_BASE_DIR.$row["aplikace_typ_id"]."/".$thumb_image_default)) {
		$og_image_tag = "<img src=\"".$CONF_BASE.$row["aplikace_typ_id"]."/".$thumb_image_default."\" />";
	}
	$og_description = htmlspecialchars($row["og:description"]);
//	pre($row, "SELECT * FROM aplikace 123");

	ob_start();

if($dashboard) {
}
?>
<?	if(mujpc()) {
//		$og_image_tag = "<img src=\"".$CONF_BASE.$row["aplikace_typ_id"]."/img/ogimage.png\" />";
//		echo $og_image_tag;
	}

	require_once($CONF_BASE_DIR."ajax-image-upload/admin_fb_og.php");
//	ajaxImageIndex();
	return ob_get_clean();
}


/**
* pridani FB OG parametru
*/
function setFBOG() {
	global $CONF_BASE_DIR, $CONF_BASE;
	######################################
	###	  5a. PRIRAZENI FB.OG		   ###
	######################################

	$aplikace_dir = $CONF_BASE_DIR."users_data/".$_SESSION["aplikace_id"];
	if(isset($_POST["og:title"])) {
		foreach($_POST as $k => $v) {
			$p[$k]  = fetch_uri($k,"p");
		}
		$p["title"] = $p["og:title"];
		$p["description"] = $p["og:description"];


		$part_sql_send_image = false;
	
		if($_FILES["og:image"]["tmp_name"]) {
			echo "COPY?";
			$og_image_path = $aplikace_dir."/".$_FILES["og:image"]["name"];
			if (!copy($_FILES["og:image"]["tmp_name"], $og_image_path)) {
				echo "failed to copy ". $_FILES["og:image"]["name"]."<br />";
				exit;
			}
			nastavit_prava ($og_image_path); 
			$part_sql_send_image = ", `og:image`=#4";
		}

		dbQuery("?UPDATE `aplikace` SET aplikace_typ_id=#1, title=#2, description=#3".$part_sql_send_image.", `og:title`=#5, `og:description`=#6, kod=#7 WHERE aplikace_id=#8",
				  $_SESSION["aplikace_typ_id"],
				  $p["title"],
				  $p["description"],
				  $_FILES["og:image"]["name"],
				  $p["og:title"],
				  $p["og:description"],
				  4,
				  $_SESSION["aplikace_id"]
				  );
		if(dbAff() == 1) {	
			echo "<p>update ok</p>";
		}
	}


	######################################
	###	 / 5a. PRIRAZENI FB.OG		   ###
	######################################


	####################################
	###  5b. zadani FB.OG			 ###
	####################################


	dbQuery("?SELECT * FROM aplikace WHERE aplikace_id=#1",$_SESSION["aplikace_id"]);
	$row = dbArr();
	ob_start();
	?>
	<div id="setapp_fbog">
		<h2>SETTING FB app</h2>
		<form action="setapp.php" method="post" enctype="multipart/form-data">

		<input type="hidden" name="aplikace_typ_id" value="<?=$_SESSION["aplikace_typ_id"]?>">

		<input name="canvas" type="hidden" value="">
		<div class="row">
			<label for="og:title">og:title</label>
			<input type="text" id="og:title" name="og:title" value="<?=$row["og:title"]?>" />
		</div>
		<div class="row">
			<label for="app_id">og:description:</label>
			<input type="text" id="og:description" name="og:description" class="long" value="<?=$row["og:description"]?>" >
		</div>

		<div class="row">
			<label for="og:image">og:image (jpg 200x200px):</label>
			<input type="file" id="og:image" name="og:image">
	<?	if($row["og:image"]) {
	?>
			<a href="<?=$CONF_BASE."users_data/"?><?=$row["aplikace_id"]?>/<?=$row["og:image"]?>" data-lightbox="baner" data-title="<?=$row["og:image"]?>" class="img"><img src="<?=$CONF_BASE."users_data/"?><?=$row["aplikace_id"]?>/<?=$row["og:image"]?>" height="40" /></a>
	<?	}
	?>
		</div>
		<input name="fane_page_id" type="hidden" value="">

		<input name="kod" type="hidden" value="4">

		<div class="row">
			<label></label>
			<input type="submit" value="odeslat">
		</div>

		</form>
	</div>

<?
	####################################
	### / 5b. zadani FB.OG			 ###
	####################################

	return ob_get_clean();
}

/**
* nastaveni spravneho og:image!
*/
function getFbOgImage($user_og_image, $aplikace_id, $aplikace_typ_id) {
	global $CONF_XTRA;
	//$CONF_BASE_SSP_DIR = "/web/x51.cz/apps/socialssprinters/";
	//$CONF_BASE_SSP = "https://x51.cz/apps/socialssprinters/";
	global $CONF_BASE_SSP_DIR, $CONF_BASE_SSP;
	// defaultni ogimage
	$def_og_image = $aplikace_typ_id."/img/ogimage.png";

	// 1. ogimage "libovolenho nazvu" ulozen v databazi
	if($user_og_image && is_file($CONF_BASE_SSP_DIR."users_data/".$aplikace_id."/".$user_og_image)) {
		$og_img = $CONF_BASE_SSP."users_data/".$aplikace_id."/".$user_og_image;
	}
	// 2. ogimage "ogimage.png" pouze na disku 
	elseif(is_file($CONF_BASE_SSP_DIR."users_data/".$aplikace_id."/ogimage.png")) {
		$og_img = $CONF_BASE_SSP."users_data/".$aplikace_id."/ogimage.png";
	}
	// 3. default ogimage "ogimage.png" na disku
	elseif(is_file($CONF_BASE_SSP_DIR.$def_og_image)) {
		$og_img = $CONF_BASE_SSP.$def_og_image."?time=".$CONF_XTRA["TIME_FILES"];
	}
	return  $og_img;
}



/**
* slider slick - administrace vyher
*/
function slider_slick_vyhry($app = false)
{
	global $CONF_BASE_DIR, $CONF_BASE, $CONF_BASE_SSP, $CONF_XTRA;
	$str = "";
	$dir_base = $app == "zalozka" ? $CONF_BASE_SSP : $CONF_BASE;
	$vyhra_img = array();

	dbQuery("SELECT vyhra_id, img, popis, pocet_vyher FROM vyhry WHERE aplikace_id=#1 ORDER BY vyhra_id", $_SESSION["aplikace_id"], "uniqid");
	while($row = dbArrTiny()) {
		$vyhra_img[$row["vyhra_id"]] = $row["img"];
		$vyhra_popis[$row["vyhra_id"]] = $row["popis"];
		$vyhra_id[$row["vyhra_id"]] = $row["vyhra_id"];
	}
	$i = 1;
	foreach ($vyhra_img as $id => $img_file) {
//			if($i==5) break;
			$img = $dir_base."users_data/".$_SESSION["aplikace_id"]."/vyhra/".$img_file;
			$big_img = $dir_base."users_data/".$_SESSION["aplikace_id"]."/vyhra/big_".$img_file;
			if($app == "zalozka") 
//				new lightbox not function			
//				$str .= "\t<div class=\"photo_content\"><a data-lightbox=\"ff".$id."\" data-title=\"".$vyhra_popis[$id]."\" href=\"".$big_img."\"><img src=\"".$img."\" alt=\"\" title=\"".$vyhra_popis[$id]."\" rel=\"".$vyhra_id[$id]."\">".$vyhra_popis[$id]."</a></div>\n";
				$str .= "\t<div class=\"photo_content\"><a rel=\"lightbox\" data-title=\"".$vyhra_popis[$id]."\" href=\"".$big_img."\"><img src=\"".$img."\" alt=\"\" title=\"".$vyhra_popis[$id]."\" rel=\"".$vyhra_id[$id]."\">".$vyhra_popis[$id]."</a></div>\n";
			else
				$str .= "\t<div class=\"photo_content\"><div class=\"del\" rel=\"".$vyhra_id[$id]."\"></div><img src=\"".$img."\" alt=\"\" title=\"".$vyhra_popis[$id]."\" rel=\"".$vyhra_id[$id]."\">".$vyhra_popis[$id]."</div>\n";
			$i++;
	}
	// verze s pridanim noveho slidu (js-add-slide), nepotrebuju, pac musim slider nacist vzdy znovu!
//	$str .= "\t<div class=\"photo_content js-add-slide\"><img src=\"img/nova_cena.png\" alt=\"\"></div>\n";
	// pouze v administraci!
	if(!$app)
		$str .= "\t<div class=\"photo_content\"><img src=\"img/nova_cena".($CONF_XTRA["ThumbSliderSizeWidth"] == 202 ? "_202x202" : "").".png\" alt=\"\" title=\"".txt("setting-create_new_prize")."\"></div>\n";

	return $str;
}

/**
* pop okno na zadavani vyher
*/
function PopVyhra($vyhra_id) {
	global $CONF_XTRA, $CONF_BASE_DIR, $CONF_BASE;
	// TODO: dodeleat nacteni s MySQL
	$pocet_kusu = 1; // pocet kusu dane vyhry s MySQL
	$nazev_vyhry = ""; // nazev vyhry s MySQL

	// promenna na test, zda jiz jsou vyherci v soutezi!
	$vyherci = 0;

	// testuji kolik jiz je vyhercu (kolo, trezor)
	if($_SESSION["aplikace_typ_id"] != 1) {
		// test aplikace:
		// 1) zda neni vyhra necham udelat
		dbQuery("SELECT count(*) AS pocet_vyhercu FROM pokus_log WHERE aplikace_id=#1 AND vyhra=1", $_SESSION["aplikace_id"]);	
		$row = dbArr();
		if($row["pocet_vyhercu"] > 0) {
			$vyherci = $row["pocet_vyhercu"];
		}
	}

	if($vyhra_id != "undefined") {
		dbQuery("SELECT * FROM vyhry WHERE vyhra_id=#1 AND aplikace_id=#2", $vyhra_id, $_SESSION["aplikace_id"]);	
		$row = dbArr();
		$pocet_kusu = $row["pocet_vyher"]; // pocet kusu dane vyhry s MySQL
		$nazev_vyhry = $row["popis"]; // nazev vyhry s MySQL
		$pravdepodobnost = $row["pravdepodobnost"];
		$umisteni = $row["umisteni"];
	}
	else $vyhra_id = "";

	$select_pocet_kusu = ""; // pro zobrazeni 1 - 500 do select komba poctu vyher!
	for($i = 1;$i<=500;$i++)
		$select_pocet_kusu .= "$i;";
	ob_start();

?>
	<div class="close" title="close win"></div>	
<? 	
	if($_SESSION["aplikace_typ_id"] != 1) {
		require_once($CONF_BASE_DIR."ajax-image-upload/admin_vyhry.php");
	}
	else {
		require_once($CONF_BASE_DIR."ajax-image-upload/admin_vyhry_static.php");
	}
?>
<?	
//	ajaxImageIndex();
	return array("vyherci" => $vyherci, "html" => ob_get_clean(), "stav_spusteno" => administrace_vyhry_stop());
}


/**
* vrati rules, pravidla HTML soubor (pravidla.html), bud jiz vyrobeny a ulozeny s users_data nebo default pravidla.html
* I: $app = true: volano z aplikace aplikace
*/
function getFBRules($lg = "cs") {
	global $CONF_XTRA, $CONF_BASE_DIR, $CONF_BASE_SSP_DIR;
	$dir = $CONF_BASE_DIR;
	return file_get_contents($dir."podminky-".$lg.".html");
}	



/**
* vrati rules, pravidla HTML soubor (pravidla.html), bud jiz vyrobeny a ulozeny s users_data nebo default pravidla.html
* I: $app = true: volano z aplikace aplikace
*/
function getRules($app = false) {
	global $CONF_XTRA, $CONF_BASE_DIR, $CONF_BASE_SSP_DIR;
	$dir = $app ? $CONF_BASE_SSP_DIR : $CONF_BASE_DIR;
	$users_dir = $dir."users_data/".$_SESSION["aplikace_id"]."/";
	if(file_exists($users_dir."pravidla.html")) 
		return file_get_contents($users_dir."pravidla.html");
	else 
		return file_get_contents($dir.$_SESSION["aplikace_typ_id"]."/pravidla/pravidla-".get_lang().".html");
}	

/**
* reset pravidel
*/
function resetRules($app = false) {
	global $CONF_XTRA, $CONF_BASE_DIR, $CONF_BASE_SSP_DIR;
	$dir = $app ? $CONF_BASE_SSP_DIR : $CONF_BASE_DIR;
	$users_dir = $dir."users_data/".$_SESSION["aplikace_id"]."/";
	if(file_exists($users_dir."pravidla.html")) 
		unlink($users_dir."pravidla.html");
	return $users_dir."pravidla.html";
}	

/**
* ulozi pravidla na disk do adr users_data
*/
function saveRules($pravidla_html) {
	global $CONF_XTRA, $CONF_BASE_DIR;
	$users_dir = $CONF_BASE_DIR."users_data/".$_SESSION["aplikace_id"]."/";
	$file = fopen($users_dir."pravidla.html","w");
	if(fwrite($file,$pravidla_html)) {
		fclose($file);
		logit("debug","Rules Saved: aplikace_id=".$_SESSION["aplikace_id"]);
		return "1";
	}
	fclose($file);
	logit("debug","Rules Saved error: aplikace_id=".$_SESSION["aplikace_id"]);
	return "-1";
}	

/**
*	ShowRules- pop okno zobrazujici pravidla souteze
*/
function ShowRules() {
	global $CONF_XTRA, $CONF_BASE_DIR;
	ob_start();
?>	
<?	echo getRules();
	return ob_get_clean();
}

/**
*	ShowPopRules- pop okno pro administraci pravidel souteze (editor tynimce)
*/
function ShowPopRules() {
	global $CONF_XTRA, $CONF_BASE_DIR;
	ob_start();
?>
	<script>
	function myCustomOnInit() {
		alert("We are ready to rumble!!");
	}

	
	var editor = tinymce.init({
		selector:'textarea',
		width : 668,
		height : 830,
//		plugins: "autosave",
//		autosave_interval: "5s",
		language: "cs",
		content_css : url_share + "css/tiny.css",
		setup: function(editor) {
			editor.on('init', function(args) {
			// Custom logic
		//		myCustomOnInit;
//				hilight_rules("#tinymce");
//				var cont = $("#PopPravidla iframe").contents().find('body');
//				cont.html(cont.html().replace(/(xx+)/gi,'<span class="hl">$1</span>'));
//				html(str.replace(/(xx+)/gi,"<span style='background:yellow'>$1</span>"));
			}),
			editor.on('change', function(event) {
				console.log('change event', event);
				var txt = editor.getContent();
				txt = txt.replace(/<span class="hl">([^<]+)<\/span>/gi,'$1');
//				alert(txt);
//				$("#PopPravidla textarea").val($("#PopPravidla iframe").contents().find('body').html());
//				$("#PopPravidla textarea").val(txt);
				var data = "type=saveRules&pravidla=" + txt; 
//				alert(data);
//			    console.log(data);
//				alert($("#PopPravidla iframe").contents().find('body').html());
//				setAJAX("php/actions.php", data, "POST", "hilight_rules", "", "");
			});
		}
	});
	</script>

	<form action="php/actions.php" method="post">
	<input type="hidden" name="type" value="saveRules">
	<textarea name="pravidla">
	<?=getRules()?>
	</textarea>
	<button type="submit"><?=txt("setting-button_ulozit")?></button>	
	</form>
<?
	return ob_get_clean();
}


/**
* smaze vyhru vcetne obrazku z disku!
*/
function delete_price ($vyhra_id) {
	global $CONF_BASE_DIR, $CONF_BASE, $CONF;
	dbQuery("DELETE FROM vyhry WHERE aplikace_id=#1 AND vyhra_id=#2 ", $_SESSION["aplikace_id"], $vyhra_id);
	if(dbAff() == 1) {
		setKodyVyhry();
		delete_old_imgs($CONF_BASE_DIR."users_data/".$_SESSION["aplikace_id"]."/vyhra/", "img", "vyhry", "big_");
	    logit("debug","DELETE VYHRA:  aplikace ".$_SESSION["aplikace_id"].", vyhra_id = ".$vyhra_id.", get=".serialize($_GET));
		return;
	}
    logit("debug","DELETE VYHRA - NEBYLA SMAZANA:  aplikace ".$_SESSION["aplikace_id"].", vyhra_id = ".$vyhra_id.", get=".serialize($_GET));
	return;
}
/**
* smaznuti starych imgs vyher!
*/
function delete_old_imgs($DestinationDirectory, $db_img_col, $db_table, $prefix_name=false, $aplikace_id=false)
{
	$aplikace_id = $aplikace_id ? $aplikace_id : $_SESSION["aplikace_id"];
	$imgs = array();
	dbQuery("SELECT `".$db_img_col."` FROM `".$db_table."` WHERE aplikace_id=#1 ", $aplikace_id);
	if(dbAff() != -1) {
		while($row = dbArr())
			$imgs[] = $row[0];
		// rozsirim o img s prefix_name!
		if($prefix_name)
			foreach($imgs as $img)
				$imgs[] = $prefix_name.$img;
//		pre($imgs, "all images with prefix");
		$dir = $DestinationDirectory;
		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
				while (($file = readdir($dh)) !== false) {
					if(!is_dir($dir.$file) && !in_array($file,$imgs))
						unlink($dir.$file);
				}
				closedir($dh);
			}
		}
	}
}

/**
*  pocita (pridava) pristupy na stranku pokud je na facebooku!
*/
function addpristup($aplikace_id) {
	if($_SESSION["access_page".$aplikace_id])
		return false;
//	if(!strpos($_SERVER["HTTP_REFERER"], "facebook.com") || $_SESSION["access_page".$aplikace_id])
//		return false;
	
	dbQuery("LOCK TABLE pristup write");
	dbQuery("UPDATE pristup SET counter = counter + 1 WHERE aplikace_id=#1", $aplikace_id);
	if(dbAff() != 1)
		dbQuery("INSERT pristup SET counter = 1, aplikace_id=#1", $aplikace_id);
	$_SESSION["access_page".$aplikace_id] = true;
	dbQuery("UNLOCK TABLES");
}


/**
* fce vrati email uzivatele
*/
function email_user($aplikace_id = false) {
	if(!$aplikace_id) 
		$aplikace_id = $_SESSION["aplikace_id"];
	dbQuery("SELECT email, email_contact FROM owner, owner_x_app WHERE owner_id = fb_id && aplikace_id=#1", $aplikace_id);
	$row=dbArr();
	return $row["email_contact"] ? trim($row["email_contact"]) : trim($row["email"]);
}	

/**
*  vraci info u demo aplikace pokud aplikace_id je z $CONF_XTRA["nahled_aplikace"] a existuje nejaky description text txt("demo-app_".$_SESSION["aplikace_typ_id"]."_descr")
*/
function DemoAppInfo($aplikace_id) {
	global $CONF_XTRA, $CONF_BASE_SSP_APP;
	if(!in_array($aplikace_id,$CONF_XTRA["nahled_aplikace"]) || !exists_txt("demo-app_".$_SESSION["aplikace_typ_id"]."_descr") || fetch_uri("from","g") != "iframe") 
		return false;
	ob_start();
?>
	<div id="pre_info_demo_app">
		<div id="left" class="col">
			<div class="head_title">
				<?echo txt("demo-app_head-title_vyzkousejte-aplikace"); ?>
			</div>
			<div class="title">
				<span class="what"><?echo txt("demo-app_title_typ-aplikace");?></span> <?echo txt("reset_app_".$_SESSION["aplikace_typ_id"]."_typ"); ?> <span class="new"><?echo txt("demo-app_novinka"); ?></span>
			</div>
			<div class="descr">
				<?echo txt("demo-app_".$_SESSION["aplikace_typ_id"]."_descr"); ?>
			</div>

		</div>
		<div id="right" class="col">
			<p class="price_title"><?echo txt("demo-app_cena-najem")?> </p>
			<p class="p_price"><span class="price"><?echo $CONF_XTRA["price"][$_SESSION["aplikace_typ_id"]]["MONTH"]?> <?=txt("demo-app_cena_mena-Kc")?></span>/<?=txt("demo-app_cena_delka-mesic")?></p>
			<div id="link_ssp"><a href="<?=$CONF_BASE_SSP_APP."?try_app=".$_SESSION["aplikace_typ_id"]?>" target="_blank"><?=txt("demo-app_button_dalsi-info")?></a></div>
		</div>
		<div class="cl"></div>
	</div>
<?
	return ob_get_clean();
}

/**
* prida podpis
*/
function ss_sign()
{
	global $CONF_BASE_SSP_HOME;
	ob_start();
?>
	<div id="ss_sign">
		<a href="<?=$CONF_BASE_SSP_HOME?>" target="_blank"><?echo txt("ss_sign")?></a>
	</div>

<?
	return ob_get_clean();
}

/**
* formular na ulozeni screenshotu aplikace
*/
function screnshot_save_form() {
	global $CONF_BASE_SSP;
	if(!mujpc())
		return;
	ob_start();
?>
		<form method="POST" enctype="multipart/form-data" id="save_screenshot" action="<?=$CONF_BASE_SSP."save_screenshot.php"?>">
		    <input type="hidden" name="aplikace_id" value="<?=APLIKACE_ID?>" />
		    <input type="hidden" name="type" value="save_screenshot" />
		    <input type="hidden" name="session_id" value="<?=session_id()?>" />
		    <input type="hidden" name="img_val" id="img_val" value="" />
		</form>
<?
	return ob_get_clean();
}

/**
* vrati html hlavicku do mailu
*/
function mail_html_body_header() {
	ob_start();
?>  <!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
	<head>
	</head>
	<body bgcolor="#ffffff" text="#000000">
<?
	return ob_get_clean();
}

/**
* vrati html paticku do mailu
*/
function mail_html_body_footer() {
	ob_start();
?>	</body>
	</html>
<?
	return ob_get_clean();
}

/**
*	vraci posledni vyhru uzivatele!
*/
function GetLastVyhra()
{
	// 1) zobnu id vyhry!
	switch($_SESSION["aplikace_typ_id"]) {
		// a) kolo stesti
		case 7:
		dbQuery("SELECT p.kod, vyhra_id FROM pokus_log p, sada_kodu s WHERE p.aplikace_id=#1 AND s.aplikace_id=p.aplikace_id AND fb_id=#2 AND vyhra=1 AND p.kod=s.poradi ORDER BY cas_pridani DESC limit 1",
			$_SESSION["aplikace_id"], $_SESSION["user"][APLIKACE_ID]);
		break;
		// a) trezor
		case 2:
		dbQuery("SELECT p.kod, vyhra_id FROM pokus_log p, kody s WHERE p.aplikace_id=#1 AND s.aplikace_id=p.aplikace_id AND fb_id=#2 AND vyhra=1 AND p.kod=s.kod ORDER BY cas_pridani DESC limit 1",
			$_SESSION["aplikace_id"], $_SESSION["user"][APLIKACE_ID]);
		break;
	}
	$row = dbArr();

	// 2) nactu prvky vyhry (popis, img ...) vratim a zobrazim vyhru!
	dbQuery("SELECT img,popis,vyhra_id FROM vyhry WHERE aplikace_id=#1 AND vyhra_id=#2", $_SESSION["aplikace_id"], $row["vyhra_id"]);
	$row = dbArr();
	return $row["popis"];
	
}

/**
* fce odesle info mail o novem vyherci!
*/
function mail_info_new_vyherce() {
	global $CONF_XTRA;
	list($adress_name) = GetDataAdressNames(false, false, APLIKACE_ID);
	$CONF = setAppConfig(APLIKACE_ID);
//	pre($CONF, $_SESSION["user"][APLIKACE_ID]);
//	pre($_GET["adress"], "get datata");

	// pripravim si data z odeslaneho formulare na ulozeni do databaze a pro info mail
	$remove = array("'",'"',"-");
	foreach($_GET["adress"] as $k => $v) {
		$v = str_replace( $remove, "", $v);
		$kontakt .= $adress_name[$k].": ".$v."<br>";
	}

	$trans = array("#add_name_soutez#" => "\"".$CONF["og:title"]."\"", "#add_fb_page_url#" => "<a href=\"".$CONF["canvas"]."\">".$CONF["canvas"]."</a>");
	$subject = txt("setting-email_info-vyherce-subject");
	$html_body1 = strtr(txt("setting-email_info-vyherce-body1"), $trans);
	$html_body1 .= "<p>".txt("setting-email_info-vyherce-nazev-vyhry-title")." ".GetLastVyhra()."</p>";
	$html_kontakt_vyherce = "<p><strong>".txt("setting-email_info-vyherce-kontakt-title")."</strong></p>";
	$html_kontakt_vyherce .= "<p>".$kontakt."</p>";

	$html_body2 = txt("setting-email_info-vyherce-body2");

	$status_email = mail_function_super(email_user(APLIKACE_ID),$subject,trim(email_user(APLIKACE_ID)), false, false, mail_html_body_header().$html_body1.$html_kontakt_vyherce.$html_body2.mail_html_body_footer());

//	dbQuery("REPLACE uzivatel_adress VALUES ".substr($qs, 0, -1), APLIKACE_ID, $_SESSION["user"][APLIKACE_ID]);
	return array("save_winner_adress" => save_vyherce(), "status_email" => $status_email);

//	echo $html_kontakt_vyherce;
}

/**
* fce odesle ulozi vyherce nebo souteziciho!
*/
function save_vyherce() {
	global $CONF_XTRA;
	$qs = "";

	//	pripravim si data z odeslaneho formulare na ulozeni do databaze a pro info mail
	$remove = array("'",'"',"-");
	foreach($_GET["adress"] as $k => $v) {
		$v = str_replace( $remove, "", $v);
		$qs .= "(".$k.", #1, #2, '$v', now()),";
	}

	dbQuery("REPLACE uzivatel_adress VALUES ".substr($qs, 0, -1), APLIKACE_ID, $_SESSION["user"][APLIKACE_ID]);
	return dbAff();

//	echo $html_kontakt_vyherce;
}




/**
* spocita pocet vyher v soutezi
*/
function check_count_vyhry()
{
	dbQuery("SELECT count(*) FROM vyhry WHERE aplikace_id=#1", $_SESSION["aplikace_id"]);
	$row = dbArr();
	return $row[0];
}

/**
* test ukonceni souteze
*/
function getAppRun($aplikace_id = false) {
//	return "2015-11-15 23:53:00";
	dbQuery("SELECT aplikace_id FROM aplikace WHERE end >= NOW() AND aplikace_id = #1",$aplikace_id ? $aplikace_id : $_SESSION["aplikace_id"]); 
	$row = dbArr();
	return $row["aplikace_id"] ? $row["aplikace_id"] : "end";
}



/**
* vrati spravny datum a cas pro Countdown
*/
function getCountdownTime($default_days_to_end = 10) {
//	return "2015-11-15 23:53:00";
	dbQuery("SELECT end FROM aplikace WHERE aplikace_id=#1",$_SESSION["aplikace_id"]); 
	$row = dbArr();
	return $row["end"] ? $row["end"] : date('Y-m-d 00:00', strtotime("+".$default_days_to_end." days"));;
}

/**
* vykresli Countdown
*/
function Countdown($default_days_to_end = 10) {
	ob_start();
?>
		<div id="getting-started"></div>
		<script type="text/javascript">
			// Split timestamp into [ Y, M, D, h, m, s ]
			var t = "<?=getCountdownTime($default_days_to_end)?>".split(/[- :]/);
			console.log(t);
			// Apply each element to the Date function
			var d = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
//			console.log(d.getTime());
			$(function() {
				$("#getting-started").countdown("<?=getCountdownTime($default_days_to_end)?>", function(event) {
//					console.log(event.strftime('%D.%H.%M.%S'));
					var nyni = new Date();
//					console.log("rozdil:");
//					console.log(d.getTime() - event.timeStamp);
//					console.log(event.timeStamp);
					/* */
/*					
					if(d.getTime() - event.timeStamp < 0) {
						$("#order_btn").remove();
						set_widget_overlay("overlay");
						$("#end, #like").fadeIn();
						$("iframe").remove();
					}
*/					
					$(this).html(event.strftime(''
				 + '<div id="time_to_end">' + time_to_end + '</div>'
				 + '<div class="time days"><span>%D</span> <span class="label"><?echo txt("setting-countdown-dnu")?></span></div>'
				 + '<div class="time hours"><span>%H</span> <span class="label"><?echo txt("setting-countdown-hodin")?></span></div>'
				 + '<div class="time minutes"><span>%M</span> <span class="label"><?echo txt("setting-countdown-minut")?></span></div>'
				 + '<div class="time seconds"><span>%S</span> <span class="label"><?echo txt("setting-countdown-vterin")?></span></div>'
				 + '<div class="cl"></div>'));
				});
			});
		</script>
<?
	return ob_get_clean();
}


/**
* convert date from mysql format for CountDown
*/
function dateformat($date)
{
	return date("d.m.Y H:i", dbDate($date));
}

/**
* fce vrati jmeno a 1. pismeno z prijmeni
*/
function jmenoPrijmeniShort($fb_id = false, $long = false) {
	if(!$fb_id) 
		$fb_id = $_SESSION["user"][APLIKACE_UNIQ_ID];
	dbQuery("SELECT jmeno, prijmeni FROM uzivatel WHERE fb_id=#1", $fb_id);
	$row=dbArr();
	if($long == "all")
		return $row["jmeno"]." ".$row["prijmeni"];
	return $row["jmeno"]." ".mb_substr($row["prijmeni"], 0, 1, "UTF-8").".";
}	

/**
* fce vrati uzivatelska data
*/
function UserData($fb_id = false) {
	if(!$fb_id) 
		$fb_id = $_SESSION["user"][APLIKACE_UNIQ_ID];
	dbQuery("SELECT * FROM uzivatel WHERE fb_id=#1", $fb_id);
	$row=dbArr();
	$row["email"] = $row["email"] != "undefined" ? $row["email"] : "";
	return $row;
}	

/**
* fce vrati owner data - majitele aplikace
*/
function OwnerDataByApp($aplikace_id = false) {
	if(!$aplikace_id) 
		$aplikace_id = $_SESSION["aplikace_id"];
	dbQuery("SELECT o.* FROM owner_x_app, owner o WHERE fb_id=owner_id AND aplikace_id=#1", $aplikace_id);
	$row=dbArr();
	$row["email"] = $row["email"] != "undefined" ? $row["email"] : "";
	return $row;
}	



/**
* fce vytvori jeden unikatni slevovy kod
* I:	$kampan - bazev kampane, $sleva - sleva v procentech,
*		$typ ('1','2', '3') - 1 = unikatni (pouze pro jednu aplikaci),  2 = opakovatelny (mozno pro vice aplikaci x jeden FB ucet!!!), 3 = premium
*		$uplatneno = 1
*		$platnost_od = unixtime od kdy plati
*		$platnost_do = unixtime do kdy plati
*/
function makeSlevCode($kampan, $sleva, $typ, $uplatneno, $platnost_od, $platnost_do, $owner_fb_id = false) {
	static $round;
	$kod = substr(md5(uniqid("")), 0,10);
	if($owner_fb_id) $extra_set = ", owner_fb_id=#8";
	dbQuery("INSERT slev_kody SET kampan=#1, kod=#2, sleva=#3, typ=#4, uplatneno=#5, platnost_od=#!6, platnost_do=#!7".$extra_set, $kampan, $kod, $sleva, $typ, $uplatneno, $platnost_od, $platnost_do, $owner_fb_id);	
	if(dbAff() == 1) 
		return $kod;
	else {
		$round++;
		if($round == 3) {
			logit("debug","slev_kod se nepodarilo vytvorit!");
			return false;
		}	
		return makeSlevCode($kampan, $sleva, $typ, $uplatneno, $platnost_od, $platnost_do);
	}
}

/**
* presortovani multi-dimensional array
*jestlize je countrycode, musim nastavit kodovani dle countrycode!!!
* NEVIM SORT by mel bytio v UTF-8 RUSIM POUZITI, ale mozna jeste nekde je!!!!
*/
function subval_sort($a,$subkey,$sortfce = "asort",$countrycode=false, $locale = true)
{
	if(!$a)
		return;
	// nastavim kodovani
	// vyjimka u stranky v cestine, kde countrycode != 2 letters iso lang
//	$lg = get_lang();
//	if($countrycode == "cz" && )
//		$lg = "cs";
//	$code = Make_setcolalesetlocales($countrycode == "cz" ? "cs" : get_lang());
	foreach($a as $k=>$v) {
//		$b[$k] = iconv("utf-8",$code,$v[$subkey]);
		$b[$k] = $v[$subkey];
//		$bb[$k] = iconv($code,"utf-8",$b[$k]);
	}
//	pre($bb);
	if($sortfce == "asort") {
//		$locale ? asort($b,SORT_LOCALE_STRING) : asort($b);
		asort($b);
//		pre($b);
	}
	elseif($sortfce == "rsort") {
		rsort($b);
	}
	elseif($sortfce == "natsort") {
		natsort($b);
	}
	elseif($sortfce == "rnatsort") {
		natsort($b);
		$b = array_reverse($b, true);
	}
	
	foreach($b as $k=>$v) 
		$c[$k] = $a[$k];
	
	return $c;
}

/**
* nahrazeni stredniku
*/
function nahrad_strednik($str) {
	$replace = array(";" => ":");
	$str = preg_replace("/(?:\n|\r\n?){2,}/", "", $str);
	return strtr($str, $replace);
}

/**
* nastavi extra prava pro x51 adminy!
* kontrola fb emailu a emailu v $CONF_BASE_DIR."x51admin.csv"
*/
function set_privileges($emailfb = false, $fb_id = false) {
	global $CONF_BASE_DIR, $CONF_BASE_DIR_SSP, $CONF_XTRA;
	if(!$emailfb) return;
	$pwds = file(($CONF_BASE_DIR_SSP ? $CONF_BASE_DIR_SSP : $CONF_BASE_DIR)."x51admin.csv");
	foreach($pwds as $pwd) 
		$pwds_admins[] = trim($pwd);
	if(in_array($emailfb, $pwds_admins) || ($fb_id && in_array($fb_id,$CONF_XTRA["x51admin"]))) 
		$_SESSION["x51admin"] = true;
}

/**
* save_time_to_end - nastaveni konce souteze 
* ulozi datum a cas konce souteze
*/
function save_time_to_end($auto = false)
{
	global $CONF_XTRA;
//	dbQuery("SELECT v.vyhra_id FROM vyhry v, sada_kodu k WHERE v.aplikace_id=#1 AND k.aplikace_id=#1 AND v.vyhra_id=k.vyhra_id LIMIT 1", $_SESSION["aplikace_id"]);
	// automaticke nastaveni ukonceni souteze dle $CONF_XTRA["default_days_to_end"] (pocet dni do konce souteze!)
	if($auto) {
		// pokud jiz je zadan cas, vracim zpet!
		dbQuery("SELECT end FROM aplikace WHERE aplikace_id=#1", $_SESSION["aplikace_id"]);
		$row = dbArr();
		if($row["end"])
			return false;
	}
	dbQuery("UPDATE aplikace SET end=#!2 WHERE aplikace_id=#1 LIMIT 1", $_SESSION["aplikace_id"], strtotime(fetch_uri("date","g")) ? strtotime(fetch_uri("date","g")) : strtotime(' + ' .$CONF_XTRA["default_days_to_end"]. ' days'));
	return dbAff();
}

/**
* snehove vlocky - pokud je
*/
function snowJS($snow_switch) {
	global $CONF_BASE_SSP;
	// pokud je nastaveno na 1 snezi!
	if($snow_switch != 1)
		return false;
	ob_start();
?>
<script>
  $(function(){
	$.snow({ flake_number: 50,
		flake_folder: '<?=$CONF_BASE_SSP?>img/small-02/',
		flake_imgs: 5,
		linked_flakes: 0,
		link: '',
		melt: 550,
		wind: 50,
		rotation: 4,
		speed: 8});
  });
</script>
<?
	return ob_get_clean();
}

/**
* switchne u aplikace snezeni 
*/
function setSnow($aplikace_id, $owner_id) {
	// kontrola, zda je aplikace uzivatele
	global $CONF;
	dbQuery("SELECT a.aplikace_id, aplikace_typ_id, snow FROM aplikace a, page_x_app pa WHERE a.aplikace_id=pa.aplikace_id AND a.aplikace_id=#1 AND page_owner_id=#2", $aplikace_id, $owner_id);
	$row = dbArr();
	if($row["aplikace_id"] == $aplikace_id) {
		dbQuery("UPDATE aplikace SET snow = #2 WHERE aplikace_id=#1", $aplikace_id, $row["snow"] == 0 ? 1 : 0);
		if(dbAff() == 1) {
			$spusteno = $row["snow"] == 0 ? 1 : 0;
			logit("debug", "Preputi stavu snow aplikace_id=".$aplikace_id.",stav=".$row["snow"] == 0 ? 1 : 0);
		}
		return array("dbaff" => dbAff(), "stav" => $row["snow"] == 0 ? 1 : 0);
	}
	return array();
}

/**
* nacte vlastni objekty (obrazky)!
*/
function getOwnObjects($y_limit = false)
{
	global $CONF_BASE, $CONF_BASE_SSP_DIR;
	dbQuery("SELECT * FROM own_block WHERE aplikace_id = #1 ORDER BY block_id", $_SESSION["aplikace_id"]);
	while($row = dbArr()) {
		// u img kontroluji, zda img existuje
		if(!$row["img"] || ($row["img"] && file_exists($CONF_BASE_SSP_DIR."users_data/".$row["aplikace_id"]."/upload_data/".$row["img"]))) 
			if(!$y_limit || $row["top"] < $y_limit)
				$own_blocks .= "<div class=\"new_obj\" rel=\"".$row["block_id"]."\" style=\"left: ".$row["left"]."px; top: ".$row["top"]."px;\">".$row["html"]."</div>";
		
	}

	return $own_blocks;
}

/**
* fce vraci nejake informace o aplikaci
*/
function getAppInfo($aplikace_id)
{	
	static $getAppInfo;
	if(isset($getAppInfo[$aplikace_id]))
		return $getAppInfo[$aplikace_id];
//	dbQuery("SELECT * FROM aplikace WHERE aplikace_id=#1", $aplikace_id);
	dbQuery("SELECT app_secret, typ_platby, a.title, `timezone`, UNIX_TIMESTAMP(t.od) tod, UNIX_TIMESTAMP(t.do) tdo, delka_trvani, aplikace_typ_id, app_id, win_repeat FROM aplikace a
	LEFT JOIN termin_log t ON a.aplikace_id=t.aplikace_id
	LEFT JOIN platba p ON a.aplikace_id=p.aplikace_id WHERE a.aplikace_id=#1 ORDER BY t.zalozeno DESC, p.zalozeno DESC LIMIT 1", $aplikace_id);
	return $getAppInfo[$aplikace_id] = dbArrTiny();
}

/**
* zkontroluje zda je gif animovany!
*/
function is_ani($filename) {
    if(!($fh = @fopen($filename, 'rb')))
        return false;
    $count = 0;
    //an animated gif contains multiple "frames", with each frame having a
    //header made up of:
    // * a static 4-byte sequence (\x00\x21\xF9\x04)
    // * 4 variable bytes
    // * a static 2-byte sequence (\x00\x2C) (some variants may use \x00\x21 ?)
   
    // We read through the file til we reach the end of the file, or we've found
    // at least 2 frame headers
    while(!feof($fh) && $count < 2) {
        $chunk = fread($fh, 1024 * 100); //read 100kb at a time
        $count += preg_match_all('#\x00\x21\xF9\x04.{4}\x00(\x2C|\x21)#s', $chunk, $matches);
   }
   
    fclose($fh);
    return $count > 1;
}


function resize_anim_gif($filename, $CurWidth, $CurHeight, $NewWidth, $NewWidth, $DestFolder)
{
		@unlink("/tmp/coalesce.gif");
		$cmd = "convert ".$filename." -coalesce /tmp/coalesce.gif";
		exec($cmd, $retArr, $retVal);
		logit("debug",$cmd.", return=". $retVal .", output=".serialize($retArr));
//		system("convert -size 200x100 coalesce.gif -resize 200x10 small.gif");
		$cmd = "convert -size ".$CurWidth."x".$CurHeight." /tmp/coalesce.gif -resize ".$NewWidth."x".$NewWidth." ".$DestFolder;
		exec($cmd, $retArr, $retVal);
		logit("debug",$cmd.", return=". $retVal .", output=".serialize($retArr));
}

function sklonuj($word, $how_much) {
	switch($word) {
		case "den":
			if($how_much == 1) return $how_much ." ". txt("sklonuj-den_1");
			if($how_much < 5) return $how_much ." ". txt("sklonuj-den_2-4");
			if($how_much >= 5) return $how_much ." ". txt("sklonuj-den_>=5");
			return $how_much + "kódy";
			break;
		case "otazka":
			if($how_much == 1) return $how_much ." ". txt("sklonuj-otazka_1");
			if($how_much < 5) return $how_much ." ". txt("sklonuj-otazka_2-4");
			if($how_much >= 5) return $how_much ." ". txt("sklonuj-otazka_>=5");
			return $how_much + "kódy";
			break;
		case "kód":
			if($how_much == 1) return "$how_much ".txt("kod");
			if($how_much == 0 || $how_much >= 5) return "$how_much ".txt("kodu");
			return "$how_much ".txt("kody");
			break;
		case "cena":
			if($how_much == 1) return "$how_much ".txt("cena");
			if($how_much == 0 || $how_much >= 5) return "$how_much ".txt("cen");
			return "$how_much ".txt("ceny");
			break;
		case "minut":
			if($how_much == 1) return "$how_much ".txt("minuta");
			if($how_much == 0 || $how_much >= 5) return "$how_much ".txt("minut");
			return "$how_much ".txt("minuty");
			break;

		default: return "neni nastaveno ve fci sklonuj";
	}
}

function session_regenator()
{
	session_regenerate_id();
	session_id(substr(session_id(), 0,12).fetch_uri("user","g"));
}


?>
