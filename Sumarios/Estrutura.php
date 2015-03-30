<?
			
		function  MenuGeral ()
		{
			echo "
			<div id='footer' class='container'>
						<nav class='navbar navbar-fixed-bottom' style='z-index:9999;'>
							<div style='padding-left:44%; padding-bottom:0.25%; padding-top:0.25%;'>
							   <button type='button' id='ListaSumarios_Nav' class='btn btn-primary btn-circle' title='Lista de Sumários'><i class='glyphicon glyphicon-list-alt'></i></button>
							   <button type='button' id='Calendar_Nav' class='btn btn-primary btn-circle ' title='Calendário'><i class='glyphicon glyphicon-calendar'></i></button>
							   <button type='button' id='Sumario_Nav' class='btn btn-primary btn-circle disabled' title='Sumário'><i class='glyphicon glyphicon-edit'></i></button>
							   <button type='button' id='Faltas_Nav' class='btn btn-primary btn-circle disabled' title='Marcação de Faltas'><i class='fa fa-list'></i></button>
							</div>
						</nav>
			</div>						
			";
		}	
	
		function Modal ()
		{
			return "<div class='modal fade' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true' id='modalAlert'>
					<div class='modal-dialog'>
						<div class='modal-content'>						  
						  <div class='modal-body' id='textoWarning'>						
						  </div>
							<div class='modal-footer' style='padding:5px 10px;'>
								<button type='button' class='btn btn-default' style='padding: 2px; 15px;' data-dismiss='modal' id='butCloseModal'>Fechar</button>						
						  </div>						  
						</div>
					  </div>
					</div>";
		}

		function PrintBanner () 
		{		
				global $conn;
				
				$query="select Logotipo from GER_Escola where IsSedeAgrupamento = 1";
				$result=sqlsrv_query($conn,$query);
				
			if($row=sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC))
			{
				$logo = base64_encode($row["Logotipo"]);
			}		
				
				return "				
						<nav class='navbar navbar-inverse navbar-fixed-top visible-print' style=' position:relative; background-color: #E0E0E0; border-color: #E0E0E0; width:100%;'>
							<div class='container' style='width:100%;' >								
																
								<div class='collapse navbar-collapse' id='bs-example-navbar-collapse-1' >
									<ul class='nav navbar-nav'>
										<li style='padding-top:5px;'>
											<img src='data:image/jpeg;base64,$logo' height='35' width='35' >
										</li>
										<li >
											<a><span class='escola'></span></a>
										</li>
										<li>
											<img src='Imagens/bar.png' height='42' width='42' style='padding-top: 10px;'>
										</li>
										<li>									
												<a>Classificações Finais</a>																					
										</li style='padding-left:10px;'>
										<li >
											<a>Ano Letivo ".$AnoLectivo ."/".($AnoLectivo+1)."</a>
										</li>
										<li >
											<a>".$Periodo."º Período</a>
										</li>
									</ul>									
								</div>
								<!-- /.navbar-collapse -->
							</div>
							<!-- /.container -->
						</nav>			
					";
		
		}
?>