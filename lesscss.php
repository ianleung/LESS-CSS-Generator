<?php
   /* 
    Plugin Name: Less CSS
    Plugin URI: 
    Description: Less CSS Editor
    Author: Ian Leung 
    Version: 1.0 
    Author URI: 
    */  
	
$current_file_path = dirname(__FILE__);
include_once( $current_file_path.'\config.php');


function lesscss_admin() {  
    include('lesscss-admin.php');  
}    
function lesscss_admin_actions() {  
    add_options_page("Less Css", "Less Css", 1, "less-css", "lesscss_admin");  
}  
function add_to_stylesheet(){
//compare times
$changed = get_option('sheets_changed_time');
	if ( $changed == 1){
		
		$default = 'body{color:green !important}';
		$current_file_path = dirname(__FILE__);
		$textfile = fopen($current_file_path.DIRECTORY_SEPARATOR.'stylesheets.css',"w+");
				
		if ($textfile) {
		$include = get_option('include_stylesheets');
		$minify=get_option('minify');
		if ($minify=="YES"){
		$compiled = compileLess($include,$textfile,$default,'YES');
		}
		else{
			$compiled = compileLess($include,$textfile,$default);
			echo "hi";
		}
		echo 'updated stylesheet';		
		update_option ('sheets_changed_time',0);
		}
		
	}
	
	$string = '<link rel="stylesheet" href="wp-content/plugins/less-css/stylesheets.css"/>';	
		
		echo $string; 
}
	
	
//add_action ('wp_head', 'add_to_stylesheet');
//add_action('admin_menu', 'lesscss_admin_actions');
	

?>  