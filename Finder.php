<?php

Class Finder {

	var $after = "";

    /* Find Phrase
	 * 
	 * Looks for a phrase within a string
	 *
	 * $phrase (STRING) - Phrase we are looking for
	 * $string (STRING) - String we are searching in
	 *
	 * returns TRUE or FALSE
	 *
	 * $this->after contains the string from where the phrase begins
	 */
	function find_phrase($phrase, $string) 
	{
		if (strpos($string,$phrase) !== false) {
			$start = strpos($string,$phrase, 0);
			$this->after = substr($string,$start,strlen($string));
			return true;
		} else {
			return false;
		}
	}

	/* Get String Between
	 *
	 * Gets a string between two other strings.
	 *
	 * $string (STRING) - The String we are searching in
	 * $start (STRING) - Between start point
	 * $end (STRING) - Between end point
	 *
	 * return STRING
	 *
	 */
	function get_string_between($string, $start, $end){
	    $string = ' ' . $string;
	    $ini = strpos($string, $start);
	    if ($ini == 0) return '';
	    $ini += strlen($start);
	    if(empty($end)) {
	    	$len = strlen($string);
	    } else {
		    $len = strpos($string, $end, $ini) - $ini;
		}
	    return substr($string, $ini, $len);
	}


}


?>