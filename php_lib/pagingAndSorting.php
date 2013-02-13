<?php
	/**
	 * This file contains the old PagingAndSorting class as it was before being
	 * ported to the CodeIgniter framework.  Any new updates and bug fixes are only
	 * being applied to the new CI version, and so this shouldn't be used in any
	 * new production projects.
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @since Unknown
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 * 
	 * @package ES_GeneralPHPLibraries
	 * @subpackage Deprecated
	 * @category Deprecated
	 * 
	 * @deprecated
	 */

/*<!-- SAMPLE OF HOW TO FORMAT pagingAndSorting table -->
<!-- 	<html>
	<head>
<style>
	table.AdminReportTable {background-color:#EEEEEE; font-size:13px;}
	table.AdminReportTable td.PagingAndSortingTitleTD {text-align:center; font-size:15px; font-weight:bold; font-family:"Times New Roman", Times, serif}
	table.AdminReportTable table {background-color:#F4F8FF; font-size:14px;}
	table.AdminReportTable table th {background-color:#E1EAFF}
	table.AdminReportTable table.PagingAndSortingContentTable {background-color:#EAF2FF}
	table.AdminReportTable table tr.PagingAndSortingRowColor {background-color:#EAF2FF}
	table.AdminReportTable span.PagingAndSortingSelectedPageSpan {color:#CC0000}
	div.PagingAndSortingNoMatchesDiv
</style>
<body>	 
 -->*/

	/*
	// SAMPLE OF HOW TO CALL pagingAndSorting
	 
	$host = "dbprod.ethixsystems.com";
	$user = "u_williamcoulson";
	$password = "JdksLKJeW)(@38!@#)(&sdfAbeREbrE_A)dajefWdsvads:.";
	$database = "DB_WilliamCoulson";
	
	$ColumnData = array();
	$ColumnData['User Name'] = array('DisplayWidth' => '30%', 'SQLColumn' => 'U_UserName', 'DoNotDisplayHeader' => true, 'Sortable' => true);
	$ColumnData['First Name'] = array('DisplayWidth' => '15%', 'SQLColumn' => 'FirstName', 'Sortable' => true);
	$ColumnData['Middle Name'] = array('DisplayWidth' => '15%', 'SQLColumn' => 'MiddleName', 'Sortable' => true);
	$ColumnData['Last Logged In'] = array('DisplayWidth' => '40%', 'SQLColumn' => 'LastLoginDT', 'Sortable' => true);
	
	$LargeItemsArray = array('Last Logged In');
	$UniqueIdentifierArray = array('PK_CustomerNum');
	$SQL = "select PK_CustomerNum, U_UserName, MiddleName, FirstName, LastLoginDT
			from TB_Customer";
	$mysqli = new mysqli($host, $user, $password, $database);
	$TitleHTML = 'List Of Users';
	$TableDefHTML = '<table border="0" cellspacing="0" cellpadding="6" align="left" width="750" class="AdminReportTable">';
	$DefaultSortCol = 'User Name';
	$DefaultSortDir = 'asc';
	$DefaultResultsPerPage = 25;
	$UserControlsResultsPerPage = true;
	$UserControlLines = 1;
	$SearchWithin = false;
	$MaxResultsPerPage = 100;
	$PagesShown = 5;
	$ResultDesc = "Customers";
	
	echo pagingAndSorting($ColumnData, $LargeItemsArray, $UniqueIdentifierArray, $SQL, $mysqli, $TitleHTML, $TableDefHTML, $DefaultSortCol, $DefaultSortDir, $DefaultResultsPerPage, $UserControlsResultsPerPage, $UserControlLines, $SearchWithin, $MaxResultsPerPage, $PagesShown, $ResultDesc);
	
	function pagingAndSortingFormattingFunction($SQLRow, $TableArray = array())
	{
		require_once('dateTimeFunctions.php');
		if(sizeof($TableArray) == 0)
		{
			$TableArray['User Name'] = "<b>".$SQLRow['U_UserName']."</b>";
			$TableArray['First Name'] = $SQLRow['FirstName'];
			$TableArray['Middle Name'] = "hutspah";
			if(isset($SQLRow['LastLoginDT']))
				$TableArray['Last Logged In'] = Database2LongDate($SQLRow['LastLoginDT']);
		}
		else
			$TableArray['Last Logged In'] = Database2LongDate($SQLRow['LastLoginDT']);
		return $TableArray;
	}
	*/
	/*
	pagingAndSorting ~ Function that will add pages and/or sort table data in PHP
		-New functionality - sort can be done within the SQL call to restrict the dataset dramatically
	
	$ColumnData :: 2-D array holding column data
			'DisplayName' :: The index of the array is the name of the column
			'DisplayWidth' :: Width of the column - leave blank if not necessary - to do percentages, just tack on a % at the end
			'MySQLColumn' :: If this column is sortable on the database, this holds the column to sort on - leave blank if not sortable on a database column
			'DoNotDisplayHeader' :: Set if the column header should be displayed, false if not
			'Sortable' :: True if the column is sortable, false if not
	$LargeItemsArray :: Array holding any SQL database fields wished to be excluded from the initial query for performance reasons - these fields will not be searchable or sortable
		-if you plan on having no search and doing no sorting, leave this array empty for highest performance
	$UniqueIdentifierArray :: Array holding the combination of fields that uniquely identify each row - only required if using LargeItemsArray
	$SQL :: Basic SQL statement to get the information for the table data - do not include an order by, limit, semicolon, or any fields in LargeItemsArray  
	$SQLConnection :: SQL object to be passed in to run the query
	$TitleHTML :: Optional param that will give the table a title
	$TableDefHTML :: Table start tag for results table -- Required for function use
	$DefaultSortCol :: Column name of $TableData to sort by if no column is selected through $_GET -- Required if using sort
	$DefaultSortDir :: Default direction to sort $TableData by if no direction is selected through $_GET -- Required if using sort -- Accepted values are ASC and DESC
	$DefaultResultsPerPage :: Number of table rows to display by default when not specified through $_GET -- Required for paging
	$UserControlsResultsPerPage :: Boolean that enables the user to select how many results there will be per page
	$UserControlLines :: Required when $UserControlsResultsPerPage = 1, valid values are 1 or 2... number of lines for user control bar - default to 1
	$SearchWithin :: Boolean that enables the user to search within the given results
	$MaxResultsPerPage :: Maximum results that can be choosen per page -- Must be evenly divisible by 5 -- Required for paging
	$PagesShown :: The number of pages that are shown at a given time -- Must be an odd number >= 3 -- Required for paging
	$ResultDesc :: Description of what the table holds (ex: Items, Customers, Orders, etc.) -- Required for paging
	$HTMLBetweenRows :: HTML code to go between rows of values - OPTIONAL - default to empty string
	$DebuggingMode :: a boolean determining whether the pagingAndSorting call should display debugging information - OPTIONAL - default to false
	$SortArrowLocation :: a string containing the URL location for the folder containing the image arrows sortArrowDown.gif and /sortArrowUp.gif - OPTIONAL - default to /img

	This function requires the following function to be defined:
		pagingAndSortingFormattingFunction :: Name of the function that will convert the row data from the database to the fields that should show up on the screen - this must be defined outside of this function
		-This function must take as a single parameter the row of data from the database
		-It must return the column data to be displayed, in an array indexed by the display name of the column, set in the order which they are to be displayed on the screen
		-You may not use "PK" or "PKValue" as one of your field names
		 
	 */

	/**
	 * This is the old PagingAndSorting class.  This class and its member variables / functions
	 * are no completely documented and will not be so due to its deprecated state.  Please see
	 * the new PagingAndSorting class in the libraries folder under php_lib. 
	 * 
	 * @since Unknown
	 * @version Unknown
	 * @author Daniel E. Carr <decarr@ethixsystems.com>
	 *
	 * @deprecated
	 */
	class PagingAndSorting
	{
		/**
		 * Enter description here...
		 *
		 * @param array $ColumnArray array holding Column objects - see Column class for details
		 * @param string $SQL Possible values are "stacked"* and "grouped"
		 *	$SQL :: Basic SQL statement to get the information for the table data - do not include an order by, limit, semicolon, or any fields in LargeItemsArray  
		 *	$SQLConnection :: SQL object to be passed in to run the query
		 *	$FormattingFunction :: The name of the function that formats the fields in the desired manner, stored as a string
		 *	$LargeItemsArray :: An array containing items considered large items - this function maximizes efficiency by loading as few of these as possible - these items may not be searchable or sortable
		 *	$TableDefHTML :: Table start tag for results table -- Required for function use
		 *	$TitleHTML :: Optional param that will give the table a title
		 *	$DefaultSortCol :: Column name of $TableData to sort by if no column is selected through $_GET -- Required if using sort
		 *	$DefaultSortDir :: Default direction to sort $TableData by if no direction is selected through $_GET -- Required if using sort -- Accepted values are ASC and DESC
		 *	$DefaultResultsPerPage :: Number of table rows to display by default when not specified through $_GET -- Required for paging
		 *	$UserControlsResultsPerPage :: Boolean that enables the user to select how many results there will be per page
		 *	$UserControlLines :: Required when $UserControlsResultsPerPage = 1, valid values are 1 or 2... number of lines for user control bar
		 *	$SearchWithin :: Boolean that enables the user to search within the given results
		 *	$MaxResultsPerPage :: Maximum results that can be choosen per page -- Must be evenly divisible by 5 -- Required for paging
		 *	$PagesShown :: The number of pages that are shown at a given time -- Must be an odd number >= 3 -- Required for paging
		 *	$ResultDesc :: Description of what the table holds (ex: Items, Customers, Orders, etc.) -- Required for paging
		 *	$HTMLBetweenRows :: HTML code to go between rows of values - OPTIONAL - default to empty string
		 *	$UniqueIdentifierArray :: Array holding the combination of fields that uniquely identify each row - only required if using LargeItemsArray
		 *	$DebuggingMode :: a boolean determining whether the pagingAndSorting call should display debugging information - OPTIONAL - default to false
		 *	$UniqueIdentifier :: a boolean determining whether the pagingAndSorting call should display debugging information - OPTIONAL - default to false
 		 *	$SortArrowLocation :: a string containing the URL location for the folder containing the image arrows sortArrowDown.gif and /sortArrowUp.gif - OPTIONAL - default to /img
		*/
			protected $ColumnArray = array();
			private $SQL = "";
			private $SQLConnection = "";
			private $LargeItemsArray = array();
			private $TableDefHTML = "";
			private $TitleHTML = "";
			private $DefaultSortCol = "";
			private $DefaultSortDir = "";
			private $DefaultResultsPerPage = "";
			private $UserControlsResultsPerPage = false;
			private $UserControlsLines = "";
			private $SearchWithin = false;
			private $MaxResultsPerPage = "";
			private $PagesShown = "";
			private $ResultDesc = "";
			private $HTMLBetweenRows = "";
			private $DebuggingMode = "";
			private $UniqueIdentifierArray = array();
			private $SortArrowLocation = "";
			private $TRClickVariables = array();
			private $TRClickAction = "";
			private $TRClickExcludeColumns = array();
			private $ColumnJustifications = array();
			private $SQLConnectionType = "";
			public $ErrorMessage = "";
			
		public function __construct($SQL, &$SQLConnection, $TableDefHTML, $ResultDesc, $SQLConnectionType = "mysqli")
		{
			$this->SQL = $SQL;
			$this->SQLConnection = &$SQLConnection;
			$this->TableDefHTML = $TableDefHTML;
			$this->TitleHTML = "";
			$this->DefaultSortCol = '';
			$this->DefaultSortDir = "asc";
			$this->DefaultResultsPerPage = 10;
			$this->UserControlsResultsPerPage = false;
			$this->UserControlLines = 1;
			$this->SearchWithin = false;
			$this->MaxResultsPerPage = 20;
			$this->PagesShown = 5;
			$this->ResultDesc = $ResultDesc;
			$this->HTMLBetweenRows = "";
			$this->DebuggingMode = false;
			$this->SortArrowLocation = "/img";
			$this->SQLConnectionType = $SQLConnectionType;
		}
		
		/**
		 * 	This function allows you to set a column up in the pagingAndSorting table
		 * @param $DisplayName The name of the column that you wish to have appear on the screen
		 * @param $DisplayWidth The width of the column, use px or %, but be consistant and include units
		 * @param $MySQLColumnName The name of the MySQL Column that you would like to sort on - leave blank if there is none
		 * @param $OptionsArray An array containing potential values that define properties of the column
		 * 		Sortable being set means that the column is sortable
		 * 		DefaultSortColumn being set means that the column should be the defaulted sort column
		 * 		DoNotDisplayHeader being set means that the header should not be displayed
		 * 		IsLargeItem should be set if the item is linked to a very large field on a database
		 * 			***Special Note on Large Items - DO NOT include them in $this->SQL, and make sure you have the correct unique identifier set
		 * 		PartOfUniqueIdentifier should be set if the item is linked to a database field that helps determine uniqueness in the query
		 * @return none
		 */
		public function setColumnInfo($DisplayName, $DisplayWidth, $MySQLColumnName, $OptionsArray)
		{
			$Column = array();
			$Column['DisplayName'] = $DisplayName;
			if($DisplayWidth != -1)
				$Column['DisplayWidth'] = $DisplayWidth;
			$Column['MySQLColumnName'] = $MySQLColumnName;
			$Column['Sortable'] = (isset($OptionsArray['Sortable']))?$OptionsArray['Sortable']:false;
			$this->DefaultSortCol = (isset($OptionsArray['DefaultSortColumn']) && $OptionsArray['DefaultSortColumn'])?$DisplayName:$this->DefaultSortCol;
			$Column['DoNotDisplayHeader'] = (isset($OptionsArray['DoNotDisplayHeader']))?$OptionsArray['DoNotDisplayHeader']:false;
			if(isset($OptionsArray['IsLargeItem']) && $OptionsArray['IsLargeItem'])
			{
				$this->LargeItemsArray[] = $MySQLColumnName;
				if($Column['Sortable'])
					$this->ErrorMessage .=  "ERROR: You cannot sort on large items.<br/>.";
			}
			if(isset($OptionsArray['PartOfUniqueIdentifier']) && $OptionsArray['PartOfUniqueIdentifier'])
				$this->UniqueIdentifierArray[] = $MySQLColumnName;
			$this->ColumnArray[] = $Column;
		}
		
		public function setTitleHTML($TitleHTML)
		{
			$this->TitleHTML = $TitleHTML;
			return true;
		}
		
		public function setDefaultSortDirection($DefaultSortDirection)
		{
			if($DefaultSortDirection != 'asc' && $DefaultSortDirection != 'desc')
			{
				$this->ErrorMessage .=  "ERROR: Default Sort Direction must be set to 'asc' or 'desc'<br/>.";
				return false;
			}
			
			$this->DefaultSortDir = $DefaultSortDirection;
			return true;
		}
		
		public function setDefaultResultsPerPage($DefaultResultsPerPage)
		{
			require_once('dataVerification.php');
			if(!isInt($DefaultResultsPerPage) && $DefaultResultsPerPage != 'ALL')
			{
				$this->ErrorMessage .= "ERROR: Default Results Per Page must be set to an integer.";
				return false;
			}
			if($DefaultResultsPerPage <= 0 && $DefaultResultsPerPage != 'ALL')
			{
				$this->ErrorMessage .= "ERROR: Default Results Per Page cannot be a negative number.";
				return false;
			}
			
			$this->DefaultResultsPerPage = $DefaultResultsPerPage;
			return true;
			
		}
		
		public function letUserControlResultsPerPage($letUserControlResultsPerPage = true)
		{
			if($letUserControlResultsPerPage)
				$this->UserControlsResultsPerPage = true;
			else
				$this->UserControlsResultsPerPage = false;
			return true;
		}
		
		public function setUserControlLines($userControlLines)
		{
			if($userControlLines != 1 && $userControlLines != 2)
			{
				$this->ErrorMessage .= "ERROR: Default Results Per Page must be set to an integer.";
				return false;
			}
			$this->UserControlLines = $userControlLines;
			return true;
		}
		
		public function letUserSearchWithin($SearchWithin = true)
		{
			if($SearchWithin)
				$this->SearchWithin = true;
			else
				$this->SearchWithin = false;
			return true;
		}
		
		public function setMaxResultsPerPage($MaxResultsPerPage)
		{
			if($MaxResultsPerPage < 5)
			{
				$this->ErrorMessage .= "ERROR: Max Results Per Page must be greater than or equal to 5.";
				return false;
			}
			
			if($MaxResultsPerPage % 5 != 0)
			{
				$this->ErrorMessage .= "ERROR: Max Results Per Page must be divisible by 5.";
				return false;
			}
			
			$this->MaxResultsPerPage = $MaxResultsPerPage;
			return true;
		}
		
		public function setPagesShown($NumPagesShown)
		{
			if($PagesShown < 3)
			{
				$this->ErrorMessage .= "ERROR: Pages shown must be greater than or equal to 3.";
				return false;
			}

			if(($PagesShown%2) != 1)
			{
				$this->ErrorMessage .= "ERROR: Pages shown must must be an odd number.";
				return false;
			}
			
			$this->PagesShown = $PagesShown;
			return true;
		}
		
		public function setHTMLBetweenRows($HTMLBetweenRows)
		{
			$this->HTMLBetweenRows = $HTMLBetweenRows;
			return true;
		}

		public function setImageArrowLocation($SortArrowLocation)
		{
			$this->SortArrowLocation = $SortArrowLocation;
			return true;
		}
		
		public function setDebugMode($DebugMode)
		{
			if($DebugMode)
				$this->DebuggingMode = true;
			else
				$this->DebuggingMode = false;
			return true;
		}
		
		public function setTRClickAction($TRClickAction, $VariableArray = array(), $ExcludeColumns = array())
		{
			$this->TRClickAction = str_replace('"',"'",$TRClickAction);
			$this->TRClickVariables = $VariableArray;
			$this->TRClickExcludeColumns = $ExcludeColumns;
		}
		
		public function setColumnJustificationChanges($ColumnJustifications)
		{
			$this->ColumnJustifications = $ColumnJustifications;
		}
		
		protected function FormattingFunction($SQLRow, $tableArray = array())
		{
			$returnArray = $tableArray;
			foreach($SQLRow as $index => $val)
			{
				foreach($this->ColumnArray as $columnIndex => $columnInfo)
				{
					if($index == $columnInfo['MySQLColumnName']):
					//	echo $columnInfo['DisplayName']." => ".$val."<br/>";
						$returnArray[$columnInfo['DisplayName']] = $val;
					break;
					endif;
				}
			}
			return $returnArray;
		}

		public function displayTable()
		{
			if($this->ErrorMessage != "")
			{
				if($this->DebuggingMode)
					return returnTextAndDebug("There were errors with the setup.  Check the error log:", $this->ErrorMessage);
				else
					return returnTextAndDebug("This information is not available at this time.  Please try again later.<br/>Error Code: P&S", "");
			}
			$outputHTML = "";	
			$DebugMessage = "";
			require_once('dataVerification.php');

			if(sizeof($this->LargeItemsArray) > 0 && sizeof($this->UniqueIdentifierArray) == 0)
				$DebugMessage .= ($this->DebuggingMode)?"ERROR::You cannot have large items without setting a unique identifier.<br/>":"";
			
			if($this->SearchWithin && sizeof($this->UniqueIdentifierArray) == 0)
				$DebugMessage .= ($this->DebuggingMode)?"ERROR::You cannot have user searches enabled without setting a unique identifier.<br/>":"";
			
			if($this->SearchWithin)
			{
				$DebugMessage .= ($this->DebuggingMode)?"DEBUGGING MESSAGE::Search Turned On - SearchWithin is true.<br/>":"";
			}
			else
			{
				$DebugMessage .= ($this->DebuggingMode)?"DEBUGGING MESSAGE::Search Turned Off - SearchWithin is false.<br/>":"";
			}
			
			//Determine if paging or sorting is going on
			if($this->DefaultResultsPerPage == "ALL")
			{
				$DisplayPaging = false;
				$DebugMessage .= ($this->DebuggingMode)?"DEBUGGING MESSAGE::Paging Turned Off - All DefaultResultsPerPage requested.<br/>":"";
			}
			else if($this->DefaultResultsPerPage <= 0)
			{
				$DisplayPaging = false;
				$DebugMessage .= ($this->DebuggingMode)?"DEBUGGING MESSAGE::Paging Turned Off - DefaultResultsPerPage less than 0.<br/>":"";
			}
			else if($this->PagesShown < 3)
			{
				$DisplayPaging = false;
				$DebugMessage .= ($this->DebuggingMode)?"DEBUGGING MESSAGE::Paging Turned Off - PagesShown is less than 3.<br/>":"";
			}
			else if(($this->PagesShown%2) != 1)
			{
				$DisplayPaging = false;
				$DebugMessage .= ($this->DebuggingMode)?"DEBUGGING MESSAGE::Paging Turned Off - PagesShown is an even number.<br/>":"";
			}
			else if($this->MaxResultsPerPage < 5)
			{
				$DisplayPaging = false;
				$DebugMessage .= ($this->DebuggingMode)?"DEBUGGING MESSAGE::Paging Turned Off - MaxResultsPerPage is less than 5.<br/>":"";
			}
			else if(($this->MaxResultsPerPage%5) != 0)
			{
				$DisplayPaging = false;
				$DebugMessage .= ($this->DebuggingMode)?"DEBUGGING MESSAGE::Paging Turned Off - MaxResultsPerPage is not divisible by 5.<br/>":"";
			}
			else if($this->ResultDesc == '')
			{
				$DisplayPaging = false;
				$DebugMessage .= ($this->DebuggingMode)?"DEBUGGING MESSAGE::Paging Turned Off - ResultDesc is not set.<br/>":"";
			}
			else
			{
				$DisplayPaging = true;
				$DebugMessage .= ($this->DebuggingMode)?"DEBUGGING MESSAGE::Paging Turned On - All conditions met.<br/>":"";
			}
			
			$DisplayTableHeader = false;
			foreach($this->ColumnArray as $SingleColumn)
			{
				if(!$SingleColumn['DoNotDisplayHeader'])
					$DisplayTableHeader = true;
			}
			if($DisplayTableHeader)
				$DebugMessage .= ($this->DebuggingMode)?"DEBUGGING MESSAGE::Table Header Display Turned On.<br/>":"";
			else
				$DebugMessage .= ($this->DebuggingMode)?"DEBUGGING MESSAGE::Table Header Display Turned Off.<br/>":"";
			
			if($this->DefaultSortCol == '')
			{
				$DisplaySorting = false;
				$DebugMessage .= ($this->DebuggingMode)?"DEBUGGING MESSAGE::Sorting Turned Off - Default Sort Column not set.<br/>":"";
			}
			else if($this->DefaultSortDir != 'asc' && $this->DefaultSortDir != 'desc')
			{
				$DisplaySorting = false;
				$DebugMessage .= ($this->DebuggingMode)?"DEBUGGING MESSAGE::Sorting Turned Off - Default Sort Direction not set correctly.<br/>":"";
			}
			else if(!$DisplayTableHeader)
			{
				$DisplaySorting = false;
				$DebugMessage .= ($this->DebuggingMode)?"DEBUGGING MESSAGE::Sorting Turned Off - Table Header turned off.<br/>":"";
			}
			else
			{
				$DisplaySorting = true;
				$DebugMessage .= ($this->DebuggingMode)?"DEBUGGING MESSAGE::Sorting Turned On - All conditions met.<br/>":"";
			}
		
			//Determine if any sortable fields are sortable by something other than a database field
			$SortableOnDatabase = true;
			$HasAUniqueIdentifier = false;
			$HasLargeItems = false;
			
			if($DisplaySorting)
			{
				foreach($this->ColumnArray as $SingleColumnData)
				{
					if($SingleColumnData['Sortable'] && !isset($SingleColumnData['MySQLColumnName']))
						$SortableOnDatabase = false;
				}
			}
			
			if($SortableOnDatabase)
				$DebugMessage .= ($this->DebuggingMode)?"DEBUGGING MESSAGE::The data is sortable on the database.<br/>":"";
			else
				$DebugMessage .= ($this->DebuggingMode)?"DEBUGGING MESSAGE::The data is not sortable on the database.<br/>":"";
			
			//Determine sorting information if applicable
			if($DisplaySorting)
			{
				// Determine which column to sort on and direction
				$SortCol = isset($_GET['SortCol']) ? $_GET['SortCol'] : $this->DefaultSortCol;
				$SortDir = isset($_GET['SortDir']) ? $_GET['SortDir'] : $this->DefaultSortDir;
				$ColumnFound = false;
				foreach($this->ColumnArray as $SingleColumnData)
				{
					if($SortCol == $SingleColumnData['DisplayName'])
						$ColumnFound = true;
				}
				if(!$ColumnFound)
					$SortCol = $this->DefaultSortCol;
				if($SortDir != "asc" && $SortDir != "desc")
				{
					$SortDir = $this->DefaultSortDir;
				}
			}
			
			$CurrentPage = 1;
			$NumberOfPages = 1;
			
			
			//Get a count of the data in the dataset
			
			//only explode on the first "from"
			$modifiedSQL = replace_first('from', 'SPLITHERE', $this->SQL);
			$modifiedSQL = replace_first('FROM', 'SPLITHERE', $modifiedSQL);
			$SQLArray = explode('SPLITHERE', $modifiedSQL);
			
			$countSQL = "select count(1) from ".$SQLArray[1];
			if($this->SQLConnectionType == "mysqli"):
				$SQLResult = $this->SQLConnection->query($countSQL);
				$SQLRow = $SQLResult->fetch_array(MYSQLI_NUM);
			elseif($this->SQLConnectionType == "mssql"):
				$SQLResult = mssql_query($countSQL, $this->SQLConnection);
				$SQLRow = mssql_fetch_array($SQLResult, MSSQL_NUM);
			else:
				$DebugMessage .= ($this->DebuggingMode)?"DEBUGGING MESSAGE::Invalid SQL Connection Type - cannot count items in dataset.<br/>":"";
			endif;
			
			$TableDataSize = $SQLRow[0];
	
			//Determine paging information if applicable
			if($DisplayPaging)
			{
				$ResultsPerPage = isset($_GET['ResultsPerPage']) ? trim($_GET['ResultsPerPage']) : $this->DefaultResultsPerPage;
				if((!isInt($ResultsPerPage) || $ResultsPerPage < 1) && $ResultsPerPage != "ALL")
					$ResultsPerPage = $this->DefaultResultsPerPage;
				$CurrentPage = isset($_GET['Page']) ? trim($_GET['Page']) : 1;
				$ItemNumberToBeginWith = ($CurrentPage-1)*$ResultsPerPage + 1;
				$ItemNumberToEndWith = $ResultsPerPage+$ItemNumberToBeginWith-1;
				if($ResultsPerPage == 'ALL')
					$ItemNumberToEndWith = $TableDataSize;
			}
			else
			{
				$ResultsPerPage = "ALL";
			}
			
			//Add to the SQL statement if necessary
			$newSQL = $this->SQL;
			
			//Order by
			if($DisplaySorting)
			{
				foreach($this->ColumnArray as $SingleColumn)
				{
					if($SingleColumn['DisplayName'] == $SortCol)
					{
						$MySQLSortCol = $SingleColumn['MySQLColumnName'];
						break;
					}
				}
				$orderbySQL = " order by $MySQLSortCol $SortDir ";
			}
			else
				$orderbySQL = "";
			
			if(!$this->SearchWithin && $SortableOnDatabase)
			{
				//This is the case where we can limit the results inside of the SQL
				
				//Limit
				if($ResultsPerPage == "ALL")
					$ResultsPerPage = $TableDataSize;
				if($DisplayPaging):
					if($this->SQLConnectionType == "mysqli"):
						$newSQL .= $orderbySQL." limit ".($ItemNumberToBeginWith-1).", $ResultsPerPage ";
					elseif($this->SQLConnectionType == "mssql"):
						$notInStrArray = array();
						foreach($this->UniqueIdentifierArray as $UID):
							$FromPartPosition = strpos($newSQL, "from");
							$notInStrArray[] = $UID." not in (select top ".($ItemNumberToBeginWith-1)." ".$UID." ".substr($newSQL, $FromPartPosition).' '.$orderbySQL.')';
						endforeach;
						$WherePartPosition = (substr_count($newSQL, "where")==0?false:strpos($newSQL,"where"));
						$newSQL = str_replace("select","select top $ResultsPerPage",substr($newSQL,0,$WherePartPosition)).(!$WherePartPosition?" where ".implode(' and ',$notInStrArray):str_replace("where","where ".implode(' and ',$notInStrArray)." and", substr($newSQL,$WherePartPosition))).$orderbySQL;
					else:
						$DebugMessage .= ($this->DebuggingMode)?"DEBUGGING MESSAGE::Invalid SQL Connection Type - cannot set a select limit on first SQL select.<br/>":"";
					endif;
				endif;
			}
			//Run the SQL statement
			$DebugMessage .= ($this->DebuggingMode)?"FIRST SQL BEING RUN::$newSQL<br/>":"";
			if($this->SQLConnectionType == "mysqli")
				$SQLResult = $this->SQLConnection->query($newSQL);
			elseif($this->SQLConnectionType == "mssql")
				$SQLResult = mssql_query($newSQL, $this->SQLConnection);
			else
				$DebugMessage .= ($this->DebuggingMode)?"DEBUGGING MESSAGE::Invalid SQL Connection Type - cannot run first SQL select.<br/>":"";
			
			//run each SQL row through the formatting function
			$TableData = array();
			$i=1;
			//get the data array
			
			while(($this->SQLConnectionType=="mysqli"?$SQLRow = $SQLResult->fetch_array(MYSQLI_ASSOC):$SQLRow = mssql_fetch_array($SQLResult, MSSQL_ASSOC)))
			{
				
				$TableData[$i] = $this->FormattingFunction($SQLRow);
				
				// tr click addition
				$TRClickVarValidation = true;
				foreach($this->TRClickVariables as $DBColumn):
					if(!array_key_exists($DBColumn, $SQLRow)):
						$DebugMessage .= ($this->DebuggingMode)?"DEBUGGING MESSAGE::One or more TR Click variables do not exist in your SQL statement; TR Click will be disabled until this is resolved.<br/>":"";
						$TRClickVarValidation = false;
						break;
					endif;
				endforeach;
				if($TRClickVarValidation):
					$ThisTRClick = $this->TRClickAction;
					foreach($this->TRClickVariables as $Placeholder => $DBColumn):
						$ThisTRClick = str_replace($Placeholder,$SQLRow[$DBColumn],$ThisTRClick);
					endforeach;
					$TableData[$i]['TRClick'] = $ThisTRClick;
				else:
					$TableData[$i]['TRClick'] = "";
				endif;
				
				foreach($this->UniqueIdentifierArray as $j => $val)
				{
					$TableData[$i]['PK'][$j] = $val;
					$TableData[$i]['PKValue'][$j] = $SQLRow[$val];
				}
				$i++;
			}
			
			if(!$this->SearchWithin && $SortableOnDatabase)
			{
				array_unshift($TableData,"");
			}
			//get the size of the dataset
			if($TableDataSize < 1)
			{
				return returnTextAndDebug('<div id="PASNoResultsDiv">There are no '.$this->ResultDesc.' to display.</div>', $DebugMessage);
			}
			
			$ResultCountStart = 1;
			$ResultCountEnd = $TableDataSize;
			
			//implement search logic if applicable
			if($this->SearchWithin)
			{
				$SearchWithinLimit = isset($_GET['SearchWithinLimit']) ? str_replace('&quot;','"',stripslashes(trim($_GET['SearchWithinLimit']))) : '';
				if($SearchWithinLimit != "")
				{
					$QuoteLocations = array();
					for($i=0; $i<strlen($SearchWithinLimit); $i++)
						if($SearchWithinLimit{$i} == '"')
							$QuoteLocations[] = $i;
					if(sizeof($QuoteLocations) > 0 && sizeof($QuoteLocations)%2 == 1)
					{
						$SearchWithinLimit = substr($SearchWithinLimit,0,$QuoteLocations[sizeof($QuoteLocations)-1]).substr($SearchWithinLimit,$QuoteLocations[sizeof($QuoteLocations)-1]+1);
						unset($QuoteLocations[sizeof($QuoteLocations)-1]);
					}
				}
				$SearchWithinHTML = (($this->UserControlLines==2)?'<br />':' ').'Search: <input id="SearchWithinLimit" value="'.str_replace('"','&quot;',$SearchWithinLimit).'" size="15" maxlength="255" /> <input type="button" value="Go" onclick="window.location.href = window.location.pathname+\''.Array2GetString($_GET, array('SearchWithinLimit','Page')).'Page=1&SearchWithinLimit=\'+encodeURIComponent(document.getElementById(\'SearchWithinLimit\').value);" />';
				$SearchWithinHTML .= '<script type="text/javascript">
										document.getElementById(\'SearchWithinLimit\').onkeydown = function(e) {submitSearchWithinLimit(e);};
										function submitSearchWithinLimit(e){
											if(e.keyCode == 13) // enter key
											window.location.href = window.location.pathname+\''.Array2GetString($_GET, array('SearchWithinLimit','Page')).'Page=1&SearchWithinLimit=\'+encodeURIComponent(document.getElementById(\'SearchWithinLimit\').value);
										}</script>';
				if($SearchWithinLimit != '')
				{	
					$SearchWithinLimit = strtoupper($SearchWithinLimit);			
					$SearchWithinLimit = str_replace(" AND "," ",$SearchWithinLimit);
					$SearchWords = explode(" ",$SearchWithinLimit);
					$InQuotes = false;
					$SearchWithinLimit = "";
					foreach($SearchWords as $i => $Word)
					{
						if(!isset($SearchWords[$i+1]))
						{
							$SearchWithinLimit .= $Word;
							break;
						}
						if(substr($Word,0,1) == '"')
							$InQuotes = true;
						if(substr($Word,strlen($Word)-1) == '"')
							$InQuotes = false;
						if(!$InQuotes && $Word != "OR")
							$SearchWithinLimit .= $Word." AND ";
						else
							$SearchWithinLimit .= $Word." ";
					}
					$SearchWithinLimit = str_replace('"','',$SearchWithinLimit);
					$SearchWithinLimit = str_replace('AND OR','OR',$SearchWithinLimit);
					
					//echo $SearchWithinLimit;
					
					$SearchBlocks = explode(" OR ",$SearchWithinLimit);
					
					for($i=1; $i<=$TableDataSize; $i++)
					{
						$KeepRow = false;
						foreach($SearchBlocks as $Block)
						{
							$BlockPieces = explode(" AND ",$Block);
							$BlockPiecesSize = sizeof($BlockPieces);
							$PiecesMatched = array();
							foreach($BlockPieces as $PieceIndex => $Piece)
							{
								foreach($TableData[$i] as $k => $DataElement)
								{
									$NoHTMLElement = preg_replace("/(<\/?)(\w+)([^>]*>)/e","",$DataElement);
									if(gettype($NoHTMLElement) != 'array')
									{
										if(substr_count(strtolower($NoHTMLElement),strtolower($Piece)) > 0)
										{
											if(!in_array($PieceIndex,$PiecesMatched))
												$PiecesMatched[] = $PieceIndex;
										}
									}
								}
							}	
							if($BlockPiecesSize == sizeof($PiecesMatched))
							{
								$KeepRow = true;
								break;
							}
						}
						if(!$KeepRow)
							unset($TableData[$i]);				
					}
					$TableDataSize = sizeof($TableData);
				}			
			}
			else
				$SearchWithinHTML = "";
			
			// determine sorting information if applicable
			if(($this->SearchWithin || !$SortableOnDatabase) && $DisplaySorting)
			{
				// Obtain a list of columns
				$ColumnNames = array();
				foreach($this->ColumnArray as $SingleColumn)
				{
					$ColumnNames[] = $SingleColumn['DisplayName'];
				}
				
				// Split columns into their own arrays for sorting
				foreach($TableData as $key => $row)
				{
					if($key < 0)
						continue;
					
					foreach($ColumnNames as $colName)
					{
						if(!isset($$colName))
							$$colName = array();
						$tmpArray = array();
						if(isset($row[$colName]))
							${$colName}[] = $row[$colName];
						/*$tmpArray[$key] = $row[$colName];
						${$colName}[] = $tmpArray;*/
					}
				}
				// Determine which column to sort on and direction
				
				// Sort the data with volume descending, edition ascending
				// Add $data as the last parameter, to sort by the common key
				//array_multisort($volume, SORT_DESC, $edition, SORT_ASC, $data);
					unset($TableData[0]);
				if(count($TableData) != 0)
				{
					//to get the sort columns to sort correctly, use the lower case versions to run the array sort
					$SortColLower = array_map('strtolower', $$SortCol);
					if($SortDir == 'desc')
						$SortDirection = SORT_DESC;
					else
						$SortDirection = SORT_ASC;
					array_multisort($SortColLower, $SortDirection, $TableData, $SortDirection);
					array_unshift($TableData,""); // padding $TableData[0] null value was lost on the multisort... re-adding it
					
				}
			}
			
			// create user selection tool to determine results per page if applicable
			$ResultsPerPage = $this->DefaultResultsPerPage;
			$PageResultsHTML = "";
			if($DisplayPaging && $this->UserControlsResultsPerPage)
			{
				$ResultsPerPage = isset($_GET['ResultsPerPage']) ? trim($_GET['ResultsPerPage']) : $this->DefaultResultsPerPage;
				if((!isInt($ResultsPerPage) || $ResultsPerPage < 1) && $ResultsPerPage != "ALL")
					$ResultsPerPage = $this->DefaultResultsPerPage;
				$ResultsPerPageOptions = array();
				$ResultsPerPageOptions[] = "ALL";
				for($i=5; $i<=$this->MaxResultsPerPage; $i+=5)
					$ResultsPerPageOptions[] = $i;
				if(!in_array($ResultsPerPage,$ResultsPerPageOptions))
				{
					$ResultsPerPageOptions[] = $ResultsPerPage;
					sort($ResultsPerPageOptions);
				}
				$PageResultsHTML .= $this->ResultDesc.' Per Page:
							   <select id="ResultsPerPage" onchange="window.location.href = window.location.pathname+\''.Array2GetString($_GET, array('ResultsPerPage', 'Page')).'ResultsPerPage=\'+getElementById(\'ResultsPerPage\').value;">'; 
				foreach($ResultsPerPageOptions as $Option)
					$PageResultsHTML .= '<option value="'.$Option.'" '.(($ResultsPerPage==$Option)?'selected="selected"':'').'>'.$Option.'</option>';
				$PageResultsHTML .= '</select>';
			}
			
			if($DisplayPaging && $ResultsPerPage != "ALL")
			{
				$NumberOfPages = ceil($TableDataSize/$ResultsPerPage);
				$CurrentPage = isset($_GET['Page']) ? trim($_GET['Page']) : 1;
				if($CurrentPage < 1 || !isInt($CurrentPage))
					$CurrentPage = 1;
				if($CurrentPage > $NumberOfPages)
					$CurrentPage = $NumberOfPages;
				$ResultCountStart = ($ResultsPerPage * $CurrentPage) - $ResultsPerPage + 1;
				$ResultCountEnd = ($CurrentPage == $NumberOfPages) ? $TableDataSize : ($ResultsPerPage * $CurrentPage);
				$PageMidpointBuffer = floor($this->PagesShown/2.0);
				$PageMidpoint = ceil($this->PagesShown/2.0);
				if($CurrentPage > $PageMidpoint)
				{
					$LastSeenPage = $CurrentPage + $PageMidpointBuffer;
					if($LastSeenPage > $NumberOfPages)
						$LastSeenPage = $NumberOfPages;
					$FirstSeenPage = $LastSeenPage - ($this->PagesShown - 1);
					if($FirstSeenPage < 1)
						$FirstSeenPage = 1;
				}
				else
				{
					$LastSeenPage = $this->PagesShown;
					if($LastSeenPage > $NumberOfPages)
						$LastSeenPage = $NumberOfPages;
					$FirstSeenPage = 1;
				}
				
				// generate page link string
				$PageLinkArray = array();
				$PageGETString = Array2GetString($_GET, array('Page'));
				$PageLinkArray[] = ($CurrentPage > 1) ? '<span style="cursor:pointer" onmouseover="this.style.textDecoration=\'underline\';" onmouseout="this.style.textDecoration=\'none\';" onclick="window.location.href = window.location.pathname+\''.$PageGETString.'Page='.($CurrentPage-1).'\';">&#171;<span style="font-size:12px;"> Prev</span></span>' : '&nbsp;&nbsp;&nbsp;&nbsp;';
				$PageLoopStart = ($FirstSeenPage == 2) ? 1 : $FirstSeenPage;
				$PageLoopEnd = ($LastSeenPage == ($NumberOfPages - 1)) ? $NumberOfPages : $LastSeenPage;
				if($PageLoopStart == $FirstSeenPage && $PageLoopStart > 2)
				{
					$PageLinkArray[] = '<span style="cursor:pointer" onmouseover="this.style.textDecoration=\'underline\';" onmouseout="this.style.textDecoration=\'none\';" onclick="window.location.href = window.location.pathname+\''.$PageGETString.'Page=1\';">1</span>';
					$PageLinkArray[] = '<span style="font-size:14px;">...</span>';
				}
				for($i=$PageLoopStart; $i<=$PageLoopEnd; $i++)
					$PageLinkArray[] = (($CurrentPage == $i)?'<span class="PagingAndSortingSelectedPageSpan selectedPage" style="font-weight:bold">'.$i.'</span>':'<span style="cursor:pointer" onmouseover="this.style.textDecoration=\'underline\';" onmouseout="this.style.textDecoration=\'none\';" onclick="window.location.href = window.location.pathname+\''.$PageGETString.'Page='.$i.'\';">'.$i.'</span>');
				if($PageLoopEnd == $LastSeenPage && $PageLoopEnd < ($NumberOfPages-1))
				{
					$PageLinkArray[] = '<span style="font-size:14px;">...</span>';
					$PageLinkArray[] = '<span style="cursor:pointer" onmouseover="this.style.textDecoration=\'underline\';" onmouseout="this.style.textDecoration=\'none\';" onclick="window.location.href = window.location.pathname+\''.$PageGETString.'Page='.$NumberOfPages.'\';">'.$NumberOfPages.'</span>';
				}
				$PageLinkArray[] = ($CurrentPage < $NumberOfPages) ? '<span style="cursor:pointer" onmouseover="this.style.textDecoration=\'underline\';" onmouseout="this.style.textDecoration=\'none\';" onclick="window.location.href = window.location.pathname+\''.$PageGETString.'Page='.($CurrentPage+1).'\';"><span style="font-size:12px;">Next </span>&#187;</span>' : '&nbsp;&nbsp;&nbsp;&nbsp;';
				$PageLinkString = (sizeof($PageLinkArray) <= 3) ? "" : implode(' ',$PageLinkArray);
			
			}
			
			if($DisplayPaging)
			{
				if($TableDataSize < $ItemNumberToEndWith)
					$ItemNumberToEndWith = $TableDataSize;
				if($ResultsPerPage == 'ALL')
					$NumberOfPages = 1;
				else
					$NumberOfPages = ceil($TableDataSize/$ResultsPerPage);
			}
			
			//Run the SQL statement
			if((($this->SearchWithin || !$SortableOnDatabase)) && (sizeof($this->UniqueIdentifierArray) > 0 || sizeof($this->LargeItemsArray) > 0))
			{
				//get all of the primary keys of the valid rows
				$i = 0;
				$stringOfPKData = "";
				foreach($TableData as $j => $col)
				{
					if(gettype($col) != 'array')
					{
						$i++;
						continue;
					}
					if(isset($ItemNumberToBeginWith) && isset($ItemNumberToEndWith))
						if($i < $ItemNumberToBeginWith || $i > ($ItemNumberToEndWith))
						{
							$i++;
							continue;
						}
					$whereClausePiece = "(";
					if(isset($col['PK']))
					foreach($col['PK'] as $j => $PK)
					{
						$whereClausePiece .= $PK." = '".addslashes($col['PKValue'][$j])."'";
						$whereClausePiece .= " and ";
					}
					if(strlen($whereClausePiece) > 2)
						$stringOfPKData .= " ".substr($whereClausePiece, 0, -4).") OR ";
					$i++;
				}
				if(strlen($stringOfPKData) <= 6)
					$stringOfPKData = "";
				else
					$stringOfPKData = "(".substr($stringOfPKData, 0, -3).") AND ";
					//parse the old SQL to include only the necessary rows
				$modifiedSQL = replace_first('from', 'SPLITHERE', $this->SQL);
				$modifiedSQL = replace_first('FROM', 'SPLITHERE', $modifiedSQL);
				$SQLArray = explode('SPLITHERE', $modifiedSQL);
				$newSQL = "select ";
				foreach($this->UniqueIdentifierArray as $PKField)
					$newSQL .= $PKField.", ";
				foreach($this->LargeItemsArray as $LargeItemField)
				{
					$newSQL .= $LargeItemField.", ";
				}
				
				//if there is nothing to select, then don't finish the SQL
				if($newSQL == 'select ')
					break;
				$newSQL = substr($newSQL, 0, -2)." from ".$SQLArray[1];
				$modifiedSQL = replace_first('where', 'SPLITHERE', $newSQL);
				$modifiedSQL = replace_first('WHERE', 'SPLITHERE', $modifiedSQL);
				$SQLArray = explode('SPLITHERE', $modifiedSQL);
				
				if(substr_count($this->SQL, 'where') == 0 && substr_count($this->SQL, 'WHERE') == 0 && $stringOfPKData != "")
					$stringOfPKData = substr($stringOfPKData, 0, -4);
				$newSQL = $SQLArray[0]." WHERE ".$stringOfPKData.(isset($SQLArray[1])?$SQLArray[1]:"");
				$DebugMessage .= ($this->DebuggingMode)?"SECOND SQL BEING RUN::$newSQL<br/>":"";
				if($this->SQLConnectionType == "mysqli")
					$SQLResult = $this->SQLConnection->query($newSQL);
				elseif($this->SQLConnectionType == "mssql")
					$SQLResult = mssql_query($newSQL, $this->SQLConnection);
				else
					$DebugMessage .= ($this->DebuggingMode)?"DEBUGGING MESSAGE::Invalid SQL Connection Type - cannot run second SQL select.<br/>":"";
				$i=1;
				while($SQLRow = $SQLResult->fetch_array(MYSQLI_ASSOC))
				{
					$i=0;
					foreach($TableData as $j => $TableColumn)
					{
						if(!isset($TableColumn['PKValue']))
						{
							$i++;
							continue;
						}
						if(gettype($TableColumn['PKValue']) != 'array')
						{
							$i++;
							continue;
						}
						$foundMatch = true;
						foreach($this->UniqueIdentifierArray as $k => $val)
						{
							if($SQLRow[$val] != $TableColumn['PKValue'][$k])
								$foundMatch = false;
						}
						if($foundMatch)
						{
							$TableData[$i] = $this->FormattingFunction($SQLRow, $TableData[$i]);
							break;
						}
						$i++;
					}					
				}
			}
			
			// generate results table
			$outputHTML .= $this->TableDefHTML;
			if($this->TitleHTML != "")
			{
				$outputHTML .= '
					<tr>
						<td colspan="2" class="PagingAndSortingTitleTD tableTitle" >'.$this->TitleHTML.'</td>
					</tr>';
			}
			if($DisplayPaging || $this->SearchWithin)
			{
				$outputHTML .= '<tr>';
				if($DisplayPaging)
					$outputHTML .= '<td align="left" valign="'.(($this->UserControlLines==2)?'top':'middle').'">'.(($TableDataSize>0)?$this->ResultDesc.' '.$ItemNumberToBeginWith.' - '.$ItemNumberToEndWith.' of '.$TableDataSize.(($this->UserControlLines==2)?'<br />':' ').'<span style="font-size:12px;">(Page '.$CurrentPage.' of '.$NumberOfPages.')</span>':'&nbsp;').'</td>';
				$outputHTML .= '<td colspan="2" align="right" valign="top">';
				if($this->UserControlsResultsPerPage)
					$outputHTML .= $PageResultsHTML;
				if($this->SearchWithin)	
					$outputHTML .= $SearchWithinHTML;
				$outputHTML .= '</td></tr>';
			}
			$outputHTML .= '<tr>
								<td colspan="3">
									<table class="PagingAndSortingContentTable" border="0" cellspacing="0" cellpadding="6" width="100%">';
			// display table header if applicable
			if($DisplayTableHeader)
			{
				$outputHTML .= '<tr>';
				foreach($this->ColumnArray as $v)
				{
					$thisOnClick = "";
					$extraStyle = "cursor:auto";
					if($v['DoNotDisplayHeader'])
					{
						$outputHTML .= '<th/>';
						continue;
					}
					if($DisplaySorting && ($TableDataSize > 0) && $v['Sortable'])
					{
						$CurrentSortColIndexArray = $SortCol;
						$SortGETString = Array2GetString($_GET, array('SortCol','SortDir','Page'));
						$thisOnClick = 'onclick="window.location.href = window.location.pathname+\''.$SortGETString.'SortCol='.$v['DisplayName'].'&Page=1';
						if($SortCol == $v['DisplayName'] && $SortDir == "asc")
							$thisOnClick .= "&SortDir=desc";
						else
						{
							$thisOnClick .= "&SortDir=asc";
						}
						$thisOnClick .= '\';"';
						$extraStyle = " cursor:pointer;";
					}
					$outputHTML .= '<th style="text-align:left;'.$extraStyle.'" '.$thisOnClick.'>'.$v['DisplayName'];
					if($DisplaySorting && $TableDataSize > 0 && $v['Sortable'])
					{
						if($CurrentSortColIndexArray == $v['DisplayName'])
						{
							if($SortDir == "desc")
								$outputHTML .= ' <img src="'.$this->SortArrowLocation.'/sortArrowDown.gif" style="display:inline" />';
							else
								$outputHTML .= ' <img src="'.$this->SortArrowLocation.'/sortArrowUp.gif" style="display:inline" />';
						}					
					}
					$outputHTML .= '</th>';
				}
				$outputHTML .= '</tr>';
			}
			// display table body
			
			if($TableDataSize < 1)
				$outputHTML .= '<tr><td colspan="'.count($this->ColumnArray).'" width="100%"><div class="PagingAndSortingNoMatchesDiv">No matches found</div></td>';
			else
			{
				$RowColor = false;
				$i=0;
				foreach($TableData as $j => $col)
				{	
					if(gettype($col) != 'array')
					{
						$i++;
						continue;
					}
					$outputHTML .= '<tr '.($RowColor?'class="PagingAndSortingRowColor rowColor"':'').'>';
					if(($this->SearchWithin || !$SortableOnDatabase) && $ResultsPerPage != 'ALL')
					{
						if($i < $ItemNumberToBeginWith || $i > ($ItemNumberToEndWith))
						{
							$i++;
							continue;
						}
					}
					foreach($this->ColumnArray as $k => $value)
					{
						$outputHTML .= '<td '.(isset($this->ColumnArray[$k]['DisplayWidth'])?'width="'.$this->ColumnArray[$k]['DisplayWidth'].'"':'').' '.(($col["TRClick"]!="" && !in_array($this->ColumnArray[$k]["DisplayName"],$this->TRClickExcludeColumns))?'onclick="'.$col["TRClick"].'"':'').' '.(array_key_exists($this->ColumnArray[$k]["DisplayName"],$this->ColumnJustifications)?'align="'.$this->ColumnJustifications[$this->ColumnArray[$k]["DisplayName"]].'"':'').'>'.(($col[$this->ColumnArray[$k]['DisplayName']]=="")?'&nbsp;':$col[$this->ColumnArray[$k]['DisplayName']]).'</td>';
					}
					$outputHTML .= '</tr>';
					$RowColor = !$RowColor;
					$i++;
					if($i < sizeof($TableData))
						$outputHTML .= $this->HTMLBetweenRows;
				}
				$outputHTML .= '	   </table>
									</td>
								</tr>';
			}
			if($CurrentPage > 1 || $NumberOfPages > 1)
			{
				$outputHTML .= '
					  <tr>
						<td colspan="2" align="left">&nbsp;</td>
						<td align="right">'.$PageLinkString.'</td>
					  </tr>';
			}
			$outputHTML .= '</table>';
			return returnTextAndDebug($outputHTML, $DebugMessage);
		}
	
/*		function pagingAndSorting($ColumnData, $LargeItemsArray, $UniqueIdentifierArray, $SQL, $SQLConnection, $TitleHTML, $TableDefHTML, $DefaultSortCol, $DefaultSortDir, $DefaultResultsPerPage, $UserControlsResultsPerPage, $UserControlLines, $SearchWithin, $MaxResultsPerPage, $PagesShown, $ResultDesc, $HTMLBetweenRows="", $DebuggingMode = false)
		{
	
			$outputHTML = "";
			$DebugMessage = "";
			require_once('dataVerification.php');
			
			if($SearchWithin)
			{
				$DebugMessage .= ($DebuggingMode)?"DEBUGGING MESSAGE::Search Turned On - SearchWithin is true.<br/>":"";
			}
			else
			{
				$DebugMessage .= ($DebuggingMode)?"DEBUGGING MESSAGE::Search Turned Off - SearchWithin is false.<br/>":"";
			}
			
			//Determine if paging or sorting is going on
			if($DefaultResultsPerPage <= 0)
			{
				$DisplayPaging = false;
				$DebugMessage .= ($DebuggingMode)?"DEBUGGING MESSAGE::Paging Turned Off - DefaultResultsPerPage less than 0.<br/>":"";
			}
			else if($DefaultResultsPerPage == "ALL")
			{
				$DisplayPaging = false;
				$DebugMessage .= ($DebuggingMode)?"DEBUGGING MESSAGE::Paging Turned Off - All DefaultResultsPerPage requested.<br/>":"";
			}
			else if($PagesShown < 3)
			{
				$DisplayPaging = false;
				$DebugMessage .= ($DebuggingMode)?"DEBUGGING MESSAGE::Paging Turned Off - PagesShown is less than 3.<br/>":"";
			}
			else if(($PagesShown%2) != 1)
			{
				$DisplayPaging = false;
				$DebugMessage .= ($DebuggingMode)?"DEBUGGING MESSAGE::Paging Turned Off - PagesShown is an even number.<br/>":"";
			}
			else if($MaxResultsPerPage < 5)
			{
				$DisplayPaging = false;
				$DebugMessage .= ($DebuggingMode)?"DEBUGGING MESSAGE::Paging Turned Off - MaxResultsPerPage is less than 5.<br/>":"";
			}
			else if(($MaxResultsPerPage%5) != 0)
			{
				$DisplayPaging = false;
				$DebugMessage .= ($DebuggingMode)?"DEBUGGING MESSAGE::Paging Turned Off - MaxResultsPerPage is not divisible by 5.<br/>":"";
			}
			else if($ResultDesc == '')
			{
				$DisplayPaging = false;
				$DebugMessage .= ($DebuggingMode)?"DEBUGGING MESSAGE::Paging Turned Off - ResultDesc is not set.<br/>":"";
			}
			else
			{
				$DisplayPaging = true;
				$DebugMessage .= ($DebuggingMode)?"DEBUGGING MESSAGE::Paging Turned On - All conditions met.<br/>":"";
			}
			
			$DisplayTableHeader = false;
			foreach($ColumnData as $SingleColumn)
			{
				if(!isset($SingleColumn['DoNotDisplayHeader']))
					$DisplayTableHeader = true;
			}
			if($DisplayTableHeader)
				$DebugMessage .= ($DebuggingMode)?"DEBUGGING MESSAGE::Table Header Display Turned On.<br/>":"";
			else
				$DebugMessage .= ($DebuggingMode)?"DEBUGGING MESSAGE::Table Header Display Turned Off.<br/>":"";
			
			if($DefaultSortCol == '')
			{
				$DisplaySorting = false;
				$DebugMessage .= ($DebuggingMode)?"DEBUGGING MESSAGE::Sorting Turned Off - Default Sort Column not set.<br/>":"";
			}
			else if($DefaultSortDir != 'asc' && $DefaultSortDir != 'desc')
			{
				$DisplaySorting = false;
				$DebugMessage .= ($DebuggingMode)?"DEBUGGING MESSAGE::Sorting Turned Off - Default Sort Direction not set correctly.<br/>":"";
			}
			else if(!$DisplayTableHeader)
			{
				$DisplaySorting = false;
				$DebugMessage .= ($DebuggingMode)?"DEBUGGING MESSAGE::Sorting Turned Off - Table Header turned off.<br/>":"";
			}
			else
			{
				$DisplaySorting = true;
				$DebugMessage .= ($DebuggingMode)?"DEBUGGING MESSAGE::Sorting Turned On - All conditions met.<br/>":"";
			}
		
			//Determine if any sortable fields are sortable by something other than a database field
			$SortableOnDatabase = true;
			if($DisplaySorting)
			{
				foreach($ColumnData as $SingleColumnData)
				{
					if($SingleColumnData['Sortable'] && !isset($SingleColumnData['SQLColumn']))
						$SortableOnDatabase = false;
				}
			}
			
			if($SortableOnDatabase)
				$DebugMessage .= ($DebuggingMode)?"DEBUGGING MESSAGE::The data is sortable on the database.<br/>":"";
			else
				$DebugMessage .= ($DebuggingMode)?"DEBUGGING MESSAGE::The data is not sortable on the database.<br/>":"";
			
			//Determine sorting information if applicable
			if($DisplaySorting)
			{
				// Determine which column to sort on and direction
				$SortCol = isset($_GET['SortCol']) ? $_GET['SortCol'] : $DefaultSortCol;
				$SortDir = isset($_GET['SortDir']) ? $_GET['SortDir'] : $DefaultSortDir;
				$ColumnFound = false;
				foreach($ColumnData as $k => $SingleColumnData)
				{
					if($SortCol == $k)
						$ColumnFound = true;
				}
				if(!$ColumnFound)
					$SortCol = $DefaultSortCol;
				if($SortDir != "asc" && $SortDir != "desc")
					$SortDir = $DefaultSortDir;
			}
			
			$CurrentPage = 1;
			$NumberOfPages = 1;
			
			
			//Get a count of the data in the dataset
			
			//only explode on the first "from"
			$modifiedSQL = replace_first('from', 'SPLITHERE', $SQL);
			$modifiedSQL = replace_first('FROM', 'SPLITHERE', $modifiedSQL);
			$SQLArray = explode('SPLITHERE', $modifiedSQL);
			$countSQL = "select count(1) from ".$SQLArray[1];
			$SQLResult = $SQLConnection->query($countSQL);
			$SQLRow = $SQLResult->fetch_array(MYSQLI_NUM);
			$TableDataSize = $SQLRow[0];
	
			//Determine paging information if applicable
			if($DisplayPaging)
			{
				$ResultsPerPage = isset($_GET['ResultsPerPage']) ? trim($_GET['ResultsPerPage']) : $DefaultResultsPerPage;
				if((!isInt($ResultsPerPage) || $ResultsPerPage < 1) && $ResultsPerPage != "ALL")
					$ResultsPerPage = $DefaultResultsPerPage;
				$CurrentPage = isset($_GET['Page']) ? trim($_GET['Page']) : 1;
				$ItemNumberToBeginWith = ($CurrentPage-1)*$ResultsPerPage + 1;
				$ItemNumberToEndWith = $ResultsPerPage+$ItemNumberToBeginWith-1;
				if($ResultsPerPage == 'ALL')
					$ItemNumberToEndWith = $TableDataSize;
			}
			else
				$ResultsPerPage = "ALL";
			
			//Add to the SQL statement if necessary
			$newSQL = $SQL;
			if(!$SearchWithin && $SortableOnDatabase)
			{
				//This is the case where we can limit the results inside of the SQL
				
				//Order by
				if($DisplaySorting)
				{
					$MySQLSortCol = $ColumnData[$SortCol]['SQLColumn'];
					$newSQL .= " order by $MySQLSortCol $SortDir ";
				}
				//Limit
				if($ResultsPerPage == "ALL")
					$ResultsPerPage = $TableDataSize;
				if($DisplayPaging)
					$newSQL .= " limit ".($ItemNumberToBeginWith-1).", $ResultsPerPage ";
			}
			//Run the SQL statement
			$SQLResult = $SQLConnection->query($newSQL);
			
			//run each SQL row through the formatting function
			$TableData = array();
			$i=1;
			//get the data array
			while($SQLRow = $SQLResult->fetch_array(MYSQLI_ASSOC))
			{
				if(!function_exists('pagingAndSortingFormattingFunction'))
				{
					$DebugMessage .= ($DebuggingMode)?"DEBUGGING MESSAGE::pagingAndSortingFormattingFunction is not set - using default.<br/>":"";
					function pagingAndSortingFormattingFunction($SQLRow, $tableArray = array())
					{
						$returnArray = $tableArray;
						foreach($SQLRow as $index => $val)
						{
							$returnArray[$index] = $val;
						}
						return $returnArray;
					}
				}
				$TableData[$i] = pagingAndSortingFormattingFunction($SQLRow);
				foreach($UniqueIdentifierArray as $j => $val)
				{
					$TableData[$i]['PK'][$j] = $val;
					$TableData[$i]['PKValue'][$j] = $SQLRow[$val];
				}
				$i++;
			}
			
			if(!$SearchWithin && $SortableOnDatabase)
			{
				array_unshift($TableData,"");
			}
			//get the size of the dataset
			if($TableDataSize < 1)
			{
				return returnTextAndDebug('There are no '.$ResultDesc.' to display.', $DebugMessage);
			}
			
			$ResultCountStart = 1;
			$ResultCountEnd = $TableDataSize;
			
			//implement search logic if applicable
			if($SearchWithin)
			{
				$SearchWithinLimit = isset($_GET['SearchWithinLimit']) ? str_replace('&quot;','"',stripslashes(trim($_GET['SearchWithinLimit']))) : '';
				if($SearchWithinLimit != "")
				{
					$QuoteLocations = array();
					for($i=0; $i<strlen($SearchWithinLimit); $i++)
						if($SearchWithinLimit{$i} == '"')
							$QuoteLocations[] = $i;
					if(sizeof($QuoteLocations) > 0 && sizeof($QuoteLocations)%2 == 1)
					{
						$SearchWithinLimit = substr($SearchWithinLimit,0,$QuoteLocations[sizeof($QuoteLocations)-1]).substr($SearchWithinLimit,$QuoteLocations[sizeof($QuoteLocations)-1]+1);
						unset($QuoteLocations[sizeof($QuoteLocations)-1]);
					}
				}
				$SearchWithinHTML .= (($UserControlLines==2)?'<br />':' ').'Search: <input id="SearchWithinLimit" value="'.str_replace('"','&quot;',$SearchWithinLimit).'" size="15" maxlength="255" />
									  <input type="button" value="Go" onclick="window.location.href = window.location.pathname+\''.Array2GetString($_GET, array('SearchWithinLimit','Page')).'Page=1&SearchWithinLimit=\'+encodeURIComponent(document.getElementById(\'SearchWithinLimit\').value);">
									  <script language="javascript">
									  	document.getElementById(\'SearchWithinLimit\').onkeydown = function(e) {submitSearchWithinLimit(e);};
										function submitSearchWithinLimit(e)
										{
											if(e.keyCode == 13) // enter key
												window.location.href = window.location.pathname+\''.Array2GetString($_GET, array('SearchWithinLimit','Page')).'Page=1&SearchWithinLimit=\'+encodeURIComponent(document.getElementById(\'SearchWithinLimit\').value);
										}
									  </script>';
				if($SearchWithinLimit != '')
				{	
					$SearchWithinLimit = strtoupper($SearchWithinLimit);			
					$SearchWithinLimit = str_replace(" AND "," ",$SearchWithinLimit);
					$SearchWords = explode(" ",$SearchWithinLimit);
					$InQuotes = false;
					$SearchWithinLimit = "";
					foreach($SearchWords as $i => $Word)
					{
						if(!isset($SearchWords[$i+1]))
						{
							$SearchWithinLimit .= $Word;
							break;
						}
						if(substr($Word,0,1) == '"')
							$InQuotes = true;
						if(substr($Word,strlen($Word)-1) == '"')
							$InQuotes = false;
						if(!$InQuotes && $Word != "OR")
							$SearchWithinLimit .= $Word." AND ";
						else
							$SearchWithinLimit .= $Word." ";
					}
					$SearchWithinLimit = str_replace('"','',$SearchWithinLimit);
					$SearchWithinLimit = str_replace('AND OR','OR',$SearchWithinLimit);
					
					//echo $SearchWithinLimit;
					
					$SearchBlocks = explode(" OR ",$SearchWithinLimit);
					
					for($i=1; $i<=$TableDataSize; $i++)
					{
						$KeepRow = false;
						foreach($SearchBlocks as $Block)
						{
							$BlockPieces = explode(" AND ",$Block);
							$BlockPiecesSize = sizeof($BlockPieces);
							$PiecesMatched = array();
							foreach($BlockPieces as $PieceIndex => $Piece)
							{
								foreach($TableData[$i] as $DataElement)
								{
									$NoHTMLElement = preg_replace("/(<\/?)(\w+)([^>]*>)/e","",$DataElement);
									if(substr_count(strtolower($NoHTMLElement),strtolower($Piece)) > 0)
									{
										if(!in_array($PieceIndex,$PiecesMatched))
											$PiecesMatched[] = $PieceIndex;
									}
								}
							}	
							if($BlockPiecesSize == sizeof($PiecesMatched))
							{
								$KeepRow = true;
								break;
							}
						}
						if(!$KeepRow)
							unset($TableData[$i]);				
					}
					$TableDataSize = sizeof($TableData);
				}			
			}
			else
				$SearchWithinHTML = "";
			
			// determine sorting information if applicable
			if(($SearchWithin || !$SortableOnDatabase) && $DisplaySorting)
			{
				// Obtain a list of columns
				$ColumnNames = array();
				foreach($ColumnData as $colName => $colVal)
				{
					$ColumnNames[] = $colName;
					$$colName = array();
				}
				
				// Split columns into their own arrays for sorting
				foreach($TableData as $key => $row)
				{
					if($key < 0)
						continue;
					foreach($ColumnNames as $colName)
					{
						$tmpArray = $$colName;
						$tmpArray[$key] = $row[$colName];
						$$colName = $tmpArray;
					}
				}
				
				// Determine which column to sort on and direction
				
				// Sort the data with volume descending, edition ascending
				// Add $data as the last parameter, to sort by the common key
				//array_multisort($volume, SORT_DESC, $edition, SORT_ASC, $data);
				unset($TableData[0]);
				if($SortDir == "desc")
					array_multisort($$SortCol, SORT_DESC, $TableData);
				else
					array_multisort($$SortCol, SORT_ASC, $TableData);
				array_unshift($TableData,""); // padding $TableData[0] null value was lost on the multisort... readding it
			}
			
			// create user selection tool to determine results per page if applicable
			$ResultsPerPage = $DefaultResultsPerPage;
			$PageResultsHTML = "";
			if($DisplayPaging && $UserControlsResultsPerPage)
			{
				$ResultsPerPage = isset($_GET['ResultsPerPage']) ? trim($_GET['ResultsPerPage']) : $DefaultResultsPerPage;
				if((!isInt($ResultsPerPage) || $ResultsPerPage < 1) && $ResultsPerPage != "ALL")
					$ResultsPerPage = $DefaultResultsPerPage;
				$ResultsPerPageOptions = array();
				$ResultsPerPageOptions[] = "ALL";
				for($i=5; $i<=$MaxResultsPerPage; $i+=5)
					$ResultsPerPageOptions[] = $i;
				if(!in_array($ResultsPerPage,$ResultsPerPageOptions))
				{
					$ResultsPerPageOptions[] = $ResultsPerPage;
					sort($ResultsPerPageOptions);
				}
				$PageResultsHTML .= $ResultDesc.' Per Page:
							   <select id="ResultsPerPage" onchange="window.location.href = window.location.pathname+\''.Array2GetString($_GET, array('ResultsPerPage', 'Page')).'ResultsPerPage=\'+getElementById(\'ResultsPerPage\').value;">'; 
				foreach($ResultsPerPageOptions as $Option)
					$PageResultsHTML .= '<option value="'.$Option.'" '.(($ResultsPerPage==$Option)?'selected="selected"':'').'>'.$Option.'</option>';
				$PageResultsHTML .= '</select>';
			}
			
			if($DisplayPaging && $ResultsPerPage != "ALL")
			{
				$NumberOfPages = ceil($TableDataSize/$ResultsPerPage);
				$CurrentPage = isset($_GET['Page']) ? trim($_GET['Page']) : 1;
				if($CurrentPage < 1 || !isInt($CurrentPage))
					$CurrentPage = 1;
				if($CurrentPage > $NumberOfPages)
					$CurrentPage = $NumberOfPages;
				$ResultCountStart = ($ResultsPerPage * $CurrentPage) - $ResultsPerPage + 1;
				$ResultCountEnd = ($CurrentPage == $NumberOfPages) ? $TableDataSize : ($ResultsPerPage * $CurrentPage);
				$PageMidpointBuffer = floor($PagesShown/2.0);
				$PageMidpoint = ceil($PagesShown/2.0);
				if($CurrentPage > $PageMidpoint)
				{
					$LastSeenPage = $CurrentPage + $PageMidpointBuffer;
					if($LastSeenPage > $NumberOfPages)
						$LastSeenPage = $NumberOfPages;
					$FirstSeenPage = $LastSeenPage - ($PagesShown - 1);
					if($FirstSeenPage < 1)
						$FirstSeenPage = 1;
				}
				else
				{
					$LastSeenPage = $PagesShown;
					if($LastSeenPage > $NumberOfPages)
						$LastSeenPage = $NumberOfPages;
					$FirstSeenPage = 1;
				}
				
				// generate page link string
				$PageLinkArray = array();
				$PageGETString = Array2GetString($_GET, array('Page'));
				$PageLinkArray[] = ($CurrentPage > 1) ? '<span style="cursor:pointer" onmouseover="this.style.textDecoration=\'underline\';" onmouseout="this.style.textDecoration=\'none\';" onclick="window.location.href = window.location.pathname+\''.$PageGETString.'Page='.($CurrentPage-1).'\';">&#171;<span style="font-size:12px;"> Prev</span></span>' : '&nbsp;&nbsp;&nbsp;&nbsp;';
				$PageLoopStart = ($FirstSeenPage == 2) ? 1 : $FirstSeenPage;
				$PageLoopEnd = ($LastSeenPage == ($NumberOfPages - 1)) ? $NumberOfPages : $LastSeenPage;
				if($PageLoopStart == $FirstSeenPage && $PageLoopStart > 2)
				{
					$PageLinkArray[] = '<span style="cursor:pointer" onmouseover="this.style.textDecoration=\'underline\';" onmouseout="this.style.textDecoration=\'none\';" onclick="window.location.href = window.location.pathname+\''.$PageGETString.'Page=1\';">1</span>';
					$PageLinkArray[] = '<span style="font-size:14px;">...</span>';
				}
				for($i=$PageLoopStart; $i<=$PageLoopEnd; $i++)
					$PageLinkArray[] = (($CurrentPage == $i)?'<span class="PagingAndSortingSelectedPageSpan selectedPage" style="font-weight:bold">'.$i.'</span>':'<span style="cursor:pointer" onmouseover="this.style.textDecoration=\'underline\';" onmouseout="this.style.textDecoration=\'none\';" onclick="window.location.href = window.location.pathname+\''.$PageGETString.'Page='.$i.'\';">'.$i.'</span>');
				if($PageLoopEnd == $LastSeenPage && $PageLoopEnd < ($NumberOfPages-1))
				{
					$PageLinkArray[] = '<span style="font-size:14px;">...</span>';
					$PageLinkArray[] = '<span style="cursor:pointer" onmouseover="this.style.textDecoration=\'underline\';" onmouseout="this.style.textDecoration=\'none\';" onclick="window.location.href = window.location.pathname+\''.$PageGETString.'Page='.$NumberOfPages.'\';">'.$NumberOfPages.'</span>';
				}
				$PageLinkArray[] = ($CurrentPage < $NumberOfPages) ? '<span style="cursor:pointer" onmouseover="this.style.textDecoration=\'underline\';" onmouseout="this.style.textDecoration=\'none\';" onclick="window.location.href = window.location.pathname+\''.$PageGETString.'Page='.($CurrentPage+1).'\';"><span style="font-size:12px;">Next </span>&#187;</span>' : '&nbsp;&nbsp;&nbsp;&nbsp;';
				$PageLinkString = (sizeof($PageLinkArray) <= 3) ? "" : implode(' ',$PageLinkArray);
			
			}
			
			if($DisplayPaging)
			{
				if($TableDataSize < $ItemNumberToEndWith)
					$ItemNumberToEndWith = $TableDataSize;
				if($ResultsPerPage == 'ALL')
					$NumberOfPages = 1;
				else
					$NumberOfPages = ceil($TableDataSize/$ResultsPerPage);
			}
			
			//Run the SQL statement
			if(($SearchWithin || !$SortableOnDatabase) && sizeof($LargeItemsArray) > 0)
			{
				//get all of the primary keys of the valid rows
				$i = 0;
				foreach($TableData as $j => $col)
				{
					if(gettype($col) != 'array')
					{
						$i++;
						continue;
					}
					if($i < $ItemNumberToBeginWith || $i > ($ItemNumberToEndWith))
					{
						$i++;
						continue;
					}
					$whereClausePiece = "(";
					foreach($col['PK'] as $j => $PK)
					{
						$whereClausePiece .= $PK." = '".$col['PKValue'][$j]."'";
						$whereClausePiece .= " and ";
					}		
					$stringOfPKData .= " ".substr($whereClausePiece, 0, -4).") OR ";
					$i++;
				}
				$stringOfPKData = "(".substr($stringOfPKData, 0, -3).") AND ";
				//parse the old SQL to include only the necessary rows
				$modifiedSQL = replace_first('from', 'SPLITHERE', $SQL);
				$modifiedSQL = replace_first('FROM', 'SPLITHERE', $modifiedSQL);
				$SQLArray = explode('SPLITHERE', $modifiedSQL);
				$newSQL = "select ";
				foreach($UniqueIdentifierArray as $PKField)
					$newSQL .= $PKField.", ";
				foreach($LargeItemsArray as $LargeItemField)
				{
					$newSQL .= $ColumnData[$LargeItemField]['SQLColumn'].", ";
				
				}
				
				$newSQL = substr($newSQL, 0, -2)." from ".$SQLArray[1];
				
				$modifiedSQL = replace_first('where', 'SPLITHERE', $newSQL);
				$modifiedSQL = replace_first('WHERE', 'SPLITHERE', $modifiedSQL);
				$SQLArray = explode('SPLITHERE', $modifiedSQL);
				
				if(substr_count($SQL, 'where') == 0 && substr_count($SQL, 'WHERE') == 0)
					$stringOfPKData = substr($stringOfPKData, 0, -4);
				$newSQL = $SQLArray[0]." WHERE ".$stringOfPKData.$SQLArray[1];
				$SQLResult = $SQLConnection->query($newSQL);
				//echo $newSQL;
				$i=1;
				while($SQLRow = $SQLResult->fetch_array(MYSQLI_ASSOC))
				{
					$i=0;
					foreach($TableData as $j => $TableColumn)
					{
						if(gettype($TableColumn['PKValue']) != 'array')
						{
							$i++;
							continue;
						}
						$foundMatch = true;
						foreach($UniqueIdentifierArray as $k => $val)
						{
							if($SQLRow[$val] != $TableColumn['PKValue'][$k])
								$foundMatch = false;
						}
						if($foundMatch)
						{
							$TableData[$i] = pagingAndSortingFormattingFunction($SQLRow, $TableData[$i]);
							break;
						}
						$i++;
					}					
				}
			}
			
			// generate results table
			$outputHTML .= $TableDefHTML;
			if($TitleHTML != "")
			{
				$outputHTML .= '
					<tr>
						<td colspan="2" class="PagingAndSortingTitleTD tableTitle" >'.$TitleHTML.'</td>
					</tr>';
			}
			if($DisplayPaging)
				$outputHTML .= '
					<tr>
						<td align="left" valign="'.(($UserControlLines==2)?'top':'middle').'">'.(($TableDataSize>0)?$ResultDesc.' '.$ItemNumberToBeginWith.' - '.$ItemNumberToEndWith.' of '.$TableDataSize.(($UserControlLines==2)?'<br />':' ').'<span style="font-size:12px;">(Page '.$CurrentPage.' of '.$NumberOfPages.')</span>':'&nbsp;').'</td>
						<td align="right" valign="top">'.$PageResultsHTML.' '.$SearchWithinHTML.'</td>
					</tr>';
			$outputHTML .= '<tr>
								<td colspan="2">
									<table class="PagingAndSortingContentTable" border="0" cellspacing="0" cellpadding="6" width="100%">';
			// display table header if applicable
			if($DisplayTableHeader)
			{
				$outputHTML .= '<tr>';
				foreach($ColumnData as $i => $v)
				{
					$thisOnClick = "";
					$extraStyle = "";
					if(isset($v['DoNotDisplayHeader']))
					{
						$outputHTML .= '<th/>';
						continue;
					}
					if($DisplaySorting && ($TableDataSize > 0) && $v['Sortable'])
					{
						$CurrentSortColIndexArray = $SortCol;
						$SortGETString = Array2GetString($_GET, array('SortCol','SortDir','Page'));
						$thisOnClick = 'onclick="window.location.href = window.location.pathname+\''.$SortGETString.'SortCol='.$i.'&Page=1';
						if($SortCol == $i && $SortDir == "asc")
							$thisOnClick .= "&SortDir=desc";
						else
							$thisOnClick .= "&SortDir=asc";
						$thisOnClick .= '\';"';
						$extraStyle = " cursor:pointer;";
					}
					$outputHTML .= '<th style="text-align:left;'.$extraStyle.'" '.$thisOnClick.'>'.$i;
					if($DisplaySorting && $TableDataSize > 0 && $v['Sortable'])
					{
						if($CurrentSortColIndexArray[0] == $i)
						{
							if($SortDir == "desc")
								$outputHTML .= ' <img src="/img/sortArrowDown.gif" style="display:inline" />';
							else
								$outputHTML .= ' <img src="/img/sortArrowUp.gif" style="display:inline" />';
						}					
					}
					$outputHTML .= '</th>';
				}
				$outputHTML .= '</tr>';
			}
			// display table body
			
			if($TableDataSize < 1)
				$outputHTML .= '<tr><td width="100%">No matches found</td>';
			else
			{
				$RowColor = false;
				$i=0;
				foreach($TableData as $j => $col)
				{	
					$outputHTML .= '<tr '.($RowColor?'class="PagingAndSortingRowColor rowColor"':'').'>';
					if(gettype($col) != 'array')
					{
						$i++;
						continue;
					}
					if($SearchWithin || !$SortableOnDatabase)
					{
						if($i < $ItemNumberToBeginWith || $i > ($ItemNumberToEndWith))
						{
							$i++;
							continue;
						}
					}
					
					foreach($ColumnData as $k => $value)
					{
						$outputHTML .= '<td '.(isset($ColumnData[$k]['DisplayWidth'])?'width="'.$ColumnData[$k]['DisplayWidth'].'"':'').'>'.(($col[$k]=="")?'&nbsp;':$col[$k]).'</td>';
					}
					$outputHTML .= '</tr>';
					$RowColor = !$RowColor;
					$i++;
					if($i < sizeof($TableData))
						$outputHTML .= $HTMLBetweenRows;
				}
				$outputHTML .= '	   </table>
									</td>
								</tr>';
			}
			if($CurrentPage > 1 || $NumberOfPages > 1)
			{
				$outputHTML .= '
					  <tr>
						<td align="left">&nbsp;</td>
						<td align="right">'.$PageLinkString.'</td>
					  </tr>';
			}
			$outputHTML .= '</table>';
			
			return returnTextAndDebug($outputHTML, $DebugMessage);
		}
*/
	}

	/*
	pagingAndSortingOld ~ Function that will add pages and/or sort table data in PHP
	
	$SQLData :: Basic SQL statement to get the information for the table data
	$SQLConnection :: Mysqli object to be passed in to run the query
	$FormattingFunction :: Name of the function that will convert the row data from the database to the fields that should show up on the screen
	$TableHeaderData :: Column headers for the generated table -- Required if using sort functionality
	$TitleHTML :: Optional param that will give the table a title
	$TableDefHTML :: Table start tag for results table -- Required for function use
	$TableColumnWidths :: Array of column widths -- Pass empty array if you don't need to specify
	$DefaultSortCol :: Column name of $TableData to sort by if no column is selected through $_GET -- Required if using sort
	$DefaultSortDir :: Default direction to sort $TableData by if no direction is selected through $_GET -- Required if using sort -- Accepted values are ASC and DESC
	$DefaultSortMethod :: Default method to sort by.  -- Required if using sort -- Accepted values are 0 (if no database column is associated to it) or the database column to associate it to
	$ExcludeSortCols :: Optional array of columns that should not be sortable (refer to them by display name given in $TableHeaderData)
	$DefaultResultsPerPage :: Number of table rows to display by default when not specified through $_GET -- Required for paging
	$UserControlsResultsPerPage :: Boolean that enables the user to select how many results there will be per page
	$UserControlLines :: Required when $UserControlsResultsPerPage = 1, valid values are 1 or 2... number of lines for user control bar
	$SearchWithin :: Boolean that enables the user to search within the given results
	$MaxResultsPerPage :: Maximum results that can be choosen per page -- Must be evenly divisible by 5 -- Required for paging
	$PagesShown :: The number of pages that are shown at a given time -- Must be an odd number >= 3 -- Required for paging
	$ResultDesc :: Description of what the table holds (ex: Items, Customers, Orders, etc.) -- Required for paging
	$PathToRoot :: Required string that tells the function where the root of the site is from the calling script
	*/
	
	
	function pagingAndSortingOld($TableData, $TableHeaderData, $TitleHTML, $TableDefHTML, $TableColumnWidths, $DefaultSortCol, $DefaultSortDir, $ExcludeSortCols, $DefaultResultsPerPage, $UserControlsResultsPerPage, $UserControlLines, $SearchWithin, $MaxResultsPerPage, $PagesShown, $ResultDesc, $PathToRoot)
	{
		// include needed files
		if($PathToRoot == "")
			return 'function pagingAndSorting() ~ $PathToRoot must be the valid path to the root of this site from the calling script.';
		require_once($PathToRoot.'php/libs/dataVerification.php');
	
		// setup function by analyzing incoming data
		$TableDataSize = sizeof($TableData);
		if($TableDataSize < 1)
			return 'function pagingAndSorting() ~ $TableData must have at least one record.';
		if($TableDefHTML == "")
			return 'function pagingAndSorting() ~ $TableDefHTML must be defined.';
		$outputHTML = "";
		array_unshift($TableData,""); // padding beginning of array with "" to shift indexes of valid data to 1-N
		$DisplayTableHeader = (sizeof($TableHeaderData) > 0) ? 1 : 0;
		$DisplayPaging = ($DefaultResultsPerPage > 0 && $PagesShown >= 3 && ($PagesShown%2) == 1 && $MaxResultsPerPage >= 5 && ($MaxResultsPerPage%5) == 0 && $ResultDesc != '') ? 1 : 0;
		$DisplaySorting = ($DefaultSortCol != '' && ($DefaultSortDir == 'asc' || $DefaultSortDir == 'desc') && $DisplayTableHeader) ? 1 : 0;
		if($UserControlsResultsPerPage < 0 || $UserControlResultsPerPage > 1)
			return 'function pagingAndSorting() ~ $UserControlsResultsPerPage must be 0 or 1.';
		if($UserControlsResultsPerPage == 1 && ($UserControlLines < 1 || $UserControlLines > 2))
			return 'function pagingAndSorting() ~ $UserControlLines must be 1 or 2.';
		
		// create search within tool if applicable
		$SearchWithinHTML = "";
		if($SearchWithin)
		{
			$SearchWithinLimit = isset($_GET['SearchWithinLimit']) ? str_replace('&quot;','"',stripslashes(trim($_GET['SearchWithinLimit']))) : '';
			if($SearchWithinLimit != "")
			{
				$QuoteLocations = array();
				for($i=0; $i<strlen($SearchWithinLimit); $i++)
					if($SearchWithinLimit{$i} == '"')
						$QuoteLocations[] = $i;
				if(sizeof($QuoteLocations) > 0 && sizeof($QuoteLocations)%2 == 1)
				{
					$SearchWithinLimit = substr($SearchWithinLimit,0,$QuoteLocations[sizeof($QuoteLocations)-1]).substr($SearchWithinLimit,$QuoteLocations[sizeof($QuoteLocations)-1]+1);
					unset($QuoteLocations[sizeof($QuoteLocations)-1]);
				}
			}
			$SearchWithinHTML .= (($UserControlLines==2)?'<br />':' ').'Search: <input id="SearchWithinLimit" value="'.str_replace('"','&quot;',$SearchWithinLimit).'" size="15" maxlength="255" />
								  <input type="button" value="Go" onclick="window.location.href = window.location.pathname+\''.Array2GetString($_GET, array('SearchWithinLimit','Page')).'Page=1&SearchWithinLimit=\'+encodeURIComponent(document.getElementById(\'SearchWithinLimit\').value);">
								  <script language="javascript">
								  	document.getElementById(\'SearchWithinLimit\').onkeydown = function(e) {submitSearchWithinLimit(e);};
									function submitSearchWithinLimit(e)
									{
										if(e.keyCode == 13) // enter key
											window.location.href = window.location.pathname+\''.Array2GetString($_GET, array('SearchWithinLimit','Page')).'Page=1&SearchWithinLimit=\'+encodeURIComponent(document.getElementById(\'SearchWithinLimit\').value);
									}
								  </script>';
			if($SearchWithinLimit != '')
			{	
				$SearchWithinLimit = strtoupper($SearchWithinLimit);			
				$SearchWithinLimit = str_replace(" AND "," ",$SearchWithinLimit);
				$SearchWords = explode(" ",$SearchWithinLimit);
				$InQuotes = false;
				$SearchWithinLimit = "";
				foreach($SearchWords as $i => $Word)
				{
					if(!isset($SearchWords[$i+1]))
					{
						$SearchWithinLimit .= $Word;
						break;
					}
					if(substr($Word,0,1) == '"')
						$InQuotes = true;
					if(substr($Word,strlen($Word)-1) == '"')
						$InQuotes = false;
					if(!$InQuotes && $Word != "OR")
						$SearchWithinLimit .= $Word." AND ";
					else
						$SearchWithinLimit .= $Word." ";
				}
				$SearchWithinLimit = str_replace('"','',$SearchWithinLimit);
				$SearchWithinLimit = str_replace('AND OR','OR',$SearchWithinLimit);
				
				//echo $SearchWithinLimit;
				
				$SearchBlocks = explode(" OR ",$SearchWithinLimit);
				
				for($i=1; $i<=$TableDataSize; $i++)
				{
					$KeepRow = false;
					foreach($SearchBlocks as $Block)
					{
						$BlockPieces = explode(" AND ",$Block);
						$BlockPiecesSize = sizeof($BlockPieces);
						$PiecesMatched = array();
						foreach($BlockPieces as $PieceIndex => $Piece)
						{
							foreach($TableData[$i] as $DataElement)
							{
								$NoHTMLElement = preg_replace("/(<\/?)(\w+)([^>]*>)/e","",$DataElement);
								if(substr_count(strtolower($NoHTMLElement),strtolower($Piece)) > 0)
								{
									if(!in_array($PieceIndex,$PiecesMatched))
										$PiecesMatched[] = $PieceIndex;
								}
							}
						}	
						if($BlockPiecesSize == sizeof($PiecesMatched))
						{
							$KeepRow = true;
							break;
						}
					}
					if(!$KeepRow)
						unset($TableData[$i]);				
				}
				$TableData = array_merge(array(),$TableData);
				$TableDataSize = sizeof($TableData) - 1;
			}
		}
		
		// determine sorting information if applicable
		if($DisplaySorting && $TableDataSize > 0)
		{
			// Obtain a list of columns
			$ColumnNames = array();
			foreach($TableData[1] as $colName => $colVal)
			{
				$ColumnNames[] = $colName;
				$$colName = array();
			}
			
			// Split columns into their own arrays for sorting
			foreach($TableData as $key => $row)
			{
				if($key < 1)
					continue;
				foreach($ColumnNames as $colName)
				{
					$tmpArray = $$colName;
					$tmpArray[$key] = $row[$colName];
					$$colName = $tmpArray;
				}
			}
			
			// Determine which column to sort on and direction
			$SortCol = isset($_GET['SortCol']) ? $_GET['SortCol'] : $DefaultSortCol;
			$SortDir = isset($_GET['SortDir']) ? $_GET['SortDir'] : $DefaultSortDir;
			if(!in_array($SortCol,$ColumnNames))
				$SortCol = $DefaultSortCol;
			if($SortDir != "asc" && $SortDir != "desc")
				$SortDir = $DefaultSortDir;
			
			// Sort the data with volume descending, edition ascending
			// Add $data as the last parameter, to sort by the common key
			//array_multisort($volume, SORT_DESC, $edition, SORT_ASC, $data);
			unset($TableData[0]);
			if($SortDir == "desc")
				array_multisort($$SortCol, SORT_DESC, $TableData);
			else
				array_multisort($$SortCol, SORT_ASC, $TableData);
			array_unshift($TableData,""); // padding $TableData[0] null value was lost on the multisort... readding it
		}
		
		// create user selection tool to determine results per page if applicable
		$ResultsPerPage = $DefaultResultsPerPage;
		$PageResultsHTML = "";
		if($DisplayPaging && $UserControlsResultsPerPage)
		{
			$ResultsPerPage = isset($_GET['ResultsPerPage']) ? trim($_GET['ResultsPerPage']) : $DefaultResultsPerPage;
			if((!isInt($ResultsPerPage) || $ResultsPerPage < 1) && $ResultsPerPage != "ALL")
				$ResultsPerPage = $DefaultResultsPerPage;
			$ResultsPerPageOptions = array();
			$ResultsPerPageOptions[] = "ALL";
			for($i=5; $i<=$MaxResultsPerPage; $i+=5)
				$ResultsPerPageOptions[] = $i;
			if(!in_array($ResultsPerPage,$ResultsPerPageOptions))
			{
				$ResultsPerPageOptions[] = $ResultsPerPage;
				sort($ResultsPerPageOptions);
			}
			$PageResultsHTML .= $ResultDesc.' Per Page:
						   <select id="ResultsPerPage" onchange="window.location.href = window.location.pathname+\''.Array2GetString($_GET, array('ResultsPerPage')).'ResultsPerPage=\'+getElementById(\'ResultsPerPage\').value;">'; 
			foreach($ResultsPerPageOptions as $Option)
				$PageResultsHTML .= '<option value="'.$Option.'" '.(($ResultsPerPage==$Option)?'selected="selected"':'').'>'.$Option.'</option>';
			$PageResultsHTML .= '</select>';
		}
		
		// determine paging information if applicable
		$ResultCountStart = 1;
		$ResultCountEnd = $TableDataSize;
		$CurrentPage = 1;
		$NumberOfPages = 1;
		if($DisplayPaging && $ResultsPerPage != "ALL")
		{
			$NumberOfPages = ceil($TableDataSize/$ResultsPerPage);
			$CurrentPage = isset($_GET['Page']) ? trim($_GET['Page']) : 1;
			if($CurrentPage < 1 || !isInt($CurrentPage))
				$CurrentPage = 1;
			if($CurrentPage > $NumberOfPages)
				$CurrentPage = $NumberOfPages;
			$ResultCountStart = ($ResultsPerPage * $CurrentPage) - $ResultsPerPage + 1;
			$ResultCountEnd = ($CurrentPage == $NumberOfPages) ? $TableDataSize : ($ResultsPerPage * $CurrentPage);
			$PageMidpointBuffer = floor($PagesShown/2.0);
			$PageMidpoint = ceil($PagesShown/2.0);
			if($CurrentPage > $PageMidpoint)
			{
				$LastSeenPage = $CurrentPage + $PageMidpointBuffer;
				if($LastSeenPage > $NumberOfPages)
					$LastSeenPage = $NumberOfPages;
				$FirstSeenPage = $LastSeenPage - ($PagesShown - 1);
				if($FirstSeenPage < 1)
					$FirstSeenPage = 1;
			}
			else
			{
				$LastSeenPage = $PagesShown;
				if($LastSeenPage > $NumberOfPages)
					$LastSeenPage = $NumberOfPages;
				$FirstSeenPage = 1;
			}
			
			// generate page link string
			$PageLinkArray = array();
			$PageGETString = Array2GetString($_GET, array('Page'));
			$PageLinkArray[] = ($CurrentPage > 1) ? '<span style="cursor:pointer" onmouseover="this.style.textDecoration=\'underline\';" onmouseout="this.style.textDecoration=\'none\';" onclick="window.location.href = window.location.pathname+\''.$PageGETString.'Page='.($CurrentPage-1).'\';">&#171;<span style="font-size:12px;"> Prev</span></span>' : '&nbsp;&nbsp;&nbsp;&nbsp;';
			$PageLoopStart = ($FirstSeenPage == 2) ? 1 : $FirstSeenPage;
			$PageLoopEnd = ($LastSeenPage == ($NumberOfPages - 1)) ? $NumberOfPages : $LastSeenPage;
			if($PageLoopStart == $FirstSeenPage && $PageLoopStart > 2)
			{
				$PageLinkArray[] = '<span style="cursor:pointer" onmouseover="this.style.textDecoration=\'underline\';" onmouseout="this.style.textDecoration=\'none\';" onclick="window.location.href = window.location.pathname+\''.$PageGETString.'Page=1\';">1</span>';
				$PageLinkArray[] = '<span style="font-size:14px;">...</span>';
			}
			for($i=$PageLoopStart; $i<=$PageLoopEnd; $i++)
				$PageLinkArray[] = (($CurrentPage == $i)?'<span class="PagingAndSortingSelectedPageSpan selectedPage" style="font-weight:bold">'.$i.'</span>':'<span style="cursor:pointer" onmouseover="this.style.textDecoration=\'underline\';" onmouseout="this.style.textDecoration=\'none\';" onclick="window.location.href = window.location.pathname+\''.$PageGETString.'Page='.$i.'\';">'.$i.'</span>');
			if($PageLoopEnd == $LastSeenPage && $PageLoopEnd < ($NumberOfPages-1))
			{
				$PageLinkArray[] = '<span style="font-size:14px;">...</span>';
				$PageLinkArray[] = '<span style="cursor:pointer" onmouseover="this.style.textDecoration=\'underline\';" onmouseout="this.style.textDecoration=\'none\';" onclick="window.location.href = window.location.pathname+\''.$PageGETString.'Page='.$NumberOfPages.'\';">'.$NumberOfPages.'</span>';
			}
			$PageLinkArray[] = ($CurrentPage < $NumberOfPages) ? '<span style="cursor:pointer" onmouseover="this.style.textDecoration=\'underline\';" onmouseout="this.style.textDecoration=\'none\';" onclick="window.location.href = window.location.pathname+\''.$PageGETString.'Page='.($CurrentPage+1).'\';"><span style="font-size:12px;">Next </span>&#187;</span>' : '&nbsp;&nbsp;&nbsp;&nbsp;';
			$PageLinkString = (sizeof($PageLinkArray) <= 3) ? "" : implode(' ',$PageLinkArray);
		}
		
		// generate results table
		$outputHTML .= $TableDefHTML;
		if($TitleHTML != "")
		{
			$outputHTML .= '
				<tr>
					<td colspan="2" class="tableTitle">'.$TitleHTML.'</td>
				</tr>';
		}
		$outputHTML .= '
			<tr>
				<td align="left" valign="'.(($UserControlLines==2)?'top':'middle').'">'.(($TableDataSize>0)?$ResultDesc.' '.$ResultCountStart.' - '.$ResultCountEnd.' of '.$TableDataSize.(($UserControlLines==2)?'<br />':' ').'<span style="font-size:12px;">(Page '.$CurrentPage.' of '.$NumberOfPages.')</span>':'&nbsp;').'</td>
				<td align="right" valign="top">'.$PageResultsHTML.' '.$SearchWithinHTML.'</td>
			</tr>';
		$outputHTML .= '<tr>
							<td colspan="2">
								<table border="0" cellspacing="0" cellpadding="6" width="100%">';
		// display table header if applicable
		if($DisplayTableHeader)
		{
			$outputHTML .= '<tr>';
			foreach($TableHeaderData as $i => $v)
			{
				$thisOnClick = "";
				$extraStyle = "";
				if($DisplaySorting && $TableDataSize > 0 && !in_array($v,$ExcludeSortCols))
				{
					$CurrentSortColIndexArray = array_keys($ColumnNames,$SortCol);
					$SortGETString = Array2GetString($_GET, array('SortCol','SortDir'));
					$thisOnClick = 'onclick="window.location.href = window.location.pathname+\''.$SortGETString.'SortCol='.$ColumnNames[$i];
					if($CurrentSortColIndexArray[0] == $i && $SortDir == "asc")
						$thisOnClick .= "&SortDir=desc";
					else
						$thisOnClick .= "&SortDir=asc";
					$thisOnClick .= '\';"';
					$extraStyle = "cursor:pointer;";
				}
				$outputHTML .= '<th style="text-align:left;'.$extraStyle.'" '.$thisOnClick.'>'.$v;
				if($DisplaySorting && $TableDataSize > 0 && !in_array($v,$ExcludeSortCols))
				{
					if($CurrentSortColIndexArray[0] == $i)
					{
						if($SortDir == "desc")
							$outputHTML .= ' <img src="'.$PathToRoot.'img/sortArrowDown.gif" style="display:inline" />';
						else
							$outputHTML .= ' <img src="'.$PathToRoot.'img/sortArrowUp.gif" style="display:inline" />';
					}					
				}
				$outputHTML .= '</th>';
			}
			$outputHTML .= '</tr>';
		}
		// display table body
		if($TableDataSize < 1)
			$outputHTML .= '<tr><td width="100%">No matches found</td>';
		else
		{
			$RowColor = false;
			for($i=$ResultCountStart; $i<=$ResultCountEnd; $i++)
			{
				$outputHTML .= '<tr '.($RowColor?'class="PagingAndSortingRowColor rowColor"':'').'>';
				$ColCount = 0;
				foreach($TableData[$i] as $j => $col)
				{
					$outputHTML .= '<td '.(isset($TableColumnWidths[$ColCount])?'width="'.$TableColumnWidths[$ColCount].'"':'').'>'.(($col=="")?'&nbsp;':$col).'</td>';
					$ColCount++;
				}
				$outputHTML .= '</tr>';
				$RowColor = !$RowColor;
			}
			$outputHTML .= '	   </table>
								</td>
							</tr>';
		}
		if($CurrentPage > 1 || $NumberOfPages > 1)
		{
			$outputHTML .= '
				  <tr>
					<td align="left">&nbsp;</td>
					<td align="right">'.$PageLinkString.'</td>
				  </tr>';
		}
		$outputHTML .= '</table>';
		
		return $outputHTML;	
	}
	
	function Array2GetString($Array, $Exceptions, $LeaveTrailingAmp = 1)
	{
		$GetString = "?";
		foreach($Array as $i => $v)
		{
			if(!in_array($i,$Exceptions))
				$GetString .= "$i=".str_replace('\"','%26quot;',$v)."&";
		}
		if(!$LeaveTrailingAmp)
			if(substr($GetString, sizeof($GetString)-1, 1) == '&')
				$GetString = substr($GetString, 0, sizeof($GetString)-1);
		return $GetString;
	}
	
	function getScriptName()
	{
		$URI = explode('?',$_SERVER['REQUEST_URI']);
		$URIPagePart = $URI[0];
		$URIPagePartArray = explode('/', $URIPagePart);
		return($URIPagePartArray[(sizeof($URIPagePartArray)-1)]);
	}
	
	function replace_first($search, $replace, $data) {
	    $res = strpos($data, $search);
	    if($res === false)
	        return $data;
	    else 
	    {
	        // There is data to be replaced
	        $left_seg = substr($data, 0, strpos($data, $search));
	        $right_seg = substr($data, (strpos($data, $search) + strlen($search)));
	        return $left_seg . $replace . $right_seg;
    	}
	}  
	
	function returnTextAndDebug($returnText, $debugText)
	{
		if($debugText != "")
		{
			$debugText = '<br/><br/><table border="2" style="bgcolor:#220000;">
								<tr><th>DEBUGGING DATA</th></tr>
								<tr><td style="color:#AA0000;">'.$debugText.'</td></tr>
							</table>';
		}

		return $returnText.$debugText;
	}
	/*<!-- </body>
</html>
 -->*/
?>