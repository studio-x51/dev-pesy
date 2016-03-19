<?
#####
// user = $_SESSION["user"][APLIKACE_UNIQ_ID]
#####

require_once("inc/inc.php");

##############################################
##### STAV PO ZAPLACENI			  ############
##### presmeruji je-li zaplaceno! ############
##############################################
if(fetch_uri("action","g") == "gopay" && fetch_uri("id","g")) {
	$savePaymentState = savePaymentState(fetch_uri("id","g"), fetch_uri("parent_id","g"), fetch_uri("notifikace","g"), fetch_uri("aplikace_id","g"));
//	pre($savePaymentState, "savePaymentState");
//	exit;
	if($savePaymentState["state"] == "PAID") {
		header("location:".$CONF_BASE."?paid=success");
		exit;
	}
}
##############################################
##### /STAV PO ZAPLACENI			##########
##############################################

// pokud se chci prevleknou za daneho uzivatele zde nastavim!!!
if($_SERVER["REMOTE_ADDR"] == "84.42.152.67" && $FBprevlek)
	$_SESSION["user"][APLIKACE_UNIQ_ID] = $FBprevlek;

// reset cele SQL do deafult!!!
if(!$_SESSION["restricted_access"] && fetch_uri("action","g") == "resetsql") {
//	exec("mysql -u ".$CONF_USER_SQL." -p".$CONF_PWD_SQL." < db.sql; mysql -u ".$CONF_USER_SQL." -p".$CONF_PWD_SQL." ".$CONF_NAME_SQL." < aplikace.dump; mysql -u ".$CONF_USER_SQL." -p".$CONF_PWD_SQL." ".$CONF_NAME_SQL." < vyhry.dump; mysql ".$CONF_USER_SQL." -p".$CONF_PWD_SQL." ".$CONF_NAME_SQL." < banery.dump");
	exec("mysqldump -u ".$CONF_USER_SQL." -p".$CONF_PWD_SQL." $CONF_NAME_SQL > backup_sql/".date("Y-m-d_H-i-s").".dump");
//	exec("mysql -u ".$CONF_USER_SQL." -p".$CONF_PWD_SQL." < db.sql; mysql -u ".$CONF_USER_SQL." -p".$CONF_PWD_SQL." ".$CONF_NAME_SQL." < aplikace.dump; mysql -u ".$CONF_USER_SQL." -p".$CONF_PWD_SQL." ".$CONF_NAME_SQL." < vyhry.dump; mysql ".$CONF_USER_SQL." -p".$CONF_PWD_SQL." ".$CONF_NAME_SQL." < banery.dump");
	exec("mysql -u ".$CONF_USER_SQL." -p".$CONF_PWD_SQL." $CONF_NAME_SQL < db.reset.sql");
	session_destroy();
	header("location: ".$CONF_BASE);
	exit;
}


//phpinfo();
//pre($_SESSION);

$end_show = "";
$class_last = "";
//$obj = getSignedRequest();
//pre($_SESSION,"SESSION data");
//exit;

########################################################################################################################################################################################################################
// zrusim session, aby sla vytvorit nova aplikace - a nedelalo by mi to bordel pri reloadu stranky (ssp/setapp?aplikace_id_set=new&aplikace_typ_id=2)
########################################################################################################################################################################################################################
if(isset($_SESSION["aplikace_id"])) {
	unset($_SESSION["aplikace_id"]);
	unset($_SESSION["aplikace_typ_id"]);
	unset($_SESSION["tema_id"]);
}	

	unset($_SESSION["aplikace_typ_id"]);
// TODO: remove after develope! :-)
logit("debug","start page");
//if(!mujpc())
//	exit;
$CONF = setConfig();

$args = array(
	"page" => "index",
	"style" => "vstup",
);

//echo "http://graph.facebook.com/".$_SESSION["user"][APLIKACE_UNIQ_ID]."/picture?width=50&height=50";
/* presunuto do inc/inc.php
if($_SESSION["user"][APLIKACE_UNIQ_ID] && !is_file("./fb_photos/50x50/". $_SESSION["user"][APLIKACE_UNIQ_ID] .".jpg")) {
	@copy("http://graph.facebook.com/".$_SESSION["user"][APLIKACE_UNIQ_ID]."/picture?width=50&height=50", "./fb_photos/50x50/".$_SESSION["user"][APLIKACE_UNIQ_ID].".jpg");
	nastavit_prava("./fb_photos/50x50/". $_SESSION["user"][APLIKACE_UNIQ_ID] .".jpg");
}
*/

require_once("inc/header.php");
?>
<script>
$(function() {
//	set_widget_overlay("overlay");
});

</script>
<?

echo fbroot($CONF,$args); // js fbAsyncInit
//pre($_SESSION);

$str = "";
//pre($_SESSION,"all_SESSION");
if(!$_SESSION["user"][APLIKACE_UNIQ_ID]) {
	$str .=  "<p id=\"login\" onclick=\"Login('".$CONF["scope"]."', '".session_id()."')\">LOGIN!</p>";
	?>
	<div id="vstup">
		<h1 class="gold">Připojte váš Facebookový účet</h1>

		<p class="premium"> 
		Následně budete moci přejít k nastavení soutěžní aplikace<br />
		a umístit ji po konečných úpravách na vaši<br />
		Facebook stránku.
		</p>

		<button onclick="Login('<?=$CONF["scope"]?>', '<?=session_id()?>', 'dashboard')" type="submit"><?=txt("setting-button_fb_login")?></button>
		<p class="dopln">Informace o přihlášení na SocialSprinters <span>se nikde neobjeví.</span></p>

		<? /*
			<div id="vyber_volby">
				<?=$str?>
			</div>
		*/
		?>
	</div>
	<?	
	require_once("inc/footer.php");
	logit("debug","end page");
	exit;
}

