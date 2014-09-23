<?php

//deletes stylesheet at index, if current stylesheet is deleted stylesheet, will default to first item on list
include "../../../wp-load.php";
$current_file_path = dirname(__FILE__);
include_once( $current_file_path.'\config.php'); 
if( isset( $_REQUEST['name']  ) ){
	$stylesheets = get_option('variable_array_list');
	$stylesheet = $_REQUEST['name'] ;
	print_r ($stylesheets);

	for ($x = 0; $x <=get_item('stylesheet_counter');$x++) { 
			if($stylesheets[$x] ==$stylesheet){
				echo $x;
				unset($stylesheets[$x]);
				update_option('variable_array_list',$stylesheets);
				deleteAllVariables($x);
				echo ($x);
				$SSID = get_item('SSID');
				if ($SSID==$x){
				//default to first item on list
				
				$keys = array_keys($stylesheets);

				echo $keys[0];
					update_item('SSID', $keys[0]);
			}
		}
	}
	echo $stylesheet;

}

?>