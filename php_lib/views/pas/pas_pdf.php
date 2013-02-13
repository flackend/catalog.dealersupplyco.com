<?php 
require_once('TCPDF/tcpdf.php');

class PASPDF extends TCPDF
{
	protected $TitleHTML;
	protected $SearchWithinGet;
	protected $ColumnArray;
	
	function PASPDF($TitleHTML, $HeaderHTML, $SearchWithinGet, $ColumnArray)
	{
		parent::__construct();
		$this->TitleHTML = $TitleHTML;
		$this->HeaderHTML = $HeaderHTML;
		$this->SearchWithinGet = $SearchWithinGet;
		$this->ColumnArray = $ColumnArray;
		$this->SetAutoPageBreak(1,5);
		$this->SetFont('Times');
		$this->SetFontSize(10);
	}
	
	function Header()
	{
		$this->setFont('', 'B', 16);
		$this->Multicell(0, 0, $this->TitleHTML, 0, 'C', 0, 1, '', '', true, 0, true);
		$this->setFont('', '', 10);
		$this->Multicell(0, 0, $this->HeaderHTML, 0, 'C', 0, 1, '', '', true, 0, true);
		if($this->SearchWithinGet != '')
			$this->cell(0, 0, 'Search Parameters: '.$this->SearchWithinGet, 0, 1, 'C');
		foreach($this->ColumnArray as $v): 
			if($v['DoNotDisplayHeader']):
				$this->cell($v['DisplayWidth'], 10, '', 1, 0, 'C');
				continue;
			endif;
			$this->cell($v['DisplayWidth'], 10, $v['DisplayName'], 1, 0, 'C');
		endforeach;
		$this->SetMargins(16.5, $this->GetY()+10);
		$this->setFont('', '', 10);
	}
}

	$pdf =& new PASPDF($TitleHTML, $HeaderHTML, $SearchWithinGet, $ColumnArray); 
	$pdf->addPage();
	
	if($TableDataSize < 1):
		$pdf->cell(0, 0, 'No matches found', 0, 1, 'C');
	else: 
		$RowColor = false;
		$i=0;
		foreach($TableData as $j => $col):
			if(gettype($col) != 'array'):
				$i++;
				continue;
			endif;
			if($RowColor):
				//for row colors to be implemented, we need the red, green, and blue values
				//$pdf->setFillColor();
			endif;
			//get maximum height for a given row
			$maxNumLines = 0;
			if($pdf->GetY() > 275)
				$pdf->AddPage();
			$startPage = $pdf->getPage();
			$startX = $pdf->GetX();
			$startY = $pdf->GetY();
			foreach($ColumnArray as $k => $value):
				$temp = $pdf->Multicell($ColumnArray[$k]['DisplayWidth'], 0, $col[$ColumnArray[$k]['DisplayName']], '', 'L', 0, 0, '', '', true, 0, false);
				$maxNumLines = max(array($maxNumLines, $pdf->getLastH()));
			endforeach;
			$pdf->setPage($startPage);
			$pdf->SetX($startX);
			$pdf->SetY($startY);
			foreach($ColumnArray as $k => $value):
				$pdf->Multicell($ColumnArray[$k]['DisplayWidth'], $maxNumLines, '', 'LTRB', 'L', 0, 0, '', '', true, 0, false);
			endforeach;
			$pdf->Ln();
			$RowColor = !$RowColor;
			$i++;
		endforeach;
	endif;

			
	$pdf->Output($PDFName, 'D');
