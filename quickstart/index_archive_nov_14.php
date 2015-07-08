<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors',1);
ini_set('log_errors',1);
ini_set('log_errors_max_len',0);
ini_set('error_log','error_log.txt');
?><!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="en" class="ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en"> <!--<![endif]-->
<head>
<meta charset=utf-8 />
<title>Quickstart</title>

<link rel="stylesheet" href="http://necolas.github.io/normalize.css/2.1.3/normalize.css">
<link rel="stylesheet" href="css/jquery.idealforms.css">
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
<link href="css/custom.css" rel="stylesheet">
<link href="css/select2.css" rel="stylesheet"/>  

  <script src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
  <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
  <script src="js/out/jquery.idealforms.js"></script>
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/select2.js"></script>  

  <!--<script src="js/out/jquery.idealforms.min.js"></script>-->

<script>
// If you add a field to the form, all you need to do is add another entry here and it will save and load properly
   function loadProject(projectName) {
		   document.forms["mainForm"].reset();
          $.getJSON('projects/'+projectName, function(jd) {
		  	 $('#project_file_name').html(projectName);
             $('#settings_application_name').val(jd.settings_application_name);		
			 $('#project_name').html(jd.settings_application_name);
			 $('#settings_email').val(jd.settings_email);		  
			 $('#settings_timezone').val(jd.settings_timezone);
			 $('#settings_framework').val(jd.settings_framework);
			 if (jd.dev_use==1){
			 	$('#dev_use').next('span').addClass('checked');
				$('#dev_use').prop('checked', true);				
			 };	
			 if (jd.access_protocol == ""){
			 // Make function to populate database access protocol
			 }
			 $('#dev_url').val(jd.dev_url); 			 		 			 		 
			 $('#dev_dbname').val(jd.dev_dbname);			 
			 $('#dev_dbuser').val(jd.dev_dbuser);
			 $('#dev_dbpass').val(jd.dev_dbpass);			 
			 $('#dev_dbhost').val(jd.dev_dbhost);
			 if (jd.pro_use==1){
			 	$('#pro_use').next('span').addClass('checked');
				$('#pro_use').prop('checked', true);				
			 };				 
			 $('#pro_dbname').val(jd.pro_dbname);			 
			 $('#pro_dbuser').val(jd.pro_dbuser);
			 $('#pro_dbpass').val(jd.pro_dbpass);			 
			 $('#pro_dbhost').val(jd.pro_dbhost);
			 if (jd.local_use==1){
			 	$('#local_use').next('span').addClass('checked');
				$('#local_use').prop('checked', true);
			 };			 
			 			 			 		 			 	
			 $('#local_dbname').val(jd.local_dbname);			 
			 $('#local_dbuser').val(jd.local_dbuser);
			 $('#local_dbpass').val(jd.local_dbpass);			 
			 $('#local_dbhost').val(jd.local_dbhost);
			 
			 $('#base_url').val(jd.base_url);
			 $('#class_url').val(jd.class_url);
			 $('#actions_url').val(jd.actions_url);
			 $('#includes_url').val(jd.includes_url);

			 var selEnvDD = '<select name="selected_environment" id="selected_environment" onChange="updateEnvironment(this.value)"><option value="" selected="selected"></option>';
			 if (jd.dev_use	== 1){
				 selEnvDD = selEnvDD + "<option value='Development'>Development</option>";
			 }
			 if (jd.pro_use	== 1){
				 selEnvDD = selEnvDD + "<option value='Production'>Production</option>";
			 }			 
			 if (jd.local_use == 1){
				 selEnvDD = selEnvDD + "<option value='Local'>Local</option>";
			 }		
			 selEnvDD = selEnvDD + "</select>";
			 $("#selected_environment").html(selEnvDD);
			 
			 
			 var selEnvDD = '<select name="selected_environment_ut" id="selected_environment_ut" onChange="updateEnvironment(this.value)"><option value="" selected="selected"></option>';
			 if (jd.dev_use	== 1){
				 selEnvDD = selEnvDD + "<option value='Development'>Development</option>";
			 }
			 if (jd.pro_use	== 1){
				 selEnvDD = selEnvDD + "<option value='Production'>Production</option>";
			 }			 
			 if (jd.local_use == 1){
				 selEnvDD = selEnvDD + "<option value='Local'>Local</option>";
			 }		
			 selEnvDD = selEnvDD + "</select>";

			 $("#selected_environment_ut").html(selEnvDD);			 
			 
			 
			 $("#baseZip").attr("onclick","triggerZip()");
			 
		});
      };
	  
	  
   </script>
