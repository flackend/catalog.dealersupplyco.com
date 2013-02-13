<?php /**
	 * This helper extends the built-in CI security helper and contains PHP functions
	 * designed to enhance security in PHP applications.
	 * 
	 * @package CI_GeneralESLibs
	 * @subpackage Helpers
	 * @category Helpers
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @since 2009-05-20 
	 * @version 2009-05-20
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 */

	if ( ! function_exists('salt_hash'))
	{
		/**
		 * This function will take a salt hash, and will append it to the password before running it through md5
		 *
		 * @author Daniel Carr <decarr@ethixsystems.com>
		 * @since 2009-05-20
		 * @version 2009-06-30 
		 * 
		 * @param string $Password The password to be encrypted
		 * @param string $Hash The hash to be used in appending to the md5
		 * @return string The md5-salt-hash-encrypted password
		 */
		function salt_hash($Password, $Hash = 'jIF7^5jad(89sjD')
		{
			return md5($Hash.html_entity_decode($Password, ENT_QUOTES));
		}
	}
?>