<?php
function color_dump_SQL($sql)
{
	if(!mujpc())
		return false;
//	return "\n".$sql."\n";
	$sql = preg_replace("/([()<>=,])/i","<span style=\"color:blue\">\\1</span>",$sql);
	$sql = preg_replace("/\b(update|replace|insert|set|into|create|select|from|where|and|or|group|order by)\b/i","<span style=\"color:red;\">\\1</span>",$sql);
	$sql = preg_replace("/\b(values|temporary|table|distinct|as)\b/i","<font color=\"green\">\\1</font>",$sql);
	return '<br><span style="background-color:white;color:black;text-align:left">'.$sql.'</span><br>';
}

function dbQuote($a) 
{ 
	return "'".mysql_real_escape_string($a)."'"; 
}

/** Kompatibilita - jako dbQuery2 ale $db se nepredava a je "am".
  */
function dbQuery($sql)
{
	global $CONF_MYSQL;
	$args = func_get_args();
	$a = array_merge(array($sql,$CONF_MYSQL['d']),array_slice($args,1));
	return call_user_func_array("dbQuery2",$a);
}

/* nahrazovani parametru #N v dbQuery2 */
function dbQuerySQL($sql,$args)
{
	$i=0;
	foreach($args as $a) {
		$i++;
		// quote string
		$aq = $a;
		// -- POZOR v dbQuery patch stefi (pri praci z db nemohu pouzit fetch_uri, krade "\"!)
		// -- nutno pouzivat $_GET a $_POST
		// takze pokud neni nejsou data $_POST (treba select + update) tak je musim escapnout
//		$a = (!get_magic_quotes_gpc() || !$_POST) ? str_replace("'","\\'",$a) : $a;
		if(is_string($a)) $aq = dbQuote($a);
		// --/dbQuery patch stefi
		if (is_null($a)) $aq = 'NULL';
		$sql = preg_replace("/#$i([^0-9]|$)/","$aq\\1",$sql);	
		if (!is_null($a)) {
			$sql = preg_replace("/#\*$i([^0-9]|$)/","'".date("Y-m-d",(int)$a)."'\\1",$sql);
			$sql = preg_replace("/#!$i([^0-9]|$)/","'".date("Y-m-d H:i:s",(int)$a)."'\\1",$sql); 
		}
		else {
			$sql = preg_replace("/#\*$i([^0-9]|$)/","null\\1",$sql);
			$sql = preg_replace("/#!$i([^0-9]|$)/","null\\1",$sql); 
		}

	}
	return $sql;
}


// vraci handle RS
// 3ti a dalsi parametry jsou nahrazeny za #N v dotazu
// vcetne konverze
function dbQuery2($sql, $db)
{
//	return false;
	global $db_conn,$last_rs,$last_err,$CONF_MYSQL,$CONF_BASE_DIR;
	$loglevel = false;
	$dup = 0;
	if (!IsSet($db_conn))
		$db_conn = mysql_connect($CONF_MYSQL['h'],$CONF_MYSQL['u'],$CONF_MYSQL['p']);
	if (!$db_conn) {
		echo "<p>Omlouvame se za nefunkcnost systemu, na odstraneni chyb se pracuje. Dekujeme.</p>";
		$log_file = "logs/err_mysql.log";
		if(!file_exists($CONF_BASE_DIR.$log_file) || filemtime($CONF_BASE_DIR.$log_file) <  (time() - 60 * 30)) { // logovani a posilani upozorneni kazdych 30 minut!
			$file_content = date("Y-m-d h:i:s")."\n";
			mail("dlouhej@vodafonemail.cz, stefi@cdi.cz","err ".$_SERVER["HTTP_HOST"]." | ".$_SERVER["REMOTE_ADDR"],"Can't connect db: $sql");
			mail("stefi@cdi.cz","err ".$_SERVER["HTTP_HOST"]." | ".$_SERVER["REMOTE_ADDR"],"Can't connect db: $sql");
		}	
		if(!file_exists($CONF_BASE_DIR.$log_file) || filemtime($CONF_BASE_DIR.$log_file) <  (time() - 60 * 1)) { // logovani do souboru kazdou 1 minutu!
			logit("err","Can't connect db: $sql", $log_file);
		}	
		unset($last_rs);
		exit;
		return false;
	}
	static $last_db = null;
	if ($last_db != $db) {
		if (!mysql_select_db($db,$db_conn)) {
			$err = mysql_error($db_conn);
			logit("err","Can't select db: $err");
			unset($last_rs);
			return false;
		}
		/* set it after db selection because it takes collation from the db */
		if (mysql_query("set names utf8",$db_conn) !== true)
		{
			$err = mysql_error($db_conn);
			logit("err","Can't set utf8 charset: $err");
		}
		/* set czech lc_time_names */
/*		
		if (mysql_query("SET lc_time_names = 'cs_CZ'",$db_conn) !== true)
		{
			$err = mysql_error($db_conn);
			logit("err","Can't SET lc_time_names = 'cs_CZ': $err");
		}
*/
		$last_db = $db;
	}

	// konverze argumentu dotazu
	$args = func_get_args();
	array_shift($args); array_shift($args);
	$sql = dbQuerySQL($sql,$args);
	if (substr($sql,0,1)=='!') {
		$sql = substr($sql,1); $dup = 1;
	}
	if (substr($sql,0,1) == '?') {
		$sql = substr($sql,1);
		if(mujpc())
			echo color_dump_SQL($sql);
	}
	if(substr($sql,0,1) == 'x') {
		$sql = substr($sql,1);
		$loglevel = "nolog";
	}
		
	$start = getmicrotime();
	$result = mysql_query($sql,$db_conn);
	$dur = round((getmicrotime()-$start)*1000);
	if ((!$result) && ($dup == 0)) {
		$err = mysql_error($db_conn);
		logit("err","SQL: $sql, error: $err [$sql]");
		$last_rs = false; $last_err = $err;
		return false;
	}
	$last_rs = array("rs"=>$result,"row"=>0);
	logit($loglevel ? $loglevel : "debug","SQL: $sql, rows:".dbRows($last_rs)." duration $dur ms");
	return $last_rs;
}

