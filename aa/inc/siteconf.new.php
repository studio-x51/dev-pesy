<?
// funguje pouze z moji IP adresy 84.42.152.67 - nastavim: $_SESSION["user"][APLIKACE_UNIQ_ID] = $FBprevlek -> v index.php!!!
// pokud nastavim $FBprevlek zrusim tim i fci mujpc()

//$FBprevlek = 1268201656530687;
//$FBprevlek = 463799547124918; // Luděk Štvrtecký
//$FBprevlek = 436801403170316; // radek ploc
//$FBprevlek = 10204996388853280; // Branko Černý, Branko.cerny@vinnysklep.cz
//$FBprevlek = 10208192168791878; // Hloušek Jan, m-zavlaha@seznam.cz
//$FBprevlek = 10204210890947604; // Jozef Spodniak
//$FBprevlek = 960978793983962; // Jozef  Maxo
//$FBprevlek = 474044712803807; // Marketa Svobodova
//$FBprevlek = 10204648498231737; // Ing. Miroslava Kardošová - Krásne Bruška		
//$FBprevlek = 228024130873700; // Domovinka Plzenska, hessy@post.cz 
//$FBprevlek = 1055173974500140; // Premium - mooverr@seznam.cz Tomas Haskovec
//$FBprevlek = 10204210890947604; // Premium - Spodniak Jozef
//$FBprevlek = 	938211519598795; // Premium - Zavázalová Lenka
//$FBprevlek = 10204956614268495; // X51 - Michal Tolinger
//$FBprevlek = 10207845182399049; // Katka Feix


$CONF_NAME_SQL = "socialsp2";
// PRO DEBUG TEST VERZE!


//if(strpos($_SERVER["HTTP_HOST"], "stefi") === false && ($_SERVER["REMOTE_ADDR"] == "87.249.153.140" || $_SERVER["REMOTE_ADDR"] == "84.42.152.67")) {
if(strpos($_SERVER["SCRIPT_NAME"], "aa-test") ) {
	$CONF_NAME_SQL = "socialsp2_test";
}	

$CONF_USER_SQL = "socialsp";
$CONF_PWD_SQL = "fhahfkg";
$CONF_MYSQL = array("h"=>"127.0.0.1","p"=>$CONF_PWD_SQL,"u"=>$CONF_USER_SQL,"d"=>$CONF_NAME_SQL);

$CONF_STATIC = array();

define("APLIKACE_UNIQ_ID","SocialSprinters");

// premium - platba na pocet mesicu
$CONF_XTRA["premium_delka_trvani"] = 120; // 120 mesicu (10 let)
$CONF_XTRA["premium_cena_mesic"] = 590; // cena za mesic!


if(isset($_GET["aplikace_id"])) {
    define("APLIKACE_ID", $_GET["aplikace_id"]);
}	

/*
adresare :
mkdir fb_photos/
mkdir fb_photos/50x50/
chmod o+rw fb_photos/ fb_photos/50x50/

users_data/{aplikace_id} ...
fb_photos/50x50/fb_user_id.jpg

*/



// TEST pro http://socialsprinters.cz test 
$CONF_STATIC["gopay-Client_ID"] = 1317115481;
$CONF_STATIC["gopay-Client_secret"] = "cvy27sbf";
$CONF_STATIC["gopay-GoID"] = 8529241259;
$CONF_STATIC["gopay-payment_url"] = "https://testgw.gopay.cz/api/payments/payment"; // u platebni brany + id platby!!!
$CONF_STATIC["gopay-torent_url"] = "https://testgw.gopay.cz/api/oauth2/token";
$CONF_STATIC["gopay-js_embed"] = "https://testgw.gopay.cz/gp-gw/js/embed.js";
// / TEST pro http://socialsprinters.cz test 

// TEST pro http://socialsprinters.com 
$CONF_STATIC["gopay-Client_ID"] = 1459103822;
$CONF_STATIC["gopay-Client_secret"] = "W6TsNQ3z";
$CONF_STATIC["gopay-GoID"] = 8802855006;
$CONF_STATIC["gopay-payment_url"] = "https://testgw.gopay.cz/api/payments/payment"; // u platebni brany + id platby!!!
$CONF_STATIC["gopay-torent_url"] = "https://testgw.gopay.cz/api/oauth2/token";
$CONF_STATIC["gopay-js_embed"] = "https://testgw.gopay.cz/gp-gw/js/embed.js";
// /TEST pro http://socialsprinters.com 


