<?php
	/**
	 * This file contains the controller for all logout functionality.
	 * Users will be redirected to this controller anytime any part of any user login
	 * validation check fails.
	 * 
	 * @package CI_DSSalesRepApp
	 * @subpackage Controllers
	 * @category Controllers
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @since 2009-03-17
	 * @version rev116
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 */

	require_once('controllers/ESBaseLogout.php');

	/**
	 * Logout controller.  Contains all routines for logout functionality. 
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @since 2009-03-17
	 * @version 2009-05-05
	 */
	class Logout extends ESBaseLogout
	{
		/**
		 * Logout class constructor.  This constructor implements the ESBaseLogout controller
		 * for its logout functionality.  Logout redirects will be to the login page.
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-03-17
		 * @version 2009-05-05
		 * 
		 * @return Logout Instanciated Logout controller class
		 */
		function __construct()
		{
			$this->LogoutController = "Logout";
			$this->LoginController = "Login";
			// default logout redirect controller is the login page 
			parent::__construct();
		}
		
		/**
		 * This function will perform any application specific session variable
		 * deletions before destroying the session at the base controller level.
		 *
		 * @access protected
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-03-17
		 * @version 2009-05-05
		 */
		protected function _destroy_session()
		{
			// @todo Destroy any custom session variables here
			parent::_destroy_session();
		}
		
	}
?>