<?php 
 class Orders {
 
		private $id;
		private $club_id;
 		private $customer;
 		private $item;
 		private $price;
 		private $quantity;
 		private $status;
		private $status_title;
 		private $tracking_number;
 		private $notes;
 		private $active;
 		private $date_created;
 		private $last_updated;
 		private $last_updated_user;
	
 		
	function __construct() {
	
	}
		public function get_id() { return $this->id;}
		public function set_id($value) {$this->id=$value;}

		public function get_club_id() { return $this->club_id;}
		public function set_club_id($value) {$this->club_id=$value;}
		
		public function get_customer() { return $this->customer;}
		public function set_customer($value) {$this->customer=$value;}
		
		public function get_item() { return $this->item;}
		public function set_item($value) {$this->item=$value;}
		
		public function get_price() { return $this->price;}
		public function set_price($value) {$this->price=$value;}
		
		public function get_quantity() { return $this->quantity;}
		public function set_quantity($value) {$this->quantity=$value;}
		
		public function get_status() { return $this->status;}
		public function set_status($value) {$this->status=$value;}
		
		public function get_status_title() { return $this->status_title;}
		public function set_status_title($value) {$this->status_title=$value;}
				
		public function get_tracking_number() { return $this->tracking_number;}
		public function set_tracking_number($value) {$this->tracking_number=$value;}
		
		public function get_notes() { return $this->notes;}
		public function set_notes($value) {$this->notes=$value;}
		
		public function get_active() { return $this->active;}
		public function set_active($value) {$this->active=$value;}
		
		public function get_date_created() { return $this->date_created;}
		public function set_date_created($value) {$this->date_created=$value;}
		
		public function get_last_updated() { return $this->last_updated;}
		public function set_last_updated($value) {$this->last_updated=$value;}
		
		public function get_last_updated_user() { return $this->last_updated_user;}
		public function set_last_updated_user($value) {$this->last_updated_user=$this->get_user_id();}
		
		
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
			//require_once($class_folder . '/class_data_manager.php');
			$dm = new DataManager();
			$this->set_last_updated_user();

			// if record does not already exist, create a new one
			if($this->get_id() == 0) {
			
				$strSQL = "INSERT INTO orders (order_club_id, order_customer, order_price, order_status, order_notes, is_active, order_date_created, order_last_updated, order_last_updated_user) 
        VALUES (
				'".mysqli_real_escape_string($dm->connection, $this->get_club_id())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_customer())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_price())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_status())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_notes())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_active())."',
				NOW(),
				NOW(),
				'".mysqli_real_escape_string($dm->connection, $this->get_last_updated_user())."')";	
						}
			else {
				$strSQL = "UPDATE orders SET 
								order_club_id='".mysqli_real_escape_string($dm->connection, $this->get_club_id())."',						 
						 		order_customer='".mysqli_real_escape_string($dm->connection, $this->get_customer())."',						 
						 		order_item='".mysqli_real_escape_string($dm->connection, $this->get_item())."',						 
						 		order_price='".mysqli_real_escape_string($dm->connection, $this->get_price())."',						 
						 		order_status='".mysqli_real_escape_string($dm->connection, $this->get_status())."',						 
						 		order_notes='".mysqli_real_escape_string($dm->connection, $this->get_notes())."',						 
						 		is_active='".mysqli_real_escape_string($dm->connection, $this->get_active())."',						 
						 		order_last_updated=NOW(),						
						 		order_last_updated_user='".mysqli_real_escape_string($dm->connection, $this->get_last_updated_user())."'
							
						 	WHERE order_id=".mysqli_real_escape_string($dm->connection, $this->get_id());
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
	public function delete_by_id($id) {
		try{
			$dm = new DataManager();
			$strSQL = "DELETE FROM orders WHERE order_id=" . $id;			
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
			$strSQL = "SELECT * FROM orders 
			LEFT JOIN orderstatus ON orders.order_status = orderstatus.orderstatus_id
			WHERE order_id=" . $id;
      
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
  
  	public function get_open_order($club_id){
	// Load the most recent open order (one that has not yet been submitted)
		try{
			$status = false;
			$dm = new DataManager();
			$strSQL = "SELECT * FROM orders 
			WHERE order_status = 6 AND order_club_id=" . $club_id . "
			ORDER BY order_date_created DESC
			LIMIT 1";
      
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
	
	public function find_existing_order_item($item_id){
	// Find exiting order item for this order of this type:
	if($this->id >0){
		try{
			$retval = false;
			$dm = new DataManager();
			$strSQL = "SELECT orderitem_id FROM orderitem 
			WHERE orderitem_order_id = '" . $this->id. "' 
			AND orderitem_item_number = '" . $item_id. "'
			AND is_active = 'Y'
			LIMIT 1";
      
			$result = $dm->queryRecords($strSQL);		
			if ($result):
				while ($line = mysqli_fetch_assoc($result)):
					$retval = $line['orderitem_id'];
				endwhile;	
			endif;

			return $retval;
		}
		catch(Exception $e) {
			// CATCH EXCEPTION HERE -- DISPLAY ERROR MESSAGE & EMAIL ADMINISTRATOR
			include_once(CLASSES . 'class_error_handler.php');
			$errorVar = new ErrorHandler();
			$errorVar->notifyAdminException($e);
			exit;
		}		
	} else {
		return "Object is missing order id";
	}
	}
	
	// loads the object data from a mysql assoc array
  	private function load($row){
		$this->set_id($row["order_id"]);
		$this->set_club_id($row["order_club_id"]);
		$this->set_customer($row["order_customer"]);
		$this->set_price($row["order_price"]);
		$this->set_status($row["order_status"]);
		$this->set_status_title($row["orderstatus_title"]);		
		$this->set_notes($row["order_notes"]);
		$this->set_active($row["is_active"]);
		$this->set_date_created($row["order_date_created"]);
		$this->set_last_updated($row["order_last_updated"]);
		$this->set_last_updated_user($row["order_last_updated_user"]);
		
  }
}