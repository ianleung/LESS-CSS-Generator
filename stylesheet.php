<?php
	$stylesheetID = 0;
	if( isset($_REQUEST["SSID"] ) ){
		$stylesheetID = $_REQUEST["SSID"];
	}
	header("Content-type: text/css", true);
	include "../../../wp-load.php";
	include_once "config.php";
	//gets the variable array with current SSID and echoes it out
	lessVarCreator(unserialize(get_option ('variable_array'.$stylesheetID)),$stylesheetID);
?>
<?php 

$less_css = get_item('Less_area'.$stylesheetID);
if ($less_css == false){
	$less_css = file_get_contents('./less-markup-tab.txt', true);
}
//gets and echoes the less contents, if does not exist, gets the default.
echo $less_css;
?>

