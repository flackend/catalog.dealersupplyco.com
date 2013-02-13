<?php
	/**
	 * This view contains catalog search that appears on the left side of the catalog
	 * listing page.
	 * 
	 * @package CI_DSSalesRepApp
	 * @subpackage Views
	 * @category Views
	 * 
	 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
	 * @since 2009-03-18
	 * @version 2009-05-01
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 */
?>
<div id="CatalogSearch">
	<form name="CatalogSearchForm" method="get" onsubmit="return PleaseWait(this);">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr><th>Section: </th></tr>
		<tr><td>
			<select id="SectionNumSearch" name="SectionNum" onchange="AJAX_GetCategoryList('CatalogSearchForm','All');">
				<option value="All" style="font-weight:bold;">All</option>
				<?php foreach($ProductSections as $Section): ?>
					<option value="<?=$Section?>" <?=($Section==$SectionNum)?'selected="selected"':''?>><?=$Section?></option>
				<?php endforeach; ?>
			</select>
		</td></tr>
		<tr><th>Category: </th></tr>
		<tr><td>
			<select id="CategoryNumSearch" name="CategoryNum" disabled="disabled">
				<option value="0" style="font-weight:bold;">--Select a Section--</option>
			</select>
		</td></tr>
		<tr><th>Number: </th></tr>
		<tr><td><input id="ProductNumSearch" name="ProductNum" size="20" maxlength="255" value="<?=$ProductNum?>" /></td></tr>
		<tr><th>Description Text: </th></tr>
		<tr><td><input id="DescriptionTextSearch" name="DescriptionText" size="20" maxlength="255" value="<?=$DescriptionText?>" /></td></tr>
		<tr><th>
			<div style="float:right; margin-top:6px; margin-right:0px; font-size:9px;"><a href="javascript:;" onclick="ClearSearchBox();">Clear Search</a></div>
			<input id="CatalogSearchFormSubmit" type="submit" value="Go" />
		</th></tr>
	</table>
	</form>
</div>
<?php if($SectionNum != "All"): ?>
<script type="text/javascript">AJAX_GetCategoryList('CatalogSearchForm','All','<?=$CategoryNum?>');</script>
<?php endif; ?>