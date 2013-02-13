<?php
	/**
	 * This view contains a text-only template for the the email sent to a user
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
	 * @version 2009-06-01
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 */
?>
This email has been sent to confirm that your password to <?=$WebsiteName?> has been reset per your request.

If you did NOT request that your password be reset for account '<?=$UserName?>' at <?=$WebsiteName?>, please contact us immediately.
Otherwise, you can disregard this notice.

Thanks,
<?=$EmailSignature?>