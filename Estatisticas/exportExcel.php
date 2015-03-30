<?php

/** Error reporting 
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

date_default_timezone_set('Europe/London');*/

/** require the PHPExcel file 1.0 */
    require 'PHPExcel/Classes/PHPExcel.php';

/** Set Memory Limit 1.0 */
    ini_set("memory_limit","500M"); // set your memory limit in the case of memory problem

/** Caching to discISAM 1.0*/
/*$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_discISAM;
$cacheSettings = array( 'dir'  => '/usr/local/tmp' // If you have a large file you can cache it optional
                      );*/
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

require_once("conn.php");


$objPHPExcel = new PHPExcel();

//tipo de ensino
$sec = $_GET['sec'];
$ciclo23 = $_GET['23ciclo'];
$ciclo1 = $_GET['1ciclo'];

//filtros
$Escola=$_GET['escola'];
$AnoLectivo=$_GET['anoletivo'];
$curso=$_GET['curso'];
$Ano=$_GET['ano'];
$Disciplina=$_GET['disciplina'];
$Turma = $_GET['turma'];
$Periodo=$_GET['periodo'];
$cursoNome = $_GET['cursoNome'];
$turmaNome = $_GET['turmaNome'];
$modular = $_GET['modular'];

	if($modular == 'false')//colocar periodo nos curso de ensino regular
	{		
		$tituloPeriodo = ", ".$Periodo."º Período";
	}	

//ficheiros com querys
require_once('QResult_Excel.php');
require_once('QResultPDA.php');

