<?php
	/**
	 * This file contains a database "system" controller that can access application
	 * inspecific information from the MySQL database such as the current date time stamp.
	 * 
	 * @package CI_GeneralESLibs
	 * @subpackage Models
	 * @category Models
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @since 2009-04-11
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 */

	/**
	 * System nodel. Contains methods to access all non-application-specific information from the MySQL database.
	 *
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @since 2009-04-11
	 * @version 2009-05-22
	 */
	class System extends Model
	{
		
		/**
		 * @var CI_DB_mysqli_driver This is the connection object for select only operations to the database.
		 */
		var $DBmaster = null;
		
		/**
		 * System class constructor.  The constructor runs all operations in the parent Model
		 * class followed by loading the select only database connection into the object
		 * stored locally to this class.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-04-11
		 * @version 2009-04-11
		 * 
		 * @return System Instanciated System model class
		 */
		function __construct()
		{
			parent::Model();
			$this->DBmaster = $this->load->database('master', TRUE);
		}
		
		/**
		 * This function will select the current date time stamp from the MySQL database server.
		 * This is usually preferred in Ethix System's applications over the PHP function in case
		 * the web and database servers are located on different servers.  The time an action happens
		 * should always reference the database server as it is usually more secure and not in any DMZ.
		 * 
		 * Also note that the CodeIgniter configuration enable_query_strings must be TRUE for this
		 * function to even execute.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-04-11
		 * @version 2009-04-11
		 * 
		 * @return mixed The current date time stamp from the MySQL database formatted as a MySQL date/time stamp
		 */
		function NOW()
		{
			$result = $this->DBmaster->query("select NOW() as now;");
			$row = $result->row();
			return $row->now;
		}
		
		/**
		 * This function will select the current date from the MySQL database server.
		 * This is usually preferred in Ethix System's applications over the PHP function in case
		 * the web and database servers are located on different servers.  The date an action happens
		 * should always reference the database server as it is usually more secure and not in any DMZ.
		 *
		 * Also note that the CodeIgniter configuration enable_query_strings must be TRUE for this
		 * function to even execute.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-04-11
		 * @version 2009-04-11
		 * 
		 * @return mixed The current date from the MySQL database formatted as a MySQL date
		 */
		function CURDATE()
		{
			$result = $this->DBmaster->query("select CURDATE() as curdate;");
			$row = $result->row();
			return $row->curdate;
		}
		
		/**
		 * This function will perform a MySQL DATE_SUB() call.  This is one of the easiest ways
		 * to get a previous date from a start value when dealing with database date/time stamps.
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-04-11
		 * @version 2009-04-11
		 * 
		 * @param date $StartDate A correctly formatted MySQL date stamp to start the subtraction at
		 * @param int $Interval The interval in which to subtract from this date
		 * @param string $IntervalUnit The number of units that the interval represents
		 * 
		 * @return mixed The date after the subtraction has been performed
		 */
		function DATE_SUB($StartDate, $Interval, $IntervalUnit)
		{
			$result = $this->DBmaster->query("select DATE_SUB('$StartDate', INTERVAL $Interval $IntervalUnit) as datesub;");
			$row = $result->row();
			return $row->datesub;
		}
		
		/**
		 * This function will perform a MySQL DATE_ADD() call.  This is one of the easiest ways
		 * to get a date in the future based on a specific number of days, months, etc away
		 * when dealing with database date/time stamps.
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-05-22
		 * @version 2009-05-22
		 * 
		 * @param date $StartDate A correctly formatted MySQL date stamp to start the subtraction at
		 * @param int $Interval The interval in which to subtract from this date
		 * @param string $IntervalUnit The number of units that the interval represents
		 * 
		 * @return mixed The date after the subtraction has been performed
		 */
		function DATE_ADD($StartDate, $Interval, $IntervalUnit)
		{
			$result = $this->DBmaster->query("select DATE_ADD('$StartDate', INTERVAL $Interval $IntervalUnit) as dateadd;");
			$row = $result->row();
			return $row->dateadd;
		}
		
		/**
		 * System class destructor.  Since these database connections are loaded by object instead
		 * of through the database class provided by the framework, we need to deallocate the
		 * connections for them.
		 * 
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-04-11
		 * @version 2009-04-11
		 */
		function __destruct()
		{
			$this->DBmaster = null;
		}
	}
?>