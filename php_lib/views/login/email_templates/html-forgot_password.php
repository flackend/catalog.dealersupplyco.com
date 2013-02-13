<?php
	/**
	 * This view contains an html email template for the the email sent to a user
	 * when they forget their password.  It contains an encoded link that will allow
	 * them to reset their password.
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
<p>You have indicated that you forgot your password to <?=$WebsiteName?><br />
for the following user account: <?=$UserName?></p>

<p>For security reasons, we can't send you your current password.<br />
However, you can choose a new password for this account by clicking the link below:</p>

<p><a href="<?=$ResetLinkURL?>">Reset My Password</a></p>

<p>After you reset your password, you will be able to log into the site from now on using only this new password.</p>

<p>Thanks,<br />
<?=$EmailSignature?></p>
</body>
</html>