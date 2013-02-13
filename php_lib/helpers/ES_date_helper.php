<?php
	/**
	 * This helper extends the built-in CI date helper and contains PHP functions
	 * designed to perform various date and time manipulations, often dealing with
	 * converting to and from date formats that exist in MySQL.
	 * 
	 * @package CI_GeneralESLibs
	 * @subpackage Helpers
	 * @category Helpers
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @since Unknown
	 * @version 2008-10-23
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 */

	if ( ! function_exists('Military2StandardTime'))
	{
		/**
		 * This function converts military time into its equivalent standard time.
		 *
		 * @since Unknown
		 * @version 2008-10-23
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * 
		 * @param string $time The string containing military time in the format hh:mm[:ss]
		 * @return string The converted standard time in the format hh:mm[:ss] AM/PM
		 */
		function Military2StandardTime($time)
		{
			$timeArray = explode(':',$time,2);
			if($timeArray[0] == 0)
			{
				$hours = 12;
				$ampm = "AM";
			}
			else if($timeArray[0] == 12)
			{
				$hours = 12;
				$ampm = "PM";
			}
			else if($timeArray[0] < 12)
			{
				$hours = (int) $timeArray[0];
				$ampm = "AM";
			}
			else
			{
				$hours = (int) $timeArray[0] - 12;
				$ampm = "PM";
			}
			require_once('dataVerification.php');
			if(!isShortTime($hours.':'.$timeArray[1]) && !isTime($hours.':'.$timeArray[1]))
				return "Invalid Time";
			else
				return ($hours.':'.$timeArray[1].' '.$ampm);
		}
	}
	
	if ( ! function_exists('Standard2MilitaryTime'))
	{
		/**
		 * This function converts standard time into its equivalent military time.
		 *
		 * @since Unknown
		 * @version 2008-10-23
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * 
		 * @param string $time The standard time formatted as hh:mm[:ss] AM/PM
		 * @return string The converted military time in the format hh:mm[:ss]
		 */
		function Standard2MilitaryTime($time)
		{
			$timePartArray = explode(' ',$time);
			$timeSuffix = $timePartArray[1];
			$timeArray = explode(':',$timePartArray[0],2);
			if(!isset($timeArray[1])) $timeArray[1] = "00";
			require_once('dataVerification.php');
			if(!isShortTime($timeArray[0].":".$timeArray[1]) && !isTime($timeArray[0].":".$timeArray[1]))
				return "Invalid Time";
			if($timeSuffix == "PM")
			{
				$hours = ((int) $timeArray[0]) + 12;
				if($hours == 24) $hours = 12;
				return ($hours.':'.$timeArray[1]);
			}
			else if($timeSuffix == "AM")
			{
				$hours = (int) $timeArray[0];
				if($hours == 12) $hours = 0;
				return ($hours.':'.$timeArray[1]);
			}
			else
				return "Invalid Time";		
		}
	}
	
	if ( ! function_exists('Database2LongDate'))
	{
		/**
		 * This function converts a standard database date format (yyyy-mm-dd) into a long date format of Month dd, yyyy
		 * Note: empty dates will returned as an empty string
		 *
		 * @since Unknown
		 * @version 2008-06-04
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * 
		 * @param string $date The date in a database format (yyyy-mm-dd)
		 * @return string The date in long display format (Month dd, yyyy)
		 */
		function Database2LongDate($date)
		{
			if($date == "") return "";
			$dateArray = explode('-',$date);
			switch($dateArray[1])
			{
				case 1: $month = "January"; break;
				case 2: $month = "February"; break;
				case 3: $month = "March"; break;
				case 4: $month = "April"; break;
				case 5: $month = "May"; break;
				case 6: $month = "June"; break;
				case 7: $month = "July"; break;
				case 8: $month = "August"; break;
				case 9: $month = "September"; break;
				case 10: $month = "October"; break;
				case 11: $month = "November"; break;
				case 12: $month = "December"; break;
			}
			if($dateArray[2]{0} == 0) $day = $dateArray[2]{1};
			else $day = $dateArray[2];
			return ($month.' '.$day.', '.$dateArray[0]);
		}
	}
	
	if ( ! function_exists('Month2LongMonth'))
	{
		/**
		 * This function converts a number from 1-12 into its corresponding month.
		 *
		 * @since Unknown
		 * @version 2008-06-04
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * 
		 * @param string $month The number representing the month in the format mm
		 * @return string The month name
		 */
		function Month2LongMonth($month)
		{
			switch($month)
			{
				case 1: $longMonth = "January"; break;
				case 2: $longMonth = "February"; break;
				case 3: $longMonth = "March"; break;
				case 4: $longMonth = "April"; break;
				case 5: $longMonth = "May"; break;
				case 6: $longMonth = "June"; break;
				case 7: $longMonth = "July"; break;
				case 8: $longMonth = "August"; break;
				case 9: $longMonth = "September"; break;
				case 10: $longMonth = "October"; break;
				case 11: $longMonth = "November"; break;
				case 12: $longMonth = "December"; break;
			}
			return $longMonth;
		}
	}
	
	if ( ! function_exists('LongMonth2Month'))
	{
		/**
		 * This function converts a month name into its corresponding number 1-12.
		 *
		 * @since Unknown
		 * @version 2008-06-04
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * 
		 * @param string $month The month name, capitalized (eg: January)
		 * @return int The number representing the month
		 */
		function LongMonth2Month($longMonth)
		{
			switch($longMonth)
			{
				case "January": $month = 1; break;
				case "February": $month = 2; break;
				case "March": $month = 3; break;
				case "April": $month = 4; break;
				case "May": $month = 5; break;
				case "June": $month = 6; break;
				case "July": $month = 7; break;
				case "August": $month = 8; break;
				case "September": $month = 9; break;
				case "October": $month = 10; break;
				case "November": $month = 11; break;
				case "December": $month = 12; break;
			}
			return $month;
		}
	}
	
	if ( ! function_exists('Display2DatabaseDate'))
	{
		/**
		 * This function converts a display date (mm/dd/yyyy) into a database date format (yyyy-mm-dd)
		 * Note: empty dates will returned as an empty string
		 *
		 * @since Unknown
		 * @version 2008-06-04
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * 
		 * @param string $displayDate The string containing a display date in the format mm/dd/yyyy
		 * @return string The date in a database format (yyyy-mm-dd)
		 */
		function Display2DatabaseDate($displayDate)
		{
			if($displayDate == "") return "";
			$dateArray = explode('/',$displayDate);
			$dateArray[0] = (int)$dateArray[0];
			$dateArray[1] = (int)$dateArray[1];
			if($dateArray[0] < 10) $dateArray[0] = "0".$dateArray[0];
			if($dateArray[1] < 10) $dateArray[1] = "0".$dateArray[1];
			return $dateArray[2].'-'.$dateArray[0].'-'.$dateArray[1];
		}
	}
	
	
	if ( ! function_exists('Database2DisplayDate'))
	{
		/**
		 * This function converts a database date (yyyy-mm-dd) into a display date format (mm/dd/yyyy)
		 * Note: empty dates will returned as an empty string
		 *
		 * @since Unknown
		 * @version 2008-06-04
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * 
		 * @param string $databaseDate The string containing a database date in the format yyyy-mm-dd
		 * @return string The date in a display format (mm/dd/yyyy)
		 */
		function Database2DisplayDate($databaseDate)
		{
			if($databaseDate == "") return "";
			$datePart = explode(' ',$databaseDate);
			$dateArray = explode('-',$datePart[0]);
			return $dateArray[1].'/'.$dateArray[2].'/'.$dateArray[0];
		}
	}
	
	if ( ! function_exists('DatabaseDT2DisplayDT'))
	{
		/**
		 * This function converts a database date/time (yyyy-mm-dd hh:mm[:ss]) into a display date/time format (mm/dd/yyyy hh:mm[:ss] AM/PM)
		 *
		 * @since Unknown
		 * @version 2008-06-04
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * 
		 * @param string $databaseDT The database date/time in the format yyyy-mm-dd hh:mm[:ss]
		 * @return string The date/time in a display format (mm/dd/yyyy hh:mm[:ss] AM/PM)
		 */
		function DatabaseDT2DisplayDT($databaseDT)
		{
			$databaseDTArray = explode(' ',$databaseDT);
			$displayDate = Database2DisplayDate($databaseDTArray[0]);
			$displayTime = Military2StandardTime($databaseDTArray[1]);
			return $displayDate." ".$displayTime;
		}
	}
	
	if ( ! function_exists('DatabaseDT2DisplayDTLong'))
	{
		/**
		 * This function converts a database date/time (yyyy-mm-dd hh:mm[:ss]) into a display date/time format with a long month (Month dd, yyyy hh:mm[:ss] AM/PM)
		 *
		 * @since Unknown
		 * @version 2008-06-04
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * 
		 * @param string $databaseDT The database date/time in the format yyyy-mm-dd hh:mm[:ss]
		 * @return string The date/time in a display format with long month (Month dd, yyyy hh:mm[:ss] AM/PM)
		 */
		function DatabaseDT2DisplayDTLong($databaseDT)
		{
			$DisplayDT = DatabaseDT2DisplayDT($databaseDT);
			$Month = substr($DisplayDT,0,2);
			$Day = substr($DisplayDT,3,2);
			$Year = substr($DisplayDT,6,4);
			$Time = substr($DisplayDT,11);
			$MonthLong = Month2LongMonth((int)$Month);
			return $MonthLong." ".(int)$Day.", ".$Year." at ".$Time;
		}
	}	
	
	if ( ! function_exists('Database2DisplayInterval'))
	{
		/**
		 * This function converts a database date/time (yyyy-mm-dd hh:mm[:ss]) into an interval display format (eg: XX Hours YY Minutes ZZ Seconds)
		 *
		 * @since Unknown
		 * @version 2008-06-04
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * 
		 * @param string $databaseInterval The database date/time in the format yyyy-mm-dd hh:mm[:ss] (hours can be > 24)
		 * @param string $returnVal The different intervals desired; defaults to "string", other valid options are "d" for days, "h" for hours, "m" for minutes, and "s" for seconds 
		 * @return string The date/time in an interval display format
		 */
		function Database2DisplayInterval($databaseInterval, $returnVal = "string")
		{
			// returnVal values
			// 'd' = days
			// 'h' = hours
			// 'm' = minutes
			// 's' = seconds
			// 'string' = full interval string
			// lifted from Advanced Fire sync frame (still hard coded there)
			if($databaseInterval == "")
				return "";
			if($returnVal != 'd' && $returnVal != 'h' && $returnVal != 'm' && $returnVal != 's')
				$returnVal = "string";
			$interval = $databaseInterval; // as to not change source var names
			$intervalArray = explode(':',$interval);
			$dayDiff = 0;
			$hourDiff = (int)$intervalArray[0];
			while($hourDiff >= 24)
			{
				$hourDiff -= 24;
				$dayDiff++;
			}
			$totalDays = $dayDiff;
			if($returnVal == 'd')
				return $totalDays;
			$totalHours = $hourDiff + ($totalDays*24);
			if($returnVal == 'h')
				return $totalHours;
			$minuteDiff = (int)$intervalArray[1];
			$totalMins = $minuteDiff + ($totalHours*60);
			if($returnVal == 'm')
				return $totalMins;
			$secondDiff = (int)$intervalArray[2];
			$totalSecs = $secondDiff + ($totalMins*60);
			if($returnVal == 's')
				return $totalSecs;
			$DiffStringArray = array();
			if($dayDiff > 0) $DiffStringArray[] = $dayDiff . (" Day".(($dayDiff==1)?"":"s"));
			if($hourDiff > 0 || $dayDiff > 0) $DiffStringArray[] = $hourDiff . (" Hour".(($hourDiff==1)?"":"s"));
			if($minuteDiff > 0 || $hourDiff > 0 || $dayDiff > 0) $DiffStringArray[] = $minuteDiff . (" Minute".(($minuteDiff==1)?"":"s"));
			$DiffStringArray[] = $secondDiff . (" Second".(($secondDiff==1)?"":"s"));
			$DiffString = implode(" ",$DiffStringArray);
			return $DiffString;		
		}
	}
	
	
	if ( ! function_exists('DateInRange'))
	{
		/**
		 * This function checks to see if a date is between two other dates
		 * Note: date/times are not supported with this function
		 * 
		 * @since Unknown
		 * @version 2008-06-04
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * 
		 * @param string $startDate The start of the interval in the format yyyy-mm-dd
		 * @param string $endDate The end of the interval in the format yyyy-mm-dd
		 * @param string $verifyDate The date being verified as in or not in the interval (in the format yyyy-mm-dd)
		 * @return bool True if the $verifyDate falls within the range of the other two dates; False otherwise
		 */
		function DateInRange($startDate, $endDate, $verifyDate)
		{
			$startDateArray = explode('-', $startDate);
			$endDateArray = explode('-' , $endDate);
			$verifyDateArray = explode('-' , $verifyDate);
			$startDateInt = ($startDateArray[0]*100000)+($startDateArray[1]*100)+($startDateArray[2]);
			$endDateInt = ($endDateArray[0]*100000)+($endDateArray[1]*100)+($endDateArray[2]);
			$verifyDateInt = ($verifyDateArray[0]*100000)+($verifyDateArray[1]*100)+($verifyDateArray[2]);
			
			if($verifyDateInt >= $startDateInt && $verifyDateInt <= $endDateInt)
				return true;
			else
				return false;
		}
	}
	
	if ( ! function_exists('MySQLTimeDiff'))
	{
		/**
		 * This function returns the difference between two date/times in specified units
		 *
		 * @since Unknown
		 * @version 2008-06-04
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * 
		 * @param string $date1 The start of the interval in the format yyyy-mm-dd [hh:mm[:ss]]
		 * @param string $date2 The end of the interval in the format yyyy-mm-dd [hh:mm[:ss]]
		 * @param string $format Represents what units you want: 's' for seconds, 'm' for minutes, 'h' for hours, 'd' for days; default is 's'
		 * @return string The difference between the two date/times in the specified units
		 */
		function MySQLTimeDiff($date1, $date2, $format = 's')
		{
			return TimeDiff(MySQL2PHPDTStamp($date1), MySQL2PHPDTStamp($date2), $format);		
		}
	}
	
	if ( ! function_exists('MySQL2PHPDTStamp'))
	{
		/**
		 * This function converts a MySQL database date[/time] (yyyy-mm-dd [hh:mm[:ss]]) into a PHP date[/timestamp]
		 *
		 * @since Unknown
		 * @version 2008-06-04
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * 
		 * @param string $date The MySQL database date[/time] in the format yyyy-mm-dd [hh:mm[:ss]]
		 * @param string $dst A flag to determine whether you want daylight savings time included: -1 to have dst calculated automatically, 0 to have dst ignored, 1 to use dst; default is -1
		 * @return string The PHP date]/timestamp]
		 */
		function MySQL2PHPDTStamp($date, $dst = -1)
		{
			// dst = -1 to have dst determined automatically
			//     = 0 to not use daylight savings
			//     = 1 to use daylight savings
			if(strlen($date) == 10)
				$date .= " 00:00:00";
			$DateTimeParts = explode(" ",$date);
			$DateParts = explode("-",$DateTimeParts[0]);
			$TimeParts = explode(":",$DateTimeParts[1]);
			return mktime($TimeParts[0],$TimeParts[1],$TimeParts[2],$DateParts[1],$DateParts[2],$DateParts[0],$dst);
		}
	}
	
	if ( ! function_exists('TimeDiff'))
	{
		/**
		 * This function returns the difference between two PHP date/times in the specified units
		 *
		 * @since Unknown
		 * @version 2008-06-04
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * 
		 * @param string $date1 The start of the interval as a PHP date/time
		 * @param string $date2 The end of the interval as a PHP date/time
		 * @param string $format Represents what units you want: 's' for seconds, 'm' for minutes, 'h' for hours, 'd' for days; default is 's'
		 * @return string The difference between the two date/times in the specified units
		 */
		function TimeDiff($date1, $date2, $format = 's')
		{
			// accepted return formats
			// s = seconds (default)
			// m = minutes (mm:ss)mktime
			// h = hours (hh:mm:ss)
			// d = days (dd[ hh:mm:ss])
			$timedifference=$date2-$date1;
			//$corr=date("I",$date2)-date("I",$date1);
			//$timedifference+=$corr;
			$timedifference=abs($timedifference);
			$timeparts = Seconds2TimeParts($timedifference);
			if($format == 's')
				return $timedifference;
			else if($format == 'm')
				
				return floor($timedifference/60).":".str_pad($timeparts["seconds"],2,'0',STR_PAD_LEFT);
			else if($format == 'h')
				return floor($timedifference/3600).':'.str_pad($timeparts["minutes"],2,'0',STR_PAD_LEFT).':'.str_pad($timeparts["seconds"],2,'0',STR_PAD_LEFT);
			else if($format == 'd')
			{
				// only report hh:mm:ss when there are some to report (in case only dates are passed in)
				if($timedifference%86400 == 0)
					return $timedifference/86400;
				else
				{
					return floor($timedifference/86400).' '.str_pad($timeparts["hours"],2,'0',STR_PAD_LEFT).':'.str_pad($timeparts["minutes"],2,'0',STR_PAD_LEFT).':'.str_pad($timeparts["seconds"],2,'0',STR_PAD_LEFT);
				}
			}		
		}
	}
	
	if ( ! function_exists('Seconds2TimeParts'))
	{
		/**
		 * This function converts a number of seconds into an array containing the parts of the time element - the array contains indices "years", "days", "hours", "minutes", and "seconds"
		 * NOTE: if the time is not
		 * 
		 * @since Unknown
		 * @version 2008-06-04
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * 
		 * @param int $time The number of seconds
		 * @return array|bool The array containing all time components or False if something went wrong
		 */
		function Seconds2TimeParts($time)
		{
			if(is_numeric($time))
			{
				$value = array(
					"years" => 0, "days" => 0, "hours" => 0,
					"minutes" => 0, "seconds" => 0,
				);
				if($time >= 31556926)
				{
					$value["years"] = floor($time/31556926);
					$time = ($time%31556926);
				}
				if($time >= 86400)
				{
					$value["days"] = floor($time/86400);
					$time = ($time%86400);
				}
				if($time >= 3600)
				{
					$value["hours"] = floor($time/3600);
					$time = ($time%3600);
				}
				if($time >= 60)
				{
					$value["minutes"] = floor($time/60);
					$time = ($time%60);
				}
				$value["seconds"] = floor($time);
				return (array) $value;
			}
			else
			{
				return (bool) FALSE;
			}
		}
	}
?>