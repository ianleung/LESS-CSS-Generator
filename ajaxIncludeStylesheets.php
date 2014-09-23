<?php
include "../../../wp-load.php";
$current_file_path = dirname(__FILE__);
include_once( $current_file_path.'\config.php');  
//takes all stylesheets with checked checkboxes and concatenates them together, also updates the minify option. (when lesscss.php is loaded, will compile the concatenated stylesheet)
if( isset( $_POST['json'] ) ){
	$json =  $_POST['json'];
	$json = stripcslashes ( $json );
	$json = json_decode( $json, true );
	$SSID = $json['SSID'];
	$stylesheets = get_option('variable_array_list','nothing');
	$stylesheet_array = array();
		$stylesheet_names = array();
		$stylesheet_compare = array_values($stylesheets);
		$lessvars = '';
		for($x=0;$x<count($stylesheets);$x++){
			if(isset($json[$stylesheet_compare[$x]])){
			$key = array_search($stylesheet_compare[$x],$stylesheets);
				$array = unserialize(get_option('variable_array'.$key));
				$stylesheet_names[] = $stylesheet_compare[$x];
				for ($y=0;$y<count($array);$y++){
					
					if( $array[$y]["NAME"] == "Less_area"){
						if ($form[$y]["Value"]== FALSE){
							$form[$y]["Value"] = $form[$y]["default"];
							}
							else{
							$form[$y]["Value"] = stripslashes($form[$x]["Value"]);
							}
							$lessvars .= "/*".$stylesheet_compare[$x]."*/".lessVarCreator($array,$key, 2).$array[$y]["Value"];		
						}
					}		
				}
			}
			
		
		echo $lessvars;
		update_option('include_stylesheets', $lessvars);
		update_option('include_stylesheet_names', $stylesheet_names);
		if($json['minify']){
		update_option('minify','YES');
		}
		else{
		update_option('minify','NO');
		}
		update_option('sheets_changed_time',1);
}
?>