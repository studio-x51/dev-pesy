<?php
echo "var url_share = '" . $CONF_BASE."';\n";
echo "var url_redir = '" . $CONF_BASE."';\n";
echo "var swich_title_spusteno = '" . txt("dashboard-description_swich-title-spusteno")."';\n";
echo "var swich_title_stopnuto = '" . txt("dashboard-description_swich-title-stopnuto")."';\n";
if($_SESSION["tema_id"][$_SESSION["aplikace_id"]])
	echo "var tema_done = true;\n";
else
	echo "var tema_done = false;\n";
if($_SESSION["skin_id"][$_SESSION["aplikace_id"]])
	echo "var skin_done = true;\n";
else
	echo "var skin_done = false;\n";
echo "var time_to_end = '" . txt("setting-do_konce_souteze_zbyva")."';\n";
echo "var are_you_sure_delete_app = '" . txt("setting-are_you_sure_delete_app")."';\n";
echo "var help_button_next = '".txt("setting-help_button_next")."';\n";
if($args["page"] != "index")
	echo "var help_hlavni_text = '".(isset($args["page"]) && txt("setting-help_hlavni_text-".$args["page"]) ? txt("setting-help_hlavni_text-".$args["page"]) : txt("setting-help_hlavni_text-default"))."';\n";
echo "var help_slick_text = '".txt("setting-help_slick_text")."';\n";
echo "var help_help_set_tema_text = '".txt("setting-help_set_tema_text")."';\n";
echo "var help_help_set_skin_text = '".txt("setting-help_set_skin_text")."';\n";
if(isset($CONF_XTRA["youtube_video"]))
	echo "var video_youtube = '".$CONF_XTRA["youtube_video"]."';\n";
if(isset($CONF_XTRA["video_vimeo"]))
	echo "var video_vimeo = '".$CONF_XTRA["vimeo_video"]."';\n";
echo "var confirm_delete_price = '".txt("setting-confirm_delete_price")."';\n";
echo "var confirm_change_price = '".txt("setting-confirm_change_price")."';\n"; // jiz jsou vyherci !!!
echo "var confirm_pravidla_resetovat = '".txt("setting-confirm_pravidla_resetovat")."';\n"; 
echo "var alert_soutez_spustena = '".strip_tags(txt("setting-stop_admin-vyhry"))."';\n"; // aplikace bezi !!!
echo "var err_form_title = '" . txt("form_check-err_zadejte-povinne_pole") ."';\n";
echo "var err_form_zadejte_platny_email = '" . txt("form_check-err_zadejte-platny_email") ."';\n";
echo "var err_form_sorry_you_are_premium = '" . txt("form_check-err_sorry_you_are_premium") ."';\n";
if(isset($_SESSION["aplikace_typ_id"]))
	echo "var aplikace_typ_id = '".$_SESSION["aplikace_typ_id"]."';\n";
echo "var price_app = {};\n";
echo "var scope = '".$CONF["scope"]."';\n";
echo "var picedit_copy_paste = '".txt("picedit-copy_paste")."';\n";
echo "var picedit_pen_tool = '".txt("picedit-pen_tool")."';\n";
echo "var picedit_crop_tool = '".txt("picedit-crop_tool")."';\n";
echo "var picedit_rotate_tool = '".txt("picedit-rotate_tool")."';\n";
echo "var picedit_resize_tool = '".txt("picedit-resize_tool")."';\n";
echo "var picedit_message_working = '".txt("picedit-message_working")."';\n";
echo "var picedit_message_vlozte_obrazek_nebo_fotak = '".txt("picedit-message_vlozte-obrazek_nebo_fotak")."';\n";
echo "var picedit_message_please_wait = '".txt("picedit-message_please_wait")."';\n";
echo "var picedit_message_sorry_no_webRTC = '".txt("picedit-message_sorry_no_webRTC")."';\n";
echo "var picedit_message_no_video_source_detected = '".txt("picedit-message_no_video_source_detected")."';\n";
echo "var picedit_message_FormData_API_is_not_supported = '".txt("picedit-message_FormData_API_is_not_supported")."';\n";
echo "var picedit_message_please_wait_uploading = '".txt("picedit-message_please_wait_uploading")."';\n";
echo "var picedit_message_please_wait_uploading_done = '".txt("picedit-message_please_wait_uploading_done")."';\n";
echo "var picedit_message_data_submited = '".txt("picedit-message_data_submited")."';\n";
echo "var picedit_width = '".txt("picedit-width")."';\n";
echo "var picedit_height = '".txt("picedit-height")."';\n";
echo "var picedit_color_black = '".txt("picedit-color_black")."';\n";
echo "var picedit_color_white = '".txt("picedit-color_white")."';\n";
echo "var picedit_color_red = '".txt("picedit-color_red")."';\n";
echo "var picedit_color_green = '".txt("picedit-color_green")."';\n";
echo "var picedit_color_red = '".txt("picedit-color_red")."';\n";
echo "var picedit_color_orange = '".txt("picedit-color_orange")."';\n";
echo "var picedit_color_blue = '".txt("picedit-color_blue")."';\n";
echo "var picedit_pen_large = '".txt("picedit-pen_large")."';\n";
echo "var picedit_pen_medium = '".txt("picedit-pen_medium")."';\n";
echo "var picedit_pen_small = '".txt("picedit-pen_small")."';\n";
echo "var picedit_confirm_delete_from_disk = '".txt("picedit-confirm_delete_from_disk")."';\n";

if(isset($CONF_XTRA["price"])) {
	foreach($CONF_XTRA["price"] as $aplikace_typ_id => $periods) {
		echo "price_app[".$aplikace_typ_id."] = {};\n";
		foreach($periods as $period => $price)
			echo "price_app[".$aplikace_typ_id."]['".$period."'] = '".$price."';\n";
	}
}	

//echo "var fb_app_page = '" . URL_HLAS_PHOTO_FB ."';\n";
?>

