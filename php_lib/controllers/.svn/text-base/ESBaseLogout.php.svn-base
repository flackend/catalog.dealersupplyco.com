<?php
	/**
	 * This file contains a base CI controller for generalized logout functionality.
	 * This class cannot be directly instantiated, instead there needs to be a
	 * local controller implementation of it inside of your project.  Also, any
	 * functions here can be overwritten or extended as needed.
	 * 
	 * @package CI_GeneralESLibs
	 * @subpackage Controllers
	 * @category Controllers
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @since 2009-05-05
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 */

	/**
	 * ESBaseLogout controller.  Contains an abstract set of routines for logout functionality.
	 * 
	 * @abstract
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @since 2009-05-05
	 * @version 2009-05-20
	 */
	abstract class ESBaseLogout extends Controller
	{
		
		/**
		 * @access protected
		 * @var string The name of the controller extending this base logout controller in the specific project
		 */
		protected $LogoutController = null;
		
		/**
		 * @access protected
		 * @var string The name of the controller extending the base ESBaseLogin controller in this project that will handle all login requests
		 */
		protected $LoginController = null;
		
		/**
		 * @access protected
		 * @var string An optional controller to redirect to after logging out; by default, redirect will be to the login page
		 */
		protected $LogoutRedirectController = null;
		
		/**
		 * ESBaseLogout class constructor.  The constructor loads all needed libraries and helper functions
		 * for use in the entire class.
		 *
		 * @internal
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-05
		 * @version 2009-05-19
		 */
		function __construct()
		{
			if(!isset($this->LogoutController) || !isset($this->LoginController) || !defined('APP_IDENT'))
				die('The logout controller '.$this->LogoutController.' was defined using ESBaseLogin without proper configuration.');
			
			parent::Controller();
			
			if(!isset($this->LogoutRedirectController))
				$this->LogoutRedirectController = strtolower($this->LoginController).'/doLogin';
			else
				$this->LogoutRedirectController = strtolower($this->LogoutRedirectController);
			
			$this->load->library('session');
			$this->load->helper('url');
		}
		
		/**
		 * This is the function that is loaded when no other action is specified.
		 * This works similarly to going to a webpage and not specifying the page you want
		 * and getting index.xxx by default.  Since logging out really only has one function,
		 * this is where the main routine for logging out is found.  The session will be
		 * destroyed and the user redirected back to the non-logged in home page.
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-05
		 * @version 2009-05-22
		 */
		function index()
		{
			$this->_destroy_session();
			redirect('/'.$this->LogoutRedirectController);
		}
		
		/**
		 * This function is called when the session times out from one of the various other
		 * application controllers.  This function will set a message for the login page to display
		 * after being logged out.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-05
		 * @version 2009-05-29
		 */
		function session_exp()
		{
			$this->_destroy_session();
			$this->session->set_flashdata(APP_IDENT.'_RedirectReason', '|C|Your session has timed out.  Please login again.');
			redirect('/'.strtolower($this->LogoutRedirectController).'/doLogin');
		}
		
		/**
		 * This function is called when concurrency is a factor in the application and the
		 * session logged in has been overridden by a login under the same account from another
		 * computer / web browser.  This function will set a message for the login page to display
		 * after being logged out.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-20
		 * @version 2009-05-29
		 */
		function session_dup()
		{
			$this->_destroy_session();
			$this->session->set_flashdata(APP_IDENT.'_RedirectReason', '|C|This account has been logged in to from another location.');
			redirect('/'.strtolower($this->LogoutRedirectController).'/doLogin');
		}
		
		/**
		 * This is an action function that destroys the encrypted cookie on the user's computer.
		 * It does not remove the session from the tracking database as this is handled internally
		 * by the CodeIgniter framework garbadge collection processes.
		 *
		 * @access protected
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-05
		 * @version 2009-05-20
		 */
		protected function _destroy_session()
		{
			$this->session->unset_userdata(APP_IDENT.'_UserNum');
			$this->session->unset_userdata(APP_IDENT.'_PasswordHash');
			$this->session->unset_userdata(APP_IDENT.'_RandomHash');
			$this->session->unset_userdata(APP_IDENT.'_PseudoSession');
			$this->session->sess_destroy();
			$this->session->sess_create();
		}
		
	}
?>