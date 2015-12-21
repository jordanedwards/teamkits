<?php

	class Cart {
		private $items = array();
		private $weight = 0;
		
		function __construct() {
			$this->start_cart();   
        }
        
        /**
		 * Outputs the initial text plus the options for the cart
		 *
		 * @uses cart_options - Outputs the options for the cart as well as the form to select a task
		 */
        private function start_cart(){
	   //     echo "Welcome to Virtual Shopping Cart! Here are a list of tasks you can do:<br/>";
	    //	$this->cart_options();
        }
		
		/**
		 * Outputs the options for the cart plus the form o select a task
		 */					  
		public function cart_options(){
			//echo "<br/>1. Add item to the cart";
			echo "<br/>2. Delete item from the cart";
			echo "<br/>3. List all items and prices in the cart";
			echo "<br/>4. Request total of the cart";
			echo "<br/>5. Reset Cart<br/><br/>";
			echo "<form method='post' action=''>";
			echo "<label>Which task would you like to perform?</label>";
			echo "<input type='text' name='cart_action' placeholder='Choose a number above' />";
			echo "<input type='submit' name='' value='Enter Task' />";
			echo "</form>";
		}
		
		/**
		 * Outputs a list of items that are currently in the cart and display an error message if there are no items
		 *
		 * @uses cart_error - display an error message if there are no items in the cart
		 */
		public function get_items(){
			return $this->items;
		}
		
		public function get_weight(){
		// Adds up the weight for all the items in the cart EXCEPT those that belong to a club order already.
			
				$dm = new DataManager();
				$strSQL = "SELECT item_weight, item_id
				FROM item";						

				$result = $dm->queryRecords($strSQL);	
				if ($result){
					while($row = mysqli_fetch_assoc($result)):
						$weights[$row['item_id']] = $row['item_weight'];
					endwhile;	
				}
				//addtolog($weights);
				
				foreach($this->items as $name => $val){
					if ($val['orderitem_id'] > 0){
						// Exclude items that are from a club order. This is important so that shipping doesn't get added twice.
					} else {
						$calculated = $calculated + $weights[$val['item_id']] * $val['qty'];
					}
				}
				$this->weight = $calculated;
			//	addtolog($calculated);	

			return $this->weight;
		}		

		public function show_items(){
			if(empty($this->items))
				$this->cart_error(1);
			else{
			
				$num = 1;
				$str ='<table class="admin_table">
			<thead>
			<tr><th>Item:</th><th>Quantity</th><th>Size</th><th>Price/item</th></tr>		
			</thead>
			
			<tbody id="order_items_table">';
				foreach($this->items as $name => $val){
					$str .= "<tr style='background: #efefef;'><td>". ucfirst($name) . "</td><td>" . $val["qty"] . "</td><td>" . $val["size"] . "</td><td style='text-align: right;'>$" . number_format($val['price'], 2, '.', ','). "</td></tr>";
					$itemCount = $itemCount + $val["qty"];
				}
				$str .= "</tbody>
			<tfoot>
				<tr style='background: #efefef;'><td>Subtotal:</td><td>" . $itemCount . "</td><td></td><td style='text-align: right;'>$" . $this->cart_total() . "</td><td></td></tr>
			</tfoot>
		</table>";
			return $str;

			}	
		}
				
		/**
		 * Outputs the form for both adding and deleting an item to/from the cart
		 *
		 * @param action - takes in a string of "add" or "delete" and ouputs the corresponding form
		 */
		public function item_form($action){
			echo "<form method='post' action=''>";
			
			if($action == "add"){
				echo "<label>Please enter the name and price of the item you would like to add:</label>";
				echo "<input type='text' name='item_name' placeholder='Item Name' />";
				echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type='text' name='item_price' placeholder='Item Price' />";
				echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type='text' name='item_qty' placeholder='Item Qty' />";		
				echo "<input type='submit' name='' value='Add Item' />";	
			}
			else{
				echo "<label>Please enter the name and qty of the item you would like to delete:</label>";
				echo "<input type='text' name='item_name' placeholder='Item Name' />";
				echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type='text' name='item_qty' placeholder='Item Qty' />";			
				echo "<input type='submit' name='' value='Delete Item' />";
			}
			
			echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='go_back' value='Go Back' />";
			echo "</form>";
		}
		
		/**
		 * Displays an error if the name is anything but characters and the price or qty is not a valid number, otherwise adds the item to the items array
		 *
		 * @uses cart_error - displays an error message if the name is anything but characters and the price or qty is not a valid number
		 * @uses cart_success - displays a success message if the item gets added to the items array
		 * @param name - name of the item
		 * @param price - price of the item
		 * @param qty - qty of the item
		 */
		public function add_item($name, $item_id=0, $price=0, $qty=1, $size="", $orderitem_id=0, $jerseyRecord_id=0){
			$name = strtolower($name);
			
		//	preg_match('/[a-zA-Z]*/', $name, $matches);
			
		//	if($matches[0] === "")
		//		$this->cart_error(5);
			if(!is_numeric($price) || !is_numeric($qty))
				$this->cart_error(4);
			if(array_key_exists($name, $this->items)){
				$this->items[$name]["qty"] += $qty;
				$this->cart_success(2, $name);	
			}
			else{
				$this->items[$name] = array("item_id"=> $item_id, "qty" => $qty, "size"=> $size, "price" => $price, "orderitem_id" => $orderitem_id, "jerseyRecord_id" => $jerseyRecord_id);
				$this->cart_success(3, $name);
			}
		}
		
		public function edit_item($name, $attName, $attVal){
			// attributes are: 
			// array("item_id"=> $item_id, "qty" => $qty, "size"=> $size, "price" => $price, "orderitem_id" => $orderitem_id, "jerseyRecord_id" => $jerseyRecord_id)

			$this->items[$name][$attName]=$attVal;
		}		

		public function add_jersey_item($jerseyRecord_id){
		
			$dm = new DataManager();
			$query = "SELECT * FROM jerseyRecord 
			LEFT JOIN orderitem ON jerseyRecord.orderitem_id = orderitem.orderitem_id
			LEFT JOIN item ON orderitem.orderitem_item_number = item.item_id
			WHERE jerseyRecord.id = $jerseyRecord_id
			LIMIT 1";
			$result = $dm->queryRecords($query);
			if($result):
				while ($row = mysqli_fetch_array($result)):
					$order_id = $row['orderitem_order_id'];
				endwhile;
			endif;
									
			$quantity_count_sql = "SELECT SUM(orderitem_quantity) AS qtycount from orderitem 
			LEFT JOIN item ON orderitem.orderitem_item_number = item.item_id 
			WHERE orderitem.orderitem_order_id=" . $order_id . "
			AND orderitem.is_active = 'Y'";
			$result = $dm->queryRecords($quantity_count_sql);	
			if ($result):
				while($row = mysqli_fetch_assoc($result)):
					$quantity_count = $row['qtycount'];
				endwhile;
			endif;
			
			$shipping_charge_sql = "SELECT orderitem_price from orderitem 
			LEFT JOIN item ON orderitem.orderitem_item_number = item.item_id 
			WHERE item_id=17 AND orderitem.orderitem_order_id=" . $order_id . "
			AND orderitem.is_active = 'Y'";			
			$result = $dm->queryRecords($shipping_charge_sql);	
			if ($result):
				while($row = mysqli_fetch_assoc($result)):
					$shipping_charges = $row['orderitem_price'];
				endwhile;
			endif;	
							
			$charge_per_item = $shipping_charges/($quantity_count-1);
			
			$query = "SELECT * FROM jerseyRecord 
			LEFT JOIN orderitem ON jerseyRecord.orderitem_id = orderitem.orderitem_id
			LEFT JOIN item ON orderitem.orderitem_item_number = item.item_id
			WHERE jerseyRecord.id = $jerseyRecord_id
			LIMIT 1";
			
			$result = $dm->queryRecords($query);
			
			if($result){
				while ($row = mysqli_fetch_array($result)):
					if($row['name'] != "" || $row['number'] != ""){
						// If name or number given, use them to label the item in the shopping cart:
						$this->items[$row['item_name']." (" . $row['name']."/#" . $row['number'].")"] = array("item_id"=> $row['orderitem_item_number'], "qty" => 1, "size"=> $row['orderitem_size'], "price" => $row['orderitem_price']+$charge_per_item, "orderitem_id" => $row['orderitem_id'], "jerseyRecord_id" => $jerseyRecord_id);
						$this->cart_success(3, $row['item_name']." (" . $row['name']."/#" . $row['number'].")");
					} else {
						// If not, then use the jersey record ID to keep them seperate:
						$this->items[$row['item_name']." (id# " . $row['id'].")"] = array("item_id"=> $row['orderitem_item_number'], "qty" => 1, "size"=> $row['orderitem_size'], "price" => $row['orderitem_price']+$charge_per_item, "orderitem_id" => $row['orderitem_id'], "jerseyRecord_id" => $jerseyRecord_id);
						$this->cart_success(3, $row['item_name']." (id# " . $row['id'].")");					
					}
				endwhile;
			} else {
				$this->cart_error(4);
			}
		}		

		public function delete_item($name, $qty){
			//$name = strtolower($name);
			
			if(!array_key_exists($name, $this->items))
				$this->cart_error(3);
			elseif(!is_numeric($qty))
				$this->cart_error(4);
			elseif($this->items[$name]["qty"] <= $qty){
				unset($this->items[$name]);
				$this->cart_success(4, $name);
			}
			else{
				$this->items[$name]["qty"] -= $qty;
				$this->cart_success(5, $name);
			}
		}
		
		/**
		 * Displays the total cost of all the items in the cart
		 */
		public function cart_total(){
			$total = 0;
			
			foreach($this->items as $name => $price){
				$total += $price['price'] * $price['qty']; 
			}
			return number_format($total, 2, '.', ',');
		}
		
		/**
		 * Deletes all of the items out of the items array
		 */
		public function reset_cart(){
			$this->items = array();
		}
		
		/**
		 * Displays all the error messages for the cart
		 *
		 * @uses get_items - Outputs a list of items that are currently in the cart and display an error message if there are no items
		 * @param err_num - the error code
		 */
		public function cart_error($err_num){
			echo "<p style='font-style: italic; margin-bottom: 10px;'>";
			
			switch($err_num){
				case 1:
					echo "You do not have any items in your cart.";
					return;
				case 2:
					echo "You have entered an invalid task option. Please choose a number 1 - 5.";
					break;
				case 3:
					echo "That item is not in you cart. Please take a look below and try again.<br/><br/>";
					$this->get_items();
					break;
				case 4:
					echo "You did not enter a valid number. Please try again.";
					break;
				case 5:
					echo "Only the letters a - z or A - Z are allowed. Please try again.";
					break;
			}	
			
			echo "</p>";
			
		//	$this->cart_options();
		}
		
		/**
		 * Displays all the success messages for the cart
		 *
		 * @uses get_items - Outputs a list of items that are currently in the cart and display an error message if there are no items
		 * @uses start_cart - Outputs the initial text plus the options for the cart
		 * @uses cart_total - Displays the total cost of all the items in the cart
		 * @uses cart_options - Outputs the options for the cart plus the form o select a task
		 * @param success_num - the success code
		 * @param name - the name of the item
		 */
		public function cart_success($success_num, $name = ""){
			echo "<p style='color: green; margin-bottom: 0px;'>";
			
			switch($success_num){
				case 1:
					$this->get_items();
					break;
				case 2:
					echo "You have added more of item <span style='font-style: italic;'>" . strtoupper($name) . "</span> to your cart for a new qty of <span style='font-style: italic;'>" . $this->items[$name]["qty"] . "</span>.";
					break;
				case 3:
					echo "You have added the item <span style='font-style: italic;'>" . strtoupper($name) . "</span> for a quantity of " . $this->items[$name]["qty"] . " and a price of <span style='font-style: italic;'>$" . number_format($this->items[$name]["price"], 2, '.', ',') . "</span>.";
					break;
				case 4:
					echo "You have successfully deleted item <span style='font-style: italic;'>" . strtoupper($name) . "</span> in your cart.";
					break;
				case 5:
					echo "You now have a quantity of <span style='font-style: italic;'>" . $this->items[$name]["qty"] . "</span> of item <span style='font-style: italic;'>" . strtoupper($name) . "</span> from your cart.";
					break;
				case 6:
					$this->cart_total();
					break;
				case 7:
					echo "Cart has been reset!";
					$this->reset_cart();
					break;
			}	
			
			echo "</p>";
			
			if($success_num === 7)
				$this->start_cart();
			//else{
			//	$this->cart_options();
		}
	}
?>