<?php
// TO ADD:
//$dd->set_display_format("table"); , or select, or list.
//Based on the display format, it would render a ul, select or table

// DROPDOWN CLASS
// This dropdown class allows the easy creation of a form select populated from a database table
// You may create one object, then call the method for each drop down in the page, supplying different arguments to each.

// Methods: (table name and name field are required, the rest are optional)
// REQUIRED:
// ->table: Database table name
// ->name_field: Database table field that you would like to use as the text for your select options. This also sets the default order by.

// OPTIONAL:
// ->name: Name you want for the select element.
// ->id: Id you want for the select element.
// ->name_field_2: Second field to show up as drop down value. Concatented to the end of name_field. Useful particularly for first name / last name sets
// ->selected_value: Sets the selected value;
// ->active_only: True/false argument for whether you want to show ALL records, or just those flagged as active. See note below
// ->required: True/false argument for whether you would like the field to be a required input.
// ->onchange: Just put in here whatever you would like as the onchange event.
// ->order: Sets the order by direction. Default is ASC, but you may want DESC, it's a free country.
// ->custom_sql: If you need a table join or something funky, write it yourself and stick it in. Table method must still be set.
// ->class_name: If you want the select to have a specific css class or classes.

// Syntax:
/*
// All methods are shown, but only the first two are necessary
	require_once(CLASSES . "/class_drop_downs_object.php");
	$dd = New DropDown();
	$dd->set_table("student");
	$dd->set_name_field("student_first_name");
	$dd->set_name_field_2("student_last_name");
	$dd->set_name("student_select");
	$dd->set_id("student_select");	
	$dd->set_selected_value($student_value);
	$dd->set_class_name("form-control");
	$dd->set_active_only(true);
	$dd->set_required(false);	
	$dd->set_onchange("updateStudent();");
	$dd->set_order("ASC");
	$dd->set_where("student_session_id = '3'");	
	$dd->add_data("data-name",$data-value);
	$dd->display();

// If NOT using this dynamically, but just with a static list, use like this:
	$dd = New DropDown();
	$dd->set_static(true);	
	$dd->set_name("production_status");	
	$dd->set_selected_value($production_status);
	$dd->set_required(true);
	$dd->set_class_name("form-control");		
	$dd->set_option_list("New,Pending,In Production,Complete,Shipped/Picked up");	
	$ddt->display();	
*/

// Notes:
//  - Please be aware that to use the "just_show_active" argument, there must be a field in the table called "is_active" with a simple 1/0 or boolean value 
//  - The find_index function finds the index field to get the unique ID to pass as the select value. That means your table must have an index (duh). If you wanted to use a different field as the selected value, just use set_index_name. 

class DropDown {

	private $class_name;
	private $name;
	private $id;
	private $table;
	private $name_field;
	private $name_field_2;
	private $selected_value;
	private $active_only;
	private $required;
	private $onchange;
	private $order;
	private $static;
	private $where;
	private $custom_sql;
	private $index_name;
	private $option_list;
	private $preset;
	private $data;
					
	function __construct() {
	}

		public function get_class_name() { return $this->class_name;}
		public function set_class_name($value) {$this->class_name=$value;}

		public function get_name() { return $this->name;}
		public function set_name($value) {$this->name=$value;}

		public function get_id() { return $this->id;}
		public function set_id($value) {$this->id=$value;}

		public function get_table() { return $this->table;}
		public function set_table($value) {$this->table=$value;}

		public function get_name_field() { return $this->name_field;}
		public function set_name_field($value) {$this->name_field=$value;}

		public function get_name_field_2() { return $this->name_field_2;}
		public function set_name_field_2($value) {$this->name_field_2=$value;}

		public function get_selected_value() { return $this->selected_value;}
		public function set_selected_value($value) {$this->selected_value=$value;}

		public function get_active_only() { return $this->active_only;}
		public function set_active_only($value) {$this->active_only=$value;}

		public function get_required() { return $this->required;}
		public function set_required($value) {$this->required=$value;}

		public function get_onchange() { return $this->onchange;}
		public function set_onchange($value) {$this->onchange=$value;}

		public function get_order() { return $this->order;}
		public function set_order($value) {$this->order=$value;}

		public function get_custom_sql() { return $this->custom_sql;}
		public function set_custom_sql($value) {$this->custom_sql=$value;}

		public function get_index_name() { return $this->index_name;}
		public function set_index_name($value) {$this->index_name=$value;}

		public function get_static() { return $this->static;}
		public function set_static($value) {$this->static=$value;}		

		public function get_where() { return $this->where;}
		public function set_where($value) {$this->where=$value;}		
		