function modalAlertExcel ()
 {
		global $conn;
		global $queryVerificaRegistos;
		global $Escola, $AnoLectivo, $curso, $Ano, $Periodo, $Turma;		
		global $sec, $ciclo23, $ciclo1;		
		
		$resultVerificaRegistos = sqlsrv_query($conn,$queryVerificaRegistos);
		
		$ResultTemRegistos = sqlsrv_has_rows ($resultVerificaRegistos);
		
		if($ResultTemRegistos)
		{return false;}
		else
		{
			if($sec)
			header("Location: index.php?sec=1&escola=".$Escola."&anoletivo=".$AnoLectivo."&curso=".$curso."&ano=".$Ano."&periodo=".$Periodo."&turma=".$Turma."");
			
			if($ciclo23)
			header("Location: index.php?23ciclo=1&escola=".$Escola."&anoletivo=".$AnoLectivo."&curso=".$curso."&ano=".$Ano."&periodo=".$Periodo."&turma=".$Turma."");

			if($ciclo1)
			header("Location: index.php?1ciclo=1&escola=".$Escola."&anoletivo=".$AnoLectivo."&curso=".$curso."&ano=".$Ano."&periodo=".$Periodo."&turma=".$Turma."");
			
		}	
  
 }

 echo modalAlertExcel ();
 
	//checkboxs
	$cb1=$_GET['AlunosNegativa'];
	$cb2=$_GET['MediaDisciplina'];
	$cb3=$_GET['MediaTurma'];
	$cb4=$_GET['MediaAno'];
	$cb5=$_GET['PercentagemNegativasporAno'];
	$cb6=$_GET['PercentagemNegativasporTurma'];
	$cb7=$_GET['NegativasporTurma'];
	$cb8=$_GET['NegativasporAno'];
	$cb9=$_GET['NegativasporDisciplina'];
	$cb10=$_GET['MediaNotasTurma'];
	$cb11=$_GET['MediaDisciplinaAno'];
	$cb12=$_GET['MediaDisciplinaTurma'];																	
	$cb13=$_GET['dist'];																							
	$cb14=$_GET['3negativas'];
	$cb15=$_GET['portmat'];	
	$cb16=$_GET['DistNivDiscPercent'];																	
	$cb17=$_GET['DistNivTurmaPercent'];
	$cb18=$_GET['MedTurmaAno'];	
	$cb19=$_GET['PDA'];
	$cb20=$_GET['PDA2'];
	
		$it=0;		
		//Alunos com negativas
			if(isset($cb1))
			{
				$it++;							
				$result1 = sqlsrv_query($conn,$query1);
				
				$sheet=$objPHPExcel->getActiveSheet();
				if($it > 1)
					{
						$sheet=$objPHPExcel->createSheet();							
							$index=$objPHPExcel->getIndex($sheet);
							$sheet = $objPHPExcel->getSheet($index);				
					}
					else{$sheet = $objPHPExcel->getActiveSheet();}
					//titulo
					//setar o tamanho de cada coluna
					$sheet->getColumnDimension('D')->setWidth(19);
					$sheet->getColumnDimension('E')->setWidth(75);
					$sheet->getColumnDimension('F')->setWidth(29);
					$sheet->getRowDimension('1')->setRowHeight(30);
					
					$sheet->setCellValue('B1','Ano');
					$sheet->setCellValue('C1','Turma');
					$sheet->setCellValue('D1','Nome do Aluno');
					$sheet->setCellValue('E1','Disciplina');
					$sheet->setCellValue('F1','Nr. de Disciplinas com Negativa');
					//formatação de titulo
					$sheet->getStyle('B1:F1')->applyFromArray(
										array('borders' => array(
													'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
													'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
													'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
													'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
													'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
												),
												'fill' => array(
													
													'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
													'color'		=> array('argb' => '428bca')		
												)
											)
										);				
				
				while ($row = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC)) {
								$alunosNegativa [$row['id_a']] [$row['id_t']] [$row['Ano']] [] = $row['id_d'];													
								$turma [$row['id_t']] = $row['turma'];													
								$nome [$row['id_a']] = $row ['Aluno'];													
								$ENEB [$row['id_d']] = $row ['eneb'];
								$disciplinasAluNEG [$row['id_d']] = $row ['disciplina'];
								$AnoID [$row ['Ano']] = $row ['Ano'];
				}
				$rowNumber = 2;
				foreach ($alunosNegativa as $id_a => $id_t)
				{
					foreach ($id_t as $id_turma=> $AnoArray)
					{
						foreach ($AnoArray  as $id_Ano => $id_d)
						{		$arrayDisciplinas = array ();
								foreach ($id_d as $idDisciplina => $DisciplinaID)
									{	
										$col = 'A';//para cada elemento volta a colocar na coluna B										
										$arrayDisciplinas [] = $disciplinasAluNEG[$DisciplinaID];										
										$objPHPExcel->getActiveSheet()->setCellValue((++$col).($rowNumber),utf8_encode($AnoID[$id_Ano]));//preenche o Ano
										$objPHPExcel->getActiveSheet()->setCellValue((++$col).($rowNumber),utf8_encode($turma[$id_turma]));//preenche a turma
										$objPHPExcel->getActiveSheet()->setCellValue((++$col).($rowNumber),utf8_encode($nome[$id_a]));//preenche a nome
										$objPHPExcel->getActiveSheet()->setCellValue((++$col).($rowNumber),utf8_encode(implode (", ", $arrayDisciplinas)));//preenche a disciplina
										$objPHPExcel->getActiveSheet()->setCellValue((++$col).($rowNumber),utf8_encode(count($id_d)));//preenche a disciplina
										
										
												
									}
								$rowNumber ++;	
						}
					}
				}
					//formatar borders de cada coluna
					$colborder = 'B';
					
					while ($colborder != 'G')
					{
						
						$sheet->getStyle($colborder.'2:'.$colborder.($rowNumber-1))->applyFromArray(
						array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
								),
							
							)
						);	
						$colborder++;
					}						
				
				$sheet->setTitle("AlunosComNegativa");
				
					
				
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
							
			  	$sheet->getColumnDimension('B')->setWidth(20);		  
				$sheet->setCellValue('B31','Disciplina');
				$sheet->setCellValue('C31','Média');
				
				//formatação de cabeçalhos
				$sheet->getStyle('B31:C31')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							'fill' => array(
								
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => '0080FF')		
							)
						)
					);
				$rowNumber = 31; //start in cell 2	
				$AnoRow = "";	
				while ($row = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC)) {
				   $col = 'A'; // start at column B
				   foreach($row as $k=>$cell) {
						if($k != "Ano")
					  {$objPHPExcel->getActiveSheet()->setCellValue((++$col).($rowNumber+1),utf8_encode($cell));}
						if($k == "Ano")
						{
							$AnoRow = $row ['Ano'];						
						}
				   }
				   $rowNumber++;
				}
								
				$objPHPExcel->getActiveSheet()->getStyle('B31:'.$col.$rowNumber )->applyFromArray(
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
							new PHPExcel_Chart_DataSeriesValues('String', 'MédiaPorDisciplina!$B$32', NULL, 10),	//	Disciplinas
							new PHPExcel_Chart_DataSeriesValues('String', 'MédiaPorDisciplina!$C$32', NULL, 10)	//	Média					
							);
							
						$xAxisTickValues = array(
						new PHPExcel_Chart_DataSeriesValues('String', 'MédiaPorDisciplina!$B$32:B$'.$rowNumber, NULL, 4),	
					);			
						$dataSeriesValues = array(
						new PHPExcel_Chart_DataSeriesValues('Number', 'MédiaPorDisciplina!$C$32:$C$'.$rowNumber, NULL, 4)				
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
					
					$title = new PHPExcel_Chart_Title('Média por Disciplina do Ano Lectivo '.$AnoLectivo.'/'.($AnoLectivo+1).', '.$Ano.'º Ano'.$tituloPeriodo);
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
					$chart->setTopLeftPosition('B2');
					$chart->setBottomRightPosition('P29');
					
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
					$sheet->setCellValue('B31','Turma');
					$sheet->setCellValue('C31','Média');
					
				//formatação de cabeçalhos
				$sheet->getStyle('B31:C31')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							'fill' => array(								
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => '0080FF')		
							)
						)
					);
				$rowNumber = 31; //start in cell 2				
				while ($row = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC)) {
				   $col = 'A'; // start at column B
				   foreach($row as $cell) {
					  $sheet->setCellValue((++$col).($rowNumber+1),utf8_encode($cell));					  
				   }
				   $rowNumber++;
				}
				
				$sheet->getStyle('B32:'.$col.$rowNumber )->applyFromArray(
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
					new PHPExcel_Chart_DataSeriesValues('String', 'MédiaPorTurma!$B$32', NULL, 10),	//	Disciplinas
					new PHPExcel_Chart_DataSeriesValues('String', 'MédiaPorTurma!$C$32', NULL, 10)	//	Média					
					);
					
				$xAxisTickValues = array(
				new PHPExcel_Chart_DataSeriesValues('String', 'MédiaPorTurma!$B$32:B$'.$rowNumber, NULL, 4),	
			);			
				$dataSeriesValues = array(
				new PHPExcel_Chart_DataSeriesValues('Number', 'MédiaPorTurma!$C$32:$C$'.$rowNumber, NULL, 4)				
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

			$title = new PHPExcel_Chart_Title('Média por Turma do Ano Lectivo '.$AnoLectivo.'/'.($AnoLectivo+1).', '.$Ano.'º Ano'.$tituloPeriodo);
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
			$chart->setTopLeftPosition('B2');
			$chart->setBottomRightPosition('P29');
			
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
					
					$sheet->setCellValue('B31','Ano');
					$sheet->setCellValue('C31','Média');
					
				//formatação de cabeçalhos
				$sheet->getStyle('B31:C31')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							'fill' => array(								
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => '0080FF')		
							)
						)
					);
				$rowNumber = 31; //start in cell 2				
				while ($row = sqlsrv_fetch_array($result4,SQLSRV_FETCH_ASSOC)) {
				   $col = 'A'; // start at column B
				   foreach($row as $k=>$cell) {
						if($k == "Ano")
						{ $sheet->setCellValue((++$col).($rowNumber+1),utf8_encode($cell).'º Ano');}
						else {$sheet->setCellValue((++$col).($rowNumber+1),utf8_encode($cell));}
						
					  
						
				   }
				   $rowNumber++;
				}
				
				$sheet->getStyle('B32:'.$col.$rowNumber )->applyFromArray(
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
					new PHPExcel_Chart_DataSeriesValues('String', 'MédiaPorAno!$B$32', NULL, 10),	//	Disciplinas
					new PHPExcel_Chart_DataSeriesValues('String', 'MédiaPorAno!$C$32', NULL, 10)	//	Média					
					);
					
				$xAxisTickValues = array(
				new PHPExcel_Chart_DataSeriesValues('String', 'MédiaPorAno!$B$32:B$'.$rowNumber, NULL, 4),	
			);			
				$dataSeriesValues = array(
				new PHPExcel_Chart_DataSeriesValues('Number', 'MédiaPorAno!$C$32:$C$'.$rowNumber, NULL, 4)				
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

			$title = new PHPExcel_Chart_Title('Média por Ano do Ano Lectivo '.$AnoLectivo.'/'.($AnoLectivo+1).', '.$cursoNome.$tituloPeriodo);
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
			$chart->setTopLeftPosition('B2');
			$chart->setBottomRightPosition('P29');
			
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
						
					$sheet->getColumnDimension('C')->setWidth(30);	  			  
					$sheet->setCellValue('B1','Ano');
					$sheet->setCellValue('C1','Percentagem Negativas por Ano');
					
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
								'color'		=> array('argb' => '0080FF')		
							)
						)
					);
				$rowNumber = 1; //start in cell 2				
				while ($row = sqlsrv_fetch_array($result5,SQLSRV_FETCH_ASSOC)) {
				   $col = 'A'; // start at column B
				   foreach($row as $k=>$cell) {
						if($k != "Ano")
					  {$sheet->setCellValue((++$col).($rowNumber+1),utf8_encode($cell).'%');}
					  if ($k != "percentagem")
						{$sheet->setCellValue((++$col).($rowNumber+1),utf8_encode($cell).'º Ano');}
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
			$sheet->setTitle('PercentagemNegativasporAno');			
			
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
						
					$sheet->getColumnDimension('C')->setWidth(32);	  	  			  
					$sheet->setCellValue('B1','Turma');
					$sheet->setCellValue('C1','Percentagem Negativas por Turma');
					
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
								'color'		=> array('argb' => '0080FF')		
							)
						)
					);
				$rowNumber = 1; //start in cell 2				
				while ($row = sqlsrv_fetch_array($result6,SQLSRV_FETCH_ASSOC)) {
				   $col = 'A'; // start at column B
				   foreach($row as $k=>$cell) {
						if($k == "turmas")
					  {$sheet->setCellValue((++$col).($rowNumber+1),utf8_encode($cell));}
					  if ($k == "percentagem")
						{$sheet->setCellValue((++$col).($rowNumber+1),utf8_encode($cell).' %');}
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
			$sheet->setTitle('PercentagemNegativasporTurma');
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
														
					$sheet->getColumnDimension('C')->setWidth(12);	  				  
					$sheet->setCellValue('B31','Turma');
					$sheet->setCellValue('C31','Nº Negativas');
					
				//formatação de cabeçalhos
				$sheet->getStyle('B31:C31')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							'fill' => array(								
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => '428bca')		
							)
						)
					);
				$rowNumber = 31; //start in cell 32				
				while ($row = sqlsrv_fetch_array($result7,SQLSRV_FETCH_ASSOC)) {
				   $col = 'A'; // start at column B
				   foreach($row as $cell) {
					  $sheet->setCellValue((++$col).($rowNumber+1),utf8_encode($cell));					  
				   }
				   $rowNumber++;
				}
				
				$sheet->getStyle('B32:'.$col.$rowNumber )->applyFromArray(
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
			$sheet->setTitle("NegativasPorTurma");			
			//criar gráfico			
			
			$objWorksheet = $sheet;								
				//	Set the Labels for each data
				
						$dataseriesLabels = array(
					new PHPExcel_Chart_DataSeriesValues('String', 'NegativasPorTurma!$B$32', NULL, 10),	//	Disciplinas
					new PHPExcel_Chart_DataSeriesValues('String', 'NegativasPorTurma!$C$32', NULL, 10)	//	Média					
					);
					
				$xAxisTickValues = array(
				new PHPExcel_Chart_DataSeriesValues('String', 'NegativasPorTurma!$B$32:B$'.$rowNumber, NULL, 4),	
			);			
				$dataSeriesValues = array(
				new PHPExcel_Chart_DataSeriesValues('Number', 'NegativasPorTurma!$C$32:$C$'.$rowNumber, NULL, 4)				
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

			$title = new PHPExcel_Chart_Title('Nº Negativas por Turma do Ano Lectivo '.$AnoLectivo.'/'.($AnoLectivo+1).', '.$Ano.'º Ano, '.$Periodo.'º Período');
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
			$chart->setTopLeftPosition('B2');
			$chart->setBottomRightPosition('P29');
			
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
					$sheet->getColumnDimension('C')->setWidth(12);		 			  
					$sheet->setCellValue('B31','Ano');
					$sheet->setCellValue('C31','Nº Negativas');
					
				//formatação de cabeçalhos
				$sheet->getStyle('B31:C31')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							'fill' => array(								
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => '428bca')		
							)
						)
					);
				$rowNumber = 31; //start in cell 2				
				while ($row = sqlsrv_fetch_array($result8,SQLSRV_FETCH_ASSOC)) {
				   $col = 'A'; // start at column B
				   foreach($row as $k=>$cell) {
						if($k == "Ano")
						 {
						 $sheet->setCellValue((++$col).($rowNumber+1),utf8_encode($cell).'º Ano' );		
												 
						 }
						 else {
						
					  $sheet->setCellValue((++$col).($rowNumber+1),utf8_encode($cell));		}
						
				   }
				   $rowNumber++;
				}
				
				$sheet->getStyle('B32:'.$col.$rowNumber )->applyFromArray(
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
			$sheet->setTitle("NegativasPorAno");			
			//criar gráfico			
			
			$objWorksheet = $sheet;								
				//	Set the Labels for each data
				
						$dataseriesLabels = array(
					new PHPExcel_Chart_DataSeriesValues('String', 'NegativasPorAno!$B$32', NULL, 10),	//	Disciplinas
					new PHPExcel_Chart_DataSeriesValues('String', 'NegativasPorAno!$C$32', NULL, 10)	//	Média					
					);
					
				$xAxisTickValues = array(
				new PHPExcel_Chart_DataSeriesValues('String', 'NegativasPorAno!$B$32:B$'.$rowNumber, NULL, 4),	
			);			
				$dataSeriesValues = array(
				new PHPExcel_Chart_DataSeriesValues('Number', 'NegativasPorAno!$C$32:$C$'.$rowNumber, NULL, 4)				
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

			$title = new PHPExcel_Chart_Title('Nº Negativas por Ano do Ano Lectivo '.$AnoLectivo.'/'.($AnoLectivo+1).', '.$cursoNome.', '.$Periodo.'º Período');
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
			$chart->setTopLeftPosition('B2');
			$chart->setBottomRightPosition('P29');
			
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
					$sheet->getColumnDimension('C')->setWidth(15);	
					$sheet->getColumnDimension('B')->setWidth(16);						
					$sheet->setCellValue('B31','Disciplina');
					$sheet->setCellValue('C31','Nº Negativas');
					
				//formatação de cabeçalhos
				$sheet->getStyle('B31:C31')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							'fill' => array(								
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => '428bca')		
							)
						)
					);
				$rowNumber = 31; //start in cell 2
				$Ano = "";				
				while ($row = sqlsrv_fetch_array($result9,SQLSRV_FETCH_ASSOC)) {
				   $col = 'A'; // start at column B
				   foreach($row as $k=>$cell) {
					  if($k != "Ano")
					  $sheet->setCellValue((++$col).($rowNumber+1),utf8_encode($cell));	

						if($k == "Ano")
						{
							$Ano = $row ['Ano'];						
						}					  
				   }			   
				   $rowNumber++;
				}				
				$sheet->getStyle('B32:'.$col.$rowNumber )->applyFromArray(
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
			$sheet->setTitle("NegativasPorDisciplina");			
			//criar gráfico			
			
			$objWorksheet = $sheet;								
				//	Set the Labels for each data
				
						$dataseriesLabels = array(
					new PHPExcel_Chart_DataSeriesValues('String', 'NegativasPorDisciplina!$B$32', NULL, 10),	//	Disciplinas
					new PHPExcel_Chart_DataSeriesValues('String', 'NegativasPorDisciplina!$C$32', NULL, 10)	//	Média					
					);
					
				$xAxisTickValues = array(
				new PHPExcel_Chart_DataSeriesValues('String', 'NegativasPorDisciplina!$B$32:B$'.$rowNumber, NULL, 4),	
			);			
				$dataSeriesValues = array(
				new PHPExcel_Chart_DataSeriesValues('Number', 'NegativasPorDisciplina!$C$32:$C$'.$rowNumber, NULL, 4)				
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

			$title = new PHPExcel_Chart_Title('Nº Negativas por Disciplina do Ano Lectivo '.$AnoLectivo.'/'.($AnoLectivo+1).', '.$Ano.'º Ano, '.$Periodo.'º Período');
			

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
			$chart->setTopLeftPosition('B2');
			$chart->setBottomRightPosition('P29');
			
			//	Add the chart to the worksheet
			$objWorksheet->addChart($chart);		
			}
			
			//cb10 alternativa
			
			if(isset($cb10))
			{	
				$it++;				
				$result10 = sqlsrv_query($conn,$query10);													
					$sheet=$objPHPExcel->getActiveSheet();
					if($it > 1)
					{
						$sheet=$objPHPExcel->createSheet();							
							$index=$objPHPExcel->getIndex($sheet);
							$sheet = $objPHPExcel->getSheet($index);				
					}
					else{$sheet = $objPHPExcel->getActiveSheet();}						
					
					// $sheet->getColumnDimension('D')->setWidth(17);			
					
				//formatação de cabeçalhos
				
				
				$sheet->setTitle("NíveisPorTurma");		
				
				$colNota = 'C';//inicio das notas
				$rowTurma10 = 33;//inicio das turmas				
				
				while ($row = sqlsrv_fetch_array($result10,SQLSRV_FETCH_ASSOC)) {
				
					$notas10 [$row ['nota']] = intval($row ['nota']);
					 
					$idturma10 [$row['id']] = $row['turma'];
					
					$DisciplinaOcorrencias10 [$row['id']][intval($row['nota'])] = $row ['ocorrencias']; 				
				
				}	
				
				$notas10= range(min ($notas10), max($notas10));//todas as notas
													
					foreach ($notas10 as $kn=>$vn)											
					{
						$sheet->setCellValue(($colNota++).'32',utf8_encode($vn));//escreve as notas na tabela	
																
					}									
												
					 foreach ($idturma10 as $kd=>$vd)									
					{	$colOcorrencia = 'C';//inicio das ocorrencias (igual a notas)
						 $sheet->setCellValue('B'.($rowTurma10),utf8_encode($vd));//preencher turmas	
						
												
						 foreach ($notas10 as $knotas=>$vnotas)
						 { 
							
							 if($DisciplinaOcorrencias10[$kd][$vnotas]=="")
							 {																	
								$DisciplinaOcorrencias10[$kd][$vnotas] = "0";														
							 }
								
								 $sheet->setCellValue(($colOcorrencia++).($rowTurma10),utf8_encode($DisciplinaOcorrencias10[$kd][$vnotas]));									
						 }															
						$rowTurma10++;					
					}
					$sheet->setCellValue('B32','Turma');	
					
				
				$sheet->getStyle('B32')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							'fill' => array(								
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => '428bca')		
							)
						)
					);
					
					$sheet->getStyle('C32:'.$colNota.'32')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							
						)
					);
					$sheet->getStyle('B33:B'.($rowTurma10-1))->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							
						)
					);					
					//graficos
					$rowTurmaLabel = ($rowTurma10-1);
					$objWorksheet = $sheet;								
				//Set the Labels for each data
						$iter=33;					
						$dataseriesLabels = array();
						while($rowTurmaLabel>=33)
						{
							$dataseriesLabels [] =new PHPExcel_Chart_DataSeriesValues('String', 'NíveisPorTurma!$B$'.$iter, NULL, 10);//turma
							$rowTurmaLabel--;
							$iter++;
						}					
					
				$xAxisTickValues = array(
				new PHPExcel_Chart_DataSeriesValues('String', 'NíveisPorTurma!$C$32:$'.$colNota.'$32', NULL, 4)	
			);				
				$rowTurmaData = ($rowTurma10-1);
				$colNotaData = $colNota;
				$dataSeriesValues = array();
				
				
				while($rowTurmaData>=33)
				{					
					$dataSeriesValues[] = new PHPExcel_Chart_DataSeriesValues('Number', 'NíveisPorTurma!$C$'.$rowTurmaData.':$'.$colNotaData.'$'.$rowTurmaData, NULL, 4);
					$rowTurmaData--;					
				}			
				
				$series10 = new PHPExcel_Chart_DataSeries(
				PHPExcel_Chart_DataSeries::TYPE_BARCHART,		// plotType
				PHPExcel_Chart_DataSeries::GROUPING_STANDARD,	// plotGrouping
				range(0, count($dataSeriesValues)-1),			// plotOrder
				$dataseriesLabels,								// plotLabel
				$xAxisTickValues,								// plotCategory
				$dataSeriesValues								// plotValues
			);
			
			$series10->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);
				
			$plotarea = new PHPExcel_Chart_PlotArea(NULL, array($series10));	
			
			//	Set the chart legend
			$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);

			$title = new PHPExcel_Chart_Title('Níveis por Turma do Ano Lectivo '.$AnoLectivo.'/'.($AnoLectivo+1).', '.$Ano.'º Ano'.$tituloPeriodo);
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
			$chart->setTopLeftPosition('B2');
			$chart->setBottomRightPosition('P29');
			$sheet->mergeCells('C31:'.($colNota).'31');
					$sheet->setCellValue('C31','Nota');
					$sheet->getStyle('C31:'.$colNota.'31')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							'fill' => array(								
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => '428bca')		
							)
						)
					);
			
				//	Add the chart to the worksheet
				$objWorksheet->addChart($chart);	
			
				
				
			}		
			
   	//cb11 - media por disciplina ano
			   if(isset($cb11))
			{	
				$it++;
				$result11 = sqlsrv_query($conn,$query11);
				
					if($it > 1)
					{
						$sheet=$objPHPExcel->createSheet();							
							$index=$objPHPExcel->getIndex($sheet);
							$sheet = $objPHPExcel->getSheet($index);				
					}
					else{$sheet = $objPHPExcel->getActiveSheet();}									
					$sheet->getColumnDimension('B')->setWidth(20);	  			  
					$sheet->setCellValue('B32','Disciplina');
					$sheet->mergeCells('C31:E31');
					$sheet->setCellValue('C31','Ano');				
					
					$sheet->getStyle('B32')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							'fill' => array(								
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => '428bca')		
							)
						)
					);
				$rowNumber = 33; //start in cell 2
				$rowNumberDisc =33;
				$sheet->setTitle("MediaDisciplinaAno");				
				
				$disctotal = array();
				$discval = array();
				
				while ($row = sqlsrv_fetch_array($result11,SQLSRV_FETCH_ASSOC)) {			   
				   
				   $disctotal [$row['id_d']] = $row['disciplina'];
				   
				   $discval [$row['Ano']][$row['id_d']] = $row ['media'];					   
				   }
				   
				   $col2 = 'C';
				  foreach($discval as $k2=>$v2)//preencher labels
						{							
							$sheet->setCellValue($col2++.'32',(utf8_encode($k2).'º Ano'));					
						} 

					foreach ($disctotal as $keydisc=>$discdes)						
					{
						$sheet->setCellValue('B'.($rowNumberDisc),utf8_encode($discdes));
						$col = 'C'; // start at column B	
						foreach($discval as $k1=>$v1)		
						{										
								$sheet->setCellValue(($col++).($rowNumberDisc),utf8_encode($v1[$keydisc]));								
								
						}
						$rowNumberDisc++;	
					}
				/*	
				
				$sheet->getStyle('B2:'.$col.$rowNumber )->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
							),
							
						'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
									'vertical'  => 	PHPExcel_Style_Alignment::VERTICAL_CENTER						)
						 )
					);*/
			
				//formatação de cabeçalhos
				$sheet->getStyle('C31:'.($col2).'31')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							'fill' => array(								
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => '428bca')		
							)
						)
					);
				
			/*//mergecells
			$countDisc = count($discval);
			$countTotal= count($disctotal);
			$mergerow=2;
			while ($countDisc > 0)
			{				
				$sheet->mergeCells('B'.$mergerow.':B'.(($mergerow+$countTotal)-1));
				$mergerow = $mergerow+$countTotal;				
				$countDisc--;
			}		*/
			
			//criar gráfico
			
			$objWorksheet = $sheet;								
				//	Set the Labels for each data		
		
				$collabel = 'C';
				$countDV = 0;
				$countLabel = count($discval);
				$dataSerieslabels = array ();
					while ($countDV <= $countLabel)
					{	
							$dataseriesLabels[] =new PHPExcel_Chart_DataSeriesValues('String', 'MediaDisciplinaAno!$'.$collabel.'$32', NULL, 1);//Ano
							$countDV++;
							$collabel++;	
					}		

			$lenghtDisciplinas = count($disctotal);			
			
			$xAxisTickValues = array(
			new PHPExcel_Chart_DataSeriesValues('String', 'MediaDisciplinaAno!$B$33:$B$'.(33+$lenghtDisciplinas), NULL, 4),	//	Q1 to Q4
);
			
			$dataSeriesValues = array();
				$auxdataVal='C';				
				$countqq= count($disctotal);
				$countLabel = count($discval);
				$itLabel=0;
				
				
				while ($itLabel < $countLabel)
					{						
						$dataSeriesValues[] = new PHPExcel_Chart_DataSeriesValues('Number', 'MediaDisciplinaAno!$'.$auxdataVal.'$33:$'.$auxdataVal.'$'.(33+$countqq-1), NULL, 20);	//dados						
						$auxdataVal++;
						$itLabel++;		
						
					} 
				
				//	Build the dataseries
	$series = new PHPExcel_Chart_DataSeries(
	PHPExcel_Chart_DataSeries::TYPE_BARCHART,		// plotType
	PHPExcel_Chart_DataSeries::GROUPING_STANDARD,	// plotGrouping
	range(0, count($dataSeriesValues)-1),			// plotOrder
	$dataseriesLabels,								// plotLabel
	$xAxisTickValues,								// plotCategory
	$dataSeriesValues								// plotValues
);
//	Set additional dataseries parameters
//		Make it a vertical column rather than a horizontal bar graph
$series->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);

