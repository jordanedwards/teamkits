<?php 
 class Base extends SessionManager {
 
 		private $tablename; // This must be set in the child class
		private $id;
 		private $is_active;
 		private $date_created;
 		private $last_updated;
 		private $last_updated_user;
 		
	function __construct() {
	
	}
		public function get_id() { return $this->id;}
		public function set_id($value) {$this->id=$value;}
		
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
			$this->set_last_updated(date("Y-m-d g:h:i",time()));
			
			// Fetch all the database fields and match them to methods, then grab method value and write to array:
			$strSQL = "SELECT * from " . $this->table_name;
			$result = $dm->queryRecords($strSQL);
			$fields = mysqli_num_fields($result);

			while ($fieldinfo=mysqli_fetch_field($result)) {
				if(property_exists($this->table_name,$fieldinfo->name)):
					$method_name = "get_".$fieldinfo->name;
					$tablefields[$fieldinfo->name] = $this->$method_name();
				endif;
			}	

			// if record does not already exist, create a new one
			if($this->get_id() == 0) {
				// remove 'id' key so that it doesn't write it an empty value
				unset($tablefields['id']);
				$this->set_date_created(date("Y-m-d g:h:i",time()));
				
				$strSQL = "INSERT INTO " . $this->table_name. " (";
				$strSQL .= implode(", ",array_keys($tablefields));
				$strSQL .= ") VALUES (";
				
				$i = 1;
				foreach($tablefields as $key => $val){
					$i++;
					if ($i < $fields){
						$strSQL .= "'" . $val . "', "; 
					}else {
						$strSQL .= "'" . $val . "'"; 
					}
				}
				
				$strSQL .= ")";
			}
			else {
			// Record already exists, so just update
				$strSQL = "UPDATE " . $this->table_name. " SET ";
				$i = 0;
				foreach($tablefields as $key => $val){
					$i++;
					if ($i < $fields){
						$strSQL .= $key."='" . $val . "', "; 
					}else {
						$strSQL .= $key."='" . $val . "'"; 
					}
				}
				$strSQL .= " WHERE id=".mysqli_real_escape_string($dm->connection, $this->get_id());
			}		

			$result = $dm->updateRecords($strSQL);
			// if this is a new record get the record id from the database
			if(!$this->get_id() > 0) {
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
		
	// function to delete or inactivate the record
	public function delete($type = "") {
		try{
			$dm = new DataManager();
			if ($type == "full"){
				$strSQL = "DELETE FROM " . $tablename . " WHERE id=" . $this->id;
			} else {
				// just inactivate or hide
				$strSQL = "UPDATE " . $tablename . " SET is_active='N' WHERE id=" . $this->id;
			}
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
				$strSQL = "SELECT * FROM " . $tablename . " WHERE id=" . $id;
		  
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

  	public function load($array){
		// Pass $_POST to this function, and if the post vars match the object methods (they should, using this program), then it will populate the object
		// loads the object data from a mysql assoc array

  		foreach ($array as $key => $val){
			if(property_exists('cmscomponent',$key)):
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

}