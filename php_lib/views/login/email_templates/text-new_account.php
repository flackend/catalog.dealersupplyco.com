<?php
	/**
	 * This view contains a text only email template for the new user account email
	 * containing the activation link to complete setting up their account in the system.
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
Thank you for signing up for <?=$WebsiteName?>.

Before you can start using your account, you must activate it first.
To do this, please copy the following link into the address bar of your web browser:

<?=$VerifyLinkURL?>

After you do this, you should receive a message that your account is activated.

That's all there is to it!  After your account is activated, you will have access to all of the services the site offers.

Thanks again,
<?=$EmailSignature?>