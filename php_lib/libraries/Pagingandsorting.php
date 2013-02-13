<?php

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

class Pagingandsorting {

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
		private $CI = "";
		protected $ColumnArray = array();
		private $Model = "";
		private $SelectClause = "";
		private $WhereClause = "";
		private $LargeItemsArray = array();
		private $TableDefHTML = "";
		private $TitleHTML = "";
		private $HeaderHTML = "";
		private $PDFName = "";
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
		private $ViewType = "";
		private $NeverShowAll = false;
		public $ErrorMessage = "";
		
	public function __construct()
	{
		$this->CI =& get_instance();
	}

	public function initialize($params)
	{
		//params contains SQL, Model, TableDefHTML, and ResultDesc
		$this->Model = $params['Model'];
		$this->CI->load->model($this->Model);
		$this->SelectClause = $params['SelectClause'];
		if(isset($params['WhereClause']))
			$this->WhereClause = $params['WhereClause'];
		$this->TableDefHTML = $params['TableDefHTML'];
		$this->TitleHTML = "";
		$this->DefaultSortCol = '';
		$this->DefaultSortDir = "asc";
		$this->DefaultResultsPerPage = 10;
		$this->UserControlsResultsPerPage = false;
		$this->UserControlLines = 1;
		$this->SearchWithin = false;
		$this->MaxResultsPerPage = 20;
		$this->PagesShown = 5;
		$this->ResultDesc = $params['ResultDesc'];
		$this->HTMLBetweenRows = "";
		$this->DebuggingMode = false;
		$this->SortArrowLocation = "/img";
		$this->ViewType = "html";
	}
		
	/**
	 * 	This function allows you to set a column up in the pagingAndSorting table
	 * @param $DisplayName The name of the column that you wish to have appear on the screen
	 * @param $DisplayWidth The width of the column, use px or %, but be consistant and include units
	 * @param $DatabaseColumnName The name of the Database Column that you would like to sort on - leave blank if there is none
	 * @param $OptionsArray An array containing potential values that define properties of the column
	 * 		Sortable being set means that the column is sortable
	 * 		DefaultSortColumn being set means that the column should be the defaulted sort column
	 * 		DoNotDisplayHeader being set means that the header should not be displayed
	 * 		IsLargeItem should be set if the item is linked to a very large field on a database
	 * 			***Special Note on Large Items - DO NOT include them in $this->SQL, and make sure you have the correct unique identifier set
	 * 		PartOfUniqueIdentifier should be set if the item is linked to a database field that helps determine uniqueness in the query
	 * @return none
	 */
	public function setColumnInfo($DisplayName, $DisplayWidth, $DatabaseColumnName, $OptionsArray)
	{
		$Column = array();
		$Column['DisplayName'] = $DisplayName;
		if($DisplayWidth != -1):
			$Column['DisplayWidth'] = $DisplayWidth;
		endif;
		$Column['DatabaseColumnName'] = $DatabaseColumnName;
		$Column['Sortable'] = (isset($OptionsArray['Sortable']))?$OptionsArray['Sortable']:false;
		$this->DefaultSortCol = (isset($OptionsArray['DefaultSortColumn']) && $OptionsArray['DefaultSortColumn'])?$DisplayName:$this->DefaultSortCol;
		$Column['DoNotDisplayHeader'] = (isset($OptionsArray['DoNotDisplayHeader']))?$OptionsArray['DoNotDisplayHeader']:false;
		if(isset($OptionsArray['IsLargeItem']) && $OptionsArray['IsLargeItem'])
		{
			$this->LargeItemsArray[] = $DatabaseColumnName;
			if($Column['Sortable'])
				$this->ErrorMessage .=  "ERROR: You cannot sort on large items.<br/>.";
		}
		if(isset($OptionsArray['PartOfUniqueIdentifier']) && $OptionsArray['PartOfUniqueIdentifier'])
			$this->UniqueIdentifierArray[] = $DatabaseColumnName;
		$this->ColumnArray[] = $Column;
	}
	
	public function setTitleHTML($TitleHTML)
	{
		$this->TitleHTML = $TitleHTML;
		return true;
	}

	public function setHeaderHTML($HeaderHTML)
	{
		$this->HeaderHTML = $HeaderHTML;
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
		$this->CI->load->helper('validation');
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
		$this->DebuggingMode = $DebugMode;
		return true;
	}

	public function neverShowAll($neverShowAll = true)
	{
		$this->NeverShowAll = $neverShowAll;
		return true;
	}
	
	public function setTRClickAction($TRClickAction, $VariableArray = array())
	{
		$this->TRClickAction = str_replace('"',"'",$TRClickAction);
		$this->TRClickVariables = $VariableArray;
	}

	public function setViewType($ViewType)
	{
		$this->ViewType = $ViewType;
		return true;
	}
	
	public function setPDFName($PDFName)
	{
		$this->PDFName = $PDFName;
		return true;
	}
	
