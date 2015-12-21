<?php
//**************************************************************************************
//  Class: DataManager
//	Description: The DataManager class will act as a proxy to the database, and will
//								contain basic APIs for the application to use.
//**************************************************************************************
class DataManager {

	//*******************************
	// class variables
	//*******************************
	private $dbhost        = '';
	private $dbuser        = '';
	private $dbpass        = '';
	private $dbname        = '';
	public $connection = "";
	private $lastInsertedId = 0;

	
	//*******************************
	// constructor
	//*******************************
	function __construct() {
		try {
			//Load all of the variable data from the config file
			require(INCLUDES . 'config_db.php');
			$this->dbhost = $dbConfig['dbhost'];
			$this->dbuser = $dbConfig['dbuser'];
			$this->dbpass = $dbConfig['dbpass'];
			$this->dbname = $dbConfig['dbname'];	
					
			$this->connection = mysqli_connect("p:". $this->dbhost, $this->dbuser, $this->dbpass);
			if ($this->connection == false){
			echo "Class: DataManager - Method: __construct - mysql_connect failed.";
				throw new Exception("Class: DataManager - Method: __construct - mysql_connect failed.");
			}
			if (mysqli_select_db($this->connection, $this->dbname) == false){
				throw new Exception("Class: DataManager - Method: __construct - mysql_select_db failed.");
			}
		}
		catch(Exception $e) {
			$_SESSION['alert_msg'] = "An unexpected system error has occurred. The Site administrators have been notified. We apologize for any inconvenience. (DataManager) - environment: ". $appConfig["environment"] . " vars: " . $this->dbhost. " / " . $this->dbuser. " / " . $this->dbpass . " / " . $this->dbname;	
			$_SESSION['alert_color'] = "red";
			echo $_SESSION['alert_msg'];
			exit();
			header("location:/error.php");
			exit;		
		}
	}

	//*******************************
	// Methods/Functions
	//*******************************
	public function queryRecords($sql) {
		try {
			$result = mysqli_query($this->connection, $sql);
			if (!$result){
				addtolog(mysqli_error($this->connection));
			}
			return $result;
		}
		catch(Exception $e) {
			echo $e->getMessage();
			//throw new Exception('Class: DataManager - Method: queryRecords - File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Reason: '. $e->getMessage());
			include_once(CLASSES . 'class_error_handler.php');
			$errorVar = new ErrorHandler();
			$errorVar->notifyAdminException($e);
			exit;					
		}
	}
	
	public function updateRecords($sql) {
		try {		
			$result = mysqli_query($this->connection, $sql);
			
			// check for a successful update - if successful return true, otherwise return false
			if($result != false) {
				return true;
			}

			addtolog(mysqli_error($this->connection));
			return false;
		}
		catch(Exception $e) {
			//throw new Exception('Class: DataManager - Method: updateRecords - File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Reason: '. $e->getMessage());
			// CATCH EXCEPTION HERE -- DISPLAY ERROR MESSAGE & EMAIL ADMINISTRATOR
			include_once(CLASSES . 'class_error_handler.php');
			$errorVar = new ErrorHandler();
			$errorVar->notifyAdminException($e);
			exit;			
		}
	}
	
	public function getConnection() {
		//Function is used by the record pager at least temporarily until rewritten
		return $this->connection;
	}
	
	public function getLastInsertedId() {
		return mysqli_insert_id($this->connection);
	}
	
}