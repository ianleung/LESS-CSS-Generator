<?php
//resets variable list by creating a new variable array list and replacing the existing one
include "../../../wp-load.php";
$current_file_path = dirname(__FILE__);
include_once( $current_file_path.'\config.php');  
$json =  $_POST['json'];
$json = stripcslashes ( $json );
	$json = json_decode( $json, true );
	$SSID = $json['SSID'];



createVariableArray($SSID);
?>
