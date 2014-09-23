<?php
include "../../../wp-load.php";
$current_file_path = dirname(__FILE__);
include_once( $current_file_path.'\config.php');  
//saves the changes to the stylesheet using ajax
if( isset( $_POST['json'] ) ){

	$json =  $_POST['json'];
	$json = stripcslashes ( $json );
	$json = json_decode( $json, true );
	$SSID = $json['SSID'];
	$compiled = "";
	$cssURL = $current_file_path.DIRECTORY_SEPARATOR.'stylesheet.txt';
	$textstring = file_get_contents($cssURL,true);
		updateAllVariables($SSID,$json);
		$form = unserialize(get_option('variable_array'.$SSID, 'default'));
	
		for ($x = 0; $x < count($form);$x++) {
		$value = $form[$x]["Value"];
			if( $form[$x]["NAME"] == "Less_area"){
				$textfile = fopen($current_file_path.DIRECTORY_SEPARATOR.'stylesheet.css',"w+");
				if ($textfile) {
					$lessvarsExample = lessVarCreator($form,$SSID, 2)."#example{".$value."}";
					$lessvars = lessVarCreator($form,$SSID, 2).$value;	
					$compiled = compileLess($lessvars,$textfile,$textstring);
					$textfile = fopen($current_file_path.DIRECTORY_SEPARATOR.'stylesheet.css',"w+");
					compileLess($lessvarsExample,$textfile,$textstring);
				}
			}
			else if($form[$x]["NAME"] =="css_area"){
			$form[$x]["Value"] = $compiled;
			
			}
			
		}
		update_option('variable_array'.$SSID,serialize($form));
		header('Content-Type: application/json');
		$json = json_encode( array("MESG" => "OK", "NEW_CSS"=>$compiled));
		echo $json;

}
?>