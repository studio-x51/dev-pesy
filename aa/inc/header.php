<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=txt("ss-fb_og_title")?></title>
<meta name="description" content="<?=txt("ss-fb_og_description")?>" />
<?if(isset($CONF["og:url"])) {?>
<meta property="og:url" content="<?=$CONF["og:url"]?>" /> 
<?}?>
<meta property="og:image" content="<?=$CONF_BASE.$CONF["og:image"]?>" />
<meta property="og:title" content="<?=txt("ss-fb_og_title")?>" />
<meta property="og:description" content="<?=txt("ss-fb_og_description")?>" />
<meta property="og:type" content="<?=$CONF_XTRA["og:type"]?>" />
<script type="text/javascript">
<? 
	require_once($CONF_BASE_DIR."js/global.php");
?>
</script>
<?
if(strpos($_SERVER["HTTP_HOST"], "socialsprinters") !== false) {
?>

<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','//connect.facebook.net/en_US/fbevents.js');

fbq('init', '858616264234169');
fbq('track', 'PageView');
</script>
<style>
<?

if(!mujpc()) {
?>
/* #set_nahrano { display: none; } */
<?
}

?>
</style>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=858616264234169&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->

<? 
	// pouze index a signupcomplete pro nove uzivatele
	if(fetch_uri("sign","g") == "signupcomplete") {
?>
<!-- Facebook Conversion Code for Registrace SS -->
<script>(function() {
var _fbq = window._fbq || (window._fbq = []);
if (!_fbq.loaded) {
var fbds = document.createElement('script');
fbds.async = true;
fbds.src = '//connect.facebook.net/en_US/fbds.js';
var s = document.getElementsByTagName('script')[0];
s.parentNode.insertBefore(fbds, s);
_fbq.loaded = true;
}
})();
window._fbq = window._fbq || [];
window._fbq.push(['track', '6028476890532', {'value':'0.00','currency':'CZK'}]);
</script>
<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=6028476890532&amp;cd[value]=0.00&amp;cd[currency]=CZK&amp;noscript=1" /></noscript>

<!-- End Facebook Conversion Code for Registrace SS -->

<?
	}
?>

<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
 (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
 m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
 })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-64225075-1', 'auto');
ga('send', 'pageview');
</script>

<!-- Begin Inspectlet Embed Code -->
<script type="text/javascript" id="inspectletjs">
window.__insp = window.__insp || [];
__insp.push(['wid', 1523886660]);
(function() {
function __ldinsp(){var insp = document.createElement('script'); insp.type = 'text/javascript'; insp.async = true; insp.id = "inspsync"; insp.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cdn.inspectlet.com/inspectlet.js'; var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(insp, x); };
document.readyState != "complete" ? (window.attachEvent ? window.attachEvent('onload', __ldinsp) : window.addEventListener('load', __ldinsp, false)) : __ldinsp();

})();
</script>
<!-- End Inspectlet Embed Code -->
<?
}

echo jsFiles();
echo cssFiles();


?>

</head>
<body class="<?echo TestPremiumMember() ? "premium " : ""?><?=mujpc() ? "mujpc " : ""?><?=isset($args["style"]) ? $args["style"]." " : ""?> <?=isset($args["page"]) ? $args["page"]." " : ""?>" rel="<?=session_id()?>">
<?
if(fetch_uri("addtab","g") == "ok") {
?>
<script>
$(function() {
	set_widget_overlay("overlay", "body");
	showGratulace();
});	
</script>
<div id="overlay"></div>
<?
}
?>
<div id="main">
	<div id="top_lista">
		<div class="top_lista_min">
			<span class="logo"><a href="<?=$CONF_BASE;?>"></a></span>
			<?=user_board();?>
			<div class="cl"></div>
		</div>
	</div>
	<?
//	PopSetLanguage();
//	PopSetFakturace();
//pre($CONF, "get CONF");
//pre($_SESSION, "get SESSION");
//pre(getSignedRequest(), "getSignedRequest");
?>

