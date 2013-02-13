<?php
	/**
	 * This view contains the page header for the entire administrative section of the site.
	 * 
	 * @package CI_DSSalesRepApp
	 * @subpackage Views-Admin
	 * @category Views-Admin
	 * 
	 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
	 * @since 2009-03-17
	 * @version 2009-04-02
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 */
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- EXT JS Includes -->
<link rel="stylesheet" type="text/css" href="/js/ext_js/resources/css/core.css" />
<link rel="stylesheet" type="text/css" href="/js/ext_js/resources/css/form.css" />
<link rel="stylesheet" type="text/css" href="/js/ext_js/resources/css/combo.css" />
<link rel="stylesheet" type="text/css" href="/js/ext_js/resources/css/button.css" />
<link rel="stylesheet" type="text/css" href="/js/ext_js/resources/css/panel.css" />
<link rel="stylesheet" type="text/css" href="/js/ext_js/resources/css/dialog.css" />
<link rel="stylesheet" type="text/css" href="/js/ext_js/resources/css/window.css" />
<link rel="stylesheet" type="text/css" href="/js/ext_js/resources/css/qtips.css" />
<link rel="stylesheet" type="text/css" href="/js/ext_js/resources/css/progress.css" />
<link rel="stylesheet" type="text/css" href="/js/ext_js/resources/css/layout.css" />
<!-- <link rel="stylesheet" type="text/css" href="/js/ext_js/resources/css/xtheme-slate.css" /> -->
<script type="text/javascript" src="/js/ext_js/adapter/ext/ext-base.js"></script>
<script type="text/javascript" src="/js/ext_js/custom/ext-all-with_password_prompt.js"></script>
<script type="text/javascript" src="/js/ext_js/custom/convert-form.js"></script>
<script type="text/javascript" src="/js/ext_js/custom/make-panel.js"></script>
<!--  End EXT JS Includes -->

<!-- jQuery Includes -->
<script type="text/javascript" src="/js/jquery/jquery.js"></script>
<script type="text/javascript" src="/js/jquery/blockUI.js"></script>
<!-- End jQuery Includes -->

<script type="text/javascript" src="/js/js_lib/boxover.js"></script>

<link rel="stylesheet" href="/css/main.css" type="text/css" media="all" />
<script type="text/javascript" src="/js/admin.js"></script>
<script type="text/javascript" src="/js/main.js"></script>
<title>Dealers Supply ~ Administration</title>
</head>

<body>
<table border="0" cellspacing="0" cellpadding="0" width="800" align="center">
	<tr>
		<td>
			<div style="float:right">
				<?php if($this->uri->segment(2) != ""): ?>
					<a href="/admin">&lt;&lt; Admin Home</a>&nbsp;&nbsp;
				<?php endif; ?>
				<?php if($UserFullName != ""): ?>
					<span id="HeaderName"><?=$UserFullName?></span>&nbsp;&nbsp;
				<?php endif; ?>
				<a href="/logout">Logout</a>
			</div>
			Dealers Supply Administration
		</td>
	</tr>
	<tr><td><hr /></td></tr>
</table>
<table border="0" cellspacing="0" cellpadding="0" width="800" align="center">
	<tr>
		<td>
				
		<?php if($this->session->flashdata(APP_IDENT.'_AdminRedirectReason')): ?>
			<table id="RedirectReason" class="ErrorMessage" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
					<td width="19" valign="top"><img src="/js/ext_js/resources/images/default/form/exclamation.gif" /></td>
					<td><?=$this->session->flashdata(APP_IDENT.'_AdminRedirectReason')?></td>
				</tr>
			</table>
			<br />
		<?php endif; ?>