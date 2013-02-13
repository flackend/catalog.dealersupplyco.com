<?php
/**
 * @since 2008-07-03
 * @version 2008-07-03
 * 
 */
	require_once('GoogleChart.php');
	
	class GooglePieChart extends GoogleChart
	{
		/**
		 * Enter description here...
		 *
		 * @param string $ChartType The pie chart style you wish to use ("2d" or "3d")
		 * @param array $ChartData array containing the chart data in the format $ChartData[slice] = value
		 * @param bool $ScaleData True if data range is to be scaled, False if it is to be left at the default 1-100 range
		 */
		public function __construct($ChartType, $ChartData, $ScaleData = true)
		{
			parent::__construct();
			
			$FormattedChartData['pie'] = $ChartData;
			
			// set the chart type
			$this->ChartType = "p";
			if($ChartType == "3d")
				$this->ChartType .= "3";
				
			// scale the data if necessary
			if($ScaleData)
				$this->_setChartDataScale($FormattedChartData, "stacked", false);
				
			// set chart data
			$this->_setChartData($FormattedChartData);
		}
		
		public function setPieChartLabels($LabelArray)
		{
			$this->ChartSpecificData['chl'] = implode('|',$LabelArray);
			$this->ChartSpecificData['chl'] = str_replace(' ', '+', $this->ChartSpecificData['chl']);
		}
	}
?>