<?

date_default_timezone_set('Europe/Prague');

function nastavit_prava ($file,$chmod = 0666)
{
//	if(mujpc())
//		echo "chmod 0666 ".$file."<br>\n";
	@chmod($file, $chmod);
}

function mujpc()
{
	global $FBprevlek;
	if($FBprevlek)
		return false;
	// 84.42.152.67 =  madridska staticka!
	// 87.249.153.140 = rybna
	// 178.210.229.175 = balaton
//	echo $_SERVER["REMOTE_ADDR"];
	if($_SERVER["REMOTE_ADDR"] == "87.249.153.140" || !$_SERVER["REMOTE_ADDR"] || $_SERVER["REMOTE_ADDR"] == "84.42.152.67" || substr($_SERVER["REMOTE_ADDR"], 0, 3) == "10." || $_SERVER["REMOTE_ADDR"] == "94.113.3.132" || substr($_SERVER["REMOTE_ADDR"], 0, 7) == "192.168") 
//	if(!$_SERVER["REMOTE_ADDR"] || substr($_SERVER["REMOTE_ADDR"], 0, 3) == "10." || substr($_SERVER["REMOTE_ADDR"], 0, 3) == "192." || $_SERVER["REMOTE_ADDR"] == "85.71.176.110" || $_SERVER["REMOTE_ADDR"] == "192.168.1.101" || $_SERVER["REMOTE_ADDR"] == "82.113.63.126") // terda
	// ++ synkac 
//	if($_SERVER["REMOTE_ADDR"] == "82.113.63.126" || substr($_SERVER["REMOTE_ADDR"], 0, 3) == "10." || $_SERVER["REMOTE_ADDR"] == "85.71.176.110" || $_SERVER["REMOTE_ADDR"] == "192.168.1.2" || $_SERVER["REMOTE_ADDR"] == "82.113.63.126") // terda
		return true;
	return false;
}


function logit($level,$text,$file = false )
{
	global $CONF_BASE_DIR, $CONF_DEBUG;
	if(!is_dir($CONF_BASE_DIR."logs"))
		mkdir($CONF_BASE_DIR."logs",0777);
	if(!$file)
		$file = $CONF_BASE_DIR."logs/logit.log";
//	echo $file;
	if($level == "nolog")
		return;
	if(!is_file($file)) {
		touch($file);
		nastavit_prava($file);
	}
	if ($level == 'err') $level = 'error';
	$levels = array('0'=>'debug','1'=>'info','2'=>'warning','3'=>'error','10'=>'log');
	if (isset($levels[$level])) $level = $levels[$level];

	$d1 = array_keys($levels,$CONF_DEBUG);
	$d2 = array_keys($levels,$level);
	if (!isset($d1[0]) or $d1[0] > $d2[0]) return;
	$f = fopen($file,'a');
//	fwrite($f,"[".date('Y-m-d H:i:s')."-".(mujpc() ? "stefi" : session_id()).",$level,".substr($_SERVER["PHP_SELF"],strrpos($_SERVER["PHP_SELF"],"/")+1).";]".$text."\n");
//	fwrite($f,"[".date('Y-m-d H:i:s')."-".microtime().",".(mujpc() ? "stefi" : session_id())."/".$_SERVER["HTTP_USER_AGENT"].",$level,$_SERVER[PHP_SELF];]".$text."\n");
//	fwrite($f,"[".date('Y-m-d H:i:s')."-".microtime().",".(mujpc() ? "stefi".session_id() : session_id())." ,$level,$_SERVER[PHP_SELF];]".$text."\n");
	fwrite($f,"[".date('Y-m-d H:i:s')."-".microtime().",$level,$_SERVER[REMOTE_ADDR] $_SERVER[PHP_SELF]; $_SERVER[HTTP_USER_AGENT]]".$text."\n");
// tohle nma ostre!
//	fwrite($f,"[".date('Y-m-d H:i:s')."-".microtime().",".(mujpc() ? "stefi" : session_id())." ,$level,$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]?$_SERVER[QUERY_STRING];".$text."\n");
//	mujpc() ? fwrite($f,"[".date('Y-m-d H:i:s')."-".microtime()."-".$level.",".$text."\n") : fwrite($f,"[".date('Y-m-d H:i:s')."-".microtime().",".(mujpc() ? "stefi" : session_id())."/".$_SERVER["HTTP_USER_AGENT"].",$level,$_SERVER[PHP_SELF];]".$text."\n");
	fclose($f);
	nastavit_prava($file);

//	if (headers_sent())
//		echo "<div>Logit: $level: $text<br/></div>\n";
}



// added stefik
function pre($str, $what = false)
{
	if(!mujpc())
		return;
	echo "<p style=\"text-align:left\"><strong>$what:</strong> <pre style=\"text-align:left\">";
	print_r($str);
	echo "</pre></p>";
}

// added stefik > fce openssl_random_pseudo_bytes (PHP 5 >= 5.3.0)
if(function_exists("openssl_random_pseudo_bytes")) return;
{
	function openssl_random_pseudo_bytes($length) {
	  $length_n = (int) $length; // shell injection is no fun
	  $handle = popen("/usr/bin/openssl rand $length_n", "r");
	  $data = stream_get_contents($handle);
	  pclose($handle);
	  return $data;
	}
}

function getmicrotime()
{
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}

function walk_stripslashes(&$val,$key)
{
    $val = stripslashes($val);
}

/** fetch_uri returns named value of POST and/or GET. It depends at
   $code (p and g letters). It uses default otherwise unless $code
   contains ! - then URI is mandatory and will trap. */

function fetch_uri($name,$code,$default = null) {
	$x1 = $x2 = null;
	if (strpos($code,'g') !== false && isset($_GET[$name]))
		$x1 = $_GET[$name];
	if (strpos($code,'p') !== false && isset($_POST[$name]))
		$x2 = $_POST[$name];
	if (strpos($code,'p') < strpos($code,'g')) {
		$t = $x1; $x1 = $x2; $x2 = $t;
	}
	if (is_null($x1)) $x1 = $x2;
	if (is_null($x1)) {
		if (strpos($code,'!') !== false) exit("Missing $name URI");
		$x1 = $default;
	}
	else if (get_magic_quotes_gpc())
    { 
            if(!is_array($x1))
                $x1 = stripslashes($x1);
            else
                array_walk($x1,'walk_stripslashes');
    }
	return $x1;
}
?>
