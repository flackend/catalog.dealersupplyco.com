<?php
	/**
	 * This library is an exntesion of the built-in CodeIgniter Language library.
	 * This will only be used by CodeIgniter projects and houses commented custom
	 * modifications to this built-in library.
	 * Modification Objectives: Be able to reference language definitions associated to helpers in the custom php_lib repository 
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
	 * MY_Language sublibrary. Contains all modifications to the base CI_Language class.
	 * 
	 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
	 * @since 2009-04-28
	 * @version 2009-04-28
	 */
	class ES_Language extends CI_Language
	{
		/**
		 * Load a language file
		 *
		 * @access	public
		 * @param	mixed	the name of the language file to be loaded. Can be an array
		 * @param	string	the language (english, etc.)
		 * @return	mixed
		 */
		function load($langfile = '', $idiom = '', $return = FALSE)
		{
			$langfile = str_replace(EXT, '', str_replace('_lang.', '', $langfile)).'_lang'.EXT;
	
			if (in_array($langfile, $this->is_loaded, TRUE))
			{
				return;
			}
	
			if ($idiom == '')
			{
				$CI =& get_instance();
				$deft_lang = $CI->config->item('language');
				$idiom = ($deft_lang == '') ? 'english' : $deft_lang;
			}
			
			// NJM: ETHIX SYSTEMS MODIFICATION
			// Determine where the language files are and load them
			$AFileExists = false;
			
			if (file_exists(BASEPATH.'language/'.$idiom.'/'.$langfile))
			{
				$AFileExists = true;
				include(BASEPATH.'language/'.$idiom.'/'.$langfile);
			}
			
			if (file_exists(CI_INCLUDE_PATH.'language/'.$idiom.'/'.$langfile))
			{
				$AFileExists = true;
				include(CI_INCLUDE_PATH.'language/'.$idiom.'/'.$langfile);
			}
			
			if (file_exists(APPPATH.'language/'.$idiom.'/'.$langfile))
			{
				echo "Here";
				$AFileExists = true;
				include(APPPATH.'language/'.$idiom.'/'.$langfile);
			}
			
			if(!$AFileExists)
			{
				show_error('Unable to load the requested language file: language/'.$langfile);
			}
			// END ETHIX SYSTEMS MODIFICATION
	
			if ( ! isset($lang))
			{
				log_message('error', 'Language file contains no data: language/'.$idiom.'/'.$langfile);
				return;
			}
	
			if ($return == TRUE)
			{
				return $lang;
			}
	
			$this->is_loaded[] = $langfile;
			$this->language = array_merge($this->language, $lang);
			unset($lang);
	
			log_message('debug', 'Language file loaded: language/'.$idiom.'/'.$langfile);
			return TRUE;
		}
	}
?>