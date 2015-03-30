<?php

/** Error reporting 
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

date_default_timezone_set('Europe/London');*/

/** require the PHPExcel file 1.0 */
    require '../Classes/PHPExcel.php';

/** Set Memory Limit 1.0 */
    ini_set("memory_limit","500M"); // set your memory limit in the case of memory problem

/** Caching to discISAM 1.0*/
/*$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_discISAM;
$cacheSettings = array( 'dir'  => '/usr/local/tmp' // If you have a large file you can cache it optional
                      );*/
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

require_once('conn.php');


$objPHPExcel = new PHPExcel();


$Escola=$_POST['escola'];
$AnoLectivo=$_POST['anoletivo'];
$curso=$_POST['curso'];
$Ano=$_POST['ano'];
$Disciplina=$_POST['disciplina'];
$Turma=$_POST['turma'];
$Periodo=$_POST['periodo'];
$cb1=$_POST['AlunosNegativa'];
$cb2=$_POST['MediaDisciplina'];
$cb3=$_POST['MediaTurma'];
$cb4=$_POST['MediaAno'];
$cb5=$_POST['PercentagemNegativasporAno'];
$cb6=$_POST['PercentagemNegativasporTurma'];
$cb7=$_POST['NegativasporTurma'];
$cb8=$_POST['NegativasporAno'];
$cb9=$_POST['NegativasporDisciplina'];

