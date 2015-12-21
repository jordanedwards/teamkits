<?php 
 class Shippingrecord extends SessionManager {
 
		private $id;
		private $order_id;
 		private $carrier;
 		private $tracking;
 		private $date;
 		private $active;
 		private $date_created;
 		private $last_updated;
 		private $last_updated_user;
 		
	function __construct() {
	
	}
		public function get_id() { return $this->id;}
		public function set_id($value) {$this->id=$value;}

		public function get_order_id() { return $this->order_id;}
		public function set_order_id($value) {$this->order_id=$value;}
		
		public function get_carrier() { return $this->carrier;}
		public function set_carrier($value) {$this->carrier=$value;}
		
		public function get_tracking() { return $this->tracking;}
		public function set_tracking($value) {$this->tracking=$value;}
		
		public function get_date() { return $this->date;}
		public function set_date($value) {$this->date=$value;}
		
		public function get_active() { return $this->active;}
		public function set_active($value) {$this->active=$value;}
		
		public function get_date_created() { return $this->date_created;}
		public function set_date_created($value) {$this->date_created=$value;}
		
		public function get_last_updated() { return $this->last_updated;}
		public function set_last_updated() {$this->last_updated=$value;}
		
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
			
				$strSQL = "INSERT INTO shippingrecord (shippingrecord_id, shippingrecord_order_id, shippingrecord_carrier, shippingrecord_tracking, shippingrecord_date, is_active, shippingrecord_date_created, shippingrecord_last_updated, shippingrecord_last_updated_user) 
        VALUES (
				'".mysqli_real_escape_string($dm->connection, $this->get_id())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_order_id())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_carrier())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_tracking())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_date())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_active())."',
				NOW(),
				NOW(),
				'".mysqli_real_escape_string($dm->connection, $this->get_last_updated_user())."')";	
						}
			else {
				$strSQL = "UPDATE shippingrecord SET 
								shippingrecord_order_id='".mysqli_real_escape_string($dm->connection, $this->get_order_id())."',						 
						 		shippingrecord_carrier='".mysqli_real_escape_string($dm->connection, $this->get_carrier())."',						 
						 		shippingrecord_tracking='".mysqli_real_escape_string($dm->connection, $this->get_tracking())."',						 
						 		shippingrecord_date='".mysqli_real_escape_string($dm->connection, $this->get_date())."',						 
						 		is_active='".mysqli_real_escape_string($dm->connection, $this->get_active())."',						 
						 		shippingrecord_last_updated=NOW(),						
						 		shippingrecord_last_updated_user='".mysqli_real_escape_string($dm->connection, $this->get_last_updated_user())."'
							
						 	WHERE shippingrecord_id=".mysqli_real_escape_string($dm->connection, $this->get_id());
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
			$strSQL = "UPDATE shippingrecord SET is_active='N' WHERE shippingrecord_id=" . $this->id;
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
			$strSQL = "SELECT * FROM shippingrecord WHERE shippingrecord_id=" . $id;
      
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
			if(property_exists('shippingrecord',$key)):
				$method_name = "set_".$key;
				$this->$method_name($val);
			endif;
		}
	} 
	
	// loads the object data from a mysql assoc array
  	private function load($row){
		$this->set_id($row["shippingrecord_id"]);
		$this->set_order_id($row["shippingrecord_order_id"]);
		$this->set_carrier($row["shippingrecord_carrier"]);
		$this->set_tracking($row["shippingrecord_tracking"]);
		$this->set_date($row["shippingrecord_date"]);
		$this->set_active($row["is_active"]);
		$this->set_date_created($row["shippingrecord_date_created"]);
		$this->set_last_updated($row["shippingrecord_last_updated"]);
		$this->set_last_updated_user($row["shippingrecord_last_updated_user"]);
		
  }
}