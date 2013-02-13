<?php
	/**
	 * This helper extends the built-in CI array helper and contains PHP functions
	 * designed to manipulate arrays in ways that could be useful in multiple
	 * instances and projects.
	 * 
	 * @package CI_GeneralESLibs
	 * @subpackage Helpers
	 * @category Helpers
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @since 2008-06-18 
	 * @version 2009-04-01
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 */

	if ( ! function_exists('Flip2DArray'))
	{
		/**
		 * This function will take a square or rectangle 2d array and flip the row
		 * and column indicies on it.
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since Unknown
		 * @version Unknown
		 * 
		 * @param array $StartArray The 2d array to be flipped.
		 * @return array The flipped 2d array.
		 */
		function Flip2DArray($StartArray)
		{
			if(sizeof($StartArray)==0 || gettype($StartArray)!="array")
				return array();
				
			$RowIndexNames = array_keys($StartArray);
			$ColIndexNames = array_keys($StartArray[$RowIndexNames[0]]);
			
			foreach($ColIndexNames as $j => $ColName)
			{
				foreach($RowIndexNames as $k => $RowName)
				$FlippedArray[$ColName][$RowName] = $StartArray[$RowName][$ColName];
			}
			
			return $FlippedArray;
		}
	}
	
	if ( ! function_exists('array_delete'))
	{
		/**
		 * This function will remove one or more elements from an array based on indexes
		 * 
		 * @author alan.lake@lakeinfoworks.com
		 * @since Unknown
		 * @version Unknown
		 * 
		 * @param $ary The array (passed by reference) to remove the element from
		 * @param $key_to_be_deleted The key (or array of keys) to delete
		 */
		function array_delete(&$ary,$key_to_be_deleted)
	    {
	        $new = array();
	        if(is_string($key_to_be_deleted)) {
	            if(!array_key_exists($key_to_be_deleted,$ary)) {
	                return;
	            }
	            foreach($ary as $key => $value) {
	                if($key != $key_to_be_deleted) {
	                    $new[$key] = $value;
	                }
	            }
	            $ary = $new;
	        }
	        if(is_array($key_to_be_deleted)) {
	            foreach($key_to_be_deleted as $del) {
	                array_delete($ary,$del);
	            }
	        }
	    } 
	}   
    
	if ( ! function_exists('object2array'))
	{
	    /**
	     * This function will convert an stdClass data object into an array.
	     * This works recursively by default.
	     * 
	     * @author http://www.jonasjohn.de/snippets/php/array2object.htm
	     * @since 2006-04-17
	     * @version 2007-12-04
	     * 
	     * @param stdclass $object The object to be converted to an array
	     * @return array The array that mirrors the data in the original object
	     */
	    function object2array($object, $recursive = true)
	    {
	    	if($recursive):
				if (is_object($object) || is_array($object)) {
					foreach ($object as $key => $value) {
						$array[$key] = object2array($value);
					}
				}
				else
					$array = $object;
				return $array;
			else:
				if (is_object($object)) {
	        		foreach ($object as $key => $value) {
	            		$array[$key] = $value;
	        		}
	    		}
	    		else {
	        		$array = $object;
	    		}
	    		return $array;
			endif;
		}
	}
	
	if ( ! function_exists('array2object'))
	{
		/**
		 * This function will convert an array of data into the stdclass equivalent.
		 * This works recursively by default.
		 * 
		 * @author http://www.jonasjohn.de/snippets/php/array2object.htm
	     * @since 2006-04-17
	     * @version 2007-11-22
		 * 
		 * @param array $array Array containing data to be converted
		 * @return stdclass The stdClass that mirrors the data in the original array
		 */
		function array2object($array, $recursive = true)
		{
			if($recursive):
				//create empty class
				$objResult=new stdClass();
			
				foreach ($array as $key => $value){
					//recursive call for multidimensional arrays
					if(is_array($value))
						$value=array2object($value);
					$objResult->{$key}=$value;
				}
				return $objResult;
			else:
				if (is_array($array)) {
	       			$obj = new StdClass();
	 
	        		foreach ($array as $key => $val){
	            		$obj->$key = $val;
	       			}
	    		}
	    		else { $obj = $array; }
	 
	    		return $obj;
			endif;
		}
	}
	
	if ( ! function_exists('Array2GetString'))
	{
		/**
		 * This function will convert an array holding variables from the $_GET array
		 * into a properly formatted string that can used as get parameters.  This is helpful
		 * for Location redirects that have GET parameters specified.  Optionally, you
		 * can specify certain parameters in the GET array to be excluded from the
		 * generated string.
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since Unknown
		 * @version 2009-04-28
		 * 
		 * @param $Array The array of GET variables indexed by the variable name that will appear in the string
		 * @param $Exceptions Any indicies of the $Array that should be excluded from the formed string
		 * @param $LeaveTrailingAmp True if the string should end with an ampersand, false otherwise
		 * @return string The converted GET string
		 */
		function Array2GetString($Array, $Exceptions, $LeaveTrailingAmp = true)
		{
			$GetString = "?";
			foreach($Array as $i => $v)
			{
				if(!in_array($i,$Exceptions))
					$GetString .= "$i=".urlencode($v)."&";
			}
			if(!$LeaveTrailingAmp)
				if(substr($GetString, sizeof($GetString)-1, 1) == '&')
					$GetString = substr($GetString, 0, sizeof($GetString)-1);
			return $GetString;
		}
	}
	
?>