	protected function FormattingFunction($SQLRow, $tableArray = array())
	{
		$returnArray = $tableArray;
		foreach($SQLRow as $index => $val)
		{
			foreach($this->ColumnArray as $columnIndex => $columnInfo)
			{
				if($index == $columnInfo['DatabaseColumnName']):
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
				return $this->_returnTextAndDebug("There were errors with the setup.  Check the error log:", $this->ErrorMessage);
			else
				return $this->_returnTextAndDebug("This information is not available at this time.  Please try again later.<br/>Error Code: P&S", "");
		}
		$outputHTML = "";	
		$DebugMessage = "";
		$this->CI->load->helper('validation');

		if(sizeof($this->LargeItemsArray) > 0 && sizeof($this->UniqueIdentifierArray) == 0)
			$DebugMessage .= ($this->DebuggingMode)?"ERROR::You cannot have large items without setting a unique identifier.<br/>":"";
		
		if($this->SearchWithin && sizeof($this->UniqueIdentifierArray) == 0)
			$DebugMessage .= ($this->DebuggingMode)?"ERROR::You cannot have user searches enabled without setting a unique identifier.<br/>":"";
		
		//if DefaultResultsPerPage is "ALL" and NeverShowAll is true, throw an error
		if($this->DefaultResultsPerPage == 'ALL' && $this->NeverShowAll)
			$DebugMessage .= ($this->DebuggingMode)?"ERROR::You cannot have a default of 'ALL'.<br/>":"";
			
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
				if($SingleColumnData['Sortable'] && !isset($SingleColumnData['DatabaseColumnName']))
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
		$TableDataSize = $this->CI->{$this->Model}->getCount($this->WhereClause);
		
		//Determine paging information if applicable
		if($DisplayPaging)
		{
			$ResultsPerPage = isset($_GET['ResultsPerPage']) ? trim($_GET['ResultsPerPage']) : $this->DefaultResultsPerPage;
			if((!isInt($ResultsPerPage) || $ResultsPerPage < 1) && $ResultsPerPage != "ALL")
				$ResultsPerPage = $this->DefaultResultsPerPage;
			if($ResultsPerPage == 'ALL' && $this->NeverShowAll)
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
		
		//Order by
		$order_by = "";
		$order_dir = 'ASC';
		if($DisplaySorting)
		{
			foreach($this->ColumnArray as $SingleColumn)
			{
				if($SingleColumn['DisplayName'] == $SortCol)
				{
					$DatabaseSortCol = $SingleColumn['DatabaseColumnName'];
					break;
				}
			}
			$order_by = $DatabaseSortCol;
			$order_dir = $SortDir;
		}
		
		$table_joins = array();
		
		$return_row_count = '*';
		$row_offset = 0;
		if(!$this->SearchWithin && $SortableOnDatabase)
		{
			//This is the case where we can limit the results inside of the SQL
			
			//Limit
			if($ResultsPerPage == "ALL")
				$ResultsPerPage = $TableDataSize;
			if($DisplayPaging):
				$row_offset = $ItemNumberToBeginWith-1;
				$return_row_count = $ResultsPerPage;
			endif;
		}
		//Run the SQL statement
		//$DebugMessage .= ($this->DebuggingMode)?"FIRST SQL BEING RUN::".$this->CI->{$this->Model}->getSQL()."<br/>":"";
		$SQLResult = $this->CI->{$this->Model}->getPagingAndSortingRows($this->SelectClause, $this->WhereClause, $order_by, $order_dir, $table_joins, $return_row_count, $row_offset);
		
		//run each SQL row through the formatting function
		$TableData = array();
		$i=1;
		//get the data array
		
		foreach($SQLResult as $SQLRow)
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
			return $this->_returnTextAndDebug('<div id="PASNoResultsDiv">There are no '.$this->ResultDesc.' to display.</div>', $DebugMessage);
		}
		
		$ResultCountStart = 1;
		$ResultCountEnd = $TableDataSize;
		
		$this->CI->load->helper('array');
		//implement search logic if applicable
		if($this->SearchWithin)
		{
			$SearchWithinGet = isset($_GET['SearchWithinLimit']) ? str_replace('&quot;','"',stripslashes(trim($_GET['SearchWithinLimit']))) : '';
			$SearchWithinLimit = $SearchWithinGet;
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
			if(!$this->NeverShowAll)
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
					$whereClausePiece .= $PK." = '".addslashes($col['PKValue'][$j])."' and ";
				}
				if(strlen($whereClausePiece) > 2)
					$stringOfPKData .= " ".substr($whereClausePiece, 0, -4).") OR ";
				$i++;
			}
			if(strlen($stringOfPKData) <= 6)
				$stringOfPKData = "";
			else
				$stringOfPKData = "(".substr($stringOfPKData, 0, -3).")";
			
			//parse the old SQL to include only the necessary rows
			$selectArray2 = array_merge($this->UniqueIdentifierArray, $this->LargeItemsArray);
			if(empty($selectArray2))
				break;
			$selectString = implode(', ', $selectArray2);
			
			$SQLResult = $this->CI->{$this->Model}->getPagingAndSortingRows($selectString, $this->WhereClause, $order_by, $order_dir, $table_joins, $return_row_count, $row_offset, $stringOfPKData);
		
			$i=1;
			foreach($SQLResult as $SQLRow)
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
		$SortGETString = Array2GetString($_GET, array('SortCol','SortDir','Page'));
		$PASHtmlArray = array();
		if(isset($this->TableDefHTML))
			$PASHtmlArray['TableDefHTML'] = $this->TableDefHTML;
		if(isset($this->TitleHTML))
			$PASHtmlArray['TitleHTML'] = $this->TitleHTML;
		if(isset($this->HeaderHTML))
			$PASHtmlArray['HeaderHTML'] = $this->HeaderHTML;
			if(isset($DisplayPaging))
			$PASHtmlArray['DisplayPaging'] = $DisplayPaging;
		if(isset($this->SearchWithin))
			$PASHtmlArray['SearchWithin'] = $this->SearchWithin;
		if(isset($this->UserControlLines))
			$PASHtmlArray['UserControlLines'] = $this->UserControlLines;
		if(isset($TableDataSize))
			$PASHtmlArray['TableDataSize'] = $TableDataSize;
		if(isset($TableData))
			$PASHtmlArray['TableData'] = $TableData;
		if(isset($this->ResultDesc))
			$PASHtmlArray['ResultDesc'] = $this->ResultDesc;
		if(isset($ItemNumberToBeginWith))
			$PASHtmlArray['ItemNumberToBeginWith'] = $ItemNumberToBeginWith;
		if(isset($ItemNumberToEndWith))
			$PASHtmlArray['ItemNumberToEndWith'] = $ItemNumberToEndWith;
		if(isset($CurrentPage))
			$PASHtmlArray['CurrentPage'] = $CurrentPage;
		if(isset($NumberOfPages))
			$PASHtmlArray['NumberOfPages'] = $NumberOfPages;
		if(isset($this->UserControlsResultsPerPage))
			$PASHtmlArray['UserControlsResultsPerPage'] = $this->UserControlsResultsPerPage;
		if(isset($PageResultsHTML))
			$PASHtmlArray['PageResultsHTML'] = $PageResultsHTML;
		if(isset($SearchWithinHTML))
			$PASHtmlArray['SearchWithinHTML'] = $SearchWithinHTML;
		if(isset($DisplayTableHeader))
			$PASHtmlArray['DisplayTableHeader'] = $DisplayTableHeader;
		if(isset($this->ColumnArray))
			$PASHtmlArray['ColumnArray'] = $this->ColumnArray;
		if(isset($SortCol))
			$PASHtmlArray['SortCol'] = $SortCol;
		if(isset($SortDir))
			$PASHtmlArray['SortDir'] = $SortDir;
		if(isset($SortGETString))
			$PASHtmlArray['SortGETString'] = $SortGETString;
		if(isset($CurrentSortColIndexArray))
			$PASHtmlArray['CurrentSortColIndexArray'] = $CurrentSortColIndexArray;
		if(isset($this->SortArrowLocation))
			$PASHtmlArray['SortArrowLocation'] = $this->SortArrowLocation;
		if(isset($SortableOnDatabase))
			$PASHtmlArray['SortableOnDatabase'] = $SortableOnDatabase;
		if(isset($ResultsPerPage))
			$PASHtmlArray['ResultsPerPage'] = $ResultsPerPage;
		if(isset($this->HTMLBetweenRows))
			$PASHtmlArray['HTMLBetweenRows'] = $this->HTMLBetweenRows;
		if(isset($PageLinkString))
			$PASHtmlArray['PageLinkString'] = $PageLinkString;
		if(isset($DisplaySorting))
			$PASHtmlArray['DisplaySorting'] = $DisplaySorting;
			
		if($this->ViewType == 'html'):
			$ViewHTML = $this->CI->load->view('pas/pas_header_html', $PASHtmlArray, true);
			$ViewHTML .= $this->CI->load->view('pas/pas_html', $PASHtmlArray, true);
			return $this->_returnTextAndDebug($ViewHTML, $DebugMessage);	
		elseif($this->ViewType == 'pdf'):
			if(isset($SearchWithinGet))
				$PASHtmlArray['SearchWithinGet'] = $SearchWithinGet;
			else
				$PASHtmlArray['SearchWithinGet'] = "";
			if(isset($this->PDFName))
				$PASHtmlArray['PDFName'] = $this->PDFName;
			else
				$PASHtmlArray['PDFName'] = "PDF.pdf";
			$this->CI->load->view('pas/pas_pdf', $PASHtmlArray);
		endif;
			
	}
	
	private function _replace_first($search, $replace, $data) {
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
	
	private function _returnTextAndDebug($returnText, $debugText)
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
	
}
?>