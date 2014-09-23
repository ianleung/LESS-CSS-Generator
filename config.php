<?php 
function formCreator($SSID=0){
	$current_file_path = dirname(__FILE__);
	$demoHTMLURL = $current_file_path.DIRECTORY_SEPARATOR.'DemoHTML.txt';
	$lessMarkupURL =$current_file_path.DIRECTORY_SEPARATOR.'less-markup-tab.txt' ;
	$demoHTML = stripslashes(file_get_contents($demoHTMLURL, true));
	$lessMarkup = file_get_contents($lessMarkupURL, true);
	$compiledcss = file_get_contents($current_file_path.DIRECTORY_SEPARATOR.'stylesheet.css',true);
	
	if (!(get_option('variable_array'.$SSID)==FALSE)){
	$variable_array = unserialize(get_option('variable_array'.$SSID));
	}
	else{
	$variable_array = array();
	}
	
	$table = array();
		$table[] = array("Title" => "Enter Header Size",  "NAME"=>"Default_Header_Size", "type"=>"number", "default" => "25"); 
		$table[] = array("Title" => "Enter Paragraph Size",  "NAME"=>"Default_Paragraph_Size", "type"=>"number", "default" => "14"); 
		$table[] = array("Title" => "Select Your Font",  "NAME"=>"Font_Family_Selector", "type"=>"string", "default" => "'Helvetica', Arial, Sans-serif"); 
		$table[] = array("Title" => "Default Primary Font Color",  "NAME"=>"Default_Primary_Font_Color", "type"=>"color", "default" => "000000"); 
		$table[] = array("Title" => "Default Primary Color",  "NAME"=>"Default_Primary_Color", "type"=>"color", "default" => "ff0000"); 
		$table[] = array("Title" => "Enter Margin Size",  "NAME"=>"Default_Top_Bottom_Margin", "type"=>"number", "default" => "7"); 
		$table[] = array("Title" => "Enter Border Radius",  "NAME"=>"Default_Border_Radius", "type"=>"number", "default" => "4"); 
		$table[] = array("Title" => "Enter Height Multiplier",  "NAME"=>"Line_Height_Multiplier", "type"=>"normal", "default" => "1.3"); 
		
		$table[] = array("Title" => "HTML Area",  "NAME"=>"HTML_area".$SSID, "type"=>"htmlmixed", "default" => stripslashes($demoHTML )); 
		$table[] = array("Title" => "Less Area",  "NAME"=>"Less_area".$SSID, "type"=>"less", "default" => $lessMarkup ); 

		$table[] = array("Title" => "Css Area",  "NAME"=>"css_area", "type"=>"css", "default" => $compiledcss );
		for ($x = 0; $x < count($variable_array);$x++) { 
			$array = array ("Title" => $variable_array[$x]["Title"],"NAME"=>$variable_array[$x]["NAME"], "type"=>$variable_array[$x]["type"], "default" =>$variable_array[$x]["default"], "added"=>"hello");	
			$table[] = $array;
		}

	
	return $table;
}

function createVariableArray($SSID=0){
	$current_file_path = dirname(__FILE__);
	$demoHTMLURL = $current_file_path.DIRECTORY_SEPARATOR.'DemoHTML.txt';
	$lessMarkupURL =$current_file_path.DIRECTORY_SEPARATOR.'less-markup-tab.txt' ;
	$demoHTML = stripslashes(file_get_contents($demoHTMLURL, true));
	$lessMarkup = file_get_contents($lessMarkupURL, true);
	$compiledcss = file_get_contents($current_file_path.DIRECTORY_SEPARATOR.'stylesheet.css',true);
	
	$table = array();
		$table[] = array("Title" => "Enter Header Size",  "NAME"=>"Default_Header_Size", "type"=>"number", "default" => "25"); 
		$table[] = array("Title" => "Enter Paragraph Size",  "NAME"=>"Default_Paragraph_Size", "type"=>"number", "default" => "14"); 
		$table[] = array("Title" => "Select Your Font",  "NAME"=>"Font_Family_Selector", "type"=>"string", "default" => "'Helvetica', Arial, Sans-serif"); 
		$table[] = array("Title" => "Default Primary Font Color",  "NAME"=>"Default_Primary_Font_Color", "type"=>"color", "default" => "000000"); 
		$table[] = array("Title" => "Default Primary Color",  "NAME"=>"Default_Primary_Color", "type"=>"color", "default" => "ff0000"); 
		$table[] = array("Title" => "Enter Margin Size",  "NAME"=>"Default_Top_Bottom_Margin", "type"=>"number", "default" => "7"); 
		$table[] = array("Title" => "Enter Border Radius",  "NAME"=>"Default_Border_Radius", "type"=>"number", "default" => "4"); 
		$table[] = array("Title" => "Enter Height Multiplier",  "NAME"=>"Line_Height_Multiplier", "type"=>"normal", "default" => "1.3");
		$table[] = array("Title" => "HTML Area",  "NAME"=>"HTML_area", "type"=>"htmlmixed", "default" => $demoHTML ); 
		$table[] = array("Title" => "Less Area",  "NAME"=>"Less_area", "type"=>"less", "default" => $lessMarkup ); 
		$table[] = array("Title" => "Css Area",  "NAME"=>"css_area", "type"=>"css", "default" => $compiledcss );
		for ($x = 0;$x < count($table);$x++){
		$table[$x]['Value']='';
		}
		$table = serialize($table);
	update_option("variable_array".$SSID, $table);
	
}
function get_item($item,$default=FALSE){
			return stripslashes(get_option("new_variable_".$item,$default));
	}
	function update_item($item,$value){
		if( $item != ""){
			update_option("new_variable_".$item,$value);
		}else{
			
		}
	}
	function delete_item($item){
	delete_option("new_variable_".$item);
	}
