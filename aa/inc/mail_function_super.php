<?php 
// viz http://webcheatsheet.com/php/send_email_text_html_attachment.php

############################################################################
### OPRAVDU FUNCKNI MAIL S PRILOHOU, VCETNE TEXT/HTML ALTERNATIVE!!! :-) ###
### VERZE S PRILOHOU JE NA WEBU výše ! #####################################
############################################################################

/*
###########################################################
########## zavolani s temito parametry : ##################
###########################################################

//define the receiver of the email 
$to = 'rs@cdi.cz'; 
$to = 'richard.stefanca@seznam.cz,rs@cdi.cz'; 
//define the subject of the email 
//$subject = "Test email with attachment";  // pozor tohle hodi v OUtlooku do nevyzadane posty!!!
$subject = "Pokus o pekny email 2"; 
//define the sender of the email 
$from = "obchod@svitok.cz";
//define the attachment 
$attachment_file = 'download/obchodni_podminky_perego.pdf';

$html_body = 
"<h2>Hello World!</h2> 
<p>This is something with <b>HTML</b> formatting.</p>";

###########################################################
########## / zavolani s temito parametry : ################
###########################################################
// volani fce:
mail_function_super($to,$subject,$from,$attachment_file,$html_body);
*/

function mail_function_super($to,$subject,$from,$attachment_file = false,$attachment_content_type = "pdf",$html_body)
{
	//create a boundary string. It must be unique 
	//so we use the MD5 algorithm to generate a random hash 
	$random_hash = md5(date('r', time())); 

	//define the headers we want passed. Note that they are separated with \r\n 
	$headers = "From: $from\r\nReply-To: $from\r\n"; 

$headers .= "Content-Type: text/html; charset=\"utf-8\"\r\n";
$headers .= "Content-Transfer-Encoding: 7bit\r\n";
	//add boundary string and mime type specification 
/*	
	$headers .= "\r\nContent-Type: multipart/alternative; boundary=\"PHP-alt-".$random_hash."\"";

	//read the atachment file contents into a string,
	//encode it with MIME base64,
	//and split it into smaller chunks
	if($attachment_file)
		$attachment = chunk_split(base64_encode(file_get_contents($attachment_file))); 
	//define the body of the message. 
	ob_start(); //Turn on output buffering 

	
?> 
--PHP-alt-<?php echo $random_hash; ?> 
Content-Type: text/plain; charset="utf-8"
Content-Transfer-Encoding: 7bit

<?php
echo strip_tags($html_body);
?>

--PHP-alt-<?php echo $random_hash; ?>  
Content-Type: text/html; charset="utf-8" 
Content-Transfer-Encoding: 7bit

<?=$html_body?>

--PHP-alt-<?php echo $random_hash; ?>-- 
<?
*/
?>
<?php 

	ob_start(); //Turn on output buffering 
	echo $html_body;

	//copy current buffer contents into $message variable and delete current output buffer 
	$message = ob_get_clean(); 
	//send the email 
	$mail_sent = @mail( $to, '=?utf-8?B?'.base64_encode($subject).'?=', $message, $headers ); 
	//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
	
	$status = $mail_sent ? "Mail sent" : "Mail failed"; 
	logit("log", "MAIL STATUS: $status, aplikace_id=".APLIKACE_ID.", from=$from, to=$to, predmet=$subject, session_id=".session_id());
	return $status;
}	
?>
