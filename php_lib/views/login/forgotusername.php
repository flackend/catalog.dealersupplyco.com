<?php
	/**
	 * This view contains the HTML form for retrieving a lost username into the system.
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
<div style="width:100%; font-size:14px; text-align:center">Please enter the email address you signed up to this site with below to retrieve your user name<br /><br /></div>
<?php if(isset($CustomError)): ?>
	<div class="ErrorMessage" style="font-size:12px; text-align:center;"><?=$CustomError?></div><br />
<?php elseif(validation_errors()): ?>
    <div class="ErrorMessage" style="font-size:12px; text-align:center;">There were errors with your submission.  Please address them below and resubmit.</div><br />
<?php endif; ?>
<form name="ForgotUserName" method="post" style="display:inline" onsubmit="javascript:PleaseWait(this);">
	<table border="0" cellspacing="0" cellpadding="6" align="center" class="LoginTable">
		<tr>
			<th colspan="2">Retrieve User Name</th>
		</tr>
		<tr>
			<td><label for="EmailAddress">Email Address: </label></td>
			<td><input type="text" name="EmailAddress" id="EmailAddress" size="20" maxlength="255" value="<?=set_value('EmailAddress')?>" class="LoginField" /> <?=form_error('EmailAddress')?></td>
		</tr>
		<tr>
			<td colspan="2" nowrap="nowrap"><?=form_error('recaptcha_response_field')?> <?=recaptcha_get_html(constant(APP_IDENT.'_RECAPTCHA_PUBLIC_KEY'), '', (constant(APP_IDENT."_APPLICATION_PROTOCOL") == 'https'?true:false))?></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input type="submit" value="Submit" class="Button" id="ForgotUserNameSubmit" /></td>
		</tr>
	</table>
</form>
<br />
<div style="width:100%; text-align:center"><a href="/">Return to the Home Page</a></div>
<script language="javascript">document.forms['ForgotUserName'].elements['EmailAddress'].focus();</script>