		public function get_option_list() { return $this->option_list;}
		public function set_option_list($value) {$this->option_list=$value;}	

public function __toString(){
		// Debugging tool
		// Dumps out the attributes and method names of this object
		// To implement:
		// $a = new SomeClass();
		// echo $a;
		
		// Get Class name:
		$class = get_class($this);
		
		// Get attributes:
		$attributes = get_object_vars($this);
		
		// Get methods:
		$methods = get_class_methods($this);
		
		$str = "<h2>Information about the $class object</h2>";
		$str .= '<h3>Attributes</h3><ul>';
		foreach ($attributes as $key => $value){
			$str .= "<li>$key: $value</li>";
		}
		$str .= "</ul>";
		
		$str .= "<h3>Methods</h3><ul>";
		foreach ($methods as $value){
			$str .= "<li>$value</li>";
		}
		$str .= "</ul>";
		
		return $str;
	}
					
	public function find_index(){
		//Find index field:
		$index_name = "";
		if ($this->table != null):
		$dm = new DataManager();
		$sql = 'SHOW INDEXES FROM ' . $this->table . ' WHERE Key_name = "PRIMARY" OR Key_name = "UNIQUE"';
		$indexResult = $dm->queryRecords($sql);
		$num_rows_index = mysqli_num_rows($indexResult);

		if ($num_rows_index > 0){
		while ($row = mysqli_fetch_array($indexResult, MYSQL_ASSOC)) {
			$index_name = $row['Column_name'];
		}
		} else {
			$index_name	= $this->table . "_id";
		}
		$this->index_name=$index_name;		
		endif;
		
				return $index_name;
	}

	public function clear(){
		 foreach ($this as $key => $value) {
             $this->$key=NULL;
        }
	}	
			
	public function display(){
	if ($this->id == ""){
		$this->id = $this->name;
	}
	$dataArray = $this->data;
	foreach ($dataArray as $dataItem => $dataValue) {
    	$dataStr.= " data-" . $dataItem . "='" . $dataValue . "'";
  	}
	
	if ($this->get_static()):
		// Static drop down	
		$cssClass = ($this->required ? ' class="{validate:{required:true}} ' . $this->class_name .' "' : " class='". $this->class_name . "'");
		$ddl = '<select id="'.$this->id.'" name="'.$this->name.'" ' . $cssClass. ' onchange="'.$this->onchange.'" ' . $dataStr . '>';
		$ddl .= "<option value=''></option>";
		
		$options = explode(",", $this->get_option_list());
		
		foreach ($options as $key):
			$selected = "";
			if ($this->selected_value == $key){
				$selected = " selected='selected' ";
			}
			$ddl .= '<option value="' . $key .'" ' . $selected . '>' . $key .'</option>';
		endforeach;
		
		$ddl .= '</select>';
		echo $ddl;		
	else:
	
	// Dynamic drop down
	if (isset($this->table) && isset($this->name_field)):
		try{
			$dm = new DataManager();
			if ($this->index_name == null){
				$this->find_index();
			}

			$strSQL = "SELECT * FROM " . $this->table;
			// Just show active records: requires a field named "is_active";
			$where_str = " WHERE 1=1 ";
			$where_str .= ($this->active_only ? " AND is_active = 'Y'" : "");
			$where_str .= ($this->where != "" ? " AND " . $this->where : "");
			
			$strSQL .= $where_str;
			// Order by field is set:
			$strSQL .= ($this->name_field != "" ? " ORDER BY " . $this->name_field . " " . $this->order : "");
			
			if ($this->custom_sql != ""){
				$strSQL = $this->custom_sql;
			}
			
			$cssClass = ($this->required ? ' class="{validate:{required:true}} ' . $this->class_name .' "' : " class='". $this->class_name . "'");

			$result = $dm->queryRecords($strSQL);	
			if ($result){
			
				$ddl = '<select id="'.$this->id.'" name="'.$this->name.'" ' . $cssClass. ' onchange="'.$this->onchange .'" ' . $dataStr . '>';
				$ddl .= "<option value=''></option>";
				
				while($row = mysqli_fetch_assoc($result)) {
					$ddl .= '<option value="'.$row[$this->index_name].'" ';
					if($row[$this->index_name]==$this->selected_value){
						$ddl .= 'selected="selected"';
					}
					if ($this->name_field_2 != null){
						$ddl .= '>'.$row[$this->name_field].' '.$row[$this->name_field_2].'</option>';
					} else {
						$ddl .= '>'.$row[$this->name_field].'</option>';
					}
				}
				$ddl .= '</select>';
				
				echo $ddl;
			}else{
				return null;
				exit;
			}
		}
		catch(Exception $e) {
			echo "drop down object creation failed";
		}
	else:
		echo "drop down class failed: table and name field not set ";
	endif;	
	endif; 	
	}
	
	public function add_data($field_name,$value){
		$this->data[$field_name] = $value;
	}
	
	public function set_preset($preset)
	{
		$this->preset = $preset;
		switch ($preset):
			case "frame":
				$this->set_table("component");
				$this->set_name_field("component_description");
				$this->set_name_field_2("component_width");				
				$this->set_class_name("form-control short");
				$this->set_active_only(true);
				$this->set_order("ASC");
				$this->set_where("component_type = 1");	
			break;
			case "active":
				$this->set_static(true);								
				$this->set_option_list("Y,N");
				$this->set_name("is_active");		
			break;	
			default:
				echo "preset not found";
				die();
			break;	
		endswitch;
	}
}
?>