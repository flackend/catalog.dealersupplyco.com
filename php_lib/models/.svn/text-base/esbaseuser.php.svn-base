<?php
/**
 * This file contains a base set of model functionality used for users and user
 * permissions across several projects.  There is no direct implementation of this
 * model, and uses ESBaseModel for its base functionality.
 *
 * @package CI_GeneralESLibs
 * @subpackage Models
 * @category Models
 *
 * @author Ethix Systems LLC <support@ethixsystems.com>
 * @since 2009-05-04
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 */

require_once('esbasemodel.php');

/**
 * ESBaseUser model.  Contains an abstract set of user permission database functionality.
 *
 * @abstract
 *
 * @author Ethix Systems LLC <support@ethixsystems.com>
 * @since 2009-05-04
 * @version 2009-05-04
 */
abstract class ESBaseUser extends ESBaseModel
{

	/**
	 * ESBaseUser class constructor.  This constructor will load the ESBaseUser constructor
	 * followed by loading the System model.
	 *
	 * @internal
	 *
	 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
	 * @since 2009-05-04
	 * @version 2009-05-20
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->helper('security');
		$this->load->model('System');
	}

	/**
	 * This function is intended to be used exclusively by the {@link Login} controller.
	 * This function takes a user name and md5 encrypted password and checks the database to
	 * see if there is a match.  This function will only return true when there is exactly one
	 * user on the database with the corresponding name and password for data integrity purposes.
	 *
	 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
	 * @since 2009-05-04
	 * @version 2009-06-30
	 *
	 * @param string $UserName The user name supplied by the login controller to check database match
	 * @param string $Password The unencrypted password supplied by the login controller to check database match
	 * @return bool True when there is exactly one record on the user table with the corresponding user name and password hash.  False if there is any less or more than that.
	 */
	function doLogin($UserName, $Password)
	{
		$this->DBmaster->select($this->PrimaryKeyField);
		$this->DBmaster->from($this->TableName);
		$this->DBmaster->where('U_UserName', $UserName);
		if(defined(APP_IDENT."_PASSWORD_SALT"))
			$this->DBmaster->where('Password', salt_hash($Password, constant(APP_IDENT."_PASSWORD_SALT")));
		else
			$this->DBmaster->where('Password', md5(html_entity_decode($Password, ENT_QUOTES)));
		$query = $this->DBmaster->get();
		if($query->num_rows() == 1):
			return true;
		else:
			return false;
		endif;
	}

	/**
	 * This function is used by various controllers throughout the application to validate
	 * that the user is still successfully logged in.  This should run in controller constructors
	 * so that these checks are always performed before any action is carried out on the database.
	 * This function should be supplied the session data assigned to the logged in user.  This
	 * function will make sure that data is still valid, hasn't been tampered with, and hasn't expired
	 * using stored encrypted hashes.
	 *
	 * The session database/cookie and IP Address link is all managed by the framework.  This is just another
	 * level of security on top of that system.
	 *
	 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
	 * @since 2009-05-04
	 * @version 2009-05-22
	 *
	 * @param int $UserPK This is the primary key for the user who is claiming to be logged in
	 * @param string $PasswordHash This is the password hash stored on the session that is compiled from the real password and the random hash also stored on the session
	 * @param string $RandomHash This is the random hash stored on the session that gets dynamically created every time a user session starts
	 * @param string $PseudoSession Will only be defined if the implementor requires [no account concurrancy] that a user who logs in kicks out all of their previously logged-in sessions, if so, this will be the unique pseudo-identifier of the latest logged in session for this user
	 * @return int 0 if the user isn't validly logged in anymore, 1 if the user is still logged in but is not an administrator, 2 if an administrative account is still logged in, false if the user has been kicked out by another login to this account (if concurrency check is required)
	 */
	function validateLogin($UserPK, $PasswordHash, $RandomHash, $PseudoSession = null)
	{
		$this->DBmaster->select('Admin, Password');
		$this->DBmaster->from($this->TableName);
		$this->DBmaster->where($this->PrimaryKeyField, $UserPK);
		$this->DBmaster->where('Active', 1);
		$this->DBmaster->where('Verified', 1);
		$query = $this->DBmaster->get();
		if($query->num_rows() != 1):
			return 0;
		else:
			$row = $query->row();
			if((md5($RandomHash.(defined(APP_IDENT."_PASSWORD_SALT")?salt_hash('eb5786q231qvUS%$4w3',constant(APP_IDENT.'_PASSWORD_SALT')):md5('eb5786q231qvUS%$4w3')).md5($row->Password).(defined(APP_IDENT."_PASSWORD_SALT")?salt_hash('03232qVrtU&N%3e',constant(APP_IDENT.'_PASSWORD_SALT')):md5('03232qVrtU&N%3e')).$RandomHash) != $PasswordHash) || $this->accountExpired($UserPK)):
				return 0;
			else:
				$same_pseudo = true;
				if(isset($PseudoSession)):
					$this->DBmaster->select('PK_UserNum');
					$this->DBmaster->from($this->TableName);
					$this->DBmaster->where($this->PrimaryKeyField, $UserPK);
					$this->DBmaster->where('pseudo_sess', $PseudoSession);
					$query_concurrency = $this->DBmaster->get();
					$same_pseudo = ($query_concurrency->num_rows() == 1);
				elseif(isset($PseudoSession) && empty($PseudoSession)):
					$same_pseudo = false;
				endif;
			
				if(!$same_pseudo):
					return false;
				elseif ($row->Admin == 1):
					return 2;
				else:
					return 1;
				endif;
			endif;
		endif;
	}

