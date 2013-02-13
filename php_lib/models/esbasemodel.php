<?php
	/**
	 * This file contains the generic base model used by Ethix Systems applications
	 * that contains a set of basic generic functions that can be useful for selecting
	 * certain sets of data from a defined subclass.  This model is not intended to be called
	 * explicitly, instead needs to have a subclass implementation to be usable.  Also, keep
	 * in mind that by abstracting these functions generically that part of the intent of
	 * model usage in CodeIgniter will be useless insomuch as that if you change the name of
	 * a field on the database, you will have to change all references to it in any controller
	 * that calls these functions instead of being able to soley modify the model subclass to
	 * make this change.
	 * 
	 * @package CI_GeneralESLibs
	 * @subpackage Models
	 * @category Models
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @since 2009-05-01
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 */

	/**
	 * ESBaseModel model.  Contains an abstract shell with generic built-in functionality for creating subclasses for specific views of data in an application that can make use of these pre-built functions. 
	 * 
	 * @abstract
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @since 2009-05-01
	 * @version 2009-05-01
	 */
	abstract class ESBaseModel extends Model
	{
		
		/**
		 * @access protected
		 * @var CI_DB_mysqli_driver This is the connection object for select only operations.
		 */
		protected $DBmaster = null;
		
		/**
		 * @access protected
		 * @var CI_DB_mysqli_driver This is the connection object for select, update, insert, and delete operations.
		 */
		protected $DBadmin = null;
		
		/**
		 * @access protected
		 * @var string The name of the primary key field on the table defined by the instanciated subclass
		 */
		protected $PrimaryKeyField = null;
		
		/**
		 * @access protected
		 * @var string The name of the table being accessed by the instanciated model subclass
		 */
		protected $TableName = null;
		
		/**
		 * @access protected
		 * @var string Strictly for error messages; so that this abstract model class knows what model is instanciated
		 */
		protected $SubclassModelName = null;
		
		/**
		 * @access protected
		 * @var bool True if the instanciated model subclass should have access to the delete method; False if not
		 */
		protected $EnableDelete = false;
		
		/**
		 * @access protected
		 * @var bool True if the instanciated model subclass should have access to the Pagingandsorting method; False if not
		 */
		protected $EnablePAS = false;
		
		/**
		 * ESBaseModel class constructor.  The constructor runs all operations in the parent Model class.
		 * 
		 * @internal
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-01
		 * @version 2009-05-01
		 */
		function __construct()
		{
			if(!isset($this->PrimaryKeyField) || !isset($this->TableName) || !isset($this->SubclassModelName))
				die('A model was defined that uses the ESBaseModel subclass without proper configuration.');
			parent::Model();
		}
		
		/**
		 * This function tells the calling script if an object with this primary key exists on the
		 * table defined by this model subclass.
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-01
		 * @version 2009-05-01
		 * 
		 * @param int $pk This is the primary key that will be checked for existence
		 * @return bool True if the pk exists on the table.  False otherwise
		 */
		function exists($pk)
		{
			$this->DBmaster->select($this->PrimaryKeyField);
			$this->DBmaster->from($this->TableName);
			$this->DBmaster->where($this->PrimaryKeyField,$pk);
			$query = $this->DBmaster->get();
			if($query->num_rows() != 1):
				return false;
			else:
				return true;
			endif;
		}
		
		/**
		 * This function will return the number of records on the table defined by this model subclass
		 * where the supplied field is equal to the supplied value.  This is helpful for determining
		 * if a value for a field is unique. Any field passed in must exist, as there are no checks
		 * in place to verify that.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-01
		 * @version 2009-06-01
		 * 
		 * @param array|string $where_caluse An optional array of field values ('field'=>'value') or explicit sql string ('field1' = 'value1' or 'field2' = 'value2') to include in the where clause of the select statement, enabling your count to be conditional
		 * @param string $group_by An optional string containing the group by clause; for more than one group by column, comma separate them within the string as you would in an SQL query
		 * @param array $table_joins An optional array of tables and key associations to join this table to before running where conditions and count (useful for quick lookup table joins)
		 * @return int The number of records on the table that have the supplied field equal to the supplied value for the field, if any
		 */
		function getCount($where_clause = array(), $group_by = "", $table_joins = array())
		{
			$this->DBmaster->select($this->PrimaryKeyField);
			$this->DBmaster->from($this->TableName);
			foreach($table_joins as $join_table => $key_assoc):
				$this->DBmaster->join($join_table, $key_assoc);
			endforeach;
			if($group_by != "")
				$this->DBmaster->group_by($group_by);
			if(gettype($where_clause) == "array"):
				foreach($where_clause as $field => $value):
					$this->DBmaster->where($field, $value);
				endforeach;
			elseif(!empty($where_clause)):
				$this->DBmaster->where($where_clause);
			endif;
			return $this->DBmaster->count_all_results();
		}
		
		/**
		 * This function will get specified fields for specific rows of data from the table defined by this model subclass.
		 * Optionally, you can specify a field and direction to order the results by as well as an sql limit if the default 
		 * doesn't fit your requirements.  Make sure the field you specify exists, however, as no checks are in place for you.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-01
		 * @version 2009-06-01
		 * 
		 * @param array|string $select_clause The optional array or comma delimited string of columns to limit the values returned in each row; default is "*", which will get all columns for each row
		 * @param array|string $where_clause An optional array of field values ('field' => 'value') or explicit sql string ('field1' = 'value1' or 'field2' = 'value2') to include in the where clause of the select statement, enabling your row selection to be conditional
		 * @param string $order_by The field to order the results by
		 * @param string $order_dir The direction the ordering of $order_by should take place (ASC OR DESC)
		 * @param bool $as_array When true, the results will be returned as an array of arrays, when false they will be as an array of stdClass objects
		 * @param array $table_joins An optional array of tables and key associations to join this table to before running where conditions and select (useful for quick lookup table joins)
		 * @param int|string $return_row_count The top number of rows to return from the table after all conditionals and sorting has been applied, "*" to return all rows
		 * @param int $row_offset From the completed query after all conditionals and sorting is applied, the first record to return (ignoring all records that come before this number)
		 * @return array A sequential array of stdClass framework objects (or arrays, if specified) that contains all the field data for their respective rows
		 */
		function getRows($select_clause = "*", $where_clause = array(), $order_by = "__TABLEPK__", $order_dir = "ASC", $as_array = false, $table_joins = array(), $return_row_count = "*", $row_offset = 0)
		{
			if($order_by == "__TABLEPK__")
				$order_by = $this->PrimaryKeyField;
			if(gettype($select_clause) == "array"):
				$select_clause = implode(', ', $select_clause);
			endif;
			$this->DBmaster->select($select_clause);
			$this->DBmaster->from($this->TableName);
			foreach($table_joins as $join_table => $key_assoc):
				$this->DBmaster->join($join_table, $key_assoc);
			endforeach;
			if($order_by != "")
				$this->DBmaster->order_by($order_by, $order_dir);
			if(gettype($where_clause) == "array"):
				foreach($where_clause as $field => $value):
					$this->DBmaster->where($field, $value);
				endforeach;
			elseif(!empty($where_clause)):
				$this->DBmaster->where($where_clause);
			endif;
			if($return_row_count != "*"):
				if($row_offset > 0):
					$this->DBmaster->limit($return_row_count, $row_offset);
				else:
					$this->DBmaster->limit($return_row_count);
				endif;
			endif;
			$query = $this->DBmaster->get();
			if($as_array)
				return $query->result_array();
			else
				return $query->result();
		}
		
		/**
		 * This function is a variation of the {@link ESBaseModel::getRows()} method that is
		 * used specifically for the Pagingandsorting library.  By default, this will simply call
		 * the getRows() method for its data, but is defined separately in case there is some specific
		 * logic that needs to be defined soley for Pagingandsorting calls.  Also the set of incoming
		 * parameters is slightly different from getRows() since all Pagingandsorting result sets must
		 * be arrays and also for applications that require a second pass of Pagingandsorting to pull
		 * large data blobs, an array of pks needs to be passed in separate from the $where_clause to
		 * act as a second limiting condition.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-01
		 * @version 2009-05-01
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
		function getPagingAndSortingRows($select_clause = "*", $where_clause = array(), $order_by = "__TABLEPK__", $order_dir = "ASC", $table_joins = array(), $return_row_count = "*", $row_offset = 0, $pk_limit = array())
		{
			if(!$this->EnablePAS)
				die("[FATAL ERROR] Model::{$this->SubclassModelName}->getPagingAndSortingRows() Method not enabled for this model.");
			if(gettype($pk_limit) == 'Array'):
				$where_from_pk_limit = "(".implode(' or ',$pk_limit).")";
				if(gettype($where_clause) == "array"):
					$where_clause[] = $where_from_pk_limit;
				else:
					$where_clause .= " AND $where_from_pk_limit";
				endif;
			endif;
			return $this->getRows($select_clause, $where_clause, $order_by, $order_dir, true, $table_joins, $return_row_count, $row_offset);
		}
		
		/**
		 * This is a generalized get function for retrieving a specific field from the table defined by this model subclass
		 * based on the passed in primary key.  This function also has the power to return all columns
		 * for a particular primary key when the wildcard "*" is passed in for the field name.  Also note
		 * that this function WILL fail with an error message if the primary key you pass it is invalid on the
		 * table or if the field does not exist on the table.  The primary key should be checked for existence 
		 * prior to calling this function using {@link ESBaseModel::exists()}.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-01
		 * @version 2009-05-01
		 *
		 * @param string|array $field This determines what field will be returned for the given primary key.  A "*" will return all fields on the table for that pk.  Pass an array of field names for selection more than one field without selecting them all.
		 * @param int $pk The primary key associated to the information you want to retrieve.
		 * @param array $table_joins An optional array of tables and key associations to join this table to before running where conditions and select (useful for quick lookup table joins)
		 * @param bool $as_array In the case where more than one field is being returned, this will dictate if that data should be returned as an stdClass object or an array; defaults to an stdClass object
		 * @return stdClass|array|mixed When selecting all fields for the pk, the function returns an stdClass object (or array if specified) that represents a row with all data fields in it from the table.  When selecting one field, it returns just that field of the type the field is.
		 */
		function getFieldValueFromPK($field, $pk, $table_joins = array(), $as_array = false)
		{
			if(gettype($field) == "array"):
				$this->DBmaster->select(implode(', ',$field));
			elseif($field != "*"):
				$this->DBmaster->select($field);
			endif;
			$this->DBmaster->from($this->TableName);
			foreach($table_joins as $join_table => $key_assoc):
				$this->DBmaster->join($join_table, $key_assoc);
			endforeach;
			$this->DBmaster->where($this->PrimaryKeyField, $pk);
			$query = $this->DBmaster->get();
			if($query->num_rows() != 1):
				die("[FATAL ERROR] Model::{$this->SubclassModelName}->getFieldValueFromPK() $pk Has ".$query->num_rows()." Records.");
			else:
				if($as_array):
					$row = $query->row_array();
				else:
					$row = $query->row();
				endif;
				if(gettype($field)=="array" || @$field == "*"):
					return $row;
				else:
					if($as_array):
						return $row[$field];
					else:
						return $row->$field;
					endif;
				endif;
			endif;
		}
		
		/**
		 * This function will return the primary key associated to the given field/value combination on
		 * the table defined by this model subclass.  This function is only intended to return a single pk,
		 * and therefore it is recommended that this function only be called on unique fields on the table to
		 * ensure that a one-to-one relationship exists.  Please note that this function WILL fail if
		 * the field/value combination you pass in does not exist on the table or exists more than once.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-01
		 * @version 2009-05-01
		 *
		 * @param string $field The (hopefully unique) database field that will be queried with a specific value to retrieve a single primary key
		 * @param mixed $value The value that will be searched on to derive a primary key
		 * @param array $where_array An optional array of field values to include in the where clause of the select statement, enabling your pk selection to be conditional for cases where the uniqueness of $field across the whole table is conditional
		 * @param array $table_joins An optional array of tables and key associations to join this table to before running where conditions and select (useful for quick lookup table joins)
		 * @return int The primary key that matches the provided field/value combination
		 */
		function getPKFromFieldValue($field, $value, $where_array = array(), $table_joins = array())
		{
			$this->DBmaster->select($this->PrimaryKeyField);
			$this->DBmaster->from($this->TableName);
			foreach($table_joins as $join_table => $key_assoc):
				$this->DBmaster->join($join_table, $key_assoc);
			endforeach;
			$this->DBmaster->where($field, $value);
			foreach($where_array as $field => $value):
				$this->DBmaster->where($field, $value);
			endforeach;
			$query = $this->DBmaster->get();
			if($query->num_rows() != 1):
				die("[FATAL ERROR] Model::{$this->SubclassModelName}->getPKFromFieldValue() $value Has ".$query->num_rows()." Records for Field $field.");
			else:
				$row = $query->row();
				return $row->{$this->PrimaryKeyField};
			endif;
		}
		
		/**
		 * This function will update any fields supplied in the $update_data array on the table defined
		 * by this model subclass for the provided primary key.  It is up to the calling script to know what values
		 * should and should not be updated using this function.
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-01
		 * @version 2009-05-01
		 * 
		 * @param int $pk The primary key of the table's record data you wish to update
		 * @param array $update_data The array of new data, indexed by field name, that you wish to have updated for this pk
		 * @return int|bool The number of rows affected by the update command (which should be 1), unless it fails, then it will be 0 (false)
		 */
		function update($pk, $update_data)
		{
			if($this->DBadmin->where($this->PrimaryKeyField, $pk)->update($this->TableName, $update_data)):
				return $this->DBadmin->affected_rows();
			else:
				return false;
			endif;
		}
		
		/**
		 * This function will insert a new record into the table defined by this model subclass with the data
		 * for the fields supplied in the $insert_data array.  It is up to the calling script
		 * to ensure that all the required fields have been supplied for this insert.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-01
		 * @version 2009-05-01
		 * 
		 * @param array $insert_data The array of data indexed by the table columns to insert the new record data into
		 * @return bool|int False if the insert fails, otherwise the record id for the newly inserted record
		 */
		function create($insert_data)
		{
			if($this->DBadmin->insert($this->TableName, $insert_data)):
				return $this->DBadmin->insert_id();
			else:
				return false;
			endif;
		}
		
		/**
		 * This function will completely remove a record from the table defined by this model subclass.
		 * This action CANNOT be undone!  Also, the primary key supplied must exist to have
		 * this function without error.  Use {@see ESBaseModel::exists()} to check that
		 * it exists first, if needed.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-01
		 * @version 2009-05-01
		 * 
		 * @param int $pk The primary key of the record to delete
		 * @return bool True if the record removal occurred successfully, False otherwise
		 */
		function delete($pk)
		{
			if(!$this->EnableDelete)
				die("[FATAL ERROR] Model::{$this->SubclassModelName}->delete() Method not enabled for this model.");
			if($this->DBadmin->delete($this->TableName, array($this->PrimaryKeyField => $pk))):
				return true;
			else:
				return false;
			endif;
		}
		
				
		/**
		 * ESBaseModel class destructor.  Since these database connections are loaded by object instead
		 * of through the database class provided by the framework, we need to deallocate the
		 * connections for them.
		 * 
		 * @internal
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-01
		 * @version 2009-05-01
		 */
		function __destruct()
		{
			$this->DBadmin = null;
			$this->DBmaster = null;
		}
	}
?>