<script>
	function updateEnvironment(environment) {
	
	$('#selected_environment').val(environment);
	$('#selected_environment_ut').val(environment);

		if (environment == "Development"){
			var dbname = $('#dev_dbname').val();
			var dbuser = $('#dev_dbuser').val();
			var dbpass = $('#dev_dbpass').val();
			var dbhost = $('#dev_dbhost').val();
		}else if(environment == "Production"){
			var dbname = $('#pro_dbname').val();
			var dbuser = $('#pro_dbuser').val();
			var dbpass = $('#pro_dbpass').val();
			var dbhost = $('#pro_dbhost').val();
		}else if(environment == "Local"){
			var dbname = $('#local_dbname').val();
			var dbuser = $('#local_dbuser').val();
			var dbpass = $('#local_dbpass').val();
			var dbhost = $('#local_dbhost').val();
		}
		
		$('#selected_db_name').val(dbname);
		$('#selected_db_user').val(dbuser);
		$('#selected_db_pass').val(dbpass);
		$('#selected_db_host').val(dbhost);
				
		$.ajax({
		url:"ajax/ajax_show_tables.php?db_name="+dbname+"&db_user="+dbuser+"&db_pass="+dbpass+"&db_host="+dbhost,
		success: function (html) {	
			$('#selected_table').html(html);
			$('#selected_table_ut').html(html);						
		}	
		});
	}
	
	function fetchDBdetails(){
		var dbDetails = "db_name="+$('#selected_db_name').val()+"&db_user="+$('#selected_db_user').val()+"&db_pass="+$('#selected_db_pass').val()+"&db_host="+$('#selected_db_host').val();
		return dbDetails
	}
	
	function ajaxFields(table){
	var dbDetails = fetchDBdetails();
		
	$.ajax({
		url:"ajax/ajax_show_fields.php?table="+$('#selected_table_ut').val()+"&"+dbDetails,
		success: function (html) {	
			$('#selected_field').html(html);
		}	
		});
	
	}
</script>
<?php
 
function generate_timezone_list()
{
    static $regions = array(
        DateTimeZone::AFRICA,
        DateTimeZone::AMERICA,
        DateTimeZone::ANTARCTICA,
        DateTimeZone::ASIA,
        DateTimeZone::ATLANTIC,
        DateTimeZone::AUSTRALIA,
        DateTimeZone::EUROPE,
        DateTimeZone::INDIAN,
        DateTimeZone::PACIFIC,
    );
 
    $timezones = array();
    foreach( $regions as $region )
    {
        $timezones = array_merge( $timezones, DateTimeZone::listIdentifiers( $region ) );
    }
 
    $timezone_offsets = array();
    foreach( $timezones as $timezone )
    {
        $tz = new DateTimeZone($timezone);
        $timezone_offsets[$timezone] = $tz->getOffset(new DateTime);
    }
 
    // sort timezone by timezone name
    ksort($timezone_offsets);
 
    $timezone_list = array();
    foreach( $timezone_offsets as $timezone => $offset )
    {
       
        $t = new DateTimeZone($timezone);
        $c = new DateTime(null, $t);
        $current_time = $c->format('g:i A');
 
        $timezone_list[$timezone] = "$timezone - $current_time";
    }
 
    return $timezone_list;
}

if (isset($_GET['load_project'])){echo "
<script>
	$( document ).ready(function() {
		loadProject('" . $_GET['load_project'] . "')
	});

</script>";
}

?>
<script>

function add_another_input() 
{
	
	var i = $(".line_item").length+1;
    var table_name = document.getElementById('table_name').value;

  var newElement = "<tr><td><span class='table_prefix'>"+table_name+"_</span><input name='field_"+i+"' type='text' class='line_item'></td><td><select class='column_type' name='type_"+i+"' style='display:block; width:auto'><option>INT</option><option value='varchar(200)' selected='selected'>VARCHAR</option><option value='text'>TEXT</option><option value='date'>DATE</option><optgroup label='NUMERIC'><option value='tinyint(2)'>TINYINT</option><option value='smallint(5)'>SMALLINT</option><option value='mediumint(7)'>MEDIUMINT</option><option value='int(10)'>INT</option><option value='bigint(20)'>BIGINT</option><option value='decimal(5,2)'>DECIMAL</option><option value='float(5,2)'>FLOAT</option><option value='double(5,2)'>DOUBLE</option><option value='real'>REAL</option><option value='bit'>BIT</option><option value='boolean'>BOOLEAN</option><option value='serial'>SERIAL</option></optgroup><optgroup label='DATE and TIME'><option value='date'>DATE</option><option value='datetime'>DATETIME</option><option value='timestamp'>TIMESTAMP</option><option value='time'>TIME</option><option value='year(4)'>YEAR</option></optgroup><optgroup label='STRING'><option value='char(4)'>CHAR</option><option value='varchar(200)'>VARCHAR</option><option value='tinytext'>TINYTEXT</option><option value='text'>TEXT</option><option value='mediumtext'>MEDIUMTEXT</option><option value='longtext'>LONGTEXT</option><option value='tinyblob'>TINYBLOB</option><option value='mediumblob'>MEDIUMBLOB</option><option value='blob'>BLOB</option><option value='longblob'>LONGBLOB</option><option value='enum'>ENUM</option><option value='set'>SET</option></optgroup></select></td></tr>";
  
	$("#field_list").append(newElement);

}

