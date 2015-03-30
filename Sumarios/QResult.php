<?php

	//tipo de ensino
	$sec = $_GET['sec'];
	$ciclo23 = $_GET['23ciclo'];
	$ciclo1 = $_GET['1ciclo'];
	$modular = $_GET['modular'];
	
	if($sec)
	{
		$negativa=10;
	}
	elseif($ciclo23 || $ciclo1)
	{ 
		$negativa=3;
	}
	
	if($modular=='true')
	{		
		$queryBase= "from PED_Matriculas m
		inner join PED_Matriculas_AnosLectivos mal on m.ID_Matricula=mal.ID_Matricula
		inner join PED_Matriculas_Disciplinas md on MD.ID_Matricula = MAL.ID_Matricula and MD.AnoLectivo = MAL.AnoLectivo and MD.ID_Ano = MAL.ID_Ano
		inner join PED_Matriculas_Modulos mm on m.ID_Matricula=mm.ID_Matricula and mal.ID_Ano=mm.ID_Ano and md.ID_Disciplina=mm.ID_Disciplina
		inner join PED_Cursos c on c.ID_Curso=m.ID_Curso
		inner join PED_Disciplinas d on mm.ID_Disciplina=d.ID_Disciplina
		inner join GER_Utentes ut on m.ID_NUtente=ut.ID_NUtente
		inner join PED_Modulos modu on modu.ID_Modulo= mm.ID_Modulo and modu.ID_Curso=m.ID_Curso and modu.ID_Disciplina=mm.ID_Disciplina
		inner join PED_CriteriosAvaliacao ca on ca.ID_Curso=M.ID_Curso and ca.ID_TipoCriterio=9 and ca.ID_Disciplina=md.ID_Disciplina and ca.ID_Modulo=mm.ID_Modulo
		inner join PED_AvaliacaoDefinitiva ad on m.ID_Matricula=ad.ID_Matricula and mal.AnoLectivo=ad.AnoLectivo and ca.ID_CriterioAvaliacao=ad.ID_CriterioAvaliacao
		inner join PED_TipoCriterio tc on ca.ID_TipoCriterio=tc.ID_TipoCriterio
		inner join PED_Turmas t on md.ID_Turma=t.ID_Turma and t.ID_Curso=".$curso."";

		//query de verificar se existem dados para o ensino profissional modular											
		$queryVerificaRegistos = "select * ".$queryBase."

		where mal.ID_EscolaMatricula='".$Escola."' and m.ID_Curso='".$curso."' and ad.AnoLectivo='".$AnoLectivo."' and ad.Nota is not null and mm.ID_Ano='".$Ano."'";
		
		//média por disciplina			
		$query2="select d.Abreviatura, d.DesignacaoPauta disciplina, md.ID_Ano Ano, d.Sigla sigla, cast (round(avg (ad.Nota),2) AS float) media ".$queryBase."			
		where m.ID_Curso=".$curso." and ad.AnoLectivo=".$AnoLectivo." and ad.Nota is not null and mm.ID_Ano=".$Ano."
		and ID_AvaliacaoQualitativaDetalhe is null and ad.Nota is not null
		group by d.Abreviatura, md.ID_Ano, d.Sigla, d.DesignacaoPauta 
		order by d.Abreviatura ASC";
		
		//média por turma
		$query3= "select t.ID_Turma id_t, t.Designacao turmas, cast (round(avg (ad.Nota),2) AS float) media ".$queryBase."
		where m.ID_Curso=".$curso." and ad.AnoLectivo=".$AnoLectivo." and ad.Nota is not null and mm.ID_Ano=".$Ano."
		and ID_AvaliacaoQualitativaDetalhe is null and ad.Nota is not null
		group by t.Designacao, t.ID_Turma
		order by t.Designacao ASC";
		
		//Média por Ano
		$query4= "select mm.ID_Ano Ano, cast (round(avg (ad.Nota),2) AS float) media ".$queryBase."
		where m.ID_Curso=".$curso." and ad.AnoLectivo=".$AnoLectivo." and ad.Nota is not null
		and ID_AvaliacaoQualitativaDetalhe is null and ad.Nota is not null
		group by mm.ID_Ano
		order by mm.ID_Ano ASC";
				
		$queryMedAno="select md.ID_Ano Ano, cast (round(avg (ad.Nota),2) AS float) mediaAno ".$queryBase."
		where m.ID_Curso=".$curso." and ad.AnoLectivo=".$AnoLectivo." and ad.Nota is not null and ID_AvaliacaoQualitativaDetalhe is null and ad.Nota is not null
		group by md.ID_Ano
		order by md.ID_Ano ASC";
		
		//niveis por turma	
		$query10="select t.Designacao turma,t.ID_Turma id, ad.Nota nota, COUNT(ad.Nota) ocorrencias ".$queryBase."					 
		where m.ID_Curso=".$curso." and ad.AnoLectivo=".$AnoLectivo." and mal.ID_EscolaMatricula='".$Escola."' and md.ID_Ano=".$Ano." and ID_AvaliacaoQualitativaDetalhe is null and ad.Nota is not null
		group by ad.Nota,t.Designacao, t.ID_Turma
		order by t.Designacao";
		
		//média por disciplina e ano
		$query11="select md.ID_Ano Ano, md.ID_Disciplina id_d, d.DesignacaoPauta disciplina, d.Abreviatura, d.Sigla sigla, cast (round(avg (ad.Nota),2) AS float) media ".$queryBase."				
		where m.ID_Curso=".$curso." and ad.AnoLectivo=".$AnoLectivo." and mal.ID_EscolaMatricula='".$Escola."' and ID_AvaliacaoQualitativaDetalhe is null and ad.Nota is not null
		group by  md.ID_Ano, d.Abreviatura, md.ID_Disciplina, d.Sigla, d.DesignacaoPauta
		order by id_d, md.ID_Ano";
		
		//media por disciplina e turma	
		$query12="select t.ID_Turma id_t, t.designacao turma, md.ID_Disciplina id_d, d.Abreviatura abv, d.DesignacaoPauta disciplina, d.Sigla sigla, cast (round(avg (ad.Nota),2) AS float) media ".$queryBase."
		where m.ID_Curso=".$curso." and ad.AnoLectivo=".$AnoLectivo." and mal.ID_EscolaMatricula='".$Escola."' and md.ID_Ano=".$Ano." and ID_AvaliacaoQualitativaDetalhe is null and ad.Nota is not null
		group by t.designacao, d.Abreviatura,md.ID_Disciplina, d.Sigla, d.DesignacaoPauta, t.ID_Turma
		order by t.ID_Turma";
		
			
		$query13="select d.Abreviatura abv, d.DesignacaoPauta Disciplina, d.Sigla sigla, md.ID_Disciplina id_d, ad.Nota nota, COUNT(ad.Nota) ocorrencias ".$queryBase."			 
		where m.ID_Curso=".$curso." and ad.AnoLectivo=".$AnoLectivo." and mal.ID_EscolaMatricula='".$Escola."' and md.ID_Ano=".$Ano." and ID_AvaliacaoQualitativaDetalhe is null and ad.Nota is not null
		group by ad.Nota, d.Abreviatura, md.ID_Disciplina, d.Sigla, d.DesignacaoPauta
		order by d.Abreviatura, ad.Nota";
			
			//dist Percentagem	
		$queryDistDisciplinaPercent = "
		set nocount on
		select * INTO #hello from (select d.Abreviatura abv, d.DesignacaoPauta Disciplina, d.Sigla sigla, md.ID_Disciplina id_d, ad.Nota nota, COUNT(ad.Nota) ocorrencias ".$queryBase."																			 
		where m.ID_Curso=".$curso." and ad.AnoLectivo=".$AnoLectivo." and mal.ID_EscolaMatricula='".$Escola."' and md.ID_Ano=".$Ano." and ID_AvaliacaoQualitativaDetalhe is null and ad.Nota is not null
		group by ad.Nota, d.Abreviatura, md.ID_Disciplina, d.Sigla, d.DesignacaoPauta
		) T
		select * into #nos from(
			select disciplina, SUM (ocorrencias) as notas
			from #hello group by Disciplina)TT

		select T.abv,T.sigla, T.Disciplina, T.nota, T.id_d, round((convert(real,(100*T.ocorrencias))/convert(real,(TT.notas))),0) as percentagem from #hello T inner join #nos TT on T.Disciplina=TT.Disciplina
		drop table #hello
		drop table #nos";
		
		//dist percentagem turma	
		$queryDistTurmaPercent = "
		set nocount on
		select * INTO #ocorrTurma from (select t.Designacao turma, t.ID_Turma id_t, ad.Nota nota, COUNT(ad.Nota) ocorrencias ".$queryBase."																			 
		where m.ID_Curso=".$curso." and ad.AnoLectivo=".$AnoLectivo." and mal.ID_EscolaMatricula='".$Escola."' and md.ID_Ano=".$Ano." and ID_AvaliacaoQualitativaDetalhe is null and ad.Nota is not null
		group by ad.Nota, t.Designacao, t.ID_Turma
		) T
		select * into #totalvalturma from(
			select id_t, SUM (ocorrencias) as notas
			from #ocorrTurma group by id_t)TT

		select T.id_t,T.turma, T.nota, round((convert(real,(100*T.ocorrencias))/convert(real,(TT.notas))),0) as percentagem from #ocorrTurma T inner join #totalvalturma TT on T.id_t=TT.id_t
		
		drop table #ocorrTurma
		drop table #totalvalturma";
			
		$queryMedTurmaAno = "select t.Designacao turmas,md.ID_Ano Ano, t.ID_Turma id_t, cast (round(avg (ad.Nota),2) AS float) media ".$queryBase."
		where m.ID_Curso=".$curso." and ad.AnoLectivo=".$AnoLectivo." and mal.ID_EscolaMatricula='".$Escola."' and ID_AvaliacaoQualitativaDetalhe is null and ad.Nota is not null
		group by t.Designacao, md.ID_Ano, t.ID_Turma
		order by md.ID_Ano, t.Designacao ASC";	
	}	
		else
		{
			$queryBase= "from PED_Matriculas m inner join PED_Matriculas_AnosLectivos mal on m.ID_Matricula=mal.ID_Matricula
			inner join PED_Matriculas_Disciplinas md on MD.ID_Matricula = MAL.ID_Matricula and MD.AnoLectivo = MAL.AnoLectivo and MD.ID_Ano = MAL.ID_Ano
			inner join PED_SituacoesMatriculas sm on sm.ID_SituacaoFinal=mal.ID_SituacaoFinal
			inner join PED_CriteriosAvaliacao ca on ca.ID_Curso=M.ID_Curso and Ca.ID_Ano=mal.ID_Ano
			and(CA.ID_Disciplina = MD.ID_Disciplina OR CA.ID_Disciplina IS NULL) and ca.ID_TipoCriterio=8			
			inner join PED_AvaliacaoDefinitiva ad on AD.ID_Matricula = M.ID_Matricula and AD.AnoLectivo = MAL.AnoLectivo and AD.ID_CriterioAvaliacao = CA.ID_CriterioAvaliacao
			inner join PED_PeriodosLectivos pl on ca.ID_PeriodoLectivo=pl.NPeriodo and pl.AnoLectivo=mal.AnoLectivo
			inner join PED_Turmas t on t.ID_Turma=md.ID_Turma and t.ID_Curso='".$curso."'
			inner join GER_Utentes ut on ut.ID_NUtente=m.ID_NUtente and mal.ID_EscolaMatricula=ut.ID_Escola
			inner join PED_Disciplinas d on d.ID_Disciplina=md.ID_Disciplina
			inner join PED_DisciplinasInternas di on d.ID_DisciplinaInterna=di.ID_DisciplinaInterna ";	
				
			//query de verificar se existem dados para o ensino profissional modular	
			$queryVerificaRegistos = "select * ".$queryBase."
			where ad.AnoLectivo='".$AnoLectivo."' and ca.ID_Ano='".$Ano."' and mal.ID_EscolaMatricula='".$Escola."' and pl.NPeriodo='".$Periodo."' and ca.ID_Curso='".$curso."' 
			and mal.DataMatricula<DataFim and mal.DataSituacao<=DataFim and ID_AvaliacaoQualitativaDetalhe is null and ad.Nota is not null";	
			
			$query2="select d.Abreviatura, d.DesignacaoPauta disciplina, ca.ID_Ano Ano, d.Sigla sigla, cast (round(avg (ad.Nota),2) AS float) media ".$queryBase."
			where ad.AnoLectivo='".$AnoLectivo."' and ca.ID_Ano='".$Ano."' and mal.ID_EscolaMatricula='".$Escola."' and pl.NPeriodo='".$Periodo."' and ca.ID_Curso='".$curso."' and
			mal.DataMatricula<DataFim and mal.DataSituacao<=DataFim and ID_AvaliacaoQualitativaDetalhe is null and ad.Nota is not null
			group by d.Abreviatura, ca.ID_Ano, d.Sigla, d.DesignacaoPauta 
			order by d.Abreviatura ASC"; 	
				
			//media por turma		
			$query3="select t.ID_Turma id_t, t.Designacao turmas, cast (round(avg (ad.Nota),2) AS float) media ".$queryBase."
				where ad.AnoLectivo='".$AnoLectivo."' and mal.ID_EscolaMatricula='".$Escola."' and pl.NPeriodo='".$Periodo."' and ca.ID_Curso='".$curso."' and ca.ID_Ano='".$Ano."'
				and mal.DataSituacao<DataFim and mal.DataMatricula<=DataFim and ID_AvaliacaoQualitativaDetalhe is null and ad.Nota is not null
				group by t.Designacao, t.ID_Turma
				order by t.Designacao ASC";
				
				$query4="select ca.ID_Ano Ano, cast (round(avg (ad.Nota),2) AS float) media ".$queryBase."
				where ad.AnoLectivo='".$AnoLectivo."' and mal.ID_EscolaMatricula='".$Escola."' and pl.NPeriodo='".$Periodo."' and ca.ID_Curso='".$curso."'
				and mal.DataSituacao<DataFim and mal.DataMatricula<=DataFim and ID_AvaliacaoQualitativaDetalhe is null and ad.Nota is not null
				group by ca.ID_Ano
				order by ca.ID_Ano ASC";
				
				$queryMedAno="select ca.ID_Ano Ano, cast (round(avg (ad.Nota),2) AS float) mediaAno ".$queryBase."
				where ad.AnoLectivo='".$AnoLectivo."' and ca.ID_Ano='".$Ano."' and mal.ID_EscolaMatricula='".$Escola."' and pl.NPeriodo='".$Periodo."' and ca.ID_Curso='".$curso."' and
				mal.DataMatricula<DataFim and mal.DataSituacao<=DataFim and ID_AvaliacaoQualitativaDetalhe is null and ad.Nota is not null
				group by ca.ID_Ano
				order by ca.ID_Ano ASC";
				
				//niveis por turma	
				$query10="select t.Designacao turma,t.ID_Turma id, ad.Nota nota, COUNT(ad.Nota) ocorrencias ".$queryBase."
				where ad.AnoLectivo='".$AnoLectivo."' and ca.ID_Ano='".$Ano."' and mal.ID_EscolaMatricula='".$Escola."' and pl.NPeriodo='".$Periodo."' and ca.ID_Curso='".$curso."' and ID_AvaliacaoQualitativaDetalhe is null
				and mal.DataSituacao<DataFim and mal.DataMatricula<=DataFim and ad.nota is not null
				group by ad.Nota,t.Designacao, t.ID_Turma
				order by t.Designacao";
				
				//media por disciplina e ano
				$query11="select ca.ID_Ano Ano, md.ID_Disciplina id_d, d.DesignacaoPauta disciplina, d.Abreviatura, d.Sigla sigla, cast (round(avg (ad.Nota),2) AS float) media ".$queryBase."
				where ad.AnoLectivo='".$AnoLectivo."' and mal.ID_EscolaMatricula='".$Escola."' and pl.NPeriodo='".$Periodo."' and ca.ID_Curso='".$curso."' and ID_AvaliacaoQualitativaDetalhe is null
				and mal.DataSituacao<DataFim and mal.DataMatricula<=DataFim and ad.Nota is not null
				group by  ca.ID_Ano, d.Abreviatura, md.ID_Disciplina, d.Sigla, d.DesignacaoPauta
				order by id_d, ca.ID_Ano ";
				
				//media por disciplina e turma
				$query12="select t.ID_Turma id_t, t.designacao turma, md.ID_Disciplina id_d, d.Abreviatura abv, d.DesignacaoPauta disciplina, d.Sigla sigla, cast (round(avg (ad.Nota),2) AS float) media ".$queryBase."
				where ad.AnoLectivo='".$AnoLectivo."' and mal.ID_EscolaMatricula='".$Escola."' and pl.NPeriodo='".$Periodo."' and ca.ID_Curso='".$curso."' and ca.ID_Ano='".$Ano."' and ID_AvaliacaoQualitativaDetalhe is null
				and mal.DataSituacao<DataFim and mal.DataMatricula<=DataFim and ad.Nota is not null
				group by t.designacao, d.Abreviatura,md.ID_Disciplina, d.Sigla, d.DesignacaoPauta, t.ID_Turma
				order by t.ID_Turma";
				
				//dist
				$query13=" select d.Abreviatura abv, d.DesignacaoPauta Disciplina, d.Sigla sigla, md.ID_Disciplina id_d, ad.Nota nota, COUNT(ad.Nota) ocorrencias ".$queryBase."
				where ad.AnoLectivo='".$AnoLectivo."' and ca.ID_Ano='".$Ano."' and mal.ID_EscolaMatricula='".$Escola."' and pl.NPeriodo='".$Periodo."' and ca.ID_Curso='".$curso."'
				and mal.DataSituacao<DataFim and mal.DataMatricula<=DataFim and ID_AvaliacaoQualitativaDetalhe is null and ad.nota is not null
				group by ad.Nota, d.Abreviatura, md.ID_Disciplina, d.Sigla, d.DesignacaoPauta
				order by d.Abreviatura, ad.Nota";
				
				//dist Percentagem	
	
				$queryDistDisciplinaPercent = "
				set nocount on
				select * INTO #hello from (select d.Abreviatura abv, d.DesignacaoPauta Disciplina, d.Sigla sigla, md.ID_Disciplina id_d, ad.Nota nota, COUNT(ad.Nota) ocorrencias ".$queryBase."
				where ad.AnoLectivo='".$AnoLectivo."' and ca.ID_Ano='".$Ano."' and mal.ID_EscolaMatricula='".$Escola."' and pl.NPeriodo='".$Periodo."' and ca.ID_Curso='".$curso."'
				and mal.DataSituacao<DataFim and mal.DataMatricula<=DataFim and ID_AvaliacaoQualitativaDetalhe is null and ad.Nota is not null
				group by ad.Nota, d.Abreviatura, md.ID_Disciplina, d.Sigla, d.DesignacaoPauta
				) T
				select * into #nos from(
					select disciplina, SUM (ocorrencias) as notas
					from #hello group by Disciplina)TT

				select T.abv,T.sigla, T.Disciplina, T.nota, T.id_d, round((convert(real,(100*T.ocorrencias))/convert(real,(TT.notas))),0) as percentagem from #hello T inner join #nos TT on T.Disciplina=TT.Disciplina
				drop table #hello
				drop table #nos";
				
				$queryDistTurmaPercent = "set nocount on
				select * INTO #ocorrTurma from (select t.Designacao turma, t.ID_Turma id_t, ad.Nota nota, COUNT(ad.Nota) ocorrencias ".$queryBase."
				where ad.AnoLectivo='".$AnoLectivo."' and ca.ID_Ano='".$Ano."' and mal.ID_EscolaMatricula='".$Escola."' and pl.NPeriodo='".$Periodo."' and ca.ID_Curso='".$curso."'
				and mal.DataSituacao<DataFim and mal.DataMatricula<=DataFim and ID_AvaliacaoQualitativaDetalhe is null and ad.Nota is not null
				group by ad.Nota, t.Designacao, t.ID_Turma
				) T
				select * into #totalvalturma from(
					select id_t, SUM (ocorrencias) as notas
					from #ocorrTurma group by id_t)TT

				select T.id_t,T.turma, T.nota, round((convert(real,(100*T.ocorrencias))/convert(real,(TT.notas))),0) as percentagem from #ocorrTurma T inner join #totalvalturma TT on T.id_t=TT.id_t
				
				drop table #ocorrTurma
				drop table #totalvalturma";	
				
				$queryMedTurmaAno = "select t.Designacao turmas,ca.ID_Ano Ano, t.ID_Turma id_t, cast (round(avg (ad.Nota),2) AS float) media ".$queryBase."
				where ad.AnoLectivo='".$AnoLectivo."' and mal.ID_EscolaMatricula='".$Escola."' and pl.NPeriodo='".$Periodo."' and ca.ID_Curso='".$curso."'
				and mal.DataSituacao<DataFim and mal.DataMatricula<=DataFim and ID_AvaliacaoQualitativaDetalhe is null and ad.Nota is not null
				group by t.Designacao, ca.ID_Ano, t.ID_Turma
				order by ca.ID_Ano, t.Designacao ASC";

			//alunos com negativas
			$query1="select t.ID_Turma id_t,t.Designacao turma, ut.NomeAbreviado Aluno, ut.ID_NUtente id_a , ad.Nota nota, d.Abreviatura disciplina, d.ID_Disciplina id_d, di.Cod_ENEB eneb, ca.ID_Ano Ano ".$queryBase."
			where ad.AnoLectivo='".$AnoLectivo."' and mal.ID_EscolaMatricula='".$Escola."' and pl.NPeriodo='".$Periodo."' and ad.Nota<".$negativa." and m.ID_Curso='".$curso."'
			and mal.DataSituacao<DataFim and mal.DataMatricula<=DataFim and ID_AvaliacaoQualitativaDetalhe is null
			group by t.ID_Turma, t.Designacao, ut.NomeAbreviado, ut.ID_NUtente, d.Abreviatura, d.ID_Disciplina, di.Cod_ENEB, ca.ID_Ano, ad.nota
			order by t.Designacao ASC";	
			
			//percentagem negativas por ano		
			$query5="set nocount on
			select * into #NumNegativasAno from (select ca.ID_Ano Ano, COUNT (ad.Nota) Negativas ".$queryBase."
			where ad.AnoLectivo='".$AnoLectivo."' and mal.ID_EscolaMatricula='".$Escola."' and pl.NPeriodo='".$Periodo."' and ad.Nota<".$negativa." and ca.ID_Curso='".$curso."'
			and mal.DataSituacao<DataFim and mal.DataMatricula<=DataFim and ID_AvaliacaoQualitativaDetalhe is null and ad.Nota is not null
			group by ca.ID_Ano)T
															
			select * into #totalAlunos from (select ca.ID_Ano Ano, COUNT(*) totalInscritos ".$queryBase."
			where ad.AnoLectivo='".$AnoLectivo."' and mal.ID_EscolaMatricula='".$Escola."' and pl.NPeriodo='".$Periodo."' and ca.ID_Curso='".$curso."'
			and mal.DataSituacao<DataFim and mal.DataMatricula<=DataFim and ID_AvaliacaoQualitativaDetalhe is null and ad.Nota is not null
			group by ca.ID_Ano ) TT
															
			select T.Ano, ((100*T.Negativas)/(TT.totalInscritos)) as percentagem from #NumNegativasAno T inner join #totalAlunos TT on T.Ano=TT.Ano
			order by T.Ano
															
			drop table #NumNegativasAno
			drop table #totalAlunos";
			
			//percentagem de negativas por turma		
			$query6="set nocount on
			select * into #NumNegativasturma from (select t.Designacao turmas, COUNT (ad.Nota) Negativas ".$queryBase."
			where ad.AnoLectivo='".$AnoLectivo."' and mal.ID_EscolaMatricula='".$Escola."' and pl.NPeriodo='".$Periodo."' and ca.ID_Curso='".$curso."' and ad.Nota<".$negativa."
			and mal.DataSituacao<DataFim and mal.DataMatricula<=DataFim and ID_AvaliacaoQualitativaDetalhe is null and ad.Nota is not null
			group by t.Designacao)T
																											
			select * into #totalAlunos from (select t.Designacao turmas, COUNT(*) totalInscritos ".$queryBase."
			where ad.AnoLectivo='".$AnoLectivo."' and mal.ID_EscolaMatricula='".$Escola."' and pl.NPeriodo='".$Periodo."' and ca.ID_Curso='".$curso."'
			and mal.DataSituacao<DataFim and mal.DataMatricula<=DataFim and ID_AvaliacaoQualitativaDetalhe is null and ad.Nota is not null
			group by t.Designacao ) TT
																												
			select T.turmas, ((100*T.Negativas)/(TT.totalInscritos)) as percentagem from #NumNegativasturma T inner join #totalAlunos TT on T.turmas=TT.turmas

																											
			drop table #NumNegativasturma
			drop table #totalAlunos";
			
		//negativas por turma	
		$query7="select t.Designacao turmas, COUNT (ad.Nota) Negativas ".$queryBase."
		where ad.AnoLectivo='".$AnoLectivo."' and mal.ID_EscolaMatricula='".$Escola."' and pl.NPeriodo='".$Periodo."' and ca.ID_Curso='".$curso."' and ad.Nota<".$negativa."
		and mal.DataSituacao<DataFim and mal.DataMatricula<=DataFim and ID_AvaliacaoQualitativaDetalhe is null and ad.Nota is not null
		group by t.Designacao
		order by t.Designacao ASC
		";
		
		//negativas por ano		
		$query8="select ca.ID_Ano Ano, COUNT (ad.Nota) Negativas ".$queryBase."
		where ad.AnoLectivo='".$AnoLectivo."' and mal.ID_EscolaMatricula='".$Escola."' and pl.NPeriodo='".$Periodo."' and ca.ID_Curso='".$curso."' and ad.Nota<".$negativa."
		and mal.DataSituacao<DataFim and mal.DataMatricula<=DataFim and ID_AvaliacaoQualitativaDetalhe is null and ad.Nota is not null
		group by ca.ID_Ano
		order by ca.ID_Ano ASC
		";	
		
		//negativas por disciplina	
		$query9="select d.Abreviatura, d.DesignacaoPauta disciplina, d.Sigla sigla, ca.ID_Ano Ano, COUNT (ad.Nota) Negativas ".$queryBase."
		where ad.AnoLectivo='".$AnoLectivo."' and ca.ID_Ano='".$Ano."' and mal.ID_EscolaMatricula='".$Escola."' and pl.NPeriodo='".$Periodo."' and ca.ID_Curso='".$curso."' and ad.Nota<".$negativa." and ID_AvaliacaoQualitativaDetalhe is null
		and mal.DataSituacao<DataFim and mal.DataMatricula<=DataFim and ID_AvaliacaoQualitativaDetalhe is null
		group by d.Abreviatura, ca.ID_Ano, d.DesignacaoPauta, d.Sigla
		order by sigla ASC
		";
		
		}					
			

