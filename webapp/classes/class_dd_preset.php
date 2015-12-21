<?php
//Extends Drop Down class with preset drop downs. 

class DD_preset extends DropDown {
				
	function __construct($preset) {
		$this->preset = $preset;
		switch ($preset):
			case "is_active":			
				$this->set_static(true);	
				$this->set_name("is_active");
				$this->set_class_name("form-control");
				$this->set_option_list("Y,N");	
			break;
			case "active":
				$this->set_static(true);								
				$this->set_option_list("Y,N");
				$this->set_name("is_active");		
			break;				
			case "supplier":						
				$this->set_table("supplier");	
				$this->set_name_field("supplier_name");
				$this->set_class_name("form-control");
				$this->set_order("ASC");
			break;
			case "province":
				$this->set_static(true);
				$this->set_name("province");												
				$this->set_option_list("AB,BC,MN,NB,NF,NWT,NS,NU,ON,PEI,QC,SK,YK");	
			break;
			case "shippingrecord":
				$this->set_static(true);	
				$this->set_name("shippingrecord_carrier");	
				$this->set_required(true);
				$this->set_class_name("form-control inline");
					$option_list = "Canada Post,Fed Ex,UPS,Other";
				$this->set_option_list($option_list);	
				$this->set_placeholder("Select carrier");		
			break;				
			default:
				echo "preset not found";
				die();
			break;
		endswitch;
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
			
	}
}	
?>