<?php
	/**
	 * This file contains the a library that extends the Pagingandsorting library for
	 * the purpose of defining a specialized formatting function that will drive how
	 * the data gathered through Pagingandsorting displays specific to the administrative
	 * catalog listing page.
	 * 
	 * @package CI_DSSalesRepApp
	 * @subpackage Libraries
	 * @category Libraries
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @version 2009-04-29
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 */

	require_once('libraries/Pagingandsorting.php');

	/**
	 * PAS_admincataloglisting library. 
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @since 2009-04-29
	 * @version 2009-04-29
	 */
	class Pas_admincataloglisting extends Pagingandsorting
	{
		
		/**
		 * This is the main Pagingandsorting formatting function that will take the data collected
		 * from the database and display it in a way that fits this specific interface screen.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-04-29
		 * @version 2009-05-14
		 * 
		 * @param array $DatabaseRowData The rows of data selected from the database represented as arrays indexed by the database column names 
		 * @param array $OutputTableRow This is used primarily for the second call to this formatting function that addresses large database data, such as image blobs.  This will contain the display data from the first pass in this case, otherwise it will be empty
		 * @return array The formatted version of the corresponding $DatabaseRowData, and optionally any new "pseudo" columns that need to get created that aren't directly based off of one database field
		 */
		protected function FormattingFunction($DatabaseRowData, $OutputTableRow = array())
		{
			if(sizeof($OutputTableRow) == 0):
				$SectionCategoryArray = explode('-',$DatabaseRowData["ClassID"],2);
				$SectionName = $SectionCategoryArray[0];
				$CategoryName = isset($SectionCategoryArray[1]) ? $SectionCategoryArray[1] : '';
				$OutputTableRow["Number"] = $DatabaseRowData["InvtID"];
				$OutputTableRow["Section"] = $SectionName;
				$OutputTableRow["Category"] = $CategoryName;
				$OutputTableRow["Product"] = $DatabaseRowData["Descr"];
				$OutputTableRow["Price"] = '$'.number_format(round($DatabaseRowData["StkBasePrc"],2),2).'/'.$DatabaseRowData["StkUnit"];
				if(isset($DatabaseRowData["Hidden"]) && $DatabaseRowData["Hidden"])
					$OutputTableRow["Action"] = '<img src="/img/note_disable.gif" width="16" style="cursor:pointer;" onclick="EXTJS_ShowProduct(\'Show Product\',\'This product is currently hidden to sales reps.  Are you sure you want to unhide it?\',\''.$DatabaseRowData["InvtID"].'\');" width="20" title="header=[Show Product] body=[Let sales reps see this product]" />';
				else
					$OutputTableRow["Action"] = '<img src="/img/note_enable.gif" width="16" style="cursor:pointer;" onclick="EXTJS_HideProduct(\'Hide Product\',\'This product is currently visible to sales reps.  Are you sure you want to hide it from them?\',\''.$DatabaseRowData["InvtID"].'\');" width="20" title="header=[Hide Product] body=[Hide this products from sales reps]" />';					
				$OutputTableRow["Action"] .= '<img src="/img/note_edit.gif" style="cursor:pointer;" onclick="AJAX_DisplayEditProductForm(\''.$DatabaseRowData["InvtID"].'\');" width="20" title="header=[Edit] body=[Edit product information]" />';
			endif;
			return $OutputTableRow;
		}
	}
?>