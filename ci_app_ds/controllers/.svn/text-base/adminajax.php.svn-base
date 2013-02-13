<?php
/**
 * This file contains the controller that will handle any ajax requests made
 * by administrators into the system.  This ajax information is only accessible
 * to admin logged in users.
 *
 * @package CI_DSSalesRepApp
 * @subpackage Controllers
 * @category Controllers
 *
 * @author Ethix Systems LLC <support@ethixsystems.com>
 * @version 2009-03-20
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 */

require_once('admin.php');

/**
 * AdminAJAX controller. Contains all routines for AJAX calls made to the admin controller.
 *
 * @author Ethix Systems LLC <support@ethixsystems.com>
 * @since 2009-03-20
 * @version 2009-04-01
 */
class AdminAJAX extends Admin
{

	/**
	 * AdminAJAX class constructor. This constructor will rely on the parent
	 * {@see Admin::__construct()} constructor for its permissions.
	 *
	 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
	 * @since 2009-03-20
	 * @version 2009-03-20
	 *
	 * @return AdminAJAX Instanciated AdminAJAX controller class
	 */
	function __construct()
	{
		$this->AJAXCall = true;
		parent::__construct();
	}

	/**
	 * This function will change the login password for the given user pk.
	 *
	 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
	 * @since 2009-03-20
	 * @version 2009-05-22
	 *
	 * @param int $UserNum The pk of the user to change the password for
	 */
	function changepassword($UserNum = 0)
	{
		if($this->AJAXRedirect)
			return;
			
		$this->load->helper('validation');
		if(!isInt($UserNum))
			$UserNum = 0;
		if(!$this->User->exists($UserNum))
			return;
			
		$XMLViewArray = array();
		$XMLViewArray["StartTag"] = true;
		$XMLViewArray["EndTag"] = true;
		$XMLViewArray["XMLDocument"] = array();
			
		if($this->input->post('Password') && $this->input->post('PasswordConfirm')):
			if($this->input->post('Password') !== $this->input->post('PasswordConfirm')):
				$XMLViewArray["XMLDocument"]["Success"] = 0;
				$XMLViewArray["XMLDocument"]["Message"] = "The two passwords you entered do not match";
			elseif($this->input->post('Password') == ""):
				$XMLViewArray["XMLDocument"]["Success"] = 0;
				$XMLViewArray["XMLDocument"]["Message"] = "Please supply a valid password";
			elseif(md5($this->input->post('Password')) === $this->User->getFieldValueFromPK('Password',$UserNum)):
				$XMLViewArray["XMLDocument"]["Success"] = 0;
				$XMLViewArray["XMLDocument"]["Message"] = "The password you entered is already the password for that account";
			else:
				$Password = $this->input->post('Password');
				$UpdateSession = ($this->User->getFieldValueFromPK('Admin', $UserNum) == 1);
				if(($ChangeSuccess = $this->User->changePassword($UserNum, $Password, $UpdateSession)) === 1):
					$XMLViewArray["XMLDocument"]["Success"] = 1;
					$XMLViewArray["XMLDocument"]["Message"] = "The password for the selected account has been changed successfully";
				elseif($ChangeSuccess === 0):
					$XMLViewArray["XMLDocument"]["Success"] = 0;
					$XMLViewArray["XMLDocument"]["Message"] = "The password you supplied is already the password for this account.";
				else:
					$XMLViewArray["XMLDocument"]["Success"] = 0;
					$XMLViewArray["XMLDocument"]["Message"] = "There was an unknown error encountered.  ERROR: DBPASSCNG01";
				endif;
			endif;
		endif;
			
		$this->load->view('ajax/viewxml', $XMLViewArray);
	}

	/**
	 * This function will retrieve all data associated to a product record for
	 * use primarily when an administrator clicks the edit button on the product
	 * administration page.
	 *
	 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
	 * @since 2009-04-01
	 * @version 2009-06-22
	 *
	 * @param int $ProductNum The product pk to retrieve the database information for
	 */
	function getproductinfo($ProductNum = 0)
	{
		if($this->AJAXRedirect)
			return;
			
		$XMLViewArray = array();
		$XMLViewArray["StartTag"] = true;
		$XMLViewArray["EndTag"] = true;	
		$XMLViewArray["XMLDocument"] = array();
		
		if(!$this->Product->exists($ProductNum)):
			$XMLViewArray["XMLDocument"]["Exists"] = "0";
		else:
			$ProductData = $this->Product->getFieldValueFromPK(array('InvtID', 'Descr', 'ClassID', 'StkBasePrc', 'StkUnit'), $ProductNum);
			$SectionCategoryArray = explode('-',$ProductData['ClassID']);
			
			$XMLViewArray["XMLDocument"]["Exists"] = "1";
			$XMLViewArray["XMLDocument"]["SectionNum"] = $SectionCategoryArray[0];
			$XMLViewArray["XMLDocument"]["CategoryNum"] = (isset($SectionCategoryArray[1])?$SectionCategoryArray[1]:'');
			$XMLViewArray["XMLDocument"]["ProductNumber"] = $ProductData['InvtID'];
			$XMLViewArray["XMLDocument"]["ProductName"] = $ProductData['Descr'];
			$XMLViewArray["XMLDocument"]["ProductPrice"] = '$'.number_format(round($ProductData["StkBasePrc"],2),2).'/'.$ProductData["StkUnit"];
			$XMLViewArray["XMLDocument"]["LongDescription"] = $this->Product->getFieldValueFromPK('LongDescription', $ProductNum);
			$XMLViewArray["XMLDocument"]["IsImage"] = ($this->Product->getFieldValueFromPK('Image', $ProductNum) == "") ? 0 : 1;
			$XMLViewArray["XMLDocument"]["Link"] = $this->Product->getFieldValueFromPK('Link', $ProductNum);
		endif;
		
		$this->load->view('ajax/viewxml', $XMLViewArray);
	}

	/**
	 * This function will clear out the image associated to a given product.
	 * This is intended for use of the product administration page.
	 *
	 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
	 * @since 2009-04-01
	 * @version 2009-04-01
	 *
	 * @param int $ProductNum The product pk to remove the image for
	 */
	function deleteproductimage($ProductNum = 0)
	{
		if($this->AJAXRedirect)
			return;

		$this->load->helper('validation');
		if(!isInt($ProductNum))
			$UserNum = 0;
		if(!$this->Product->exists($ProductNum))
			return;

		$XMLViewArray = array();
		$XMLViewArray["StartTag"] = true;
		$XMLViewArray["EndTag"] = true;
		$XMLViewArray["XMLDocument"] = array();
			
		if($this->Product->update($ProductNum, array('Image' => '', 'ImageMimeType' => '')) !== FALSE)
			$XMLViewArray["XMLDocument"]["Success"] = 1;
		else
			$XMLViewArray["XMLDocument"]["Success"] = 0;
			
		$this->load->view('ajax/viewxml', $XMLViewArray);
	}

}
?>