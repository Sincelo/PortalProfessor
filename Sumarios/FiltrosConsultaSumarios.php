<?	
	require_once ("conn.php");
	require_once ("Functions.php");
	
	$idProf=$_SESSION['id'];
	
	if($_GET["act"]=="anoletivo")
	{				
		echo getAnoLetivoProf($idProf);		
	}				
	
	if($_GET["act"]=="escola")
	{	
		echo getEscolaProf($idProf,$_GET['anoletivo']);
	}
	
	if($_GET["act"]=="ano")
	{
		echo getAnoProf($idProf,$_GET['anoletivo'], $_GET['escola']);						
	}	
		
	if($_GET["act"]=="disciplina")
	{
		echo getDisciplinasProf($idProf,$_GET['anoletivo'],$_GET['escola'],$_GET['ano']);
	}

	if($_GET["act"]=="turma")
	{
		echo getTurmasProf($idProf,$_GET['anoletivo'],$_GET['escola'],$_GET['ano'],$_GET['disciplina']);
	}

	if($_GET["act"]=="resultado")
	{		
		echo utf8_encode(TableSumarios($idProf,$_GET['anoletivo'],$_GET['escola'],$_GET['ano'],$_GET['disciplina'],$_GET['turma']));
	}	

	if($_GET["act"]=="sumarios")
	{		
		$res=getAgenda($idProf,$_GET["start"],$_GET["end"],true,(isset($get["turma"])?$get["turma"]:false));		
		
		// $query="SELECT ID_Horario FROM PED_Testes WHERE Data BETWEEN '".$_GET["start"]."' AND '".$_GET["end"]."'";
		$query="SELECT PED_Sumarios.ID_Horario FROM PED_Sumarios INNER JOIN PED_Actas ON PED_Actas.ID_Acta=PED_Sumarios.ID_Acta WHERE PED_Actas.Data BETWEEN CONVERT(DATETIME,'".$_GET["start"]."',120) AND CONVERT(DATETIME,'".$_GET["end"]."',120)";
		$result=sqlsrv_query($conn,$query) or debug( $query.__LINE__.print_r( sqlsrv_errors(), true));
		
		foreach($res as $k=>$v)
		{			
			if($v["ID_NUtente"]==$_SESSION["id"]) $res[$k]["url"]="javascript:InsertSumHorario('".utf8_decode($v['ID'])."','".$v['ID_Tempo']."','".$v['ID_Turma']."','".$v['ID_Disciplina']."','".$v['DataConvert']."');";
		}
		while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_NUMERIC)) 
		{
			foreach($res as $k=>$v) if($v["ID"]==$row[0])
			{
				$res[$k]["backgroundColor"]="#B2B26B";
				$mytime=date("Y-m-d",strtotime($v["start"]));
				$myinitime=date("H:i",strtotime($v["start"]));
				$myendtime=date("H:i",strtotime($v["end"]));
				if($v["ID_NUtente"]==$_SESSION["id"]) $res[$k]["url"]="javascript:InsertSumHorario('".utf8_decode($v['ID'])."','".$v['ID_Tempo']."','".$v['ID_Turma']."','".$v['ID_Disciplina']."','".$v['DataConvert']."');";			
			}
		}
			$res=json_encode(array_map_recursive("utf8_encode",$res));
			echo $res;
	}
	
	if($_POST["act"]=="InsertSumDB")
	{
		if($_POST['NaoTemSumario'])
		{		
			//inserir pelo calendario um sumario que nao existe
			$id_submit=InsertSumDB ($_POST['NumAula'], $_POST['DescSum'], $_POST['Data'], $_POST['idProf'], $_POST['TempoLetivo'], $_POST['idHorario']);
						
			header("Location: index.php?id_submit=".$id_submit."&turma=".$_POST['Turma']."&disciplina=".$_POST['Disciplina']."&id_h=".$_POST['idHorario']."&id_tl=".$_POST['TempoLetivo']."&id_Data=".$_POST['Data']);
		}
		else if($_POST['NaoTemSumarioEmAula'])
		{			
			//inserir um sumario que nao existe em aula (filtros preset)
			InsertSumDB ($_POST['NumAula'], $_POST['DescSum'], $_POST['Data'], $_POST['idProf'], $_POST['TempoLetivo'], $_POST['idHorario'] );							
			header("Location: index.php?InsMarcacaoFaltas=1&turma=".$_POST['Turma']."&disciplina=".$_POST['Disciplina']."&id_h=".$_POST['idHorario']."&id_tl=".$_POST['TempoLetivo']."&id_Data=".$_POST['Data']);					
		}		
		else
		{
			//editar um sumario
			UpdateSumDB ($_POST['ID_Acta'],$_POST['DescSum'], $_POST['idProf']);
			header("Location: index.php?Edit=1&turma=".$_POST['Turma']."&disciplina=".$_POST['Disciplina']."&id_h=".$_POST['idHorario']."&id_tl=".$_POST['TempoLetivo']."&id_Data=".$_POST['Data']);
		}
		exit;		
	}
	
	if($_GET["act"]=="InsertSumHorarioACT")
	{	
		// die($_GET['Disciplina']."|".$_GET['Turma']."|".$_GET['TempoLetivo']."|".$_GET['idHorario']."|".$_GET['idData']);
		echo utf8_encode(InsertSumario($idProf, $_GET['Disciplina'],$_GET['Turma'],$_GET['TempoLetivo'],$_GET['idHorario'],$_GET['idData']));		
	}
	
	if($_GET["act"]=="VisualizarFaltasClickHorario")
	{	
		//die($_GET['Disciplina']."|".$_GET['Turma']."|".$_GET['TempoLetivo']."|".$_GET['idHorario']."|".$_GET['idData']);
		echo utf8_encode(htmlFaltasSumarios ($_GET['Disciplina'],$_GET['Turma'],$_GET['idHorario'],$_GET['TempoLetivo'],$_GET['idData'],$idProf));
	}

	if($_POST["act"]=="InsertFaltas")
	{		
		if($_POST['Edit'] || $_POST['emAula'])
		{						
			DeleteFaltasDB ($_POST['idHorario'],$_POST['TempoLetivo'],$_POST['idProf'],$_POST['Data']);
		}
		foreach ($_POST as $k=>$v)			
		{				
			if((substr($k,0,11) == "InsertFalta") && $v == "on")
			{
				$arrayFalta = explode('_', $k, 3);
				$ID_NUtente=$arrayFalta[2];
				$TipoFalta = $arrayFalta[1];
				
				
				InsertFaltasDB ($_POST['idHorario'],$idProf,$_POST['TempoLetivo'],$ID_NUtente,$TipoFalta,$_POST['Data']);
			}			
		}	
		header("Location: index.php?InsertConcluido=".($_POST['emAula']?"2":"1")."&turma=".$_POST['Turma']."&id_Data=".$_POST['Data']);
	}		
?>		