<?php
	/**
	 * This file contains the controller for displaying binary data that is either generated
	 * trough php or pulled from the database as the actual file through html.
	 * Access to this functionality is locked down by user accounts on the database.
	 * This controller is usually accessed through a new popup window, download button, or an html img tag.
	 * In either case the normal error messages, redirects, and includes don't apply to this content.
	 * 
	 * @package CI_DSSalesRepApp
	 * @subpackage Controllers
	 * @category Controllers
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @version 2009-03-24
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 */

	/**
	 * DisplayBlob controller.  Contains all routines for displaying database / php generated blob data. 
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @since 2009-03-24
	 * @version 2009-05-04
	 */
	class DisplayBlob extends Controller
	{
		
		/**
		 * @var int The id of the user that is currently logged in
		 */
		private $UserNum = 0;
		
		/**
		 * @var bool Flag as to if the currently logged in user is an administrator
		 */
		private $AdminLogin = false;
		
		/**
		 * DisplayBlob class constructor.  The constructor loads all needed libraries and helper functions
		 * for use in the entire class.  It also double checks that the user accessing these functions
		 * currently has access to the system through means of an active user account on the database.
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-03-24
		 * @version 2009-05-04
		 * 
		 * @return DisplayBlob Instanciated DisplayBlob controller class
		 */
		function __construct()
		{
			parent::Controller();
			
			$this->load->library('session');
			$this->load->model('User');
			$this->load->model('Product');
			$this->load->helper('url');
			
			$UserNum = $this->session->userdata(APP_IDENT.'_UserNum');
			$this->UserNum = $UserNum;
			$PasswordHash = $this->session->userdata(APP_IDENT.'_PasswordHash');
			$RandomHash = $this->session->userdata(APP_IDENT.'_RandomHash');
			
			// grab LoginLevel from db, 1 = normal user, 2 = admin, 0 or anything else = bad credentials
			$LoginLevel = $this->User->validateLogin($UserNum, $PasswordHash, $RandomHash);
			
			if($LoginLevel != 1 && $LoginLevel != 2):
				// not logged in... no permission to pull blobs
				redirect('/logout/session_exp');
			endif;
			
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
			header("Cache-Control: no-cache");
			header("Pragma: no-cache");
			
			if($LoginLevel == 2):
				$this->AdminLogin = true;
			endif;
		}
		
		/**
		 * This is the function called by default when no other function is specified on the url line.
		 * Currently this controller has no default action when called, so it will just return the
		 * user to their currently logged in home page.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-03-24
		 * @version 2009-03-24
		 */
		function index()
		{
			redirect('');
		}
		
		/**
		 * This function will pull the image off of the database for the specified product.
		 * The function will display a default "no image" image if the product has no image.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-03-24
		 * @version 2009-04-01
		 * 
		 * @param int $ProductNum The primary key for the product to get the image for
		 */
		function productimage($ProductNum = 0)
		{
			if($this->Product->exists($ProductNum)):;
				$ImageData = $this->Product->getFieldValueFromPK('Image',$ProductNum);
				if($ImageData != ""):
					header("Content-type: ".$this->Product->getFieldValueFromPK('ImageMimeType',$ProductNum));
				else:
					header("Content-type: image/gif");
					$ImageFile = $_SERVER['DOCUMENT_ROOT'].((substr($_SERVER['DOCUMENT_ROOT'],-1)=="/")?'':'/')."img/noImage.gif";
					$ImageFP = fopen($ImageFile,"rb");
	        		$ImageData = fread($ImageFP,filesize($ImageFile));
				endif;
				echo $ImageData;
			endif;
		}

	}
?>