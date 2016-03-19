<?
/*
echo "Omlouvame se, probiha udrzba systemu, zkuste to za par minut.";
exit;
*/
/** dulezite fce
*	administrace_vyhry_stop - vraci "stop" pokud je aplikace spustena (v /inc/global_fce.php)
*	Login2app - nalogovani do aplikace
*	set_privileges - nastavi pripadna viceprava, napr pro x51admin - presunuto do inc/global_fce.php (pouzivam i na mazani fotek ve fotosoutezi!)
*	setKodyVyhry - generuje kody k vyhram!
*	PopVyhra - pop okno na zadavani vyher (presunuto do /inc/global_fce.php)
*	PopBaner - pop okno na zadavani baneru (presunuto do /inc/global_fce.php)
#################### design ######################
*	setSkins - nacteni skinu k tematu!
*	action_setTema - ulozi do tabulky `tema_x_skin` `tema_id` 
*	action_setTemaAuto()	- automaticke ala action_setTema nastaveni tematu, pokud je pouze v tema jedna polozka (jeden adresar)!
*	action_setSkin - ulozi do tabulky `tema_x_skin `skin_id`
*	action_setPrvekSkin
*	action_setTextHtml - fce ulozi text (html) prvku 
*	action_zobrazSkinPic - naplni vyberovou listu prvku skinu (zohledni jazykove verze a podadresari /{lang})
#################### /design ######################
#################### dashboard ################################
*	AllApp -	prehled vsech aplikaci
*	DashBoard	-	tiskne dashboard
*	printDashBoardTypAppAll	- zakladni obecna 
*	printDashBoardTypApp2	- pro aplikaci_typ_id - 2
*	statistika_app_2		- statistika pro aplikaci_typ_id - 2
#################### /dashboard ################################
*	getAppInfo - vrati zakladni, informace o aplikaci !!!  premisteno do inc/global_fce.php !!!
*	updateAppUniqUrl	-	prida unikatni app_short_code pro short url!
*	getStavApp	- fce vrati stav (spusteno, nespusteno) aplikace na zaklade platby a termin od, do
*	makeSlevCode - v inc/global_fce.php - vyrobi slevovy kod!
*   OwnerData - vrati privat data ownera (uzivatele SS z tabulky owner) pro pouziti v platbach atd ...

* capture_screenshots - udela screenshot aplikace!
* rescrapeFbOg - rescrape og paramtery - prhozeno do inc/global_fce.php
### administrace jazyka a fakturace ###
* PopSetLanguage - zmena jazyka
* PopSetFakturace - Nastaveni fakturacnich udaju
*/

// fce getOwnObjects v inc/global_fce.php!

/**
* nacte config parametry s MySQL
*/
function setConfig()
{
	global $CONF_STATIC;
	return $CONF_STATIC;
}

/**
* vybere globalni jazyk SS
*/
function get_lang()
{
//	return "sk";
//	unset($_SESSION["texty"]);
	if(!$_SESSION["user"][APLIKACE_UNIQ_ID])
		return "cs";
	if($_SESSION["SS_lang"]) return $_SESSION["SS_lang"];

	dbQuery("SELECT lang FROM owner WHERE fb_id=#1", $_SESSION["user"][APLIKACE_UNIQ_ID]);
	$row = dbArr();
	return $_SESSION["SS_lang"] = $row["lang"];
}

/**
* nastavi/ulozi globalni jazyk SS
* zatim vracim vzdy "cs"
*/
function set_lang($lang, $fb_id) 
{
//	return "sk";
	dbQuery("UPDATE owner SET lang=#1 WHERE fb_id=#2",$lang, $fb_id);
	if(dbAff() >= 0) {
		unset($_SESSION["texty"]);
		$_SESSION["SS_lang"] = $lang;
	}
	return array("dbaff" => dbAff());
}
/**
* nastavi/ulozi fakturacni udaje
*/
function setFakturace($fb_id) 
{
	logit("debug","fb_id=$fb_id,_SESSION[user][APLIKACE_UNIQ_ID]=".$_SESSION["user"][APLIKACE_UNIQ_ID].",get:".serialize($_GET));

//	if($fb_id == "undefined") // uvidime jeste!
	// kontrola zda jde o x51 admina, ci vlastnika
	if($fb_id != $_SESSION["user"][APLIKACE_UNIQ_ID] && !$_SESSION["x51admin"])
		return;

	$owner = OwnerData($fb_id);
//	if(!$owner["email_contact"])
	dbQuery("UPDATE owner SET email_contact=#2 WHERE fb_id=#1", $fb_id, trim(fetch_uri("email","g")));
	dbQuery("REPLACE odberatel SET fb_id=#1, nazev=#2, ulice=#3, mesto=#4, psc=#5, stat_iso=#6, ic=#7, dic=#8, platce_dph=#9, telefon=#10, email=#11", $fb_id, 
		fetch_uri("nazev","g"), fetch_uri("ulice","g"), fetch_uri("mesto","g"), fetch_uri("psc","g"), fetch_uri("stat_iso","g"), fetch_uri("ic","g"), fetch_uri("dic","g"), fetch_uri("platce_dph","g"), fetch_uri("telefon","g"), trim(fetch_uri("email","g")));
	return array("dbaff" => dbAff());
}



/**
* vybere spravnou menu
*/
function currency_code()
{
	return "Kč";
}

/**
* vybere spravnou menu v ISO
*/
function currency_code_gopay()
{
    return "CZK";
}
/**
* zobrazí spravně měnu dle ISO (gopay platby)
*/
function currency_code_from_ISO($currency)
{
	switch($currency) {
		case "CZK":
			return "Kč";
			break;

		case "EUR":
			return "EUR";
			break;
	}
}


/**
* logovaci fce do aplikace administrace SS, volam v php/actions.php (ostatni aplikace maji Login2appAll)
*/
function Login2app()
{
	logit("debug","LOGIN: owner ".fetch_uri("user","g").", ".serialize($_GET));

	if(fetch_uri("user","g")=="undefined") {
		return json_encode(array("redirect" => "redirect", "try_app" => fetch_uri("try_app","g")));
	}
	// nalogovani odjinud -> redirect na dashboard 
	// po nalogovani, musim redirectnout na HP!
	if(fetch_uri("redir_admin","g") == "dashboard" || !isset($_SESSION["user"][APLIKACE_UNIQ_ID])) {
		$redirect = 2;
	}
	logit("debug", "sess=".$_SESSION["user"][APLIKACE_UNIQ_ID]);
	// kontrola zda jiz neni email prirazen k uzivateli (owner)
	if(isset($_SESSION["x51academy"])) {
		dbQuery("SELECT * FROM owner WHERE email_contact=#2 AND fb_id <> #1", fetch_uri("user","g"), $_SESSION["x51academy"]);
		if(dbRows() > 0) {
//			return json_encode(array("email_contant" => "used")); // vratim a hodim alert, ze smula!
			return json_encode(array("email_contant" => "used", "try_app" => fetch_uri("try_app","g"))); // vratim a hodim alert, ze smula! (uz nepouzivam !)
		}
	}
	$wh = "";

	// nastavim extra prava!
	set_privileges(fetch_uri("email","g"), fetch_uri("user","g"));

	if($_SESSION["x51academy"])
		$wh = ", email_contact = #6";
	dbQuery("SELECT * FROM owner WHERE fb_id=#1",fetch_uri("user","g"));
	if(dbRows() == 1) {
		// update uctu po znovu nalogovani
		dbQuery("UPDATE owner SET jmeno=#2,prijmeni=#3,pohlavi=#4,email=#5$wh WHERE fb_id=#1",
			fetch_uri("user","g"), // 1
			fetch_uri("firstname","g"), // 2
			fetch_uri("lastname","g"), // 3
			fetch_uri("gender","g"), // 4
			fetch_uri("email","g"), //5
//			APLIKACE_UNIQ_ID, //6
			$_SESSION["x51academy"] //6
	//		fetch_uri("","g"),
		);
		if(!strpos(session_id(),fetch_uri("user","g"))) {
			$redirect = true;
			session_regenator();
			logit("debug","LOGIN: session_regenerate_id:".session_id());
		}

		$_SESSION["user"][APLIKACE_UNIQ_ID] = fetch_uri("user","g");
		$_SESSION["user_name"] = fetch_uri("firstname","g")." ".fetch_uri("lastname","g");

		// PO ZRUSENI VSTUPNI STRANKY - nastavim $_SESSION["access_grant"] = true!
		$_SESSION["access_grant"] = true;

		logit("debug", "UPDATE dbAff=".dbAff());

		// nastavim on_demand_academy!
		// vratim zpet a doplnim fb_id a sesion_id
		if(fetch_uri("redir_admin","g") == "on_demand_academy") {
			// testnu premium usera a nactu i data odberatele!
			dbQuery("SELECT * FROM odberatel WHERE fb_id=#1", $_SESSION["user"][APLIKACE_UNIQ_ID]);
			$row = dbArrTiny();
			return json_encode(array("type" => "on_demand_academy", "fb_id" => $_SESSION["user"][APLIKACE_UNIQ_ID], "session_id" => session_id(), "odberatel" => $row, "premium_user" => TestPremiumMember()));
		}

		// nastavim pdf 26 napadu stahni !
		// vratim zpet a doplnim fb_id a sesion_id
		if(fetch_uri("redir_admin","g") == "stahni") {
			return json_encode(array("type" => "stahni", "fb_id" => $_SESSION["user"][APLIKACE_UNIQ_ID], "session_id" => session_id()));
		}



		// nastavim presmerovani premium!
		if(fetch_uri("redir_admin","g") == "premium" || fetch_uri("redir_admin","g") == "on_demand") 
			return json_encode(array("redirect" => "redirect", "url_new" => trim(fetch_uri("redir_admin","g")), "session_id" => session_id()));
		// nastavim presmerovani!
		if($redirect == 2) {
//			echo "redirect";
			return json_encode(array("redirect" => "redirect", "try_app" => fetch_uri("try_app","g"), "user"=>"old", "session_id" => session_id()));
		}
		return json_encode(array("try_app" => fetch_uri("try_app","g"), "user"=>"old", "session_id" => session_id()));
	}
	else {
		// insert noveho 
		dbQuery("INSERT owner SET fb_id=#1,jmeno=#2,prijmeni=#3,pohlavi=#4,email=#5$wh",
			fetch_uri("user","g"), // 1
			fetch_uri("firstname","g"), // 2
			fetch_uri("lastname","g"), // 3
			fetch_uri("gender","g"), // 4
			fetch_uri("email","g"), //5
			$_SESSION["x51academy"] // 6
	/*		fetch_uri("","g"),
			fetch_uri("","g"),
			fetch_uri("","g"),
			fetch_uri("","g"),
			fetch_uri("","g"),
			fetch_uri("","g")
	*/
		);
		if(!strpos(session_id(),fetch_uri("user","g"))) {
			$redirect = true;
			session_regenator();
			logit("debug","LOGIN: session_regenerate_id:".session_id());
		}

		$_SESSION["user"][APLIKACE_UNIQ_ID] = fetch_uri("user","g");
		$_SESSION["user_name"] = fetch_uri("firstname","g")." ".fetch_uri("lastname","g");

		// PO ZRUZENI VSTUPNI STRANKY - nastavim $_SESSION["access_grant"] = true!
		$_SESSION["access_grant"] = true;

		logit("debug", "sess=".$_SESSION["user"][APLIKACE_UNIQ_ID].", INSERT dbAff=".dbAff());
		// nastvim liked po 1. pristupu!
//		dbQuery("REPLACE uz_polozka SET polozka_id=#1, aplikace_id=#2, fb_id=#3, hodnota=#4","like", APLIKACE_UNIQ_ID, $_SESSION["user"][APLIKACE_UNIQ_ID], 1);

		// nastavim on_demand_academy!
		// vratim zpet a doplnim fb_id a sesion_id
		if(fetch_uri("redir_admin","g") == "on_demand_academy") 
			return json_encode(array("type" => "on_demand_academy", "fb_id" => $_SESSION["user"][APLIKACE_UNIQ_ID], "session_id" => session_id()));

		// nastavim presmerovani premium!
		if(fetch_uri("redir_admin","g") == "premium") 
			return json_encode(array("redirect" => "redirect", "url_new" => "premium", "session_id" => session_id()));
		// nastavim presmerovani!
		if($redirect == 2) {
//			echo "redirect";
			return json_encode(array("redirect" => "redirect", "try_app" => fetch_uri("try_app","g"), "user"=>"new", "session_id" => session_id()));
		}
		return json_encode(array("try_app" => fetch_uri("try_app","g"), "user"=>"new", "session_id" => session_id()));
	}
}


/**
*	zpracuje a vrati FB signed_request
*/
function getSignedRequest(){
	if(!isset($_REQUEST['signed_request'])) return "";
	$signed_request = $_REQUEST['signed_request']; // Get the POST signed_request variable.
	if(isset($signed_request)) // Determine if signed_request is blank.
	{
		$pre = explode('.',$signed_request); // Get the part of the signed_request we need.
/*		
		pre($pre, "signed_request $pre");
		foreach($pre as $k => $jj) {
			$json = base64_decode($jj); // Base64 Decode signed_request making it JSON.
			pre($json, "signed_request $json");
			$obj[] = json_decode($json,true); // Split the JSON into arrays.
			pre($obj, "signed_request $k");
		}
*/		
		$json = base64_decode($pre['1']); // Base64 Decode signed_request making it JSON.
		$obj = json_decode($json,true); // Split the JSON into arrays.
  //	  pre($obj, "signed_request");
		$page = $obj['page']; // Get the page array. It has a sub array.
		
		$_SESSION["signed_request"][APLIKACE_UNIQ_ID] = $obj;
  //	  echo("Your App Data: " . $obj['app_data']);
		return($obj);
	}
	else
	{
		return "";
	}
}

/**
* FB fbroot, javascript SDK FB login
*/
function fbroot($CONF,$args) {
	global $FBprevlek;
	
	ob_start();
	?>
	<div id="fb-root"></div>
	<script type="text/javascript">
	window.fbAsyncInit = function() {
		FB.init({
		  appId      : <?=$CONF["app_id"]?>, // App ID
	//	  channelUrl : 'https://x51.cz/apps/fk/channel.html', // Channel File
		  status     : true, // check login status
		  cookie     : true, // enable cookies to allow the server to access the session
		  xfbml      : true,  // parse XFBML
	//	  scope: 'user_likes',
		});
		FB.Canvas.setAutoGrow();

		FB.getLoginStatus(function(response){
			if (response.status === 'connected') 
			{
				console.log("FB Connect");
//				getUserInfo();
<?
/*
		if(mujpc()) {
			dbQuery("SELECT DISTINCT page_id FROM page_x_app WHERE page_url IS NULL");
			while($row = dbArr()) {
?>			//	getPageName(<?=$row["page_id"]?>);
<?			}
		}
*/		
?>

				access_token =   FB.getAuthResponse()['accessToken'];
				console.log("UserID from FB.getAuthResponse:" + FB.getAuthResponse()['userID']);
				console.log(response);
//				getPageName(124065277762183); // stahne nazev stranky a ulozi do db saveFBTab(page_id, page_name)

<?	
// pokud potrebuji doplnit FB name a FB picture k FB strankam
if(mujpc() && 1 == 666) {
	dbQuery("SELECT DISTINCT page_id FROM page_x_app");
	// KatkaFodor
//	dbQuery("SELECT DISTINCT page_id FROM page_x_app WHERE page_id = 259239627467123");
	while($row = dbArrTiny()) {
		$page_ids[] = $row["page_id"];
	}

	foreach($page_ids as $page_id) {
?>		console.log("getPageName - pageid:" + <?=$page_id?>)	;
		getPageName(<?=$page_id?>);
<?
	}
}
?>




				var user = '<?
				if(isset($_SESSION["user"][APLIKACE_UNIQ_ID]))
					echo $_SESSION["user"][APLIKACE_UNIQ_ID];
				?>';
<?			if($args["page"] != "hura" && !$FBprevlek) {
?>
				if(user && FB.getAuthResponse()['userID'] != user) {
					logout_reload();
				}
<?				
			}
?>			
				
//				userFriends(access_token);
//				isFan();

//				$("#main").html(FB.getAuthResponse()['userID']);

//				isFan(access_token);
//				fbSendMessage();  // poustet pouze pokud bude zakometovana podminka z pouze mujpc!
//				fbShareAsLike();
//				fbSendShare();
			}
			else if (response.status === 'not_authorized') 
			{
				// FAILED
				console.log("FB Failed to Connect");
			}	
			else 
			{
				//UNKNOWN ERROR
				console.log("FB Logged Out");
				var data = "type=logout&try_app=" + getUrlVars()["try_app"] + "&session_id=" + getSession();
				$.ajax({
					type:'GET',
					url: url_redir + 'php/actions.php',
					data: data,
					success: function()
					{
						console.log('<?=serialize($args)?>');
//						alert("UNKN ERR - SESSION SMAZNUTA!");
<?php						// presmerovani na logout.php krome vstupu odjinud (adminentry nebo hura.php)!
							if($args["page"] != "adminentry" && $args["page"] != "hura") {
?>
							if(getUrlVars()["action"] != "logoff" && getUrlVars()["action"] != "logon")
								logout_reload(getUrlVars()["try_app"]);
//							else
//								Login('<?=$CONF["scope"]?>', '<?=session_id()?>', "dashboard");
<?php						}	
							
?>					}
				});
			}
		});
	}; // / window.fbAsyncInit

	// Load the SDK asynchronously
	(function(d){
	 var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
	 if (d.getElementById(id)) {return;}
	 js = d.createElement('script'); js.id = id; js.async = true;
//	 js.src = "//connect.facebook.net/en_US/all.js";
	 js.src = "//connect.facebook.net/cs_CZ/all.js";
	 ref.parentNode.insertBefore(js, ref);
	}(document));
	</script>
<?
	return ob_get_clean();
}

