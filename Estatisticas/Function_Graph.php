<?
	function graph_MedDisc ()	
	{
		global $conn;
		global $query2;
		global $Ano;
		global $Periodo;
		global $AnoLectivo;
	
		echo "
	           <div class='col-lg-6' style='width:100%;'>
                    <div class='panel panel-default' >
                        <div class='panel-heading active' id='hideMedDiscGraph' style='cursor: pointer;'>
						<a><i class='fa fa-bar-chart-o fa-fw'></i> Média por Disciplina do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).", <span class='curso'></span>, ".$Ano."º  Ano<span class='periodo'></span> <span class='fa arrow'></span></a>
						</div>                        
                        <div class='panel-body' id='cb2hide'>
                            <div class='flot-chart'>							
                                <div class='flot-chart-content' id='flot-bar-chart2'></div>
				            </div>
		                </div>                    
                    </div>                    
                </div> "; 
				
		$i=0;
		$result2 = sqlsrv_query($conn,$query2);
		
		while ($row = sqlsrv_fetch_array( $result2, SQLSRV_FETCH_ASSOC))
			{
				
				$dataset2[] = array($i,floatval($row['media']));
				$labels2 [] = array ($i,utf8_encode($row['sigla']));
				$i++;
			}		
        
		echo "			
					
		<script type='text/javascript'>	
			var activeGraph2 = true;
			$(function() {
				$( '#hideMedDiscGraph' ).click(function() {
							  $( '#cb2hide' ).slideToggle( 'slow', function() {	
									if(this.style.display == 'none')
									{
										hideDiv+=this.id+',';}
									else { 
										hideDiv = hideDiv.replace(','+this.id+',',',');
									}
									});
									if(activeGraph2)
									{
										$('#hideMedDiscGraph').removeClass('active');
										activeGraph2 = false;
									}
									else {
										$('#hideMedDiscGraph').addClass('active');
										activeGraph2 = true;
										
									}	
									});
									
				var barOptions = {
					series: {
						bars: {
							show: true,
							align: 'center',
							barWidth: 0.29,
							
							order: 1,
							
							showNumbers: true,
							numbers : {								
								show: true,
								xAlign: function(x) { return x; },							
							},

						}
					},
					xaxis: {
					mode: 'categories',
					autoscaleMargin: 0.02,
					ticks: ".json_encode($labels2)."
					
					},
					grid: {
						hoverable: true,
						
					},
					legend: {
						show: true
					},
					tooltip: true,
					tooltipOpts: {
						content:  function (label, x , y)
						{
							return ('Média:'+y);
						}
					}
       			};
			   var barData = {
				
					barWidth: 0.25,
					label: 'Média',
					data: ".json_encode($dataset2)."
				
					};			
				 $.plot($('#flot-bar-chart2'), [barData], barOptions);
				
				
				
					
				});
					</script>
				";
	}
	
	function graph_MedTurma ()
	{
		global $conn;
		global $query3;
		global $Ano;
		global $Periodo;
		global $AnoLectivo;
		global $queryMedAno;
		
		// die($query3);
		echo "    
                <div class='col-lg-6' style='width:100%;'>
                    <div class='panel panel-default'>                        
                        <div class='panel-heading active' id='hideMedTurmaGraph' style='cursor: pointer;'>
							<a><i class='fa fa-bar-chart-o fa-fw'></i> Média por Turma do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).",  <span class='curso'></span>, ".$Ano."º  Ano<span class='periodo'></span> <span class='fa arrow'></span></a>
						</div>                        
                        <div class='panel-body' id='cb3hide'>
                            <div class='flot-chart'>							
                                <div class='flot-chart-content' id='flot-bar-chart3'></div>
				            </div>
		                </div>                    
                    </div>                    
                </div>		
        ";
				
		$resultMedAno = sqlsrv_query($conn,$queryMedAno);
				while($rowMedAno = sqlsrv_fetch_array($resultMedAno, SQLSRV_FETCH_ASSOC))
				{
					$MedAno = $rowMedAno['mediaAno'];
				}				
				
		$i=1;
		$result3 = sqlsrv_query($conn,$query3);
		while ($row = sqlsrv_fetch_array( $result3, SQLSRV_FETCH_ASSOC))
			{
				$dataset3[] = array($i,floatval($row['media']));
				$labels3 [] = array ($i,utf8_encode($row['turmas']));				
				$i++;
			}
			
			$countArray = count($labels3);
			for ($itMed=0; $itMed < $countArray+2; $itMed++ )
					{
						$lineMedAno [] = array ($itMed,$MedAno);				
					}	
			
		echo "			
					
		<script type='text/javascript'>	
			var activeGraph3 = true;
		
			$(function() {
				$( '#hideMedTurmaGraph' ).click(function() {
							  $( '#cb3hide' ).slideToggle( 'slow', function() {	
										if(this.style.display == 'none')
																{
																	hideDiv+=this.id+',';}
																else { 
																	hideDiv = hideDiv.replace(','+this.id+',',',');
																}
									});
									if(activeGraph3)
									{
										$('#hideMedTurmaGraph').removeClass('active');
										activeGraph3 = false;
									}
									
									else {
										$('#hideMedTurmaGraph').addClass('active');
										activeGraph3 = true;
									
									}
										
									});
									
				var barOptions = {
					
					xaxis: {
					
					ticks: ".json_encode($labels3)."
					},
					grid: {
						hoverable: true
					},
					legend: {
						show: true
					},
					tooltip: true,
					tooltipOpts: {
						content:  function (label, x , y)
						{
							return ('Média:'+y);
						}
					}
       			};
			   var barData = [{
					
						bars: {
								show: true,
								align: 'center',
								barWidth: 0.3,
								
								
								showNumbers: true,
								numbers : {
									show: true,
									xAlign: function(x) { return x; },
									 
								},															
							},
							
					yaxis: 1,
					
					label: 'Média da Turma',
					data: ".json_encode($dataset3)."
				
					},
					{							
							points: { symbol: 'circle', fillColor: '#e6e6e6', show: true },	
							yaxis: 1,
							color: '#A0A0A0',
							lines: {show:true},
							label: 'Média do Ano (".$MedAno.")',
							data: ".json_encode($lineMedAno).",						
							
						},
						];			
				$.plot($('#flot-bar-chart3'), barData, barOptions);
					
				});
					</script>
				";
	}
	
	function graph_MedAno ()
	{
		global $conn;
		global $query4;
		global $Ano;
		global $Periodo;
		global $AnoLectivo;
		
				echo "   
                 <div class='col-lg-6' style='width:100%;'>
                    <div class='panel panel-default'>
                        <div class='panel-heading active' id='hideMedAnoGraph' style='cursor: pointer;'>
							<a><i class='fa fa-bar-chart-o fa-fw'></i> Média por Ano do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).", <span class='curso'></span><span class='periodo'></span><span class='fa arrow' ></a>
						</div>                        
                        <div class='panel-body' id='cb4hide'>
                            <div class='flot-chart'>							
                                <div class='flot-chart-content' id='flot-bar-chart4'></div>
				            </div>
		                </div>                    
                    </div>                    
                </div>	                             
            ";	

		$i=0;
		$result4 = sqlsrv_query($conn,$query4);
		while ($row = sqlsrv_fetch_array( $result4, SQLSRV_FETCH_ASSOC))
			{
				$dataset4[] = array($i,floatval($row['media']));
				$labels4 [] = array ($i,utf8_encode($row['Ano'])."º Ano");
				$i++;
			}
			
			echo "	<script type='text/javascript'>	
				var activeGraph4 = true;
			
			$(function() {
				$( '#hideMedAnoGraph' ).click(function() {
							  $( '#cb4hide' ).slideToggle( 'slow', function() {	
											if(this.style.display == 'none')
																{
																	hideDiv+=this.id+',';}
																else { 
																	hideDiv = hideDiv.replace(','+this.id+',',',');
																}
									});
									if(activeGraph4)
									{
										$('#hideMedAnoGraph').removeClass('active ');
										activeGraph4 = false;
									}
									else {
									$('#hideMedAnoGraph').addClass('active ');
										activeGraph4 = true;
									
									}
									
									});
									
									var barOptions = {
					series: {
						bars: {
							show: true,
							align: 'center',
							barWidth: 0.2,
							
							
							showNumbers: true,
							numbers : {
								show: true,
								xAlign: function(x) { return x; }, // shows numbers at the top of the bar
								xOffset: 0 // pixel offset so numbers are not right up on the edge of the top of the bar        
							},

						}
					},
					xaxis: {
					autoscaleMargin: 0.75,
					ticks: ".json_encode($labels4)."
					},
					grid: {
						hoverable: true
					},
					legend: {
						show: true
					},
					tooltip: true,
					tooltipOpts: {
						content:  function (label, x , y)
						{
							return ('Média:'+y);
						}
					}					
       			};
			   var barData = {
			   
					
					label: 'Média',
					data: ".json_encode($dataset4)."
				
					};			
				$.plot($('#flot-bar-chart4'), [barData], barOptions);
										});
					</script>
					";		
			}
	
	function graph_NegTurma ()
	{
		global $conn;
		global $query7;
		global $Ano;
		global $Periodo;
		global $AnoLectivo;
		
		echo "
				<div class='col-lg-6' style='width:100%;'>
                    <div class='panel panel-default'>
                        <div class='panel-heading active' id='hideNegTurmaGraph' style='cursor: pointer;'>
							<a><i class='fa fa-bar-chart-o fa-fw'></i> Negativas por Turma do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).", <span class='curso'></span><span class='periodo'></span> <span class='fa arrow'></span></a>
						</div>                       
                        <div class='panel-body' id='cb7hide'>
                            <div class='flot-chart'>							
                                <div class='flot-chart-content' id='flot-bar-chart7'></div>
				            </div>
		                </div>                    
                    </div>                    
                </div>	";
				
			$i=0;
			$result7 = sqlsrv_query($conn,$query7);
			while ($row = sqlsrv_fetch_array( $result7, SQLSRV_FETCH_ASSOC))
				{
					$dataset7[] = array($i,intval($row['Negativas']));
					$labels7 [] = array ($i,utf8_encode($row['turmas']));
					$i++;
				}
				
				echo "<script type='text/javascript'>	
				
				var activeGraph5 = true;
			$(function() {
				$( '#hideNegTurmaGraph' ).click(function() {
							  $( '#cb7hide' ).slideToggle( 'slow', function() {
									if(this.style.display == 'none')
																{
																	hideDiv+=this.id+',';}
																else { 
																	hideDiv = hideDiv.replace(','+this.id+',',',');
																}
							  
									});
									if(activeGraph5)
									{
										$('#hideNegTurmaGraph').removeClass('active ');
										activeGraph5 = false;
									}
									else {
										$('#hideNegTurmaGraph').addClass('active ');
										activeGraph5 = true;
										
									}
									
									});							
				
				var barOptions = {
					series: {
						bars: {
							show: true,
							align: 'center',
							barWidth: 0.2,
							
							
							showNumbers: true,
							numbers : {
								show: true,
								xAlign: function(x) { return x; },
							},

						}
					},
					xaxis: {
					autoscaleMargin: 0.05,
					ticks: ".json_encode($labels7)."
					},
					grid: {
						hoverable: true
					},
					legend: {
						show: true,
						
					},
					tooltip: true,
					tooltipOpts: {
						content:  function (label, x , y)
						{
							return ('Nº de Negativas:'+y);
						}
					}
       			};
			   var barData = {
					
					label: 'Nº de Negativas',
					data: ".json_encode($dataset7)."
				
					};			
				$.plot($('#flot-bar-chart7'), [barData], barOptions);
				
				});
				</script>";
	}
	
	function graph_NegAno ()	
	{
		global $conn;
		global $query8;
		global $Ano;
		global $Periodo;
		global $AnoLectivo;
		
		echo "
	<div class='col-lg-6' style='width:100%;'>
                    <div class='panel panel-default'>
                        <div class='panel-heading active' id='hideNegAnoGraph' style='cursor: pointer;'>
							<a><i class='fa fa-bar-chart-o fa-fw'></i> Negativas por Ano do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).",  <span class='curso'></span><span class='periodo'></span> <span class='fa arrow'></span></a>
						</div>                        
                        <div class='panel-body' id='cb8hide'>
                            <div class='flot-chart'>							
                                <div class='flot-chart-content' id='flot-bar-chart8'></div>
				            </div>
		                </div>                    
                    </div>                    
                </div>	"; 
				
		$i=0;
			$result8 = sqlsrv_query($conn,$query8);
			while ($row = sqlsrv_fetch_array( $result8, SQLSRV_FETCH_ASSOC))
				{
					$dataset8[] = array($i,intval($row['Negativas']));
					$labels8 [] = array ($i,utf8_encode($row['Ano'])."º Ano");
					$i++;
				}		
        
		echo "			
					
		<script type='text/javascript'>	
		var activeGraph6 = true;
			$(function() {
				$( '#hideNegAnoGraph' ).click(function() {
							  $( '#cb8hide' ).slideToggle( 'slow', function() {	
										if(this.style.display == 'none')
																{
																	hideDiv+=this.id+',';}
																else { 
																	hideDiv = hideDiv.replace(','+this.id+',',',');
																}
									});
									if(activeGraph6)
									{
										$('#hideNegAnoGraph').removeClass('active ');
										activeGraph6 = false;
									}
									else {
										$('#hideNegAnoGraph').addClass('active ');
										activeGraph6 = true;
									}
									
									});
									
				var barOptions = {
						series: {
							bars: {
								show: true,
								align: 'center',
								barWidth: 0.25,
								
								
								showNumbers: true,
								numbers : {
									show: true,
									xAlign: function(x) { return x; }, // shows numbers at the top of the bar
									xOffset: 0 // pixel offset so numbers are not right up on the edge of the top of the bar  
									
								},

							}
						},
						xaxis: {
						autoscaleMargin: 0.5,
						ticks: ".json_encode($labels8)."
						},
						grid: {
							hoverable: true
						},
						legend: {
							show: true
						},
						tooltip: true,
						tooltipOpts: {
							content:  function (label, x , y)
							{
								return ('Nº de Negativas:'+y);
							}
						}
					};
				   var barData = {
						
						label: 'Nº de Negativas',
						data: ".json_encode($dataset8)."
					
						};			
				$.plot($('#flot-bar-chart8'), [barData], barOptions);
					
				});
					</script>
				";
	}
	
	function graph_NegDisc ()	
	{
		global $conn;
		global $query9;
		global $Ano;
		global $Periodo;
		global $AnoLectivo;
		
		echo "
				<div class='col-lg-6' style='width:100%;'>
                    <div class='panel panel-default' >
                       <div class='panel-heading active' id='hideNegDiscGraph' style='cursor: pointer;'>
							<a><i class='fa fa-bar-chart-o fa-fw'></i> Negativas por Disciplina do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).", <span class='curso'></span>, ".$Ano."º Ano<span class='periodo'></span> <span class='fa arrow'></span></a>
						</div>                        
                        <div class='panel-body' id='cb9hide'>
                            <div class='flot-chart'>							
                                <div class='flot-chart-content' id='flot-bar-chart9'></div>
				            </div>
		                </div>                    
                    </div>                    
                </div>";
				
		$i=0;
			$result9 = sqlsrv_query($conn,$query9);
			while ($row = sqlsrv_fetch_array( $result9, SQLSRV_FETCH_ASSOC))
				{					
					$dataset9[] = array($i,intval($row['Negativas']));
					$labels9 [] = array ($i,utf8_encode($row['sigla']));
					$i++;
				}		
        
		echo "			
					
		<script type='text/javascript'>	
		var activeGraph7 = true;
			$(function() {
				$( '#hideNegDiscGraph' ).click(function() {
							  $( '#cb9hide' ).slideToggle( 'slow', function() {		
										if(this.style.display == 'none')
																{
																	hideDiv+=this.id+',';}
																else { 
																	hideDiv = hideDiv.replace(','+this.id+',',',');
																}
									});
									if (activeGraph7)
									{
										$('#hideNegDiscGraph').removeClass('active ');
										activeGraph7 = false;
									}
									else {
										$('#hideNegDiscGraph').addClass('active ');
										activeGraph7 = true;
									}
									
									});
									
				var barOptions = {
						series: {
							bars: {
								show: true,
							align: 'center',
							barWidth: 0.15,
														
							showNumbers: true,
							numbers : {
								show: true,
								xAlign: function(x) { return x; },
							},
							
							}
						},
						xaxis: {
						autoscaleMargin: 0.05,
						ticks: ".json_encode($labels9)."
						},
						grid: {
							hoverable: true
						},
						legend: {
							show: true
						},
						tooltip: true,
						tooltipOpts: {
							content:  function (label, x , y)
							{
								return ('Nº de Negativas:'+y);
							}
						}
					};
				   var barData = {
						
						label: 'Nº de Negativas',
						data: ".json_encode($dataset9)."
					
						};			
					$.plot($('#flot-bar-chart9'), [barData], barOptions);
					
				});
					</script>
				";
	}

	function graph_MedNotasTurma ()	
	{
		global $conn;
		global $query10;
		global $Ano;
		global $Periodo;
		global $AnoLectivo;
		
		echo "
	<div class='col-lg-6' style='width:100%;'>
                    <div class='panel panel-default' >
                        <div class='panel-heading active' id='hideMedNotTurmaGraph' style='cursor: pointer;'>
							<a><i class='fa fa-bar-chart-o fa-fw'></i> Níveis por Turma do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).",  <span class='curso'></span>, ".$Ano."º Ano<span class='periodo'></span> <span class='fa arrow'></span></a>
						</div>                        
                        <div class='panel-body' id='cb10hide'>
                            <div class='flot-chart'>							
                                <div class='flot-chart-content' id='flot-bar-chart10'></div>
				            </div>
		                </div>                    
                    </div>                    
                </div>	";
				
			
				$result10 = sqlsrv_query($conn,$query10);
			while ($row = sqlsrv_fetch_array($result10, SQLSRV_FETCH_ASSOC))
				{	
					
					$dataset10[$row['nota']][$row['id']]= utf8_encode($row['ocorrencias']);
					$label10[$row['id']]= utf8_encode($row['turma']);					
				}
				
				$i=0;
				ksort ($dataset10);
				foreach ($dataset10 as $k=>$v)
					{
						$labels10 [] = array($i,intval($k));//notas
						foreach ($v as $k1=>$v1)
						{
							$data10 [$k1] [] = array($i,intval($v1));//ocorrencias
						}
					$i++;			
					}		
        
		echo "			
					
		<script type='text/javascript'>	
		var activeGraph8 = true;
		
			$(function() {
				$( '#hideMedNotTurmaGraph' ).click(function() {
							  $( '#cb10hide' ).slideToggle( 'slow', function() {	
										if(this.style.display == 'none')
																{
																	hideDiv+=this.id+',';}
																else { 
																	hideDiv = hideDiv.replace(','+this.id+',',',');
																}
							  
									});
									if(activeGraph8)
									{
										$('#hideMedNotTurmaGraph').removeClass('active ');
										activeGraph8 = false;
									}
									else {
										$('#hideMedNotTurmaGraph').addClass('active ');
										activeGraph8 = true;
									}
									
									});
									
				var barOptions = {	
						series: {
						shadowSize: 1, 
						bars: {
							show: true,
							align:'center',
							barWidth: 0.09,
							 
							order: 1,
							
							showNumbers: true,
							vertical : {
								show: true,
								yAlign: function(y) { return y; },
								xAlign: function(x) { return x; },
							},

						}},
						xaxis: {
						autoscaleMargin: 0.05,						
						ticks: ".json_encode($labels10)."
						},
						grid: {
							hoverable: true,
							clickable: true
						},					
						legend: {
							show: true
						},
									 tooltip: true,
						tooltipOpts: {
							content:  function (label, x , y)
							{
								return ('Ocorrências:'+y+' <br> '+'Turma:'+label);
							}
						}
						
					};
					
				   var barData = [";
				   $col=0;
						$graph10="";
						foreach ($data10 as $k=>$v) $graph10 .="{
						
						label: '".$label10[$k]."',
						data: ".json_encode($v)."
							
						},";
						echo substr($graph10,0,-1);
						
						echo "]					
						
					$.plot($('#flot-bar-chart10'), barData, barOptions);
					
				});
					</script>
				";
	}
	
	function graph_MedDiscAno ()	
	{
	
		global $conn;
		global $query11;
		global $Ano;
		global $Periodo;
		global $AnoLectivo; 		
		
		echo "
		<div class='col-lg-6' style='width:100%;'>
						<div class='panel panel-default' >
							<div class='panel-heading active' id='MedDiscAnoGraph' style='cursor: pointer;'>
								<a><i class='fa fa-bar-chart-o fa-fw'></i> Média por Disciplina e Ano do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).",  <span class='curso'></span><span class='periodo'></span> <span class='fa arrow'></span></a>
						</div>                        
                        <div class='panel-body' id='cb11hide'>
								<div class='flot-chart'>							
									<div class='flot-chart-content' id='flot-bar-chart11'></div>
								</div>
							</div>                    
						</div>                    
					</div>	"; 
				
		$i=0;
			
			$result11 = sqlsrv_query($conn,$query11);
			while ($row = sqlsrv_fetch_array($result11, SQLSRV_FETCH_ASSOC))
				{		
					$disciplinas11 [$row['id_d']] = $row ['sigla'];
					
						$dataset11[$row['Ano']][$row['id_d']]= utf8_encode($row['media']);
						
						
						$label11[$row['Ano']]= utf8_encode($row['Ano']);
						$labels11 [] = array ($i,utf8_encode($row['sigla']));					
						$i++;						
				}			
		
		
		$discmedia = array();
		
		$i=0;
		foreach ($disciplinas11 as $k=>$v)
				{
					foreach ($dataset11 as $k1=>$v1)
					{	
						// if($v1[$k])
						$discmedia11 [$k1] []  = array($i,floatval($v1[$k]));						
					}
				$ticks [] = array ($i,utf8_encode($v));					
				$i++;	
				
				}
				
        
		echo "			
					
		<script type='text/javascript'>	
			var activeGraph9 = true;
			$(function() {
				$( '#MedDiscAnoGraph' ).click(function() {
							  $( '#cb11hide' ).slideToggle( 'slow', function() {
										if(this.style.display == 'none')
																{
																	hideDiv+=this.id+',';}
																else { 
																	hideDiv = hideDiv.replace(','+this.id+',',',');
																}
									});
									if(activeGraph9)
									{
										$('#MedDiscAnoGraph').removeClass('active ');
										activeGraph9 = false;
									}
									else {
										$('#MedDiscAnoGraph').addClass('active ');
										activeGraph9 = true;
									}
									
									});
									
				var barOptions = {
						series: {
							bars: {
								show: true,
								align:'center',
								barWidth: 0.19,
								
								order: 1,
								
							vertical : {
								show: true,	
								xAlign: function(x) { return x; },
									
							},	
							}
						},
						xaxis: {
						mode:'categories',	
						autoscaleMargin: 0.05,						
						ticks: ".json_encode($ticks).",
						 tickLength: 0,
						 
						},
						grid: {
							hoverable: true,
							
							
						},
						legend: {
							show: true
						},
						tooltip: true,
						tooltipOpts: {
							content:  function (label, x , y)
							{
								return ('Média:'+y+' <br> '+'Ano:'+label);
							}
						}
					};
					
				   var barData = [";
				   $col=0;
						$graph11="";
						 foreach ($dataset11 as $k=>$v)$graph11 .="{
						
						label: '".$label11[$k]."º Ano',
						data: ".json_encode($discmedia11[$k])."
							
						},";
						echo substr($graph11,0,-1);
						
						echo "]
						
						
						
					$.plot($('#flot-bar-chart11'), barData, barOptions);	
								

				});
					</script>
				";
	}
	
	function graph_MedDiscTurma ()	
	{
		global $conn;
		global $query12;
		global $Ano;
		global $Periodo;
		global $AnoLectivo;
		
		echo "
			<div class='col-lg-6' style='width:100%;'>
							<div class='panel panel-default' >
								<div class='panel-heading active' id='MedDisciplinaTurmaGraph' style='cursor: pointer;'>
									<a><i class='fa fa-bar-chart-o fa-fw'></i> Média por Disciplina e Turma do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).", <span class='curso'></span>, ".$Ano."º Ano<span class='periodo'></span> <span class='fa arrow'></span></a>
								</div>                        
								<div class='panel-body' id='cb12hide'>
									<div class='flot-chart'>							
										<div class='flot-chart-content' id='flot-bar-chart12'></div>
									</div>
								</div>                    
							</div>                    
						</div>";
				
		$i=0;
			
			$result12 = sqlsrv_query($conn,$query12);
			while ($row = sqlsrv_fetch_array($result12, SQLSRV_FETCH_ASSOC))
				{		
					$disciplinasgraph12 [$row['id_d']] = $row ['sigla'];
					
						$dataset12[$row['turma']][$row['id_d']]= utf8_encode($row['media']);
						
						
						$label15[$row['turma']]= utf8_encode($row['turma']);
						$labels15 [] = array ($i,utf8_encode($row['sigla']));					
						$i++;						
				}			
			$discmedia = array();
			
			$i=0;
			foreach ($disciplinasgraph12 as $k=>$v)
					{
					foreach ($dataset12 as $k1=>$v1)
					{	
						if($v1[$k])
						$discmedia12 [$k1] []  = array($i,floatval($v1[$k]));						
					}
				$ticks15 [] = array ($i,utf8_encode($v));
					
				$i++;				
				}	
        
		echo "			
					
		<script type='text/javascript'>	
		var activeGraph10 = true;
			$(function() {
				$( '#MedDisciplinaTurmaGraph' ).click(function() {
							  $( '#cb12hide' ).slideToggle( 'slow', function() {
											if(this.style.display == 'none')
																{
																	hideDiv+=this.id+',';}
																else { 
																	hideDiv = hideDiv.replace(','+this.id+',',',');
																}
									});
									if(activeGraph10)
									{
										$('#MedDisciplinaTurmaGraph').removeClass('active ');
										activeGraph10 = false;
									}
									else {
											$('#MedDisciplinaTurmaGraph').addClass('active ');
											activeGraph10 = true;
										}
									
									});
									
				var barOptions = {
					series: {
						bars: {
							show: true,
							barWidth: 0.08,
							align:'left',							
							order: 1,
							showNumbers: true,
							vertical : {
								show: true,
								
								yAlign: function(y) { return y; },
								
								
							},
							
						}
					},
					xaxis: {
					autoscaleMargin: 0.02,
					ticks: ".json_encode($ticks15)."
					},
					yaxis: {
								autoscaleMargin: 0.08,
							},
					grid: {
						hoverable: true
					},
					legend: {
						show: true
					},
					tooltip: true,
					tooltipOpts: {
						content:  function (label, x , y)
						{
							return ('Média:'+y+' <br> '+'Turma:'+label);
						}
					}
       			};				
			   var barData = [";
			   $col=0;
					$graph15="";
					 foreach ($dataset12 as $k=>$v)$graph15 .="{
					
					label: '".$label15[$k]."',
					data: ".json_encode($discmedia12[$k])."
						
					},";
					echo substr($graph15,0,-1);
					
					echo "]
					
					
					
				$.plot($('#flot-bar-chart12'), barData, barOptions);
					
				});
					</script>
				";
	}
	
	function graph_DistNotas ()	
	{
		global $conn;
		global $query13;
		global $Ano;
		global $Periodo;
		global $AnoLectivo;
		global $sec;
		
		
		echo "
					<div class='col-lg-6' style='width:100%;'>
						<div class='panel panel-default' >
							<div class='panel-heading active' id='DistGraph' style='cursor: pointer;'>
								<a><i class='fa fa-bar-chart-o fa-fw'></i> Número de Ocorrências de Níveis Por Disciplina do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).", <span class='curso'></span>, ".$Ano."º  Ano<span class='periodo'></span> <span class='fa arrow'></span></a>
							</div>                        
							<div class='panel-body' id='cb13hide'>
								<div class='flot-chart'>							
									<div class='flot-chart-content' id='flot-bar-chart13'></div>
								</div>
							</div>                    
						</div>                    
					</div>	"; 
					
			
				
		$result13 = sqlsrv_query($conn,$query13);
			while ($row = sqlsrv_fetch_array($result13, SQLSRV_FETCH_ASSOC))
				{
					$dataset13[$row['nota']][$row['id_d']]= utf8_encode($row['ocorrencias']);
					$label13[$row['id_d']]= utf8_encode($row['sigla']);
				}		
				
				$i=0;
				ksort ($dataset13);
				foreach ($dataset13 as $k=>$v)
					{
						$labels13 [] = array($i,intval($k));//notas
						foreach ($v as $k1=>$v1)
						{
							$data13 [$k1] [] = array($i,intval($v1));//ocorrencias
						}
					$i++;			
					}		
		
		echo "			
					
		<script type='text/javascript'>	
		var activeGraph11 = true;
			$(function() {
				$( '#DistGraph' ).click(function() {
							  $( '#cb13hide' ).slideToggle( 'slow', function() {	
										if(this.style.display == 'none')
																{
																	hideDiv+=this.id+',';}
																else { 
																	hideDiv = hideDiv.replace(','+this.id+',',',');
																}
									});
									if(activeGraph11)
									{
										$('#DistGraph').removeClass('active ');
										activeGraph11 = false;
									}
									
									else {
										$('#DistGraph').addClass('active ');
										activeGraph11 = true;
									}
									
									});	
									
				var barOptions = {
						series: {
						shadowSize: 1, 
						bars: {
							show: true, 
							barWidth: 0.05,
							align: 'center',
							 
							order: 1,
							showNumbers: true,
							vertical : {
								show: true,
								xAlign: function(x) { return x; },
								yAlign: function(y) { return y; },
							},

						}},
						xaxis: {
						autoscaleMargin: 0.02,
						ticks: ".json_encode($labels13).",
											
						},
						
						grid: {
							hoverable: true
						},
						legend: {
							show: true
						},
						 tooltip: true,
						tooltipOpts: {
							content:  function (label, x , y)
							{
								return ('Ocorrências:'+y+' <br> '+'Disciplina:'+label);
							}
						}
					};
					
					   var barData = [";
					   $col=0;
						$graph13="";
					
						foreach ($data13 as $k=>$v) $graph13 .="{
						
						label: '".$label13[$k]."',
						data: ".json_encode($v)."
							
						},";
						echo substr($graph13,0,-1);
						
						echo "]					
						
					$.plot($('#flot-bar-chart13'), barData, barOptions);
					
				});
					</script>
				";
	}
	
	/*function graph_DistNivDiscPercent ()	
	{
		global $conn;
		global $queryDistDisciplinaPercent;
		global $Ano;
		global $Periodo;
		global $curso;
		global $AnoLectivo;		
		
		echo "
					<div class='col-lg-6' style='width:100%;'>
						<div class='panel panel-default' >
							<div class='panel-heading active' id='DistDisciplinaGraph' style='cursor: pointer;'>
								<a><i class='fa fa-bar-chart-o fa-fw'></i> Distribuição de Níveis por Disciplina em Percentagem do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).", <span class='curso'></span>, ".$Ano."º Ano<span class='periodo'></span> <span class='fa arrow'></span></a>
							</div>                        
							<div class='panel-body' id='cb16hide'>
								<div class='flot-chart'>							
									<div class='flot-chart-content' id='flot-bar-chart16'></div>
								</div>
							</div>                    
						</div>                    
					</div>	"; 					
			
				
		$result16 = sqlsrv_query($conn,$queryDistDisciplinaPercent);
			while ($row = sqlsrv_fetch_array($result16, SQLSRV_FETCH_ASSOC))
				{
					$dataset16[$row['sigla']][$row['nota']]= utf8_encode($row['percentagem']);
					
					$notas16 [$row ['nota']] = intval($row ['nota']);					
				}					
				
				$i=0;
				
				foreach ($dataset16 as $k=>$v)
					{	
						$Disciplina16 [] = array($i,utf8_encode($k));//disciplinas
						foreach ($v as $k1=>$v1)
						{
							$data16 [$k1] [] = array($i,intval($v1));//percentagem
						}
						$i++;			
					}
					ksort($data16);
						
				
		
		echo "			
					
		<script type='text/javascript'>	
		 var activeGraph12 = true;
		 var percFormatter = function(val, axis) {
        return (val).toFixed() + '%';
			};
			
			var barnumberFormatter = function(value) {
            return value + '%';
        };
		
		
	
			$(function() {
			
				$( '#DistDisciplinaGraph' ).click(function() {
							  $( '#cb16hide' ).slideToggle( 'slow', function() {
											if(this.style.display == 'none')
																{
																	hideDiv+=this.id+',';}
																else { 
																	hideDiv = hideDiv.replace(','+this.id+',',',');
																}
									});
									if(activeGraph12)
									{
									
										$('#DistDisciplinaGraph').removeClass('active ');
										activeGraph12 = false;
										}
									else {
										$('#DistDisciplinaGraph').addClass('active ');
										activeGraph12 = true;									
									}	
									
									});	
									
				var barOptions = {
						series: {
						stack: true,
						shadowSize: 1, 
						bars: {
							show: true, 
							barWidth: 0.4,
							align: 'center',
							 
							
							showNumbers: true,
							numbers : {
								show:true,
								formatter: barnumberFormatter,
								yAlign: function(y) { return y; },
								xAlign: function(x) { return x; }, // shows numbers at the top of the bar
								xOffset: 0 // pixel offset so numbers are not right up on the edge of the top of the bar        
								
							},

						} },
						xaxis: {
						autoscaleMargin: 0.05,
						ticks: ".json_encode($Disciplina16)."
						},
						yaxis: {min:0, max:100, tickFormatter: percFormatter},
						
						grid: {
							hoverable: true
						},
						legend: {
							show: true
						},
						 tooltip: true,
						tooltipOpts: {
							content:  function (label, x , y)
							{
								return ('Percentagem:'+y+' <br> '+'Nota:'+label);
							}
						}
					};
					
					   var barData = [";
					   $col=0;
						$graph16="";
					
						foreach ($data16 as $k=>$v) $graph16 .="{
						
						label: '".$notas16[$k]."',
						data: ".json_encode($v)."
							
						},";
						echo substr($graph16,0,-1);
						
						echo "]					
						
					$.plot($('#flot-bar-chart16'), barData, barOptions);
					
				});
					</script>
				";
	}*/
		
	/*function graph_DistNivTurmaPercent ()	
	{
		global $conn;
		global $queryDistTurmaPercent;
		global $Ano;
		global $Periodo;
		global $curso;
		global $AnoLectivo;	
	
		
		echo "
					<div class='col-lg-6' style='width:100%;'>
						<div class='panel panel-default' >
							<div class='panel-heading active' id='DistTurmaGraph' style='cursor: pointer;'>
								<a><i class='fa fa-bar-chart-o fa-fw'></i> Distribuição de Níveis por Turma em Percentagem do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).", <span class='curso'></span>, ".$Ano."º Ano<span class='periodo'></span> <span class='fa arrow'></span></a>
							</div>                        
							<div class='panel-body' id='cb17hide'>
								<div class='flot-chart'>							
									<div class='flot-chart-content' id='flot-bar-chart17'></div>
								</div>
							</div>                    
						</div>                    
					</div>	";			
				
		$result17 = sqlsrv_query($conn,$queryDistTurmaPercent);
			while ($row = sqlsrv_fetch_array($result17, SQLSRV_FETCH_ASSOC))
				{
					$dataset17[$row['turma']][$row['nota']]= utf8_encode($row['percentagem']);
					
					$notas17 [$row ['nota']] = intval($row ['nota']);
					
				}								
				
				$i=0;
				ksort ($dataset17);
				foreach ($dataset17 as $k=>$v)
					{
						$turmas17 [] = array($i,utf8_encode($k));//turmas
						foreach ($v as $k1=>$v1)
						{
							$data17 [$k1] [] = array($i,intval($v1));//percentagem
						}
						$i++;			
					}				
		
		echo "			
					
		<script type='text/javascript'>	
		 var activeGraph13 = true;
		 var percFormatter = function(val, axis) {
        return (val).toFixed() + '%';
			};
			
			var barnumberFormatter = function(value) {
            return value + '%';
        };
	
			$(function() {
			
				$( '#DistTurmaGraph' ).click(function() {
							  $( '#cb17hide' ).slideToggle( 'slow', function() {	
										if(this.style.display == 'none')
																{
																	hideDiv+=this.id+',';}
																else { 
																	hideDiv = hideDiv.replace(','+this.id+',',',');
																}
									});
									if(activeGraph13)
									{
										$('#DistTurmaGraph').removeClass('active ');
										activeGraph13 = false;
									}
									else {
										$('#DistTurmaGraph').addClass('active ');
										activeGraph13 = true;
										
									}
									
									});	
									
				var barOptions = {
						series: {
						stack: true,
						shadowSize: 1, 
						bars: {
							show: true, 
							barWidth: 0.4,
							align: 'center',
							 
							
							showNumbers: true,
							numbers : {
								show:true,
								formatter: barnumberFormatter,
								yAlign: function(y) { return y; },
								xAlign: function(x) { return x; }, // shows numbers at the top of the bar
								xOffset: 0 // pixel offset so numbers are not right up on the edge of the top of the bar   
								
							},

						} },
						xaxis: {
						autoscaleMargin: 0.05,
						ticks: ".json_encode($turmas17)."
						},
						yaxis: {min:0, max:101, tickFormatter: percFormatter},
						
						grid: {
							hoverable: true
						},
						legend: {
							show: true
						},
						 tooltip: true,
						tooltipOpts: {
							content:  function (label, x , y)
							{
								return ('Percentagem:'+y+' <br> '+'Nota:'+label);
							}
						}
					};
					
					   var barData = [";
					   $col=0;
						$graph17="";
					
						foreach ($data17 as $k=>$v) $graph17 .="{
						
						label: '".$notas17[$k]."',
						data: ".json_encode($v)."
							
						},";
						echo substr($graph17,0,-1);
						
						echo "]					
						
					$.plot($('#flot-bar-chart17'), barData, barOptions);
					
				});
					</script>
				";
	}*/	
	
	function graph_DistNivTurmaPercent_20val ()	
	{
		global $conn;
		global $queryDistTurmaPercent;
		global $Ano;
		global $Periodo;
		global $curso;
		global $AnoLectivo;	
						
		$result17 = sqlsrv_query($conn,$queryDistTurmaPercent);
			while ($row = sqlsrv_fetch_array($result17, SQLSRV_FETCH_ASSOC)) 
				{
					
					$dataset17[$row['id_t']][$row['nota']]= utf8_encode($row['percentagem']);
					
					$notas17 [$row ['nota']] = intval($row ['nota']);

					$turmas17 [$row['id_t']] = $row['turma'];	
				}								
				
				asort($turmas17);
				ksort ($dataset17);
				
				echo "	
						<div class='col-lg-6' style='width:100%;'>
							<div class='panel panel-default' >
								<div class='panel-heading active' id='DistNivTurmaPerGraphsec20val' style='cursor: pointer;'>
									<a><i class='fa fa-bar-chart-o fa-fw'></i> Distribuição de Níveis por Turma em Percentagem do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).", <span class='curso'></span>, ".$Ano."º Ano<span class='periodo'></span></span> <span class='fa arrow'></span></a>
								</div>                        
								<div class='panel-body' id='DistNivTurmaPanelBody20val'>";
				$pagebreakMed = 0;
				foreach ($dataset17 as $k=>$v)
					{
						if($pagebreakMed >= 1)
						{
							echo "<div class='page-break'></div>";							
						}
						echo"
						<div class='col-lg-4'>
							<div class='panel panel-default'>
							<div class='panel-heading'>
								<a><i class='fa fa-bar-chart-o fa-fw'></i> Turma ".utf8_encode($turmas17[$k])."</a>									
							</div>                        
                        <div class='panel-body'>
									<div class='flot-chart'>							
										<div class='flot-chart-content' id='flot-pie-chart".$k."'></div>
									</div> 
								</div>                       
							</div>                   
						</div>";
									
						echo "
						
						<script type='text/javascript'>	
						
						$(function() {							
													
							var Options = {
									series: {
									
									pie: {
										show: true									
										}

									}, 
									
									grid: {
										hoverable: true
									},
									legend: {
										show: false
									},
									tooltip: true,
									tooltipOpts: {
										  content: '%p.0%, %s', 
										  shifts: {
											  x: 20,
											  y: 0
										  },
										  defaultTheme: false
									  }
									
								};
								var Data = [
								";
						$graph20val="";
						foreach ($v as $k1=>$v1) $graph20val .="								    					   
										{								   
										label: '".$notas17[$k1]."',
										data: ".$v1."},";
										
										echo substr($graph20val,0,-1);
						
						
						echo "
							]
									
						
					$.plot($('#flot-pie-chart".$k."'), Data, Options);
					
						});
							</script>
						";
						$pagebreakMed++;
						}
						
						echo "</div>                    
								</div>                    
							</div>";
							
					echo "<script>
						var activeGraph14 = true;
						$(function() {
				
								$( '#DistNivTurmaPerGraphsec20val' ).click(function() {
											  $( '#DistNivTurmaPanelBody20val' ).slideToggle( 'slow', function() {	
														if(this.style.display == 'none')
																{
																	hideDiv+=this.id+',';}
																else { 
																	hideDiv = hideDiv.replace(','+this.id+',',',');
																}
												
													});
													if(activeGraph14)
									{
									
										$('#DistNivTurmaPerGraphsec20val').removeClass('active ');
										activeGraph14 = false;
										}
									else {
										$('#DistNivTurmaPerGraphsec20val').addClass('active ');
										activeGraph14 = true;									
									}
												
											
													});	});
					
					</script>";	
								
					}							
		
	function graph_DistNivDiscPercent_20val ()	
	{
		global $conn;
		global $queryDistDisciplinaPercent;
		global $Ano;
		global $Periodo;
		global $curso;
		global $AnoLectivo;				
				
		$result17 = sqlsrv_query($conn,$queryDistDisciplinaPercent);
			while ($row = sqlsrv_fetch_array($result17, SQLSRV_FETCH_ASSOC))
				{
					$dataset16[$row['id_d']][$row['nota']]= utf8_encode($row['percentagem']);
					
					$notasDisc17 [$row ['nota']] = intval($row ['nota']);

					$Disciplina17 [$row['id_d']] = $row['sigla'];	
				}								
				
				asort($Disciplina17);
				ksort ($dataset16);
				
				echo "		<div class='col-lg-6 ' style='width:100%;'>
								<div class='panel panel-default' >
									<div class='panel-heading active' id='DistDiscPercentGraph' style='cursor: pointer;'>
										<a><i class='fa fa-bar-chart-o fa-fw'></i> Distribuição de Níveis por Disciplina em Percentagem do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).", <span class='curso'></span>, ".$Ano."º Ano<span class='periodo'></span></span> <span class='fa arrow'></span></a>
									</div>                        
									<div class='panel-body' id='DistNivDiscPer'>";
				$pagebreakMed = 0;
				foreach ($dataset16 as $k=>$v)
					{
						if($pagebreakMed>=1)
						{
							echo "<div class='page-break'></div>";							
						}
						echo "	
						
					<div class='col-lg-4 ' >
                    <div class='panel panel-default'>
                        <div class='panel-heading'>
                           <a><i class='fa fa-bar-chart-o fa-fw'></i> ".utf8_encode($Disciplina17[$k])."</a>									
                        </div>                        
                        <div class='panel-body'>
                           <div class='flot-chart'>							
											<div class='flot-chart-content' id='flot-pie-chart".$k."'></div>
							</div>
                        </div>                       
                    </div>                   
                </div>
						
										";
						echo "						
						<script type='text/javascript'>	
							
							$(function() {
				
								
													
							var Options = {
									series: {									
									pie: {
											show: true									
										}
									}, 
									
									grid: {
										hoverable: true
									},
									legend: {
										show: false
									},
									tooltip: true,
									tooltipOpts: {
										  content: '%p.0%, %s', 
										  shifts: {
											  x: 20,
											  y: 0
										  },
										  defaultTheme: false
									  }
									
								};
								var Data = [
								";
						$graphDisc20val="";
						foreach ($v as $k1=>$v1) $graphDisc20val .="								    					   
										{								   
										label: '".$notasDisc17[$k1]."',
										data: ".$v1."},";
										
										echo substr($graphDisc20val,0,-1);
						
						
						echo "
							]
									
						
						$.plot($('#flot-pie-chart".$k."'), Data, Options);
					
						});
							</script>
						";	
						$pagebreakMed++;
					}					
					
					echo "</div>                    
								</div>                    
							</div>";
							
					echo "<script>
						var activeGraph15 = true;
						$(function() {
				
								$( '#DistDiscPercentGraph' ).click(function() {
											  $( '#DistNivDiscPer' ).slideToggle( 'slow', function() {	
														if(this.style.display == 'none')
																{
																	hideDiv+=this.id+',';}
																else { 
																	hideDiv = hideDiv.replace(','+this.id+',',',');
																}
													});
													if(activeGraph15)
									{
									
										$('#DistDiscPercentGraph').removeClass('active ');
										activeGraph15 = false;
										}
									else {
										$('#DistDiscPercentGraph').addClass('active ');
										activeGraph15 = true;									
									}
													
													});	});
					
					</script>";	
								
								
	}							
	
	function graph_MedDiscTurmaV2 ()	
	{
		global $conn;
		global $query12;
		global $Ano;
		global $Periodo;
		global $AnoLectivo;	
		$pagebreakMedDiscTurma = 0;
		
			$resultMedDiscTurmav2 = sqlsrv_query($conn,$query12);
			while ($row = sqlsrv_fetch_array($resultMedDiscTurmav2, SQLSRV_FETCH_ASSOC))
				{		
					$MedDiscTurma [$row ['id_t']][$row['id_d']] = $row ['media'];//media
					
					$Turmas [$row['id_t']] = $row ['turma'];//turmas
					
					$Disciplinas [$row ['id_d']] = $row ['sigla'];//disciplinas					
				}
				echo "<div class='col-lg-6' style='width:100%;'>
									<div class='panel panel-default' >
										<div class='panel-heading active' id='MedDisciplinaTurmaGraphv2' style='cursor: pointer;'>
											<a><i class='fa fa-bar-chart-o fa-fw'></i> Média por Disciplina e Turma do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).", <span class='curso'></span>, ".$Ano."º Ano<span class='periodo'></span></span> <span class='fa arrow'></span></a>
										</div>                        
										<div class='panel-body' id='cbMedDiscTurmav2'>";
				
			$i=0;
			
			foreach ($MedDiscTurma as $idt=>$idd)
			{			
				//divs para inserir graficos
				if($pagebreakMedDiscTurma>=1)
				{					
					echo "<div class='page-break'></div>";
				}
				
				echo "
					<div class='col-lg-4 col-md-6 col-sm-6'>
                    <div class='panel panel-default'>
                        <div class='panel-heading'>
                           <a><i class='fa fa-bar-chart-o fa-fw'></i> Turma ".utf8_encode($Turmas[$idt])."</a>									
                        </div>                        
                        <div class='panel-body'>
											<div class='flot-chart'>							
												<div class='flot-chart-content' id='flot-bar-chartMDT".$idt."'></div>
											</div>
										</div>                       
									</div>                   
								</div>	
											
											";
					$medDisc = array();
					$disciplinasTicks = array ();
				foreach ($idd as $idd2=>$med)
					{
						$disciplinasTicks [] = array ($i,utf8_encode($Disciplinas[$idd2]));
						$medDisc [] = array ($i, $med);
						$i++;					
					}
								
					//scrip para geerar grafico					
					echo "<script type='text/javascript'>	
					$(function() {										
						var barOptions = {
							series: {
								bars: {
									show: true,
									align: 'left',
									barWidth: 0.15,
									
									order: 1,
									
									showNumbers: true,
									vertical : {
									
										show: true,
										yAlign: function(y) { return y; },
									
									},
								}
							},
							xaxis: {
							autoscaleMargin: 0.05,
							ticks: ".json_encode($disciplinasTicks)."
							
							},
							yaxis: {
								autoscaleMargin: 0.11,
							},
							grid: {
								hoverable: true,
								
							},
							legend: {
								show: true
							},
							tooltip: true,
							tooltipOpts: {
								content:  function (label, x , y)
								{
									return ('Média:'+y);
								}
							}
						};
							var barData = [{ label: 'Média',			   	
							data: ".json_encode($medDisc)."						
							}];";			
						echo "$.plot($('#flot-bar-chartMDT".$idt."'), barData, barOptions);
							
						});
							</script>
							
						";
							$pagebreakMedDiscTurma++;
			}
					
					echo "<script>
					var activeGraph16 = true;
					$(function() {
								$( '#MedDisciplinaTurmaGraphv2' ).click(function() {
									  $( '#cbMedDiscTurmav2' ).slideToggle( 'slow', function() {	
												if(this.style.display == 'none')
																{
																	hideDiv+=this.id+',';}
																else { 
																	hideDiv = hideDiv.replace(','+this.id+',',',');
																}
									  
											});
											if(activeGraph16)
											{
												$('#MedDisciplinaTurmaGraphv2').removeClass('active');
												activeGraph16 = false;
											}
											else {
												$('#MedDisciplinaTurmaGraphv2').addClass('active');
												activeGraph16 = true;
											}
											
											});});
						
					
						</script>";	

					echo "
						</div>                    
						</div>                    
						</div>";
			 }
			 
	function graph_MedTurmaAno ()	
	{
		global $conn;
		global $queryMedTurmaAno;
		global $Ano;
		global $Periodo;
		global $AnoLectivo;
		
		echo "
			<div class='col-lg-6' style='width:100%;'>
							<div class='panel panel-default'>
								<div class='panel-heading active' id='MedAnoTurma' style='cursor: pointer;'>
									<a><i class='fa fa-bar-chart-o fa-fw'></i> Média por Turma e Ano do Ano Letivo ".$AnoLectivo."/".($AnoLectivo+1).", <span class='curso'></span><span class='periodo'></span><span class='fa arrow'></span></a>
								</div>                        
								<div class='panel-body' id='cb12MedAnoTurma'>
									<div class='flot-chart'>							
										<div class='flot-chart-content' id='flot-bar-chartMedAnoTurma'></div>
									</div>
								</div>                    
							</div>                    
						</div>";
				
		
			
			$resultMedTurmaAno = sqlsrv_query($conn,$queryMedTurmaAno);
			while ($row = sqlsrv_fetch_array($resultMedTurmaAno, SQLSRV_FETCH_ASSOC))
				{		
					$turmasmedturmaano [$row['id_t']] = $row ['turmas'];
					
						$datasetturmasano[$row['Ano']][$row['id_t']]= utf8_encode($row['media']);						
						
						$labelAno[$row['Ano']]= utf8_encode($row['Ano']);											
				}	
				
			$i=0;
			foreach ($turmasmedturmaano as $k=>$v)
					{
					foreach ($datasetturmasano as $k1=>$v1)
					{
						if($v1[$k])
						$turmaano [$k1] []  = array($i,floatval($v1[$k]));						
					}
				$ticksMedAno [] = array ($i,utf8_encode($v));
					
				$i++;				
				}		
        
		echo "			
					
		<script type='text/javascript'>	
			$(function() {
				var activeGraph17 = true;
				$( '#MedAnoTurma' ).click(function() {
							  $( '#cb12MedAnoTurma' ).slideToggle( 'slow', function() {
										if(this.style.display == 'none')
																{
																	hideDiv+=this.id+',';}
																else { 
																	hideDiv = hideDiv.replace(','+this.id+',',',');
																}
									});
									if (activeGraph17)
									{	
										$('#MedAnoTurma').removeClass('active ');
										activeGraph17 = false;
									}
									else {
										$('#MedAnoTurma').addClass('active ');
										activeGraph17 = true;
										
									}
									
									});
									
				var barOptions = {
					series: {
						bars: {
							show: true,
							barWidth: 0.2,
							align:'left',
							
							order: 1,
							showNumbers: true,
							vertical : {
								show: true,
								yAlign: function(y) { return y; },
								
							},							
						}
					},
					xaxis: {
					autoscaleMargin: 0.05,
					ticks: ".json_encode($ticksMedAno)."
					},
					yaxis: 
					{
						autoscaleMargin: 0.1,
					},
					grid: {
						hoverable: true
					},
					legend: {
						show: true
					},
					tooltip: true,
					tooltipOpts: {
						content:  function (label, x , y)
						{
							return ('Média:'+y+' <br> '+label);
						}
					}
       			};				
			   var barData = [";
			   $col=0;
					$graphmedano="";
					 foreach ($datasetturmasano as $k=>$v)$graphmedano .="{
					
					label: '".$labelAno[$k]."º Ano',
					data: ".json_encode($turmaano[$k])."
						
					},";
					echo substr($graphmedano,0,-1);
					
					echo "]
					
					
					
				$.plot($('#flot-bar-chartMedAnoTurma'), barData, barOptions);
				
				
					
				});
					</script>
				";
	}
									

	?>