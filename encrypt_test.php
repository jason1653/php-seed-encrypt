<?php
error_reporting(~E_NOTICE);  
include "Encrypt.php";


$g_bszIV = "00,01,02,03,04,05,06,07,08,09,0A,0B,0C,0D,0E,0F";
$g_bszUser_key = "11";
$cardInfo = "1234567890123456=2212";


/**
 * 테스트 모드 
$return = seedEncrypt($g_bszIV, $g_bszUser_key, $cardInfo, "Y");
*/

/** 
 * 실제
 */
$return = seedEncrypt($g_bszIV, $g_bszUser_key, $cardInfo);

echo "<br>";
echo "결과=".$return;

?>