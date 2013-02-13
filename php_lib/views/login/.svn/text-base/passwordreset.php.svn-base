<?php
/**
 * This view contains the HTML form for resetting a user's account password.
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
<div style="width: 100%; font-size: 13px; text-align: center;">Please
choose a new password for your user account.<br />
This is the password you will use from now on to access this site.</div>
<br />
<?php if(isset($CustomError)): ?>
	<div class="ErrorMessage" style="font-size: 12px; width:100%; text-align:center; padding-bottom:7px;"><?=$CustomError?></div><br />
<?php elseif(validation_errors()): ?>
	<div class="ErrorMessage" style="font-size: 12px; width:100%; text-align:center; padding-bottom:7px;">There were errors with your submission. Please address them below and resubmit.</div><br />
<?php endif; ?>
<form name="PasswordReset" method="post" style="display: inline" onsubmit="PleaseWait(this);">
<table border="0" cellspacing="0" cellpadding="3" align="center" class="LoginTable">
	<tr>
		<th colspan="2">Create New Password</th>
	</tr>
	<tr>
		<td height="28" align="right" class="body-text formRequired"><label for="Password1">New Password: </label></td>
		<td><input name="Password1" id="Password1" type="password" class="form-box" size="20" maxlength="255" value="" /> <?=form_error('Password1')?></td>
	</tr>
	<tr>
		<td height="28" align="right" class="body-text formRequired"><label for="Password2">Confirm New Password: </label></td>
		<td><input name="Password2" id="Password2" type="password" class="form-box" size="20" maxlength="255" value="" /> <?=form_error('Password2')?></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="submit" value="Submit" class="Button" id="PasswordResetSubmit" /></td>
	</tr>
</table>
</form>
<script language="javascript">document.forms['PasswordReset'].elements['Password1'].focus();</script>
