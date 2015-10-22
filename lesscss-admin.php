<div style=" font-weight: bold;
  color: #000000;
  margin:25px 250px; font-size:72px;">LESS CSS EDITOR!</div>
<?php	

$path = "/wp-content/plugins/less-css";
$current_file_path = dirname(__FILE__);
include_once( $current_file_path.'\config.php');
//this page defines what you see in the control page in Settings
$newSSID = get_item('stylesheet_counter','nothing');
$stylesheets = get_option('variable_array_list','nothing');

if ($newSSID=='nothing'){
echo "creating stylesheet";
$newSSID = 0;
update_item('stylesheet_counter', $newSSID);
createVariableArray($newSSID);
$stylesheets =array();
$stylesheets[] = 'defaultstylesheet';
	update_option('variable_array_list',$stylesheets);
	update_item('SSID',$newSSID);
}
else if (($stylesheets =='nothing'||$stylesheets==""||empty($stylesheets))){
echo "creating another stylesheet";
$newSSID=0;
update_item('stylesheet_counter', $newSSID);
createVariableArray($newSSID);
$stylesheets =array();
$stylesheets[] = 'defaultstylesheet';
	update_option('variable_array_list',$stylesheets);
	update_item('SSID',$newSSID);
}
	$path = "/wp-content/plugins/less-css";
	$current_file_path = dirname(__FILE__);
	include_once( $current_file_path.'\config.php');
	$SSID = get_item('SSID','nothing');
	if ($SSID =='nothing'){
	$SSID = 0;	
	}

	$demoHTMLURL = $current_file_path.DIRECTORY_SEPARATOR.'DemoHTML.txt';
	$demoHTML = file_get_contents($demoHTMLURL, true);
	$cssURL = $current_file_path.DIRECTORY_SEPARATOR.'stylesheet.txt';
	$textstring = file_get_contents($cssURL,true);

	$form = unserialize(get_option('variable_array'.$SSID, 'default')); 
	
	
	//checks if hidden variable y exists
	if($_POST['hidden_variable'] == 'Y') {
	

		$SSID =$_POST['stylesheet_select']; 
		update_item('SSID',$SSID);
		$form = unserialize(get_option ('variable_array'.$_POST['SSID']));
		for ($x = 0;$x < count($form);$x++){
			$form[$x]["Value"]= stripslashes( $_POST[ $form[$x]["NAME"] ] == "" ? $form[$x]["default"] :  $_POST[ $form[$x]["NAME"] ]  );
		}
		$form = (serialize($form));
		update_option('variable_array'.$_POST['SSID'],$form);
		$form = unserialize(get_option ('variable_array'.$SSID));
		for ($x = 0; $x < count($form);$x++) { 
			
			 if ($form[$x]["Value"]== FALSE){
			 
			$form[$x]["Value"] = $form[$x]["default"];
			}
			else{
			$form[$x]["Value"] = stripslashes($form[$x]["Value"]);
			}
		
			if( $form[$x]["NAME"] == "HTML_area"){
				$demoHTML = $form[$x]["Value"];
			}
			else if( $form[$x]["NAME"] == "Less_area"){
				$textfile = fopen($current_file_path.DIRECTORY_SEPARATOR.'stylesheet.css',"w+");
				if ($textfile) {
				$lessvarsExample= lessVarCreator($form,$SSID, 2)."#example{". $form[$x]["Value"]."}";	
					$lessvars = lessVarCreator($form,$SSID, 2).$form[$x]["Value"];	
					$compiledcss = compileLess ($lessvars,$textfile,$textstring);
					$textfile = fopen($current_file_path.DIRECTORY_SEPARATOR.'stylesheet.css',"w+");
					compileLess ($lessvarsExample,$textfile,$textstring);
				}
			} 
		}	
		
	
	}else{
		
		
	
		for ($x = 0; $x < count($form);$x++) { 
			if($form[$x]["Value"]== ''){
				$form[$x]["Value"] = $form[$x]["default"];
			}
			else{
			$form[$x]["Value"] = stripslashes($form[$x]["Value"]);
			}
			if( $form[$x]["NAME"] == "HTML_area"){
				$demoHTML = $form[$x]["Value"];
			}
		}
	}
	
	$stylesheet_names = get_option ('include_stylesheet_names');
	if (!$stylesheet_names){
		$stylesheet_names = array();
		}
		
		
	
