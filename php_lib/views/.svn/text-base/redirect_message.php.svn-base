<?php
	/**
	 * This view contains a generic message that can be included in the top of any header file
	 * so that code igniter controllers can easily redirect users to other pages with specific
	 * messages set that will only appear until the page is refreshed or the user browses to
	 * another page.
	 * 
	 * |+| or |-| Can be defined at the beginning of any new message line to flag it as an error or success message
	 *  - if nothing is specified on the line, it will assume it's the type of the first message line
	 *  - if nothing is specified on the first message line, it will assume |-|
	 * 
	 * |L| or |C| or |R| Can be defined at the beginning of any new message line to flag the message line to be aligned to the left, right, or center
	 *  - if nothing is specified on the line, it will assume it's the alignment of the first message line
	 *  - if nothing is specified on the first message line, it will assume |L|
	 * 
	 * |N| Designates a new line in the message that can be styled and aligned independently of any other line
	 * 
	 * Example: |+||C|The survey is complete!|N|Visit our home page to see more information.|N||R|<a href="homepage.html">Go Home</a>
	 * 
	 * @package CI_GeneralESLibs
	 * @subpackage Views
	 * @category Views
	 * 
	 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
	 * @since 2009-05-29
	 * @version 2009-05-29
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 */
?>

<?php if($RR_FullMessage = $this->session->flashdata(APP_IDENT.'_RedirectReason')): $TableCols = 1; ?>
	<table id="RedirectReason" border="0" cellspacing="0" cellpadding="0" align="center">
		<?php 
		$RR_MessageArray = explode('|N|', $RR_FullMessage);
		foreach($RR_MessageArray as $RR_Message):
			$RR_MessageType = substr_count($RR_Message,"|+|") ? "Success" : (isset($RR_FirstMessageType) ? $RR_FirstMessageType : "Error");
			$RR_MessageAlign = substr_count($RR_Message, "|C|") ? "center" : (substr_count($RR_Message, "|L|") ? "left" : (isset($RR_FirstMessageAlign) ? $RR_FirstMessageAlign : "right"));
			$RR_Message = ereg_replace("^(\|.\|)+","", $RR_Message);  // Removes all the alignment and error/success flags from the beginning of the actual display string
			if($RR_Message == "")
				$RR_Message = "&nbsp;";
		?>
			<tr>
				<td style="text-align: <?=$RR_MessageAlign?>" class="<?=$RR_MessageType?>Message">
					<?php if($RR_MessageType == "Error" && !isset($RR_FirstMessageType)): ?><img src="/js/ext_js/resources/images/default/form/exclamation.gif" /><?php endif; ?>
					<?=$RR_Message?>
				</td>
			</tr>
	<?php 
			if(!isset($RR_FirstMessageType))
				$RR_FirstMessageType = $RR_MessageType;
			if(!isset($RR_FirstMessageAlign))
				$RR_FirstMessageAlign = $RR_MessageAlign;
		endforeach;
	?>
	</table>
<?php endif; ?>