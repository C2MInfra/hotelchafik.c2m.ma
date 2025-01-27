<?php
class serial_code{
protected $id;
protected $code;
protected $date_a;
//protected $date_v;
//protected $active;

public static function selectAll(){
	$result=connexion::getConnexion()->query("select * from serial_code where id=1  ");
  return $result->fetch(PDO::FETCH_OBJ);
}

public static function selectMaxSerial(){
	$result=connexion::getConnexion()->query("select code from serial_code where id=1  ");
  return $result->fetch(PDO::FETCH_OBJ);

}

	
public static function selectCode($key){

	$result=connexion::getConnexion()->query("select * from serial_code where code = '$key' and id=1  ");
        return $result->fetchAll(PDO::FETCH_OBJ);
	}	
	
	
public static function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);

    return $d && $d->format($format) == $date;
}
	
public static function  crypter($maCleDeCryptage="", $maChaineACrypter){
if($maCleDeCryptage==""){
$maCleDeCryptage=$GLOBALS['_COOKIE']['PHPSESSID'];
}
$maCleDeCryptage = md5($maCleDeCryptage);
$letter = -1;
$newstr = '';
$strlen = strlen($maChaineACrypter);
for($i = 0; $i < $strlen; $i++ ){
$letter++;
if ( $letter > 31 ){
$letter = 0;
}
$neword = ord($maChaineACrypter{$i}) + ord($maCleDeCryptage{$letter});
if ( $neword > 255 ){
$neword -= 256;
}
$newstr .= chr($neword);
}
return base64_encode($newstr);
}	
	
public static function decrypter($maCleDeCryptage="", $maChaineCrypter){
if($maCleDeCryptage==""){
	/*echo "<pre>";
	print_r($GLOBALS);
		echo "</pre>";*/
$maCleDeCryptage=$GLOBALS['_COOKIE']['PHPSESSID'];
}


$maCleDeCryptage = md5($maCleDeCryptage);

$letter = -1;
$newstr = '';

$maChaineCrypter = base64_decode($maChaineCrypter);

$strlen = strlen($maChaineCrypter);
for ( $i = 0; $i < $strlen; $i++ ){
$letter++;
if ( $letter > 31 ){
$letter = 0;
}
$neword = ord($maChaineCrypter{$i}) - ord($maCleDeCryptage{$letter});
if ( $neword < 1 ){
$neword += 256;
}
$newstr .= chr($neword);
}

return $newstr;
}


	

}

?>