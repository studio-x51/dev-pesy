<?
##############################################################################################
####### POZOR musi byt na kazde settap. strance nastaven spravny $args["page"] !!! ###########
##############################################################################################
	dbQuery("SELECT off FROM head_help_off WHERE aplikace_typ_id=#1 AND fb_id=#2",$_SESSION["aplikace_typ_id"],$_SESSION["user"][APLIKACE_UNIQ_ID]);
	$row = dbArr();
	if($row["off"])
		$_SESSION["head_help_off"][$_SESSION["aplikace_typ_id"]] = true;

	// pokud neni zadana vyhra musim okno zobrazt!!!
	if(fetch_uri("err","g") == "setmandatory" || fetch_uri("err","g") == "setvyhry")
		$_SESSION["head_help_off"][$_SESSION["aplikace_typ_id"]] = false;

	if(
		!isset($_SESSION["tema_id"][$_SESSION["aplikace_id"]]) && !isset($_SESSION["head_help_done"][$args["page"]][$_SESSION["aplikace_id"]])	||
		fetch_uri("err","g") == "setvyhry" ||
		fetch_uri("err","g") == "setmandatory" ||
		!isset($_SESSION["skin_id"][$_SESSION["aplikace_id"]]) && !isset($_SESSION["head_help_done"][$args["page"]][$_SESSION["aplikace_id"]]) ||
		isset($_SESSION["skin_id"][$_SESSION["aplikace_id"]]) && !isset($_SESSION["head_help_tema_skin"][$args["page"]][$_SESSION["aplikace_id"]])

	) {

		// add FB tab musim vynechat!!!
		// a pokus si odswitchne musim vynehchat  a zapsat do databaze
		if($args["page"] != "pagetab_setting" && $args["page"] != "pagetab_pay" && !$_SESSION["head_help_off"][$_SESSION["aplikace_typ_id"]]) {
	?>
			<script type="text/javascript">
			$(function(){
				show_head_help();		
			});
			</script>
	<?	}
		if(isset($_SESSION["skin_id"][$_SESSION["aplikace_id"]]))
			$_SESSION["head_help_tema_skin"][$args["page"]][$_SESSION["aplikace_id"]] = true;
	}

		ob_start();
	?>
		<p class="title"><?=txt("HELP-TEXT_SET-TEMA_title")?></p>
		<p>
		<?=$_SESSION["TemaSingle"]==1 ? txt("HELP-TEXT_SET-SKIN_text") : txt("HELP-TEXT_SET-TEMA_text")?>
		</p>
		<p class="subtitle">
		<?=$_SESSION["TemaSingle"]==1 ? txt("HELP-TEXT_SET-SKIN_subtitle") : txt("HELP-TEXT_SET-TEMA_subtitle")?>
		</p>
<? 
	if(file_exists("./img/help-set-tema.gif")) {
?>
		<img src="./img/help-set-tema.gif" alt="help" class="help">
<?
	}
?>		
		<button type="submit"><?=txt("HELP-TEXT_SET-TEMA_button")?></button>
<?		$tema_skin_help = ob_get_clean();
		if(!isset($_SESSION["tema_id"][$_SESSION["aplikace_id"]]) || (!$_SESSION["head_help_done"][$args["page"]][$_SESSION["aplikace_id"]] && !isset($_SESSION["skin_id"][$_SESSION["aplikace_id"]])))
			$help = $tema_skin_help;
			
		$_SESSION["head_help_done"][$args["page"]][$_SESSION["aplikace_id"]] = true;
?>			
