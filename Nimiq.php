<?php

Class Nimiq {

	var $result = "";
	var $server = "";
	var $timestamp = "";
	var $data = array();
	var $headers = array();
	var $alias = "";
	var $hide_active = 0;

	/* Construct Nimiq Server
	 *
	 * Sets the server to check for, grabs the log file and splits it line
	 * by line
	 *
	 * $url (STRING) - URL of the logfile to parse
	 *
	 */
	public function __construct($url, $alias, $hide = 0) 
	{
		$this->server = $url;
		$this->alias = $alias;
		$this->hide_active = $hide;
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
		$balance = "";
		foreach($this->result as $line) {
		    if(!empty($line)) {
		        if($finder->find_phrase("Node: Wallet initialized for address", $line)) {
		            $wallet = $finder->get_string_between($line, "Node: Wallet initialized for address", ".");
		        }
		        if($finder->find_phrase("Node: Hashrate: ", $line)) {
		            $hashrate = $finder->get_string_between($line, "Node: Hashrate: ", "H/s");
		        }
		        if($finder->find_phrase("Node: Now at block", $line)) {
		            $latest_block = $finder->get_string_between($line, "Node: Now at block: ", "");
		        }
		        if($finder->find_phrase("- Balance: ", $line)) {
		            $balance = $finder->get_string_between($line, "- Balance: ", " NIM -");
		        }
		    }
		}

		// Get timestamp
		if (isset($this->headers['last-modified'])) { 
			$this->timestamp = $this->headers['last-modified'][0];
			// Convert to timestamp
			$datetime = DateTime::createFromFormat("D, d M Y H:i:s e", $this->timestamp);
			$this->timestamp = $datetime->getTimestamp();
		} else {
			$this->timestamp = 0;
		}

		$this->data = array(
			"wallet" => $wallet,
			"hashrate" => $hashrate,
			"latest_block" => $latest_block,
			"timestamp" => $this->timestamp,
			"server" => $this->server,
			"balance" => $balance
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
		if($this->hide_active == 0) {
			if($this->data['timestamp'] == 0) {
				$timeout = "<strong>Unable to get last-modified header</strong>";
			} else {
				if(time() - $this->data['timestamp'] > 300) {
					$timeout='<strong>NOT UPDATED</strong>';
				} else {
					$timeout='<span class="label label-success small-text pull-right">Active</span>';
				}
			}
		}
		$html = '<div class="bubble">';
		if(!empty($this->data['wallet'])) {
		$html .= '<table class="">';
		if(!empty($this->alias)) {
			$html .= '<tr><td width="100">Server</td><td><strong>'. $this->alias .'</strong></td></tr>';
		} else {
			$html .= '<tr><td width="100">Server</td><td>'. $this->data['server'] .'</td></tr>';
		}
		$html .= '<tr><td>Wallet</td><td>'. $this->data['wallet'] .'</td></tr>
		    <tr><td>Balance</td><td>'. number_format($this->data['balance'], 2) .' NIM</td></tr>
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
		$headers = array();
	    curl_setopt($curl_handle, CURLOPT_URL, $request_url);
	    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 0);
	    //curl_setopt($curl_handle, CURLOPT_RANGE, '0-500');
	    curl_setopt($curl_handle, CURLOPT_TIMEOUT, 0);
	    curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, FALSE); 
	    curl_setopt($curl_handle, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	    curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl_handle, CURLOPT_HEADERFUNCTION,
		  function($curl, $header) use (&$headers)
		  {
		    $len = strlen($header);
		    $header = explode(':', $header, 2);
		    if (count($header) < 2) // ignore invalid headers
		      return $len;

		    $name = strtolower(trim($header[0]));
		    if (!array_key_exists($name, $headers))
		      $headers[$name] = [trim($header[1])];
		    else
		      $headers[$name][] = trim($header[1]);

		    return $len;
		  }
		);
	    $JsonResponse = curl_exec($curl_handle);
	    $http_code = curl_getinfo($curl_handle);
	    $this->headers = $headers;

	    return($JsonResponse);
	}

}


?>