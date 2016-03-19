<?
#####
// user = $_SESSION["user"][APLIKACE_UNIQ_ID]
#####

require_once("inc/inc.php");

if(!$_COOKIE["_ssuser"]) {
	setcookie("_ssuser", strtotime("+ 3 days") * 1000, time() + 864000);
}

##############################################
##### STAV PO ZAPLACENI			  ############
##### presmeruji je-li zaplaceno! ############
##############################################
if(fetch_uri("action","g") == "gopay" && fetch_uri("id","g")) {
	// zde unsetnu test pro PREMIUM (po zaplaceni), dalsim testem se vytrvori nva SESSION s kodem!
	unset($_SESSION["premium"]);
	$savePaymentState = savePaymentState(fetch_uri("id","g"), fetch_uri("parent_id","g"), fetch_uri("notifikace","g"), fetch_uri("aplikace_id","g"));
//	pre($savePaymentState, "savePaymentState");
//	exit;
	if($savePaymentState["state"] == "PAID") {
		header("location:".$CONF_BASE."premium?paid=success");
		exit;
	}
}
##############################################
##### /STAV PO ZAPLACENI			##########
##############################################


logit("debug","start page");
//if(!mujpc())
//	exit;
$CONF = setConfig();

$args = array(
	"page" => "hura",
	"style" => "vstup",
);
require_once("inc/header.php");

?>
<script src="js/js.cookie.js"></script>
<script>
$(function() {
/*
	console.log(Cookies.get('_ssuser'));
	console.log($.now());
	console.log("<?=$_COOKIE["_ssuser"] / 1000?>");
	console.log("<?=time()?>");
	alert("<?=date("Y-m-d h:i:s", $_COOKIE["_ssuser"] / 1000)?>");
*/	
});
</script>
<?


echo fbroot($CONF,$args); // js fbAsyncInit
//pre($_SESSION);


	?>
<div id="cont_all_app">	
		<div id="all_app" class="cont_center">
	       <h2><?
				echo txt("all-app_title-zadne_aplikace");
			?></h2>
		</div>
</div>

	<?
//if(fetch_uri("x","g") == "xtra" || $_SESSION["xtra_premium"] == true) 
if(fetch_uri("x","g") == "xtra" || fetch_uri("x","g") == "video" || $_SESSION["xtra_premium"] == true || strtotime(getCountdownTimexxx()) > time()) {
	if(fetch_uri("x","g") == "video") {
//		unset($_SESSION["xtra_premium"]);
	}
	echo show_premium_platba();

//	unset($_SESSION["xtra_premium"]);
	if(fetch_uri("x","g") == "video" || fetch_uri("x","g") == "xtra")
		$_SESSION["xtra_premium"] = true;
}
else {
	?>

	<div id="PopPlatba" class="PopWin PopWinWhite hura premium">
		<div class="title">
			Bohužel jste přišli pozdě.<br /> Nabídka vypršela.
		</div>
		<p>
			Sledujte nás na Facebooku a už vám nic neuteče.
		</p>
		<div class="fb-page" data-href="https://www.facebook.com/socialsprinters/" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false"></div>
	</div>
	<?
}

require_once("inc/footer.php");
logit("debug","end page");
exit;

/**
* vrati spravny datum a cas pro Countdown
*/
function getCountdownTimexxx() {
//	return "2015-11-20 14:45:00";
//	return "2015-11-29 23:59:59";
	if(fetch_uri("x","g") == "video") {
//		$_COOKIE["_ssuser"] = strtotime("+ 3 days") * 1000;
		if(!$_COOKIE["_ssuser"] || $_COOKIE["_ssuser"] / 1000 < time()) {
			return date("Y-m-d h:i:s", strtotime("+ 3 days")); // "2016-01-30 17:40:15";
		}
		return date("Y-m-d h:i:s", $_COOKIE["_ssuser"] / 1000); // "2016-01-30 17:40:15";
	}
	return "2016-01-29 23:59:59";
}


function Countdownxxx($default_days_to_end = 10) {
	ob_start();
?>
		<div id="getting-started"></div>
		<script type="text/javascript">
			if (!Date.now) {
			    Date.now = function() { return new Date().getTime(); }
			}
			// Split timestamp into [ Y, M, D, h, m, s ]
			var t = "<?=getCountdownTimexxx($default_days_to_end)?>".split(/[- :]/);
//			console.log(t);
			// Apply each element to the Date function
			var d = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
//			console.log(d.getTime());
			$(function() {
				$("#getting-started").countdown("<?=getCountdownTimexxx($default_days_to_end)?>", function(event) {
//					console.log(event.strftime('%D.%H.%M.%S'));
					var nyni = new Date();
//					console.log("rozdil:");
//					console.log(d.getTime() - event.timeStamp);
//					console.log(event.timeStamp);
					if(d.getTime() < event.timeStamp) {
<?						if(fetch_uri("x","g") == "video" && $_COOKIE["_ssuser"]) {
?>						//	window.location.replace("http://socialsprinters.cz/video/closed.php");
							window.location.reload();
<?						}
						else {
?>							window.location.reload();
<?						}
?>
					}
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
				 + '<div id="time_to_end">Do konce nabídky zbývá:</div>'
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
?>
