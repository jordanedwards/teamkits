<?php 
 class Brand extends SessionManager {
 
		private $id;
		private $name;
		private $currency;		
 		private $catalogue;
 		private $active;
 		private $date_created;
 		private $last_updated;
 		private $last_updated_user;
 		
	function __construct() {
	
	}
		public function get_id() { return $this->id;}
		public function set_id($value) {$this->id=$value;}

		public function get_name() { return $this->name;}
		public function set_name($value) {$this->name=$value;}

		public function get_currency() { return $this->currency;}
		public function set_currency($value) {$this->currency=$value;}
				
		public function get_catalogue() { return $this->catalogue;}
		public function set_catalogue($value) {$this->catalogue=$value;}
		
		public function get_active() { return $this->active;}
		public function set_active($value) {$this->active=$value;}
		
		public function get_date_created() { return $this->date_created;}
		public function set_date_created($value) {$this->date_created=$value;}
		
		public function get_last_updated() { return $this->last_updated;}
		public function set_last_updated($value) {$this->last_updated=$value;}
		
		public function get_last_updated_user() { return $this->last_updated_user;}
		public function set_last_updated_user() {$this->last_updated_user=$this->get_user_id();}
	
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

public function clear(){
	 foreach ($this as $key => $value) {
		 $this->$key=NULL;
	}
}	
		
public function save() {

		try{
			$dm = new DataManager();
			$this->set_last_updated_user();
			
			// if record does not already exist, create a new one
			if($this->get_id() == 0) {
			
				$strSQL = "INSERT INTO brand (brand_id, brand_name, brand_currency, brand_catalogue, is_active, brand_date_created, brand_last_updated, brand_last_updated_user) 
        VALUES (
				'".mysqli_real_escape_string($dm->connection, $this->get_id())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_name())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_currency())."',				
				'".mysqli_real_escape_string($dm->connection, $this->get_catalogue())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_active())."',
				NOW(),
				NOW(),
				'".mysqli_real_escape_string($dm->connection, $this->get_last_updated_user())."')";	
						}
			else {
				$strSQL = "UPDATE brand SET 
								brand_name='".mysqli_real_escape_string($dm->connection, $this->get_name())."',		
								brand_currency='".mysqli_real_escape_string($dm->connection, $this->get_currency())."',												 
						 		brand_catalogue='".mysqli_real_escape_string($dm->connection, $this->get_catalogue())."',						 
						 		is_active='".mysqli_real_escape_string($dm->connection, $this->get_active())."',						 
						 		brand_last_updated=NOW(),						
						 		brand_last_updated_user='".mysqli_real_escape_string($dm->connection, $this->get_last_updated_user())."'
							
						 	WHERE brand_id=".mysqli_real_escape_string($dm->connection, $this->get_id());
			}		
				
			$result = $dm->updateRecords($strSQL);

			// if this is a new record get the record id from the database
			if(!$this->get_id() >= "0") {
				$this->set_id(mysqli_insert_id($dm->connection));
			}
			
          	if (!$result):
      			throw new Exception("Failed Query: ". $strSQL);
   			endif;
      
			// fetch data from the db to update object properties      
      		$this->get_by_id($this->get_id());
      
			return $result;

		}
		catch(Exception $e) {
			// CATCH EXCEPTION HERE -- DISPLAY ERROR MESSAGE & EMAIL ADMINISTRATOR
			include_once(CLASSES . 'class_error_handler.php');
			$errorVar = new ErrorHandler();
			$errorVar->notifyAdminException($e);
			exit;
		}

	}

	// function to delete the record
	public function delete() {
		try{
			$dm = new DataManager();
			$strSQL = "UPDATE brand SET is_active='N' WHERE brand_id=" . $this->id;
			$result = $dm->updateRecords($strSQL);
			return $result;
		}
		catch(Exception $e) {
			// CATCH EXCEPTION HERE -- DISPLAY ERROR MESSAGE & EMAIL ADMINISTRATOR
			include_once(CLASSES . 'class_error_handler.php');
			$errorVar = new ErrorHandler();
			$errorVar->notifyAdminException($e);
			exit;
		}
	}	

	// function to fetch the record and populate the object
	public function get_by_id($id) {
		try{
			$status = false;
			$dm = new DataManager();
			$strSQL = "SELECT * FROM brand WHERE brand_id=" . $id;
      
			$result = $dm->queryRecords($strSQL);
			$num_rows = mysqli_num_rows($result);

			if ($num_rows != 0){
				$row = mysqli_fetch_assoc($result);
        		$this->load($row);
				$status = true;
			}

			return $status;
		}
		catch(Exception $e) {
			// CATCH EXCEPTION HERE -- DISPLAY ERROR MESSAGE & EMAIL ADMINISTRATOR
			include_once(CLASSES . 'class_error_handler.php');
			$errorVar = new ErrorHandler();
			$errorVar->notifyAdminException($e);
			exit;
		}
	}

  	public function load_from_post($array){
  		foreach ($array as $key => $val){
			if(property_exists('brand',$key)):
				$method_name = "set_".$key;
				$this->$method_name($val);
			endif;
		}
	} 
	  
	// loads the object data from a mysql assoc array
  	private function load($row){
		$this->set_id($row["brand_id"]);
		$this->set_name($row["brand_name"]);
		$this->set_currency($row["brand_currency"]);		
		$this->set_catalogue($row["brand_catalogue"]);
		$this->set_active($row["is_active"]);
		$this->set_date_created($row["brand_date_created"]);
		$this->set_last_updated($row["brand_last_updated"]);
		$this->set_last_updated_user($row["brand_last_updated_user"]);
		
  }
}