/**
* kontrola zda je nalogovan user do SSP
*/
function check_login_SSP()
{
	global $CONF_BASE;
	if(!isset($_SESSION["user"][APLIKACE_UNIQ_ID])) {
		header("location: ".$CONF_BASE);
		exit;
	}


	// KONTROLA, ZDA JE SPRAVNY TYP APLIKACE!!!
	if(isset($_SESSION["aplikace_typ_id"]) && fetch_uri("aplikace_typ_id_control","g") != $_SESSION["aplikace_typ_id"]) {
//		echo $CONF_BASE;
		header("location: ".$CONF_BASE);
		exit;
	}
}

/**
* kontrola zda je aplikace prirazena facebook strance
* TODO: dodelat kontrolu pokud bude potreba!
*/
function check_FB_ADDed($back_step)
{
	global $CONF_BASE;
	if(!isset($_SESSION["user"][APLIKACE_UNIQ_ID])) {
		header("location: ".$CONF_BASE);
		exit;
	}


	// KONTROLA, ZDA JE SPRAVNY TYP APLIKACE!!!
	if(isset($_SESSION["aplikace_typ_id"]) && fetch_uri("aplikace_typ_id_control","g") != $_SESSION["aplikace_typ_id"]) {
//		echo $CONF_BASE;
		header("location: ".$back_step);
		exit;
	}
}



/**
* naloaduje js files 
*/
function jsFiles($jsfiles = false) {
	global $CONF_XTRA;
	if(!$jsfiles)
		$jsfiles = $CONF_XTRA["JS_FILES"];
	ob_start();
	foreach($jsfiles as $file) {
		if($file) {
			?><script type="text/javascript" src="<?=$file?>?time=<?=$CONF_XTRA["TIME_FILES"]?>"></script>
<?		}
	}
	return ob_get_clean();
}

/**
* naloaduje css files 
*/
function cssFiles() {
	global $CONF_XTRA;
	ob_start();
	foreach($CONF_XTRA["CSS_FILES"] as $file => $media) {
		?><link href="<?=$file?>?time=<?=$CONF_XTRA["TIME_FILES"]?>" rel="stylesheet" media="<?=$media?>" type="text/css">
<?	}
	return ob_get_clean();
}


/**
*	vraci FB access token aplikace
*/
function getAppToken($application_id, $application_secret) {

        $token_url = "https://graph.facebook.com/oauth/access_token?" .
            "client_id=" . $application_id .
            "&client_secret=" . $application_secret .
            "&grant_type=client_credentials";
        //$app_token = file_get_contents($token_url);
        $accessToken = explode('=', file_get_contents($token_url));
//		pre($accessToken,"accessToken data");
        return $accessToken[1];
    }

/**
* poslani notifikace - funkcni a pouzite v trezor (crontab)
*/
function doNotification($fb_id, $token, $template, $hrf = '')
{
    $attachment = array(
        'access_token' => $token,
        'href' => $hrf,
        'template' => $template,
    );
//	$fb_id = "me";
//	pre($attachment, $fb_id)   ;
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/'. $fb_id .'/notifications');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $attachment);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, false); //to suppress the curl output
	ob_start();
    $data  = curl_exec($ch);
//	echo $data;
	$str = ob_get_clean();
    curl_close($ch);
    return $str;
}


/**
 * Send Facebook notification using CURL 
 * @param string $recipientFbid Scoped recipient's FB ID
 * @param string $text Text of notification (<150 chars)
 * @param string $url Relative URL to use when user clicks the notification
 * @return String
 */
function sendNotification($recipientFbid, $text, $url, $app_id, $app_secret) {
  $href = urlencode($url);
  $post_data = "access_token=". $app_id . "|" . $app_secret ."&template={$text}&href={$href}";
echo $post_data;
  $curl = curl_init(); 
echo "https://graph.facebook.com/v2.5/". $recipientFbid ."/notifications";
  curl_setopt($curl, CURLOPT_URL, "https://graph.facebook.com/v2.5/". $recipientFbid ."/notifications"); 
  curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data); 
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
  $data = curl_exec($curl); 
  curl_close($curl); 

  return $data;
}



/**
* convert obrazku - nepouzivam nikde zatim
*/
function convert($from,$to,$size,$quality = 75)
{
	global $CONF_XTRA;
	$output = array();
	$return = false;
	$args = "-strip -resize \"$size\" -colorspace RGB -quality $quality";
	$cmd = "convert";
	passthru("$cmd $args \"$from\" \"$to\"", $output);
/*
	pre($_FILES);
	echo "$cmd $args \"$from\" \"$to\"";
	pre($return, "return");
	pre($output, "output");
*/
	if(substr(phpversion(),0,1) > 4 && $return || (!is_file($to) || (time() - filectime($to) > 2)))
//	if(!is_file($to) || (time() - filectime($to) > 2))
	{
		logit("error","convert: argument -strip nefunguje.");

/*		// uz nepotrebuju navic nejak blbnul :(
		// patch pro starej imagemagic, aby neresizoval obrazky pokud jsou mensi nez je pozadavek
		$size_test = getimagesize($from);
		$size_request = explode("x",$size);
		if(substr($size,-1) == ">" && ($size_test[0] <= $size_request[0] || $size_test[1] <= substr($size_request[1],0,-1)))
		{
			echo $from." | ".$to;
			if(!copy($from, $to)) {
				logit(3,"Nepodařilo se zkopirovat $from > $to!");
				return false;
			}
		}
		else {
			$args = " -resize \"$size\" -colorspace RGB -quality $quality";
			exec("convert $args $from $to", $output,$return);
		}
*/		
		$args = " -resize \"$size\" -colorspace RGB -quality $quality";
		exec("convert $args $from $to", $output,$return);
	}
	// v PHP 4 vraci 
	// TODO: v php 4. exec vraci $return = 1, i kdyz probehne v poradku :( v php 5 vyhodit z podminky  && $return !=1
	if(substr(phpversion(),0,1) > 4 && $return || (is_file($to) && (time() - filectime($to) < 2)))
	{
		return true;
	}
	else {
		logit(3,"Nepodařilo se vytvořit thumbs $from > $to! convert returned $return ".serialize($output));
		return false;
	}
}

// pre($_SESSION, "SESSSSSSSSSSSSSION");




/**
* vytvoreni adresaru pro ukladani users dat aplikace (aplikace, banery, vyhry)
*/
function mkdirs_users_data() {
	global $CONF_BASE_DIR, $CONF_BASE, $CONF;
	####################################
	###   6. zpracovani baneru a vyher (kodu) ###
	####################################

	$aplikace_dir = $CONF_BASE_DIR."users_data/".$_SESSION["aplikace_id"];
	if(!is_dir($aplikace_dir) && !mkdir ( $aplikace_dir, 0777)) {
		echo "Nepodarilo se vytvorit adresar $aplikace_dir";
		exit;
	}

	$baner_dir = $CONF_BASE_DIR."users_data/".$_SESSION["aplikace_id"]."/baner/";
	if(!is_dir($baner_dir) && !mkdir ( $baner_dir, 0777)) {
		echo "<h1>Nepodarilo se vytvorit adresar $baner_dir</h1>";
		exit;
	}

	$vyhra_dir = $CONF_BASE_DIR."users_data/".$_SESSION["aplikace_id"]."/vyhra/";
	if(!is_dir($vyhra_dir) && !mkdir ( $vyhra_dir, 0777)) {
		echo "<h1>Nepodarilo se vytvorit adresar $vyhra_dir</h1>";
		exit;
	}
}

######################################
###	  NACTENI TEMAT			  		##
######################################
function setTema() {
	$tema_thumbs = "";
	if(isset($_SESSION["aplikace_id"])) {
		$dir_tema = 'tema/';
		if ($handle = opendir($dir_tema)) {
		//	echo "Directory handle: $handle\n";
		//	echo "Entries:\n";
			
			while (false !== ($entry = readdir($handle))) {
				// preskocim x51 extra privileg temata
				if(strpos($entry, "x51") && !$_SESSION["x51admin"]) continue;
				$img_thumb = $dir_tema.$entry."/thumb.png";
				$class = "";
				if(isset($_SESSION["tema_id"][$_SESSION["aplikace_id"]]) && $_SESSION["tema_id"][$_SESSION["aplikace_id"]] == $entry)
					$class .= "current";
				$class = $class ? " class=\"".$class."\"" : "";
				if(is_file($img_thumb)) 
					$tema_thumbs .="<div$class><img src=\"".$img_thumb."\" id=\"tema_$entry\"></div>\n";
//					$tema_thumbs .="<img src=\"".$img_thumb."\" id=\"tema_$entry\" class=\"$class\">\n";
			}

			closedir($handle);
		}
		return $tema_thumbs;
	}
}	

######################################
###	  /NACTENI TEMAT			   ###
######################################




######################################
###	 NACTENI SKINU K TEMATU		   ###
######################################

/**
* fce srovna adresare podle datumu vzniku
*/
function newest($a, $b) 
{ 
//	return filemtime($a) - filemtime($b); 
	return filemtime($b) - filemtime($a); 
} 

/**
* nacteni vlastni grafiky!
*/
function refreshNahrano() {
	global $CONF_BASE_DIR, $CONF_BASE, $CONF_BASE_SSP;
	ob_start();
	$dir = "users_data/".$_SESSION["aplikace_id"]."/upload_data/";
	$dest_dir = $CONF_BASE_DIR.$dir;
	if(is_dir($dest_dir)) {
		if ($handle = opendir($dest_dir)) {
			/* This is the correct way to loop over the directory. */
			while (false !== ($entry = readdir($handle))) {
				if(substr($entry, 0, 1) == ".") continue;
				$images .= "<div class=\"up_img\"><img src=\"".$CONF_BASE_SSP.$dir.$entry."\"></div>";
			}

			closedir($handle);
		}
	}
	if(!$images)
		echo "<p>".txt("setting-nahrajte_si_vlastni_obrazky-zatim-nemate-nic")."</p>";
	else {
		echo $images."<div class=\"cl\"></div>";
?>
	<script>
	uploaded_img_draggable();
	</script>
<?
	}

	return ob_get_clean();
}



/**
* smaznuti vlastni grafiky z disku! (pouze z disku)
*/
function removeNahrano() {
	global $CONF_BASE_DIR, $CONF_BASE, $CONF_BASE_SSP;
	ob_start();
	$dir = "users_data/".$_SESSION["aplikace_id"]."/upload_data/";
	$dest_dir = $CONF_BASE_DIR.$dir;
	if(is_dir($dest_dir)) {
		if ($handle = opendir($dest_dir)) {
			/* This is the correct way to loop over the directory. */
			while (false !== ($entry = readdir($handle))) {
				if(substr($entry, 0, 1) == ".") continue;
				$images .= "<div class=\"up_img\"><img src=\"".$CONF_BASE_SSP.$dir.$entry."\"></div>";
			}

			closedir($handle);
		}
	}
	if(!$images)
		echo "<p>".txt("setting-nahrajte_si_vlastni_obrazky-zatim-nemate-nic")."</p>";
	else {
		echo $images."<div class=\"cl\"></div>";
?>
	<script>
	uploaded_img_draggable();
	</script>
<?
	}

	return ob_get_clean();
}


/**
* nacteni skinu k tematu!
*/
function setSkins() {
	$skiny_thumbs = "";
	if(isset($_SESSION["tema_id"][$_SESSION["aplikace_id"]])) {
		$dir_tema = 'tema/';
		$dir_skin = $dir_tema.$_SESSION["tema_id"][$_SESSION["aplikace_id"]]."/skiny";
		
		$dir = glob($dir_skin.'/*');

		// srovname adresare podle datumu ulozeni, aby nove skiny, byli na zacatku!
		uasort($dir, "newest");
		
		foreach($dir as $file) 
		{ 
//		    echo basename($file).'<br />'; 
//		    echo $file.'<br />'; 
		}

		if ($dir) {
		//	echo "Directory handle: $handle\n";
		//	echo "Entries:\n";

			/* This is the correct way to loop over the directory. */
//			while (false !== ($entry = readdir($handle))) 
			foreach($dir as $entry)  {
				$entry = basename($entry);
				// preskocim x51 extra privileg temata
				if(strpos($entry, "x51") && !$_SESSION["x51admin"]) continue;
				if(substr($entry, 0,1) == ".") continue;
				$img_thumb = $dir_skin."/".$entry."/"."thumb.png";
				$class = "";
				if(isset($_SESSION["skin_id"][$_SESSION["aplikace_id"]]) && $_SESSION["skin_id"][$_SESSION["aplikace_id"]] == $entry)
					$class .= "current";
				$class = $class ? " class=\"".$class."\"" : "";
				if(is_file($img_thumb))
					$skiny_thumbs .="<div$class><img src=\"".$img_thumb."\" id=\"skin_$entry\"></div>\n";
			}
		}
		else
			return "<p>Nejsou skiny pro toto téma</p>";
		return $skiny_thumbs;
	}
}

######################################
###	 /NACTENI SKINU K TEMATU	   ###
######################################

/**
* fce zalozi tema do MySQL tabulky "tema_x_skin", volam ve php/actions.php
*/
function action_setTema()
{
		logit("debug","setTema aplikace_id:".$_SESSION["aplikace_id"]);
		unset($_SESSION["skin_id"]);
		dbQuery("DELETE FROM css WHERE aplikace_id=#1",$_SESSION["aplikace_id"]);
		$tema_id = substr(fetch_uri("tema","g"), strpos(fetch_uri("tema","g"),"_") + 1);
		// kontrola jestli je aplikace majitele!
		dbQuery("SELECT aplikace_id FROM owner_x_app WHERE owner_id=#1 AND aplikace_id=#2",$_SESSION["user"][APLIKACE_UNIQ_ID],$_SESSION["aplikace_id"]);
		$row = dbArr();
		if($row[0] == $_SESSION["aplikace_id"]) {
			dbQuery("REPLACE tema_x_skin SET aplikace_id=#1, tema_id=#2",$_SESSION["aplikace_id"], $tema_id);
			logit("debug","setTema: owner=".$_SESSION["user"][APLIKACE_UNIQ_ID]." | aplikace_id=".$_SESSION["aplikace_id"]." | tema=".fetch_uri("tema","g"));
			$_SESSION["tema_id"][$_SESSION["aplikace_id"]] = $tema_id;
			return json_encode(array("redirect" => "redirect"));
		}
}

/**
* fce zalozi zutomaticky tema do MySQL tabulky "tema_x_skin", a to pokud je pouze jeden adresar tema!
*/
function action_setTemaAuto()
{
	// test zda tema jiz neni nastaveno
	if($_SESSION["tema_id"][$_SESSION["aplikace_id"]]) 
		return;
	// test zda je pouze 1 tema
	list($countTema, $tema_id) = countTema();
	
	if($countTema != 1 || !$tema_id)
		return;
		
	logit("debug","setTemaAuto aplikace_id:".$_SESSION["aplikace_id"]);
	unset($_SESSION["skin_id"]);
	// kontrola jestli je aplikace majitele!
	dbQuery("SELECT aplikace_id FROM owner_x_app WHERE owner_id=#1 AND aplikace_id=#2",$_SESSION["user"][APLIKACE_UNIQ_ID],$_SESSION["aplikace_id"]);
	$row = dbArr();
	if($row[0] == $_SESSION["aplikace_id"]) {
		dbQuery("REPLACE tema_x_skin SET aplikace_id=#1, tema_id=#2",$_SESSION["aplikace_id"], $tema_id);
		logit("debug","setTemaAuto: owner=".$_SESSION["user"][APLIKACE_UNIQ_ID]." | aplikace_id=".$_SESSION["aplikace_id"]." | tema=".$tema_id);
		$_SESSION["tema_id"][$_SESSION["aplikace_id"]] = $tema_id;
	}
}

/**
* test kolik je temat a nastaveni session - pokud je pouze jeden adresar tema!
*/
function setTemaSingle()
{
	// test zda je pouze 1 tema
	unset($_SESSION["TemaSingle"]);
	list($countTema, $tema_id) = countTema();
	
	if($countTema != 1 || !$tema_id)
		return;
	$_SESSION["TemaSingle"] = true;
}


/**
* vraci pocet adresaru temat dane aplikace
*/
function countTema() {
	global $CONF_BASE_DIR;
	$tema_thumbs = "";
	if(isset($_SESSION["aplikace_id"])) {
		$dir_tema = $CONF_BASE_DIR.$_SESSION["aplikace_typ_id"].'/tema/';
		if ($handle = opendir($dir_tema)) {
		//	echo "Directory handle: $handle\n";
		//	echo "Entries:\n";
			$count = 0;
			while (false !== ($entry = readdir($handle))) {
				$img_thumb = $dir_tema.$entry."/thumb.png";
				if(is_file($img_thumb)) {
					$tema_id = $entry;
					$count++;
				}
			}
			closedir($handle);
		}
		return array($count, $tema_id);
	}
	return false;
}	




