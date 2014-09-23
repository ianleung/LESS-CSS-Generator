<?php
	include "../../../wp-load.php";
	//creates a new stylesheet with 'name' and increments the stylesheet count by 1
$current_file_path = dirname(__FILE__);
include_once( $current_file_path.'\config.php'); 
	if( isset( $_REQUEST['name']  ) ){
	$stylesheets = get_option('variable_array_list');
	$newSSID = get_item('stylesheet_counter');
	echo $newSSID;
	$newSSID++;
	update_item('stylesheet_counter', $newSSID);
	$stylesheets[$newSSID] = $_REQUEST['name'];
	update_option('variable_array_list',$stylesheets);
		//add variables here that you will create
	$demoHTMLURL = $current_file_path.DIRECTORY_SEPARATOR.'DemoHTML.txt';
	$demoHTML = file_get_contents($demoHTMLURL, true);
	$demoLESS =  file_get_contents('./less-markup-tab.txt', true);
	echo $newSSID;
	}	
	createVariableArray($newSSID);
	update_item('SSID', $newSSID);
?>