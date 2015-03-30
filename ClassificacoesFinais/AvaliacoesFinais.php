<?
require_once('conn.php');

require_once('Estrutura.php');

require_once('FunctionsAvaliacoesFinais.php');

$idProf = $_SESSION['id'];

//variaveis depois de inserção
	$anoletivo = $_GET['anoletivo'];
	$escola = $_GET['escola'];
	$ano = $_GET['ano'];
	$disciplina = $_GET['disciplina'];
	$turma = $_GET['turma'];
	$modulo = $_GET['modulo'];
	$curso = $_GET['curso'];
	$periodo = $_GET['periodo'];
	$avaliacaoInserida = $_GET['avaliacaoInserida'];
	$data = date('Y-m-d'); 
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	
	<title>Classificações Finais</title>	
	 <!-- jQuery Version 1.11.2 -->
	<script src="../js/jquery-1.11.2.min.js"></script>
	<script src="../js/jquery-ui.min.js"></script>
	
    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>
	<script src="../js/bootstrap.js"></script>
	 <!-- Timeline CSS -->
    <link href="../css/plugins/timeline.css" rel="stylesheet">
	<!-- DataTables CSS -->
    <link href="../css/plugins/dataTables.bootstrap.css" rel="stylesheet">    

    <!-- Bootstrap Core CSS -->  
	<link type="text/css" href="../js/jquery-ui.theme.css" rel="stylesheet" /> 	
	<link type="text/css" href="../js/jquery-ui.min.css" rel="stylesheet" />
	<link href="../css/bootstrap.css" rel="stylesheet">
	<link href="../css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">
	<link href="../css/bootstrap-theme.min.css" rel="stylesheet">    
	
    <!-- Custom CSS -->
    <link href="../css/sb-admin-2.css" rel="stylesheet">
	<!--se for preciso adicionar o thumbnail gallery -->

    <!-- Custom Fonts -->
    <link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">	
	<link rel="shortcut icon" href="PAAE.ico">	
	
	  <script src="../js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="../js/plugins/dataTables/dataTables.bootstrap.js"></script> 
	<style>		
		.ck-button input:checked + span
		{		   
			background-color: #428bca;
			border-color: #428bca;			
		}					
	
		.glyphicon-plus
		{
			cursor:pointer;
		}
		
		.glyphicon-search
		{
			cursor:pointer;
		}

		.table
		{
			font-size:12px;
			white-space: nowrap;	
		}
		
		.table > tbody > tr > td
		{
		  padding: 4px;
		  line-height: 1.42857143;
		  vertical-align: center;
		  border-top: 1px solid #ddd;
		}
		
		.form-control {		
			border-radius:2px;
		}
	</style>
</head>

