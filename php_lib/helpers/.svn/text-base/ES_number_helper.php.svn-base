<?php
	/**
	 * This helper extends the built-in CI number helper and contains PHP functions
	 * designed to perform various numeric manipulations.
	 * 
	 * @package CI_GeneralESLibs
	 * @subpackage Helpers
	 * @category Helpers
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @since Unknown
	 * @version 2008-10-24
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 */

	if ( ! function_exists('Number2Price'))
	{
		/**
		 * This function converts a number to a price format - expects a numeric value
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since Unknown
		 * @version 2008-10-24
		 * 
		 * @param string $number The string containing the amount to be converted to a price
		 * @param string $currency This is currently not being used
		 * @param string $symbol This is the symbol desired at the front of the price - default to "$"
		 * @return String of price formatted correctly
		 */
		function Number2Price($number, $currency = "US Dollars", $symbol = '$')
		{
			if($number == "") return $symbol."0.00";
			$neg = "";
			if(substr($number, 0, 1) == "-")
			{
				$number = substr($number, 1);
				$neg = "-";
			}
			$numberArray = explode('.', $number);
			$dollars = $numberArray[0];
			if(!isset($numberArray[1])) $cents = "00";
			else if($numberArray[1] == "") $cents = "00";		
			else
			{
				$cents = $numberArray[1];
				if(strlen($cents) == 1) $cents .= "0";
			}
			
			// commas
			$dollarsLen = strlen($dollars);
			$dollarsChars = array_slice(split('-l-',chunk_split($dollars,1,'-l-')),0,-1); // PHP5 would be str_split($dollars);
			$dollarsChars = array_reverse($dollarsChars);
			$dollarsArrayNew = array();
			$count = 0;
			foreach($dollarsChars as $char)
			{
				$count++;
				$dollarsArrayNew[] = $char;
				if($count == 3) 
				{
					$dollarsArrayNew[] = ",";
					$count = 0;
				}
			}
			$dollarsArrayNew = array_reverse($dollarsArrayNew);
			$dollars = implode("",$dollarsArrayNew);
			if($dollars{0} == ",") $dollars = substr($dollars,1);
			return $neg.$symbol.$dollars.".".$cents;
		}
	}
	
	if ( ! function_exists('Price2Number'))
	{
		/**
		 * This function converts a string in price format to a number - expects a valid price
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since Unknown
		 * @version 2008-04-24
		 * 
		 * @param string $price The string containing the price to be converted
		 * @return String of the number without price formatting
		 */
		function Price2Number($price)
		{
			if($price == "") return "0";
			$PriceChars = array_slice(split('-l-',chunk_split($price,1,'-l-')),0,-1); // PHP5 would be str_split($price);
			$CleanPrice = "";
			foreach($PriceChars as $char)
			{
				if($char == "0" || $char == "1" || $char == "2" || $char == "3" || $char == "4" || $char == "5" ||
				   $char == "6" || $char == "7" || $char == "8" || $char == "9" || $char == "0" || $char == ".")
				{
					$CleanPrice .= $char;	
				}
			}
			$PricePartArray = explode(".",$CleanPrice);
			$Dollars = (!empty($PricePartArray[0])) ? $PricePartArray[0] : "0";
			$Cents = (isset($PricePartArray[1])) ? $PricePartArray[1] : "00";
			$Cents = substr($Cents, 0, 2);
			return (($Cents == "00") ? $Dollars : ($Dollars.".".$Cents));
		}
	}
	
	if ( ! function_exists('Number2SSN'))
	{
		/**
		 * This function converts a number into a formatted Social Security Number
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since Unknown
		 * @version 2008-04-24
		 * 
		 * @param string $number The string containing the number to be converted accepts either a 9 digit number (###-##-####) or a 4 digit number (XXX-XX-####)
		 * @return String of SSN formatted correctly - returns empty if input not correct
		 */
		function Number2SSN($number)
		{
			if(ereg('^[0-9]{9}$',$number))
				return(substr($number,0,3).'-'.substr($number,3,2).'-'.substr($number,5));
			else if(ereg('^[0-9]{4}$',$number))
				return("xxx-xx-".$number);
			return "";
		}
	}
	
	if ( ! function_exists('DatabaseFileSize2HumanFileSize'))
	{
		/**
		 * This function converts a number of bytes into a readable file size (eg: 10240 => 10 Kb)
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since Unknown
		 * @version 2008-04-24
		 * 
		 * @param string $filesize The string containing the number of bytes in a file 
		 * @return String of file size described in Kb or Mb
		 */
		function DatabaseFileSize2HumanFileSize($filesize)
		{
			$filesize = (int)$filesize;
			$label = "b";
			$remainder = "";
			if($filesize >= 1024)
			{
				$remainder = (int)substr(round($filesize%1024,0),0,2);
				$filesize = floor($filesize/1024);
				$label = "Kb";
			}
			if($filesize >= 1024)
			{
				$filesize = (int)($filesize.".".$remainder);
				$remainder = (int)substr(round($filesize%1024,0),0,2);
				$filesize = floor($filesize/1024);
				$label = "Mb";
			}
			return $filesize.($remainder==""?'':'.').$remainder.$label;
		}
	}

?>