/**
* fce zalozi skin do MySQL tabulek "tema_x_skin" a "css", volam ve php/actions.php
*/
function action_setSkin()
{
	global $CONF_BASE_DIR;
		logit("debug","setSkin aplikace_id:".$_SESSION["aplikace_id"]);
		$skin_id = substr(fetch_uri("skin","g"), strpos(fetch_uri("skin","g"),"_") + 1);
		$_SESSION["skin_id"][$_SESSION["aplikace_id"]] = $skin_id;
		// kontrola jestli je aplikace majitele!
		dbQuery("SELECT aplikace_id FROM owner_x_app WHERE owner_id=#1 AND aplikace_id=#2",$_SESSION["user"][APLIKACE_UNIQ_ID],$_SESSION["aplikace_id"]);
		$row = dbArr();
		if($row[0] == $_SESSION["aplikace_id"]) {
			dbQuery("UPDATE tema_x_skin SET skin_id=#2 WHERE aplikace_id=#1",$_SESSION["aplikace_id"], $skin_id);
			logit("debug","setSkin: owner=".$_SESSION["user"][APLIKACE_UNIQ_ID]." | aplikace_id=".$_SESSION["aplikace_id"]." | skin=".fetch_uri("skin","g"));
			
			// ulozeni vsech prvku do tabulky css
			$dir_tema = 'tema/';
			$dir = $dir_tema.$_SESSION["tema_id"][$_SESSION["aplikace_id"]]."/skiny/".$_SESSION["skin_id"][$_SESSION["aplikace_id"]];
			if ($handle = opendir($CONF_BASE_DIR.$_SESSION["aplikace_typ_id"]."/".$dir)) {
				/* This is the correct way to loop over the directory. */
				while (false !== ($entry = readdir($handle))) {
					if(substr($entry, 0,1) == ".") continue;
					$img = $dir."/".$entry;
					$class = "";
					if(is_file($CONF_BASE_DIR.$_SESSION["aplikace_typ_id"]."/".$img)) {
						dbQuery("REPLACE css SET prvek_id=#3, skin_id=#2, aplikace_id=#1",$_SESSION["aplikace_id"], $skin_id, $entry);
					}
				}

				closedir($handle);
			}

			// update design_change kvuli capture screenshot
			update_design_change();

//			dbQuery("DELETE FROM css WHERE aplikace_id=#1",$_SESSION["aplikace_id"));

			return json_encode(array("redirect" => "redirect"));
		}
}

/**
* fce ulozi prvek skinu do MySQL tabulky "css", volam napr v 2/php/actions.php
*/
function action_setPrvekSkin()
{

		$skin_id = fetch_uri("skin","g");
		$prvek_id = fetch_uri("prvek","g");
		// zamezeni ulozeni prazdneho retezce!
		// TODO: kontrola a pripadne nezmeneni ?

		if($skin_id == "undefined" || $prvek_id == "undefined")
			break;
		// kontrola jestli je aplikace majitele!
		dbQuery("SELECT aplikace_id FROM owner_x_app WHERE owner_id=#1 AND aplikace_id=#2",$_SESSION["user"][APLIKACE_UNIQ_ID],$_SESSION["aplikace_id"]);
		$row = dbArr();
		if($row[0] == $_SESSION["aplikace_id"]) {
//			dbQuery("UPDATE tema_x_skin SET skin_id=#2 WHERE aplikace_id=#1",$_SESSION["aplikace_id"], $skin_id);
			logit("debug","setPrvekSkin: owner=".$_SESSION["user"][APLIKACE_UNIQ_ID]." | aplikace_id=".$_SESSION["aplikace_id"]." | skin=".$skin_id." | prvek=".$prvek_id);
			
			dbQuery("REPLACE css SET prvek_id=#3, skin_id=#2, aplikace_id=#1",$_SESSION["aplikace_id"], $skin_id, $prvek_id);
			// u sipek, se vzdy meni obe!!!!
			if($prvek_id == "left.png")
				dbQuery("REPLACE css SET prvek_id=#3, skin_id=#2, aplikace_id=#1",$_SESSION["aplikace_id"], $skin_id, "right.png");
			// u sipek, se vzdy meni obe!!!!
			if($prvek_id == "right.png")
				dbQuery("REPLACE css SET prvek_id=#3, skin_id=#2, aplikace_id=#1",$_SESSION["aplikace_id"], $skin_id, "left.png");

			// update design_change kvuli capture screenshot
			update_design_change();
			
			$dir_tema = 'tema/';
			$dir_skin = $dir_tema.$_SESSION["tema_id"][$_SESSION["aplikace_id"]]."/skiny/";
			return json_encode(array("dir_skin" => $dir_skin));
		}
}

/**
* fce naplni vyberoveho listu pro vyber prvku skinu, volam napr v 2/php/actions.php
*/
function action_zobrazSkinPic()
{
	global $CONF_BASE_DIR;
		$pic = fetch_uri("pic","g");
		$pic_id = substr($pic, 0, strrpos($pic,"."));
		// kontrola jestli je aplikace majitele!
		dbQuery("SELECT aplikace_id FROM owner_x_app WHERE owner_id=#1 AND aplikace_id=#2",$_SESSION["user"][APLIKACE_UNIQ_ID],$_SESSION["aplikace_id"]);
		$row = dbArr();
		if($row[0] == $_SESSION["aplikace_id"]) {
//			logit("debug","setSkin: owner=".$_SESSION["user"][APLIKACE_UNIQ_ID]." | aplikace_id=".$_SESSION["aplikace_id"]." | skin=".fetch_uri("skin","g"));
			

			// nactu si nastavene master skin
			dbQuery("SELECT * FROM tema_x_skin WHERE aplikace_id=#1",$_SESSION["aplikace_id"]);
			$row = dbArr();
			$master_skin_full_name = $row["skin_id"];
			$master_skin = strpos($row["skin_id"], "master_") == 0 ? substr($row["skin_id"], 7) : $row["skin_id"];
			
			// nactu prvky skinu
			dbQuery("SELECT * FROM css WHERE aplikace_id=#1",$_SESSION["aplikace_id"]);
			while($row = dbArr())
				$prvek[$row["prvek_id"]] = $row["skin_id"];
			
			// nacteni vsech prvku ze vsech skinu
			$dir_tema = 'tema/';
			$dir = $dir_tema.$_SESSION["tema_id"][$_SESSION["aplikace_id"]]."/skiny";
			$str = "";
			if ($handle = opendir($CONF_BASE_DIR.$_SESSION["aplikace_typ_id"]."/".$dir)) {
				/* This is the correct way to loop over the directory. */
				$i = 0;
				$current_index = 0;
				while (false !== ($entry = readdir($handle))) {
					// preskocim x51 extra privileg skiny 
					if(strpos($entry, "x51") && $entry != $master_skin_full_name && !$_SESSION["x51admin"]) continue;
					// pokud je v master pripona "-uni" preskocim vsechny "uni-" child skiny!
					if(substr($master_skin_full_name,-4) == "-uni" && $entry != $master_skin_full_name && substr($entry,0,4) == "uni-") continue;
					// preskocim vsechny ostatni master skiny!
					if(strpos($entry, "master_") && $entry != $master_skin_full_name) continue;
					// pouze child skiny k master skinu!!!
					if(strpos($entry, $master_skin) === false && substr($entry,0,4) != "uni-") continue;
					// preskocim adresare "." a ".."
					if(substr($entry, 0,1) == ".") continue;
					// kontrola jazykove verze prvku skinu (obrazku) / pokud je pic adresari "lang/" zobrazim ten!
					if(is_file($CONF_BASE_DIR.$_SESSION["aplikace_typ_id"]."/".$dir."/".$entry."/".get_lang()."/".$pic))
						$img_path = $dir."/".$entry."/".get_lang()."/".$pic;
					else	
						$img_path = $dir."/".$entry."/".$pic;
					$class = "";
					if($prvek[$pic] == $entry) {
						$class = " current";
						$current_index = $i;
					}
					if(is_file($CONF_BASE_DIR.$_SESSION["aplikace_typ_id"]."/".$img_path)) {
//						$str .= '<li class="als-item"><img src="'.$img_path.'" title="'.$pic.'" /></li>'."\n";
						$str .= '<div class="item'.$class.'" rel="'.$entry.'"><img src="'.$img_path.'" rel="'.$pic_id.'" rel2="'.$pic.'" /></div>'."\n";
						$i++;
					}
				}

				closedir($handle);
			}

			return json_encode(array("list" => $str, "current_index" => $current_index, "master_skin" => $master_skin));
		}
}

/**
* fce ulozi text (html) prvku, volam napr v 4/php/actions.php
* I: $content - upraveny content nebo primo GET[content]
*/
function action_setTextHtml($content_id = false, $content = false)
{
	global $CONF_BASE_DIR;

	$content = $content != false ? html_entity_decode($content,ENT_NOQUOTES,"UTF-8") : html_entity_decode(fetch_uri("content","gp"),ENT_NOQUOTES,"UTF-8");
	$content_id = $content_id ? $content_id : fetch_uri("content_id","gp");

	if($content_id == "pravidla") {
		return json_encode(array("saveRules" => saveRules($content)));
	}
	else {
		// pokud je ukladany text svazany s nejakou dalsi MySQL tabulkou (napriklad u kvizu tabulka "vysledky") musim radek v teto tabulce zalozit pokud neexistuje!
		if(fetch_uri("table_data","pg")) {
			$table_arr = explode("_",fetch_uri("table_data","pg"));
			// musim locknout tabulku!!!
			dbQuery("LOCK tables ".$table_arr[0]." WRITE");
			dbQuery("SELECT * FROM ".$table_arr[0]." WHERE aplikace_id=#1 AND id=#2", $_SESSION["aplikace_id"], $table_arr[1]);
			if(dbRows() == 0)
				dbQuery("INSERT ".$table_arr[0]." SET aplikace_id=#1, id=#2", $_SESSION["aplikace_id"], $table_arr[1]);
			dbQuery("UNLOCK tables");

		}
		dbQuery("REPLACE html SET prvek_id=#1, aplikace_id=#2, html=#3", $content_id, $_SESSION["aplikace_id"], $content);
		$dbaff = dbAff();
		// update design_change kvuli capture screenshot
		update_design_change();
	}		

//	pre($_GET,"GETSSS");
	return json_encode(array("dbAff" => $dbaff));
}			
/**
* fce nacte prvky vice obrazku - reload
*/
function action_getNewPics()
{
	global $CONF_BASE_DIR, $CONF_BASE_SSP;
	$prvek = getNewPics();
	foreach($prvek as $img) {
		$pics .= "<div><img alt=\"Thumbnail\" src=\"".$CONF_BASE_SSP."users_data/".$_SESSION["aplikace_id"]."/".$img."\"></div>";
	}
	return json_encode(array("pics" => $pics));
}		

function getNewPics() {
	$prvek = array();
	dbQuery("SELECT * FROM html WHERE prvek_id IN(#1,#2,#3,#4) AND aplikace_id=#5","picid_1","picid_2","picid_3","picid_4", $_SESSION["aplikace_id"]);
	while($row=dbArrTiny()) {
		$prvek[$row["prvek_id"]] = $row["html"];
	}
	return $prvek;
}

/**
* fce nacte html (text, ci obrazek) 1 prvku - reload
*/
function action_getNewPic($prvek_id)
{
	global $CONF_BASE_DIR, $CONF_BASE_SSP;
	dbQuery("SELECT * FROM html WHERE prvek_id=#1 AND aplikace_id=#2", $prvek_id, $_SESSION["aplikace_id"]);
	$row = dbArr();
	$src = $CONF_BASE_SSP."users_data/".$_SESSION["aplikace_id"]."/".$row["html"];
	return json_encode(array("pic_src" => $src));
}		

/**
*	Prehled vsech dostupnych aplikaci 
*/
function AllApp($pocet_aplikaci)
{
	global $CONF_XTRA;
	dbQuery("SELECT app_number FROM vote_new_app WHERE fb_id = #1",$_SESSION["user"][APLIKACE_UNIQ_ID] );
	$row = dbArr();
	dbQuery("SELECT sum(vote) FROM vote_new_app WHERE app_number=#1",$app_number = $row[0]);
	$row = dbArr();
?>
	<script>
	$(function() {
<?	if($row[0]) {
?>	
		after_vote(<?=$app_number?>, <?=$row[0]?>);
<?
	}
?>		
	});		
	</script>
<?
	ob_start();
?>	
	<div id="all_app" class="cont_center">
		<h2><?
			echo $pocet_aplikaci > 0 ? txt("all-app_title") : txt("all-app_title-zadne_aplikace");
		?></h2>	
<?
		$i = 1;
		foreach($CONF_XTRA["all-app_config"] as $number => $data) {
			// 1. ma class left!
?>		
			<div id="app_<?=$number?>" class="app<?echo $i%3 == 1 ? " left": ""?><?=$CONF_XTRA["nahled_app_url_".$data["aplikace_typ_id"]] ? " app_nahled" : ""?>" rel="<?=$data["aplikace_typ_id"]?>">
				<div class="name">
					<p><?=txt("all-app_config_".$number."_name")?></p>
				</div>
<?			if($i > 6 ) { // hlasovani, jakja aplikace ma byt nejdriv!
?>				<div class="vote"></div>
<?			}
?>				<div class="appinfo"><?=txt("all-app_config_".$number."_info")?></div>
			</div>
<?			if($i == 3) { // schovat / zobrazit nabidku aplikaci
?>				<div id="other_app">
					<div class="show<?=$pocet_aplikaci > 0 ? " shown" : ""?>">
						<?=txt("all-app_show-other-app")?>		
					</div>
					<div class="hide<?=$pocet_aplikaci == 0 ? " shown" : ""?>">
						<?=txt("all-app_hide-other-app")?>	
					</div>
				</div>
<?			}
			if($i%3 == 0) { // zalomeni radku po kazde 3.
?>				<div class="cl"></div>
<?			}
			$i++;
		}
		if(($i-1)%3 != 0) { // konecne zalomeni / pokud neni nasobek 3!
?>			<div class="cl"></div>
<?		}
?>
	</div>
<?	
	return ob_get_clean();

}


/**
* dashboard - prehled aplikaci uzivatele
*/
function DashBoard() 
{
	ob_start();
//	var_dump(TestPremiumMember());
//	pre($_SESSION["premium"]);
?>	<div id="dashboard" class="cont_center">
		<h2><?=txt("dashboard_title")?></h2>
<?
//	pre($_SESSION["texty"]);
	
	// hashnu si nazev FB stranky pictogram (profil obrazek!)
	$rs = dbQuery("SELECT pa.aplikace_id, page_name, page_picture FROM page_x_app pa, owner_x_app oa WHERE owner_id = #1 AND oa.aplikace_id=pa.aplikace_id", $_SESSION["user"][APLIKACE_UNIQ_ID]);
	while($row = dbArrTiny()) {
		$page_x_app[$row["aplikace_id"]] = $row;
	}
//	pre($page_x_app,"page_x_app data");
	$i = 1;
	$rs = dbQuery("SELECT a.*, tema_id, skin_id FROM owner_x_app oa, aplikace a LEFT JOIN tema_x_skin ts ON a.aplikace_id=ts.aplikace_id WHERE owner_id = #1 AND a.aplikace_id=oa.aplikace_id ORDER BY zalozeno DESC, a.aplikace_id", $_SESSION["user"][APLIKACE_UNIQ_ID]);
	// hack na zobrazeni aplikace dle QS aplikace_id, pouze pro mujpc()
	if(mujpc() && fetch_uri("aplikace_id","g"))
		$rs = dbQuery("SELECT a.*, tema_id, skin_id FROM owner_x_app oa, aplikace a LEFT JOIN tema_x_skin ts ON a.aplikace_id=ts.aplikace_id WHERE a.aplikace_id = #1 AND a.aplikace_id=oa.aplikace_id ORDER BY zalozeno DESC, a.aplikace_id", fetch_uri("aplikace_id","g"));
	while($row = dbArr2($rs)) {
		$list_apps .= "<li><a href=\"".$row["aplikace_typ_id"]."/setapp?aplikace_id=".$row["aplikace_id"]."\">".$row["og:title"]." | ".$row["og:description"]."</a></li>";
		$apps[$row["aplikace_typ_id"]][] = $row;

	//	pridam nazev FB stranky pictogram (profil obrazek!)
		$row["page_name"] = $page_x_app[$row["aplikace_id"]]["page_name"];
		$row["page_picture"] = $page_x_app[$row["aplikace_id"]]["page_picture"];

//		pre($row);
		$printDashoardFce = "printDashBoardTypApp".$row["aplikace_typ_id"];

		if (!function_exists($printDashoardFce)) {
			logit("error",$printDashoardFce." neni definovana!!");
			if(mujpc()) {
				echo "<p>fce ".$printDashoardFce." neni definovana!!</p>";
			}
		}
		else {
?>
			<div class="aplikace<?echo $i%2 == 0 ? " right" : " left"?>" id="app_id_<?=$row["aplikace_id"]?>">
				<div class="delete" rel="<?=$row["aplikace_id"]?>"></div>
<?				echo call_user_func($printDashoardFce, $row);
?>			</div>
<?		}
		$i++;
	//	pre($row, "vsechny aplikace uzivatele ".$_SESSION["user"][APLIKACE_UNIQ_ID]);
	}
?>		<div class="cl"></div>
		<div id="gateWayPaypal"><?echo gateWayPaypalEmpty()?></div>
	</div><!--id=dashboard-->
<?
	return ob_get_clean();
//	pre($apps, "aplikace:");
}

/**
* print dashboard app typ 2 -> trezor
*/
function printDashBoardTypApp2($data, $aplikace_typ_id = 2) 
{
		global $CONF_BASE;
		ob_start();
	
		printDashBoardTypAppAll($data);
		// statistiky
		$count = statistika_app_2($data["aplikace_id"]);
?>
		<div class="rounded_stat count_users">
			<div class="count"><?=$count["pocet_uzivatelu"]?></div>
			<div class="txt"><?=txt("dashboard-statistika_pocet-uzivatelu")?></div>
		</div>
		<div class="rounded_stat count_win_prices">
			<div class="count"><?=$count["pocet_vyhranych_cen"]?><span>/<?=$count["pocet_cen"]?></span> </div>
			<div class="txt"><?=txt("dashboard-statistika_pocet-vyhranych-cen")?></div>
		</div>
		<div class="rounded_stat count_count_tips">
			<div class="count"><?=$count["pocet_tipu"]?></div>
			<div class="txt"><?
			switch($aplikace_typ_id) {
				case 2:
					echo txt("dashboard-statistika_pocet-tipu-na-trezor-klavesnici");
					break;
				case 7:
					echo txt("dashboard-statistika_pocet-pokusu-otoceni-kolem-stesti");
					break;
			}
			?>
			
			</div>
		</div>
		<div class="rounded_stat">
			<a href="<?=$CONF_BASE?>make_export_data?type=winners&amp;aplikace_id=<?=$data["aplikace_id"]?>&amp;aplikace_typ_id=<?=$aplikace_typ_id?>" class="link_export_vyherci"><?=txt("dashboard-link_exportovat-vyherce")?></a>
		</div>
		<div class="cl"></div>
		<a href="<?=$CONF_BASE?>make_export_data?type=users&amp;aplikace_id=<?=$data["aplikace_id"]?>" class="button link_export_users"><?=txt("dashboard-link_exportovat-data")?></a>
		<div class="cl"></div>
		<a href="<?=$data["aplikace_typ_id"]?>/setapp?aplikace_id=<?=$data["aplikace_id"]?>" class="button link_editor"><?=txt("dashboard-link_to-editor")?></a>

<?
		
	return ob_get_clean();
}

