<?php 
 class Settings {
 
	public $attributes;
 		
	function __construct() {
		$dm = new DataManager(); 
		$strSQL = "SELECT * FROM settings";						
	
		$result = $dm->queryRecords($strSQL);	
		while($row = mysqli_fetch_assoc($result)):
			$this->attributes[$row['settings_name']]["value"] = $row['settings_value'];			
			$this->attributes[$row['settings_name']]['id'] = $row['settings_id'];
		endwhile;
	}
 
		 
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
}