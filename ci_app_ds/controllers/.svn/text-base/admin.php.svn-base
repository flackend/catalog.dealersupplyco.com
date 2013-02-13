<?php
/**
 * This file contains the controller for all of the administrative functionality
 * for the site.  This content is restricted to administrative logins only.
 *
 * @package CI_DSSalesRepApp
 * @subpackage Controllers
 * @category Controllers
 *
 * @author Ethix Systems LLC <support@ethixsystems.com>
 * @version 2009-03-20
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 */

/**
 * Admin controller. Contains all routines for administrative functionality.
 *
 * @author Ethix Systems LLC <support@ethixsystems.com>
 * @since 2009-03-20
 * @version 2009-04-23
 */
class Admin extends Controller
{

	/**
	 * @var int The primary key of the user logged in to the system
	 */
	private $UserNum = 0;

	/**
	 * @var bool This flag is needed to determine if view output should be in HTML or XML
	 */
	protected $AJAXCall = false;

	/**
	 * @var bool This flag is used for ajax calls to notify the actual ajax functions that a redirect request has been initiated and to not execute their logic
	 */
	protected $AJAXRedirect = false;

	/**
	 * Admin class constructor. The constructor loads all needed libraries and helper functions
	 * for use in the entire class.  This controller will also throw out any users who are not
	 * logged into the system or not admins.
	 *
	 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
	 * @since 2009-03-20
	 * @version 2009-05-04
	 *
	 * @return Admin Instanciated Admin controller class
	 */
	function __construct()
	{
		parent::Controller();
		//$this->output->enable_profiler(TRUE);
		$this->load->library('session');
		$this->load->model('User');
		$this->load->helper('url');

		if((!$this->session->userdata(APP_IDENT.'_UserNum') || !$this->session->userdata(APP_IDENT.'_PasswordHash') || !$this->session->userdata(APP_IDENT.'_RandomHash'))):
			// there has been no login session established on this computer yet: send user to login page with no errors
			if(!$this->AJAXCall):
				redirect('/logout/session_exp');
			else:
				$this->load->view('ajax/redirect', array('URL' => '/logout/session_exp'));
				$this->AJAXRedirect = true;
				return;
			endif;
		endif;

		$LoginLevel = $this->User->validateLogin($this->session->userdata(APP_IDENT.'_UserNum'), $this->session->userdata(APP_IDENT.'_PasswordHash'), $this->session->userdata(APP_IDENT.'_RandomHash'));
		if($LoginLevel == 1):
			// normal user, they shouldn't be here... redirect to catalog area
			if(!$this->AJAXCall):
				redirect('/catalog');
			else:
				$this->load->view('ajax/redirect', array('URL' => '/catalog'));
				$this->AJAXRedirect = true;
				return;
			endif;
		elseif($LoginLevel != 2):
			// bad login or inactive admin account, force logout
			if(!$this->AJAXCall):
				redirect('/logout/session_exp');
			else:
				$this->load->view('ajax/redirect', array('URL' => '/logout/session_exp'));
				$this->AJAXRedirect = true;
				return;
			endif;
		endif;
			
		$this->load->model('Product');
			
		// credentials good, grab user id off of session
		$this->UserNum = $this->session->userdata(APP_IDENT.'_UserNum');
			
		if(!$this->AJAXCall):
			$UserFirstName = $this->User->getFieldValueFromPK('FirstName',$this->session->userdata(APP_IDENT.'_UserNum'));
			$UserLastName = $this->User->getFieldValueFromPK('LastName',$this->session->userdata(APP_IDENT.'_UserNum'));
			$HeaderViewData["UserFullName"] = $UserFirstName." ".$UserLastName;
			$this->load->view('admin/header', $HeaderViewData);
		endif;
	}