$CONF_XTRA["x51admin"] = array(
	"nina" => 760217254111596,
	"matej" => 1556933147910486,
	);

//$CONF_BASE_DIR_APP = array();
// OSTRA na SSL! https://x51.cz/apps/socialsprinters/
// pro SSL verzi umistenou na https://x51.cz/apps/socialsprinters/ (/web/x51.cz/apps/socialsprinters/) [socialsprinters s jednim s]!!!
// POZOR: SAMOSTANY ADRESAR NUTNO AKTUALIZOVAT!!!
if(strpos($_SERVER["PHP_SELF"], "apps/socialsprinters/") !== false)  {
	$CONF_BASE_DIR = $CONF_BASE_SSP_DIR = "/web/x51.cz/apps/socialsprinters/"; // samostany adresar, take nutno aktualizovat!!!
	$CONF_BASE = $CONF_BASE_SSP = "https://x51.cz/apps/socialsprinters/";
	$CONF_BASE_HOME = "http://www.socialsprinters.cz/";
	$CONF_STATIC["app_id"] = '892552947456114';
	$CONF_STATIC["app_secret"] = '5ce56e10c46e7543889e230c96ac9ad2';
	$CONF_XTRA["wkhtmltopdf"] = "/web/www.socialsprinters.cz/aa/wkhtmltopdf-amd64";

	// pro muj pc stale testovaci platby!
	if($_SERVER["REMOTE_ADDR"] != "84.42.152.67") {
		// http://socialsprinters.cz OSTRA!!! 
		$CONF_STATIC["gopay-Client_ID"] = 1742898652; // nedodano asi!!!
		$CONF_STATIC["gopay-Client_secret"] = "P9dcpXSY";
		$CONF_STATIC["gopay-GoID"] = 8599107396; // CZK , EUR
		$CONF_STATIC["gopay-payment_url"] = "https://gate.gopay.cz/api/payments/payment"; // u platebni brany + id platby!!!
		$CONF_STATIC["gopay-torent_url"] = "https://gate.gopay.cz/api/oauth2/token";
		$CONF_STATIC["gopay-js_embed"] = "https://gate.gopay.cz/gp-gw/js/embed.js";
	}	
	
	// http://socialsprinters.cz 
}
// TESTOVACI VERZE http://www.socialsprinters.cz/aa-test/
elseif(strpos($_SERVER["PHP_SELF"], "aa-test") !== false)  {
	$CONF_BASE_DIR = $CONF_BASE_SSP_DIR = "/web/www.socialsprinters.cz/aa-test/";
//	$CONF_BASE_SSP_DIR = "/web/x51.cz/apps/socialssprinters/"; // SYMLINK na /web/www.socialsprinters.cz/aa/ !!!
	$CONF_BASE_SSP = "https://x51.cz/apps/socialssprinters-test/"; // SSL verze
	$CONF_BASE = "http://".$_SERVER["HTTP_HOST"]."/aa-test/";
	$CONF_BASE_HOME = "http://".$_SERVER["HTTP_HOST"]."/";
	$CONF_STATIC["app_id"] = '892552947456114';
	$CONF_STATIC["app_secret"] = '5ce56e10c46e7543889e230c96ac9ad2';
	$CONF_XTRA["wkhtmltopdf"] = "/web/www.socialsprinters.cz/aa-test/wkhtmltopdf-amd64";
	
	// pro muj pc stale testovaci platby!
//	if($_SERVER["REMOTE_ADDR"] != "84.42.152.67")  
	// $_SERVER["REMOTE_ADDR"] 52.28.11.107 => ip sandbox gopay
	if(!$_SESSION["x51admin"] && $_SERVER["REMOTE_ADDR"] != "52.28.11.107") {
		// http://socialsprinters.cz OSTRA!!! 
		$CONF_STATIC["gopay-Client_ID"] = 1742898652; // nedodano asi!!!
		$CONF_STATIC["gopay-Client_secret"] = "P9dcpXSY";
		$CONF_STATIC["gopay-GoID"] = 8599107396; // CZK , EUR
		$CONF_STATIC["gopay-payment_url"] = "https://gate.gopay.cz/api/payments/payment"; // u platebni brany + id platby!!!
		$CONF_STATIC["gopay-torent_url"] = "https://gate.gopay.cz/api/oauth2/token";
		$CONF_STATIC["gopay-js_embed"] = "https://gate.gopay.cz/gp-gw/js/embed.js";
	}	
	
	// http://socialsprinters.cz 
}


