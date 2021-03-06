<?php
require_once("../inc/inc.php");
require_once("../inc/fce_admin.php");

if(!$_SESSION["access_admin_ss"]) {
	header("location:./");
}
//print_r(getUsers());
require_once("../inc/header.php");
?>
<script type="text/javascript" src="../js/admin.js?time=<?=$CONF_XTRA["TIME_FILES"]?>"></script>
<link href="../css/admin.css" rel="stylesheet" media="all" type="text/css">

<div id="admin">
<?php menu_admin();?>
  <h1>Přehled žádostí</h1>
<?php if (is_array(getCancelRequests())&&(count(getCancelRequests())>0)) {?>    
  <table class="prehled_table">
		<tr>
      <th>#</th>
      <th>Datum žádosti</th>
			<th>FB ID</th>
			<th>Uživatel</th>
      <th>Email</th>
      <th>Důvod</th>
      <th>Poznámka</th>
      <th>Akce</th>
		</tr>
    <?php 
    $i=1; 
    foreach (getCancelRequests() as $key => $req) {
      $email = ($req['email']!='' && $req['email']!='undefined') ? $req['email'] : $req['email_contact'];
      $notice = (!$req['cancel_notice']) ? 'x' : $req['cancel_notice'];
      $reason = ($CONF_XTRA["premium_cancel_reason"]['cs'][$req['cancel_reason']]);
    ?>
      <tr>
        <td><?php echo $i;?></td>
        <td><?php echo htmlspecialchars_decode($req['dt_formatted'])?></td>
        <td><?php echo htmlspecialchars_decode($key)?></td>
        <td><?php echo htmlspecialchars_decode($req['fullName'])?></td>
        <td><?php echo htmlspecialchars_decode($email)?></td>
        <td><?php echo htmlspecialchars_decode($reason)?></td>
        <td><?php echo htmlspecialchars_decode($notice)?></td>
        <td>GoPay akce</td>
      </tr>    
    <?php 
    $i++; 
    } // foreach getUsers?>
  </table>  
<?php } // if getUsers?>
</div><!--/id="admin"-->
<?php require_once("../inc/footer.php"); ?>

<?php
/* return cancel request - owner*/
function getCancelRequests() {
$query_request = "SELECT O.*, CONCAT(O.prijmeni, ' ', O.jmeno) AS fullName,
                         DATE_FORMAT(O.dt_request_cancel, '%d.%m.%Y %H:%i') AS dt_formatted
                    FROM owner AS O
                   WHERE O.dt_request_cancel IS NOT NULL
                     AND typ = 'premium'
                ORDER BY O.dt_request_cancel DESC
                ";
	dbQuery($query_request);
	while($row = dbArrTiny()) {
		$members[$row["fb_id"]]= $row;
  }
  return $members;
}  
//print_r(getCancelRequests());


/*function getUsers() {
	// 1. hash dat odberatele
	dbQuery("SELECT nazev, fb_id FROM odberatel");
	while($row = dbArr()) {
		$hash_odb[$row["fb_id"]] = $row["nazev"];
  }
  return $hash_odb;
}*/

//------------------------------------------------------------------------------

// nactu vsechny najitele, kteru maji zaplacene premium! - RICHARD
/*$wh = " AND gopay_parent_id IS NULL";
$query = "SELECT p.zalozeno, gopay_id, amount, currency, CONCAT(prijmeni, ' ', jmeno) cele_jmeno, kod, fb_id, o.status status_owner 
            FROM platba p, owner o, slev_kody k 
           WHERE state = 'PAID' 
             AND spec_slev_kod IS NOT NULL 
             $wh 
             AND spec_slev_kod=kod 
             AND owner_fb_id=fb_id 
        ORDER BY p.zalozeno";*/

// nactu vsechny najitele, kteru maji zaplacene premium! - PESY
/*$query_m = "SELECT P.zalozeno, P.gopay_id, P.amount, P.currency, CONCAT(O.prijmeni, ' ', O.jmeno) AS fullName, K.kod, O.fb_id, O.status AS statusOwner
              FROM owner AS O
              JOIN slev_kody AS K ON K.owner_fb_id = O.fb_id
              JOIN platba AS P ON P.spec_slev_kod = K.kod
               AND P.state = 'PAID'
               AND P.gopay_parent_id IS NULL
               AND P.spec_slev_kod = K.kod
          GROUP BY O.fb_id
          ORDER BY P.zalozeno
          ";


	dbQuery($query_m);
	while($row = dbArrTiny()) {
		$members[$row["fb_id"]]= $row;
  }*/
  
  /*echo count($members);
  print_r($members);*/
  
//------------------------------------------------------------------------------
  
// nactu vsechny platby! - RICHARD
/*$wh = "";  
$query = "SELECT p.zalozeno, UNIX_TIMESTAMP(p.zalozeno) utime, gopay_id, gopay_parent_id, amount, currency, kod, fb_id
            FROM platba p, owner o, slev_kody k 
           WHERE state = 'PAID' 
             AND spec_slev_kod IS NOT NULL 
             $wh
             AND spec_slev_kod=kod 
             AND owner_fb_id=fb_id 
        ORDER BY zalozeno";*/  
  
  
// nactu vsechny platby! - PESY
/*$query_p = "SELECT P.zalozeno, UNIX_TIMESTAMP(P.zalozeno) AS utime, P.gopay_id, P.gopay_parent_id, P.amount, P.currency, K.kod, O.fb_id
              FROM owner AS O
              JOIN slev_kody AS K ON K.owner_fb_id = O.fb_id
              JOIN platba AS P ON P.spec_slev_kod = K.kod
               AND P.state = 'PAID'
               AND P.spec_slev_kod = K.kod
          ORDER BY P.zalozeno
          ";

  dbQuery($query_p);
	while($row = dbArrTiny()) {
		$members_platby[$row["fb_id"]][] = $row;
	}*/  
  
  /*echo count($members_platby);
  print_r($members_platby);*/