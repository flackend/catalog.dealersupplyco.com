<?php
	/**
	 * This file contains a base CI controller for generalized login functionality.
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
	 * ESBaseLogin controller.  Contains an abstract set of routines for login functionality.
	 * 
	 * @abstract
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @since 2009-05-05
	 * @version 2009-05-26
	 */
	abstract class ESBaseLogin extends Controller
	{
		
		/**
		 * @access protected
		 * @var string The name of the local project model that houses functionality for users, preferably and extension of the ESBaseUser model
		 */
		protected $UserModel = null;
		
		/**
		 * @access protected
		 * @var string The name of the controller extending this base login controller in the specific project
		 */
		protected $LoginController = null;
		
		/**
		 * @access protected
		 * @var string The name of the controller extending the base ESBaseLogout controller in this project that will handle all logout requests
		 */
		protected $LogoutController = null;
		
		/**
		 * @access protected
		 * @var string The location of the HTML views for the login pages, will default to the login controller name if not specified
		 */
		protected $ViewFolder = null;
		
		/**
		 * @access protected
		 * @var bool True if the login functionaliy is permitted to use the uri segment following "doLogin" as a redirect controller source
		 */
		protected $EnableRedirect = false;

		/**
		 * @access protected
		 * @var bool True to allow registration routines to be enabled, false otherwise
		 */
		protected $EnableRegistration = false;
		
		/**
		 * @access protected
		 * @var int The custom offset for registration user pks associated to this project.  This is needed so that no pk trend for registration pages can be determined between any two Ethix Systems developed projects
		 */
		protected $RegistrationOffsetSeed = null;
		
		/**
		 * @access protected
		 * @var bool True if administrators of this application have a completely separate set of controllers/functionality as normal users, False if they share the same controller logic and the controllers determine what specifically to do with administrators
		 */
		protected $AdminSegregation = true;
		
		/**
		 * @access protected
		 * @var string Only needed if $AdminSegregation is true; defines what controller administrators will always get redirected to
		 */
		protected $AdminRedirectController = null;
		
		/**
		 * @access protected
		 * @var string Required; defines what controller the system will redirect a user to by default
		 */
		protected $DefaultRedirectController = null;
		
		/**
		 * @access protected
		 * @var int Counting flag for the pseudo-global {@link Login::_valid_login()} function.
		 */
		protected $CheckLogin = 0;
		
		/**
		 * ESBaseLogin class constructor.  The constructor loads all needed libraries and helper functions
		 * for use in the entire class.  It also double checks that the user isn't already logged in.
		 * If they are, it will automatically redirect them to where they need to be.
		 *
		 * @internal
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-05
		 * @version 2009-05-27
		 */
		function __construct()
		{
			if(!isset($this->UserModel) || !isset($this->LoginController) || !isset($this->LogoutController) || !defined('APP_IDENT') || !isset($this->DefaultRedirectController))
				die('The login controller '.$this->LoginController.' was defined using ESBaseLogin without proper configuration.');
			
			if($this->EnableRegistration && !isset($this->RegistrationOffsetSeed))
				die('The login controller '.$this->LoginController.' was defined using a registration enabled version of ESBaseLogin without setting the registration offset seed.');
				
			parent::Controller();
			
			$this->LoginController = strtolower($this->LoginController);
			if(!isset($this->ViewFolder))
				$this->ViewFolder = $this->LoginController;
			
			$this->load->library('session');
			$this->load->model($this->UserModel);
			$this->load->helper('url');
			if($this->EnableRegistration):
				$this->load->helper('validation');
				$this->load->plugin('recaptcha');
				$this->load->helper('email');
			endif;
			
			if($this->session->userdata(APP_IDENT.'_UserNum')):
				$ModuleRedirect = 0;
				if($this->uri->segment(2)==="doLogin" && $this->uri->segment(3)!=""):
					$ModuleRedirect = $this->uri->segment(3);
				endif;
				$this->_login_redirect($ModuleRedirect);
			endif;
			
			$this->load->library('form_validation');
			$this->load->helper('validation');
			$this->load->view($this->ViewFolder.'/header');
		}
		
		/**
		 * This is the function that is loaded when no other action is specified.
		 * This works similarly to going to a webpage and not specifying the page you want
		 * and getting index.xxx by default.  This default login routine is called when
		 * no specific logged in destination is desired (or is an admin login) and will merely
		 * redirect the call to the default {@link ESBaseLogin::doLogin()} call.
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-05
		 * @version 2009-05-05
		 */
		function index()
		{
			redirect($this->ViewFolder.'/doLogin');
		}
		
		/**
		 * This function is the main login function for all users accessing the site.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-05
		 * @version 2009-05-26
		 *
		 * @param mixed $ControllerRedirect This value holds what controller the user should be redirected to after a successful login.  This should be 0 for an undirected login
		 */
		function doLogin($ControllerRedirect = 0)
		{	
			if(!$this->EnableRedirect)
				$ControllerRedirect = 0; // redirecting is disabled here
			
			$this->form_validation->set_rules('UserName','User Name','trim|required|callback__valid_login');
			$this->form_validation->set_rules('Password','Password','trim|required|callback__valid_login');
			if($this->form_validation->run() == FALSE):
				$this->load->view($this->ViewFolder."/login");
			else:
				$UserPK = $this->User->getPKFromFieldValue('U_UserName', set_value('UserName'));
				$this->_create_session($UserPK);
				// remove any password reset hash for this account, as the user must know their password to have logged in
				$this->User->update($UserPK, array('PasswordResetToken' => ''));
				// @todo should login tracking be more extensive?
				$this->User->trackLogin($UserPK);
				$this->_login_redirect($ControllerRedirect);
			endif;
			
			$this->load->view($this->ViewFolder.'/footer');
		}
		
		/**
		 * This function will display the "register.php" file contained in the project's
		 * login view folder and process registration requests for the site.  This can only be
		 * referenced when a user is not already logged in.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-18
		 * @version 2009-05-26
		 * 
		 * @param string $Status If the string "done" is passed to the register module, the registration success page will be shown, otherwise the form will be shown
		 * @param int $NewUserNum This value is used only for the register success page and represents a custom offset of the user id of the account just created
		 */
		function register($Status = "", $NewUserNum = 0)
		{
			if(!$this->EnableRegistration)
				die('The login controller '.$this->LoginController.' tried to access the "register" function.  However, EnableRegistration is not enabled for this controller.');
			
			if($Status==="done"):
				if($NewUserNum == 0) $NewUserNum = $this->RegistrationOffsetSeed+412;
				if($this->{$this->UserModel}->exists($NewUserNum-($this->RegistrationOffsetSeed+412))):
					if(!$this->{$this->UserModel}->getFieldValueFromPK('Verified',$NewUserNum-($this->RegistrationOffsetSeed+412))):
						$RegisterSuccessViewData["NewUserNum"] = ($NewUserNum-($this->RegistrationOffsetSeed+412))+($this->RegistrationOffsetSeed+412);
						$this->load->view($this->ViewFolder.'/register_success', $RegisterSuccessViewData);
						$this->load->view($this->ViewFolder.'/footer');
					else:
						redirect('/'.$this->LoginController);
					endif;
				else:
					redirect('/'.$this->LoginController);
				endif;
			else:			
				$this->form_validation->set_rules('FirstName','First Name','trim|required|max_length[255]');
				$this->form_validation->set_rules('LastName','Last Name','trim|required|max_length[255]');
				$this->form_validation->set_rules('PhoneNumber','Phone Number','trim|required|max_length[255]|callback__is_phone_number');
				$this->form_validation->set_rules('EmailAddress1','Email Address','trim|required|max_length[255]|valid_email|matches[EmailAddress2]|callback__unique_email_address');
				$this->form_validation->set_rules('EmailAddress2','Confirm Email Address','trim|required');
				$this->form_validation->set_rules('UserName','Username','trim|required|min_length[6]|max_length[255]|callback__unique_user_name');
				$this->form_validation->set_rules('Password1','Password','trim|required|min_length[6]|max_length[255]|matches[Password2]');
				$this->form_validation->set_rules('Password2','Confirm Password','trim|required');
				if($this->form_validation->run() != FALSE):
					// perform user creation if all is well
					if($NewUserPK = $this->_create_user()):
						$this->_send_new_account_email();
						redirect($this->LoginController.'/register/done/'.($NewUserPK+($this->RegistrationOffsetSeed+412)));
					else:
						$RegistrationFormData["DBError"] = $this->session->flashdata(APP_IDENT.'_LoginRegisterErrorMessage');
					endif;
				endif;
				
				$this->load->view($this->ViewFolder.'/register',$RegistrationFormData);
				$this->load->view($this->ViewFolder.'/footer');	
			endif;	
		}
		
		/**
		 * This function will display the "forgotpassword.php" file contained in the project's
		 * login view folder and process any forgot password request by looking up the user's
		 * account information and emailing them a password with a link for them to reset their
		 * password with.  Since all passwords are stored on the database in a non-reversible standard,
		 * there is no way to actually retrieve the password for them.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-18
		 * @version 2009-06-02
		 * 
		 * @param string $Status If the string "done" is passed to the forgotpassword module, the forgotpassword success page will be shown, otherwise the form will be shown
		 */
		function forgotpassword($Status = "")
		{
			if(!$this->EnableRegistration)
				die('The login controller '.$this->LoginController.' tried to access the "forgotpassword" function.  However, EnableRegistration is not enabled for this controller.');
			
			if($Status == "done"):
				$this->load->view($this->ViewFolder.'/forgotpassword_success');
				$this->load->view($this->ViewFolder.'/footer');
			else:
				$ForgotPasswordFormData = array();
				$this->form_validation->set_rules('UserName','User Name','trim|required');
				$this->form_validation->set_rules('EmailAddress','Email Address','trim|required|callback__email_address_exists_on_this_account');
				$this->form_validation->set_rules('recaptcha_challenge_field','','');
				$this->form_validation->set_rules('recaptcha_response_field','','callback__valid_recaptcha_code');
				if($this->form_validation->run() != FALSE):
					$UserNum = $this->{$this->UserModel}->getPKFromFieldValue('U_EmailAddress', set_value('EmailAddress'));
					$PasswordResetToken = md5(time().set_value('EmailAddress').rand().($UserNum+4));
					if($this->_send_forgot_password_email($UserNum, $PasswordResetToken) === TRUE):
						$this->{$this->UserModel}->update($UserNum, array("PasswordResetToken" => $PasswordResetToken));
						redirect($this->LoginController.'/forgotpassword/done');
					else:
						$ForgotPasswordFormData["CustomError"] = "There was an unknown error encountered while retrieving your account information.  Please try again later.";
					endif;
				endif;
				
				$this->load->view($this->ViewFolder.'/forgotpassword',$ForgotPasswordFormData);
				$this->load->view($this->ViewFolder.'/footer');
			endif;	
		}
		
		/**
		 * This function will display the "passwordreset.php" file contained in the project's
		 * login view folder after a user clicks the link in their email to complete their password
		 * reset for their account on this site.  This function will carry-out the actual password change.
		 * Also, if a user requests successive reset requests from forgotpassword, this function will
		 * only acknoledge the most recent request, and all old emails won't work to reset the password.
		 * Also, once the password is reset using this method, all email links sent to them will be
		 * deactivated; a new request will need to be made to reset the password in this manner.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-18
		 * @version 2009-05-27
		 * 
		 * @param string $PasswordResetToken This string is an authorization hash that must match on the user table for the given user account before a password reset will be acknowledged; this was generated when the last password reset request was initiated
		 * @param int $UserNum This value is used as an offset of the actual primary key for the user requesting to have their password reset
		 */
		function passwordreset($PasswordResetToken = "", $UserNum = 0)
		{
			if(!$this->EnableRegistration)
				die('The login controller '.$this->LoginController.' tried to access the "passwordreset" function.  However, EnableRegistration is not enabled for this controller.');
			
			if($UserNum == 0) $UserNum = $this->RegistrationOffsetSeed+742;
			if($this->{$this->UserModel}->exists($UserNum-($this->RegistrationOffsetSeed+742)) && $PasswordResetToken != ""):
				if($this->{$this->UserModel}->getFieldValueFromPK('PasswordResetToken', ($UserNum-($this->RegistrationOffsetSeed+742))) == $PasswordResetToken):
					$PasswordResetFormData = array();
					$this->form_validation->set_rules('Password1','New Password','trim|required|min_length[6]|max_length[255]|matches[Password2]');
					$this->form_validation->set_rules('Password2','Confirm New Password','trim|required');
					if($this->form_validation->run() != FALSE):
						if(($ResetSuccess = $this->{$this->UserModel}->changePassword(($UserNum-($this->RegistrationOffsetSeed+742)), set_value('Password1'))) === 1):
							$this->{$this->UserModel}->update($UserNum-($this->RegistrationOffsetSeed+742), array('PasswordResetToken' => ''));
							$this->_send_password_reset_email();
							$this->session->set_flashdata(APP_IDENT.'_LoginSuccessMessage','Your password has been reset');
							redirect($this->ViewFolder.'/doLogin');
						elseif($ResetSuccess === 0):
							$PasswordResetFormData['CustomError'] = "The password you entered is already the password currently assigned to this account.";
						else:
							$PasswordResetFormData['CustomError'] = "There was an unknown error encoutered while resetting your password.  Please try again later.";
						endif;
					endif;
					
					$this->load->view($this->ViewFolder.'/passwordreset',$PasswordResetFormData);
					$this->load->view($this->ViewFolder.'/footer');
					return;
				endif;
			endif;
			redirect($this->LoginController.'/doLogin');
		}
		
		/**
		 * This function will display the "forgotusername.php" file contained in the project's
		 * login view folder and process any request for a user's forgotten user name.
		 * An email will be sent containing the user's email address to the email they signed up
		 * on the site with.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-18
		 * @version 2009-06-02
		 * 
		 * @param string $Status If the string "done" is passed to the forgotusername module, the forgotusername success page will be shown, otherwise the form will be shown
		 */
		function forgotusername($Status = "")
		{
			if(!$this->EnableRegistration)
				die('The login controller '.$this->LoginController.' tried to access the "forgotusername" function.  However, EnableRegistration is not enabled for this controller.');
			
			if($Status == "done"):
				$this->load->view($this->ViewFolder.'/forgotusername_success');
				$this->load->view($this->ViewFolder.'/footer');
			else:
				$ForgotUserNameFormData = array();
				$this->form_validation->set_rules('EmailAddress','Email Address','trim|required|callback__email_address_exists_on_an_account');
				$this->form_validation->set_rules('recaptcha_challenge_field','','');
				$this->form_validation->set_rules('recaptcha_response_field','','callback__valid_recaptcha_code');
				if($this->form_validation->run() != FALSE):
					if($this->_send_forgot_username_email() === TRUE):
						redirect($this->LoginController.'/forgotusername/done');
					else:
						$ForgotUserNameFormData["CustomError"] = "There was an unknown error encountered while retrieving your user name.  Please try again later.";
					endif;
				endif;
				
				$this->load->view($this->ViewFolder.'/forgotusername',$ForgotUserNameFormData);
				$this->load->view($this->ViewFolder.'/footer');
			endif;	
		}
		
		/**
		 * This function will resend an activation email to a user who has not activated
		 * their account yet and requests one sent to them after trying to login or after
		 * not successfully receiving one after signing up initially.  Activation codes
		 * are created once and saved on the database for that user until they activate.
		 * This function will not create a new code for this purpose.
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-18
		 * @version 2009-05-19
		 * 
		 * @param int $UserPK An offset of the id of the user to send the activation email to
		 */		
		function activationemail($UserPK = 0)
		{
			if(!$this->EnableRegistration)
				die('The login controller '.$this->LoginController.' tried to access the "activationemail" function.  However, EnableRegistration is not enabled for this controller.');
			
			if($UserPK == 0) $UserPK = $this->RegistrationOffsetSeed+976;
			if($this->{$this->UserModel}->exists($UserPK-($this->RegistrationOffsetSeed+976))):
				if(!$this->{$this->UserModel}->getFieldValueFromPK('Verified', $UserPK-($this->RegistrationOffsetSeed+976))):
					$this->NewUserPK = $UserPK-($this->RegistrationOffsetSeed+976);
					if($this->_send_new_account_email() === TRUE):
						$this->session->set_flashdata(APP_IDENT.'_LoginSuccessMessage','An email containing your account activation link has been sent to the email address we have on file.<br />Follow the link in that email to activate your account.');
					else:
						$this->session->set_flashdata(APP_IDENT.'_SessionExpiredMessage','There was an error sending the activation email.  Please try again shortly.');
					endif;
				endif;
			endif;
			redirect('/'.$this->LoginController);
		}
		
		/**
		 * This function will activate a user's account based on a link sent to them in an email
		 * upon creating their account or requesting the link again.  The hash passed to this
		 * function must match the activation hash that was created for that user on the database
		 * when their account was first created in the system.
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-18
		 * @version 2009-05-19
		 * 
		 * @param int $UserPK Represents the id of the user account to activate
		 * @param string $ActivationCode The activation code to activate this account
		 */
		function activateacct($UserPK = 0, $ActivationCode = "")
		{
			if(!$this->EnableRegistration)
				die('The login controller '.$this->LoginController.' tried to access the "activateacct" function.  However, EnableRegistration is not enabled for this controller.');
			
			if($UserPK == 0) $UserPK = $this->RegistrationOffsetSeed+172;
			if($this->{$this->UserModel}->exists($UserPK-($this->RegistrationOffsetSeed+172))):
				if(!$this->{$this->UserModel}->getFieldValueFromPK('Verified', $UserPK-($this->RegistrationOffsetSeed+172))):
					$update_data = array(
						"Verified" => 1,
						"VerifiedDT" => $this->System->NOW()
					);
					if($this->{$this->UserModel}->update($UserPK-($this->RegistrationOffsetSeed+172), $update_data)):
						$this->session->set_flashdata(APP_IDENT.'_LoginSuccessMessage','Your account has been successfully activated.  Please login below.');
					else:
						$this->session->set_flashdata(APP_IDENT.'_SessionExpiredMessage','There was an error activating your account.  Please try again shortly.');
					endif;
				else:
					$this->session->set_flashdata(APP_IDENT.'_LoginSuccessMessage','Your account has already been activated.  You can login below.');
				endif;
			endif;
			redirect('/'.$this->LoginController);
		}
		
		/**
		 * This is the pseudo-global validation function that runs only after the user name and password fields have
		 * been individually validated.  This function checks to make sure that the user name and password
		 * you provided are indeed correct together and that the user is active for non-administrative user accounts.
		 *
		 * @internal
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-05
		 * @version 2009-05-05
		 * 
		 * @return bool True if all login credentials are ok.  False if any part of the login check fails.
		 */
		function _valid_login($dummy)
		{
			// one other validation stream must be successful before check can commence
			if($this->CheckLogin < 1):
				$this->CheckLogin++;
				return true;
			endif;
			
			if($this->User->doLogin(set_value('UserName'), set_value('Password'))):
				$UserPK = $this->User->getPKFromFieldValue('U_UserName', set_value('UserName'));
				if(!$this->User->getFieldValueFromPK('Active', $UserPK)):
					$this->form_validation->set_message('_valid_login', "This user account has been disabled.");
					return false;
				else:
					if(!$this->User->getFieldValueFromPK('Verified', $UserPK)):
						$this->form_validation->set_message('_valid_login', "This user account has not been activated yet.<br />If you need the activation email resent to you, please click <a href=\"/login/activationemail/".($UserPK+3247)."\">here</a>.");
						return false;
					else:
						if($this->User->accountExpired($UserPK)):
							$this->form_validation->set_message('_valid_login', "Your temporary account has expired.  Please contact us if you need more time.  Thanks!");
							return false;
						else:
							return true;
						endif;
					endif;
				endif;
			else:
				$this->form_validation->set_message('_valid_login', "Invalid User Name / Password Combination");
				return false;
			endif;			
		}
		
		/**
		 * This is an action function that encrypts and safely stores the user's login information
		 * on the server's session and local session cookie.  This session is constantly validated
		 * (implemented in the module function {@link User::validateLogin()}) through the application's
		 * database to make sure it hasn't timed out and that all password hashes are correct to throw
		 * anyone out who has tampered with their encrypted cookie.
		 *
		 * @access protected
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-05
		 * @version 2009-05-22
		 * 
		 * @param int $UserPK | The unique user id of the logged in user who needs their session established
		 */
		protected function _create_session($UserPK)
		{
			$this->session->sess_destroy();
			$this->session->sess_create();
			$PassHash = md5($this->User->getFieldValuefromPK('Password', $UserPK));
			$RandomHash = md5(rand().'wv4575RTVYW#$'.$this->config->item('encryption_key').'B9i764e4$#'.rand());
			$this->session->set_userdata(APP_IDENT.'_UserNum', $UserPK);
			$this->session->set_userdata(APP_IDENT.'_RandomHash', $RandomHash);
			$this->session->set_userdata(APP_IDENT.'_PasswordHash', md5($RandomHash.(defined(APP_IDENT."_PASSWORD_SALT")?salt_hash('eb5786q231qvUS%$4w3',constant(APP_IDENT.'_PASSWORD_SALT')):md5('eb5786q231qvUS%$4w3')).$PassHash.(defined(APP_IDENT."_PASSWORD_SALT")?salt_hash('03232qVrtU&N%3e',constant(APP_IDENT.'_PASSWORD_SALT')):md5('03232qVrtU&N%3e')).$RandomHash));
		}
		
		/**
		 * This is a support function used to redirect the user to the correct application
		 * controller once it has been determined that the user is logged in.
		 *
		 * @access protected
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-05
		 * @version 2009-05-19
		 * 
		 * @param mixed $ControllerRedirect This value is used only for redirecting for a user that has requested a specific functionality defined by a spcific controller... this should be 0 at all other times
		 */
		protected function _login_redirect($ControllerRedirect = 0)
		{
			$LoginLevel = $this->User->validateLogin($this->session->userdata(APP_IDENT.'_UserNum'), $this->session->userdata(APP_IDENT.'_PasswordHash'), $this->session->userdata(APP_IDENT.'_RandomHash'));
			if($LoginLevel === 2 && $this->AdminSegregation):
				redirect("/".strtolower($this->AdminRedirectController));
			elseif($LoginLevel === 1 || $LoginLevel === 2):
				if($ControllerRedirect !== 0 && !empty($ControllerRedirect)):	
					if(file_exists(strtolower(APP_IDENT)."system/application/controllers/$ControllerRedirect.php")):
						redirect("/$ControllerRedirect");
					else:
						redirect("/".strtolower($this->DefaultRedirectController));
					endif;
				else:
					redirect("/".strtolower($this->DefaultRedirectController));				
				endif;
			else:
				redirect("/".strtolower($this->LogoutController));
			endif;
		}
		
		/**
		 * This validation function can be used by form_validation to check that any phone
		 * number entered on the registration form is in the valid format (according to
		 * our general validation helper library).
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-18
		 * @version 2009-05-18
		 * 
		 * @internal
		 * 
		 * @param string $PhoneNumber The entered value for the phone number field being validated
		 * @return bool True if the phone number is in the correct format, False otherwise
		 */
		function _is_phone_number($PhoneNumber)
		{
			if(!$this->EnableRegistration)
				die('The login controller '.$this->LoginController.' tried to access the "_is_phone_number" function.  However, EnableRegistration is not enabled for this controller.');
			
			if(isPhoneNumber($PhoneNumber)):
				return true;
			else:
				$this->form_validation->set_message("_is_phone_number", "Please enter as ###-###-####");
				return false;
			endif;
		}
		
		/**
		 * This validation function ensures that the chosen user name on the registration
		 * form (which could also just be the user's email address if desired) is not already
		 * registered in the system.
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-18
		 * @version 2009-05-18
		 * 
		 * @internal
		 * 
		 * @param string $UserName The entered user name value being checked for uniqueness
		 * @return bool True if the supplied user name is not already a user on the database, False otherwise
		 */
		function _unique_user_name($UserName)
		{
			if(!$this->EnableRegistration)
				die('The login controller '.$this->LoginController.' tried to access the "_unique_user_name" function.  However, EnableRegistration is not enabled for this controller.');
			
			if($this->{$this->UserModel}->getCount(array('U_UserName' => $UserName)) == 0):
				return true;
			else:
				$this->form_validation->set_message("_unique_user_name", "This user name is already taken");
				return false;
			endif;	
		}
		
		/**
		 * This validation function ensures that no other account was previously created
		 * using the email address provided.  If for some reason you do not want to restrict
		 * the number of accounts to an email address, you can remove this from your form
		 * validation checks.  However, currently functionality such as user name retrieval
		 * won't function properly if the email field is not unique.
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-18
		 * @version 2009-05-18
		 * 
		 * @internal
		 * 
		 * @param string $EmailAddress The email address value provided to create this account under
		 * @return bool True if the email address is not already associated to another account on this system, False otherwise
		 */
		function _unique_email_address($EmailAddress)
		{
			if(!$this->EnableRegistration)
				die('The login controller '.$this->LoginController.' tried to access the "_unique_email_address" function.  However, EnableRegistration is not enabled for this controller.');
			
			if($this->{$this->UserModel}->getCount(array('U_EmailAddress' => $EmailAddress)) == 0):
				return true;
			else:
				$this->form_validation->set_message("_unique_email_address", "There is already an account registered to this email address");
				return false;
			endif;
			return true;	
		}
		
		/**
		 * This validation function is used to make sure that the supplied email address
		 * exists on an account in this system.  This is used on account information retrieval
		 * forms, such as forgotusername to make sure a user with the supplied information actually
		 * exists before sending an email to the address.
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-18
		 * @version 2009-05-18
		 *
		 * @internal
		 * 
		 * @param string $EmailAddress The email address submitted through the form to be validated
		 * @return bool True if the email address is formatted correctly and exists on a user account, False otherwise
		 */
		function _email_address_exists_on_an_account($EmailAddress)
		{
			if(!$this->EnableRegistration)
				die('The login controller '.$this->LoginController.' tried to access the "_email_address_exists_on_an_account" function.  However, EnableRegistration is not enabled for this controller.');
			
			if(!isEmailAddress($EmailAddress)):
				$this->form_validation->set_message('_email_address_exists_on_an_account','Email is not valid.');
				return false;
			else:
				if($this->{$this->UserModel}->getCount(array("U_EmailAddress" => $EmailAddress)) != 1):
				$this->form_validation->set_message('_email_address_exists_on_an_account','There is no account with this email address');
					return false;
				else:
					return true;
				endif;
			endif;
		}
		
		/**
		 * This validation function is used to make sure that the supplied email address
		 * exists on a specific user account.  This is used on account information retrieval
		 * forms, such as forgotpassword, where both the email address and user name are both
		 * supplied together in order to get information back from the system.
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-18
		 * @version 2009-05-18
		 *
		 * @internal
		 * 
		 * @param string $EmailAddress The email address submitted through the form to be validated
		 * @return bool True if the email address is formatted correctly and exists on the specified account, False otherwise
		 */
		function _email_address_exists_on_this_account($EmailAddress)
		{
			if(!$this->EnableRegistration)
				die('The login controller '.$this->LoginController.' tried to access the "_email_address_exists_on_this_account" function.  However, EnableRegistration is not enabled for this controller.');
			
			if(!isEmailAddress($EmailAddress)):
				$this->form_validation->set_message('_email_address_exists_on_this_account','Email is not valid.');
				return false;
			elseif(set_value('UserName') == ""):
				return true;
			else:
				if($this->{$this->UserModel}->getCount(array("U_EmailAddress" => $EmailAddress, "U_UserName" => set_value('UserName'))) != 1):
				$this->form_validation->set_message('_email_address_exists_on_this_account','There is no account with this user name and email address');
					return false;
				else:
					return true;
				endif;
			endif;
		}
		
		/**
		 * This validation function integrates with the reCAPTCHA plugin to validate that
		 * the bot check code the user entered actually matches the code provided through the
		 * reCAPTCHA service.  These checks prevent against several different kinds of bot DoS
		 * database attacks as well as email spamming bots on forms that perform automated emails.
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-18
		 * @version 2009-05-19
		 *
		 * @internal
		 * 
		 * @return bool True if the entered words match those in the reCAPTCHA image, false otherwise
		 */
		function _valid_recaptcha_code()
		{
			if(!$this->EnableRegistration)
				die('The login controller '.$this->LoginController.' tried to access the "_valid_recaptcha_code" function.  However, EnableRegistration is not enabled for this controller.');
			
			if(!defined(APP_IDENT.'_RECAPTCHA_PUBLIC_KEY') || !defined(APP_IDENT.'_RECAPTCHA_PRIVATE_KEY'))
				die('The login controller '.$this->LoginController.' tried to access the "_valid_recaptch_code" function.  However, one or more of the recaptcha keys are not defined in your project constant definitions.');
			$RecaptchaResult = recaptcha_check_answer(constant(APP_IDENT.'_RECAPTCHA_PRIVATE_KEY'), $_SERVER['REMOTE_ADDR'], set_value('recaptcha_challenge_field'), set_value('recaptcha_response_field'));
			if($RecaptchaResult->is_valid):
				return true;
			else:
				$this->form_validation->set_message('_valid_recaptcha_code', 'The reCAPTCHA code you entered is invalid.');
				return false;
			endif;
		}
		
		/**
		 * This function will perform the actual user creation on the database after
		 * registration is successful.  This function will also envoke the call to send
		 * the first activation email after the account has been created.
		 * 
		 * NOTE: You can decide upon implementation that you don't want an activation requirement at all by simply
		 * having the code below set the validated flag to true on the database by default.
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-18
		 * @version 2009-05-20
		 * 
		 * @access protected
		 * 
		 * @return bool True if the user was created successfully on the database, False otherwise
		 */
		protected function _create_user()
		{	
			$EncPass = defined(APP_IDENT."_PASSWORD_SALT") ? salt_hash(set_value('Password1'),constant(APP_IDENT."_PASSWORD_SALT")) : md5(set_value('Password1'));
			if($newUserPK = $this->{$this->UserModel}->create(set_value('FirstName'), set_value('LastName'), set_value('PhoneNumber'), set_value('EmailAddress1'), set_value('UserName'), $EncPass)):
				return $newUserPK;
			else:
				$this->session->set_flashdata(SESSIONPREFIXVAR.'LoginRegisterErrorMessage', "There was an unknown error creating your account.  However, your authorization code has already been marked as used.  Please contact us to have your code reset.");
				return false;
			endif;
		}
		
		/**
		 * This function will send the signed up user their new account creation email
		 * which includes the link needed to activate their account.  Using the send_html_email,
		 * this function relies on an html and text-based email template in the main CI
		 * application under the subfolder email_templates in the view folder defined upon
		 * class extension.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-18
		 * @version 2009-05-26
		 * 
		 * @access protected
		 * 
		 * @return bool True if the email call was processed successfully, False otherwise
		 */
		protected function _send_new_account_email()
		{
			if(!$this->EnableRegistration)
				die('The login controller '.$this->LoginController.' tried to access the "_send_new_account_email" function.  However, EnableRegistration is not enabled for this controller.');
			
			if($this->{$this->UserModel}->exists($this->NewUserPK)):
				$EmailAddress = $this->{$this->UserModel}->getFieldValueFromPK('U_EmailAddress',$this->NewUserPK);
				$VerificationCode = $this->{$this->UserModel}->getFieldValueFromPK('VerificationCode',$this->NewUserPK);
				$VerifyLinkURL = constant(APP_IDENT.'_APPLICATION_PROTOCOL').'://'.$_SERVER['HTTP_HOST'].'/login/activateacct/'.($this->NewUserPK+($this->RegistrationOffsetSeed+172)).'/'.$VerificationCode;
				
				$to = $EmailAddress;
				$subject = (defined(APP_IDENT.'_NEW_ACCOUNT_EMAIL_TITLE')) 
						 ? constant(APP_IDENT.'_NEW_ACCOUNT_EMAIL_TITLE')
						 : "Your Account is Almost Ready -- Action Required!";
				
				// variables the email templates will need
				$EmailTemplateVars = array();
				$EmailTemplateVars["VerifyLinkURL"] = $VerifyLinkURL;
				$EmailTemplateVars["WebsiteName"] = constant(APP_IDENT."_EMAIL_WEBSITE_NAME");
				$EmailTemplateVars["EmailSignature"] = constant(APP_IDENT."_EMAIL_SIGNATURE");
				
				// get view data from email templates
				$TextEmailBody = $this->load->view($this->ViewFolder.'/email_templates/text-new_account', $EmailTemplateVars, TRUE);
				$HTMLEmailBody = $this->load->view($this->ViewFolder.'/email_templates/html-new_account', $EmailTemplateVars, TRUE);
				
				return send_html_email($to, constant(APP_IDENT.'_EMAIL_SENDER'), constant(APP_IDENT.'_EMAIL_REPLY_TO'), $subject, $TextEmailBody, $HTMLEmailBody);
			endif;
			return false;
		}
		
		/**
		 * This function will send a user who has provided basic account verification an
		 * email to the address they signed up their account with a link containing
		 * a form to reset their password to access their account.  Using the send_html_email,
		 * this function relies on an html and text-based email template in the main CI
		 * application under the subfolder email_templates in the view folder defined upon
		 * class extension.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-18
		 * @version 2009-05-26
		 * 
		 * @access protected
		 * 
		 * @param int $UserNum This is the primary key of the user that is requesting the password reset; an adjusted offset of this number will need to be included in the password reset link
		 * @param string $PasswordResetToken This is the password reset hash that needs to be included in the password reset link in the email
		 * @return bool True if the email call was processed successfully, False otherwise
		 */
		protected function _send_forgot_password_email($UserNum, $PasswordResetToken)
		{
			if(!$this->EnableRegistration)
				die('The login controller '.$this->LoginController.' tried to access the "_send_forgot_password_email" function.  However, EnableRegistration is not enabled for this controller.');
			
			$to = set_value('EmailAddress');
			if($this->{$this->UserModel}->getCount(array("U_EmailAddress" => $to, "U_UserName" => set_value('UserName'))) == 1):
				$UserPK = $this->{$this->UserModel}->getPKFromFieldValue("U_UserName", set_value('UserName'));
				$ResetLinkURL = constant(APP_IDENT.'_APPLICATION_PROTOCOL').'://'.$_SERVER['HTTP_HOST'].'/login/passwordreset/'.$PasswordResetToken.'/'.($UserNum+($this->RegistrationOffsetSeed+742));
				
				$subject = (defined(APP_IDENT.'_FORGOT_PASSWORD_EMAIL_TITLE')) 
						 ? constant(APP_IDENT.'_FORGOT_PASSWORD_EMAIL_TITLE')
						 : "Password Reset Request Received -- Action Required!";
				
				// variables the email templates will need
				$EmailTemplateVars = array();
				$EmailTemplateVars["UserName"] = set_value('UserName');
				$EmailTemplateVars["ResetLinkURL"] = $ResetLinkURL;
				$EmailTemplateVars["WebsiteName"] = constant(APP_IDENT."_EMAIL_WEBSITE_NAME");
				$EmailTemplateVars["EmailSignature"] = constant(APP_IDENT."_EMAIL_SIGNATURE");
				// get view data from email templates
				$TextEmailBody = $this->load->view($this->ViewFolder.'/email_templates/text-forgot_password', $EmailTemplateVars, TRUE);
				$HTMLEmailBody = $this->load->view($this->ViewFolder.'/email_templates/html-forgot_password', $EmailTemplateVars, TRUE);
				
				return send_html_email($to, constant(APP_IDENT.'_EMAIL_SENDER'), constant(APP_IDENT.'_EMAIL_REPLY_TO'), $subject, $TextEmailBody, $HTMLEmailBody);
			endif;
			return false;
		}
		
		/**
		 * This function will send a user who has just reset their password through
		 * the secure password reset portal a link confirming that the password has
		 * indeed been reset for their account.  This is also where text alerting the
		 * user that on the very rare change they didn't perform this reset, who they
		 * should contact to resolve the issue.  Using the send_html_email,
		 * this function relies on an html and text-based email template in the main CI
		 * application under the subfolder email_templates in the view folder defined upon
		 * class extension.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-18
		 * @version 2009-05-26
		 * 
		 * @access protected
		 * 
		 * @return bool True if the email call was processed successfully, False otherwise
		 */
		protected function _send_password_reset_email()
		{
			if(!$this->EnableRegistration)
				die('The login controller '.$this->LoginController.' tried to access the "_send_password_reset_email" function.  However, EnableRegistration is not enabled for this controller.');
			
			$to = set_value('EmailAddress');
			if($this->{$this->UserModel}->getCount(array("U_EmailAddress" => $to, "U_UserName" => set_value('UserName'))) == 1):
				$UserPK = $this->{$this->UserModel}->getPKFromFieldValue("U_UserName", set_value('UserName'));
				
				$subject = (defined(APP_IDENT.'_PASSWORD_RESET_EMAIL_TITLE')) 
						 ? constant(APP_IDENT.'_PASSWORD_RESET_EMAIL_TITLE')
						 : "Your Account Password Has Been Reset -- Please Verify!";
				
				// variables the email templates will need
				$EmailTemplateVars = array();
				$EmailTemplateVars["UserName"] = set_value('UserName');
				$EmailTemplateVars["WebsiteName"] = constant(APP_IDENT."_EMAIL_WEBSITE_NAME");
				$EmailTemplateVars["EmailSignature"] = constant(APP_IDENT."_EMAIL_SIGNATURE");
				
				// get view data from email templates
				$TextEmailBody = $this->load->view($this->ViewFolder.'/email_templates/text-password_reset', $EmailTemplateVars, TRUE);
				$HTMLEmailBody = $this->load->view($this->ViewFolder.'/email_templates/html-password_reset', $EmailTemplateVars, TRUE);
				
				return send_html_email($to, constant(APP_IDENT.'_EMAIL_SENDER'), constant(APP_IDENT.'_EMAIL_REPLY_TO'), $subject, $TextEmailBody, $HTMLEmailBody);
			endif;
			return false;
		}
		
		/**
		 * This function will send a user who has provided their email address and
		 * email containing the username that is associated to their email address in
		 * the system.  This functionality must absolutely be monitored by a CAPTCHA to avoid
		 * unintential spamming by the system.  Using the send_html_email,
		 * this function relies on an html and text-based email template in the main CI
		 * application under the subfolder email_templates in the view folder defined upon
		 * class extension.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-18
		 * @version 2009-05-26
		 * 
		 * @access protected
		 * 
		 * @return bool True if the email call was processed successfully, False otherwise
		 */
		protected function _send_forgot_username_email()
		{
			if(!$this->EnableRegistration)
				die('The login controller '.$this->LoginController.' tried to access the "_send_forgot_username_email" function.  However, EnableRegistration is not enabled for this controller.');
			
			$to = set_value('EmailAddress');
			if($this->{$this->UserModel}->getCount(array("U_EmailAddress" => $to)) == 1):
				$UserPK = $this->{$this->UserModel}->getPKFromFieldValue("U_EmailAddress", $to);
				$LinkToForgotPasswordForm = constant(APP_IDENT.'_APPLICATION_PROTOCOL').'://'.$_SERVER['HTTP_HOST'].'/'.$this->LoginController.'/forgotpassword';
				$UserName = $this->{$this->UserModel}->getFieldValueFromPK("U_UserName", $UserPK);
				
				$subject = (defined(APP_IDENT.'_PASSWORD_RESET_EMAIL_TITLE')) 
						 ? constant(APP_IDENT.'_PASSWORD_RESET_EMAIL_TITLE')
						 : "The User Name for Your Account!";
				
				// variables the email templates will need
				$EmailTemplateVars = array();
				$EmailTemplateVars["ForgotPasswordLink"] = $LinkToForgotPasswordForm;
				$EmailTemplateVars["UserName"] = $UserName;
				$EmailTemplateVars["WebsiteName"] = constant(APP_IDENT."_EMAIL_WEBSITE_NAME");
				$EmailTemplateVars["EmailSignature"] = constant(APP_IDENT."_EMAIL_SIGNATURE");
				
				// get view data from email templates
				$TextEmailBody = $this->load->view($this->ViewFolder.'/email_templates/text-forgot_username', $EmailTemplateVars, TRUE);
				$HTMLEmailBody = $this->load->view($this->ViewFolder.'/email_templates/html-forgot_username', $EmailTemplateVars, TRUE);
				
				return send_html_email($to, constant(APP_IDENT.'_EMAIL_SENDER'), constant(APP_IDENT.'_EMAIL_REPLY_TO'), $subject, $TextEmailBody, $HTMLEmailBody);
			endif;
			return false;
		}
		
	}
?>