	/**
	 * This is the default function that is loaded when no other method is specified.
	 * It will display the administrative main menu.
	 *
	 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
	 * @since 2009-03-20
	 * @version 2009-03-24
	 */
	function index()
	{
		if($this->AJAXCall)
			exit;
			
		$MenuViewArray = array();
		$MenuViewArray["UserNum"] = $this->UserNum;
		$this->load->view('admin/menu', $MenuViewArray);
		$this->load->view('admin/footer');
	}
	
	/**
	 * This function will display a list of products in the catalog and will allow
	 * administrators to click a link to delete a product, add a product, or edit
	 * the product's details.
	 *
	 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
	 * @since 2009-03-24
	 * @version 2009-06-22
	 */
	function products()
	{
		if($this->AJAXCall)
		exit;
		
		$this->load->library('form_validation');
		
		if(isset($_POST)):
			if(sizeof($_POST) > 0):
				$RequestType = isset($_POST['RequestType']) ? trim($this->input->post('RequestType')) : "";
				if($RequestType == "HideProduct" || $RequestType == "ShowProduct"):
					$ProductNum = isset($_POST['ProductNum']) ? trim($this->input->post('ProductNum')) : 0;
					if($this->Product->exists($ProductNum)):
						if(!$this->Product->existsOnAux($ProductNum)):
							$this->Product->create(array('FK_InvtID' => $ProductNum));
						endif;
						$update_data["Hidden"] = ($RequestType == "HideProduct"?1:0);
						echo $ProductNum."  ->   ";
						print_r($update_data);
						if($this->Product->update($ProductNum, $update_data))
							$SuccessMessage = "Product is now ".($RequestType == "HideProduct"?'hidden':'showing');
						else
							$ErrorMessage = "There was an unknown error encountered while changing this product's visibility";
					else:
						$ErrorMessage = "The product you are trying to edit no longer exists on the Solomon inventory database.";
					endif;
				elseif($RequestType == "EditProduct"):
					if($this->form_validation->run('explicit/adminproducts') != FALSE):
						$sql_data = array(
							'FK_InvtID' => set_value('ProductNumber'),
							'LongDescription' => set_value("ProductDescription"),
							'Link' => set_value('ProductLink')
						);
						$ProductPK = set_value('ProductNumber');
						if(!$this->Product->existsOnAux(set_value('ProductNumber'))): // product aux insert
							if($this->Product->create($sql_data) !== FALSE):
								$TempSuccessMessage = "Product entry updated successfully";
							else:
								$ErrorMessage = "There was an unknown error encountered while updating this product (UC)";
							endif;
						else: // product aux update
							if($this->Product->update($ProductPK, $sql_data) !== FALSE):
								$TempSuccessMessage = "Product entry updated successfully";
							else:
								$ErrorMessage = "There was an unknown error encountered while updating this product (UU)";
							endif;
						endif;
						if(isset($_FILES['ProductImage'])):
							if(($_FILES['ProductImage']['error'] == UPLOAD_ERR_OK) && !isset($ErrorMessage)):
								// image actually uploaded, put it to the database
								if($this->Product->exists($ProductPK)):
									$ImageFile = $_FILES['ProductImage']['tmp_name'];
									$ImageFP = fopen($ImageFile,"rb");
		        					$ImageData = fread($ImageFP,filesize($ImageFile));
		        					if($this->Product->update($ProductPK, array('Image' => $ImageData, 'ImageMimeType' => $_FILES['ProductImage']['type'])) === FALSE):
		        						$ErrorMessage = "Product data update was successful, but the image was not added successfully.<br />Please try the image upload again.";
		        					endif;
		        				else:
		        					$ErrorMessage = "Could not update product image: invalid product selected";
		        				endif;
	        				endif;
	        			endif;
        				if(!isset($ErrorMessage) && isset($TempSuccessMessage)):
	        				$SuccessMessage = $TempSuccessMessage;
	        			endif;
	        		else:
	        			$ClassIDTemp = $this->Product->getFieldValueFromPK('ClassID',set_value('ProductNumber'));
	        			$ClassIDTempArray = explode('-',$ClassIDTemp);
						$ProductsView['SectionNum'] = $ClassIDTempArray[0];
						$ProductsView['CategoryNum'] = isset($ClassIDTempArray[1])?$ClassIDTempArray[1]:'';
						$ProductsView['ProductNumber'] = set_value('ProductNumber');
						$ProductExtraData =  $this->Product->getFieldValueFromPK(array('StkBasePrc','StkUnit','Descr'), set_value('ProductNumber'));
						$ProductsView['ProductPrice'] = '$'.number_format(round($ProductExtraData["StkBasePrc"],2),2).'/'.$ProductExtraData["StkUnit"];
						$ProductsView['ProductName'] = $ProductExtraData['Descr'];
					endif;
				endif;
				
				if(isset($SuccessMessage)):
					$this->session->set_flashdata(APP_IDENT."_ProductListingSM", $SuccessMessage);
					header("Location: ".$_SERVER['REQUEST_URI'].($_SERVER['QUERY_STRING']?'?'.$_SERVER['QUERY_STRING']:''));
					exit;
				endif;
			endif;
		endif;
	
		$this->load->view('admin/productsheader');
		
		$this->load->helper('validation');
		$SearchSectionNum = isset($_GET['SectionNum']) ? $this->input->get('SectionNum') : "All";
		$SearchCategoryNum = isset($_GET['CategoryNum']) ? $this->input->get('CategoryNum') : "All";
		$SearchProductNum = isset($_GET['ProductNum']) ? $this->input->get('ProductNum') : "";
		$SearchDescriptionText = isset($_GET['DescriptionText']) ? $this->input->get('DescriptionText') : "";
		
		$SearchBoxViewArray = array();
		$ProductSectionRows = $this->Product->getProductSections(array('StkItem' => '1'));
		$SearchBoxViewArray['ProductSections'] = $ProductSectionRows;
		$SearchBoxViewArray['SectionNum'] = $SearchSectionNum;
		$SearchBoxViewArray['CategoryNum'] = $SearchCategoryNum;
		$SearchBoxViewArray['ProductNum'] = $SearchProductNum;
		$SearchBoxViewArray['DescriptionText'] = $SearchDescriptionText;
		$this->load->view('searchbox', $SearchBoxViewArray);
		
		// paging and sorting for the product listing
		$this->load->library('pas_admincataloglisting');
		$model_name = "Product";
		$select_clause = "InvtID, Descr, ClassID, StkBasePrc, StkUnit, Hidden";
		$where_clause = "StkItem = 1";
		if($SearchSectionNum != "All") $where_clause .= " and (ClassID like '".addslashes($SearchSectionNum)."-%' or ClassID = '".addslashes($SearchSectionNum)."')";
		if($SearchCategoryNum != "All") $where_clause .= " and ClassID like '%-".addslashes($SearchCategoryNum)."'";
		if($SearchProductNum != "") $where_clause .= " and InvtID like '%".addslashes($SearchProductNum)."%'";
		if($SearchDescriptionText != "") $where_clause .= " and Descr like '%".addslashes($SearchDescriptionText)."%'";
		
		$pas_config = array(
			'SelectClause' => $select_clause,
			'WhereClause' => $where_clause,
			'Model' => $model_name,
			'TableDefHTML' => '<table cellpadding="5" cellspacing="5" class="CatalogListingAdmin" width="77%" align="center">',
			'ResultDesc' => "Products"
		);
			
		$this->pas_admincataloglisting->initialize($pas_config);

		$this->pas_admincataloglisting->setColumnInfo('Number', '', 'InvtID', array('Sortable' => true, 'PartOfUniqueIdentifier' => true, 'DefaultSortColumn' => true));
		$this->pas_admincataloglisting->setColumnInfo('Section', '', 'ClassID', array('Sortable' => true, 'PartOfUniqueIdentifier' => true));
		$this->pas_admincataloglisting->setColumnInfo('Category', '', 'ClassID', array('Sortable' => ($SearchSectionNum=="All"?false:true), 'PartOfUniqueIdentifier' => true));
		$this->pas_admincataloglisting->setColumnInfo('Product', '', 'Descr', array('Sortable' => true, 'PartOfUniqueIdentifier' => true));
		$this->pas_admincataloglisting->setColumnInfo('Price', '', 'StkBasePrc', array('Sortable' => true, 'PartOfUniqueIdentifier' => true));
		$this->pas_admincataloglisting->setColumnInfo('Action', '50', '', array('Sortable' => false, 'PartOfUniqueIdentifier' => false));

		//$this->pas_admincataloglisting->setColumnJustificationChanges(array("Action" => "center"));
		//$this->pas_admincataloglisting->setTRClickAction("window.location.href='/admin/productdetails/[1]';", array("[1]" => "PK_ProductNum"), array("Action"));
			
		$this->pas_admincataloglisting->setTitleHTML('');
		$this->pas_admincataloglisting->setDefaultSortDirection('asc');
		$this->pas_admincataloglisting->setHTMLBetweenRows("");
		$this->pas_admincataloglisting->setUserControlLines(2);
		$this->pas_admincataloglisting->setDefaultResultsPerPage(10);
		$this->pas_admincataloglisting->setMaxResultsPerPage(25);
		$this->pas_admincataloglisting->neverShowAll();
		$this->pas_admincataloglisting->letUserControlResultsPerPage(true);
		$this->pas_admincataloglisting->letUserSearchWithin(false);
		$this->pas_admincataloglisting->setImageArrowLocation('/img/cataloglisting');
		$this->pas_admincataloglisting->setDebugMode(false);

		$ProductsView['ListingHTML'] = $this->pas_admincataloglisting->displayTable();
		if(isset($ErrorMessage))
			$ProductsView["ErrorMessage"] = $ErrorMessage;
		elseif($SuccessMessage = $this->session->flashdata(APP_IDENT."_ProductListingSM"))
			$ProductsView["SuccessMessage"] = $SuccessMessage;
		$ProductsView["ProductSections"] = $ProductSectionRows;
		$ProductsView["RequestType"] = "";
		if(isset($RequestType))
			$ProductsView["RequestType"] = $RequestType;
		$this->load->view('admin/products', $ProductsView);

		$this->load->view('admin/footer');
	}
	
