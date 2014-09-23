<?php
include "../../../wp-load.php";
$current_file_path = dirname(__FILE__);
include_once( $current_file_path.'\config.php');  
//adds variable with type, name, value to existing variable array
if( isset( $_POST['json'] ) ){
	
	$json =  $_POST['json'];
	$json = stripcslashes ( $json );
	$json = json_decode( $json, true );
	$SSID = $json["SSID"];
	echo $SSID;
		$variable_array = array();
		$variable_array = unserialize(get_option('variable_array'.$SSID));
	
	if($json['hidden_variable'] == 'x') {   
		$name = $json["variable_name"];
		$type = $json["variable_type"];
		$exist = get_item($name);
		
		if( !isset($exist) || $exist == false){
		
			if ($type == "string"){
				$value =$json["variable_valueb"];
			}else if ($type =="color"){
				$value =$json["variable_valuea"];
			} else{
				$value =$json["variable_valuec"];
			}
			
			$variable_array[] = array("Title" => $name,  "NAME"=> str_replace(' ', '_', $name), "type"=>$type, "default" => $value, "Value" => $value, "added"=> "hello"); 
		
			update_option('variable_array'.$SSID,serialize($variable_array));
			
		
		}else{
			echo "Already Exists";
		}
	}
}
?>