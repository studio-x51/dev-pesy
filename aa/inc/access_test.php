<?
// prirazeni nove aplikace s jiz zadanym AP FB ID atd ...
// test pro vsechny aplikace, ktere maji unikatni AP FB ID (napriklad "trezor" aplikace_typ_id = 2)

#####################################################################################################
# test pro vsechny aplikace, ktere maji unikatni AP FB ID (je ze sdileni FB)						#
# "trezor" aplikace_typ_id = 2)																		#
# "placeni sdilenim" aplikace_typ_id = 5)															#
# aplikace bez sdileni kotroluji souborem inc/access_test.2.php	($CONF_XTRA["fb_app_id_group"])									#
# "zalozka" (aplikace_typ_id = 4) je kontrolovana souborem 4/inc/access_test.2.php					#
# "Budovani databaze" (aplikace_typ_id = 6) je kontrolovana souborem 4/inc/access_test.2.php		#
#####################################################################################################


// testuji krome index.php, vstup.php, actions.php
if(!strpos($_SERVER["SCRIPT_NAME"], "index.php") && !strpos($_SERVER["SCRIPT_NAME"], "vstup.php") && !strpos($_SERVER["SCRIPT_NAME"], "actions.php")) {
	########################################
	# test existance aplikace x owner - treba pri smaznuti databaze redirect na index.php!
	########################################
	if(fetch_uri("aplikace_id","g") || isset($_SESSION["aplikace_id"]) && $_SESSION["user"][APLIKACE_UNIQ_ID]) {
		dbQuery("SELECT * FROM aplikace a, owner_x_app o WHERE a.aplikace_id=#2 AND owner_id=#1 AND a.aplikace_id=o.aplikace_id AND aplikace_typ_id=#3", $_SESSION["user"][APLIKACE_UNIQ_ID], fetch_uri("aplikace_id","g") ? fetch_uri("aplikace_id","g") : $_SESSION["aplikace_id"], $CONF_XTRA["aplikace_typ_id"]);
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
		dbQuery("SELECT a.aplikace_id, tema_id, skin_id, a.aplikace_typ_id FROM owner_x_app oa, aplikace a LEFT JOIN tema_x_skin ts ON ts.aplikace_id=a.aplikace_id WHERE owner_id = #1 AND oa.aplikace_id = #2 AND a.aplikace_id=oa.aplikace_id AND aplikace_typ_id=#3", $_SESSION["user"][APLIKACE_UNIQ_ID], fetch_uri("aplikace_id","g"), $CONF_XTRA["aplikace_typ_id"]);
		$row = dbArr();
	//	pre($row, "row z owner_x_app oa, aplikace a LEFT JOIN tema_x_skin");
		if($row["aplikace_id"] == fetch_uri("aplikace_id","g")) {
			// 1. priradim aplikace_id do session
			$_SESSION["aplikace_id"] = $aplikace_id = $row["aplikace_id"];
			$_SESSION["aplikace_typ_id"] = $row["aplikace_typ_id"];
			if($row["tema_id"] != 0) {
				// 2. priradim tema_id do session
				$_SESSION["tema_id"][$_SESSION["aplikace_id"]] = $row["tema_id"];
				if($row["skin_id"]) 
					$_SESSION["skin_id"][$_SESSION["aplikace_id"]] = $row["skin_id"];
			}
			// nastavi session $_SESSION["setTemaSingle"]  na true pokud je pouze jedno tema!
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
		unset($_SESSION["tema_id"]);
		unset($_SESSION["skin_id"]);
		// typ aplikace 2 - trezor, ...
		$_SESSION["aplikace_typ_id"] = $CONF_XTRA["aplikace_typ_id"];
			
		$slev_kod = "";
//			if($_SESSION["x51academy"] || $_SESSION["access_grant_password"]) { }
		
		// PREMIUM MEMBERS - rovnou priradim slev_kod!
		if(TestPremiumMember())
			$slev_kod = $_SESSION["premium"]["kod"][$_SESSION["user"][APLIKACE_UNIQ_ID]];


		// 1) zamknu tabulky aplikace, owner_x_app
		dbQuery("LOCK TABLES aplikace WRITE, aplikace a WRITE, owner_x_app oa WRITE, owner_x_app WRITE, texty WRITE");

		// 2) zobnu si jednu aplikaci bez majitele!
	//	dbQuery("SELECT a.aplikace_id FROM aplikace a LEFT JOIN owner_x_app oa ON a.aplikace_id=oa.aplikace_id WHERE oa.aplikace_id IS NULL AND aplikace_typ_id='2' ORDER BY a.aplikace_id LIMIT 1;", fetch_uri("aplikace_typ_id","pg"));
		dbQuery("SELECT a.aplikace_id FROM aplikace a LEFT JOIN owner_x_app oa ON a.aplikace_id=oa.aplikace_id WHERE a.aplikace_typ_id=#1 AND oa.aplikace_id IS NULL ORDER BY a.aplikace_id LIMIT 1", $_SESSION["aplikace_typ_id"]);
		$row = dbArr();
		$_SESSION["aplikace_id"] = $aplikace_id = $row["aplikace_id"];

		
		// kontrola zda je jeste nejaka aplikace k prirazeni
		if(!$aplikace_id) {
			dbQuery("UNLOCK TABLES");
			header("location: ".$CONF_BASE."?err=app_no_added");
			exit;
		}


		if($aplikace_id) {

			// pridam short_url jako uniq_id!
			if(!$row["app_short_code"])
				updateAppUniqUrl($row["aplikace_id"]);

			logit("debug","setTema?:");
			
			// TODO: u x51 academy - pridat slev_kod = "FREEAPP" v pripade, ze jde o 1. zalozenou aplikaci a navic o "trezor" (aplikace_typ_id = 2)! 
			if(!$slev_kod && in_array($_SESSION["aplikace_typ_id"], $CONF_XTRA["1_APLIKACE_FREE"])) {
				dbQuery("SELECT * FROM aplikace a, owner_x_app oa WHERE a.aplikace_id=oa.aplikace_id AND owner_id=#1 AND aplikace_typ_id=#2 AND slev_kod = #3",
					$_SESSION["user"][APLIKACE_UNIQ_ID],$_SESSION["aplikace_typ_id"], "FREEAPP");
				if(dbRows() < 1) {
					$slev_kod = "FREEAPP";
				}
			}
			// TODO: tuto kontrolu zrusit
			// test if uz nema nejakou nastaveno pro restricted_access (pouze jednu app!!!)
			$aplikace_x_owner = false;
			// OK: zde restricted_access spravne pouzito
			if($_SESSION["restricted_access"] && !mujpc()) {
				dbQuery("SELECT * FROM aplikace a, owner_x_app oa WHERE a.aplikace_id=oa.aplikace_id AND owner_id=#1 AND aplikace_typ_id=#2", $_SESSION["user"][APLIKACE_UNIQ_ID],$_SESSION["aplikace_typ_id"]);
				if(dbArr() > 0) {
					$aplikace_x_owner = true;
					dbQuery("UNLOCK TABLES");
					header("location: ".$CONF_BASE);
					exit;
				}
			}
		// 3) priradim aplikaci majiteli!
	//		if(!$_SESSION["tema"][$_SESSION["aplikace_id"]])
			if(!$aplikace_x_owner) {
				dbQuery("INSERT owner_x_app SET owner_id=#1, aplikace_id=#2, slev_kod=#3", $_SESSION["user"][APLIKACE_UNIQ_ID], $aplikace_id, $slev_kod);
				if(dbAff() == 1) {
					$_SESSION["owner_x_app"] = $aplikace_id;

					######################################
					###	1a. Vytvoreni users_data adresare   ###
					###  vytvoreni adresaru pro ukladani users dat aplikace (aplikace, banery, vyhry) ###
					######################################
					mkdirs_users_data();
					
					// updatne FB OG, title a decription parametry aplikace pri prirazeni aplikace majiteli - kvuli jazykovym verzim
					updateAppFBOgByLang($aplikace_id,$CONF_XTRA["aplikace_typ_id"]);

					// zkopiruji vsechny vlastni obrazky ze vsech aplikaci do nove aplikace
					copyOwnPhotos2AllApps($aplikace_id);
				}
			}
		}
		dbQuery("UNLOCK TABLES");

		// nastavi session $_SESSION["setTemaSingle"]  na true pokud je pouze jedno tema!
		setTemaSingle();

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
