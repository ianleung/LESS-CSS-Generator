<?php
	include "../../../wp-load.php";
$current_file_path = dirname(__FILE__);
include_once( $current_file_path.'\config.php'); 
//ajax delete - deletes the variable from the textfield 
	if( isset( $_REQUEST['name']  ) ){
		$variable_array = array();
		$SSID = get_item('SSID');
		$variable_array = unserialize(get_option('variable_array'.$SSID));
		$value = $_REQUEST['name'] ;

		for ($x = 0; $x < count($variable_array);$x++) { 
			if($variable_array[$x]['NAME'] ==$value){
			echo $x;
			unset($variable_array[$x]);
			$variable_array = array_values($variable_array);
			update_option('variable_array'.$SSID,serialize($variable_array));
			
			}
		}
		echo $variable_array;
		echo $value;
		
	}

	
?>