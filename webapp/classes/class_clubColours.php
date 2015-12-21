<?php 
 class ClubColours extends SessionManager {
 
		private $id;
		private $club_id;
 		private $code;
 		private $hex_code;
 		private $title;
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
		
		public function get_code() { return $this->code;}
		public function set_code($value) {$this->code=$value;}
		
		public function get_hex_code() { return $this->hex_code;}
		public function set_hex_code($value) {$this->hex_code=$value;}
		
		public function get_title() { return $this->title;}
		public function set_title($value) {$this->title=$value;}
		
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
			
				$strSQL = "INSERT INTO clubColours (clubColours_id, clubColours_club_id, clubColours_code, clubColours_hex_code, clubColours_title, is_active, clubColours_date_created, clubColours_last_updated, clubColours_last_updated_user) 
        VALUES (
				'".mysqli_real_escape_string($dm->connection, $this->get_id())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_club_id())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_code())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_hex_code())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_title())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_active())."',
				NOW(),
				NOW(),
				'".mysqli_real_escape_string($dm->connection, $this->get_last_updated_user())."')";	
						}
			else {
				$strSQL = "UPDATE clubColours SET 
								clubColours_club_id='".mysqli_real_escape_string($dm->connection, $this->get_club_id())."',						 
						 		clubColours_code='".mysqli_real_escape_string($dm->connection, $this->get_code())."',						 
						 		clubColours_hex_code='".mysqli_real_escape_string($dm->connection, $this->get_hex_code())."',						 
						 		clubColours_title='".mysqli_real_escape_string($dm->connection, $this->get_title())."',						 
						 		is_active='".mysqli_real_escape_string($dm->connection, $this->get_active())."',						 
						 		clubColours_last_updated=NOW(),						
						 		clubColours_last_updated_user='".mysqli_real_escape_string($dm->connection, $this->get_last_updated_user())."'
							
						 	WHERE clubColours_id=".mysqli_real_escape_string($dm->connection, $this->get_id());
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
			$strSQL = "UPDATE clubColours SET is_active='N' WHERE clubColours_id=" . $this->id;
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
			$strSQL = "SELECT * FROM clubColours WHERE clubColours_id=" . $id;
      
			$result = $dm->queryRecords($strSQL);
			if ($result){
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
		// Pass $_POST to this function, and if the post vars match the object methods (they should, using this program), then it will populate the object
  		foreach ($array as $key => $val){
			if(property_exists('clubColours',$key)):
				$method_name = "set_".$key;
				$this->$method_name($val);
			endif;
		}
	} 

	public function get_json_data(){
		// Used in an ajax function to return the object properties:
 		$var = get_object_vars($this);
        foreach($var as &$value){
           if(is_object($value) && method_exists($value,'get_json_data')){
              $value = $value->get_json_data();
           }
        }
        return json_encode($var);
	}
		  
	// loads the object data from a mysql assoc array
  	private function load($row){
		$this->set_id($row["clubColours_id"]);
		$this->set_club_id($row["clubColours_club_id"]);
		$this->set_code($row["clubColours_code"]);
		$this->set_hex_code($row["clubColours_hex_code"]);
		$this->set_title($row["clubColours_title"]);
		$this->set_active($row["is_active"]);
		$this->set_date_created($row["clubColours_date_created"]);
		$this->set_last_updated($row["clubColours_last_updated"]);
		$this->set_last_updated_user($row["clubColours_last_updated_user"]);
		
  }
}