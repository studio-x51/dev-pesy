<?php
include_once 'Config.class.php';
include_once 'Base.class.php';
include_once 'GoPay.class.php';


$gopay = new GoPay();

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
									"country_code"=>'CZE'),
								"recurrence"=>
									array(
									'recurrence_cycle'=>$gopay::$payment_cycle['M'],
									'recurrence_period'=>'1',
									'recurrence_date_to'=>'2016-12-31')									
								);

//print_r($payment_data);


/*$gopay->setPaymentData($payment_data);
echo $gopay->gateWayInline($gopay->createRecurrencePayment());*/


//$token = $gopay->getPaymentToken();
//echo $token->token_type;
//$gopay->createPayment();
//echo $gopay->gateWayRedirect();