<?

include("conn.php");
$nivel = $_GET['nivel'];
$curso = $_GET['curso'];

				if($_GET["act"]=="escola") {
				
				echo filtros_getEscola();
				
				}				
				
				if($_GET["act"]=="anoletivo") {
				
					echo filtros_getAnoLetivo($_GET['escola']);
				}
				
				if($_GET["act"]=="curso") {

					echo filtros_getCursos($nivel,$_GET['escola'],$_GET['anoletivo']);				
					
				}
				
					
				if($_GET["act"]=="ano") {

					echo filtros_getAno($nivel,$_GET['escola'],$_GET['anoletivo'],$_GET['curso']);
				}
				
				if($_GET["act"]=="isModular")
				{
					//query se o curso modular
					$queryCursoModular="select * from PED_MatrizCursos mc
										inner join PED_Cursos c on mc.ID_MatrizCurso=c.ID_MatrizCurso and c.isActive=mc.isActive
										where c.ID_Curso='".$curso."' and isModular='1'";										
					$resultCursoModular = sqlsrv_query($conn,$queryCursoModular);	
		
					$CursoModular = sqlsrv_has_rows ($resultCursoModular);
					
					if($CursoModular)
					{$CursoModular="true";}
					else {$CursoModular="false";}
					echo $CursoModular;
				}			
				
				if($_GET["act"]=="disciplina" ) {					
										
					echo filtros_getDisciplina($_GET['escola'],$_GET['anoletivo'],$_GET['curso'], $_GET['ano']);
				
				}
				
				elseif($_GET["act"]=="periodo" ) {					
										
					echo filtros_getPeriodo($_GET['escola'],$_GET['anoletivo'],$_GET['curso'], $_GET['ano']);
				
				}
				if($_GET["act"]=="turma") {

					echo filtros_getTurma($_GET['escola'],$_GET['anoletivo'],$_GET['curso'],$_GET['ano'],$_GET['periodo'],$_GET['disciplina']);
				
				}
				
				if($_GET["act"]=="datas" ) {

					echo filtros_datasPeriodos ($_GET['anoletivo'],$_GET['periodo']);
				
				}
				
				?>