<?php
	/**
	 * This file contains the model for all database related transactions involving
	 * the TB_ProductAux table and all tables containing information directly related to
	 * this table's data.  If possible, this file should not merely represent a single table on the
	 * DB_DealersSupply database, but instead represent a cross-section of related data.
	 * This model extends the abstract base model class ESBaseModel for its basic functionality.
	 * 
	 * @package CI_DSSalesRepApp
	 * @subpackage Models
	 * @category Models
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @since 2009-03-18
	 * @version rev115
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 */

	require_once('models/esbasemodel.php');

	/**
	 * Product model.  Contains all database connections and sql to and from the TB_ProductAux table and any tables that contain information directly related to the information contained in table DB_DealersSupply. 
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @since 2009-03-18
	 * @version 2009-06-22
	 */
	class Product extends ESBaseModel
	{
		
		/**
		 * @var CI_DB_mssql_driver This is the interconnect connection object for select operations on the Inventory table on Dealers Supply's current MSSQL Solomon database
		 */
		var $DBinventory = null;
		
		/**
		 * @var array The fields that are stored in the MySQL Aux table
		 */
		var $FieldsInMysql = array(
				"LongDescription",
				"Image",
				"ImageMimeType",
				"Hidden",
				"Link"
			);
		
		/**
		 * Product class constructor.  The constructor runs all operations in the parent ESBaseModel class
		 * followed by loading both database connections to DB_DealersSupply and the database connection
		 * to the MSSQL Inventory table into objects stored locally to this class as well as some other
		 * variables that are needed for the ESBaseModel routines.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-03-18
		 * @version 2009-05-04
		 * 
		 * @internal
		 * 
		 * @return Product Instanciated Product model class
		 */
		function __construct()
		{
			$this->PrimaryKeyField = "FK_InvtID";
			$this->TableName = "TB_ProductAux";
			$this->SubclassModelName = "Product";
			$this->EnablePAS = true;
			$this->EnableDelete = true;
			parent::__construct();
			$this->DBmaster = $this->load->database('master', TRUE);
			$this->DBadmin = $this->load->database('admin', TRUE);
			$this->DBinventory = $this->load->database('inventory', TRUE);
		}
		
		/**
		 * This function tells the calling script if an object with this primary key exists in the
		 * TB_Product table.
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-03-18
		 * @version 2009-04-15
		 * 
		 * @param int $pk This is the primary key on the TB_Product table that will be checked for existence
		 * @return bool True if the pk exists on the table.  False otherwise
		 */
		function exists($pk)
		{
			$this->DBinventory->select('InvtID');
			$this->DBinventory->from('Inventory');
			$this->DBinventory->where('InvtID',$pk);
			$query = $this->DBinventory->get();
			if($query->num_rows() != 1):
				return false;
			else:
				return true;
			endif;
		}
		
		/**
		 * This function will return the number of records on the TB_Product table where the supplied
		 * field is equal to the supplied value.  This is helpful for determining if a value for
		 * a field is unique. Any field passed in must exist, as there are no checks in place to verify that.
		 * 
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-03-18
		 * @version 2009-05-14
		 * 
		 * @param array $where_array An optional array of field values to include in the where clause of the select statement, enabling your count to be conditional
		 * @param string $group_by An optional string containing the group by clause; for more than one group by column, comma separate them within the string as you would in an SQL query
		 * @return int The number of records on the table that have the supplied field equal to the supplied value for the field, if any
		 */
		function getCount($where_clause = array(), $group_by = "")
		{
			$aux_where = $this->_parse_out_aux_where($where_clause);
			$this->DBinventory->from('Inventory');
			$percentposoffset = 0;
			while($percentpos = strpos($where_clause,'%',$percentposoffset+1)):
				$percentposoffset = $percentpos;
				if($where_clause{$percentpos-1} == "'" || $where_clause{$percentpos+1} == "'")
					continue;
				$where_clause = substr($where_clause,0,$percentpos)."[%]".substr($where_clause,$percentpos+1);
				$percentposoffset += 2;
			endwhile;
			$this->DBinventory->where(str_replace("\'", "''", str_replace("_", "[_]", $where_clause)));
			if(sizeof($aux_where) > 0):
				$this->DBmaster->select('FK_InvtID');
				$this->DBmaster->from('TB_ProductAux');
				foreach($aux_where as $where_part):
					if(strpos($where_part,'Hidden')!==FALSE)	
						$aux_where = str_replace('0','1',trim($where_part));
					$this->DBmaster->where($aux_where);
				endforeach;
				$myquery = $this->DBmaster->get();
				$myresult = $myquery->result_array();
	
				if(isset($myresult) && @sizeof($myresult)>0):
					$myresultkeys = array();
					foreach($myresult as $key => $val):
						$myresultkeys[] = $val["FK_InvtID"];
					endforeach;
					if($where_clause)
					$this->DBinventory->where_not_in('InvtID',$myresultkeys);
				endif;
			endif;
			return $this->DBinventory->count_all_results();
		}
		
		/**
		 * This is a function specific to paging and sorting row selection.  For most cases,
		 * this will simply call the getRows function for this model.  However, this hook is
		 * here in case the application requires custom modifications.
		 * 
		 * At this point, it assumes the only mysql field being referenced by Pagingandsorting
		 * is the Hidden field.  So, due to time restraints, this is hard coded for the time being.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-04-27
		 * @version 2009-05-04
		 * 
		 * @param array|string $select_clause The optional array or comma delimited string of columns to limit the values returned in each row; default is "*", which will get all columns for each row
		 * @param array|string $where_clause An optional array of field values ('field' => 'value') or explicit sql string ('field1' = 'value1' or 'field2' = 'value2') to include in the where clause of the select statement, enabling your row selection to be conditional
		 * @param string $order_by The field to order the results by
		 * @param string $order_dir The direction the ordering of $order_by should take place (ASC OR DESC)
		 * @param array $table_joins An optional array of tables and key associations to join this table to before running where conditions and select (useful for quick lookup table joins)
		 * @param int|string $return_row_count The top number of rows to return from the table after all conditionals and sorting has been applied, "*" to return all rows
		 * @param int $row_offset From the completed query after all conditionals and sorting is applied, the first record to return (ignoring all records that come before this number)
		 * @param array $pk_limit An array of table primary keys that should be added to the $where_clause for pulling large blob data
		 * @return array A sequential array of arrays that contains all the field data for their respective rows
		 */
		function getPagingAndSortingRows($select_clause = "", $where_clause = "", $order_by = "InvtID", $order_dir = "ASC", $table_joins = array(), $return_row_count = "*", $row_offset = 0, $pk_limit = array())
		{
			if(!$this->EnableDelete)
				die("[FATAL ERROR] Model::{$this->SubclassModelName}->getPagingAndSortingRows() Method not enabled for this model.");
			if($order_by == "") $order_by = "InvtID";
			// for this app, hidden is a cross db field, and it can appear in only the select or where clause or not at all
			if(substr_count($select_clause,"Hidden") > 0):
				$HiddenPos = strpos($select_clause,"Hidden");
				$CommaPos = strpos($select_clause,",",($HiddenPos-3));
				$aux_select = "Hidden";
				$select_clause = substr($select_clause,0,$CommaPos);
			elseif(substr_count($where_clause,"Hidden") > 0):
				$aux_where = $this->_parse_out_aux_where($where_clause);
				//$HiddenPos = strpos($where_clause,"Hidden");
				//$AndPos = strpos($where_clause,"and",($HiddenPos-5));
				//$aux_where = substr($where_clause,$HiddenPos);
				//$where_clause = substr($where_clause,0,$AndPos);
			endif;
			if(isset($aux_where)):
				$this->DBmaster->select('FK_InvtID');
				$this->DBmaster->from('TB_ProductAux');
				foreach($aux_where as $where_part):
					if(strpos($where_part,'Hidden')!==FALSE)	
						$aux_where = str_replace('0','1',trim($where_part));
					$this->DBmaster->where($aux_where);
				endforeach;
				$myquery = $this->DBmaster->get();
				$myresult = $myquery->result_array();
			endif;
			$this->DBinventory->select($select_clause);
			$this->DBinventory->from('Inventory');
			$percentposoffset = 0;
			while($percentpos = strpos($where_clause,'%',$percentposoffset+1)):
				$percentposoffset = $percentpos;
				if($where_clause{$percentpos-1} == "'" || $where_clause{$percentpos+1} == "'")
					continue;
				$where_clause = substr($where_clause,0,$percentpos)."[%]".substr($where_clause,$percentpos+1);
				$percentposoffset += 2;
			endwhile;
			$this->DBinventory->where(str_replace("\'", "''", str_replace("_", "[_]", $where_clause)));
			if(isset($myresult) && @sizeof($myresult)>0):
				$myresultkeys = array();
				foreach($myresult as $key => $val):
					$myresultkeys[] = $val["FK_InvtID"];
				endforeach;
				$this->DBinventory->where_not_in('InvtID',$myresultkeys);
			endif;
			$this->DBinventory->order_by($order_by, $order_dir);
			$this->DBinventory->limit($return_row_count, $row_offset);
			$query = $this->DBinventory->get();
			$result = $query->result_array();
			foreach($result as $v => $k):
				$result[$v] = array_map("trim", $result[$v]);
				if(isset($result[$v]['StkBasePrc'])):
					$result[$v]['StkBasePrc'] = $result[$v]['StkBasePrc']*2;
				endif;	
			endforeach;
			if(isset($aux_select)):
				$resultkeys = array();
				foreach($result as $key => $val):
					$resultkeys[] = $val["InvtID"];
				endforeach;
				$this->DBmaster->select('FK_InvtID, '.$aux_select);
				$this->DBmaster->from('TB_ProductAux');
				if(empty($resultkeys))
					return $result;
				$this->DBmaster->where_in('FK_InvtID', $resultkeys);
				$myquery = $this->DBmaster->get();
				$myresult = $myquery->result_array();
				if(sizeof($myresult > 0)):
					foreach($result as $key => $val):
						foreach($myresult as $mykey => $myval):
							if($myval["FK_InvtID"] == $val["InvtID"]):
								foreach($myval as $mycol => $mycoldata):
									$result[$key][$mycol] = $mycoldata;
								endforeach;
								break;
							endif;
						endforeach;
					endforeach;
				endif;
			endif;
			//print_r($result);
			return $result;
		}
		
		/**
		 * This is a generalized get function for retrieving a specific field from the TB_Product table
		 * based on the passed in primary key.  This function also has the power to return all columns
		 * for a particular primary key when the wildcard "*" is passed in for the field name.  Also note
		 * that this function WILL fail with an error message if the primary key you pass it is invalid on the
		 * table or if the field does not exist on the table.  The primary key should be checked for existence 
		 * prior to calling this function using {@link Table::exists()}.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-03-18
		 * @version 2009-04-21
		 *
		 * @param string|array $field This determines what field will be returned for the given TB_Product primary key.  A "*" will return all fields on the TB_Product table for that pk.  Pass an array of field names for selection more than one field without selecting them all.
		 * @param int $pk The primary key on the TB_Product table associated to the information you want to retrieve.
		 * @return stdClass|mixed When selecting all fields for the pk, the function returns a framework object that represents a row with all data fields in it from the table.  When selecting one field, it returns just that field of the type the field is.
		 */
		function getFieldValueFromPK($field, $pk)
		{
			if(gettype($field) != "array"):
				$field = array($field);
			endif;
			$myvalues = array();
			$values_needed_from_inv = array();
			foreach($field as $thefield):
				if(in_array($thefield, $this->FieldsInMysql) || $thefield == "*"):
					if($thefield == "*")
						$values_needed_from_inv[] = "*";
					$this->DBmaster->select($thefield);
					$this->DBmaster->from('TB_ProductAux');
					$this->DBmaster->where('FK_InvtID', $pk);
					$myquery = $this->DBmaster->get();
					if($myquery->num_rows() == 0):
						if($field!="*"):
							return "";
						endif;
					elseif($myquery->num_rows() > 1):
						die("[FATAL ERROR] Model::Product->getFieldValueFromPK() $pk Has ".$query->num_rows()." Records (Aux).");
					else:
						$myrow = $myquery->row_array();
						if($field != "*"):
							$myvalues[$thefield] = $myrow[$thefield];
						else:
							$myvalues = $myrow;
							break;
						endif;
					endif;
				else:
					$values_needed_from_inv[] = $thefield;
				endif;
			endforeach;
			$this->DBinventory->select(implode(', ',$values_needed_from_inv));
			$this->DBinventory->from('Inventory');
			$this->DBinventory->where('InvtID', $pk);
			$query = $this->DBinventory->get();
			if($query->num_rows() != 1):
				die("[FATAL ERROR] Model::Product->getFieldValueFromPK() $pk Has ".$query->num_rows()." Records.");
			else:
				$row = $query->row_array(); 
				$row = array_merge($myvalues, $row);
				$row = array_map('trim', $row);
				if(isset($row['StkBasePrc']))
					$row['StkBasePrc'] = $row['StkBasePrc']*2;
				if(gettype($field)=="array"):
					if(sizeof($field)==1):
						if($field[0]=="*")
							return $row;
						else
							return $row[$field[0]];
					else:
						return $row;
					endif;
				else:
					return $row[$field];
				endif;
			endif;
		}
		
		/**
		 * This function will return the primary key associated to the given field/value combination on
		 * the TB_Product table.  This function is only intended to return a single pk, and therefore it
		 * is recommended that this function only be called on unique fields on the TB_Product table to
		 * ensure that a one-to-one relationship exists.  Please note that this function WILL fail if
		 * the field/value combination you pass in does not exist on the table or exists more than once.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-03-18
		 * @version 2009-04-15
		 *
		 * @param string $field The (hopefully unique) database field that will be queried with a specific value to retrieve a single TB_Product primary key
		 * @param mixed $value The value that will be searched on to derive a TB_Product primary key
		 * @param array $where_array An optional array of field values to include in the where clause of the select statement, enabling your pk selection to be conditional for cases where the uniqueness of $field across the whole table is conditional
		 * @return int The primary key from the TB_Product table that matches the provided field/value combination
		 */
		function getPKFromFieldValue($field, $value, $where_array = array())
		{
			$this->DBinventory->select('InvtID');
			$this->DBinventory->from('Inventory');
			$this->DBinventory->where($field, $value);
			foreach($where_array as $field => $value):
				$this->DBinventory->where($field, $value);
			endforeach;
			$query = $this->DBinventory->get();
			if($query->num_rows() != 1):
				die("[FATAL ERROR] Model::Product->getPKFromFieldValue() $value Has ".$query->num_rows()." Records for Field $field.");
			else:
				$row = $query->row();
				return $row->InvtID;
			endif;
		}
		
		/**
		 * This function will check and see if the InvtID exists on the mysql aux
		 * table or not.
		 * 
		 * @param int $fk The InvtID of the corresponding inventory aux record being looked for
		 * @return bool True if there is a corresponding record on the aux table, False otherwise
		 */
		function existsOnAux($fk)
		{
			$this->DBmaster->select('FK_InvtID');
			$this->DBmaster->from('TB_ProductAux');
			$this->DBmaster->where('FK_InvtID',$fk);
			$query = $this->DBmaster->get();
			if($query->num_rows() != 1):
				return false;
			else:
				return true;
			endif;
		}
		
		/**
		 * This function will get all of the unique section names that are currently
		 * associated to items in the mssql Inventory table.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-04-15
		 * @version 2009-04-15
		 * 
		 * @param array $where_array An optional array of field values to include in the where clause of the select statement
		 * @return array The product sections, as strings, that exist in the inventory table
		 */
		function getProductSections($where_array = array())
		{
			$this->DBinventory->select('ClassID');
			$this->DBinventory->from('Inventory');
			foreach($where_array as $field => $value):
				$this->DBinventory->where($field, $value);
			endforeach;
			$this->DBinventory->group_by('ClassID');
			$this->DBinventory->order_by('ClassID');
			$query = $this->DBinventory->get();
			$result = $query->result();
			$ProductSections = array();
			foreach($result as $row):
				$TempSplitArray = explode('-',trim($row->ClassID),2);
				if(in_array($TempSplitArray[0],$ProductSections))
					continue;
				$ProductSections[] = $TempSplitArray[0];
			endforeach;
			sort($ProductSections);
			return $ProductSections;
		}
		
		/**
		 * This function will get all of the unique product categories associated to the 
		 * specified product section.  This will be pulling from the mssql Inventory table;
		 * from the field ClassID where the format is SectionName-CategoryName.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-04-15
		 * @version 2009-04-15
		 * 
		 * @param string $SectionName The name of the section to pull associated categories for
		 * @param array $where_array An optional array of field values to include in the where clause of the select statement
		 * @return array The product categories, as strings, that are associated to the given product section
		 */
		function getProductCategories($ProductSection = "All", $where_array = array())
		{
			$this->DBinventory->select('ClassID');
			$this->DBinventory->from('Inventory');
			if($ProductSection != "All"):
				$this->DBinventory->where('ClassID like',$ProductSection.'-%');
			endif;
			foreach($where_array as $field => $value):
				$this->DBinventory->where($field, $value);
			endforeach;
			$query = $this->DBinventory->get();
			$result = $query->result();
			$ProductCategories = array();
			foreach($result as $row):
				$TempSplitArray = explode('-',trim($row->ClassID),2);
				if(!isset($TempSplitArray[1]))
					continue;
				if(in_array($TempSplitArray[1],$ProductCategories))
					continue;
				$ProductCategories[] = $TempSplitArray[1];
			endforeach;
			sort($ProductCategories);
			return $ProductCategories;
		}
		
		/**
		 * This function is used by different product model functions to strip out
		 * any occurances of TB_ProductAux fields that appear in the where clause of
		 * an sql statement into a separate array.  Currently, this ONLY works for
		 * compound where statements that are "anded" together.
		 * 
		 * @internal
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-03-18
		 * @version 2009-05-04
		 * 
		 * @param $where_clause The where clause string to be stripped for any fields on the MySQL table
		 * @return array An array of discovered where clause parts that belong to the MySQL table
		 */
		function _parse_out_aux_where(&$where_clause)
		{
			foreach($this->FieldsInMysql as $mysql_field):
				$ReferenceCount = substr_count($where_clause, $mysql_field);
				$ReferenceLoc = 0;
				for($i=0; $i<$ReferenceCount; $i++):
					$ReferenceLoc = strpos($where_clause, $mysql_field);
					if(($NextAnd = strpos($where_clause, 'and', $ReferenceLoc)) !== FALSE):
						$where_for_aux[] = substr($where_clause, $ReferenceLoc, ($NextAnd-$ReferenceLoc-1));
					else:
						$where_for_aux[] = substr($where_clause, $ReferenceLoc);
					endif;	
					if(($LastAnd = strpos($where_clause, 'and', $ReferenceLoc-5)) !== FALSE):
						if($NextAnd !== FALSE):
							$where_clause = substr($where_clause, 0, $LastAnd).substr($where_clause, $NextAnd);
						else:
							$where_clause = substr($where_clause, 0, $LastAnd);
						endif;
					else:
						if($NextAnd !== FALSE):
							$where_clause = substr($where_clause, $NextAnd+3);
						else:
							$where_clause = "";
						endif;
					endif;
				endfor;
			endforeach;
			return (isset($where_for_aux)?$where_for_aux:array());
		}
		
		/**
		 * Product class destructor.  Since these database connections are loaded by object instead
		 * of through the database class provided by the framework, we need to deallocate the
		 * connections for them.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-03-18
		 * @version 2009-05-04
		 * 
		 * @internal
		 */
		function __destruct()
		{
			$this->DBadmin = null;
			$this->DBmaster = null;
			$this->DBinventory = null;
		}
		
	}

?>