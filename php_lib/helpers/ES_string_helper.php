<?php
	/**
	 * This helper extends the built-in CI string helper and contains PHP functions
	 * designed to perform various string manipulations.
	 * 
	 * @package CI_GeneralESLibs
	 * @subpackage Helpers
	 * @category Helpers
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @since Unknown
	 * @version 2008-05-22
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 */

	if ( ! function_exists('AddressParts2FullAddress'))
	{
		/**
		 * This function takes a passed in array of address pieces, and formats it as a typical address would appear
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since Unknown
		 * @version 2008-05-22
		 * 
		 * @param array $AddressPartsArray The array containing the parts of the address - each is optional - Valid indices for this array include:  Address1, Address2, Address3, City, State, Zip, and Phone
		 * @param string $LineSeparator The string desired to be used for separation of the parts of the address - defaults to '<br/>'
		 * @param bool $LeaveSpaceForBlankLines This variable tells us whether or not we leave extra spacing for optional address lines at the end of the string  - defaults to false	
		 * @param int $DefaultTotalSpaces This variable tells us how many spaces an address will be - this only affects the extra spacing - it will NOT truncate existing lines  - defaults to 4	
		 * @return String of address formatted
		 */
		function AddressParts2FullAddress($AddressPartsArray, $LineSeparator = '<br />', $LeaveSpacingForBlankLines = false, $DefaultTotalSpaces = 4)
		{
			$Address1 = isset($AddressPartsArray["Address1"]) ? $AddressPartsArray["Address1"] : "";
			$Address2 = isset($AddressPartsArray["Address2"]) ? $AddressPartsArray["Address2"] : "";
			$Address3 = isset($AddressPartsArray["Address3"]) ? $AddressPartsArray["Address3"] : "";
			$City = isset($AddressPartsArray["City"]) ? $AddressPartsArray["City"] : "";
			$State = isset($AddressPartsArray["State"]) ? $AddressPartsArray["State"] : "";
			$Zip = isset($AddressPartsArray["Zip"]) ? $AddressPartsArray["Zip"] : "";
			$Phone = isset($AddressPartsArray["Phone"]) ? $AddressPartsArray["Phone"] : " ";
			
			$CityState = ($City != "" && $State != "") ? ($City.", ".$State) : (($City == "") ? $State : (($State == "") ? $City : ""));
			$CityStateZip = ($CityState !="" && $Zip != "") ? ($CityState." ".$Zip) : (($CityState == "") ? $Zip : (($Zip == "") ? $CityState : ""));
			$Address1And2 = ($Address1 != "" && $Address2 != "") ? ($Address1.$LineSeparator.$Address2) : (($Address1 == "") ? $Address2 : (($Address2 == "") ? $Address1 : ""));
			$Address123 = ($Address1And2 != "" && $Address3 != "") ? ($Address1And2.$LineSeparator.$Address3) : (($Address1And2 == "") ? $Address3 : (($Address3 == "") ? $Address1And2 : ""));
			$FullAddress = ($Address123 != "" && $CityStateZip != "") ? ($Address123.$LineSeparator.$CityStateZip) : (($Address1 == "") ? $Address2 : (($Address2 == "") ? $Address1 : ""));
			$FullAddressPhone = $FullAddress.$LineSeparator.$Phone;
			
			if($LeaveSpacingForBlankLines)
			{
				$LineCount=substr_count($FullAddressPhone, $LineSeparator);
				//$FullAddressPhone=$FullAddressPhone." ".$LineCount;
				while($LineCount < $DefaultTotalSpaces)
				{ 
					$FullAddressPhone = $FullAddressPhone.$LineSeparator;
					$LineCount++;
				}
			}
			//$FullAddressPhone=$FullAddressPhone." ".$LineCount;
			return $FullAddressPhone; //$FullAddressPhone;
		}
	}
	
	if ( ! function_exists('EscapeRegExp'))
	{
		/**
		 * This function escapes out any regular expression special characters
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since Unknown
		 * @version 2008-05-22
		 * 
		 * @param string $string This variable is the string prior to having regular expression special characters escaped -characters escaped:  [ \ ^ $ . | ? * + ( ) / $
		 * @return String after having regular expression special characters escaped
		 */
		function EscapeRegExp($string)
		{
			$charsToReplace = array('[', '\\', '^', '$', '.', '|', '?', '*', '+', '(', ')', '/', '$');
			foreach($charsToReplace as $char)
			{
				$string = str_replace($char, "\\".$char, $string);
			}
			return $string;
		}
	}
	
	if ( ! function_exists('EscapeParsedXML'))
	{
		/**
		 * This function converts XML special characters into their HTML equivalent
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since Unknown
		 * @version 2008-05-22
		 * 
		 * @param string $string This variable is the string prior to having XML special characters replaced -characters replaced:  & " ' < >
		 * @return String after having XML special characters converted to HTML equivalent
		 */
		function EscapeParsedXML($string)
		{
			$replaceArray = array();
			$startArray[0] = '&';
			$replaceArray[0] = '&amp;';
			$startArray[1] = '"';
			$replaceArray[1] = '&quot;';
			$startArray[2] = "'";
			$replaceArray[2] = '&apos;';
			$startArray[3] = '<';
			$replaceArray[3] = '&lt;';
			$startArray[4] = '>';
			$replaceArray[4] = '&gt;';
			
			foreach($replaceArray as $i => $v)
			{
				$string = str_replace($startArray[$i], $v, $string);
			}
	
			return $string;
		}
	}
	
?>