function remove_input() 
{

    var td = document.getElementById('field_list_group');
	td.removeChild( td.childNodes[ td.childNodes.length - 1 ] );
	
}

//////////////////////////////////////////////////////////////////////////////////////
// STILL WORKING ON THESE FUNCTIONS TO CUT OUT UNDERSCORES, AND CAMEL CASE THE TABLE NAME
//////////////////////////////////////////////////////////////////////////////////////

function strpos(haystack, needle, offset) {
  var i = (haystack + '')
    .indexOf(needle, (offset || 0));
  return i === -1 ? false : i;
}

function capitaliseFirstLetter(string,pos)
{
	alert(string+"/"+pos);

    var new_string = string.charAt(pos).toUpperCase() + string.slice(1);
	alert(new_string);
	return new_string.replace("_", "");	

}

function update_table_name(){
    var table_name = document.getElementById('table_name').value;
	// Underscores not allowed - it messes things up:
	
	var underscore = strpos(table_name,"_",0);

	if (underscore > 0){
		var new_table_name = capitaliseFirstLetter(table_name,underscore);	
		//var new_table_name = new_table_name.replace("_", "");	
	
		document.getElementById('err_msg').innerHTML="<i> Underscores not permitted, it screws things up. I have taken the liberty of camel casing the table name for you though.</i>";
	} else {
		var new_table_name = table_name;
		document.getElementById('err_msg').innerHTML="";
	}
	
	$('.table_prefix').html(new_table_name+"_");
	
}
//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////


function trigger_download(source){
	var dbDetails = fetchDBdetails();
	window.location.assign(source+'&selected_table='+$('#selected_table').val()+"&"+dbDetails+"&projectName="+$('#project_file_name').html());
}

function sqlCreate(){
	$('#mainForm').attr('action', 'actions/action_sql_creator.php');
	$('#mainForm').submit();
}

  $(function() {
    $( "#class_create" ).dialog({
		autoOpen: false,
		width: 450,	
		modal: true,
		buttons: {
			"Continue": function(){
				var dbDetails = fetchDBdetails();
				trigger_download('action_class_creator.php?selected_table='+$('#selected_table').val()+"&name_field="+$('#name_field').val()+"&"+dbDetails)
	          	$( this ).dialog( "close" );				
			},
			Cancel: function() {
          	$( this ).dialog( "close" );
        }
        }
	});
  });
  
  function classCreator(){
  	var dbDetails = fetchDBdetails();
	var msg = 'actions/action_class_creator.php?selected_table='+$('#selected_table').val()+"&"+dbDetails;
	window.location.assign('actions/action_class_creator.php?selected_table='+$('#selected_table').val()+"&"+dbDetails);
  }
  
  function triggerZip(){
 // alert('zip.php?project='+ $('#project_file_name').html());
  	window.location.assign('zip.php?project='+ $('#project_file_name').html());
  }
  
  function triggerZipAll(){
 // alert('zip.php?project='+ $('#project_file_name').html());
  	window.location.assign('zip_all.php?project='+ $('#project_file_name').html());
  } 
 
 function updateSelected(){
 // Allow the buttons to work:
 	var templateType = $('#output_type').val();
 	$("#buttonCreateClass").attr("onclick","classCreator()");
 	$("#buttonCreateList").attr("onclick","trigger_download('actions/action_list_creator.php?template="+templateType+"')");
 	$("#buttonCreateListTemplate").attr("onclick","trigger_download('actions/action_page_template_creator.php?template="+templateType+"')");	
 	$("#buttonCreateEditForm").attr("onclick","trigger_download('actions/action_edit_form_creator.php?template="+templateType+"')");
 	$("#buttonCreateEditAction").attr("onclick","trigger_download('actions/action_action_edit_creator.php?template="+templateType+"')");
 	$("#buttonCreateDeleteAction").attr("onclick","trigger_download('actions/action_action_delete_creator.php?template="+templateType+"')");;
 	$("#buttonCreateAll").attr("onclick","triggerZipAll()");
 
 }
 
 
  </script>
</head>
<body>
<div id="top_fade"></div>
<!--<div id="bottom_fade"></div>-->
<?php require("header_boot.php");?>

<!-- Dialog Boxes -->

<div id="class_create" title="Create Class" style="display:none">
<table>
<tr>
<td>
  <p><label>Choose NAME field:</label><br>
  <span style="font-size:10px">(For dynamic drop down function)</span></p>
</td>
<td id="fieldDropdown">
<p>
	<select id="name_field">
	<option value="">None</option>
		<?php
		/*	$dm = new DataManager();

			$sql = "Show fields from equipment";
			$result = $dm->queryRecords($sql);
			while ($row = mysql_fetch_row($result)) {
				 echo '<option value="'. $row[0] . '">' . $row[0] . '</option>';
			}*/
?>
    </select>
  </p>
</td>
</tr>
</table>
</div>

