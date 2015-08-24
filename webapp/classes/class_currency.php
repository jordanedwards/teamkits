<?php 
 class Currency extends SessionManager {
 
		private $id;
		private $name;
 		private $shortname;
 		private $is_active;
 		private $date_created;
 		private $last_updated;
 		private $last_updated_user;
 		
	function __construct() {
	
	}
		public function get_id() { return $this->id;}
		public function set_id($value) {$this->id=$value;}

		public function get_name() { return $this->name;}
		public function set_name($value) {$this->name=$value;}
		
		public function get_shortname() { return $this->shortname;}
		public function set_shortname($value) {$this->shortname=$value;}
		
		public function get_is_active() { return $this->is_active;}
		public function set_is_active($value) {$this->is_active=$value;}
		
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
				$this->is_active = "Y";
				
				$strSQL = "INSERT INTO currency (id, name, shortname, is_active, date_created, last_updated, last_updated_user) 
        VALUES (
				'".mysqli_real_escape_string($dm->connection, $this->get_id())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_name())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_shortname())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_is_active())."',
				NOW(),
				NOW(),
				'".mysqli_real_escape_string($dm->connection, $this->get_last_updated_user())."')";	
						}
			else {
				$strSQL = "UPDATE currency SET 
				name='".mysqli_real_escape_string($dm->connection, $this->get_name())."',						 
						shortname='".mysqli_real_escape_string($dm->connection, $this->get_shortname())."',						 
						is_active='".mysqli_real_escape_string($dm->connection, $this->get_is_active())."',						 
						last_updated=NOW(),						
						last_updated_user='".mysqli_real_escape_string($dm->connection, $this->get_last_updated_user())."'
							
						WHERE id=".mysqli_real_escape_string($dm->connection, $this->get_id());
			}		
				
			$result = $dm->updateRecords($strSQL);

			// if this is a new record get the record id from the database
			if(!$this->id > 0) {
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
			$strSQL = "UPDATE currency SET is_active='N' WHERE id=" . $this->id;
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
			if ($id > 0){
				$status = false;
				$dm = new DataManager();
				$strSQL = "SELECT * FROM currency WHERE id=" . $id;
		  
				$result = $dm->queryRecords($strSQL);
				if ($result){
					$row = mysqli_fetch_assoc($result);
					$this->load($row);
					$status = true;
				}
	
				return $status;
			} else {
			exit("id not set");
			}				
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
			if(property_exists('currency',$key)):
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
		$this->set_id($row["id"]);
		$this->set_name($row["name"]);
		$this->set_shortname($row["shortname"]);
		$this->set_is_active($row["is_active"]);
		$this->set_date_created($row["date_created"]);
		$this->set_last_updated($row["last_updated"]);
		$this->set_last_updated_user($row["last_updated_user"]);
		
  }
}