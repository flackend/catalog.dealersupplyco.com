<?php
	/**
	 * This library is an exntesion of the built-in CodeIgniter Loader library.
	 * This will only be used by CodeIgniter projects and houses commented custom
	 * modifications to this built-in library.
	 * Modification Objectives: Be able to load custom models, helpers, libraries, and views from a common php_lib repository
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
	 * MY_Loader sublibrary. Contains all modifications to the base CI_Loader class.
	 * 
	 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
	 * @since 2009-04-28
	 * @version 2009-04-28
	 */
	class ES_Loader extends CI_Loader
	{
		/**
		 * Model Loader
		 *
		 * This function lets users load and instantiate models.
		 *
		 * @access	public
		 * @param	string	the name of the class
		 * @param	string	name for the model
		 * @param	bool	database connection
		 * @return	void
		 */	
		function model($model, $name = '', $db_conn = FALSE)
		{		
			if (is_array($model))
			{
				foreach($model as $babe)
				{
					$this->model($babe);	
				}
				return;
			}
	
			if ($model == '')
			{
				return;
			}
		
			// Is the model in a sub-folder? If so, parse out the filename and path.
			if (strpos($model, '/') === FALSE)
			{
				$path = '';
			}
			else
			{
				$x = explode('/', $model);
				$model = end($x);			
				unset($x[count($x)-1]);
				$path = implode('/', $x).'/';
			}
		
			if ($name == '')
			{
				$name = $model;
			}
			
			if (in_array($name, $this->_ci_models, TRUE))
			{
				return;
			}
			
			$CI =& get_instance();
			if (isset($CI->$name))
			{
				show_error('The model name you are loading is the name of a resource that is already being used: '.$name);
			}
		
			$model = strtolower($model);
			
			/** NJM: MODIFIED BY ETHIX SYSTEMS **/
			if ( ! file_exists(APPPATH.'models/'.$path.$model.EXT) && ! file_exists(CI_INCLUDE_PATH.'models/'.$path.$model.EXT))
			{
				show_error('Unable to locate the model you have specified: '.$model);
			}
			/** END MODIFICATION **/
					
			if ($db_conn !== FALSE AND ! class_exists('CI_DB'))
			{
				if ($db_conn === TRUE)
					$db_conn = '';
			
				$CI->load->database($db_conn, FALSE, TRUE);
			}
		
			if ( ! class_exists('Model'))
			{
				load_class('Model', FALSE);
			}
			//echo APPPATH.'models/'.$path.$model.EXT.'<br />';
			/** NJM: MODIFIED BY ETHIX SYSTEMS **/
			if(file_exists(APPPATH.'models/'.$path.$model.EXT)) {
				include_once(APPPATH.'models/'.$path.$model.EXT);
			}
			if (!class_exists($name))
			{
				require_once(CI_INCLUDE_PATH.'models/'.$path.$model.EXT);
			}
			if (!class_exists($name))
				show_error("Unable to load the model $name locally or in php_lib.  Please check spelling and case sensitivity.");
			/** END MODIFICATION **/
	
			$model = ucfirst($model);
					
			$CI->$name = new $model();
			$CI->$name->_assign_libraries();
			
			$this->_ci_models[] = $name;	
		}
		
		/**
		 * Load Helper
		 *
		 * This function loads the specified helper file.
		 *
		 * @access	public
		 * @param	mixed
		 * @return	void
		 */
		function helper($helpers = array())
		{
			if ( ! is_array($helpers))
			{
				$helpers = array($helpers);
			}
		
			foreach ($helpers as $helper)
			{		
				$helper = strtolower(str_replace(EXT, '', str_replace('_helper', '', $helper)).'_helper');
	
				if (isset($this->_ci_helpers[$helper]))
				{
					continue;
				}
				
				$ext_helper = APPPATH.'helpers/'.config_item('subclass_prefix').$helper.EXT;
	
				// Is this a helper extension request?			
				if (file_exists($ext_helper))
				{
					$base_helper = BASEPATH.'helpers/'.$helper.EXT;
					
					if ( ! file_exists($base_helper))
					{
						show_error('Unable to load the requested file: helpers/'.$helper.EXT);
					}
					
					include_once($ext_helper);
					include_once($base_helper);
				}
				/** NJM: MODIFIED BY ETHIX SYSTEMS **/
				elseif (file_exists(CI_INCLUDE_PATH.'helpers/'.config_item('subclass_prefix').$helper.EXT))
				{
					$base_helper = BASEPATH.'helpers/'.$helper.EXT;
					
					if ( ! file_exists($base_helper))
					{
						show_error('Unable to load the requested file: helpers/'.$helper.EXT);
					}
					
					include_once(CI_INCLUDE_PATH.'helpers/'.config_item('subclass_prefix').$helper.EXT);
					include_once($base_helper);
				}
				/** END MODIFICATION **/
				elseif (file_exists(APPPATH.'helpers/'.$helper.EXT))
				{ 
					include_once(APPPATH.'helpers/'.$helper.EXT);
				}
				/** NJM: MODIFIED BY ETHIX SYSTEMS **/
				elseif (file_exists(CI_INCLUDE_PATH.'helpers/'.$helper.EXT))
				{
					include_once(CI_INCLUDE_PATH.'helpers/'.$helper.EXT);
				}
				else
				{		
					if (file_exists(BASEPATH.'helpers/'.$helper.EXT))
					{
						include_once(BASEPATH.'helpers/'.$helper.EXT);
					}
					else
					{
						show_error('Unable to load the requested file: helpers/'.$helper.EXT);
					}
				}
	
				$this->_ci_helpers[$helper] = TRUE;
				log_message('debug', 'Helper loaded: '.$helper);	
			}		
		}
		
		/**
		 * Load Plugin
		 *
		 * This function loads the specified plugin.
		 *
		 * @access	public
		 * @param	array
		 * @return	void
		 */
		function plugin($plugins = array())
		{
			if ( ! is_array($plugins))
			{
				$plugins = array($plugins);
			}
		
			foreach ($plugins as $plugin)
			{	
				$plugin = strtolower(str_replace(EXT, '', str_replace('_pi', '', $plugin)).'_pi');		
	
				if (isset($this->_ci_plugins[$plugin]))
				{
					continue;
				}
	
				/** NJM: MODIFIED BY ETHIX SYSTEMS **/
				if (file_exists(CI_INCLUDE_PATH.'plugins/'.$plugin.EXT))
				{
					include_once(CI_INCLUDE_PATH.'plugins/'.$plugin.EXT);
				}
				/** END MODIFICATION **/
				elseif (file_exists(APPPATH.'plugins/'.$plugin.EXT))
				{
					include_once(APPPATH.'plugins/'.$plugin.EXT);	
				}
				else
				{
					if (file_exists(BASEPATH.'plugins/'.$plugin.EXT))
					{
						include_once(BASEPATH.'plugins/'.$plugin.EXT);	
					}
					else
					{
						show_error('Unable to load the requested file: plugins/'.$plugin.EXT);
					}
				}
				
				$this->_ci_plugins[$plugin] = TRUE;
				log_message('debug', 'Plugin loaded: '.$plugin);
			}		
		}
		
		/**
		 * Loader
		 *
		 * This function is used to load views and files.
		 * Variables are prefixed with _ci_ to avoid symbol collision with
		 * variables made available to view files
		 *
		 * @access	private
		 * @param	array
		 * @return	void
		 */
		function _ci_load($_ci_data)
		{
			// Set the default data variables
			foreach (array('_ci_view', '_ci_vars', '_ci_path', '_ci_return') as $_ci_val)
			{
				$$_ci_val = ( ! isset($_ci_data[$_ci_val])) ? FALSE : $_ci_data[$_ci_val];
			}
	
			// Set the path to the requested file
			if ($_ci_path == '')
			{
				$_ci_ext = pathinfo($_ci_view, PATHINFO_EXTENSION);
				$_ci_file = ($_ci_ext == '') ? $_ci_view.EXT : $_ci_view;
				$_ci_path = $this->_ci_view_path.$_ci_file;
			}
			else
			{
				$_ci_x = explode('/', $_ci_path);
				$_ci_file = end($_ci_x);
			}
			
			/** NJM: MODIFIED BY ETHIX SYSTEMS **/
			if ( ! file_exists($_ci_path) && ! file_exists(CI_INCLUDE_PATH.'views/'.$_ci_file))
			{
				show_error('Unable to load the requested file: '.$_ci_file);
			}
			/** END MODIFICATION **/
		
			// This allows anything loaded using $this->load (views, files, etc.)
			// to become accessible from within the Controller and Model functions.
			// Only needed when running PHP 5
			
			if ($this->_ci_is_instance())
			{
				$_ci_CI =& get_instance();
				foreach (get_object_vars($_ci_CI) as $_ci_key => $_ci_var)
				{
					if ( ! isset($this->$_ci_key))
					{
						$this->$_ci_key =& $_ci_CI->$_ci_key;
					}
				}
			}
	
			/*
			 * Extract and cache variables
			 *
			 * You can either set variables using the dedicated $this->load_vars()
			 * function or via the second parameter of this function. We'll merge
			 * the two types and cache them so that views that are embedded within
			 * other views can have access to these variables.
			 */	
			if (is_array($_ci_vars))
			{
				$this->_ci_cached_vars = array_merge($this->_ci_cached_vars, $_ci_vars);
			}
			extract($this->_ci_cached_vars);
					
			/*
			 * Buffer the output
			 *
			 * We buffer the output for two reasons:
			 * 1. Speed. You get a significant speed boost.
			 * 2. So that the final rendered template can be
			 * post-processed by the output class.  Why do we
			 * need post processing?  For one thing, in order to
			 * show the elapsed page load time.  Unless we
			 * can intercept the content right before it's sent to
			 * the browser and then stop the timer it won't be accurate.
			 */
			ob_start();
					
			// If the PHP installation does not support short tags we'll
			// do a little string replacement, changing the short tags
			// to standard PHP echo statements.
			
			if ((bool) @ini_get('short_open_tag') === FALSE AND config_item('rewrite_short_tags') == TRUE)
			{
				echo eval('?>'.preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?=', '<?php echo ', file_get_contents($_ci_path))));
			}
			else
			{
				/** NJM: MODIFIED BY ETHIX SYSTEMS **/
				if(!@include($_ci_path)) // include() vs include_once() allows for multiple views with the same name
					include(CI_INCLUDE_PATH.'views/'.$_ci_file);
				/** NJM: END MODIFICATION **/
			}
			
			log_message('debug', 'File loaded: '.$_ci_path);
			
			// Return the file data if requested
			if ($_ci_return === TRUE)
			{		
				$buffer = ob_get_contents();
				@ob_end_clean();
				return $buffer;
			}
	
			/*
			 * Flush the buffer... or buff the flusher?
			 *
			 * In order to permit views to be nested within
			 * other views, we need to flush the content back out whenever
			 * we are beyond the first level of output buffering so that
			 * it can be seen and included properly by the first included
			 * template and any subsequent ones. Oy!
			 *
			 */	
			if (ob_get_level() > $this->_ci_ob_level + 1)
			{
				ob_end_flush();
			}
			else
			{
				// PHP 4 requires that we use a global
				global $OUT;
				$OUT->append_output(ob_get_contents());
				@ob_end_clean();
			}
		}
		
		/**
		 * Load class
		 *
		 * This function loads the requested class.
		 *
		 * @access	private
		 * @param 	string	the item that is being loaded
		 * @param	mixed	any additional parameters
		 * @param	string	an optional object name
		 * @return 	void
		 */
		function _ci_load_class($class, $params = NULL, $object_name = NULL)
		{	
			// Get the class name, and while we're at it trim any slashes.  
			// The directory path can be included as part of the class name, 
			// but we don't want a leading slash
			$class = str_replace(EXT, '', trim($class, '/'));
		
			// Was the path included with the class name?
			// We look for a slash to determine this
			$subdir = '';
			if (strpos($class, '/') !== FALSE)
			{
				// explode the path so we can separate the filename from the path
				$x = explode('/', $class);	
				
				// Reset the $class variable now that we know the actual filename
				$class = end($x);
				
				// Kill the filename from the array
				unset($x[count($x)-1]);
				
				// Glue the path back together, sans filename
				$subdir = implode($x, '/').'/';
			}
	
			// We'll test for both lowercase and capitalized versions of the file name
			foreach (array(ucfirst($class), strtolower($class)) as $class)
			{
				$subclass = APPPATH.'libraries/'.$subdir.config_item('subclass_prefix').$class.EXT;
				/** NJM: ETHIX SYSTEMS MODIFICATION **/
				$subclasslibver = CI_INCLUDE_PATH.'libraries/'.CI_VERSION.'/'.$subdir.config_item('subclass_prefix').$class.EXT;
				$subclasslib = CI_INCLUDE_PATH.'libraries/'.$subdir.config_item('subclass_prefix').$class.EXT;
	
				// Is this a class extension request?			
				if (file_exists($subclass) || file_exists($subclasslibver) || file_exists($subclasslib))
				/** END ETHIX SYSTEMS MODIFICATION **/
				{
					$baseclass = BASEPATH.'libraries/'.ucfirst($class).EXT;
					
					if ( ! file_exists($baseclass))
					{
						log_message('error', "Unable to load the requested class: ".$class);
						show_error("Unable to load the requested class: ".$class);
					}
	
					// Safety:  Was the class already loaded by a previous call?
					/** NJM: ETHIX SYSTEMS MODIFICATION **/
					if (in_array($subclass, $this->_ci_loaded_files) || in_array($subclasslibver, $this->_ci_loaded_files) || in_array($subclasslib, $this->_ci_loaded_files))
					/** END ETHIX SYSTEMS MODIFICATION **/
					{
						// Before we deem this to be a duplicate request, let's see
						// if a custom object name is being supplied.  If so, we'll
						// return a new instance of the object
						if ( ! is_null($object_name))
						{
							$CI =& get_instance();
							if ( ! isset($CI->$object_name))
							{
								return $this->_ci_init_class($class, config_item('subclass_prefix'), $params, $object_name);			
							}
						}
						
						$is_duplicate = TRUE;
						log_message('debug', $class." class already loaded. Second attempt ignored.");
						return;
					}
		
					include_once($baseclass);
					/** NJM: ETHIX SYSTEMS MODIFICATION **/
					if(file_exists($subclass))			
						include_once($subclass);
					elseif(file_exists($subclasslibver))
						include_once($subclasslibver);
					else
						include_once($subclasslib);
					
					/** END ETHIX SYSTEMS MODIFICATION **/
					$this->_ci_loaded_files[] = $subclass;
		
					return $this->_ci_init_class($class, config_item('subclass_prefix'), $params, $object_name);			
				}
			
				// Lets search for the requested library file and load it.
				$is_duplicate = FALSE;		
				for ($i = 1; $i < 3; $i++)
				{
					$path = ($i % 2) ? APPPATH : BASEPATH;	
					$filepath = $path.'libraries/'.$subdir.$class.EXT;
					/** NJM: ETHIX SYSTEMS MODIFICATION **/
					$filepathlib = ($path == APPPATH) ? CI_INCLUDE_PATH.'libraries/'.$subdir.$class.EXT : '';
					
					// Does the file exist?  No?  Bummer...
					if ( ! file_exists($filepath) && ! file_exists($filepathlib))
					/** END ETHIX SYSTEMS MODIFICATION **/
					{
						continue;
					}
					
					// Safety:  Was the class already loaded by a previous call?
					/** NJM: ETHIX SYSTEMS MODIFICATION **/
					if (in_array($filepath, $this->_ci_loaded_files) || in_array($filepath, $this->_ci_loaded_files))
					/** END ETHIX SYSTEMS MODIFICATION **/
					{
						// Before we deem this to be a duplicate request, let's see
						// if a custom object name is being supplied.  If so, we'll
						// return a new instance of the object
						if ( ! is_null($object_name))
						{
							$CI =& get_instance();
							if ( ! isset($CI->$object_name))
							{
								return $this->_ci_init_class($class, '', $params, $object_name);
							}
						}
					
						$is_duplicate = TRUE;
						log_message('debug', $class." class already loaded. Second attempt ignored.");
						return;
					}
					
					/** NJM: ETHIX SYSTEMS MODIFICATION **/
					if(!@include_once($filepath)):
						include_once($filepathlib);
						$this->_ci_loaded_files[] = $filepathlib;
					else:
						$this->_ci_loaded_files[] = $filepath;
					endif;
					/** END ETHIX SYSTEMS MODIFICATION **/
					
					return $this->_ci_init_class($class, '', $params, $object_name);
				}
			} // END FOREACH
	
			// One last attempt.  Maybe the library is in a subdirectory, but it wasn't specified?
			if ($subdir == '')
			{
				$path = strtolower($class).'/'.$class;
				return $this->_ci_load_class($path, $params);
			}
			
			// If we got this far we were unable to find the requested class.
			// We do not issue errors if the load call failed due to a duplicate request
			if ($is_duplicate == FALSE)
			{
				log_message('error', "Unable to load the requested class: ".$class);
				show_error("Unable to load the requested class: ".$class);
			}
		}
	}
?>