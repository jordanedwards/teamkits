<?php 
 class Cmscomponent extends Base {
 
		public $id;
		private $tag;
 		private $content;
 		private $extra;
 		private $is_active;
 		private $date_created;
 		private $last_updated;
 		private $last_updated_user;
		public $table_name;
 		
	function __construct() {
		$this->table_name = "cmscomponent";
	}
	
	public function get_id() { return $this->id;}
	public function set_id($value) {$this->id=$value;}

	public function get_tag() { return $this->tag;}
	public function set_tag($value) {$this->tag=$value;}
		
	public function get_content() { return $this->content;}
	public function set_content($value) {$this->content=$value;}
		
	public function get_extra() { return $this->extra;}
	public function set_extra($value) {$this->extra=$value;}
		
	public function get_is_active() { return $this->is_active;}
	public function set_is_active($value) {$this->is_active=$value;}
		
	public function get_date_created() { return $this->date_created;}
	public function set_date_created($value) {$this->date_created=$value;}
		
	public function get_last_updated() { return $this->last_updated;}
	public function set_last_updated($value) {$this->last_updated=$value;}
		
	public function get_last_updated_user() { return $this->last_updated_user;}
	public function set_last_updated_user() {$this->last_updated_user=$this->get_user_id();}
	
	// Inherited functions from BASE class:
	// Full documentation in class_base.php
	
	/*
		__toString(): Use to debug, like this: echo $component
		clear(): Empty all properties
		save(): Maps the values stored in the class functions whose name matches the database field, saves a new record if no id given, creates a new one if no id given
		delete($type = ""): If argument "full" is passed, the delete function removes the record, if not, it just makes the record inactive
		get_by_id($id): Populate object with values from database record
		load($array): Populate object with values from an array where array keys match the property names. 
		get_json_data(): Returns object values as key/value pairs in JSON format. Useful for ajax.
	*/	
	
	// function to fetch the record and populate the object
	public function get_by_tag($tag) {
		try{
			if ($tag != ""){
				$status = false;
				$dm = new DataManager();
				$strSQL = "SELECT * FROM cmscomponent WHERE tag='" . $tag . "'";

				$result = $dm->queryRecords($strSQL);
				if ($result){
					$row = mysqli_fetch_assoc($result);
					$this->load($row);
					$status = true;
				}
	
				return $status;
			} else {
			exit("tag not set");
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
}

function get_component($tag){
	$component = new Cmscomponent();
	$component->get_by_tag($tag);
	echo $component->get_content();	
}