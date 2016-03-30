<?php
include_once './inc/smartemailing.class.php';
$email = 'petr.syrny@centrum.cz';
$se = new SmartEmailing();
$se->createCancelAction($email);