// pocet radek vysledku
function dbRows($rs=-1)
{
	global $last_rs;
	if ($rs < 0) {
		if (!IsSet($last_rs)) {
			logit("err","no rs in dbRows");
			return 0;
		}
		$rs = $last_rs;
	}
	return @mysql_num_rows($rs["rs"]);
}

// vraci pole s dalsim radkem nebo false
// pokud dany radek neexistuje. pokud nezadame
// radek pouzije se o jednu vetsi nez minule
function dbArr($row=-1)
{
	global $last_rs;
	if ($last_rs < 0) {
		if (!IsSet($last_rs)) {
			logit("err","no rs in dbArr");
			return false;
		}
		$last_rs = $last_rs;
	} else $last_rs = $last_rs;
	if (!$last_rs) return false;

	if ($row < 0) $row = $last_rs["row"];
	$last_rs["row"] = $row + 1;
	@ $err = mysql_data_seek($last_rs["rs"],$row);
	if (!$err) return false;
	return mysql_fetch_array($last_rs["rs"]);
}

function dbArr2(&$rs,$row=-1)
{
	global $last_rs;
	if ($rs < 0) {
		if (!IsSet($last_rs)) {
			logit("err","no rs in dbArr2");
			return false;
		}
		$rs = $last_rs;
	} else $last_rs = $rs;
	if (!$rs) return false;

	if ($row < 0) $row = $rs["row"];
	$rs["row"] = $row + 1;
	@ $err = mysql_data_seek($rs["rs"],$row);
	if (!$err) return false;
	return mysql_fetch_array($rs["rs"]);
}

/* dbArrTiny odstrani duplicitni polozky v poli vracenem funkci dbArr */
function dbArrTiny($row=-1) {
	$data = dbArr($row);
	if (!is_array($data)) return $data;
	$dt = array();
	foreach ($data as $k => $v) {
		if (is_int($k)) continue;
		$dt[$k] = $v;
	}
	return $dt;
}

// stefi rozsiril o disabled option jako 3 nepovinny parametr val~name~disabled
function renderOptionsFromStr($selectedValue,$defValue,$str)
{
	if(!$str)
		return false;
	$dupCode = 2; // 0 means to stop using "selected"
	if (!IsSet($selectedValue) || $selectedValue === false) $selectedValue = $defValue;
	if (!is_array($selectedValue)) { 
		/* must use string val for in_array fce */
		$selectedValue = Array((string)$selectedValue);
		$dupCode = 1;
	}
	$data = explode(";",$str);
	for ($i=0;$i<sizeof($data);$i++) {
		$disabled = false;
		$row = explode("~",$data[$i]);
		if (sizeof($row) < 2) $row[] = $row[0];
		if (isset($row[2]) && $row[2] == "disabled") {
			// ms internet explorer, Konqueror ignoruji normu option disabled!!! preskocim nevypisu
			// osetreno ve fci uprav_kategorii!!! 
//			if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE") || strpos($_SERVER["HTTP_USER_AGENT"],"Konqueror")) continue;
			$disabled = "disabled style=\"color:red\"";
		}
		echo "\t<option $disabled value=\"".preg_replace( "/\"/", "&quot;",$row[0])."\"";
		if ($dupCode && (in_array($row[0],$selectedValue) || $defValue == -1)) {
			echo " selected=\"selected\"";
			$dupCode &= 2;
		}
		echo ">".$row[1]."</option>\n";
	}
}

function dbLastErr()
{
	global $last_err;
	if (!$last_err) return '';
	return $last_err;
}
// parses date returned by query into PHP format (seconds)
function dbDate($date)
{
	if (!preg_match('/(\d+)-(\d+)-(\d+)( (\d+):(\d+):(\d+))?/', $date, $regs )) {
		die ("Bad datum format: $date");
		return 0;
	}
	if ($regs[4])
		return mktime($regs[5],$regs[6],$regs[7],$regs[2],$regs[3],$regs[1]);
	return mktime(0,0,0,$regs[2],$regs[3],$regs[1]);
}

// computes number of days between dates
function uDayDiff($d1,$d2)
{
	return $d2/86400-$d1/86400;
}