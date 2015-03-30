<?php
//estabelecer ligação ao servidor
require_once('conn.php');

// Include the main TCPDF library (search for installation path).
require_once('tcpdf\examples\tcpdf_include.php');

//valores dos filtros passado pelo url
$Escola=$_GET['escola'];
$escolaNome=$_GET['escolaNome'];
$AnoLectivo=$_GET['anoletivo'];
$curso=$_GET['curso'];
$Ano=$_GET['ano'];
$Turma = $_GET['turma'];
$Periodo=$_GET['periodo'];
$cursoNome = $_GET['cursoNome'];
$sec=$_GET['sec'];
$turmaNome = $_GET['turmaNome'];
$modular = $_GET['modular'];

	if($modular == 'false')//colocar periodo nos curso de ensino regular
	{		
		$tituloPeriodo = ", ".$Periodo."º Período";
		$tituloHorasouAulasPrevistas = "Aulas Previstas";
		$tituloHorasouAulasDadas = "Aulas Dadas";		
	}
	else
	{		
		$tituloHorasouAulasPrevistas = "Horas Previstas";
		$tituloHorasouAulasDadas = "Horas Dadas";
	}

//ficheiro com a querys
require_once('QResultPDA.php');
require_once('QResult.php');

function modalAlertPDF ()
 {
		global $conn;
		global $queryVerificaRegistos;
		global $Escola;
		global $AnoLectivo;
		global $curso;
		global $Ano;
		global $Periodo;
		global $Turma;

		$resultVerificaRegistos = sqlsrv_query($conn,$queryVerificaRegistos);
		
		$ResultTemRegistos = sqlsrv_has_rows ($resultVerificaRegistos);		
		
		if($ResultTemRegistos)
		{return false;}
		else { 
			header("Location: index.php?sec=1&escola=".$Escola."&anoletivo=".$AnoLectivo."&curso=".$curso."&ano=".$Ano."&periodo=".$Periodo."&turma=".$Turma."");
		}
		}

 echo modalAlertPDF ();
 
 //checkboxs

$cb19=$_GET['PDA'];
$cb20=$_GET['PDA2'];

//logotipo
	$query="SELECT Designacao, Logotipo FROM GER_Escola";
	$result = sqlsrv_query($conn,$query);
		if($row=sqlsrv_fetch_array( $result, SQLSRV_FETCH_NUMERIC)) {
		 $nomeescola=$row[0];
		 $logotipo=$row[1];
		}
	
  file_put_contents("../../com/uploads/tmplogotipo.jpg",$logotipo);