PopContactEmail();
//PopSetLanguage();




//copy("http://graph.facebook.com/".fbidFromUniqid($uniqid)."/picture?width=50&height=50", "./fb_photos/50x50/".fbidFromUniqid($uniqid).".jpg");

// seznam vsech aplikaci ownera
$list_apps = "";
$apps = array();
dbQuery("SELECT * FROM owner_x_app oa, aplikace a WHERE owner_id = #1 AND a.aplikace_id=oa.aplikace_id ORDER BY zalozeno, a.aplikace_id", $_SESSION["user"][APLIKACE_UNIQ_ID]);
while($row = dbArrTiny()) {
	$list_apps .= "<li><a href=\"".$row["aplikace_typ_id"]."/setapp?aplikace_id=".$row["aplikace_id"]."\">".$row["og:title"]." | ".$row["og:description"]."</a></li>";
	$apps[] = $row["aplikace_id"];
	$apps_aplikace_typ[] = $row["aplikace_typ_id"];
//	pre($row, "vsechny aplikace uzivatele ".$_SESSION["user"][APLIKACE_UNIQ_ID]);
}
//pre($_SESSION, "data SESSION");

// 1) omezeno na cleny x51 akademie (docasne!)
// OK: zde restricted_access spravne pouzito TODO: nechat celou podminku a nebude se pouzivat!!!!
if($_SESSION["restricted_access"]) {
?>

	<div id="vstup">
<?
	if(fetch_uri("err","g") == "app_no_added")
		echo txt("setting-neni_fb_aplikace_k_prirazeni");
?>

	<h1 class="green">Díky za přihlášení!</h1>

	<p class="premium">Jakmile budete připraveni, můžete začít s editací<br />
	vaší soutěžní aplikace. K editaci se můžete kdykoliv vrátit.
	</p>
	<?
	if($list_apps) {
		if(!in_array(2, $apps_aplikace_typ)) {
?>			<button class="gold_bt" rel="2_new" onclick="javascript:window.location.href='2/setapp?aplikace_id_set=new&amp;aplikace_typ_id=2'" type="submit"><?=txt("setting-button_prejit_k_tematu_2")?></button>
<?		}
		if(!in_array(4, $apps_aplikace_typ)) {
?>			<button class="gold_bt" rel="4_new" onclick="javascript:window.location.href='4/setapp?aplikace_id_set=new&amp;aplikace_typ_id=4'" type="submit"><?=txt("setting-button_prejit_k_tematu_4")?></button>
<?		}
	}
	else {
		// odkaz na novy trezor!
	?>
		<button class="gold_bt" rel="2_new" onclick="javascript:window.location.href='2/setapp?aplikace_id_set=new&amp;aplikace_typ_id=2'" type="submit"><?=txt("setting-button_prejit_k_tematu_2")?></button>
		<button class="gold_bt" rel="4_new" onclick="javascript:window.location.href='4/setapp?aplikace_id_set=new&amp;aplikace_typ_id=4'" type="submit"><?=txt("setting-button_prejit_k_tematu_4")?></button>
	<?
	}
	?>
	</div>
	<div id="dashboard">
		<? echo DashBoard(); ?>
	</div>	
<?

}
// 2) pristup prozatim s heslem
else {
	?>
	
	<div id="cont_all_app" class="<?=count($apps) == 0 ? "shown" : ""?>">
		<?
		echo AllApp(count($apps));
		?>
	</div>
<?

// Dashboard zobrazuji pouze pokuz jiz nejaka aplikace je!!!
if(count($apps) > 0 ) { ?>
	<div id="cont_dashboard">
<?		if(fetch_uri("err","g") == "app_no_added") {
			echo "<p class=\"err\">".txt("setting-neni_fb_aplikace_k_prirazeni")."</p>";
		}
		echo DashBoard();
	?>
	</div><!--cont_dash_board-->
<?
	if(mujpc()) {
	?>
				<div onclick="FacebookInviteFriends()">invite friends</div>
	<?
	}

}

if(1==2 || (count($apps) > 0 && strpos($_SERVER["HTTP_HOST"], "socialsprinters") !== false)) {
?>
	<iframe id="frameapp" src="http://www.socialsprinters.cz/id3ed3ob1oin0ji6oc4u/" width="100%" scrolling="no" frameBorder="0" onload="javascript:resizeIframe(this);"></iframe>
<?
}
	
	
/*
	if(!$_SESSION["restricted_access"]) {
	?>
	<div id="resetsql">
		<a href="./?action=resetsql" onclick="return confirm('MYSLÍŠ TO VÁŽNĚ?');">RESETUJE VSECHNY APLIKACE DO START VERZE !!!</a>
	</div>
	<?
	}
*/	
}
//pre($_SESSION, "get SESSION");

require_once("inc/footer.php");
logit("debug","end page");
?>
