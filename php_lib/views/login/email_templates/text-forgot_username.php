<?php
	/**
	 * This view contains a text-only email template for the the email sent to a user
	 * when they forget their username.  It contains only their username to the site.
	 * If they also forgot their password, they will be instructed as to where to go
	 * to have it reset.
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
You have indicated that you forgot your username to <?=$WebsiteName?>
Your username to the site is: <?=$UserName?>

If you have also forgotten your password, you can copy link below into
the address bar of your web browser to request a password reset:

<?=$ForgotPasswordLink?>

If you did not request your username be sent to you, please just disregard this email.
Someone most likely requested this to be sent to you on your behalf by mistake.

Thanks,
<?=$EmailSignature?>