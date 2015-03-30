<?
	//$idProf = $_SESSION['id'];		
	
	//Filtros de Sumarios
	//substituir por esta depois dos testes
	$QFiltrosPreset  = "
	DECLARE @diaAtual as int
	SET @diaAtual = (datepart(dw,getdate())-1)
	declare @dataatual as date
	set @dataatual =  DATEADD(day, DATEDIFF(day, 0, GETDATE()), 0)

	select distinct pl.AnoLectivo, t.ID_Escola idEscola, e.Designacao escolaNome, t.ID_Ano Ano, d.ID_Disciplina id_d, d.Sigla sigla, t.ID_Turma id_t, t.Designacao turma, tl.HoraInicial, tl.ID_Tempo tempoLetivo, ht.ID_Horario id_h from PED_Horarios_Turmas ht
	inner join PED_Horarios_Turmas_Professores htp on htp.ID_Horario=ht.ID_Horario
	inner join PED_PeriodosLectivos pl on pl.ID_NPeriodo=ht.ID_NPeriodo
	inner join PED_TemposLectivos tl on tl.ID_Tempo=ht.ID_Tempo
	inner join PED_Turmas t on t.ID_Turma=ht.ID_Turma and pl.AnoLectivo=t.AnoLectivo and t.AnoLectivo is not null
	inner join GER_Escola as E on E.ID_Escola = T.ID_Escola
	inner join PED_Disciplinas d on d.ID_Disciplina=ht.ID_Disciplina

	where htp.ID_NUtente='".$idProf."' and tl.isActivo=1
	and CONVERT(VARCHAR(5),getdate(),108) BETWEEN CONVERT(varchar(5),HoraInicial,108) and  CONVERT(varchar(5),HoraFinal,108) and ID_Dia= @diaAtual 
	and @dataatual between DATEADD(day, DATEDIFF(day, 0, pl.DataInicio), 0) and DATEADD(day, DATEDIFF(day, 0, pl.DataFim), 0)
	order by tl.HoraInicial ASC
	";
	
	//substituir pela cima depois dos testes	
	$QFiltrosPreset__Testes = "DECLARE @diaAtual as int
	SET @diaAtual = (datepart(dw,getdate())-1)
	declare @dataatual as date
	set @dataatual =  DATEADD(day, DATEDIFF(day, 0, GETDATE()), 0)

	select distinct pl.AnoLectivo, t.ID_Escola idEscola, e.Designacao escolaNome, t.ID_Ano Ano, d.ID_Disciplina id_d, d.Sigla sigla, t.ID_Turma id_t, t.Designacao turma, tl.HoraInicial, tl.ID_Tempo tempoLetivo, ht.ID_Horario id_h from PED_Horarios_Turmas ht
	inner join PED_Horarios_Turmas_Professores htp on htp.ID_Horario=ht.ID_Horario
	inner join PED_PeriodosLectivos pl on pl.ID_NPeriodo=ht.ID_NPeriodo
	inner join PED_TemposLectivos tl on tl.ID_Tempo=ht.ID_Tempo
	inner join PED_Turmas t on t.ID_Turma=ht.ID_Turma and pl.AnoLectivo=t.AnoLectivo and t.AnoLectivo is not null
	inner join GER_Escola as E on E.ID_Escola = T.ID_Escola
	inner join PED_Disciplinas d on d.ID_Disciplina=ht.ID_Disciplina

	where htp.ID_NUtente='20000000000035' and tl.isActivo=1
	and CONVERT(VARCHAR(5),'09:20',108) BETWEEN CONVERT(varchar(5),HoraInicial,108) and  CONVERT(varchar(5),HoraFinal,108) and ID_Dia= 5--@diaAtual 
	and @dataatual between DATEADD(day, DATEDIFF(day, 0, pl.DataInicio), 0) and DATEADD(day, DATEDIFF(day, 0, pl.DataFim), 0)
	order by tl.HoraInicial ASC";
	
	//função com query para preencher tabela de consulta
	function QueryConsultaSumarios($idProf,$Disciplina,$Turma)
	{		
		return "
		
		SELECT PA.ID_Acta id_act, PA.NumActa,CONVERT(VARCHAR(10),PA.Data, 20) as Data, CONVERT(VARCHAR(5),TL.HoraInicial, 108) Hora, PA.Descricao, PS.ID_Horario, PS.ID_TempoLectivo, PS.ID_TipoHorario
		FROM PED_Actas PA
		INNER JOIN PED_Sumarios PS ON PS.ID_Acta=PA.ID_Acta
		INNER JOIN PED_Horarios_Turmas HT ON PS.ID_Horario=HT.ID_Horario
		INNER JOIN PED_Horarios_Turmas_Professores HTP ON HTP.ID_Horario=HT.ID_Horario
		INNER JOIN PED_TemposLectivos TL ON TL.ID_Tempo=HT.ID_Tempo					
		WHERE PA.isSumario=1 AND		
		HT.ID_Disciplina='".$Disciplina."' and
		HT.ID_Turma='".$Turma."' AND
		HTP.ID_NUtente='".$idProf."'
		order by NumActa Desc		
		";	
	}
	
	function QueryAlunosFaltas($idProf,$Disciplina,$Turma)
	{		
		return "
		SELECT PA.ID_Acta id_act, fa.ID_TipoFalta TipoFalta, tf.Abreviatura abv, md.NumeroAluno
		FROM PED_Actas PA
		INNER JOIN PED_Sumarios PS ON PS.ID_Acta=PA.ID_Acta
		INNER JOIN PED_Horarios_Turmas HT ON PS.ID_Horario=HT.ID_Horario
		INNER JOIN PED_Horarios_Turmas_Professores HTP ON HTP.ID_Horario=HT.ID_Horario
		INNER JOIN PED_TemposLectivos TL ON TL.ID_Tempo=HT.ID_Tempo
		inner join PED_FaltasAlunos fa on fa.ID_Horario=ht.ID_Horario and ht.ID_Tempo=fa.ID_TempoLectivo and PA.Data=fa.Data and fa.ID_TempoLectivo=tl.ID_Tempo
		inner join PED_TiposFalta tf on tf.ID_TipoFalta=fa.ID_TipoFalta
		inner join PED_Matriculas m on m.ID_NUtente=fa.ID_NUtente
		inner join PED_Matriculas_Disciplinas md on ht.ID_Disciplina=md.ID_Disciplina and md.ID_Turma=ht.ID_Turma and md.ID_Matricula=m.ID_Matricula
						
		WHERE PA.isSumario=1 AND
		TF.ID_TipoUtente = 1 AND
		HT.ID_Disciplina='".$Disciplina."' and
		HT.ID_Turma='".$Turma."' AND
		HTP.ID_NUtente='".$idProf."'
		order by id_act, TipoFalta, NumeroAluno asc
		";	
	}	
			
	/*----------------------------------------------------------------------------------------------------------------------------*/

	//Preencher Sumarios Da Aula Anterior
	
	function QSumAulaAnterior ($idProf,$Disciplina,$Turma,$NumAulaAtual=false, $idHorario=false)
	{
		return "
		SELECT top 1 PA.NumActa,CONVERT(VARCHAR(10),PA.Data, 20) as Data, CONVERT(VARCHAR(5),TL.HoraInicial, 108) Hora, PA.Descricao, PS.ID_Horario, PS.ID_TempoLectivo, PS.ID_TipoHorario
		FROM PED_Actas PA
		INNER JOIN PED_Sumarios PS ON PS.ID_Acta=PA.ID_Acta
		INNER JOIN PED_Horarios_Turmas HT ON PS.ID_Horario=HT.ID_Horario
		INNER JOIN PED_Horarios_Turmas_Professores HTP ON HTP.ID_Horario=HT.ID_Horario
		INNER JOIN PED_TemposLectivos TL ON TL.ID_Tempo=HT.ID_Tempo					
		WHERE PA.isSumario=1 AND		
		HT.ID_Disciplina='".$Disciplina."' and
		HT.ID_Turma='".$Turma."' AND
		HTP.ID_NUtente='".$idProf."'
		".($NumAulaAtual?"and NumActa=".($NumAulaAtual-1):"")."		
		order by data desc, Hora Desc";
	}

	
?>