	/**
	 * This function is used to determine if the account supplied was a temporary account
	 * and has expired.
	 *
	 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
	 * @since 2009-05-04
	 * @version 2009-05-04
	 *
	 * @param int $UserPK The primary key of the user account that needs to be checked for expiry
	 * @return bool True if the account is temporary and has expired.  False if it hasn't expired or isn't a temporary account at all
	 */
	function accountExpired($UserPK)
	{
		$this->DBmaster->select('ExpirationDate, DATEDIFF(ExpirationDate,"'.$this->System->CURDATE().'") as DateDiff', FALSE);
		$this->DBmaster->from($this->TableName);
		$this->DBmaster->where($this->PrimaryKeyField, $UserPK);
		$query = $this->DBmaster->get();
		if($query->num_rows() != 1):
			return true;
		else:
			$row = $query->row();
			if($row->ExpirationDate == ""):
				return false;
			elseif($row->DateDiff > 0):
				return false;
			else:
				return true;
			endif;
		endif;
	}

	/**
	 * This function updates the user table with when the user last logged in upon
	 * a successfull login.
	 *
	 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
	 * @since 2009-05-04
	 * @version 2009-05-20
	 *
	 * @param int $UserPK The id for the user having their login tracked
	 * @param string $Type "single" if this information is directly on the user table (only tracks the last login), "multi" if it is on a separate table for historic login tracking
	 * @return bool True is the login tracking was logged successfully, False otherwise
	 */
	function trackLogin($UserPK, $Type = "single")
	{
		if($Type == "single"):
			$PseudoSession = md5($UserPK.rand()."ajaxverify".time().$this->input->server('REMOTE_ADDR'));
			$update_data = array(
						'LastLoginDT' => $this->System->NOW(),
						'LastLoginIP' => $this->input->server('REMOTE_ADDR'),
						'LastLoginBrowser' => $this->input->server('HTTP_USER_AGENT'),
						'pseudo_sess' => $PseudoSession
			);
			$this->session->set_userdata(APP_IDENT.'_PseudoSession', $PseudoSession);
			return $this->update($UserPK, $update_data);
		elseif($Type == "multi"):
			// @todo: implement this case
			return false;
		else:
			return false;
		endif;
	}

	/**
	 * This function will set a new password for a user account.  This has been implemented
	 * at the model level so that any optional password salt logic can be centralized.
	 *
	 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
	 * @since 2009-05-21
	 * @version 2009-05-22
	 *
	 * @param $UserPK The id for the user to update the password for
	 * @param $NewPassword The new, unencrypted, password to assign to this user
	 * @return bool|int False if the update occurred successfully, otherwise the number of user rows affected by this update
	 */
	function changePassword($UserPK, $NewPassword, $UpdateSession = false)
	{
		$EncPass = defined(APP_IDENT."_PASSWORD_SALT") ? salt_hash($NewPassword,constant(APP_IDENT."_PASSWORD_SALT")) : md5($NewPassword);
		$update_array = array(
			'Password' => $EncPass
		);
		if(!($ChangeSuccess = $this->update($UserPK, $update_array)))
			return $ChangeSuccess;
		if($UpdateSession):
			$PassHash = md5($EncPass);
			$RandomHash = md5(rand().'wv4575RTVYW#$'.$this->config->item('encryption_key').'B9i764e4$#'.rand());
			$this->session->set_userdata(APP_IDENT.'_RandomHash', $RandomHash);
			$this->session->set_userdata(APP_IDENT.'_PasswordHash', md5($RandomHash.(defined(APP_IDENT."_PASSWORD_SALT")?salt_hash('eb5786q231qvUS%$4w3',constant(APP_IDENT.'_PASSWORD_SALT')):md5('eb5786q231qvUS%$4w3')).$PassHash.(defined(APP_IDENT."_PASSWORD_SALT")?salt_hash('03232qVrtU&N%3e',constant(APP_IDENT.'_PASSWORD_SALT')):md5('03232qVrtU&N%3e')).$RandomHash));
		endif;
		return $ChangeSuccess;
	}

}

?>