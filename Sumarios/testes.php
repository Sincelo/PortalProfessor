<?
require_once('conn.php'); //establish the connection with the database on this page.

require_once ('Estrutura.php');

$idProf=$_SESSION['id'];

require_once ('Querys.php');

require_once('Functions.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>

<style>
	#calendar {
		max-width: 900px;
		margin: 0 auto;
	}
</style>
      <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">	
	
	<title>Sumários</title>	
	 <!-- jQuery Version 1.11.2 -->
	<script src="js/jquery-1.11.2.min.js"></script>    
	<script src="js/jquery-ui.min.js"></script>   

    <!-- Bootstrap Core CSS -->  
	<link type="text/css" href="js/jquery-ui.min.css" rel="stylesheet" />
	<link href="css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

	<link href="css/bootstrap.css" rel="stylesheet"> 
	
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
	
	<link href='fullcalendar/fullcalendar.css' rel='stylesheet' />
	<link href='fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
	<script src='fullcalendar/lib/moment.min.js'></script>	
	<script src='fullcalendar/fullcalendar.min.js'></script>
	<script src='fullcalendar/lang-all.js'></script>		
	
</head>
	<body style="cursor: default;">  
	
	<div>
		<?
			echo MenuGeral ();
		?>
	</div>
	<div  id='Next' style='width:5%; float:right; height:90%; z-index:1001;' >
		<div   style ='padding-left:50%; padding-top:350%;'>
			<span   class="glyphicon glyphicon-chevron-right" aria-hidden="true" style='padding-top:100%;' ></span>
		</div>
	</div>
	
	<div  id='Prev' style=' width:5%; float:left; height:90%; z-index:1001; display:none; '>
		<div    style ='padding-left:50%; padding-top:350%; '>
			<span  class="glyphicon glyphicon-chevron-left" style='padding-top:100%;' aria-hidden="true" ></span>
		</div>		
	</div>	
	<div id='Menu' style='position: absolute; padding-left:5%; padding-right:5%; float: center; width:100%; margin:0 auto; align: center;'>
		
		<!-- Small modal -->
<!-- Button trigger modal -->
<div >

	<div class='form-group' style='display:inline; padding-left:1%;'>			
		<select id='anoletivo' class='form-control select' name='anoletivo' style='height:35px; width:15%; display:inline; border-radius:1px;' ><option></option></select>			
		<select id='escola' class='form-control select' name='escola' style='height:35px; width:35%; display:inline; border-radius:1px;' ><option></option></select>
		<select id='ano' class='form-control select' name='ano' style='height:35px; width:10%; display:inline; border-radius:1px;' ><option></option>".JSONtoOption(($Anojson?$Anojson:false) ,0,1,$ano)."</select>
		<select id='turma' class='form-control select' name='turma' style='height:35px; width:10%; display:inline; border-radius:1px;' ><option></option>".JSONtoOption(($Turmajson?$Turmajson:false),0,1,$turma)."</select>
		<select id='disciplina' name='disciplina' class='form-control select' style='height:35px; width:10%; display:inline; border-radius:1px;' ><option></option>".JSONtoOption(($Disciplinajson?$Disciplinajson:false),0,1,$disciplina)."</select>
		<select id='modulo' name='modulo' class='form-control select' style='height:35px; width:10%; display:none; border-radius:1px; border-color:#FF8000' ><option></option>".JSONtoOption(($Modulojson?$Modulojson:false),0,1,$modulo)."</select>
	</div>
		
</div>
	</div>
	
	<script>
	
		$('#butt').click( function () {
			$('#ModalAvalFinal').modal('show');
		});
	
	</script>

								
								
	
</div>
		
	</div>  
	</body>		
	<!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

		 <!-- Metis Menu Plugin JavaScript -->
    <script src="js/plugins/metisMenu/metisMenu.min.js"></script>
    

    <!-- Custom Theme JavaScript -->
    <script src="js/sb-admin-2.js"></script>

	</html> 	