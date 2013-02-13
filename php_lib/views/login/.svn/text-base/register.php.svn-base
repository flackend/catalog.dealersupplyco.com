<?php
	/**
	 * This view contains the registration form for gaining signing up for an account on the site.
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
<?php if(isset($DBError)): ?>
	<div class="ErrorMessage"><?=$DBError?></div><br />
<?php elseif(validation_errors()): ?>
	<div class="ErrorMessage">There were errors with your submission.  Please address them below and resubmit.</div><br />
<?php endif; ?>
Please fill out the following form to create a user account for this site.<br />
All of the following fields are required.<br />
<form name="UserRegistration" method="post" style="display:inline" onsubmit="PleaseWait(this);">
	<table border="0" cellpadding="3" cellspacing="3">
		<tr>
			<td height="28"><label for="FirstName"><b>First Name: </b></label></td>
			<td><input name="FirstName" id="FirstName" type="text" size="35" maxlength="255" value="<?=set_value('FirstName')?>" /> <?=form_error('FirstName')?></td>
		</tr>
		<tr>
			<td height="28"><label for="LastName"><b>Last Name: </b></label></td>
			<td valign="top"><input name="LastName" id="LastName" type="text" size="35" maxlength="255" value="<?=set_value('LastName')?>" /> <?=form_error('LastName')?></td>
		</tr>
		<tr>
			<td height="28"><label for="PhoneNumber"><b>Phone Number: </b></label></td>
			<td><input name="PhoneNumber" id="PhoneNumber" type="text" size="35" maxlength="20" value="<?=set_value('PhoneNumber')?>" /> <?=(form_error('PhoneNumber')?form_error('PhoneNumber'):"(###-###-####)")?></td>
		</tr>
	</table>
	<br />
	All correspondence from the system will be sent to the following email address.<br />Be sure you have immediate access to this email inbox.
	<table border="0" cellpadding="3" cellspacing="3">
		<tr>
			<td height="28" style="padding-top:7px;"><label for="EmailAddress1"><b>Email Address: </b></label></td>
			<td valign="top"><input name="EmailAddress1" id="EmailAddress1" type="text" size="35" maxlength="255" value="<?=set_value('EmailAddress1')?>" /> <?=form_error('EmailAddress1')?></td>
		</tr>
		<tr>
			<td height="28"><label for="EmailAddress2"><b>Confirm Email Address: </b></label></td>
			<td><input name="EmailAddress2" id="EmailAddress2" type="text" size="35" maxlength="255" value="<?=set_value('EmailAddress2')?>" /> <?=form_error('EmailAddress2')?></td>
		</tr>
	</table>
	<br />
	This is the account information you will use to login into the site from now on.<br />
	Make sure you choose information that is easy for you to remember, but not easy for other people to guess.<br />
	<table border="0" cellpadding="3" cellspacing="3">
		<tr>
			<td height="28"><label for="UserName"><b>Create Your Username: </b></label></td>
			<td><input name="UserName" id="UserName" type="text" size="20" maxlength="25" value="<?=set_value('UserName')?>" /> <?=form_error('UserName')?></td>
		</tr>
		<tr>
			<td height="28"><label for="Password1"><b>Create Your Password: </b></label></td>
			<td><input name="Password1" id="Password1" type="password" size="20" maxlength="255" value="<?=(set_value('Password1')===set_value('Password2')?set_value('Password1'):'')?>" /> <?=form_error('Password1')?></td>
		</tr>
		<tr>
			<td height="28"><label for="Password2"><b>Confirm Your Password: </b></label></td>
			<td><input name="Password2" id="Password2" type="password" size="20" maxlength="255" value="<?=(set_value('Password1')===set_value('Password2')?set_value('Password2'):'')?>" /> <?=form_error('Password2')?></td>
		</tr>
		<tr>
			<td colspan="2" valign="middle">
				<input id="UserRegistrationSubmit" type="submit" name="Submit" value="Submit" style="margin-left:20px;" />
				<input type="button" name="Reset" value="Reset" onclick="window.location='/login/register';" />
			</td>
		</tr>
	</table>
</form>
<br /><br /><a href="/">Cancel Account Creation</a>
<script language="javascript">
	document.forms['UserRegistration'].elements['FirstName'].focus();
</script>