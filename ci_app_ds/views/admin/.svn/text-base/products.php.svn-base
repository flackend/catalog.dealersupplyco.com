<?php
	/**
	 * This view contains the listing of products for the administrative screen along with
	 * the forms for allowing the administrator to add and delete products from the catalog.
	 * 
	 * @package CI_DSSalesRepApp
	 * @subpackage Views-Admin
	 * @category Views-Admin
	 * 
	 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
	 * @since 2009-03-31
	 * @version 2009-06-22
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 */
?>

<div style="padding-top:4px;">
<?=$ListingHTML?>
</div>
</td></tr></table>

<form name="HideProductForm" method="post">
	<input type="hidden" name="RequestType" value="HideProduct" />
	<input type="hidden" name="ProductNum" value="0" />
</form>

<form name="ShowProductForm" method="post">
	<input type="hidden" name="RequestType" value="ShowProduct" />
	<input type="hidden" name="ProductNum" value="0" />
</form>

<h4 id="EditProductH4" <?=(validation_errors() && $RequestType=="EditProduct")?'':'style="display:none"'?>>Edit Product</h4>
<table id="EditProductH4Table" <?=(validation_errors() && $RequestType=="EditProduct")?'':'style="display:none"'?> width="100%" cellspacing="0" cellpadding="0" height="1"><tr><td style="background:url(/img/headerBullets.gif)"><img src="/img/headerBullets.gif" width="4" height="1"></td></tr></table>
<form name="EditProductForm" method="post" style="display:inline" onsubmit="PleaseWait(this);" enctype="multipart/form-data">
<?=(form_error('ProductNumber')?form_error('ProductNumber'):'')?>
<table id="EditProductTable" border="0" cellspacing="0" cellpadding="6" <?=(validation_errors() && $RequestType=="EditProduct")?'':'style="display:none"'?>>
	<tr>
		<td valign="top" class="Required">Section: </td>
		<td id="SectionNumEdit"><?=isset($SectionNum)?$SectionNum:''?></td>
	</tr>
	<tr>
		<td valign="top" class="Required">Category: </td>
		<td id="CategoryNumEdit"><?=isset($CategoryNum)?$CategoryNum:''?></td>
	</tr>
	<tr>
		<td valign="top" class="Required">Number: </td>
		<td id="ProductNumberEdit"><?=isset($ProductNumber)?$ProductNumber:''?></td>
	</tr>
	<tr>
		<td valign="top" class="Required">Name: </td>
		<td id="ProductNameEdit"><?=isset($ProductName)?$ProductName:''?></td>
	</tr>
	<tr>
		<td valign="top" class="Required">Price: </td>
		<td id="ProductPriceEdit"><?=isset($ProductPrice)?$ProductPrice:''?></td>
	</tr>
	<tr>
		<td valign="top" class="Required">Link: </td>
		<td>http:// <input type="text" name="ProductLink" maxlength="255" size="45" value="<?=set_value("ProductLink")?>" /> <?=form_error('ProductLink')?></td>
	</tr>
	<tr>
		<td valign="top" class="Required">Description: </td>
		<td><textarea name="ProductDescription" cols="40" rows="5"><?=set_value("ProductDescription")?></textarea> <?=form_error('ProductDescription')?></td>
	</tr>
	<tr>
		<td valign="top" class="Required">Image: </td>
		<td id="ProductImageTD"><input type="file" name="ProductImage" /> <?=form_error('ProductImage')?></td>
	</tr>
	<tr>
		<td colspan="2">
			<input type="hidden" name="RequestType" value="EditProduct" />
			<input type="hidden" name="ProductNumber" value="<?=set_value('ProductNumber')?set_value('ProductNumber'):'0'?>" />
			<input id="EditProductFormSubmit" type="submit" value="Submit" />
			<input type="button" value="Cancel" onclick="HideProductForms();" />
		</td>
	</tr>
</table>
</form>
<?php if($RequestType=="EditProduct" && @$SectionNum > 0): ?>
<script type="text/javascript">AJAX_GetCategoryList('EditProductForm','', '<?=(isset($CategoryNum)?$CategoryNum:0)?>');</script>
<?php endif; ?>

<?php if(isset($SuccessMessage)): ?>
<script type="text/javascript">EXTJS_DisplaySuccessMsg("Success!", "<?=$SuccessMessage?>");</script>
<?php elseif(isset($ErrorMessage)): ?>
<script type="text/javascript">EXTJS_DisplayErrorMsg("Error", "<?=$ErrorMessage?>");</script>
<?php endif; ?>

<?php if(validation_errors()): ?>
<script type="text/javascript">document.getElementById('EditProductFormSubmit').scrollIntoView(true);</script>
<?php endif; ?>