<?php 
 class Item extends SessionManager {
 
		private $id;
		private $name;
 		private $price;
 		private $weight;		
 		private $image;
 		private $brand;
 		private $club_id;
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
		
		public function get_price() { return $this->price;}
		public function set_price($value) {$this->price=$value;}

		public function get_weight() { return $this->weight;}
		public function set_weight($value) {$this->weight=$value;}
				
		public function get_image() { return $this->image;}
		public function set_image($value) {$this->image=$value;}
		
		public function get_brand() { return $this->brand;}
		public function set_brand($value) {$this->brand=$value;}
		
		public function get_club_id() { return $this->club_id;}
		public function set_club_id($value) {$this->club_id=$value;}
		
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
			
				$strSQL = "INSERT INTO item (item_id, item_name, item_price, item_weight, item_image, item_brand, item_club_id, is_active, item_date_created, item_last_updated, item_last_updated_user) 
        VALUES (
				'".mysqli_real_escape_string($dm->connection, $this->get_id())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_name())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_price())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_weight())."',				
				'".mysqli_real_escape_string($dm->connection, $this->get_image())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_brand())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_club_id())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_active())."',
				NOW(),
				NOW(),
				'".mysqli_real_escape_string($dm->connection, $this->get_last_updated_user())."')";	
						}
			else {
				$strSQL = "UPDATE item SET 
								item_name='".mysqli_real_escape_string($dm->connection, $this->get_name())."',						 
						 		item_price='".mysqli_real_escape_string($dm->connection, $this->get_price())."',						 
						 		item_weight='".mysqli_real_escape_string($dm->connection, $this->get_weight())."',						 
						 		item_image='".mysqli_real_escape_string($dm->connection, $this->get_image())."',						 
						 		item_brand='".mysqli_real_escape_string($dm->connection, $this->get_brand())."',						 
						 		item_club_id='".mysqli_real_escape_string($dm->connection, $this->get_club_id())."',						 
						 		is_active='".mysqli_real_escape_string($dm->connection, $this->get_active())."',						 
						 		item_last_updated=NOW(),						
						 		item_last_updated_user='".mysqli_real_escape_string($dm->connection, $this->get_last_updated_user())."'
							
						 	WHERE item_id=".mysqli_real_escape_string($dm->connection, $this->get_id());
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
			$strSQL = "UPDATE item SET is_active='N' WHERE item_id=" . $this->id;
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
		//	if ($id > 0){
				$status = false;
				$dm = new DataManager();
				$strSQL = "SELECT * FROM item WHERE item_id=" . $id;
		  
				$result = $dm->queryRecords($strSQL);
				$num_rows = mysqli_num_rows($result);
	
				if ($num_rows != 0){
					$row = mysqli_fetch_assoc($result);
					$this->load($row);
					$status = true;
				}
	
				return $status;
		//	} else {
		//	exit("id not set ".$id);
		//	}
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
			if(property_exists('item',$key)):
				$method_name = "set_".$key;
				$this->$method_name($val);
			endif;
		}
	} 
	  
	// loads the object data from a mysql assoc array
  	private function load($row){
		$this->set_id($row["item_id"]);
		$this->set_name($row["item_name"]);
		$this->set_price($row["item_price"]);
		$this->set_weight($row["item_weight"]);
		$this->set_image($row["item_image"]);
		$this->set_brand($row["item_brand"]);
		$this->set_club_id($row["item_club_id"]);
		$this->set_active($row["is_active"]);
		$this->set_date_created($row["item_date_created"]);
		$this->set_last_updated($row["item_last_updated"]);
		$this->set_last_updated_user($row["item_last_updated_user"]);
		
  }
}