/**
* print dashboard app typ 3 -> kviz
*/
function printDashBoardTypApp3($data, $aplikace_typ_id = 3) 
{
		global $CONF_BASE;
		ob_start();
	
		printDashBoardTypAppAll($data);
		// statistiky
		$count = statistika_app_3($data["aplikace_id"]);
?>
		<div class="rounded_stat count_users">
			<div class="count"><?=$count["pocet_uzivatelu"]?></div>
			<div class="txt"><?=txt("dashboard-statistika_pocet-uzivatelu")?></div>
		</div>
		<div class="rounded_stat count_win_prices">
			<div class="count"><?=$count["pocet_dokoncenych_kvizu"]?><span></div>
			<div class="txt"><?=txt("dashboard-statistika_APP-3_pocet-dokoncecnych-kvizu")?></div>
		</div>
		<div class="rounded_stat count_count_tips">
			<div class="count"><?=isset($count["procento_uspesnosti_testu"]) && $count["procento_uspesnosti_testu"] != false ? $count["procento_uspesnosti_testu"] : "?"?>%</div>
			<div class="txt"><?=txt("dashboard-statistika_APP-3_prumer-spravnych-odpovedi")?></div>
		</div>
		<div class="cl"></div>
		<a href="<?=$CONF_BASE?>make_export_data?type=users&amp;aplikace_id=<?=$data["aplikace_id"]?>" class="button link_export_users"><?=txt("dashboard-link_exportovat-data")?></a>
		<div class="cl"></div>
		<a href="<?=$data["aplikace_typ_id"]?>/setapp?aplikace_id=<?=$data["aplikace_id"]?>" class="button link_editor"><?=txt("dashboard-link_to-editor")?></a>

<?
		
	return ob_get_clean();
}



function printDashBoardTypAppAll($data)
{
	global $CONF_XTRA;
//	list($stav, $termin, $zbyva_dni, $licence) = getStavApp($data["aplikace_id"]);
	$stav_app = getStavApp($data["aplikace_id"]);
//	pre($stav_app, "getStavApp z printDashBoardTypAppAll");
?>	
		<div class="img">
			<img class="thumbapp" src="<?=thumbApp($data)?>">
		</div>
<?
		if(!$data["page_name"])
			$fb_state = "class=\"fb_noadded\"";
		else {
			$fb_state = "style=\"background-image: url(".$data["page_picture"].")\"";
		}
?>
		<div class="desc">
			<div class="switch-app-on-off tooltip<?=$data["spusteno"] ? " on" : ""?>" rel="<?=$data["aplikace_id"]?>" title="<?=$data["spusteno"] ? txt("dashboard-description_swich-title-spusteno") : txt("dashboard-description_swich-title-stopnuto")?>"></div>
			<p class="title" title="<?=txt("dashboard_title-clik-edit")?>">
				<span id="title_<?=$data["aplikace_id"]?>" class="title_<?=$data["aplikace_id"]?>" rel="<?=$data["aplikace_id"]?>"><?=zkrat_text($data["og:title"], 60)?></span>
				<input id="txt_<?=$data["aplikace_id"]?>" class="txt_<?=$data["aplikace_id"]?>" type="text" name="app_name_<?=$data["aplikace_id"]?>" rel="<?=$data["aplikace_id"]?>" value="<?=$data["og:title"]?>" />
				<button class="txt_<?=$data["aplikace_id"]?>" rel="<?=$data["aplikace_id"]?>"><?=txt("dashboard_title-button-uloz")?></button>
			</p>
			<p class="fb_tab_page"><span <?=$fb_state?>><?=$data["page_name"] ? $data["page_name"] : txt("dashboard_pridej-app-na-FB")?></span></p>
			<div class="cont_stav">
				<p class="termin">
					<span class="item"><?=txt("dashboard-description_termin")?></span>
					<span class="val"><?=$stav_app["termin"]?></span>
				</p>
				<p class="licence">
					<span class="item"><?=txt("dashboard-description_licence")?></span>
					<span class="val"><? echo txt("dashboard-description_licence-".$stav_app["licence"]).doplnekLicence($stav_app["licence"], $stav_app["zbyva_dni"], $stav_app["termin"]);?> </span>
				</p>
				<p class="stav">
					<span class="item"><?=txt("dashboard-description_stav")?></span>
					<span class="val"> <?=txt("dashboard-description_stav-".$stav_app["stav"])?> </span> 
					<span class="platba" rel="<?=$data["aplikace_id"]?>"><?=$stav_app["platba"]?></span>
				</p>
			</div>
		</div>
		<div class="cl"></div>
		<div class="link_short_share">
			<div class="edit_og" rel="<?=$data["aplikace_id"]?>"></div>
			<?=txt("dashboard-link_short_share")?> <input type="text" onClick="this.select();" value="<?=$CONF_XTRA["SHORT_HOST"]?>/<?=$data["app_short_code"]?>">
			<a href="http://<?=$CONF_XTRA["SHORT_HOST"]?>/<?=$data["app_short_code"]?>" onclick="return openAWin(this.href, 1200, 800, event, '_blank', 1, 1, 1);"></a>
		</div>
<?
}


function adminDashboard($aplikace_id)
{
	dbQuery("SELECT a.*, tema_id, skin_id FROM owner_x_app oa, aplikace a LEFT JOIN tema_x_skin ts ON a.aplikace_id=ts.aplikace_id WHERE a.aplikace_id = #1 AND a.aplikace_id=oa.aplikace_id", $aplikace_id);
	$row = dbArr();
	$printDashoardFce = "printDashBoardTypApp".$row["aplikace_typ_id"];
	return array("html" => call_user_func($printDashoardFce, $row));

}
/**
* switchne aplikace ON /OFF a vrati pole (stav, spusteno, zbyva dni) v textove podobe rovnou pro zobrazeni 
* zpracuje se v js a nastavi!!
*/
function switch_app_on_off($aplikace_id) {
	// kontrola, zda je aplikace uzivatele
	dbQuery("SELECT a.aplikace_id, aplikace_typ_id, spusteno FROM aplikace a, owner_x_app oa WHERE a.aplikace_id=oa.aplikace_id AND a.aplikace_id=#1 AND owner_id=#2", $aplikace_id, $_SESSION["user"][APLIKACE_UNIQ_ID]);
	$row = dbArr();
	if($row["aplikace_id"] == $aplikace_id) {
//		dbQuery("UPDATE aplikace SET spusteno = CASE WHEN spusteno = 0 THEN 1 WHEN spusteno = 1 THEN 0 END WHERE aplikace_id=#1", $aplikace_id);
		dbQuery("UPDATE aplikace SET spusteno = #2 WHERE aplikace_id=#1", $aplikace_id, $row["spusteno"] == 1 ? 0 : 1);
		if(dbAff() == 1)
			$spusteno = $row["spusteno"] == 1 ? 0 : 1;
		$stav_app = getStavApp($aplikace_id);
//list($stav, $termin, $zbyva_dni, $licence) = getStavApp($aplikace_id);
		return array(
			"stav" => txt("dashboard-description_stav-".$stav_app["stav"]),
			"termin" => $stav_app["termin"],
			"spusteno" => $spusteno,
			"zbyva_dni" => $stav_app["zbyva_dni"],
			"licence" => txt("dashboard-description_licence-".$stav_app["licence"]).doplnekLicence($stav_app["licence"], $stav_app["zbyva_dni"], $stav_app["termin"]));
	}
	return array();
}


/**
* print dashboard app typ 4 -> zalozka
*/
function printDashBoardTypApp4($data) 
{
	ob_start();
	
	printDashBoardTypAppAll($data);
?>	
		<div class="cl"></div>
		<a href="<?=$data["aplikace_typ_id"]."/setapp?aplikace_id=".$data["aplikace_id"]?>" class="button link_editor"><?=txt("dashboard-link_to-editor")?></a>
<?		
	return ob_get_clean();
}
	
/**
* print dashboard app typ 5 -> video share - zalozka
*/
function printDashBoardTypApp5($data) 
{
	return printDashBoardTypApp4($data);
}

/**
* print dashboard app typ 8 -> instagram - zalozka
*/
function printDashBoardTypApp8($data) 
{
	return printDashBoardTypApp4($data);
}



/**
* print dashboard app typ 6 -> buduj databazi
*/
function printDashBoardTypApp6($data) 
{
		ob_start();
	
		printDashBoardTypAppAll($data);
		// statistiky
		$count_all = getpristup($data["aplikace_id"]);
		$konverze = getcountadress_nofb($data["aplikace_id"]);
		// dashboard-statistika_konverze
?>
		<div class="rounded_stat count_users">
			<div class="count"><?=$count_all?></div>
			<div class="txt"><?=txt("dashboard-statistika_pocet-zobrazeni")?></div>
		</div>

		<div class="rounded_stat count_win_prices">
			<div class="count"><?=$konverze?></div>
			<div class="txt"><?=txt("dashboard-statistika_pocet-ziskanych-emailu")?></div>
		</div>
		<div class="rounded_stat count_count_tips">
			<div class="count"><?=getkonverze($count_all, $konverze)?></div>
			<div class="txt"><?=txt("dashboard-statistika_konverzni-pomer")?></div>
		</div>
		<div class="cl"></div>
		<a href="<?=$CONF_BASE?>make_export_data?type=emails&amp;aplikace_id=<?=$data["aplikace_id"]?>" class="button link_export_users"><?=txt("dashboard-link_exportovat-data")?></a>
		<div class="cl"></div>
		<a href="<?=$CONF_BASE?><?=$data["aplikace_typ_id"]."/setapp?aplikace_id=".$data["aplikace_id"]?>" class="button link_editor"><?=txt("dashboard-link_to-editor")?></a>

<?
		
	return ob_get_clean();
}

/**
* print dashboard app typ 7 -> kolo stesti
*/
function printDashBoardTypApp7($data) 
{
	return printDashBoardTypApp2($data, 7);
}

/**
* print dashboard app typ 1 -> fotosoutez
*/
function printDashBoardTypApp1($data, $aplikace_typ_id = 1)
{
		global $CONF_BASE;
		ob_start();
	
		printDashBoardTypAppAll($data);
		// statistiky
		$count = statistika_app_1($data["aplikace_id"]);
?>

		<div class="rounded_stat count_users">
			<div class="count"><?=$count["pocet_uzivatelu"]?></div>
			<div class="txt"><?=txt("dashboard-statistika_pocet-uzivatelu")?></div>
		</div>

		<div class="rounded_stat count_win_prices">
			<div class="count"><?=$count["pocet_polozek"]?></div>
			<div class="txt"><?=txt("dashboard-statistika_celkovy_pocet_fotografii")?></div>
		</div>
		<div class="rounded_stat count_count_tips">
			<div class="count"><?=$count["pocet_hlasu"]?></div>
			<div class="txt"><?=txt("dashboard-statistika_celkovy_pocet_hlasu")?></div>
		</div>
		<div class="rounded_stat">
			<a href="<?=$CONF_BASE?>make_export_data?type=winners&amp;aplikace_id=<?=$data["aplikace_id"]?>&amp;aplikace_typ_id=<?=$aplikace_typ_id?>" class="link_export_vyherci"><?=txt("dashboard-link_exportovat-vyherce")?></a>
		</div>

		<div class="cl"></div>
		<a href="<?=$CONF_BASE?>make_export_data?type=users&amp;aplikace_id=<?=$data["aplikace_id"]?>" class="button link_export_users link_export_photos"><?=txt("dashboard-link_exportovat-data")?></a>
		<a href="<?=$CONF_BASE?>make_export_data?type=all_photos&amp;aplikace_id=<?=$data["aplikace_id"]?>" class="button link_export_users"><?=txt("dashboard-link_download-all-photos-as-zip")?></a>
		<div class="cl"></div>
		<a href="<?=$CONF_BASE?><?=$data["aplikace_typ_id"]."/setapp?aplikace_id=".$data["aplikace_id"]?>" class="button link_editor"><?=txt("dashboard-link_to-editor")?></a>

<?
		
	return ob_get_clean();
}



/**
* print dashboard thumb image
*/
function thumbApp($data)
{
	global $CONF_BASE, $CONF_BASE_DIR;
	$base_dir = "users_data/".$data["aplikace_id"]."/";
//	echo $base_dir."screen_shot.jpg";
//	return $base_dir."screen_shot.jpg";
	return is_file($CONF_BASE_DIR.$base_dir."screen_shot.jpg") ? $CONF_BASE.$base_dir."screen_shot.jpg" : ($data["skin_id"] ? $CONF_BASE.$data["aplikace_typ_id"]."/tema/".$data["tema_id"]."/skiny/".$data["skin_id"]."/thumb.png" : $CONF_BASE.$data["aplikace_typ_id"]."/tema/nopic.png");
}	

/**
*  vrati privat data ownera (uzivatele SS z tabulky owner) pro pouziti v platbach atd ...
*/
function OwnerData($fb_id = false) {
	static $OwnerData;
	if(!$fb_id)
		$fb_id = $_SESSION["user"][APLIKACE_UNIQ_ID];
	if($OwnerData[$fb_id]) return $OwnerData[$fb_id];
	dbQuery("SELECT * FROM owner WHERE fb_id=#1", $fb_id);
	$row  = dbArrTiny();
	$row["email"] = $row["email_contact"] && $row["email_contact"] != "undefined" ? $row["email_contact"] : ($row["email"] && $row["email"] != "undefined" ? $row["email"] : "");
	$row["email_contact"] = $row["email_contact"] && $row["email_contact"] != "undefined" ? $row["email_contact"] : "";
	return $OwnerData[$fb_id] = $row;
}
/**
* okno platby
* TODO: viz dalsi body
* 1) DONE - vyber terminu spusteni a ukonceni
* 2) vyber metody placeni, pokud je doba kratsi nez 30 dni! "MONTH" x "YEAR" -> odeslani
* 3)a) Zapis do datababaze, tab aplikace: "od", "do", "typ_platby"
*	b) Zapis do datababaze, tab termin_log:  cela table vcetne "od", "do"
*	c) Zapis do datababaze, tab platba: cela table vcetne "state", "gw_url"
*	d) Zapis do datababaze, tab platba_log: cela table 
* 5) Vytvoreni platby createPayment()
* 6) Zobrazeni platebni brany
* 7) Ulozeni jakehokoliv stavu
* 8) Pripadne opakovani stavu pouze v pripadech "CREATED" nebo "PAYMENT_METHOD_CHOSEN"
*/
function show_platba($aplikace_id, $popup = false)
{
	global $CONF_XTRA, $CONF, $CONF_BASE;
	$getAppInfo = getAppInfo($aplikace_id);
	if(fetch_uri("action","g") == "gopay" && fetch_uri("id","g")) {
		$tdo = date("d.m.Y", $getAppInfo["tdo"]);
		$typ_platby = $getAppInfo["typ_platby"];
		$delka_trvani = $getAppInfo["delka_trvani"];
	}
	$aplikace_typ_id = $getAppInfo["aplikace_typ_id"];
		
	ob_start();
?>	
	<div id="PopPlatba" class="PopWin PopWinWhite">
<?	if($popup) {
?> 		<div class="close" title="<?=txt("seting-close_pop_win")?>"></div>
<?	}

	
//	pre($tzlist, $str);
//	pre($getAppInfo);
?>
	<form>
		<p class="title"><?=txt("setting-platba_okno-title")?></p>		
		<input type="hidden" name="sleva_za_kupon" value="" id="sleva_za_kupon">
		<input type="hidden" name="sleva_kupon" value="" id="sleva_kupon">
		<input type="hidden" name="aplikace_typ_id" value="<?=$aplikace_typ_id?>" id="aplikace_typ_id">
		<input type="hidden" name="aplikace_id" value="<?=$aplikace_id?>" id="aplikace_id">
		<div id="set_dates">
			<p class="title"><?=txt("setting-platba_vyber-delky-trvani-title")?></p>
			<div class="delka_trvani">
				<input type="radio" name="delka_trvani" id="delka_trvani_1m" value="1"<?=get_radio_checked($delka_trvani, "1", false)?>><label for="delka_trvani_1m"><?=txt("setting-platba_vyber-delky-trvani-1m");?></label>
			</div>
			<div class="delka_trvani">
				<input type="radio" name="delka_trvani" id="delka_trvani_3m" value="3"<?=get_radio_checked($delka_trvani, "3", false)?>><label for="delka_trvani_3m"><?=txt("setting-platba_vyber-delky-trvani-3m");?></label>
			</div>
			<div class="delka_trvani">
				<input type="radio" name="delka_trvani" id="delka_trvani_6m" value="6"<?=get_radio_checked($delka_trvani, "6", false)?>><label for="delka_trvani_6m"><?=txt("setting-platba_vyber-delky-trvani-6m");?></label>
			</div>
			<div class="delka_trvani">
				<input type="radio" name="delka_trvani" id="delka_trvani_12m" value="12"<?=get_radio_checked($delka_trvani, "12", false)?>><label for="delka_trvani_12m"><?=txt("setting-platba_vyber-delky-trvani-12m");?></label>
			</div>
			<div id="recurrency">
				<p class="title"><?=txt("setting-platba_set-pay_month_all-title")?></p>
<?           if(mujpc()) { ?>				
				<input type="radio" name="typ_platby" value="DAY" id="typ_platby_day"<?=get_radio_checked($typ_platby, "DAY", false)?>><label for="typ_platby_day">denni</label>
<?           } ?>				
				<input type="radio" name="typ_platby" value="MONTH" id="typ_platby_month"<?=get_radio_checked($typ_platby, "MONTH", false)?>><label for="typ_platby_month"><?=txt("setting-platba_set-pay_month")?></label>
				<input type="radio" name="typ_platby" value="ALL" id="typ_platby_all"<?=get_radio_checked($typ_platby, "ALL", "ALL")?>><label for="typ_platby_all"><?=txt("setting-platba_set-pay_all")?></label>
			</div>
		</div>
		<img src="<?=$CONF_BASE?>img/ajax_preloader.gif" id="loading-img" style="display:none;" alt="Please Wait"/>
		<div id="set_payment_method_cont">
			<input type="hidden" name="amount" id="amount" value="" />
			<input type="hidden" name="amount_together" id="amount_together" value="" />

			<div id="cont_slev_kupon">
				<label for="slev_kupon"><?=txt("setting-platba_zadej-slevovy-kupon");?></label>
				<div id="inputs">
					<input type="text" name="slev_kupon" id="slev_kupon" />
					<button id="send_slev_kupon"><?=txt("setting-platba_zadej-slevovy-kupon_ok")?></button>
				</div>
			</div>


			<div id="set_payment_method">
				<div id="name_app" class="items"><?=$getAppInfo["title"]?></div>
				<div id="price" class="items" rel="<?=$CONF["ceny"][$aplikace_typ_id]?>"><span><?=price_format($CONF["ceny"][$aplikace_typ_id])?></span> <?=currency_code()?></div>
				<div class="cl"></div>
			</div>
			<div id="discount" class="discount"><?=txt("setting-platba_cena_sleva");?> <span><?=($CONF_XTRA["price"][$aplikace_typ_id]["YEAR_DISCOUNT"] * 100);?> %</span></div>
			<div id="discount_slev_kupon" class="discount"><?=txt("setting-platba_cena_sleva-slev_kupon");?> <span><?=($CONF_XTRA["price"][$aplikace_typ_id]["YEAR_DISCOUNT"] * 100);?> %</span></div>
			<div id="price_monthly"><?=txt("setting-platba_set-pay_monthly_reccurency");?> <span></span> <?=currency_code()?></div>
			<div id="price_together"><?=txt("setting-platba_cena_celkem");?> <span><?=price_format($CONF["ceny"][$aplikace_typ_id])?></span> <?=currency_code()?></div>
			<button id="setPayment"><?=txt("setting-platba_provest_platbu")?></button>
		</div>
		</form>
<?	if(!$popup) {
?>		<div id="gateWayPaypal"><?echo gateWayPaypalEmpty()?></div>
<?		}
?>	
	</div>
<?
	return ob_get_clean();
}

