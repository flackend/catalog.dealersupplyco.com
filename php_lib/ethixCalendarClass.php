<?php
	/**
	 * This file contains a class that will create a simple calendar widget
	 * for selecting dates or filling in form fields or even just for reference in a project.
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @since 2009-01-15
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 * 
	 * @package ES_GeneralPHPLibraries
	 * @subpackage General
	 * @category General
	 */

	/**
	 * Adaptation of the AJAX calendar popup library to be used internally in other projects.
	 * 
	 * @since 2009-01-15
	 * @version 2009-01-15
	 * @author Nicholas J. Marshall <njmarshall@ethixsystems.com>
	 */
	class EthixCalendar
	{
		private $CurrentMonthLock = false;
		private $YearControl = true;
		private $DateFormat = '-';
		private $FocusYear = "";
		private $FocusMonth = "";
		private $SelectedDate = "";
		private $DateAction = "";
		private $TodayButtonAction = "";
		private $Today = "";
		private $ArrowImgDirectory = "/";
		
		/**
		 * Possible $settings_array values that will override the defaults (indicated by *)
		 *  - SelectedDate [yyyy-mm-dd] - The date that has been selected, but not necessarily in focus, defaults to today
		 *  - FocusYearMonth [yyyy-mm] - The year and month to bring the calendar into focus by, defaults to the year and month SelectedDate is in, and will be ignored if CurrentMonthLock is true 
		 *  - CurrentMonthLock [true, false*] - When true, disables the user from changing the current displayed month (which will be the current month we are in)
		 *  - YearControl [true*, false] - When true, there will be a seperate control for the year from the month
		 *  - DateFormat [-, /] - The format in which to render the date in the DateAction, if defined and applicable
		 *  - DateAction [javascript action] - The javascript action to perform upon selecting a date from the calendar, defaults to passing the selected date as the GET variable SelectedDate appended to the current page (use [DATE] to insert the current date in the format defined in DateFormat)
		 *  - ArrowImgDirectory [/path/to/arrow/images/] - This is the path to ecLeftArrow.gif, ecLeftArrowOver.gif, ecRightArrow.gif, and ecRightArrowOver.gif to be used in this calendar, will default to / (note that this path MUST have a trailing / to work)
		 * 
		 * @param $settings_array array Global settings for the appearance and functionality of the calendar
		 */
		function __construct($settings_array = array())
		{
			if(isset($settings_array['CurrentMonthLock'])) $this->CurrentMonthLock = $settings_array['CurrentMonthLock'];
			if(isset($settings_array['YearControl'])) $this->YearControl = $settings_array['YearControl'];
			if(isset($settings_array['DateFormat'])) $this->DateFormat = $settings_array['DateFormat'];
			if(isset($settings_array['ArrowImgDirectory'])) $this->ArrowImgDirectory = $settings_array['ArrowImgDirectory'];
			
			if(substr($this->ArrowImgDirectory,-1,1) != "/")
				$this->ArrowImgDirectory .= "/";
			
			if(isset($settings_array['DateAction'])):
				$this->DateAction = $settings_array['DateAction'];
			else:
				require_once('arrayFunctions.php');
				// for clicking a date
				$CurrentGETVars = $_GET;
				array_delete($CurrentGETVars, 'SelectedDate');
				if(sizeof($CurrentGETVars) > 0):
					$this->DateAction = "window.location='?";
					foreach($CurrentGETVars as $GETIndex => $GETValue):
						$this->DateAction .= $GETIndex.'='.$GETValue.'&';
					endforeach;
					$this->DateAction .= "SelectedDate=[DATE]'";
				else:
					$this->DateAction = "window.location='?SelectedDate=[DATE]'";
				endif;
				// for clicking the today button
				$CurrentGETVars = $_GET;
				array_delete($CurrentGETVars, array('SelectedDate','FocusYearMonth'));
				if(sizeof($CurrentGETVars) > 0):
					$this->TodayButtonAction = "window.location='?";
					foreach($CurrentGETVars as $GETIndex => $GETValue):
						$this->TodayButtonAction .= $GETIndex.'='.$GETValue.'&';
					endforeach;
					$this->TodayButtonAction .= "SelectedDate=[DATE]'";
				else:
					$this->TodayButtonAction = "window.location='?SelectedDate=[DATE]'";
				endif;
			endif;
			
			$Today = getdate();
			$this->Today = $Today["year"].'-'.(($Today["mon"] < 10)?"0".$Today["mon"]:$Today["mon"]).'-'.(($Today["mday"] < 10)?"0".$Today["mday"]:$Today["mday"]);
			
			require_once('dateTimeFunctions.php');
			
			if(!isset($settings_array['SelectedDate'])): // if no selected date, select today
				$this->SelectedDate = $this->Today;
			elseif(!isDate($settings_array['SelectedDate'])): // validates the data supplied
				$this->SelectedDate = $this->Today;
			else:
				$this->SelectedDate = $settings_array['SelectedDate'];
			endif;
			
			if(!isset($settings_array['FocusYearMonth']) || $this->CurrentMonthLock): // focus on the selected date	
				$this->FocusYear = substr($this->Today,0,4);
				$this->FocusMonth = substr($this->Today,5,2);
			elseif(!isDate($settings_array['FocusYearMonth'].'-01')): // validates the data supplied
				$this->FocusYear = substr($this->Today,0,4);
				$this->FocusMonth = substr($this->Today,5,2);
			else:
				$this->FocusYear = substr($settings_array['FocusYearMonth'],0,4);
				$this->FocusMonth = substr($settings_array['FocusYearMonth'],5,2);
			endif;
		}
		
		function getHTML()
		{
			require_once('dateTimeFunctions.php');
			require_once('arrayFunctions.php');
			
			$CurrentGETVars = $_GET;
			array_delete($CurrentGETVars, 'FocusYearMonth');
			$CurrentGETString = "";
			if(sizeof($CurrentGETVars) > 0):
				foreach($CurrentGETVars as $GETIndex => $GETValue):
					$CurrentGETString .= $GETIndex.'='.$GETValue.'&';
				endforeach;
			endif;
			
			$CalendarHTML = "";
			
			$NextFocusMonth = ((int)$this->FocusMonth + 1 == 13) ? 1 : (int)$this->FocusMonth + 1;
			$NextFocusYear = ($NextFocusMonth == 1) ? (int)$this->FocusYear + 1 : $this->FocusYear;
			$NextFocusYearMonth = $NextFocusYear.'-'.(($NextFocusMonth<10)?"0".$NextFocusMonth:$NextFocusMonth);
			
			$LastFocusMonth = ((int)$this->FocusMonth - 1 == 0) ? 12 : (int)$this->FocusMonth - 1;
			$LastFocusYear = ($LastFocusMonth == 12) ? (int)$this->FocusYear - 1: $this->FocusYear;
			$LastFocusYearMonth = $LastFocusYear.'-'.(($LastFocusMonth<10)?"0".$LastFocusMonth:$LastFocusMonth);
			
			// Get num days in the month and current weekday to aid with calendar generation
			$weekday = date("w", mktime(0, 0, 0, (int)$this->FocusMonth, 1, (int)$this->FocusYear));
			$daysInMonth = date("t", mktime(0, 0, 0, (int)$this->FocusMonth, 1, (int)$this->FocusYear));
		
			// Begin Calendar Output
			$CalendarHTML .= '<table id="EthixCalendarTable" cellspacing="0" cellpadding="3" width="161" border="0">';
			if($this->CurrentMonthLock):
				$CalendarHTML .= '<tr style="margin-bottom: 7px; padding-bottom: 7px;">
									<td align="left" valign="top" nowrap="nowrap">&nbsp;</td>
									<td colspan="5" align="center" valign="top" nowrap="nowrap" class="EthixCalendarTableTitle">'.Month2LongMonth(((int)$this->FocusMonth)).' '.$this->FocusYear.'</td>
									<td align="left" valign="top" nowrap="nowrap">&nbsp;</td>
								  </tr>';
			elseif(!$this->YearControl):
				$CalendarHTML .= '<tr style="margin-bottom: 7px; padding-bottom: 7px;">
									<td align="left" valign="top" nowrap="nowrap"><span onclick="window.location = \'?'.$CurrentGETString.'FocusYearMonth='.$LastFocusYearMonth.'\';"><img style="cursor:pointer" src="'.$this->ArrowImgDirectory.'ecLeftArrow.gif" onmouseover="this.src=\''.$this->ArrowImgDirectory.'ecLeftArrowOver.gif\';" onmouseout="this.src=\''.$this->ArrowImgDirectory.'ecLeftArrow.gif\';" /></span></td>
									<td colspan="5" align="center" valign="top" nowrap="nowrap" class="EthixCalendarTableTitle">'.Month2LongMonth(((int)$this->FocusMonth)).' '.$this->FocusYear.'</td>
									<td align="left" valign="top" nowrap="nowrap"><span onclick="window.location = \'?'.$CurrentGETString.'FocusYearMonth='.$NextFocusYearMonth.'\';"><img style="cursor:pointer" src="'.$this->ArrowImgDirectory.'ecRightArrow.gif" onmouseover="this.src=\''.$this->ArrowImgDirectory.'ecRightArrowOver.gif\';" onmouseout="this.src=\''.$this->ArrowImgDirectory.'ecRightArrow.gif\';" /></span></td>
								  </tr>';
			else:
				$CalendarHTML .= '<tr>
									<td align="left" valign="top" nowrap="nowrap"><span onclick="window.location = \'?'.$CurrentGETString.'FocusYearMonth='.$this->FocusYear.'-'.$LastFocusMonth.'\';"><img style="cursor:pointer" src="'.$this->ArrowImgDirectory.'ecLeftArrow.gif" onmouseover="this.src=\''.$this->ArrowImgDirectory.'ecLeftArrowOver.gif\';" onmouseout="this.src=\''.$this->ArrowImgDirectory.'ecLeftArrow.gif\';" /></span></td>
								  	<td width="100%" colspan="5" align="center" valign="top" nowrap="nowrap" class="EthixCalendarTableTitle">'.Month2LongMonth(((int)$this->FocusMonth)).'</td>
									<td align="left" valign="top" nowrap="nowrap"><span onclick="window.location = \'?'.$CurrentGETString.'FocusYearMonth='.$this->FocusYear.'-'.$NextFocusMonth.'\';"><img style="cursor:pointer" src="'.$this->ArrowImgDirectory.'ecRghtArrow.gif" onmouseover="this.src=\''.$this->ArrowImgDirectory.'ecRightArrowOver.gif\';" onmouseout="this.src=\''.$this->ArrowImgDirectory.'ecRightArrow.gif\';" /></span></td>
								  </tr>
								  <tr style="margin-bottom: 7px; padding-bottom: 7px;">
								  	<td align="left" valign="top" nowrap="nowrap"><span onclick="window.location = \'?'.$CurrentGETString.'FocusYearMonth='.($this->FocusYear-1).'-'.$this->FocusMonth.'\';"><img style="cursor:pointer" src="'.$this->ArrowImgDirectory.'ecLeftArrow.gif" onmouseover="this.src=\''.$this->ArrowImgDirectory.'ecLeftArrowOver.gif\';" onmouseout="this.src=\''.$this->ArrowImgDirectory.'ecLeftArrow.gif\';" /></span></td>
									<td width="100%" colspan="5" align="center" valign="top" nowrap="nowrap" class="EthixCalendarTableTitle">'.$this->FocusYear.'</td>
									<td align="left" valign="top" nowrap="nowrap"><span onclick="window.location = \'?'.$CurrentGETString.'FocusYearMonth='.($this->FocusYear+1).'-'.$this->FocusMonth.'\';"><img style="cursor:pointer" src="'.$this->ArrowImgDirectory.'ecRightArrow.gif" onmouseover="this.src=\''.$this->ArrowImgDirectory.'ecRightArrowOver.gif\';" onmouseout="this.src=\''.$this->ArrowImgDirectory.'ecRightArrow.gif\';" /></span></td>
								  </tr>';
			endif;  
			$CalendarHTML .= '<tr>
								<th width="23" align="center">Sun</th>
								<th width="23" align="center">Mon</th>
								<th width="23" align="center">Tue</th>
								<th width="23" align="center">Wed</th>
								<th width="23" align="center">Thu</th>
								<th width="23" align="center">Fri</th>
								<th width="23" align="center">Sat</th>
							  </tr>';
			// Empty squares before month start
			$CalendarHTML .= '<tr>';
			for ($i=0;$i<$weekday;$i++)
			{
			   // Insert table value that will blacken the square
			   $CalendarHTML .= '<td width="28"> </td>';
			}
			// Done to shorten the looped $today-populating logic
			for($i = 1; $i <= $daysInMonth; $i++)
			{
				$ThisDate = $this->FocusYear.'-'.$this->FocusMonth.'-'.(($i<10)?"0".$i:$i);
				$ThisIsToday = ($ThisDate == $this->Today);
				$ThisDateSelected = ($ThisDate == $this->SelectedDate);
				$CalendarHTML .= '<td class="EthixCalendarDate'.(($ThisDateSelected && $ThisIsToday)?' EthixCalendarSelectedTodayDate':($ThisDateSelected?' EthixCalendarSelectedDate':($ThisIsToday?' EthixCalendarTodayDate':''))).'" id="EthixCalendarDate-'.$i.'" align="center" '.(!$ThisDateSelected?'onclick="'.str_replace('[DATE]',$ThisDate,$this->DateAction).'"':'').'>'.$i.'</td>';
				// If we reached the end of the week
			    if ($weekday == 6)
			    {
					// Complete the row
					$CalendarHTML .= '</tr><tr>';
				   $weekday = -1;
			    }
			    $weekday++;
			}
			
			// The squares after any of the dates
			if($weekday > 0)
			{
				for ($i=$weekday;$i<=6;$i++)
				{
				   // Insert table value that will blacken the square
				   $CalendarHTML .= '<td width="28"> </td>';
				}
			}
			
			// Close table
			$CalendarHTML .= '</tr>
							  <tr>
							  	<td width="100%" colspan="7" align="center" valign="top" nowrap="nowrap">
									<input type="button" value="Return To Today" onclick="'.str_replace('[DATE]',$this->Today,$this->TodayButtonAction).'" />
								</td>
							  </tr></table>';
			
			return $CalendarHTML;
		}
	}
?>