// STANDARDNI OSTRA VERZE www.socialsprinters.cz/aa/
// sprinte.rs
// a pro https://x51.cz/apps/socialssprinters/
elseif(strpos($_SERVER["HTTP_HOST"], "socialsprinters") !== false || strpos($_SERVER["HTTP_HOST"], "sprinte.rs") !== false || strpos($_SERVER["SCRIPT_NAME"], "socialssprinters") !== false )  {
	$CONF_BASE_DIR = $CONF_BASE_SSP_DIR = "/web/www.socialsprinters.cz/aa/";
//	$CONF_BASE_SSP_DIR = "/web/x51.cz/apps/socialssprinters/"; // SYMLINK na /web/www.socialsprinters.cz/aa/ !!!
	$CONF_BASE_SSP = "https://x51.cz/apps/socialssprinters/"; // SSL verze
	$CONF_BASE = "http://".$_SERVER["HTTP_HOST"]."/aa/";
	$CONF_BASE_HOME = "http://".$_SERVER["HTTP_HOST"]."/";
	$CONF_STATIC["app_id"] = '892552947456114';
	$CONF_STATIC["app_secret"] = '5ce56e10c46e7543889e230c96ac9ad2';
	$CONF_XTRA["wkhtmltopdf"] = "/web/www.socialsprinters.cz/aa/wkhtmltopdf-amd64";

	// pro muj pc stale testovaci platby!
//	if($_SERVER["REMOTE_ADDR"] != "84.42.152.67") {
		// http://socialsprinters.cz OSTRA!!! 
		$CONF_STATIC["gopay-Client_ID"] = 1742898652; // nedodano asi!!!
		$CONF_STATIC["gopay-Client_secret"] = "P9dcpXSY";
		$CONF_STATIC["gopay-GoID"] = 8599107396; // CZK , EUR
		$CONF_STATIC["gopay-payment_url"] = "https://gate.gopay.cz/api/payments/payment"; // u platebni brany + id platby!!!
		$CONF_STATIC["gopay-torent_url"] = "https://gate.gopay.cz/api/oauth2/token";
		$CONF_STATIC["gopay-js_embed"] = "https://gate.gopay.cz/gp-gw/js/embed.js";
//	}

	// http://socialsprinters.cz 
}
// TESTOVACI VERZE na eax.cz
elseif(strpos($_SERVER["HTTP_HOST"], "stefi") !== false)  {
	$CONF_BASE_SSP_DIR = $CONF_BASE_DIR = "/web/stefi.pub.cz/ssp/";
	$CONF_BASE_HOME = "http://www.socialsprinters.cz/";
	$CONF_BASE = $CONF_BASE_SSP = "http://stefi.pub.cz/ssp/";
	$CONF_STATIC["app_id"] = '1546102739004012';
	$CONF_XTRA["wkhtmltopdf"] = "/web/secure.ccl.cz/wkhtmltopdf-amd64";
}
else {
	$CONF_BASE_SSP_DIR = $CONF_BASE_DIR = "/web/x51.cz/apps/ssp/";
	$CONF_BASE = $CONF_BASE_SSP = "https://x51.cz/apps/ssp/";
	$CONF_BASE_HOME = "http://www.socialsprinters.cz/";
	$CONF_STATIC["app_id"] = '1542373226043630';
}
$CONF_STATIC["canvas"] = "";
$CONF_STATIC["og:image"] = "img/shareimg2.png";
$CONF_STATIC["scope"] = "email";
$CONF_STATIC["kod"] = 4; // 4 mistny kod!
//$CONF_STATIC["ceny"][2] = 2500; // cena v Kc aplikace Trezor / 1 mesic!
$CONF_STATIC["logs"]["gopay_notify"] = $CONF_BASE_DIR."logs/gopay_notify.log"; // GOPAY notikace!
$CONF_STATIC["logs"]["gopay_notify_state"] = $CONF_BASE_DIR."logs/gopay_notify_state.log"; // GOPAY notikace!

