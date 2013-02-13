<?php	if($TableDataSize < 1): ?>
			<tr><td colspan="<?=count($ColumnArray)?>" width="100%">No matches found</td>
<?php 	else: 
			$RowColor = false;
			$i=0;
			foreach($TableData as $j => $col):
				if(gettype($col) != 'array'):
					$i++;
					continue;
				endif;
			?>
			<tr <?=($RowColor)?'class="PagingAndSortingRowColor rowColor"':''?> <?=($col["TRClick"]!="")?'onclick="'.$col["TRClick"].'"':''?>>
<?php			if(($SearchWithin || !$SortableOnDatabase) && $ResultsPerPage != 'ALL'):
					if($i < $ItemNumberToBeginWith || $i > ($ItemNumberToEndWith)):
						$i++;
						continue;
					endif;
				endif;
				foreach($ColumnArray as $k => $value): ?>
					<td <?=(isset($ColumnArray[$k]['DisplayWidth']))?'width="'.$ColumnArray[$k]['DisplayWidth'].'"':''?>><?=($col[$ColumnArray[$k]['DisplayName']]=="")?'&nbsp;':$col[$ColumnArray[$k]['DisplayName']]?></td>
<?php 			endforeach; ?>
			</tr>
<?php 			$RowColor = !$RowColor; 
				$i++;
				if($i < sizeof($TableData))
					echo $HTMLBetweenRows;
			endforeach; ?>
		</table>
	</td>
</tr>
<?php 	endif;
		if($CurrentPage > 1 || $NumberOfPages > 1): ?>
<tr>
	<td colspan="2" align="left">&nbsp;</td>
	<td align="right"><?=$PageLinkString?></td>
</tr>
<?php 	endif;?>
</table>