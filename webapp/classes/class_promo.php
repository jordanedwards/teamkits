<?php 
 class Promo {
 
		private $id;
		private $sport;
 		private $title;
 		private $description;
 		private $view_list;
 		private $wholesale;
 		private $price;
 		private $image;
 		private $tax;
 		private $active;
 		private $date_created;
 		private $last_updated;
 		private $last_updated_user;
 		
	function __construct() {
	
	}
		public function get_id() { return $this->id;}
		public function set_id($value) {$this->id=$value;}

		public function get_sport() { return $this->sport;}
		public function set_sport($value) {$this->sport=$value;}
		
		public function get_title() { return $this->title;}
		public function set_title($value) {$this->title=$value;}
		
		public function get_description() { return $this->description;}
		public function set_description($value) {$this->description=$value;}
		
		public function get_view_list() { return $this->view_list;}
		public function set_view_list($value) {$this->view_list=$value;}
		
		public function get_wholesale() { return $this->wholesale;}
		public function set_wholesale($value) {$this->wholesale=$value;}
		
		public function get_price() { return $this->price;}
		public function set_price($value) {$this->price=$value;}
		
		public function get_image() { return $this->image;}
		public function set_image($value) {$this->image=$value;}
		
		public function get_tax() { return $this->tax;}
		public function set_tax($value) {$this->tax=$value;}
		
		public function get_active() { return $this->active;}
		public function set_active($value) {$this->active=$value;}
		
		public function get_date_created() { return $this->date_created;}
		public function set_date_created($value) {$this->date_created=$value;}
		
		public function get_last_updated() { return $this->last_updated;}
		public function set_last_updated($value) {$this->last_updated=$value;}
		
		public function get_last_updated_user() { return $this->last_updated_user;}
		public function set_last_updated_user($value) {$this->last_updated_user=$value;}
		
		
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

			// if record does not already exist, create a new one
			if($this->get_id() == 0) {
			
				$strSQL = "INSERT INTO promo (promo_id, promo_sport, promo_title, promo_description, promo_view_list, promo_wholesale, promo_price, promo_image, promo_tax, is_active, promo_date_created, promo_last_updated, promo_last_updated_user) 
        VALUES (
				'".mysqli_real_escape_string($dm->connection, $this->get_id())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_sport())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_title())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_description())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_view_list())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_wholesale())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_price())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_image())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_tax())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_active())."',
				NOW(),
				NOW(),
				'".mysqli_real_escape_string($dm->connection, $this->get_last_updated_user())."')";	
						}
			else {
				$strSQL = "UPDATE promo SET 
								promo_sport='".mysqli_real_escape_string($dm->connection, $this->get_sport())."',						 
						 		promo_title='".mysqli_real_escape_string($dm->connection, $this->get_title())."',						 
						 		promo_description='".mysqli_real_escape_string($dm->connection, $this->get_description())."',						 
						 		promo_view_list='".mysqli_real_escape_string($dm->connection, $this->get_view_list())."',						 
						 		promo_wholesale='".mysqli_real_escape_string($dm->connection, $this->get_wholesale())."',						 
						 		promo_price='".mysqli_real_escape_string($dm->connection, $this->get_price())."',						 
						 		promo_image='".mysqli_real_escape_string($dm->connection, $this->get_image())."',						 
						 		promo_tax='".mysqli_real_escape_string($dm->connection, $this->get_tax())."',						 
						 		is_active='".mysqli_real_escape_string($dm->connection, $this->get_active())."',						 
						 		promo_last_updated=NOW(),						
						 		promo_last_updated_user='".mysqli_real_escape_string($dm->connection, $this->get_last_updated_user())."'
							
						 	WHERE promo_id=".mysqli_real_escape_string($dm->connection, $this->get_id());
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

			$strSQL = "DELETE FROM promo WHERE promo_id=" . $id;
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
			$strSQL = "SELECT * FROM promo WHERE promo_id=" . $id;
      
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
  
	// loads the object data from a mysql assoc array
  	private function load($row){
		$this->set_id($row["promo_id"]);
		$this->set_sport($row["promo_sport"]);
		$this->set_title($row["promo_title"]);
		$this->set_description($row["promo_description"]);
		$this->set_view_list($row["promo_view_list"]);
		$this->set_wholesale($row["promo_wholesale"]);
		$this->set_price($row["promo_price"]);
		$this->set_image($row["promo_image"]);
		$this->set_tax($row["promo_tax"]);
		$this->set_active($row["is_active"]);
		$this->set_date_created($row["promo_date_created"]);
		$this->set_last_updated($row["promo_last_updated"]);
		$this->set_last_updated_user($row["promo_last_updated_user"]);
		
  }
}