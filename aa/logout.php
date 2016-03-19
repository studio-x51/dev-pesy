<?
session_start();
session_destroy();
require_once("inc/inc.php");
logit("debug","logout stranka, qs:".$_SERVER["QUERY_STRING"]);
header("location:./?action=logoff".(fetch_uri("try_app","g") ? "&try_app=".fetch_uri("try_app","g") : ""));
exit;
?>

