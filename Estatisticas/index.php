<?
require_once('conn.php'); //establish the connection with the database on this page.
require_once ('Filtros.php');
require_once ('Estrutura.php');
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
		
require_once('QResult.php');
require_once('QResultPDA.php');
$nivelEnsino=0;
	//permite saber se algum nivel de ensino ja foi selecionado para aparecer as checkbox
	if($_GET['sec'] || $_GET['1ciclo'] || $_GET['23ciclo'])
	{
		$nivelEnsino=1;
	}
	
 function modalAlert ()
 {
		global $conn;
		global $queryVerificaRegistos;		
		// die($queryVerificaRegistos);
		$resultVerificaRegistos = sqlsrv_query($conn,$queryVerificaRegistos);	
		
		$ResultTemRegistos = sqlsrv_has_rows ($resultVerificaRegistos);
		
		if($ResultTemRegistos)
		{return false;}
		else {return "Não existem avaliações para este período";}	 
 }
 
function preencheNivEn ()
{	
	if($_GET['sec']==1)
	{return "Secundário";}
	
	if ($_GET['23ciclo']==1)
	{return "2º e 3º Ciclo";}
	
	if ($_GET['1ciclo']==1)
	{return "1º Ciclo";}
	return "Nível de Ensino";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

      <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">	
	
	<title>Estatistica</title>	
	 <!-- jQuery Version 1.11.2 -->    
	
	<script src="../js/jquery-1.11.2.min.js"></script>
	<script src="../js/jquery-ui.min.js"></script>
	<script src="../js/plugins/flot/excanvas.min.js"></script>	
    <script src="../js/plugins/flot/jquery.flot.js"></script>
	<script src="../js/plugins/flot/jquery.flot.orderBars.js"></script>
	<script src="../js/plugins/flot/jquery.flot.axislabels.js"></script>
    <script src="../js/plugins/flot/jquery.flot.pie.js"></script>
    <script src="../js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="../js/plugins/flot/jquery.flot.tooltip.min.js"></script>
	<script src="../js/plugins/flot/jquery.flot.barnumbers.enhanced.js"></script>
	<script src="../js/plugins/flot/jquery.flot.symbol.min.js"></script>
	<script src="../js/plugins/flot/jquery.flot.stack.js"></script>
	
	<script src="../js/plugins/flot/graphEstat.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>
	<script src="../js/bootstrap.js"></script>
	 <!-- Timeline CSS -->
    <link href="../css/plugins/timeline.css" rel="stylesheet">    

    <!-- Bootstrap Core CSS -->  
	<link type="text/css" href="js/jquery-ui.theme.css" rel="stylesheet" /> 	
	<link type="text/css" href="js/jquery-ui.min.css" rel="stylesheet" />
	<link href="../css/bootstrap.css" rel="stylesheet">
	<link href="../css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">
	

    <!-- DataTables CSS -->
    <link href="../css/plugins/dataTables.bootstrap.css" rel="stylesheet">

	
    <!-- Custom CSS -->
    <link href="../css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">	
	<link rel="shortcut icon" href="PAAE.ico">
	
</head>
	<body style="cursor: default;">
	<div style="z-index:1001; background-color:white;">
		<div>
			<?
				echo PrintBanner ();
			?>		
		</div>
		<div id="menus" style="width:100%; padding-bottom:0.25%; z-index:1000; background-color:white;">			
			<div style="padding-left: 4%; overflow:auto;">
				<input type="button" id="NEn" class="btn btn-primary btn-lg no-print" style="border-radius: 2px; width:150px;" value="<? echo preencheNivEn (); ?>"></input>				
				<input type="button" id="TEst" class="btn btn-primary <? echo $nivelEnsino?"":"disabled"; ?> btn-lg no-print" style="border-radius: 2px; width:150px;" value="Estatísticas" ></input>
				<input type="button" id="Opt" class="btn btn-primary <? echo $nivelEnsino?"":"disabled"; ?> btn-lg no-print" style=" border-radius: 2px; width:150px;"  value="Filtros" ></input>
				<div style="float:right; padding-right:2%;">					
					<? echo showResult ();?>
				</div>
			</div>
			<div id="checkboxs" style="<? echo $nivelEnsino && !$modular?"display:block;":"display:none;";  ?> padding-top:0.1%; padding-left: -0.25%; padding-right:1%; z-index:999;">				
					<? echo showCheckBox (); ?>				
			</div>
			<div id="selects" style="display:none; padding-top:0.1%; padding-left: -0.25%; z-index:1000;">
					<? echo showSelects (); ?>
			</div>			
			<div id="nivelEnsino" style="<? echo $nivelEnsino?"display:none;":"";?> padding-top:0.1%; padding-left: -0.25%; z-index:1000;">
				<form method="post" id="formNivEn" role="form" name="NivelEnsino">
					<? echo showNivEnsino (); ?>
				</form>
			</div>
		</div>
	</div>	
		<div style="padding-top: 0.25%; width:100%; z-index:998">
			
	</div>
	
	<div class='modal fade' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true' id='modalAlert'>
				  <div class='modal-dialog'>
					<div class='modal-content'>
					  <div class='modal-header'>
						<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>
						<h4 class='modal-title' id='myModalLabel'>Impossível Apresentar Resultados</h4>
					  </div>
					  <div class='modal-body' id='textoWarning'>						
					  </div>					  
					</div>
				  </div>
				</div>
				
				<?php
			if($Periodo || $Disciplina)
			{					
				$modal = modalAlert ();
				if( $modal === false)
				{				
					include ("Result.php");
				}
				else {
						// echo "ola";
						echo "<script>
						$(function() { $('#modalAlert').modal('show')});
						$('#textoWarning').text('".$modal."');
						
						</script>";
				}}
					
			?>
		<div class="SpinningWheel"><!-- Place at bottom of page --></div>
	</body>
	<script>
			var niv = false;
			var sel= true;
			var check = false;		
			var cursoNome;
			var escolaNome;
			var turmaNome;
			var disciplinaNome;
			var mod = <? echo json_encode($modular);?>;			
			//passar os nomes das variaveis no link
			$(function() {
				if(mod == 'false')
				{
					$('.periodo').text( ', ' + $('#periodo option:selected').text());
					$('#periodoNome').val( $('periodo option:selected').text());
				}			
			});
			
			$(function() {
			$('.curso').text( $('#curso option:selected').text());
			$('#cursoNome').val( $('#curso option:selected').text());
			});			
			
			$(function() {
			$('.escola').text( $('#escola option:selected').text());
			$('#escolaNome').val( $('#escola option:selected').text());
			});		
			
			$(function() {
			$('.Turma').text( $('#turma option:selected').text());
			$('#turmaNome').val( $('#turma option:selected').text());
			});	
			
			$(function() {
			$('.Disciplina').text( $('#disciplina option:selected').text());
			$('#disciplinaNome').val( $('#disciplina option:selected').text());
			});
			
			//quando carrego nos botoes de submeter		

				
			
			$('#Exceldiv').click(function(){
			
			  if ( $('#nivelEnsino').css('display') == 'block')
				$('#nivelEnsino').toggle( "slow", function() { niv = false;});
				
			   $('#selects').css('display','none');
			   sel = false;
			   
			  if ( $('#TEst').css('display') == 'block'  )
				{$('#TEst').show( "fast", function() {});}
				
				
			if ( $('#checkboxs').css('display') == 'block'  )
			$('#checkboxs').toggle( "slow", function() {check = false;});
							
					
			});	
			
			$('#PDFdiv').click(function(){
			
			  if ( $('#nivelEnsino').css('display') == 'block')
				$('#nivelEnsino').toggle( "slow", function() { niv = false;});
				
			   $('#selects').css('display','none');
			   sel = false;
			   
			  if ( $('#TEst').css('display') == 'block'  )
				{$('#TEst').show( "fast", function() {});}
				
				
			if ( $('#checkboxs').css('display') == 'block'  )
			$('#checkboxs').toggle( "slow", function() {check = false;});
							
					
			});	
			
			//botoes de menu
			$('#NEn').click(function(){
			
			  if ( $('#selects').css('display') == 'block'  )
				$('#selects').toggle( "slow", function() {sel = false;});
				
			  if ( $('#checkboxs').css('display') == 'block'  )
				$('#checkboxs').toggle( "slow", function() {check = false;});
			
				if ( niv == false )
				$('#nivelEnsino').toggle( "slow", function() {niv = true});
				else {
				$('#nivelEnsino').toggle( "slow", function() {niv = false});}						
			});
			
			$('#OptConc').click(function(){
				
			  if ( $('#nivelEnsino').css('display') == 'block'  )
				$('#nivelEnsino').toggle( "slow", function() {niv = false;});
				
			  if ( $('#checkboxs').css('display') == 'block'  )
				$('#checkboxs').toggle( "slow", function() {check = false;});
			
				if ( $('#selects').css('display') == 'none' )
				$('#selects').toggle( "slow", function() {sel = true});
				else {
				$('#selects').toggle( "slow", function() {sel = false});}			
					
			});
			
			$('#Opt').click(function(){
				
			  if ( $('#nivelEnsino').css('display') == 'block'  )
				$('#nivelEnsino').toggle( "slow", function() {niv = false;});
				
			  if ( $('#checkboxs').css('display') == 'block'  )
				$('#checkboxs').toggle( "slow", function() {check = false;});
			
				if ( sel == false )
				$('#selects').toggle( "slow", function() {sel = true});
				else {
				$('#selects').toggle( "slow", function() {sel = false});}			
					
			});
			
			$('#TEst').click(function(){
			
			  if ( $('#nivelEnsino').css('display') == 'block'  )
				$('#nivelEnsino').toggle( "slow", function() { niv = false;});
				
			  if ( $('#selects').css('display') == 'block'  )
				$('#selects').toggle( "slow", function() {sel = false;});
			
				if ( check == false )
				$('#checkboxs').toggle( "slow", function() {check = true});
				else {
				$('#checkboxs').toggle( "slow", function() {check = false});}			
					
			});	
		
	</script>
	
	<script src="../js/sb-admin-2.js"></script>
	<!-- DataTables JavaScript -->
    <script src="../js/plugins/dataTables/jquery.dataTables.min.js"></script>
    <script src="../js/plugins/dataTables/dataTables.bootstrap.js"></script>
	
	
	</html>	