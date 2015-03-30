 <?php
	
	require_once('Function_Graph.php');
	require_once ('Function_Table.php');

	$sec=$_GET['sec'];
	$ciclo23 = $_GET['23ciclo'];
	$ciclo1=$_GET['1ciclo'];
	
	$Escola=$_GET['escola'];
	$AnoLectivo=$_GET['anoletivo'];
	$curso=$_GET['curso'];
	$Ano=$_GET['ano'];	
	$Periodo=$_GET['periodo'];	
	$Turma = $_GET['turma'];
	$Disciplina = $_GET['disciplina'];
	$DataInicio = $_GET['datainicio'];
	$DataFim = $_GET['datafim'];
	$modular = $_GET['modular'];
	
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
	
	if($_GET['hideDiv']){
		$hideDivs=array_filter(explode(',', $_GET['hideDiv']));
	}
	else {
		$hideDivs = array();
	}	
		$pagebreak = 0;
		$noPageBreak = 0;	
		
		if(isset($cb2))
			{				
								
				if(!in_array("cb2hide",$hideDivs))
					{
						if($pagebreak)
						{echo "<div class='page-break'></div>";
							}
						
							echo graph_MedDisc ();
							$pagebreak++;
					}
					
				if(!in_array("hidecb2",$hideDivs))
				{	
					if($pagebreak)
						{echo "<div class='page-break'></div>";
							}
						echo Table_MedDisc ();
						$pagebreak++;						
								
				}
				
			}
		if(isset($cb3))
			{			
				
				if(!in_array("cb3hide",$hideDivs))
				{	
					if($pagebreak)
						{echo "<div class='page-break'></div>";
							}
						
					echo graph_MedTurma ();
					$pagebreak++;
				}				
				
				if(!in_array("hidecb3",$hideDivs))
				{
						if($pagebreak)
						{	
							echo "<div class='page-break'></div>";
							}
							
							echo Table_MedTurma ();
							$pagebreak++;
						
						
											
				}								
			}	
		if(isset($cb4))
			{
				if(!in_array("cb4hide",$hideDivs))
				{
					if($pagebreak)
						{echo "<div class='page-break'></div>";}
						echo graph_MedAno ();
						$pagebreak++;
				}
				
				
				if(!in_array("hidecb4",$hideDivs))
				{	
					if($pagebreak)
						{
							echo "<div class='page-break'></div>";}
							echo Table_MedAno ();
							$pagebreak++;
					
				}						
			}	
			if(isset($cb11))
			{	
				
				
				
				if(!in_array("cb11hide",$hideDivs))
				{
					if($pagebreak)
					{echo "<div class='page-break'></div>";	}				
					echo graph_MedDiscAno ();
					$pagebreak++;
				}
								
				if(!in_array("hidecb11",$hideDivs))
				{
					if($pagebreak)
					{
						echo "<div class='page-break'></div>";}
						echo Table_MedDiscAno ();
						$pagebreak++;
					
				}		
			}
			
			if(isset($cb12))
			{	
				
				
				if(!in_array("cb12hide",$hideDivs))
				{	if($pagebreak)
					{echo "<div class='page-break'></div>";}	
					$pagebreak++;					
					echo graph_MedDiscTurma ();				
				}
				
				if(!in_array("cbMedDiscTurmav2",$hideDivs))
				{
					if($pagebreak)
					{echo "<div class='page-break'></div>";}
					$pagebreak++;
					echo graph_MedDiscTurmaV2 ();
				
				}				
				
				if(!in_array("hidecb12",$hideDivs))
				{
					if($pagebreak)
					{
						echo "<div class='page-break'></div>";}
						$pagebreak++;
						echo Table_MedDiscTurma ();
					
				}
					
			}				
			
			if(isset($cb10))
			{					
				
				if(!in_array("cb10hide",$hideDivs))
				{
					if($pagebreak)
					{echo "<div class='page-break'></div>";	}
						$pagebreak++;
					echo graph_MedNotasTurma ();
				}
				
				
				if(!in_array("hidecb10",$hideDivs))
				{
					if($pagebreak)
					{
						echo "<div class='page-break'></div>";}
						echo Table_MedNotaTurma ();
						$pagebreak++;
					
						
				}			
			}	
		if(isset($cb5))
			{	
				
				
				if(!in_array("hidecb5",$hideDivs))
				{
					if($pagebreak)
					{echo "<div class='page-break'></div>";	}
					
					echo Table_PerNegAno ();
					$pagebreak++;}
			}		
		
		if(isset($cb6))
			{	
									
				
				if(!in_array("hidecb6",$hideDivs))
				{
					if($pagebreak)
					{echo "<div class='page-break'></div>";	}
				
					echo Table_PerNegTurma ();
					$pagebreak++;
				}	
			}
			
		if(isset($cb7))
			{	
				
						
				if(!in_array("cb7hide",$hideDivs))
				{
					if($pagebreak)
					{echo "<div class='page-break'></div>";	}
					$pagebreak++;
					echo graph_NegTurma ();
				}
				
				
				if(!in_array("hidecb7",$hideDivs))
				{
					if($pagebreak)
					{
						echo "<div class='page-break'></div>";}
						echo Table_NegTurma ();
						$pagebreak++;
				}
				
					
			}
		if(isset($cb8))
			{	
					
				if(!in_array("cb8hide",$hideDivs))
				{
					if($pagebreak)
					{echo "<div class='page-break'></div>";	}
					
					echo graph_NegAno ();
				}
				
				
				if(!in_array("hidecb8",$hideDivs))
				{
					if($pagebreak)
					{
						echo "<div class='page-break'></div>";}
						echo Table_NegANo ();
						$pagebreak++;						
				}			
			}	
		if(isset($cb9))
			{	
					
				if(!in_array("cb9hide",$hideDivs))
				{
					if($pagebreak)
					{echo "<div class='page-break'></div>";}
					echo graph_NegDisc ();
				}
				
				
				if(!in_array("hidecb9",$hideDivs))
				{
					if($pagebreak)
					{
						echo "<div class='page-break'></div>";}
						echo Table_NegDisc ();	
						$pagebreak++;										
				}				
			}	
			
		if(isset($cb13))
			{	
				
				if(!in_array("cb13hide",$hideDivs))
				{				
					if($pagebreak)
					{echo "<div class='page-break'></div>";	}
					$pagebreak++;
					echo graph_DistNotas ();
				}
				
				
				if(!in_array("hidecb13",$hideDivs))
				{
					if($pagebreak)
					{
						echo "<div class='page-break'></div>";						
					}
					$pagebreak++;
					echo Table_DistNotas ();
				}				
			}
			
			if(isset($cb1) && !in_array("hidecb1",$hideDivs))
			{	
				
					
				if($pagebreak)
				{echo "<div class='page-break'></div>";}
					$pagebreak++;
					echo Table_AlunNeg ();
			}
			
			if(isset($cb16))
			{					
						if(!in_array("DistNivDiscPer",$hideDivs))
						{
							if($pagebreak)
							{echo "<div class='page-break'></div>";	}
							
							$pagebreak++;
							echo  graph_DistNivDiscPercent_20val ();
						}
							
						/*elseif(!in_array("cb16hide",$hideDivs))
						{
							if($pagebreak)
							{echo "<div class='page-break'></div>";	}
							
							$pagebreak++;
							graph_DistNivDiscPercent_20val ();
						}*/
										
					if(!in_array("hidecb16",$hideDivs))
					{
						if($pagebreak)
						{	
							echo "<div class='page-break'></div>";}
							
							$pagebreak++;
							echo Table_DistNivDiscPercent ();									
					}			
			}					
			
			if(isset($cb17))
			{							
				
				if(!in_array("DistNivTurmaPanelBody20val",$hideDivs))
					{
						if($pagebreak)
						{echo "<div class='page-break'></div>";	}
						$pagebreak++;
						echo graph_DistNivTurmaPercent_20val ();}					
					
				/*elseif(!in_array("cb17hide",$hideDivs)) {
					
					if($pagebreak)
					{echo "<div class='page-break'></div>";	}	
					$pagebreak++;
					graph_DistNivTurmaPercent ();
				}*/
				
				
				if(!in_array("hidecb17",$hideDivs))
				{
					if($pagebreak)
					{
						echo "<div class='page-break'></div>";}
						$pagebreak++;
						echo Table_DistNivTurmaPercent ();		
					}				
							
			}	

			if(isset($cb18))
			{	
				
				
				if(!in_array("cb12MedAnoTurma",$hideDivs))
				{
					if($pagebreak)
					{echo "<div class='page-break'></div>";	}
					$pagebreak++;
					echo graph_MedTurmaAno ();
				}
				
				
				if(!in_array("hidecbMedTurmaAno",$hideDivs))
				{				
					if($pagebreak)
					{
						echo "<div class='page-break'></div>";}
						echo Table_MedTurmaAno ();
						$pagebreak++;
					}					
				}		
			if(isset($cb19))
			{
				if(!in_array("hidecbPDA",$hideDivs))
				{
					if($pagebreak)
					{echo "<div class='page-break'></div>";	}
					
					$pagebreak++;
					
					echo Table_PDA ();
				}		
			}
			
			if(isset($cb20))
			{
				if(!in_array("hidecbPDA2",$hideDivs))
				{
					if($pagebreak)
					{echo "<div class='page-break'></div>";	}
					
					$pagebreak++;					
					echo Table_PDA_Disciplina_Turma ();
				}		
			}
			
			
   ?>		

 
	

