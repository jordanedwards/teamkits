<?php
require("../classes/class_project.php");
require("../classes/class_data_manager_extended.php");

if (isset($_POST['project'])){

$project = new DataManager();
$project->get_by_name($_POST['project']);
$project->set_selected_environment($_POST['environment']);
$project->setConnection();

?>
	<select name="dd_table_name_field[<?php echo $_POST['field_name'] ?>]">
	<option value="" disabled="disabled">Select name field</option>
		<?php
			$sql = "Show fields from " . $_POST['table_name'];
			$result=mysql_query($sql);
			
			while ($row = mysql_fetch_row($result)) {
				 echo '<option value="'. $row[0] . '">' . $row[0] . '</option>';
			}
?>
    </select>
<?php } ?>