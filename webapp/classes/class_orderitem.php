<?php 
 class Orderitem extends SessionManager {
 
		private $id;
		private $order_id;
 		private $item_number;
 		private $price;
 		private $quantity;
 		private $size;
		private $no;
		private $name;		
 		private $discount;
 		private $active;
		private $currency;
		private $item_price;
		private $item_name;
 		private $date_created;
 		private $last_updated;
 		private $last_updated_user;
 		
	function __construct() {
	
	}
		public function get_id() { return $this->id;}
		public function set_id($value) {$this->id=$value;}

		public function get_order_id() { return $this->order_id;}
		public function set_order_id($value) {$this->order_id=$value;}
		
		public function get_item_number() { return $this->item_number;}
		public function set_item_number($value) {$this->item_number=$value;}
		public function get_item_price() { return $this->item_price;}
		public function get_item_name() { return $this->item_name;}

		public function get_price() { return $this->price;}
		public function set_price($value) {$this->price=$value;}
		
		public function get_quantity() { return $this->quantity;}
		public function set_quantity($value) {$this->quantity=$value;}
		
		public function get_size() { return $this->size;}
		public function set_size($value) {$this->size=$value;}
		
		public function get_no() { return $this->no;}
		public function set_no($value) {$this->no=$value;}
						
		public function get_name() { return $this->name;}
		public function set_name($value) {$this->name=$value;}
				
		public function get_discount() { return $this->discount;}
		public function set_discount($value) {$this->discount=$value;}
		
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

	if (!$this->check_currency()){
		// Item and order currencies do not match
	}else{
		try{
			$dm = new DataManager();
			$this->set_last_updated_user();
			
			// if record does not already exist, create a new one
			if($this->get_id() == 0) {
				$this->set_active("Y");
			
				$strSQL = "INSERT INTO orderitem (orderitem_id, orderitem_order_id, orderitem_item_number, orderitem_price, orderitem_quantity, orderitem_size, orderitem_no, orderitem_name, orderitem_discount, is_active, orderitem_date_created, orderitem_last_updated, orderitem_last_updated_user) 
        VALUES (
				'".mysqli_real_escape_string($dm->connection, $this->get_id())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_order_id())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_item_number())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_price())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_quantity())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_size())."',				
				'".mysqli_real_escape_string($dm->connection, $this->get_no())."',				
				'".mysqli_real_escape_string($dm->connection, $this->get_name())."',				
				'".mysqli_real_escape_string($dm->connection, $this->get_discount())."',
				'".mysqli_real_escape_string($dm->connection, $this->get_active())."',
				NOW(),
				NOW(),
				'".mysqli_real_escape_string($dm->connection, $this->get_last_updated_user())."')";	
						}
			else {
				$strSQL = "UPDATE orderitem SET 
								orderitem_order_id='".mysqli_real_escape_string($dm->connection, $this->get_order_id())."',						 
						 		orderitem_item_number='".mysqli_real_escape_string($dm->connection, $this->get_item_number())."',						 
						 		orderitem_price='".mysqli_real_escape_string($dm->connection, $this->get_price())."',
						 		orderitem_quantity='".mysqli_real_escape_string($dm->connection, $this->get_quantity())."',	
						 		orderitem_size='".mysqli_real_escape_string($dm->connection, $this->get_size())."',
						 		orderitem_no='".mysqli_real_escape_string($dm->connection, $this->get_no())."',
						 		orderitem_name='".mysqli_real_escape_string($dm->connection, $this->get_name())."',
						 		orderitem_discount='".mysqli_real_escape_string($dm->connection, $this->get_discount())."',
						 		is_active='".mysqli_real_escape_string($dm->connection, $this->get_active())."',						 
						 		orderitem_last_updated=NOW(),						
						 		orderitem_last_updated_user='".mysqli_real_escape_string($dm->connection, $this->get_last_updated_user())."'
							
						 	WHERE orderitem_id=".mysqli_real_escape_string($dm->connection, $this->get_id());
			}		
				
			$result = $dm->updateRecords($strSQL);

			// if this is a new record get the record id from the database
			if($this->get_id() == "0") {
				$this->set_id(mysqli_insert_id($dm->connection));
			}
			
          	if (!$result):
      			throw new Exception("Failed Query: ". $strSQL);
   			endif;
          
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
	}

	// function to delete the record
	public function delete() {
		try{
			$dm = new DataManager();

			$strSQL = "DELETE FROM orderitem WHERE orderitem_id=" . $this->id;
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
			$strSQL = "SELECT * FROM orderitem WHERE orderitem_id=" . $id;
      
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
  		foreach ($array as $key => $val){
			if(property_exists('orderitem',$key)):
				$method_name = "set_".$key;
				$this->$method_name($val);
			endif;
		}
	} 
	
	public function get_item_details(){
		$dm = new DataManager();			
		$strSQL = "SELECT * FROM item 
		LEFT JOIN brand ON item.item_brand = brand.brand_id
		WHERE item_id = " .$this->item_number;
		$result = $dm->queryRecords($strSQL);
					
		if ($result):
			while ($line = mysqli_fetch_assoc($result)):
				$this->item_name = $line['item_name'];
				$this->item_price = $line['item_price'];
				$this->item_currency = $line['brand_currency'];
				if ($this->item_currency == ""){
					$this->item_currency = 1;
				}
			endwhile;	
		endif;
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
	
	public function check_currency(){
		// Check to make sure that the manufacturer of this item has been set to the same currency as the order:
		// Get item details if we haven't yet:
		if($this->item_name == ""){
			$this->get_item_details();
		}
		
		$dm = new DataManager();	
		$strSQL = "SELECT id, shortname FROM currency";
		$result = $dm->queryRecords($strSQL);
					
		if ($result):
			while ($line = mysqli_fetch_assoc($result)):
				$currency_name[$line["id"]]=$line["shortname"];
			endwhile;	
		endif;
				
		$strSQL = "SELECT order_currency FROM orders WHERE order_id = " .$this->order_id;
		$result = $dm->queryRecords($strSQL);
					
		if ($result):
			while ($line = mysqli_fetch_assoc($result)):
				$order_currency = $line["order_currency"];
			endwhile;	
		endif;
		
		if ($this->item_currency != $order_currency && $order_currency != 0){
			$this->set_success(false);
			$this->setAlertMessage("Order item (" . $currency_name[$this->item_currency].") is priced in a different currency than the order (" . $currency_name[$order_currency]."). Please <a href='orders_edit.php?id=0'>create a new order</a> for this item.");		
			return false;
		}else {
		//	if ($order_currency == 0){
				// Set order's currency from the item's currency if the order currency is not set:
				$this->set_currency($this->item_currency);
			//	return true;
		//	}
			return true;
		}
	}
	
	public function set_currency($currency){
	addtolog("Order: " .$this->order_id . "/ item currency: " . $currency);
		if ($this->order_id > 0 && $currency > 0){
			include_once("class_orders.php");
			$order= new Orders();
			$order->get_by_id($this->order_id);
			$order->set_currency($currency);
			$order->save();
		}
	}
		  
	// loads the object data from a mysql assoc array
  	private function load($row){
		$this->set_id($row["orderitem_id"]);
		$this->set_order_id($row["orderitem_order_id"]);
		$this->set_item_number($row["orderitem_item_number"]);
		$this->set_price($row["orderitem_price"]);
		$this->set_quantity($row["orderitem_quantity"]);
		$this->set_size($row["orderitem_size"]);		
		$this->set_no($row["orderitem_no"]);		
		$this->set_name($row["orderitem_name"]);		
		$this->set_discount($row["orderitem_discount"]);
		$this->set_active($row["is_active"]);
		$this->set_date_created($row["orderitem_date_created"]);
		$this->set_last_updated($row["orderitem_last_updated"]);
		$this->set_last_updated_user($row["orderitem_last_updated_user"]);
		
  }
}