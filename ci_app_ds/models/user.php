<?php
	/**
	 * This file contains the model for all database related transactions involving
	 * the TB_User table and all tables containing information directly related to
	 * this table's data.  If possible, this file should not merely represent a single table on the
	 * DB_DealersSupply database, but instead represent a cross-section of related data.
	 * This model extends the abstract base model class ESBaseModel for its basic functionality.
	 * 
	 * @package CI_DSSalesRepApp
	 * @subpackage Models
	 * @category Models
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @since 2009-03-17
	 * @version rev115
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 */

	require_once('models/esbaseuser.php');

	/**
	 * User model.  Contains all database connections and sql to and from the TB_User table and any tables that contain information directly related to the information contained in table TB_User. 
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @since 2009-03-17
	 * @version 2009-05-04
	 */
	class User extends ESBaseUser
	{
		
		/**
		 * User class constructor.  The constructor runs all operations in the parent ESBaseUser class
		 * followed by loading both database connections to DB_DealersSupply into objects stored
		 * locally to this class as well as some other variables that are needed for the ESBaseUser and ESBaseModel
		 * routines.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-03-17
		 * @version 2009-05-04
		 * 
		 * @internal
		 * 
		 * @return User Instanciated User model class
		 */
		function __construct()
		{
			$this->PrimaryKeyField = "PK_UserNum";
			$this->TableName = "TB_User";
			$this->SubclassModelName = "User";
			// no paging and sorting support 
			// no record removal support 
			parent::__construct();
			$this->DBmaster = $this->load->database('master', TRUE);
			$this->DBadmin = $this->load->database('admin', TRUE);
		}
		
	}
?>