function show_premium_platba($popup = false)
{
	global $CONF_XTRA, $CONF, $CONF_BASE;
	$getAppInfo = getAppInfo($aplikace_id);
	if(fetch_uri("action","g") == "gopay" && fetch_uri("id","g")) {
		$tdo = date("d.m.Y", $getAppInfo["tdo"]);
		$typ_platby = $getAppInfo["typ_platby"];
		$delka_trvani = $getAppInfo["delka_trvani"];
	}
	$aplikace_typ_id = $getAppInfo["aplikace_typ_id"];
		
	ob_start();
?>	
	<div id="PopPlatba" class="PopWin PopWinWhite hura premium_platba<?echo fetch_uri("paid","g") == "success" ? " schovat" : ""?>">
		<?
		if(fetch_uri("x","g") != "video" && fetch_uri("x","g") != "xtra" && !$_SESSION["xtra_premium"] && fetch_uri("paid","g") != "success")
			echo Countdownxxx(3);
/*
		echo "<p>cookie=".$_COOKIE["_ssuser"]."</p>"; 
		echo "<p>".date("Y-m-d h:i:s", $_COOKIE["_ssuser"] / 1000)."</p>";
*/		
		?>
		<form>
		<input type="hidden" name="aplikace_id" value="0" id="aplikace_id" />
		<input type="hidden" name="amount" id="amount" value="<?=$CONF_XTRA["premium_cena_mesic"]?>" />
		<input type="hidden" name="amount_together" id="amount_together" value="<?= $CONF_XTRA["premium_cena_mesic"] * $CONF_XTRA["premium_delka_trvani"]?>" />
<?		if(fetch_uri("paid","g") == "success") {
			unset($_SESSION["xtra_premium"]);
?>			<p class="title"><?=txt("setting-platba_description-ss_premium_members-title-gratulace")?></p>		
			<button id="godashboard" rel="premium"><?=txt("setting-platba_description-ss_premium_members-button_vstup")?></button>
<?		}
		elseif(!$_SESSION["user"][APLIKACE_UNIQ_ID]) {
?>			<div class="premium_info"></div>		
			<button class="login" rel="premium" onclick="Login('<?=$CONF["scope"]?>', '<?=session_id()?>', 'premium'); return false;" type="submit"><?=txt("setting-platba_login-provest_platbu")?></button>
<?		}

		else {
			$OwnerData = OwnerData();
//			pre($OwnerData);
			if(!$OwnerData["email"]) {
?>				<div class="premium_info"></div>
				
				<label for="email"><?=txt("setting-platba_description-ss_premium_members-label-zadejte_email")?></label>
				<input type="text" class="text" id="email" name="email" placeholder="<?=txt("setting-adress_email")?>" />
				<button id="setPayment" rel="premium"><?=txt("setting-platba_provest_platbu")?></button>
<?			}
			else {
?>				<div class="premium_info"></div>		
				<label for="email"><?=txt("setting-platba_description-ss_premium_members-label-zkotrolujte_si_email")?></label>
				<input type="text" class="text" id="email" name="email" placeholder="<?=txt("setting-adress_email")?>" value="<?=$OwnerData["email"]?>"/>

				<button id="setPayment" rel="premium"><?=txt("setting-platba_provest_platbu")?></button>
<?			}
		}
?>
		</form>
<?		if(!$popup) {
?>			<div id="gateWayPaypal"><?echo gateWayPaypalEmpty()?></div>
<?		}
?>		
		<div id="link-obchodni-podminky"><a href="podminky/obchodnipodminkySSP_premium.pdf" target="_blank"><?=txt("link-obchodni-podminky")?></a></div>
	</div>
<?
	return ob_get_clean();
}

/**
* otestuje zda je user premium  a zda ma i zaplaceno!
*/
function TestPremiumMember($fb_id = false)
{
	static $PREMIUM_TEST_DONE;
	unset($PREMIUM_TEST_DONE);
	unset($_SESSION["premium"]);
	if(!$fb_id) $fb_id = $_SESSION["user"][APLIKACE_UNIQ_ID];
	if($fb_id < 100)
		return false;
//	pre($_SESSION["premium"]["member"], $_SESSION["user"][APLIKACE_UNIQ_ID]);
//	unset($_SESSION["premium"]["member"][APLIKACE_UNIQ_ID]);
	if($PREMIUM_TEST_DONE[$fb_id] && $_SESSION["premium"]["member"][$fb_id]) {	
		if($_SESSION["premium"]["member"][$fb_id] == "yes")
			return true;
		return false;
	}
	$PREMIUM_TEST_DONE = true;
	// 1) test platby premium pres GOPAY
//	dbQuery("SELECT kod, zaplaceno_do FROM slev_kody k, platba p WHERE owner_fb_id=#1 AND kod=spec_slev_kod AND state='PAID' AND zaplaceno_do>now()", $fb_id);
//	else
	dbQuery("SELECT kod, zaplaceno_do FROM slev_kody k, platba p WHERE owner_fb_id=#1 AND kod=spec_slev_kod AND state='PAID' AND DATE_ADD(zaplaceno_do, INTERVAL +1 DAY)>now()", $fb_id);
	$row = dbArr();
	if($row["zaplaceno_do"]) {
		$_SESSION["premium"]["member"][$fb_id] = "yes";
		$_SESSION["premium"]["kod"][$fb_id] = $row["kod"];
		return $fb_id;
		return true;
	}
	// 2) test platby premium pres platbu mimo GOPAY
	dbQuery("SELECT kod, zaplaceno_do FROM slev_kody k, platba_extra p WHERE owner_fb_id=#1 AND kod=spec_slev_kod AND zaplaceno_do>now()",	$fb_id);
	$row = dbArr();
	if($row["zaplaceno_do"]) {
		$_SESSION["premium"]["member"][$fb_id] = "yes";
		$_SESSION["premium"]["kod"][$fb_id] = $row["kod"];
		return true;
	}

	unset($_SESSION["premium"]);
	$_SESSION["premium"]["member"][$fb_id] = "no";
	return false;
}

/**
* ochekuje platnost slevoveho kodu 
* typy kodu `typ` enum('1','2') NOT NULL DEFAULT '1', -- 1 = unikatni (pouze pro jednu aplikaci), ... 2 opakovatelny (mozno pro vice aplikaci x jeden FB ucet!!!) 
*/
function checkSlevKod($slev_kupon, $aplikace_id)
{	
	global $CONF_XTRA;	
	dbQuery("SELECT k.*, UNIX_TIMESTAMP(platnost_od) unixod, UNIX_TIMESTAMP(platnost_do) unixdo, owner_id FROM slev_kody k LEFT JOIN owner_x_app oa ON kod=slev_kod AND aplikace_id!=#2 WHERE kod=#1", $slev_kupon, $aplikace_id);
	$row = dbArrTiny();
	$row["txt1"] = txt("setting-platba_slevovy-kupon_uplatnen"); 
	$row["txt2"] = txt("setting-platba_slevovy-kupon_sleva"); 
	
	// pokud je neplatny zasilam info jako state!
	$state["BAD"] = txt("setting-platba_slevovy-kupon_BAD"); 
	$state["EXPIRED"] = txt("setting-platba_slevovy-kupon_EXPIRED"); 
	$state["TOO-SOON"] = txt("setting-platba_slevovy-kupon_TOO-SOON"); 
	$state["USED_BY_OWN"] = txt("setting-platba_slevovy-kupon_USED_BY_OWN"); 
	$state["USED_BY_OTHER"] = txt("setting-platba_slevovy-kupon_USED_BY_OTHER"); 
//	pre($row);
	// neexistujici kod!
	if(!$row["kod"])
		return array("state_slev_kupon" => "BAD", "state" => $state["BAD"]);
	// kod jeste neni platny!
	if($row["unixod"] > time())
		return array("state_slev_kupon" => "TOO-SOON", "state" => $state["TOO-SOON"]);
//		return array_merge($row,array("state_slev_kupon" => "TOO-SOON", "state" => $state["TOO-SOON"], "time" => time()));
	// kod expiroval!
	if($row["unixdo"] < time())
		return array("state_slev_kupon" => "EXPIRED", "state" => $state["EXPIRED"]);
	// kod je volny a platny!
	if(!$row["owner_id"])
		return $row;
	### pro typ =1 (kod unikatni) je to vse, ale pro kod typ = 2 (kod unikatni k majiteli), musim jeste jeden select! ###
	// kod typ = 1 unikat!
	if($row["typ"] == 1) {
		// kod pouzit prihlasenym uzivatelem
		if($row["owner_id"] == $_SESSION["user"][APLIKACE_UNIQ_ID]) {
			return array("state_slev_kupon" => "USED_BY_OWN", "state" => $state["USED_BY_OWN"]);
		}
		// kod pouzit jinym uzivatelem
		if($row["owner_id"] != $_SESSION["user"][APLIKACE_UNIQ_ID]) {
			return array("state_slev_kupon" => "USED_BY_OTHER", "state" => $state["USED_BY_OTHER"]);
		}
		// kod je volny a platny!
		return $row;
	}
	
	// pro typ = 2 musim jeste udelat dalsi select, zda tento klient jiz pouzil!
	dbQuery("SELECT owner_id FROM slev_kody k LEFT JOIN owner_x_app oa ON kod=slev_kod AND aplikace_id!=#2 WHERE kod=#1 AND owner_id=#3", $slev_kupon, $aplikace_id, $_SESSION["user"][APLIKACE_UNIQ_ID]);
	$row2 = dbArrTiny();
	// kod nepouzit prihlasenym uzivatelem, zatim pouze jinym uzivatelem, coz u "typ = 2" je v poradku!
	if(!$row2["owner_id"]) {
		return array_merge($row, array("state_slev_kupon" => "USED_BY_OTHER_2"));
	}
	// kod pouzit prihlasenym uzivatelem
	return array("state_slev_kupon" => "USED_BY_OWN", "state" => $state["USED_BY_OWN"]);
}

function price_format($price)
{
	return number_format ( round($price), $decimals = 0 , "." ," " );
}


/**
* vraci nazev, resp. title aplikace
*/
function getAppInfoName()
{
	$app = getAppInfo($_SESSION["aplikace_id"]);
	return $app["title"];
}

/**
* spocita koncove hranicni datum, do ktereho je aplikace predplacene, resp. mela by byt placena mesicne v pripade recurrency MONTH typu!
* O: - end_date je datum do ktereho je aplikace zaplacena
*	 - monthdiff je pocet mesicu k platbe!
*/
function mysql_month_diff($from, $to)
{
	dbQuery("SELECT period_diff(date_format(str_to_date(#1,   '%Y-%m-%d'), '%Y%m'),     date_format(str_to_date(#2, '%Y-%m-%d'), '%Y%m'))", $to, $from);
	$row = dbArr();
//	pre($row);
	$monthdiff = $row[0];
	dbQuery("SELECT DATE_ADD(#1,INTERVAL #2 MONTH), DATE_ADD(#1, INTERVAL #3 MONTH )", $from, $monthdiff, $monthdiff + 1);
	$row = dbArr();
//	pre($row);
	return array("end_date" => strtotime($row[0]) >= strtotime($to) ? $row[0] : $row[1], "monthdiff" => strtotime($row[0]) >= strtotime($to) ? $monthdiff  : $monthdiff + 1);
}

/**
* spocita koncove hranicni datum, do ktereho je aplikace predplacene, resp. mela by byt placena mesicne v pripade recurrency MONTH typu!
*/
function mysql_date_add_month($from, $monthdiff)
{
	dbQuery("SELECT DATE_ADD(#1,INTERVAL #2 MONTH)", $from, $monthdiff);
	$row = dbArr();
//	pre($row);
	return $row[0];
}


/**
* vrati vetu o aktualnim pocctu zbyvajicich dni do konce aplikace!
*/
function doplnekLicence($licence, $zbyva_dni, $termin)
{
	if($licence != "placena")
		return;
	if($zbyva_dni > 0 && $zbyva_dni <= 10)
		return " <span class=\"attention\">(".txt("dashboard-description_konci-za")." ".sklonuj("den",$zbyva_dni).")";
	if($zbyva_dni > 0)
		return " (".txt("dashboard-description_konci-za")." ".sklonuj("den",$zbyva_dni).")";
	if($zbyva_dni <= 0 && $termin)
		return " (".txt("dashboard-description_skoncila").")";
}



/**
* fce prida uniqid k aplikaci, slouzi pro vytvoreni short url sprinte.rs/uniqid
* kontroluje, unikatnost uniq_id :-)
*/
function updateAppUniqUrl($aplikace_id)
{
	dbQuery("UPDATE aplikace SET app_short_code=#2 WHERE aplikace_id=#1", $aplikace_id, uniqid(""));
	if(dbAff() != 1)
		updateAppUniqUrl($aplikace_id);
}

/**
* updatne FB OG, title a description parametry aplikace pri prirazeni aplikace majiteli - kvuli jazykovym verzim
*/
function updateAppFBOgByLang($aplikace_id, $aplikace_typ_id)
{
	dbQuery("UPDATE `aplikace` SET `title`=#2, `description`=#3, `og:title`=#2,`og:description`=#3, `canvas`=NULL, `od`=NULL, `do`=NULL, `end`=NULL, `og:image`=NULL, `snow`=0, `spusteno`=0, `win_repeat`=0 WHERE aplikace_id=#1",
		$aplikace_id, txt("reset_app_".$aplikace_typ_id."_title"),txt("reset_app_".$aplikace_typ_id."_descr"));
	return dbAff();
//	if(dbAff() != 1)
}




/**
* DashBoard statistika pro aplikace_typ_id = 1 (fotosoutez)
*/
function statistika_app_1($aplikace_id) {
	/* pocet uzivatelu */
	dbQuery("SELECT count(*) FROM uzivatel WHERE aplikace_id = #1 AND prijmeni <> 'undefined'", $aplikace_id);
	$row = dbArr();
	$pocet_uzivatelu = $row[0];

	/* pocet polozek (fotografii) v soutezi */
	dbQuery("SELECT count(*) soucet FROM soutez_polozka WHERE aplikace_id=#1", $aplikace_id);
	while($row = dbArr()) {
		$pocet_polozek = $row["soucet"];
	}

	/* pocet FB hlasu k polozkam (fotografiim)*/
	dbQuery("SELECT count(*) soucet FROM hlas_soutez hs, uzivatel u WHERE u.fb_id=hs.fb_id AND hs.aplikace_id=#1", $aplikace_id);
	while($row = dbArr()) {
		$pocet_hlasu = $row["soucet"];
	}
	
	return array(
		"pocet_uzivatelu" => $pocet_uzivatelu,
		"pocet_polozek" => $pocet_polozek,
		"pocet_hlasu" => $pocet_hlasu
		);
/*
	echo "<h2>Statistika global:</h2>";
	echo "<p>Počet uživatelů aplikace: ".$pocet_uzivatelu."</p>";
	echo "<p>Počet tipů: ".($pocet_spravnych_pokusu + $pocet_spatnych_pokusu)."</p>";
	echo "<p>Počet cen v soutěži: ".array_sum($hash_pocet_vyher)."</p>";
	echo "<p>Počet vyhraných cen: ".$pocet_spravnych_pokusu."</p>";
	echo "<p>Počet zbývajících cen: ".(array_sum($hash_pocet_vyher) - $pocet_spravnych_pokusu)."</p>";
*/	
}

