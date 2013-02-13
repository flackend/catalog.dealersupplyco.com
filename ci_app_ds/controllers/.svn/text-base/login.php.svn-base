<?php
	/**
	 * This file contains the controller for all login functionality.
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

	require_once('controllers/ESBaseLogin.php');

	/**
	 * Login controller.  Contains all routines for login functionality. 
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @since 2009-03-17
	 * @version 2009-05-05
	 */
	class Login extends ESBaseLogin
	{
		
		/**
		 * Login class constructor.  This constructor implements the ESBaseLogin controller for
		 * its login functionality.  Admin functionality is segregated for this application,
		 * there is no registration support, and no controller-based redirect support.
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-03-17
		 * @version 2009-05-05
		 * 
		 * @return Login Instanciated Login controller class
		 */
		function __construct()
		{
			$this->UserModel = "User";
			$this->LoginController = "Login";
			$this->LogoutController = "Logout";
			// default view folder 
			// no redirect support 
			// no registration support 
			// admin functionality not segragated 
			$this->AdminRedirectController = "Admin";
			$this->DefaultRedirectController = "Catalog";
			parent::__construct();
		}
		
	}
?>