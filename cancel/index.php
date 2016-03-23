<?php 
$screen_debug = false;
$err = (!$screen_debug) ? 0 : E_ALL;
session_start();
error_reporting($err);

include_once 'inc/cancelForm.class.php';
/*  instance tridy Form, 
 *  drzeni hodnoty formulare, pokud nefunguje validace pomoci bootstrap a js */
$frm = new cancelForm();
/* submit formulare - otestovani hodnot, vypsani chyby - hlavne pokud je vypnuty JS*/
$frm->sendCancelForm($_POST);
/* pole hodnot pro select odpovedi */
$answer_arr = array('1'=>'Tuto službu nemám kde využít, není pro mě',
                    '2'=>'Momentálně nemám čas se službou zabývat',
                    '3'=>'Měsíční poplatek je vysoký',
                    '4'=>'Celkově mi služba nevyhovuje',
                    '5'=>'Ani jedna z dostupných aplikací mi nevyhovuje',
                    '6'=>'Efektivita aplikací nesplnila moje očekávání');  
?>
<!DOCTYPE html>
<html lang="cs-CZ">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, maximum-scale=1" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="robots" content="noindex, nofollow" />
<meta name="googlebot" content="noindex, nofollow" />
<meta name="copyright" content="SocialSprinters.cz" />
<meta name="description" content="Zrušení členství služby SocialSprinters" />
<meta property="og:title" content="">
<meta property="og:description" content="">
<meta property="og:image" content="">
<meta property="og:url" content="">
<link rel="shortcut icon" href="favicon.ico">
<link rel="https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,700&amp;subset=latin,latin-ext">
<link rel="stylesheet" href="css/style.css">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>!window.jQuery && document.write('<script src="js/jquery.min.js"><\/script>')</script>
<script src="js/main.js"></script>
<!--[if lt IE 9]>
	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<title>CHCI ZRUŠIT ČLENSTVÍ SLUŽBY SOCIALSPRINTERS</title>
</head>
<body>
<!-- GA - měřící kód -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-55151547-1', 'auto');
  ga('send', 'pageview');
</script>
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','//connect.facebook.net/en_US/fbevents.js');

fbq('init', '631173753652934');
fbq('track', "PageView");
fbq('track', 'Lead');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=631173753652934&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->

<div id="main">
  <header id="header">
    <a href="http://www.socialsprinters.cz/" title="SocialSprinters [Úvodní stránka]" id="logo">SocialSprinters<span></span></a>
  </header>

  <div class="content clearfix">
    <h1>CHCI ZRUŠIT ČLENSTVÍ SLUŽBY SOCIALSPRINTERS</h1>
    <?php echo $frm->getSuccessMessage();?>
    <div class="col fl">
      <img src="img/cancel.png"  width="511" height="360" alt="cancel membership" />
      <h2>Škoda, že nás opouštíte. :(</h2>
      <p>Zrušením členství ztratíte přístup ke všem aplikacím a všechny výhody, které jste spolu s členstvím získali.</p>
      <ul>
        <li>Vyplňte následující údaje ve formuláři.</li>
        <li>Prosím o vyplnění přesných údajů. Nesprávné vyplnění údajů akorát zpomalí váš požadavek.</li>
      </ul>
    </div>
    <div class="col fr">
      <?php echo $frm->getErrorMessage();?>
      <?php include_once 'inc/form.php';?>
    </div>
  </div><!--/content-->

  <footer id="footer">
    <small>&copy; <?php echo date("Y") ?> <strong>SocialSprinters</strong></small>
  </footer>

</div><!--/main-->
</body>
</html>