/**
* DashBoard statistika pro aplikace_typ_id = 2 (trezor)
*/
function statistika_app_2($aplikace_id) {
	/* pocet uzivatelu */
	dbQuery("SELECT count(*) FROM uzivatel WHERE aplikace_id = #1 AND prijmeni <> 'undefined'", $aplikace_id);
	$row = dbArr();
	$pocet_uzivatelu = $row[0];

	/* pocet spravnych a nespravnych tipu (respektive opakovanych kodu nebo jiz vybranych vyher!) */
	$pocet_spravnych_pokusu = 0;
	$pocet_spatnych_pokusu = 0;
	//dbQuery("?SELECT vyhra, count(*) soucet FROM pokus_log p, kody k WHERE k.aplikace_id = p.aplikace_id AND k.aplikace_id=#1 AND k.kod=p.kod GROUP BY vyhra", $aplikace_id);
	dbQuery("SELECT vyhra, count(*) soucet FROM pokus_log WHERE aplikace_id=#1 GROUP BY vyhra", $aplikace_id);
	while($row = dbArr()) {
		if($row["vyhra"] == 1)
			$pocet_spravnych_pokusu = $row["soucet"];
		if($row["vyhra"] == 2)
			$pocet_spatnych_pokusu = $row["soucet"];
	}

	/* hash z tabulky vyher v soutezi */
	$hash_pocet_vyher = array();
	dbQuery("SELECT * FROM vyhry WHERE aplikace_id=#1", $aplikace_id);
	while($row = dbArr()) {
		$hash_vyhra[$row["vyhra_id"]] = $row["popis"];
		$hash_pocet_vyher[$row["vyhra_id"]] = $row["pocet_vyher"];
	}

	//pre($hash_vyhra,"nazvy vyher");
	//pre($hash_pocet_vyher,"pocty vyher ve hre");

	/* pocty vyher */
	$pocet_vyher_celkem = 0;
	//dbQuery("?SELECT vyhra_id, count(*) soucet FROM pokus_log p, kody k, uzivatel_adress a WHERE k.aplikace_id = p.aplikace_id AND k.aplikace_id=#1 AND k.aplikace_id=a.aplikace_id AND p.aplikace_id=a.aplikace_id AND p.fb_id=a.fb_id AND k.kod=p.kod AND vyhra = 1 GROUP BY vyhra_id", $aplikace_id);
	dbQuery("SELECT vyhra_id, count(*) soucet FROM pokus_log p, kody k WHERE k.aplikace_id = p.aplikace_id AND k.aplikace_id=#1 AND k.kod=p.kod AND vyhra = 1 GROUP BY vyhra_id", $aplikace_id);
	while($row = dbArr())  {
		/* normalne */
	//	pre($row,"pokusy");
	//	$pocty_vyher[$hash_vyhra[$row["vyhra_id"]]] = $row["soucet"];
		/* hack poctu vyher, kvuli chybe, pri vice spravnych tipu nez vyher :-(  -	uz snad nepotrebuji! */
		$row["soucet"] = $row["soucet"] >= $hash_pocet_vyher[$row["vyhra_id"]] ? $hash_pocet_vyher[$row["vyhra_id"]] : $row["soucet"];
		$pocty_vyher[$hash_vyhra[$row["vyhra_id"]]] = "celkem = ".$hash_pocet_vyher[$row["vyhra_id"]]." | uhodnuto = ".$row["soucet"]." | zbývá = ".($hash_pocet_vyher[$row["vyhra_id"]] - $row["soucet"]);
	//	$pocet_vyher_celkem += $row["soucet"];
	}
	
	return array(
		"pocet_uzivatelu" => $pocet_uzivatelu,
		"pocet_tipu" => $pocet_spravnych_pokusu + $pocet_spatnych_pokusu,
		"pocet_cen" => array_sum($hash_pocet_vyher),
		"pocet_vyhranych_cen" => $pocet_spravnych_pokusu,
		);
/*
	echo "<h2>Statistika global:</h2>";
	echo "<p>Počet uživatelů aplikace: ".$pocet_uzivatelu."</p>";
	echo "<p>Počet tipů: ".($pocet_spravnych_pokusu + $pocet_spatnych_pokusu)."</p>";
	echo "<p>Počet cen v soutěži: ".array_sum($hash_pocet_vyher)."</p>";
	echo "<p>Počet vyhraných cen: ".$pocet_spravnych_pokusu."</p>";
	echo "<p>Počet zbývajících cen: ".(array_sum($hash_pocet_vyher) - $pocet_spravnych_pokusu)."</p>";
*/	
}

/**
* DashBoard statistika pro aplikace_typ_id = 3 (kviz)
*/
function statistika_app_3($aplikace_id) {
	/* pocet uzivatelu */
	dbQuery("SELECT count(*) FROM uzivatel WHERE aplikace_id = #1 AND prijmeni <> 'undefined'", $aplikace_id);
	$row = dbArr();
	$pocet_uzivatelu = $row[0];

	/* pocet dokoncenych kvizu */
	dbQuery("SELECT count(*) soucet FROM user_test WHERE aplikace_id=#1", $aplikace_id);
	while($row = dbArr()) {
		$pocet_dokoncenych_kvizu = $row["soucet"];
	}
	
	$id_tests = array();
	/* prumer spravnych odpovedi */
	dbQuery("SELECT count(*) soucet_vysledku, spravne, o.id_test FROM user_odpovedi o, user_test t WHERE o.aplikace_id=#1 AND o.id_test=t.id_test AND done=1 GROUP BY id_test, spravne", $aplikace_id);
	if(dbRows() == 0)
		return array(
			"pocet_uzivatelu" => 0,
			"pocet_dokoncenych_kvizu" => 0,
			"procento_uspesnosti_testu" => false
			);

	while($row = dbArr()) {
		$id_tests[$row["id_test"]] = $row["id_test"];
		if($row["spravne"] == 1)
			$spravne[$row["id_test"]] = $row["soucet_vysledku"];
		if($row["spravne"] == 0)
			$spatne[$row["id_test"]] = $row["soucet_vysledku"];
	}
//	pre($spravne, "spravne");
//	pre($spatne, "spatne");

	foreach($id_tests as $id_test) {
		$procento_uspesnosti[] = 100*$spravne[$id_test]/($spatne[$id_test]+$spravne[$id_test]);
	}

	// prumer ze vsech testu!
	$all_test_average = round(array_sum($procento_uspesnosti) / count($procento_uspesnosti));
//	pre($procento_uspesnosti, $average = round(array_sum($procento_uspesnosti) / count($procento_uspesnosti)));
	
	return array(
		"pocet_uzivatelu" => $pocet_uzivatelu,
		"pocet_dokoncenych_kvizu" => $pocet_dokoncenych_kvizu,
		"procento_uspesnosti_testu" => $all_test_average
		);
}

/**
*	zkrati text na pocet znaku na cela slova!!! 
*/
function zkrat_text($text, $pocet_znaku) {
    return strlen($text) <= $pocet_znaku ? $text : mb_substr(mb_substr($text, 0, $pocet_znaku, "UTF-8"), 0, strrpos(mb_substr($text, 0, $pocet_znaku, "UTF-8"), " "))." ...";
}

/**
*	user board (logout ...) 
*/
function user_board()
{
	global $CONF_BASE, $CONF_BASE_HOME, $CONF_BASE_DIR; 
	if(!$_SESSION["user"][APLIKACE_UNIQ_ID]) return;
	ob_start();
?>	
<nav class="menu-hlavni-navigace-container">
<ul id="menu" class="menu">
<?
if(strpos($_SERVER["PHP_SELF"], "index.php")) {
?>
<li><a href="<?=$CONF_BASE_HOME;?>blog/" target="_blank"><?=txt("menu-blog")?></a></li>
<li><a href="<?=$CONF_BASE_HOME;?>pripadove-studie/"><?=txt("menu-cases")?></a></li>
<li><a href="<?=$CONF_BASE_HOME;?>cenik/"><?=txt("menu-cenik")?></a></li>
<li><a href="<?=$CONF_BASE_HOME;?>kontakt/"><?=txt("menu-kontakt")?></a></li>
<? }
else {
?>
<li><a href="<?=$CONF_BASE;?>"><?=txt("menu-dashboard")?></a></li>
<?
}
?>
</ul>
</nav>

	<div id="user_board">	

		<div id="user">
			<img id="fbphoto" src="<?=$CONF_BASE?>fb_photos/50x50/<?=$_SESSION["user"][APLIKACE_UNIQ_ID].".jpg"?>">
			<a id="name" href="#"><?=$_SESSION["user_name"]?></a>
			<div class="cl"></div>
			<div id="cont_sprava">
				<div id="sprava">
					<a href="<?=$CONF_BASE?>?action=setting" id="nastaveni"><?=txt("setting-link_nastaveni")?></a>
					<div id="cont_sub_nastaveni">
						<a href="<?=$CONF_BASE?>?action=setting" id="nastaveni_lang"><?=txt("setting-link_nastaveni-lang")?></a>
						<a href="<?=$CONF_BASE?>?action=setting" id="nastaveni_fakturace" rel="<?=$_SESSION["user"][APLIKACE_UNIQ_ID]?>"><?=txt("setting-link_nastaveni-fakturace")?></a>
					</div>
					<a href="<?=$CONF_BASE?>?action=setting" id="prehled_fakturace" rel="<?=$_SESSION["user"][APLIKACE_UNIQ_ID]?>"><?=txt("setting-link_prehled-fakturace")?></a>
					<a href="<?=$CONF_BASE?>?action=logout"><?=txt("setting-link_logout")?></a>
				</div>
			</div>
		</div>
	</div>
<?
	return ob_get_clean();
}

function vote_new_app($app_number) {
	dbQuery("REPLACE vote_new_app SET vote = 1, fb_id = #1, app_number=#2", $_SESSION["user"][APLIKACE_UNIQ_ID], $app_number);
	dbQuery("SELECT SUM(vote) FROM vote_new_app WHERE app_number=#2", $_SESSION["user"][APLIKACE_UNIQ_ID], $app_number);
	$row = dbArr();
	return $row[0];
}


/**
* smaze celou aplikaci z mysql bez naroku na vraceni!
*/
function deleteApp($aplikace_id) {
	global $CONF_BASE_DIR;
	// kontrola zda aplikace patri majiteli!
	dbQuery("SELECT aplikace_id FROM owner_x_app WHERE owner_id=#1 AND aplikace_id=#2",$_SESSION["user"][APLIKACE_UNIQ_ID],$aplikace_id);
	$row = dbArr();
	if($row[0] != $aplikace_id) 
		return;

	dbQuery("SELECT aplikace_typ_id FROM aplikace WHERE aplikace_id=#1",$aplikace_id);
	$row = dbArr();
	$aplikace_typ_id = $row[0];

	$flag = true;
	// First of all, let's begin a transaction
	dbQuery("START TRANSACTION");

	// A set of queries; if one fails, an exception should be thrown

	dbQuery("DELETE FROM uzivatel WHERE aplikace_id=#1",$aplikace_id);
	if(dbAff()<0) {
		  $flag = false;
	}
	dbQuery("DELETE FROM uzivatel_adress WHERE aplikace_id=#1",$aplikace_id);
	if(dbAff()<0) {
		  $flag = false;
	}
	dbQuery("DELETE FROM uzivatel_adress_set WHERE aplikace_id=#1",$aplikace_id);
	if(dbAff()<0) {
		  $flag = false;
	}

	dbQuery("DELETE FROM uzivatel_adress_nofb WHERE aplikace_id=#1",$aplikace_id);
	if(dbAff()<0) {
		  $flag = false;
	}
	dbQuery("DELETE FROM kody WHERE aplikace_id=#1",$aplikace_id);
	if(dbAff()<0) {
		  $flag = false;
	}
	dbQuery("DELETE FROM vyhry WHERE aplikace_id=#1",$aplikace_id);
	if(dbAff()<0) {
		  $flag = false;
	}
	dbQuery("DELETE FROM banery WHERE aplikace_id=#1",$aplikace_id);
	if(dbAff()<0) {
		  $flag = false;
	}
	dbQuery("DELETE FROM pokus_log WHERE aplikace_id=#1",$aplikace_id);
	if(dbAff()<0) {
		  $flag = false;
	}
	dbQuery("DELETE FROM pokus_counter WHERE aplikace_id=#1",$aplikace_id);
	if(dbAff()<0) {
		  $flag = false;
	}
	dbQuery("DELETE FROM hlas WHERE aplikace_id=#1",$aplikace_id);
	if(dbAff()<0) {
		  $flag = false;
	}
	dbQuery("DELETE FROM hlas_soutez WHERE aplikace_id=#1",$aplikace_id);
	if(dbAff()<0) {
		  $flag = false;
	}

	dbQuery("DELETE FROM uz_polozka WHERE aplikace_id=#1",$aplikace_id);
	if(dbAff()<0) {
		  $flag = false;
	}
	dbQuery("DELETE FROM polozka WHERE aplikace_id=#1",$aplikace_id);
	if(dbAff()<0) {
		  $flag = false;
	}
	dbQuery("DELETE FROM soutez_polozka WHERE aplikace_id=#1",$aplikace_id);
	if(dbAff()<0) {
		  $flag = false;
	}

	dbQuery("DELETE FROM platba_log WHERE aplikace_id=#1",$aplikace_id);
	if(dbAff()<0) {
		  $flag = false;
	}
	dbQuery("DELETE FROM platba WHERE aplikace_id=#1",$aplikace_id);
	if(dbAff()<0) {
		  $flag = false;
	}
	dbQuery("DELETE FROM termin_log WHERE aplikace_id=#1",$aplikace_id);
	if(dbAff()<0) {
		  $flag = false;
	}
	dbQuery("DELETE FROM feedback WHERE aplikace_id=#1",$aplikace_id);
	if(dbAff()<0) {
		  $flag = false;
	}
	dbQuery("DELETE FROM html WHERE aplikace_id=#1",$aplikace_id);
	if(dbAff()<0) {
		  $flag = false;
	}
	dbQuery("DELETE FROM css WHERE aplikace_id=#1",$aplikace_id);
	if(dbAff()<0) {
		  $flag = false;
	}
	dbQuery("DELETE FROM tema_x_skin WHERE aplikace_id=#1",$aplikace_id);
	if(dbAff()<0) {
		  $flag = false;
	}
	dbQuery("DELETE FROM owner_x_app WHERE aplikace_id=#1",$aplikace_id);
	if(dbAff()<0) {
		  $flag = false;
	}
	dbQuery("DELETE FROM page_x_app WHERE aplikace_id=#1",$aplikace_id);
	if(dbAff()<0) {
		  $flag = false;
	}
	dbQuery("DELETE FROM pristup WHERE aplikace_id=#1",$aplikace_id);
	if(dbAff()<0) {
		  $flag = false;
	}
	dbQuery("DELETE FROM sada_kodu WHERE aplikace_id=#1",$aplikace_id);
	if(dbAff()<0) {
		  $flag = false;
	}
	dbQuery("DELETE FROM polozka_uni WHERE aplikace_id=#1",$aplikace_id);
	if(dbAff()<0) {
		  $flag = false;
	}

	/* pouze kviz zatim asi */
	dbQuery("DELETE FROM vysledky WHERE aplikace_id=#1",$aplikace_id);
	if(dbAff()<0) {
		  $flag = false;
	}
	dbQuery("DELETE FROM otazky WHERE aplikace_id=#1",$aplikace_id);
	if(dbAff()<0) {
		  $flag = false;
	}
	dbQuery("DELETE FROM odpovedi WHERE aplikace_id=#1",$aplikace_id);
	if(dbAff()<0) {
		  $flag = false;
	}
	dbQuery("DELETE FROM user_test WHERE aplikace_id=#1",$aplikace_id);
	if(dbAff()<0) {
		  $flag = false;
	}
	dbQuery("DELETE FROM user_odpovedi WHERE aplikace_id=#1",$aplikace_id);
	if(dbAff()<0) {
		  $flag = false;
	}
	/* /pouze kviz zatim asi */

	/* pouze instagram zatim asi */
	dbQuery("DELETE FROM instagram_user WHERE aplikace_id=#1",$aplikace_id);
	if(dbAff()<0) {
		  $flag = false;
	}
	/* /pouze instagram zatim asi */

	/* vlastni prvky */
	dbQuery("DELETE FROM own_block WHERE aplikace_id=#1",$aplikace_id);
	if(dbAff()<0) {
		$flag = false;
	}
	/* /vlastni prvky */


	$dbAff = updateAppFBOgByLang($aplikace_id, $aplikace_typ_id);
/*
	dbQuery("UPDATE `aplikace` SET `title`=#2, `description`=#3, `og:title`=#2,`og:description`=#3, `canvas`=NULL, `od`=NULL, `do`=NULL, `end`=NULL, `og:image`=NULL, `snow`=0, `spusteno`=0, `win_repeat`=0 WHERE aplikace_id=#1",
		$aplikace_id, txt("reset_app_".$aplikace_typ_id."_title"),txt("reset_app_".$aplikace_typ_id."_descr"));
	$dbAff = dbAff();
*/	

	if($dbAff<0) {
		  $flag = false;
	}
	
	// zmeni unikatni app_short_code pro short url!
	updateAppUniqUrl($aplikace_id);
	if($dbAff<0) {
		  $flag = false;
	}

	// zde nelze rescrape, pac neni prirazeno zadne strance - recrape udelam po prirazeni do FB tabu!
//	rescrapeFbOg($aplikace_id);

	// If we arrive here, it means that no exception was thrown
	// i.e. no query has failed, and we can commit the transaction
	if($flag) {
		$user_dir = $CONF_BASE_DIR."users_data/".$aplikace_id;
		rrmdir($user_dir);
		dbQuery("COMMIT");
		return array("transaction" => "commit", "aff" => $dbAff);
	}
	// An exception has been thrown
	// We must rollback the transaction
	else {
		dbQuery("ROLLBACK");
		return array("transaction" => "rollback", "aff" => $dbAff);
	}

}

