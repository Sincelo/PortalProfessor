<?
require_once ("conn.php");
require_once ("FunctionsAvaliacoesFinais.php");

$idProf=$_SESSION['id'];

	if($_GET["act"] == "periodoPDF")
	{		
		echo getPeriodosProf ($_GET['anoletivo'])	;
	}

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
	
	if($_GET["act"]=="turma")
	{		
		echo getTurmasProf($idProf,$_GET['anoletivo'],$_GET['escola'],$_GET['ano']);
	}
	
	if($_GET["act"]=="disciplina")
	{
		$DiretorTurma = getDiretorTurmaProf($idProf, $_GET['turma']);		
		if($DiretorTurma)
			echo json_encode(array('Diretor',json_decode(getDisciplinasProf($idProf,$_GET['anoletivo'],$_GET['escola'],$_GET['ano'],$_GET['turma'],$DiretorTurma))));
		else
			echo getDisciplinasProf($idProf,$_GET['anoletivo'],$_GET['escola'],$_GET['ano'],$_GET['turma'],$DiretorTurma);		
	}
	
	if($_GET["act"] == "resultado")
	{	
		$QCursoTurma = "select ID_Curso from ped_turmas
		where ID_TURMA='".$_GET['turma']."'";
		
		$QCursoTurma=sqlsrv_query($conn,$QCursoTurma);
		
		while ($rowCursoTurma=sqlsrv_fetch_array($QCursoTurma,SQLSRV_FETCH_NUMERIC))
		{
			$curso=$rowCursoTurma[0];
		}
		
		$query="select * from PED_MatrizCursos mc
		inner join PED_Cursos c on mc.ID_MatrizCurso=c.ID_MatrizCurso and c.isActive=mc.isActive
		where c.ID_Curso='".$curso."' and isModular='1' and c.isActive='1'";		
		
		$result = sqlsrv_query($conn, $query);
		
		$isModular=sqlsrv_has_rows ($result);
		
		if($isModular)		
		{
			$modulos = getModulosProf($_GET['disciplina'],$curso, $_GET['anoletivo'], $_GET['ano']);			
			
			if(json_decode($modulos) == null)
			{				
				echo json_encode(array(false, tableAvalFinais ($idProf,$_GET['anoletivo'],$_GET['escola'],$_GET['ano'],$_GET['disciplina'],$_GET['turma'])));
			}
			else
			{				
				$modular = array('isModular',json_decode($modulos));
				echo json_encode($modular);
			}		
		}		
		else
		{
			echo json_encode(array(false, tableAvalFinais ($idProf,$_GET['anoletivo'],$_GET['escola'],$_GET['ano'],$_GET['disciplina'],$_GET['turma'])));
		}		
	}
	
	if($_GET["act"] == "resultadoModulo" && $_GET['modulo'])
	{		
		echo tableAvalFinais ($idProf,$_GET['anoletivo'],$_GET['escola'],$_GET['ano'],$_GET['disciplina'],$_GET['turma'],$_GET['modulo']);
	}
	
	if($_POST["act"] == "InsertNotas")
	{		
		// $idMatricula,$id_curso, $idProf, $anoletivo, $disciplina, $ano, $turma, $periodoletivo, $nota
		$alunos = json_decode($_POST['arrayAlunos']);
		if($_POST['modulo'])
		{		
			if($_POST['edit1'])
			{
				
				$criterioAvaliacao = getCriterioAvaliacao ($_POST['id_curso'], $_POST['id_disciplina'], $_POST['id_ano'], $p, $_POST['modulo']);
				
				foreach ($alunos as $k=>$v)
				{					
					if($_POST['qualitativa'])
					{					
						foreach ($_POST as $kpost=>$vpost)
						{						
							$arrayNotaQ = explode('_', $kpost, 3);
							
							if($arrayNotaQ[0] == 'NotaQ' && $arrayNotaQ[2]==$v && $arrayNotaQ[1]==$p)
							{							
								$_POST['Nota_1_'.$v]=$_POST['NotaQ_1_'.$v];							
							}						
						}									
					}					
					
						$idMatricula = $v;	
					
						$verificacao  = verificaNotas ($_POST['anoletivo'], $criterioAvaliacao,$idMatricula);					
						
							
						if( is_array($verificacao) && ( $verificacao[0] != $_POST['Nota_1_'.$v] || $verificacao [1] != $_POST['Notatxtobs_1_'.$v] || $verificacao [2] != $_POST['Notaabv_1_'.$v] ) ) // caso na edição seja diferente a nota a obs ou o abv
						{							
							$idMat_comment [$v] = $_POST['Notatxtobs_1_'.$v];					
							$idMat_abv [$v] = $_POST['Notaabv_1_'.$v]; 	

							if( $_POST['Nota_1_'.$v] > $_POST['escalaMax'] )
							{								
								header("Location: AvaliacoesFinais.php?erroEscala=1&anoletivo=".$_POST['anoletivo']."&escola=".$_POST['escola']."&ano=".$_POST['id_ano']."&disciplina=".$_POST['id_disciplina']."&turma=".$_POST['id_turma']."&modulo=".$_POST['modulo']."&curso=".$_POST['id_curso']);
								return false;
							}								
							else if(  $_POST['Nota_1_'.$v] < $_POST['escalaMin'] )
							{
								
								header("Location: AvaliacoesFinais.php?erroEscala=1&anoletivo=".$_POST['anoletivo']."&escola=".$_POST['escola']."&ano=".$_POST['id_ano']."&disciplina=".$_POST['id_disciplina']."&turma=".$_POST['id_turma']."&modulo=".$_POST['modulo']."&curso=".$_POST['id_curso']);
								return false;
							}							
							else
							{							
								updateNota ($_POST['anoletivo'], $criterioAvaliacao,$idMatricula, $_POST['Nota_1_'.$v], $idMat_comment [$v], $idMat_abv [$v], $_POST['qualitativa'] , ($_POST['Nota_1_'.$v] != $verificacao[0]?$_POST['dataAval']:false));
							}
						}					
						else if (!$verificacao)
						{							
							if($_POST['Notatxtobs_1_'.$v])
							{					
								$idMat_comment [$v] = $_POST['Notatxtobs_1_'.$v]; 								
							}
						
							if($_POST['Notaabv_1_'.$v])
							{
								$idMat_abv [$v] = $_POST['Notaabv_1_'.$v]; 								
							}
							
							if($_POST['Nota_1_'.$v] || ($idMat_abv[$idMatricula] && !$_POST['Nota_1_'.$v]) || ($idMat_comment[$idMatricula] && !$_POST['Nota_1_'.$v]) )							
							{						
								InsertNotas($idMatricula, $_POST['id_curso'],$idProf, $_POST['anoletivo'], $_POST['id_disciplina'], $_POST['id_ano'], $_POST['id_turma'], $periodoletivo, $_POST['Nota_1_'.$v] , $_POST['dataAval'], $idMat_comment[$idMatricula], $idMat_abv[$idMatricula], $_POST['modulo']);				
							}
						}
				}
			}		
		}
		else
		{			
			if($_POST['edit1'])
			{
				$periodoletivo = 1;
				$p=1;
			}
			if($_POST['edit2'])
			{
				$periodoletivo = 2;
				$p=2;
			}
			if($_POST['edit3'])
			{
				$periodoletivo = 3;
				$p=3;
			}
			
			if($_POST['edit'.$p])
			{
				$criterioAvaliacao = getCriterioAvaliacao ($_POST['id_curso'], $_POST['id_disciplina'], $_POST['id_ano'], $p, $_POST['modulo']);
				
				foreach ($alunos as $k=>$v)			
				{
					
					if($_POST['qualitativa'])
					{					
						foreach ($_POST as $kpost=>$vpost)
						{						
							$arrayNotaQ = explode('_', $kpost, 3);
							
							if($arrayNotaQ[0] == 'NotaQ' && $arrayNotaQ[2]==$v && $arrayNotaQ[1]==$p)
							{							
								$_POST['Nota_'.$p.'_'.$v]=$_POST['NotaQ_'.$p.'_'.$v];															 
							}						
						}									
					}						
					
						$idMatricula = $v;								
						$verificacao = verificaNotas ($_POST['anoletivo'], $criterioAvaliacao,$idMatricula);			
						// echo $criterioAvaliacao."|";
						// echo $verificacao [0]."|" ;
						// echo $_POST['Nota_'.$p.'_'.$v]."|";
						
						if( is_array($verificacao) && ( $verificacao[0] != $_POST['Nota_'.$p.'_'.$v] || $verificacao [1] != $_POST['Notatxtobs_'.$p.'_'.$v] || $verificacao [2] != $_POST['Notaabv_'.$p.'_'.$v] ) ) // caso na edição seja diferente a nota a obs ou o abv
						{							
							$idMat_comment [$v] = $_POST['Notatxtobs_'.$p.'_'.$v];
							
							$idMat_abv [$v] = $_POST['Notaabv_'.$p.'_'.$v]; 	

							if( $_POST['Nota_'.$p.'_'.$v] > $_POST['escalaMax'] )
							{								
								header("Location: AvaliacoesFinais.php?erroEscala=1&anoletivo=".$_POST['anoletivo']."&escola=".$_POST['escola']."&ano=".$_POST['id_ano']."&disciplina=".$_POST['id_disciplina']."&turma=".$_POST['id_turma']."&modulo=".$_POST['modulo']."&curso=".$_POST['id_curso']);
								return false;
							}								
							else if(  $_POST['Nota_'.$p.'_'.$v] < $_POST['escalaMin'] )
							{								
								header("Location: AvaliacoesFinais.php?erroEscala=1&anoletivo=".$_POST['anoletivo']."&escola=".$_POST['escola']."&ano=".$_POST['id_ano']."&disciplina=".$_POST['id_disciplina']."&turma=".$_POST['id_turma']."&modulo=".$_POST['modulo']."&curso=".$_POST['id_curso']);
								return false;
							}							
							else
							{
								updateNota ($_POST['anoletivo'], $criterioAvaliacao,$idMatricula, $_POST['Nota_'.$p.'_'.$v], $idMat_comment [$v], $idMat_abv [$v], $_POST['qualitativa'], ($_POST['Nota_'.$p.'_'.$v] != $verificacao[0]?$_POST['dataAval']:false));							
							}					
						}
						else if ( !$verificacao)
						{							
							if($_POST['Notatxtobs_'.$p.'_'.$v])
							{					
								$idMat_comment [$v] = $_POST['Notatxtobs_'.$p.'_'.$v]; 									
							}
						
							if($_POST['Notaabv_'.$p.'_'.$v])
							{
								$idMat_abv [$v] = $_POST['Notaabv_'.$p.'_'.$v]; 								
							}
							
							if($_POST['Nota_'.$p.'_'.$v] || ($idMat_abv[$idMatricula] && !$_POST['Nota_'.$p.'_'.$v]) || ($idMat_comment[$idMatricula] && !$_POST['Nota_'.$p.'_'.$v]))							
							{							
								InsertNotas($idMatricula, $_POST['id_curso'],$idProf, $_POST['anoletivo'], $_POST['id_disciplina'], $_POST['id_ano'], $_POST['id_turma'], $periodoletivo, $_POST['Nota_'.$p.'_'.$v] , $_POST['dataAval'], $idMat_comment[$idMatricula], $idMat_abv[$idMatricula], $_POST['modulo']);				
							}
						}
				}				
			}			
		}						
	
		// exit;
		header("Location: AvaliacoesFinais.php?avaliacaoInserida=1&anoletivo=".$_POST['anoletivo']."&escola=".$_POST['escola']."&ano=".$_POST['id_ano']."&disciplina=".$_POST['id_disciplina']."&turma=".$_POST['id_turma']."&modulo=".$_POST['modulo']."&curso=".$_POST['id_curso']);
	}

?>