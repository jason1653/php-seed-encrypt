<?php
include "KISA_SEED_CBC.php";

/** String to Hex 변환 */
function strToHex($string){

	$hex='';
	for ($i=0; $i < strlen($string); $i++){
		$hex .= "," . dechex(ord($string[$i]));	
	}
	
	return $hex;
}

/** Hex -> Str 변환 */
function hexToStr($hex){

	$string='';
	
	for ($i=0; $i < strlen($hex)-1; $i+=2){
		$string .= chr(hexdec($hex[$i].$hex[$i+1]));
	}	
	return $string;
	
}

/**
 * seed 암호화
 * $bszIV: vi값
 * $bszUser_key: 가맹점ID
 * $str: 암호화할 문자열
 */
function seedEncrypt($bszIV, $g_bszUser_key, $str, $testYn="N") {
    /** 사용자 아이디 16자리로 생성 */
    $key = str_pad($g_bszUser_key, 16,"0");
    $key = strToHex($key);
	$key = substr( $key , 1, strlen($key)); 


    /** 암호화할 문자열 HEX로 변환 */
    $encodeStr = "";
    $encodeStr = strToHex($str);
    $encodeStr = substr( $encodeStr , 1, strlen($encodeStr)); 

    if($testYN == "Y") {

        echo "----------- KEY ----------------";
        echo "<br>";
        echo $key;
        echo "<br>";
        echo "----------- KEY ----------------";
    
    
    
        echo "<br><br>";
        echo "------------- plaintext ---------------";
        echo "<br>";
        echo $encodeStr;
        echo "<br>";
        echo "------------- plaintext ---------------";
    }




    $planBytes = explode(",",$encodeStr);
    $keyBytes = explode(",",$key);
    $IVBytes = explode(",",$bszIV);

    for($i = 0; $i < 16; $i++)    {
		@$keyBytes[$i] = hexdec($keyBytes[$i]);
		$IVBytes[$i] = hexdec($IVBytes[$i]);
	}


	for ($i = 0; $i < count($planBytes); $i++) {
		@$planBytes[$i] = hexdec($planBytes[$i]);
	}


	if (count($planBytes) == 0) {
		return $str;
	}

    $bszChiperText = KISA_SEED_CBC::SEED_CBC_Encrypt($keyBytes, $IVBytes, $planBytes, 0, count($planBytes));

	$r = count($bszChiperText);


    $ret = "";
	for($i=0;$i< $r;$i++) {
		$ret .= sprintf("%02X", $bszChiperText[$i]).",";
	}

    $returnData = substr($ret,0,strlen($ret)-1);

    $base64Data = str_replace(",", "", $returnData);
    $base64Data = hexToStr($base64Data);


    if($testYn == "Y") {
        echo "<br><br>";
        echo "-------------- Ciphertext ----------------";
        echo "<br>";
        echo $returnData;
        echo "<br>";
        echo "------------- Ciphertext ---------------";
    
    
        
        echo "<br><br>";
        echo "-------------- str_base64encode ----------------";
        echo "<br>";
        echo base64_encode($base64Data);
        echo "<br>";
        echo "------------- str_base64encode ---------------";
    
    }

    return base64_encode($base64Data);

}

function seedDecrypt($bszIV, $bszUser_key, $str, $testYn="N") {
    /** 사용자 아이디 16자리로 생성 */
    $key = str_pad($bszUser_key, 16,"0");
    $key = strToHex($key);
	$key = substr( $key , 1, strlen($key)); 

    $decordStr = base64_decode($str);
    $decordStr = strToHex($decordStr);
    $decordStr = substr( $decordStr , 1, strlen($decordStr));  // Hex 변환시 값 사이에 콤마를 찍는데, 맨 앞의 콤마를  삭제합니다.

    if($testYn == "Y") {
        echo "----------- KEY ----------------";
        echo "<br>";
        echo $key;
        echo "<br>";
        echo "----------- KEY ----------------";


        echo "<br><br>";
        echo "-------------- Ciphertext ----------------";
        echo "<br>";
        echo $decordStr;
        echo "<br>";
        echo "------------- Ciphertext ---------------";
    }



    $planBytes = explode(",",$decordStr);
	$keyBytes = explode(",",$key);
	$IVBytes = explode(",",$bszIV);


	for($i = 0; $i < 16; $i++)    {
		$keyBytes[$i] = hexdec($keyBytes[$i]);
		$IVBytes[$i] = hexdec($IVBytes[$i]);
	}

	for ($i = 0; $i < count($planBytes); $i++) {
		$planBytes[$i] = hexdec($planBytes[$i]);
	}

	if (count($planBytes) == 0) {
		return $str;
	}

    $bszPlainText = KISA_SEED_CBC::SEED_CBC_Decrypt($keyBytes, $IVBytes, $planBytes, 0, count($planBytes));

    for($i=0;$i< sizeof($bszPlainText);$i++) {
		$planBytresMessage .= sprintf("%02X", $bszPlainText[$i]).",";
	}
	$returnData = substr($planBytresMessage,0,strlen($planBytresMessage)-1);


    $decordStr = str_replace(",","", $returnData); // 디코드된 Hex 값 사이의 콤마를 제거합니다. 
    $decordStr = hexToStr( $decordStr);


    if($testYn == "Y") {
        echo "<br><br>";
        echo "-------------- SEED_CBC_Decrypt ----------------";
        echo "<br>";
        echo $returnData;
        echo "<br>";
        echo "------------- SEED_CBC_Decrypt ---------------";

        


        echo "<br><br>";
        echo "-------------- 복호화 정보 ----------------";
        echo "<br>";
        echo $decordStr;
        echo "<br>";
        echo "------------- 복호화 정보 ---------------";
    }
    

    return $decordStr;
}





?>