/**
* Pop okno pro zmenu jazyka
*/
function PopSetLanguage() {
	ob_start();
?>
<div id="set_lang" class="PopWin PopWinWhite">
	<div class="close" title="<?=txt("seting-close_pop_win")?>"></div>
	<p class="title"><?=txt("setting-title_nastaveni_jazyka")?></p>
	<p class="subtitle"><?=txt("setting-text_nastaveni_jazyka")?></p>
	<a href="php/action.php" id="lang_cs"><?=txt("setting-nastaveni_jazyka-cestina")?></a>
	<a href="php/action.php" id="lang_sk"><?=txt("setting-nastaveni_jazyka-slovenstina")?></a>
<?	if($_SESSION["x51admin"]) {
?>		<a href="php/action.php" id="lang_en"><?=txt("setting-nastaveni_jazyka-anglictina")?></a>
		<a href="php/action.php" id="lang_de"><?=txt("setting-nastaveni_jazyka-nemcina")?></a>
<?	}
?>
</div>
<?
	return ob_get_clean();
}

/**
* Pop okno vypis faktur v pdf
*/
function PopShowFaktury($fb_id = false) {
	global $CONF_BASE_DIR, $CONF_BASE, $CONF;
	// kontrola zda jde o x51 admina, ci vlastnika
	if($fb_id != $_SESSION["user"][APLIKACE_UNIQ_ID] && !$_SESSION["x51admin"])
		return false;
	$fak = array();
	$fb_id = $fb_id ? $fb_id : $_SESSION["user"][APLIKACE_UNIQ_ID];
	dbQuery("SELECT * FROM faktury WHERE fb_id=#1 ORDER BY vs DESC", $fb_id);
	while($row = dbArr()) {
		$fak[] = $row;
	}

	$fb_id = $fb_id ? $fb_id : $_SESSION["user"][APLIKACE_UNIQ_ID];
	dbQuery("SELECT nazev FROM odberatel WHERE fb_id=#1", $fb_id);
	$row = dbArr();
	$odberatel = $row["nazev"];
?>
<div id="show_faktury" class="PopWin PopWinWhite">
	<div class="close" title="<?=txt("seting-close_pop_win")?>"></div>
	<p class="title"><?=txt("setting-title_prehled_fakturace")?></p>

	<table>
	<tr>
		<th><?=txt("setting-table_prehled_fakturace-title_stav")?></th>
		<th><?=txt("setting-table_prehled_fakturace-title_vs")?></th>
		<th><?=txt("setting-table_prehled_fakturace-title_prijemce")?></th>
		<th><?=txt("setting-table_prehled_fakturace-title_datum_vystaveni")?></th>
		<th><?=txt("setting-table_prehled_fakturace-title_datum_splatnosti")?></th>
		<th><?=txt("setting-table_prehled_fakturace-title_typ_dokladu")?></th>
		<th><?=txt("setting-table_prehled_fakturace-title_castka")?></th>
		<th></th>
	</tr>

	<?
	foreach($fak as $k => $row) {
		$filename = urlencode($row["vs"]).".pdf";
		if(is_file($CONF_BASE_DIR."faktury/".$filename)) { 
?>		<tr>	
			<td><?=txt("setting-table_prehled_fakturace-stav-zaplaceno")?></td>
			<td><?=$row["vs"]?></td>
			<td><?=$odberatel?></td>
			<td><?=date("d.m.Y",dbDate($row["datum_vystaveni"]))?></td>
			<td><?=date("d.m.Y",dbDate($row["datum_splatnosti"]))?></td>
			<td><?=txt("setting-table_prehled_fakturace-typ_dokladu-danovy")?></td>
			<td class="castka"><?=price_format($row["cena"] / 100)?> <?=currency_code_from_ISO($row["currency"])?></td>
			<td class="download"><a href="download.php?type=faktura&amp;file=faktury/<?=$filename?>"></a></td>
		</tr>
<?
		}
	}
//	pre($fak,"frakltury");
	
	?>
</div>
<?
	return ob_get_clean();
}



/**
* Pop okno pro editace fakturacnich udaju
*/
function PopSetFakturace($fb_id = false) {
	$row = array();
	// kontrola zda jde o x51 admina, ci vlastnika
	if($fb_id != $_SESSION["user"][APLIKACE_UNIQ_ID] && !$_SESSION["x51admin"])
		return false;
	$fb_id = $fb_id ? $fb_id : $_SESSION["user"][APLIKACE_UNIQ_ID];
	if($fb_id) {
		dbQuery("SELECT * FROM odberatel WHERE fb_id=#1", $fb_id);
		$row = dbArr();
		// pokud je dokoncena platba a jiz je zalozen odberatel nezobrazuji!
		if(fetch_uri("action","g") == "paid_success" && $row["nazev"])
			return false;
	}
	ob_start();
?>
<div id="set_fakturace" class="PopWin PopWinWhite set_fakturace">
	<div class="close" title="<?=txt("seting-close_pop_win")?>"></div>
	<p class="title"><?=txt("setting-title_nastaveni_fakturace")?></p>
	<form action="php/actions.php" method="GET">
<?
		forms_inputs_odberatel($fb_id, $row);
?>		

		<button><?=txt("setting-button_vstup")?></button>
	</form>
</div>
<?
	return ob_get_clean();
}

function forms_inputs_odberatel($fb_id, $row = array())
{

	if($fb_id && !$row) {
		dbQuery("SELECT * FROM odberatel WHERE fb_id=#1", $fb_id);
		$row = dbArr();
	}

?>
		<input type="hidden" id="f_fb_id" name="fb_id" value="<?=$fb_id?>">
		<input type="hidden" id="f_session_id" name="session_id" value="<?=session_id()?>">
		<input type="hidden" name="type" value="setFakturace">
		<div>
			<label for="nazev"><?=txt("odberatel-nazev")?></label>
			<input type="text" class="text" name="nazev" value="<?=htmlspecialchars($row["nazev"])?>" placeholder="<?=txt("odberatel-nazev")?>" rel="y">
		</div>
		<div>
			<label for="ulice"><?=txt("odberatel-ulice")?></label>
			<input type="text" class="text" name="ulice" value="<?=htmlspecialchars($row["ulice"])?>" placeholder="<?=txt("odberatel-ulice")?>" rel="y">
		</div>
		<div>
			<label for="mesto"><?=txt("odberatel-mesto")?></label>
			<input type="text" class="text" name="mesto" value="<?=htmlspecialchars($row["mesto"])?>" placeholder="<?=txt("odberatel-mesto")?>" rel="y">
		</div>
		<div>
			<label for="stat_iso"><?=txt("odberatel-stat")?></label>
<?			dbQuery("SELECT iso, printable_name FROM zeme ORDER BY printable_name");
?>			<select	name="stat_iso" id="name_iso">
<?			renderOptionsFromDb($row["stat_iso"], "CZ");
?>			</select>
		</div>

		<div>
			<label for="psc"><?=txt("odberatel-psc")?></label>
			<input type="text" class="text" name="psc" value="<?=htmlspecialchars($row["psc"])?>" placeholder="<?=txt("odberatel-psc")?>" rel="y">
		</div>
		<div>
			<label for="ic"><?=txt("odberatel-ic")?></label>
			<input type="text" class="text" name="ic" value="<?=htmlspecialchars($row["ic"])?>" placeholder="<?=txt("odberatel-ic")?>" >
		</div>
		<div>
			<label for="dic"><?=txt("odberatel-dic")?></label>
			<input type="text" class="text" name="dic" value="<?=htmlspecialchars($row["dic"])?>" placeholder="<?=txt("odberatel-dic")?>" >
		</div>
		<div>
			<label for="platce_dph"><?=txt("odberatel-platce_dph")?></label>
			<input type="checkbox" id="platce_dph" name="platce_dph" value="ano"<?=get_checked(htmlspecialchars($row["platce_dph"]), "ano", false)?>>
		</div>

		<div>
			<label for="telefon"><?=txt("odberatel-telefon")?></label>
			<input type="text" class="text" name="telefon" value="<?=htmlspecialchars($row["telefon"])?>" placeholder="<?=txt("odberatel-telefon")?>" rel="y">
		</div>
		<div>
			<label for="email"><?=txt("odberatel-email")?></label>
			<input type="text" class="text" id="f_email" name="email" value="<?=htmlspecialchars($row["email"])?>" placeholder="<?=txt("odberatel-email")?>" rel="y">
		</div>
<?
}



/**
* Pop okno pro zadani kontaktniho emailu
*/
function PopContactEmail() {
	$OwnerData = OwnerData();
//	pre($OwnerData);
	// TODO: fce PopContactEmail uncomment!!!
	if($OwnerData["email_contact"]) 
		return "";
?>
<div id="set_email" class="PopWin PopWinWhite">
	<div class="close" title="<?=txt("seting-close_pop_win")?>"></div>
	<p class="title"><?=txt("setting-kontaktni_email")?></p>
	<p class="subtitle"><?=txt("setting-kontaktni_email-info_text")?></p>
	<form action="php/actions.php" method="post">
	<input type="hidden" name="type" value="saveContactEmail" />
	<input class="text" type="text" id="contact_email" name="contact_email" value="<?=$OwnerData["email"]?>" />
	<button type="submit"><?=txt("setting-button_confirm")?></button>	
	</form>
</div>
<?
}

/**
* Ulozeni kontaktniho emailu ownera
*/
function SaveContactEmail($email, $smartmailing_id = false)  {
	$owner = OwnerData();
	$CONF = setConfig();
	$smartmailing = false;

	// pokud jde o nejakou akci, kde se potvrzuje email potrebuji datm zapisu uzivatele - zapisu do MySql do tabulky owner_x_akce!
	if($CONF["nazev_akce"]) {
		dbQuery("INSERT owner_x_akce SET owner_id=#1, nazev_akce=#2", $_SESSION["user"][APLIKACE_UNIQ_ID], $CONF["nazev_akce"]);
		$dbaff_owner_x_akce = dbAff();
	}

	if($smartmailing_id && sendRequest(make_add_smartmailing_xml($owner, $smartmailing_id)) == "SUCCESS") {
		$smartmailing = "ok";
		logit("debug", "add_smartemailing id= $smartmailing_id, fb_id:".$owner["fb_id"].", email:".$owner["email"]);
		dbQuery("INSERT ".$db."smartemailing SET fb_id=#1, contactlist=#2", $owner["fb_id"], $smartmailing_id);
	}

	dbQuery("UPDATE owner SET email_contact=#2 WHERE fb_id=#1", $_SESSION["user"][APLIKACE_UNIQ_ID], $email);
	return array("dbaff" => dbAff(), "smartmailing" => $smartmailing, "dbaff_owner_x_akce" => $dbaff_owner_x_akce);
}

  function isValidEmail($email) {
	return filter_var($email, FILTER_VALIDATE_EMAIL) 
	  && preg_match('/@.+\./', $email) 
	  && !preg_match('/@\[/', $email) 
	  && !preg_match('/".+@/', $email) 
	  && !preg_match('/=.+@/', $email);
  }

/**
* recursivni smazani celeho adresare
*/
function rrmdir($dir) {
  if (is_dir($dir)) {
	$objects = scandir($dir);
	foreach ($objects as $object) {
	  if ($object != "." && $object != "..") {
		if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
	  }
	}
	reset($objects);
	rmdir($dir);
  }
} 

/**
*	vraci pocet pristupu na stranku
*/
function getpristup($aplikace_id) {
	dbQuery("SELECT counter FROM pristup WHERE aplikace_id=#1", $aplikace_id);
	$row = dbArr();
	return $row["counter"] ? $row["counter"] : 0;
}

/**
*	vraci pocet zadanych emailu, resp adress (vyherci, emaily atd..)
*/
function getcountadress_nofb($aplikace_id) {
	dbQuery("SELECT count(DISTINCT(uniq_id)) FROM uzivatel_adress_nofb WHERE aplikace_id=#1", $aplikace_id);
	$row = dbArr();
	return $row[0] ? $row[0] : 0;
}

/**
*	vraci konverzi 
*/
function getkonverze($all, $konverze) {
	if($konverze < 1)
		return 0;
	return round($konverze / $all * 100) > 10 ? round($konverze / $all * 100)."%" : round($konverze / $all * 100, 2)."%";
}

/**
*	posledni krok - link na dashboard
*/
function last_or_next_step_dashboard($stav_app, $next_step = false, $bottom = false) {
	global $CONF_BASE;
	ob_start();
	// nezaplacano!
	if($stav_app["licence"] == "placena" && $stav_app["stav"] == "nezaplaceno" && $next_step) {
?>		<a href="<?=$next_step?>" class="kroky next"<?echo !$bottom ? " id=\"krok_next\"" : ""?>><?=txt("setting-next_step")?></a>
<?  }
	// zaplaceno!
	else {
?>		<a href="<?=$CONF_BASE?>" class="kroky placena next"<?echo !$bottom ? " id=\"krok_next\"" : ""?>><?=txt("setting-posledni_krok-link-dashboard")?></a>
<?	}
	return ob_get_clean();
}

/**
* nahled aplikace truhly po vyberu aplikace!
*/
function nahled_app($aplikace_typ_id)
{
	global $CONF_XTRA;
	if(!$CONF_XTRA["nahled_app_url_".$aplikace_typ_id])
		return "no_nahled";
	$height = $aplikace_typ_id == 1 ? 2050 : 1170;
	ob_start();
?>
	<div id="cont_test_app" class="cont_test_app_<?=$aplikace_typ_id?>">
		<div id="cont_test_app_content">
			<div id="cont_test_app_action" class="cont_test_app">
			</div>
			<div id="cont_test_app_frame" class="cont_test_app">
				<a id="nahled_app_fb_url" href="<?=$CONF_XTRA["nahled_app_fb_url_".$aplikace_typ_id]?>" target="_blank"><?=txt("setting-text_nahled_link_app2fb")?></a>
				<iframe id="" src="<?=$CONF_XTRA["nahled_app_url_".$aplikace_typ_id]?>" width="821" height="<?=$height?>"  style="display: block; border: 0px none; background-color: transparent; z-index: 9999; margin: 0px; padding: 0px; overflow: hidden; visibility: visible; background: #ffffff" onload="this.style.visibility='visible'" frameBorder="0" scrolling="no" seamless="seamless">
				</iframe>
			</div>
			<div id="cont_test_app_info" class="cont_test_app">
				<div id="close_nahled"></div>
				<?echo txt("setting-text_nahled_app_".$aplikace_typ_id) ?>
				<button rel="<?=$aplikace_typ_id?>"><?=txt("setting-button_nahled_app")?></button>
			</div>
			<div class="cl"></div>
		</div>
	</div>

<?
	return ob_get_clean();
}

/**
* fce zapise zmeu designu, kvuli naslednemu capture screenshot
*/
function update_design_change()
{
	dbQuery("REPLACE design_change SET aplikace_id=#1,  aplikace_typ_id=#2",$_SESSION["aplikace_id"], $_SESSION["aplikace_typ_id"]);
}

/**
* fce zapise zmeu designu, kvuli naslednemu capture screenshot
* I: frekvence v minutach
*/
function capture_screenshots($frekvence, $aplikace_ids = array())
{
	
	global $CONF_XTRA, $CONF_BASE_DIR;
	$log_file = "logs/capture.log";
	if(empty($aplikace_ids)) {
		dbQuery("?SELECT * FROM design_change WHERE `change` > DATE_SUB(NOW(),INTERVAL #1 MINUTE)", $frekvence);
	}
	else
		dbQuery("?SELECT aplikace_id, aplikace_typ_id FROM aplikace WHERE aplikace_id IN (".implode(", ", $aplikace_ids).")", $frekvence);
	while($row = dbArrTiny()) {
		pre($row);
		$aplikace_id = $row["aplikace_id"];
		$url = $CONF_XTRA["reset_app"][$row["aplikace_typ_id"]]["url"];
		if(substr($CONF_XTRA["reset_app"][$row["aplikace_typ_id"]]["url"],-8) == "trezor2/")
			$url = $CONF_XTRA["reset_app"][$row["aplikace_typ_id"]]["url"].$aplikace_id."/?aplikace_id=".$aplikace_id;
		else
			$url = $CONF_XTRA["reset_app"][$row["aplikace_typ_id"]]["url"].$aplikace_id;

		$cmd = $CONF_XTRA["wkhtmltopdf"]."  -L 0mm -R 0mm -T 0mm -B 0mm http://".substr($url, 8)."\&capture=1 --encoding UTF8 --print-media-type /tmp/capture".$row["aplikace_id"].".pdf";
		echo $cmd."<br>\n";
		logit("debug", "capture:".$row["aplikace_id"]."|".$cmd, $log_file);
		exec($cmd);
		$tmp_pdf = "/tmp/capture".$row["aplikace_id"].".pdf";
		if(empty($aplikace_ids)) {
			$cmd_convert = "/usr/bin/convert -density 288 '".$tmp_pdf."[0]' -resize 94x128 ".$CONF_BASE_DIR."users_data/".$row["aplikace_id"]."/screen_shot.jpg";
		}
		$cmd_convert_big = "/usr/bin/convert -density 288 '".$tmp_pdf."[0]' ".$CONF_BASE_DIR."users_data/".$row["aplikace_id"]."/screen_shot_big.jpg";
		echo $cmd_convert."<br>\n";
		logit("debug", "convert:".$row["aplikace_id"]."|".$cmd_convert, $log_file);
		exec($cmd_convert);
		exec($cmd_convert_big);
		nastavit_prava($tmp_pdf);
		nastavit_prava($CONF_BASE_DIR."users_data/".$row["aplikace_id"]."/screen_shot.jpg");
		copy($tmp_pdf, $CONF_BASE_DIR."users_data/".$row["aplikace_id"]."/screen_shot_big.pdf");
		nastavit_prava($CONF_BASE_DIR."users_data/".$row["aplikace_id"]."/screen_shot_big.pdf");
		nastavit_prava($CONF_BASE_DIR."users_data/".$row["aplikace_id"]."/screen_shot_big.jpg");
//		unlink($tmp_pdf);
	}
	dbQuery("DELETE FROM design_change WHERE `change` > DATE_SUB(NOW(),INTERVAL #1 MINUTE)", $frekvence + 5);
}

