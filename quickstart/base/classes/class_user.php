<?php 
 class User {
 
		private $id;
		private $first_name;
 		private $last_name;
 		private $user_name;		
 		private $email;
 		private $tel;
 		private $password;
 		private $login;
 		private $role;
 		private $date_created;
 		private $last_updated;
 		private $last_updated_user;
 		
	function __construct() {
	
	}
				public function get_id() { return $this->id;}
		 		public function set_id($value) {$this->id=$value;}
 
		 		public function get_first_name() { return $this->first_name;}
		 		public function set_first_name($value) {$this->first_name=$value;}
 
		 		public function get_last_name() { return $this->last_name;}
		 		public function set_last_name($value) {$this->last_name=$value;}
  
		 		public function get_user_name() { return $this->user_name;}
		 		public function set_user_name($value) {$this->user_name=$value;}
				
		 		public function get_email() { return $this->email;}
		 		public function set_email($value) {$this->email=$value;}
 
		 		public function get_tel() { return $this->tel;}
		 		public function set_tel($value) {$this->tel=$value;}
 
		 		public function get_password() { return $this->password;}
				public function set_password($value, $hashed = false) { 
					require(INCLUDES . 'config_secure.php'); 
					$this->password = ($hashed ? $value : hash("sha256", trim($value) . $appConfig["salt"])); 
				}
							 
		 		public function get_login() { return $this->login;}
		 		public function set_login($value) {$this->login=$value;}
 
		 		public function get_role() { return $this->role;}
		 		public function set_role($value) {$this->role=$value;}
 
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

	//****************************************************************************************
	// login function: checks the login information and returns true if it's valid
	//****************************************************************************************
	public function login() {

		try{
			$dm = new DataManager();
			$strSQL = "SELECT user_id, user_role FROM user WHERE user_email='" . $this->get_email() . "' AND user_password='" . $this->get_password() . "'  AND user_email != '' AND user_password != ''";
			$result = $dm->queryRecords($strSQL);
			$num_rows = mysqli_num_rows($result);

			if ($num_rows != 0){
				$row = mysqli_fetch_assoc($result);
				$this->set_role($row["user_role"]);
				return $row["user_id"];
			}else{
				return "";
				exit;
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

	public function update_last_login($id) {
		
		try{
		//	require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/class_data_manager.php');
			$dm = new DataManager();
			$strSQL = "UPDATE user SET user_login = NOW() WHERE user_id=".$id;
			$dm->updateRecords($strSQL);
			return "";

		}
		catch(Exception $e) {
			// CATCH EXCEPTION HERE -- DISPLAY ERROR MESSAGE & EMAIL ADMINISTRATOR
			include_once(CLASSES . 'class_error_handler.php');
			$errorVar = new ErrorHandler();
			$errorVar->notifyAdminException($e);
			exit;
		}
	}

	
public function save() {

		try{
			//require_once(CLASSES . '/class_data_manager.php');
			$dm = new DataManager();

			// if record does not already exist, create a new one
			if($this->get_id() == 0) {
			
				$strSQL = "INSERT INTO user (user_id, user_first_name, user_last_name, user_email, user_tel, user_password, user_login, user_role, user_date_created, user_last_updated, user_last_updated_user) 
        VALUES (
								'".mysqli_real_escape_string($dm->connection,$this->get_id())."',
								'".mysqli_real_escape_string($dm->connection,$this->get_first_name())."',
								'".mysqli_real_escape_string($dm->connection,$this->get_last_name())."',
								'".mysqli_real_escape_string($dm->connection,$this->get_email())."',
								'".mysqli_real_escape_string($dm->connection,$this->get_tel())."',
								'".mysqli_real_escape_string($dm->connection,$this->get_password())."',
								'".mysqli_real_escape_string($dm->connection,$this->get_login())."',
								'".mysqli_real_escape_string($dm->connection,$this->get_role())."',
								NOW(),
							NOW(),
							'".mysqli_real_escape_string($dm->connection,$this->get_last_updated_user())."')";	
						}
			else {
				$strSQL = "UPDATE user SET 
								user_first_name='".mysqli_real_escape_string($dm->connection,$this->get_first_name())."',						 
						 		user_last_name='".mysqli_real_escape_string($dm->connection,$this->get_last_name())."',						 
						 		user_email='".mysqli_real_escape_string($dm->connection,$this->get_email())."',						 
						 		user_tel='".mysqli_real_escape_string($dm->connection,$this->get_tel())."',						 
						 		user_password='".mysqli_real_escape_string($dm->connection,$this->get_password())."',						 
						 		user_login='".mysqli_real_escape_string($dm->connection,$this->get_login())."',						 
						 		user_role='".mysqli_real_escape_string($dm->connection,$this->get_role())."',						 
						 		user_last_updated=NOW(),						
						 		user_last_updated_user='".mysqli_real_escape_string($dm->connection,$this->get_last_updated_user())."'
							
						 	WHERE user_id=".mysqli_real_escape_string($dm->connection,$this->get_id());
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

	// function to delete the record
	public function delete_by_id($id) {
		try{
			$dm = new DataManager();

			$strSQL = "DELETE FROM user WHERE user_id=" . $id;
			$result = $dm->updateRecords($strSQL);
			return $result;
		}
		catch(Exception $e) {
			include_once(CLASSES . 'class_error_handler.php');
			$errorVar = new ErrorHandler();
			$errorVar->notifyAdminException($e);
			exit;
		}
	}

	public function get_by_id($id) {
		try{
			$status = false;
			$dm = new DataManager();
			$strSQL = "SELECT * FROM user WHERE user_id=" . $id;
      
			$result = $dm->queryRecords($strSQL);
			if ($result):
				$row = mysqli_fetch_assoc($result);
        		$this->load($row);
				$status = true;
			endif;

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
  
	public function get_by_email($email) {
		try{
			$status = false;
			$dm = new DataManager();
			$strSQL = "SELECT * FROM user WHERE user_email='" . $email."'";
			$result = $dm->queryRecords($strSQL);
			$num_rows = mysqli_num_rows($result);

			if ($num_rows != 0){
				$row = mysqli_fetch_assoc($result);
        		$this->load($row);
				$status = true;
			} else {
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
	  
      public function get_random_password($length) {
        $chars = "abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789-=+#$@"; // 60 chars
        $pass = '' ;
        while ($length-- > 0) $pass .= substr($chars, rand(0, 59), 1);
        return $pass;
    }
	
	// loads the object data from a mysql assoc array
  private function load($row){
		$this->set_id($row["user_id"]);
		$this->set_first_name($row["user_first_name"]);
		$this->set_last_name($row["user_last_name"]);
		$this->set_user_name($row["user_first_name"] . " " . $row["user_last_name"]);				
		$this->set_email($row["user_email"]);
		$this->set_tel($row["user_tel"]);
		$this->set_password($row["user_password"],true);
		$this->set_login($row["user_login"]);
		$this->set_role($row["user_role"]);
		$this->set_date_created($row["user_date_created"]);
		$this->set_last_updated($row["user_last_updated"]);
		$this->set_last_updated_user($row["user_last_updated_user"]);
	}
}