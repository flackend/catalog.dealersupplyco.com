<?php
/**
 * @since 2008-07-03
 * @version 2008-07-03
 * 
 */
	require_once('GoogleChart.php');
	
	class GoogleLineChart extends GoogleChart
	{
		/**
		 * Enter description here...
		 *
		 * @param array $ChartData 2D array containing the chart data in the format $ChartData[line][x-axis] = y-axis
		 * @param bool $ScaleData True if data range is to be scaled, False if it is to be left at the default 1-100 range
		 * @param bool $RoundRange True if the label range is to be adjusted to the nearest significant value and not left as a random value (ex: if max is 135, max becomes 200).  False to leave the values unadjusted
		 */
		public function __construct($ChartData, $ScaleData = true, $RoundRange = true)
		{
			parent::__construct();
			
			// set the chart type
			$this->ChartType = "lc";

			// scale the data if necessary
			if($ScaleData)
				$this->_setChartDataScale($ChartData, "grouped", $RoundRange);
			
			// set chart data
			$this->_setChartData($ChartData);
		}
	
		/**
		 * This function should be called once for each line in the chart
		 *
		 * @param unknown_type $LineThickness The thickness of the chart line in pixels (default 1px)
		 * @param unknown_type $LineSegmentLength The length of each line segment in pixels -for non-solid lines- (default 1px)
		 * @param unknown_type $SpaceBetweenSegments The space in pixels between each line segment -for non-solid lines- (default 1px)
		 */
		public function setLineStyle($LineThickness = 1, $LineSegmentLength = 1, $SpaceBetweenSegments = 0)
		{
			if(!isset($this->ChartSpecificData['chls']))
				$this->ChartSpecificData['chls'] = '';
			else
				$this->ChartSpecificData['chls'] .= '|';
			$this->ChartSpecificData['chls'] .= "$LineThickness,$LineSegmentLength,$SpaceBetweenSegments";
			
		}
	}
?>