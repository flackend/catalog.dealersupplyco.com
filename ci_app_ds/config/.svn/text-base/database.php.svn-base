<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the "Database Connection"
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the "default" group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = "ses";
$active_record = TRUE;

$db['ses']['hostname'] = constant(APP_IDENT.'_DB_SERVER');
$db['ses']['username'] = constant(APP_IDENT.'_SES_DB_USER');
$db['ses']['password'] = constant(APP_IDENT.'_SES_DB_PASS');;
$db['ses']['database'] = constant(APP_IDENT.'_SES_DB');
$db['ses']['dbdriver'] = "mysqli";
$db['ses']['dbprefix'] = "";
$db['ses']['pconnect'] = TRUE;
$db['ses']['db_debug'] = (constant(APP_IDENT.'_DEBUG')?TRUE:FALSE);
$db['ses']['cache_on'] = FALSE;
$db['ses']['cachedir'] = "";
$db['ses']['char_set'] = "utf8";
$db['ses']['dbcollat'] = "utf8_general_ci";

$db['master']['hostname'] = constant(APP_IDENT.'_DB_SERVER');
$db['master']['username'] = constant(APP_IDENT.'_APP_DB_S_USER');
$db['master']['password'] = constant(APP_IDENT.'_APP_DB_S_PASS');
$db['master']['database'] = constant(APP_IDENT.'_APP_DB');
$db['master']['dbdriver'] = "mysqli";
$db['master']['dbprefix'] = "";
$db['master']['pconnect'] = TRUE;
$db['master']['db_debug'] = (constant(APP_IDENT.'_DEBUG')?TRUE:FALSE);
$db['master']['cache_on'] = FALSE;
$db['master']['cachedir'] = "";
$db['master']['char_set'] = "utf8";
$db['master']['dbcollat'] = "utf8_general_ci";

$db['admin']['hostname'] = constant(APP_IDENT.'_DB_SERVER');
$db['admin']['username'] = constant(APP_IDENT.'_APP_DB_U_USER');
$db['admin']['password'] = constant(APP_IDENT.'_APP_DB_U_PASS');
$db['admin']['database'] = constant(APP_IDENT.'_APP_DB');
$db['admin']['dbdriver'] = "mysqli";
$db['admin']['dbprefix'] = "";
$db['admin']['pconnect'] = TRUE;
$db['admin']['db_debug'] = (constant(APP_IDENT.'_DEBUG')?TRUE:FALSE);
$db['admin']['cache_on'] = FALSE;
$db['admin']['cachedir'] = "";
$db['admin']['char_set'] = "utf8";
$db['admin']['dbcollat'] = "utf8_general_ci";

$db['inventory']['hostname'] = constant(APP_IDENT.'_INVENTORY_DB_SERVER');
$db['inventory']['username'] = constant(APP_IDENT.'_INVENTORY_DB_USER');
$db['inventory']['password'] = constant(APP_IDENT.'_INVENTORY_DB_PASS');
$db['inventory']['database'] = constant(APP_IDENT.'_INVENTORY_DB');
$db['inventory']['dbdriver'] = "mssql";
$db['inventory']['dbprefix'] = "";
$db['inventory']['pconnect'] = TRUE;
$db['inventory']['db_debug'] = (constant(APP_IDENT.'_DEBUG')?TRUE:FALSE);
$db['inventory']['cache_on'] = FALSE;
$db['inventory']['cachedir'] = "";
$db['inventory']['char_set'] = "utf8";
$db['inventory']['dbcollat'] = "utf8_general_ci";

/* End of file database.php */
/* Location: ./system/application/config/database.php */