/*$query14="select ut.Nome Aluno, t.Designacao turma  from PED_Matriculas m inner join PED_Matriculas_AnosLectivos mal on m.ID_Matricula=mal.ID_Matricula
			inner join PED_Matriculas_Disciplinas md on MD.ID_Matricula = MAL.ID_Matricula and MD.AnoLectivo = MAL.AnoLectivo and MD.ID_Ano = MAL.ID_Ano
			inner join PED_SituacoesMatriculas sm on sm.ID_SituacaoFinal=mal.ID_SituacaoFinal
			inner join PED_CriteriosAvaliacao ca on ca.ID_Curso=M.ID_Curso and Ca.ID_Ano=mal.ID_Ano
			and(CA.ID_Disciplina = MD.ID_Disciplina OR CA.ID_Disciplina IS NULL) and ca.ID_TipoCriterio=8 
			--and (MAL.ID_SituacaoFinal = 1 or (MAL.ID_SituacaoFinal in (6,7,8,11,12,13)) or (MAL.ID_SituacaoFinal in (2,3,4,5,9,10,14,15,16,17,18,19,20,21,30,31,32) ))
			inner join PED_AvaliacaoDefinitiva ad on AD.ID_Matricula = M.ID_Matricula and AD.AnoLectivo = MAL.AnoLectivo and AD.ID_CriterioAvaliacao = CA.ID_CriterioAvaliacao
			inner join PED_PeriodosLectivos pl on ca.ID_PeriodoLectivo=pl.NPeriodo and pl.AnoLectivo=mal.AnoLectivo
			inner join PED_Turmas t on t.ID_Turma=md.ID_Turma and t.ID_Curso='".$curso."'
			inner join GER_Utentes ut on ut.ID_NUtente=m.ID_NUtente and mal.ID_EscolaMatricula=ut.ID_Escola

			where mal.AnoLectivo='".$AnoLectivo."' and mal.ID_EscolaMatricula='".$Escola."' and ad.Nota<".$negativa." and pl.NPeriodo='".$Periodo."' and mal.DataSituacao>DataFim and mal.DataMatricula<DataFim
			and m.ID_Curso='".$curso."' and ca.ID_Ano='".$Ano."' and ID_AvaliacaoQualitativaDetalhe is null and ad.Nota is not null
			group by ut.Nome, t.Designacao 
			having count(ad.nota)>=3";*/		
?>