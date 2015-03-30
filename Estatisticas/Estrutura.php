<?
			
		function  MenuGeral ()
		{
			echo "			
						<nav class='navbar navbar-inverse navbar-fixed-top' role='navigation' id='MenuGeral' '>
							<div class='container'>
								
								<div class='navbar-header'>
									<button type='button' class='navbar-toggle' data-toggle='collapse' data-target='#bs-example-navbar-collapse-1'>
										<span class='sr-only'>Toggle navigation</span>
										<span class='icon-bar'></span>
										<span class='icon-bar'></span>
										<span class='icon-bar'></span>
									</button>
									<a class='navbar-brand' href='#'>Módulo Estatísticas</a>
								</div>
								<!-- Collect the nav links, forms, and other content for toggling -->
								<div class='collapse navbar-collapse' id='bs-example-navbar-collapse-1'>
									<ul class='nav navbar-nav'>
										<li>
											<a href='#'>About</a>
										</li>
										<li>
											<a href='#'>Services</a>
										</li>
										<li>
											<a href='#'>Contact</a>
										</li>
									</ul>
								</div>
								<!-- /.navbar-collapse -->
							</div>
							<!-- /.container -->
						</nav>			
			";
		}				
		function PrintBanner () 
		{		
				global $AnoLectivo;
				global $Periodo;
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
												<a>Estatísticas</a>																					
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