<?php
/**
 * @since 2008-06-12
 * @version 2008-06-12
 * 
 */
	require_once('GoogleChart.php');
	
	class GoogleBarChart extends GoogleChart
	{
		/**
		 * Enter description here...
		 *
		 * @param string $Orientation Possible values are "horizontal" and "vertical"*
		 * @param string $GroupData Possible values are "stacked"* and "grouped"
		 * @param array $ChartData 2D array containing the chart data in the format $ChartData[group][x-axis] = y-axis
		 * @param bool $ScaleData True if data range is to be scaled, False if it is to be left at the default 1-100 range
		 * @param bool $RoundRange True if the label range is to be adjusted to the nearest significant value and not left as a random value (ex: if max is 135, max becomes 200).  False to leave the values unadjusted
		 */
		public function __construct($Orientation, $GroupData, $ChartData, $ScaleData = true, $RoundRange = true)
		{
			parent::__construct();
			
			// set the chart type
			$this->ChartType = "b";
			if($Orientation != "horizontal" && $Orientation != "vertical")
				$Orientation = "vertical";
			if($GroupData != "stacked" && $GroupData != "grouped")
				$GroupData = "stacked";
			$this->ChartType .= ($Orientation=="horizontal")?"h":"v";
			$this->ChartType .= ($GroupData=="stacked")?"s":"g";

			// scale the data if necessary
			if($ScaleData)
				$this->_setChartDataScale($ChartData, $GroupData, $RoundRange);
			
			// set chart data
			$this->_setChartData($ChartData);
		}
		
		public function setBarSizes($BarWidth, $SpaceBetweenBarsInGroup = "", $SpaceBetweenGroups = "")
		{
			$this->ChartSpecificData["chbh"] = $BarWidth;
			if($SpaceBetweenBarsInGroup != "") $this->ChartSpecificData["chbh"] .= ",$SpaceBetweenBarsInGroup";
			if($SpaceBetweenGroups != "") $this->ChartSpecificData["chbh"] .= ",$SpaceBetweenGroups";		
		}
		
		/**
		 * This is an implementation for JFreeChart only!
		 * This function must be called for each trend line that is to be added to the chart
		 *
		 * @param array $DataArray The 1d array that contains the values for the trend line
		 * @param string $LineColor The hex color that the line should appear as (default is black)
		 * @param string $LineThickness The size of the trend line in pixels (default is 3)
		 */
		public function setTrendLine($DataArray, $LineColor = "000000", $LineThickness = "3")
		{
			if(!isset($this->ChartSpecificData["ewd2"])) $this->ChartSpecificData["ewd2"] = "t:";
			else $this->ChartSpecificData["ewd2"] .= "|";
			$this->ChartSpecificData["ewd2"] .= implode(",",$DataArray);
			
			if(!isset($this->ChartSpecificData["ewtr"])) $this->ChartSpecificData["ewtr"] = "";
			else $this->ChartSpecificData["ewtr"] .= "|";
			$this->ChartSpecificData["ewtr"] .= "0,$LineColor,$LineThickness";
				
		}
		
		/**
		 * This is an implementation for JFreeChart only!
		 * This function will make the bar chart 3D.
		 */
		public function makeChart3D()
		{
			$this->ChartType .= "3";
		}
	}
?>