/**
* render progress bar 
*/
function progress_bar($steps, $step, $bottom = false)
{
	ob_start();
?>	<div class="progress step<?=$step?> steps<?=$steps?>">	
		<ul>
<?	
	for($i = 1; $i<=$steps; $i++) {
		$check = $i < $step ? '<div class="check"></div>' : "";
?>		
			<li class="p<?=$i?>"><span><a href="setapp<?=$i != 1 ? $i : "" ?>"><?=$check?><?=$i?></a></span></li>
<?		if($i<$steps) { // u posledniho kroku netisknu line
?>
			<li class="p<?=$i?> line"><span></span></li>
<?		}
	}
?>		</ul>
	</div>
<?
	return ob_get_clean();
}

/**
* zkontroluje zda jsou nastaveny vyhry!
*/
function check_set_vyhry()
{
//	dbQuery("SELECT v.vyhra_id FROM vyhry v, sada_kodu k WHERE v.aplikace_id=#1 AND k.aplikace_id=#1 AND v.vyhra_id=k.vyhra_id LIMIT 1", $_SESSION["aplikace_id"]);
	dbQuery("SELECT vyhra_id FROM vyhry WHERE aplikace_id=#1 LIMIT 1", $_SESSION["aplikace_id"]);
	if(dbRows() == 1) {
		return true;
	}
	header("location:setapp2?err=setvyhry");
	exit;
}


/**
* creates a compressed zip file
*/
function create_zip($files = array(),$destination = '',$overwrite = false) {
	//if the zip file already exists and overwrite is false, return false
	if(file_exists($destination) && !$overwrite) { return false; }
	//vars
	$valid_files = array();
	//if files were passed in...
	if(is_array($files)) {
		//cycle through each file
		foreach($files as $file) {
			//make sure the file exists
			if(file_exists($file)) {
				$valid_files[] = $file;
			}
		}
	}
	//if we have good files...
	if(count($valid_files)) {
		//create the archive
		$zip = new ZipArchive();
		if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
			return false;
		}
		//add the files
		foreach($valid_files as $file) {
//			echo $file." | ".substr($file, strrpos($file,"/") + 1)."<br>";
			// useknu cestu k souboru substr($file, strrpos($file,"/") + 1);
			$zip->addFile($file,substr($file, strrpos($file,"/") + 1));
//			$zip->addFile($file,$file);
		}
		//debug
		//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
		
		//close the zip -- done!
		$zip->close();
		
		//check to make sure the file exists
		return file_exists($destination);
	}
	else
	{
		return false;
	}
}

/**
* fce vrati pole polozek (fotografii z databaze) pro nasledny zip - pro export zip fotografii z fotosouteze v make_export_data.php	
*/
function getAllImage2Array($aplikace_id) {
	global $CONF_BASE_DIR;
	$dir = $CONF_BASE_DIR."users_data/".$aplikace_id."/soutez/";
	$photos = array();
	dbQuery("SELECT polozka FROM soutez_polozka p WHERE p.aplikace_id=#1", $aplikace_id);
	while($row = dbArr())
		$photos[] = $dir.$row["polozka"];
	return $photos;
}

function blockNahrano($class_set_nahrano)			
{
	ob_start();
?>
			<div id="vyber_nahrano" class="vyber nahrano<?=$class_set_nahrano?>">
				<h2><?=txt("setting-nahrajte_si_vlastni_obrazky")?></h2>
				<p><?=txt("setting-nahrajte_si_vlastni_obrazky-popis")?></p>
				<button id="nn"><?=txt("setting-nahrajte_si_vlastni_obrazky-submit")?></button>
				<div class="cl"></div>
				<hr>
				<div id="uploaded_img">
					<?=refreshNahrano()?>
				</div>
			</div>
<?
	return ob_get_clean();
}


/**
* vytvori formular na upload vlastnich obrazku!
*/
function picEdit()
{
	global $CONF_BASE;
	ob_start();
?>
<div id="tip_before">
	<?=txt("picedit-tiptext_before")?>
</div>
<div id="tip_after">
	<?=txt("picedit-tiptext_after")?>
</div>
<div class="close" title="<?=txt("seting-close_pop_win")?>"></div>

<form action="<?=$CONF_BASE?>picEdit-master/dist/upload.php" method="post" enctype="multipart/form-data" id="xid">

	<input type="file" name="own_image" id="thebox">

	<div id="picedit_btn">
		<button type="submit"><?=txt("setting-nahrajte_si_vlastni_obrazky-picedit_submit")?></button>
    </div>

</form>
<?
	return array("html" => ob_get_clean());
}

/**
* ulozi vlastni objekty (obrazky)!
*/
function saveOwnObjects()
{
	global $CONF_BASE, $CONF_BASE_DIR;
	if($_POST["left"])
		foreach($_POST["left"] as $k => $left )
			$values .= "(".$k.",".$_SESSION["aplikace_id"].",'".$_POST["html"][$k]."','".$_POST["img"][$k]."',".$_POST["top"][$k].",".$left."),";

	dbQuery("DELETE FROM own_block WHERE aplikace_id=#1", $_SESSION["aplikace_id"]);
	if($values)
		dbQuery("INSERT INTO own_block VALUES ".(substr($values,0,-1)));
	if(fetch_uri("del_img","p")) {
		logit("debug", "saveOwnObjects mazu: ".$CONF_BASE_DIR."users_data/".$_SESSION["aplikace_id"]."/upload_data/".fetch_uri("del_img","p"));
		@unlink($CONF_BASE_DIR."users_data/".$_SESSION["aplikace_id"]."/upload_data/".fetch_uri("del_img","p"));
	}
/*	$rs = dbQuery("SELECT block_id, img FROM own_block WHERE aplikace_id=#1", $_SESSION["aplikace_id"]);
	while($row = dbArr()) {
		if(!is_file($CONF_BASE_DIR."users_data/".$_SESSION["aplikace_id"]."/upload_data/".$row["img"]))
			dbQuery("DELETE FROM own_block WHERE block_id=#2 AND aplikace_id=#1", $_SESSION["aplikace_id"], $row["block_id"]);
			
	}
*/	
	// kontrola zda jsou soubory na disku a kdyz ne smaznu i z adabaze!


	return array("dbaff" => dbAff());
}


/**
* fce
*	1) zkopiruje prave uploadovany vlastni obrazek do ostatnich aplikaci
*   2) k prave zalozene aplikaci zkopiruje vsechny obrazky s ostatnich aplikaci uzivatele
*/
function copyOwnPhotos2AllApps($aplikace_id, $filename = false)
{
	global $CONF_BASE_DIR;
	$dest_dir_new_app = $CONF_BASE_DIR."users_data/".$aplikace_id."/upload_data/";
	@mkdir($dest_dir_new_app,0777);
	// zkopiruju do vsech jeho aplikaci
	dbQuery("SELECT owner_id FROM owner_x_app WHERE aplikace_id=#1", $aplikace_id);
	$row_owner_id = dbArr();
	dbQuery("SELECT aplikace_id FROM owner_x_app WHERE owner_id=#1 AND aplikace_id<>#2", $row_owner_id["owner_id"], $aplikace_id);
	while($row = dbArr()) {
		$dest_dir = $CONF_BASE_DIR."users_data/".$row["aplikace_id"]."/upload_data/";
//		logit("debug","copy($dest_file, $dest_dir)");
		@mkdir($dest_dir,0777);

		// 1. kopirovani prave uploadovaneho obrazku:
		if($filename) {
			$dest_file = $dest_dir_new_app.$filename;
			if(!copy($dest_file, $dest_dir.$filename))
				logit("debug","failed to app ".$row["aplikace_id"]." copy ".$dest_dir.$filename);
			else
				logit("debug","success to app ".$row["aplikace_id"]." copy ".$dest_dir.$filename);
		}
		else {
			$files = scandir($dest_dir);
			foreach ($files as $file) {
				if(substr($file,0,1) == ".") continue;
				if(copy($dest_dir.$file, $dest_dir_new_app.$file))
					logit("debug","copy success to new_app ".$row["aplikace_id"].":".$dest_dir.$file.",".$dest_dir_new_app.$file);
				else
					logit("debug","copy failed to new_app ".$row["aplikace_id"].":".$dest_dir.$file.",".$dest_dir_new_app.$file);
			}
		}
	}
}

// fce getOwnObjects v inc/global_fce.php!

function showGratulace()
{
	global $CONF_XTRA;
	// pro premium platbu neukazuji gratulaci s anim gifem!
	if(strpos($_SERVER["HTTP_REFERER"],"premium")) return array("typ" => "premium");
	ob_start();
	if($_SESSION["paid_aplikace_id"]) {
		dbQuery("SELECT app_short_code FROM aplikace WHERE aplikace_id=#1", $_SESSION["paid_aplikace_id"]);
		$data = dbArr();
		ob_start();
?>		
		<p><?=txt("setting-pop_okno-gratulace_aplikace_text_share")?></p>		
		<div class="link_short_share">
			<?=txt("dashboard-link_short_share")?> <input type="text" onClick="this.select();" value="<?=$CONF_XTRA["SHORT_HOST"]?>/<?=$data["app_short_code"]?>">
			<a href="http://<?=$CONF_XTRA["SHORT_HOST"]?>/<?=$data["app_short_code"]?>" onclick="return openAWin(this.href, 1200, 800, event, '_blank', 1, 1, 1);"></a>
		</div>
<?		$share = ob_get_clean();

	}
?>	<div id="PopGratulace" class="PopWin PopWinWhite">
 		<div class="close" title="<?=txt("seting-close_pop_win")?>"></div>
		<p class="title"><?=txt("setting-pop_okno-gratulace_aplikace_done")?></p>		
		<div><?=anim_gif_showGratulace()?></div>
		<? echo $share?>
	</div>
<?
	return array("html" => ob_get_clean());
}

function anim_gif_showGratulace()
{
	return "<img src=\"img/animgif/giphy".rand (1,8).".gif\">";
}

/**
* formular na zobrazeni ON DEMAND "academy premium" platebniho formulare
*/
function show_on_demand_academy($what = "premium_academy", $amount = 1, $polozka = 'Členství v online programu Studio x51 academy PREMIUM + zkušební verze SocialSprinters - 1 Kč včetně DPH', $podminky)
{
	global $CONF_XTRA, $CONF, $CONF_BASE;
	$getAppInfo = getAppInfo($aplikace_id);
	if(fetch_uri("action","g") == "gopay" && fetch_uri("id","g")) {
		$tdo = date("d.m.Y", $getAppInfo["tdo"]);
		$typ_platby = $getAppInfo["typ_platby"];
		$delka_trvani = $getAppInfo["delka_trvani"];
	}
	$aplikace_typ_id = $getAppInfo["aplikace_typ_id"];
		
	ob_start();
?>	
	<div id="PopPlatba" class="PopWin PopWinWhite set_fakturace academy_platba hura<?echo fetch_uri("paid","g") == "success" ? " schovat" : ""?>">
		<?
/*
		echo "<p>cookie=".$_COOKIE["_ssuser"]."</p>"; 
		echo "<p>".date("Y-m-d h:i:s", $_COOKIE["_ssuser"] / 1000)."</p>";
*/		
		?>
		<form id="form_by_what_premium" class="form_odberatel">
		<input type="hidden" name="aplikace_id" value="0" id="aplikace_id" />
		<input type="hidden" name="amount" id="amount" value="<?=$amount?>" rel="<?=$amount?>" />
		<input type="hidden" name="amount_together" id="amount_together" value="<?= $CONF_XTRA["premium_cena_mesic"] * $CONF_XTRA["premium_delka_trvani"]?>" />
		<input type="hidden" name="typ_platby" value="ON_DEMAND" />
		<input type="hidden" name="what" value="<?=$what?>" />
		<input type="hidden" name="type" value="setFakturace" />
<?		forms_inputs_odberatel($_SESSION["user"][APLIKACE_UNIQ_ID]); ?>
<?		if(fetch_uri("paid","g") == "success") {
			unset($_SESSION["xtra_premium"]);
?>			<p class="title"><?=txt("setting-platba_description-ss_premium_members-title-gratulace")?></p>		
			<button id="godashboard" rel="premium"><?=txt("setting-platba_description-ss_premium_members-button_vstup")?></button>
<?		}
			// 3. parametr fce Login: url_new = "on_demand"; pro presmerovani na on_demand stranku!
?>		
<!--
			<button id="bt_login" class="login" rel="premium" onclick="Login('<?=$CONF["scope"]?>', '<?=session_id()?>', 'on_demand_academy'); return false;" type="submit">with login <?=txt("setting-academy_upis-platba_login-provest_platbu")?></button>
-->		
		<div class="polozky">
			<h3>
				Položky a ceny
			</h3>
			<div>
				  <input type="checkbox" disabled="disabled" checked="checked">
				  <label for="frm-variants-225114"><?=$polozka?></label>
			</div>
			<h3>Způsob platby</h3>
			<div>
				<label for="frm-payMethod-credit_card">
				<input type="radio" name="payMethod"  disabled="disabled" checked="checked" value="credit_card">Online platební karta (ihned)
				<img src="https://form.fapi.cz/images/icons-payment-card.png?v=2" width="231" height="15">
			</div>
			<h3>Obchodní podmínky</h3>
			<div>
				<input type="checkbox" id="souhlas_podminky" rel="y" placeholder="<?=txt("form_check-err_musite_souhlas_s_obch_podminkami")?>">
<!--
Musíte souhlasit s obchodními podmínkami
-->
				<label for="souhlas_podminky">Souhlasím s <a href="<?=$podminky?>" target="_blank">obchodními podmínkami</a>.</label>
			</div>
		</div>

		<button id="bt_login" class="login" rel="premium" type="submit"><?=txt("setting-academy_upis-platba_login-provest_platbu")?></button>

		</form>
		<div id="gateWayPaypal"><?echo gateWayPaypalEmpty()?></div>
		
	</div>
<?
	return ob_get_clean();
}



/**
* formular na zobrazeni ON DEMAND platebniho formulare
*/
function show_on_demand_platba($popup = false)
{
	global $CONF_XTRA, $CONF, $CONF_BASE;
	$getAppInfo = getAppInfo($aplikace_id);
	if(fetch_uri("action","g") == "gopay" && fetch_uri("id","g")) {
		$tdo = date("d.m.Y", $getAppInfo["tdo"]);
		$typ_platby = $getAppInfo["typ_platby"];
		$delka_trvani = $getAppInfo["delka_trvani"];
	}
	$aplikace_typ_id = $getAppInfo["aplikace_typ_id"];
		
	ob_start();
?>	
	<div id="PopPlatba" class="PopWin PopWinWhite hura premium_platba<?echo fetch_uri("paid","g") == "success" ? " schovat" : ""?>">
		<?
/*
		echo "<p>cookie=".$_COOKIE["_ssuser"]."</p>"; 
		echo "<p>".date("Y-m-d h:i:s", $_COOKIE["_ssuser"] / 1000)."</p>";
*/		
		?>
		<form>
		<input type="hidden" name="aplikace_id" value="0" id="aplikace_id" />
		<input type="hidden" name="amount" id="amount" value="1" />
		<input type="hidden" name="amount_together" id="amount_together" value="<?= $CONF_XTRA["premium_cena_mesic"] * $CONF_XTRA["premium_delka_trvani"]?>" />
		<input type="hidden" name="typ_platby" value="ON_DEMAND" />
		<input type="hidden" name="what" value="premium_academy" />
<?		if(fetch_uri("paid","g") == "success") {
			unset($_SESSION["xtra_premium"]);
?>			<p class="title"><?=txt("setting-platba_description-ss_premium_members-title-gratulace")?></p>		
			<button id="godashboard" rel="premium"><?=txt("setting-platba_description-ss_premium_members-button_vstup")?></button>
<?		}
		elseif(!$_SESSION["user"][APLIKACE_UNIQ_ID]) {
			// 3. parametr fce Login: url_new = "on_demand"; pro presmerovani na on_demand stranku!
?>			<div id="academy_info">
				Členství v online programu Studio x51 academy PREMIUM + zkušební verze SocialSprinters - 1 Kč včetně DPH
			</div>		
			<button class="login" rel="premium" onclick="Login('<?=$CONF["scope"]?>', '<?=session_id()?>', 'on_demand'); return false;" type="submit"><?=txt("setting-platba_login-provest_platbu")?></button>
<?		}

		else {
			$OwnerData = OwnerData();
?>			<div id="academy_info">
				Členství v online programu Studio x51 academy PREMIUM + zkušební verze SocialSprinters - 1 Kč včetně DPH
			</div>		
			<label for="email"><?=txt("setting-platba_description-ss_premium_members-label-zkotrolujte_si_email")?></label>
			<input type="text" class="text" id="email" name="email" placeholder="<?=txt("setting-adress_email")?>" value="<?=$OwnerData["email"]?>"/>

			<button id="setPayment" rel="premium_academy"><?=txt("setting-platba_provest_platbu")?></button>
<?		}
?>
		</form>
		<div id="gateWayPaypal"><?echo gateWayPaypalEmpty()?></div>
		
	</div>
	<script><?
	if($_SESSION["user"][APLIKACE_UNIQ_ID]) { 	
?>		$("#setPayment").show(); <?
	} 
?>	</script>

<?
	return ob_get_clean();
}




?>