?>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="<?php echo $path."/"; ?>css/jquery-ui-1.10.3.custom.min.css" />
	<link rel="stylesheet" href="<?php echo $path; ?>/lib/codemirror.css">
	<script src="<?php echo $path; ?>/lib/codemirror.js"></script>
	<script src="<?php echo $path; ?>/mode/xml/xml.js"></script>
	<script src="<?php echo $path; ?>/mode/javascript/javascript.js"></script>
	<script src="<?php echo $path; ?>/mode/less/less.js"></script>
	<script src="<?php echo $path; ?>/mode/css/css.js"></script>
	<script src="<?php echo $path; ?>/stylesheet.php?ssid=<?php echo $SSID; ?>"></script>
	<link id="pagestyle" rel="stylesheet" type="text/less" href="<?php echo $path."/"; ?>stylesheet.css"/>
	<style>
		.CodeMirror {
			border: 1px solid #000;
		}
	</style>
	<script src="<?php echo $path; ?>/mode/htmlmixed/htmlmixed.js"></script>
	<script type="text/javascript" src="<?php echo $path."/"; ?>js/jquery-1.9.1.js"></script>
	<script src="<?php echo $path; ?>/jscolor/jscolor.js"></script>
	<script type="text/javascript" src="<?php echo $path."/"; ?>js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="<?php echo $path."/"; ?>js/less-1.4.1.min.js"></script>
	<script type="text/javascript">
	less.refresh();
	var newURL='';
	
	function writeToFile(){
	
		$('#example').html(  document.getElementById("code_HTML_area").value );
	}
	$(function(){
		 $('#check-all').click(function(){
		 	event.preventDefault();
			$("input:checkbox").prop('checked', true).checkboxradio('refresh');
		});
		$('#uncheck-all').click(function(){
			event.preventDefault();
			$("input:checkbox").prop('checked', false).checkboxradio('refresh');
		});
		$('#addvar_form').submit(function(event){
		event.preventDefault();
			var frm = $('#addvar_form');
			var postData = JSON.stringify(frm.serializeObject());
			var postArray = { json:postData };
			
			$.ajax({
				type: 'POST',
				url: "/wp-content/plugins/less-css/ajaxHandlerNew.php",
				data: postArray,
				success: function(data){
				if( $.trim(data) == "Already Exists" ){
				alert("Variable with same name already exists!");
				}
				else {
				alert("Variable created!");
				if( newURL ){
						//alert("pre-reload");
						
						window.location = newURL;
location.reload();
						
					}else{		
						window.location = window.location;
					}
				}
				}
			});		
			return false;
		});
		$(window).scroll(function() {
			//saves scroll value to scroll variable, and sets it to #scrollPosition, which is a hidden variable in the form; this occurs whenever page is scrolled
			var scrollTop = $(window).scrollTop();
			//shove this somewhere
			$('#scrollPosition').val( scrollTop );
		});
		function scrollBackTo( position ){
			//runs when page is loaded, animates the page to the scroll position
			$('html, body').animate({
					scrollTop: position}, 500);
			}
			//applying Code Mirror text editor to all textareas
			<?php for ($x = 0; $x < count($form);$x++) { 
				if (($form[$x]["type"] == "htmlmixed") || ($form[$x]["type"] =="less")||($form[$x]["type"] =="css")){?>
					var myScript<?php echo "_".$x; ?> = document.getElementById("<?php echo "code_".$form[$x]["NAME"];   ?>");
					myCodeMirrorCode<?php echo "_".$x; ?> = CodeMirror.fromTextArea(myScript<?php echo "_".$x; ?>, { 
						mode: "<?php echo $form[$x]["type"];   ?>",    
						lineNumbers: true, <?php if ($form[$x]["type"] == 'css'){
							echo 'readOnly:"true" ';
						}?>
					});
					//myCodeMirrorCode<?php echo "_".$x; ?>.setSize(500,300);
					myCodeMirrorCode<?php echo "_".$x; ?>.on('change',function(cMirror){
						// get value right from instance
						$(myScript<?php echo "_".$x; ?>).val( cMirror.getValue() );
					});
				<?php }
			}?>
			//activates jquery tabs
			$( "#tabs" ).tabs();
			//gets your saved scroll position here
			scrollBackTo( <?php echo $_POST['scrollPosition']; ?> ); 
			//prevents default of cntrl-save and lets user save - see the #ajaxSave function.
			$(document).bind('keydown', function(e){
				if (String.fromCharCode(e.keyCode) == 'S' && e.ctrlKey) {
					e.preventDefault();
					$("#ajaxSave").click();
				}
			});		
		$('#tabs > ul li a').click(function(event){
			//assigns new url when tab is clicked so that page refresh will bring you back to same tab
			event.preventDefault();
			newURL = $('#postURL').text() + $(this).attr('href');
			$('#addvar_form').attr('action', newURL);
			$('#lesscss_form').attr('action', newURL);
			//refreshes the code mirror for the compiled less css tab to make it visible
			myCodeMirrorCode_10.refresh();	
		});
		//serialize function that will be used
		$.fn.serializeObject = function(){
			var o = {};
			var a = this.serializeArray();
			$.each(a, function() {
				if (o[this.name] !== undefined) {
					if (!o[this.name].push) {
						o[this.name] = [o[this.name]];
					}
					o[this.name].push(this.value || '');
				} else {
					o[this.name] = this.value || '';
				}
			});
			return o;
		};
		$( ".variable_type" ).change(function() {
		var value = $(this).find("option:selected").val();
		if (value == "string"){
			$('#code_variable_value2').attr('required','required').show();
			$('#code_variable_value1').removeAttr('required').hide();
			$('#code_variable_value3').removeAttr('required').hide();
		  }
		  else if (value =="color"){
		  $('#code_variable_value2').removeAttr('required').hide();
			$('#code_variable_value1').attr('required','required').show();
			$('#code_variable_value3').removeAttr('required').hide();
		  }
		  else{
		  	  $('#code_variable_value2').removeAttr('required').hide();
			$('#code_variable_value1').removeAttr('required').hide();
			$('#code_variable_value3').attr('required','required').show();
		  }
		});
		 
	var name = $( "#name" ),
      email = $( "#email" ),
      password = $( "#password" ),
      allFields = $( [] ).add( name ).add( email ).add( password ),
      tips = $( ".validateTips" );
 
    function updateTips( t ) {
      tips
        .text( t )
        .addClass( "ui-state-highlight" );
      setTimeout(function() {
        tips.removeClass( "ui-state-highlight", 1500 );
      }, 500 );
    }
 
    function checkLength( o, n, min, max ) {
      if ( o.val().length > max || o.val().length < min ) {
        o.addClass( "ui-state-error" );
        updateTips( "Length of " + n + " must be between " +
          min + " and " + max + "." );
        return false;
      } else {
        return true;
      }
    }
 
    function checkRegexp( o, regexp, n ) {
      if ( !( regexp.test( o.val() ) ) ) {
        o.addClass( "ui-state-error" );
        updateTips( n );
        return false;
      } else {
        return true;
      }
    }
 
		 $( "#dialog-form" ).dialog({
      autoOpen: false,
      height: 300,
      width: 350,
      modal: true,
      buttons: {
        "Create stylesheet": function() {
          var bValid = true;
          allFields.removeClass( "ui-state-error" );
 
          bValid = bValid && checkLength( name, "name", 3, 16 );
         
 
          bValid = bValid && checkRegexp( name, /^[a-z]([0-9a-z_])+$/i, "Stylesheet name may consist of a-z, 0-9, underscores, begin with a letter." );
          // From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
         
 
          if ( bValid ) {
           var value = name.val();
		
		
		$.ajax({
				type: 'POST',
				url: "/wp-content/plugins/less-css/createFileHandler.php?name=" + value,
				success: function(data){
				location.reload();
				}
			});
            $( this ).dialog( "close" );
          }
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      },
      close: function() {
        allFields.val( "" ).removeClass( "ui-state-error" );
      }
    });
 
    $( "#create-stylesheet" )
      .button()
      .click(function() {
	  event.preventDefault();
        $( "#dialog-form" ).dialog( "open" );
      });
	  $('#include-stylesheets').click(function(event){
	  event.preventDefault();
			var frm = $('#lesscss_form');
			var postData = JSON.stringify(frm.serializeObject());
			var postArray = { json:postData };
			$.ajax({
				type: 'POST',
				url: "/wp-content/plugins/less-css/ajaxIncludeStylesheets.php",
				data: postArray,
				success: function(data){
					alert("Updated Stylesheets, refresh lavacorp.fmginc.com to see the changes!");
				}
			});
	  });
	  
		$('#ajaxSave').click(function(event){
			//enables saving through ajax handling
			event.preventDefault();
			var frm = $('#lesscss_form');
			var postData = JSON.stringify(frm.serializeObject());
			var postArray = { json:postData };
			//displays spinner and "saving"
			$('#saveMsg').html('<img src="<?php  echo $path."/" ;?>ajax-loader.gif">  Saving...').show();
			$.ajax({
				type: 'POST',
				url: "/wp-content/plugins/less-css/ajaxHandler.php",
				data: postArray,
				success: function(data){
					if( $.trim(data.MESG) == "OK" ){
					//shows saved if ajax handler succeeds and returns ok
						 $('#saveMsg').html('<span style="color: #000;">Saved!</span>').fadeOut(1500);
						 //force remove
						 $('#pagestyle').remove();
						 //re-append (force the reload)
						// $('head').append('<link id="pagestyle" rel="stylesheet" type="text/less" href="<?php echo $path."/"; ?>stylesheet.php?SSID=' +  <?php echo $SSID; ?>+ '&reload' + ( Math.floor(Math.random() * (1000 - 1 + 1)) + 1 ) + '" />');
						$('head').append( '<link id="pagestyle" rel="stylesheet" type="text/less" href="<?php echo $path."/"; ?>stylesheet.css"/>');
						 //refresh that less!
						less.refresh();
						//sets new stylesheet.css to the textarea, and reassigns codemirror to it.
						myCodeMirrorCode_10.setValue( data.NEW_CSS );
					}else{    
					//displays error message
						$('#saveMsg').html('<span style="color: #f00;">ERROR! - COULD NOT SAVE</span>');
					}
				}
			});
		});
		$('#delete-all-stylesheets').click(function(event){
			event.preventDefault();
			var frm = $('#addvar_form');
			var postData = JSON.stringify(frm.serializeObject());
			var postArray = { json:postData };
		$.ajax({
				type: 'POST',
				url: "/wp-content/plugins/less-css/deleteAllStylesheets.php",
				data: postArray,
				success: function(data){
				alert('All stylesheets deleted upon refresh');
				location.reload();
				}
			});
		});
		$('#delete-stylesheet').click(function(event){
		
			event.preventDefault();
			var value = $( "#code_stylesheet_select option:selected" ).text();
			alert(value);

		$.ajax({
				type: 'POST',
				url: "/wp-content/plugins/less-css/deleteStylesheetHandler.php?name=" + value,

				success: function(data){
				alert("Deleted Stylesheet on Refresh!");
				location.reload();
				}
			});
		
		});
		$('#resetAll').click(function(event){
		event.preventDefault();
			var frm = $('#addvar_form');
			var postData = JSON.stringify(frm.serializeObject());
			var postArray = { json:postData };
		$.ajax({
				type: 'POST',
				url: "/wp-content/plugins/less-css/ajaxReset.php",
				data: postArray,
				success: function(data){
				

				if( newURL ){
						window.location = newURL;
						location.reload();
						
					}else{		
						window.location = window.location;
					}
				}
			});
		});
		$('.deleteVariable').click(function(event){  
			event.preventDefault();
			var value = $(this).attr('name');
		$.ajax({
				type: 'POST',
				url: "/wp-content/plugins/less-css/ajaxDelete.php?name=" + value,
				success: function(data){
				alert("Deleted on Refresh!");
				if( newURL ){

						window.location = newURL;
location.reload();
						
					}else{		
						window.location = window.location;
					}
				}
			});
		
		});
		
		$('#editVariable').click(function(event){
		event.preventDefault();
			var frm = $('#addvar_form');
			var postData = JSON.stringify(frm.serializeObject());
			var postArray = { json:postData };
		$.ajax({
				type: 'POST',
				url: "/wp-content/plugins/less-css/ajaxEdit.php",
				data: postArray,
				success: function(data){
									if( $.trim(data) == "FAILURE" ){
									alert("Variable cannot be edited as it does not exist!");
				}
				else{
					alert("Edited on Refresh!");
					if( newURL ){
						window.location = newURL;
						location.reload();
						
					}else{		
						window.location = window.location;
					}
				}
				}
			});
		});
		$('.changeVariable').click(function(event){
		event.preventDefault();
		var getValue = 'code_';
		var getType = 'hidden_type_';
		var name = $(event.target).attr('name');
		getValue += name;
		getType += name;

		var theType = document.getElementById(getType).value;
		var textvalue = document.getElementById(getValue).value;

		document.addvar_form.variable_name.value = name;
		
		if (theType =="color"){
			document.getElementById('code_variable_type').value = "color";
			document.addvar_form.variable_valuea.value = "";
			document.addvar_form.variable_valuea.value += textvalue;
			document.addvar_form.variable_valuea.style.display = "inline";
			document.addvar_form.variable_valueb.style.display = "none";
			document.addvar_form.variable_valuec.style.display = "none";
		}
		else if (theType =="string"){
			document.getElementById('code_variable_type').value = "string";
			document.addvar_form.variable_valueb.value = "";
			document.addvar_form.variable_valueb.value += textvalue;
			document.addvar_form.variable_valueb.style.display = "inline";
			document.addvar_form.variable_valuea.style.display = "none";
			document.addvar_form.variable_valuec.style.display = "none";
		}
		else{
			document.getElementById('code_variable_type').value = "number";
			document.addvar_form.variable_valuec.value = "";
			document.addvar_form.variable_valuec.value += textvalue;
			document.addvar_form.variable_valuec.style.display = "inline";
			document.addvar_form.variable_valuea.style.display = "none";
			document.addvar_form.variable_valueb.style.display = "none";
		}
		alert ("Press Edit to change!");
		});
	});	
	

</script>
</head>
<body>
	<div id="example" style="overflow-y:scroll; padding:10px;height:450px;border-style:solid;
border-width:medium;">
	<?php 
	echo stripslashes($demoHTML);
	?>
	</div>
	<div id="saveMsg" style="display:none">SAVE OK!</div>
	<form id="lesscss_form" name="lesscss_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<div id="tabs">
		  <ul>
			<li><a href="#tabs-1">Config</a></li>
			<li><a href="#tabs-2">Demo HTML</a></li>
			<li><a href="#tabs-3">Less-CSS</a></li>
			<li><a href="#tabs-4">Compiled-Less-CSS</a></li>
			<li><a href="#tabs-5">Stylesheet Manager</a></li>
			<li><a href="#tabs-6">Includes Manager</a></li>
			
		  </ul>
		<div id="tabs-1">
			Sample Form<br />
			<input type="hidden" name="hidden_variable" value="Y">  
			<input type="hidden" name = "SSID" value="<?php echo $SSID?>">
			<input type ="hidden" name="postURL" id="postURL"	value = "<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
			<input type = "hidden" name="scrollPosition" id="scrollPosition">
			<?php for ($x = 0; $x < count($form);$x++) { 
			//iterates through array and creates textfields based on what type of input it is expecting, color gives a color palette to choose from, number gives a number scroller, string gives a normal textfield.
			if($form[$x]["type"] == 'number') { ?>
				<input type="<?php echo $form[$x]["type"]?>" id="code_<?php echo $form[$x]["NAME"];   ?>" name="<?php echo $form[$x]["NAME"]?>" placeholder="<?php echo $form[$x]["Title"]?>" min="1" max="72" style="width: 300px;" value="<?php echo $form[$x]["Value"];   ?>" step="any" />   px         <?php echo $form[$x]["Title"]?>
			<?php } ?>
			<?php if ($form[$x]["type"] == 'string') { ?>
				<input type="text" id="code_<?php echo $form[$x]["NAME"];   ?>" name="<?php echo $form[$x]["NAME"]?>"placeholder="<?php echo $form[$x]["Title"]?>"  style="width: 300px;" value="<?php echo $form[$x]["Value"];   ?>"  required/>            <?php echo $form[$x]["Title"]?>
			<?php } 
			if($form[$x]["type"] == 'normal') { ?>
				
				<input type="number" id="code_<?php echo $form[$x]["NAME"];   ?>" name="<?php echo $form[$x]["NAME"]?>" placeholder="<?php echo $form[$x]["Title"]?>" min="1" max="72" style="width: 300px;" value="<?php echo $form[$x]["Value"];   ?>" step="any" />          <?php echo $form[$x]["Title"]?>
			<?php } ?>
			<?php if ($form[$x]["type"] == 'color'){ ?>
				<input type="text" class ="color" id="code_<?php echo $form[$x]["NAME"];   ?>" name="<?php echo $form[$x]["NAME"]?>"placeholder="<?php echo $form[$x]["Title"]?>"  style="width: 300px;" value="<?php echo $form[$x]["Value"];   ?>"  required/>            <?php echo $form[$x]["Title"]?>
			<?php } ?>
			<?php if ($form[$x]["added"] == 'hello'){ ?>
			<input type="hidden" id="hidden_type_<?php echo $form[$x]["NAME"];?>" value="<?php echo $form[$x]["type"];?>">  
			<button type="button" class = "deleteVariable "id= "code_delete_<?php echo $form[$x]["NAME"];?>" name = "<?php echo $form[$x]["NAME"];?>" > Delete</button>
			<button type="button" class = "changeVariable "id= "code_change_<?php echo $form[$x]["NAME"];?>" name = "<?php echo $form[$x]["NAME"];?>" > Edit</button>
			
			<?php }?>
			<br/>
			<?php } ?>
			
		</div>
		<?php 
			$z = 2;
			for ($x = 0; $x < count($form);$x++) { 
				if ($form[$x]["type"] == 'htmlmixed'||$form[$x]["type"] == 'less'){  ?>
					<div id="tabs-<?php echo $z; ?>">
						<textarea id="code_<?php echo $form[$x]["NAME"];?>" rows="15" cols="70" name="<?php echo $form[$x]["NAME"];?>"><?php echo  $form[$x]["Value"];?></textarea>
						<?php if($form[$x]["type"] == 'less'){?>
							<textarea style="resize: none;" name="Less_area_variables" id="code_Less_area" rows="15" cols="50" disabled><?php echo  (lessVarCreator($form, $SSID));
							?></textarea>
							<textarea name="Less_area_vars" id="code_Less_vars_area" rows="15" cols="50" disabled>lighten(), darken(), saturate(), desaturate(), fadein(), fadeout(), fade(), spin(), mix()</textarea>
						<?php } ?>		
					</div>
					<?php $z ++;
				}
				else if ($form[$x]["type"] == 'css'){?>
					<div id="tabs-<?php echo $z; ?>">
						<textarea style="resize: none;"id="code_<?php echo $form[$x]["NAME"];   ?>" rows="15" cols="70" name="<?php echo $form[$x]["NAME"];   ?>">  <?php echo $compiledcss  ?></textarea>
					</div>
					<?php $z ++;
				}
			} ?>
			<div id="tabs-5">
				
				Choose Stylesheet - 
				 <select id="code_stylesheet_select" class = "stylesheet_select" name="stylesheet_select" style="width:300px;" required>
					<?php for ($x = 0; $x <= get_item('stylesheet_counter');$x++) { 
					if ($stylesheets[$x]){
					if ($x == $SSID){?>
					<option value="<?php echo $x?>"selected><?php echo $stylesheets[$x]?></option>
					<?php }else{ ?>
					<option value="<?php echo $x?>"><?php echo $stylesheets[$x]?></option>
					<?php }}} ?>
				</select>
				</br>
				<div id="dialog-form" title="Create new user">
  <p class="validateTips">All form fields are required.</p>
  <form>
  <fieldset>
    <label for="name">Name</label>
    <input type="text" name="name" id="name" class="text ui-widget-content ui-corner-all">
  </fieldset>
  </form>
</div>
				<button id="edit-stylesheet" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only " role="button" aria-disabled="false"><span class="ui-button-text">Edit This Stylesheet!</span></button>
				<button id="create-stylesheet">Create New Stylesheet!</button>
				<button id="delete-stylesheet" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only " role="button" aria-disabled="false"><span class="ui-button-text">Delete This Stylesheet!</span></button>
				<button id="delete-all-stylesheets" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only " role="button" aria-disabled="false"><span class="ui-button-text">Delete All Stylesheets!</span></button>
				
			</div>
		
			<div id="tabs-6">
			<div>Which Stylesheets do you wish to include?</div><br>
			<?php for ($x = 0; $x <= get_item('stylesheet_counter');$x++) { 
					if ($stylesheets[$x]){
						if (in_array($stylesheets[$x],$stylesheet_names)){?>
						<input type="checkbox" name="<?php echo $stylesheets[$x]?>" value='<?php echo $stylesheets[$x]?>' checked='checked'>     <?php echo $stylesheets[$x]?><br>
					<?php } else{ ?>
					<input type="checkbox" name="<?php echo $stylesheets[$x]?>" value='<?php echo $stylesheets[$x]?>'>     <?php echo $stylesheets[$x]?><br>

					
					<?php }}} ?>
					<br>
					<br>
					<button id="include-stylesheets" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only " role="button" aria-disabled="false"><span class="ui-button-text">Update with Checked Stylesheets!</span></button>
					<button id="check-all" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only " role="button" aria-disabled="false"><span class="ui-button-text">Check all</button>
					<button id="uncheck-all" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only " role="button" aria-disabled="false"><span class="ui-button-text">Uncheck all</button> 
					<input type="checkbox" name="minify" value='minify'>Minify?
			</div>
				<?php echo "Currently Editing:".$stylesheets[$SSID]?>
				<p class="submit">  
	
			<input type="submit" id="ajaxSave"  onClick ="writeToFile()" value="Live Save & Refresh"/>
			<input type="submit" id="posting"   value="Post"/>
		</p>  
		</div>
		
	
	</form>
	<form id="addvar_form" name="addvar_form">
	<div>
	<input type="hidden" name="hidden_variable" value="x">  
	<input type="hidden" name = "SSID" value="<?php echo $SSID?>">
				Name:   <input type="text" id="code_variable_name" name="variable_name" style="width:300px;" required></br>
				Default Value:   <input type="text" class ="color" id="code_variable_value1" name="variable_valuea" style="width:250px;">
				<input type="text" id="code_variable_value2" name="variable_valueb" style="width:250px; display:none;">
				<input type="number" id="code_variable_value3" name="variable_valuec" style="width:250px; display:none;"></br>
				Type:   <select id="code_variable_type" class = "variable_type" name="variable_type" style="width:300px;" required>
					<option value="color">color</option>
					<option value="string">string</option>
					<option value="number">number</option>
				</select>
				</br>
				</div>
				<p class="submit">  
				
				<input type="submit" id="varSave" value="Add Variable"/>
				<input type="submit" id="resetAll" value="Reset Variables"/>
				<input type="submit" id="editVariable" value="Edit Variable"/>
</p>

				
	
</form>
</body>
</html>