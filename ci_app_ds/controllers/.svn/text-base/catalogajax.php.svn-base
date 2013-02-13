<?php
	/**
	 * This file contains the controller that will handle any ajax requests made
	 * by sales reps logged into the system.  This ajax information is only accessible
	 * to normal logged in users.
	 * 
	 * @package CI_DSSalesRepApp
	 * @subpackage Controllers
	 * @category Controllers
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @version 2009-03-18
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 */

	require_once('catalog.php');

	/**
	 * CatalogAJAX controller. Contains all routines for AJAX calls made to the catalog controlelr.
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @since 2009-03-18
	 * @version 2009-04-03
	 */
	class CatalogAJAX extends Catalog
	{
		
		/**
		 * CatalogAJAX class constructor. This constructor will rely on the parent
		 * {@see Catalog::__construct()} constructor for its permissions.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-03-18
		 * @version 2009-03-18
		 * 
		 * @return CatalogAJAX Instanciated CatalogAJAX controller class
		 */
		function __construct()
		{
			$this->AJAXCall = true;
			parent::__construct();
		}
		
		/**
		 * This function will return an XML list of all the product categories that
		 * are associated to the given product section.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-03-18
		 * @version 2009-04-15
		 * 
		 * @param int $SectionNum The product section pk to get a list of associated categories from
		 */
		function getcategorylist($SectionNum = 0)
		{
			if($this->AJAXRedirect)
				return;
			
			if($SectionNum == "All")
				return;
				
			$SectionCategories = $this->Product->getProductCategories($SectionNum, array('StkItem' => '1'));
			
			$SectionCategoriesXML = array();
			foreach($SectionCategories as $Category):
				$SectionCategoriesXML["ProductCategory-".$Category]["ID"] = $Category;
				$SectionCategoriesXML["ProductCategory-".$Category]["Name"] = $Category;
			endforeach;
			
			$XMLViewArray = array();
			$XMLViewArray["XMLDocument"] = $SectionCategoriesXML;
			$XMLViewArray["StartTag"] = true;
			$XMLViewArray["EndTag"] = true;
			$this->load->view('ajax/viewxml', $XMLViewArray);
		}
		
		/**
		 * This function will retrieve all data associated to a product record for
		 * use primarily when a sales rep is viewing the product's details.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-04-03
		 * @version 2009-06-22
		 * 
		 * @param int $ProductNum The product pk to retrieve the database information for
		 */
		function getproductinfo($ProductNum = 0)
		{
			if($this->AJAXRedirect)
				return;
			
			if(!$this->Product->exists($ProductNum))
				return;
				
			$this->load->helper('array');
			
			$ProductInfo = object2array($this->Product->getFieldValueFromPK(array('ClassID', 'StkItem', 'Descr', 'StkUnit', 'StkBasePrc'), $ProductNum));
			$SectionCategoryArray = explode('-',$ProductInfo["ClassID"],2);
			$ProductInfo["InvtID"] = $ProductNum;
			$ProductInfo["StkBasePrc"] = number_format(round($ProductInfo["StkBasePrc"],2),2);
			$ProductInfo["ProductSection"] = $SectionCategoryArray[0];
			$ProductInfo["ProductCategory"] = (isset($SectionCategoryArray[1])?$SectionCategoryArray[1]:'');
			$ProductInfo["LongDescr"] = $this->Product->getFieldValueFromPK('LongDescription', $ProductNum);
			$ProductInfo["Link"] = $this->Product->getFieldValueFromPK('Link', $ProductNum);
			unset($ProductInfo["ClassID"]);
			
			$XMLViewArray = array();
			$XMLViewArray["StartTag"] = true;
			$XMLViewArray["EndTag"] = true;
			$XMLViewArray["XMLDocument"] = $ProductInfo;
			
			$this->load->view('ajax/viewxml', $XMLViewArray);
		}
		
	}
?>