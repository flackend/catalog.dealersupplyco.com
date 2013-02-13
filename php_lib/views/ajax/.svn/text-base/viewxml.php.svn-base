<?php
	/**
	 * This view displays the passed in array variables in an XML format 
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
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
	header("cache-Control: no-store, no-cache, must-revalidate");
	header("Pragma: no-cache");

	$RandomNum = rand(1,1000);
	
	if(!function_exists('convertArrayToXML'))
	{
		function convertArrayToXML($array)
		{
			require_once('dataVerification.php');
			foreach($array as $index => $value)
			{
				$indexArray = explode('-', $index);
				if(gettype($value) == 'array'):
					echo "<$indexArray[0]>";
					convertArrayToXML($value);
					echo "</$indexArray[0]>";
				else:
					$value = trim($value);
					if($value == "")
						echo "<$indexArray[0] />
";
					elseif(isReal($value))
						echo "<$indexArray[0]>$value</$indexArray[0]>
";
					else
						echo "<$indexArray[0]><![CDATA[$value]]></$indexArray[0]>
";
				endif;
			}
		}
	}
	if(isset($StartTag) && @$StartTag)
		echo "<".APP_IDENT.$RandomNum."AutResp>
";
	
	convertArrayToXML($XMLDocument);
	
	if(isset($EndTag) && @$EndTag)
		echo "</".APP_IDENT.$RandomNum."AutResp>";

	unset($EndTag);
	unset($StartTag);
?>