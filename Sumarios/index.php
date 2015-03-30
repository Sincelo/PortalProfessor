<?
require_once('conn.php'); //establish the connection with the database on this page.

require_once ('Estrutura.php');

$idProf = $_SESSION['id'];
$id_submit = $_GET['id_submit'];//variavel depois de submetido pelo calendario
$id_h = $_GET ['id_h'];
$id_tl = $_GET ['id_tl'];

//variaveis que passam ao clicar no calendario
$id_tHorario= $_GET ['id_t'];
$id_dHorario= $_GET ['id_d'];
$id_Data = $_GET['id_Data'];
/////////////////////////////////////////////
$anoletivo = $_GET ['anoletivo'];
$escola = $_GET ['escola'];
$ano = $_GET ['ano'];
$disciplina = $_GET ['disciplina'];
$turma = $_GET ['turma'];

$Edit = $_GET['Edit'];
// $EditEmAula = $_GET['EditEmAula'];
$InsMarcacaoFaltas = $_GET['InsMarcacaoFaltas'];//em modo de inserção
$InsertConcluido = $_GET['InsertConcluido'];//depois de concluida a inserção em modo aulas

require_once ('Querys.php');
require_once('Functions.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
		
	<style>
		#txtSumAulaAnt
		{
			-moz-appearance: textfield-multiline;
			-webkit-appearance: textarea;
			border: 1px solid gray;
			font: medium -moz-fixed;
			font: -webkit-small-control;
			height: 28px;
			overflow: auto;
			padding: 2px;
			width: 400px;
			resize: none;
		}
		
	#calendarSum
	{
		width:100%;
		padding-left:5%;		
	}
	</style>
	
	<title>Sumários</title>	
	 <!-- jQuery Version 1.11.2 -->
	<script src="js/jquery-1.11.2.min.js"></script>
	<script src="js/jquery-ui.min.js"></script>
	
	<link href='fullcalendar/fullcalendar.css' rel='stylesheet' />
	<link href='fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
	<script src='fullcalendar/lib/moment.min.js'></script>	
	<script src='fullcalendar/fullcalendar.min.js'></script>
	<script src='fullcalendar/lang-all.js'></script>
	
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
	<script src="js/bootstrap.js"></script>
	 <!-- Timeline CSS -->
    <link href="css/plugins/timeline.css" rel="stylesheet">
	<!-- DataTables CSS -->
    <link href="css/plugins/dataTables.bootstrap.css" rel="stylesheet">    

    <!-- Bootstrap Core CSS -->  
	<link type="text/css" href="js/jquery-ui.theme.css" rel="stylesheet" /> 	
	<link type="text/css" href="js/jquery-ui.min.css" rel="stylesheet" />
	<link href="css/bootstrap.css" rel="stylesheet">
	<link href="css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">
	<link href="css/bootstrap-theme.min.css" rel="stylesheet">    
	
    <!-- Custom CSS -->
    <link href="css/sb-admin-2.css" rel="stylesheet">
	<!--se for preciso adicionar o thumbnail gallery -->

    <!-- Custom Fonts -->
    <link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">	
	<link rel="shortcut icon" href="PAAE.ico">	
	
	  <script src="js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>
  	
	<script src="js/bootstrap3-wysihtml5.all.min.js"></script> 
	<script src="js/bootstrap-wysihtml5.pt-BR.js"></script> 
	<link rel="stylesheet" type="text/css" href="css/bootstrap3-wysihtml5.min.css"></link>
	
		
</head>
	<body style="cursor: default;">  
	
	<div style='overflow:auto;'>
	
		<div>
			<?
				echo MenuGeral ();
			?>
		</div>
	
		<div id='Next' style='width:5%; float:right; height:90%; z-index:1001; display:none; position:relative;' >
			<div style ='padding-left:50%;'>
				<span  class="glyphicon glyphicon-chevron-right" aria-hidden="true" style='padding-top:100%;' ></span>
			</div>
		</div>	
	
		<div id='Prev' style=' width:5%; float:left; height:90%; z-index:1001; display:block; position:absolute;'>
			<div    style ='padding-left:50%;'>
				<span  class="glyphicon glyphicon-chevron-left" style='padding-top:100%;' aria-hidden="true" ></span>
			</div>		
		</div>	
				<?	
					//variáveis de filtros
					$FiltrosPresetJson = getPresetFiltros($idProf);		
					$FiltrosPreset = json_decode ($FiltrosPresetJson);					
					
					if($FiltrosPresetJson)
					{
						$AnoLetivoPreset = $FiltrosPreset[0][0];					
						$EscolaPreset = $FiltrosPreset[1][0];					
						$AnoPreset = $FiltrosPreset[2][0];				
						$DisciplinaPreset = $FiltrosPreset[3][0];				
						$TurmaPreset = $FiltrosPreset[4][0];				
						//para a inserção de dados					
						$TempoLetivo = $FiltrosPreset[5][0];					
						$idHorario = $FiltrosPreset[6][0];
					}					
					else
					{
						$AnosLetivosjson = getAnoLetivoProf ($idProf);		
						$AnoLetivoArray = json_decode($AnosLetivosjson);						
						$AnoLetivoPreset = $AnoLetivoArray[0][0];
						
						$Escolajson = getEscolaProf ($idProf,($AnoLetivoPreset?$AnoLetivoPreset:$AnoLetivoArray[0][0]));
						$EscolaArray = json_decode($Escolajson);
						$EscolaPreset = $EscolaArray[0][0];
					}				
				?>		
		<div  id='Pagina0' style='position:absolute; float:center; width:90%; padding-left:5%; display:none;'>
			<div>		
				<?					
					echo filtrosConsultaSumarios ($idProf,$AnoLetivoPreset,$EscolaPreset,$AnoPreset,$DisciplinaPreset,$TurmaPreset);//filtro de consulta					
				?>
			</div>	
			
			<div id='div_tablesumarios'>	
				<?					
					echo utf8_encode(TableSumarios ($idProf,$AnoLetivoPreset,$EscolaPreset,$AnoPreset,$DisciplinaPreset,$TurmaPreset));//table de sumarios										
				?>
			</div>			
		</div>			
		<div  id='Pagina1' style='position:absolute; float:center; width:90%; padding-left:5%; display:block;'>
			<?	
				if($FiltrosPresetJson)
				{
					echo utf8_encode(InsertSumario ($idProf,($DisciplinaPreset?$DisciplinaPreset:$id_dHorario),($TurmaPreset?$TurmaPreset:$id_tHorario),($id_tl?$id_tl:$TempoLetivo),($id_h?$id_h:$idHorario), $id_Data, $id_submit));
				}
				if(!$FiltrosPresetJson)
				{					
					echo InsertCalendar ($idProf,$AnoLetivoPreset,$TurmaPreset?$TurmaPreset:$turma,$id_Data);												
				}				
			?>
				<div id='calendarSum'></div>			
		</div>
		<div id='Pagina2'  style='position:absolute; padding-left:5%; float:center; width:90%; display:none;'>				
			<?
			if(!$FiltrosPresetJson)
			{			
				if($id_submit || $Edit)
				{					
					echo utf8_encode(InsertSumario ($idProf,($DisciplinaPreset?$DisciplinaPreset:$disciplina),($TurmaPreset?$TurmaPreset:$turma),($id_tl?$id_tl:$TempoLetivo),($id_h?$id_h:$idHorario), $id_Data, $id_submit));					
				}
			}
			else if ($FiltrosPresetJson)
			{
				if ($InsMarcacaoFaltas)
				{
					echo utf8_encode(htmlFaltasSumarios ($disciplina,$turma,$id_h,$id_tl,$id_Data,$idProf,$InsMarcacaoFaltas));	
				}
				else 
				{					
					$id_Data = date('Y-m-d');
					$emAula=1;
							
					echo utf8_encode(htmlFaltasSumarios ($DisciplinaPreset,$TurmaPreset,$idHorario,$TempoLetivo,$id_Data,$idProf,$InsMarcacaoFaltas,$Edit,$emAula));
				}
			}			
			?>		
		</div>
		<?			
			if(!$FiltrosPresetJson)
			{
				echo "<div id='Pagina3' style='position:absolute; padding-left:5%; float:center; width:90%; display:none;'>";
				
				if($id_submit)
				{
					$InsMarcacaoFaltas=1;
					
					echo utf8_encode(htmlFaltasSumarios ($disciplina,$turma,$id_h,$id_tl,$id_Data,$idProf,$InsMarcacaoFaltas,$Edit, $id_submit));													
				}
				else if ($Edit)
				{					
					echo utf8_encode(htmlFaltasSumarios ($disciplina,$turma,$id_h,$id_tl,$id_Data,$idProf,$InsMarcacaoFaltas,$Edit));							
				}				
				echo "					
				</div>
				";			
			}
			
		?>	
		
	</div>	
	<?
		if($Edit || $id_submit || $InsMarcacaoFaltas || $InsertConcluido)
		{			
			echo Modal ();			
		}	
	?>		
	
	<div class='modal fade' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true' id='modalFaltasDisciplinar' >
		<div class='modal-dialog'>
			<div class='modal-content'>						  
				<div class='modal-body' id='modalText'>
					Falta Disciplinar é equivalente a uma Falta de Presença.
				</div>									  
		</div>
	  </div>
	</div>
		
	
	
	</body>	
	
	<script>
	
	var id_submit = <? echo json_encode($id_submit); ?>;
	var id_Data = <? echo json_encode($id_Data); ?>;
	var Edit = <? echo json_encode($Edit); ?>;
	var InsMarcacaoFaltas = <? echo json_encode($InsMarcacaoFaltas); ?>;
	var InsertConcluido = <? echo json_encode($InsertConcluido); ?>;	
	var FiltrosPresetJson = <? echo json_encode($FiltrosPresetJson); ?>;
	var jaexiste = 0;
	var omeutimeout;
	
	$(function()
	{		
		if(id_submit || (Edit && !FiltrosPresetJson))
		{			
			$("#Pagina1").css("display", "none");
			$("#Pagina2").css("display", "block");
			$('#Sumario_Nav').removeClass('disabled');
		}
		if(FiltrosPresetJson)
		{			
			$('#Next').show();
			$('#Sumario_Nav').removeClass('disabled');
			$('#Calendar_Nav').addClass('disabled');	
		}
	});
	
	//coloca a turma no span vindo dos filtros	
	
	//função de direcionar para inserir sumários quando não se encontra em aula
	function InsertSumHorario (idHorario, idTempo, idTurma, idDisciplina, idData, Edit, id_submit )
	{		
		$.get('FiltrosConsultaSumarios.php',{act:'InsertSumHorarioACT',Disciplina:idDisciplina,Turma:idTurma, TempoLetivo:idTempo, idHorario:idHorario, idData:idData}).done(function(data) 
			{				
				jaexiste=0;
				$('#Pagina2').html(data);
				$('#Next').show();
				slideNext();
				$('#Sumario_Nav').removeClass('disabled');
			}
		);

		$.get('FiltrosConsultaSumarios.php',{act:'VisualizarFaltasClickHorario',Disciplina:idDisciplina,Turma:idTurma, TempoLetivo:idTempo, idHorario:idHorario, idData:idData}).done(function(data) 
		{			
			$('#Pagina3').html(data);			
		});		
	}
	
	function SlideMarcacaoFaltas (InsMarcacaoFaltas,id_submit,Edit,InsertConcluido)
	{
		if(InsMarcacaoFaltas || id_submit || Edit || (InsertConcluido == 2))
		{			
			slideNext();
		}
	}
	
	$( '#butCloseModal' ).click ( function () { SlideMarcacaoFaltas(InsMarcacaoFaltas,id_submit, Edit, InsertConcluido); clearTimeout(omeutimeout); });//se fechar modal passa para a marcacao de faltas	
	
	$(function ()
	{
		omeutimeout=setTimeout( function () { $('#modalAlert').modal('hide'); SlideMarcacaoFaltas(InsMarcacaoFaltas,id_submit, Edit, InsertConcluido); }, 3000);	
	});
	
	if((id_Data == null || id_submit == null) && InsMarcacaoFaltas == null && $("#DescSum").is(":visible"))
	{		
		$('#DescSum').wysihtml5({
		toolbar: {
			"font-styles": false, //Font styling, e.g. h1, h2, etc. Default true
			"emphasis": true, //Italics, bold, etc. Default true
			"lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
			"html": false, //Button which allows you to edit the generated HTML. Default false
			"link": false, //Button to insert a link. Default true
			"image": false, //Button to insert an image. Default true,
			"color": false, //Button to change color of font 
			"fa": true
		},
			locale: "pt-BR"			
		});
		
		jaexiste = 1;	
	}

	else if (Edit)	
	{
		$(function() { $('#modalAlert').modal('show')});
		$('#textoWarning').text('Sumário Editado Com Sucesso');	
	}
	else
	{
		$(function() { $('#modalAlert').modal('show')});
		$('#textoWarning').text('Sumário Submetido Com Sucesso');	
	}					
	
	function slideNext()
	{	
		
		if(	$('#Pagina0').is(':visible') )
		{
			$('#Pagina0').hide('slide',{direction:'left',easing:'swing'},1500);
			$('#Pagina1').show('slide',{direction:'right',easing:'swing'},1500, function () {
			$('#Prev').show();
			
			if(FiltrosPresetJson)
			{
				$('#Next').show();
			}
			else
			{				
				$('#Next').hide();
			}			
			});
		}
		
		else if( $('#Pagina1').is(':visible'))		
		{
			if( $('#Next').is(':visible'))
			{
				$('#Pagina1').hide('slide',{direction:'left',easing:'swing'},1500);
				$('#Pagina2').show('slide',{direction:'right',easing:'swing'},1500, function(){
				
				if(	!($('#DescSum').val()) && jaexiste!=1)	
				{					
					wysi();//se nao tiver texto aparece
					jaexiste = 1;
				}			
				});			
			
				$('#Prev').show()
				$('#Next').show();
				if(FiltrosPresetJson)
				{				
					$('#Next').hide();
				}
				else
				{
					$('#Next').show();	
				}
			}				
		}

		else if( $('#Pagina2').is(':visible') )
		{
			$('#Pagina2').hide('slide',{direction:'left',easing:'swing'},1500);
			$('#Pagina3').show('slide',{direction:'right',easing:'swing'},1500, function(){});
			$('#Prev').show();
			$('#Next').hide();
		}
	}
	
	function slidePrev()
	{
		
		if(	$('#Pagina1').is(':visible') )
		{			
			$('#Pagina1').hide('slide',{direction:'right',easing:'swing'},1500, function(){	});
			$('#Pagina0').show('slide',{direction:'left', easing:'swing'},1500, function(){$('#Next').show();});		
			$('#Prev').hide();
			$('#Next').show();
		}
		else if ( $('#Pagina2').is(':visible') )
		{
				// $('#Pagina2').animate({right:'-90%'},1000).done(function () { $('#Pagina2').hide() });
				$('#Pagina2').hide('slide',{direction:'right',easing:'swing'},1500);
				$('#Pagina1').show('slide',{direction:'left',easing:'swing'},1500, function(){$('#Next').show();});		
				$('#Prev').show();
				$('#Next').show();
		}
		else if ( $('#Pagina3').is(':visible') )
		{
				$('#Pagina3').hide('slide',{direction:'right',easing:'swing'},1500);
				$('#Pagina2').show('slide',{direction:'left',easing:'swing'},1500, function(){$('#Next').show();});		
				$('#Prev').show();
				$('#Next').show();
		}	
		
		
	}
	//para botões
	$('#Next').click(function()
	{
		slideNext();
	});
	
	  $('#Prev').click(function()
	  {				
		slidePrev();
	  });
	  
	//para keys
	$("body").keydown(function(e) {
	if(e.keyCode == 37) { // left
		slidePrev();
	}
	else if(e.keyCode == 39) { // right
		slideNext();
	}
	});	
	
	
	$('#ListaSumarios_Nav').click(function()
	{
		var direction;
		
		if( !($("#Pagina0").is(":visible")) )
		{	
			if( ($("#Pagina1").is(":visible")) )
			{
				$('#Pagina1').hide('slide',{direction:'right',easing:'swing'},1500, function(){	});
				
				direction = 'left';
				
			}
			
			if( ($("#Pagina2").is(":visible")) )
			{
				$('#Pagina2').hide('slide',{direction:'right',easing:'swing'},1500, function(){	});
				
				direction = 'left';
			}
			
			if( ($("#Pagina3").is(":visible")) )
			{
				$('#Pagina3').hide('slide',{direction:'right',easing:'swing'},1500, function(){	});
				
				direction = 'left';
			}
			
			$('#Pagina0').show('slide',{direction: direction, easing:'swing'},1500, function(){});
		}
	
	});
	
	$('#Calendar_Nav').click(function()
	{	
		var direction;
		
		if( !($("#Pagina1").is(":visible")) )
		{	
			if( ($("#Pagina0").is(":visible")) )
			{
				$('#Pagina0').hide('slide',{direction:'left',easing:'swing'},1500, function(){	});
				direction = 'right';
			}
			
			if( ($("#Pagina2").is(":visible")) )
			{
				$('#Pagina2').hide('slide',{direction:'right',easing:'swing'},1500, function(){	});
				direction = 'left';
			}
			
			if( ($("#Pagina3").is(":visible")) )
			{
				$('#Pagina3').hide('slide',{direction:'right',easing:'swing'},1500, function(){	});
				direction = 'left';
			}
			
			$('#Pagina1').show('slide',{direction:direction, easing:'swing'},1500, function(){});			
		}
	});
	
	$('#Sumario_Nav').click(function()
	{
		var direction;
		
		if(FiltrosPresetJson)
		{			
			if( !($("#Pagina1").is(":visible")) )
			{	
				if( ($("#Pagina0").is(":visible")) )
				{
					$('#Pagina0').hide('slide',{direction:'left',easing:'swing'},1500, function(){	 });
					direction = 'right';
				}
				
				if( ($("#Pagina2").is(":visible")) )
				{					
					$('#Pagina2').hide('slide',{direction:'right',easing:'swing'},1500, function(){	});
					direction = 'left';
				}
				
				if( ($("#Pagina3").is(":visible")) )
				{
					$('#Pagina3').hide('slide',{direction:'right',easing:'swing'},1500, function(){	});
					direction = 'left';
				}
				
				$('#Pagina1').show('slide',{direction:direction, easing:'swing'},1500, function(){});
			}
		}
		else
		{
			if( !($("#Pagina2").is(":visible")) )
			{	
				if( ($("#Pagina0").is(":visible")) )
				{
					$('#Pagina0').hide('slide',{direction:'left',easing:'swing'},1500, function(){	});
					direction = 'right';
				}
				
				if( ($("#Pagina1").is(":visible")) )
				{
					$('#Pagina1').hide('slide',{direction:'left',easing:'swing'},1500, function(){	});
					direction = 'right';
				}
				
				if( ($("#Pagina3").is(":visible")) )
				{
					$('#Pagina3').hide('slide',{direction:'right',easing:'swing'},1500, function(){	});
					direction = 'left';
				}
				
				$('#Pagina2').show('slide',{direction:direction, easing:'swing'},1500, function(){});
			}
		}	
	});
	
	$('#Faltas_Nav').click(function()
	{
		var direction;
		if(FiltrosPresetJson)
		{
			if( !($("#Pagina2").is(":visible")) )
			{	
				if( ($("#Pagina0").is(":visible")) )
				{
					$('#Pagina0').hide('slide',{direction:'left',easing:'swing'},1500, function(){});
					direction = 'right';
				}
				
				if( ($("#Pagina1").is(":visible")) )
				{
					$('#Pagina1').hide('slide',{direction:'left',easing:'swing'},1500, function(){});
					direction = 'right';
				}
				
				if( ($("#Pagina3").is(":visible")) )
				{
					$('#Pagina2').hide('slide',{direction:'left',easing:'swing'},1500, function(){});
					direction = 'right';
				}
				
				$('#Pagina2').show('slide',{direction:direction, easing:'swing'},1500, function(){ $('#Next').hide(); });
			}
		}
		else
		{
			if( !($("#Pagina3").is(":visible")) )
			{	
				if( ($("#Pagina0").is(":visible")) )
				{
					$('#Pagina0').hide('slide',{direction:'left',easing:'swing'},1500, function(){});
					direction = 'right';
				}
				
				if( ($("#Pagina1").is(":visible")) )
				{
					$('#Pagina1').hide('slide',{direction:'left',easing:'swing'},1500, function(){});
					direction = 'right';
				}
				
				if( ($("#Pagina2").is(":visible")) )
				{
					$('#Pagina2').hide('slide',{direction:'left',easing:'swing'},1500, function(){});
					direction = 'right';
				}
				
				$('#Pagina3').show('slide',{direction:direction, easing:'swing'},1500, function(){ $('#Next').hide(); });
			}
		}
		
	});	
		
	</script>	

		 <!-- Metis Menu Plugin JavaScript -->
    <script src="js/plugins/metisMenu/metisMenu.min.js"></script>
    

    <!-- Custom Theme JavaScript -->
    <script src="js/sb-admin-2.js"></script>	
	</html> 	