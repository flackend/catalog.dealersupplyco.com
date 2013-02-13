<?php
	/**
	 * This helper is designed specifically for validating content found in the
	 * $_FILES array for form submission.  By default, it will be assumed that
	 * the content being validated is image content, but this can be easily
	 * overridden through a parameter in the function below.
	 * 
	 * @package CI_GeneralESLibs
	 * @subpackage Helpers
	 * @category Helpers
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @since 2009-04-23
	 * @version 2009-04-23
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 */

	if ( ! function_exists('validate_file_upload'))
	{
		/**
		 * This helper function will validate any file content supplied to the server
		 * through a multipart encoded web form using the $_FILES array.  Currently,
		 * this function will need to be called for each file that needs validated.
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-04-23
		 * @version 2009-04-23
		 * 
		 * @access public
		 * 
		 * @param string $form_file_name The name of the input field in the multipart form that contains the file being validated
		 * @param bool $file_form_name_required Indicates if the input field on the form needs to exist or not (sometimes there will be logic to hide the input in place of the file itself, like images for example, so the input won't be defined)
		 * @param bool $file_required Indicates if, when the input field actually exists, if a user is required to choose a file for upload, as there could be a case where supplying an image is optional
		 * @param int $maximum_file_size The maximum size the file can be, in bytes (this cannot be larger than the upload_max_filesize directive set in php.ini)
		 * @param array $accepted_mime_types An array indexed by accepted mime types for this file upload along with their display names.  If this is not supplied, it will be assumed you are uploading a basic image file (JPEG,PNG,GIF)
		 * @return array return[0]=bool True if the email was sent successfully; False otherwise.  return[1]=string Any messages generated as a result to the success/failure of this call
		 */	
		function validate_file_upload($form_file_name, $file_form_name_required = true, $file_required = true, $maximum_file_size = 2097152, $accepted_mime_types = "")
		{
			// see if we need to use the default mime types
			if(gettype($accepted_mime_types) != "array"):
				$accepted_mime_types = array(
					'image/jpeg' => 'JPEG',
					'image/pjpeg' => 'JPEG',
					'image/png' => 'PNG',
					'image/x-png' => 'PNG',
					'image/gif' => 'GIF'
				);
			endif;
			
			if(@$FileResource = $_FILES[$form_file_name]):
				if($FileResource['error'] == UPLOAD_ERR_OK): // 0 means the file did not generate any normal PHP file upload errors
					if($FileResource['size'] > 0):
						if($FileResource['size'] <= $maximum_file_size):
							if(is_uploaded_file($FileResource['tmp_name'])):
								if(array_key_exists($FileResource['type'], $accepted_mime_types)):
									// file supplied and all checks passed!
									return array(true, '');
								else:
									return array(false, 'Not a valid file type <span style="font-size:12px;">(supported types: '.implode(', ',array_unique($accepted_mime_types)).')</span>');
								endif;
							else:
								return array(false, 'Invalid or corrupt file');
							endif;
						else:
							return array(false, 'The file cannot be bigger than '.(floor($maximum_file_size/1048576)).'MB');
						endif;
					else:
						return array(false, 'The file you supplied is empty');
					endif;
				elseif($FileResource['error'] == UPLOAD_ERR_INI_SIZE): // 1 means the file was too big according to the parameter in php.ini upload_max_filesize
					return array(false, 'The file cannot be bigger than '.ini_get('upload_max_filesize').'B');
				// UPLOAD_ERR_FORM_SIZE (2) is not checked since we define max file size in constants.php, not in the calling form
				elseif($FileResource['error'] == UPLOAD_ERR_PARTIAL): // 3 means that the file did not upload completely due to some sort of network, client, server, or other interuption
					return array(false, 'The file upload was interrupted unexpectedly, please try again');
				elseif($FileResource['error'] == UPLOAD_ERR_NO_FILE): // 4 means no file was supplied at all
					// only if the file is required will we error here
					if($file_required):
						return array(false, 'Required');
					else:
						return array(true, '');
					endif;
				elseif($FileResource['error'] == UPLOAD_ERR_NO_TMP_DIR): // 5 means that no writable temporary directory is defined for form file upload; please fix before making this code live!
					return array(false, 'Your server was not configured properly for file upload (ERROR: NOTMPDIR)');
				elseif($FileResource['error'] == UPLOAD_ERR_CANT_WRITE): // 6 means that the temp directory for file upload exists, but couldn't be written to; please fix this before making this code live!
					return array(false, 'Your server was not configured properly for file upload (ERROR: TMPDIRWRITE)');
				endif;
			elseif(!$file_form_name_required):
				// this occurrs when the actual form input for this file doesn't exist and it is deemed ok that this can happn.  This is usually because there is already an file uploaded for this item and it is not being changed
				return array(true, '');
			endif;
		
			// some other error, posting from wrong form, or just missed condition somewhere... return generic error
			return array(false, 'Unknown file error');
		}
	}
?>