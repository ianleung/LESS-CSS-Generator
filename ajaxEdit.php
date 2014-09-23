<?php
//ajax edit - edits the variable provided in the textbox 
include "../../../wp-load.php";
$current_file_path = dirname(__FILE__);
include_once( $current_file_path.'\config.php');  

if( isset( $_POST['json'] ) ){

	$json =  $_POST['json'];
	$json = stripcslashes ( $json );
	$json = json_decode( $json, true );
	$SSID = $json["SSID"];

		$variable_array = array();
		$variable_array = unserialize(get_option('variable_array'.$SSID));
	
	if($json['hidden_variable'] == 'x') {   
		$name = $json[variable_name];
		$type = $json[variable_type];
		if ($type == "string"){
			$value =$json[variable_valueb];
			
		  }
		  else if ($type =="color"){
		  $value =$json[variable_valuea];
		
		  }
		  else{
		  	 $value =$json[variable_valuec];
			
		  }
		$result = changeVariableArrayWithItem($name,$SSID,$value,$type);
		if ($result === FALSE){
		echo "FAILURE";
		}
		else{
		echo "SUCCESSFULLY UPDATED";
		echo $result;
		}
	}
}
?>