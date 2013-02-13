<?php
	/**
	 * This file contains the controller for the main sales rep area of the site.
	 * This area of the site is only accessible to normal logged in users.
	 * 
	 * @package CI_DSSalesRepApp
	 * @subpackage Controllers
	 * @category Controllers
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @version 2009-03-18
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 */

	/**
	 * Catalog controller. Contains all routines logged in sales reps.
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @since 2009-03-18
	 * @version 2009-04-03
	 */
	class Catalog extends Controller
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
		 * Catalog class constructor. The constructor loads all needed libraries and helper functions
		 * for use in the entire class.  This controller will also throw out any users who are not
		 * logged into the system yet.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-03-18
		 * @version 2009-05-04
		 * 
		 * @return Catalog Instanciated Catalog controller class
		 */
		function __construct()
		{
			parent::Controller();

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
			if($LoginLevel == 2 && !$this->AJAXCall):
				// admin user; they should only be here for ajax calls
				redirect('/admin');
			elseif($LoginLevel != 1 && $LoginLevel != 2):
				// bad login or inactive account, force logout
				if(!$this->AJAXCall):
					redirect('/logout/session_exp');
				else:
					$this->load->view('ajax/redirect', array('URL' => '/logout/session_exp'));
					$this->AJAXRedirect = true;
					return;
				endif;
			endif;
			
			// credentials good, grab user id off of session
			$this->UserNum = $this->session->userdata(APP_IDENT.'_UserNum');

			$this->load->model('System');
			$this->load->model('Product');
			
			if(!$this->AJAXCall && $this->uri->segment(2) != "listingpdf"):			
				$UserFirstName = $this->User->getFieldValueFromPK('FirstName',$this->session->userdata(APP_IDENT.'_UserNum'));
				$UserLastName = $this->User->getFieldValueFromPK('LastName',$this->session->userdata(APP_IDENT.'_UserNum'));
				$HeaderViewData["UserFullName"] = $UserFirstName." ".$UserLastName;
				$this->load->view('catalog/header', $HeaderViewData);
			endif;
		}
		
		/**
		 * This is the default function that is loaded when no other method is specified.
		 * This function will initiate the catalog listing to be displayed in HTML.
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-03-18
		 * @version 2009-04-29
		 */
		function index()
		{
			if($this->AJAXCall)
				exit;
			
			$this->_generate_listing(false);
		}
		
		/**
		 * This is the function that will initiate the catalog listing to be display as a PDF.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-04
		 * @version 2009-05-04
		 */
		function listingpdf()
		{
			if($this->AJAXCall)
				exit;
			
			ini_set('memory_limit', '32M');
			$this->_generate_listing(true);
		}
		
		/**
		 * This function will display that catalog listing as either a pdf or in html.
		 * Also, if it is in HTML, it will accept search / sorting variables.
		 * This uses Pagingandsorting for its output.
		 * 
		 * @access private
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-04
		 * @version 2009-05-14
		 * 
		 * @param bool $as_pdf True if the catalog listing should be a PDF, False if it should just be in HTML
		 */
		private function _generate_listing($as_pdf)
		{
			$SectionNum = isset($_GET['SectionNum']) ? trim($this->input->get('SectionNum')) : "All";
			$CategoryNum = isset($_GET['CategoryNum']) ? trim($this->input->get('CategoryNum')) : "All";
			$ProductNum = isset($_GET['ProductNum']) ? trim($this->input->get('ProductNum')) : "";
			$DescriptionText = isset($_GET['DescriptionText']) ? trim($this->input->get('DescriptionText')) : "";
			
			if(!$as_pdf):
				$SearchBoxViewArray = array();
				$SearchBoxViewArray['ProductSections'] = $this->Product->getProductSections(array('StkItem' => '1'));
				$SearchBoxViewArray['SectionNum'] = $SectionNum;
				$SearchBoxViewArray['CategoryNum'] = $CategoryNum;
				$SearchBoxViewArray['ProductNum'] = $ProductNum;
				$SearchBoxViewArray['DescriptionText'] = $DescriptionText;
				$this->load->view('searchbox', $SearchBoxViewArray);
			endif;
			
			// paging and sorting for the product listing
			$this->load->library('pas_cataloglisting');
			$model_name = "Product";
			$select_clause = "InvtID, Descr, ClassID, StkBasePrc, StkUnit";
			$where_clause = "StkItem = 1 and Hidden = 0";
			if($SectionNum != "All") $where_clause .= " and (ClassID like '".addslashes($SectionNum)."-%' or ClassID = '".addslashes($SectionNum)."')";
			if($CategoryNum != "All") $where_clause .= " and ClassID like '%-".addslashes($CategoryNum)."'";
			if($ProductNum != "") $where_clause .= " and InvtID like '%".addslashes($ProductNum)."%'";
			if($DescriptionText != "") $where_clause .= " and Descr like '%".addslashes($DescriptionText)."%'";
			
			$pas_config = array(
				'SelectClause' => $select_clause,
				'WhereClause' => $where_clause,
				'Model' => $model_name,
				'TableDefHTML' => '<table cellpadding="5" cellspacing="5" class="CatalogListing" width="77%">',
				'ResultDesc' => "Products"
			);
			
			$this->pas_cataloglisting->initialize($pas_config);
			
			if($as_pdf):
				$this->pas_cataloglisting->setColumnInfo('Number', '30', 'InvtID', array('PartOfUniqueIdentifier' => true, 'DefaultSortColumn' => true));
				$this->pas_cataloglisting->setColumnInfo('Section', '16', 'ClassID', array('PartOfUniqueIdentifier' => false, 'IsLargeItem' => false));
				$this->pas_cataloglisting->setColumnInfo('Category', '16', 'ClassID', array('PartOfUniqueIdentifier' => false, 'IsLargeItem' => false));
				$this->pas_cataloglisting->setColumnInfo('Product', '100', 'Descr', array('DefaultSortColumn' => false, 'PartOfUniqueIdentifier' => false, 'IsLargeItem' => false));
				$this->pas_cataloglisting->setColumnInfo('Price', '25', 'StkBasePrc', array('DefaultSortColumn' => false, 'PartOfUniqueIdentifier' => false, 'IsLargeItem' => false));
			else:
				$this->pas_cataloglisting->setColumnInfo('Number', '', 'InvtID', array('Sortable' => true, 'PartOfUniqueIdentifier' => true, 'DefaultSortColumn' => true));
				$this->pas_cataloglisting->setColumnInfo('Section', '', 'ClassID', array('Sortable' => true, 'PartOfUniqueIdentifier' => false, 'IsLargeItem' => false));
				$this->pas_cataloglisting->setColumnInfo('Category', '', 'ClassID', array('Sortable' => ($SectionNum=="All"?false:true), 'PartOfUniqueIdentifier' => false, 'IsLargeItem' => false));
				$this->pas_cataloglisting->setColumnInfo('Product', '', 'Descr', array('Sortable' => true, 'DefaultSortColumn' => false, 'PartOfUniqueIdentifier' => false, 'IsLargeItem' => false));
				$this->pas_cataloglisting->setColumnInfo('Price', '', 'StkBasePrc', array('Sortable' => true, 'PartOfUniqueIdentifier' => false, 'IsLargeItem' => false));
			endif;
			//$ProductListingPAS->setTRClickAction("window.location.href='/catalog/product/[1]';", array("[1]" => "PK_ProductNum"));
			$this->pas_cataloglisting->setTRClickAction("AJAX_DisplayProductDetails('[1]');", array("[1]" => "InvtID"));
			
			$this->load->helper('date');
			if($as_pdf):
				$this->pas_cataloglisting->setTitleHTML('Dealers Supply Catalog - '.Database2DisplayDate($this->System->CURDATE()));
				$HeaderHTML = "";
				if($SectionNum != "All") $HeaderHTML .= "<b>Section:</b> ".$SectionNum."  ";
				if($CategoryNum != "All") $HeaderHTML .= "<b>Category:</b> ".$CategoryNum."  ";
				if($ProductNum != "") $HeaderHTML .= "<b>Number Contains:</b> ".$ProductNum."  ";
				if($DescriptionText != "") $HeaderHTML .= "<b>Description Contains:</b> ".$DescriptionText."  ";
				$this->pas_cataloglisting->setHeaderHTML($HeaderHTML);
			endif;
			$this->pas_cataloglisting->setDefaultSortDirection('asc');
			$this->pas_cataloglisting->setHTMLBetweenRows("");
			$this->pas_cataloglisting->setUserControlLines(2);
			if($as_pdf)
				$this->pas_cataloglisting->setDefaultResultsPerPage("ALL");
			else
				$this->pas_cataloglisting->setDefaultResultsPerPage(10);
			$this->pas_cataloglisting->setMaxResultsPerPage(25);
			$this->pas_cataloglisting->letUserControlResultsPerPage(true);
			$this->pas_cataloglisting->letUserSearchWithin(false);
			$this->pas_cataloglisting->setImageArrowLocation('/img/cataloglisting');
			$this->pas_cataloglisting->setDebugMode(false);	
			if($as_pdf):
				$this->pas_cataloglisting->setViewType('pdf');	
				$this->pas_cataloglisting->setPDFName('Catalog_Search.pdf');
			endif;		

			$CatalogListingView['ListingHTML'] = $this->pas_cataloglisting->displayTable();
			$this->load->view('catalog/listing', $CatalogListingView);
			
			$this->load->view('catalog/footer');
		}

	}
?>