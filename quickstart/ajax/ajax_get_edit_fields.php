<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors',1);
ini_set('log_errors',1);

require("../classes/class_project.php");
require("../classes/class_data_manager_extended.php");

if (isset($_GET['project'])){

$project = new DataManager();
$project->get_by_name($_GET['project']);
$project->set_selected_environment($_GET['environment']);
$project->setConnection();

//Build drop down list of all tables for Dynamic Drop down selects		
	
	$sql = "Show tables from " . $project->get_dbname();
	$result=mysql_query($sql);
	
	while ($row = mysql_fetch_row($result)) {
		 $tables_dd .= '<option value="'. $row[0] . '">' . $row[0] . '</option>';
	}
	
function getOption($type, $name){
$retval = "text";

// Suggest the best field type based on the mysql field type:
		
switch ($type) {
	case "int":
		$retval = "number";
	break;
	
	case "string":
		$retval = "text";
	break;
	
	case "datetime":
		$retval = "date";
	break;

	case "date":
		$retval = "date";
	break;

	case "text":
		$retval = "textarea";
	break;			
}

//Do some guesses based on the name of the field
if (strpos($name,"tel") || strpos($name,"phone")){ $retval = "tel";}
if (strpos($name,"comment") || strpos($name,"text") || strpos($name,"note")){ $retval = "textarea";}
if (strpos($name,"color") || strpos($name,"colour")){ $retval = "color";}
if (strpos($name,"email")){ $retval = "email";}
if (strpos($name,"date")){ $retval = "date";}
if (strpos($name,"pass")){ $retval = "password";}
if (strpos($name,"price")){ $retval = "currency";}
if (strpos($name,"cost")){ $retval = "currency";}
if (strpos($name,"_id")){ $retval = "dropdown_dynamic";}

if ($name == "is_active"){ $retval = "dropdown_static";}


return $retval;
}
?>
<form action="actions/action_edit_form_creator.php" id="fieldsForm" method="get">
<table class="table">
<tr><th>Field</th><th>Input type</th><th>Required ?</th></tr>
<input name="selected_table" value="<?php echo $_GET['table']; ?>" type="hidden">
<input name="environment" value="<?php echo $_GET['environment']; ?>"  type="hidden"/>
<input name="project_name" value="<?php echo $_GET['project']; ?>"  type="hidden"/>
<input name="template" value="<?php echo $_GET['template']; ?>"  type="hidden"/>

		<?php 
			$sql = "Show fields from " . $_GET['table'];
			$sql = "SELECT * from " . $_GET['table'];
			
			$result=mysql_query($sql);
			
			$result = mysql_query($sql);
			$fields = mysql_num_fields($result);
			$rows   = mysql_num_rows($result);
			
		for ($i=0; $i < $fields; $i++) {
			$type  = mysql_field_type($result, $i);
			$name  = mysql_field_name($result, $i);
			$len   = mysql_field_len($result, $i);
			$flags = mysql_field_flags($result, $i);
			//echo  $name . " / " . $type .  " / " . $flags . "<br>";

			
			// Don't check the fields that are automatically set in the class. Probably no need to edit these:
			if (strpos($name,"date_created",0) || strpos($name,"last_updated")){
				$checked = "";			
			} else {
				$checked = " checked='checked' ";
			}
			
			// Don't check the primary key. Probably no need to edit this:
			if (strpos($flags," primary_key ",0) || strpos($flags," unique_key ")){
				$checked = "";			
			}
			
			// Don't show if this field is the primary key:
			if ((strpos($flags," primary_key ",0))==0){
			
			$option_type = getOption($type, $name);	
			echo "<tr>";		
			echo "<td><input type='checkbox' id='" . $name . "' value='" . $option_type . "' name='fields[" . $name . "]' class='formCheckbox' " . $checked . "/>&nbsp; $name</td>";

			?><td>
					<select name="<?php echo $name ?>_type" onchange="updateCheckbox('<?php echo $name ?>',this.value)">
						<option value="text" <?php if ($option_type == "text"){echo "selected='selected'";}?>>Text</option>
						<option value="number" <?php if ($option_type == "number"){echo "selected='selected'";}?>>Number</option>
						<option value="currency" <?php if ($option_type == "currency"){echo "selected='selected'";}?>>Currency</option>												
						<option value="date" <?php if ($option_type == "date"){echo "selected='selected'";}?>>Date</option>
						<option value="email" <?php if ($option_type == "email"){echo "selected='selected'";}?>>Email address</option>
						<option value="password" <?php if ($option_type == "password"){echo "selected='selected'";}?>>Password</option>
						<option value="tel" <?php if ($option_type == "tel"){echo "selected='selected'";}?>>Telephone #</option>
						<option value="textarea" <?php if ($option_type == "textarea"){echo "selected='selected'";}?>>Text area</option>
						<option value="checkbox" <?php if ($option_type == "checkbox"){echo "selected='selected'";}?>>Checkbox</option>
						<option value="color" <?php if ($option_type == "color"){echo "selected='selected'";}?>>Color</option>
						<option value="dropdown_static" <?php if ($option_type == "dropdown_static"){echo "selected='selected'";}?>>Drop down select (static)</option>
						<option value="dropdown_dynamic" <?php if ($option_type == "dropdown_dynamic"){echo "selected='selected'";}?>>Drop down select (dynamic)</option>
					</select>		
				
				<div <?php if ($option_type != "dropdown_dynamic"){echo "style='display:none'";} ?> id="<?php echo $name ?>_dd_dynamic_div" >
				<!-- This select box to be shown when dynamic dropdown is selected -->
				<select name="dd_tables[<?php echo $name ?>]" onchange="update_field_name_list($(this).val(),'<?php echo $name ?>')">
					<option disabled selected="selected" value="">Please select table</option>
					<?php echo $tables_dd; ?>
				</select>
				
				<div id="<?php echo $name ?>_dd_dynamic_div_field_name">
					<!-- This table field name select box to be populated by ajax and shown when the table name changes -->
				</div>	
				
				</div>
				
			
			</td>
			<td style="text-align:center"><input type="checkbox" name="required[<?php echo $name ?>]" value="1" /></td>

			<?php
			echo "</tr>";
		}
		}
		?>
		<tr><td>
			<a href="#" id="checkall">&raquo; Check all</a>
		</td></tr>
		</table>
					
		<?php

// Use this in future to find when a dynamic drop down should be used by looking up foreign keys

	//	echo "<br>Indexes:<br>";
	/*	
		$sql = "SELECT *
		FROM
			information_schema.key_column_usage
		WHERE
			`CONSTRAINT_SCHEMA` LIKE 'orchardc_jordanedwards' AND
			referenced_table_name is not null AND
			table_name = '" .  $_GET['table'] . "'";

		//$sql = 'SHOW INDEXES FROM ' . $_GET['table'] . ' '; //WHERE Key_name = "PRIMARY"
		$indexResult = $project->queryRecords($sql);
		$num_rows_index = mysql_num_rows($indexResult);
	
		if ($num_rows_index > 0){
			while ($row = mysql_fetch_array($indexResult, MYSQL_ASSOC)) {
			//	echo $row['REFERENCED_TABLE_NAME'] . " - " . $row['REFERENCED_COLUMN_NAME'] . "<br>";
			}
		} 
*/
?>
<button type="submit" value="Submit">Submit</button>
</form>
	
<?php } ?>
<script>
$("#checkall").on("click", function (e) {
	e.preventDefault();
            $('.formCheckbox').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"               
            });
});	

function updateCheckbox(checkbox_id,value){
//alert(checkbox_id+" "+value);
	$('#'+checkbox_id).val(value);
	if (value=="dropdown_dynamic"){
		$('#'+checkbox_id+'_dd_dynamic_div').show()
	} else {
		$('#'+checkbox_id+'_dd_dynamic_div').hide()
	}
}

function update_field_name_list(selectedValue,fieldName){
	//alert(var1+" "+var2);
	$.ajax({
		type: "POST",
		url: "ajax/ajax_show_table_fields.php",
		data: { project: "<?php echo $_GET['project'] ?>", table_name:selectedValue, environment:"<?php echo $_GET['environment'] ?>", field_name:fieldName},
		success: function (html) {	
			$('#'+fieldName+"_dd_dynamic_div_field_name").html(html);
		},
	});
}
</script>