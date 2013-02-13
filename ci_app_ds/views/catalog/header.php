<?php
	/**
	 * This view contains the page header that will be displayed for the normal catalog
	 * area of the site that the sales reps have access to.
	 * 
	 * @package CI_DSSalesRepApp
	 * @subpackage Views-Catalog
	 * @category Views-Catalog
	 * 
	 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
	 * @since 2009-03-17
	 * @version 2009-04-07
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- jQuery Includes -->
<script type="text/javascript" src="/js/jquery/jquery.js"></script>
<script type="text/javascript" src="/js/jquery/blockUI.js"></script>
<!-- End jQuery Includes -->

<link rel="stylesheet" href="/css/main.css" type="text/css" media="all" />
<script type="text/javascript" src="/js/main.js"></script>
<script type="text/javascript" src="/js/catalog.js"></script>
<title>Dealers Supply ~ Catalog</title>
</head>

<body>
<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td>
			<div style="float:right">
				<?php if($this->uri->segment(2) != ""): ?>
					<a href="/catalog">&lt;&lt; Product Catalog</a>&nbsp;&nbsp;
				<?php endif; ?>
				<?php if($UserFullName != ""): ?>
					<span id="HeaderName"><?=$UserFullName?></span>&nbsp;&nbsp;
				<?php endif; ?>
				<a href="/logout">Logout</a>
			</div>
			Dealers Supply Catalog			
		</td>
	</tr>
	<tr><td><hr /></td></tr>
</table>
<table border="0" cellspacing="0" cellpadding="0" width="800" align="center" style="height:500px;">
	<tr>
		<td valign="top">
		
		<?php if($this->session->flashdata(APP_IDENT.'_RedirectReason')): ?>
			<table id="RedirectReason" class="ErrorMessage" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
					<td width="19" valign="top"><img src="/js/ext_js/resources/images/default/form/exclamation.gif" /></td>
					<td><?=$this->session->flashdata(APP_IDENT.'_RedirectReason')?></td>
				</tr>
			</table>
			<br />
		<?php endif; ?>
		
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr><td width="100%">