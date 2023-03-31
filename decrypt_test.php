<?php
error_reporting(~E_NOTICE);  
include "Encrypt.php";


$g_bszIV = "00,01,02,03,04,05,06,07,08,09,0A,0B,0C,0D,0E,0F";
$g_bszUser_key = "11";
$cardEncode = "Hk+X2nRtwF2/A/3+c8v9oe4YdMNJyBwPPIEKL0fA4+4=";

/**
 * 테스트 모드 
$return = seedDecrypt($g_bszIV, $g_bszUser_key, $cardEncode, "Y");

 */

$return = seedDecrypt($g_bszIV, $g_bszUser_key, $cardEncode);


echo $return;

?>