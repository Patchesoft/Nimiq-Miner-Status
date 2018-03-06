<?php

Class Nimiq {

	var $result = "";
	var $server = "";
	var $data = array();

	/* Construct Nimiq Server
	 *
	 * Sets the server to check for, grabs the log file and splits it line
	 * by line
	 *
	 * $url (STRING) - URL of the logfile to parse
	 *
	 */
	public function __construct($url) 
	{
		$this->server = $url;
		$doc = $this->get_url($url);
		$this->result = explode("\n",$doc);
	}


	/* Get Data
	 *
	 * Parses the Logfile to obtain various data points
	 *
	 * return $this (object)
	 */
	public function get_data() 
	{
		$finder = new Finder();

		$wallet = "";
		$hashrate = "";
		$latest_block = "";
		$timestamp = "";
		foreach($this->result as $line) {
		    if(!empty($line)) {
		        if($finder->find_phrase("Node: Wallet initialized for address", $line)) {
		            $wallet = $finder->get_string_between($line, "Node: Wallet initialized for address", ".");
		        }
		        if($finder->find_phrase("Miner: Starting work on BlockHeader", $line)) {
		            $hashrate = $finder->get_string_between($line, "hashrate=", "H/s");
		        }
		        if($finder->find_phrase("Node: Now at block", $line)) {
		            $latest_block = $finder->get_string_between($line, "Node: Now at block: ", "");
		        }
		        if($finder->find_phrase("Miner: Starting work on BlockHeader", $line)) {
		            $timestamp = $finder->get_string_between($line, "timestamp=", ",");
		        }
		    }
		}

		$this->data = array(
			"wallet" => $wallet,
			"hashrate" => $hashrate,
			"latest_block" => $latest_block,
			"timestamp" => $timestamp,
			"server" => $this->server
		);

		return $this;
	}

	/* Display
	 *
	 * Displays the data points in HTML table
	 *
	 * return $html (STRING)
	 */
	public function display() 
	{
		$timeout = "";
		if(time() - $this->data['timestamp'] > 300) $timeout='<strong>NOT UPDATED</strong>';
		$html = '<div class="bubble">';
		if(!empty($this->data['wallet'])) {
		$html .= '<table>
		    <tr><td width="100"><strong>Object</strong></td><td width="300"><strong>Data</strong></td></tr>
		    <tr><td>Server</td><td>'. $this->data['server'] .'</td></tr>
		    <tr><td>Wallet</td><td>'. $this->data['wallet'] .'</td></tr>
		    <tr><td>HashRate</td><td>'.$this->data['hashrate'] .' H/s</td></tr>
		    <tr><td>Latest Block</td><td>'.$this->data['latest_block'] .'</td></tr>
		    <tr><td>Timestamp</td><td>'. date("jS F H:i:s", $this->data['timestamp']).' ' . $timeout . '</td></tr>
		</table>';
		} else {
		    $html .='Could not find wallet for '. $this->data['server'];
		}
		$html.='</div>';
		return $html;
	}

	/* Get URL
	 *
	 * Gets the contents of the URL
	 *
	 * return $JsonResponse (STRING)
	 */
	private function get_url($request_url) {

		$curl_handle = curl_init();
	    curl_setopt($curl_handle, CURLOPT_URL, $request_url);
	    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 0);
	    //curl_setopt($curl_handle, CURLOPT_RANGE, '0-500');
	    curl_setopt($curl_handle, CURLOPT_TIMEOUT, 0);
	    curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, FALSE); 
	    curl_setopt($curl_handle, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	    curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
	    $JsonResponse = curl_exec($curl_handle);
	    $http_code = curl_getinfo($curl_handle);

	  return($JsonResponse);
	}

}


?>