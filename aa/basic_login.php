<?
require_once("aa/inc/inc.php");

$str = "";

$CONF = setConfig();

$args = array(
	"page" => "adminentry",
	"style" => "vstup",
);
?>
<script type="text/javascript">
<? 
//	require_once($CONF_BASE_DIR."js/global.php");
	require_once("aa/js/global.php");
?>
</script>
<?
	// nacte 
	echo jsFiles($CONF_XTRA["JS_FILES_WP"]);

echo fbroot($CONF,$args);
?>
