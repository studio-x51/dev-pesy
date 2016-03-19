<?
#############################################################################
# test pro vsechny aplikace, kde se muze opakovat FBID						#
# to jsou takove, kde neni potreba sdileni aplikace							#
# pokud je sdileni musim kontrolovat souborem inc/access_test.php !!!		#
# musi byt definovana  sada FB aplikaci $CONF_XTRA["fb_app_id_group"]		#
#############################################################################

// testuji krome index.php
if(!strpos($_SERVER["SCRIPT_NAME"], "index.php") && !strpos($_SERVER["SCRIPT_NAME"], "vstup.php") && !strpos($_SERVER["SCRIPT_NAME"], "actions.php")) {
	########################################
	# test existence aplikace x owner - treba pri smaznuti databaze redirect na index.php! (zda patri tato aplikace prihlasenemu uzivateli)
	########################################
	if(fetch_uri("aplikace_id","g") || isset($_SESSION["aplikace_id"]) && $_SESSION["user"][APLIKACE_UNIQ_ID]) {
		dbQuery("SELECT * FROM owner_x_app WHERE owner_id=#1 AND aplikace_id=#2", $_SESSION["user"][APLIKACE_UNIQ_ID], fetch_uri("aplikace_id","g") ? fetch_uri("aplikace_id","g") : $_SESSION["aplikace_id"]);
		if(dbRows() != 1) {
			header("location: ".$CONF_BASE);
			exit;
		}
	}
	########################################
	# / test existance aplikace x owner - treba pri smaznuti databaze redirect na index.php!
	########################################

	########################################
	# nacteni jiz vytvorene aplikace dle aplikace_id
	# - nacte aplikaci a vytvori potrebne SESSION
	########################################
	if(fetch_uri("aplikace_id","g")) {
		dbQuery("SELECT a.aplikace_id, tema_id, skin_id, a.aplikace_typ_id FROM owner_x_app oa, aplikace a LEFT JOIN tema_x_skin ts ON ts.aplikace_id=a.aplikace_id WHERE owner_id = #1 AND oa.aplikace_id = #2 AND a.aplikace_id=oa.aplikace_id", $_SESSION["user"][APLIKACE_UNIQ_ID], fetch_uri("aplikace_id","g"));
		$row = dbArr();
	//	pre($row, "row z owner_x_app oa, aplikace a LEFT JOIN tema_x_skin");
		if($row["aplikace_id"] == fetch_uri("aplikace_id","g"))
		// 1. priradim aplikace_id do session
		$_SESSION["aplikace_id"] = $aplikace_id = $row["aplikace_id"];
		$_SESSION["aplikace_typ_id"] = $row["aplikace_typ_id"];
		if($row["tema_id"] != 0) {
			// 2. priradim tema_id do session
			$_SESSION["tema_id"][$_SESSION["aplikace_id"]] = $row["tema_id"];
			if($row["skin_id"]) 
				$_SESSION["skin_id"][$_SESSION["aplikace_id"]] = $row["skin_id"];
		}
			
	}
	########################################
	# / nacteni jiz vytvorene aplikace 
	########################################
	//pre($_SESSION, "_SESSIONmjj");

	########################################
	###	  1. PRIRAZENI aplikace majiteli	 ###
	########################################

	if(fetch_uri("aplikace_id_set","g") == "new" && !isset($_SESSION["aplikace_id"])) {
		if(fetch_uri("aplikace_typ_id","pg") != $CONF_XTRA["aplikace_typ_id"]) {
			header("location: ".$CONF_BASE."?err=app_no_accept&except_typ_id=".$CONF_XTRA["aplikace_typ_id"]);
			exit;
		}
		unset($_SESSION["tema_id"]);
		unset($_SESSION["skin_id"]);
		// typ aplikace 2 - trezor, ...,  4 - zalozka
		$_SESSION["aplikace_typ_id"] = fetch_uri("aplikace_typ_id","pg");

		$slev_kod = "";
//			if($_SESSION["x51academy"] || $_SESSION["access_grant_password"]) { }
		
		// PREMIUM MEMBERS - rovnou priradim slev_kod!
		if(TestPremiumMember())
			$slev_kod = $_SESSION["premium"]["kod"][$_SESSION["user"][APLIKACE_UNIQ_ID]];



		// 1) zamknu tabulky aplikace, owner_x_app
		dbQuery("LOCK TABLES aplikace WRITE, aplikace a WRITE, owner_x_app oa WRITE, owner_x_app WRITE, owner WRITE, texty WRITE, smartemailing WRITE");

		// pokud vytvori 1. zalozku posilam do smartemailingu
		dbQuery("SELECT owner_id FROM aplikace a, owner_x_app oa WHERE a.aplikace_id=oa.aplikace_id AND owner_id=#1 AND aplikace_typ_id=#2", $_SESSION["user"][APLIKACE_UNIQ_ID],4);
		if(dbRows() == 0) {
			// poslu do smartemailingu pokud neni jiz!
			dbQuery("SELECT fb_id FROM smartemailing WHERE fb_id=#1 AND contactlist=#2", $_SESSION["user"][APLIKACE_UNIQ_ID], $CONF_XTRA["smartmailing"]["new_user_zalozka"]);
			if(dbRows() == 0) {
				$owner = OwnerData($owner_id);
				if(sendRequest(make_add_smartmailing_xml($owner, $CONF_XTRA["smartmailing"]["new_user_zalozka"])) == "SUCCESS") {
					logit("debug", "add_new_user_zalozka_2_smartemailing fb_id:".$owner["fb_id"].", email:".$owner["email"]);
					dbQuery("INSERT smartemailing SET fb_id=#1, contactlist=#2", $owner["fb_id"], $CONF_XTRA["smartmailing"]["new_user_zalozka"]);
				}
			}
		}



		// 2) TUTO PODMINKU JIZ NEPOTREBUJI! - test if uz nema nejakou nastaveno pro restricted_access (pouze jednu app!!!) 
		/*
			dbQuery("SELECT * FROM aplikace a, owner_x_app oa WHERE a.aplikace_id=oa.aplikace_id AND owner_id=#1 AND aplikace_typ_id=#2", $_SESSION["user"][APLIKACE_UNIQ_ID],$CONF_XTRA["aplikace_typ_id"]);
			if(dbArr() > 0) {
				dbQuery("UNLOCK TABLES");
				header("location: ".$CONF_BASE);
				exit;
			}
		*/			
		// 3) zalozim novou aplikaci zalozka, zobnu si jednu aplikaci kterou jeste nema tento majitel!!! Vice majitelu ma stejne FBID!
		dbQuery("SELECT app_id, app_secret FROM aplikace WHERE aplikace_id IN (".implode($CONF_XTRA["fb_app_id_group"],",").") AND app_id NOT IN (SELECT DISTINCT app_id FROM owner_x_app oa, aplikace a WHERE owner_id=#1 AND a.aplikace_id=oa.aplikace_id) ORDER BY aplikace_id LIMIT 1", $_SESSION["user"][APLIKACE_UNIQ_ID]);
//		exit;
		if(dbAff() != 1) {
			dbQuery("UNLOCK TABLES");
			header("location: ".$CONF_BASE."?err=app_no_added");
			exit;
		}
		$row = dbArr();
		dbQuery("INSERT INTO `aplikace` SET aplikace_typ_id=#1, app_id=#2, app_secret=#3, title=#4, description=#5, `og:title`=#6, `og:description`=#7",
			  $CONF_XTRA["aplikace_typ_id"],
			  $row["app_id"],
			  $row["app_secret"],
			  txt("reset_app_".$_SESSION["aplikace_typ_id"]."_title"),
			  txt("reset_app_".$_SESSION["aplikace_typ_id"]."_descr"),
			  txt("reset_app_".$_SESSION["aplikace_typ_id"]."_title"),
			  txt("reset_app_".$_SESSION["aplikace_typ_id"]."_descr")
			  );
		// aplikace se nepodarila zalozit!
		if(dbAff() != 1) {
			dbQuery("UNLOCK TABLES");
			header("location: ".$CONF_BASE."?err=app_no_added");
			exit;
		}

		$aplikace_id = getLastInsertId();

		// pridam shor_url jako uniq_id!
		updateAppUniqUrl($aplikace_id);
		
		if(!$slev_kod && in_array($_SESSION["aplikace_typ_id"], $CONF_XTRA["1_APLIKACE_FREE"])) {
			// kazdy ma narok na jednu aplikaci ze seznamu $CONF_XTRA["1_APLIKACE_FREE"] zdarma! (zatim jen zalozka aplikace_typ_id=4)
			dbQuery("SELECT * FROM aplikace a, owner_x_app oa WHERE a.aplikace_id=oa.aplikace_id AND owner_id=#1 AND aplikace_typ_id=#2 AND slev_kod = #3",
			  $_SESSION["user"][APLIKACE_UNIQ_ID],$_SESSION["aplikace_typ_id"], "FREEAPP");
			if(dbRows() < 1) {
				$slev_kod = "FREEAPP";
			}
		}


		$_SESSION["aplikace_id"] = $aplikace_id;
		// 4) priradim aplikaci majiteli!
		dbQuery("INSERT owner_x_app SET owner_id=#1, aplikace_id=#2, slev_kod=#3", $_SESSION["user"][APLIKACE_UNIQ_ID], $aplikace_id, $slev_kod);
		if(dbAff() != 1) {
			dbQuery("UNLOCK TABLES");
			header("location: ".$CONF_BASE."?err=app_no_added_to_owner");
			exit;
		}
		$_SESSION["owner_x_app"] = $aplikace_id;

		######################################
		###	1a. Vytvoreni users_data adresare   ###
		###  vytvoreni adresaru pro ukladani users dat aplikace (aplikace, banery, vyhry) ###
		#####################################
		mkdirs_users_data();

		// updatne FB OG, title a decription parametry aplikace pri prirazeni aplikace majiteli - kvuli jazykovym verzim
		updateAppFBOgByLang($aplikace_id,$CONF_XTRA["aplikace_typ_id"]);

		// zkopiruji vsechny vlastni obrazky ze vsech aplikaci do nove aplikace
		copyOwnPhotos2AllApps($aplikace_id);

		setTemaSingle();

		dbQuery("UNLOCK TABLES");
		header("location: ".$CONF_BASE.$CONF_XTRA["aplikace_typ_id"]."/setapp");
		exit;
	}

	########################################
	###	/ 1. PRIRAZENI aplikace majiteli  ###
	########################################


	###############################################################################
	### POKUD se nevytvorila $_SESSION["aplikace_id"], vratim zpet !			###
	###############################################################################
	if(!isset($_SESSION["aplikace_id"])) {
		header("location: ".$CONF_BASE);
		exit;
	}
	###############################################################################
	### /POKUD se nevytvorila $_SESSION["aplikace_id"], vratim zpet !			###
	###############################################################################




	###################################################
	### 1b. automaticke prirazeni tema   ###
	###################################################

		// nastavi session $_SESSION["setTemaSingle"]  na true pokud je pouze jedno tema!
		setTemaSingle();

		action_setTemaAuto();


	###################################################
	### 1c. nastaveni trid class pro tema a skiny   ###
	###################################################
	// defaultne a pro 1. pristup!!!
	$class_set_tema = " current";
	$class_set_prvky = "";
	$class_set_nahrano = "";

	// je-li skin
	if(isset($_SESSION["skin_id"][$_SESSION["aplikace_id"]])) {
		$class_set_tema = "";
		$class_set_prvky = " current";
		$class_set_nahrano = "";
	}
	// je-li tema
	elseif(isset($_SESSION["tema_id"][$_SESSION["aplikace_id"]])) {
		$class_set_tema = "";
		$class_set_prvky = " current";
		$class_set_nahrano = "";
	}
}
?>
