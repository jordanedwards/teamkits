<?php 
 class Club extends User{
 
		private $id;
		private $name;
 		private $sport;
 		private $brand;
 		private $tel;
 		private $address;
		private $website;
 		private $city;
 		private $province;
 		private $country;
 		private $postal_code;
 		private $email;
 		private $code;
 		private $account_type;
 		private $tax_id;
 		private $active;
		private $user_id;
		private $user_login;
		private $user_active;
 		private $date_created;
 		private $last_updated;
 		private $last_updated_user;
 		
	function __construct() {
		
	}
		public function get_id() { return $this->id;}
		public function set_id($value) {$this->id=$value;}

		public function get_name() { return $this->name;}
		public function set_name($value) {$this->name=$value;}
		
		public function get_sport() { return $this->sport;}
		public function set_sport($value) {$this->sport=$value;}
		
		public function get_brand() { return $this->brand;}
		public function set_brand($value) {$this->brand=$value;}
		
		public function get_tel() { return $this->tel;}
		public function set_tel($value) {$this->tel=$value;}
		
		public function get_address() { return $this->address;}
		public function set_address($value) {$this->address=$value;}
		
		public function get_website() { return $this->website;}
		public function set_website($value) {$this->website=$value;}
				
		public function get_city() { return $this->city;}
		public function set_city($value) {$this->city=$value;}
		
		public function get_province() { return $this->province;}
		public function set_province($value) {$this->province=$value;}
		
		public function get_country() { return $this->country;}
		public function set_country($value) {$this->country=$value;}
		
		public function get_postal_code() { return $this->postal_code;}
		public function set_postal_code($value) {$this->postal_code=$value;}
		
		public function get_email() { return $this->email;}
		public function set_email($value) {$this->email=$value;}
		
		public function get_code() { return $this->code;}
		public function set_code($value) {$this->code=$value;}
		
		public function get_account_type() { return $this->account_type;}
		public function set_account_type($value) {$this->account_type=$value;}
		
		public function get_tax_id() { return $this->tax_id;}
		public function set_tax_id($value) {$this->tax_id=$value;}
		
		public function get_active() { return $this->active;}
		public function set_active($value) {$this->active=$value;}
		
		public function get_date_created() { return $this->date_created;}
		public function set_date_created($value) {$this->date_created=$value;}
		
		public function get_last_updated() { return $this->last_updated;}
		public function set_last_updated($value) {$this->last_updated=$value;}
		
		public function get_last_updated_user() { return $this->last_updated_user;}
		public function set_last_updated_user() {$this->last_updated_user=$value;}
		
		public function get_user_active() { return $this->user_active;}
		public function set_user_active($value) {$this->user_active=$value;}
		
		public function get_user_login() { return $this->user_login;}
		public function set_user_login($value) {$this->user_login=$value;}
		
		public function get_user_id() { return $this->user_id;}
		public function set_user_id($value) {$this->user_id=$value;}								

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
			
				$strSQL = "INSERT INTO club (club_id, club_name, club_sport, club_brand, club_tel, club_address, club_website, club_city, club_province, club_country, club_postal_code, club_email, club_code, club_account_type, club_tax_id, is_active, club_date_created, club_last_updated, club_last_updated_user, club_user_id) 
        VALUES (
				'".mysqli_real_escape_string($dm->connection, $this->get_id())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_name())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_sport())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_brand())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_tel())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_address())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_website())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_city())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_province())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_country())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_postal_code())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_email())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_code())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_account_type())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_tax_id())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_active())."',
				NOW(),
				NOW(),
				'".mysqli_real_escape_string($dm->connection, $this->get_last_updated_user())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_user_id())."')";	
			}
			else {
				$strSQL = "UPDATE club SET 
					club_name='".mysqli_real_escape_string($dm->connection, $this->get_name())."',						 
					club_sport='".mysqli_real_escape_string($dm->connection, $this->get_sport())."',						 
					club_brand='".mysqli_real_escape_string($dm->connection, $this->get_brand())."',						 
					club_tel='".mysqli_real_escape_string($dm->connection, $this->get_tel())."',						 
					club_address='".mysqli_real_escape_string($dm->connection, $this->get_address())."',						 
					club_website='".mysqli_real_escape_string($dm->connection, $this->get_website())."',						 
					club_city='".mysqli_real_escape_string($dm->connection, $this->get_city())."',						 
					club_province='".mysqli_real_escape_string($dm->connection, $this->get_province())."',						 
					club_country='".mysqli_real_escape_string($dm->connection, $this->get_country())."',						 
					club_postal_code='".mysqli_real_escape_string($dm->connection, $this->get_postal_code())."',						 
					club_email='".mysqli_real_escape_string($dm->connection, $this->get_email())."',						 
					club_code='".mysqli_real_escape_string($dm->connection, $this->get_code())."',						 
					club_account_type='".mysqli_real_escape_string($dm->connection, $this->get_account_type())."',						 
					club_tax_id='".mysqli_real_escape_string($dm->connection, $this->get_tax_id())."',	
					club_user_id='".mysqli_real_escape_string($dm->connection, $this->get_user_id())."', 
					is_active='".mysqli_real_escape_string($dm->connection, $this->get_active())."',						 
					club_last_updated=NOW(),						
					club_last_updated_user='".mysqli_real_escape_string($dm->connection, $this->get_last_updated_user())."'
				
				WHERE club_id=".mysqli_real_escape_string($dm->connection, $this->get_id());
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
			
			// Update login info:
      
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

			$strSQL = "DELETE FROM club WHERE club_id=" . $id;
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
			$strSQL = "SELECT * FROM club WHERE club_id=" . $id;
      
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

	public function get_by_user_id($user_id) {
		try{
			$status = false;
			$dm = new DataManager();
			$strSQL = "SELECT * FROM club WHERE club_user_id=" . $user_id;
      
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
			if(property_exists('club',$key)):
				$method_name = "set_".$key;
				$this->$method_name($val);
			endif;
		}
	} 
	  
	// loads the object data from a mysql assoc array
  	private function load($row){
		$this->user_id = $row["club_user_id"];
	
		//Load user object
		parent::get_by_id($this->user_id);
		$this->set_user_active($this->get_active());
		$this->set_user_login($this->get_email());
			
		$this->set_id($row["club_id"]);
		$this->set_name($row["club_name"]);
		$this->set_sport($row["club_sport"]);
		$this->set_brand($row["club_brand"]);
		$this->set_tel($row["club_tel"]);
		$this->set_address($row["club_address"]);
		$this->set_website($row["club_website"]);		
		$this->set_city($row["club_city"]);
		$this->set_province($row["club_province"]);
		$this->set_country($row["club_country"]);
		$this->set_postal_code($row["club_postal_code"]);
		$this->set_email($row["club_email"]);
		$this->set_code($row["club_code"]);
		$this->set_account_type($row["club_account_type"]);
		$this->set_tax_id($row["club_tax_id"]);
		$this->set_active($row["is_active"]);
		$this->set_date_created($row["club_date_created"]);
		$this->set_last_updated($row["club_last_updated"]);
		$this->set_last_updated_user($row["club_last_updated_user"]);
		
	//	addToLog($this::__toString());

  }
}