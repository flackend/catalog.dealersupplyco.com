<?php
/**
 * @since 2008-06-12
 * @version 2008-07-03
 *
 */
	class GoogleChart
	{
		protected $ChartType = "";
		private $ChartData = "";
		private $ChartColors = "";
		protected $ChartScale = "";
		protected $LabelMinVal = 0;
		protected $LabelMaxVal = 100;
		private $ChartLabels = array();
		protected $ChartSpecificData = array();
		private $ChartPNG = "";
		private $ChartLegendLabels = "";
		private $ChartLegendLocation = "";
		private $ChartTitle = "";
		private $ChartTitleStyle = "";
		
		public function __construct()
		{
			
		}
		
		/**
		 * Enter description here...
		 *
		 * @param array $ChartColors Array of hex values that represent each data set's color
		 */
		public function setChartColors($ChartColors)
		{
			$this->ChartColors = implode(",", $ChartColors);
			$this->ChartColors = str_replace("#","",$this->ChartColors);
		}
		
		/**
		 * setChartLegend creates a legend for the chart
		 *
		 * @param array $legendLabels  The array of labels to apply to the data sets  
		 * @param string $legendLocation  The location of the legend, pass 't' for top, 'l' for left, 'r' for right, or 'b' for bottom
		 */
		public function setChartLegend($LegendLabels, $LegendLocation)
		{
			$this->ChartLegendLabels = implode("|", $LegendLabels);
			$this->ChartLegendLabels = str_replace(' ', '+', $this->ChartLegendLabels);
			if($LegendLocation == "t" || $LegendLocation == "l" || $LegendLocation == "b" || $LegendLocation =="r")
				$this->ChartLegendLocation = $LegendLocation;
		}
		
		/**
		 * Enter description here...
		 *
		 * @param char $Axis The axis to apply labels to (x,y,r,t) => (bottom, left, right, top)
		 * @param string $LabelType The type of label (values,range) => (specific values, range of values)
		 * @param array $Labels For LabelType values => the specific labels that will appear.  For LabelType range => the start and end values for the range to be calculated... array not of size 2 to use calculated max/min by default.
		 * @param array $Positioning Optional array that specified the location that value labels should appear
		 */
		public function setLabels($Axis, $LabelType, $Labels = array(), $Positioning = array())
		{
			$LabelIndex = sizeof($this->ChartLabels);
			if($LabelType == "values")
			{
				$this->ChartLabels[$LabelIndex]["axis"] = $Axis;
				$this->ChartLabels[$LabelIndex]["type"] = "chxl";
				$this->ChartLabels[$LabelIndex]["data"] = "$LabelIndex:|".implode("|",$Labels);
				$this->ChartLabels[$LabelIndex]["data"] = str_replace(' ', '+', $this->ChartLabels[$LabelIndex]["data"]);
				if(sizeof($Positioning == sizeof($Labels)) && sizeof($Positioning) > 0)
					$this->ChartLabels[$LabelIndex]["position"] = $LabelIndex.",".implode(",",$Positioning);
			}
			else if($LabelType == "range")
			{
				$this->ChartLabels[$LabelIndex]["axis"] = $Axis;
				$this->ChartLabels[$LabelIndex]["type"] = "chxr";
				$this->ChartLabels[$LabelIndex]["data"] = "$LabelIndex,";
				if(sizeof($Labels) == 2)
					$this->ChartLabels[$LabelIndex]["data"] .= "{$Labels[0]},{$Labels[1]}";
				else
					$this->ChartLabels[$LabelIndex]["data"] .= "{$this->LabelMinVal},{$this->LabelMaxVal}";
			}
		}
		
		/**
		 * Enter description here...
		 *
		 * @param array $TitleLines Array of title lines... each array element will be on its own line
		 * @param string $TitleColor The color, in hex (without the #), of the title text
		 * @param int $TitleFontSize The size of the font in pixels to be used in the title text
		 */
		public function setChartTitle($TitleLines, $TitleColor, $TitleFontSize)
		{
			$this->ChartTitle = implode('|',$TitleLines);
			$this->ChartTitle = str_replace(' ','+',$this->ChartTitle);
			$this->ChartTitleStyle = "$TitleColor,$TitleFontSize";
		}
		
		public function generateChart($ChartAPILocation, $ChartWidth, $ChartHeight)
		{
			$ChartParams = "cht=".$this->ChartType;
			$ChartParams .= "&chs={$ChartWidth}x{$ChartHeight}";
			if($this->ChartData != "") $ChartParams .= "&chd=".$this->ChartData;
			if($this->ChartScale != "") $ChartParams .= "&chds=".$this->ChartScale;
			if($this->ChartColors != "") $ChartParams .= "&chco=".$this->ChartColors;
			if($this->ChartLegendLabels != "" && $this->ChartLegendLocation) $ChartParams .= "&chdl=".$this->ChartLegendLabels."&chdlp=".$this->ChartLegendLocation;
			if($this->ChartTitle != "")
			{
				$ChartParams .= "&chtt=".$this->ChartTitle;
				if($this->ChartTitleStyle != "") $ChartParams .= "&chts=".$this->ChartTitleStyle;
			}
			foreach($this->ChartSpecificData as $i => $Data)
				$ChartParams .= "&$i=$Data";
			if(sizeof($this->ChartLabels > 0))
			{
				$chxt = array();
				$chxl = array();
				$chxr = array();
				$chxp = array();
				foreach($this->ChartLabels as $LabelIndex => $LabelValues)
				{
					$chxt[] = $LabelValues["axis"];
					${$LabelValues["type"]}[] = $LabelValues["data"];	
					if(isset($LabelValues["position"]))
						$chxp[] = $LabelValues["position"];
				}
				$chxt = implode(",",$chxt);
				$chxl = implode("|",$chxl);
				$chxr = implode("|",$chxr);
				$chxp = implode("|",$chxp);
				if($chxt != "" && ($chxl != "" || $chxr != ""))
				{
					$ChartParams .= "&chxt=$chxt";
					if($chxl != "") $ChartParams .= "&chxl=$chxl";
					if($chxr != "") $ChartParams .= "&chxr=$chxr";
					if($chxp != "") $ChartParams .= "&chxp=$chxp";
				}
			}
			$ChartParams = str_replace('/','%2F',$ChartParams);
			//echo $ChartAPILocation."?$ChartParams";
			$curl_handle = curl_init($ChartAPILocation."?$ChartParams");
			curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, false);
			$this->ChartPNG = curl_exec($curl_handle);
			curl_close($curl_handle);
		}
		
		public function getChartPNG()
		{
			return $this->ChartPNG;
		}
		
		/**
		 * Called by subclasses that require data scaling
		 *
		 * @param array $ChartData The 2D array of chart data supplied by the subclass constructor
		 * @param string $GroupData "stacked" or "grouped" depending on how that data sets are arranged
		 * @param bool $RoundRange True if the label range should be rounded to the nearest significant value
		 */
		protected function _setChartDataScale($ChartData, $GroupData, $RoundRange)
		{
			// determine min and max values for data scaling
			require_once('arrayFunctions.php');
			$ChartDataFlip = Flip2DArray($ChartData);
			
			foreach($ChartDataFlip as $j => $CompleteBar)
			{
				$BarHeightSum = 0;
				foreach($CompleteBar as $k => $BarSegment)
				{
					if($GroupData == "stacked")
						$BarHeightSum += $BarSegment;
					else if($GroupData == "grouped")
						$BarHeightSum = max($BarHeightSum, $BarSegment);
				}
				if(!isset($minValue)) $minValue = $BarHeightSum;
				if(!isset($maxValue)) $maxValue = $BarHeightSum;
				if($BarHeightSum > $maxValue) $maxValue = $BarHeightSum;
				if($BarHeightSum < $minValue) $minValue = $BarHeightSum;
			}
			if($minValue > 0) $minValue = 0;
			if($RoundRange)
			{
				$logVal = floor(log10($maxValue));
				$maxValue = round(($maxValue+4.99*(pow(10, $logVal-1))), $logVal-2); 
				$logVal = floor(log10($minValue));
				$minValue = round(($minValue-4.99*(pow(10, $logVal-1))), $logVal-2);
			}
			$this->ChartScale = $minValue.",".$maxValue;
			$this->LabelMaxVal = $maxValue;
			$this->LabelMinVal = $minValue;
		}
		
		protected function _setChartData($ChartData)
		{
			$this->ChartData = "t:";
			foreach($ChartData as $i => $ChartDataGroup)
				$this->ChartData .= "|".implode(",", $ChartDataGroup);
			$this->ChartData = str_replace("t:|", "t:", $this->ChartData);
		}
	}
?>