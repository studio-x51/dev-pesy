<?
if(isset($_GET["session_id"]) && $_GET["session_id"] != "undefined") session_id($_GET["session_id"]);
if(isset($_POST["session_id"]) && $_POST["session_id"] != "undefined") session_id($_POST["session_id"]);

//ini_set( "max_input_vars" , "12000");

//	test zda je jiz pustena session (pokud prichazim z home page www.socialsprinters.cz)
if(function_exists("session_status") && session_status() == PHP_SESSION_NONE) {
  session_start();
} elseif(!function_exists("session_status")) {
  session_start();
}

//header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');

//require_once("config.php"); // soubor inc/config.php automaticky generovano pri configuraci nove aplikace!  
require_once("siteconf.php");
require_once("common.php");
require_once("db_test.php");
require_once("fce.php");


// globalni fce, pouzivane i v samotnych aplikacich!
require_once("global_fce.php");
require_once("gopay_fce.php");
require_once("global_parameters.php");
require_once($CONF_BASE_DIR."smartemailing/base.php");
require_once($CONF_BASE_DIR."smartemailing/add_premium.php");


if((isset($_GET["action"]) && $_GET["action"] == "logout") || ( $_SERVER["HTTP_HOST"] != "sprinte.rs" && !isset($_SESSION["access_grant"]) && !strpos($_SERVER["SCRIPT_NAME"], "vstup.php") && $_SERVER["SCRIPT_FILENAME"] !=  "/web/www.socialsprinters.cz/index.php" && !strpos($_SERVER["SCRIPT_NAME"], "test.php") && !strpos($_SERVER["SCRIPT_NAME"], "actions.php"))) {
	if(fetch_uri("action","g") == "logout") {
		session_destroy();
		header("location:".$CONF_BASE);
		exit;
	}
//if($_SERVER["HTTP_HOST"] != "sprinte.rs" && !isset($_SESSION["access_grant"]) && !strpos($_SERVER["SCRIPT_NAME"], "vstup.php") && !strpos($_SERVER["SCRIPT_NAME"], "test.php")) {
}

//echo "http://graph.facebook.com/".$_SESSION["user"][APLIKACE_UNIQ_ID]."/picture?width=50&height=50";
if($_SESSION["user"][APLIKACE_UNIQ_ID] && !is_file("./fb_photos/50x50/". $_SESSION["user"][APLIKACE_UNIQ_ID] .".jpg")) {
	@copy("http://graph.facebook.com/".$_SESSION["user"][APLIKACE_UNIQ_ID]."/picture?width=50&height=50", $CONF_BASE_DIR."fb_photos/50x50/".$_SESSION["user"][APLIKACE_UNIQ_ID].".jpg");
	nastavit_prava($CONF_BASE_DIR."fb_photos/50x50/". $_SESSION["user"][APLIKACE_UNIQ_ID] .".jpg");
}
?>