//	Set the series in the plot area
$plotarea = new PHPExcel_Chart_PlotArea(NULL, array($series));
//	Set the chart legend
$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);

$title = new PHPExcel_Chart_Title('Media por Disciplina e Ano do Ano Lectivo '.$AnoLectivo.'/'.($AnoLectivo+1).', '.$cursoNome.$tituloPeriodo);



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
$chart->setTopLeftPosition('B2');
$chart->setBottomRightPosition('P29');

//	Add the chart to the worksheet
$objWorksheet->addChart($chart);
			}
			
	
			//cb12 - media por Disciplina e Turma
			   if(isset($cb12))
			{	$it++;
				$result12 = sqlsrv_query($conn,$query12);	
				
					$sheet=$objPHPExcel->getActiveSheet();
					if($it > 1)
					{
						
						$sheet=$objPHPExcel->createSheet();							
							$index=$objPHPExcel->getIndex($sheet);
							$sheet = $objPHPExcel->getSheet($index);				
					}
					else{$sheet = $objPHPExcel->getActiveSheet();}			
					
					$sheet->getColumnDimension('B')->setWidth(18);	  			  
					$sheet->setCellValue('B32','Disciplina');
					$sheet->setCellValue('C31','Turma');			
					
				$rowNumber = 33; //start in cell 2
				$rowNumberDisc =33;
				$sheet->setTitle("MediaDisciplinaTurma");				
				
				$disctotal = array();
				$discval = array();
				
				while ($row = sqlsrv_fetch_array($result12,SQLSRV_FETCH_ASSOC)) {			   
				   
				   $disctotal [$row['id_d']] = $row['disciplina'];
				   
				   $discval [$row['turma']][$row['id_d']] = $row ['media'];					   
				   }
				   
				   $col2 = 'C';
				  foreach($discval as $k2=>$v2)//preencher labels
						{							
							$sheet->setCellValue($col2++.'32',utf8_encode($k2));					
						}
						
					foreach ($disctotal as $keydisc=>$discdes )						
					{
						$sheet->setCellValue('B'.($rowNumberDisc),utf8_encode($discdes));
						$col = 'C'; // start at column B	
						foreach($discval as $k1=>$v1)		
						{										
								$sheet->setCellValue(($col++).($rowNumberDisc),utf8_encode($v1[$keydisc]));		
										
						}
						$rowNumberDisc++;	
					}
				//formatação de cabeçalhos
				$sheet->getStyle('C31:'.$col2.'31')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							'fill' => array(								
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => '428bca')		
							)
						)
					);
					
					$sheet->getStyle('B32')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							'fill' => array(								
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => '428bca')		
							)
						)
					);
					
					$sheet->getStyle('B32:B'.$rowNumberDisc)->applyFromArray(
						array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
								),
							
							)
						);	
						
					$sheet->getStyle('C32:'.$col2.'32')->applyFromArray(
						array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
								),
							
							)
						);		
			
			//criar gráfico
			
			$objWorksheet = $sheet;								
				//	Set the Labels for each data		
		
				$collabel = 'C';
				$countDV = 0;
				$countLabel = count($discval);
				$dataSerieslabels12 = array ();
					while ($countDV <= $countLabel)
					{	
							$dataseriesLabels12[] =new PHPExcel_Chart_DataSeriesValues('String', 'MediaDisciplinaTurma!$'.$collabel.'$32', NULL, 1);//Ano
							$countDV++;
							$collabel++;	
					}				

			$lenghtDisciplinas = count($disctotal);
					
			
			$xAxisTickValues = array(
			new PHPExcel_Chart_DataSeriesValues('String', 'MediaDisciplinaTurma!$B$33:$B$'.(33+$lenghtDisciplinas), NULL, 4),	//	Q1 to Q4
				);
			
			$dataSeriesValues = array();
				$auxdataVal='C';				
				$countqq= count($disctotal);
				$countLabel = count($discval);
				$it=0;			
				
				while ($it < $countLabel)
					{						
						 $dataSeriesValues[] = new PHPExcel_Chart_DataSeriesValues('Number', 'MediaDisciplinaTurma!$'.$auxdataVal.'$33:$'.$auxdataVal.'$'.(33+$countqq-1), NULL, 20);	//dados
						 
						
						$auxdataVal++;
						$it++;		
						
					} 
				
							//	Build the dataseries
				$series = new PHPExcel_Chart_DataSeries(
				PHPExcel_Chart_DataSeries::TYPE_BARCHART,		// plotType
				PHPExcel_Chart_DataSeries::GROUPING_STANDARD,	// plotGrouping
				range(0, count($dataSeriesValues)-1),			// plotOrder
				$dataseriesLabels12,								// plotLabel
				$xAxisTickValues,								// plotCategory
				$dataSeriesValues								// plotValues
			);
			//	Set additional dataseries parameters
			//		Make it a vertical column rather than a horizontal bar graph
			$series->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);

			//	Set the series in the plot area
			$plotarea = new PHPExcel_Chart_PlotArea(NULL, array($series));
			//	Set the chart legend
			$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);

			$title = new PHPExcel_Chart_Title('Media por Disciplina e Turma do Ano Lectivo '.$AnoLectivo.'/'.($AnoLectivo+1).', '.$Ano.'º Ano'.$tituloPeriodo);



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
			$chart->setTopLeftPosition('B2');
			$chart->setBottomRightPosition('P29');

			//	Add the chart to the worksheet
			$objWorksheet->addChart($chart);	
						}
						
			//cb13 - Distribuição notas por disciplina
			   if(isset($cb13))
			{	$it++;
				$result13 = sqlsrv_query($conn,$query13);													
					$sheet=$objPHPExcel->getActiveSheet();
					if($it > 1)
					{
						$sheet=$objPHPExcel->createSheet();							
							$index=$objPHPExcel->getIndex($sheet);
							$sheet = $objPHPExcel->getSheet($index);				
					}
					else{$sheet = $objPHPExcel->getActiveSheet();}						
					$sheet->getColumnDimension('B')->setWidth(17);
					$sheet->getColumnDimension('D')->setWidth(18);					
					$sheet->setCellValue('B31','Disciplina');
					$sheet->setCellValue('C31','Nota');
					$sheet->setCellValue('D31','Nº de Ocorrências');
					
					
				//formatação de cabeçalhos
				$sheet->getStyle('B31:D31')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							'fill' => array(								
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => '428bca')		
							)
						)
					);
					
				$rowNumber = 31; //start in cell 2
				$rowNumberID = 1000;
				$sheet->setTitle("NrOcorrenciasPorDisciplina");
				$id10="";
				$rnid=0;				
				$rowChangeId=0;
				$dataVal = array();
				$kush="";
				$varnota=0;
				$colNota=0;		
				
				//definir fim de escala 
				if($sec)
				{
					$escala=21;
				}
				elseif($ciclo23 || $ciclo1)
				{
					$escala=6;
				}
				
				while ($row = sqlsrv_fetch_array($result13,SQLSRV_FETCH_ASSOC))
				{
					$xnota=$varnota+1;	
					$col = 'A'; // start at column B
					
					  if($row['id_d']!=$id10 && $id10!="" )
				        {
						 
								while($xnota < $escala)
								{
									$sheet->setCellValue((++$col).($rowNumber+1),utf8_encode($row['Disciplina']));
									$sheet->setCellValue((++$col).($rowNumber+1),utf8_encode($xnota));
									$sheet->setCellValue((++$col).($rowNumber+1),utf8_encode('0'));
									
									$rowNumber++;
									$col = 'A';
									$xnota++;
									$varnota++;	
								}
								$varnota=0;
								$xnota=1;
						}
						
							while($xnota < $row['nota'])
						  {
							$sheet->setCellValue((++$col).($rowNumber+1),utf8_encode($row['Disciplina']));
							$sheet->setCellValue((++$col).($rowNumber+1),utf8_encode($xnota));
							$sheet->setCellValue((++$col).($rowNumber+1),utf8_encode('0'));
							
							$rowNumber++;
							$col = 'A';
							$xnota++;
							$varnota++;	
						  }				
				 
				   
				   foreach($row as $k=>$cell)					
					{
						
						
						if($k!="id_d")
						{	
							
							$sheet->setCellValue((++$col).($rowNumber+1),utf8_encode($cell));
							if($kush!=$row['Disciplina'])
								{
									$kush=$row['Disciplina'];
									$rowChangeId=$rowNumber+1;
									$dataVal[] = $rowChangeId;									
								}								
						}					
								
						if($row['id_d']!=$id10)
						{
															
							$cellDes=$row['Disciplina'];							
							$sheet->setCellValue('Z'.$rowNumberID++,utf8_encode($cellDes));							
							$id10=$row['id_d'];
							$rnid++;							
						}
					}	
					$rowNumber++;
					$varnota++;
					
					if($varnota> ($escala-1))
					
					{$varnota=0;}	

					$ultDisc = $row['Disciplina'];		
				}
								$xnota=$varnota+1;	
								$col = 'A'; // start at column B
								while($xnota < $escala)
								{
									$sheet->setCellValue((++$col).($rowNumber+1),utf8_encode($ultDisc));
									$sheet->setCellValue((++$col).($rowNumber+1),utf8_encode($xnota));
									$sheet->setCellValue((++$col).($rowNumber+1),utf8_encode('0'));
									
									$rowNumber++;
									$col = 'A';
									$xnota++;
									$varnota++;	
								}				
				
					$dataVal[]=$rowNumber+1;
					$countDisc = (count($dataVal)-1);
					$it1=0;
					$styleVar1=32;
				while ($it1< $countDisc)
					{
						$sheet->getStyle('B'.$styleVar1.':B'.($styleVar1+($escala-2)))->applyFromArray(
							array('borders' => array(
										'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
										'left'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
										'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
										'top'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
										'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
									),
									
								'alignment' => array(
											'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
											'vertical'  => 	PHPExcel_Style_Alignment::VERTICAL_CENTER						)						
								)
							);
						$it1++;
						$styleVar1=$styleVar1+($escala-1);			
					}
					
					$it2=0;
					$styleVar2=32;
					
					while ($it2< $countDisc)
					{
						$sheet->getStyle('C'.$styleVar2.':C'.($styleVar2+($escala-2)))->applyFromArray(
						array('borders' => array(
									'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
									'left'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
									'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
									'top'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
									'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
								),
								
							'alignment' => array(
										'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
										'vertical'  => 	PHPExcel_Style_Alignment::VERTICAL_CENTER						)						
							)
						);
						
						$it2++;
						$styleVar2=$styleVar2+($escala-1);
					}
					
					$it3=0;
					$styleVar3=32;
					
					while ($it3< $countDisc)
					{
					
							$sheet->getStyle('D'.$styleVar3.':D'.($styleVar3+($escala-2)))->applyFromArray(
							array('borders' => array(
										'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
										'left'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
										'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
										'top'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
										'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
									),
									
								'alignment' => array(
											'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
											'vertical'  => 	PHPExcel_Style_Alignment::VERTICAL_CENTER						)						
								)
							);
							
							$it3++;
						$styleVar3=$styleVar3+20;
					}
					
					
			$col++;
			$rowNumber++;
			
			
			//mergecells
			$countDisc = (count($dataVal)-1);
			$mergerow=32;
			while ($countDisc > 0)
			{				
				$sheet->mergeCells('B'.$mergerow.':B'.($mergerow+($escala-2)));
				$mergerow=$mergerow+($escala-1);				
				$countDisc--;
			}					
			//criar gráfico
			
			
			$objWorksheet = $sheet;								
				//	Set the Labels for each data
						$iter=0;					
											
						$dataseriesLabels = array();
						while($rnid>=0)
						{						
							if($iter>9)
							{
								$dataseriesLabels[]=new PHPExcel_Chart_DataSeriesValues('String', 'NrOcorrenciasPorDisciplina!$Z$10'.$iter, NULL, 10);//Disciplina
							}
							else {$dataseriesLabels[]=new PHPExcel_Chart_DataSeriesValues('String', 'NrOcorrenciasPorDisciplina!$Z$100'.$iter, NULL, 10);}//Disciplina
							$rnid--;
							$iter++;
						}					
				
				
			
			$xAxisTickValues = array(
				new PHPExcel_Chart_DataSeriesValues('String', 'NrOcorrenciasPorDisciplina!$C$32:C$51', NULL, 21)	
			);				
			
				//valores
				$dataSeriesValues = array();
				$auxdataVal=0;			
				$countVal = count($dataVal);				
				
				while($auxdataVal<$countVal-1)
				{					
					$dataSeriesValues[] = new PHPExcel_Chart_DataSeriesValues('Number', 'NrOcorrenciasPorDisciplina!$D$'.(32+($auxdataVal*($escala-1))).':$D$'.(32+($auxdataVal*($escala-1))+($escala-2)), NULL, 4);					
					$auxdataVal++;	
				}
			
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
			$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);

			$title = new PHPExcel_Chart_Title('Numero de Ocorrências por Disciplina do Ano Lectivo '.$AnoLectivo.'/'.($AnoLectivo+1).', '.$Ano.'º Ano, '.$Periodo.'º Período');
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
			$chart->setTopLeftPosition('B2');
			$chart->setBottomRightPosition('P29');
			
			//	Add the chart to the worksheet
			$objWorksheet->addChart($chart);		
			}
	
		if(isset($cb16))
			{	$it++;
				
				$resultDistNivDisciplinaPercent = sqlsrv_query($conn,$queryDistDisciplinaPercent);													
					$sheet=$objPHPExcel->getActiveSheet();
					if($it > 1)
					{
						$sheet=$objPHPExcel->createSheet();							
							$index=$objPHPExcel->getIndex($sheet);
							$sheet = $objPHPExcel->getSheet($index);				
					}
					else{$sheet = $objPHPExcel->getActiveSheet();}						
					
					// $sheet->getColumnDimension('D')->setWidth(17);			
					
				//formatação de cabeçalhos
				
				
				$sheet->setTitle("DistNivDiscPercent");		
				
				$colNota = 'B';//inicio das notas
							
				
				while ($row = sqlsrv_fetch_array($resultDistNivDisciplinaPercent,SQLSRV_FETCH_ASSOC)) {
				
					$notas16 [$row ['nota']] = intval($row ['nota']);
											  
					$DistDiscPercent [$row['id_d']] = $row['Disciplina'];
													
					$Disciplina16 [$row['id_d']][intval($row['nota'])] = $row ['percentagem'];				
				
				}	
				
				
				$numDisc = count($DistDiscPercent);
				$rowTurma = (($numDisc * 33)-25);
				$rowNota = ($rowTurma-1);
				
				ksort ($notas16);										
													
				$notas16 = range (min ($notas16), max($notas16));	
				$rowNotaAux = 1;
				
					foreach ($notas16 as $kn=>$vn)											
					{
					
						$sheet->setCellValue((++$colNota).($rowNota),utf8_encode($vn));//escreve as notas na tabela
						
						$sheet->setCellValue('ZZ'.($rowNotaAux),'Nível '.utf8_encode($vn));//escreve numa linha afastada para legenda
						$rowNotaAux++;	
																
					}									
						
					 foreach ($DistDiscPercent as $kd=>$vd)									
					{	
						$colOcorrencia = 'C';//inicio das percentagens (igual a notas)
						 $sheet->setCellValue('B'.($rowTurma),utf8_encode($vd));//preencher turmas	
						
												
						 foreach ($notas16 as $kn=>$vn)
						 {						
							 if($Disciplina16[$kd][$vn]=="")
							 {																	
								$Disciplina16[$kd][$vn] = "0";														
							 }
								
								 $sheet->setCellValue(($colOcorrencia++).($rowTurma),(utf8_encode($Disciplina16[$kd][$vn])/100));	
									
						 }															
						$rowTurma++;
						
					}
					$sheet->getColumnDimension('B')->setWidth(26);
					$sheet->setCellValue('B'.$rowNota,'Disciplina');	
					
				
				$sheet->getStyle('B'.$rowNota)->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							'fill' => array(								
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => '428bca')		
							)
						)
					);
					
					$sheet->getStyle('C'.$rowNota.':'.$colNota.$rowNota)->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							
						)
					);
					
					$sheet->getStyle('B'.$rowTurma.':B'.($rowTurma-1))->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							
						)
					);
					$numDisc = count($DistDiscPercent);
				$rowDisciplina = (($numDisc * 33)-25);
						$objPHPExcel->getActiveSheet()->getStyle('C'.$rowDisciplina.':'.$colNota.$rowTurma)->getNumberFormat()->applyFromArray( 
						array( 
							'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE
						)
					);
					
					
					$rowGraphStart = 2;//inicio do graficos
					$rowGraphEnd = 29;//fim do grafico
					
					
				//coordenadas das variaveis de data, label e ticks	
				$numDisc = count($DistDiscPercent);
				$rowDisciplina = (($numDisc * 33)-25);
				$rowNota = ($rowDisciplina-1);
				$rowNotaAux = 1;
				$NumNotas = count($notas16);
				
				
					
					foreach ($DistDiscPercent as $kd=>$vd)	
							{
								//Gráfico Pie Chart para uma disciplina
								$objWorksheet = $sheet;	
									$dataseriesLabels2 = array(
										new PHPExcel_Chart_DataSeriesValues('String', 'DistNivDiscPercent!$B$'.$rowDisciplina, NULL, 1),	// Labels das notas
									);
									$xAxisTickValues2 = array(
										new PHPExcel_Chart_DataSeriesValues('String', 'DistNivDiscPercent!$ZZ$'.$rowNotaAux.':$ZZ$'.($rowNotaAux+$NumNotas), NULL, 4),	//	Valores das percentagens
									);
									$dataSeriesValues2 = array(
										new PHPExcel_Chart_DataSeriesValues('Number', 'DistNivDiscPercent!$C$'.$rowDisciplina.':$'.$colNota.'$'.$rowDisciplina.'', NULL, 4), //
									);
									$series2 = new PHPExcel_Chart_DataSeries(
										PHPExcel_Chart_DataSeries::TYPE_PIECHART,		// plotType
										null,											// plotGrouping
										range(0, count($dataSeriesValues2)-1),			// plotOrder
										$dataseriesLabels2,								// plotLabel
										$xAxisTickValues2,								// plotCategory
										$dataSeriesValues2								// plotValues
									);
									$layout2 = new PHPExcel_Chart_Layout();
									$layout2->setShowVal(TRUE);
									//$layout2->setShowCatName(TRUE);
									$plotarea2 = new PHPExcel_Chart_PlotArea($layout2, array($series2));
									$title2 = new PHPExcel_Chart_Title('Distribuição de Niveis de '.utf8_encode($vd));//nome consoante a disciplina
									$legend1 = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
									$chart2 = new PHPExcel_Chart(
										'chart2',		// name
										$title2,		// title
										$legend1,		// legend
										$plotarea2,		// plotArea
										true,			// plotVisibleOnly
										0,				// displayBlanksAs
										NULL,			// xAxisLabel
										NULL			// yAxisLabel		- Like Pie charts, Donut charts don't have a Y-Axis
									);
									//	Set the position where the chart should appear in the worksheet
									$chart2->setTopLeftPosition('B'.$rowGraphStart);
								
									$chart2->setBottomRightPosition('P'.$rowGraphEnd);
									//	Add the chart to the worksheet
									$objWorksheet->addChart($chart2);
									
									$rowGraphStart = ($rowGraphEnd+2);//incremento de area para proximo gráfico
									$rowGraphEnd = ($rowGraphStart+27)	;
									
									$rowDisciplina++;
									
									
								}	
						$numDisc = count($DistDiscPercent);
						$rowTurma = (($numDisc * 33)-25);
					$sheet->mergeCells('C'.($rowTurma-2).':'.($colNota).($rowTurma-2));
					$sheet->setCellValue('C'.($rowTurma-2),'Nota');
					$sheet->getStyle('C'.($rowTurma-2).':'.$colNota.($rowTurma-2))->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							'fill' => array(								
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => '428bca')		
							)
						)
					);
			
			}

		if(isset($cb17))
			{	
				$it++;				
				$resultDistTurmaPercent = sqlsrv_query($conn,$queryDistTurmaPercent);													
					$sheet=$objPHPExcel->getActiveSheet();
					if($it > 1)
					{
						$sheet=$objPHPExcel->createSheet();							
							$index=$objPHPExcel->getIndex($sheet);
							$sheet = $objPHPExcel->getSheet($index);				
					}
					else{$sheet = $objPHPExcel->getActiveSheet();}						
					
					// $sheet->getColumnDimension('D')->setWidth(17);			
					
				//formatação de cabeçalhos
				
				
				$sheet->setTitle("DistNivTurmaPercent");		
				
				$colNota = 'B';//inicio das notas							
				
				while ($row = sqlsrv_fetch_array($resultDistTurmaPercent,SQLSRV_FETCH_ASSOC)) {
				
					$notas17 [$row ['nota']] = intval($row ['nota']);
											  
					$DistTurmaPercent [$row['id_t']] = $row['turma'];
													
					$Turmas17 [$row['id_t']][intval($row['nota'])] = $row ['percentagem'];			
				
				}	
				
				
				$numTurma = count($DistTurmaPercent);
				$rowTurma = (($numTurma * 29)+5);
				$rowNota = ($rowTurma-1);
				
				ksort ($notas17);	
				$notas17 = range (min ($notas17), max($notas17));
				$rowNotaAux = 1;
				
					foreach ($notas17 as $kn=>$vn)											
					{
					
						$sheet->setCellValue((++$colNota).($rowNota),utf8_encode($vn));//escreve as notas na tabela
						
						$sheet->setCellValue('ZZ'.($rowNotaAux),'Nível '.utf8_encode($vn));//escreve numa linha afastada para legenda
						$rowNotaAux++;	
																
					}									
						
					 foreach ($DistTurmaPercent as $kd=>$vd)									
					{	
						$colOcorrencia = 'C';//inicio das percentagens (igual a notas)
						 $sheet->setCellValue('B'.($rowTurma),utf8_encode($vd));//preencher turmas	
						
												
						 foreach ($notas17 as $kn=>$vn)
						 {						
							 if($Turmas17[$kd][$vn]=="")
							 {																	
								$Turmas17[$kd][$vn] = "0";														
							 }
								
								 $sheet->setCellValue(($colOcorrencia++).($rowTurma),(utf8_encode($Turmas17[$kd][$vn])/100));	
									
						 }															
						$rowTurma++;
						
					}
					$sheet->getColumnDimension('B')->setWidth(26);
					$sheet->setCellValue('B'.$rowNota,'Turma');	
					
				
				$sheet->getStyle('B'.$rowNota)->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							'fill' => array(								
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => '428bca')		
							)
						)
					);
					
					$sheet->getStyle('C'.$rowNota.':'.$colNota.$rowNota)->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							
						)
					);
					
					$sheet->getStyle('B'.$rowTurma.':B'.($rowTurma-1))->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							
						)
					);
					$numDisc = count($DistTurmaPercent);
				$rowDisciplina = (($numDisc * 29)+5);
						$objPHPExcel->getActiveSheet()->getStyle('C'.$rowDisciplina.':'.$colNota.$rowTurma)->getNumberFormat()->applyFromArray( 
						array( 
							'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE
						)
					);
					
					
					$rowGraphStart = 2;//inicio do graficos
					$rowGraphEnd = 29;//fim do grafico
					
					
				//coordenadas das variaveis de data, label e ticks	
				$numDisc = count($DistTurmaPercent);
				$rowDisciplina = (($numDisc * 29)+5);
				$rowNota = ($rowDisciplina-1);
				$rowNotaAux = 1;
				$NumNotas = count($notas17);
				
				
					
					foreach ($DistTurmaPercent as $kd=>$vd)	
							{
								//Gráfico Pie Chart para uma disciplina
								$objWorksheet = $sheet;	
									$dataseriesLabels2 = array(
										new PHPExcel_Chart_DataSeriesValues('String', 'DistNivTurmaPercent!$B$'.$rowDisciplina, NULL, 1),	// Labels das notas
									);
									$xAxisTickValues2 = array(
										new PHPExcel_Chart_DataSeriesValues('String', 'DistNivTurmaPercent!$ZZ$'.$rowNotaAux.':$ZZ$'.($rowNotaAux+$NumNotas), NULL, 4),	//	Valores das percentagens
									);
									$dataSeriesValues2 = array(
										new PHPExcel_Chart_DataSeriesValues('Number', 'DistNivTurmaPercent!$C$'.$rowDisciplina.':$'.$colNota.'$'.$rowDisciplina.'', NULL, 4), //
									);
									$series2 = new PHPExcel_Chart_DataSeries(
										PHPExcel_Chart_DataSeries::TYPE_PIECHART,		// plotType
										null,											// plotGrouping
										range(0, count($dataSeriesValues2)-1),			// plotOrder
										$dataseriesLabels2,								// plotLabel
										$xAxisTickValues2,								// plotCategory
										$dataSeriesValues2								// plotValues
									);
									$layout2 = new PHPExcel_Chart_Layout();
									$layout2->setShowVal(TRUE);
									//$layout2->setShowCatName(TRUE);
									$plotarea2 = new PHPExcel_Chart_PlotArea($layout2, array($series2));
									$title2 = new PHPExcel_Chart_Title('Distribuição de Niveis da Turma '.utf8_encode($vd));//nome consoante a disciplina
									$legend1 = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
									$chart2 = new PHPExcel_Chart(
										'chart2',		// name
										$title2,		// title
										$legend1,		// legend
										$plotarea2,		// plotArea
										true,			// plotVisibleOnly
										0,				// displayBlanksAs
										NULL,			// xAxisLabel
										NULL			// yAxisLabel		- Like Pie charts, Donut charts don't have a Y-Axis
									);
									//	Set the position where the chart should appear in the worksheet
									$chart2->setTopLeftPosition('B'.$rowGraphStart);
								
									$chart2->setBottomRightPosition('P'.$rowGraphEnd);
									//	Add the chart to the worksheet
									$objWorksheet->addChart($chart2);
									
									$rowGraphStart = ($rowGraphEnd+2);//incremento de area para proximo gráfico
									$rowGraphEnd = ($rowGraphStart+27)	;
									
									$rowDisciplina++;
									
									
								}	
						$numDisc = count($DistTurmaPercent);
						$rowTurma = (($numDisc * 29)+5);
					$sheet->mergeCells('C'.($rowTurma-2).':'.($colNota).($rowTurma-2));
					$sheet->setCellValue('C'.($rowTurma-2),'Nota');
					$sheet->getStyle('C'.($rowTurma-2).':'.$colNota.($rowTurma-2))->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							'fill' => array(								
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => '428bca')		
							)
						)
					);
			
			}	
			//Média por turma e ano
			if(isset($cb18))
			{
				$it++;
				$resultMedTurmaAno = sqlsrv_query($conn,$queryMedTurmaAno);	

						
					$sheet=$objPHPExcel->getActiveSheet();
					if($it > 1)
					{
							$sheet=$objPHPExcel->createSheet();							
							$index=$objPHPExcel->getIndex($sheet);
							$sheet = $objPHPExcel->getSheet($index);				
					}
					else{$sheet = $objPHPExcel->getActiveSheet();}						
					$sheet->getColumnDimension('B')->setWidth(10);	  
					$sheet->getColumnDimension('C')->setWidth(10);
					$sheet->getColumnDimension('D')->setWidth(10);		
					
					$sheet->setCellValue('C31','Ano');
					$sheet->setCellValue('B32','Turma');										
					
					$rowNumber = 32; //start in cell 33					
					$sheet->setTitle("MediaTurmaAno");				
					
					while ($row = sqlsrv_fetch_array($resultMedTurmaAno,SQLSRV_FETCH_ASSOC)) 
					{			   
						$turmasmedturmaano [$row['id_t']] = $row ['turmas'];					
						$datasetturmasano[$row['Ano']][$row['id_t']]= utf8_encode($row['media']);				
						$labelAno[$row['Ano']]= utf8_encode($row['Ano']);							
					}
					
						$rowTurma = 33;					
						$colAno = 'C';				
					foreach ($datasetturmasano as $ano=>$idturma)
					{						
						$sheet->setCellValue($colAno.'32',utf8_encode($ano));						
						foreach ($idturma as $idt=>$med)						
						{						
							$sheet->setCellValue('B'.$rowTurma,utf8_encode($turmasmedturmaano[$idt]));
							if($med == "")
							{								
								$med = "-";
							}
							
							$sheet->setCellValue($colAno.$rowTurma++,utf8_encode($med));					
						}
						$colAno++;					
					}				
					
					//grafico
								
					$objWorksheet = $sheet;								
				//	Set the Labels for each data		
		
				$collabel = 'C';
				$itAno = 0;
				$countAno = count($labelAno);
				$dataseriesLabels = array();
				
					while ($itAno <= $countAno)
					{		
							$dataseriesLabels[] =new PHPExcel_Chart_DataSeriesValues('String', 'MediaTurmaAno!$'.$collabel.'$32', NULL, 1);//Ano
							$itAno++;
							$collabel++;							
					}			
			
			$xAxisTickValues = array(
			new PHPExcel_Chart_DataSeriesValues('String', 'MediaTurmaAno!$B$33:$B$'.($rowTurma-1), NULL, 4),	//	Q1 to Q4
				);
				
				$rowTurmaData = ($rowTurma-1);
				
				$colNotaData = 'C';
				$dataSeriesValues = array();				
				
				while($colNotaData<$colAno)
				{													
					$dataSeriesValues[] = new PHPExcel_Chart_DataSeriesValues('Number','MediaTurmaAno!$'.$colNotaData.'$33:$'.$colNotaData.'$'.$rowTurmaData, NULL, 4);
					$colNotaData++;
				}					
							//	Build the dataseries
				$series = new PHPExcel_Chart_DataSeries(
				PHPExcel_Chart_DataSeries::TYPE_BARCHART,		// plotType
				PHPExcel_Chart_DataSeries::GROUPING_STANDARD,	// plotGrouping
				range(0, count($dataSeriesValues)-1),			// plotOrder
				$dataseriesLabels,								// plotLabel
				$xAxisTickValues,								// plotCategory
				$dataSeriesValues								// plotValues
			);
			//	Set additional dataseries parameters
			//		Make it a vertical column rather than a horizontal bar graph
			$series->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);

			//	Set the series in the plot area
			$plotarea = new PHPExcel_Chart_PlotArea(NULL, array($series));
			//	Set the chart legend
			$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);

			$title = new PHPExcel_Chart_Title('Media por Ano e Turma do Ano Lectivo '.$AnoLectivo.'/'.($AnoLectivo+1).', '.$cursoNome.$tituloPeriodo);
			
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
			$chart->setTopLeftPosition('B2');
			$chart->setBottomRightPosition('P29');

			//	Add the chart to the worksheet
			$objWorksheet->addChart($chart);	
			//formatar headers de ano
			$sheet->getStyle('C31:'.$colAno.'31')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							'fill' => array(								
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => '428bca')		
							)
						)
					);	
					
					//formatar headers de turma
			$sheet->getStyle('B32')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							'fill' => array(								
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => '428bca')		
							)
						)
					);	
							
			}
							
			
			
			
			if(isset($cb19))
			{
				$it++;							
				$resultPDAAluno_Turma = sqlsrv_query($conn,$queryPDAAluno_Turma);
				
				$sheet=$objPHPExcel->getActiveSheet();
				if($it > 1)
					{
						$sheet=$objPHPExcel->createSheet();							
						$index=$objPHPExcel->getIndex($sheet);
						$sheet = $objPHPExcel->getSheet($index);				
					}
				else{$sheet = $objPHPExcel->getActiveSheet();}

				while ($row = sqlsrv_fetch_array($resultPDAAluno_Turma,SQLSRV_FETCH_ASSOC))
				{
					$DisciplinaDesignacao [$row['id_d']] = $row['abv'];
			
					$disciplinasPDA [$row['id_d']] = array($row['Sigla'], $row['AulasPrevistas'], $row['AulasDadas']);
					
					$dadosAluno [$row['id_a']] = array($row['NumeroAluno'], $row['NomeAluno'], $row['BI'] );	

					$AlunosFaltas [$row['id_a']] [$row['id_d']] = $row['Faltas'];		
				}
				
				$numDisciplinas = count($disciplinasPDA);
				$numDisciplinas = 2*$numDisciplinas;
				$col='E';
				$itDisc=1;
				
				while($itDisc<$numDisciplinas)
				{
					$col++;
					$itDisc++;				
				}
				
				$colCab=$col;
				
				$sheet->setTitle("AulasAssistidasPorAluno");//titulo				
				
				$sheet->mergeCells('E3:'.$col.'3');//merge para escrever disciplinas
				$sheet->mergeCells('E7:'.$col.'7');//merge para escrever assistidas				
				
				$sheet->mergeCells('C1:X1');
				
				$sheet->getStyle('C1:X1')->applyFromArray(
					array(						
							'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
							)
						)
					);
					
				$sheet->setCellValue('C1','Aulas Assistidas por Aluno do Ano Letivo '.$AnoLectivo.'/'.($AnoLectivo+1).','.$cursoNome.', '.$Ano.'º Ano, '.$Periodo.'º Periodo, Turma'.$turmaNome);
				
				$sheet->setCellValue('E3','Disciplinas');
				$sheet->setCellValue('E7','Assistidas');
				$sheet->setCellValue('B7','#');
				$sheet->getStyle('B7')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							'fill' => array(
								
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => '428bca')		
							),
							'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
							)
						)
					);
				$sheet->setCellValue('C7','Nome');
				$sheet->getStyle('C7')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							'fill' => array(
								
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => '428bca')		
							),
							'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
							)
						)
					);
					
				$sheet->setCellValue('D7','BI');
				
				$sheet->getStyle('D7')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							'fill' => array(
								
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => '428bca')		
							),
							'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
							)
						)
					);
				
				$itDisc=0;
				$col1='E';
				$col2='F';
				
				foreach ($disciplinasPDA as $kidd=>$valor)	//merge de cells para escrever a disciplina
				{				
					$sheet->mergeCells($col1.'4:'.$col2.'4');
					$sheet->SetCellValue($col1.'5', 'P');//escreve o P
					$sheet->SetCellValue($col2.'5', 'D');//escreve o D

					$sheet->getStyle($col1.'5')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
							),							
							'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
							)
						)
					);
					
					$sheet->getStyle($col2.'5')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
							),							
							'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
							)
						)
					);

					$sheet->getStyle($col1.'6')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
							),							
							'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
							)
						)
					);	
					
					$sheet->getStyle($col2.'6')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
							),							
							'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
							)
						)
					);	
					
					$sheet->SetCellValue($col1.'6', $valor[1]);//escreve o valor de P
					$sheet->SetCellValue($col2.'6', $valor[2]);//escreve o valor de D			
					
					$col1++;
					$col1++;
					$col2++;
					$col2++;				
				}				
				
				$col='E';//coluna inicial para escrever disciplinas
				
				foreach ($disciplinasPDA as $kidd=>$vSigla)
				{												
					$sheet->SetCellValue($col.'4', $vSigla [0]);
					
					$colStyle=$col;				
					$colStyle=++$colStyle;
										
					//formatação de disciplinas
					$sheet->getStyle($col.'4:'.$colStyle.'4')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
							),							
							'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
							)
						)
					);
					
					$col++;					
					$col++;					
				}				
				
				$row=8;
				
				foreach ($dadosAluno as $idAluno=>$dados)
				{
					$col='B';
					$sheet->SetCellValue($col++.$row, $dados[0]);//numero do aluno						
					$sheet->SetCellValue($col++.$row, utf8_encode($dados[1]));//nome
					
					//formatação nome do aluno
					$sheet->getStyle($col.$row)->applyFromArray(
					array(						
							'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
							)
						)
					);			
					
					$sheet->SetCellValue($col++.$row, $dados[2]);//BI
					
					//formatação BI
					$sheet->getStyle($col.$row)->applyFromArray(
					array(						
							'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
							)
						)
					);
					
						foreach ($disciplinasPDA as $keyidd=>$dadosDisciplina)
						{	
							$col1=$col;
							if(isset($AlunosFaltas[$idAluno][$keyidd]))
							{$sheet->SetCellValue($col++.$row, $dadosDisciplina[2]-$AlunosFaltas[$idAluno][$keyidd]);}//aulas assistidas
							else {$sheet->SetCellValue($col++.$row, 'Não Inscrito');}
							$col2=$col;
							$col++;
							$sheet->mergeCells($col1.$row.':'.$col2.$row);
							
							$sheet->getStyle($col1.$row.':'.$col2.$row)->applyFromArray(
									array(						
											'alignment' => array(
													'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
											)
										)
									);							
								
						}
						
					$row++;								
				}
				//formatação
				$sheet->getColumnDimension('B')->setWidth(3);
				$sheet->getColumnDimension('C')->setWidth(20);
				$sheet->getColumnDimension('D')->setWidth(15);	
				
				//formatação de cabeçalhos
				$sheet->getStyle('E3:'.$colCab.'3')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							'fill' => array(
								
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => '428bca')		
							),
							'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
							)
						)
					);				
					
					$sheet->getStyle('E7:'.$colCab.'7')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							'fill' => array(
								
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => '428bca')		
							),
							'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
							)
						)
					);			
			}
			
			if(isset($cb20))
			{
				$it++;							
				$resultPDADisciplina_Turma = sqlsrv_query($conn,$queryPDADisciplina_Turma);
				
				$sheet=$objPHPExcel->getActiveSheet();
				if($it > 1)
				{
					$sheet=$objPHPExcel->createSheet();							
					$index=$objPHPExcel->getIndex($sheet);
					$sheet = $objPHPExcel->getSheet($index);				
				}
				else{$sheet = $objPHPExcel->getActiveSheet();}
				$sheet->mergeCells('B1:R1');
				
				$sheet->getStyle('B1:R1')->applyFromArray(
					array(						
							'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
							)
						)
					);
				
				$sheet->setCellValue('B1','Aulas Dadas Por Disciplina do Ano Letivo do Ano Letivo '.$AnoLectivo.'/'.($AnoLectivo+1).','.$cursoNome.', '.$Ano.'º Ano'.$tituloPeriodo);
				$sheet->setTitle("AulasDadasPorDisciplina");//titulo
				
				while ($row = sqlsrv_fetch_array($resultPDADisciplina_Turma,SQLSRV_FETCH_ASSOC))
				{
					$DisciplinasTurmas [$row['id_d']] [$row['Turma']]  = array ($row['AulasDadas'],$row['AulasPrevistas']);
					
					$Disciplinas [$row['id_d']] = $row['Sigla'];		
				}
				
				$var=0;
				$col='B';
				$headers = array("Disciplina","Turma","Horas Previstas", "Horas Dadas", "% de Aulas Dadas");		
				
				//preencher headers e formatar
				while( $var < 5 )
				{
					$sheet->setCellValue($col.'4', $headers[$var]);
					//formatação de headers
					$sheet->getStyle($col.'4')->applyFromArray(
					array('borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
								'middle'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
							),
							'fill' => array(
								
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => '428bca')		
							),
							'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
							)
						)
					);						
					
					$col++;
					$var++;
				}
				
				//redimensionar widths dos headers
				foreach(range('B','F') as $columnID) 
				{
					$sheet->getColumnDimension($columnID)->setAutoSize(true);
				}				
				
				$row=5;
				$rowdados=5;
				//preencher tabela com valores
				foreach ($DisciplinasTurmas as $kIDD=>$DT)
				{
					$sheet->setCellValue('B'.$row, $Disciplinas[$kIDD]);
					$sheet->mergeCells('B'.$row.':B'.($row+count($DT)-1));//merge para escrever disciplinas
					
					
					
					foreach ($DT as $kT=>$Val)
					{	
						$col='C';//coluna de inicio de preenchimento de tabela
						
						$sheet->setCellValue($col++.$rowdados,utf8_encode($kT));//turma
						$sheet->setCellValue($col++.$rowdados, $Val[0]);//aulas previstas
						$sheet->setCellValue($col++.$rowdados, $Val[1]);//aulas dadas
												
						//conta para percentagem
						$AulasDadas=intval($Val[1]);
						$AulasPrevistas=intval($Val[0]);
						$conta = ((($AulasDadas)*100)/($AulasPrevistas+0.000000000001));
						
						$sheet->setCellValue($col++.$rowdados, number_format($conta,1,'.','').'%');//percentagem de aulas dadas
						$rowdados++;						
					}
					
					$row=$row+count($DT);					
					
				}

				$sheet->getStyle('B4:F'.$row)->applyFromArray(
					array(							
							'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
									'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
							)
						)
					);			
			}
			
			// Save Excel 2007 file			
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	if($sec)	
    header('Content-Disposition: attachment;filename="Estatísticas_Secundário.xlsx"');
    
	if($ciclo23)	
    header('Content-Disposition: attachment;filename="Estatísticas_23Ciclo.xlsx"');
	
	if($ciclo1)	
    header('Content-Disposition: attachment;filename="Estatísticas_1Ciclo.xlsx"');
	
	header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->setIncludeCharts(TRUE);
    $objWriter->save('php://output');
exit;
?>