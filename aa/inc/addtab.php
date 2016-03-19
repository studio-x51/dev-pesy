<?
#####################################################################################################
# soubor na pridani aplikace do FB tab, otevira se v iframe SS #
#####################################################################################################
logit("debug","start page");
//logit("debug","addtabs.php 2, aplikace_id=".APLIKACE_ID.", session_id=".session_id().", session=".serialize($_SESSION));
logit("debug","addtabs.php 2, aplikace_id=".APLIKACE_ID.", session_id=".session_id());

// nutno prevat v url pri volani iframe aplikace_id!
// pak zde nutno zapsat do session, pac nepredavam do php/actions.php aplikace_id!!!
$_SESSION["aplikace_id"] = APLIKACE_ID;
//$_SESSION["user"][APLIKACE_UNIQ_ID] = fetch_uri("uid", "g");


$CONF = setAppConfig(APLIKACE_ID);

$stav_app = getStavApp(APLIKACE_ID);
//pre($stav_app, "stav aplikace");

//pre($_GET,"GET session_id=".session_id());
//pre($CONF,"CONFFFFF session_id=".session_id());

$app_facebook_tab = false;
$args = array(
	"page" => "addtabs",
);

dbQuery("SELECT owner_id FROM owner_x_app WHERE owner_id=#1 AND aplikace_id=#2 AND 1=1", fetch_uri("uid","g"), APLIKACE_ID);
$row = dbArr();
if($row["owner_id"] != fetch_uri("uid","g")) {
	exit;
}


require_once("inc/header.php");

echo fbroot($CONF,$args); // js fbAsyncInit


?>
<script>
$(function() {
	if(!getParentUrl()) {
//		alert("neni frame!");
		window.location.replace("<?=$CONF_BASE_SSP_APP?>");
	}
	window.parent.postMessage("blur", "*");
//	Login('<?=$CONF["scope"]?>', '<?=session_id()?>', '', 'addtab', '<?=fetch_uri("uid","g")?>');
});	
</script>

<div id="main_addtab" rel="<?=fetch_uri("uid","g")?>">
	<div id="help_aplikace_ready" class="PopWin PopWinWhite hlavni_help">
		<div class="close" title="close win"></div>
		<div id="cont_aplikace_added">
			<p class="title"><?=txt("setting-add2FBTab_aplikace_added_to_FB_title")?></p>
			<p><?=txt("setting-add2FBTab_aplikace_added_to_FB_text")?></p>
