<?php
	/**
	 * This view contains the HTML form for requesting a password reset email to be sent.
	 * This view is used soley by the {@link Login} controller.
	 * 
	 * @package CI_GeneralESLibs
	 * @subpackage Views-Login
	 * @category Views-Login
	 * 
	 * @author Daniel Carr <decarr@ethixsystems.com>
	 * @since 2009-05-26
	 * @version 2009-06-02
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 */
?>

<br />
<div style="width:100%; font-size:14px; text-align:center">Please enter your user name, the email address you signed up to this<br />site with, and the security code desplayed below to reset your password.<br /><br /></div>
<?php if(isset($CustomError)): ?>
	<div class="ErrorMessage" style="font-size:12px; text-align: center;"><?=$CustomError?></div><br />
<?php elseif(validation_errors()): ?>
    <div class="ErrorMessage" style="font-size:12px; text-align: center;">There were errors with your submission.  Please address them below and resubmit.</div><br />
<?php endif; ?>
<form name="ForgotPassword" method="post" style="display:inline" onsubmit="PleaseWait(this);">
	<table border="0" cellspacing="0" cellpadding="6" align="center" class="LoginTable">
		<tr>
			<th colspan="2">Password Reset Request</th>
		</tr>
		<tr>
			<td><label for="UserName">User Name: </label></td>
			<td><input type="text" name="UserName" id="UserName" size="20" maxlength="255" value="<?=set_value('UserName')?>" class="LoginField" /> <?=form_error('UserName')?></td>
		</tr>
		<tr>
			<td><label for="EmailAddress">Email Address: </label></td>
			<td><input type="text" name="EmailAddress" id="EmailAddress" size="20" maxlength="255" value="<?=set_value('EmailAddress')?>" class="LoginField" /> <?=form_error('EmailAddress')?></td>
		</tr>
		<tr>
			<td colspan="2" nowrap="nowrap"><?=form_error('recaptcha_response_field')?> <?=recaptcha_get_html(constant(APP_IDENT.'_RECAPTCHA_PUBLIC_KEY'), '', (constant(APP_IDENT."_APPLICATION_PROTOCOL") == 'https'?true:false))?></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input type="submit" value="Submit" class="Button" id="ForgotPasswordSubmit" /></td>
		</tr>
	</table>
</form>
<br />
<div style="width:100%; text-align:center"><a href="/login/forgotusername">I Forgot my User Name</a></div>
<br />
<div style="width:100%; text-align:center"><a href="/">Return to the Home Page</a></div>
<script language="javascript">document.forms['ForgotPassword'].elements['UserName'].focus();</script>