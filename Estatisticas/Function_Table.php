<?

	function Table_MedDisc ()
	{
		global $conn;
		global $query2;
		global $AnoLectivo;
		global $Ano;	
		
			$result2 = sqlsrv_query($conn,$query2);		
								
											echo "<div class='col-lg-6' style='width:100%;'>
											<div class='panel panel-default'>
												<div class='panel-heading active' id='hideMedDisc' style='cursor: pointer;'>
												<a><i class='fa fa-table fa-fw'></i> Média por Disciplina do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).", <span class='curso'></span>, ".$Ano."º  Ano<span class='periodo'></span> <span class='fa arrow'></span></a>
											</div>											
											<div class='panel-body' id='hidecb2'>";
												echo "<div class='table-responsive'>";
												echo "<table class='table table-striped table-bordered table-hover' id='TableMedDisc'>";
												echo "<thead>";
														echo"<tr>";
															echo"<th>Sigla</th>";
															echo"<th>Disciplina</th>";
															echo"<th>Média</th>";
														echo"</tr>";
													echo"</thead>";
												echo "<tbody>";						
												
												  while ($row = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC))
												  {								  		
														echo "<tr>";
														echo "<td>".utf8_encode($row['sigla'])."</td>";
														echo "<td>".utf8_encode($row['disciplina'])."</td>";
														echo "<td>".number_format(utf8_encode($row['media']),2,'.','')."</td>";
														echo"</tr>";										
													}																 
													echo "</tbody>";
												echo "</table>";
												echo "</div>     
													</div>												
												</div>										   
											</div>
											
											<script type='text/javascript'>
												var activeTab1 = true;
												
												$(document).ready(function() {
												$('#TableMedDisc').dataTable({
											
												'bFilter': false,									
											  'bPaginate': false,
												'bInfo' : false										  
											  });									  
											});											
														$(function() {
														
														$( '#hideMedDisc' ).click(function() {
														  $( '#hidecb2' ).slideToggle( 'slow', function() {
																if(this.style.display == 'none')
																{
																	hideDiv+=this.id+',';}
																else { 
																	hideDiv = hideDiv.replace(','+this.id+',',',');
																}
																});
																if (activeTab1)
																{																
																	$('#hideMedDisc').removeClass('active');
																	activeTab1 = false;
																}
																else {
																	$('#hideMedDisc').addClass('active');
																	activeTab1 = true;
																
																}
																});	
															});					
															
											</script>
											
											";	
	}	
	function Table_MedTurma ()
	{
		global $conn;
		global $query3;
		global $AnoLectivo;
		global $Ano;
		global $Periodo;	
			
		$result3 = sqlsrv_query($conn,$query3);
											echo "<div class='col-lg-6' style='width:100%;'>
										<div class='panel panel-default'>
											<div class='panel-heading active' id='hideMedTurma' style='cursor: pointer;'>
											<a><i class='fa fa-table fa-fw'></i> Média por Turma do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).",  <span class='curso'></span>, ".$Ano."º  Ano<span class='periodo'></span> <span class='fa arrow'></span></a>
											</div>											
											<div class='panel-body' id='hidecb3'>";
											echo "<div class='table-responsive'>";
											
											echo "<table class='table table-striped table-bordered table-hover' id='TableMedTurma'>";
											echo "<thead>";
													echo"<tr>";														
														echo"<th>Turma</th>";
														echo"<th>Média</th>";												
													echo"</tr>";
												echo"</thead>";
											echo "<tbody>";						
											
											  while ($row = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC))
											  {								  		
													echo "<tr>";													
													echo "<td>".utf8_encode($row['turmas'])."</td>";											
													echo "<td>".number_format(utf8_encode($row['media']),2,'.','')."</td>";									
													echo"</tr>";												
												}																 
												echo "</tbody>";
											echo "</table>";
											echo "</div>     
												</div>												
											</div>										   
										</div>
										
										<script type='text/javascript'>
												
												var activeTab2 = true;
													$(document).ready(function() {
											$('#TableMedTurma').dataTable({
											
												'bFilter': false,									
											  'bPaginate': false,
												'bInfo' : false										  
											  });									  
											});											
														$(function() {														
														$( '#hideMedTurma' ).click(function() {
														  $( '#hidecb3' ).slideToggle( 'slow', function() {	
																	if(this.style.display == 'none')
																{
																	hideDiv+=this.id+',';}
																else { 
																	hideDiv = hideDiv.replace(','+this.id+',',',');
																}
																});
																if (activeTab2)
																{																	
																	$('#hideMedTurma').removeClass('active');
																	activeTab2 = false;																	
																}
																else {
																	$('#hideMedTurma').addClass('active');
																	activeTab2 = true;																																		
																}
																});	
															});
											</script>									
										";
	}
	
	function Table_MedAno ()	
	{
		global $conn;
		global $query4;
		global $AnoLectivo;
		global $Ano;
		global $Periodo;
		
		$result4 = sqlsrv_query($conn,$query4);
			echo "<div class='col-lg-6' style='width:100%;'>
		<div class='panel panel-default'>
			<div class='panel-heading active' id='hideMedAno' style='cursor: pointer;'>
			<a><i class='fa fa-table fa-fw'></i> Média por Ano do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).",  <span class='curso'></span><span class='periodo'></span> <span class='fa arrow'></span></a>
			</div>											
			<div class='panel-body' id='hidecb4'>";
			echo "<div class='table-responsive'>";
			echo "<table class='table table-striped table-bordered table-hover' id='TableMedAno'>";
			echo "<thead>";
					echo"<tr>";
						echo"<th>Ano</th>";
						echo"<th>Média</th>";												
					echo"</tr>";
				echo"</thead>";
			echo "<tbody>";	
			
			  while ($row = sqlsrv_fetch_array($result4,SQLSRV_FETCH_ASSOC))
			  {								  		
					echo "<tr>";
					echo "<td>".utf8_encode($row['Ano'])."</td>";											
					echo "<td>".number_format(utf8_encode($row['media']),2,'.','')."</td>";											
					echo"</tr>";
				
				}																 
				echo "</tbody>";
			echo "</table>";
			echo "</div>     
				</div>												
			</div>										   
		</div>
		<script type='text/javascript'>	

						var activeTab3 = true;
							$(document).ready(function() {
			$('#TableMedAno').dataTable({
			
				'bFilter': false,									
			  'bPaginate': false,
				'bInfo' : false										  
			  });									  
			});		
						
						
						$(function() {														
						$( '#hideMedAno' ).click(function() {
						  $( '#hidecb4' ).slideToggle( 'slow', function() {	
									if(this.style.display == 'none')
								{
									hideDiv+=this.id+',';}
								else { 
									hideDiv = hideDiv.replace(','+this.id+',',',');
								}
								});
								if(activeTab3)
								{
									$('#hideMedAno').removeClass('active');
									activeTab3 = false;
								}
								else {
								$('#hideMedAno').addClass('active');
								activeTab3 = true;
									
								}
								});	
							});
			</script>
			";
	}
	
	function Table_PerNegAno ()
	{
			global $conn;
			global $query5;
			global $Ano;
			global $Periodo;
			global $AnoLectivo;
		
				$result5 = sqlsrv_query($conn,$query5);
											echo "<div class='col-lg-6' style='width:100%;'>
										<div class='panel panel-default'>
											<div class='panel-heading active' id='hidePerNegAno' style='cursor: pointer;'>
											<a><i class='fa fa-table fa-fw'></i> Percentagem de Negativas por Ano do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).",  <span class='curso'></span><span class='periodo'></span> <span class='fa arrow'></span></a>
											</div>											
											<div class='panel-body' id='hidecb5'>";
											echo "<div class='table-responsive'>";
											echo "<table class='table table-striped table-bordered table-hover' id='TablePerNegAno'>";
											echo "<thead>";
													echo"<tr>";
														echo"<th>Ano</th>";
														echo"<th>Percentagem de Negativas</th>";												
													echo"</tr>";
												echo"</thead>";
											echo "<tbody>";						
											
											  while ($row = sqlsrv_fetch_array($result5,SQLSRV_FETCH_ASSOC))
											  {								  		
													echo "<tr>";
													echo "<td>".utf8_encode($row['Ano'])."</td>";											
													echo "<td>".utf8_encode($row['percentagem'])."%</td>";
													
													echo"</tr>";
												
												}																 
												echo "</tbody>";
											echo "</table>";
											echo "</div>     
												</div>												
											</div>										   
										</div>
										<script type='text/javascript'>	
											
											var activeTab4 = true;
											
											$(document).ready(function() {
											$('#TablePerNegAno').dataTable({
											
												'bFilter': false,									
											  'bPaginate': false,
												'bInfo' : false										  
											  });									  
											});									
														$(function() {														
														$( '#hidePerNegAno' ).click(function() {
														  $( '#hidecb5' ).slideToggle( 'slow', function() {	
																	if(this.style.display == 'none')
																{
																	hideDiv+=this.id+',';}
																else { 
																	hideDiv = hideDiv.replace(','+this.id+',',',');
																}
																});
																if(activeTab4)
																{ 
																$('#hidePerNegAno').removeClass('active');
																activeTab4 = false;
																}
																
																else {
																	$('#hidePerNegAno').addClass('active');
																	activeTab4 = true;
																}
																
																
																});	
															});
											</script>";	
	}
	
	function Table_PerNegTurma ()
	{
		global $conn;
		global $query6;
		global $Ano;
		global $Periodo;
		global $AnoLectivo;		
		
		$result6 = sqlsrv_query($conn,$query6);
		
											echo "<div class='col-lg-6' style='width:100%;'>
										<div class='panel panel-default'>
											<div class='panel-heading active' id='hidePerNegTurma' style='cursor: pointer;'>
											<a><i class='fa fa-table fa-fw'></i> Percentagem de Negativas por Turma do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).",  <span class='curso'></span><span class='periodo'></span> <span class='fa arrow'></span></a>
											</div>											
											<div class='panel-body' id='hidecb6'>";
											echo "<div class='table-responsive'>";
											echo "<table class='table table-striped table-bordered table-hover' id='TablePerNegTurma'>";
											echo "<thead>";
													echo"<tr>";
														echo"<th>Turma</th>";
														echo"<th>Percentagem de Negativas</th>";												
													echo"</tr>";
												echo"</thead>";
											echo "<tbody>";						
											
											  while ($row = sqlsrv_fetch_array($result6,SQLSRV_FETCH_ASSOC))
											  {								  		
													echo "<tr>";
													echo "<td>".utf8_encode($row['turmas'])."</td>";											
													echo "<td>".utf8_encode($row['percentagem'])."%</td>";											
													echo"</tr>";										
												}																 
												echo "</tbody>";
											echo "</table>";
											echo "</div>     
												</div>												
											</div>										   
										</div>
										<script type='text/javascript'>
													var activeTab5 = true;
													
													$(document).ready(function() {
											$('#TablePerNegTurma').dataTable({
											
												'bFilter': false,									
											  'bPaginate': false,
												'bInfo' : false										  
											  });									  
											});	
											
														$(function() {														
														$( '#hidePerNegTurma' ).click(function() {
														  $( '#hidecb6' ).slideToggle( 'slow', function() {	
																	if(this.style.display == 'none')
																{
																	hideDiv+=this.id+',';}
																else { 
																	hideDiv = hideDiv.replace(','+this.id+',',',');
																}
																});
																
																if(activeTab5)
																{
																	$('#hidePerNegTurma').removeClass('active');
																	activeTab5 = false;
																}
																else{
																	$('#hidePerNegTurma').addClass('active');
																	activeTab5 = true;
																}
																
																});	
															});
															
															
											</script>
										";	
	}
	
	function Table_NegTurma ()
	{
		global $conn;
		global $query7;
		global $Ano;
		global $Periodo;
		global $AnoLectivo;
		
		$result7 = sqlsrv_query($conn,$query7);
											echo "<div class='col-lg-6' style='width:100%;'>
										<div class='panel panel-default'>
											<div class='panel-heading active' id='hideNegTurma' style='cursor: pointer;'>
											<a><i class='fa fa-table fa-fw'></i> Negativas por Turma do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).", <span class='curso'></span><span class='periodo'></span><span class='fa arrow'></span></a>
											</div>											
											<div class='panel-body' id='hidecb7'>";
											echo "<div class='table-responsive'>";
											echo "<table class='table table-striped table-bordered table-hover' id='TableNegTurma'>";
											echo "<thead>";
													echo"<tr>";
														echo"<th>Turma</th>";
														echo"<th>Nº de Negativas</th>";												
													echo"</tr>";
												echo"</thead>";
											echo "<tbody>";						
											
											  while ($row = sqlsrv_fetch_array($result7,SQLSRV_FETCH_ASSOC))
											  {			  		
													echo "<tr>";
													echo "<td>".utf8_encode($row['turmas'])."</td>";											
													echo "<td>".utf8_encode($row['Negativas'])."</td>";											
													echo"</tr>";										
												}																 
												echo "</tbody>";
											echo "</table>";
											echo "</div>     
												</div>												
											</div>										   
										</div>
										<script type='text/javascript'>
														var activeTab6 = true;
														
															$(document).ready(function() {
											$('#TableNegTurma').dataTable({
											
												'bFilter': false,									
											  'bPaginate': false,
												'bInfo' : false										  
											  });									  
											});	
										
														$(function() {														
														$( '#hideNegTurma' ).click(function() {
														  $( '#hidecb7' ).slideToggle( 'slow', function() {	
																	if(this.style.display == 'none')
																{
																	hideDiv+=this.id+',';}
																else { 
																	hideDiv = hideDiv.replace(','+this.id+',',',');
																}
																});
																if(activeTab6)
																{	
																	$('#hideNegTurma').removeClass('active');
																	activeTab6 = false;
																}
																else {
																		$('#hideNegTurma').addClass('active');
																	activeTab6 = true;														
																}
																});	
															});
											</script>";
	
	}
	
	function Table_NegANo ()
	{		
		global $conn;
		global $query8;
		global $Ano;
		global $Periodo;
		global $AnoLectivo;		
		
			
					$result8 = sqlsrv_query($conn,$query8);
										echo "<div class='col-lg-6' style='width:100%;'>
										<div class='panel panel-default'>
											<div class='panel-heading active' id='hideNegAno' style='cursor: pointer;'>
											<a><i class='fa fa-table fa-fw'></i> Negativas por Ano do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).",  <span class='curso'></span><span class='periodo'></span> <span class='fa arrow'></span></a>
											</div>											
											<div class='panel-body' id='hidecb8'>";
											echo "<div class='table-responsive'>";
											echo "<table class='table table-striped table-bordered table-hover' id='TableNegAno'>";
											echo "<thead>";
													echo"<tr>";
														echo"<th>Ano</th>";
														echo"<th>Nº de Negativas</th>";												
													echo"</tr>";
												echo"</thead>";
											echo "<tbody>";						
											
											  while ($row = sqlsrv_fetch_array($result8,SQLSRV_FETCH_ASSOC))
											  {								  		
													echo "<tr>";
													echo "<td>".utf8_encode($row['Ano'])."º Ano</td>";											
													echo "<td>".utf8_encode($row['Negativas'])."</td>";
													
													echo"</tr>";
												
												}																 
												echo "</tbody>";
											echo "</table>";
											echo "</div>     
												</div>												
											</div>										   
										</div>
										<script type='text/javascript'>
													var activeTab7 = true;
																$(document).ready(function() {
											$('#TableNegAno').dataTable({
											
												'bFilter': false,									
											  'bPaginate': false,
												'bInfo' : false										  
											  });									  
											});												
														$(function() {														
														$( '#hideNegAno' ).click(function() {
														  $( '#hidecb8' ).slideToggle( 'slow', function() {	
																	if(this.style.display == 'none')
																{
																	hideDiv+=this.id+',';}
																else { 
																	hideDiv = hideDiv.replace(','+this.id+',',',');
																}
																});
																if(activeTab7)
																{
																	$('#hideNegAno').removeClass('active');
																	activeTab7 = false;}
																else {
																	$('#hideNegAno').addClass('active');
																	activeTab7 = true;
																	}
																
																});	
															});
											</script>
											";
	}
	
	function Table_NegDisc ()
	{
		global $conn;
		global $query9;
		global $Ano;
		global $Periodo;
		global $AnoLectivo;
	
		$result9 = sqlsrv_query($conn,$query9);
											echo "<div class='col-lg-6' style='width:100%;'>
										<div class='panel panel-default'>
											<div class='panel-heading active' id='hideNegDisc' style='cursor: pointer;'>
											<a><i class='fa fa-table fa-fw'></i> Negativas por Disciplina do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).", <span class='curso'></span>, ".$Ano."º Ano<span class='periodo'></span> <span class='fa arrow'></span></a>
											</div>											
											<div class='panel-body' id='hidecb9'>";
											echo "<div class='table-responsive'>";
											echo "<table class='table table-striped table-bordered table-hover' id='TableNegDisc'>";
											echo "<thead>";
													echo"<tr>";
														echo"<th>Sigla</th>";
														echo"<th>Disciplina</th>";
														echo"<th>Nº de Negativas</th>";												
													echo"</tr>";
												echo"</thead>";
											echo "<tbody>";						
											
											  while ($row = sqlsrv_fetch_array($result9,SQLSRV_FETCH_ASSOC))
											  {						  		
													echo "<tr>";
													echo "<td>".utf8_encode($row['sigla'])."</td>";											
													echo "<td>".utf8_encode($row['disciplina'])."</td>";											
													echo "<td>".utf8_encode($row['Negativas'])."</td>";											
													echo"</tr>";										
												}																 
												echo "</tbody>";
											echo "</table>";
											echo "</div>     
												</div>												
											</div>										   
										</div>
										<script type='text/javascript'>	

													var activeTab8 = true;
													
															$(document).ready(function() {
											$('#TableNegDisc').dataTable({
											
												'bFilter': false,									
											  'bPaginate': false,
												'bInfo' : false										  
											  });									  
											});	

										
														$(function() {														
														$( '#hideNegDisc' ).click(function() {
																
														  $( '#hidecb9' ).slideToggle( 'slow', function() {	

																	if(this.style.display == 'none')
																{
																	hideDiv+=this.id+',';}
																else { 
																	hideDiv = hideDiv.replace(','+this.id+',',',');
																}
																});
																if(activeTab8)
																{
																	$('#hideNegDisc').removeClass('active');
																	activeTab8 = false;
																	
																}	
																else {
																
																		$('#hideNegDisc').addClass('active');
																	activeTab8 = true;
																
																}
																});	
															});
											</script>										
										";
	
	}
	
	function Table_MedNotaTurma ()
	{
		global $conn;
		global $query10;
		global $Ano;
		global $AnoLectivo;
		global $Periodo;
		
		
		$result10= sqlsrv_query($conn,$query10);
											while ($row = sqlsrv_fetch_array($result10,SQLSRV_FETCH_ASSOC))
											  {
													$notas [$row ['nota']] = intval($row ['nota']);
											  
													$idturma [$row['id']] = $row['turma'];
													
													$DisciplinaOcorrencias [$row['id']][intval($row['nota'])] = $row ['ocorrencias'];											
												}									
												
													$notas= range(min ($notas), max($notas));
		
		
											echo "<div class='col-lg-6' style='width:100%;'>
										<div class='panel panel-default'>
											<div class='panel-heading active' id='hideMedNotasTurma' style='cursor: pointer;'>
											<a><i class='fa fa-table fa-fw'></i> Níveis por Turma do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).",  <span class='curso'></span>, ".$Ano."º Ano<span class='periodo'></span> <span class='fa arrow'></span></a>
											</div>											
											<div class='panel-body' id='hidecb10'>";
											echo "<div class='table-responsive'>";											
											echo "<table class='table table-striped table-bordered table-hover' id='TableNivTur'>";
											echo "<thead>";
												echo"<tr>";
													echo"<th></th>";
													echo"<th colspan = '".count($notas)."' style='text-align:center;'>Níveis</th>";
												echo"<tr>";
												echo"</tr>";
													echo"<tr>";
														echo"<th>Turma</th>";		  					
													
													foreach ($notas as $kn=>$vn)											
													{
														echo"<th style='text-align:center;'>".$vn."</th>";										
													}									
												
													foreach ($idturma as $kd=>$vd)									
														{	
															echo "<tr>";	
															echo "<td style='vertical-align:middle'>".utf8_encode($vd)."</td>";//disciplina
														
															foreach ($notas as $kn=>$vn)
															{
																if($DisciplinaOcorrencias[$kd][$vn]=="")
																{																	
																	$DisciplinaOcorrencias[$kd][$vn] = "0";														
																}
																echo "<td style='text-align:center;'>".$DisciplinaOcorrencias[$kd][$vn]."</td>";																
															}															
															echo "</tr>";								
														}
											echo "</tbody>";
											echo "</table>";
											echo "</div>     
												</div>												
											</div>										   
										</div>
										<script type='text/javascript'>	
			
													var activeTab9 = true;
										
														$(function() {														
														$( '#hideMedNotasTurma' ).click(function() {
														  $( '#hidecb10' ).slideToggle( 'slow', function() {

																	if(this.style.display == 'none')
																{
																	hideDiv+=this.id+',';}
																else { 
																	hideDiv = hideDiv.replace(','+this.id+',',',');
																}
																});
																
																if(activeTab9)
																{
																	$('#hideMedNotasTurma').removeClass('active');
																	activeTab9 = false;
																	
																}
																else{
																		$('#hideMedNotasTurma').addClass('active');
																		activeTab9 = true;
																
																}
																});	
															});
											</script>	
											";		
	}
	
	function Table_MedDiscAno ()
	{
		global $conn;
		global $query11;
		global $AnoLectivo;
		global $Ano;
		global $Periodo;
	
		
		$result11 = sqlsrv_query($conn,$query11);
		
		 while ($row = sqlsrv_fetch_array($result11,SQLSRV_FETCH_ASSOC))
												  {	
													$siglas [$row['id_d']]	= $row ['sigla'];
														
													 $iddisciplina11 [$row['id_d']] = $row['disciplina'];
							   
														$DisciplinaAno [$row['id_d']][$row['Ano']] = $row ['media'];
														
														$AnoHeader [$row['Ano']] = $row['Ano'];
													}			
													
												echo "<div class='col-lg-6' style='width:100%;'>
											<div class='panel panel-default'>
												<div class='panel-heading active' id='MedDisciplinaAno' style='cursor: pointer;'>
											<a><i class='fa fa-table fa-fw'></i> Média por Disciplina e Ano do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).",  <span class='curso'></span><span class='periodo'></span> <span class='fa arrow'></span></a>
											</div>											
											<div class='panel-body' id='hidecb11'>";
												echo "<div class='table-responsive'>";
												echo "<table class='table table-striped table-bordered table-hover' id='TableMedDiscAno'>";
												echo "<thead>";
														echo"<tr>";
															echo "<th colspan='2'></th>";
															echo"<th colspan='".count($AnoHeader)."' style='text-align:center;'>Ano</th>";
														echo"</tr>";
														echo"<tr>";
															echo"<th>Sigla</th>";
															echo"<th>Disciplina</th>";		

														foreach($AnoHeader as $kAno=>$vAno)
														{
															echo"<th style='text-align:center;'>".$vAno."º</th>";
														}
														echo "</tr>";
														echo "</thead>";
														echo "<tbody>";
														foreach ($iddisciplina11 as $kd=>$vd)
														{
															echo"<tr>";
															echo "<td style='vertical-align:middle'>".utf8_encode($siglas[$kd])."</td>";//disciplina													
															echo "<td style='vertical-align:middle'>".utf8_encode($vd)."</td>";//disciplina													
															
															foreach ($AnoHeader as $ka=>$va)
																{	
																	if($DisciplinaAno [$kd][$ka] == '')
																	{
																		$DisciplinaAno [$kd][$ka] = "-";
																		echo "<td style='text-align:center;'>".$DisciplinaAno [$kd][$ka]."</td>";//Media
																	}
																	else{
																		echo "<td style='text-align:center;'>".number_format($DisciplinaAno [$kd][$ka],2,'.','')."</td>";//Media
																	}
															
																																																													
																}										
															echo "</tr>";
														}			  
																					
												echo "</tbody>";
											echo "</table>";
											echo "</div>     
												</div>												
											</div>										   
										</div>
										<script type='text/javascript'>	
													var activeTab10 = true;
													
														$(function() {														
														$( '#MedDisciplinaAno' ).click(function() {
														  $( '#hidecb11' ).slideToggle( 'slow', function() {

																	if(this.style.display == 'none')
																{
																	hideDiv+=this.id+',';}
																else { 
																	hideDiv = hideDiv.replace(','+this.id+',',',');
																}
																});
																if(activeTab10)
																{
																	$('#MedDisciplinaAno').removeClass('active');
																	activeTab10 = false;
																}
																
																else {
																	$('#MedDisciplinaAno').addClass('active');
																	activeTab10 = true;
																}
																
																$('#MedDisciplinaAno').removeClass('active');
																});	
															});
											</script>
										";	
	}
	
	function Table_MedDiscTurma ()
	{
		global $conn;
		global $query12;
		global $AnoLectivo;
		global $Ano;
		global $Periodo;
		$result12 = sqlsrv_query($conn,$query12);
		
		 while ($row = sqlsrv_fetch_array($result12,SQLSRV_FETCH_ASSOC))
											  {	
												$sigla [$row['id_d']] = $row ['sigla'];
												
												 $disciplinas12Table [$row['id_d']] = $row['disciplina'];
						   
												$DisciplinaTurma [$row['id_d']][$row['id_t']] = $row ['media'];
												
												$turmaHeader [$row['id_t']] = $row['turma'];
												}			
												
											echo "<div class='col-lg-6' style='width:100%;'>
											<div class='panel panel-default'>
											<div class='panel-heading active' id='MedDisciplinaTurma' style='cursor: pointer;'>
											<a><i class='fa fa-table fa-fw'></i> Média por Disciplina e Turma do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).", <span class='curso'></span>, ".$Ano."º Ano<span class='periodo'></span> <span class='fa arrow'></span></a>
											</div>											
											<div class='panel-body' id='hidecb12'>";
											echo "<div class='table-responsive'>";
											echo "<table class='table table-striped table-bordered table-hover' id='TableMedDiscTurma'>";
											echo "<thead>";
													echo"<tr>";
														echo "<td colspan='2'></td>";
														echo"<th colspan='".count($turmaHeader)."' style='text-align:center;'>Turma</th>";
													echo"</tr>";
													echo"<tr>";													
													echo"<th>Sigla</th>";
														echo"<th>Disciplina</th>";										
											
													foreach ($turmaHeader as $kid_t=> $vturma)
													{
														echo "<th style='text-align:center;'>".utf8_encode($vturma)."</th>";
													}													
													echo "</tr>";
													echo "</thead>";
													echo "</tbody>";
													foreach ($disciplinas12Table as $kdisc=>$vdisc)
													{
														echo "<tr>";
															echo "<td style='vertical-align:middle'>".utf8_encode($sigla[$kdisc])."</td>";//disciplina	
															echo "<td style='vertical-align:middle'>".utf8_encode($vdisc)."</td>";//disciplina												
														
															foreach ($turmaHeader as $kid_t=> $vturma)
															{
																echo "<td style='text-align:center;'>".number_format($DisciplinaTurma [$kdisc][$kid_t],2,'.','')."</td>";
																
															}										
														echo "</tr>";
													}
											echo "</tbody>";
											echo "</table>";
											echo "</div>     
												</div>												
											</div>										   
										</div>
										
										<script type='text/javascript'>	
											var activeTab11 = true;
																	
														$(function() {														
														$( '#MedDisciplinaTurma' ).click(function() {
														  $( '#hidecb12' ).slideToggle( 'slow', function() {
																		if(this.style.display == 'none')
																{
																	hideDiv+=this.id+',';}
																else { 
																	hideDiv = hideDiv.replace(','+this.id+',',',');
																}
																});
																if(activeTab11)
																{
																	$('#MedDisciplinaTurma').removeClass('active');
																	activeTab11 = false;
																	}
																	
																
																else {
																	$('#MedDisciplinaTurma').addClass('active');
																	activeTab11 = true;
																}
																
																});	
															});
											</script>
											";
	}
	
	function Table_DistNotas ()
	{
		global $conn;
		global $query13;
		global $Ano;
		global $Periodo;
		global $curso;
		global $AnoLectivo;
		global $sec;
		
		
		$result13= sqlsrv_query($conn,$query13);
		
										while ($row = sqlsrv_fetch_array($result13,SQLSRV_FETCH_ASSOC))
											  {	
													$sigla [$row['id_d']] = $row['sigla'];
													
													$notas13 [$row ['nota']] = intval($row ['nota']);
											  
													$disciplinasDist [$row['id_d']] = $row['Disciplina'];
													
													$Disciplina13 [$row['id_d']][intval($row['nota'])] = $row ['ocorrencias'];
												}
												
												if($sec)
													{$notas13= range (((min ($notas13))), max($notas13));}
													else {$notas13= range (min ($notas13), max($notas13));}
		
											echo "<div class='col-lg-6' style='width:100%;'>
												<div class='panel panel-default'>
													<div class='panel-heading active' id='hidedist' style='cursor: pointer;'>
														<a><i class='fa fa-table fa-fw'></i> Número de Ocorrências de Níveis Por Disciplina do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).", <span class='curso'></span>, ".$Ano."º  Ano<span class='periodo'></span> <span class='fa arrow'></span></a>
													</div>											
												<div class='panel-body' id='hidecb13'>";
											echo "<div class='table-responsive'>";											
											echo "<table class='table table-striped table-bordered table-hover' id='TableDistNotas'>";
											echo "<thead>";
													echo"<tr>";
														echo"<th colspan='2'></th>";
														
														echo"<th colspan='".count($notas13)."' style='text-align:center;'>Níveis</th>";
													echo"</tr>";
													echo"<tr>";
														echo"<th>Sigla</th>";
														echo"<th>Disciplina</th>";												
													
													foreach ($notas13 as $kn=>$vn)											
													{
														echo"<th style='text-align:center;'>".$vn."</th>";										
													}									
												
													foreach ($disciplinasDist as $kd=>$vd)									
														{	
															echo "<tr>";
															echo "<td style='vertical-align:middle'>".utf8_encode($sigla[$kd])."</td>";//disciplina	
															echo "<td style='vertical-align:middle'>".utf8_encode($vd)."</td>";//disciplina
														
															foreach ($notas13 as $kn=>$vn)
															{
																if($Disciplina13[$kd][$vn]=="")
																{
																	
																	$Disciplina13[$kd][$vn] = "0";														
																}
																	echo "<td style='text-align:center;'>".$Disciplina13[$kd][$vn]."</td>";															
																	
															}															
															echo "</tr>";								
														}
														echo "</tbody>";
											echo "</table>";
											echo "</div>     
												</div>												
											</div>										   
										</div>
										
										<script type='text/javascript'>		
													
													var activeTab12 = true;
											
														$(function() {														
														$( '#hidedist' ).click(function() {
														  $( '#hidecb13' ).slideToggle( 'slow', function() {
																	if(this.style.display == 'none')
																{
																	hideDiv+=this.id+',';}
																else { 
																	hideDiv = hideDiv.replace(','+this.id+',',',');
																}
																});
																if (activeTab12)
																{
																	$('#hidedist').removeClass('active');
																	activeTab12 = false;
																}
																else {											

																	$('#hidedist').addClass('active');
																	activeTab12 = true;
																}															
																
																});	
															});
											</script>
										";								
	}
	
	function Table_3Neg ()
	{
		global $conn;
		global $query14;
		global $Ano;
		global $Periodo;
		global $curso;
		global $AnoLectivo;
		$result14 = sqlsrv_query($conn,$query14);
									echo "<div class='col-lg-6' style='width:100%;'>
										<div class='panel panel-default'>
											<div class='panel-heading active' id='hide3negativas' style='cursor: pointer;'>												
												<a><i class='fa fa-table fa-fw'></i> Alunos Com 3 ou Mais Negativas do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).", ".$Ano."º  Ano<span class='periodo'></span> <span class='fa arrow'></span></a>
											</div>																									
											<div class='panel-body' id='hidecb14'>";
										echo "<div class='table-responsive'>";
											echo "<table class='table table-striped table-bordered table-hover' id='Table3Neg'>";
												echo "<thead>";
													echo"<tr>";
													
																echo"<th>Turma</th>";
																echo"<th>Nome do Aluno</th>";
																
															echo"</tr>";
												echo"</thead>";
										echo "<tbody>";						
									
									  while ($row = sqlsrv_fetch_array($result14,SQLSRV_FETCH_ASSOC))
									  {						  		
											echo "<tr>";
											echo "<td>".utf8_encode($row['turma'])."</td>";
											echo "<td>".utf8_encode(ucwords(strtolower($row['Aluno'])))."</td>";											
											echo"</tr>";										
										}	
										echo "</tbody>";
													echo "</table>";
													echo "</div>     
													</div>												
												</div>										   
											</div>								
											
										<script type='text/javascript'>	
												var active = true;
												
													$(document).ready(function() {
											$('#Table3Neg').dataTable({	

											'language': {
											'sSearch': '<i class=\"fa fa-search\"></i>  ',		
												searchPlaceholder: 'Procurar... ',
												'sZeroRecords': 'Sem Resultados'
											},	
											
																				  
										  'bPaginate': false,
											'bInfo' : false										  
										  });									  
										});											
														$(function() {														
														$( '#hide3negativas' ).click(function() {
														  $( '#hidecb14' ).slideToggle( 'slow', function() {
																	if(this.style.display == 'none')
																{
																	hideDiv+=this.id+',';}
																else { 
																	hideDiv = hideDiv.replace(','+this.id+',',',');
																}
																});
																if (active)
																{	
																	$('#hide3negativas').removeClass('active');
																	active = false;
																}
																else {
																	$('#hide3negativas').addClass('active');
																	active = true;																	
																}
																
																});	
															});
											</script>
										";								
	}
	
	function Table_PortMat ()
	{
		global $conn;
		global $query15;
		global $Ano;
		global $Periodo;
		global $curso;
		global $AnoLectivo;
		$result15 = sqlsrv_query($conn,$query15);
		
							echo "<div class='col-lg-6' style='width:100%;'>
										<div class='panel panel-default'>											
											<div class='panel-heading active' id='hideportmat' style='cursor: pointer;'>
												<a><i class='fa fa-table fa-fw'></i> Alunos Com Negativa a Português e Matemática do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).", ".$Ano."º  Ano<span class='periodo'></span> <span class='fa arrow'></span></a>
											</div>													
										<div class='panel-body' id='hidecb15'>";
										echo "<div class='table-responsive'>";
											echo "<table class='table table-striped table-bordered table-hover' id='TablePortMat'>";
												echo "<thead>";
													echo"<tr>";
														echo"<th>Turma</th>";
														echo"<th>Nome do Aluno</th>";														
													echo"</tr>";
												echo"</thead>";
										echo "<tbody>";											
									  while ($row = sqlsrv_fetch_array($result15,SQLSRV_FETCH_ASSOC))
									  {						  		
											echo "<tr>";
											echo "<td>".utf8_encode($row['Turma'])."</td>";
											echo "<td>".utf8_encode(ucwords(strtolower($row['Aluno'])))."</td>";											
											echo"</tr>";									
										}																 
										echo "</tbody>";
													echo "</table>";
													echo "</div>     
													</div>												
												</div>										   
											</div>
									<script type='text/javascript'>	
													
													var active = true;
													
													$(document).ready(function() {
											$('#TablePortMat').dataTable({	

											'language': {
											'sSearch': '<i class=\"fa fa-search\"></i>  ',		
												searchPlaceholder: 'Procurar... ',
												'sZeroRecords': 'Sem Resultados'
											},	
											
																				  
										  'bPaginate': false,
											'bInfo' : false										  
										  });									  
										});											
														$(function() {														
														$( '#hideportmat' ).click(function() {
														  $( '#hidecb15' ).slideToggle( 'slow', function() {
																		if(this.style.display == 'none')
																{
																	hideDiv+=this.id+',';}
																else { 
																	hideDiv = hideDiv.replace(','+this.id+',',',');
																}
																});
																if(active)
																{
																	$('#hideportmat').removeClass('active');
																	active = false;
																	}
																else
																{
																	$('#hideportmat').addClass('active');
																	active = true;																
																}
																$('#hideportmat').removeClass('active');
																});	
															});
											</script>
										";									
	}
	
	function Table_DistNivDiscPercent ()
	{
		global $conn;
		global $queryDistDisciplinaPercent;
		global $Ano;
		global $Periodo;
		global $curso;
		global $AnoLectivo;	
		
		

		$result16= sqlsrv_query($conn,$queryDistDisciplinaPercent) or die (print_r(sqlsrv_errors ()));

		 while ($row = sqlsrv_fetch_array($result16,SQLSRV_FETCH_ASSOC))
											  {		
													$sigla [$row['id_d']] = $row['sigla'];	
													
													$notas16 [$row ['nota']] = intval($row ['nota']);
											  
													$DistDiscPercent [$row['id_d']] = $row['Disciplina'];
													
													$Disciplina16 [$row['id_d']][intval($row['nota'])] = $row ['percentagem'];
												}	
											
											ksort ($notas16);										
													
											$notas16 = range (min ($notas16), max($notas16));		
			
											echo "<div class='col-lg-6' style='width:100%;'>
												<div class='panel panel-default'>
													<div class='panel-heading active' id='hidedistdisciplinapercent' style='cursor: pointer;'>
														<a><i class='fa fa-table fa-fw'></i> Distribuição de Níveis por Disciplina em Percentagem do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).", <span class='curso'></span>, ".$Ano."º Ano<span class='periodo'></span> <span class='fa arrow'></span></a>
													</div>											
												<div class='panel-body' id='hidecb16'>";
											echo "<div class='table-responsive'>";											
											echo "<table class='table table-striped table-bordered table-hover' id='TableDistNivDiscPercent'>";
											echo "<thead>";
													echo"<tr>";
														echo"<th colspan='2'></th>";				
														echo"<th colspan='".count($notas16)."' style='text-align:center;'>Níveis</th>";				
													echo"</tr>";
													echo"<tr>";
														echo"<th>Sigla</th>";				
														echo"<th>Disciplina</th>";				
												
											 												
													
													foreach ($notas16 as $kn=>$vn)											
													{
														echo"<th style='text-align:center;'>".$vn."</th>";										
													}									
												
													foreach ($DistDiscPercent as $kd=>$vd)									
														{	
															echo "<tr>";
															echo "<td style='vertical-align:middle'>".utf8_encode($sigla[$kd])."</td>";//disciplina	
															echo "<td style='vertical-align:middle'>".utf8_encode($vd)."</td>";//disciplina
														
															foreach ($notas16 as $kn=>$vn)
															{
																if($Disciplina16[$kd][$vn]=="")
																{																	
																	$Disciplina16[$kd][$vn] = "0";														
																}
																	echo "<td style='text-align:center;'>".$Disciplina16[$kd][$vn]." %</td>";															
																	
															}															
															echo "</tr>";								
														}
														echo "</tbody>";
											echo "</table>";
											echo "</div>     
												</div>												
											</div>										   
										</div>
										
										<script type='text/javascript'>											
													var activeTab13 = true;	
														$(function() {														
														$( '#hidedistdisciplinapercent' ).click(function() {
														  $( '#hidecb16' ).slideToggle( 'slow', function() {
																	if(this.style.display == 'none')
																{
																	hideDiv+=this.id+',';}
																else { 
																	hideDiv = hideDiv.replace(','+this.id+',',',');
																}
																});
																if(activeTab13)
																{
																	$('#hidedistdisciplinapercent').removeClass('active');
																	activeTab13 = false;
																}
																else {
																	$('#hidedistdisciplinapercent').addClass('active');
																	activeTab13 = true;
																}
																
																
																});	
															});
											</script>
										";							
	}
	
	function Table_DistNivTurmaPercent ()
	{
		global $conn;
		global $queryDistTurmaPercent;
		global $Ano;
		global $Periodo;
		global $curso;
		global $AnoLectivo;
		
		
		
		
		$result17= sqlsrv_query($conn,$queryDistTurmaPercent);		
											
											  while ($row = sqlsrv_fetch_array($result17,SQLSRV_FETCH_ASSOC))
											  {
													
													$notas17 [$row ['nota']] = intval($row ['nota']);
											  
													$DistTurmaPercent [$row['id_t']] = $row['turma'];
													
													$Turmas17 [$row['id_t']][intval($row['nota'])] = $row ['percentagem'];
												}	
												
											ksort ($notas17);	
											$notas17 = range (min ($notas17), max($notas17));	
											
											echo "<div class='col-lg-6' style='width:100%;'>
												<div class='panel panel-default'>
													<div class='panel-heading active' id='hidedistturmapercent' style='cursor: pointer;'>
														<a><i class='fa fa-table fa-fw'></i> Distribuição de Níveis por Turma em Percentagem do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).", <span class='curso'></span>, ".$Ano."º Ano<span class='periodo'></span> <span class='fa arrow'></span></a>
													</div>											
												<div class='panel-body' id='hidecb17'>";
											echo "<div class='table-responsive'>";											
											echo "<table class='table table-striped table-bordered table-hover' id='TableDistNivTurmaPercent'>";
											echo "<thead>";
													echo "<tr>";
														echo"<th></th>";				
														echo"<th colspan='".count($notas17)."' style='text-align:center;'>Níveis</th>";				
													echo "</tr>";
													echo"<tr>";
														echo"<th>Turma</th>";																					
													
													foreach ($notas17 as $kn=>$vn)											
													{
														echo"<th style='text-align:center;'>".$vn."</th>";										
													}									
												
													foreach ($DistTurmaPercent as $kd=>$vd)									
														{	
															echo "<tr>";	
															echo "<td style='vertical-align:middle'>".utf8_encode($vd)."</td>";//disciplina
														
															foreach ($notas17 as $kn=>$vn)
															{
																if($Turmas17[$kd][$vn]=="")
																{
																	
																	$Turmas17[$kd][$vn] = "0";														
																}
																	echo "<td style='text-align:center;'>".$Turmas17[$kd][$vn]." %</td>";															
																	
															}															
															echo "</tr>";								
														}
														echo "</tbody>";
											echo "</table>";
											echo "</div>     
												</div>												
											</div>										   
										</div>
										
										<script type='text/javascript'>	
												 var activeTab14= true;
													$(function() {														
														$( '#hidedistturmapercent' ).click(function() {
														  $( '#hidecb17' ).slideToggle( 'slow', function() {
																	if(this.style.display == 'none')
																{
																	hideDiv+=this.id+',';}
																else { 
																	hideDiv = hideDiv.replace(','+this.id+',',',');
																}
																});
																if(activeTab14)
																{
																	$('#hidedistturmapercent').removeClass('active');
																	activeTab14 = false;
																	}
																else {
																	$('#hidedistturmapercent').addClass('active');
																	activeTab14 = true;
																}	
																	
																});	
															});
											</script>
										";								
	}
	
	function Table_AlunNeg ()
	{
		global $conn;
		global $query1;
		global $AnoLectivo;
		global $Ano;
		global $Periodo;
		global $sec;	
		
		$result1 = sqlsrv_query($conn,$query1);		
		
		echo "<div class='col-lg-6' style='width:100%;'>
		<div class='panel panel-default'>											
		<div class='panel-heading active' id='hideAlunosNeg' style='cursor: pointer;'>
			<a><i class='fa fa-table fa-fw'></i> Alunos com Negativa do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).", <span class='curso'></span><span class='periodo'></span> <span class='fa arrow'></span></a>
		</div>													
		<div class='panel-body' id='hidecb1'>";
		echo "<div class='table-responsive' >";
		echo "<table class='table  table-bordered' id='TableAlunNeg'>";
		echo "<thead>";
				echo "<tr>";
				echo "<div class='bgcolor3mais' style='float:left;'> <img src='Imagens\m3.png'> <= 3 negativas</div>";
				
				if(!$sec)//fazer desaparecer a legenda no caso do secundário
				{
					echo "<div class='bgcolor3maisPM' style='float:left; padding-left:10px;'> <img src='Imagens\pm3mais.png'> <= 3 negativas e negativa a Português e Matemática</div>";
					echo "<div class='bgcolor3mais' style='float:left; padding-left:10px;'> <img src='Imagens\pm.png'> Negativa a Português e Matemática</div>";
				}								
				
				echo "</tr>";
				echo"<tr>";
					echo"<th style='width:8%'>Ano</th>";
					echo"<th style='width:8%'>Turma</th>";
					echo"<th style='width:15%'>Nome do Aluno</th>";																												
					echo"<th>Disciplina(s) com Negativa</th>";
					echo "<th style='width:15%'>Nº de Negativas</th>";
					echo "<th style='width:2%'></th>";														
				echo"</tr>";
			echo"</thead>";
		echo "<tbody>";						
				
		  while ($row = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC))
			{				
				$alunosNegativa [$row['id_a']] [$row['id_t']] [$row['Ano']] [] = $row['id_d'];													
				$turma [$row['id_t']] = $row['turma'];													
				$nome [$row['id_a']] = $row ['Aluno'];													
				$ENEB [$row['id_d']] = $row ['eneb'];
				$disciplinasAluNEG [$row['id_d']] = $row ['disciplina']." (".intval($row['nota']).")";
				$AnoID [$row ['Ano']] = $row ['Ano']; 	
			}															
			
			foreach ($alunosNegativa as $id_a=>$id_t)
			{			
				
				foreach ($id_t as $id_turma=>$AnoArray)
				{
					foreach($AnoArray as $id_Ano => $id_d)
				   {
						$arrayDisciplinas = array ();
					foreach ($id_d as $idDisciplina => $DisciplinaID)
						$arrayDisciplinas [] = $disciplinasAluNEG[$DisciplinaID];
																					
						if(count($id_d) >= 3 ) 
						{	
							if((in_array (array_search('B901',$ENEB),$id_d) && in_array (array_search('B912',$ENEB),$id_d) )|| (in_array (array_search('B612',$ENEB),$id_d) && in_array (array_search('B601',$ENEB),$id_d)) || (in_array (array_search('B412',$ENEB),$id_d) && in_array (array_search('B401',$ENEB),$id_d)))
								{	
									echo "</tr>
											<tr title='Alunos com mais de 3 ou mais negativas e Negativa a Português e Matemática'>
											<td>
												".utf8_encode(ucwords(strtolower($AnoID[$id_Ano])))."
											</td>
											<td>
												".utf8_encode(ucwords(strtolower($turma[$id_turma])))."
											</td>
											<td>
												".utf8_encode(ucwords(strtolower($nome[$id_a])))."
											</td>																												
											<td>".utf8_encode(implode (", ", $arrayDisciplinas))."</td>";													
									echo "<td style='text-align:center;'>".count($id_d)."</td>
									<td class='bgcolor3maisPM' style=' background-color: #FF3333;'></td>";}
						
							else{echo "</tr>
								<tr title='Alunos com mais de 3 ou mais negativas'>
								<td>
								".utf8_encode(ucwords(strtolower($AnoID[$id_Ano])))."
								</td>
								<td>
									".utf8_encode(ucwords(strtolower($turma[$id_turma])))."
								</td>
								<td>
									".utf8_encode(ucwords(strtolower($nome[$id_a])))."
								</td>																												
								<td>".utf8_encode(implode (", ", $arrayDisciplinas))."</td>";													
								echo "<td style='text-align:center;'>".count($id_d)."</td>
									<td class='bgcolor3mais' style='background-color:#FF9999;'></td>";}
									
																				
						}
						elseif (in_array (array_search('B901',$ENEB),$id_d) && in_array (array_search('B912',$ENEB),$id_d) || in_array (array_search('B612',$ENEB),$id_d) && in_array (array_search('B601',$ENEB),$id_d) || (in_array (array_search('B412',$ENEB),$id_d) && in_array (array_search('B401',$ENEB),$id_d))){													
								echo "</tr>
								<tr title='Alunos com Negativa a Português e Matemática'>
								<td>
								".utf8_encode(ucwords(strtolower($AnoID[$id_Ano])))."
								</td>
								<td>
									".utf8_encode(ucwords(strtolower($turma[$id_turma])))."
								</td>
								<td>
									".utf8_encode(ucwords(strtolower($nome[$id_a])))."
								</td>																												
								<td>".utf8_encode(implode (", ", $arrayDisciplinas))."</td>";													
								echo "<td style='text-align:center;'>".count($id_d)."</td>
									<td class='bgcolorPM' style='background-color:#FF9933;'></td>";}
								
						else {echo "</tr>
								<tr>
								<td>
								".utf8_encode(ucwords(strtolower($AnoID[$id_Ano])))."
								</td>
								<td>
									".utf8_encode(ucwords(strtolower($turma[$id_turma])))."
								</td>
								<td>
									".utf8_encode(ucwords(strtolower($nome[$id_a])))."
								</td>																												
								<td>".utf8_encode(implode (", ", $arrayDisciplinas))."</td>";													
								echo "<td style='text-align:center;'>".count($id_d)."</td>
								<td></td>";}		
												
					}	
				}				
			}
				echo "</tbody>";
				echo "</table>";													
				echo "</div>     
				</div>												
			</div>										   
		</div>											
				
		<script type='text/javascript'>
        
		var activeTab15 = true;

					$(document).ready(function() {
											 var aluntable = $('#TableAlunNeg').dataTable({	
											
											'language': {
											'sSearch': '<i class=\"fa fa-search\"></i>  ',		
												searchPlaceholder: 'Procurar... ',
												'sZeroRecords': 'Sem Resultados',												 
											},											
											columnDefs: [
												{ orderable: false, 'targets': -1 }
												],									  
										  'bPaginate': false,
											'bInfo' : false										  
										  });
										 								
												
										});										
											$(function() {									
														
														$( '#hideAlunosNeg' ).click(function() {
														
														  $( '#hidecb1' ).slideToggle( 'slow', function() {
															if(this.style.display == 'none')
																{hideDiv+=this.id+',';}
																else { 
																	hideDiv = hideDiv.replace(','+this.id+',',',');
																}															
															});
																if(activeTab15)
																{ $('#hideAlunosNeg').removeClass('active');
																	
																activeTab15 = false;}
																else{ $('#hideAlunosNeg').addClass('active');
																
																activeTab15 = true;}
																
																});
															 
															});								
									</script>";									
	}
	
	function Table_PDA ()
	{
		global $conn;
		global $queryPDAAluno_Turma;
		global $Ano;
		global $Periodo;
		global $curso;
		global $AnoLectivo;	
		global $Turma;
		global $sec;
		global $modular;
		global $DataInicio;
		global $DataFim;	
					
		if($modular =="true")
		{			
			$Previstas="Horas Previstas";
			$Dadas="Horas Dadas";
		}
		else
		{
			$Previstas="Aulas Previstas";
			$Dadas="Aulas Dadas";	
		}
		
		
		$resultPDAAluno_Turma = sqlsrv_query($conn,$queryPDAAluno_Turma)or die(print_r(sqlsrv_errors()));
		
		$ResultTemRegistosPDA1 = sqlsrv_has_rows ($resultPDAAluno_Turma);
		
		if($ResultTemRegistosPDA1)
		{	
			while ($row = sqlsrv_fetch_array($resultPDAAluno_Turma,SQLSRV_FETCH_ASSOC))
			{			
				$DisciplinaDesignacao [$row['id_d']] = $row['abv'];
				
				$disciplinasPDA [$row['id_d']] = array($row['Sigla'], $row['AulasPrevistas'], $row['AulasDadas']);
				
				$dadosAluno [$row['id_a']] = array($row['NumeroAluno'], $row['NomeAluno'], $row['BI'] );	

				$AlunosFaltas [$row['id_a']] [$row['id_d']] = $row['Faltas'];				
			}	
			
			$numDisciplinas = count($disciplinasPDA);
			$numDisciplinas = 2*$numDisciplinas;
			
			echo "<div class='col-lg-6' style='width:100%;'>											
									<div class='panel panel-default'>											
													<div class='panel-heading active' id='hidecbPDAHead' style='cursor: pointer;'>
														<a><i class='fa fa-table fa-fw'></i> Aulas Assistidas por Aluno do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).", <span class='curso'></span><span class='periodo'></span>, Turma <span class='Turma'></span>, entre ".$DataInicio." e ".$DataFim." <span class='fa arrow'></span></a>
													</div>													
												<div class='panel-body' id='hidecbPDA' >
													<div class='table-responsive'>
														<table class='table  table-bordered' id='TablePDA'";
														
														if(!$sec)
														{echo "style='font-size:11px;'";}													
															echo">
															<thead>
																<tr>
																	<th colspan=3 rowspan=4></th>												
																	<th colspan=".$numDisciplinas." style='text-align:center; width:80%'>Disciplinas</th>												
																</tr>
																<tr>
																	";
													
																	foreach ($disciplinasPDA as $kidd=>$vSigla)
																	{												
																		echo "<th colspan=2 style='text-align:center;' title='".utf8_encode($DisciplinaDesignacao[$kidd])."'>".$vSigla [0]."</th>";								
																	}
																echo "</tr>";	
																echo "<tr>";
																
																foreach ($disciplinasPDA as $kidd)
																{
																	echo "<th style='text-align:center;' title='".$Previstas."'>P</th>";
																	echo "<th style='text-align:center;' title='".$Dadas."'>D</th>";
																}
												
																echo"</tr>";											
																echo "<tr>";
																		foreach ($disciplinasPDA as $kidd=>$valor)	
																		{
																			echo "<th style='text-align:center;'>".$valor[1]."</th>";
																			echo "<th style='text-align:center;'>".$valor[2]."</th>";
																		}											
																		echo "											
																		</tr>
																		<tr>
																			<th style='width:2%;'>#</th>
																			<th style='width:8%'>Nome</th>
																			<th style='width:8%'>BI</th>
																			
																			<th style='text-align:center;' colspan=".(2*($numDisciplinas)).">Assistidas</th>	
																		</tr>	
															</thead>
															<tbody>";
													
															foreach ($dadosAluno as $idAluno=>$dados)
															{
																echo"<tr>";
																	echo"<td style='text-align:center;'>".$dados[0]."</td>";
																	echo"<td>".utf8_encode($dados[1])."</td>";
																	echo"<td  style='text-align:center;'>".$dados[2]."</td>";
																																
																	foreach ($disciplinasPDA as $keyidd=>$dadosDisciplina)
																	{
																		if(isset($AlunosFaltas[$idAluno][$keyidd]))																		
																		{echo"<td colspan=2 style='text-align:center;'>".($dadosDisciplina[2]-$AlunosFaltas[$idAluno][$keyidd])."</td>";}
																		else {echo"<td colspan=2 style='text-align:center;'>Não Inscrito</td>";}
																	}																
																echo"</tr>";															
															}
												
												echo"</tbody>		
														</table>													
														</div>     
														</div>												
													</div>										   
												</div>";
												
									echo "<script type='text/javascript'>												
																								
														var activeTabPDA = true;											
												
														$(function() {														
															$( '#hidecbPDAHead' ).click(function() {
															  $( '#hidecbPDA' ).slideToggle( 'slow', function() {
																		if(this.style.display == 'none')
																	{
																		hideDiv+=this.id+',';}
																	else { 
																		hideDiv = hideDiv.replace(','+this.id+',',',');
																	}
																	});
																	if (activeTabPDA)
																	{
																		$('#hidecbPDAHead').removeClass('active');
																		activeTabPDA = false;
																	}
																	else
																	{	
																		$('#hidecbPDAHead').addClass('active');
																		activeTabPDA = true;
																	}															
																	
																	});	
																});														
												</script>			";
		}
		else 
		{
			echo "<script>
						$(function() { $('#modalAlert').modal('show')});
						$('#textoWarning').text('É necessário carregar os PDAs em Pedagógico -> Actividade Pedagógica -> Turmas -> Aulas Previstas e Dadas ');						
				</script>";	
		}
											
	}

	
	function Table_PDA_Disciplina_Turma ()
	{
		global $conn;
		global $queryPDADisciplina_Turma;
		global $Ano;
		global $Periodo;
		global $curso;
		global $AnoLectivo;	
		global $Turma;
		global $sec;	
		global $modular;
		global $DataInicio;
		global $DataFim;
		
		
		if($modular =="true")
		{			
			$HeaderPrevistas="Horas Previstas";
			$HeaderDadas="Horas Dadas";
			$PercentDadas="% de Horas Dadas";
		}
		else
		{
			$HeaderPrevistas="Aulas Previstas";
			$HeaderDadas="Aulas Dadas";	
			$PercentDadas="% de Aulas Dadas";
		}
				
		$resultPDADisciplina_Turma = sqlsrv_query($conn,$queryPDADisciplina_Turma);
		
		$ResultTemRegistosPDA2 = sqlsrv_has_rows ($resultPDADisciplina_Turma);
		
		if($ResultTemRegistosPDA2)
		{
			while ($row = sqlsrv_fetch_array($resultPDADisciplina_Turma,SQLSRV_FETCH_ASSOC))
		{
			$DisciplinasTurmas [$row['id_d']] [$row['Turma']]  = array ($row['AulasDadas'],$row['AulasPrevistas']);
			
			$Disciplinas [$row['id_d']] = $row['Sigla'];		
		}	
		
		echo "<div class='col-lg-6' style='width:100%;'>											
								<div class='panel panel-default'>											
												<div class='panel-heading active' id='hidecbPDAHead2' style='cursor: pointer;'>
													<a><i class='fa fa-table fa-fw'></i> Aulas Dadas por Disciplina do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).", <span class='curso'></span>, ".$Ano."º Ano<span class='periodo'></span>, entre ".$DataInicio." e ".$DataFim." <span class='fa arrow'></span></a>
												</div>													
											<div class='panel-body' id='hidecbPDA2' >
												<div class='table-responsive'>
													<table class='table  table-bordered' id='TablePDA2'>											
														<thead>
															<tr>
																<th style='text-align:center;'>Disciplina</th>
																<th style='text-align:center;'>Turma</th>
																<th style='text-align:center;' >".$HeaderPrevistas."</th>
																<th style='text-align:center;' >".$HeaderDadas."</th>
																<th style='text-align:center;'>".$PercentDadas."</th>
															</tr>
														</thead>		
														<tbody>";
														foreach ($DisciplinasTurmas as $kIDD=>$DT)
														{	
															echo "<tr >";
																echo "<td rowspan=".count($DT)." style='text-align:center; vertical-align:middle;'>".$Disciplinas[$kIDD]."</td>";//Disciplina
																foreach ($DT as $kT=>$Val)
																{																	
																	echo "<td style='text-align:center;'>".utf8_encode($kT)."</td>";//Turma
																	echo "<td style='text-align:center;'>".$Val[0]."</td>";//AulasPrevistas
																	echo "<td style='text-align:center;'>".$Val[1]."</td>";//AulasDadas
																	$AulasDadas=intval($Val[1]);
																	$AulasPrevistas=intval($Val[0]);
																	$conta = ((($AulasDadas)*100)/($AulasPrevistas+0.000000000001));
																	echo "<td style='text-align:center;'>".number_format($conta,1,'.','')."%</td>";//Percentagem de aulas dadas
																	
																	echo "</tr >";
																	echo "<tr >";
																}
																echo "</tr >";
															
														}										
											echo"</tbody>		
													</table>													
													</div>     
													</div>												
												</div>										   
											</div>";
											
								echo "<script type='text/javascript'>											
													
													var activeTabPDA2 = true;											
											
													$(function() {														
														$( '#hidecbPDAHead2' ).click(function() {
														  $( '#hidecbPDA2' ).slideToggle( 'slow', function() {
																	if(this.style.display == 'none')
																{
																	hideDiv+=this.id+',';}
																else { 
																	hideDiv = hideDiv.replace(','+this.id+',',',');
																}
																});
																if (activeTabPDA2)
																{
																	$('#hidecbPDAHead2').removeClass('active');
																	activeTabPDA2 = false;
																}
																else {											

																	$('#hidecbPDAHead2').addClass('active');
																	activeTabPDA2 = true;
																}															
																
																});	
															});
											</script>			";
		} 		
			else 
			{
				echo "<script>
						$(function() { $('#modalAlert').modal('show')});
						$('#textoWarning').text('É necessário carregar os PDAs em Pedagógico -> Actividade Pedagógica -> Turmas -> Aulas Previstas e Dadas ');						
				</script>";
			}										
	}	
	
	
	function Table_MedTurmaAno ()
	{
		global $conn;
		global $queryMedTurmaAno;
		global $AnoLectivo;		
		global $Periodo;
		
		
		
		$resultMedTurmaAno = sqlsrv_query($conn,$queryMedTurmaAno);
											echo "<div class='col-lg-6' style='width:100%;'>
										<div class='panel panel-default'>
											<div class='panel-heading active' id='hideMedTurmaA' style='cursor: pointer;'>
											<a><i class='fa fa-table fa-fw'></i> Média por Turma e Ano do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).", <span class='curso'></span><span class='periodo'></span><span class='fa arrow'></span></a>
											</div>											
											<div class='panel-body' id='hidecbMedTurmaAno'>";
											echo "<div class='table-responsive'>";
											
											echo "<table class='table table-striped table-bordered table-hover' id='TableMedTurmaAno'>";
											echo "<thead>";
													echo"<tr>";														
														echo"<th>Ano</th>";
														echo"<th>Turma</th>";
														echo"<th>Média</th>";												
													echo"</tr>";
												echo"</thead>";
											echo "<tbody>";						
											
											  while ($row = sqlsrv_fetch_array($resultMedTurmaAno,SQLSRV_FETCH_ASSOC))
											  {								  		
													echo "<tr>";
													echo "<td>".utf8_encode($row['Ano'])."</td>";
													echo "<td>".utf8_encode($row['turmas'])."</td>";											
													echo "<td>".number_format(utf8_encode($row['media']),2,'.','')."</td>";													
													echo"</tr>";
												
												}																 
												echo "</tbody>";
											echo "</table>";
											echo "</div>     
												</div>												
											</div>										   
										</div>
										
										<script type='text/javascript'>
											var activeTab16 = true;
											
													$(document).ready(function() {
											$('#TableMedTurmaAno').dataTable({
											
												'bFilter': false,									
											  'bPaginate': false,
												'bInfo' : false										  
											  });									  
											});											
														$(function() {														
														$( '#hideMedTurmaA' ).click(function() {
														  $( '#hidecbMedTurmaAno' ).slideToggle( 'slow', function() {
																	if(this.style.display == 'none')
																{
																	hideDiv+=this.id+',';}
																else { 
																	hideDiv = hideDiv.replace(','+this.id+',',',');
																}
																});
																if(activeTab16)
																{
																		$('#hideMedTurmaA').removeClass('active');
																		activeTab16 = false;
																		}
																else { $('#hideMedTurmaA').addClass('active');
																		activeTab16 = true;}		
																});	
															});
											</script>									
										";
	}
	
	?>