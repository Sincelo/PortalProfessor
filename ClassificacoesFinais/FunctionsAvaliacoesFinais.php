<?
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
			$filtros [] = array ($row['Ano'], $row['Ano']."º Ano" );
		}		
		return json_encode($filtros);		
	}	
	
	function getDisciplinasProf ($idProf, $AnoLetivo,$Escola,$Ano,$Turma, $DiretorTurma=false)
	{
		global $conn;
		
		$filtros=array();
		
		if(is_numeric($idProf) && is_numeric($AnoLetivo) && is_numeric($Escola) && is_numeric($Ano) && is_numeric($Turma)) {
			$QGetDisciplinasProf="select distinct d.ID_Disciplina id_d, d.Sigla sigla from PED_Turmas_Disciplinas_Professores tdp
			inner join PED_Disciplinas d on tdp.ID_Disciplina=d.ID_Disciplina
			inner join PED_Turmas t on t.ID_Turma=tdp.ID_Turma
			WHERE ".($DiretorTurma?"":"TDP.ID_NUTENTE = '".$idProf."' and")." T.ANOLECTIVO = '".$AnoLetivo."' AND T.ID_Escola = '".$Escola."' AND T.ID_Ano = '".$Ano."' and t.ID_Turma='".$Turma."'";			
			
			// die($QGetDisciplinasProf);
			
			$resultDisciplinasProf = sqlsrv_query($conn, $QGetDisciplinasProf);	

			while($row=sqlsrv_fetch_array($resultDisciplinasProf,SQLSRV_FETCH_ASSOC))
			{
				$filtros [] = array ($row['id_d'], utf8_encode($row['sigla']));
			}
		}
		return json_encode($filtros);		
	}	
	
	function getTurmasProf ($idProf, $AnoLetivo,$Escola,$Ano)		
	{	
		global $conn;
		
		$filtros=array();
		
		if(is_numeric($Ano) && is_numeric($AnoLetivo) && is_numeric($Escola)) {
			$QGetTurmasProf = "SELECT DISTINCT TDP.ID_TURMA id_t,T.DESIGNACAO turma
			FROM PED_TURMAS_DISCIPLINAS_PROFESSORES TDP
			INNER JOIN PED_TURMAS T ON T.ID_TURMA = TDP.ID_TURMA 
			WHERE TDP.ID_NUTENTE = '".$idProf."' AND T.ANOLECTIVO = '".$AnoLetivo."' AND T.ID_Escola = '".$Escola."' AND T.ID_Ano = '".$Ano."'";
			
			$resultTurmasProf = sqlsrv_query($conn, $QGetTurmasProf) or debug(__LINE__.$QGetTurmasProf.print_r(sqlsrv_errors(),1));

			while($row=sqlsrv_fetch_array($resultTurmasProf,SQLSRV_FETCH_ASSOC))
			{
				$filtros [] = array ($row['id_t'], utf8_encode($row['turma']));
			}
		}
		return json_encode($filtros);
	}
	
	function getModulosProf($Disciplina,$curso=false, $anoletivo=false, $ano=false)
	{
		global $conn;
		//trocar o curso e a disciplina. Curso pode ser por condiçao no where ou nao.
		$QModulos="select distinct mod.ID_Modulo, mod.Designacao from PED_Matriculas_Modulos mm
		inner join PED_Modulos mod on mod.ID_Modulo=mm.ID_Modulo and mod.ID_Disciplina=mm.ID_Disciplina
		where mm.ID_Disciplina='".$Disciplina."' ".($curso?"and ID_Curso='".$curso."'":"")." and isActive='1' and mm.AnoLectivo='".$anoletivo."' and ID_Ano='".$ano."'
		";		
		// die($QModulos);		
		$RModulos = sqlsrv_query($conn,$QModulos);
		
		while($row=sqlsrv_fetch_array($RModulos,SQLSRV_FETCH_NUMERIC))
		{		
			$filtros [] = array ($row[0], utf8_encode($row[1]));
		}		
		return json_encode($filtros);
	}
	
	function getDiretorTurmaProf($idProf, $turma)
	{
		global $conn;
		
		$QCursoTurma = "select ID_Curso from ped_turmas
		where ID_TURMA='".$turma."'";
		
		$QCursoTurma=sqlsrv_query($conn,$QCursoTurma);
		
		while ($rowCursoTurma=sqlsrv_fetch_array($QCursoTurma,SQLSRV_FETCH_NUMERIC))
		{
			$curso=$rowCursoTurma[0];
		}
		
		$query="select * from PED_MatrizCursos mc
		inner join PED_Cursos c on mc.ID_MatrizCurso=c.ID_MatrizCurso and c.isActive=mc.isActive
		where c.ID_Curso='".$curso."' and isModular='1' and c.isActive='1'";		
		
		$result = sqlsrv_query($conn, $query);
		
		$isModular=sqlsrv_has_rows ($result);
		
		$query="select * from ped_turmas
		where ID_NUtenteDirector='".$idProf."' and ID_Turma='".$turma."'";
		
		$result=sqlsrv_query($conn, $query);
		
		if($result && !$isModular)//se nao for disciplina modular
		$ProfDiretorTurma = sqlsrv_has_rows($result);	
		
		return $ProfDiretorTurma;		
	}
	
	function filtrosAvalFinais ($idProf,$anoletivo=false, $escola=false, $ano=false, $disciplina=false, $turma=false, $modulo=false, $avaliacaoInserida=false, $curso=false)
	{
		global $conn;	
		
		$AnosLetivosjson = getAnoLetivoProf ($idProf);		
		$AnoLetivoArray = json_decode($AnosLetivosjson);
			
		$Escolajson = getEscolaProf ($idProf,$AnoLetivoArray[0][0]);
		$EscolaArray = json_decode($Escolajson);
		
		if($ano)
		{
			$Anojson = getAnoProf($idProf, $anoletivo, $escola);
		}
		
		if($turma)
		{
			$Turmajson = getTurmasProf($idProf, $anoletivo, $escola, $ano);
		}
		
		if($disciplina)
		{
			$Disciplinajson = getDisciplinasProf($idProf, $anoletivo, $escola, $ano,$turma);
		}	
		
		if($modulo)
		{
			$Modulojson = getModulosProf($disciplina,$curso, $anoletivo, $ano);
		}
		
		$res = "
			<script>
				var avaliacaoInserida =".json_encode($avaliacaoInserida).";		
				var guardarano;
				var guardaranoletivo;
				var guardarturma;
				var guardardisciplina;
				var guardarescola;
				var guardamodulo;
				var arrayguardar = new Array();
				var inputHasChanged;
				
				
				function preenche(dados,anoletivo,escola,ano,turma,disciplina)
				{	
						var discmodal = $('#disciplina').val();
						
						if( $.isNumeric(discmodal) && editon && inputHasChanged )
						{
							$(document).on('change','.select',function(){
									   changed = $(this).attr('id');									   
								  });
							$('#ModalAvalFinalSubmit').modal('show');
							$('#textoModalSub').text('Deseja sair sem guardar alterações?');							
							
							$('#closeModalAvalFinalsub').click( function ()
							{								
								arrayguardar [0] = ['anoletivo', guardaranoletivo];
								arrayguardar [1] = ['escola', guardarescola];
								arrayguardar [2] = ['ano', guardarano];
								arrayguardar [3] = ['turma', guardarturma];
								arrayguardar [4] = ['disciplina', guardardisciplina];								
								
								for ( i=0; i<=arrayguardar.length; i++)
								{
									if( changed == arrayguardar[i][0])
									{
										$('#'+changed).val( arrayguardar[i][1] );
									}
									i++;
								}								
							});							
							return false;							
						}	
					
					$.get('FiltrosAvalFinaisGET.php',{act:dados,anoletivo:anoletivo, escola:escola, ano:ano, turma:turma, disciplina:disciplina}).done(function(data) 
					{
						var obj = JSON.parse(data);						
						
						if(obj.length != undefined && obj[0] == 'Diretor' )
						{
							if(obj)
							{						
								var valor = $('#'+dados).val();
							
								$('#'+dados).empty().append($('<option />').text(dados).attr('disabled','disabled').attr('selected','selected'));									
								
								for(var i=0; i<obj[1].length; i++)
								$('#'+dados).append($('<option />').val(obj[1][i][0]).text(obj[1][i][1]));							
								
								if (valor)
								{								
									$('#'+dados).val(valor);						
								}					
								
								$('#'+dados).change();						
							}
								$('#PDFdiv').css('display','inline');
						}
						else
						{
							if(obj)
							{						
								var valor = $('#'+dados).val();
							
								$('#'+dados).empty().append($('<option />').text(dados).attr('disabled','disabled').attr('selected','selected'));									
								
								for(var i=0; i<obj.length; i++)
								$('#'+dados).append($('<option />').val(obj[i][0]).text(obj[i][1]));							
								
								if (valor)
								{								
									$('#'+dados).val(valor);						
								}					
								
								$('#'+dados).change();				
							}
						}												
					});										
				}		
				
				function Resultados ()
				{					
					if( $('#disciplina').val() != guardardisciplina && editon  && inputHasChanged)
					{						
						$(document).on('change','.select',function(){
									   changed = $(this).attr('id');									   
								  });
							$('#ModalAvalFinalSubmit').modal('show');
							$('#textoModalSub').text('Deseja sair sem guardar alterações?');							
							
							$('#closeModalAvalFinalsub').click( function () {
								
								arrayguardar [0] = ['anoletivo', guardaranoletivo];
								arrayguardar [1] = ['escola', guardarescola];
								arrayguardar [2] = ['ano', guardarano];
								arrayguardar [3] = ['turma', guardarturma];
								arrayguardar [4] = ['disciplina', guardardisciplina];								
								
								for ( i=0; i<=arrayguardar.length; i++)
								{
									if( changed == arrayguardar[i][0])
									{
										$('#'+changed).val( arrayguardar[i][1] );
									}
									i++;
								}
								
							});
							
							return false;
					}
					guardarano = $('#ano').val();
					guardaranoletivo = $('#anoletivo').val();
					guardarturma = $('#turma').val();
					guardardisciplina = $('#disciplina').val();
					guardarescola = $('#escola').val();
					$('.SpinningWheel').css('display', 'block');
					
					$.get('FiltrosAvalFinaisGET.php',{act:'resultado',anoletivo: $('#anoletivo').val(), escola:$('#escola').val(), ano:$('#ano').val(), turma:$('#turma').val(), disciplina:$('#disciplina').val()}).done(function(data)
					{
						$('.SpinningWheel').css('display', 'none');
						obj = JSON.parse(data);							
						
						if( obj [0] == 'isModular')
						{							
							$('#modulo').css('display','inline');					
							
							if(obj [1])
							{								
								var valorModulo = $('#modulo').val();
							
								$('#modulo').empty().append($('<option />').text('módulo').attr('disabled','disabled').attr('selected','selected'));									
								
								for(var i=0; i<obj[1].length; i++)
								$('#modulo').append($('<option />').val(obj[1][i][0]).text(obj[1][i][1]));							
								
								if (valorModulo)
								{								
									$('#modulo').val(valorModulo);						
								}				
								
								$('#modulo').change();				
							}							
						}				
						else
						{
							$('#modulo').css('display','none');
							$('#div_avalfinal').html(obj[1]);		
						}				
					});
					
					
				}

				function ResultadosModulo ()
				{
					if( $('#modulo').val() != guardamodulo && editon  && inputHasChanged )
					{
						$(document).on('change','.select',function(){
								   changed = $(this).attr('id');									   
							  });
						$('#ModalAvalFinalSubmit').modal('show');
						$('#textoModalSub').text('Deseja sair sem guardar alterações?');							
						
						$('#closeModalAvalFinalsub').click( function () {
							
							arrayguardar [0] = ['anoletivo', guardaranoletivo];
							arrayguardar [1] = ['escola', guardarescola];
							arrayguardar [2] = ['ano', guardarano];
							arrayguardar [3] = ['turma', guardarturma];
							arrayguardar [4] = ['disciplina', guardardisciplina];
							arrayguardar [5] = ['modulo', guardarmodulo];							
							
							for ( i=0; i<=arrayguardar.length; i++)
							{
								if( changed == arrayguardar[i][0])
								{
									$('#'+changed).val( arrayguardar[i][1] );
								}
								i++;
							}							
						});						
						return false;
					}
					
					guardamodulo = $('#modulo').val();
					
					$.get('FiltrosAvalFinaisGET.php',{act:'resultadoModulo',anoletivo: $('#anoletivo').val(), escola:$('#escola').val(), ano:$('#ano').val(), turma:$('#turma').val(), disciplina:$('#disciplina').val(), modulo:$('#modulo').val()}).done(function(data)
					{						
						$('#div_avalfinal').html(data);
						
						
						// if($('#modulo').val() != null)
						// {						
							// $('#Imprimir').css('display','inline');
						// }	
					});
				}			
					function PrintWindow ()
					{
						window.print();						
					}
			</script>
			
			<div class='form-group' style='display:inline; padding-left:1%;'>			
				<select id='anoletivo' class='form-control select' name='anoletivo' style='height:35px; width:15%; display:inline; border-radius:1px;' onchange=\"preenche('escola',this.value)\" ><option></option>".JSONtoOption($AnosLetivosjson,0,1,($anoletivo?$anoletivo:$AnoLetivoArray[0][0]))."</select>			
				<select id='escola' class='form-control select' name='escola' style='height:35px; width:35%; display:inline; border-radius:1px;' onchange=\"preenche('ano',$('#anoletivo').val(),this.value)\"><option></option>".JSONtoOption($Escolajson ,0,1,($escola?$escola:$EscolaArray[0][0]))."</select>
				<select id='ano' class='form-control select' name='ano' style='height:35px; width:10%; display:inline; border-radius:1px;' onchange=\"preenche('turma',$('#anoletivo').val(),$('#escola').val(),this.value)\" ><option></option>".JSONtoOption(($Anojson?$Anojson:false) ,0,1,$ano)."</select>
				<select id='turma' class='form-control select' name='turma' style='height:35px; width:10%; display:inline; border-radius:1px;' onchange=\"preenche('disciplina',$('#anoletivo').val(),$('#escola').val(),$('#ano').val(),this.value)\"><option></option>".JSONtoOption(($Turmajson?$Turmajson:false),0,1,$turma)."</select>
				<select id='disciplina' name='disciplina' class='form-control select' style='height:35px; width:10%; display:inline; border-radius:1px;' onchange=\"Resultados()\"><option></option>".JSONtoOption(($Disciplinajson?$Disciplinajson:false),0,1,$disciplina)."</select>
				<select id='modulo' name='modulo' class='form-control select' style='height:35px; width:10%; display:none; border-radius:1px; border-color:#FF8000' onchange=\"ResultadosModulo()\"><option></option>".JSONtoOption(($Modulojson?$Modulojson:false),0,1,$modulo)."</select>
			</div>
			<div id='PDFdiv' style='display:none; padding-left:1px;' title='Exportar para PDF'>
				<img src='../Imagens/pdf.png' class='btn btn-default' style='border-radius: 2px; margin:0 auto; border:0px; padding:2px; cursor:pointer;' height='35px' width='35px' name='PDF' id='PDF' onclick='exportPDF()'></img>
			</div>	
		";	
		
		return $res;
	}
	
	function getAverbamentos ()
	{
		global $conn;
		
		$QAverbamentos = "
		select ID_Averbamento, Alinea, Descricao from PED_Averbamentos
		where isActive=1 and isAvaliacao=1";
		
		$RAverbamentos = sqlsrv_query($conn, $QAverbamentos);
		
		while ($row = sqlsrv_fetch_array($RAverbamentos,SQLSRV_FETCH_NUMERIC)) 
		{
			$averbamentos [$row[0]] = array($row[1], utf8_encode($row[2]));			
		}		
		return $averbamentos;		
	}
	
	function getcriterios($idsel,$factor)
	{
		global $conn;
		$query="SELECT ID_CriterioAvaliacao, Factor FROM PED_CriteriosAvaliacao WHERE ID_CriterioPai='$idsel'";
		$res = sqlsrv_query($conn,$query) or die(__LINE__.print_r(sqlsrv_errors(),1));
		$criterios=array();
		while($row=sqlsrv_fetch_array( $res, SQLSRV_FETCH_NUMERIC)) {
			$c=getcriterios($row[0],$factor?($row[1]/$factor)*100:0);
			if(count($c)) {
				$f=array_sum($c)?$row[1]/array_sum($c):0;
				foreach($c as $k=>$v) $c[$k]=$v*$f;
				$criterios=$criterios+$c;
			}
		}
		if(count($criterios)) {
			$f=array_sum($criterios)?$factor/array_sum($criterios):0;
			foreach($criterios as $k=>$v) $criterios[$k]=$v*$f;
		} else $criterios[$idsel]=$factor;
		return $criterios;
	}
	
	function getNotasProvisorias ($Disciplina, $curso, $Ano, $AnoLetivo, $Turma) 
	{
		global $conn;
		
		$QPeriodos = "select Nperiodo, convert(varchar(10),DataInicio,120), convert(varchar(10),DataFim,120)from PED_PeriodosLectivos
		where AnoLectivo='".$AnoLetivo."'";	
		
		$RPeriodos = sqlsrv_query($conn, $QPeriodos);
		
		while($row=sqlsrv_fetch_array($RPeriodos, SQLSRV_FETCH_NUMERIC))
		{
			$Periodos [$row[0]] = array($row[1], $row[2]); 
		}		
		
		$QAvaliacaoProv="SELECT ID_CriterioAvaliacao, Numero FROM PED_CriteriosAvaliacao CA WHERE CA.ID_Disciplina = '".$Disciplina."' AND CA.ID_Curso = '".$curso."' AND CA.ID_Ano = '".$Ano."' AND ID_TipoCriterio=8 ORDER BY Numero";
		$RAvaliacaoProv=sqlsrv_query($conn,$QAvaliacaoProv);
		$cperiodo=array();
		$maxperiodo=0;

		while($row=sqlsrv_fetch_array( $RAvaliacaoProv, SQLSRV_FETCH_NUMERIC))
		{
			$query="SELECT ID_CriterioAvaliacao, Numero FROM PED_CriteriosAvaliacao CA WHERE CA.ID_Disciplina = '".$Disciplina."' AND CA.ID_Curso = '".$curso."' AND CA.ID_Ano = '".$Ano."' AND ID_CriterioPai='".$row[0]."' ORDER BY ID_CriterioAvaliacao";
			$res = sqlsrv_query($conn,$query) or die(__LINE__.print_r(sqlsrv_errors(),1));
			if(sqlsrv_fetch_array( $res, SQLSRV_FETCH_NUMERIC)) $maxperiodo=$row[1];
			$cperiodo[$row[1]]=$row[0];
		}		
		
		$query="SELECT NPeriodo FROM PED_PeriodosLectivos WHERE GETDATE() BETWEEN DataInicio AND DataFim";
		$res = sqlsrv_query($conn,$query) or die(__LINE__.print_r(sqlsrv_errors(),1));
		if($row=sqlsrv_fetch_array( $res, SQLSRV_FETCH_NUMERIC)) $myperiodo=$row[0];
		else $myperiodo=1;

		if($maxperiodo>1) {
			$nperiodo=$myperiodo;
		} else $nperiodo=1;
		$nperiodo=1;
		$criterios[$Disciplina]=array();		
		$criterios[$Disciplina]=getcriterios($cperiodo[$nperiodo],100);		
		
		$QAlunosTurma="SELECT U.ID_NUtente, UTU.ID_Utente, MAL.NumeroAluno, U.NomeAbreviado, UF.Imagem, SM.Sigla as SituacaoMatricula, m.ID_Matricula, m.ID_Curso
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
		
		$RAlunosTurma = sqlsrv_query($conn, $QAlunosTurma);		
		
		while ($row = sqlsrv_fetch_array($RAlunosTurma,SQLSRV_FETCH_NUMERIC)) 			
		{
				for($i=1; $i<4; $i++)
				{
					$query="SELECT AVG(Nota), ID_CriterioAvaliacao FROM PED_AvaliacaoProvisoria AP WHERE AnoLectivo='".$AnoLetivo."' AND ID_CriterioAvaliacao IN ('".implode("','",array_keys($criterios[$Disciplina]))."') AND ID_Matricula='".$row[6]."' AND Nota IS NOT NULL 
					AND CONVERT(VARCHAR(10),AP.DataNota, 20) BETWEEN '".$Periodos[$i][0]."' AND '".$Periodos[$i+1][0]."'
					GROUP BY ID_CriterioAvaliacao";
					
					$res = sqlsrv_query($conn,$query) or debug(__LINE__.$query.print_r(sqlsrv_errors(),1));
					$mycriterios=array();
					$media[$Disciplina][$row[6]][$i]=0;
					while($rw=sqlsrv_fetch_array( $res, SQLSRV_FETCH_NUMERIC)) {
						$mycriterios[$rw[1]][$row[6]][$i]=$rw[0];					
						$media[$Disciplina][$row[6]][$i]+=$rw[0]*$criterios[$Disciplina][$rw[1]];
					}					
					$sumfactor=0;
					foreach($mycriterios as $k=>$v) $sumfactor+=$criterios[$Disciplina][$k];
					if($sumfactor==0) $media[$Disciplina][$row[6]][$i]=0;
					else $media[$Disciplina][$row[6]][$i]=round(10*$media[$Disciplina][$row[6]][$i]/($sumfactor*10));
					
				}		
		}		
		return $media;	
	}
	
	function tableAvalFinais ($idProf,$AnoLetivo=false,$Escola=false,$Ano=false,$Disciplina=false,$Turma=false, $Modulo=false,$avaliacaoInserida=false )
	{		
		global $conn;
		
		$QCursoTurma = "select ID_Curso from ped_turmas
		where ID_TURMA='".$Turma."'";
		
		$QCursoTurma=sqlsrv_query($conn,$QCursoTurma);
		
		while ($rowCursoTurma=sqlsrv_fetch_array($QCursoTurma,SQLSRV_FETCH_NUMERIC))
		{
			$curso=$rowCursoTurma[0];
		}
		
		if(!$modulo)
		{
			for ($i = 1; $i<=3;$i++)
			{
				$criterio= getCriterioAvaliacao($curso, $Disciplina, $Ano, $i, $Modulo );				
				$criteriosNotas [$i] = $criterio;
			}		
		}
		else
		{
			$criterio= getCriterioAvaliacao($curso, $Disciplina, $Ano, $i, $Modulo );				
			$criteriosNotas [1] = $criterio;
		}	

		$data = date('Y-m-d'); 
		
		$QFechoAvaliacao = "select NPeriodo from PED_Turmas_PeriodosLectivos tpl
		inner join PED_Turmas t on t.ID_Turma=tpl.ID_Turma
		where tpl.ID_Turma='".$Turma."' and tpl.isFechoAvaliacao=1
		order by Nperiodo ASC";	
		
		$RFechoAvaliacao=sqlsrv_query($conn,$QFechoAvaliacao);	
		
		while($rowFechoAval=sqlsrv_fetch_array($RFechoAvaliacao, SQLSRV_FETCH_NUMERIC))
		{
			$fechoPeriodo [$rowFechoAval[0]] = 1;
		}	
		
		$QAlunosTurma="SELECT U.ID_NUtente, UTU.ID_Utente, MAL.NumeroAluno, U.NomeAbreviado, UF.Imagem, SM.Sigla as SituacaoMatricula, m.ID_Matricula, m.ID_Curso
		from PED_Turmas as T
		inner join PED_Matriculas_AnosLectivos as MAL on MAL.ID_Turma = T.ID_Turma and t.AnoLectivo=mal.AnoLectivo and mal.AnoLectivo='".$AnoLetivo."'
		inner join PED_Matriculas as M on M.ID_Matricula = MAL.ID_Matricula
		inner join PED_Matriculas_Disciplinas md on md.ID_Matricula=m.ID_Matricula and md.ID_Disciplina='".$Disciplina."' and md.AnoLectivo=MAl.AnoLectivo and t.ID_Turma=md.ID_Turma		
		inner join GER_Utentes as U on U.ID_NUtente = M.ID_NUtente
		inner join GER_Utentes_TiposUtentes as UTU on UTU.ID_NUtente = U.ID_NUtente
		inner join PED_SituacoesMatriculas as SM on SM.ID_SituacaoFinal = MAL.ID_SituacaoFinal
		inner join GER_UtentesFoto as UF on UF.ID_NUtente = U.ID_NUtente
		".($Modulo?"inner join PED_Modulos mod on mod.ID_Disciplina=md.ID_Disciplina and md.ID_Disciplina='".$Disciplina."'":"")."		
		where T.ID_Turma = '".$Turma."' and md.ID_Disciplina='".$Disciplina."' ".($Modulo?"and mod.ID_Modulo='".$Modulo."'":"")."		
		order by MAL.NumeroAluno";

		// die($QAlunosTurma);
		
		$RAlunosTurma=sqlsrv_query($conn,$QAlunosTurma);	
		$FaltasTotal = array();
		$FaltasJustificadas = array();		
		
		while($row=sqlsrv_fetch_array($RAlunosTurma,SQLSRV_FETCH_NUMERIC))
		{
			$NotasPorPeriodo = array();
			$JustificacaoPeriodo = array();
			$AverbamentoPeriodo = array();
			
			foreach ($criteriosNotas as $k=>$v)
			{
				$QNotasAlunos = "select ad.Nota, ID_PeriodoLectivo, Justificacao, ID_Averbamento from PED_AvaliacaoDefinitiva ad
				inner join PED_Matriculas_Disciplinas md on ad.ID_Matricula=md.ID_Matricula and ad.AnoLectivo=md.AnoLectivo
				inner join PED_Matriculas m on md.ID_Matricula=m.ID_Matricula
				".($Modulo?"inner join PED_Modulos mod on mod.ID_Disciplina=md.ID_Disciplina and md.ID_Disciplina='".$Disciplina."'":"")."
				inner join PED_CriteriosAvaliacao ca on ca.ID_CriterioAvaliacao=ad.ID_CriterioAvaliacao and ca.ID_Disciplina=md.ID_Disciplina ".($Modulo?" and ca.ID_Modulo=mod.ID_Modulo":"")."	
				where m.ID_NUtente='".$row[0]."' and md.ID_Disciplina='".$Disciplina."' and md.AnoLectivo='".$AnoLetivo."' and md.ID_Ano='".$Ano."' and md.ID_Turma='".$Turma."' ".($Modulo?"and mod.ID_Modulo='".$Modulo."'  and ca.ID_TipoCriterio='35'":"")." and ad.ID_CriterioAvaliacao='".$criteriosNotas[$k]."'
				order by ID_PeriodoLectivo";
				
				// die($QNotasAlunos);
				
				$RNotasAlunos=sqlsrv_query($conn,$QNotasAlunos);		
				
				while($rowNota=sqlsrv_fetch_array($RNotasAlunos,SQLSRV_FETCH_NUMERIC))
				{					
					$NotasPorPeriodo[$k]=$rowNota[0];
					if($rowNota[2]!="NULL")
					{
						$JustificacaoPeriodo [$k] = $rowNota[2];
					}
					
					if($rowNota[3]!="NULL")
					{
						$AverbamentoPeriodo [$k] = $rowNota[3];
					}			
				}				
			}			
			
			$cursoAluno = $row[7];
			$dadosAluno [$row[0]]= array ($row[1],$row[2],$row[3], base64_encode($row[4]), $row[5], $NotasPorPeriodo[1], $NotasPorPeriodo[2], $NotasPorPeriodo[3], $row[6], $JustificacaoPeriodo[1], $JustificacaoPeriodo[2], $JustificacaoPeriodo[3], $AverbamentoPeriodo[1], $AverbamentoPeriodo[2], $AverbamentoPeriodo[3],);			
			
				$QTotalFaltas="select fa.ID_NUtente, Nperiodo, COUNT (ID_Falta) from PED_FaltasAlunos fa
				inner join PED_TiposFalta tf on tf.ID_TipoFalta=fa.ID_TipoFalta
				inner join PED_Horarios_Turmas ht on ht.ID_Horario=fa.ID_Horario and ht.ID_Tempo=fa.ID_TempoLectivo
				inner join PED_PeriodosLectivos pl on pl.ID_NPeriodo=ht.ID_NPeriodo					
				where TF.ID_TipoUtente = 1				
				and fa.ID_TipoFalta=1
				and ID_TipoUtente=1
				AND HT.ID_Disciplina='".$Disciplina."'
				AND HT.ID_Turma='".$Turma."'				
				AND  FA.ID_NUtente='".$row[0]."'
				
				group by fa.ID_NUtente, Nperiodo";			

				$RTotalFaltas = sqlsrv_query($conn,$QTotalFaltas);			
				
				while ($rowFaltasTotal = sqlsrv_fetch_array($RTotalFaltas,SQLSRV_FETCH_NUMERIC))
				{
					$FaltasTotal [$rowFaltasTotal [0] ][ $rowFaltasTotal [1] ] = $rowFaltasTotal [2]; 
				}			
				
				$QFaltasInjustificadas = "select fa.ID_NUtente, Nperiodo, COUNT (ID_Falta) from PED_FaltasAlunos fa
				inner join PED_TiposFalta tf on tf.ID_TipoFalta=fa.ID_TipoFalta
				inner join PED_Horarios_Turmas ht on ht.ID_Horario=fa.ID_Horario and ht.ID_Tempo=fa.ID_TempoLectivo
				inner join PED_PeriodosLectivos pl on pl.ID_NPeriodo=ht.ID_NPeriodo
				where TF.ID_TipoUtente = 1
				and fa.isJustificada=0
				and fa.ID_TipoFalta=1
				and ID_TipoUtente=1
				AND HT.ID_Disciplina='".$Disciplina."'
				AND HT.ID_Turma='".$Turma."'
				AND  FA.ID_NUtente='".$row[0]."'				
				group by fa.ID_NUtente, Nperiodo";				
				
				$RFaltasInjustificadas = sqlsrv_query($conn,$QFaltasInjustificadas);			
				
				while ($rowFaltasInjustificadas = sqlsrv_fetch_array($RFaltasInjustificadas,SQLSRV_FETCH_NUMERIC))
				{							
					$FaltasInjustificadas [$rowFaltasInjustificadas [0] ] [$rowFaltasInjustificadas [1] ]= $rowFaltasInjustificadas [2]; 
				}	
		}		
		
		$QEscalaAvaliacao = "select distinct NotaMinima, NotaMaxima, ID_AvaliacaoQualitativa from PED_EscalasAvaliacao ea inner join PED_Cursos c on c.ID_EscalaAvaliacao=ea.ID_EscalaAvaliacao
		inner join PED_MatrizOpcoesCursos moc on moc.ID_Curso=c.ID_Curso
		inner join PED_Disciplinas d on d.ID_NivelEnsino=c.ID_NivelEnsino and d.ID_Disciplina=moc.ID_Disciplina
		where d.ID_Disciplina='".$Disciplina."' and c.ID_Curso='".$curso."'
		";		
		
		$REscalaAvaliacao=sqlsrv_query($conn,$QEscalaAvaliacao);
		
		while ($rowEscala=sqlsrv_fetch_array($REscalaAvaliacao,SQLSRV_FETCH_NUMERIC))
		{
			$escalaMin = $rowEscala[0];
			$escalaMax = $rowEscala[1];
			if($rowEscala[2] != NULL)
			{				
				$qualitativa=$rowEscala[2];				
			}			
		}	
		$averbamentos_array = getAverbamentos ();		
		
		//avaliacao provisoria	
		$NotasProvisorias = getNotasProvisorias ($Disciplina, $curso, $Ano, $AnoLetivo, $Turma);
		
		// print_r($NotasProvisorias);
		// exit;
		
		if($dadosAluno)
		{
			foreach ($dadosAluno as $k_IDN=>$vdados)
			{
				$idMat_alunos [] = $vdados[8]; 
			}
		}		
			$res ="
			<script>
				var escalaMin = ".json_encode($escalaMin).";
				var escalaMax = ".json_encode($escalaMax).";				
				
				function escalaAvaliacao(id,num)
				{	
					if(num == 1)
					{
						var id_linha = $('#txt_1_'+id);
						var text = $('#txt_1_'+id).val();
					}					
				
					if(num == 2)
					{
						var id_linha = $('#txt_2_'+id);
						var text = $('#txt_2_'+id).val();						
					}
					
					if(num == 3)
					{
						var id_linha = $('#txt_3_'+id);
						var text = $('#txt_3_'+id).val();
					}								
					if(TestarEscala (id, num))
					{					
						id_linha.css('background-color','#FF0000');
						var textModalAviso = 'O valor inserido é inválido. Insira um valor entre '+escalaMin+' e '+escalaMax;
						
						id_linha.prop('title', textModalAviso );
					}
					else
					{						
						id_linha.css('background-color','#FFF');
						id_linha.prop('title', '');
					}
				}
				
				function TestarEscala (id, num)
				{
					if(num == 1)
					{
						var id_linha = $('#txt_1_'+id);
						var text = $('#txt_1_'+id).val();
					}					
				
					if(num == 2)
					{
						var id_linha = $('#txt_2_'+id);
						var text = $('#txt_2_'+id).val();						
					}
					
					if(num == 3)
					{
						var id_linha = $('#txt_3_'+id);
						var text = $('#txt_3_'+id).val();
					}					
					
					var text_int = parseInt(text);								
					
					if(text_int > escalaMax || text_int < escalaMin)
					{ return true;}
					else
					{ return false;}
				}
				
			</script>		
			<input type='hidden' name='act' value='InsertNotas'>			
			<input type='hidden' name='id_curso' id='id_curso' value='".$curso."'>
			<input type='hidden' name='id_disciplina' id='id_disciplina' value='".$Disciplina."'>
			<input type='hidden' name='id_ano' id='id_ano' value='".$Ano."'>
			<input type='hidden' name='id_turma' id='id_turma' value='".$Turma."'>
			<input type='hidden' name='anoletivo' id='anoletivo' value='".$AnoLetivo."'>
			<input type='hidden' name='escola' id='escola' value='".$Escola."'>
			<input type='hidden' name='edit1' id='edit1' value=''>
			<input type='hidden' name='edit2' id='edit2' value=''>
			<input type='hidden' name='edit3' id='edit3' value=''>			
			<input type='hidden' name='arrayAlunos' id='arrayAlunos' value='".json_encode($idMat_alunos)."'>
			<input type='hidden' name='qualitativa' id='qualitativa' value='".$qualitativa."'>
			<input type='hidden' name='modulo' id='modulo' value='".$Modulo."'>
			<input type='hidden' name='escalaMin' id='escalaMin' value='".$escalaMin."'>
			<input type='hidden' name='escalaMax' id='escalaMax' value='".$escalaMax."'>";				
			
			if(!$Modulo)
			{
				$res.="										
				<div class='panel-body'>						
					<div class='table-responsive' style='overflow-x: hidden !important; padding-left:1%;'>
						<table class='table table-striped table-bordered table-hover' id='tableAvalFinais' style='width:99%;'>
							<thead>
								<tr>
									<tr style='font-size:14px;'>
										<th style='width:22%;' colspan='5'></th>
										<th colspan='6' title='Classificações 1º Período'>1º Período <div id='EditNotasPeriodo1' title='Editar Classificações do 1º Período' style='cursor:pointer; float:right; ".($fechoPeriodo[1]?"display:none;":"display:block;")."'><i id='editGlyph1' class='glyphicon glyphicon-pencil'/></div></th>
										<th colspan='6' title='Classificações 2º Período'>2º Período <div id='EditNotasPeriodo2' title='Editar Classificações do 2º Período' style='cursor:pointer; float:right; ".($fechoPeriodo[2]?"display:none;":"display:block;")."'><i id='editGlyph2' class='glyphicon glyphicon-pencil'/></div></th>
										<th colspan='6' title='Classificações 3º Período'>3º Período <div id='EditNotasPeriodo3' title='Editar Classificações do 3º Período' style='cursor:pointer; float:right; ".($fechoPeriodo[3]?"display:none;":"display:block;")."'><i id='editGlyph3' class='glyphicon glyphicon-pencil'/></div></th>
									</tr>
									<tr>
										<th title='Nº de Processo'>NPI</th>
										<th title='Número do Aluno'>Nº</th>
										<th title='Nome'>Nome</th>
										<th style='text-align:center; width:24px;' title='Fotografia'><span class='glyphicon glyphicon-picture'></span></th>
										<th style='width:40px;' title='Situação de Matrícula'>SM</th>
										<th style='width:40px;' title='Classificação Provisória'>CP</th>										
										<th title='Classificação Final'>CF</th>										
										<th style='width:50px;' title='Faltas Totais'>FT</th>										
										<th style='width:50px;' title='Faltas Injustificadas'>FI</th>
										<th style='width:30px;' title='Averbamentos 1º Período'>Avb.</th>										
										<th style='width:30px;' title='Observações 1º Período'>Obs.</th>
										<th style='width:40px;' title='Classificação Provisória'>CP</th>										
										<th title='Classificação Final'>CF</th>										
										<th style='width:50px;' title='Faltas Totais'>FT</th>										
										<th style='width:50px;' title='Faltas Injustificadas'>FI</th>
										<th style='width:30px;' title='Averbamentos 2º Período'>Avb.</th>
										<th style='width:30px;' title='Observações 2º Período'>Obs.</th>
										<th style='width:40px;' title='Classificação Provisória'>CP</th>
										<th title='Classificação Final'>CF</th>
										<th style='width:50px;' title='Faltas Totais'>FT</th>										
										<th style='width:50px;' title='Faltas Injustificadas'>FI</th>
										<th style='width:30px;' title='Averbamentos 3º Período'>Avb.</th>
										<th style='width:30px;' title='Observações 3º Período'>Obs.</th>
									</tr>
								</tr>
							</thead>
							<tbody>";
							if($dadosAluno)
							{
								foreach ($dadosAluno as $k_idNutente => $v_dados)
								{									
									$res.="<tr class='gradeA ".($v_dados[4] != 'M'?"NaoMat":"")."' style='height:15px; ".($v_dados[4]!="M"?"background-color:#FFE5CC;":"")."'>
												<td style='text-align:center;'>".$v_dados[0]."</td>
												<td style='text-align:center;'>".$v_dados[1]."</td>
												<td >".utf8_encode($v_dados[2])."</td>											
												<td text-align:center; width:24px;'>
													<div style='position:relative; width:0px; height:0px; text-align:center;'>
														<div style='position:absolute; top:0px; left:0px;'>".($v_dados[3]?"<img src='data:image/jpeg;base64,".$v_dados[3]."' class='fotoAluno' >":"<img src='../Imagens/utilizador.png' class='fotoAluno' title='Utilizador sem Fotografia'>")."
														</div>
													</div>
												</td>
												<td style='text-align:center;'><div class='SM'>".$v_dados[4]."</div></td>
												<td style='text-align:center;'>".($NotasProvisorias[$Disciplina][$v_dados[8]][1]?$NotasProvisorias[$Disciplina][$v_dados[8]][1]:"-")."</td>
												<td style='text-align:center;' class='td_Notas1Periodo'>";
													//variavel para teste [apagar]
													$EscalaQual=EscalaQualitativa($qualitativa);	
													
													if($qualitativa && !($v_dados[5]))
														$res.= checkAvalQualitativa($v_dados[8],1,$qualitativa,0, ($v_dados[4] != "M"?1:false));
													else if($qualitativa && $v_dados[5])
													{
														foreach ($EscalaQual as $kescala=>$vescala)
														{
															if($v_dados[5]>=$vescala[1] && $v_dados[5]<=$vescala[2])
															{
																$res.="<div class='temValor1' id='temvalor_1_".$v_dados[8]."' title='".$vescala[0]."'>".$vescala[3]."</div>";
																$checkedNota = $vescala [1];
															}
															
														}
														$res.= checkAvalQualitativa($v_dados[8],1,$qualitativa,0, $checkedNota, ($v_dados[4] != "M"?1:false));
														//no caso de os valores ja estarem inseridos na base de dados e serem qualitativos																					
													}
													else
													{
														$res.="<div class='temValor1' id='temvalor_1_".$v_dados[8]."' style='".($v_dados[5]?"Display:block":"Display:none").";'>".($v_dados[5]?intval($v_dados[5]):"")."</div>
														<div class='txt1Input enter' style='display:none;'><input type='number' ".($v_dados[4]!="M"?"disabled":"")." autocomplete='off' class='form-control testeNotas' id='txt_1_".$v_dados[8]."' onkeypress='return isNumberKey(event)' onchange=\"escalaAvaliacao(".json_encode($v_dados[8]).",1)\" style='text-align:center; padding:2px 4px; font-size:12px; height:20px;' name='Nota_1_".$v_dados[8]."' value='".($v_dados[5]?intval($v_dados[5]):"")."'></div>																									";
													}												
												$res.="</td>												
												<td style='text-align:center;'>".($FaltasTotal[$k_idNutente][1]?$FaltasTotal[$k_idNutente][1]:"-")."</td>
												<td style='text-align:center;'>".($FaltasInjustificadas[$k_idNutente][1]?$FaltasInjustificadas[$k_idNutente][1]:"-")."</td>
												<td style='text-align:center;'>
													<div title='".$averbamentos_array[$v_dados[12]][1]."' id='vis_abv_1_".$v_dados[8]."' style='".($v_dados[12]?"display:block":"display:none").";' class='vis_abv'>".$averbamentos_array[$v_dados[12]][0]."</div>
													<div class='abv_1' id='abv_1_".$v_dados[8]."' style='".($v_dados[12]?"display:none":"display:block").";'><i class='glyphicon glyphicon-plus'></i></div>
													<input type='hidden' id='Notaabv_1_".$v_dados[8]."' name='Notaabv_1_".$v_dados[8]."' value='".($v_dados[12]?$v_dados[12]:"")."'>
												</td>
												<td style='text-align:center;' >
													<div class='visObs' id='vis_obs_1_".$v_dados[8]."' style='".($v_dados[9]?"display:block":"display:none").";'><i class='glyphicon glyphicon-search'></i></div>												
													<div class='obs_1' id='obs_1_".$v_dados[8]."' style='".($v_dados[9]?"display:none":"display:block").";'><i class='glyphicon glyphicon-plus'></i></div>
													<input type='hidden' id='Notatxtobs_1_".$v_dados[8]."' name='Notatxtobs_1_".$v_dados[8]."' value='".($v_dados[9]?$v_dados[9]:"")."'>
												</td>
												<td style='text-align:center;'>".($NotasProvisorias[$Disciplina][$v_dados[8]][2]?$NotasProvisorias[$Disciplina][$v_dados[8]][2]:"-")."</td>
												<td style='text-align:center;' class='td_Notas2Periodo'>";
													if($qualitativa && !($v_dados[6]))
														$res.= checkAvalQualitativa($v_dados[8],2,$qualitativa,0, ($v_dados[4] != "M"?1:false));
													else if($qualitativa && $v_dados[6])
													{
														foreach ($EscalaQual as $kescala=>$vescala)
														{
															if($v_dados[6]>=$vescala[1] && $v_dados[6]<=$vescala[2] )
															{
																$res.="<div class='temValor2' id='temvalor_2_".$v_dados[8]."' title='".$vescala[0]."'>".$vescala[3]."</div>";
																$checkedNota = $vescala [1];
															}															
														}
														$res.= checkAvalQualitativa($v_dados[8],2,$qualitativa,0,$checkedNota, ($v_dados[4] != "M"?1:false));																																																
													}
													else
													{
														$res.="<div class='temValor2' id='temvalor_2_".$v_dados[8]."' style='".($v_dados[6]?"Display:block":"Display:none").";'>".($v_dados[6]?intval($v_dados[6]):"")."</div>
														<div class='txt2Input enter' style='display:none;'><input class='form-control testeNotas' autocomplete='off' type='number' ".($v_dados[4]!="M"?"disabled":"")." onkeypress='return isNumberKey(event)' id='txt_2_".$v_dados[8]."' onchange=\"escalaAvaliacao(".json_encode($v_dados[8]).",2)\" style='text-align:center; padding:2px 4px; font-size:12px; height:20px;' name='Nota_2_".$v_dados[8]."' value='".($v_dados[6]?intval($v_dados[6]):"")."'></div>";
													}
													
												$res.="</td>
												<td style='text-align:center;'>".($FaltasTotal[$k_idNutente][2]?$FaltasTotal[$k_idNutente][2]:"-")."</td>
												<td style='text-align:center;'>".($FaltasInjustificadas[$k_idNutente][2]?$FaltasInjustificadas[$k_idNutente][2]:"-")."</td>
												<td style='text-align:center;'>
													<div title='".$averbamentos_array[$v_dados[13]][1]."' id='vis_abv_2_".$v_dados[8]."' style='".($v_dados[13]?"display:block":"display:none").";' class='vis_abv'>".$averbamentos_array[$v_dados[13]][0]."</div>
													<div class='abv_2' id='abv_2_".$v_dados[8]."' style='".($v_dados[13]?"display:none":"display:block").";'><i class='glyphicon glyphicon-plus'></i></div>
													<input type='hidden' id='Notaabv_2_".$v_dados[8]."' name='Notaabv_2_".$v_dados[8]."' value='".($v_dados[13]?$v_dados[13]:"")."'>
												</td>
												<td style='text-align:center;'>
													<div class='visObs' id='vis_obs_2_".$v_dados[8]."' style='".($v_dados[10]?"display:block":"display:none").";'><i class='glyphicon glyphicon-search'></i></div>													
													<div class='obs_2' id='obs_2_".$v_dados[8]."' style='".($v_dados[10]?"display:none":"display:block").";'><i class='glyphicon glyphicon-plus'></i></div>
													<input type='hidden' id='Notatxtobs_2_".$v_dados[8]."' name='Notatxtobs_2_".$v_dados[8]."' value='".($v_dados[10]?$v_dados[10]:"")."'>
												</td>
												<td style='text-align:center;'>".($NotasProvisorias[$Disciplina][$v_dados[8]][3]?$NotasProvisorias[$Disciplina][$v_dados[8]][3]:"-")."</td>
												<td style='text-align:center;' class='td_Notas3Periodo'>";
													if($qualitativa && !($v_dados[7]))
														$res.= checkAvalQualitativa($v_dados[8],3,$qualitativa,0, ($v_dados[4] != "M"?1:false));
													else if($qualitativa && $v_dados[7])													
													{
														foreach ($EscalaQual as $kescala=>$vescala)
														{
															if($v_dados[7]>=$vescala[1] && $v_dados[7]<=$vescala[2])
															{
																$res.="<div class='temValor3' id='temvalor_3_".$v_dados[8]."' title='".$vescala[0]."'>".$vescala[3]."</div>";
																$checkedNota = $vescala [1];
															}															
														}
														$res.= checkAvalQualitativa($v_dados[8],3,$qualitativa,0, $checkedNota, ($v_dados[4] != "M"?1:false));																																															
													}
													else
													{ $res.="<div class='temValor3' id='temvalor_3_".$v_dados[8]."' style='".($v_dados[7]?"Display:block":"Display:none").";'>".($v_dados[7]?intval($v_dados[7]):"")."</div>
															<div class='txt3Input enter' style='display:none;'><input type='number' autocomplete='off' ".($v_dados[4]!="M"?"disabled":"")." class='form-control testeNotas' onkeypress='return isNumberKey(event)' id='txt_3_".$v_dados[8]."' onchange=\"escalaAvaliacao(".json_encode($v_dados[8]).",3)\" style='text-align:center; padding:2px 4px; font-size:12px; height:20px;' name='Nota_3_".$v_dados[8]."' value='".($v_dados[7]?intval($v_dados[7]):"")."'></div>";													
													}
												$res.="</td>
												<td style='text-align:center;'>".($FaltasTotal[$k_idNutente][3]?$FaltasTotal[$k_idNutente][3]:"-")."</td>
												<td style='text-align:center;'>".($FaltasInjustificadas[$k_idNutente][3]?$FaltasInjustificadas[$k_idNutente][3]:"-")."</td>
												<td style='text-align:center;'>
													<div title='".$averbamentos_array[$v_dados[14]][1]."' id='vis_abv_3_".$v_dados[8]."' style='".($v_dados[14]?"display:block":"display:none").";'>".$averbamentos_array[$v_dados[14]][0]."</div>
													<div class='abv_3' id='abv_3_".$v_dados[8]."' style='".($v_dados[14]?"display:none":"display:block").";'><i class='glyphicon glyphicon-plus'></i></div>
													<input type='hidden' id='Notaabv_3_".$v_dados[8]."' name='Notaabv_3_".$v_dados[8]."' value='".($v_dados[14]?$v_dados[14]:"")."'>
												</td>
												<td style='text-align:center;'>
													<div class='visObs' id='vis_obs_3_".$v_dados[8]."' style='".($v_dados[11]?"display:block":"display:none").";' class='vis_abv'><i class='glyphicon glyphicon-search'></i></div>													
													<div class='obs_3' id='obs_3_".$v_dados[8]."' style='".($v_dados[11]?"display:none":"display:block").";'><i class='glyphicon glyphicon-plus'></i></div>
													<input type='hidden' id='Notatxtobs_3_".$v_dados[8]."' name='Notatxtobs_3_".$v_dados[8]."' value='".($v_dados[11]?$v_dados[11]:"")."'>
												</td>
											</tr>";
								}
							}
							$res.="</tbody>
                                </table>								
                            </div>                           
                        </div>														                  
				";
			}
			else if ($Modulo)
			{
				$res.="				
				<div class='panel-body'>						
					<div class='table-responsive' style='overflow-x: hidden !important; padding-left:1%;'>
						<table class='table table-striped table-bordered table-hover' id='tableAvalFinais' style='width:99%;'>
							<thead>
								<tr>
									<tr>
										<th style='width:40%;' colspan='5'></th>
										<th colspan='6' title='Classificação'>Classificações <div id='EditNotasPeriodo1' title='Editar Classificações' style='cursor:pointer; float:right;'><i id='editGlyph1' class='glyphicon glyphicon-pencil'/></div></th>										
									</tr>
									<tr>
										<th title='Nº de Processo'>NPI</th>
										<th title='Número do Aluno'>Nº</th>
										<th title='Nome'>Nome</th>
										<th style='text-align:center; width:24px;' title='Fotografia'><span class='glyphicon glyphicon-picture'></span></th>
										<th style='width:40px;' title='Situação de Matrícula'>SM</th>																			
										<th title='Classificação Final'>CF</th>										
										<th style='width:30px;' title='Averbamentos'>Avb.</th>										
										<th style='width:30px;' title='Observações'>Obs.</th>										
									</tr>
								</tr>
							</thead>
							<tbody>";
							if($dadosAluno)
							{
								foreach ($dadosAluno as $k_idNutente => $v_dados)
								{
									$res.="<tr class='gradeA ".($v_dados[4] != 'M'?"NaoMat":"")."' style='height:20px; ".($v_dados[4]!="M"?"background-color:#FFE5CC;":"")."'>
												<td style='text-align:center;'>".$v_dados[0]."</td>
												<td style='text-align:center;'>".$v_dados[1]."</td>
												<td >".utf8_encode($v_dados[2])."</td>											
												<td style='text-align:center; width:24px;'>
													<div style='position:relative; width:0px; height:0px; text-align:center;'>
														<div style='position:absolute; top:0px; left:0px;'>".($v_dados[3]?"<img src='data:image/jpeg;base64,".$v_dados[3]."' class='fotoAluno' >":"<img src='../Imagens/utilizador.png' class='fotoAluno' title='Utilizador sem Fotografia'>")."
														</div>
													</div>
												</td>
												<td style='text-align:center;'><div class='SM'>".$v_dados[4]."</div></td>									
												<td style='text-align:center;' class='td_Notas1Periodo'>";
													//variavel para teste [apagar]
													$EscalaQual=EscalaQualitativa($qualitativa);												
													if($qualitativa && !($v_dados[5]))
														$res.= checkAvalQualitativa($v_dados[8],1,$qualitativa,0, ($v_dados[4] != "M"?1:false));
													else if($qualitativa && $v_dados[5])
													{
														foreach ($EscalaQual as $kescala=>$vescala)
														{
															if($v_dados[5]>=$vescala[1] && $v_dados[5]<=$vescala[2])
															{
																$res.="<div class='temValor1' id='temvalor_1_".$v_dados[8]."' title='".$vescala[0]."'>".$vescala[3]."</div>";
																$checkedNota = $vescala [1];
															}															
														}
														$res.= checkAvalQualitativa($v_dados[8],1,$qualitativa,0,$checkedNota, ($v_dados[4] != "M"?1:false) );
														//no caso de os valores ja estarem inseridos na base de dados e serem qualitativos																					
													}
													else
													{
														$res.="<div class='temValor1' id='temvalor_1_".$v_dados[8]."' style='".($v_dados[5]?"Display:block":"Display:none").";'>".($v_dados[5]?intval($v_dados[5]):"")."</div>													
														<div class='txt1Input enter' style='display:none;'><input type='number' onkeypress='return isNumberKey(event)' autocomplete='off' class='form-control testeNotas' id='txt_1_".$v_dados[8]."' ".($v_dados[4]!="M"?"disabled":"")." onchange=\"escalaAvaliacao(".json_encode($v_dados[8]).",1)\" style='text-align:center; padding:2px 4px; font-size:12px; height:20px;' name='Nota_1_".$v_dados[8]."' value='".($v_dados[5]?intval($v_dados[5]):"")."'></div>																									";
													}												
												$res.="</td>																								
												<td style='text-align:center;'>
													<div title='".$averbamentos_array[$v_dados[12]][1]."' style='".($v_dados[12]?"display:block":"display:none").";' id='vis_abv_1_".$v_dados[8]."' class='vis_abv'>".$averbamentos_array[$v_dados[12]][0]."</div>
													<div class='abv_1' id='abv_1_".$v_dados[8]."' style='".($v_dados[12]?"display:none":"display:block").";'><i class='glyphicon glyphicon-plus'></i></div>
													<input type='hidden' id='Notaabv_1_".$v_dados[8]."' name='Notaabv_1_".$v_dados[8]."' value='".($v_dados[12]?$v_dados[12]:"")."'>
												</td>
												<td style='text-align:center;'>
													<div class='visObs' id='vis_obs_1_".$v_dados[8]."' style='".($v_dados[9]?"display:block":"display:none").";'><i class='glyphicon glyphicon-search'></i></div>												
													<div class='obs_1' id='obs_1_".$v_dados[8]."' style='".($v_dados[9]?"display:none":"display:block").";'><i class='glyphicon glyphicon-plus'></i></div>
													<input type='hidden' id='Notatxtobs_1_".$v_dados[8]."' name='Notatxtobs_1_".$v_dados[8]."' value='".($v_dados[9]?$v_dados[9]:"")."'>
												</td>												
											</tr>";
								}
							}
							$res.="</tbody>
                                </table>								
                            </div>                           
                        </div>														
                    </div> 					
                </div>
				";
			}
		
			$res.="
				<div class='modal fade' id='ModalComment' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
				  <div class='modal-dialog'>
					<div class='modal-content'>
					  <div class='modal-header'>
						<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
						<h4 class='modal-title' id='myModalLabel'>Observações</h4>
					  </div>
					  <div class='modal-body'>
						<textarea id='obs' class='textarea form-control' placeholder='Observações...' ></textarea>
					  </div>
					  <div class='modal-footer'>
						<button type='button' class='btn btn-default' data-dismiss='modal' id='modalclose' >Cancelar</button>
						<button type='button' class='btn btn-primary' id='ModalSave' >Confirmar</button>
					  </div>
					</div>
				  </div>
				</div>
				
				
				<div class='modal fade' id='ModalAverbamento' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
				  <div class='modal-dialog'>
					<div class='modal-content'>
					  <div class='modal-header'>
						<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
						<h4 class='modal-title' id='myModalLabel'>Averbamentos</h4>
					  </div>
					  <div class='modal-body'>
					  <div style='min-height:100px;'>";
					  if(is_array($averbamentos_array))
						{foreach ($averbamentos_array as $k_idAvb=> $v_avb)
							{
								$res.="<div>
									<div class='ck-button'>
										<label>																	
											<input type='checkbox' style='top:50px;' class='avb' id='avb_".$k_idAvb."'><span style='width:30px; height:30px;' class='spanAVB' title='".$v_avb[1]."'>".($v_avb[0])."</span>											
										</label>
									</div>
								</div>";								
							}
						}						
					  $res.="
					  <div>
							<div class='ck-button'>
								<label>																	
									<input type='checkbox' style='top:50px;' class='avb_delete' ><span style='width:30px; height:30px;' class='spanAVB' title='Limpar todas as seleções'></span>											
								</label>
							</div>
						</div>
					  </div>
					  <div id='averbInfo'></div>
					  </div>
					  <div class='modal-footer'>
						<button type='button' class='btn btn-default' data-dismiss='modal' id='modalcloseAvb' >Cancelar</button>
						<button type='button' class='btn btn-primary' id='ModalSaveAvb' >Confirmar</button>
					  </div>
					</div>			  
				
				</form>
				<script>
						
						var idMat;
						var idMatVis;
						var edit1=false;
						var edit2=false;
						var edit3=false;
						var idMatabv;		
						var idMatabv_vis;	
						var editon;
						
						$('.AvalQualitativa').click(function ()
							{
								var thisteste = $(this).attr('id');								
								
								if( !this.checked )
								{									
									$(this).attr('checked','false');
								}
								else
								{									
									var teste = $(this).closest('td').find(':checkbox').each( function () {									

									if( thisteste != $(this).attr('id') ) this.checked=false; });																
									
								}						
							}
						);
						
						function isNumberKey(evt){
							var charCode = (evt.which) ? evt.which : event.keyCode
							if (charCode > 31 && (charCode < 48 || charCode > 57))
								return false;
							return true;
						} 																
						
						$('#tableAvalFinais tbody tr').click(function () {							
							 $('#tableAvalFinais tbody tr').css('background-color', '#FFFFFF');
							 $('.NaoMat').css('background-color', '#FFE5CC');
							$(this).css('background-color', '#CCE5FF');	
						});	
						
						$('.NaoMat').click(function () {							
								$(this).css('background-color', '#FFE5CC');					
						});	
						
						$('input').change(function(){							
							  inputHasChanged = 1;
						})
						
						$('#EditNotasPeriodo1').click(function()
						{
							if(edit1)
							{													
								if(inputHasChanged)
								{
									$('#ModalAvalFinalSubmit').modal('show');
									$('#textoModalSub').text('Deseja sair sem guardar alterações?');								

									$('#closeModalAvalFinalsub').click( function () {
											
										$('#edit1').val('');								
										$('#editGlyph1').addClass('glyphicon-pencil');
										$('#editGlyph1').removeClass('glyphicon-edit');
										$('.temValor1').show();
										$('.txt1Input').hide();	
										$('.AvalQualitativaCB_1').css('display','none');
										edit1 = false;
										editon= false;
										
									})
									return false;	
								}
								else
								{
									$('#edit1').val('');								
									$('#editGlyph1').addClass('glyphicon-pencil');
									$('#editGlyph1').removeClass('glyphicon-edit');
									$('.temValor1').show();
									$('.txt1Input').hide();	
									$('.AvalQualitativaCB_1').css('display','none');
									edit1 = false;
									editon= false;
								}						
							}
							else if (!edit1 && !edit2 && !edit3) 
							{
								$('#edit1').val(1);
								$('#editGlyph1').removeClass('glyphicon-pencil');
								$('#editGlyph1').addClass('glyphicon-edit');
								$('#subAval').removeClass('disabled');
								$('.temValor1').hide();
								$('.txt1Input').show();	
								$('.AvalQualitativaCB_1').css('display','inline');
								edit1 = 1;
								editon=1;
							}
							else
							{								
								$('#ModalAvalFinalSubmit').modal('show');
								$('#textoModalSub').text('Deseja guardar as alterações?');
								
								$('#closeModalAvalFinalsub').click( function () {
									$('#edit1').val(1);
									$('#subAval').removeClass('disabled');
									$('.temValor1').hide();
									$('.txt1Input').show();	
									$('.AvalQualitativaCB_1').css('display','inline');
									edit1 = 1;
									editon=1;
									
									$('#edit2').val('');								
									$('.temValor2').show();
									$('.txt2Input').hide();	
									$('.AvalQualitativaCB_2').css('display','none');
									edit2 = false;
									editon= false;
									
									$('#edit3').val('');								
									$('.temValor3').show();
									$('.txt3Input').hide();	
									$('.AvalQualitativaCB_3').css('display','none');
									edit3 = false;
									editon= false;
								})
							}							
						});
						
						$('#EditNotasPeriodo2').click(function()
						{
							if(edit2)
							{
								if(inputHasChanged)
								{
									$('#ModalAvalFinalSubmit').modal('show');
									$('#textoModalSub').text('Deseja sair sem guardar alterações?');										

									$('#closeModalAvalFinalsub').click( function () {
										$('#edit2').val('');
										$('.temValor2').show();
										$('.txt2Input').hide();	
										$('.AvalQualitativaCB_2').css('display','none');
										edit2 = false;
										editon= false;
										$('#editGlyph2').addClass('glyphicon-pencil');
										$('#editGlyph2').removeClass('glyphicon-edit');
									})
									
									return false;
								}
								else
								{
									$('#edit2').val('');								
									$('.temValor2').show();
									$('.txt2Input').hide();	
									$('.AvalQualitativaCB_2').css('display','none');
									edit2 = false;
									editon= false;
									$('#editGlyph2').addClass('glyphicon-pencil');
									$('#editGlyph2').removeClass('glyphicon-edit');
								}
								
							}
							else if (!edit1 && !edit2 && !edit3) 
							{
								$('#edit2').val(1);
								$('#subAval').removeClass('disabled');
								$('.temValor2').hide();
								$('.txt2Input').show();	
								$('.AvalQualitativaCB_2').css('display','inline');
								edit2 = 1;
								editon=1;
								$('#editGlyph2').removeClass('glyphicon-pencil');
								$('#editGlyph2').addClass('glyphicon-edit');
							}
							else
							{					
								$('#ModalAvalFinalSubmit').modal('show');
								$('#textoModalSub').text('Deseja guardar as alterações?');
								
								$('#closeModalAvalFinalsub').click( function () {									
									
									$('#edit2').val(1);
									$('#subAval').removeClass('disabled');
									$('.temValor2').hide();
									$('.txt2Input').show();	
									$('.AvalQualitativaCB_2').css('display','inline');
									edit2 = 1;
									editon=1;
									
									$('#edit1').val('');								
									$('.temValor1').show();
									$('.txt1Input').hide();	
									$('.AvalQualitativaCB_1').css('display','none');
									edit1 = false;
									editon= false;
									
									$('#edit3').val('');								
									$('.temValor3').show();
									$('.txt3Input').hide();	
									$('.AvalQualitativaCB_3').css('display','none');
									edit3 = false;
									editon= false;
								})
							}							
						});
						
						$('#EditNotasPeriodo3').click(function()
						{
							if(edit3)
							{							
								if(inputHasChanged)
								{
									$('#ModalAvalFinalSubmit').modal('show');
									$('#textoModalSub').text('Deseja sair sem guardar alterações?');

									$('#closeModalAvalFinalsub').click( function () {
										$('#edit3').val('');								
										$('.temValor3').show();
										$('.txt3Input').hide();	
										$('.AvalQualitativaCB_3').css('display','none');
										edit3 = false;
										editon= false;
										$('#editGlyph3').addClass('glyphicon-pencil');
										$('#editGlyph3').removeClass('glyphicon-edit');
									})
									return false;
								}
								else
								{
									$('#edit3').val('');								
									$('.temValor3').show();
									$('.txt3Input').hide();	
									$('.AvalQualitativaCB_3').css('display','none');
									edit3 = false;
									editon= false;
									$('#editGlyph3').addClass('glyphicon-pencil');
									$('#editGlyph3').removeClass('glyphicon-edit');
									
									$('#closeModalAvalFinalsub').click( function () {
										$('#edit3').val('');								
										$('.temValor3').show();
										$('.txt3Input').hide();	
										$('.AvalQualitativaCB_3').css('display','none');
										edit3 = false;
										editon= false;
										$('#editGlyph3').addClass('glyphicon-pencil');
										$('#editGlyph3').removeClass('glyphicon-edit');
									})
								}
							}
							else if (!edit1 && !edit2 && !edit3) 
							{
								$('#edit3').val(1);
								$('#subAval').removeClass('disabled');
								$('.temValor3').hide();
								$('.txt3Input').show();	
								$('.AvalQualitativaCB_3').css('display','inline');
								edit3 = 1;
								editon=1;
								$('#editGlyph3').removeClass('glyphicon-pencil');
								$('#editGlyph3').addClass('glyphicon-edit');
							}
							else
							{
								$('#ModalAvalFinalSubmit').modal('show');
								$('#textoModalSub').text('Deseja guardar as alterações?');
								
								$('#closeModalAvalFinalsub').click( function () {
									$('#edit3').val(1);
									$('#subAval').removeClass('disabled');
									$('.temValor3').hide();
									$('.txt3Input').show();	
									$('.AvalQualitativaCB_3').css('display','inline');
									edit3 = 1;
									editon=1;
									
									$('#edit1').val('');								
									$('.temValor1').show();
									$('.txt1Input').hide();	
									$('.AvalQualitativaCB_1').css('display','none');
									edit1 = false;
									editon= false;
									
									$('#edit2').val('');								
									$('.temValor2').show();
									$('.txt2Input').hide();	
									$('.AvalQualitativaCB_2').css('display','none');
									edit2 = false;
									editon= false;
								})
							}
						});						
						
						$('.visObs').click(function()
						{							
							$('#obs').val('');
							idMatVis = $(this).attr('id');							
							novoIdMat = idMatVis.substring(4);							
							comment = $('#Notatxt'+novoIdMat).val();
							
							if( !editon )
							{								
								$('#obs').attr('disabled','true');
							}
							else
							{								
								$('#obs').attr('disabled', false);
							}
							
							$('#ModalComment').modal('show');
							$('#obs').val(comment);								
						});					
						
						$('.obs_1').click(function()
						{
							$('#obs').val('');
							idMat = $(this).attr('id');
							
							novoIdMatadd = idMat.substring(6);									
							
							if(edit1 )
							{								
								$('#ModalComment').modal('show');
							}
							else
							{								
								$('#ModalAvalFinal').modal('show');
								$('#textoModal').text('Clique no editar para adicionar uma observação');
							}						
						});						
												
						
						$('.obs_2').click(function()
						{
							$('#obs').val('');
							idMat = $(this).attr('id');
							
							novoIdMatadd = idMat.substring(6);									
							
							if(edit2 )
							{											
								$('#ModalComment').modal('show');
							}
							else
							{								
								$('#ModalAvalFinal').modal('show');
								$('#textoModal').text('Clique no editar para adicionar uma observação');
							}						
						});						
						
						$('.obs_3').click(function()
						{
							$('#obs').val('');
							idMat = $(this).attr('id');
							
							novoIdMatadd = idMat.substring(6);									
							
							if(edit3 )
							{											
								$('#ModalComment').modal('show');
							}
							else
							{								
								$('#ModalAvalFinal').modal('show');
								$('#textoModal').text('Clique no editar para adicionar uma observação');
							}						
						});						
												

						$('#ModalSave').click(function()
						{
							if( $('#'+idMatVis).is(':visible') )
							{
								
								idMatVis = idMatVis.substring(4);																
								$('#Notatxt'+idMatVis).val( $('#obs').val() );
								
								if( $('#obs').val() == '')
								{									
									$('#'+idMatVis).show();
									$('#vis_'+idMatVis).hide();									
								}								
							}
							else
							{
								valor = $('#obs').val();											
								$('#Notatxt'+idMat).val( valor );			
								
								if( $('#obs').val() != '')
								{								
									$('#vis_'+idMat).show();
									$('#'+idMat).hide();
								}
								else
								{
									$('#vis_'+idMat).hide();
									$('#'+idMat).show();
								}
							}						
							$('#ModalComment').modal('hide');
						});			
						
						$('#modalclose').click(function()
						{							
							$('#obs').val('');//limpar texto quando fecha o modal		
						});
						
						$('.abv_1').click(function()
						{	
							$('#averbInfo').text('');
							$( '.avb' ).prop( 'checked', false );
							idMatabv = $(this).attr('id');
							idMatabvplus = idMatabv.substring(6);
							if(edit1 )
							{								
								$('#ModalAverbamento').modal('show');							
							}
							else							
							{
								$('#textoModal').text('Clique no editar para adicionar uma observação');
								$('#ModalAvalFinal').modal('show');								
							}							
						});
						
						$('.abv_2').click(function()
						{							
							$('#averbInfo').text('');
							$( '.avb' ).prop( 'checked', false );
							idMatabv = $(this).attr('id');
							idMatabvplus = idMatabv.substring(6);
							if(edit2 )
							{
								$('#ModalAverbamento').modal('show');							
							}
							else							
							{
								$('#textoModal').text('Clique no editar para adicionar uma observação');
								$('#ModalAvalFinal').modal('show');								
							}							
						});
						
						$('.abv_3').click(function()
						{							
							$('#averbInfo').text('');
							$( '.avb' ).prop( 'checked', false );
							idMatabv = $(this).attr('id');
							idMatabvplus = idMatabv.substring(6);
							if(edit3 )
							{
								$('#ModalAverbamento').modal('show');							
							}
							else							
							{
								$('#textoModal').text('Clique no editar para adicionar uma observação');
								$('#ModalAvalFinal').modal('show');								
							}				
						});
						
						$('.spanAVB').click( function ()
						{
							var texto = $(this).attr('title');							
							$('#averbInfo').text(texto);
						});
						
						$('.avb').click( function ()
						{					
							$('.avb').prop('checked', false);
							$(this).prop('checked', true);
						});		

						$('.avb_delete').click( function () {
							$('.avb').prop('checked', false);
							$('.avb_delete').prop('checked', false);
							$('#averbInfo').text('');
						})
						
						$('#ModalSaveAvb').click(function()
						{					
							var avb_ids = $('input:checkbox:checked.avb').map(function ()
							{		
								ids_avb = $(this).attr('id');								
								
								return ids_avb.substring(4);
							}).get();							
							
							if(avb_ids.length)
							{
								if( $('#'+idMatabv_vis).is(':visible') )
								{								
									idMatabvEdit = idMatabv_vis.substring(4);
									$('#Nota'+idMatabvEdit).val( avb_ids );							
									var textAVB =$('#avb_'+avb_ids).closest('label').find('span').text();											
									$('#vis_'+idMatabvEdit).text(textAVB);
									$('#'+idMatabvEdit).hide();
									$('#vis_'+idMatabvEdit).show();			
								}
								else
								{																
									$('#Nota'+idMatabv).val( avb_ids );							
									var textAVB =$('#avb_'+avb_ids).closest('label').find('span').text();											
									$('#vis_'+idMatabv).text(textAVB);
									$('#'+idMatabv).hide();
									$('#vis_'+idMatabv).show();			
								}
							}
							else
							{	
								idMatabvmais = idMatabv_vis.substring(4);
								$('#'+idMatabvmais).show();
								$('#Nota'+idMatabvmais).val('');	
								$('#'+idMatabv_vis).hide();								
							}					
							
							$('#ModalAverbamento').modal('hide');							
						});
						
						$('#modalcloseAvb').click(function()
						{	
							$('#averbInfo').text('');
							$( '.avb' ).prop( 'checked', false );
						});
						
						$('.vis_abv').click( function ()
						{
							if( editon )
							{								
								idMatabv_vis = $(this).attr('id'); 
								var valAbv = $(this).closest('td').find('input').val();							
								$('#ModalAverbamento').modal('show');
								
								$('#avb_'+valAbv).prop('checked', true);
							}
							else
							{								
								idMatabv_vis = $(this).attr('id'); 
								var valAbv = $(this).closest('td').find('input').val();							
								$('#ModalAverbamento').modal('show');								
								$('#avb_'+valAbv).prop('checked', true);
								$('.avb').attr('disabled','true');								
								$('#ModalSaveAvb').css('display','none');
								textSub = $('#'+idMatabv_vis).attr('title');
								$('#averbInfo').text( textSub );
							}
												
						});				
						
				$('.form-control').keydown(function(e) {
				var si;
				var i=1;
				
				do
				{
					if (e.which == 13)//enter
					{						 
						$('#tableAvalFinais tbody tr').css('background-color', '#FFFFFF');
						
						 $('.NaoMat').css('background-color', '#FFE5CC');
						var indexH = $(this).closest('td').index();
						var indexV = $(this).closest('tr').index() + i;
						si=$(this).closest('table').find('tbody > tr:eq('+indexV+')').css('background-color', '#CCE5FF').find('td:eq('+indexH+')').find('input');
						si.select();
						e.preventDefault();
					}
					else if(e.which == 38)//cima
					{
						$('#tableAvalFinais tbody tr').css('background-color', '#FFFFFF');
						
						$('.NaoMat').css('background-color', '#FFE5CC');
						var indexH = $(this).closest('td').index();
						var indexV = $(this).closest('tr').index() - i;
						si=$(this).closest('table').find('tbody > tr:eq('+indexV+')').css('background-color', '#CCE5FF').find('td:eq('+indexH+')').find('input');
						si.select();
						e.preventDefault();
					}
					else if(e.which == 40)//baixo
					{
						$('#tableAvalFinais tbody tr').css('background-color', '#FFFFFF');						
						 $('.NaoMat').css('background-color', '#FFE5CC');
						var indexH = $(this).closest('td').index();
						var indexV = $(this).closest('tr').index() + i;
						si=$(this).closest('table').find('tbody > tr:eq('+indexV+')').css('background-color', '#CCE5FF').find('td:eq('+indexH+')').find('input');
						si.select();
						e.preventDefault();
					}
					if(si!=undefined && si.offset()!=undefined) {
						var docViewTop = $(window).scrollTop();
						var docViewBottom = docViewTop + $(window).height();
						var elemTop = si.offset().top;
						var elemBottom = elemTop + si.height();

						if(!((elemBottom <= docViewBottom) && (elemTop >= docViewTop)))  $('html, body').animate({scrollTop:elemBottom-($(window).height()/2)}, '500', 'swing');
					}
					// console.log( si.attr('disabled') );
					i++;					
				}				
				while ( si.attr('disabled') == 'disabled' && i<10 );
				return false;		
				
			});				

			</script>
					";
					
			return $res;		
	}
	
	function getCriterioAvaliacao ($id_curso=false, $disciplina=false, $ano=false, $periodoletivo=false, $modulo=false)
	{
		global $conn;
		
		$QcriterioAvaliaçao = "select ID_CriterioAvaliacao from PED_CriteriosAvaliacao
		where ID_Disciplina='".$disciplina."' ".($modulo?"":"and ID_Ano='".$ano."'")." ".($modulo?"and ID_Modulo='".$modulo."'":"and ID_PeriodoLectivo='".$periodoletivo."'")." and ID_Curso='".$id_curso."' ".($modulo?"and ID_TipoCriterio=35":"")." ";
		// die($QcriterioAvaliaçao);
		$RcriterioAvaliação = sqlsrv_query($conn,$QcriterioAvaliaçao);
		// die($QcriterioAvaliaçao);
		while($row = sqlsrv_fetch_array($RcriterioAvaliação, SQLSRV_FETCH_NUMERIC))
		{			
			$criterioAvaliacao = $row [0];
		}
		
		return $criterioAvaliacao;
	}
	
	function InsertNotas($idMatricula,$id_curso, $idProf, $anoletivo, $disciplina, $ano, $turma, $periodoletivo, $nota, $data, $comment=false, $abv=false, $modulo=false)
	{		
		global $conn;		
		
		//query criterioAvaliacao
		$QcriterioAvaliaçao = "		
		select ID_CriterioAvaliacao from PED_CriteriosAvaliacao
		where ID_Disciplina='".$disciplina."' ".($modulo?"":"and ID_Ano=".$ano."")." ".($modulo?"and ID_Modulo='".$modulo."'":"and ID_PeriodoLectivo='".$periodoletivo."'")." and ID_Curso='".$id_curso."' ".($modulo?"and ID_TipoCriterio=9":"")."";		

		$RcriterioAvaliação = sqlsrv_query($conn,$QcriterioAvaliaçao);
		// die($QcriterioAvaliaçao);
		while($row = sqlsrv_fetch_array($RcriterioAvaliação, SQLSRV_FETCH_NUMERIC))
		{
			$criterioAvaliacao = $row [0];
		}		
		// die($criterioAvaliacao);
		//query Inserir Notas		
		$QInsertNotas = "
		declare @data as date set @data=N'$data'
		insert into PED_AvaliacaoDefinitiva (ID_Matricula, ID_CriterioAvaliacao, AnoLectivo, Nota, isEquivalencia, isIncluirAvaliacao, Justificacao, DataNota, isAprovada, ID_Averbamento, isTrancada, isProcessada, isAvaliado, ID_AvaliacaoQualitativaDetalhe, Ajuste, IsLancadaAdministrativamente, isImportada, Utilizador, DataAlteracao, IsParaPortal)
		values ('".$idMatricula."', '".$criterioAvaliacao."', '".$anoletivo."', ".($nota?floatval($nota):"NULL").", 0,1,".($comment?"'".$comment."'":"NULL").", @data, 0, ".($abv?$abv:"NULL").", 0,1,1,NULL, '=', 0,0, '".$idProf."', NULL, 0 )";
		// die($QInsertNotas);
		// echo $QInsertNotas;
		// echo "\n";
		sqlsrv_query($conn,$QInsertNotas) or debug($QInsertNotas.__LINE__.print_r(sqlsrv_errors(),1));	
	}

	function EscalaQualitativa ($qualitativa)
	{
		global $conn;
		
		$QIntervaloEscala = "select distinct Designacao, ValorMinimo, ValorMaximo, Abreviatura from PED_AvaliacoesQualitativasDetalhes
		where ID_AvaliacaoQualitativa='".$qualitativa."'
		order by valorMinimo";		
		
		$RIntervaloEscala=sqlsrv_query($conn, $QIntervaloEscala);
		$i=0;		
		while ($rowEscalaQual=sqlsrv_fetch_array($RIntervaloEscala, SQLSRV_FETCH_NUMERIC))
		{			
			$EscalaQual [$i]=array(utf8_encode($rowEscalaQual[0]),$rowEscalaQual[1], $rowEscalaQual[2], utf8_encode($rowEscalaQual[3]));
			$i++;
		}		
		return $EscalaQual;
	}
	
	function checkAvalQualitativa ($idMat,$p,$qualitativa,$display, $checkvalor=false, $sitMat=false)
	{
		global $conn;	

		
		$EscalaQual=EscalaQualitativa($qualitativa);		
		
		foreach($EscalaQual as $k=>$v)
		{
			$res.="
			<div class='AvalQualitativaCB_".$p."' style='".($display?"display:inline":"display:none").";'>
				<div class='ck-button' style='display:inline;'>
					<label>
						<input type='checkbox' ".($sitMat?"disabled":"")." style='top:0px;' id='NotaQ_".$p."_".$idMat."_".$v[1]."' class='AvalQualitativa' ".($v[1] == $checkvalor?"checked":"")." name='NotaQ_".$p."_".$idMat."' value='".$v[1]."'><span style='width:25px; height:25px; padding-top:5px; font-size:9px;'  title='".$v[0]."'>".$v[3]."</span>											
					</label>
				</div>
			</div>";
		}		
		return $res;
	}

	function verificaNotas($anoletivo, $criterioAvaliacao,$idMatricula)
	{
		global $conn;
		$query = "select Nota, Justificacao, ID_Averbamento from PED_AvaliacaoDefinitiva
		where ID_Matricula=".$idMatricula." and AnoLectivo=".$anoletivo." and ID_CriterioAvaliacao=".$criterioAvaliacao;		
		
		$result = sqlsrv_query($conn, $query);
		$verificacaoHasResults = sqlsrv_has_rows ($result);
		// echo ($query);
		// echo "\n";
		
		if( !$verificacaoHasResults )
		{
			return false;
		}
		
		$verificacao = array ();
		while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_NUMERIC))
		{
			$verificacao = array($row[0], $row[1], $row[2]);			
		}
		
		// print_r($verificacao);
		
		return $verificacao;
	}
	
	function updateNota($anoletivo, $criterioAvaliacao, $idMatricula, $nota, $comment=false, $abv=false, $qualitativa=false, $data=false)
	{
		global $conn;	
		
		if($qualitativa)
		{
			$queryIDQualitativa="select ID_AvaliacaoQualitativaDetalhe from PED_AvaliacoesQualitativasDetalhes
			where ID_AvaliacaoQualitativa='".$qualitativa."' and ValorMinimo='".$nota."'";
			// die($queryIDQualitativa);
			$resultIDQualitativa = sqlsrv_query($conn, $queryIDQualitativa);
			
			while ($rowIDQualitativa = sqlsrv_fetch_array($resultIDQualitativa, SQLSRV_FETCH_NUMERIC))
			{
				$ID_AvaliacaoQualitativaDetalhe= $rowIDQualitativa[0];
			}	
		}
		
		$query = "
		".($data?"declare @data as date set @data=N'$data'":"")."
		update PED_AvaliacaoDefinitiva
		set Nota=  ".($nota?floatval($nota):"NULL")." ".($comment?", Justificacao='".$comment."'":", Justificacao=NULL")." ".($abv?", ID_Averbamento=".$abv."":", ID_Averbamento=NULL")." ".($qualitativa?", ID_AvaliacaoQualitativaDetalhe='".$ID_AvaliacaoQualitativaDetalhe."'":"")." ".($data?", DataNota=@data":"")." ".($data?", DataAlteracao=@data":"")."
		where ID_Matricula=".$idMatricula." and AnoLectivo=".$anoletivo." and ID_CriterioAvaliacao=".$criterioAvaliacao;
		// die($query);
		$result = sqlsrv_query ($conn, $query);		
	}
	
	function filtrosPeriodoPDF ($anoletivo )
	{
		global $conn;
		
			if($anoletivo)
			{
				$periodoJson = getPeriodosProf ($anoletivo);				
				$periodoArray = json_decode($periodoJson);				
			}			
		
		$res="
		<script>
			function trocaPeriodo (dados)		
			{				
				$.get('FiltrosAvalFinaisGET.php',{act:'periodoPDF', anoletivo: $('#anoletivo').val() }).done(function(data) 
				{
					var obj = JSON.parse(data);						
					{							
						var valor = $('#periodo').val();
					
						$('#periodo').empty().append($('<option />').text('periodo').attr('disabled','disabled').attr('selected','selected'));									
						
						for(var i=0; i<obj.length; i++)
						$('#periodo').append($('<option />').val(obj[i][0]).text(obj[i][1]));							
						
						if (valor)
						{								
							$('#periodo').val(valor);															
						}												
					}
				});			
			}	
			
		</script>
			<select id='periodo' class='form-control select' name='periodo' style='height:35px; width:35%; display:inline; border-radius:1px;' onchange=\"trocaPeriodo ( )\"><option></option>".JSONtoOption($periodoJson ,0,1,$periodoArray[0][0])."</select>
			<button type='button' class='btn btn-primary subAval' id='subAvalPeriodo' >Selecionar</button>		
		";
		
		return $res;		
	}
	
	function getPeriodosProf ($anoletivo)	
	{
		global $conn;		
		
		$query="select NPeriodo, Designacao from PED_PeriodosLectivos 
		where AnoLectivo = '".$anoletivo."'";
		
		$result = sqlsrv_query($conn, $query);
		
		while ($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_NUMERIC))
		{
			$filtros [] = array ($row[0], utf8_encode($row[1]));
		}
		// die($filtros);
		return json_encode($filtros);
	}
	
	?>