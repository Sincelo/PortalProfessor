<?
	require_once ("conn.php");
	
	function filtros_getEscola ($NivEn)	
	{
		global $conn;
		
		if($NivEn == "sec")
		{
			$ID_NivEN = "(5)";
		}
		elseif($NivEn == "23")
		{
			$ID_NivEN = "(3,4)";
		}
		elseif($NivEn == "1")
		{
			$ID_NivEN = "(2)";
		}
				$query1="declare @anolectivo int = 2014
				select distinct E.ID_Escola, E.Designacao  from GER_Escola as E
				inner join PED_Turmas as T on T.ID_Escola = E.ID_Escola 
				inner join ped_cursos as C on C.id_curso = T.id_curso and C.isActive=1
				where T.AnoLectivo = @anolectivo and ID_NivelEnsino in ".$ID_NivEN;				
				
				$result1=sqlsrv_query($conn,$query1);
				$dados=array();
				while($row=sqlsrv_fetch_array($result1,SQLSRV_FETCH_NUMERIC)) {
					$dados[]=array($row[0],utf8_encode($row[1]));
				}
				return json_encode($dados);
	}	
	
	
	function filtros_getAnoLetivo ($escola)	
	{
		global $conn;
		$query2="select Distinct AnoLectivo from PED_Matriculas_AnosLectivos where ID_EscolaMatricula='$escola'
				order by AnoLectivo DESC
																		";
				$result2=sqlsrv_query($conn,$query2);
				$dados=array();
				while($row=sqlsrv_fetch_array($result2,SQLSRV_FETCH_NUMERIC)) {
					$dados[]=array($row[0],utf8_encode($row[0]."/".($row[0]+1)));
				}
				return json_encode($dados);
	}	
	
	function filtros_getCursos ($NivEn,$escola, $AnoLectivo)	
	{
		global $conn;
		
		if($NivEn == "sec")
		{
			$ID_NivEN = "in (5)";
		}
		elseif($NivEn == "23")
		{
			$ID_NivEN = "in ('3','4')";
		}
		elseif($NivEn == "1")
		{
			$ID_NivEN = "in (2)";
		}
		
		$query3="select distinct c.ID_Curso, c.Designacao from ped_cursos as C
				inner join PED_SubTiposCursos as SC on SC.id_subtipocurso = C.id_subtipocurso
				inner join PED_TiposCursos as TC on TC.ID_TipoCurso = SC.ID_TipoCurso 
				inner join PED_Matriculas m on m.ID_Curso=c.ID_Curso 
				inner join PED_Matriculas_AnosLectivos mal on mal.ID_Matricula=m.ID_Matricula 
				inner join PED_MatrizCursos mc on c.ID_MatrizCurso=mc.ID_MatrizCurso and c.isActive=mc.isActive
				 
				where c.ID_NivelEnsino ".$ID_NivEN." and C.isActive ='1' and mal.AnoLectivo='".$AnoLectivo."' and mal.ID_EscolaMatricula='".$escola."'";								
							
					$result3=sqlsrv_query($conn,$query3);
					$dados=array();
						while($row=sqlsrv_fetch_array($result3,SQLSRV_FETCH_NUMERIC)) {
							$dados[]=array($row[0],utf8_encode($row[1]));						}						
						return json_encode($dados);
						
	}	
	
	function filtros_getAno ($NivEn, $escola, $AnoLectivo,$curso)	
	{	
		global $conn;		
		
		if($NivEn == "sec")
		{
			$ID_NivEN = "(5)";
		}
		elseif($NivEn == "23")
		{
			$ID_NivEN = "(3,4)";
		}
		elseif($NivEn == "1")
		{
			$ID_NivEN = "(2)";
		}
		else
		{
			$ID_NivEN = "('')";
		}
		
		
		$query4="select distinct ID_Ano from PED_Turmas t
				inner join PED_Cursos c on t.ID_Curso=c.ID_Curso
				where C.isActive='1' and C.ID_NivelEnsino in ".$ID_NivEN." and c.ID_Curso='".$curso."' and ID_Ano <> -1 and AnoLectivo='".$AnoLectivo."' and ID_Escola='".$escola."'";		
				
				$result4=sqlsrv_query($conn,$query4) or die(print_r(sqlsrv_errors(),1).$query4.__LINE__);
				$dados=array();
				while($row=sqlsrv_fetch_array($result4,SQLSRV_FETCH_NUMERIC)) {
					$dados[]=array($row[0],utf8_encode($row[0]).'º Ano');
				}
				return json_encode($dados);
	}		
	
	function filtros_getPeriodo ($escola, $AnoLectivo,$curso, $Ano)	
	{	
		global $conn;
		
		if(!$Ano)
		{return json_encode(array());}
		$query5="select NPeriodo, Designacao from PED_PeriodosLectivos 
			where AnoLectivo = '".$AnoLectivo."'";
		
		$result5=sqlsrv_query($conn,$query5);
		$dados=array();
		while($row=sqlsrv_fetch_array($result5,SQLSRV_FETCH_NUMERIC)) {
			$dados[]=array($row[0],utf8_encode($row[1]));
		}				
		return json_encode($dados);
	}		
	
	function filtros_getDisciplina ($escola, $AnoLectivo,$curso,$Ano)	
	{	
		global $conn;
		
		if(!$Ano)
		{return json_encode(array());}
		$queryDisciplina="select distinct d.ID_Disciplina, d.Abreviatura from PED_Matriculas m 
					inner join PED_Matriculas_AnosLectivos mal on m.ID_Matricula=mal.ID_Matricula
					inner join PED_Matriculas_Disciplinas md on MD.ID_Matricula = MAL.ID_Matricula and MD.AnoLectivo = MAL.AnoLectivo and MD.ID_Ano = MAL.ID_Ano
					inner join PED_Disciplinas d on d.ID_Disciplina=md.ID_Disciplina
					
					where mal.ID_EscolaMatricula='".$escola."' and md.AnoLectivo='".$AnoLectivo."' and md.ID_Ano=".$Ano." and m.ID_Curso='".$curso."'
					order by d.Abreviatura ASC";				
				
				$resultDisciplina=sqlsrv_query($conn,$queryDisciplina);
				$dados=array();
				while($row=sqlsrv_fetch_array($resultDisciplina,SQLSRV_FETCH_NUMERIC)) {
					$dados[]=array($row[0],utf8_encode($row[1]));
				}				
				return json_encode($dados);
	}	
	
	function filtros_getTurma ($escola, $AnoLectivo,$curso,$Ano,$Periodo,$Disciplina)	
	{	
		global $conn;	
				
			$queryTurma="select distinct t.ID_Turma, t.Designacao from PED_Matriculas_AnosLectivos mal
			inner join PED_Turmas t on t.ID_Turma=mal.ID_Turma
			where mal.ID_Ano='".$Ano."' and mal.AnoLectivo='".$AnoLectivo."' and mal.ID_EscolaMatricula='".$escola."' and t.ID_Curso='".$curso."'
			order by t.Designacao ASC";				
				
			$resultTurma=sqlsrv_query($conn,$queryTurma);
			$dados=array();
			while($row=sqlsrv_fetch_array($resultTurma,SQLSRV_FETCH_NUMERIC)) 
			{
				$dados[]=array($row[0],utf8_encode($row[1]));
			}				
			return json_encode($dados);	
	}
	
	function filtros_datasPeriodos ($AnoLectivo,$Periodo)
	{
		global $conn;
		
		if(!$Periodo)
		{			
			return json_encode(array());
		}
		
		$queryDatasPeriodo = "select convert(varchar, DataInicio, 23) as DataInicio, convert(varchar, DataFim, 23) as DataFim from PED_PeriodosLectivos
		where AnoLectivo=".$AnoLectivo." and NPeriodo=".$Periodo."";
		
		$resultDatasPeriodo = sqlsrv_query($conn, $queryDatasPeriodo) or die(print_r(sqlsrv_errors()));
		$dados=array();
		
		while ($row=sqlsrv_fetch_array($resultDatasPeriodo, SQLSRV_FETCH_NUMERIC))
		{	
			$dados[]=array($row[0],$row[1]);			
		}
		
		return json_encode($dados);
	}

	

?>