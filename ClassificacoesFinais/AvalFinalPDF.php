<?
require_once('conn.php');
require_once('FunctionsAvaliacoesFinais.php');
require_once('tcpdf/tcpdf.php');
require_once('tcpdf/config/tcpdf_config.php');

	$idProf = $_SESSION['id'];

	//variaveis depois de inserção
	$anoletivo = $_GET['anoletivo'];
	$escola = $_GET['escola'];
	$ano = $_GET['ano'];
	$disciplina = $_GET['disciplina'];
	$turma = $_GET['turma'];
	// $modulo = $_GET['modulo'];	
	$periodo = $_GET['periodo'];
	
		//logotipo
		$query="SELECT Designacao, Logotipo FROM GER_Escola";
		$result = sqlsrv_query($conn,$query);
		if($row=sqlsrv_fetch_array( $result, SQLSRV_FETCH_NUMERIC)) {
		 $nomeescola=$row[0];
		 $logotipo=$row[1];
		}
		
		file_put_contents("../../com/uploads/tmplogotipo.jpg",$logotipo);
		
		//create new PDF document
		$pdf = new TCPDF(LANDSCAPE, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('PAAE');
		$pdf->SetTitle('Classificações Finais');
		$pdf->SetSubject('');
		$pdf->SetKeywords('');	
		
		//reset no header do ficheiro
		$pdf->setHeaderTemplateAutoreset(true);
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));		
		// set default header data
		$pdf->SetHeaderData("../../../com/uploads/tmplogotipo.jpg", PDF_HEADER_LOGO_WIDTH, 'Classificações Finais', $escola );
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
			
			$pdf->AddPage('L','A4');
			
			$pdf->SetFont('dejavusans', '', 9);
			
			$pdf->Write(8, 'Classificações Finais de '.$anoletivo.'/'.($anoletivo+1).', '.$ano.'º Ano '.$periodo.'º Período, Turma '.$turma, '', 0, 'C', true, 0, false, false, 0);
			
			$pdf->SetFont('dejavusans', '', 7);	
			
			$averbamentos_array = getAverbamentos ();
			
			$QCursoTurma = "select ID_Curso from ped_turmas
			where ID_TURMA='".$turma."'";			
			$QCursoTurma=sqlsrv_query($conn,$QCursoTurma);			
			while ($rowCursoTurma=sqlsrv_fetch_array($QCursoTurma,SQLSRV_FETCH_NUMERIC))
			{ $curso=$rowCursoTurma[0];	}
			
			//calcular disciplinas que cada turma tem
			$QDisciplinasTurma = "select distinct d.ID_Disciplina, d.Sigla from PED_Matriculas_Disciplinas md 
			inner join PED_Disciplinas d on md.ID_Disciplina = d.ID_Disciplina
			where ID_Turma='".$turma."'";			
			
			$RDisciplinasTurma = sqlsrv_query($conn, $QDisciplinasTurma);			
			$Disciplina_QAval = array ();
			while ($rowDiscCrit = sqlsrv_fetch_array($RDisciplinasTurma, SQLSRV_FETCH_NUMERIC))
			{
				$criterio= getCriterioAvaliacao($curso, $rowDiscCrit[0], $ano, $periodo);
				$Disciplina_QAval [$rowDiscCrit[0]] = array ($criterio,$rowDiscCrit[1]);
			}		
			
			$QAlunosTurma="SELECT U.ID_NUtente, UTU.ID_Utente, MAL.NumeroAluno, U.NomeAbreviado, UF.Imagem, SM.Sigla as SituacaoMatricula, m.ID_Matricula, m.ID_Curso
			from PED_Turmas as T
			inner join PED_Matriculas_AnosLectivos as MAL on MAL.ID_Turma = T.ID_Turma and t.AnoLectivo=mal.AnoLectivo and mal.AnoLectivo='".$anoletivo."'
			inner join PED_Matriculas as M on M.ID_Matricula = MAL.ID_Matricula			
			inner join GER_Utentes as U on U.ID_NUtente = M.ID_NUtente
			inner join GER_Utentes_TiposUtentes as UTU on UTU.ID_NUtente = U.ID_NUtente
			inner join PED_SituacoesMatriculas as SM on SM.ID_SituacaoFinal = MAL.ID_SituacaoFinal
			inner join GER_UtentesFoto as UF on UF.ID_NUtente = U.ID_NUtente					
			where T.ID_Turma = '".$turma."'
			order by MAL.NumeroAluno";			
			
			$RAlunosTurma=sqlsrv_query($conn,$QAlunosTurma);	
			$FaltasTotal = array();
			$FaltasJustificadas = array();		
		
			while($row=sqlsrv_fetch_array($RAlunosTurma,SQLSRV_FETCH_NUMERIC))
			{
				$dadosAluno [$row[0]]= array ($row[2],$row[3],$row[5]);//dados do Aluno
				$cursoAluno = $row[7];			
				
				foreach ($Disciplina_QAval as $k=>$v)
				{
					$Nota=0;
					$Justificacao = '';
					$Averbamento = '';				
					
					$QNotasAlunos = "select ad.Nota, ID_PeriodoLectivo, Justificacao, ID_Averbamento from PED_AvaliacaoDefinitiva ad
					inner join PED_Matriculas_Disciplinas md on ad.ID_Matricula=md.ID_Matricula and ad.AnoLectivo=md.AnoLectivo
					inner join PED_Matriculas m on md.ID_Matricula=m.ID_Matricula
					inner join PED_CriteriosAvaliacao ca on ca.ID_CriterioAvaliacao=ad.ID_CriterioAvaliacao and ca.ID_Disciplina=md.ID_Disciplina					
					where m.ID_NUtente='".$row[0]."' and md.ID_Disciplina='".$k."' and md.AnoLectivo='".$anoletivo."' and md.ID_Ano='".$ano."' and md.ID_Turma='".$turma."' and ad.ID_CriterioAvaliacao='".$v[0]."'
					order by ID_PeriodoLectivo";					
					
					$RNotasAlunos=sqlsrv_query($conn,$QNotasAlunos);
					
					while($rowNota=sqlsrv_fetch_array($RNotasAlunos,SQLSRV_FETCH_NUMERIC))
					{				
						$Nota=$rowNota[0];
						if($rowNota[2]!="NULL")
						{
							$Justificacao = $rowNota[2];
						}
						
						if($rowNota[3]!="NULL")
						{
							$Averbamento = $rowNota[3];
						}	
					}

					$QTotalFaltas="select fa.ID_NUtente, COUNT (ID_Falta) from PED_FaltasAlunos fa
					inner join PED_TiposFalta tf on tf.ID_TipoFalta=fa.ID_TipoFalta
					inner join PED_Horarios_Turmas ht on ht.ID_Horario=fa.ID_Horario and ht.ID_Tempo=fa.ID_TempoLectivo
					inner join PED_PeriodosLectivos pl on pl.ID_NPeriodo=ht.ID_NPeriodo					
					where TF.ID_TipoUtente = 1				
					and fa.ID_TipoFalta=1
					and ID_TipoUtente=1
					AND HT.ID_Disciplina='".$k."'
					AND HT.ID_Turma='".$turma."'				
					AND  FA.ID_NUtente='".$row[0]."'
					and Nperiodo='".$periodo."'			
					group by fa.ID_NUtente";				
					
					$RTotalFaltas = sqlsrv_query($conn,$QTotalFaltas);				
					
					while ($rowFaltasTotal = sqlsrv_fetch_array($RTotalFaltas,SQLSRV_FETCH_NUMERIC))
					{
						$FaltasTotal = $rowFaltasTotal [1]; 
					}			
					
					$QFaltasInjustificadas = "select fa.ID_NUtente, COUNT (ID_Falta) from PED_FaltasAlunos fa
					inner join PED_TiposFalta tf on tf.ID_TipoFalta=fa.ID_TipoFalta
					inner join PED_Horarios_Turmas ht on ht.ID_Horario=fa.ID_Horario and ht.ID_Tempo=fa.ID_TempoLectivo
					inner join PED_PeriodosLectivos pl on pl.ID_NPeriodo=ht.ID_NPeriodo					
					where TF.ID_TipoUtente = 1
					and fa.isJustificada=0
					and fa.ID_TipoFalta=1
					and ID_TipoUtente=1
					AND HT.ID_Disciplina='".$k."'
					AND HT.ID_Turma='".$turma."'				
					AND  FA.ID_NUtente='".$row[0]."'			
					group by fa.ID_NUtente";				
					
					$RFaltasInjustificadas = sqlsrv_query($conn,$QFaltasInjustificadas);			
					
					while ($rowFaltasInjustificadas = sqlsrv_fetch_array($RFaltasInjustificadas,SQLSRV_FETCH_NUMERIC))
					{							
						$FaltasInjustificadas = $rowFaltasInjustificadas [1]; 
					}
					
					$AlunoNotasDisciplina [$row[0]] [$k] = array ($Nota, $FaltasTotal, $FaltasInjustificadas, $Averbamento, $Justificacao);				
				}				
			}						
			
			$html = '<table cellspacing="0" cellpadding="1" border="1" align="center">											
						<thead>
							<tr style="background-color:#428bca; font-size:10px; font-weight:Bold;">
								<th>Nome</th>
								<th>Nº</th>';
								foreach ($Disciplina_QAval as $k=>$v)
								{
									$html.='<th>'.$v[1].'</th>';
								}							
							$html.='</tr>
						</thead>		
						<tbody>';						
						foreach ($dadosAluno as $kdadosAluno=>$vdadosAluno)
						{								
							$html.='
							<tr>
								<td>'.$vdadosAluno[1].'</td>
								<td>'.$vdadosAluno[0].'</td>
								<td>'.$vdadosAluno[2].'</td>
							</tr>';								
						}
						$html.='</body>
						</table>';
							
							
			// die($html);
			// output the HTML content
			$pdf->writeHTML(utf8_encode($html), false, false, false, false, '');
			
			// reset pointer to the last page
			$pdf->lastPage();
			
			//fim
			unlink("../../com/uploads/tmplogotipo.jpg");
			//Close and output PDF document
			$pdf->Output('ClassificaçõesFinaisPDF', 'I');		
		?>