<?
		if($stav_app["licence"] == "placena" && $stav_app["stav"] == "nezaplaceno") {
?>
			<button id="gotonext" class="nobold"><?=txt("setting-add2FBTab_gotopay")?></button>
<?		} else {
?>		
			<button id="gotonext" class="nobold"><?=txt("setting-add2FBTab_gotodashboard")?></button>
<?		}
?>			
		</div>
		<div id="cont_aplikace_ready">
			<p class="title"><?=txt("setting-add2FBTab_aplikace_ready")?></p>
	<?
	//	dbQuery("SELECT pa.*, app_id FROM page_x_app pa, aplikace a WHERE pa.aplikace_id=#1 AND pa.aplikace_id=a.aplikace_id AND page_owner_id=#2", APLIKACE_ID, $_SESSION["user"][APLIKACE_ID]);
		dbQuery("SELECT p.*, app_id FROM owner_x_app o, page_x_app p, aplikace a WHERE a.aplikace_id=#1 AND a.aplikace_id = o.aplikace_id AND
		a.aplikace_id = p.aplikace_id AND owner_id=#2", $_SESSION["aplikace_id"], fetch_uri("uid","g"));
		while($row = dbArr()) {
			if($row["app_id"]) {
				$app_facebook_tab = true;
	?>
				<button id="gofbtab" class="nobold" onclick="return openAWin('https://www.facebook.com/<?=$row["page_id"]?>?sk=app_<?=$row["app_id"]?>',1200,900,event,'fb',1, 1)"><?=txt("setting-add2FBTab_showFB")?></button>
	<?
			}
		}

	// pokud neni pridano na facebook page tab
	if(!$app_facebook_tab) {
		?>
			<p class="subtitle"><?=txt("setting-add2FBTab_button_add")?></p>
			<button id="loginfb" class="nobold" onclick="Login('<?=$CONF["scope"]?>', '<?=session_id()?>', '', 'addtab', '<?=fetch_uri("uid","g")?>')"><?=txt("setting-add2FBTab_login_and_setFB")?></button>
			<p class="italic"><?=txt("setting-add2FBTab_public_info")?></p>
	<?
	}
	// pokud je jiz pridano na facebook page tab
	else {
	?>
			<button id="loginfb" class="nobold" onclick="Login('<?=$CONF["scope"]?>', '<?=session_id()?>', '', 'addtab', '<?=fetch_uri("uid","g")?>')"><?=txt("setting-add2FBTab_login_and_setFB_new")?></button>
	<?		echo show_feeedback_form();
	}
	?>
		</div><!--/id=cont_aplikace_ready-->
	</div><!--/id=help_aplikace_ready-->
</div><!--/id=main_addtab-->
<?
require_once("inc/footer.php");
logit("debug","end page");


function show_feeedback_form()
{
	global $CONF_XTRA;
	return false;
	dbQuery("SELECT * FROM feedback WHERE aplikace_id=#1", APLIKACE_ID);
	if(dbRows() == 0) {
		?>
				<div id="feedback">
					<div id="feedback_cont">
						<p class="title"><?=$CONF_XTRA["texty"]["cs"]["setting-add2FBapp_feedback_title"]?></p>			
						<div id="smiles">
							<div id="smiley_yes" class="smiley" rel="1"><p><?=$CONF_XTRA["texty"]["cs"]["setting-add2FBapp_feedback_smiley_yes"]?></p></div>
							<div id="smiley_neutral"class="smiley" rel="2"><p><?=$CONF_XTRA["texty"]["cs"]["setting-add2FBapp_feedback_smiley_neutral"]?></p></div>
							<div id="smiley_no" class="smiley" rel="3"><p><?=$CONF_XTRA["texty"]["cs"]["setting-add2FBapp_feedback_smiley_no"]?></p></div>
							<div class="cl"></div>
						</div>
						<form id="f_feedback" action="php/actions.php" method="get">
						<input type="hidden" name="spokojenost" id="spokojenost" value="">
						<input type="hidden" name="type" id="saveFBTabFeedback" value="saveFBTabFeedback">
						<p class="title"><?=$CONF_XTRA["texty"]["cs"]["setting-add2FBapp_feedback_title_message"]?></p>
						<textarea name="what"></textarea>
						<button type="submit"><?=$CONF_XTRA["texty"]["cs"]["setting-add2FBapp_feedback_send"]?></button>
						</form>
					</div>
					<p id="thanks" class="title"><?=$CONF_XTRA["texty"]["cs"]["setting-add2FBapp_feedback_thanks"]?></p>
				
				</div>
		<?
	}
}

?>
<?
//pre($CONF);
/*
	pre($_GET, "GETS");
	echo "ssp user=".$_SESSION["user"][APLIKACE_ID]."<br>";
	echo "trezor user=".$_SESSION["user"][APLIKACE_UNIQ_ID]."<br>";
?>
<div id="addfbtab" onclick="addFBTab('<?=$CONF_BASE?>')">Pridej tabff</div>
<div><a href="https://www.facebook.com/dialog/pagetab?app_id=<?=$CONF["app_id"]?>&redirect_uri=<?=$CONF_BASE?>">ADD 2 TAB</a></div>
<div id="changefbtabname" onclick="changeFBTabName(259239627467123, 1376210906024288, 'test the west 6')">change name tab</div>
<div onclick="ShowMyName()">My Name</div>
<?
*/

/*
				$access_token = file_get_contents("https://graph.facebook.com/oauth/access_token?client_id=".$CONF["app_id"]."&client_secret=".$CONF["app_secret"]."&grant_type=client_credentials");
				$app_check = json_decode(file_get_contents("https://graph.facebook.com/". $CONF["page_id"] . "/tabs/" .  $_SESSION["user"][APLIKACE_ID] . "?access_token=" . $access_token));
				if(!empty($app_check->data))
				{
				    echo "APP IS INSTALLED";
				}
				else {
					echo "APP IS NOT INSTALLED";
				}
*/				
?>
