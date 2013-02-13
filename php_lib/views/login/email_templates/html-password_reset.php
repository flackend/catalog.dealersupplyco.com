<?php
	/**
	 * This view contains an html email template for the the email sent to a user
	 * after they reset their password through a link provided in a forgot password
	 * email.  This is sent for security purposes in case someone has malicously
	 * reset their password without their consent. 
	 * This will be used only be the {@link Login} controller.
	 * 
	 * @package CI_GeneralESLibs
	 * @subpackage Views-Login-EmailTemplates
	 * @category Views-Login-EmailTemplates
	 * 
	 * @author Daniel Carr <decarr@ethixsystems.com>
	 * @since 2009-05-26
	 * @version 2009-05-26
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 */
?>
<html>
<body>
<p>This email has been sent to confirm that your password to <?=$WebsiteName?> has been reset per your request.<p>

<p>If you did not reset you password for account '<?=$UserName?>' at <?=$WebsiteName?>, please contact us immediately.<br />
Otherwise, you can disregard this notice.</p>

<p>Thanks,<br />
<?=$EmailSignature?></p>
</body>
</html>