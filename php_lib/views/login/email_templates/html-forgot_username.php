<?php
	/**
	 * This view contains an html email template for the the email sent to a user
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
	 * @version 2009-05-26
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 */
?>
<html>
<body>
<p>You have indicated that you forgot your username to <?=$WebsiteName?><br />
Your username to the site is: <?=$UserName?></p>

<p>If you have also forgotten your password, you can click the link below to request a password reset:</p>

<p><a href="<?=$ForgotPasswordLink?>">I Forgot My Password</a></p>

<p>If you did not request your username be sent to you, please just disregard this email.<br />
Someone most likely requested this to be sent to you on your behalf by mistake.</p>

<p>Thanks,<br />
<?=$EmailSignature?></p>
</body>
</html>