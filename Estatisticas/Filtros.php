
<?


require_once ("conn.php");

$sec = $_GET['sec'];
$ciclo23 = $_GET['23ciclo'];
$ciclo1 = $_GET['1ciclo'];

		//checkbox
		function showCheckBox ()
		{
			global $sec, $ciclo23, $ciclo1;
			global $CursoModular;

			if($sec)
			{
				$nivel="sec";
			}
			elseif($ciclo23)
			{
				$nivel="23";
			}
			elseif($ciclo1)
			{
				$nivel="1";
			}			
			
			if($nivel)
			{
				echo "
					<div class='col-lg-4' style = 'width:100%; padding-right: 0%; overflow:auto;'>
						<div class='well well-lg' style='border-radius:2px; overflow:auto; width:100%;'>
														
						<div style='float:left; padding:10px;' title='Média Por Disciplina'>												
							<div class='ck-button ResultLauncher'>
										<label>
										<input type='checkbox' id='MediaDisciplina' name='MediaDisciplina' value='MediaDisciplina'".('MediaDisciplina'==$_GET['MediaDisciplina']? ' checked':'')."><span style='width:115px; height:70px;'>Média Por Disciplina</span>
									</label>
									</div>																
							</div>
							<div style='float:left; padding:10px;' title='Média Por Turma'>	
								<div class='ck-button ResultLauncher'>
									<label>																	
										<input type='checkbox' id='MediaTurma'  name='MediaTurma' value='MediaTurma'".('MediaTurma'==$_GET['MediaTurma']? ' checked':'')."><span style='width:115px; height:70px;'>Média Por Turma</span>
									</label>
								</div>
							</div>
							<div style='float:left; padding:10px;' title='Média Por Ano'>	
								<div class='ck-button ResultLauncher'>
									<label>																	
										<input type='checkbox' id='MediaAno'  name='MediaAno' value='MediaAno'".('MediaAno'==$_GET['MediaAno']? ' checked':'')."><span style='width:115px; height:70px;'>Média Por Ano</span>
									</label>
								</div>
							</div>															
							<div style='float:left; padding:10px;' title='Média Por Disciplina e Ano'>	
									<div class='ck-button ResultLauncher'>
										<label>
											<input type='checkbox' name='MediaDisciplinaAno' id='MediaDisciplinaAno' value='MediaDisciplinaAno'" .('MediaDisciplinaAno'==$_GET['MediaDisciplinaAno']? ' checked':'')."><span style='width:115px; height:70px;'>Média Por Disciplina e Ano</span>
										</label>
									</div>
							</div>
							<div style='float:left; padding:10px;' title='Média Por Disciplina e Turma'>	
								<div class='ck-button ResultLauncher'>
										<label>
									<input type='checkbox' name='MediaDisciplinaTurma' id='MediaDisciplinaTurma' value='MediaDisciplinaTurma'" .('MediaDisciplinaTurma'==$_GET['MediaDisciplinaTurma']? ' checked':'')."><span  style='width:115px; height:70px;'>Média Disciplina e Turma</span>
								</label>																
								</div>
							</div>
							<div style='float:left; padding:10px;' title='Níveis por Turma'>	
								<div class='ck-button ResultLauncher'>
										<label>
										<input type='checkbox' name='MediaNotasTurma' id='MediaNotasTurma' value='MediaNotasTurma'".('MediaNotasTurma'==$_GET['MediaNotasTurma']? ' checked':'')."><span  style='width:115px; height:70px;'>Níveis por Turma</span>
									</label>
								</div>
							</div>
							
							<div style='float:left; padding:10px;' title='Percentagem de Negativas por Ano' class='cbNegativas'>	
								<div class='ck-button ResultLauncher'>
									<label>																	
										<input type='checkbox' name='PercentagemNegativasporAno' id='PercentagemNegativasporAno' value='PercentagemNegativasporAno'".('PercentagemNegativasporAno'==$_GET['PercentagemNegativasporAno']? ' checked':'')."><span  style='width:115px; height:70px;'>Percent. de Neg. por Ano</span>
									</label>
								</div>
							</div>
							
							<div style='float:left; padding:10px;' title='Percentagem de Negativas por Turma' class='cbNegativas'>	
								<div class='ck-button ResultLauncher'>
									<label>																	
										<input type='checkbox' name='PercentagemNegativasporTurma' id='PercentagemNegativasporTurma' value='PercentagemNegativasporTurma'".('PercentagemNegativasporTurma'==$_GET['PercentagemNegativasporTurma']? ' checked':'')."><span style='width:115px; height:70px;'>Percent. de Neg. por Turma</span>
									</label>
								</div>
							</div>
							
							<div style='float:left; padding:10px;' title='Numero de Negativas por Turma' class='cbNegativas'>	
								<div class='ck-button ResultLauncher'>
											<label>																	
												<input type='checkbox' name='NegativasporTurma' id='NegativasporTurma' value='NegativasporTurma'".('NegativasporTurma'==$_GET['NegativasporTurma']? ' checked':'')."><span style='width:115px; height:70px;'>Negativas Por Turma</span>
											</label>
								</div>
							</div>	
							
							<div style='float:left; padding:10px;' title='Numero de Negativas por Ano' class='cbNegativas'>															
								<div class='ck-button ResultLauncher'>
									<label>
										<input type='checkbox' name='NegativasporAno' id='NegativasporAno' value='NegativasporAno'".('NegativasporAno'==$_GET['NegativasporAno']? ' checked':'')."><span style='width:115px; height:70px; '>Negativas Por Ano</span>
									</label>
								</div>
							</div>
							
							<div style='float:left; padding:10px;' title='Numero de Negativas por Disciplina' class='cbNegativas'>	
									<div class='ck-button ResultLauncher'>
										<label>
											<input type='checkbox' name='NegativasporDisciplina' id='NegativasporDisciplina' value='NegativasporDisciplina'".('NegativasporDisciplina'==$_GET['NegativasporDisciplina']? ' checked':'')."><span style='width:115px; height:70px;'>Negativas Por Disciplina</span>
										</label>
									</div>
							</div>
							
							<div style='float:left; padding:10px;' title='Numero de Ocorrências de Níveis Por Disciplina' class='cbNegativas'>				
										<div class='ck-button ResultLauncher'>
											<label>
												<input type='checkbox' name='dist' id='dist' title='Distribuição de Avaliações por Disciplina' value='dist'".('dist'==$_GET['dist']? ' checked':'')."><span style='width:115px; height:70px;'>Nr. Ocorrências Por Disciplina</span>
											</label>
										</div>																		
							</div>
							
							<div style='float:left; padding:10px;' title='Alunos Com Negativa' class='cbNegativas'>	
											<div class='ck-button ResultLauncher'>
												<label>
												<input type='checkbox' id='AlunosNegativa' name='AlunosNegativa' value='AlunosNegativa'" .('AlunosNegativa'==$_GET['AlunosNegativa']? ' checked':'')."><span style='width:115px; height:70px;'>Alunos Com Negativas</span>
											 </label>
											</div>																
							</div>
							
							<div style='float:left; padding:10px;' title='Distribuição de Niveis por Disciplina em Percentagem'>	
											<div class='ck-button ResultLauncher'>
												<label>
												<input type='checkbox' id='DistNivDiscPercent' name='DistNivDiscPercent' value='DistNivDiscPercent'" .('DistNivDiscPercent'==$_GET['DistNivDiscPercent']? ' checked':'')."><span style='width:115px; height:70px;'>Distr. de Niv. por Disc. Percent.</span>
											 </label>
											</div>																
							</div>
							
							<div style='float:left; padding:10px;' title='Distribuição de Niveis por Turma em Percentagem'>	
											<div class='ck-button ResultLauncher'>
												<label>
												<input type='checkbox' id='DistNivTurmaPercent' name='DistNivTurmaPercent' value='DistNivTurmaPercent'" .('DistNivTurmaPercent'==$_GET['DistNivTurmaPercent']? ' checked':'')."><span style='width:115px; height:70px;'>Distr. de Niv. por Turma Percent.</span>
											 </label>
											</div>																
							</div>
							
							<div style='float:left; padding:10px;' title='Média por Turma e Ano'>	
											<div class='ck-button ResultLauncher'>
												<label>
												<input type='checkbox' id='MedTurmaAno' name='MedTurmaAno' value='MedTurmaAno'" .('MedTurmaAno'==$_GET['MedTurmaAno']? ' checked':'')."><span style='width:115px; height:70px;'>Média por Turma e Ano</span>
											 </label>
											</div>																
							</div>
							<div style='float:left; padding:10px;' title='Aulas Assistidas por Aluno'>	
											<div class='ck-button ResultLauncher'>
												<label>
												<input type='checkbox' id='PDA' name='PDA' value='PDA'" .('PDA'==$_GET['PDA']? ' checked':'')."><span style='width:115px; height:70px;'>Aulas Assistidas por Aluno</span>
											 </label>
											</div>																
							</div>
							<div style='float:left; padding:10px;' title='Aulas Dadas Por Disciplina'>	
											<div class='ck-button ResultLauncher'>
												<label>
												<input type='checkbox' id='PDA2' name='PDA2' value='PDA2'" .('PDA2'==$_GET['PDA2']? ' checked':'')."><span style='width:115px; height:70px;'>Aulas Dadas Por Disciplina</span>
											 </label>
											</div>																
							</div>
							<div style='float:right; padding-top:5%; padding-right:15px;'>																		
							
								<input type='button' class='btn btn-primary' name='OptConc' id='OptConc' style='width:150px; height:50px;' value='Seguinte'></input>
					
							</div>
						</div>
					</div>						";
			}				
																					
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
						
						 
	}	
		
		//selects
		function showSelects ()
		{												
							
			global $sec, $ciclo23, $ciclo1;
			global $CursoModular;

			if($sec)
			{
				$nivel="sec";
			}
			elseif($ciclo23)
			{
				$nivel="23";
			}
			elseif($ciclo1)
			{
				$nivel="1";
			}			
			
			if($nivel)
			{
					echo "
				<div class='col-lg-4' id='opt' style = 'width:100%; overflow:auto;'>
				<div class='well well-lg' style='border-radius:2px; overflow:auto;'>
						<div class='form-group'>";						
					
						$getEscola = filtros_getEscola($nivel);							
						
						$getEscola_array = json_decode($getEscola);
						$getAnoLetivo = filtros_getAnoLetivo ($_GET['escola']?$_GET['escola']:$getEscola_array[0][0]);					
				
						$getAnoLetivo_array = json_decode($getAnoLetivo);						
						
				echo"
					<div style='float:left; padding:15px; ' id='div_escola'>
						<label>Escola</label>
								<script>								
								
								$(function()
								{
									$('#escola').change();
									$( '#datainicio' ).datepicker({dateFormat: 'yy-mm-dd'});
									$( '#datafim' ).datepicker({dateFormat: 'yy-mm-dd'});									
								});
								function preenche(dados, escola, anoletivo, curso,ano, periodo, turma, disciplina)
								{
									$.get('filtros_dados.php',{act: dados, escola: escola, anoletivo: anoletivo, curso: curso,ano: ano, periodo:periodo, turma:turma, nivel:'$nivel', disciplina: disciplina}).done(function(data) 
									{										
										var obj = JSON.parse(data);
										
										if(obj.length)
										{
											$( '#div_'+dados).css('display', 'block');
										}
										else
										{
											$('#div_'+dados).css('display', 'none');
										}
										var omeuid = $('#'+dados).val();
										var disciplinatext = $('#disciplina').val();
										var periodotext = $('#periodo').val();										
										
										
										
										if(( ( $('#div_periodo').css('display') == 'block') && periodotext) || (( $('#div_disciplina').css('display') == 'block') && disciplinatext))
										{
											
											$( '#div_turma').css('display', 'block');
										}
										else										
										{
											$( '#div_turma').css('display', 'none');
										}									
									
										$('#'+dados).empty().append($('<option />'));									
										
										for(var i =0; i< obj.length; i++) $('#'+dados).append($('<option />').val(obj[i][0]).text(obj[i][1]));
										
										$('#'+dados).val(omeuid);
										$('#'+dados).change();
										
										
										
									})
								}
										
										function preencheModular(escola, anoletivo, curso,ano) { 
									$.get('filtros_dados.php',{act: 'isModular', escola: escola, anoletivo: anoletivo, curso: curso,ano: ano, nivel:'$nivel'}).done(function(data) {										
										
										if(data == 'true')
										{
											preenche('disciplina',escola, anoletivo, curso,ano);
											$( '#div_periodo').css('display', 'none');											
											$( '#modular').val('true');
											$( '.cbNegativas').css('display', 'none');	
										}
										else
										{
											preenche('periodo',escola, anoletivo, curso,ano);
											$( '#div_disciplina').css('display', 'none');
											
											$( '#disciplina option:selected').removeAttr('selected');
											
											
											$( '#modular').val('false');
											$( '.cbNegativas').css('display', 'block');												
										}										
										})
										}

										function preencheDatas(dados, datas,  periodo, anoletivo )
										{	
												$.get('filtros_dados.php',{act: dados, datas: datas, periodo:periodo, anoletivo:anoletivo}).done(function(data) 
												{	
													
													var obj = JSON.parse(data);
													
													var turmatext = $('#turma').val();	
													
													if( ( $('#div_turma').css('display') == 'block') && turmatext )
													{
														$( '#div_datas').css('display', 'block');
														$( '#datainicio').val(obj[0][0]);
														$( '#datafim').val(obj[0][1]);
													}
																					
												});
												
											
											
												
										
										}
										
								</script>
						<select id='escola' name='escola' class='form-control' style='width:200px;' onchange=\"preenche('anoletivo',this.value,null) \"><option></option>". JSONtoOption($getEscola ,0,1,$_GET['escola']?$_GET['escola']:$getEscola_array[0][0]). "</select>	
					</div>
					<div style='float:left; padding:15px;' id='div_anoletivo'>																		
					<label>Ano Letivo</label>
						<select id='anoletivo' name='anoletivo' class='form-control' style='width:200px; ' onchange=\"preenche('curso', $('#escola').val(), this.value); \" ><option></option>". JSONtoOption($getAnoLetivo ,0,1,$_GET['anoletivo']?$_GET['anoletivo']:$getAnoLetivo_array[0][0]). "</select>
					</div>
					
					<div id='div_curso' style='float:left; padding:15px;'> 
						<label>Curso</label>
						<select id='curso' name='curso' class='form-control' style='width:200px;' onchange=\"preenche('ano', $('#escola').val(), $('#anoletivo').val(), this.value);\"><option></option>". JSONtoOption(filtros_getCursos ($nivel,($_GET['escola']?$_GET['escola']:$getEscola_array[0][0]),($_GET['anoletivo']?$_GET['anoletivo']:$getAnoLetivo_array[0][0])),0,1,$_GET['curso']). "</select>
					</div>
					
					<div id='div_ano' style='float:left; padding:15px; display:none;' >																	
						<label>Ano</label>											
						<select id='ano' class='form-control' style='width:200px; ' name='ano'  onchange=\"preencheModular($('#escola').val(), $('#anoletivo').val(), $('#curso').val(), this.value)\"><option></option>". JSONtoOption(filtros_getAno ($nivel,$_GET['escola'],$_GET['anoletivo'],$_GET['curso']),0,1,$_GET['ano']). "</select>
					</div>
					
					<div id='div_periodo' style='float:left; padding:15px; display:none;' >																		
						<label>Período</label>											
						<select id='periodo' name='periodo'  class='form-control ResultLauncher' style='width:200px; ' onchange=\"preenche('turma', $('#escola').val(), $('#anoletivo').val(), $('#curso').val(), $('#ano').val(), this.value)\"><option></option>". JSONtoOption(filtros_getPeriodo ($_GET['escola'],$_GET['anoletivo'],$_GET['curso'],$_GET['ano']),0,1,$_GET['periodo']). "</select>
					</div>
					
					<div id='div_disciplina' style='float:left; padding:15px; display:none;' >																		
						<label>Disciplina</label>											
						<select id='disciplina' name='disciplina'  class='form-control ResultLauncher' style='width:200px;' onchange=\"preenche('turma', $('#escola').val(), $('#anoletivo').val(), $('#curso').val(), $('#ano').val(), this.value)\" ><option></option>". JSONtoOption(filtros_getDisciplina ($_GET['escola'],$_GET['anoletivo'],$_GET['curso'],$_GET['ano']),0,1,$_GET['disciplina']). "</select>
					</div>
					
					<div id='div_turma' style='float:left; padding:15px; display:none;'>					
						<label>Turma</label>											
						<select id='turma' name='turma' class='form-control' style='width:200px;' onchange=\"preencheDatas('datas', null, $('#periodo').val(), $('#anoletivo').val())\" ><option></option>". JSONtoOption(filtros_getTurma ($_GET['escola'],$_GET['anoletivo'],$_GET['curso'],$_GET['ano'],$_GET['periodo'],$_GET['disciplina']),0,1,$_GET['turma']). "</select>
					</div>
					
					<div class='form-group' style='float:left; padding:15px; display:none' id='div_datas'>
						
						<div style='float:left; '>
							<label>Data de Início</label>
							<input class='form-control ResultLauncher' id='datainicio' name='datainicio' style='width:200px;' placeholder='aaaa-mm-dd' value='".$_GET['datainicio']."'>			
						</div>
						<div style='float:left; padding-left:30px;'>
							<label>Data de Fim</label>
							<input class='form-control ResultLauncher' id='datafim' name='datafim' style='width:200px;' placeholder='aaaa-mm-dd' value='".$_GET['datafim']."'>
						</div>
					</div>								
				</div>
				</div>
			</div>
																							
					";
			}			
			echo"</form>";			
		}		
		
		function showNivEnsino ()
		{	
			global $conn;
			$queryNivEnsino="select distinct c.ID_NivelEnsino from PED_Matriculas_AnosLectivos mal
							inner join PED_Matriculas m on mal.ID_Matricula=m.ID_Matricula and mal.DataMatricula=m.DataMatricula
							inner join ped_cursos c on m.ID_Curso=c.ID_Curso
							order by c.ID_NivelEnsino";						
					
			$resultNivEnsino=sqlsrv_query($conn,$queryNivEnsino)or die(print_r(sqlsrv_errors(),1));			
			echo "
					<div class='col-lg-4' style = 'width:100%;' style='border-radius:2px; overflow:auto;' >
						<div class='well well-lg' style='overflow:auto;'>";
						
						$jaexiste = 0;
						$pad = "";
			
					while($row=sqlsrv_fetch_array($resultNivEnsino,SQLSRV_FETCH_NUMERIC)) {					
						
							if(	$row[0]=="2")
							{
								$pad="%";								
								
								echo "
									<div style='float:left; padding-left:25".$pad.";'>	
										<input type='button' class='btn btn-outline btn-primary btn-lg' style='border-radius:2px; width:200px; height:100px;' id='niven1'  value='1º Ciclo'  onclick='javascript: window.location=\"index.php?1ciclo=1\";'></input>
									</div>";										
							}
							
							if(	($row[0]=="3" || $row[0]=="4") && $jaexiste < 1 )
							{								
								if($pad=="")
								{
									$pad="%";									
								}
								else if($pad=="%")
								{
									$pad="px";
								}
								
								$jaexiste++;
								echo "
									<div style='float:left; padding-left:25".$pad."; '>
										<input type='button' class='btn btn-outline btn-primary btn-lg' id='niven23' style='border-radius:2px;  width:200px; height:100px;' value='2º e 3º Ciclo'  onclick='javascript: window.location=\"index.php?23ciclo=1\";'></input>
									</div>	";							
							}
							
							if($row[0]=="5" )
							{								
								if($pad=="")
								{									
									$pad="%";									
								}
								else if($pad=="%")
								{
									$pad="px";
								}
								
								echo "
									<div style='float:left; padding-left:25".$pad.";'>							
										<input type='button' class='btn btn-outline btn-primary btn-lg' id='nivensec' style='border-radius:2px;  width:200px; height:100px;' value='Secundário' onclick='javascript: window.location=\"index.php?sec=1\";'></input>
									</div>	";	
								$padding++;	
							}
						}
				
			
				echo "		
				</div>
			</div>		
			";	
		}
		
		function ShowResult ()		
		{		
			global $sec, $ciclo23, $ciclo1;
			
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
			
			echo "<form method='get' id='form2' role='form' name='filtros'>";									 
											
				if($sec)
				{	
					$tipEN="sec";
				}
				elseif($ciclo23)
				{
					$tipEN="23ciclo";
				}
				elseif($ciclo1)
				{
					$tipEN="1ciclo";
				}
					
					echo"	
			<input type='hidden' name='".$tipEN."' id='".$tipEN."' value=''>
			<input type='hidden' name='cursoNome' id='cursoNome' value=''>
			<input type='hidden' name='escolaNome' id='escolaNome' value=''>
			<input type='hidden' name='turmaNome' id='turmaNome' value=''>
			<input type='hidden' name='disciplinaNome' id='disciplinaNome' value=''>
			<input type='hidden' name='modular' id='modular' value=''>
			<input type='hidden' name='periodoNome' id='periodoNome' value=''>
			
				<div class='no-print'>
					<div id='butAt' style='float:left; display:inline-block'>
						<input type='submit' class='btn btn-primary btn-lg ". ((($cb1 || $cb2 || $cb3 || $cb4 || $cb5 || $cb6 || $cb7 || $cb8 || $cb9 || $cb10 || $cb11 || $cb12 || $cb13 || $cb14 || $cb15 || $cb16 || $cb17 || $cb18 || $cb19 || $cb20))?" ":"disabled") ."' style='border-radius: 2px; width:150px;' name='Atualizar' id='Atualizar' value='Resultados' onclick='getResultados()'></input>
					</div>
					
					<div id='Exceldiv' style='float:left; display:inline-block; padding-left:1px;' title='Exportar para Excel'>															
						<img src='imagens/excel-icon.png' class='btn btn-default ". (($cb1 || $cb2 || $cb3 || $cb4 || $cb5 || $cb6 || $cb7 || $cb8 || $cb9 || $cb10 || $cb11 || $cb12 || $cb13 || $cb14 || $cb15 || $cb16 || $cb17 || $cb18 || $cb19 || $cb20)?" ":"disabled") ."' style='border-radius:2px; margin:0 auto; border:0px; padding:2px; cursor:pointer;' name='Excel' id='Excel' onclick= 'exportExcel()'></img>
					</div>	
					
					<div id='ImprimirDiv' style='float:left; display:inline-block; padding-left:1px;' title='Imprimir'>															
						<img src='imagens/print.png' class='btn btn-default ". (($cb1 || $cb2 || $cb3 || $cb4 || $cb5 || $cb6 || $cb7 || $cb8 || $cb9 || $cb10 || $cb11 || $cb12 || $cb13 || $cb14 || $cb15 || $cb16 || $cb17 || $cb18 || $cb19 || $cb20)?" ":"disabled") ."' style='border-radius: 2px; margin:0 auto; border:0px; padding:2px; cursor:pointer;' name='Imprimir' id='Imprimir' onclick='PrintWindow()'></button>
					</div>
					<div id='PDFdiv' style='float:left; display:inline-block; padding-left:1px;' title='Exportar para PDF'>
						<img src='imagens/pdf.png' class='btn btn-default ". (($cb19 || $cb20)?" ":"disabled") ."' style='border-radius: 2px; margin:0 auto; border:0px; padding:2px; cursor:pointer;' name='PDF' id='PDF' onclick='exportPDF()'></button>
					</div>																
				</div>										
			";			

			echo "<script>	
			
				var hideDiv= ',';														

				function exportExcel()
				{															
					if( $('#Excel').hasClass('disabled') )
					{ 
						return false;
					}
					else { document.getElementById('form2').action = 'exportExcel.php';
					document.getElementById('" .$tipEN."').value = '1';
					document.getElementById('modular').value;
					$('#cursoNome').val( $('#curso option:selected').text());
					$('#escolaNome').val( $('#escola option:selected').text());
					$('#turmaNome').val( $('#turma option:selected').text());
					$('#disciplinaNome').val( $('#disciplina option:selected').text());
					$('#periodoNome').val( $('#periodo option:selected').text());
						
						document.getElementById('form2').submit();}															
				}	
				
				function exportPDF()
				{															
					if( $('#PDF').hasClass('disabled') )
					{ 
						return false;
					}
					else { document.getElementById('form2').action = 'exportPDF.php';
						document.getElementById('" .$tipEN."').value = '1';
						document.getElementById('modular').value;
						$('#cursoNome').val( $('#curso option:selected').text());
						$('#escolaNome').val( $('#escola option:selected').text());
						$('#disciplinaNome').val( $('#disciplina option:selected').text());
						$('#periodoNome').val( $('#periodo option:selected').text());
						$('#turmaNome').val( $('#turma option:selected').text());
						
						document.getElementById('form2').submit();}															
				}	
				
				function getResultados()
				{				
						var datainicioSet = $('#datainicio').val();
						var datafimSet = $('#datafim').val();
						if( ($('#PDA').prop('checked') || $('#PDA2').prop('checked')) && (!datainicioSet && !datafimSet) )
							{	
								$('#Atualizar').addClass('disabled');
								$('#selects').show( 'fast', function() {});
								
								$('#modalAlert').modal('show');								
								
								$('#textoWarning').text('Preencha os campos de datas para este tipo de estatística.');						
								
								return false;
							}				
						
					else if( $('#Atualizar').hasClass('disabled') )
					{ 
					return false; }
					else { document.getElementById('form2').action = 'index.php';
						document.getElementById('" .$tipEN."').value = '1';
						document.getElementById('modular').value;
						$('.SpinningWheel').css('display', 'block');
						
						 
						document.getElementById('form2').submit();}					
				}
			
				$(function() {												
					$( '#butAt' ).click(function() {			
					
					if( !$('#Atualizar').hasClass('disabled'))
					{				
							$('#checkboxs').hide( 'fast', function() {});
							$('#selects').hide( 'fast', function() {});
							$('#nivelEnsino').hide( 'fast', function() {});
					}
					 else {
						 $('#modalAlert').modal('show');
							$('#textoWarning').text('Preencha todos os campos necessários.');
					 } 
					});
					
					$( '#ImprimirDiv' ).click(function() {
					
					if( !$('#Imprimir').hasClass('disabled'))
					{
						$('#checkboxs').hide( 'fast', function() {});					
					}
					 else
						{
							$('#modalAlert').modal('show');
							$('#textoWarning').text('Preencha todos os campos necessários.');
						} 
					});
					
					$( '#Exceldiv' ).click(function() {
					
					if( !$('#Excel').hasClass('disabled'))
					{																
						$('#checkboxs').hide( 'fast', function() {});
					  															
					 }
					 else
					 {
							$('#modalAlert').modal('show');
							$('#textoWarning').text('Preencha todos os campos necessários.');
					 } 
					});
					
					$( '#PDFdiv' ).click(function() {
					
					if( !$('#PDF').hasClass('disabled'))
					{																
						$('#checkboxs').hide( 'fast', function() {});					  															
					}
					 else
						{ 			
							$('#modalAlert').modal('show');
							$('#textoWarning').text('Funcionalidade disponível apenas para tipos de estatistíca de PDAs.');	
						} 
					});
																				
					
					$('.ResultLauncher').change( function()
					{
						afuncaodochange();
					});
									
					
					});	
	
				var PrintWindow;
				
					function PrintWindow() {
					var url = document.URL+'&hideDiv='+hideDiv;
						if( $('#Imprimir').hasClass('disabled') )
						{ 
						return false; }
						else{
						PrintWindow = window.open(url, 'PrintWindow', 'width=1075, height=768');															
						
						PrintWindow.print();
						
						}						
					}
					
					function afuncaodochange()
					{
						
						
						var disciplinaSelected = $('#disciplina').val();
						var periodoSelected = $('#periodo').val();
						var datainicioSet = $('#datainicio').val();
						var datafimSet = $('#datafim').val();
						
						
						if ( ( $('#dist').prop('checked') ||  $('#MediaDisciplina').prop('checked') || $('#MediaTurma').prop('checked') || $('#MediaAno').prop('checked') || $('#MediaNotasTurma').prop('checked') || $('#MediaDisciplinaAno').prop('checked') || $('#MediaDisciplinaTurma').prop('checked')|| $('#DistNivDiscPercent').prop('checked') || $('#DistNivTurmaPercent').prop('checked') || $('#MedTurmaAno').prop('checked') ||  $('#PDA').prop('checked') || $('#PDA2').prop('checked') ) && (disciplinaSelected) )
						{
							
							$('#Atualizar').removeClass('disabled');
							$('#Excel').removeClass('disabled');
							$('#Imprimir').removeClass('disabled');							
						}
						
						else if( ( $('#AlunosNegativa').prop('checked') || $('#PercentagemNegativasporAno').prop('checked') || $('#PercentagemNegativasporTurma').prop('checked')|| $('#NegativasporTurma').prop('checked')|| $('#NegativasporAno').prop('checked')|| $('#NegativasporDisciplina').prop('checked') ) && ( $( '#modular').val()==='true' ) )
						{				
							$('#modalAlert').modal('show');
							$('#textoWarning').text('Não existem os tipos de estatística selecionados para os cursos profissionais. Selecione novamente por favor.');	
							
							 $('#checkboxs').css('display','block');
							 $('#selects').css('display','none');
							 check = true;
							 
							$('#AlunosNegativa').prop('checked', false);
							$('#PercentagemNegativasporAno').prop('checked', false);
							$('#PercentagemNegativasporTurma').prop('checked', false);
							$('#NegativasporTurma').prop('checked', false);
							$('#NegativasporAno').prop('checked', false);
							$('#NegativasporDisciplina').prop('checked', false);														 
						}

						else if(($('#AlunosNegativa').prop('checked') || $('#dist').prop('checked') ||  $('#MediaDisciplina').prop('checked') || $('#MediaTurma').prop('checked') || $('#MediaAno').prop('checked') || $('#PercentagemNegativasporAno').prop('checked') || $('#PercentagemNegativasporTurma').prop('checked')|| $('#NegativasporTurma').prop('checked')|| $('#NegativasporAno').prop('checked')|| $('#NegativasporDisciplina').prop('checked')|| $('#MediaNotasTurma').prop('checked')|| $('#MediaDisciplinaAno').prop('checked')|| $('#MediaDisciplinaTurma').prop('checked')|| $('#DistNivDiscPercent').prop('checked') || $('#DistNivTurmaPercent').prop('checked') || $('#MedTurmaAno').prop('checked') ||  $('#PDA').prop('checked') || $('#PDA2').prop('checked') ) && (periodoSelected) )
						{
							$('#Atualizar').removeClass('disabled');
							$('#Excel').removeClass('disabled');
							$('#Imprimir').removeClass('disabled');																		
						}					

						else if (disciplinaSelected=='' && periodoSelected=='')
						{
							$('#Atualizar').addClass('disabled');																			
								$('#Imprimir').addClass('disabled');
								$('#PDF').addClass('disabled');
								$('#Excel').addClass('disabled');
						}				
						
						else
						{	
								$('#Atualizar').addClass('disabled');																			
								$('#Imprimir').addClass('disabled');
								$('#PDF').addClass('disabled');
								$('#Excel').addClass('disabled');
						}

						if ( ((datafimSet) && (!datainicioSet)) || ((!datafimSet) && (datainicioSet))  )
						{
							
							$('#Atualizar').addClass('disabled');																			
								$('#Imprimir').addClass('disabled');
								$('#PDF').addClass('disabled');
								$('#Excel').addClass('disabled');
						}
						
						else if ( (datafimSet) && (datainicioSet) )
						{							
							$('#Atualizar').removeClass('disabled');
							$('#Excel').removeClass('disabled');
							$('#Imprimir').removeClass('disabled');
							if(($('#PDA').prop('checked')) && $('#PDA2').prop('checked'))
							{
								$('#PDF').removeClass('disabled');
							}
						}
								
					}

			</script>";
		}
		
		
	?>

	