<!-- End Dialog boxes  -->
<div class="container">
      <div class="row">
        <div class="col-md-12">
  <div class="content">

    <div class="idealsteps-container">

      <nav class="idealsteps-nav"></nav>


        <div class="idealsteps-wrap">

		   <form action="actions/config_action.php" novalidate autocomplete="on" class="idealforms" id="mainForm" method="post">

<!-- Configuration Page -->
          <section class="idealsteps-step" name="Settings">
            <div class="field">
              <label class="main">Application name:</label>
              <input name="settings_application_name" id="settings_application_name" type="text" >
              <span class="error"></span>
            </div>

            <div class="field">
              <label class="main">Admin Email:</label>
              <input name="settings_email" id="settings_email" type="email">
              <span class="error"></span>
            </div>

            <div class="field">
              <label class="main">Timezone:</label>
              <select name="settings_timezone" id="settings_timezone">
			  	<option></option>
				<?php 
				// To do: If no timezone is selected, populate with current timezone as taken from server
				
				$tz_array = generate_timezone_list();
				foreach ($tz_array as $key => $val){
					if (ini_get('date.timezone') == $key){
						echo '<option value="' . $key . '" selected=selected>' . $val . '</option>
					';} else {
						echo '<option value="' . $key . '">' . $val . '</option>
					';}
				}?>
				</optgroup>
              </select>
              <span class="error"></span>
            </div>

            <div class="field">
              <label class="main">Framework:</label>
              <p class="group">
                <label><input name="settings_framework" id="settings_framework" type="radio" value="oop" checked>Object-Oriented PHP</label>
                <label><input name="settings_framework" id="settings_framework" type="radio" value="zend" disabled="disabled">Zend</label>
                <label><input name="settings_framework" id="settings_framework" type="radio" value="zend" disabled="disabled">Yii</label>
                <label><input name="settings_framework" id="settings_framework" type="radio" value="zend" disabled="disabled">Ruby</label>						
              </p>
              <span class="error"></span>
            </div>

 <div class="field">
              <label class="main">Use URL rewriting:</label>
              <p class="group">
                <label><input name="url_routing" id="url_routingk" type="radio" value="true" disabled="disabled">Yes</label>
                <label><input name="url_routing" id="url_routing" type="radio" value="false" checked>No</label>					
              </p>
              <span class="error"></span>
            </div>
			
  <div class="field">
                <label class="main">Database set up:</label>
<label>
<div id="accordion">
			<h3>Database Access Protocol:</h3>
              <div>
                <label><input name="access_protocol" id="access_protocol" type="radio" value="mysql" checked>MySQL</label><br>
                <label><input name="access_protocol" id="access_protocol" type="radio" value="mysqli" disabled="disabled">MySQLi</label><br>
                <label><input name="access_protocol" id="access_protocol" type="radio" value="PDO" disabled="disabled">PDO</label>
            </div>
			            
			<h3>Production Environment:</h3>
              <div>
                <label><input name="pro_use" id="pro_use" type="checkbox"  value="1"> Use</label><br>			  
				<label style="line-height:1">DB Name:&nbsp;<input name="pro_dbname" id="pro_dbname" type="text" ></label><br>
				<label style="line-height:1">DB User:&nbsp;<input name="pro_dbuser" id="pro_dbuser" type="text" ></label><br>
				<label style="line-height:1">DB Pass:&nbsp;<input name="pro_dbpass" id="pro_dbpass" type="text" ></label><br>
				<label style="line-height:1">DB Host:&nbsp;<input name="pro_dbhost" id="pro_dbhost" type="text" ></label><br>
				<label style="line-height:1"><button value="test" class="Test" id="test_pro">Test</button></label>	
            </div>
			
           <h3>Development Environment:</h3>
		   <div>
                <label><input name="dev_use" id="dev_use" type="checkbox" value="1">Use</label><br>		 
				<label style="line-height:1">Site URL:&nbsp;<input name="dev_url" id="dev_url" type="text" style="width:150px"></label><br>				 
				<label style="line-height:1">DB Name:&nbsp;<input name="dev_dbname" id="dev_dbname" type="text" ></label><br>
				<label style="line-height:1">DB User:&nbsp;<input name="dev_dbuser" id="dev_dbuser" type="text" ></label><br>
				<label style="line-height:1">DB Pass:&nbsp;<input name="dev_dbpass" id="dev_dbpass" type="text" ></label><br>
				<label style="line-height:1">DB Host:&nbsp;<input name="dev_dbhost" id="dev_dbhost" type="text" ></label>
				<label style="line-height:1"><button value="test" class="Test" id="test_dev">Test</button></label>					
			</div>
   
            <h3>Local Environment:</h3>
              <div>
                <label><input name="local_use" id="local_use" type="checkbox" value="1">Use</label><br>			  
				<label style="line-height:1">DB Name:&nbsp;<input name="local_dbname" id="local_dbname" type="text" ></label><br>
				<label style="line-height:1">DB User:&nbsp;<input name="local_dbuser" id="local_dbuser" type="text" value="root"></label><br>
				<label style="line-height:1">DB Pass:&nbsp;<input name="local_dbpass" id="local_dbpass" type="text" ></label><br>
				<label style="line-height:1">DB Host:&nbsp;<input name="local_dbhost" id="local_dbhost" type="text"  value="localhost"></label>	
				<label style="line-height:1"><button value="test" class="Test" id="test_local">Test</button></label>								

            </div>


</div>
</label>
<input type="hidden" name="selected_db_name" id="selected_db_name">
<input type="hidden" name="selected_db_user" id="selected_db_user">
<input type="hidden" name="selected_db_pass" id="selected_db_pass">
<input type="hidden" name="selected_db_host" id="selected_db_host">
<p id="project_file_name" style="display:none"></p>
</div>

<div class="field">
                <label class="main">Folder Structure:</label>
<label>
<div class="accordion">
            
			<h3>Base Folder:</h3>
              <div>
				<label style="line-height:1"><i><?php echo $_SERVER['HTTP_HOST']?>/</i><input name="base_url" id="base_url" type="text" value=""></label>			
            </div>

			<h3>Includes Folder:</h3>
              <div>
				<label style="line-height:1"><i>Base Folder/</i><input name="includes_url" id="includes_url" type="text" value="<?php if($_GET['load_project']==0){echo "includes";}?>"></label>			
            </div>
						
           <h3>Classes Folder:</h3>
              <div>
				<label style="line-height:1"><i>Base Folder/</i><input name="class_url" id="class_url" type="text" value="<?php if($_GET['load_project']==0){echo "classes";}?>"></label>			
            </div>
   
            <h3>Actions Folder:</h3>
              <div>
				<label style="line-height:1"><i>Base Folder/</i><input name="actions_url" id="actions_url" type="text" value="<?php if($_GET['load_project']==0){echo "actions";}?>"></label>			
            </div>


</div>
</label>
</div>

			
<div class="field">
                <label class="main">Includes:</label>
<label>
<div class="accordion">
             
              <h3>Use LESS (client side):</h3>
			  <div>
                <label><input name="use_LESS" id="use_LESS" type="radio" value="true" disabled="disabled">Yes</label>
                <label><input name="use_LESS" id="use_LESS" type="radio" value="false" checked>No</label>					
            </div>
			
			<h3>Javascript:</h3>
			  
            <div>
				<label style="line-height:1"><input id="picture" name="picture" type="file" multiple></label>			
            </div>

			<h3>Css:</h3>
              <div>
				<label style="line-height:1"><input id="picture" name="picture" type="file" multiple></label>	            
			</div>
						
           <h3>Other:</h3>
              <div>
				<label style="line-height:1"><input id="picture" name="picture" type="file" multiple></label>	            
			</div>
</div>
</label>
</div>

            <div class="field buttons">
			<br>
              <label class="main">&nbsp;</label>
              <button type="submit" name="Generate" value="1">Save Project &raquo;</button><button type="button" onClick="alert('Save or load project first')" id="baseZip" style="background: rgb(238, 163, 149); margin-bottom:10px;">&raquo; Download Project Base</button>
            </div>

          </section>


<!-- SQL GEnerator page -->
          <section class="idealsteps-step" name="SQL">
<div>
<h2>SQL Table Generator</h2>
            <div class="field">
              <label class="main">Table name:</label>
              <input name="table_name" id="table_name" type="text" onChange="update_table_name();" >
			  <span id="err_msg"></span>
              <span class="error"></span>
            </div>

            <div class="field">
              <label class="main">Fields:</label>
			  <table id="field_list">
			  <tbody id="field_list_group">
			  	<tr><td><span class="table_prefix"></span><input name="field_1" value="id" disabled="disabled" class='line_item'></td><td><select class="column_type" name="type_1" style="display:block; width:auto" disabled="disabled">
<option selected="selected" value="5">INT</option><option value="varchar(200)">VARCHAR</option><option value="text">TEXT</option><option value="date">DATE</option><optgroup label="NUMERIC"><option value="tinyint(2)">TINYINT</option><option value="smallint(5)">SMALLINT</option><option value="mediumint(7)">MEDIUMINT</option><option value="int(10)">INT</option><option value="bigint(20)">BIGINT</option><option value="decimal(5,2)">DECIMAL</option><option value="float(5,2)">FLOAT</option><option value="double(5,2)">DOUBLE</option><option value="real">REAL</option><option value="bit">BIT</option><option value="boolean">BOOLEAN</option><option value="serial">SERIAL</option></optgroup><optgroup label="DATE and TIME"><option value="date">DATE</option><option value="datetime">DATETIME</option><option value="timestamp">TIMESTAMP</option><option value="time">TIME</option><option value="year(4)">YEAR</option></optgroup><optgroup label="STRING"><option value="char(4)">CHAR</option><option value="varchar(200)">VARCHAR</option><option value="tinytext">TINYTEXT</option><option value="text">TEXT</option><option value="mediumtext">MEDIUMTEXT</option><option value="longtext">LONGTEXT</option><option value="tinyblob">TINYBLOB</option><option value="mediumblob">MEDIUMBLOB</option><option value="blob">BLOB</option><option value="longblob">LONGBLOB</option><option value="enum">ENUM</option><option value="set">SET</option></optgroup>
</select></td></tr>
			  	<tr><td><span class="table_prefix"></span><input name="field_2" value="date_created" disabled="disabled" class='line_item'></td><td><select class="column_type" name="type_2" style="display:block; width:auto" disabled="disabled">
<option value="5">INT</option><option value="varchar(200)">VARCHAR</option><option value="text">TEXT</option><option value="date">DATE</option><optgroup label="NUMERIC"><option value="tinyint(2)">TINYINT</option><option value="smallint(5)">SMALLINT</option><option value="mediumint(7)">MEDIUMINT</option><option value="int(10)">INT</option><option value="bigint(20)">BIGINT</option><option value="decimal(5,2)">DECIMAL</option><option value="float(5,2)">FLOAT</option><option value="double(5,2)">DOUBLE</option><option value="real">REAL</option><option value="bit">BIT</option><option value="boolean">BOOLEAN</option><option value="serial">SERIAL</option></optgroup><optgroup label="DATE and TIME"><option value="date" selected="selected">DATE</option><option value="datetime">DATETIME</option><option value="timestamp">TIMESTAMP</option><option value="time">TIME</option><option value="year(4)">YEAR</option></optgroup><optgroup label="STRING"><option value="char(4)">CHAR</option><option value="varchar(200)">VARCHAR</option><option value="tinytext">TINYTEXT</option><option value="text">TEXT</option><option value="mediumtext">MEDIUMTEXT</option><option value="longtext">LONGTEXT</option><option value="tinyblob">TINYBLOB</option><option value="mediumblob">MEDIUMBLOB</option><option value="blob">BLOB</option><option value="longblob">LONGBLOB</option><option value="enum">ENUM</option><option value="set">SET</option></optgroup>
</select></td></tr>
			  	<tr><td><span class="table_prefix"></span><input name="field_3" value="last_updated" disabled="disabled" class='line_item'></td><td><select class="column_type" name="type_3" style="display:block; width:auto" disabled="disabled">
<option value="5">INT</option><option value="varchar(200)">VARCHAR</option><option value="text">TEXT</option><option value="date">DATE</option><optgroup label="NUMERIC"><option value="tinyint(2)">TINYINT</option><option value="smallint(5)">SMALLINT</option><option value="mediumint(7)">MEDIUMINT</option><option value="int(10)">INT</option><option value="bigint(20)">BIGINT</option><option value="decimal(5,2)">DECIMAL</option><option value="float(5,2)">FLOAT</option><option value="double(5,2)">DOUBLE</option><option value="real">REAL</option><option value="bit">BIT</option><option value="boolean">BOOLEAN</option><option value="serial">SERIAL</option></optgroup><optgroup label="DATE and TIME"><option value="date" selected="selected">DATE</option><option value="datetime">DATETIME</option><option value="timestamp">TIMESTAMP</option><option value="time">TIME</option><option value="year(4)">YEAR</option></optgroup><optgroup label="STRING"><option value="char(4)">CHAR</option><option value="varchar(200)">VARCHAR</option><option value="tinytext">TINYTEXT</option><option value="text">TEXT</option><option value="mediumtext">MEDIUMTEXT</option><option value="longtext">LONGTEXT</option><option value="tinyblob">TINYBLOB</option><option value="mediumblob">MEDIUMBLOB</option><option value="blob">BLOB</option><option value="longblob">LONGBLOB</option><option value="enum">ENUM</option><option value="set">SET</option></optgroup>
</select></td></tr>
			  	<tr><td><span class="table_prefix"></span><input name="field_4" value="last_updated_user" disabled="disabled" class='line_item'></td><td><select class="column_type" name="type_4" style="display:block; width:auto" disabled="disabled">
<option value="5">INT</option><option value="varchar(200)" selected="selected">VARCHAR</option><option value="text">TEXT</option><option value="date">DATE</option><optgroup label="NUMERIC"><option value="tinyint(2)">TINYINT</option><option value="smallint(5)">SMALLINT</option><option value="mediumint(7)">MEDIUMINT</option><option value="int(10)">INT</option><option value="bigint(20)">BIGINT</option><option value="decimal(5,2)">DECIMAL</option><option value="float(5,2)">FLOAT</option><option value="double(5,2)">DOUBLE</option><option value="real">REAL</option><option value="bit">BIT</option><option value="boolean">BOOLEAN</option><option value="serial">SERIAL</option></optgroup><optgroup label="DATE and TIME"><option value="date">DATE</option><option value="datetime">DATETIME</option><option value="timestamp">TIMESTAMP</option><option value="time">TIME</option><option value="year(4)">YEAR</option></optgroup><optgroup label="STRING"><option value="char(4)">CHAR</option><option value="varchar(200)">VARCHAR</option><option value="tinytext">TINYTEXT</option><option value="text">TEXT</option><option value="mediumtext">MEDIUMTEXT</option><option value="longtext">LONGTEXT</option><option value="tinyblob">TINYBLOB</option><option value="mediumblob">MEDIUMBLOB</option><option value="blob">BLOB</option><option value="longblob">LONGBLOB</option><option value="enum">ENUM</option><option value="set">SET</option></optgroup>
</select></td></tr>
			  	<tr><td><span class="table_prefix"></span><input name="field_5" type="text" class='line_item'></td><td><select class="column_type" name="type_5" style="display:block; width:auto"><option selected="selected"></option>
<option value="int(10)">INT</option><option value="varchar(200)" selected="selected">VARCHAR</option><option value="text">TEXT</option><option value="date">DATE</option><optgroup label="NUMERIC"><option value="tinyint(2)">TINYINT</option><option value="smallint(5)">SMALLINT</option><option value="mediumint(7)">MEDIUMINT</option><option value="int(10)">INT</option><option value="bigint(20)">BIGINT</option><option value="decimal(5,2)">DECIMAL</option><option value="float(5,2)">FLOAT</option><option value="double(5,2)">DOUBLE</option><option value="real">REAL</option><option value="bit">BIT</option><option value="boolean">BOOLEAN</option><option value="serial">SERIAL</option></optgroup><optgroup label="DATE and TIME"><option value="date">DATE</option><option value="datetime">DATETIME</option><option value="timestamp">TIMESTAMP</option><option value="time">TIME</option><option value="year(4)">YEAR</option></optgroup><optgroup label="STRING"><option value="char(4)">CHAR</option><option value="varchar(200)">VARCHAR</option><option value="tinytext">TINYTEXT</option><option value="text">TEXT</option><option value="mediumtext">MEDIUMTEXT</option><option value="longtext">LONGTEXT</option><option value="tinyblob">TINYBLOB</option><option value="mediumblob">MEDIUMBLOB</option><option value="blob">BLOB</option><option value="longblob">LONGBLOB</option><option value="enum">ENUM</option><option value="set">SET</option></optgroup>
</select></td></tr>
</tbody>
			  </table>
			  </div>
			  	
            <div class="field buttons">
              <label class="main">&nbsp;</label>
				<button type="button" onClick="add_another_input();">Add another field</button>
				<button type="button" onClick="remove_input();">Remove last field</button>
				<button type="button" name="Reset_SQL" value="1" onClick="resetSql()">Reset Form</button>	
              	<button type="button" name="Create_SQL" value="1" onClick="sqlCreate()"  disabled="disabled">Add to DB;</button>
              	<button type="button" name="Create_SQL" value="1" onClick="sqlCreate()">Create SQL &raquo;</button>				
				<br>
            </div>
</div>

<div style="width:100%; clear:both;">
<br>
<h2>SQL Designer</h2>
<iframe src="sqldesigner/index.html" width="100%" height="900px"></iframe>

</div>

          </section>

          
<!-- Class, Action, View files page -->
          <section class="idealsteps-step" name="Class, Action, View files">

            <div class="field">
			<label class="main">Select Environment:</label>
			<select name="selected_environment" id="selected_environment" onChange="updateEnvironment(this.value)">
				<option value="" selected="selected"></option>
				</select>
              <span class="error"></span>
            </div>
			
            <div class="field">
			<label class="main">Select Table:</label>
			<select name="selected_table" id="selected_table" onChange="updateSelected(this.value)">
				<option value="" selected="selected"></option>
			</select>
              <span class="error"></span>
            </div>

			 <div class="field">
			<label class="main">Select Template type:</label>
			<select name="output_type" id="output_type" onChange="updateSelected();">
				<option value="Standard" >Standard</option>
				<option value="None">No template</option>				
				<optgroup label="Responsive Frameworks">
				<option value="Bootstrap" selected="selected">Bootstrap</option>
				<option value="" disabled="disabled">HTML5 Boilerplate</option>				
				<option value="" disabled="disabled">Angular JS</option>				
				<option value="" disabled="disabled">Skeleton</option>				
				<option value="" disabled="disabled">Foundation</option>				
				<option value="" disabled="disabled">jQuery Mobile</option>	
				</optgroup>
				<optgroup label="Custom">
				<option value="" disabled="disabled">Style 1</option>
				</optgroup>							
				<?php
		/*		$dm = new DataManager();

				$sql = "Show tables from " . $dbname;
				$result = $dm->queryRecords($sql);
				while ($row = mysql_fetch_row($result)) {
 					echo '<option value="'. $row[0] . '">' . $row[0] . '</option>';
				}*/
				?>
			</select>
              <span class="error"></span>
            </div>
						


            <div class="field">
              <label class="main">Create files:</label>
			  <table><tr><td>
							  
			<!--	<button type="button" onClick='$( "#class_create" ).dialog( "open" )'>&raquo; Create Class File</button><br>-->
				
				<button type="button" id="buttonCreateClass" onClick="alert('Please select Environment and Table first');">&raquo; Create Class File</button><br>	
				<!-- Make this download in to a zip, and include page template -->			
				<button type="button" id="buttonCreateList" onClick="alert('Please select Environment and Table first');">&raquo; Create List View</button><br>
				<button type="button" id="buttonCreateListTemplate" onClick="alert('Please select Environment and Table first');">&raquo; Create List Template</button><br>				
				<button type="button" id="buttonCreateEditForm" onClick="alert('Please select Environment and Table first');">&raquo; Create Edit Form</button><br>
				<button type="button" id="buttonCreateEditAction" onClick="alert('Please select Environment and Table first');">&raquo; Create Edit Action File</button>	<br>					
				<!--<button type="button" id="buttonCreateDeleteAction" onClick="alert('Please select Environment and Table first');">&raquo; Create Delete Action File</button><br>-->
				<button type="button" id="buttonCreateAll" onClick="alert('Please select Environment and Table first');" style="background: rgb(194, 194, 221); margin-top:10px;" >&raquo; Create All Files</button><br><br>
				
				</td></tr></table>									
            </div>

          </section>


<!-- Utilities page -->
          <section class="idealsteps-step" name="Utilities">

            <div class="field">
			<label class="main">Select Environment:</label>
			<select name="selected_environment_ut" id="selected_environment_ut" onChange="updateEnvironment(this.value)">
				<option value="" selected="selected">Load Project First</option>
				<option value=""></option>
				</select>
              <span class="error"></span>
            </div>
			
            <div class="field">
<h3>Create Drop Down Input Class:</h3>
			</div>
            
			<div class="field">
              <label class="main">Select Table</label>
				<select name="selected_table_ut" id="selected_table_ut" onChange="ajaxFields(this.value)">
					<option value="" selected="selected"><i>Select Environment First</i></option>
				</select>
			</div>
						
			<div class="field">
              <label class="main">Select NAME Field:</label>
				<select name="selected_field" id="selected_field">
					<option value="" selected="selected"><i>Select Table First</i></option>
				</select>
				<button type="button" onClick="addFieldToDD()">&raquo; Add</button>								
            </div>
			
			<div class="field" style="float:right; clear:none; margin-top:-50px">
				<!--<textarea rows="10"></textarea><br>-->
				<select id="e2_2" multiple="multiple" style="width:300px" class="populate placeholder">
				</select>
<br>
				<button name="Create Drop Down Class">Create Drop Down Class</button>
            </div>		
			


          </section>    

        <span id="invalid"></span>

      </form>
		</div>
    </div>


  </div>
	  	</div>
	</div>
</div>
<script>

    $('form.idealforms').idealforms({

      silentLoad: false,

  /*    rules: {
        'settings_application_name': 'required',
        'table_name': 'required',
        'selected_table': 'required',
        'selected_environment': 'required'	
      },*/

      errors: {
        'username': {
          ajaxError: 'Username not available'
        }
      },

	steps: {
  MY_stepsItems: ['Configuration', 'SQL Generator/Designer', 'Class, Action, View files', 'Utilites'],
  buildNavItems: function(i) {
    return this.opts.steps.MY_stepsItems[i];
  }
}
    /*  , onSubmit: function(invalid, e) {
        e.preventDefault();
        $('#invalid')
          .show()
          .toggleClass('valid', ! invalid)
          .text(invalid ? (invalid +' invalid fields') : 'All good!');
      }*/
    });

    $('form.idealforms').find('input, select, textarea').on('change keyup', function() {
      $('#invalid').hide();
    });

    $('.prev').click(function(){
      $('.prev').show();
      $('form.idealforms').idealforms('prevStep');
    });
    $('.next').click(function(){
      $('.next').show();
      $('form.idealforms').idealforms('nextStep');
    });

  </script>
  <script>
  $(function() {
    $( "#accordion" ).accordion({
		heightStyle: "content"
	});
	
	 $( ".accordion" ).accordion({
		 heightStyle: "content"
	 });
	 
	$("#e2_2").select2({
    placeholder: "Select a field, then click on the button below"
});


  });
  </script>
  
     <script>
 

function addFieldToDD(){
  var addName = $('#selected_table_ut').val() + " - "+$('#selected_field').val();
  $("#e2_2").append('<option value="'+addName+'">'+addName+'</option>');

  //If values already exist, append
	if ($("#e2_2").select2("val") != ""){
  		var currentVals =JSON.stringify($("#e2_2").select2("data"));
  
  		currentVals = currentVals.substring(0, currentVals.length-1);
  		currentVals = currentVals+',{"id":"'+addName+'","text":"'+addName+'","element":[{}],"disabled":false,"locked":false}]';
    }
	else {
		currentVals='[{"id":"'+addName+'","text":"'+addName+'","element":[{}],"disabled":false,"locked":false}]';
	
	}
   currentVals =JSON.parse(currentVals);

 $("#e2_2").select2("data", currentVals);

 }

$('.Test').click(function(e){
	e.preventDefault();
	alert("Database connection testing not yet operational");
	
	
});
 </script>
</body>
</html>
