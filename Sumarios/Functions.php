<?	
	require_once ('Querys.php');	

	//tabela de sumarios
	function TableSumarios ($idProf,$AnoLetivo,$Escola,$Ano=false,$Disciplina=false,$Turma=false)
	{	
		global $conn;
		
		//função com a query
		$QConsultarSumarios = QueryConsultaSumarios ($idProf,$Disciplina,$Turma);	
		$QAlunosFaltas = QueryAlunosFaltas ($idProf,$Disciplina,$Turma);	
		
		$resultQConsultarSumarios = sqlsrv_query($conn,$QConsultarSumarios);
		$resultQConsultarFaltas = sqlsrv_query($conn,$QAlunosFaltas);		
		
		$res = "	
        <div class='col-lg-12' style='padding-top:1%;' >
                    <div class='panel panel-primary'>
                        <div class='panel-heading'>
                            Sumários de ".$AnoLetivo."/".($AnoLetivo+1)."
							
						   <div style='display:inline; float:right;'>
								<input type='text' id='max' name='max' placeholder='Data máxima' class='form-control' style='height:25px; width:75%; display:inline;'>					
							</div>
							<div style='display:inline; float:right;'>
								<input type='text' id='min' name='min' placeholder='Data miníma' class='form-control' style='display:inline; height:25px; width:75%;'>
							</div>							
                        </div>
                        
                        <div class='panel-body'>
                            <div class='table-responsive' style='overflow-x: hidden !important; padding-left:1%;'>
                                <table class='table table-striped table-bordered table-hover' id='ConsultarSumarios' style='width:99%;'>
                                    <thead>
                                        <tr>
                                            <th>Nº da Aula</th>
                                            <th>Data</th>
											<th>Hora</th>                                            
                                            <th>Descrição</th>
											<th title='Faltas de Presença'>Faltas</th>											
                                        </tr>
                                    </thead>
                                    <tbody>";
		
		
		while ($row = sqlsrv_fetch_array($resultQConsultarSumarios,SQLSRV_FETCH_ASSOC))
		{			
			$sumarios [$row['id_act']] = array($row['NumActa'],$row['Data'], $row['Hora'], $row['Descricao']);
		}

		while ($row = sqlsrv_fetch_array($resultQConsultarFaltas,SQLSRV_FETCH_ASSOC))
		{
			$faltas [$row['id_act']] [$row['TipoFalta']] [] = $row['NumeroAluno'];
			$descricaoFaltas [$row['TipoFalta']]= $row['abv'];
		}	
		
			if(is_array($sumarios))
			{
				foreach ($sumarios as $idact=>$sumval)
				{
					$res.="<tr>
					<td align='center'>".$sumval[0]."</td>
					<td align='center'>".$sumval[1]."</td>
					<td align='center'>".$sumval[2]."</td>			
					<td>".html_entity_decode(utf8_decode(str_replace(array("\n","\r"),"",$sumval[3])))."</td>";
					$res.="<td>";
					if(is_array($faltas[$idact]))
						foreach ($faltas[$idact] as $k_tipofalta=>$numAluno)
						{
							$res.=$descricaoFaltas[$k_tipofalta].": ".(implode(", ",$numAluno))."<br/>";
						}
					$res.="</td></tr>";
				}				
			}		
		
		$res.="</tbody></table></div></div></div><div style='min-height:37px;'>
						&nbsp;&nbsp;
					</div>
					</div>";
		
		$res.="
		<script>
		$(function()
					{		
						$( '#min' ).datepicker({dateFormat: 'yy-mm-dd',onSelect:function(){table.draw();}});
						$( '#max' ).datepicker({dateFormat: 'yy-mm-dd', onSelect:function(){table.draw();}});									
					});
							$.fn.dataTable.ext.search.push(
								function( settings, data, dataIndex ) {
								var min = $('#min').val();
								var max = $('#max').val();
								var datas = data[1]; // use data for the age column
								
								
									if ( (min && max && datas<=max && datas>=min)  || ( !max  && min<=datas) || (!min && max>=datas) || (!min && !max) )
									{									
										return true;
									}
									
									return false;
								});
		
			var table; 
			$(function() {
			table = $('#ConsultarSumarios').DataTable({
																	  	
			'order': [[ 0, 'desc' ]],				
				 
			'language': {
							'sSearch': '<i class=\"fa fa-search\"></i>  ',		
								searchPlaceholder: 'Procurar... ',
								'sZeroRecords': 'Sem Resultados. É necessário preencher os filtros',												 
							},
							columnDefs: [ { orderable: false, 'targets': [-1,-2] } ],								
							 'bPaginate': false,
							'bInfo' : false							
			});
			 
			// Event listener to the two range filtering inputs to redraw on input
				$('#min, #max').keyup( function() {
					table.draw();
				});
			} );
			</script>";
		
		return $res;
	}	
	function getAnoLetivoProf ($idProf)
	{		
		global $conn;
		
		$QGetAnosLetProf="select AnoLectivo from PED_Professores_AnoLectivo
		where ID_NUtente= ".$idProf."
		order by AnoLectivo DESC";
		
		$resultAnoLetivoProf = sqlsrv_query($conn, $QGetAnosLetProf);		

		while($row=sqlsrv_fetch_array($resultAnoLetivoProf,SQLSRV_FETCH_ASSOC))
		{			
			$filtros[]=array($row['AnoLectivo'],utf8_encode($row['AnoLectivo']."/".($row['AnoLectivo']+1)));			
		}				
		return json_encode($filtros);
	}
	
	function getEscolaProf ($idProf,$AnoLetivo)
	{		
		global $conn;
		
		$QGetEscolaProf = "select distinct t.ID_escola idEscola, e.Designacao escolaNome from ped_turmas as T 
		inner join PED_Turmas_Disciplinas_Professores as TDP on TDP.ID_Turma = T.ID_Turma 
		inner join GER_Escola as E on E.ID_Escola = T.ID_Escola
		where AnoLectivo = '".$AnoLetivo."' and tdp.ID_NUtente ='".$idProf."'";
		
		$resultEscolaProf = sqlsrv_query($conn, $QGetEscolaProf);
		
		while($row=sqlsrv_fetch_array($resultEscolaProf,SQLSRV_FETCH_ASSOC))
		{		
			$filtros [] = array ($row['idEscola'], utf8_encode($row['escolaNome']));
		}		
		return json_encode($filtros);		
	}
	
	function getAnoProf ($idProf, $AnoLetivo,$Escola)
	{
		global $conn;
		
		$QGetAnosProf = "SELECT DISTINCT T.ID_Ano Ano
		FROM	PED_TURMAS_DISCIPLINAS_PROFESSORES TDP
		INNER JOIN PED_TURMAS T ON T.ID_TURMA = TDP.ID_TURMA 
		WHERE	TDP.ID_NUTENTE = '".$idProf."' AND T.ANOLECTIVO = '".$AnoLetivo."' AND T.ID_Escola = '".$Escola."'";
		
		$resultAnoProf = sqlsrv_query($conn, $QGetAnosProf);		

		while($row=sqlsrv_fetch_array($resultAnoProf,SQLSRV_FETCH_ASSOC))
		{		
			$filtros [] = array ($row['Ano'], $row['Ano'] );
		}		
		return json_encode($filtros);		
	}	
	
	function getDisciplinasProf ($idProf, $AnoLetivo,$Escola,$Ano)
	{
		global $conn;
		
		$QGetDisciplinasProf="select distinct d.ID_Disciplina id_d, d.Sigla sigla from PED_Turmas_Disciplinas_Professores tdp
		inner join PED_Disciplinas d on tdp.ID_Disciplina=d.ID_Disciplina
		inner join PED_Turmas t on t.ID_Turma=tdp.ID_Turma
		WHERE TDP.ID_NUTENTE = '".$idProf."' AND T.ANOLECTIVO = '".$AnoLetivo."' AND T.ID_Escola = '".$Escola."' AND T.ID_Ano = '".$Ano."'";
		
		$resultDisciplinasProf = sqlsrv_query($conn, $QGetDisciplinasProf);	

		while($row=sqlsrv_fetch_array($resultDisciplinasProf,SQLSRV_FETCH_ASSOC))
		{		
			$filtros [] = array ($row['id_d'], utf8_encode($row['sigla']));
		}	
		return json_encode($filtros);		
	}	
	
	function getTurmasProf ($idProf, $AnoLetivo,$Escola,$Ano,$Disciplina)		
	{
		global $conn;	
		
		$QGetTurmasProf = "SELECT DISTINCT TDP.ID_TURMA id_t,T.DESIGNACAO turma
		FROM PED_TURMAS_DISCIPLINAS_PROFESSORES TDP
		INNER JOIN PED_TURMAS T ON T.ID_TURMA = TDP.ID_TURMA 
		WHERE TDP.ID_NUTENTE = '".$idProf."' AND T.ANOLECTIVO = '".$AnoLetivo."' AND T.ID_Escola = '".$Escola."' AND T.ID_Ano = '".$Ano."' and tdp.ID_Disciplina='".$Disciplina."'";
		
		$resultTurmasProf = sqlsrv_query($conn, $QGetTurmasProf);		

		while($row=sqlsrv_fetch_array($resultTurmasProf,SQLSRV_FETCH_ASSOC))
		{		
			$filtros [] = array ($row['id_t'], utf8_encode($row['turma']));
		}
		
		return json_encode($filtros);		
	}
	
	//esta ao contrario para efeitos de teste!!!! trocar a condição do IF!!!!
	function getPresetFiltros ($idProf)
	{
		global $conn, $QFiltrosPreset;			
		
		$resultFiltrosPreset = sqlsrv_query($conn, $QFiltrosPreset);		
		
		$FiltrosPresetRegistos = sqlsrv_has_rows($resultFiltrosPreset);
		
		if($FiltrosPresetRegistos)
		{			
			while ($row=sqlsrv_fetch_array($resultFiltrosPreset,SQLSRV_FETCH_ASSOC))
			{
				
				$filtros [0] = array ($row['AnoLectivo'],utf8_encode($row['AnoLectivo']."/".($row['AnoLectivo']+1)));
				$filtros [1] = array ($row['idEscola'], utf8_encode($row['escolaNome']));
				$filtros [2] = array ($row['Ano'], $row['Ano']);
				$filtros [3] = array ($row['id_d'], utf8_encode($row['sigla']));
				$filtros [4] = array ($row['id_t'], utf8_encode($row['turma']));
				$filtros [5] = array ($row ['tempoLetivo']);
				$filtros [6] = array ($row ['id_h']);
			}			
			return json_encode($filtros);
		}
		else
		{
			return false;
		}	
	}
	
	function filtrosConsultaSumarios ($idProf,$AnoLetivoPreset=false,$EscolaPreset=false,$AnoPreset=false,$DisciplinaPreset=false,$TurmaPreset=false)	
	{
		global $conn;		
		
			$AnosLetivosjson = getAnoLetivoProf ($idProf);		
			$AnoLetivoArray = json_decode($AnosLetivosjson);
			
			$Escolajson = getEscolaProf ($idProf,($AnoLetivoPreset?$AnoLetivoPreset:$AnoLetivoArray[0][0]));
			$EscolaArray = json_decode($Escolajson);
		
			$Anojson = getAnoProf ($idProf,$AnoLetivoPreset,$EscolaPreset);	
			
			$Disciplinajson = getDisciplinasProf ($idProf,$AnoLetivoPreset,$EscolaPreset,$AnoPreset);		

			$Turmajson = getTurmasProf ($idProf,$AnoLetivoPreset,$EscolaPreset,$AnoPreset,$DisciplinaPreset);		
		
		return
		"	
		<script>	
		
			$(function()
			{
				$('#anoletivo').change();												
			});
			
			function Resultados ()
			{
				$.get('FiltrosConsultaSumarios.php',{act:'resultado',anoletivo: $('#anoletivo').val(), escola:$('#escola').val(), ano:$('#ano').val(), disciplina:$('#disciplina').val(), turma:$('#turma').val()}).done(function(data)
					{
						$('#div_tablesumarios').html(data);
					});							
			}
			function preenche(dados,anoletivo,escola,ano,disciplina,turma)
				{
					$.get('FiltrosConsultaSumarios.php',{act:dados,anoletivo:anoletivo, escola:escola, ano:ano, disciplina:disciplina, turma:turma}).done(function(data) 
					{	
						var obj = JSON.parse(data);	
						
						if(obj)
						{						
							var valor = $('#'+dados).val();												
													
							$('#'+dados).empty().append($('<option />'));									
							
							for(var i=0; i< obj.length; i++)
							$('#'+dados).append($('<option />').val(obj[i][0]).text(obj[i][1]));					
							
							$('#'+dados).val(valor);
							$('#'+dados).change();						
						}						
					})
				}
		</script>
		
		<div class='form-group' style='display:inline; padding-left:1%; '>			
			<select id='anoletivo' class='form-control' name='anoletivo' style='height:35px; width:15%; display:inline; border-radius:1px;' onchange=\"preenche('escola',this.value)\" ><option></option>".JSONtoOption($AnosLetivosjson,0,1,($AnoLetivoPreset?$AnoLetivoPreset:$AnoLetivoArray[0][0]))."</select>			
			<select id='escola' class='form-control' name='escola' style='height:35px; width:35%; display:inline; border-radius:1px;' onchange=\"preenche('ano',$('#anoletivo').val(),this.value)\"><option></option>".JSONtoOption($Escolajson ,0,1,($EscolaPreset?$EscolaPreset:$EscolaArray[0][0]))."</select>
			<select id='ano' class='form-control' name='ano' style='height:35px; width:15%; display:inline; border-radius:1px;' onchange=\"preenche('disciplina',$('#anoletivo').val(),$('#escola').val(),this.value)\" ><option></option>".JSONtoOption($Anojson ,0,1,$AnoPreset)."</select>
			<select id='disciplina' name='disciplina' class='form-control' style='height:35px; width:15%; display:inline; border-radius:1px;' onchange=\"preenche('turma',$('#anoletivo').val(),$('#escola').val(),$('#ano').val(),this.value)\"><option></option>".JSONtoOption($Disciplinajson,0,1,$DisciplinaPreset)."</select>
			<select id='turma' class='form-control' name='turma' style='height:35px; width:15%; display:inline; border-radius:1px;' onchange=\"Resultados()\"><option></option>".JSONtoOption($Turmajson,0,1,$TurmaPreset)."</select>				
		</div>				
		";
	}
	
	function InsertSumario ($idProf,$Disciplina,$Turma, $TempoLetivo=false, $idHorario=false, $idData=false,$id_submit=false)
	{		
		global $conn;		
		$data = date('Y-m-d'); 
		//sumario submetido ou acedido pela vista de horario
		
			//aula atual quando o id e dado
			$QSumAulaAtual = "select act.ID_Acta, act.Descricao, LEFT(CONVERT(Varchar, Data, 120),10) Data,LEFT(CONVERT(Time, HoraInicial, 120),5) as Hora, NumActa, t.Designacao  from PED_Actas act
			inner join PED_Sumarios s on s.ID_Acta=act.ID_Acta
			inner join PED_Horarios_Turmas ht on ht.ID_Horario=s.ID_Horario
			inner join PED_TemposLectivos tl on tl.ID_Tempo=ht.ID_Tempo
			inner join PED_Turmas t on t.ID_Turma=ht.ID_Turma
			where ".($id_submit?"act.ID_Acta=".$id_submit:" tl.ID_Tempo=".$TempoLetivo." and ht.ID_Horario=".$idHorario." and CONVERT(Varchar(10), Data, 20)='".($idData?$idData:$data)."'");		
			
			
			$resultQSumAulaAtual = sqlsrv_query($conn,$QSumAulaAtual)or die(print_r(sqlsrv_errors($resultQSumAulaAtual)));
			
			// die($QSumAulaAtual);
			$clickHorarioComSumFilled = sqlsrv_has_rows($resultQSumAulaAtual);
			
			if(!$clickHorarioComSumFilled)
			{				
				//aula anterior quando o ID é dado.
				$QSumAulaAnterior = QSumAulaAnterior ($idProf,$Disciplina,$Turma, $NumAulaAtual);//preencher sumario da aula anterior
				$resultQSumAulaAnterior = sqlsrv_query($conn,$QSumAulaAnterior);				
				// die($QSumAulaAnterior);
				
				while ($row = sqlsrv_fetch_array($resultQSumAulaAnterior,SQLSRV_FETCH_ASSOC))
				{			
					$descAulaAnterior = $row['Descricao'];
					$NumAulaAnt = $row['NumActa'];				
				}				
				
				$QdescobrirTurmaHoraInicio="select distinct t.Designacao, LEFT(CONVERT(Time, HoraInicial, 120),5) as Hora from ped_turmas t
				inner join PED_Horarios_Turmas ht on t.ID_Turma=ht.ID_Turma
				inner join PED_TemposLectivos tl on ht.ID_Tempo=tl.ID_Tempo
				where t.id_turma='".$Turma."' and tl.ID_Tempo='".$TempoLetivo."'";
				
				$ResultTurmaDesignacaoHora = sqlsrv_query($conn,$QdescobrirTurmaHoraInicio);	
				while ($row = sqlsrv_fetch_array($ResultTurmaDesignacaoHora,SQLSRV_FETCH_ASSOC))
				{			
					$TurmaDesignacao = $row ['Designacao'];	
					$DataRegisto = ($idData?$idData:$data)." | ".$row['Hora'];					
				}					
					if($id_submit || $idData) 
					{
						$NaoTemSumario = true;
					}
					else
					{
						$NaoTemSumarioEmAula = true;
					}
					$NumAulaAtual = ($NumAulaAnt+1); 				
								
			}
			else
			{				
				while ($row = sqlsrv_fetch_array($resultQSumAulaAtual,SQLSRV_FETCH_ASSOC))
				{			
					$descAulaAtual = $row['Descricao'];
					$DataRegisto = $row['Data']." | ".$row['Hora'];
					$NumAulaAtual = $row ['NumActa'];
					$TurmaDesignacao = $row ['Designacao'];
					$ID_Acta=$row['ID_Acta'];
				}
				//aula anterior quando o ID é dado.
				$QSumAulaAnterior = QSumAulaAnterior ($idProf,$Disciplina,$Turma, $NumAulaAtual);//preencher sumario da aula anterior
				$resultQSumAulaAnterior = sqlsrv_query($conn,$QSumAulaAnterior);								
				
				while ($row = sqlsrv_fetch_array($resultQSumAulaAnterior,SQLSRV_FETCH_ASSOC))
				{			
					$descAulaAnterior = $row['Descricao'];
					$NumAulaAnt = $row['NumActa'];
				}									
					
				$NumAulaAtual = ($NumAulaAnt+1);

				$NaoTemSumario = false;				
				$NaoTemSumarioEmAula = false;
				
			}	
		
		$res="
			<form method='post' id='form1' role='form' action='FiltrosConsultaSumarios.php' name='InsertSumDB'>
			<input type='hidden' name='act' value='InsertSumDB'>
			<input type='hidden' name='idProf' id='idProf' value=".$idProf.">
			<input type='hidden' name='TempoLetivo' id='TempoLetivo' value=".$TempoLetivo.">
			<input type='hidden' name='idHorario' id=='idHorario' value=".$idHorario.">
			<input type='hidden' name='Data' id='Data' value=".($idData?$idData:$data).">			
			<input type='hidden' name='NumAula' id='NumAula' value=".$NumAulaAtual.">
			<input type='hidden' name='NaoTemSumario' id='NaoTemSumario' value=".$NaoTemSumario.">
			<input type='hidden' name='id_submit' id='id_submit' value=".$id_submit.">
			<input type='hidden' name='Turma' id='Turma' value=".$Turma.">
			<input type='hidden' name='ID_Acta' id='ID_Acta' value=".$ID_Acta.">
			<input type='hidden' name='NaoTemSumarioEmAula' id='NaoTemSumarioEmAula' value=".$NaoTemSumarioEmAula.">
			<input type='hidden' name='Disciplina' id='Disciplina' value=".$Disciplina.">
			<input type='hidden' name='emAula' id='emAula' value=".$emAula.">
								
					<div  id='div_descSum' class='col-lg-12 col-xs-12 col-sm-12 col-md-12' style='display:inline; width:70%; -webkit-transition: all 0.5s ease; -moz-transition: all 0.5s ease; -o-transition: all 0.5s ease; transition: all 0.5s ease;'>
						<div class='panel panel-primary'>
							<div class='panel-heading'>
								Descrição de Sumário
								<div id='EditSumario' style='cursor:pointer; float:right; ".(($id_submit || ($descAulaAtual && $idData) )?"":"Display:none;")."'>
									<i title='Editar' class='glyphicon glyphicon-pencil'></i>
								</div>
							</div>
							<div class='panel-body'>
								<div class='form-group' style='margin-top:5px; display:inline;'>					
									<div>
										<div>
											<div style='display:inline;'>
												<button type='button' class='btn btn-primary btn-circle btn-xs'><i class='glyphicon glyphicon-edit'></i></button> Sumário nº ".$NumAulaAtual."								
											</div>
											<div style='display:inline;'>
												<button type='button' class='btn btn-primary btn-circle btn-xs'><i class='glyphicon glyphicon-star'></i></button> Turma ".$TurmaDesignacao."						
											</div>
											<div style='display:inline;'>
												<button type='button' class='btn btn-primary btn-circle btn-xs'><i class='glyphicon glyphicon-calendar'></i></button> Data: <span id='data'>".$DataRegisto."</span>							
											</div>																												
										</div>
										<div style='padding-top:50px;'>
											<div class='form-control' disabled id='txtSumAulaAtual' placeholder='Descrição...' style='width: 90%; height:175px; resize: none;".(($id_submit || $descAulaAtual)?"":"Display:none;")."'>".html_entity_decode(utf8_decode(str_replace(array("\n","\r"),"",$descAulaAtual)))."</div>
											<textarea id='DescSum' name='DescSum' class='textarea form-control' placeholder='Descrição...' style='width: 90%; height:175px; ".(($id_submit || $descAulaAtual)?"Display:none;":"")."'>".html_entity_decode(utf8_decode(str_replace(array("\n","\r"),"",$descAulaAtual)))."</textarea>											
										</div>
									</div>
									<div style='float:right; padding-top:5px;'>
										<button type='submit' id='SubmitButton' class='btn btn-default ".(($id_submit || $descAulaAtual)?"disabled":"")."'>Submeter</button>			
									</div>							
								</div>					
							</div>
						</div>					
					</div>
					
							
					<div id='div_SumAulAnt' class='col-lg-12 col-xs-12 col-sm-12 col-md-12' style='display:inline; width:27%; padding-right:2%; -webkit-transition: all 0.5s ease; -moz-transition: all 0.5s ease; -o-transition: all 0.5s ease; transition: all 0.5s ease;'>
						<div class='panel panel-default'>					
							<div class='panel-heading' style='cursor: pointer;' id='head_AulaAnterior'>
								Sumário da aula anterior <span class='glyphicon glyphicon-triangle-right' style='float:right;'></span>
							</div>
						<div class='panel-body'>
							<div class='form-group' style='margin-top:5px;'>
								<div style='float:left;'>
									<button type='button' class='btn btn-primary btn-circle btn-xs'><i class='glyphicon glyphicon-edit'></i></button> Sumário nº ".$NumAulaAnt."								
								</div>
								<div style='padding-top:90px;' >
									<div class='form-control' disabled id='txtSumAulaAnt' placeholder='Descrição...' style='width: 90%; height:175px; resize: none;'>".html_entity_decode(utf8_encode(str_replace(array("\n","\r"),"",$descAulaAnterior)))."</div>
								</div>						
							</div>					
						</div>
						</div>	
					</div>
					</div>
					
					<div style='float:right; display:none;' id='buttAulAnt'>
						<button class='btn btn-default' type='button' style='height:35px;'>
							<i class='glyphicon glyphicon-triangle-left'></i>
						</button>
					</div>
					</div>					
					</form>	
					
					<script>					
						var NaoTemSumarioEmAula = ".json_encode($NaoTemSumarioEmAula).";
						
						$( '#head_AulaAnterior' ).click(function()
						{		
							$('#div_SumAulAnt').animate( {'width':'0%'}, 'slow', function() { $('#div_SumAulAnt').hide(); } );					
							$( '#div_descSum' ).animate( {'width':'97%'}, 'slow', function () { $('#buttAulAnt').show(); } );					
						});
						
						$('#buttAulAnt').click(function()
						{		
							$( '#div_descSum' ).animate( {'width':'65%'}, 'slow', function() { $( '#div_SumAulAnt' ).animate( {'width':'33%'}, 'slow', function() { $('#buttAulAnt').hide(); $('#div_SumAulAnt').show('fast', function() {} ); }); });				
						});	
			
						function wysi()
						{							
							$('#DescSum').wysihtml5({
							toolbar: {
								'font-styles': false, //Font styling, e.g. h1, h2, etc. Default true
								'emphasis': true, //Italics, bold, etc. Default true
								'lists': true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
								'html': false, //Button which allows you to edit the generated HTML. Default false
								'link': false, //Button to insert a link. Default true
								'image': false, //Button to insert an image. Default true,
								'color': false, //Button to change color of font 
								'fa': true
							},
							locale: 'pt-BR'			
							});
						}							
							$( '#EditSumario' ).click(function() {
								$( '#DescSum' ).show();
								$( '#txtSumAulaAtual' ).hide();
								$( '#SubmitButton' ).removeClass('disabled');
								$( '#EditSumario' ).hide();						
								wysi();
							});	
						
						$(function()
						{
							if(NaoTemSumarioEmAula && jaexiste!=1)
							{
								wysi();
							}
						});
						
					if( Edit || id_submit || InsMarcacaoFaltas)
					{
						$('#Faltas_Nav').removeClass('disabled');
					}
					
					var clickHorarioComSumFilled = ".json_encode($clickHorarioComSumFilled).";
					
					if( clickHorarioComSumFilled)
					{						
						$('#Faltas_Nav').removeClass('disabled');
					}				
					</script>					
					";		
		return $res;
	}

	function InsertCalendar ($idProf,$AnoLetivo,$Turma=false, $idData=false) 
	{
		global $conn;
		
		$horas = getHorasHorarioCalendar($idProf,$AnoLetivo,true,($Turma?$Turma:false));			
		
		$mdcarray=array();
		foreach($horas as $k=>$v)
		{
			if(!in_array($k,$mdcarray)) $mdcarray[]=$k;
			if(!in_array($v,$mdcarray)) $mdcarray[]=$v;
		}		
		sort($mdcarray);
		
		$first_key = reset($mdcarray);//primeira posicao		
		$lastPost=end($mdcarray);//ultima posicao		
		
		$interval = ($lastPost - $first_key);
		
		$heightCalendar = ($interval*1.58);			
		
		$mdc = 15;
		
		$res="
		<script>
		
		var DataInsert = ".json_encode($idData).";		
		
		$(function() {
				$('#calendarSum').fullCalendar({					
					header: {
						left: '',
						center: 'title',
						right: 'prev,next today'
					},
					defaultView: 'agendaWeek',					
					editable: false,
					eventLimit: true,
					minTime: '".format_minutes(min($mdcarray))."',
					maxTime: '".format_minutes(max($mdcarray))."',
					allDaySlot: false,
					lang: 'pt',
					slotDuration: '".format_minutes($mdc)."',
					showSlotTimes: true,
					slotTimes: [".implode(",",$mdcarray)."],
					axisFormat: 'HH:mm',
					hideTime: true,					
					weekends: true,
					hiddenDays: [0],
					slotEventOverlap: true,
					events: {
						url: 'FiltrosConsultaSumarios.php',
						data: function(){return {act:'sumarios',turma: ($('#turma').val()==undefined?'".$Turma."':$('#turma').val())}}
					}					
				});
				
				if(DataInsert)
				{
					$('#calendarSum').fullCalendar('gotoDate', DataInsert);
				}
				else
				{
					$('#calendarSum').fullCalendar('today');
				}
				
				$('#calendarSum').fullCalendar('option', 'contentHeight', ".$heightCalendar.");
		});
							
		</script>	
		";
		
		return $res;
	}
	
	function getHorasHorarioCalendar($id, $AnoLetivo, $apenasaulas=false,$turma=false)
	{
		global $conn;
		
		$query="
		(SELECT DISTINCT TL.HoraInicial HI, TL.HoraFinal
		FROM PED_Professores as P
		inner join PED_Horarios_Turmas_Professores as HTP on HTP.ID_NUtente = P.ID_NUtente
		inner join PED_Horarios_Turmas as HT on HT.ID_Horario = HTP.ID_Horario
		inner join PED_DiasSemana as DS on DS.ID_Dia = HT.ID_Dia
		inner join PED_PeriodosLectivos as PL on PL.ID_NPeriodo = HT.ID_NPeriodo
		inner join PED_TemposLectivos as TL on TL.ID_Tempo = HT.ID_Tempo
		inner join PED_Turmas as T on T.ID_Turma = HT.ID_Turma
		inner join PED_Disciplinas as D on D.ID_Disciplina = HT.ID_Disciplina
		inner join GER_Salas as S on S.ID_Sala = HT.ID_Sala
		WHERE T.AnoLectivo = '".$AnoLetivo."' ".($turma?"":"and P.ID_NUtente = '".$id."'")."
		) UNION (SELECT DISTINCT TL.HoraInicial HI, TL.HoraFinal
		FROM PED_Professores as P
		inner join PED_Horarios_AulasReforco_Professores as HTP on HTP.ID_NUtente = P.ID_NUtente
		inner join PED_Horarios_AulasReforco as HT on HT.ID_Horario = HTP.ID_Horario
		inner join PED_DiasSemana as DS on DS.ID_Dia = HT.ID_Dia
		inner join PED_PeriodosLectivos as PL on PL.ID_NPeriodo = HT.ID_NPeriodo
		inner join PED_TemposLectivos as TL on TL.ID_Tempo = HT.ID_Tempo
		inner join PED_Turmas as T on T.ID_Turma = HT.ID_Turma
		inner join PED_Disciplinas as D on D.ID_Disciplina = HT.ID_Disciplina
		inner join GER_Salas as S on S.ID_Sala = HT.ID_Sala
		WHERE T.AnoLectivo = '".$AnoLetivo."'".($turma?"":" and P.ID_NUtente = '".$id."'").")
		".($apenasaulas?"":"
		--ATIVIDADES
		UNION (
		SELECT DISTINCT HA.HoraInicio HI, HA.HoraFim
		FROM PED_Professores as P
		inner join PED_Horarios_Actividades_Utentes as HAU on HAU.ID_NUtente = P.ID_NUtente
		inner join PED_Horarios_Actividades as HA on HA.ID_Horario = HAU.ID_Horario
		inner join PED_DiasSemana as DS on DS.ID_Dia = HA.ID_Dia
		inner join PED_TurmasAEC as TAEC on TAEC.ID_Turma= HA.ID_Turma
		WHERE P.ID_NUtente = '".$id."' and TAEC.AnoLectivo = '".$AnoLetivo."')

		--APOIO
		UNION (
		SELECT DISTINCT HA.HoraInicio HI, HA.HoraFim
		FROM PED_Professores as P
		inner join PED_Horarios_Actividades_Utentes as HAU on HAU.ID_NUtente = P.ID_NUtente
		inner join PED_Horarios_Actividades as HA on HA.ID_Horario = HAU.ID_Horario
		inner join PED_DiasSemana as DS on DS.ID_Dia = HA.ID_Dia
		WHERE P.ID_NUtente = '".$id."' AND HA.ID_Turma IS NULL)
	")."
		ORDER BY HI";		
		
		$result = sqlsrv_query($conn,$query) or debug(__LINE__.$query.print_r(sqlsrv_errors(),1));
		$res=array();
		while($row=sqlsrv_fetch( $result)) {
			$x1=sqlsrv_get_field( $result, 0, SQLSRV_PHPTYPE_STRING("UTF-8"));
			$x2=sqlsrv_get_field( $result, 1, SQLSRV_PHPTYPE_STRING("UTF-8"));
			$v1=(strtotime("1970-01-01".substr($x1,10))/60)+60;
			$v2=(strtotime("1970-01-01".substr($x2,10))/60)+60;
			$res[$v1]=$v2;
		}		
		return $res;
	}
	
	function InsertSumDB ($NumAulaAtual, $Descricao, $Data, $idProf, $TempoLetivo, $idHorario)
	{		
		global $conn;		
		
		$QInsert1 = "
		
		declare @data as date set @data=N'$Data'
		INSERT INTO PED_Actas ( NumActa, Descricao, IsSumario, Data, DataRegisto, ID_NUtenteRegisto, Is1Ciclo)  VALUES ( ".$NumAulaAtual.", '".$Descricao."', 1, @data, GETDATE() , ".$idProf.", NULL); SELECT SCOPE_IDENTITY();";
		$result1 = sqlsrv_query($conn,$QInsert1)or die($QInsert1.print_r(sqlsrv_errors()));		
	
		sqlsrv_next_result($result1);
		sqlsrv_fetch($result1);
		$field = sqlsrv_get_field( $result1, 0);		
				
		$QInsert2 = "INSERT into PED_Sumarios (ID_Acta, ID_Horario, ID_TipoHorario, NumActa2, DescricaoSumario, ID_TempoLectivo) values ( ".$field.",".$idHorario.", 1, ".$NumAulaAtual.", NULL, ".$TempoLetivo.")";			
		$result2 = sqlsrv_query($conn,$QInsert2)or print_r(sqlsrv_errors());
		
		return $field;
	}
	
	function UpdateSumDB ($id_submit, $Descricao, $idProf)
	{
		global $conn;
		
		$QUpdateSumario = "update PED_Actas 
		SET Descricao='".$Descricao."', DataRegisto=GETDATE(), ID_NUtenteRegisto=".$idProf."
		WHERE ID_Acta='".$id_submit."';";
		
		$R_UpdateSumario = sqlsrv_query($conn,$QUpdateSumario)or die(sqlsrv_errors());	
		
	}
	
	//para visualizar faltas
	function getFaltas($Disciplina,$Turma,$idHorario,$TempoLetivo,$Data,$idProf,$InsMarcacaoFaltas=false)
	{
		global $conn;
		
		if($conn)
		{
			$query="SELECT U.ID_NUtente, UTU.ID_Utente, MAL.NumeroAluno, U.Nome, UF.Imagem, SM.Sigla as SituacaoMatricula, m.ID_Matricula, m.ID_Curso
			from PED_Turmas as T
			inner join PED_Matriculas_AnosLectivos as MAL on MAL.ID_Turma = T.ID_Turma and t.AnoLectivo=mal.AnoLectivo and mal.AnoLectivo='".$AnoLetivo."'
			inner join PED_Matriculas as M on M.ID_Matricula = MAL.ID_Matricula
			inner join PED_Matriculas_Disciplinas md on md.ID_Matricula=m.ID_Matricula and ID_Disciplina='".$Disciplina."' and md.AnoLectivo=MAl.AnoLectivo and t.ID_Turma=md.ID_Turma		
			inner join GER_Utentes as U on U.ID_NUtente = M.ID_NUtente
			inner join GER_Utentes_TiposUtentes as UTU on UTU.ID_NUtente = U.ID_NUtente
			inner join PED_SituacoesMatriculas as SM on SM.ID_SituacaoFinal = M.ID_SituacaoFinal
			inner join GER_UtentesFoto as UF on UF.ID_NUtente = U.ID_NUtente
			where T.ID_Turma = '".$Turma."' and md.ID_Disciplina='".$Disciplina."'
			order by MAL.NumeroAluno";
				
			$result=sqlsrv_query($conn,$query);
			while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_NUMERIC))
			{
				$ids_faltas= array("","","","","","");
				$faltas=array("","false","false","false","false","false");
				$query="select FA.ID_TipoFalta, FA.ID_Falta
						from PED_FaltasAlunos as FA
						INNER JOIN PED_TiposFalta as TF on TF.ID_TipoFalta = FA.ID_TipoFalta
						INNER JOIN PED_Horarios_Turmas as HT on HT.ID_Horario=FA.ID_Horario
						INNER JOIN PED_Horarios_Turmas_Professores HTP ON HTP.ID_Horario=HT.ID_Horario
						INNER JOIN PED_TemposLectivos TL ON TL.ID_Tempo=FA.ID_TempoLectivo
						where TF.ID_TipoUtente = 1 AND -- so faltas dos alunos
						HT.ID_Disciplina='".$Disciplina."' AND
						HT.ID_Turma='".$Turma."' AND
						HT.ID_Horario = '".$idHorario."' AND
						TL.ID_Tempo = '".$TempoLetivo."' AND
						CONVERT(VARCHAR(10), FA.Data, 20) = '".$Data."' AND
						HTP.ID_NUtente='".$idProf."' AND --prof
						FA.ID_NUtente='".$row[0]."' --aluno";		
				
				$res=sqlsrv_query($conn,$query);
				$faltas = array();
				$id_faltas = array();
				while($rw=sqlsrv_fetch_array($res,SQLSRV_FETCH_NUMERIC))
				{
					$faltas[$rw[0]]="true";
					$ids_faltas[$rw[0]]=$rw[1];				
				}
				
				if($InsMarcacaoFaltas)
				{
					$ids_faltas[1]=0;
					$faltas[1]=0;
					$ids_faltas[2]=0;
					$faltas[2]=0;
					$ids_faltas[3]=0;
					$faltas[3]=0;
					$ids_faltas[4]=0;
					$faltas[4]=0;
					$ids_faltas[5]=0;
					$faltas[5]=0;				
				}

				$QTotalFaltas="select fa.ID_TipoFalta, COUNT (ID_Falta) from PED_FaltasAlunos fa
				inner join PED_TiposFalta tf on tf.ID_TipoFalta=fa.ID_TipoFalta
				inner join PED_Horarios_Turmas ht on ht.ID_Horario=fa.ID_Horario and ht.ID_Tempo=fa.ID_TempoLectivo								
				where TF.ID_TipoUtente = 1
				AND HT.ID_Disciplina='".$Disciplina."'
				AND HT.ID_Turma='".$Turma."'				
				AND  FA.ID_NUtente='".$row[0]."'
				and fa.ID_NUtenteRegisto = '".$idProf."'
				group by fa.ID_TipoFalta";				
				
				$RTotalFaltas = sqlsrv_query($conn,$QTotalFaltas);			
				$FaltasTotal = array();
				while ($rowFaltas = sqlsrv_fetch_array($RTotalFaltas,SQLSRV_FETCH_NUMERIC))
				{
					$FaltasTotal [ $rowFaltas [0] ] = $rowFaltas [1]; 
				}
				
				$QFaltasJustificadas = "select fa.ID_NUtente, COUNT (ID_Falta) from PED_FaltasAlunos fa
				inner join PED_TiposFalta tf on tf.ID_TipoFalta=fa.ID_TipoFalta
				inner join PED_Horarios_Turmas ht on ht.ID_Horario=fa.ID_Horario and ht.ID_Tempo=fa.ID_TempoLectivo								
				where TF.ID_TipoUtente = 1
				and fa.isJustificada=1
				and fa.ID_TipoFalta=1
				AND HT.ID_Disciplina='".$Disciplina."'
				AND HT.ID_Turma='".$Turma."'				
				AND  FA.ID_NUtente='".$row[0]."'
				and fa.ID_NUtenteRegisto = '".$idProf."'
				group by fa.ID_NUtente";				
				
				$RFaltasJustificadas = sqlsrv_query($conn,$QFaltasJustificadas);			
				$FaltasJustificadas = array();
				while ($rowFaltasJustificadas = sqlsrv_fetch_array($RFaltasJustificadas,SQLSRV_FETCH_NUMERIC))
				{
					$FaltasJustificadas [ $rowFaltasJustificadas [0] ] = $rowFaltasJustificadas [1]; 
				}
				
				$dados[$row[0]]=array($row[0],$row[1],$row[2],base64_encode($row[4]),utf8_encode($row[3]),$ids_faltas[1],$faltas[1],$ids_faltas[2],$faltas[2],$ids_faltas[3],$faltas[3],$ids_faltas[4],$faltas[4],$ids_faltas[5],$faltas[5],$row[5],$FaltasTotal[1],$FaltasTotal[2],$FaltasTotal[3],$FaltasTotal[4],$FaltasTotal[5], $FaltasJustificadas[$row[0]]);				
			}			
			if($dados) return $dados;
		}
		else
		{
			echo "Não existe ligação.<br/>";
		}
	}

	function htmlFaltasSumarios ($Disciplina,$Turma,$idHorario,$TempoLetivo,$Data,$idProf,$InsMarcacaoFaltas=false, $Edit=false,$emAula=false)
	{		
		global $conn;		
		
		$faltas = getFaltas($Disciplina,$Turma,$idHorario,$TempoLetivo,$Data,$idProf,$InsMarcacaoFaltas);
		
		$QequivalenciaFaltas="select * from PED_FaltasEquivalencia_TiposFaltas fetf
		inner join PED_FaltasEquivalencia fe on fe.ID_Equivalencia=fetf.ID_Equivalencia
		where ID_TipoFalta=1 and ID_TipoUtente=1 and fe.ID_Equivalencia=-1";
		
		$RequivalenciaFaltas = sqlsrv_query($conn,$RequivalenciaFaltas);
		
		$RequivalenciaFaltasTemEqui = sqlsrv_has_rows($RequivalenciaFaltas);
		//comentar depois, só para testes
		// $RequivalenciaFaltasTemEqui = true;
		
		$res="
		<form method='post' id='InsertFaltas' role='form' action='FiltrosConsultaSumarios.php' name='InsertFaltas'>
			<input type='hidden' name='act' value='InsertFaltas'>
			<input type='hidden' name='idHorario' id='idHorario' value=".$idHorario.">
			<input type='hidden' name='idProf' id='idProf' value=".$idProf.">
			<input type='hidden' name='TempoLetivo' id='TempoLetivo' value=".$TempoLetivo.">
			<input type='hidden' name='Data' id='Data' value=".$Data.">
			<input type='hidden' name='Turma' id='Turma' value=".$Turma.">
			<input type='hidden' name='Edit' id='Edit' value=".$Edit.">
			<input type='hidden' name='emAula' id='emAula' value=".$emAula.">						
                <div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
                    <div class='panel panel-primary'>
                        <div class='panel-heading'>
                            Marcação de Faltas							
                        </div>						
                        <div class='panel-body'>
							<div style='display:inline; padding-left:1%;'>							
							<div style='padding-left:70%; display:inline;'>
								<button type='submit' id='SubmitButton' class='btn btn-primary' style='border-radius:1px;".($InsMarcacaoFaltas || $Edit || $emAula ?"display:inline;":"display:none")."'>Submeter</button>		
							</div>	
							</div>
                            <div class='table-responsive' style='overflow-x: hidden !important; padding-left:1%;'>
                                <table class='table table-striped table-bordered table-hover' id='tableFaltas' style='width:99%;'  >
                                    <thead>
                                        <tr>
											<th style='width:8%;' title='Nº de Processo'>NPI</th>
											<th style='width:5%;' Número do Aluno>Nº</th>
                                            <th style='width:45%;'>Nome</th>                                            
                                            <th style='width:10%; text-align:center;'><span class='glyphicon glyphicon-picture'></span></th>
                                            <th style='width:4%;' title='Faltas de Presença' id='TodasFP' style='cursor: pointer;' >FP</th>
											<th style='width:4%;' title='Faltas Disciplinares'>FD</th>
											<th style='width:4%;' title='Faltas de Atraso'>FA</th>
											<th style='width:4%;' title='Faltas de Material'>FM</th>
											<th style='width:5%;' title='Faltas de TPC'>FTPC</th>												
											<th style='width:18%;' title='Faltas Injustificadas'>FI</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
								foreach ($faltas as $k=>$dadosAluno)
								{									
									$res.="
										<tr class='gradeA' style='height:20px;'>
											<td style='width:8%;'>".$dadosAluno[1]."</td>
                                            <td style='width:5%;'>".$dadosAluno[2]."</td>
                                            <td style='width:45%;'>".utf8_decode($dadosAluno[4])."</td>											
											<td style='width:10%; text-align:center;'>
												<div style='position:relative; width:0px; height:0px; text-align:center;'>
													<div style='position:absolute; top:-8px; left:-8px;'>".($dadosAluno[3]?"<img src='data:image/jpeg;base64,$dadosAluno[3]' class='fotoAluno' >":"<img src='Imagens/utilizador.png' class='fotoAluno' title='Utilizador sem Fotografia'>")."
													</div>
												</div>
											</td>
											
											<td style='width:4%; text-align:center;'>
												
													<div class='ck-button'>
														<label>																	
															<input type='checkbox' class='todasFaltasPresenca' style='top:50px;' ".($InsMarcacaoFaltas || $Edit || $emAula?"":"disabled")." id='InsertFalta_1_".$dadosAluno[0]."' name='InsertFalta_1_".$dadosAluno[0]."' ".($dadosAluno[5]?'checked':'')." onchange='RemoveFaltas(".$dadosAluno[0].")'><span style='width:20px; height:20px;' title='Falta Presença'>".($dadosAluno[16]?$dadosAluno[16]:"0")."</span>
														</label>
													</div>	
												
											</td>
											<td style='width:4%;'>					
																									
													<div class='ck-button'>
														<label>
															<input type='checkbox' style='top:50px;' ".($InsMarcacaoFaltas || $Edit || $emAula?"":"disabled")." id='InsertFalta_2_".$dadosAluno[0]."' ".($RequivalenciaFaltasTemEqui?"onchange='PreencheFaltaPresenca(".$dadosAluno[0].", this)'":"")." name='InsertFalta_2_".$dadosAluno[0]."' ".($dadosAluno[7]?'checked':'')."><span style='width:20px; height:20px;' title='Falta Disciplinar'>".($dadosAluno[17]?$dadosAluno[17]:"0")."</span>
														</label>
													</div>												
											</td>
											<td style='width:4%;'>											
												
													<div class='ck-button'>
														<label>																	
															<input type='checkbox' style='top:50px;' ".($InsMarcacaoFaltas || $Edit || $emAula?"":"disabled")." id='InsertFalta_3_".$dadosAluno[0]."' name='InsertFalta_3_".$dadosAluno[0]."' ".($dadosAluno[9]?'checked':'')."><span style='width:20px; height:20px;' title='Falta Atraso'>".($dadosAluno[18]?$dadosAluno[18]:"0")."</span>
														</label>
													</div>												
											</td>
											<td style='width:4%;'>											
												
													<div class='ck-button'>
														<label>																	
															<input type='checkbox' style='top:50px;' ".($InsMarcacaoFaltas || $Edit || $emAula?"":"disabled")." id='InsertFalta_4_".$dadosAluno[0]."' name='InsertFalta_4_".$dadosAluno[0]."' ".($dadosAluno[11]?'checked':'')."><span style='width:20px; height:20px;' title='Falta Material'>".($dadosAluno[19]?$dadosAluno[19]:"0")."</span>
														</label>
													</div>
											
											</td>
											<td style='width:5%; text-align:center;'>										
												<div class='ck-button' >
													<label>																	
														<input type='checkbox' style='top:50px;' ".($InsMarcacaoFaltas || $Edit || $emAula?"":"disabled")." id='InsertFalta_5_".$dadosAluno[0]."' name='InsertFalta_5_".$dadosAluno[0]."' ".($dadosAluno[13]?'checked':'')."><span style='width:20px; height:20px;' title='Falta TPC'>".($dadosAluno[20]?$dadosAluno[20]:"0")."</span>
													</label>
												</div>												
											</td>											
											<td style='width:18%; text-align:center;'>".($dadosAluno[21]?$dadosAluno[21]:"0")."</td>
                                        </tr>              
									";
								}                                        
                                $res.="   
                                    </tbody>
                                </table>								
                            </div>                           
                        </div>														
                    </div> 
					<div style='min-height:37px;'>
						&nbsp;&nbsp;
					</div>					
                </div>				
			</form>			
		<script>
		
		function RemoveFaltas (dadosAluno)
		{
			if(  $('#InsertFalta_1_'+dadosAluno).prop( 'checked' ) )  
			{
				var i = 2;
				for(i = 2; i<=5; i++)
				{
					$('#InsertFalta_'+i+'_'+dadosAluno).prop('checked', false);
					$('#InsertFalta_'+i+'_'+dadosAluno).attr('disabled', true);
				}			
			}
			else
			{
				var i = 2;
				for(i = 2; i<=5; i++)
				{					
					$('#InsertFalta_'+i+'_'+dadosAluno).attr('disabled', false);
				}						
			}
				
		}
		
		var todoscomFaltaPresenca = false;
		
		function preencheFaltasFP (todoscomFP)
		{					
			if (!todoscomFP)
			{
				$('.todasFaltasPresenca').prop('checked', true);
				$('#modalFaltasDisciplinar').modal('show');
				
				$('#modalText').text('Foram marcadas falta de presença a todos os alunos');
				setTimeout( function () { $('#modalFaltasDisciplinar').modal('hide'); }, 3000);		
				
				return todoscomFaltaPresenca = true;
			}
			else
			{
				$('.todasFaltasPresenca').prop('checked', false);
				return todoscomFaltaPresenca = false;							
			}			
		}		
		
		
		$('#TodasFP').click( function () { preencheFaltasFP (todoscomFaltaPresenca); } )
		
		function PreencheFaltaPresenca(dadosAluno, element)
		{			
			if( $('#InsertFalta_2_'+dadosAluno).is( ':checked' ))
			{			
				$('#InsertFalta_1_'+dadosAluno).prop('checked', true);		
				$('#InsertFalta_1_'+dadosAluno).click( function()
				{
					return false;
				});			
			}
			else
			{				
				$('#InsertFalta_1_'+dadosAluno).prop('checked', false);		
			}
			
			$('#modalFaltasDisciplinar').modal('show'); 				
				
			setTimeout( function () { $('#modalFaltasDisciplinar').modal('hide'); }, 3000);
		}	
			
		var tableFaltas; 
			$(function() {
				tableFaltas = $('#tableFaltas').DataTable({
					 'order': [[ 1,'asc' ]],
				'language': {								
									'sZeroRecords': 'Sem Resultados. É necessário preencher os filtros',												 
								},columnDefs: [
														{ orderable: false, 'targets': [-7,-6,-5,-4,-3,-2,-1] }
														],														  
								 'bPaginate': false,
								'bInfo' : false,
								bFilter: false
				});
			});	

				$('#TodasFP').css('cursor','pointer');
				
				if( Edit || id_submit || InsMarcacaoFaltas)
				{
					$('#Faltas_Nav').removeClass('disabled');
				}			
				
		</script>
		";
		return $res;
	}
	
	function InsertFaltasDB ($idHorario,$idProf,$TempoLetivo,$ID_NUtente,$TipoFalta,$Data)
	{
		global $conn;
		
		$QInsertFaltas = "
		declare @data as date set @data=N'$Data'
		INSERT INTO PED_FaltasAlunos (ID_Horario,ID_TipoHorario, ID_NUtente, ID_TipoFalta, Data, isJustificada,ID_FaltaPresenca,ID_Equivalencia,ID_Prova,isAnulada,isAutomatica,isParaLimiteNotificacao,ID_FaltasNotificacao,ID_FaltaNotificacaoAviso,ID_FaltaPremarcacao,DataRegisto,ID_NUtenteRegisto,inProcessamentoFaltas,inProcessamentoAvisos,inProcessamentoNotificacoes,ID_TempoLectivo)
		values (".$idHorario.",1,".$ID_NUtente.",".$TipoFalta.", @data ,0,NULL,NULL,NULL,0,0,1,NULL,NULL,NULL,GETDATE(),".$idProf.",0,0,0,".$TempoLetivo.")";
		
		$RInsertFaltas = sqlsrv_query($conn,$QInsertFaltas)or die(print_r(sqlsrv_errors()));		
	}	
	
	function DeleteFaltasDB ($idHorario,$TempoLetivo,$idProf,$Data)
	{
		global $conn;
		
		$query="
		declare @data as date set @data=N'$Data'
		delete from PED_FaltasAlunos
		where ID_Horario='".$idHorario."'
		and ID_TempoLectivo='".$TempoLetivo."'
		and ID_NUtenteRegisto='".$idProf."'
		and Data=@data
		";
		
		sqlsrv_query($conn,$query)or die(print_r(sqlsrv_errors()));			
	}
	?>	
	
	
