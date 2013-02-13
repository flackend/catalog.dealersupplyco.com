<?php
	/**
	 * This view contains the HTML login form for gaining entry into the members only areas of the site.
	 * This view is used solely by the {@link Login} controller.
	 * 
	 * @package CI_DSSalesRepApp
	 * @subpackage Views-Login
	 * @category Views-Login
	 * 
	 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
	 * @since 2009-03-17
	 * @version 2009-03-17
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 */
?>

<?php if($this->session->flashdata(APP_IDENT.'_AutomaticLogoutReason')): ?>
	<table class="ErrorMessage" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td width="19" valign="top"><img src="/js/ext_js/resources/images/default/form/exclamation.gif" /></td>
			<td><?=$this->session->flashdata(APP_IDENT.'_AutomaticLogoutReason')?></td>
		</tr>
	</table>
	<br />
<?  $this->session->sess_destroy();
	$this->session->sess_create();
	endif; ?>
<?php if($this->session->flashdata(APP_IDENT.'_LoginSuccessMessage')): ?>
	<table class="SuccessMessage" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td><?=$this->session->flashdata(APP_IDENT.'_LoginSuccessMessage')?></td>
		</tr>
	</table>
	<br />
<?  $this->session->sess_destroy();
	$this->session->sess_create();
	endif; ?>
<?= (validation_errors() && substr_count(validation_errors(),"Required") == 0) ? '<table border="0" cellspacing="0" align="center"><tr><td>'.validation_errors().'</td></tr></table>':''?>
<form name="UserLogin" method="post" style="display:inline" onsubmit="javascript:PleaseWait(this);">
	<table border="0" cellspacing="0" cellpadding="6" align="center" class="LoginTable">
		<tr>
			<th colspan="2">Please Login</th>
		</tr>
		<tr>
			<td>User Name: </td>
			<td><input type="text" name="UserName" size="20" maxlength="255" value="<?=set_value('UserName')?>" class="LoginField" /> <?=(substr_count(form_error('UserName'),"Required")==1)?form_error('UserName'):''?></td>
		</tr>
		<tr>
			<td>Password: </td>
			<td><input type="password" name="Password" size="20" maxlength="255" class="LoginField" value="" /> <?=(substr_count(form_error('Password'),"Required")==1)?form_error('Password'):''?></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input type="submit" value="Login" class="Button" id="UserLoginSubmit" /></td>
		</tr>
	</table>
</form>
<script language="javascript">document.forms['UserLogin'].elements['UserName'].focus();</script>