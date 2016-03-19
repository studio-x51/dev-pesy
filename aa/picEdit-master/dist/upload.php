<?

require_once("../../inc/inc.php");

$dest_dir = $CONF_BASE_DIR."users_data/".$_SESSION["aplikace_id"]."/upload_data/";
@mkdir($dest_dir,0777);

$filename = uniqid("").$_FILES["own_image"]["name"];

$dest_file = $dest_dir.$filename;

if(!convert($_FILES["own_image"]["tmp_name"],$dest_file,">809x1300",$quality = 80))
//if(!copy($_FILES["own_image"]["tmp_name"], $dest_file))
	logit("debug","failed to copy ".$_FILES["tmp_name"]);
else {
	logit("debug","success to copy ".$_FILES["tmp_name"]);
	nastavit_prava($dest_file);
	copyOwnPhotos2AllApps($_SESSION["aplikace_id"], $filename);
}



?>
