<?php
mb_internal_encoding("UTF-8");
include_once 'Config.class.php';
include_once 'Base.class.php';
include_once 'GoPay.class.php';
$gopay = new GoPay();
$payment = $gopay->getPaymentState($_GET['id']);
echo Gopay::$payment_state[$payment->state] ;
//print_r($gopay->getPaymentState($_GET['id']));