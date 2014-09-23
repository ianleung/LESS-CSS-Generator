<?php
include "../../../wp-load.php";
$current_file_path = dirname(__FILE__);
include_once( $current_file_path.'\config.php'); 
//deletes all stylesheets
	$stylesheets = get_option('variable_array_list');
	


	for ($x = 0; $x <=get_item('stylesheet_counter');$x++) { 
			if($stylesheets[$x]){
				
				deleteAllVariables($x);
			}
		}
		
	delete_option('variable_array_list');
		




?>