// create new PDF document
	$pdf = new TCPDF(LANDSCAPE, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('PAAE');
	$pdf->SetTitle('Estatísticas');
	$pdf->SetSubject('');
	$pdf->SetKeywords('');			
	
	//reset no header do ficheiro
	$pdf->setHeaderTemplateAutoreset(true);

	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	
	// set default header data
	$pdf->SetHeaderData("../../../../../com/uploads/tmplogotipo.jpg", PDF_HEADER_LOGO_WIDTH, 'Estatisticas', $escolaNome );

	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// set margins
	$pdf->SetMargins(10, 20, 10);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, 2);

	// set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	// set some language-dependent strings (optional)
	if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
		require_once(dirname(__FILE__).'/lang/eng.php');
		$pdf->setLanguageArray($l);	}
			
		// set font
		$pdf->SetFont('dejavusans', '', 8);	
			
	//Aulas Assistidas por Aluno			
	if(isset($cb19))
	{			
			$pdf->AddPage('L','A4');
			
			$pdf->SetFont('dejavusans', '', 9);
			
			$pdf->Write(8, 'Aulas Assistidas por Aluno do Ano Letivo '.$AnoLectivo.'/'.($AnoLectivo+1).', '.$cursoNome.', '.$Ano.'º Ano'.$tituloPeriodo.', Turma '.$turmaNome, '', 0, 'C', true, 0, false, false, 0);
			
			$pdf->SetFont('dejavusans', '', 7);		
		
		// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
		// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

		//query para preencher tabela			
			
			$resultPDAAluno_Turma = sqlsrv_query($conn,$queryPDAAluno_Turma);
		
 		while ($row = sqlsrv_fetch_array($resultPDAAluno_Turma,SQLSRV_FETCH_ASSOC))
		{
			$DisciplinaDesignacao [$row['id_d']] = $row['abv'];
			
			$disciplinasPDA [$row['id_d']] = array($row['Sigla'], $row['AulasPrevistas'], $row['AulasDadas']);
			
			$dadosAluno [$row['id_a']] = array($row['NumeroAluno'], $row['NomeAluno'], $row['BI'] );	

			$AlunosFaltas [$row['id_a']] [$row['id_d']] = $row['Faltas'];		
		}
		
		$numDisciplinas = count($disciplinasPDA);
		$numDisciplinas = 2*$numDisciplinas;
		
			// create some HTML content
			$html = '<table cellspacing="0" cellpadding="3" border="0" align="center">											
						<thead>
							<tr >
								<th width="20%" colspan="3" rowspan="4" ></th>												
								<th border="1" style="background-color:#428bca; font-size:10px; font-weight:Bold; width:80%;" colspan="'.$numDisciplinas.'" >Disciplinas</th>
							</tr>
							<tr>';
							
							foreach ($disciplinasPDA as $kidd=>$vSigla)
							{												
								$html.= '<th border="1" colspan="2">'.$vSigla [0].'</th>';								
							}
						$html.='
							</tr>
							<tr >';
						foreach ($disciplinasPDA as $kidd)
						{
							$html.='<th border="1">P</th>
									<th border="1">D</th>';
						}
						$html.='</tr>
								<tr >';
						foreach ($disciplinasPDA as $kidd=>$valor)	
						{
							$html.='<th border="1">'.$valor[1].'</th>
									<th border="1">'.$valor[2].'</th>';
						}
						$html.='</tr>
								<tr style="background-color:#428bca; font-size:10px; font-weight:Bold;">
								
									<th border="1" width="2%">#</th>
									<th border="1" width="10%">Nome</th>
									<th border="1" width="8%">BI</th>
									
									<th border="1" colspan="'.(2*($numDisciplinas)).'">Assistidas</th>	
								</tr>	
							</thead>
							<tbody>
							';											
						
							foreach ($dadosAluno as $idAluno=>$dados)
							{	$html.='<tr >
								<td border="1" width="2%" >'.$dados[0].'</td>
								<td border="1" width="10%" align="left">'.utf8_encode($dados[1]).'</td>
								<td border="1" width="8%">'.$dados[2].'</td>';
								
								foreach ($disciplinasPDA as $keyidd=>$dadosDisciplina)
								{
									if(isset($AlunosFaltas[$idAluno][$keyidd]))	
									{$html.='<td border="1" width="'.(80/($numDisciplinas/2)).'%" colspan="2" >'.($dadosDisciplina[2]-$AlunosFaltas[$idAluno][$keyidd]).'</td>';}																	
									else{
										$html.='<td border="1" width="'.(80/($numDisciplinas/2)).'%" colspan="2" >Não Inscrito</td>';
									}
									
								}
								$html.='</tr>';
							}
											
											$html.='</tbody>		
													</table>';				
			
			// output the HTML content
			$pdf->writeHTML($html, true, false, false, false, '');
			
			// reset pointer to the last page
			$pdf->lastPage();
		
	}

	//Aulas Dadas por Disciplina
	if(isset($cb20))
	{		
		$pdf->AddPage('P','A4');
			
			$pdf->SetFont('dejavusans', '', 9);
			
			$pdf->Write(8, 'Aulas Dadas Por Disciplina do Ano Letivo '.$AnoLectivo.'/'.($AnoLectivo+1).', '.$cursoNome.', '.$Ano.'º Ano'.$tituloPeriodo, '', 0, 'C', true, 0, false, false, 0);
			
			$pdf->SetFont('dejavusans', '', 7);		
		
		// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
		// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

		//query para preencher tabela			

			$resultPDADisciplina_Turma = sqlsrv_query($conn,$queryPDADisciplina_Turma);

			while($row = sqlsrv_fetch_array($resultPDADisciplina_Turma,SQLSRV_FETCH_ASSOC))
			{		
				$DisciplinasTurmas [$row['id_d']] [$row['Turma']]  = array ($row['AulasDadas'],$row['AulasPrevistas']);
					
				$Disciplinas [$row['id_d']] = $row['Sigla'];	
			}

			// create some HTML content
			$html = '<table cellspacing="0" cellpadding="1" border="1" align="center">											
						<thead>
							<tr style="background-color:#428bca; font-size:10px; font-weight:Bold;">
								<th>Disciplina</th>
								<th>Turma</th>
								<th>'.$tituloHorasouAulasPrevistas.'</th>
								<th>'.$tituloHorasouAulasDadas.'</th>
								<th>% de Aulas Dadas</th>
							</tr>
						</thead>		
						<tbody>';							
						
							foreach ($DisciplinasTurmas as $kIDD=>$DT)
							{	
								$rowspanDisciplina = count($DT);
								$centerDisciplina = ($rowspanDisciplina/2); 
								$html.='<tr>
											<td rowspan="'.$rowspanDisciplina.'">'; while($centerDisciplina > 0)
											{
												$html.='<br>';
												$centerDisciplina--;
											}
											$html.=''.$Disciplinas[$kIDD].'</td>';
								foreach ($DT as $kT=>$Val)
								{																									
									$html.='<td>'.utf8_encode($kT).'</td>
											<td>'.$Val[0].'</td>
											<td>'.$Val[1].'</td>';
									$AulasDadas=intval($Val[1]);
									$AulasPrevistas=intval($Val[0]);
									$conta = ((($AulasDadas)*100)/($AulasPrevistas+0.000000000001));
									$html.='<td>'.number_format($conta,1,'.','').'%</td>
									</tr>
									<tr>
									
									';
								}
								$html.='</tr>';	
							}										
									$html.='</tbody>		
											</table>';
			
			// output the HTML content
			$pdf->writeHTML($html, true, false, false, false, '');
			
			// reset pointer to the last page
			$pdf->lastPage();
		}
			unlink("../../com/uploads/tmplogotipo.jpg");
			//Close and output PDF document
$pdf->Output('Estatisticas_PDF', 'I');
