<?php
include_once ('KDPay.class.php');

//商户号
//$P_UserId = '1003716';
//$P_UserId = '1003281';
$P_UserId = '1004854';

//密钥
//$SalfStr = '4be20d2e3e9b4bb983fd09c76b3b87ec';
//$SalfStr = 'ead6dbccc2d54705bfa2ad5e2bd9930b';
$SalfStr = '9d834723120e4fdc80a83c496db23a9e';

$kdPay = new KDPay($P_UserId,$SalfStr);
