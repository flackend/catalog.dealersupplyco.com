<?php
	/**
	 * This view contains a simple XML response for an ajax call that requires the user to be
	 * redirected to a different URL due to security or other data integrety issues.
	 * 
	 * @package CI_GeneralESLibs
	 * @subpackage Views-Ajax
	 * @category Views-Ajax
	 * 
	 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
	 * @since 2009-03-18
	 * @version 2009-05-28
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 */
?>

<?php 
	header("Content-type: text/xml");
	header("cache-Control: no-store, no-cache, must-revalidate");
	header("Pragma: no-cache");
	
	$RandomNum = rand(1,1000);
?>
<<?=APP_IDENT.$RandomNum?>AutResp>
	<Redirect><![CDATA[<?=$URL?>]]></Redirect>
</<?=APP_IDENT.$RandomNum?>AutResp>