$CONF_STATIC["og:url"] = $CONF_STATIC["canvas"] = $CONF_BASE;




//$CONF_STATIC["gopay-return_url"] = $CONF_BASE."gopay.php?ret=d";
//$CONF_STATIC["gopay-return_url"] = $_SERVER["SCRIPT_URI"]."?ret=gopay";
$CONF_STATIC["gopay-notification_url"] = $CONF_BASE."gopay_notify.php";


$CONF_DEBUG = "debug"; //"debug","info","warning","error"

$CONF_XTRA["1_APLIKACE_FREE"] = array("4"); // zatim jen jedna aplikace "zalozka"!

if(isset($_GET["aplikace_typ_id_control"])) {
	$CONF_XTRA["aplikace_typ_id"] = $_GET["aplikace_typ_id_control"]; // vezmu z query string, poreseno v .htaccess (NEVYRESENO!!! ASI VYRESENO DALE, (funguje oboji ;-) resim dale z URL!!!)
}

/* zobnu si aplikace_typ_id z url */
$url_bez_con_base = substr($_SERVER["SCRIPT_FILENAME"], strlen($CONF_BASE_DIR));
$CONF_XTRA["aplikace_typ_id"] = substr($url_bez_con_base, 0, strpos($url_bez_con_base,"/"));
/* /zobnu si aplikace_typ_id z url */

/* smartemailing */

$CONF_XTRA["smartemailing_token"] = 'TApH2gLh2cKKf00ehlcAFPMHZ6w1OpjocvYXCeDO';
$CONF_XTRA["smartemailing_username"] = 'tomas.vans@seznam.cz';
/* /smartemailing */


//$CONF_XTRA["aplikace_typ_id"] = 2;

$CONF_XTRA["TIME_FILES"] = "2015121703";
$CONF_XTRA["JS_FILES"] = array (
//	"https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js",
//	"//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js", //
	$CONF_BASE."js/jquery.min.js", //
	$CONF_BASE."js/jquery-ui.min.js", //

	// slider - jQuery.limitslider (http://vanderlee.github.io/limitslider/)
		// Markdown parser
//		"https://cdnjs.cloudflare.com/ajax/libs/pagedown/1.0/Markdown.Converter.min.js",
		$CONF_BASE."js/Markdown.Converter.min.js", //
		//Prettyprint
//		"https://google-code-prettify.googlecode.com/svn/loader/prettify.js",
		$CONF_BASE."js/prettify.js",
		$CONF_BASE."js/jquery.limitslider.js",
	// /slider - jQuery.limitslider (http://vanderlee.github.io/limitslider/)
	
//	"//code.jquery.com/jquery-migrate-1.2.1.min.js", // slider u administrace imgs (lista1)
//	$CONF_BASE."js/jquery-2.1.1.min.js",
//	"js/FancyFileInputs.js", // primo v ajax-image-upload/index_app.php
	$CONF_BASE."js/easySlider1.7.js",
//	$CONF_BASE."js/lightbox.min.js", // viz http://lokeshdhakar.com/projects/lightbox2/ (pouzito u slideru!)
	$CONF_BASE."js/lightbox.js", // viz http://lokeshdhakar.com/projects/lightbox2/ (pouzito u slideru!)
	$CONF_BASE."js/jquery.form.min.js",
//	$CONF_BASE."js/jquery.als-1.7.min.js",
//	$CONF_BASE."js/modernizr.custom.49304.js",
//	$CONF_BASE."js/jquery.form.js", // viz http://lokeshdhakar.com/projects/lightbox2/ (pouzito u slideru!)
//	$CONF_BASE."js/jquery.lightbox-0.5.min.js",
//	"//cdn.jsdelivr.net/jquery.slick/1.3.15/slick.min.js", // slider u administrace imgs (lista1)
//	"//cdn.jsdelivr.net/jquery.slick/1.4.1/slick.min.js", // slider u administrace imgs (lista1)
	$CONF_BASE."js/slick.min.js", // ajax upload img
//	$CONF_BASE."js/FancyFileInputs.js", // ajax upload img
//	$CONF_BASE."ajax-image-upload/js/jquery.form.min.js", // ajax upload img
	$CONF_BASE."js/strrpos.js", // from http://phpjs.org/functions/strrpos/
	$CONF_BASE."js/number_format.js", // from http://phpjs.org/functions/strrpos/
//	$CONF_BASE."js/substr.js", // from http://phpjs.org/functions/strrpos/
//	"//tinymce.cachefly.net/4.1/tinymce.min.js", // tinymce editor
	$CONF_BASE."js/tinymce/tinymce.min.js", // from http://phpjs.org/functions/strrpos/
	$CONF_BASE."js/jquery-ui-timepicker-addon.min.js", // from http://trentrichardson.com/examples/timepicker/
	$CONF_BASE."js/datepicker-cs.js", // from http://trentrichardson.com/examples/timepicker/
	$CONF_BASE."js/jquery-ui-sliderAccess.js", // from http://trentrichardson.com/examples/timepicker/
	$CONF_BASE."js/ckeditor/ckeditor.js",
	$CONF_BASE."js/jquery.mCustomScrollbar.js",
	$CONF_BASE."js/html2canvas.js",
	$CONF_BASE."js/jquery.plugin.html2canvas.js",
	$CONF_BASE."js/jquery.countdown.min.js", // http://hilios.github.io/jQuery.countdown/
	$CONF_BASE."picEdit-master/dist/js/picedit.js",
	$CONF_BASE."js/global.js",
	$CONF_BASE."js/ss.js",
	$CONF_BASE."js/inc.js",
);
$CONF_XTRA["JS_FILES_WP"] = array (
	$CONF_BASE."js/jquery.mCustomScrollbar.js",
	$CONF_BASE."js/inc.js",
);