	/**
	 * This validation function will verify that the product being edited actually
	 * exists on the inventory table on the mssql database.
	 * 
	 * @internal
	 * 
	 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
	 * @since 2009-04-23
	 * @version 2009-04-23
	 * 
	 * @param string $ProductNum The corresponding InvtID being checked for existance
	 * @return bool True if the product exists on the master inventory table, False otherwise
	 */
	function _product_exists($ProductNum)
	{
		if($this->Product->exists($ProductNum)):
			return true;
		else:
			$this->form_validation->set_message('_product_exists','The product you are trying to edit no longer exists on the Solomon inventory database.');
			return false;
		endif;
	}
	
	/**
	 * This validation function will verify that the image supplied for the product, if any,
	 * is a valid image file that can be used in this system.  This will use the
	 * validate_file_upload helper to perform this validation.
	 * 
	 * @internal
	 * 
	 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
	 * @since 2009-03-31
	 * @version 2009-05-22
	 * 
	 * @param null $dummy The file resources don't get passed through normal post data, so this will contain nothing and the file will need to be accessed directly
	 * @return bool True if the image is valid for this this program, False otherwise
	 */
	function _is_valid_image_resource($dummy)
	{		
		$this->load->helper('file');
		list($success, $message) = validate_file_upload('ProductImage', false, false);
		
		if($success):
			return true;
		else:
			$this->form_validation->set_message('_is_valid_image_resource',$message);
			return false;
		endif;
	}

}
?>
