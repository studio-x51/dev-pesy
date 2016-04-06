<?php
include_once 'Config.class.php';
include_once 'Base.class.php';
include_once 'GoPay.class.php';


$payment_data = array(
								"contact"=>
									array(
									'first_name'=>'Jan',
									'last_name'=>'Soural',
									'email'=>'info@kulturne.com',
									'phone_number'=>'+420724528287',
									'city'=>'Chotoviny',
									'street'=>'K Václavu 190',
									'postal_code'=>'39137',
									"country_code"=>'CZE')
								);

$gopay = new GoPay();
$gopay->setPaymentData($payment_data);
echo $gopay->gateWayInline();


//$token = $gopay->getPaymentToken();
//echo $token->token_type;
//$gopay->createPayment();
//echo $gopay->gateWayRedirect();