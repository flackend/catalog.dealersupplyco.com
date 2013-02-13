<?php // generate results table ?>
<?=$TableDefHTML?>
<?php if($TitleHTML != ""):  ?>
	<tr>
		<td colspan="2" class="PagingAndSortingTitleTD tableTitle" >'.$TitleHTML.'</td>
	</tr>
<?php endif; ?>
<?php if($DisplayPaging || $SearchWithin): ?>
	<tr>
	<?php if($DisplayPaging): ?>
		<td align="left" valign="<?=($UserControlLines==2)?'top':'middle'?>"><?=($TableDataSize>0)?$ResultDesc.' '.$ItemNumberToBeginWith.' - '.$ItemNumberToEndWith.' of '.$TableDataSize.(($UserControlLines==2)?'<br />':' ').'<span style="font-size:12px;">(Page '.$CurrentPage.' of '.$NumberOfPages.')</span>':'&nbsp;'?></td>
		<td colspan="2" align="right" valign="top">
<?php	  	if($UserControlsResultsPerPage)
		echo $PageResultsHTML;
			if($SearchWithin)	
		echo $SearchWithinHTML; ?>
	</td></tr>
<?php 		endif; 
		endif;?>
<tr>
	<td colspan="3">
		<table class="PagingAndSortingContentTable" border="0" cellspacing="0" cellpadding="6" width="100%">
<?php 	// display table header if applicable
		if($DisplayTableHeader): ?>
			<tr>
<?php 		foreach($ColumnArray as $v): 
				if($v['DoNotDisplayHeader']): ?>
				<th/>
<?php 				continue;
				endif; ?>
				<th nowrap="nowrap" style="text-align:left; <?=($DisplaySorting && ($TableDataSize > 0) && $v['Sortable'])?'cursor:pointer':'cursor:auto'?>" <?=(($v['Sortable'])?'onclick="window.location.href = window.location.pathname+\''.$SortGETString.'SortCol='.$v['DisplayName'].'&Page=1'.(($SortCol == $v['DisplayName'] && $SortDir == "asc")?'&SortDir=desc':'&SortDir=asc').'\'"':'')?>>
					<?=$v['DisplayName']?><?=($SortCol != $v['DisplayName'])?'':(($SortDir == "desc")?' <img src="'.$SortArrowLocation.'/sortArrowDown.gif" style="display:inline" />':' <img src="'.$SortArrowLocation.'/sortArrowUp.gif" style="display:inline" />')?>
				</th>
<?php 		endforeach; ?>
			</tr>
<?php 	endif; ?>