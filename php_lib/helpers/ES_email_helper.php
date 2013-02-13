<?php
	/**
	 * This helper extends the built-in CI email helper to add the ability to send
	 * html encoded emails.  This helper expects an html and a text-only template of
	 * the email body for compatibility with all email clients.
	 * 
	 * @package CI_GeneralESLibs
	 * @subpackage Helpers
	 * @category Helpers
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @since 2009-02-18
	 * @version 2009-02-18
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 */

	if ( ! function_exists('send_html_email'))
	{
		/**
		 * This helper function will send an email in two parts, html and text, based
		 * on the two email bodies provided and the sender/sendee information.
		 *
		 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
		 * @since 2009-02-18
		 * @version 2009-02-18
		 * 
		 * @access public
		 * 
		 * @param string $to The email recipient
		 * @param string $from The email sender
		 * @param string $replyto The email address that any replies to this email should go to
		 * @param string $subject The subject line of the email
		 * @param string $TextTemplateData The string containing the text-only version of the email body
		 * @param string $HTMLTemplateData The string containing the html version of the email body
		 * @return bool True if the email was sent successfully; False otherwise
		 */	
		function send_html_email($to, $from, $replyto, $subject, $TextTemplateData, $HTMLTemplateData)
		{
			//create a boundary string. It must be unique
			//so we use the MD5 algorithm to generate a random hash
			$random_hash = md5(date('r', time()));
			
			//define the headers we want passed. Note that they are separated with \r\n
			$headers = "MIME-Version: 1.0\r\nFrom: $from\r\nReply-To: $replyto";
			//add boundary string and mime type specification
			$headers .= "\r\nContent-Type: multipart/alternative;\r\n\tboundary=\"PHP-alt-".$random_hash."a\"";
			
			//define the body of the message.
			$body = "--PHP-alt-{$random_hash}a\r\n";
			$body .= "Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
			$body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
			$body .= $TextTemplateData;
			$body .= "\r\n\r\n--PHP-alt-{$random_hash}a\r\n";
			$body .= "Content-Type: text/html; charset=\"iso-8859-1\"\r\n";
			$body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
			$body .= $HTMLTemplateData;
			$body .= "\r\n\r\n--PHP-alt-{$random_hash}a--";
			
			return @mail($to,$subject,$body,$headers);
		}
	}
?>