require_once('..\..\QResult_123.php');

		$it=0;		
		//Alunos com negativas
			if(isset($cb1))
			{							
			$result1 = sqlsrv_query($conn,$query1);
			/** Create a new PHPExcel object 1.0 */
			   
								  
			/** Loop through the result set 1.0 */
				$rowNumber = 2; //start in cell 1
				
				while ($row = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC)) {
				   $col = 'B'; // start at column A
				   foreach($row as $cell) {
					  $objPHPExcel->getActiveSheet()->setCellValue($col.$rowNumber,utf8_encode($cell));
					  $col++;
				   }
				   $rowNumber++;
				}
				
				//nao tem graficos				
			}
			
			//cb2-media por disciplina
			if(isset($cb2))
			{	
				$it++;	
				$result2 = sqlsrv_query($conn,$query2);	 
					$sheet=$objPHPExcel->getActiveSheet();
					if($it > 1)
					{
						$sheet=$objPHPExcel->createSheet();							
							$index=$objPHPExcel->getIndex($sheet);
							$sheet = $objPHPExcel->getSheet($index);				
					}
					else{$sheet = $objPHPExcel->getActiveSheet();}  				
							
			  			  
				$sheet->setCellValue('B1','Disciplina');
				$sheet->setCellValue('C1','Média');
				
				//formatação de cabeçalhos
				$sheet->getStyle('B1:C1')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							'fill' => array(
								
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => 'F79646')		
							)
						)
					);
				$rowNumber = 1; //start in cell 2				
				while ($row = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC)) {
				   $col = 'A'; // start at column B
				   foreach($row as $cell) {
					  $objPHPExcel->getActiveSheet()->setCellValue((++$col).($rowNumber+1),utf8_encode($cell));					  
				   }
				   $rowNumber++;
				}
								
				$objPHPExcel->getActiveSheet()->getStyle('B2:'.$col.$rowNumber )->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
							)
						 )
					);
			$col++;
			$rowNumber++;
			$sheet->setTitle("MédiaPorDisciplina");			
			//criar gráfico			
			
			$objWorksheet = $sheet;								
				//	Set the Labels for each data
				
						$dataseriesLabels = array(
					new PHPExcel_Chart_DataSeriesValues('String', 'MédiaPorDisciplina!$B$2', NULL, 10),	//	Disciplinas
					new PHPExcel_Chart_DataSeriesValues('String', 'MédiaPorDisciplina!$C$2', NULL, 10)	//	Média					
					);
					
				$xAxisTickValues = array(
				new PHPExcel_Chart_DataSeriesValues('String', 'MédiaPorDisciplina!$B$2:B$'.$rowNumber, NULL, 4),	
			);			
				$dataSeriesValues = array(
				new PHPExcel_Chart_DataSeriesValues('Number', 'MédiaPorDisciplina!$C$2:$C$'.$rowNumber, NULL, 4)				
				);
				
				$series = new PHPExcel_Chart_DataSeries(
				PHPExcel_Chart_DataSeries::TYPE_BARCHART,		// plotType
				PHPExcel_Chart_DataSeries::GROUPING_STANDARD,	// plotGrouping
				range(0, count($dataSeriesValues)-1),			// plotOrder
				$dataseriesLabels,								// plotLabel
				$xAxisTickValues,								// plotCategory
				$dataSeriesValues								// plotValues
			);
			
			$series->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);
				
			$plotarea = new PHPExcel_Chart_PlotArea(NULL, array($series));	
			
			//	Set the chart legend
			//$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);

			$title = new PHPExcel_Chart_Title('Média por Disciplina');
			//$yAxisLabel = new PHPExcel_Chart_Title('Média por Disciplina');

			//	Create the chart
			$chart = new PHPExcel_Chart(
				'chart1',		// name
				$title,			// title
				$legend,		// legend
				$plotarea,		// plotArea
				true,			// plotVisibleOnly
				0,				// displayBlanksAs
				NULL,			// xAxisLabel
				$yAxisLabel		// yAxisLabel
			);
			//	Set the position where the chart should appear in the worksheet
			$chart->setTopLeftPosition('I3');
			$chart->setBottomRightPosition('X30');
			
			//	Add the chart to the worksheet
			$objWorksheet->addChart($chart);			
			}
						
			//cb3 media por turma
				if(isset($cb3))
				{	
					$it++;
					$result3 = sqlsrv_query($conn,$query3);	
					
					$sheet=$objPHPExcel->getActiveSheet();
					if($it > 1)
					{
						$sheet=$objPHPExcel->createSheet();							
							$index=$objPHPExcel->getIndex($sheet);
							$sheet = $objPHPExcel->getSheet($index);				
					}
					else{$sheet = $objPHPExcel->getActiveSheet();}
					
															
							
						//fazer cabeçalhos			  
					$sheet->setCellValue('B1','Turma');
					$sheet->setCellValue('C1','Média');
					
				//formatação de cabeçalhos
				$sheet->getStyle('B1:C1')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							'fill' => array(								
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => 'F79646')		
							)
						)
					);
				$rowNumber = 1; //start in cell 2				
				while ($row = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC)) {
				   $col = 'A'; // start at column B
				   foreach($row as $cell) {
					  $sheet->setCellValue((++$col).($rowNumber+1),utf8_encode($cell));					  
				   }
				   $rowNumber++;
				}
				
				$sheet->getStyle('B2:'.$col.$rowNumber )->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
							)
						 )
					);
			$col++;
			$rowNumber++;
			$sheet->setTitle("MédiaPorTurma");			
			//criar gráfico			
			
			$objWorksheet = $sheet;								
				//	Set the Labels for each data
				
						$dataseriesLabels = array(
					new PHPExcel_Chart_DataSeriesValues('String', 'MédiaPorTurma!$B$2', NULL, 10),	//	Disciplinas
					new PHPExcel_Chart_DataSeriesValues('String', 'MédiaPorTurma!$C$2', NULL, 10)	//	Média					
					);
					
				$xAxisTickValues = array(
				new PHPExcel_Chart_DataSeriesValues('String', 'MédiaPorTurma!$B$2:B$'.$rowNumber, NULL, 4),	
			);			
				$dataSeriesValues = array(
				new PHPExcel_Chart_DataSeriesValues('Number', 'MédiaPorTurma!$C$2:$C$'.$rowNumber, NULL, 4)				
				);
				
				$series = new PHPExcel_Chart_DataSeries(
				PHPExcel_Chart_DataSeries::TYPE_BARCHART,		// plotType
				PHPExcel_Chart_DataSeries::GROUPING_STANDARD,	// plotGrouping
				range(0, count($dataSeriesValues)-1),			// plotOrder
				$dataseriesLabels,								// plotLabel
				$xAxisTickValues,								// plotCategory
				$dataSeriesValues								// plotValues
			);
			
			$series->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);
				
			$plotarea = new PHPExcel_Chart_PlotArea(NULL, array($series));	
			
			//	Set the chart legend
			//$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);

			$title = new PHPExcel_Chart_Title('Média por Turma');
			//$yAxisLabel = new PHPExcel_Chart_Title('Média por Disciplina');

			//	Create the chart
			$chart = new PHPExcel_Chart(
				'chart1',		// name
				$title,			// title
				$legend,		// legend
				$plotarea,		// plotArea
				true,			// plotVisibleOnly
				0,				// displayBlanksAs
				NULL,			// xAxisLabel
				$yAxisLabel		// yAxisLabel
			);
			//	Set the position where the chart should appear in the worksheet
			$chart->setTopLeftPosition('I3');
			$chart->setBottomRightPosition('X30');
			
			//	Add the chart to the worksheet
			$objWorksheet->addChart($chart);			
			}
				//cb4 - media por ano	
				if(isset($cb4))
				{
				
				$it++;
				$result4 = sqlsrv_query($conn,$query4);	
				
					$sheet=$objPHPExcel->getActiveSheet();
					if($it > 1)
					{
						$sheet=$objPHPExcel->createSheet();							
							$index=$objPHPExcel->getIndex($sheet);
							$sheet = $objPHPExcel->getSheet($index);				
					}
					else{$sheet = $objPHPExcel->getActiveSheet();}
					
					$sheet->setCellValue('B1','Ano');
					$sheet->setCellValue('C1','Média');
					
				//formatação de cabeçalhos
				$sheet->getStyle('B1:C1')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							'fill' => array(								
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => 'F79646')		
							)
						)
					);
				$rowNumber = 1; //start in cell 2				
				while ($row = sqlsrv_fetch_array($result4,SQLSRV_FETCH_ASSOC)) {
				   $col = 'A'; // start at column B
				   foreach($row as $cell) {
					  $sheet->setCellValue((++$col).($rowNumber+1),utf8_encode($cell));					  
				   }
				   $rowNumber++;
				}
				
				$sheet->getStyle('B2:'.$col.$rowNumber )->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
							)
						 )
					);
			$col++;
			$rowNumber++;
			$sheet->setTitle("MédiaPorAno");			
			//criar gráfico			
			
			$objWorksheet = $sheet;								
				//	Set the Labels for each data
				
						$dataseriesLabels = array(
					new PHPExcel_Chart_DataSeriesValues('String', 'MédiaPorAno!$B$2', NULL, 10),	//	Disciplinas
					new PHPExcel_Chart_DataSeriesValues('String', 'MédiaPorAno!$C$2', NULL, 10)	//	Média					
					);
					
				$xAxisTickValues = array(
				new PHPExcel_Chart_DataSeriesValues('String', 'MédiaPorAno!$B$2:B$'.$rowNumber, NULL, 4),	
			);			
				$dataSeriesValues = array(
				new PHPExcel_Chart_DataSeriesValues('Number', 'MédiaPorAno!$C$2:$C$'.$rowNumber, NULL, 4)				
				);
				
				$series = new PHPExcel_Chart_DataSeries(
				PHPExcel_Chart_DataSeries::TYPE_BARCHART,		// plotType
				PHPExcel_Chart_DataSeries::GROUPING_STANDARD,	// plotGrouping
				range(0, count($dataSeriesValues)-1),			// plotOrder
				$dataseriesLabels,								// plotLabel
				$xAxisTickValues,								// plotCategory
				$dataSeriesValues								// plotValues
			);
			
			$series->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);
				
			$plotarea = new PHPExcel_Chart_PlotArea(NULL, array($series));	
			
			//	Set the chart legend
			//$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);

			$title = new PHPExcel_Chart_Title('Média por Ano');
			//$yAxisLabel = new PHPExcel_Chart_Title('Média por Disciplina');

			//	Create the chart
			$chart = new PHPExcel_Chart(
				'chart1',		// name
				$title,			// title
				$legend,		// legend
				$plotarea,		// plotArea
				true,			// plotVisibleOnly
				0,				// displayBlanksAs
				NULL,			// xAxisLabel
				$yAxisLabel		// yAxisLabel
			);
			//	Set the position where the chart should appear in the worksheet
			$chart->setTopLeftPosition('I3');
			$chart->setBottomRightPosition('X30');
			
			//	Add the chart to the worksheet
			$objWorksheet->addChart($chart);		
			}
			//cb5 - percentagem de negativas por ano
			if(isset($cb5))
			{
				$it++;
				$result5 = sqlsrv_query($conn,$query5);
				
				$sheet=$objPHPExcel->getActiveSheet();
					if($it > 1)
					{
						$sheet=$objPHPExcel->createSheet();							
							$index=$objPHPExcel->getIndex($sheet);
							$sheet = $objPHPExcel->getSheet($index);				
					}
					else{$sheet = $objPHPExcel->getActiveSheet();}
						
						  			  
					$sheet->setCellValue('B1','Ano');
					$sheet->setCellValue('C1','PercentagemNegativasporAno');
					
				//formatação de cabeçalhos
				$sheet->getStyle('B1:C1')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							'fill' => array(								
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => 'F79646')		
							)
						)
					);
				$rowNumber = 1; //start in cell 2				
				while ($row = sqlsrv_fetch_array($result5,SQLSRV_FETCH_ASSOC)) {
				   $col = 'A'; // start at column B
				   foreach($row as $cell) {
					  $sheet->setCellValue((++$col).($rowNumber+1),utf8_encode($cell));					  
				   }
				   $rowNumber++;
				}
				
				$sheet->getStyle('B2:'.$col.$rowNumber )->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
							)
						 )
					);
			$col++;
			$rowNumber++;
			$sheet->setTitle("Percentagem Negativas por Ano");			
			
			}
				//cb6 - percentagem de negativas por turma		
				if(isset($cb6))
				{
				$it++;
				$result6 = sqlsrv_query($conn,$query6);
				$sheet=$objPHPExcel->getActiveSheet();
					if($it > 1)
					{
						$sheet=$objPHPExcel->createSheet();							
							$index=$objPHPExcel->getIndex($sheet);
							$sheet = $objPHPExcel->getSheet($index);				
					}
					else{$sheet = $objPHPExcel->getActiveSheet();}
														
						  			  
					$sheet->setCellValue('B1','Turma');
					$sheet->setCellValue('C1','PercentagemNegativasporTurma');
					
				//formatação de cabeçalhos
				$sheet->getStyle('B1:C1')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							'fill' => array(								
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => 'F79646')		
							)
						)
					);
				$rowNumber = 1; //start in cell 2				
				while ($row = sqlsrv_fetch_array($result6,SQLSRV_FETCH_ASSOC)) {
				   $col = 'A'; // start at column B
				   foreach($row as $cell) {
					  $sheet->setCellValue((++$col).($rowNumber+1),utf8_encode($cell));					  
				   }
				   $rowNumber++;
				}
				
				$sheet->getStyle('B2:'.$col.$rowNumber )->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
							)
						 )
					);
			$col++;
			$rowNumber++;
			$sheet->setTitle("Percentagem Negativas por Turma");			
			
			}
			//cb7 -negativas por turma
			if(isset($cb7))
			{
				$it++;
				$result7 = sqlsrv_query($conn,$query7);	
				$sheet=$objPHPExcel->getActiveSheet();
					if($it > 1)
					{
						$sheet=$objPHPExcel->createSheet();							
							$index=$objPHPExcel->getIndex($sheet);
							$sheet = $objPHPExcel->getSheet($index);				
					}
					else{$sheet = $objPHPExcel->getActiveSheet();}			
														
									  
					$sheet->setCellValue('B1','Turma');
					$sheet->setCellValue('C1','Nº Negativas');
					
				//formatação de cabeçalhos
				$sheet->getStyle('B1:C1')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							'fill' => array(								
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => 'F79646')		
							)
						)
					);
				$rowNumber = 1; //start in cell 2				
				while ($row = sqlsrv_fetch_array($result7,SQLSRV_FETCH_ASSOC)) {
				   $col = 'A'; // start at column B
				   foreach($row as $cell) {
					  $sheet->setCellValue((++$col).($rowNumber+1),utf8_encode($cell));					  
				   }
				   $rowNumber++;
				}
				
				$sheet->getStyle('B2:'.$col.$rowNumber )->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
							)
						 )
					);
			$col++;
			$rowNumber++;
			$sheet->setTitle("NegativasTurma");			
			//criar gráfico			
			
			$objWorksheet = $sheet;								
				//	Set the Labels for each data
				
						$dataseriesLabels = array(
					new PHPExcel_Chart_DataSeriesValues('String', 'NegativasTurma!$B$2', NULL, 10),	//	Disciplinas
					new PHPExcel_Chart_DataSeriesValues('String', 'NegativasTurma!$C$2', NULL, 10)	//	Média					
					);
					
				$xAxisTickValues = array(
				new PHPExcel_Chart_DataSeriesValues('String', 'NegativasTurma!$B$2:B$'.$rowNumber, NULL, 4),	
			);			
				$dataSeriesValues = array(
				new PHPExcel_Chart_DataSeriesValues('Number', 'NegativasTurma!$C$2:$C$'.$rowNumber, NULL, 4)				
				);
				
				$series = new PHPExcel_Chart_DataSeries(
				PHPExcel_Chart_DataSeries::TYPE_BARCHART,		// plotType
				PHPExcel_Chart_DataSeries::GROUPING_STANDARD,	// plotGrouping
				range(0, count($dataSeriesValues)-1),			// plotOrder
				$dataseriesLabels,								// plotLabel
				$xAxisTickValues,								// plotCategory
				$dataSeriesValues								// plotValues
			);
			
			$series->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);
				
			$plotarea = new PHPExcel_Chart_PlotArea(NULL, array($series));	
			
			//	Set the chart legend
			//$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);

			$title = new PHPExcel_Chart_Title('Nº Negativas por Turma');
			//$yAxisLabel = new PHPExcel_Chart_Title('Média por Disciplina');

			//	Create the chart
			$chart = new PHPExcel_Chart(
				'chart1',		// name
				$title,			// title
				$legend,		// legend
				$plotarea,		// plotArea
				true,			// plotVisibleOnly
				0,				// displayBlanksAs
				NULL,			// xAxisLabel
				$yAxisLabel		// yAxisLabel
			);
			//	Set the position where the chart should appear in the worksheet
			$chart->setTopLeftPosition('I3');
			$chart->setBottomRightPosition('X30');
			
			//	Add the chart to the worksheet
			$objWorksheet->addChart($chart);		
			}
			
			// cb8 negativas por ano
			if(isset($cb8))
			{
				$it++;
				$result8 = sqlsrv_query($conn,$query8);	
				$sheet=$objPHPExcel->getActiveSheet();
					if($it > 1)
					{
						$sheet=$objPHPExcel->createSheet();							
							$index=$objPHPExcel->getIndex($sheet);
							$sheet = $objPHPExcel->getSheet($index);				
					}
					else{$sheet = $objPHPExcel->getActiveSheet();}
						 			  
					$sheet->setCellValue('B1','Ano');
					$sheet->setCellValue('C1','Nº Negativas');
					
				//formatação de cabeçalhos
				$sheet->getStyle('B1:C1')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							'fill' => array(								
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => 'F79646')		
							)
						)
					);
				$rowNumber = 1; //start in cell 2				
				while ($row = sqlsrv_fetch_array($result8,SQLSRV_FETCH_ASSOC)) {
				   $col = 'A'; // start at column B
				   foreach($row as $cell) {
					  $sheet->setCellValue((++$col).($rowNumber+1),utf8_encode($cell));					  
				   }
				   $rowNumber++;
				}
				
				$sheet->getStyle('B2:'.$col.$rowNumber )->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
							)
						 )
					);
			$col++;
			$rowNumber++;
			$sheet->setTitle("NegativasAno");			
			//criar gráfico			
			
			$objWorksheet = $sheet;								
				//	Set the Labels for each data
				
						$dataseriesLabels = array(
					new PHPExcel_Chart_DataSeriesValues('String', 'NegativasAno!$B$2', NULL, 10),	//	Disciplinas
					new PHPExcel_Chart_DataSeriesValues('String', 'NegativasAno!$C$2', NULL, 10)	//	Média					
					);
					
				$xAxisTickValues = array(
				new PHPExcel_Chart_DataSeriesValues('String', 'NegativasAno!$B$2:B$'.$rowNumber, NULL, 4),	
			);			
				$dataSeriesValues = array(
				new PHPExcel_Chart_DataSeriesValues('Number', 'NegativasAno!$C$2:$C$'.$rowNumber, NULL, 4)				
				);
				
				$series = new PHPExcel_Chart_DataSeries(
				PHPExcel_Chart_DataSeries::TYPE_BARCHART,		// plotType
				PHPExcel_Chart_DataSeries::GROUPING_STANDARD,	// plotGrouping
				range(0, count($dataSeriesValues)-1),			// plotOrder
				$dataseriesLabels,								// plotLabel
				$xAxisTickValues,								// plotCategory
				$dataSeriesValues								// plotValues
			);
			
			$series->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);
				
			$plotarea = new PHPExcel_Chart_PlotArea(NULL, array($series));	
			
			//	Set the chart legend
			//$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);

			$title = new PHPExcel_Chart_Title('Nº Negativas por Ano');
			//$yAxisLabel = new PHPExcel_Chart_Title('Média por Disciplina');

			//	Create the chart
			$chart = new PHPExcel_Chart(
				'chart1',		// name
				$title,			// title
				$legend,		// legend
				$plotarea,		// plotArea
				true,			// plotVisibleOnly
				0,				// displayBlanksAs
				NULL,			// xAxisLabel
				$yAxisLabel		// yAxisLabel
			);
			//	Set the position where the chart should appear in the worksheet
			$chart->setTopLeftPosition('I3');
			$chart->setBottomRightPosition('X30');
			
			//	Add the chart to the worksheet
			$objWorksheet->addChart($chart);		
			}
						
			  //cb9 - negativas por disciplina
			   if(isset($cb9))
			{	$it++;
				$result9 = sqlsrv_query($conn,$query9);													
					$sheet=$objPHPExcel->getActiveSheet();
					if($it > 1)
					{
						$sheet=$objPHPExcel->createSheet();							
							$index=$objPHPExcel->getIndex($sheet);
							$sheet = $objPHPExcel->getSheet($index);				
					}
					else{$sheet = $objPHPExcel->getActiveSheet();}						
						  			  
					$sheet->setCellValue('B1','Disciplina');
					$sheet->setCellValue('C1','Nº Negativas');
					
				//formatação de cabeçalhos
				$sheet->getStyle('B1:C1')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							'fill' => array(								
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => 'F79646')		
							)
						)
					);
				$rowNumber = 1; //start in cell 2				
				while ($row = sqlsrv_fetch_array($result9,SQLSRV_FETCH_ASSOC)) {
				   $col = 'A'; // start at column B
				   foreach($row as $cell) {
					  $sheet->setCellValue((++$col).($rowNumber+1),utf8_encode($cell));					  
				   }
				   $rowNumber++;
				}
				
				$sheet->getStyle('B2:'.$col.$rowNumber )->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
							)
						 )
					);
			$col++;
			$rowNumber++;
			$sheet->setTitle("NegativasDisciplina");			
			//criar gráfico			
			
			$objWorksheet = $sheet;								
				//	Set the Labels for each data
				
						$dataseriesLabels = array(
					new PHPExcel_Chart_DataSeriesValues('String', 'NegativasDisciplina!$B$2', NULL, 10),	//	Disciplinas
					new PHPExcel_Chart_DataSeriesValues('String', 'NegativasDisciplina!$C$2', NULL, 10)	//	Média					
					);
					
				$xAxisTickValues = array(
				new PHPExcel_Chart_DataSeriesValues('String', 'NegativasDisciplina!$B$2:B$'.$rowNumber, NULL, 4),	
			);			
				$dataSeriesValues = array(
				new PHPExcel_Chart_DataSeriesValues('Number', 'NegativasDisciplina!$C$2:$C$'.$rowNumber, NULL, 4)				
				);
				
				$series = new PHPExcel_Chart_DataSeries(
				PHPExcel_Chart_DataSeries::TYPE_BARCHART,		// plotType
				PHPExcel_Chart_DataSeries::GROUPING_STANDARD,	// plotGrouping
				range(0, count($dataSeriesValues)-1),			// plotOrder
				$dataseriesLabels,								// plotLabel
				$xAxisTickValues,								// plotCategory
				$dataSeriesValues								// plotValues
			);
			
			$series->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);
				
			$plotarea = new PHPExcel_Chart_PlotArea(NULL, array($series));	
			
			//	Set the chart legend
			//$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);

			$title = new PHPExcel_Chart_Title('Nº Negativas por Disciplina');
			//$yAxisLabel = new PHPExcel_Chart_Title('Média por Disciplina');

			//	Create the chart
			$chart = new PHPExcel_Chart(
				'chart1',		// name
				$title,			// title
				$legend,		// legend
				$plotarea,		// plotArea
				true,			// plotVisibleOnly
				0,				// displayBlanksAs
				NULL,			// xAxisLabel
				$yAxisLabel		// yAxisLabel
			);
			//	Set the position where the chart should appear in the worksheet
			$chart->setTopLeftPosition('I3');
			$chart->setBottomRightPosition('X30');
			
			//	Add the chart to the worksheet
			$objWorksheet->addChart($chart);		
			}
			
   
/** Create Excel 2007 file with writer 1.0 */
	
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Estatísticas.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->setIncludeCharts(TRUE);
    $objWriter->save('php://output');
exit;
?>