$CONF_XTRA["CSS_FILES"] = array(
//	$CONF_BASE."css/jquery.lightbox-0.5.css" => "all",
//	$CONF_BASE."easyslider1.7/css/screen.css" => "all",
//	"//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" => "all",
	$CONF_BASE."css/jquery-ui.min.css" => "all",
	$CONF_BASE."css/lightbox.css" => "all",
	// slider - jQuery.limitslider (http://vanderlee.github.io/limitslider/)
		// jqueryui theme
//		"https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/themes/sunny/jquery-ui.css" => "all",
		$CONF_BASE."css/jquery-ui.css" => "all",
		//Prettyprint
//		"https://google-code-prettify.googlecode.com/svn/loader/prettify.css" => "all",
		$CONF_BASE."css/prettify.css" => "all",
	// /slider - jQuery.limitslider (http://vanderlee.github.io/limitslider/)

//	$CONF_BASE."1.7/css/CSSreset.min.css" => "all",
//	$CONF_BASE."1.7/css/als_demo.css" => "all",
//	"//cdn.jsdelivr.net/jquery.slick/1.3.15/slick.css" => "all",
//	"//cdn.jsdelivr.net/jquery.slick/1.4.1/slick.css" => "all",
	$CONF_BASE."css/ajax-image-upload.css" => "all",
	$CONF_BASE."css/slick.css" => "all",
	$CONF_BASE."css/jquery-ui-timepicker-addon.min.css" => "all",
	$CONF_BASE."css/jquery.mCustomScrollbar.css" => "all",
	$CONF_BASE."js/ckeditor/contents.css" => "all",
	$CONF_BASE."picEdit-master/dist/css/picedit.css" => "all",
	$CONF_BASE."css/all.css" => "all",
	$CONF_BASE."css/all_group.css" => "all",
	$CONF_BASE."css/all_lg.php" => "all",
);

// texty a parametry cen aplikaci oddeleny do inc/global_parameters.php
// pac je potrebuji a nacitam i v samotne aplikaci
//if(!strpos($_SERVER["SCRIPT_URL"], "texty.php"))
require_once($CONF_BASE_DIR."inc/global_parameters.php");

/*
// TEST pro http://socialsprinters.com 
$CONF_STATIC["gopay-Client_ID"] = 1459103822;
$CONF_STATIC["gopay-Client_secret"] = "W6TsNQ3z";
$CONF_STATIC["gopay-GoID"] = 8802855006;
$CONF_STATIC["gopay-payment_url"] = "https://testgw.gopay.cz/api/payments/payment"; // u platebni brany + id platby!!!
$CONF_STATIC["gopay-torent_url"] = "https://testgw.gopay.cz/api/oauth2/token";
$CONF_STATIC["gopay-js_embed"] = "https://testgw.gopay.cz/gp-gw/js/embed.js";
// /TEST pro http://socialsprinters.com 
*/

?>
