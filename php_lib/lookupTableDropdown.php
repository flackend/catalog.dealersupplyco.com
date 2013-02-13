<?php
	/**
	 * This file contains a function that will create a select HTML dropdown from
	 * a database table lookup.
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @since Unknown
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 * 
	 * @package ES_GeneralPHPLibraries
	 * @subpackage General
	 * @category General
	 */

	/**
	 * Function will create a select HTML dropdown from a database table lookup.
	 * 
	 * @since Unknown
	 * @version Unknown
	 * @author Daniel E. Carr <decarr@ethixsystems.com>
	 * 
	 * @param string $ID The HTML ID and name that you want for the select input field
	 * @param string $PK The name of the primary key for the table you are looking up
	 * @param string $description The description of the field in the table (what will show up in the dropdown box)
	 * @param string $table The table name you are looking up
	 * @param mysqli $mysqli The mysqli object used to connect to the database
	 * @param string $where A where clause if applicable for the select statement from the database - MUST INCLUDE THE WORD WHERE AT THE BEGINNING
	 * @param string $default The dropdown value (not selectedIndex) you want to be selected by default (useful for form posts with errors)
	 * @param bool $emptyField True if you want the first and default value to be empty (default is true)
	 * @param bool $disabled True if you want the field rendered as a disabled field (default is false)
	 * @return unknown_type
	 */
	function lookupTableDropdown($ID, $PK, $description, $table, $mysqli, $where="", $default="", $emptyField=true, $disabled=false)
	{
		if($default == 0)
			$default = "";
		$outputHTML = "";
		$outputHTML .= "<select name='$ID' id='$ID' ".($disabled?'disabled="disabled"':'').">
		";
		if($emptyField)
			$outputHTML .= "	<option value=''></option>
			";
		
		$sql = "select $PK, $description
				from $table
				$where
				order by $description;";
		$result = $mysqli->query($sql);
		while($row = $result->fetch_array(MYSQLI_NUM))
		{
			$outputHTML .= "	<option value='$row[0]'";
			if($default == $row[0])
				$outputHTML .= " selected='selected' ";
			$outputHTML .= ">$row[1]</option>
			";
		}
		
		$outputHTML .= "</select>
		";
		
		return $outputHTML;
	}
?>
