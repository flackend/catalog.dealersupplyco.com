<?php
	/**
	 * This helper is intended to extend the built-in validation methods defined by
	 * form_validation.  Adapted from the old dataVerification library, these methods
	 * will perform basic data constraint checks that can be used repeatedly throughout
	 * many projects.  The majority of these checks are a way of creating standard
	 * regular expression match checks.
	 * 
	 * @package CI_GeneralESLibs
	 * @subpackage Helpers
	 * @category Helpers
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @since 2008-04-24
	 * @version 2008-10-15
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 */
	
	if ( ! function_exists('isInt'))
	{
		/**
		 * This function verifies that the value passed is a valid integer
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since Unknown
		 * @version 2009-05-28
		 * 
		 * @param mixed $str The value supplied that will be checked against the integer regular expression
		 * @return bool True if the supplied value is an integer, False otherwise
		 */
		function isInt($str)
		{
			return (!ereg('^[-]?[0-9]+$',$str)) ? false : true;
		}
	}
	
	if ( ! function_exists('isReal'))
	{
		/**
		 * This function verifies that the value passed is a valid real number
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since Unknown
		 * @version 2009-05-28
		 * 
		 * @param mixed $str The value supplied that will be checked against the real number regular expression
		 * @return bool True if the supplied value is a real number, False otherwise
		 */
		function isReal($str)
		{
			return (!ereg('^[-]?[0-9]+([.][0-9]+)?$',$str)) ? false : true;
		}
	}
	
	if ( ! function_exists('isPriceNum'))
	{
		/**
		 * This function verifies that the value passed is a valid number that would
		 * appear as a price.  This price can be a whole dollar amount or contain
		 * only and exactly two decimal places for fractions of a dollar.
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since Unknown
		 * @version 2009-05-28
		 * 
		 * @param mixed $str The value supplied that will be checked against the regular expression to determine if it is a valid price number
		 * 	 
		 * @return bool True if the value supplied is a valid price, False otherwise
		 */
		function isPriceNum($str)
		{
			return (!ereg('^[0-9]+([.][0-9][0-9])?$',$str)) ? false : true;
		}
	}
	
	if ( ! function_exists('isLeapYear'))
	{
		/**
		 * This function will verify that the value supplied is first a valid integer,
		 * then a valid year, and finally a leap year.  Note, however, that unless these
		 * first two checks are done before calling this function, it is impossible to
		 * conclude from a False result here that the value passed is NOT a leap year, since
		 * the value passed could have just failed one of these first two checks.
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since Unknown
		 * @version 2009-04-28
		 * 
		 * @param mixed $str The value supplied representing the year to be validated as a leap year
		 * 	 
		 * @return bool True if the value supplied is a valid leap year, False if it could not be proven to be a leap year
		 */
		function isLeapYear($year)
		{
			if(!isInt($year)) return false;
			if(strlen($year) != 4) return false;
			if($year%400==0 || ($year%4==0 && $year%100!=0)) return true;
			else return false;
		}
	}
	
	if ( ! function_exists('isDisplayDate'))
	{
		/**
		 * This function will verify that the value supplied is a valid
		 * display date in the format (mm/dd/yyyy)
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since Unknown
		 * @version 2008-10-15
		 * 
		 * @param mixed $str The value supplied that will be checked against the display date regular expression and constraint logic
		 * @return bool True if the value supplied is a valid display date, False otherwise
		 */
		function isDisplayDate($str)
		{
			if(ereg('^[0-1]?[0-9]\/[0-3]?[0-9]\/[0-9]{4}$',$str))
			{
				$DisplayDateArray = explode('/',$str);
				if((int)$DisplayDateArray[0] > 12 || (int)$DisplayDateArray[0] < 1)
					return false;
				switch((int)$DisplayDateArray[0])
				{
					case 4:
					case 6:
					case 9:
					case 11:
						$maxMonthDay = 30;
						break;
					case 2:
						if(isLeapYear($DisplayDateArray[2]))
							$maxMonthDay = 29;
						else
							$maxMonthDay = 28;
						break;
					default:
						$maxMonthDay = 31;
				}
				if((int)$DisplayDateArray[1] > $maxMonthDay || (int)$DisplayDateArray[1] < 1)
					return false;
				return true;
			}
			else return false;
		}
	}
	
	if ( ! function_exists('isDisplayTime'))
	{
		/**
		 * This function will verify that the value supplied is a valid
		 * display time in the format (hh:mm AM/PM)
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since Unknown
		 * @version 2009-05-28
		 * 
		 * @param mixed $str The value supplied that will be checked against the display time regular expression
		 * @return bool True if the value supplied is a valid display time, False otherwise
		 */
		function isDisplayTime($str)
		{
			return (!eregi('^[0-2]?[0-9]:[0-5]?[0-9]\ [AP]M$',$str)) ? false : true;
		}
	}
	
	if ( ! function_exists('isDate'))
	{
		/**
		 * This function will verify that the value supplied is a valid
		 * database date in the format (yyyy-mm-dd)
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since Unknown
		 * @version 2009-05-28
		 * 
		 * @param mixed $str The value supplied that will be checked against the database date regular expression
		 * @return bool True if the value supplied is a valid database date, False otherwise
		 */
		function isDate($str)
		{
			return (!ereg('^[0-9]{4}-[0-1]?[0-9]-[0-3]?[0-9]$',$str)) ? false : true;
		}
	}
	
	if ( ! function_exists('isTime'))
	{
		/**
		 * This function will verify that the value supplied is a valid
		 * database time in the format (hh:mm:ss)
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since Unknown
		 * @version 2009-05-28
		 * 
		 * @param mixed $str The value supplied that will be checked against the database time regular expression
		 * @return bool True if the value supplied is a valid database time, False otherwise
		 */
		function isTime($str)
		{
			return (!ereg('^[0-2]?[0-9]:[0-5]?[0-9]:[0-5]?[0-9]$',$str)) ? false : true;
		}
	}
	
	if ( ! function_exists('isShortTime'))
	{
		/**
		 * This function will verify that the value supplied is a valid
		 * database short time in the format (hh:mm)
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since Unknown
		 * @version 2009-05-28
		 * 
		 * @param mixed $str The value supplied that will be checked against the database short time regular expression
		 * @return bool True if the value supplied is a valid database short time, False otherwise
		 */
		function isShortTime($str)
		{
			return (!ereg('^[0-2]?[0-9]:[0-5]?[0-9]$',$str)) ? false : true;
		}
	}
	
	if ( ! function_exists('isDateTime'))
	{
		/**
		 * This function will verify that the value supplied is a valid
		 * database date time in the format (yyyy-mm-dd hh:mm:ss)
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since Unknown
		 * @version 2008-10-15
		 * 
		 * @param mixed $str The value supplied that will be checked against the database date time regular expression
		 * @return bool True if the value supplied is a valid database date time, False otherwise
		 */
		function isDateTime($str)
		{
			$strs = explode(" ",$str);
			return (isDate($strs[0]) && isTime($strs[1]));
		}
	}
	
	if ( ! function_exists('isPhoneNumber'))
	{
		/**
		 * This function will verify that the value supplied is a valid
		 * phone number in the format (xxx-xxx-xxxx)
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since Unknown
		 * @version 2009-05-28
		 * 
		 * @param mixed $str The value supplied that will be checked against the phone number regular expression
		 * @return bool True if the value supplied is a valid phone number, False otherwise
		 */
		function isPhoneNumber($str)
		{
			return (!ereg('^[0-9]{3}-[0-9]{3}-[0-9]{4}$',$str)) ? false : true;
		}
	}
	
	if ( ! function_exists('isEmailAddress'))
	{
		/**
		 * This function will verify that the value supplied is a valid
		 * email address
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since Unknown
		 * @version 2009-05-28
		 * 
		 * @param mixed $str The value supplied that will be checked against the email address regular expression
		 * @return bool True if the value supplied is a valid email address, False otherwise
		 */
		function isEmailAddress($str)
		{
			return (!eregi('^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z.]{2,5}$', $str)) ? false : true;
		}
	}
	
	if ( ! function_exists('isRawSSN'))
	{
		/**
		 * This function will verify that the value supplied is a valid
		 * unformatted social security number (xxxxxxxxx)
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since Unknown
		 * @version 2009-05-28
		 * 
		 * @param mixed $str The value supplied that will be checked against the unformatted social security number regular expression
		 * @return bool True if the value supplied is a valid unformatted social security number, False otherwise
		 */
		function isRawSSN($str)
		{
			return (!ereg('^[0-9]{9}$',$str)) ? false : true;
		}
	}
	
	if ( ! function_exists('isSSN'))
	{
		/**
		 * This function will verify that the value supplied is a valid
		 * social security number (xxx-xx-xxxx)
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since Unknown
		 * @version 2009-05-28
		 * 
		 * @param mixed $str The value supplied that will be checked against the social security number regular expression
		 * @return bool True if the value supplied is a valid social security number, False otherwise
		 */
		function isSSN($str)
		{
			return (!ereg('^[0-9]{3}-[0-9]{2}-[0-9]{4}$',$str)) ? false : true;
		}
	}
	
	if ( ! function_exists('isZipShort'))
	{
		/**
		 * This function will verify that the value supplied is a valid
		 * short zip code (xxxxx)
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since Unknown
		 * @version 2009-05-28
		 * 
		 * @param mixed $str The value supplied that will be checked against the short zip code regular expression
		 * @return bool True if the value supplied is a valid short zip code, False otherwise
		 */
		function isZipShort($str)
		{
			return (!ereg('^[0-9]{5}',$str)) ? false : true;
		}
	}

	if ( ! function_exists('isZipShort'))
	{
		/**
		 * This function will verify that the value supplied is a valid
		 * long zip code (xxxxx-xxxx)
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since Unknown
		 * @version 2009-05-28
		 * 
		 * @param mixed $str The value supplied that will be checked against the long zip code regular expression
		 * @return bool True if the value supplied is a valid long zip code, False otherwise
		 */
		function isZipLong($str)
		{
			return (!ereg('^[0-9]{5}-[0-9]{4}',$str)) ? false : true;
		}
	}
	
?>