<body>
	<div id="AvalFinais">
		<div id='div_filtrosavalfinal'>		
			<?
				// echo PrintBanner ($modulo, $disciplina, $anoletivo);
				echo filtrosAvalFinais ($idProf,$anoletivo, $escola, $ano, $disciplina, $turma, $modulo, $avaliacaoInserida, $curso);
			?>
		</div>
		<form method='post' id='formInsertAval' role='form' action='FiltrosAvalFinaisGET.php' name='InsertNotas'>			
		<div style='padding-top:0.5%;'>
			<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
				<div class='panel panel-primary'>
				<div class='panel-heading' style='padding: 2px 15px;'>
					Classificações Finais
					<div style='display:inline; padding-left:50%;'>
						Data de Avaliação:
						<input type='text' id='dataAval' name='dataAval' placeholder='Data de Avaliação' class='form-control' style='display:inline; height:25px; width:150px;' value='<?echo $data?>'>
					</div>
					<div style='display:inline; padding-left:2%;'>
						<button type='button' class='btn btn-default subAval' id='subAval1' style='padding: 3px 6px;'>Guardar</button>
					</div>				
				</div>				
			<div id='div_avalfinal'>	
			<?				
				echo tableAvalFinais($idProf, $anoletivo, $escola, $ano, $disciplina, $turma,$modulo, $avaliacaoInserida);
			?>
			</div>
			</div>
			</div>
		</div>		
	</div>
	
	<div class="modal fade" id='ModalAvalEscolherPeriodo'>
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">			
			<h4 class="modal-title" id='textoModalEscolherPeriodo'></h4>
		  </div>		  
		<div class="modal-body" id='modalbodyPeriodo'>
			<? echo filtrosPeriodoPDF ($anoletivo); ?>
		</div>
		</div>
	  </div>
	</div>
	
	<div class="modal fade" id='ModalAvalFinalSubmit'>
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">			
			<h4 class="modal-title" id='textoModalSub'>Modal title</h4>
		  </div>		  
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" id='closeModalAvalFinalsub' data-dismiss="modal">Fechar</button>
			<button type='button' class='btn btn-primary subAval' id='subAval2'>Guardar</button>		
		  </div>		
		</div>
	  </div>
	</div>
	
	<div class="modal fade" id='ModalAvalFinal'>
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header" style='padding:8px;'>			
			<h4 class="modal-title" id='textoModal'>Modal title</h4>
		  </div>		  
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" id='closeModalAvalFinal' data-dismiss="modal">Fechar</button>			
		  </div>
		</div>
	  </div>
	</div>
	
	<div class="SpinningWheel"><!-- Place at bottom of page --></div>
	
</body> 
	<script>
	var avaliacaoInserida = <? echo json_encode($avaliacaoInserida); ?>;
	var table; 	
		$(function()
		{		
			$('#anoletivo').change();						
			
			if(avaliacaoInserida)
			{
				// $('#subAval').addClass('disabled');
				$('#ModalAvalFinal').modal('show');
				$('#textoModal').text('Avaliações Inseridas com Sucesso');				
			}	
			
			$( '#closeModalAvalFinal' ).click ( function () { clearTimeout(omeutimeout); });//se fechar modal passa para a marcacao de faltas	
			
			omeutimeout=setTimeout( function () { $('#ModalAvalFinal').modal('hide');}, 3000);			
				
			$( '#dataAval' ).datepicker({dateFormat: 'yy-mm-dd',onSelect:function(){},
				dateFormat: 'dd/mm/yy',
				dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
				dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
				dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
				monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
				monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
				nextText: 'Próximo',
				prevText: 'Anterior'			
			});
			
		});	

		$('.subAval').click(function () {
			var verifica =true;
			
			$('.testeNotas').each(
				function (data){
					
					idSplit = $(this).attr('name');
					idSplitted = idSplit.split("_");			
					
					verifica = TestarEscala(idSplitted[2],idSplitted[1]) && verifica;
					
					if (verifica)
					{												
						$('#ModalAvalFinal').modal('show');
						$('#textoModal').text('Não é possivel inserir valores fora da escala de avaliação');
						return false;						
					}					
					$('#formInsertAval').submit();				
				}
			);
			
		});
		
		function exportPDF()
		{
			$('#ModalAvalEscolherPeriodo').modal('show');
			$('#textoModalEscolherPeriodo').text('Selecione o Período');
			$('#periodo').change();				
		}
		
		$('#subAvalPeriodo').click ( function () {
					var win = window.open('AvalFinalPDF.php?anoletivo='+$('#anoletivo').val()+'&escola='+$('#escola').val()+'&ano='+$('#ano').val()+'&turma='+$('#turma').val()+'&periodo='+$('#periodo').val(), 'PDF');
					if(win){					
						win.focus();
					}
					else
					{					
						alert('Please allow popups for this site');
					}						
			});
		
	</script>	 
    <script src="../js/plugins/metisMenu/metisMenu.min.js"></script>
    <script src="../js/sb-admin-2.js"></script>	
</html>
