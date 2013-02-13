<?php
	/**
	 * This library is an exntesion of the built-in CodeIgniter Form_validation library.
	 * This will only be used by CodeIgniter projects and houses commented custom
	 * modifications to this built-in library.
	 * Modification Objectives: Be able to define page-based validation rules in the validation config array corresponsing to a regular expression and not just a static page for cases where the same config needs to be used across N pks is defined by one of the URI segments
	 * Complient With: CI 1.7.1
	 * 
	 * @package CI_GeneralESLibs
	 * @subpackage Libraries
	 * @category Libraries
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @since 2009-04-28
	 * @version 2009-04-28
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 */

	/**
	 * MY_Form_validation sublibrary. Contains all modifications to the base CI_Form_validation class.
	 * 
	 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
	 * @since 2009-04-28
	 * @version 2009-04-28
	 */
	class ES_Form_validation extends CI_Form_validation
	{
		var $_error_prefix		= '<div class="ErrorMessageQT"><span style="padding-left:18px;">';
		var $_error_suffix		= '</span></div>';
		
		/**
		 * Run the Validator
		 *
		 * This function does all the work.
		 *
		 * @access	public
		 * @return	bool
		 */		
		function run($group = '')
		{
			// Do we even have any data to process?  Mm?
			if (count($_POST) == 0)
			{
				return FALSE;
			}
			
			// Does the _field_data array containing the validation rules exist?
			// If not, we look to see if they were assigned via a config file
			if (count($this->_field_data) == 0)
			{
				// No validation rules?  We're done...
				if (count($this->_config_rules) == 0)
				{
					return FALSE;
				}
				
				// Is there a validation rule for the particular URI being accessed?
				$uri = ($group == '') ? trim($this->CI->uri->ruri_string(), '/') : $group;
				
				//if ($uri != '' AND isset($this->_config_rules[$uri]))
				// NJM: modified form validation config file to make use of regular expression rule defining
				if ($uri != '' AND gettype($this->_config_rules) == "array")
				{
					$valid_rule_index = "0";
					foreach($this->_config_rules as $rule_index => $rule)
					{
						$index_reg_ex = str_replace('/','\/',$rule_index); // escape any forward slashes in the literal part of the uri name
						$index_reg_ex = str_replace('\/*','(\/[^\/]*)?',$index_reg_ex); // replace any wildcards with a regular expression wildcard for a / followed by anything other than a /
						$index_reg_ex = "/^".$index_reg_ex."$/"; // add the match beginning to end markers for reg ex
						$valid_rule_index = (preg_match($index_reg_ex, $uri) ? $rule_index : "0");
						if($valid_rule_index != "0") break;
					}
					if($valid_rule_index != "0")
						$this->set_rules($this->_config_rules[$valid_rule_index]);
				}
				else
				{
					$this->set_rules($this->_config_rules);
				}
		
				// We're we able to set the rules correctly?
				if (count($this->_field_data) == 0)
				{
					log_message('debug', "Unable to find validation rules");
					return FALSE;
				}
			}
		
			// Load the language file containing error messages
			$this->CI->lang->load('form_validation');
								
			// Cycle through the rules for each field, match the 
			// corresponding $_POST item and test for errors
			foreach ($this->_field_data as $field => $row)
			{		
				// Fetch the data from the corresponding $_POST array and cache it in the _field_data array.
				// Depending on whether the field name is an array or a string will determine where we get it from.
				
				if ($row['is_array'] == TRUE)
				{
					$this->_field_data[$field]['postdata'] = $this->_reduce_array($_POST, $row['keys']);
				}
				else
				{
					if (isset($_POST[$field]) AND $_POST[$field] != "")
					{
						$this->_field_data[$field]['postdata'] = $_POST[$field];
					}
				}
			
				$this->_execute($row, explode('|', $row['rules']), $this->_field_data[$field]['postdata']);		
			}
	
			// Did we end up with any errors?
			$total_errors = count($this->_error_array);
	
			if ($total_errors > 0)
			{
				$this->_safe_form_data = TRUE;
			}
	
			// Now we need to re-set the POST data with the new, processed data
			$this->_reset_post_array();
			
			// No errors, validation passes!
			if ($total_errors == 0)
			{
				return TRUE;
			}
	
			// Validation fails
			return FALSE;
		}
		
		/**
		 * Executes the Validation routines
		 *
		 * @access	private
		 * @param	array
		 * @param	array
		 * @param	mixed
		 * @param	integer
		 * @return	mixed
		 */	
		function _execute($row, $rules, $postdata = NULL, $cycles = 0)
		{
			// If the $_POST data is an array we will run a recursive call
			if (is_array($postdata))
			{ 
				foreach ($postdata as $key => $val)
				{
					$this->_execute($row, $rules, $val, $cycles);
					$cycles++;
				}
				
				return;
			}
			
			// --------------------------------------------------------------------
	
			// If the field is blank, but NOT required, no further tests are necessary
			$callback = FALSE;
			if ( ! in_array('required', $rules) AND is_null($postdata))
			{
				// Before we bail out, does the rule contain a callback?
				if (preg_match("/(callback_\w+)/", implode(' ', $rules), $match))
				{
					$callback = TRUE;
					$rules = (array('1' => $match[1]));
				}
				else
				{
					return;
				}
			}
	
			// --------------------------------------------------------------------
			
			// Isset Test. Typically this rule will only apply to checkboxes.
			if (is_null($postdata) AND $callback == FALSE)
			{
				if (in_array('isset', $rules, TRUE) OR in_array('required', $rules))
				{
					// Set the message type
					$type = (in_array('required', $rules)) ? 'required' : 'isset';
				
					if ( ! isset($this->_error_messages[$type]))
					{
						if (FALSE === ($line = $this->CI->lang->line($type)))
						{
							$line = 'The field was not set';
						}							
					}
					else
					{
						$line = $this->_error_messages[$type];
					}
					
					// Build the error message
					$message = sprintf($line, $this->_translate_fieldname($row['label']));
	
					// Save the error message
					$this->_field_data[$row['field']]['error'] = $message;
					
					if ( ! isset($this->_error_array[$row['field']]))
					{
						$this->_error_array[$row['field']] = $message;
					}
				}
						
				return;
			}
	
			// --------------------------------------------------------------------
	
			// Cycle through each rule and run it
			foreach ($rules As $rule)
			{
				// Ethix Systems - NJM - 07-03-2009 
				// since we are relying on result being set or not for a conditional below,
				// and since we are in a loop, this value needs unset each time the loop cycles
				unset($result);
				// End Ethix Systems (see below for remainder of changes)
				
				$_in_array = FALSE;
				
				// We set the $postdata variable with the current data in our master array so that
				// each cycle of the loop is dealing with the processed data from the last cycle
				if ($row['is_array'] == TRUE AND is_array($this->_field_data[$row['field']]['postdata']))
				{
					// We shouldn't need this safety, but just in case there isn't an array index
					// associated with this cycle we'll bail out
					if ( ! isset($this->_field_data[$row['field']]['postdata'][$cycles]))
					{
						continue;
					}
				
					$postdata = $this->_field_data[$row['field']]['postdata'][$cycles];
					$_in_array = TRUE;
				}
				else
				{
					$postdata = $this->_field_data[$row['field']]['postdata'];
				}
	
				// --------------------------------------------------------------------
		
				// Is the rule a callback?			
				$callback = FALSE;
				if (substr($rule, 0, 9) == 'callback_')
				{
					$rule = substr($rule, 9);
					$callback = TRUE;
				}
				
				// Strip the parameter (if exists) from the rule
				// Rules can contain a parameter: max_length[5]
				$param = FALSE;
				if (preg_match("/(.*?)\[(.*?)\]/", $rule, $match))
				{
					$rule	= $match[1];
					$param	= $match[2];
				}
				
				// Call the function that corresponds to the rule
				if ($callback === TRUE)
				{
					if ( ! method_exists($this->CI, $rule))
					{ 		
						continue;
					}
					
					// Run the function and grab the result
					$result = $this->CI->$rule($postdata, $param);
	
					// Re-assign the result to the master data array
					if ($_in_array == TRUE)
					{
						$this->_field_data[$row['field']]['postdata'][$cycles] = (is_bool($result)) ? $postdata : $result;
					}
					else
					{
						$this->_field_data[$row['field']]['postdata'] = (is_bool($result)) ? $postdata : $result;
					}
				
					// If the field isn't required and we just processed a callback we'll move on...
					if ( ! in_array('required', $rules, TRUE) AND $result !== FALSE)
					{
						return;
					}
				}
				else
				{				
					if ( ! method_exists($this, $rule))
					{
						// If our own wrapper function doesn't exist we see if a native PHP function does. 
						// Users can use any native PHP function call that has one param.
						if (function_exists($rule))
						{
							$result = $rule($postdata);
												
							if ($_in_array == TRUE)
							{
								$this->_field_data[$row['field']]['postdata'][$cycles] = (is_bool($result)) ? $postdata : $result;
							}
							else
							{
								$this->_field_data[$row['field']]['postdata'] = (is_bool($result)) ? $postdata : $result;
							}
						}
					//Ethix Systems - DEC - 07-03-2009 - added logic to allow custom functions to be run as form validation
					//The original CI code does not let non-method functions to allow form validation to return false	
					//continue was commented out, and if(!isset($result)) case was added					
						//continue;
					}
					
					if(!isset($result))
					{
						$result = $this->$rule($postdata, $param);
		
						if ($_in_array == TRUE)
						{
							$this->_field_data[$row['field']]['postdata'][$cycles] = (is_bool($result)) ? $postdata : $result;
						}
						else
						{
							$this->_field_data[$row['field']]['postdata'] = (is_bool($result)) ? $postdata : $result;
						}
					}
				}
							
				// Did the rule test negatively?  If so, grab the error.
				if ($result === FALSE)
				{			
					if ( ! isset($this->_error_messages[$rule]))
					{
						if (FALSE === ($line = $this->CI->lang->line($rule)))
						{
							$line = 'Unable to access an error message corresponding to your field name.';
						}						
					}
					else
					{
						$line = $this->_error_messages[$rule];
					}
					
					// Is the parameter we are inserting into the error message the name
					// of another field?  If so we need to grab its "field label"
					if (isset($this->_field_data[$param]) AND isset($this->_field_data[$param]['label']))
					{
						$param = $this->_field_data[$param]['label'];
					}
					
					// Build the error message
					$message = sprintf($line, $this->_translate_fieldname($row['label']), $param);
	
					// Save the error message
					$this->_field_data[$row['field']]['error'] = $message;
					
					if ( ! isset($this->_error_array[$row['field']]))
					{
						$this->_error_array[$row['field']] = $message;
					}
					
					return;
				}
			}
		}
	}
	
?>