<?
/**
* vybere jazyk aplikace
*/
function get_lang()
{
	static $LANG;
	if($LANG[APLIKACE_ID]) return $LANG[APLIKACE_ID];

	dbQuery("SELECT lang FROM owner o, owner_x_app oa WHERE owner_id=fb_id AND aplikace_id=#1", APLIKACE_ID);
	$row = dbArr();
//	echo $row["lang"];
	return $LANG[APLIKACE_ID] = $row["lang"];
}

/**
* vrati FB request
*/
function getSignedRequest(){
//	pre($_REQUEST, "getSignedRequest");
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
		if(defined("APLIKACE_ID"))
			$_SESSION["signed_request"][APLIKACE_ID] = $obj;
  //	  echo("Your App Data: " . $obj['app_data']);
		return($obj);
	}
	else
	{
		return "";
	}
}


/**
* naloaduje js files 
*/
function jsFiles() {
	global $CONF_XTRA, $CONF_BASE_SSP;
	$CONF = setAppConfig(APLIKACE_ID);
	ob_start();
?>
<script>
var url_ssp = '<?echo $CONF_BASE_SSP?>';
</script>
<?

	foreach($CONF_XTRA["JS_FILES"] as $file) {
		if($file) {
			?><script type="text/javascript" src="<?=$file?>?time=<?=$CONF_XTRA["TIME_FILES"]?>"></script>
<?		}
	}
	foreach($CONF_XTRA["JS_FILES_GLOBAL"] as $file) {
		if($file) {
			?><script type="text/javascript" src="<?=$CONF_BASE_SSP.$file?>?time=<?=$CONF_XTRA["TIME_FILES"]?>"></script>
<?		}
	}

	// JS snezeni - snow flakes - prozatim vypnuto
//	echo snowJS($CONF["snow"]);
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


function getAppToken($application_id, $application_secret) {

	$token_url = "https://graph.facebook.com/oauth/access_token?" .
		"client_id=" . $application_id .
		"&client_secret=" . $application_secret .
		"&grant_type=client_credentials";
	//$app_token = file_get_contents($token_url);
//	echo file_get_contents($token_url);
	$accessToken = explode('=', file_get_contents($token_url));
//		pre($accessToken,"accessToken data");
	return $accessToken[1];
}

/**
* ulozi informaci o sdileni na FB
*/
function saveFbShare() {
	dbQuery("UPDATE uz_polozka SET hodnota=1 WHERE aplikace_id=#1 AND fb_id=#2 AND polozka_id='share'",
		$_SESSION["aplikace_id"], $_SESSION["user"][$_SESSION["aplikace_id"]]); // vyhra pouze 1 nebo na
	$dbaff = dbAff();
	if($dbaff == 1) {
		$_SESSION["dalsi_pokus"] = true;
		return array("dbaff" => $dbaff, "dalsi_pokus" => "1");
	}
	return array("dbaff" => $dbaff);
}

/**
* logovaci fce do vsech aplikaci krome administarce SS (v SS Login2app)
*/
function Login2appAll()
{
	logit("debug","LOGIN: uzivatel ".fetch_uri("user","g").", ".serialize($_GET));
	dbQuery("SELECT * FROM uzivatel WHERE fb_id=#1",fetch_uri("user","g"));
	if(dbRows() == 1) {
		if(fetch_uri("user","g") == "undefined") {
			logit("debug","fce Login2appAll: user undefined");
//			echo json_encode(array("session" => "expired"));
			unset($_SESSION["user"]);
//			session_destroy();
//			sleep(15);
			return json_encode(array("redirect" => "redirect", "user" => "undefined", "session_id" => session_id()));
		}

		$redirect = false;
		$u = $_SESSION["user"][APLIKACE_ID];
		$a = APLIKACE_ID;
		if(!$_SESSION["user"][APLIKACE_ID] || $_SESSION["user"][APLIKACE_ID] < 1000) {
			$redirect = true;
		}

		// nastavim, extra prava  (pro x51adminy)
		set_privileges(fetch_uri("email","g"));

		// update uctu po znovu nalogovani
		dbQuery("UPDATE uzivatel SET jmeno=#2,prijmeni=#3,pohlavi=#4,email=#5, aplikace_id=#6 WHERE fb_id=#1",
			fetch_uri("user","g"), // 1
			fetch_uri("firstname","g"), // 2
			fetch_uri("lastname","g"), // 3
			fetch_uri("gender","g"), // 4
			fetch_uri("email","g"), //5
			APLIKACE_ID //6
	//		fetch_uri("","g"),
		);
		// krome addtab.php (pridani na facebook)
		
		if(fetch_uri("addtab","g") != "addtab" && !strpos(session_id(),fetch_uri("user","g"))) {
			$redirect = true;
			session_regenator();
			logit("debug","LOGIN: session_regenerate_id:".session_id());
		}
		unset($_SESSION["user"]);
		
		$_SESSION["user_logged"][APLIKACE_ID] = fetch_uri("user","g");
		$_SESSION["user"][APLIKACE_ID] = fetch_uri("user","g");
		$_SESSION["timer"][APLIKACE_ID] = time();
		logit("debug", "LOGIN: UPDATE dbAff=".dbAff()."fb_id=".fetch_uri("user","g")."|sessionid=".session_id()."|sessionid_pred=".$u);
		// nastavim presmerovani!
		if($redirect) {
			return json_encode(array("redirect" => "redirect", "user" => $_SESSION["user"][APLIKACE_ID], "aplikace_id" => $a, "us pred" => $u, "session_id" => session_id()));
		}
		return json_encode(array("login" => "update", "user" => $_SESSION["user"][APLIKACE_ID], "session_id" => session_id()));
	}
	else {
		// insert noveho 
		dbQuery("INSERT uzivatel SET fb_id=#1,jmeno=#2,prijmeni=#3,pohlavi=#4,email=#5, aplikace_id=#6",
			fetch_uri("user","g"), // 1
			fetch_uri("firstname","g"), // 2
			fetch_uri("lastname","g"), // 3
			fetch_uri("gender","g"), // 4
			fetch_uri("email","g"), //5
			APLIKACE_ID //6
	/*		fetch_uri("","g"),
			fetch_uri("","g"),
			fetch_uri("","g"),
			fetch_uri("","g"),
			fetch_uri("","g"),
			fetch_uri("","g")
	*/
		);
		// krome addtab.php (pridani na facebook)

		if(fetch_uri("addtab","g") != "addtab" && !strpos(session_id(),fetch_uri("user","g"))) {
			$redirect = true;
			session_regenator();
			logit("debug","LOGIN: session_regenerate_id:".session_id());
		}

		unset($_SESSION["user"]);
		$_SESSION["user_logged"][APLIKACE_ID] = fetch_uri("user","g");
		$_SESSION["user"][APLIKACE_ID] = fetch_uri("user","g");
		logit("debug", "INSERT dbAff=".dbAff());

		// zalozim uzivatelske polozky!
		dbQuery("SELECT * FROM polozka p, uz_polozka up WHERE p.aplikace_id=#1 AND up.aplikace_id=#1 AND fb_id=#2 AND p.polozka_id=up.polozka_id", APLIKACE_ID, $_SESSION["user"][APLIKACE_ID]);
		logit("debug","TEST POLOZEK: uzivatel ".$_SESSION["user"][APLIKACE_ID].", pocet polozek: ".dbRows());
		// zalozim ucet s polozkami :-)
		if(dbRows() == 0) {
			dbQuery("SELECT * FROM polozka WHERE aplikace_id=#1 ORDER BY polozka_id", APLIKACE_ID);
			$new_rows = "";
			while($row = dbArr()) {
				if($row["hodnota"] == "uniqid") $row["hodnota"] = uniqid();
				$new_rows .= "('".$row["polozka_id"]."','".APLIKACE_ID."','".$_SESSION["user"][APLIKACE_ID]."','".$row["hodnota"]."',NULL),";
			}
			logit("debug","create_polozky:");
			dbQuery("INSERT uz_polozka VALUES ".substr($new_rows, 0, -1));
		}

		// nastavim liked po 1. pristupu!
		dbQuery("REPLACE uz_polozka SET polozka_id=#1, aplikace_id=#2, fb_id=#3, hodnota=#4","like", APLIKACE_ID, $_SESSION["user"][APLIKACE_ID], 1);
		return json_encode(array("redirect" => "redirect", "user" => $_SESSION["user"][APLIKACE_ID], "login" => "new_user", "session_id" => session_id()));
	}

	// nastavim SESSION["user"] a ulozim do ni uzivatele!
//		if(dbAff() >= 0)
}

function getPageName($page_id, $fce = false) {
	$config['callback_url']         =   'https://x51.cz/apps/ssp-zalozka/group/?appid=1431307337166328&fbTrue=true';

	$getAppInfo = getAppInfo($_SESSION["aplikace_id"]);
	logit("debug","getAppInfo:".serialize($getAppInfo)."|".trim($getAppInfo['app_id'])."|");
	
	$graph_url= "https://graph.facebook.com/".$page_id."?fields=id,name,link,picture&access_token=".getAppToken(trim($getAppInfo['app_id']),trim($getAppInfo['app_secret']));

	$response = file_get_contents($graph_url);   // get access token from url

	$params = null;
//	parse_str($response, $params);
	$dataobj = json_decode($response);
	return $dataobj;
//	pre($dataobj, $dataobj->id."<br>".$dataobj->picture->data->url);
	
	
/*
//	$access_token =$getAppInfo['app_id']."|".$getAppInfo['app_secret'];
//	$graph_url= "https://graph.facebook.com/".$page_id;
//	$graph_url= "https://graph.facebook.com/v2.2/me?fields=id,name";
	$graph_url= $token_url;
//	$postData = "?access_token=" .$access_token;

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $graph_url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST, 1);
//	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	$output = curl_exec($ch);
	echo $output;
	curl_close($ch);
*/	
}


?>