function lessVarCreator($form,$SSID=0, $return = 0){

	$vars = array();
	$keys = array();
	for ($x = 0; $x < count($form);$x++) { 
		if (!( ($form[$x]["NAME"] == "HTML_area")||($form[$x]["NAME"] == "HTML_area".$SSID)||$form[$x]["NAME"] == "Less_area".$SSID||$form[$x]["NAME"] == "css_area"||$form[$x]["NAME"] == "Less_area")){
			if ($form[$x]["type"] == "number"){
				$vars[ $form[$x]["NAME"] ] =  stripslashes($form[$x]["Value"])."px";
			}else if ($form[$x]["type"] == "color"){
				$vars[ $form[$x]["NAME"] ] = "#".stripslashes($form[$x]["Value"]);
			}else{
				$vars[ $form[$x]["NAME"] ] = stripslashes($form[$x]["Value"]);
			}
		}
	}
	if( $return == 0 ){
		$keys = array_keys ($vars);
		for($x = 0; $x < count($vars);$x++) { 
			echo "@".$keys[$x].":".$vars[ $keys[$x] ].";"."\n";
		}

	}else if ( $return == 1 ){
		$keys = array_keys ($vars);
		for($x = 0; $x < count($vars);$x++) { 
			$str .= "@".$keys[$x].":".$vars[ $keys[$x] ].PHP_EOL;
		}
	}
	
	else {
		$keys = array_keys ($vars);
		for($x = 0; $x < count($vars);$x++) { 
			$str .= "@".$keys[$x].":".$vars[ $keys[$x] ].";";
		}
	}
	return $str;
}

function searchForName($id, $array) {
if (is_array($array)){
   foreach ($array as $key => $val) {
       if ($val['NAME'] == $id) {
        
		   return $key;
       }
   }
   }
   return FALSE;
}
function minifyFileWithPath($file){
$options = array('type' => 'css',
                             'linebreak' => true,
                             'verbose' => true,
                             'nomunge' => true,
                             'semi' => true,
                             'nooptimize' => true);
$current_file_path = dirname(__FILE__);
include_once($current_file_path.'\yuicompressor.php');
$JAR_PATH = dirname(__FILE__).'\minifylessphp\yuicompressor-2.4.8.jar';
$TEMP_FILES_DIR = dirname(__FILE__).'\minifylessphp\tmp';
$yui = new YUICompressor($JAR_PATH, $TEMP_FILES_DIR,$options);
$yui->addFile($file);
$code = $yui->compress();

return $code;
}
function minifyString($string){
$current_file_path = dirname(__FILE__);
include_once($current_file_path.'\yuicompressor.php');
$JAR_PATH = dirname(__FILE__).'\minifylessphp\yuicompressor-2.4.8.jar';
$TEMP_FILES_DIR = dirname(__FILE__).'\minifylessphp\tmp';
$yui = new YUICompressor($JAR_PATH, $TEMP_FILES_DIR);
$yui->addString($string);
$code = $yui->compress();
return $code;
}


function compileLess($lessvars,$textfile,$default='',$minify = 'NO'){
	$current_file_path = dirname(__FILE__);
	include_once ($current_file_path.'\lessc.inc.php');
	$less = new lessc();
	try {
		 $compiled = $less->compile( $lessvars );
	 	 if ($minify == 'YES'){
			$compiled = compress($compiled);
		 } 
	} catch (exception $e) {
		$error = $e->getMessage();
		 // 
	}
	if ($error==''){
		fwrite ($textfile,$compiled);
		}
		else{
		echo $error;
		fwrite ($textfile,$default);
		}
		
		fclose($textfile);
		return $compiled;
}
function clearItemValue ($item){
	echo "deleting".$item; 
delete_item($item);
}

function changeVariableArrayWithItem($name,$SSID=0,$value=FALSE,$type=FALSE){
	$variable_array = array();
	$variable_array = unserialize(get_option('variable_array'.$SSID));
	$key = searchForName($name,$variable_array);
	$previousType = $variable_array[$key]["type"];
	$newName =  str_replace(' ', '_', $name);
	if(!($key===FALSE)){
	if($value===FALSE){
	$value = $variable_array[$key]["default"];
	}
	if($type===FALSE){
		$type = $variable_array[$key]["type"];
	}
	else if (!($type == $previousType)){
		clearItemValue($newName);
	
	}
		
	unset($variable_array[$key]);
	$variable_array = array_values($variable_array);
	$variable_array[] = array("Title" => $name,  "NAME"=> $newName, "type"=>$type, "default" => $value, "Value" => $value, 'added'=> 'hello');
	update_option('variable_array'.$SSID,serialize($variable_array));
	
	return "HI";
		
	}
	else {
	return FALSE;
	}
}

function updateAllVariables($SSID,$json=null){
	$form = unserialize(get_option('variable_array'.$SSID, 'default')); 
	for ($x = 0; $x < count($form);$x++){
		if ($json){
			$value = ( $json[ $form[$x]["NAME"] ] == "" ? $form[$x]["default"] :  $json[ $form[$x]["NAME"] ] );
		}
		else{
			$value = $form[$x]["default"];
		}
		$form[$x]["Value"] = $value ;
	}
	update_option('variable_array'.$SSID,serialize($form));
update_item('SSID',$SSID);
}

function deleteAllVariables($SSID){
delete_option("variable_array".$SSID);

}


function compress($compressed){
                //compress unnecesary spaces 
                $pattern = array('/\>[^\S ]+/s','/[^\S ]+\</s','/(\s)+/s','/[\r\n]/'); 
                $replace = array('>','<','\\1',''); 
                $compressed = preg_replace($pattern, $replace, $compressed);
                return $compressed; 
}



	



?>

