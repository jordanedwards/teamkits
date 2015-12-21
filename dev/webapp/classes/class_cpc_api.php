<?php
 /**
 * Class to interact with Canada Post's Shipping API
 * 
 * The service returns a list of shipping services, prices and transit times 
 * for a given item to be shipped. 
 *
 * Requires from and to postal codes + weight of item.
*/

class CPC {
 
		private $api_username;
		private $api_password;
		private $customer_number;
		private $pc_origin;
		private $pc_destination;
		private $country;
		private $weight;
		private $service_url;
		private $success;
		public $errors;
		private $json_data;
 		
	function __construct() {
		// Pull constants defined in init (from the settings table)
		if (CPC_ENVIRONMENT == "live"){
			$this->api_username = CPC_API_PRO_USERNAME;
			$this->api_password = CPC_API_PRO_PASSWORD;
		} else {
			// development
			$this->api_username = CPC_API_DEV_USERNAME;
			$this->api_password = CPC_API_DEV_PASSWORD;	
		}
		$this->customer_number = CPC_CUSTOMER_NUMBER;
		$this->service_url = 'https://ct.soa-gw.canadapost.ca/rs/ship/price';
	}
	
	public function get_pc_origin() { return $this->pc_origin;}
	public function set_pc_origin($value) {$this->pc_origin=$value;}
	
	public function get_pc_destination() { return $this->pc_destination;}
	public function set_pc_destination($value) {$this->pc_destination=$value;}
	
	public function get_weight() { return $this->weight;}
	public function set_weight($value) {$this->weight=$value;}		
	
	public function get_country() { return $this->country;}
	public function set_country($value) {$this->country=$value;}	
	
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
	
	public function get_json_data(){
		$this->request();
//        return json_encode($this->json_data);
		return $this->json_data;
	}
	
  	public function load($array){
  		foreach ($array as $key => $val){
			if(property_exists('CPC',$key)):
				$method_name = "set_".$key;
				$this->$method_name($val);
			endif;
		}
	} 	
	
	public function request(){
		if (isset($this->weight) && isset($this->pc_origin) && isset($this->pc_destination)){
		} else {
			$this->success = 0;
			$this->errors = "Weight or postal codes not set";
			exit($this->errors);
		}
		if ($this->weight == 0){
			//$this->json_data = '{"HTTP Response Status":"200","Expedited Parcel USA":"0","Priority Worldwide envelope USA":"0","Priority Worldwide pak USA":"0","Priority Worldwide parcel USA":"0","Small Packet USA Air":"0","Tracked Packet - USA":"0","Xpresspost USA":"0","Expedited Parcel":"0","Priority":"0","Regular Parcel":"0","Xpresspost":"0"}';
			$this->json_data = '{"zeroval":"true"}';			
			return;
		}
		$str = "{";
		
$xmlRequest = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<mailing-scenario xmlns="http://www.canadapost.ca/ws/ship/rate-v3">
  <customer-number>{$this->customer_number}</customer-number>
  <parcel-characteristics>
	<weight>{$this->weight}</weight>
  </parcel-characteristics>
  <origin-postal-code>{$this->pc_origin}</origin-postal-code>
XML;
if ($this->country==1){
$xmlRequest .= <<<XML
  <destination>
	<domestic>
	  <postal-code>{$this->pc_destination}</postal-code>
	</domestic>
  </destination>
</mailing-scenario>
XML;
}else{
$xmlRequest .= <<<XML
  <destination>
	<united-states>
	  <zip-code>{$this->pc_destination}</zip-code>
	</united-states>
  </destination>
</mailing-scenario>
XML;
}
		$curl = curl_init($this->service_url); // Create REST Request
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($curl, CURLOPT_CAINFO, INCLUDES . 'cacert.pem');		
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $xmlRequest);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_USERPWD, $this->api_username . ':' . $this->api_password);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/vnd.cpc.ship.rate-v3+xml', 'Accept: application/vnd.cpc.ship.rate-v3+xml'));
		$curl_response = curl_exec($curl); // Execute REST Request
		if(curl_errno($curl)){
			$this->success = 0;
			$this->errors .= 'Curl error: ' . curl_error($curl) . "\n ";
		}
		
		$str .= '"HTTP Response Status":"' . curl_getinfo($curl,CURLINFO_HTTP_CODE) . '",';
		curl_close($curl);
		
		//addtolog($curl_response);
		
		// Example of using SimpleXML to parse xml response
		libxml_use_internal_errors(true);
		$xml = simplexml_load_string('<root>' . preg_replace('/<\?xml.*\?>/','',$curl_response) . '</root>');
		if (!$xml) {
			$this->success = 0;
			$this->errors = 'Failed loading XML
			' .  $curl_response . '
			';
			foreach(libxml_get_errors() as $error) {
				$this->errors .= "\t" . $error->message;
			}
		} else {
			$this->success = 1;
			if ($xml->{'price-quotes'} ) {
				$priceQuotes = $xml->{'price-quotes'}->children('http://www.canadapost.ca/ws/ship/rate-v3');
				if ( $priceQuotes->{'price-quote'} ) {
					$not_first=false;
				//	$str .= '"services":[';
					foreach ( $priceQuotes as $priceQuote ) {  
						if ($not_first){
							$str.= ',';
						}
						$str .= '"'. $priceQuote->{'service-name'} . '":"' . $priceQuote->{'price-details'}->{'due'} . '"';
				//		$str .= '{"service_name":"' . $priceQuote->{'service-name'} . '",';
				//		$str .= '"price":"' . $priceQuote->{'price-details'}->{'due'} . '"}';	
						$not_first = true;
					}
				//	$str .= "]";					
				}
			}
			if ($xml->{'messages'} ) {					
				$messages = $xml->{'messages'}->children('http://www.canadapost.ca/ws/messages');		
				foreach ( $messages as $message ) {
					$this->errors .= 'Error Code: ' . $message->code . "\n";
					$this->errors .= 'Error Msg: ' . $message->description . "\n\n";
				}
			}
		}
		$str.= ',"errors":"' . $this->errors . '"';		
		$str.= "}";
		$this->json_data = $str;
	}  

}