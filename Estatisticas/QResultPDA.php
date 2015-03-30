<?	
	//tipo de ensino
	$sec = $_GET['sec'];
	$ciclo23 = $_GET['23ciclo'];
	$ciclo1 = $_GET['1ciclo'];
	$modular = $_GET['modular'];
	
	
	//esta query nao funciona corretamente (numero de faltas é mal calculado)
		
	
	if($modular == 'true')	{
				
		$queryPDAAluno_Turma= "
		DECLARE @datai date = '".$DataInicio."'		
		DECLARE @dataf date = '".$DataFim."'
		declare @turma as int      set @turma = '".$Turma."'
		declare @curso as int set @curso = '".$curso."'
		declare @Ano as int set @Ano = '".$Ano."' 
		set nocount on
		 select * into #PDA from (select ut.NomeAbreviado NomeAluno, t.Designacao Turma, m.ID_NUtente id_a, d.ID_Disciplina id_d, md.NumeroAluno NumeroAluno, D.Abreviatura abv,d.Sigla Sigla, ut.DocIdNumero BI, t.ID_Turma id_t,
		COUNT (modu.CargaHoraria) as AulasPrevistas, COUNT(act.ID_Acta) as AulasDadas, 0 as Faltas
		from PED_Matriculas m
		inner join PED_Matriculas_Disciplinas md on md.ID_Matricula=m.ID_Matricula		
		inner join PED_Disciplinas d on d.ID_Disciplina=md.ID_Disciplina
		inner join PED_Turmas t on t.ID_Turma=md.ID_Turma and t.ID_Curso=@curso
		inner join GER_Utentes ut on ut.ID_NUtente=m.ID_NUtente
		inner join PED_Modulos modu on modu.ID_Curso=m.ID_Curso and modu.ID_Disciplina=md.ID_Disciplina
		inner join PED_Sumarios suma on modu.ID_Modulo=suma.ID_Modulo and suma.ID_Modulo is not null 
		inner join PED_Actas act on act.ID_Acta=suma.ID_Acta 
				
		where md.AnoLectivo='".$AnoLectivo."' and t.ID_Curso=@curso and md.ID_Ano=@Ano and t.ID_Turma=@turma and md.NumeroAluno is not null

		group by d.ID_Disciplina, ut.NomeAbreviado, md.NumeroAluno,md.AnoLectivo, t.Designacao, ut.DocIdNumero, d.Abreviatura, d.Sigla, m.ID_NUtente, t.ID_Turma)T1	

		select * into #Faltas from 
		(select u.NomeAbreviado NomeAluno, t.Designacao Turma, t.ID_Turma id_t, m.ID_NUtente id_a, d.ID_Disciplina id_d, md.NumeroAluno NumeroAluno, D.Abreviatura abv,d.Sigla Sigla, u.DocIdNumero BI, COUNT( distinct fa.ID_Falta) as Faltas
		FROM PED_FaltasAlunos FA
		inner join PED_Horarios_Turmas as HT on HT.ID_Horario = FA.ID_Horario
		inner join PED_Turmas as T on T.ID_Turma = HT.ID_Turma and T.ID_Turma = @turma and t.ID_Curso=@curso
		inner join PED_TemposLectivos as TL on TL.ID_Tempo = FA.ID_TempoLectivo
		inner join GER_utentes as U on  FA.ID_NUtente = U.ID_NUtente 
		inner join PED_Disciplinas as D on D.ID_Disciplina = HT.ID_Disciplina
		inner join PED_Matriculas m on m.ID_NUtente=u.ID_NUtente
		inner join PED_Matriculas_Disciplinas md on d.ID_Disciplina=md.ID_Disciplina and m.ID_Matricula=md.ID_Matricula

		where FA.ID_TipoHorario in (1,4)
		and FA.isAnulada = 0
		and FA.isJustificada = 0
		and fa.ID_TipoFalta=1
		and FA.data between @datai and @dataf and HT.ID_Turma = @turma

		GROUP BY D.ID_Disciplina, u.NomeAbreviado, md.NumeroAluno,md.AnoLectivo, t.Designacao, u.DocIdNumero, D.Abreviatura, d.Sigla, m.ID_NUtente, t.ID_Turma) T2

		update T1
		set Faltas=T2.Faltas

		from #PDA T1 
		inner join #Faltas T2 on T1.id_d=T2.id_d and T1.id_a=T2.id_a and T1.id_t=T2.id_t
		
		select NomeAluno, Turma, id_a, NumeroAluno, abv, Sigla, BI, AulasPrevistas, AulasDadas, id_d, Faltas from #PDA		 
		order by NumeroAluno, abv
		drop table #PDA
		drop table #Faltas";	
		
		$queryPDADisciplina_Turma="
		DECLARE @datai date = '".$DataInicio."'		
		DECLARE @dataf date = '".$DataFim."'
		declare @turma as int      set @turma = '".$Turma."'
		declare @curso as int set @curso = '".$curso."'
		declare @Ano as int set @Ano = '".$Ano."' 
		set nocount on
		 select * into #PDA from (select ut.NomeAbreviado NomeAluno, t.Designacao Turma, m.ID_NUtente id_a, d.ID_Disciplina id_d, md.NumeroAluno NumeroAluno, D.Abreviatura abv,d.Sigla Sigla, ut.DocIdNumero BI, t.ID_Turma id_t,
		COUNT (modu.CargaHoraria) as AulasPrevistas, COUNT(act.ID_Acta) as AulasDadas, 0 as Faltas
		from PED_Matriculas m
		inner join PED_Matriculas_Disciplinas md on md.ID_Matricula=m.ID_Matricula		
		inner join PED_Disciplinas d on d.ID_Disciplina=md.ID_Disciplina
		inner join PED_Turmas t on t.ID_Turma=md.ID_Turma and t.ID_Curso=@curso
		inner join GER_Utentes ut on ut.ID_NUtente=m.ID_NUtente
		inner join PED_Modulos modu on modu.ID_Curso=m.ID_Curso and modu.ID_Disciplina=md.ID_Disciplina
		inner join PED_Sumarios suma on modu.ID_Modulo=suma.ID_Modulo and suma.ID_Modulo is not null 
		inner join PED_Actas act on act.ID_Acta=suma.ID_Acta 
				
		where md.AnoLectivo='".$AnoLectivo."' and t.ID_Curso=@curso and md.ID_Ano=@Ano and md.NumeroAluno is not null

		group by d.ID_Disciplina, ut.NomeAbreviado, md.NumeroAluno,md.AnoLectivo, t.Designacao, ut.DocIdNumero, d.Abreviatura, d.Sigla, m.ID_NUtente, t.ID_Turma)T1	

		select * into #Faltas from 
		(select u.NomeAbreviado NomeAluno, t.Designacao Turma, t.ID_Turma id_t, m.ID_NUtente id_a, d.ID_Disciplina id_d, md.NumeroAluno NumeroAluno, D.Abreviatura abv,d.Sigla Sigla, u.DocIdNumero BI, COUNT( distinct fa.ID_Falta) as Faltas
		FROM PED_FaltasAlunos FA
		inner join PED_Horarios_Turmas as HT on HT.ID_Horario = FA.ID_Horario
		inner join PED_Turmas as T on T.ID_Turma = HT.ID_Turma and T.ID_Turma = @turma and t.ID_Curso=@curso
		inner join PED_TemposLectivos as TL on TL.ID_Tempo = FA.ID_TempoLectivo
		inner join GER_utentes as U on  FA.ID_NUtente = U.ID_NUtente 
		inner join PED_Disciplinas as D on D.ID_Disciplina = HT.ID_Disciplina
		inner join PED_Matriculas m on m.ID_NUtente=u.ID_NUtente
		inner join PED_Matriculas_Disciplinas md on d.ID_Disciplina=md.ID_Disciplina and m.ID_Matricula=md.ID_Matricula

		where FA.ID_TipoHorario in (1,4)
		and FA.isAnulada = 0
		and FA.isJustificada = 0
		and fa.ID_TipoFalta=1
		and FA.data between @datai and @dataf and HT.ID_Turma = @turma

		GROUP BY D.ID_Disciplina, u.NomeAbreviado, md.NumeroAluno,md.AnoLectivo, t.Designacao, u.DocIdNumero, D.Abreviatura, d.Sigla, m.ID_NUtente, t.ID_Turma) T2

		update T1
		set Faltas=T2.Faltas

		from #PDA T1 
		inner join #Faltas T2 on T1.id_d=T2.id_d and T1.id_a=T2.id_a and T1.id_t=T2.id_t
		
		select Distinct Sigla, Turma, id_d, AulasDadas, AulasPrevistas from #PDA		 
		order by Sigla, Turma
		drop table #PDA
		drop table #Faltas";
	}
	else
	{
		$queryPDAAluno_Turma = "
		DECLARE @datai date = '".$DataInicio."'		
		DECLARE @dataf date = '".$DataFim."'
		declare @turma as int      set @turma = '".$Turma."'
		declare @curso as int set @curso = '".$curso."'
		declare @Ano as int set @Ano = '".$Ano."'
		declare @periodo as int set @periodo = '".$Periodo."'
		declare @AnoLectivo as int set @AnoLectivo = '".$AnoLectivo."'
		set nocount on
		select * INTO #PDA from ( select ut.NomeAbreviado NomeAluno, t.Designacao Turma, m.ID_NUtente id_a, d.ID_Disciplina id_d, md.NumeroAluno NumeroAluno, D.Abreviatura abv,d.Sigla Sigla, ut.DocIdNumero BI,
		 t.ID_Turma id_t, 0 as Faltas, tdp.NrAulasDadas AulasDadas, tdp.NrAulasPrevistas AulasPrevistas
		from PED_Matriculas m
		inner join PED_Matriculas_Disciplinas md on md.ID_Matricula=m.ID_Matricula		
		inner join PED_Disciplinas d on d.ID_Disciplina=md.ID_Disciplina
		inner join PED_Turmas t on t.ID_Turma=md.ID_Turma and t.ID_Curso=@curso
		inner join GER_Utentes ut on ut.ID_NUtente=m.ID_NUtente
		inner join PED_Turmas_Disciplinas_Periodos tdp on tdp.ID_Disciplina=md.ID_Disciplina and tdp.ID_Turma=t.ID_Turma
		inner join PED_PeriodosLectivos pl on pl.AnoLectivo=md.AnoLectivo 
				
		where md.AnoLectivo=@AnoLectivo and t.ID_Curso=@curso and md.ID_Ano=@Ano and t.ID_Turma=@turma and md.NumeroAluno is not null and pl.NPeriodo=@periodo)T1	

		select * into #Faltas from 
		(select u.NomeAbreviado NomeAluno, t.Designacao Turma, t.ID_Turma id_t, m.ID_NUtente id_a, d.ID_Disciplina id_d, md.NumeroAluno NumeroAluno, D.Abreviatura abv,d.Sigla Sigla, u.DocIdNumero BI, COUNT( distinct fa.ID_Falta) as Faltas
		FROM PED_FaltasAlunos FA
		inner join PED_Horarios_Turmas as HT on HT.ID_Horario = FA.ID_Horario
		inner join PED_Turmas as T on T.ID_Turma = HT.ID_Turma and T.ID_Turma = @turma and t.ID_Curso=@curso
		inner join PED_TemposLectivos as TL on TL.ID_Tempo = FA.ID_TempoLectivo
		inner join GER_utentes as U on  FA.ID_NUtente = U.ID_NUtente 
		inner join PED_Disciplinas as D on D.ID_Disciplina = HT.ID_Disciplina
		inner join PED_Matriculas m on m.ID_NUtente=u.ID_NUtente
		inner join PED_Matriculas_Disciplinas md on d.ID_Disciplina=md.ID_Disciplina and m.ID_Matricula=md.ID_Matricula

		where FA.ID_TipoHorario in (1,4)
		and FA.isAnulada = 0
		and FA.isJustificada = 0
		and fa.ID_TipoFalta=1
		and FA.data between @datai and @dataf and HT.ID_Turma = @turma

		GROUP BY D.ID_Disciplina, u.NomeAbreviado, md.NumeroAluno,md.AnoLectivo, t.Designacao, u.DocIdNumero, D.Abreviatura, d.Sigla, m.ID_NUtente, t.ID_Turma) T2

		update T1
		set Faltas=T2.Faltas

		from #PDA T1 
		inner join #Faltas T2 on T1.id_d=T2.id_d and T1.id_a=T2.id_a and T1.id_t=T2.id_t
		
		select NomeAluno, Turma, id_a, NumeroAluno, abv, Sigla, BI, AulasPrevistas, AulasDadas, id_d, Faltas from #PDA		 
		order by NumeroAluno, abv
		drop table #PDA
		drop table #Faltas";		
	
		$queryPDADisciplina_Turma = "		 
		DECLARE @datai date = '".$DataInicio."'		
		DECLARE @dataf date = '".$DataFim."'
		declare @turma as int      set @turma = '".$Turma."'
		declare @curso as int set @curso = '".$curso."'
		declare @Ano as int set @Ano = '".$Ano."'
		declare @periodo as int set @periodo = '".$Periodo."'
		declare @AnoLectivo as int set @AnoLectivo = '".$AnoLectivo."'
		set nocount on
		select * INTO #PDA from ( select ut.NomeAbreviado NomeAluno, t.Designacao Turma, m.ID_NUtente id_a, d.ID_Disciplina id_d, md.NumeroAluno NumeroAluno, D.Abreviatura abv,d.Sigla Sigla, ut.DocIdNumero BI,
		t.ID_Turma id_t, 0 as Faltas, tdp.NrAulasDadas AulasDadas, tdp.NrAulasPrevistas AulasPrevistas
		from PED_Matriculas m
		inner join PED_Matriculas_Disciplinas md on md.ID_Matricula=m.ID_Matricula		
		inner join PED_Disciplinas d on d.ID_Disciplina=md.ID_Disciplina
		inner join PED_Turmas t on t.ID_Turma=md.ID_Turma and t.ID_Curso=@curso
		inner join GER_Utentes ut on ut.ID_NUtente=m.ID_NUtente
		inner join PED_Turmas_Disciplinas_Periodos tdp on tdp.ID_Disciplina=md.ID_Disciplina and tdp.ID_Turma=t.ID_Turma
		inner join PED_PeriodosLectivos pl on pl.AnoLectivo=md.AnoLectivo 
				
		where md.AnoLectivo=@AnoLectivo and t.ID_Curso=@curso and md.ID_Ano=@Ano and md.NumeroAluno is not null and pl.NPeriodo=@periodo)T1	

		select * into #Faltas from 
		(select u.NomeAbreviado NomeAluno, t.Designacao Turma, t.ID_Turma id_t, m.ID_NUtente id_a, d.ID_Disciplina id_d, md.NumeroAluno NumeroAluno, D.Abreviatura abv,d.Sigla Sigla, u.DocIdNumero BI, COUNT( distinct fa.ID_Falta) as Faltas
		FROM PED_FaltasAlunos FA
		inner join PED_Horarios_Turmas as HT on HT.ID_Horario = FA.ID_Horario
		inner join PED_Turmas as T on T.ID_Turma = HT.ID_Turma and T.ID_Turma = @turma and t.ID_Curso=@curso
		inner join PED_TemposLectivos as TL on TL.ID_Tempo = FA.ID_TempoLectivo
		inner join GER_utentes as U on  FA.ID_NUtente = U.ID_NUtente 
		inner join PED_Disciplinas as D on D.ID_Disciplina = HT.ID_Disciplina
		inner join PED_Matriculas m on m.ID_NUtente=u.ID_NUtente
		inner join PED_Matriculas_Disciplinas md on d.ID_Disciplina=md.ID_Disciplina and m.ID_Matricula=md.ID_Matricula

		where FA.ID_TipoHorario in (1,4)
		and FA.isAnulada = 0
		and FA.isJustificada = 0
		and fa.ID_TipoFalta=1
		and FA.data between @datai and @dataf and HT.ID_Turma = @turma

		GROUP BY D.ID_Disciplina, u.NomeAbreviado, md.NumeroAluno,md.AnoLectivo, t.Designacao, u.DocIdNumero, D.Abreviatura, d.Sigla, m.ID_NUtente, t.ID_Turma) T2

		update T1
		set Faltas=T2.Faltas

		from #PDA T1 
		inner join #Faltas T2 on T1.id_d=T2.id_d and T1.id_a=T2.id_a and T1.id_t=T2.id_t
		
		select Distinct Sigla, Turma, id_d, AulasDadas, AulasPrevistas from #PDA		 
		order by Sigla, Turma
		drop table #PDA
		drop table #Faltas";
	}
	
							
?>