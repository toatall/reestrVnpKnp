USE [arrears]
GO
/****** Object:  StoredProcedure [dbo].[PR_ARR_UPDATE_REQUIMENT]    Script Date: 26.02.2016 17:49:17 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Author:		<Author,,Name>
-- Create date: <Create Date,,>
-- Description:	<Description,,>
-- =============================================
CREATE PROCEDURE [dbo].[PR_ARR_UPDATE_REQUIMENT]
	@id_reestr int
AS
BEGIN
	
	UPDATE A
	SET 
		A.requiment_number		= B.requiment_number, 
		A.requiment_date		= B.requiment_date, 
		A.requiment_term		= B.requiment_term, 
		A.requiment_summ		= B.requiment_summ, 
		A.requiment_summ_rest	= B.requiment_summ_rest,
		A.recovered_summ		= B.recovered_summ
	FROM dbo.arr_reestr_check A
	OUTER APPLY (
		SELECT TOP 1 
			requiment_number
		   ,requiment_date
		   ,requiment_term
		   ,requiment_summ
		   ,requiment_summ_rest
		   ,recovered_summ
		FROM dbo.arr_reestr_check_requiment
		WHERE id_reestr = A.id
		ORDER BY requiment_date, id DESC	
	) B
	WHERE A.id=@id_reestr
	
END


GO
/****** Object:  StoredProcedure [dbo].[PR_EXEC_ALL_EOD]    Script Date: 26.02.2016 17:49:17 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		Трусов Олег
-- Create date: 10.08.2015
-- Description:	Выполнение процедуры по всем базам ИФНС
-- =============================================
CREATE PROCEDURE [dbo].[PR_EXEC_ALL_EOD]
	@pr_name varchar(100)
AS
BEGIN
	DECLARE @eodDatabases TABLE (code VARCHAR(4))
	INSERT INTO @eodDatabases VALUES ('01')
	INSERT INTO @eodDatabases VALUES ('02')
	INSERT INTO @eodDatabases VALUES ('03')
	INSERT INTO @eodDatabases VALUES ('06')
	INSERT INTO @eodDatabases VALUES ('07')
	INSERT INTO @eodDatabases VALUES ('08')
	INSERT INTO @eodDatabases VALUES ('10')
	INSERT INTO @eodDatabases VALUES ('11')
	INSERT INTO @eodDatabases VALUES ('17')
	INSERT INTO @eodDatabases VALUES ('19')
	INSERT INTO @eodDatabases VALUES ('22')
	INSERT INTO @eodDatabases VALUES ('24')
	
	DECLARE @code VARCHAR(4)
	DECLARE cursor_databases CURSOR FOR 
		SELECT code FROM @eodDatabases
	
	OPEN cursor_databases
	
	FETCH NEXT FROM cursor_databases INTO @code
	
	WHILE @@FETCH_STATUS=0
	BEGIN
			
		IF (EXISTS(SELECT * FROM master.sys.databases
			WHERE [name]='Taxes'+@code AND [state]=0))
		BEGIN
			EXEC (@pr_name + ' @lnk = '''+@code+'''')
			
			PRINT 'Процедура ' + @pr_name + ' выполнена на Taxes' + @code
			
		END 
		ELSE
		BEGIN
			PRINT 'Процедуру ' + @pr_name + ' не удалось выполнить, т.к. Taxes'
				+ @code + ' недоступна'
		END
		
		FETCH NEXT FROM cursor_databases INTO @code
		
	END
	
	CLOSE cursor_databases
	DEALLOCATE cursor_databases
	
END

GO
/****** Object:  StoredProcedure [dbo].[PR_GET_ARREARS_EOD]    Script Date: 26.02.2016 17:49:17 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		<Author,,Name>
-- Create date: <Create Date,,>
-- Description:	<Description,,>
-- =============================================
CREATE PROCEDURE [dbo].[PR_GET_ARREARS_EOD]
	@lnk varchar(2)
AS
BEGIN
	
	EXEC ('
		-- Таблица для промежуточного хранения информации из ПК СЭОД	
	IF OBJECT_ID(''tempdb..#eod_data'') IS NULL
	BEGIN		
		create table #eod_data (		
			code_NO varchar(4) not null, 	 -- код НО
			id_NP int not null,				 -- УН лица
			type_NP int not null,			 -- тип лица
			id_check int not null, 			 -- УН проверки
			name_NP varchar(160) not null, 	 -- наименование налогоплательщика
			inn_NP varchar(12) null, 		 -- ИНН налогоплательщика
			kpp_NP varchar(9) null, 		 -- КПП налогоплательщика
			sum decimal(18,2) not null, 	 -- сумма
			num_resolution varchar(25) null, -- номер решения
			date_resolution datetime null 	 -- дата решения
		)	
	END
	-- Выборка 1
	-- Ветка "Контрольная деятельность\ВНП, другие проверки, сведения внешних корреспондентов\Учет сведений (QBE по решениям)", QBE: "Доступные проверки"	
	DECLARE @T1 TABLE (id int)
	INSERT INTO @T1
	SELECT FN800.D270_2 FROM Taxes'+@lnk+'.dbo.FN800 
		join Taxes'+@lnk+'.dbo.FN212 FN212 (nolock) on FN800.N1_1=FN212.N1 
		join Taxes'+@lnk+'.dbo.FN213 FN213 (nolock) on FN212.N314=FN213.N314 
		join (Taxes'+@lnk+'.dbo.FN1189 FN1189 
			join Taxes'+@lnk+'.dbo.FN1190 on FN1189.D895 = FN1190.D895 
			join Taxes'+@lnk+'.dbo.FN74 sa on FN1190.N269 = sa.N269 
				and sa.D686 = (select distinct nt_username from master..sysprocesses where spid=@@SPID)) on FN1189.D895 = FN800.D895
		left join Taxes'+@lnk+'.dbo.FN1532 on FN1532.D270=FN800.D270_1
		left join Taxes'+@lnk+'.dbo.FN1016 on FN1016.D81=FN1532.D81
		left join (Taxes'+@lnk+'.dbo.FN1532 Decision join Taxes'+@lnk+'.dbo.FN1016 DecisionDoc on Decision.D81=DecisionDoc.D81  
		left join Taxes'+@lnk+'.dbo.FN1017 FN1017 on FN1017.D172=DecisionDoc.D172
			) on Decision.D270=FN800.D270_2
		left join Taxes'+@lnk+'.dbo.FN212 FN212Doc on FN212Doc.N1=Decision.N1
		left join Taxes'+@lnk+'.dbo.FN800_VRUCH va on va.N800__1=FN800.N800__1
		left join Taxes'+@lnk+'.dbo.FN772 FN772 on va.N772__1_3=FN772.N772__1
		left join Taxes'+@lnk+'.dbo.FN800269 a on a.N800__1=FN800.N800__1 and a.S8=3
		left join Taxes'+@lnk+'.dbo.FN212 FN212A on FN212A.N1=Decision.N1_1 and Decision.N1_1<>0
		left join Taxes'+@lnk+'.dbo.FN4411 FN212AU on FN212AU.N4411__1=a.N4411__1
		left join Taxes'+@lnk+'.dbo.FN800269 b on b.N800__1=FN800.N800__1 and b.S2=1
		left join Taxes'+@lnk+'.dbo.FN74_VNP FN74A on FN74A.N269_VNP=b.N269_VNP and FN74A.N269_VNP<>0
		left join Taxes'+@lnk+'.dbo.FN74 e on e.N269 = FN800.N269_101  and e.N269<>0
		left join Taxes'+@lnk+'.dbo.FN800269 c on c.N800__1=FN800.N800__1 and c.S3=1
		left join Taxes'+@lnk+'.dbo.FN74_VNP FN74B on FN74B.N269_VNP=c.N269_VNP and FN74B.N269_VNP<>0
		left join Taxes'+@lnk+'.dbo.FN74 f on f.N269 = FN800.N269_2  and f.N269<>0
		left join Taxes'+@lnk+'.dbo.FN800DOP FN800DOP on FN800DOP.N800__1=FN800.N800__1 and FN800DOP.N800DOP__2=5 and FN800DOP.N800DOP__21=3
		left join Taxes'+@lnk+'.dbo.FN800T FN800T on FN800T.N800__1=FN800.N800__1 and FN800T.N800T__3=0
		left join Taxes'+@lnk+'.dbo.FN74 ff on ff.N269=FN800T.N269
					left join Taxes'+@lnk+'.dbo.FN800DOP2 FN800DOP2 on FN800DOP2.n800__1= FN800.N800__1
		left join Taxes'+@lnk+'.dbo.FN74 vr on vr.N269 = va.N269_1  and vr.N269<>0
		left join Taxes'+@lnk+'.dbo.FN74_VNP vnpvr on vnpvr.N269_VNP = va.N269_VNP_1  and vnpvr.N269_VNP<>0
	WHERE (IsNull(FN1016.D172,0)<>216 and IsNull(DecisionDoc.D172,0)<>316 
		and not exists(select 1 from Taxes'+@lnk+'.dbo.FN819 FN819 where FN819.N739__1=17 and FN819.N800__1=FN800.N800__1 and FN819.N800__1_1 is NULL) and IsNull(FN1016.D430,0) <>2727) 
		AND ((DecisionDoc.D172 IN (145,146))) 
	GROUP BY FN800.D270_2



	-- Выборка 2
	-- Ветка "Контрольная деятельность\ВНП, другие проверки, сведения внешних корреспондентов\Учет сведений (QBE по решениям)", QBE: "Проверка + Налог + Операция"
	INSERT INTO #eod_data
	SELECT 
		''86'+@lnk+''' 		-- код ИФНС (устанавливается вручную в зависимости от выбранной базы Инспекции)
		,FN212.N1			-- УН лица
		,FN212.N312			-- тип лица
		,FN800.N800__1 		-- УН проверки
		,FN212.N18 			-- наименование налогоплательщика
		,FN212.N134 		-- ИНН налогоплательщика
		,FN212.D3 			-- КПП налогоплательщика
		,SUM(FN801.N801__3) -- сумма
		,Decision.N590 		-- номер решения
		,Decision.D223 		-- дата решения
	FROM Taxes'+@lnk+'.dbo.FN800 
		join Taxes'+@lnk+'.dbo.FN212 on FN800.N1_1=FN212.N1
		join (Taxes'+@lnk+'.dbo.FN1189 join Taxes'+@lnk+'.dbo.FN1190 on FN1189.D895 =  FN1190.D895 
		join Taxes'+@lnk+'.dbo.FN74 sa on FN1190.N269 = sa.N269 and  sa.D686 = (select distinct nt_username from master..sysprocesses where spid=@@SPID)) on FN1189.D895 = FN800.D895
		left join Taxes'+@lnk+'.dbo.FN1532 on FN1532.D270=FN800.D270_1
		left join Taxes'+@lnk+'.dbo.FN1016 on FN1016.D81=FN1532.D81
				 left join Taxes'+@lnk+'.dbo.FN1532 Decision on Decision.D270=FN800.D270_2
		left join Taxes'+@lnk+'.dbo.FN1016 DecisionDoc on Decision.D81=DecisionDoc.D81
				 left join  (Taxes'+@lnk+'.dbo.FN830 join Taxes'+@lnk+'.dbo.FN1002 on FN830.D6=FN1002.D6 
				 left join Taxes'+@lnk+'.dbo.fn42 (NOLOCK)  On Fn42.n1270=fn1002.n1270
			left join Taxes'+@lnk+'.dbo.FN801 on FN830.N830__1=FN801.N830__1 and FN801.N801__8 = 3
			left join Taxes'+@lnk+'.dbo.FN74 on FN74.N269=FN801.N269_1
			left join Taxes'+@lnk+'.dbo.FN1020 on FN801.D11=FN1020.D11
 			left join Taxes'+@lnk+'.dbo.FN1501 on FN801.D300__1=FN1501.D300
			left join Taxes'+@lnk+'.dbo.FN1003 on FN801.D8 = FN1003.D8
			left join Taxes'+@lnk+'.dbo.FN806 on FN806.N830__1=FN830.N830__1
			left join Taxes'+@lnk+'.dbo.FN1262 on FN1262.D2718=FN1002.D2718
			left join Taxes'+@lnk+'.dbo.FN1262 FN1262a on FN1262a.D2718=FN1003.D2718
			left join Taxes'+@lnk+'.dbo.FN74 FPDDL on FPDDL.N269=FN801.N269_200
	) on  FN830.N800__1= FN800.N800__1
	WHERE IsNull(FN1016.D172,0)<>216 and IsNull(DecisionDoc.D172,0)<>316  and not exists(select 1 from Taxes'+@lnk+'.dbo.FN819 where FN819.N739__1=17 and FN819.N800__1=FN800.N800__1 and FN819.N800__1_1 is NULL) and IsNull(FN1016.D430,0) <>2727 
		AND FN800.D270_2 IN (SELECT id FROM @T1)
	GROUP BY FN212.N1, FN212.N312, FN212.N18, FN212.N134, FN212.D3, Decision.N590, Decision.D223, FN800.N800__1  
	HAVING (SUM(FN801.N801__3) > 0)
	
	INSERT INTO arr_reestr_check 
		(id_NP,type_NP,id_check,code_NO,code_NO_current_dept,inn_NP,kpp_NP,name_NP,credit_addational,resolution_date,resolution_number,type_check,date_create,log_change)
	
	SELECT DISTINCT b.id_NP,b.type_NP,b.id_check,b.code_NO,a.code_NO,b.inn_NP,b.kpp_NP,b.name_NP,(b.sum/1000),b.date_resolution,b.num_resolution,1,
		getdate(),convert(varchar,getdate(),104) + '' ''+convert(varchar,getdate(),108)+''|8600_SVC_Manager|создание''
	FROM (
		SELECT  
			IMNS   [code_NO], -- код НО	
			S10100 [inn_NP], -- ИНН
			S10200 [kpp_NP], -- КПП 
			S10300 [name_NP] -- Наименование
		FROM [u8600-app004].[pa_2015].dbo.BC_KLS075 a       
			LEFT JOIN [U8600-APP004].[pknsi].dbo.KDB5KAZNA e 
				ON a.S10700 COLLATE CYRILLIC_GENERAL_CI_AS=e.KOD COLLATE CYRILLIC_GENERAL_CI_AS      
			LEFT join [u8600-app004].[pa_2015].dbo.BC_UNL075 f 
				ON a.id_dc=f.id_bc_kls 
		WHERE IMNS LIKE ''86__'' AND (N33001>0.00 OR N33002>0.00 OR N33003>0.00) 
		GROUP BY [IMNS],[S10100],[S10200],[S10300]
	) as a
		INNER JOIN #eod_data as b 
			ON a.inn_NP collate SQL_Latin1_General_CP1251_CI_AS=b.inn_NP 
			AND ISNULL(a.kpp_NP collate SQL_Latin1_General_CP1251_CI_AS,'''')=ISNULL(b.kpp_NP collate SQL_Latin1_General_CP1251_CI_AS,'''')
			--AND a.code_NO collate SQL_Latin1_General_CP1251_CI_AS=b.code_NO
		
	')
	
END

GO
/****** Object:  StoredProcedure [dbo].[PR_GET_ARREARS_EOD_2]    Script Date: 26.02.2016 17:49:17 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		<Author,,Name>
-- Create date: <Create Date,,>
-- Description:	<Description,,>
-- =============================================
CREATE PROCEDURE [dbo].[PR_GET_ARREARS_EOD_2]
	@lnk varchar(2)
AS
BEGIN
	
	EXEC ('
		
	CREATE TABLE #RelDC 
		(N800__1 int, N800__1_1 int, N739__1 smallint, FN819_D301 smalldatetime,
			N1_1 int, RelD270_1 int, RelD270_2 int, RelD270_3 int, N800__900 bit)

	create index RelDC_800 on #RelDC(N800__1)

	insert into #RelDC
	select FN819.N800__1,  FN819.N800__1_1, FN819.N739__1, FN819.D301,
		FN800.N1_1, FN800.D270_1, FN800.D270_2, FN800.D270_3, FN800.N800__900
	from Taxes'+@lnk+'.dbo.FN819 
		left join Taxes'+@lnk+'.dbo.FN800 on FN800.N800__1 = FN819.N800__1_1


	DECLARE @id_check INT
	DECLARE @id_NP INT

	DECLARE reestr_cursor CURSOR FOR
		SELECT id_check, id_NP FROM arrears.dbo.arr_reestr_check WHERE code_NO=''86'+@lnk+'''
		
	OPEN reestr_cursor

	FETCH NEXT FROM reestr_cursor
		INTO @id_check, @id_NP
		
	WHILE @@FETCH_STATUS = 0
	BEGIN
		
		/* ***********************************************************
			№ требования об уплате
			Дата требования об уплате
			Срок уплаты по требованию
			Сумма, включенная в требование (тыс. руб.)
			Остаток непогашенной суммы по требованию (тыс. руб.)
		************************************************************* */
			
		-- Оперативно-бухгалтерский учет\Недоимка\Налоговые проверки
		declare @system_number int
		SELECT @system_number = t.D270 FROM (
			select a.d270, a.n1, 
			(CASE COUNT(distinct isnull(a.D2022, 0)) WHEN 1 THEN (CASE sum(distinct a.d2022) WHEN 0 THEN 12 ELSE 10 END) ELSE 14 END) as status, 
			a.n120, sum(abs(a.d83)) as d83, a.d81
		  from Taxes'+@lnk+'.dbo.fn1500 a (nolock)
		  join Taxes'+@lnk+'.dbo.vDocsProverk v (nolock) on a.d81 = v.d81
		  where a.d270 is not null and a.d812 = 1 
			and  (not exists(select 1 from Taxes'+@lnk+'.dbo.fn10023 n1 (nolock) where n1.d6_1 = a.d6) or a.d512 > 0)
			and not (a.d12 in (1, 2, 6) and a.d244 = 195)
		  group by a.d270, a.n1, a.n120, a.d81
		  ) t
		  join Taxes'+@lnk+'.dbo.fn212 (nolock) on fn212.n1 = t.n1
		  join Taxes'+@lnk+'.dbo.fn52 (nolock) on fn52.n120 = t.n120
		  join Taxes'+@lnk+'.dbo.fn1532 (nolock) on fn1532.d270 = t.d270
		  left join Taxes'+@lnk+'.dbo.fn800 (nolock) on fn800.d270_2 = fn1532.d270
		  join Taxes'+@lnk+'.dbo.fn1016 (nolock) on t.d81 = fn1016.d81
		  left join Taxes'+@lnk+'.dbo.fn1534 (nolock) on fn1534.d270 = T.D270
		  left join Taxes'+@lnk+'.dbo.fn208 (nolock) on fn212.n1 = fn208.n1
		  left join (select distinct a.d270
			from Taxes'+@lnk+'.dbo.fn1532 a (nolock)
			join Taxes'+@lnk+'.dbo.fn1016 vid (nolock) on vid.d81 = a.d81
			join Taxes'+@lnk+'.dbo.fn800 PrGol (nolock) on PrGol.d270_2 = a.d270
			join Taxes'+@lnk+'.dbo.fn800op (nolock) on fn800op.n800__1 = PrGol.n800__1
			where vid.d172 in (145, 146)
			  and fn800op.prizn_op = 1--Признак проверки ПО
			  and exists(select 1 from Taxes'+@lnk+'.dbo.fn819 (nolock) where fn819.n800__1 = PrGol.n800__1 and fn819.n739__1 = 7)
		  ) ReshVnpOp on ReshVnpOp.d270 = fn1532.d270--решения по ВНП для общего требования
		  left join (select distinct fn800.d270_2 
			from Taxes'+@lnk+'.dbo.fn819 (nolock) 
			join Taxes'+@lnk+'.dbo.fn800 (nolock) on fn800.n800__1 = fn819.n800__1 
			join Taxes'+@lnk+'.dbo.fn800 PrGol (nolock) on PrGol.N800__1 = fn819.n800__1_1
			join Taxes'+@lnk+'.dbo.fn800op (nolock) on fn800op.n800__1 = PrGol.n800__1 and fn800op.prizn_op = 1
			where fn819.n739__1 = 12-- and fn819.n800__1_1 = <<5>>
			  and exists(select 1 from Taxes'+@lnk+'.dbo.fn819 (nolock) where fn819.n800__1 = PrGol.n800__1 and fn819.n739__1 = 7)
		  ) ReshVnpOPFil on ReshVnpOPFil.d270_2 = fn1532.d270--решения по ВНП по филиалам для общего требования
		  left join (select distinct fn1534.d270
			from Taxes'+@lnk+'.dbo.fn1534 (nolock)
			join Taxes'+@lnk+'.dbo.CamResh (nolock) on CamResh.d270 = fn1534.d270
			where fn1534.d430 in (15, 372)
			  and CamResh.PrizProvOp = 1
		  ) ReshKnpOp on ReshKnpOp.d270 = fn1534.d270--решения по КНП для общего требования
		  left join(
			select lsb.n1, lsb.n120, lsb.d270, count(lsb.d300) as ''col'',
			count(case when lsb.D83=lsb.D512 then lsb.d300 end) as ''col_12'',
			count(case when lsb.D512=0 then lsb.d300 end) as ''col_10''
			 from Taxes'+@lnk+'.dbo.fn1500 lsb
			 JOIN Taxes'+@lnk+'.dbo.fn1020   (nolock) ON fn1020.d11 = lsb.d11 
		   where lsb.D812=1 and fn1020.d09_1 = 0
		   group by lsb.n1, lsb.n120, lsb.d270
		   )b  on b.N1=t.n1 and b.N120=t.n120  and b.d270=t.d270 
		  left join (select Resh.d270, max(fn1532.d1967) as d1967
			from Taxes'+@lnk+'.dbo.fn1532 Resh (nolock)
			join Taxes'+@lnk+'.dbo.fn800 osnResh (nolock) on osnResh.d270_2 = Resh.d270
			join Taxes'+@lnk+'.dbo.FN819 (nolock) on FN819.N800__1_1 = osnResh.N800__1 and FN819.N739__1 = 16
			join Taxes'+@lnk+'.dbo.fn800 apell (nolock) on apell.N800__1 = FN819.N800__1
			join Taxes'+@lnk+'.dbo.fn1532 (nolock) on fn1532.d270 = apell.d270_2
			group by Resh.d270
		  ) ApellVNO on ApellVNO.d270 = t.d270
		WHERE (ReshKnpOp.d270 is null and ReshVnpOp.d270 is null and ReshVnpOPFil.d270_2 is null) 
			AND ((fn800.N800__1 = @id_check))  -- поиск по УН проверки

			
		-- Оперативно-бухгалтерский учет\Недоимка\Налоговые проверки
		---- подрежим "Сформированные требования"		
		INSERT INTO [arrears].[dbo].[arr_reestr_check_requiment] 
			([id_reestr],[requiment_number],[requiment_date],[requiment_summ],[requiment_term])
		SELECT --TOP 1
			@id_check,	 -- @УН проверки
			t.D865,		 -- @УН решения
			''86'+@lnk+''',		 -- Код НО
			t.D86,		 -- Номер требования об уплате
			t.D87,		 -- Дата требования об уплате
			t.D83/1000,  -- Сумма включенная в требование (тыс. руб.)
			t.D1967		 -- Срок уплаты по требованию
		FROM Taxes'+@lnk+'.dbo.FN1577 (nolock) 
		  Join Taxes'+@lnk+'.dbo.FN1519 (nolock) On fn1519.D850=FN1577.D850
		  Join Taxes'+@lnk+'.dbo.FN1517 t (nolock) On t.D850=FN1577.D850
		  Join Taxes'+@lnk+'.dbo.FN1182 (nolock) On FN1182.D865=t.D865  
		  Join Taxes'+@lnk+'.dbo.FN1016 (nolock) On FN1016.D81=t.D81 
		  Join Taxes'+@lnk+'.dbo.FN1082 (nolock) On FN1082.D428=t.D428
		  left join Taxes'+@lnk+'.dbo.fn1175 (nolock) on fn1175.d832=fn1577.d832
		  left join Taxes'+@lnk+'.dbo.fn1517 rr (nolock) on rr.d851=t.D851_2
		  CROSS JOIN Taxes'+@lnk+'.dbo.fn1044 (nolock) 
		  left join Taxes'+@lnk+'.dbo.fn15179 (nolock) on fn15179.d851=t.d851
		  left join Taxes'+@lnk+'.dbo.fn1257 on fn1257.d3025=fn15179.d3025
		  left join Taxes'+@lnk+'.dbo.fn1517_19 pr (nolock) on t.d851=pr.d851 and pr.d270_2 is null
		  left join (select distinct  a.d865,max(svod.d270) as d270 
			from Taxes'+@lnk+'.dbo.fn1596 a (nolock)
			join Taxes'+@lnk+'.dbo.fn1596 struct_svod (nolock) on struct_svod.d307 = a.d307
			join Taxes'+@lnk+'.dbo.fn1517 svod on struct_svod.d865 = svod.d865
			where svod.d81 = 45 
			group by a.d865
		  ) svod on t.d865 = svod.d865
		WHERE (FN1577.D863=1 and fn1016.d430 <> 4) 
			AND (fn1577.n1=@id_NP and fn1519.n120 = 1 and fn1016.d430 in (183, 184, 1352) 
			AND t.d851 in (select d851 from Taxes'+@lnk+'.dbo.fn15177 where d270 = @system_number)) 
		GROUP BY t.d851, t.D865, t.D86, t.D87, t.D83, t.D1967
		ORDER BY t.d851 DESC
		
		
		IF (EXISTS(SELECT 1 FROM [arrears].[dbo].[arr_reestr_check_requiment] WHERE [id_reestr_check]=@id_check AND [code_NO]=''86'+@lnk+'''))
		BEGIN
			UPDATE [arrears].[dbo].[arr_reestr_check_requiment]
				-- Остаток непогашенной суммы по требованию (тыс. руб.)
				SET [requiment_summ_rest] = (
					-- Оперативно-бухгалтерский учет\Недоимка\Структура документа дела
					SELECT SUM(abs(FN1500.D512))/1000 FROM Taxes'+@lnk+'.dbo.fn1577 (nolock)
					  join Taxes'+@lnk+'.dbo.fn1517 (nolock) on fn1577.d850=fn1517.d850 and fn1517.d3505=0
					  join Taxes'+@lnk+'.dbo.fn1596 (nolock) on fn1517.d865=fn1596.d865 
					  join Taxes'+@lnk+'.dbo.fn212 (nolock) on fn1517.n1=fn212.n1
					  join Taxes'+@lnk+'.dbo.FN1016 (nolock) on FN1016.D81=FN1517.D81
					  join Taxes'+@lnk+'.dbo.fn1080 (nolock) on fn1080.d430=fn1016.d430
					  join Taxes'+@lnk+'.dbo.fn1500 (nolock) on fn1500.d307=fn1596.d307
					  join Taxes'+@lnk+'.dbo.FN1002 (nolock) on FN1002.D6 =fn1500.D6
					  join Taxes'+@lnk+'.dbo.FN1011 (nolock) on FN1011.D73=fn1500.D73
					  join Taxes'+@lnk+'.dbo.fn1020 (nolock) on fn1500.d11=fn1020.d11
					  join Taxes'+@lnk+'.dbo.fn1143 (nolock) on fn1596.d2578=fn1143.d2578
					  left join Taxes'+@lnk+'.dbo.fn1516 (nolock) on fn1516.d307=fn1596.d307 and fn1516.d850=FN1517.D850
					  join Taxes'+@lnk+'.dbo.fn1262 e (nolock) on e.D2718=fn1002.D2718
					  left join Taxes'+@lnk+'.dbo.fn208 (nolock) on fn212.n1 = fn208.n1
					  Join Taxes'+@lnk+'.dbo.FN1182 (nolock) On FN1182.D865=fn1517.D865  
					  JOIN Taxes'+@lnk+'.dbo.FN1041 (nolock) on fn212.N1=FN1041.N1 and  fn1500.N120=FN1041.N120 and fn1500.D6=FN1041.D6
					WHERE (FN1517.D865 = A.[id_resolution])
				)
				FROM [arrears].[dbo].[arr_reestr_check_requiment] A
			WHERE [id_reestr_check] = @id_check
			
			UPDATE [arrears].[dbo].[arr_reestr_check] 
				SET [requiment_id_resolution] = (
					SELECT TOP 1 [id_resolution] FROM [arrears].[dbo].[arr_reestr_check_requiment] 
						WHERE [id_reestr_check]=@id_check AND [code_NO]=''86'+@lnk+''' ORDER BY [id_resolution] DESC)
			WHERE [id_check]=@id_check AND [code_NO]=''86'+@lnk+'''				
			
		END
		
		
		
		/* ****************************************************************************
			Уменьшено по решению вышестоящего налогового органа, всего (тыс. руб.)
		***************************************************************************** */	

		DECLARE @id INT
		SET @id = NULL
		SELECT TOP 1 @id = FN800.D270_2 FROM Taxes'+@lnk+'.dbo.FN800 
			join (Taxes'+@lnk+'.dbo.FN1189 join Taxes'+@lnk+'.dbo.FN1190 on FN1189.D895 =  FN1190.D895 
				join Taxes'+@lnk+'.dbo.FN74 sa on FN1190.N269 = sa.N269 and  sa.D686 = (select distinct nt_username from master..sysprocesses where spid=@@SPID)) on FN1189.D895 = FN800.D895
			join Taxes'+@lnk+'.dbo.FN212 on FN800.N1_1=FN212.N1
			left join Taxes'+@lnk+'.dbo.FN1532 Decision on Decision.D270=FN800.D270_2
			left join Taxes'+@lnk+'.dbo.FN1016 DecisionDoc on Decision.D81=DecisionDoc.D81
			left join (Taxes'+@lnk+'.dbo.FN1532 join Taxes'+@lnk+'.dbo.FN1016 on FN1532.D81=FN1016.D81  left join Taxes'+@lnk+'.dbo.FN1017 on FN1017.D172=FN1016.D172) on FN1532.D270=FN800.D270_1
			left join #RelDC on FN800.N800__1 = #RelDC.N800__1
			join Taxes'+@lnk+'.dbo.FN739 on #RelDC.N739__1 = FN739.N739__1
			left join Taxes'+@lnk+'.dbo.FN212 RelOrg on RelOrg.N1=#RelDC.N1_1
			left join (Taxes'+@lnk+'.dbo.FN1532 RelAct join Taxes'+@lnk+'.dbo.FN1016 RA  on RelAct.D81 = RA.D81  left join Taxes'+@lnk+'.dbo.FN1017 RDT on RDT.D172=RA.D172) on RelAct.D270 = #RelDC.RelD270_1
			left join (Taxes'+@lnk+'.dbo.FN1532 RelDec join Taxes'+@lnk+'.dbo.FN1016 RD on RelDec.D81 = RD.D81) on RelDec.D270 = #RelDC.RelD270_2
			left join (Taxes'+@lnk+'.dbo.FN1532 RelCert join Taxes'+@lnk+'.dbo.FN1016 RC on RelCert.D81 = RC.D81) on RelCert.D270 = #RelDC.RelD270_3
						left join Taxes'+@lnk+'.dbo.fn800op on fn800op.n800__1 = fn800.n800__1
		WHERE (IsNull(FN1016.D172,0)<>216 and IsNull(DecisionDoc.D172,0)<>316 and not exists(select 1 from Taxes'+@lnk+'.dbo.FN819 where FN819.N739__1=17 and FN819.N800__1=FN800.N800__1 and FN819.N800__1_1 is NULL) and IsNull(FN1016.D430,0) <>2727) 
			AND ((FN800.N800__1 = @id_check) AND (#RelDC.N739__1 IN (10,16))) 
			AND FN800.D270_2 IS NOT NULL
		GROUP BY FN800.D270_2  

		
		IF (@id IS NOT NULL)
		BEGIN
			UPDATE [arrears].[dbo].[arr_reestr_check]
				SET [reduced_higher_NO_summ] = (
					SELECT SUM(FN801.N801__3)/1000 FROM Taxes'+@lnk+'.dbo.FN800 
						join Taxes'+@lnk+'.dbo.FN212 on FN800.N1_1=FN212.N1
								 join (Taxes'+@lnk+'.dbo.FN1189 join Taxes'+@lnk+'.dbo.FN1190 on FN1189.D895 =  FN1190.D895 
							join Taxes'+@lnk+'.dbo.FN74 sa on FN1190.N269 = sa.N269 and  sa.D686 = (select distinct nt_username from master..sysprocesses where spid=@@SPID)) on FN1189.D895 = FN800.D895
						left join Taxes'+@lnk+'.dbo.FN1532 on FN1532.D270=FN800.D270_1
						left join Taxes'+@lnk+'.dbo.FN1016 on FN1016.D81=FN1532.D81
								 left join Taxes'+@lnk+'.dbo.FN1532 Decision on Decision.D270=FN800.D270_2
						left join Taxes'+@lnk+'.dbo.FN1016 DecisionDoc on Decision.D81=DecisionDoc.D81
								 left join  (Taxes'+@lnk+'.dbo.FN830 join Taxes'+@lnk+'.dbo.FN1002 on FN830.D6=FN1002.D6 
								 left join Taxes'+@lnk+'.dbo.fn42 (NOLOCK)  On Fn42.n1270=fn1002.n1270
							left join Taxes'+@lnk+'.dbo.FN801 on FN830.N830__1=FN801.N830__1 and FN801.N801__8 = 3
							left join Taxes'+@lnk+'.dbo.FN74 on FN74.N269=FN801.N269_1
							left join Taxes'+@lnk+'.dbo.FN1020 on FN801.D11=FN1020.D11
							left join Taxes'+@lnk+'.dbo.FN1501 on FN801.D300__1=FN1501.D300
							left join Taxes'+@lnk+'.dbo.FN1003 on FN801.D8 = FN1003.D8
							left join Taxes'+@lnk+'.dbo.FN806 on FN806.N830__1=FN830.N830__1
							left join Taxes'+@lnk+'.dbo.FN1262 on FN1262.D2718=FN1002.D2718
							left join Taxes'+@lnk+'.dbo.FN1262 FN1262a on FN1262a.D2718=FN1003.D2718
							left join Taxes'+@lnk+'.dbo.FN74 FPDDL on FPDDL.N269=FN801.N269_200
					) on  FN830.N800__1= FN800.N800__1
					 WHERE ( IsNull(FN1016.D172,0)<>216 and IsNull(DecisionDoc.D172,0)<>316  and not exists(select 1 from Taxes'+@lnk+'.dbo.FN819 where FN819.N739__1=17 and FN819.N800__1=FN800.N800__1 and FN819.N800__1_1 is NULL) and IsNull(FN1016.D430,0) <>2727) 
						AND ((FN800.D270_2 = @id))
				)
			WHERE [id_check]=@id_check AND [code_NO]=''86'+@lnk+'''	
		END

		
		/* ***********************************************************
			Уменьшено по решению Арбитражного суда   (тыс. руб.)
		************************************************************** */
			
		
		SET @id = NULL
		SELECT TOP 1 @id = FN800.D270_2 FROM Taxes'+@lnk+'.dbo.FN800 
			join (Taxes'+@lnk+'.dbo.FN1189 join Taxes'+@lnk+'.dbo.FN1190 on FN1189.D895 =  FN1190.D895 
				join Taxes'+@lnk+'.dbo.FN74 sa on FN1190.N269 = sa.N269 and  sa.D686 = (select distinct nt_username from master..sysprocesses where spid=@@SPID)) on FN1189.D895 = FN800.D895
			join Taxes'+@lnk+'.dbo.FN212 on FN800.N1_1=FN212.N1
			left join Taxes'+@lnk+'.dbo.FN1532 Decision on Decision.D270=FN800.D270_2
			left join Taxes'+@lnk+'.dbo.FN1016 DecisionDoc on Decision.D81=DecisionDoc.D81
			left join (Taxes'+@lnk+'.dbo.FN1532 join Taxes'+@lnk+'.dbo.FN1016 on FN1532.D81=FN1016.D81  left join Taxes'+@lnk+'.dbo.FN1017 on FN1017.D172=FN1016.D172) on FN1532.D270=FN800.D270_1
			left join #RelDC on FN800.N800__1 = #RelDC.N800__1
			join Taxes'+@lnk+'.dbo.FN739 on #RelDC.N739__1 = FN739.N739__1
			left join Taxes'+@lnk+'.dbo.FN212 RelOrg on RelOrg.N1=#RelDC.N1_1
			left join (Taxes'+@lnk+'.dbo.FN1532 RelAct join Taxes'+@lnk+'.dbo.FN1016 RA  on RelAct.D81 = RA.D81  left join Taxes'+@lnk+'.dbo.FN1017 RDT on RDT.D172=RA.D172) on RelAct.D270 = #RelDC.RelD270_1
			left join (Taxes'+@lnk+'.dbo.FN1532 RelDec join Taxes'+@lnk+'.dbo.FN1016 RD on RelDec.D81 = RD.D81) on RelDec.D270 = #RelDC.RelD270_2
			left join (Taxes'+@lnk+'.dbo.FN1532 RelCert join Taxes'+@lnk+'.dbo.FN1016 RC on RelCert.D81 = RC.D81) on RelCert.D270 = #RelDC.RelD270_3
						left join Taxes'+@lnk+'.dbo.fn800op on fn800op.n800__1 = fn800.n800__1
		WHERE (IsNull(FN1016.D172,0)<>216 and IsNull(DecisionDoc.D172,0)<>316 and not exists(select 1 from Taxes'+@lnk+'.dbo.FN819 where FN819.N739__1=17 and FN819.N800__1=FN800.N800__1 and FN819.N800__1_1 is NULL) and IsNull(FN1016.D430,0) <>2727) 
			AND ((FN800.N800__1 = @id_check) AND (#RelDC.N739__1 IN (5))) 
			AND FN800.D270_2 IS NOT NULL
		GROUP BY FN800.D270_2  
		

		IF (@id IS NOT NULL)
		BEGIN
			UPDATE [arrears].[dbo].[arr_reestr_check]
				SET [reduced_arb_court_summ] = (				
					SELECT SUM(FN801.N801__3)/1000 FROM Taxes'+@lnk+'.dbo.FN800 
						join Taxes'+@lnk+'.dbo.FN212 on FN800.N1_1=FN212.N1
								 join (Taxes'+@lnk+'.dbo.FN1189 join Taxes'+@lnk+'.dbo.FN1190 on FN1189.D895 =  FN1190.D895 
							join Taxes'+@lnk+'.dbo.FN74 sa on FN1190.N269 = sa.N269 and  sa.D686 = (select distinct nt_username from master..sysprocesses where spid=@@SPID)) on FN1189.D895 = FN800.D895
						left join Taxes'+@lnk+'.dbo.FN1532 on FN1532.D270=FN800.D270_1
						left join Taxes'+@lnk+'.dbo.FN1016 on FN1016.D81=FN1532.D81
								 left join Taxes'+@lnk+'.dbo.FN1532 Decision on Decision.D270=FN800.D270_2
						left join Taxes'+@lnk+'.dbo.FN1016 DecisionDoc on Decision.D81=DecisionDoc.D81
								 left join  (Taxes'+@lnk+'.dbo.FN830 join Taxes'+@lnk+'.dbo.FN1002 on FN830.D6=FN1002.D6 
								 left join Taxes'+@lnk+'.dbo.fn42 (NOLOCK)  On Fn42.n1270=fn1002.n1270
							left join Taxes'+@lnk+'.dbo.FN801 on FN830.N830__1=FN801.N830__1 and FN801.N801__8 = 3
							left join Taxes'+@lnk+'.dbo.FN74 on FN74.N269=FN801.N269_1
							left join Taxes'+@lnk+'.dbo.FN1020 on FN801.D11=FN1020.D11
							left join Taxes'+@lnk+'.dbo.FN1501 on FN801.D300__1=FN1501.D300
							left join Taxes'+@lnk+'.dbo.FN1003 on FN801.D8 = FN1003.D8
							left join Taxes'+@lnk+'.dbo.FN806 on FN806.N830__1=FN830.N830__1
							left join Taxes'+@lnk+'.dbo.FN1262 on FN1262.D2718=FN1002.D2718
							left join Taxes'+@lnk+'.dbo.FN1262 FN1262a on FN1262a.D2718=FN1003.D2718
							left join Taxes'+@lnk+'.dbo.FN74 FPDDL on FPDDL.N269=FN801.N269_200
					) on  FN830.N800__1= FN800.N800__1
					 WHERE ( IsNull(FN1016.D172,0)<>216 and IsNull(DecisionDoc.D172,0)<>316  and not exists(select 1 from Taxes'+@lnk+'.dbo.FN819 where FN819.N739__1=17 and FN819.N800__1=FN800.N800__1 and FN819.N800__1_1 is NULL) and IsNull(FN1016.D430,0) <>2727) 
						AND ((FN800.D270_2 = @id))
				)
			WHERE [id_check]=@id_check AND [code_NO]=''86'+@lnk+'''	
		END
		
		
		/* ********************************************************************************************************************
			Решение о принятии обеспечительных мер (по невзысканным платежам). Запрет на отчуждение имущества
		*********************************************************************************************************************** */
			
		UPDATE [arrears].[dbo].[arr_reestr_check]
			SET [resolution_adop_sec_measure_ban_alien_date] = A.[D223],
				[resolution_adop_sec_measure_ban_alien_num] = A.[N590]
			FROM (
				-- Контрольная деятельность\ВНП, другие проверки, сведения внешних корреспондентов\Учет сведений (QBE по решениям) / Проверка + связанная Проверка
				SELECT TOP 1 Decision.D223, Decision.N590 FROM Taxes'+@lnk+'.dbo.FN800 
					join (Taxes'+@lnk+'.dbo.FN1189 join Taxes'+@lnk+'.dbo.FN1190 on FN1189.D895 =  FN1190.D895 
						join Taxes'+@lnk+'.dbo.FN74 sa on FN1190.N269 = sa.N269 and  sa.D686 = (select distinct nt_username from master..sysprocesses where spid=@@SPID)) on FN1189.D895 = FN800.D895
					join Taxes'+@lnk+'.dbo.FN212 on FN800.N1_1=FN212.N1
					left join Taxes'+@lnk+'.dbo.FN1532 Decision on Decision.D270=FN800.D270_2
					left join Taxes'+@lnk+'.dbo.FN1016 DecisionDoc on Decision.D81=DecisionDoc.D81
					left join (Taxes'+@lnk+'.dbo.FN1532 join Taxes'+@lnk+'.dbo.FN1016 on FN1532.D81=FN1016.D81  left join Taxes'+@lnk+'.dbo.FN1017 on FN1017.D172=FN1016.D172) on FN1532.D270=FN800.D270_1
					left join #RelDC on FN800.N800__1 = #RelDC.N800__1
					join Taxes'+@lnk+'.dbo.FN739 on #RelDC.N739__1 = FN739.N739__1
					left join Taxes'+@lnk+'.dbo.FN212 RelOrg on RelOrg.N1=#RelDC.N1_1
					left join (Taxes'+@lnk+'.dbo.FN1532 RelAct join Taxes'+@lnk+'.dbo.FN1016 RA  on RelAct.D81 = RA.D81  left join Taxes'+@lnk+'.dbo.FN1017 RDT on RDT.D172=RA.D172) on RelAct.D270 = #RelDC.RelD270_1
					left join (Taxes'+@lnk+'.dbo.FN1532 RelDec join Taxes'+@lnk+'.dbo.FN1016 RD on RelDec.D81 = RD.D81) on RelDec.D270 = #RelDC.RelD270_2
					left join (Taxes'+@lnk+'.dbo.FN1532 RelCert join Taxes'+@lnk+'.dbo.FN1016 RC on RelCert.D81 = RC.D81) on RelCert.D270 = #RelDC.RelD270_3
								left join Taxes'+@lnk+'.dbo.fn800op on fn800op.n800__1 = fn800.n800__1
				WHERE (IsNull(FN1016.D172,0)<>216 and IsNull(DecisionDoc.D172,0)<>316 and not exists(select 1 from Taxes'+@lnk+'.dbo.FN819 where FN819.N739__1=17 and FN819.N800__1=FN800.N800__1 and FN819.N800__1_1 is NULL) and IsNull(FN1016.D430,0) <>2727) 
					AND ((FN800.N800__1 = @id_check) AND (#RelDC.N739__1 IN (5))) 
					AND FN800.D270_2 IS NOT NULL
			) AS A
		WHERE [id_check]=@id_check AND [code_NO]=''86'+@lnk+'''	

		
		
		/* ************************************************
			Сведения о снятии с учета налогоплательщика
		*************************************************** */
		
		IF EXISTS(SELECT 1 FROM [arrears].[dbo].[arr_reestr_check] WHERE [type_NP]=1 AND [id_check]=@id_check AND [code_NO]=''86'+@lnk+''')
		BEGIN
			-- ЮЛ
			UPDATE [arrears].[dbo].[arr_reestr_check] SET 
				[info_removal_register_NP_date] = A.N322
			   ,[info_removal_register_NP_reason] = A.N349
			   ,[info_removal_register_NP_to_NO] = A.toNO
			FROM (
				SELECT 
					tax1.N322,		-- дата снятия с учета
					FN86.N349,		-- причина снятия с учета	
					(case when (isnull(b.N279,'''') = '''' or b.N279 = ''0'') 
						and not exists(select 1 from Taxes'+@lnk+'.dbo.FN1508 where N1 = a.N1 
						and N348 in(11, 28, 203)) then '''' else b.N279 end) [toNO] -- код НО
				FROM Taxes'+@lnk+'.dbo.FN212 a  
					join Taxes'+@lnk+'.dbo.FN210 on FN210.N1 = a.N1, Taxes'+@lnk+'.dbo.FN209 tax1
					left join Taxes'+@lnk+'.dbo.FN213 b on tax1.N314 = b.N314, Taxes'+@lnk+'.dbo.FN86 
				WHERE a.N350=0 and a.D428 not in (380,381) 
					and a.N1=tax1.N1 and a.N312=1 and tax1.N348=FN86.N348 
					and (tax1.N322 is not NULL) and substring(a.D3,5,2) in (''01'',''50'')
					and a.N1 = @id_NP
			) A
			WHERE [id_check]=@id_check AND [code_NO]=''86'+@lnk+'''	
		END
		ELSE
		BEGIN
			-- ФЛ
			UPDATE [arrears].[dbo].[arr_reestr_check] SET 
				[info_removal_register_NP_date] = A.N322
			   ,[info_removal_register_NP_reason] = A.N349		  
			FROM (
				SELECT 
					tax1.N322,		-- дата снятия с учета
					FN86.N349		-- причина снятия с учета	
				FROM Taxes'+@lnk+'.dbo.FN212 a, Taxes'+@lnk+'.dbo.FN209 tax1, Taxes'+@lnk+'.dbo.FN86 
				WHERE a.N1=tax1.N1 and a.D428 not in (380,381) 
					and a.N312=2 and tax1.N348=FN86.N348 and (tax1.N322 is not NULL)
					and a.N1 = @id_NP			
			) A
			WHERE [id_check]=@id_check AND [code_NO]=''86'+@lnk+'''		
		END
		

		
		/* **************************************
			Текущая процедура банкротства
			Дата введения 
		***************************************** */
		
		UPDATE [arrears].[dbo].[arr_reestr_check] SET 
			[current_proc_bankruptcy] = A.d3762
		   ,[intro_date] = A.d40_2
		FROM (
			SELECT
				fn1373.d3762, -- текущая процедура банкротства
				bkr.d40_2	  -- дата введения
			FROM Taxes'+@lnk+'.dbo.FN212 a 
			left join (select fn212_02.n1, max(fn212_02.d3525) as d3525
				from Taxes'+@lnk+'.dbo.fn212_02 (nolock)
				group by fn212_02.n1
				) MaxBkr on MaxBkr.n1 = a.n1
			left join Taxes'+@lnk+'.dbo.fn212_02 bkr (nolock) on bkr.d3525 = MaxBkr.d3525
			left join Taxes'+@lnk+'.dbo.fn1373 (nolock) on fn1373.d09 = bkr.d09
			WHERE (D1811 = 1 and a.D428 not in (380, 381)) 
				AND ((a.N1 = @id_NP) -- УН лица
				AND (fn1373.d3762 IS NOT NULL) AND (bkr.d40_2 IS NOT NULL)) 
			GROUP BY fn1373.d3762, bkr.d40_2
		) A
		WHERE [id_check]=@id_check AND [code_NO]=''86'+@lnk+'''	
		
		
		/* ************************************************************
			Материалы переданы в следственные органы по ст. 32 НК РФ
				Дата
				Номер
		*************************************************************** */
		
		UPDATE [arrears].[dbo].[arr_reestr_check] SET 
			[material_SLEDSTV_ORG_date] = A.N7010__2 -- дата документа
		   ,[material_SLEDSTV_ORG_num]  = A.N7010__3 -- номер документа
		FROM (
			SELECT b.N7010__2,b.N7010__3 FROM Taxes'+@lnk+'.dbo.FN7020 a
				join (Taxes'+@lnk+'.dbo.FN1189 join Taxes'+@lnk+'.dbo.FN1190 on FN1189.D895 =  FN1190.D895
					join Taxes'+@lnk+'.dbo.FN74 sa on FN1190.N269 = sa.N269 and  sa.D686 = (select distinct nt_username from master..sysprocesses where spid=@@SPID)) on FN1189.D895 = a.D895
				left join Taxes'+@lnk+'.dbo.FN800 on FN800.N800__1=a.N800__1
				left join Taxes'+@lnk+'.dbo.FN1532 on FN1532.D270=FN800.D270_1
				left join Taxes'+@lnk+'.dbo.FN1532 Decision on Decision.D270=FN800.D270_2
				left join (Taxes'+@lnk+'.dbo.FN1534 join Taxes'+@lnk+'.dbo.FN1532 IncDoc on IncDoc.D270=FN1534.D270_2) on FN1534.D270=a.D270
				left join Taxes'+@lnk+'.dbo.FN1532 DeclFace on DeclFace.D270=a.D270
				left join Taxes'+@lnk+'.dbo.FN212 on FN212.N1=case when FN800.N1_1 is Null then IsNull(IsNull(FN1534.N1,DeclFace.N1),a.N1_3) else FN800.N1_1 end
				left join Taxes'+@lnk+'.dbo.FN213 on FN213.N314=FN212.N314
				left join (Taxes'+@lnk+'.dbo.FN212 OVD left join Taxes'+@lnk+'.dbo.FN213 OVDAddr on OVDAddr.N314=OVD.N314) on OVD.N1=a.N1_1
				left join Taxes'+@lnk+'.dbo.FN71 GNI on GNI.N1=a.N1_2
				join (Taxes'+@lnk+'.dbo.FN7010 b join Taxes'+@lnk+'.dbo.FN7000 on FN7000.N7000__1=b.N7000__1) on b.N7020__1=a.N7020__1
				left join Taxes'+@lnk+'.dbo.FN7030 State2 on State2.N7010__1=b.N7010__1 and State2.N7001__1=2
				left join Taxes'+@lnk+'.dbo.FN212 SPer on SPer.N1=b.N1_1		
				left join Taxes'+@lnk+'.dbo.FN74 d on d.N269=b.N269
				left join (Taxes'+@lnk+'.dbo.FN74 e join Taxes'+@lnk+'.dbo.FN73 on FN73.N277=e.N277
							  join Taxes'+@lnk+'.dbo.FN1050 on FN1050.D15=e.D15) on e.N269=b.N269_1
				left join Taxes'+@lnk+'.dbo.FN74 f on f.N269=b.N269_11
				left join Taxes'+@lnk+'.dbo.FN74 g on g.N269=b.N269_7
				left join Taxes'+@lnk+'.dbo.FN74 h on h.N269=b.N269_12
				left join Taxes'+@lnk+'.dbo.FN74 i on i.N269=b.N269_15
							left join Taxes'+@lnk+'.dbo.fn7010 z on (b.n7020__1 = z.n7020__1) and (b.n7000__1 = 6) and (z.n7000__1 = 16) 
							left join Taxes'+@lnk+'.dbo.fn4411 q on q.N4411__1 = a.N4411__1
				left join Taxes'+@lnk+'.dbo.FN212 FN212KA on FN212KA.N1=a.N1_4
							left join Taxes'+@lnk+'.dbo.FN7244 p on p.N7244__1=b.N7244__1
							left join Taxes'+@lnk+'.dbo.FN7246 tt on tt.N7246__1 = b.N7246__1
							left join Taxes'+@lnk+'.dbo.FN7245 ttt on ttt.N7245__1 = b.N7245__1
			WHERE ( b.N7010__34 is Null) AND ((b.N7000__1 = 1) AND (a.N800__1 IN (@id_check))) GROUP BY b.N7010__2, b.N7010__3 
		) A
		WHERE [id_check]=@id_check AND [code_NO]=''86'+@lnk+'''
		
		
		/* ************************************************************
			Материалы переданы в следственные органы по ст. 32 НК РФ
				Статья
		*************************************************************** */
			
		DECLARE @id_article_SLEDSTV_ORG int
		DECLARE @material_SLEDSTV_ORG_article varchar(250)
		SET @material_SLEDSTV_ORG_article = ''''
		
		DECLARE material_SLEDSTV_ORG_article_cursor CURSOR FOR		
			-- Ветка: Материалы, направляемые в правоохранительные органы для решения вопроса о возбуждении уголовных дел\Дела по материалам, направляемым в правоохранительный орган для возбуждения УД\Дела по материалам, направляемым в правоохранительный орган для возбуждения УД\ QBE Дело + Законодательный акт УК
			SELECT 
				FN724.N724__5
			 --,FN724.N724__2 
			FROM Taxes'+@lnk+'.dbo.FN7020 a
				join (Taxes'+@lnk+'.dbo.FN1189 join Taxes'+@lnk+'.dbo.FN1190 on FN1189.D895 =  FN1190.D895
					join Taxes'+@lnk+'.dbo.FN74 sa on FN1190.N269 = sa.N269 and  sa.D686 = (select distinct nt_username from master..sysprocesses where spid=@@SPID)) on FN1189.D895 = a.D895
				left join Taxes'+@lnk+'.dbo.FN800 on FN800.N800__1=a.N800__1
				left join Taxes'+@lnk+'.dbo.FN1532 on FN1532.D270=FN800.D270_1
				left join Taxes'+@lnk+'.dbo.FN1532 Decision on Decision.D270=FN800.D270_2
				left join (Taxes'+@lnk+'.dbo.FN1534 join Taxes'+@lnk+'.dbo.FN1532 IncDoc on IncDoc.D270=FN1534.D270_2) on FN1534.D270=a.D270
				left join Taxes'+@lnk+'.dbo.FN1532 DeclFace on DeclFace.D270=a.D270
				left join Taxes'+@lnk+'.dbo.FN212 on FN212.N1=case when FN800.N1_1 is Null then IsNull(IsNull(FN1534.N1,DeclFace.N1),a.N1_3) else FN800.N1_1 end
				left join Taxes'+@lnk+'.dbo.FN213 on FN213.N314=FN212.N314
				left join (Taxes'+@lnk+'.dbo.FN212 OVD left join Taxes'+@lnk+'.dbo.FN213 OVDAddr on OVDAddr.N314=OVD.N314) on OVD.N1=a.N1_1
				left join Taxes'+@lnk+'.dbo.FN71 GNI on GNI.N1=a.N1_2
				join (Taxes'+@lnk+'.dbo.FN7025 join Taxes'+@lnk+'.dbo.FN724 on FN724.N724__1=FN7025.N724__1) on FN7025.N7020__1=a.N7020__1
				left join Taxes'+@lnk+'.dbo.FN74 c on c.N269=a.N2691
				left join Taxes'+@lnk+'.dbo.FN74 d on d.N269=a.N269_2
				left join Taxes'+@lnk+'.dbo.FN74 e on e.N269=a.N269
							left join Taxes'+@lnk+'.dbo.fn4411 q on q.N4411__1 = a.N4411__1
				left join Taxes'+@lnk+'.dbo.FN212 FN212KA on FN212KA.N1=a.N1_4
			WHERE (a.N7020__4 is NULL and (FN1189.D40<=GetDate() OR FN1189.D40 is NULL ) AND (FN1189.D41>=GetDate() OR FN1189.D41 is NULL) and (FN1190.D40<=GetDate() OR FN1190.D40 is NULL ) AND (FN1190.D41>=GetDate() OR FN1190.D41 is NULL)) 
				AND ((a.N800__1 IN (@id_check))) GROUP BY FN724.N724__5, FN724.N724__2  
		
		OPEN material_SLEDSTV_ORG_article_cursor
		
		FETCH NEXT FROM material_SLEDSTV_ORG_article_cursor INTO @id_article_SLEDSTV_ORG
		
		DECLARE @tempId INT
		
		WHILE @@FETCH_STATUS = 0
		BEGIN		
			
			SET @tempId = null
			SET @tempId = CASE
				WHEN @id_article_SLEDSTV_ORG = 1  THEN 5 -- статья 198    
				WHEN @id_article_SLEDSTV_ORG = 2  THEN 6 -- статья 199    
				WHEN @id_article_SLEDSTV_ORG = 11 THEN 7 -- статья 199.1   
				WHEN @id_article_SLEDSTV_ORG = 12 THEN 8 -- статья 199.2
			END
							
			IF (@tempId IS NOT NULL)
			BEGIN			
				IF (@material_SLEDSTV_ORG_article <> '''')
					SET @material_SLEDSTV_ORG_article = @material_SLEDSTV_ORG_article + ''|''
				SET @material_SLEDSTV_ORG_article = @material_SLEDSTV_ORG_article 
					+ convert(varchar, @tempId)			
			END		
										
			FETCH NEXT FROM material_SLEDSTV_ORG_article_cursor INTO @id_article_SLEDSTV_ORG
		END 
		
		CLOSE material_SLEDSTV_ORG_article_cursor
		DEALLOCATE material_SLEDSTV_ORG_article_cursor
		
		IF (@material_SLEDSTV_ORG_article <> '''')
		BEGIN
			UPDATE [arrears].[dbo].[arr_reestr_check] SET 
				[material_SLEDSTV_ORG_article] = @material_SLEDSTV_ORG_article
			WHERE [id_check]=@id_check AND [code_NO]=''86'+@lnk+'''
		END
		
		
		/* ************************************************************************************************************
			Результат рассмотрения следственными органами материалов налоговых проверок. Возбуждено УД. Статья УК РФ
		*************************************************************************************************************** */
		
		DECLARE @id_article_SLEDSTV_ORG_filed int
		DECLARE @result_see_SLEDSTV_ORG_filed_article varchar(250)
		SET @result_see_SLEDSTV_ORG_filed_article = ''''
		
		DECLARE material_SLEDSTV_ORG_article_cursor CURSOR FOR		
			-- Ветка: Материалы, направляемые в правоохранительные органы для решения вопроса о возбуждении уголовных дел\Дела по материалам, направляемым в правоохранительный орган для возбуждения УД\Дела по материалам, направляемым в правоохранительный орган для возбуждения УД\ QBE Дело + Постановления (Уведомления) о возбуждении УД + ЗА УК РФ
			SELECT f25.n724__1 FROM Taxes'+@lnk+'.dbo.FN7020 a
				join (Taxes'+@lnk+'.dbo.FN1189 join Taxes'+@lnk+'.dbo.FN1190 on FN1189.D895 =  FN1190.D895
					join Taxes'+@lnk+'.dbo.FN74 sa on FN1190.N269 = sa.N269 and  sa.D686 = (select distinct nt_username from master..sysprocesses where spid=@@SPID)) on FN1189.D895 = a.D895
				left join Taxes'+@lnk+'.dbo.FN800 on FN800.N800__1=a.N800__1
				left join Taxes'+@lnk+'.dbo.FN1532 on FN1532.D270=FN800.D270_1
				left join Taxes'+@lnk+'.dbo.FN1532 Decision on Decision.D270=FN800.D270_2
				left join (Taxes'+@lnk+'.dbo.FN1534 join Taxes'+@lnk+'.dbo.FN1532 IncDoc on IncDoc.D270=FN1534.D270_2) on FN1534.D270=a.D270
				left join Taxes'+@lnk+'.dbo.FN1532 DeclFace on DeclFace.D270=a.D270
				left join Taxes'+@lnk+'.dbo.FN212 on FN212.N1=case when FN800.N1_1 is Null then IsNull(IsNull(FN1534.N1,DeclFace.N1),a.N1_3) else FN800.N1_1 end
				left join Taxes'+@lnk+'.dbo.FN213 on FN213.N314=FN212.N314
				left join (Taxes'+@lnk+'.dbo.FN212 OVD left join Taxes'+@lnk+'.dbo.FN213 OVDAddr on OVDAddr.N314=OVD.N314) on OVD.N1=a.N1_1
				left join Taxes'+@lnk+'.dbo.FN71 GNI on GNI.N1=a.N1_2
				join Taxes'+@lnk+'.dbo.FN7010 b on b.N7020__1=a.N7020__1
				join Taxes'+@lnk+'.dbo.FN7000 on FN7000.N7000__1=b.N7000__1
				left join Taxes'+@lnk+'.dbo.FN7010 Req on Req.N7010__1=b.N7010__1_2
				left join Taxes'+@lnk+'.dbo.FN7030 State2 on State2.N7010__1=b.N7010__1 and State2.N7001__1=2
				left join Taxes'+@lnk+'.dbo.FN74 c on c.N269=a.N2691
				left join Taxes'+@lnk+'.dbo.FN74 d on d.N269=a.N269_2
				left join (Taxes'+@lnk+'.dbo.FN74 e  join Taxes'+@lnk+'.dbo.FN73 on FN73.N277=e.N277
							   join Taxes'+@lnk+'.dbo.FN1050 on FN1050.D15=e.D15) on e.N269=b.N269_1
				left join Taxes'+@lnk+'.dbo.FN74 i on i.N269=b.N269_15
							left join Taxes'+@lnk+'.dbo.fn7025 f25 on b.n7010__1 = f25.n7010__1
							left join Taxes'+@lnk+'.dbo.fn724 f24 on f25.N724__1 = f24.N724__1
							left join Taxes'+@lnk+'.dbo.fn4411 q on q.N4411__1 = a.N4411__1
				left join Taxes'+@lnk+'.dbo.FN212 FN212KA on FN212KA.N1=a.N1_4
							left join Taxes'+@lnk+'.dbo.FN7246 tt on tt.N7246__1 = b.N7246__1
			 WHERE ( b.N7000__1 in (5,22) and (FN1189.D40<=GetDate() OR FN1189.D40 is NULL ) AND (FN1189.D41>=GetDate() OR FN1189.D41 is NULL) and (FN1190.D40<=GetDate() OR FN1190.D40 is NULL ) AND (FN1190.D41>=GetDate() OR FN1190.D41 is NULL)) 
				AND ((a.N800__1 IN (@id_check))) GROUP BY f25.n724__1    
		
		OPEN material_SLEDSTV_ORG_article_cursor
		
		FETCH NEXT FROM material_SLEDSTV_ORG_article_cursor INTO @id_article_SLEDSTV_ORG_filed
			
		WHILE @@FETCH_STATUS = 0
		BEGIN		
			
			SET @tempId = null
			SET @tempId = CASE
				WHEN @id_article_SLEDSTV_ORG_filed = 1  THEN 5 -- статья 198    
				WHEN @id_article_SLEDSTV_ORG_filed = 2  THEN 6 -- статья 199    
				WHEN @id_article_SLEDSTV_ORG_filed = 11 THEN 7 -- статья 199.1   
				WHEN @id_article_SLEDSTV_ORG_filed = 12 THEN 8 -- статья 199.2
			END
							
			IF (@tempId IS NOT NULL)
			BEGIN			
				IF (@result_see_SLEDSTV_ORG_filed_article <> '''')
					SET @result_see_SLEDSTV_ORG_filed_article = @result_see_SLEDSTV_ORG_filed_article + ''|''
				SET @result_see_SLEDSTV_ORG_filed_article = @result_see_SLEDSTV_ORG_filed_article 
					+ convert(varchar, @tempId)			
			END		
										
			FETCH NEXT FROM material_SLEDSTV_ORG_article_cursor INTO @id_article_SLEDSTV_ORG_filed
		END 
		
		CLOSE material_SLEDSTV_ORG_article_cursor
		DEALLOCATE material_SLEDSTV_ORG_article_cursor
		
		IF (@result_see_SLEDSTV_ORG_filed_article <> '''')
		BEGIN
			UPDATE [arrears].[dbo].[arr_reestr_check] SET 
				[result_see_SLEDSTV_ORG_filed_article] = @result_see_SLEDSTV_ORG_filed_article
			WHERE [id_check]=@id_check AND [code_NO]=''86'+@lnk+'''
		END
		
		
		/* ************************************************************************************************************
			Результат рассмотрения следственными органами материалов налоговых проверок. Возбуждено УД. Статья УК РФ
		*************************************************************************************************************** */
		
		UPDATE [arrears].[dbo].[arr_reestr_check] SET 
			[result_see_SLEDSTV_ORG_filed_date] = A.N7010__2 -- дата документа
		   ,[result_see_SLEDSTV_ORG_filed_num]  = A.N7010__3 -- номер документа
		FROM ( 	
			-- Ветка: Материалы, направляемые в правоохранительные органы для решения вопроса о возбуждении уголовных дел\Документы по материалам, направляемым в правоохранительный орган для решения вопроса о возбуждении УД\Документы по материалам, направляемым в правоохранительный орган для решения вопроса о возбуждении УД\QBE – дело+Сведения о документах
			SELECT 
				b.N7010__2
			   ,b.N7010__3 FROM Taxes'+@lnk+'.dbo.FN7020 a
				join (Taxes'+@lnk+'.dbo.FN1189 join Taxes'+@lnk+'.dbo.FN1190 on FN1189.D895 =  FN1190.D895
					join Taxes'+@lnk+'.dbo.FN74 sa on FN1190.N269 = sa.N269 and  sa.D686 = (select distinct nt_username from master..sysprocesses where spid=@@SPID)) on FN1189.D895 = a.D895
				left join Taxes'+@lnk+'.dbo.FN800 on FN800.N800__1=a.N800__1
				left join Taxes'+@lnk+'.dbo.FN1532 on FN1532.D270=FN800.D270_1
				left join Taxes'+@lnk+'.dbo.FN1532 Decision on Decision.D270=FN800.D270_2
				left join (Taxes'+@lnk+'.dbo.FN1534 join Taxes'+@lnk+'.dbo.FN1532 IncDoc on IncDoc.D270=FN1534.D270_2) on FN1534.D270=a.D270
				left join Taxes'+@lnk+'.dbo.FN1532 DeclFace on DeclFace.D270=a.D270
				left join Taxes'+@lnk+'.dbo.FN212 on FN212.N1=case when FN800.N1_1 is Null then IsNull(IsNull(FN1534.N1,DeclFace.N1),a.N1_3) else FN800.N1_1 end
				left join Taxes'+@lnk+'.dbo.FN213 on FN213.N314=FN212.N314
				left join (Taxes'+@lnk+'.dbo.FN212 OVD left join Taxes'+@lnk+'.dbo.FN213 OVDAddr on OVDAddr.N314=OVD.N314) on OVD.N1=a.N1_1
				left join Taxes'+@lnk+'.dbo.FN71 GNI on GNI.N1=a.N1_2
				join (Taxes'+@lnk+'.dbo.FN7010 b join Taxes'+@lnk+'.dbo.FN7000 on FN7000.N7000__1=b.N7000__1) on b.N7020__1=a.N7020__1
				left join Taxes'+@lnk+'.dbo.FN7030 State2 on State2.N7010__1=b.N7010__1 and State2.N7001__1=2
				left join Taxes'+@lnk+'.dbo.FN212 SPer on SPer.N1=b.N1_1		
				left join Taxes'+@lnk+'.dbo.FN74 d on d.N269=b.N269
				left join (Taxes'+@lnk+'.dbo.FN74 e 
					join Taxes'+@lnk+'.dbo.FN73 on FN73.N277=e.N277
					join Taxes'+@lnk+'.dbo.FN1050 on FN1050.D15=e.D15) on e.N269=b.N269_1
				left join Taxes'+@lnk+'.dbo.FN74 f on f.N269=b.N269_11
				left join Taxes'+@lnk+'.dbo.FN74 g on g.N269=b.N269_7
				left join Taxes'+@lnk+'.dbo.FN74 h on h.N269=b.N269_12
				left join Taxes'+@lnk+'.dbo.FN74 i on i.N269=b.N269_15
							left join Taxes'+@lnk+'.dbo.fn7010 z on (b.n7020__1 = z.n7020__1) and (b.n7000__1 = 6) and (z.n7000__1 = 16) 
							left join Taxes'+@lnk+'.dbo.fn4411 q on q.N4411__1 = a.N4411__1
				left join Taxes'+@lnk+'.dbo.FN212 FN212KA on FN212KA.N1=a.N1_4
							left join Taxes'+@lnk+'.dbo.FN7244 p on p.N7244__1=b.N7244__1
							left join Taxes'+@lnk+'.dbo.FN7246 tt on tt.N7246__1 = b.N7246__1
							left join Taxes'+@lnk+'.dbo.FN7245 ttt on ttt.N7245__1 = b.N7245__1

			WHERE ( b.N7010__34 is Null) AND ((b.N7000__1 = 22) 
				AND (a.N800__1 IN (@id_check))) GROUP BY b.N7010__2, b.N7010__3
		) A
		WHERE [id_check]=@id_check AND [code_NO]=''86'+@lnk+'''
		
		
		/* *************************************************************************************************************************
			Результат рассмотрения следственными органами материалов налоговых проверок Отказано в возбуждении УД. Причина отказа
		**************************************************************************************************************************** */
		
		UPDATE [arrears].[dbo].[arr_reestr_check] SET 
			[result_see_SLEDSTV_ORG_refused_article] = A.N7241__2 -- Результат рассмотрения следственными органами материалов налоговых проверок  (отказано в возбуждении УД) (причина отказа)	
		FROM ( 	
			-- Ветка: Материалы, направляемые в правоохранительные органы для решения вопроса о возбуждении уголовных дел\Документы по материалам, направляемым в правоохранительный орган для решения вопроса о возбуждении УД\Документы по материалам, направляемым в правоохранительный орган для решения вопроса о возбуждении УД\QBE –Дело+ Постановление (Уведомление) об отказе в возбуждении УД + Виды причин отказа
			 SELECT TOP 1
				f41.N7241__2 FROM Taxes'+@lnk+'.dbo.FN7020 a
				join (Taxes'+@lnk+'.dbo.FN1189 join Taxes'+@lnk+'.dbo.FN1190 on FN1189.D895 =  FN1190.D895
											  join Taxes'+@lnk+'.dbo.FN74 sa on FN1190.N269 = sa.N269 and  sa.D686 = (select distinct nt_username from master..sysprocesses where spid=@@SPID)) on FN1189.D895 = a.D895
				left join Taxes'+@lnk+'.dbo.FN800 on FN800.N800__1=a.N800__1
				left join Taxes'+@lnk+'.dbo.FN1532 on FN1532.D270=FN800.D270_1
				left join Taxes'+@lnk+'.dbo.FN1532 Decision on Decision.D270=FN800.D270_2
				left join (Taxes'+@lnk+'.dbo.FN1534 join Taxes'+@lnk+'.dbo.FN1532 IncDoc on IncDoc.D270=FN1534.D270_2) on FN1534.D270=a.D270
				left join Taxes'+@lnk+'.dbo.FN1532 DeclFace on DeclFace.D270=a.D270
				left join Taxes'+@lnk+'.dbo.FN212 on FN212.N1=case when FN800.N1_1 is Null then IsNull(IsNull(FN1534.N1,DeclFace.N1),a.N1_3) else FN800.N1_1 end
				left join Taxes'+@lnk+'.dbo.FN213 on FN213.N314=FN212.N314
				left join (Taxes'+@lnk+'.dbo.FN212 OVD left join Taxes'+@lnk+'.dbo.FN213 OVDAddr on OVDAddr.N314=OVD.N314) on OVD.N1=a.N1_1
				left join Taxes'+@lnk+'.dbo.FN71 GNI on GNI.N1=a.N1_2
				join (Taxes'+@lnk+'.dbo.FN7010 b join Taxes'+@lnk+'.dbo.FN7000 on FN7000.N7000__1=b.N7000__1) on b.N7020__1=a.N7020__1
				left join Taxes'+@lnk+'.dbo.FN7010V Req on Req.N7010__1=b.N7010__1_2
				left join Taxes'+@lnk+'.dbo.FN7030 State2 on State2.N7010__1=b.N7010__1 and State2.N7001__1=2
				left join Taxes'+@lnk+'.dbo.FN212 SPer on SPer.N1=b.N1_1
							left join Taxes'+@lnk+'.dbo.FN74 c on c.N269=a.N2691		
				left join Taxes'+@lnk+'.dbo.FN74 d on d.N269=b.N269
				left join (Taxes'+@lnk+'.dbo.FN74 e join Taxes'+@lnk+'.dbo.FN73 on FN73.N277=e.N277
							  join Taxes'+@lnk+'.dbo.FN1050 on FN1050.D15=e.D15) on e.N269=b.N269_1
				left join Taxes'+@lnk+'.dbo.FN74 f on f.N269=b.N269_11
				left join Taxes'+@lnk+'.dbo.FN74 g on g.N269=b.N269_7
				left join Taxes'+@lnk+'.dbo.FN74 h on h.N269=b.N269_12
				left join Taxes'+@lnk+'.dbo.FN74 i on i.N269=b.N269_15
							left join Taxes'+@lnk+'.dbo.fn7010 z on (b.n7020__1 = z.n7020__1) and (b.n7000__1 = 6) and (z.n7000__1 = 16) 
							left join Taxes'+@lnk+'.dbo.fn7026 f26 on b.n7010__1 = f26.n7010__1 
							left join Taxes'+@lnk+'.dbo.fn7241 f41 on f26.N7241__1 = f41.N7241__1
							left join Taxes'+@lnk+'.dbo.fn4411 q on q.N4411__1 = a.N4411__1
				left join Taxes'+@lnk+'.dbo.FN212 FN212KA on FN212KA.N1=a.N1_4
			WHERE ( b.N7000__1 in (6,18) and (FN1189.D40<=GetDate() OR FN1189.D40 is NULL ) AND (FN1189.D41>=GetDate() OR FN1189.D41 is NULL) and (FN1190.D40<=GetDate() OR FN1190.D40 is NULL ) AND (FN1190.D41>=GetDate() OR FN1190.D41 is NULL)) 
				AND ((a.N800__1 IN (@id_check))) 
			GROUP BY f41.N7241__2
		) A
		WHERE [id_check]=@id_check AND [code_NO]=''86'+@lnk+'''
			
		
		
		FETCH NEXT FROM reestr_cursor
		INTO @id_check, @id_NP
	END

	CLOSE reestr_cursor
	DEALLOCATE reestr_cursor


	DROP TABLE #RelDC')
	
END

GO
/****** Object:  StoredProcedure [dbo].[PR_UPDATE_REESTR_01]    Script Date: 26.02.2016 17:49:17 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		<Author,,Name>
-- Create date: <Create Date,,>
-- Description:	
/** Обновляемые реквизиты:

	requiment_number		Номер требования об уплате
	requiment_date			Дата требования об уплате
	requiment_term			Срок уплаты по требованию
	requiment_summ			Сумма включенная в требование (тыс. руб.)
	requiment_summ_rest		Остаток непогашенной суммы по требованию (тыс. руб.)
**/
-- =============================================
CREATE PROCEDURE [dbo].[PR_UPDATE_REESTR_01]
	@lnk varchar(2)
AS
BEGIN
	EXEC ('
		DECLARE @id_check INT
		DECLARE @id_NP INT
		DECLARE @id_reestr INT

		DECLARE reestr_cursor CURSOR FOR
			SELECT id_check, id_NP, id FROM arrears.dbo.arr_reestr_check WHERE code_NO=''86'+@lnk+'''
			
		OPEN reestr_cursor

		FETCH NEXT FROM reestr_cursor
			INTO @id_check, @id_NP, @id_reestr
			
		WHILE @@FETCH_STATUS = 0
		BEGIN
									
			-- Оперативно-бухгалтерский учет\Недоимка\Налоговые проверки
			declare @system_number int
			SELECT @system_number = t.D270 FROM (
				select a.d270, a.n1, 
				(CASE COUNT(distinct isnull(a.D2022, 0)) WHEN 1 THEN (CASE sum(distinct a.d2022) WHEN 0 THEN 12 ELSE 10 END) ELSE 14 END) as status, 
				a.n120, sum(abs(a.d83)) as d83, a.d81
			  from Taxes'+@lnk+'.dbo.fn1500 a (nolock)
			  join Taxes'+@lnk+'.dbo.vDocsProverk v (nolock) on a.d81 = v.d81
			  where a.d270 is not null and a.d812 = 1 
				and  (not exists(select 1 from Taxes'+@lnk+'.dbo.fn10023 n1 (nolock) where n1.d6_1 = a.d6) or a.d512 > 0)
				and not (a.d12 in (1, 2, 6) and a.d244 = 195)
			  group by a.d270, a.n1, a.n120, a.d81
			  ) t
			  join Taxes'+@lnk+'.dbo.fn212 (nolock) on fn212.n1 = t.n1
			  join Taxes'+@lnk+'.dbo.fn52 (nolock) on fn52.n120 = t.n120
			  join Taxes'+@lnk+'.dbo.fn1532 (nolock) on fn1532.d270 = t.d270
			  left join Taxes'+@lnk+'.dbo.fn800 (nolock) on fn800.d270_2 = fn1532.d270
			  join Taxes'+@lnk+'.dbo.fn1016 (nolock) on t.d81 = fn1016.d81
			  left join Taxes'+@lnk+'.dbo.fn1534 (nolock) on fn1534.d270 = T.D270
			  left join Taxes'+@lnk+'.dbo.fn208 (nolock) on fn212.n1 = fn208.n1
			  left join (select distinct a.d270
				from Taxes'+@lnk+'.dbo.fn1532 a (nolock)
				join Taxes'+@lnk+'.dbo.fn1016 vid (nolock) on vid.d81 = a.d81
				join Taxes'+@lnk+'.dbo.fn800 PrGol (nolock) on PrGol.d270_2 = a.d270
				join Taxes'+@lnk+'.dbo.fn800op (nolock) on fn800op.n800__1 = PrGol.n800__1
				where vid.d172 in (145, 146)
				  and fn800op.prizn_op = 1--Признак проверки ПО
				  and exists(select 1 from Taxes'+@lnk+'.dbo.fn819 (nolock) where fn819.n800__1 = PrGol.n800__1 and fn819.n739__1 = 7)
			  ) ReshVnpOp on ReshVnpOp.d270 = fn1532.d270--решения по ВНП для общего требования
			  left join (select distinct fn800.d270_2 
				from Taxes'+@lnk+'.dbo.fn819 (nolock) 
				join Taxes'+@lnk+'.dbo.fn800 (nolock) on fn800.n800__1 = fn819.n800__1 
				join Taxes'+@lnk+'.dbo.fn800 PrGol (nolock) on PrGol.N800__1 = fn819.n800__1_1
				join Taxes'+@lnk+'.dbo.fn800op (nolock) on fn800op.n800__1 = PrGol.n800__1 and fn800op.prizn_op = 1
				where fn819.n739__1 = 12-- and fn819.n800__1_1 = <<5>>
				  and exists(select 1 from Taxes'+@lnk+'.dbo.fn819 (nolock) where fn819.n800__1 = PrGol.n800__1 and fn819.n739__1 = 7)
			  ) ReshVnpOPFil on ReshVnpOPFil.d270_2 = fn1532.d270--решения по ВНП по филиалам для общего требования
			  left join (select distinct fn1534.d270
				from Taxes'+@lnk+'.dbo.fn1534 (nolock)
				join Taxes'+@lnk+'.dbo.CamResh (nolock) on CamResh.d270 = fn1534.d270
				where fn1534.d430 in (15, 372)
				  and CamResh.PrizProvOp = 1
			  ) ReshKnpOp on ReshKnpOp.d270 = fn1534.d270--решения по КНП для общего требования
			  left join(
				select lsb.n1, lsb.n120, lsb.d270, count(lsb.d300) as ''col'',
				count(case when lsb.D83=lsb.D512 then lsb.d300 end) as ''col_12'',
				count(case when lsb.D512=0 then lsb.d300 end) as ''col_10''
				 from Taxes'+@lnk+'.dbo.fn1500 lsb
				 JOIN Taxes'+@lnk+'.dbo.fn1020   (nolock) ON fn1020.d11 = lsb.d11 
			   where lsb.D812=1 and fn1020.d09_1 = 0
			   group by lsb.n1, lsb.n120, lsb.d270
			   )b  on b.N1=t.n1 and b.N120=t.n120  and b.d270=t.d270 
			  left join (select Resh.d270, max(fn1532.d1967) as d1967
				from Taxes'+@lnk+'.dbo.fn1532 Resh (nolock)
				join Taxes'+@lnk+'.dbo.fn800 osnResh (nolock) on osnResh.d270_2 = Resh.d270
				join Taxes'+@lnk+'.dbo.FN819 (nolock) on FN819.N800__1_1 = osnResh.N800__1 and FN819.N739__1 = 16
				join Taxes'+@lnk+'.dbo.fn800 apell (nolock) on apell.N800__1 = FN819.N800__1
				join Taxes'+@lnk+'.dbo.fn1532 (nolock) on fn1532.d270 = apell.d270_2
				group by Resh.d270
			  ) ApellVNO on ApellVNO.d270 = t.d270
			WHERE (ReshKnpOp.d270 is null and ReshVnpOp.d270 is null and ReshVnpOPFil.d270_2 is null) 
				AND ((fn800.N800__1 = @id_check))  -- поиск по УН проверки

				
			-- Оперативно-бухгалтерский учет\Недоимка\Налоговые проверки
			---- подрежим "Сформированные требования"		
			INSERT INTO [arrears].[dbo].[arr_reestr_check_requiment] 
				([id_reestr],[requiment_number],[requiment_date],[requiment_summ],[requiment_term],[requiment_summ_rest])
			SELECT --TOP 1
				@id_reestr,	 -- @УН				
				t.D86,		 -- Номер требования об уплате
				t.D87,		 -- Дата требования об уплате
				t.D83/1000,  -- Сумма включенная в требование (тыс. руб.)
				t.D1967,	 -- Срок уплаты по требованию
				(				
					-- Оперативно-бухгалтерский учет\Недоимка\Структура документа дела
						SELECT SUM(abs(FN1500.D512))/1000 FROM Taxes'+@lnk+'.dbo.fn1577 (nolock)
						  join Taxes'+@lnk+'.dbo.fn1517 (nolock) on fn1577.d850=fn1517.d850 and fn1517.d3505=0
						  join Taxes'+@lnk+'.dbo.fn1596 (nolock) on fn1517.d865=fn1596.d865 
						  join Taxes'+@lnk+'.dbo.fn212 (nolock) on fn1517.n1=fn212.n1
						  join Taxes'+@lnk+'.dbo.FN1016 (nolock) on FN1016.D81=FN1517.D81
						  join Taxes'+@lnk+'.dbo.fn1080 (nolock) on fn1080.d430=fn1016.d430
						  join Taxes'+@lnk+'.dbo.fn1500 (nolock) on fn1500.d307=fn1596.d307
						  join Taxes'+@lnk+'.dbo.FN1002 (nolock) on FN1002.D6 =fn1500.D6
						  join Taxes'+@lnk+'.dbo.FN1011 (nolock) on FN1011.D73=fn1500.D73
						  join Taxes'+@lnk+'.dbo.fn1020 (nolock) on fn1500.d11=fn1020.d11
						  join Taxes'+@lnk+'.dbo.fn1143 (nolock) on fn1596.d2578=fn1143.d2578
						  left join Taxes'+@lnk+'.dbo.fn1516 (nolock) on fn1516.d307=fn1596.d307 and fn1516.d850=FN1517.D850
						  join Taxes'+@lnk+'.dbo.fn1262 e (nolock) on e.D2718=fn1002.D2718
						  left join Taxes'+@lnk+'.dbo.fn208 (nolock) on fn212.n1 = fn208.n1
						  Join Taxes'+@lnk+'.dbo.FN1182 (nolock) On FN1182.D865=fn1517.D865  
						  JOIN Taxes'+@lnk+'.dbo.FN1041 (nolock) on fn212.N1=FN1041.N1 and  fn1500.N120=FN1041.N120 and fn1500.D6=FN1041.D6
						WHERE (FN1517.D865 = t.D865)				
				)
				
				
			FROM Taxes'+@lnk+'.dbo.FN1577 (nolock) 
			  Join Taxes'+@lnk+'.dbo.FN1519 (nolock) On fn1519.D850=FN1577.D850
			  Join Taxes'+@lnk+'.dbo.FN1517 t (nolock) On t.D850=FN1577.D850
			  Join Taxes'+@lnk+'.dbo.FN1182 (nolock) On FN1182.D865=t.D865  
			  Join Taxes'+@lnk+'.dbo.FN1016 (nolock) On FN1016.D81=t.D81 
			  Join Taxes'+@lnk+'.dbo.FN1082 (nolock) On FN1082.D428=t.D428
			  left join Taxes'+@lnk+'.dbo.fn1175 (nolock) on fn1175.d832=fn1577.d832
			  left join Taxes'+@lnk+'.dbo.fn1517 rr (nolock) on rr.d851=t.D851_2
			  CROSS JOIN Taxes'+@lnk+'.dbo.fn1044 (nolock) 
			  left join Taxes'+@lnk+'.dbo.fn15179 (nolock) on fn15179.d851=t.d851
			  left join Taxes'+@lnk+'.dbo.fn1257 on fn1257.d3025=fn15179.d3025
			  left join Taxes'+@lnk+'.dbo.fn1517_19 pr (nolock) on t.d851=pr.d851 and pr.d270_2 is null
			  left join (select distinct  a.d865,max(svod.d270) as d270 
				from Taxes'+@lnk+'.dbo.fn1596 a (nolock)
				join Taxes'+@lnk+'.dbo.fn1596 struct_svod (nolock) on struct_svod.d307 = a.d307
				join Taxes'+@lnk+'.dbo.fn1517 svod on struct_svod.d865 = svod.d865
				where svod.d81 = 45 
				group by a.d865
			  ) svod on t.d865 = svod.d865
			WHERE (FN1577.D863=1 and fn1016.d430 <> 4) 
				AND (fn1577.n1=@id_NP and fn1519.n120 = 1 and fn1016.d430 in (183, 184, 1352) 
				AND t.d851 in (select d851 from Taxes'+@lnk+'.dbo.fn15177 where d270 = @system_number)) 
			GROUP BY t.d851, t.D865, t.D86, t.D87, t.D83, t.D1967
			ORDER BY t.d851 DESC									
		
		
			FETCH NEXT FROM reestr_cursor
			INTO @id_check, @id_NP, @id_reestr
		END

		CLOSE reestr_cursor
		DEALLOCATE reestr_cursor')
	
END

GO
/****** Object:  StoredProcedure [dbo].[PR_UPDATE_REESTR_02]    Script Date: 26.02.2016 17:49:17 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		<Author,,Name>
-- Create date: <Create Date,,>
-- Description:	
/** Обновляемые реквизиты:

	reduced_higher_NO_summ		Уменьшено по решению вышестоящего налогового органа, всего (тыс. руб.)
**/
-- =============================================
CREATE PROCEDURE [dbo].[PR_UPDATE_REESTR_02]
	@lnk varchar(2)
AS
BEGIN
	EXEC ('
	
		CREATE TABLE #RelDC 
			(N800__1 int, N800__1_1 int, N739__1 smallint, FN819_D301 smalldatetime,
				N1_1 int, RelD270_1 int, RelD270_2 int, RelD270_3 int, N800__900 bit)

		create index RelDC_800 on #RelDC(N800__1)

		insert into #RelDC
		select FN819.N800__1,  FN819.N800__1_1, FN819.N739__1, FN819.D301,
			FN800.N1_1, FN800.D270_1, FN800.D270_2, FN800.D270_3, FN800.N800__900
		from Taxes'+@lnk+'.dbo.FN819 
			left join Taxes'+@lnk+'.dbo.FN800 on FN800.N800__1 = FN819.N800__1_1
			
			
		DECLARE @id_check INT
		DECLARE @id_NP INT

		DECLARE reestr_cursor CURSOR FOR
			SELECT id_check, id_NP FROM arrears.dbo.arr_reestr_check WHERE code_NO=''86'+@lnk+'''
			
		OPEN reestr_cursor

		FETCH NEXT FROM reestr_cursor
			INTO @id_check, @id_NP
			
		WHILE @@FETCH_STATUS = 0
		BEGIN
									
			
			/* ****************************************************************************
				Уменьшено по решению вышестоящего налогового органа, всего (тыс. руб.)
			***************************************************************************** */	
			
			DECLARE @T TABLE (id int)
			DELETE FROM @T
			INSERT INTO @T
			SELECT FN800.D270_2 FROM Taxes'+@lnk+'.dbo.FN800 
				join (Taxes'+@lnk+'.dbo.FN1189 join Taxes'+@lnk+'.dbo.FN1190 on FN1189.D895 =  FN1190.D895
					join Taxes'+@lnk+'.dbo.FN74 sa on FN1190.N269 = sa.N269 and  sa.D686 = (select distinct nt_username from master..sysprocesses where spid=@@SPID)) on FN1189.D895 = FN800.D895
				join Taxes'+@lnk+'.dbo.FN212 on FN800.N1_1=FN212.N1 
				left join Taxes'+@lnk+'.dbo.FN1532 on FN1532.D270=FN800.D270_1
				left join Taxes'+@lnk+'.dbo.FN1016 on FN1016.D81=FN1532.D81
				left join Taxes'+@lnk+'.dbo.FN1532 Decision on Decision.D270=FN800.D270_2
				left join Taxes'+@lnk+'.dbo.FN1016 DecisionDoc on Decision.D81=DecisionDoc.D81
				left join Taxes'+@lnk+'.dbo.FN1532 Certificate on Certificate.D270=FN800.D270_3
				left join (Taxes'+@lnk+'.dbo.FN212 GNIOrg left join Taxes'+@lnk+'.dbo.FN71 on FN71.N1=GNIOrg.N1
						left join Taxes'+@lnk+'.dbo.FN213 on FN213.N314=GNIOrg.N314  
						left join Taxes'+@lnk+'.dbo.FN1263 on FN1263.D2700=FN71.D2700
										   ) on GNIOrg.N1=FN800.N1_3
				left join Taxes'+@lnk+'.dbo.FN800_VRUCH va on va.N800__1=FN800.N800__1
				left join Taxes'+@lnk+'.dbo.FN74 e on e.N269=FN800.N269
				left join Taxes'+@lnk+'.dbo.fn8151 on fn8151.n800__1=fn800.n800__1
				left join (select count(*) cnt, FN819.N800__1_1 from Taxes'+@lnk+'.dbo.FN819 
					where FN819.N739__1=1 or (FN819.N739__1=17 and exists(select * from Taxes'+@lnk+'.dbo.FN800VSTR join Taxes'+@lnk+'.dbo.FN441 on FN441.N800VSTR__1=FN800VSTR.N800VSTR__1 and FN441.N441__20=0 and FN441.N440__1=26 where FN800VSTR.N800__1=FN819.N800__1))
					group by FN819.N800__1_1) Vstr_cnt on Vstr_cnt.N800__1_1=FN800.N800__1
							left join Taxes'+@lnk+'.dbo.FN800DOP2 on FN800DOP2.n800__1= FN800.N800__1
							left join Taxes'+@lnk+'.dbo.fn800op on fn800op.n800__1 = fn800.n800__1
				left join Taxes'+@lnk+'.dbo.FN350SUD on FN350SUD.N800__1=FN800.N800__1 and IsNull(N350SUD__50,0) = 0
			WHERE ( IsNull(FN1016.D172,0)<>216 and IsNull(DecisionDoc.D172,0)<>316 and not exists(select 1 from Taxes'+@lnk+'.dbo.FN819 where FN819.N739__1=17 and FN819.N800__1=FN800.N800__1 and FN819.N800__1_1 is NULL) and IsNull(FN1016.D430,0) <>2727) 
				AND ((FN800.N800__1 IN (@id_check))) 
			GROUP BY FN800.D270_2
			
			
			DECLARE @id INT
			SET @id = NULL
			SELECT TOP 1 @id = FN800.D270_2 FROM Taxes'+@lnk+'.dbo.FN800 
				join (Taxes'+@lnk+'.dbo.FN1189 join Taxes'+@lnk+'.dbo.FN1190 on FN1189.D895 =  FN1190.D895 
					join Taxes'+@lnk+'.dbo.FN74 sa on FN1190.N269 = sa.N269 and  sa.D686 = (select distinct nt_username from master..sysprocesses where spid=@@SPID)) on FN1189.D895 = FN800.D895
				join Taxes'+@lnk+'.dbo.FN212 on FN800.N1_1=FN212.N1
				left join Taxes'+@lnk+'.dbo.FN1532 Decision on Decision.D270=FN800.D270_2
				left join Taxes'+@lnk+'.dbo.FN1016 DecisionDoc on Decision.D81=DecisionDoc.D81
				left join (Taxes'+@lnk+'.dbo.FN1532 join Taxes'+@lnk+'.dbo.FN1016 on FN1532.D81=FN1016.D81  left join Taxes'+@lnk+'.dbo.FN1017 on FN1017.D172=FN1016.D172) on FN1532.D270=FN800.D270_1
				left join #RelDC on FN800.N800__1 = #RelDC.N800__1
				join Taxes'+@lnk+'.dbo.FN739 on #RelDC.N739__1 = FN739.N739__1
				left join Taxes'+@lnk+'.dbo.FN212 RelOrg on RelOrg.N1=#RelDC.N1_1
				left join (Taxes'+@lnk+'.dbo.FN1532 RelAct join Taxes'+@lnk+'.dbo.FN1016 RA  on RelAct.D81 = RA.D81  left join Taxes'+@lnk+'.dbo.FN1017 RDT on RDT.D172=RA.D172) on RelAct.D270 = #RelDC.RelD270_1
				left join (Taxes'+@lnk+'.dbo.FN1532 RelDec join Taxes'+@lnk+'.dbo.FN1016 RD on RelDec.D81 = RD.D81) on RelDec.D270 = #RelDC.RelD270_2
				left join (Taxes'+@lnk+'.dbo.FN1532 RelCert join Taxes'+@lnk+'.dbo.FN1016 RC on RelCert.D81 = RC.D81) on RelCert.D270 = #RelDC.RelD270_3
							left join Taxes'+@lnk+'.dbo.fn800op on fn800op.n800__1 = fn800.n800__1
			WHERE (IsNull(FN1016.D172,0)<>216 and IsNull(DecisionDoc.D172,0)<>316 and not exists(select 1 from Taxes'+@lnk+'.dbo.FN819 where FN819.N739__1=17 and FN819.N800__1=FN800.N800__1 and FN819.N800__1_1 is NULL) and IsNull(FN1016.D430,0) <>2727) 
				AND ((RelDec.D270 IN (SELECT id FROM @T)) AND (#RelDC.N739__1 IN (10,16))) 
				AND FN800.D270_2 IS NOT NULL
			GROUP BY FN800.D270_2  

			
			IF (@id IS NOT NULL)
			BEGIN
				UPDATE [arrears].[dbo].[arr_reestr_check]
					SET [reduced_higher_NO_summ] = (
						SELECT SUM(FN801.N801__3)/1000 FROM Taxes'+@lnk+'.dbo.FN800 
							join Taxes'+@lnk+'.dbo.FN212 on FN800.N1_1=FN212.N1
									 join (Taxes'+@lnk+'.dbo.FN1189 join Taxes'+@lnk+'.dbo.FN1190 on FN1189.D895 =  FN1190.D895 
								join Taxes'+@lnk+'.dbo.FN74 sa on FN1190.N269 = sa.N269 and  sa.D686 = (select distinct nt_username from master..sysprocesses where spid=@@SPID)) on FN1189.D895 = FN800.D895
							left join Taxes'+@lnk+'.dbo.FN1532 on FN1532.D270=FN800.D270_1
							left join Taxes'+@lnk+'.dbo.FN1016 on FN1016.D81=FN1532.D81
									 left join Taxes'+@lnk+'.dbo.FN1532 Decision on Decision.D270=FN800.D270_2
							left join Taxes'+@lnk+'.dbo.FN1016 DecisionDoc on Decision.D81=DecisionDoc.D81
									 left join  (Taxes'+@lnk+'.dbo.FN830 join Taxes'+@lnk+'.dbo.FN1002 on FN830.D6=FN1002.D6 
									 left join Taxes'+@lnk+'.dbo.fn42 (NOLOCK)  On Fn42.n1270=fn1002.n1270
								left join Taxes'+@lnk+'.dbo.FN801 on FN830.N830__1=FN801.N830__1 and FN801.N801__8 = 3
								left join Taxes'+@lnk+'.dbo.FN74 on FN74.N269=FN801.N269_1
								left join Taxes'+@lnk+'.dbo.FN1020 on FN801.D11=FN1020.D11
								left join Taxes'+@lnk+'.dbo.FN1501 on FN801.D300__1=FN1501.D300
								left join Taxes'+@lnk+'.dbo.FN1003 on FN801.D8 = FN1003.D8
								left join Taxes'+@lnk+'.dbo.FN806 on FN806.N830__1=FN830.N830__1
								left join Taxes'+@lnk+'.dbo.FN1262 on FN1262.D2718=FN1002.D2718
								left join Taxes'+@lnk+'.dbo.FN1262 FN1262a on FN1262a.D2718=FN1003.D2718
								left join Taxes'+@lnk+'.dbo.FN74 FPDDL on FPDDL.N269=FN801.N269_200
						) on  FN830.N800__1= FN800.N800__1
						 WHERE ( IsNull(FN1016.D172,0)<>216 and IsNull(DecisionDoc.D172,0)<>316  and not exists(select 1 from Taxes'+@lnk+'.dbo.FN819 where FN819.N739__1=17 and FN819.N800__1=FN800.N800__1 and FN819.N800__1_1 is NULL) and IsNull(FN1016.D430,0) <>2727) 
							AND ((FN800.D270_2 = @id)) AND FN801.N801__3 IS NOT NULL
					)
				WHERE [id_check]=@id_check AND [code_NO]=''86'+@lnk+'''	
			END
		
		
			FETCH NEXT FROM reestr_cursor
			INTO @id_check, @id_NP
		END

		CLOSE reestr_cursor
		DEALLOCATE reestr_cursor
		
		DROP TABLE #RelDC')
	
END

GO
/****** Object:  StoredProcedure [dbo].[PR_UPDATE_REESTR_03]    Script Date: 26.02.2016 17:49:17 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		<Author,,Name>
-- Create date: <Create Date,,>
-- Description:	
/** Обновляемые реквизиты:

	reduced_arb_court_summ		Уменьшено по решению Арбитражного суда   (тыс. руб.)
**/
-- =============================================
CREATE PROCEDURE [dbo].[PR_UPDATE_REESTR_03]
	@lnk varchar(2)
AS
BEGIN
	EXEC ('
	
		CREATE TABLE #RelDC 
			(N800__1 int, N800__1_1 int, N739__1 smallint, FN819_D301 smalldatetime,
				N1_1 int, RelD270_1 int, RelD270_2 int, RelD270_3 int, N800__900 bit)

		create index RelDC_800 on #RelDC(N800__1)

		insert into #RelDC
		select FN819.N800__1,  FN819.N800__1_1, FN819.N739__1, FN819.D301,
			FN800.N1_1, FN800.D270_1, FN800.D270_2, FN800.D270_3, FN800.N800__900
		from Taxes'+@lnk+'.dbo.FN819 
			left join Taxes'+@lnk+'.dbo.FN800 on FN800.N800__1 = FN819.N800__1_1
			
			
		DECLARE @id_check INT
		DECLARE @id_NP INT

		DECLARE reestr_cursor CURSOR FOR
			SELECT id_check, id_NP FROM arrears.dbo.arr_reestr_check WHERE code_NO=''86'+@lnk+'''
			
		OPEN reestr_cursor

		FETCH NEXT FROM reestr_cursor
			INTO @id_check, @id_NP
			
		WHILE @@FETCH_STATUS = 0
		BEGIN
									
			
			/* ****************************************************************************
				Уменьшено по решению Арбитражного суда   (тыс. руб.)
			***************************************************************************** */	
			
			DECLARE @T TABLE (id int)
			DELETE FROM @T
			INSERT INTO @T
			SELECT FN800.D270_2 FROM Taxes'+@lnk+'.dbo.FN800 
				join (Taxes'+@lnk+'.dbo.FN1189 join Taxes'+@lnk+'.dbo.FN1190 on FN1189.D895 =  FN1190.D895
					join Taxes'+@lnk+'.dbo.FN74 sa on FN1190.N269 = sa.N269 and  sa.D686 = (select distinct nt_username from master..sysprocesses where spid=@@SPID)) on FN1189.D895 = FN800.D895
				join Taxes'+@lnk+'.dbo.FN212 on FN800.N1_1=FN212.N1 
				left join Taxes'+@lnk+'.dbo.FN1532 on FN1532.D270=FN800.D270_1
				left join Taxes'+@lnk+'.dbo.FN1016 on FN1016.D81=FN1532.D81
				left join Taxes'+@lnk+'.dbo.FN1532 Decision on Decision.D270=FN800.D270_2
				left join Taxes'+@lnk+'.dbo.FN1016 DecisionDoc on Decision.D81=DecisionDoc.D81
				left join Taxes'+@lnk+'.dbo.FN1532 Certificate on Certificate.D270=FN800.D270_3
				left join (Taxes'+@lnk+'.dbo.FN212 GNIOrg left join Taxes'+@lnk+'.dbo.FN71 on FN71.N1=GNIOrg.N1
						left join Taxes'+@lnk+'.dbo.FN213 on FN213.N314=GNIOrg.N314  
						left join Taxes'+@lnk+'.dbo.FN1263 on FN1263.D2700=FN71.D2700
										   ) on GNIOrg.N1=FN800.N1_3
				left join Taxes'+@lnk+'.dbo.FN800_VRUCH va on va.N800__1=FN800.N800__1
				left join Taxes'+@lnk+'.dbo.FN74 e on e.N269=FN800.N269
				left join Taxes'+@lnk+'.dbo.fn8151 on fn8151.n800__1=fn800.n800__1
				left join (select count(*) cnt, FN819.N800__1_1 from Taxes'+@lnk+'.dbo.FN819 
					where FN819.N739__1=1 or (FN819.N739__1=17 and exists(select * from Taxes'+@lnk+'.dbo.FN800VSTR join Taxes'+@lnk+'.dbo.FN441 on FN441.N800VSTR__1=FN800VSTR.N800VSTR__1 and FN441.N441__20=0 and FN441.N440__1=26 where FN800VSTR.N800__1=FN819.N800__1))
					group by FN819.N800__1_1) Vstr_cnt on Vstr_cnt.N800__1_1=FN800.N800__1
							left join Taxes'+@lnk+'.dbo.FN800DOP2 on FN800DOP2.n800__1= FN800.N800__1
							left join Taxes'+@lnk+'.dbo.fn800op on fn800op.n800__1 = fn800.n800__1
				left join Taxes'+@lnk+'.dbo.FN350SUD on FN350SUD.N800__1=FN800.N800__1 and IsNull(N350SUD__50,0) = 0
			WHERE ( IsNull(FN1016.D172,0)<>216 and IsNull(DecisionDoc.D172,0)<>316 and not exists(select 1 from Taxes'+@lnk+'.dbo.FN819 where FN819.N739__1=17 and FN819.N800__1=FN800.N800__1 and FN819.N800__1_1 is NULL) and IsNull(FN1016.D430,0) <>2727) 
				AND ((FN800.N800__1 IN (@id_check))) 
			GROUP BY FN800.D270_2
			
			
			DECLARE @ids TABLE(id INT)
			DELETE FROM @ids
			INSERT INTO @ids
			SELECT FN800.D270_2 FROM Taxes'+@lnk+'.dbo.FN800 
				join (Taxes'+@lnk+'.dbo.FN1189 join Taxes'+@lnk+'.dbo.FN1190 on FN1189.D895 =  FN1190.D895 
					join Taxes'+@lnk+'.dbo.FN74 sa on FN1190.N269 = sa.N269 and  sa.D686 = (select distinct nt_username from master..sysprocesses where spid=@@SPID)) on FN1189.D895 = FN800.D895
				join Taxes'+@lnk+'.dbo.FN212 on FN800.N1_1=FN212.N1
				left join Taxes'+@lnk+'.dbo.FN1532 Decision on Decision.D270=FN800.D270_2
				left join Taxes'+@lnk+'.dbo.FN1016 DecisionDoc on Decision.D81=DecisionDoc.D81
				left join (Taxes'+@lnk+'.dbo.FN1532 join Taxes'+@lnk+'.dbo.FN1016 on FN1532.D81=FN1016.D81  left join Taxes'+@lnk+'.dbo.FN1017 on FN1017.D172=FN1016.D172) on FN1532.D270=FN800.D270_1
				left join #RelDC on FN800.N800__1 = #RelDC.N800__1
				join Taxes'+@lnk+'.dbo.FN739 on #RelDC.N739__1 = FN739.N739__1
				left join Taxes'+@lnk+'.dbo.FN212 RelOrg on RelOrg.N1=#RelDC.N1_1
				left join (Taxes'+@lnk+'.dbo.FN1532 RelAct join Taxes'+@lnk+'.dbo.FN1016 RA  on RelAct.D81 = RA.D81  left join Taxes'+@lnk+'.dbo.FN1017 RDT on RDT.D172=RA.D172) on RelAct.D270 = #RelDC.RelD270_1
				left join (Taxes'+@lnk+'.dbo.FN1532 RelDec join Taxes'+@lnk+'.dbo.FN1016 RD on RelDec.D81 = RD.D81) on RelDec.D270 = #RelDC.RelD270_2
				left join (Taxes'+@lnk+'.dbo.FN1532 RelCert join Taxes'+@lnk+'.dbo.FN1016 RC on RelCert.D81 = RC.D81) on RelCert.D270 = #RelDC.RelD270_3
							left join Taxes'+@lnk+'.dbo.fn800op on fn800op.n800__1 = fn800.n800__1
			WHERE (IsNull(FN1016.D172,0)<>216 and IsNull(DecisionDoc.D172,0)<>316 and not exists(select 1 from Taxes'+@lnk+'.dbo.FN819 where FN819.N739__1=17 and FN819.N800__1=FN800.N800__1 and FN819.N800__1_1 is NULL) and IsNull(FN1016.D430,0) <>2727) 
				AND ((RelDec.D270 IN (SELECT id FROM @T)) AND (#RelDC.N739__1 IN (5))) 
				AND FN800.D270_2 IS NOT NULL
			GROUP BY FN800.D270_2  

			
			IF EXISTS(SELECT 1 FROM @ids)
			BEGIN
				UPDATE [arrears].[dbo].[arr_reestr_check]
					SET [reduced_arb_court_summ] = (
						SELECT SUM(FN801.N801__3)/1000 FROM Taxes'+@lnk+'.dbo.FN800 
							join Taxes'+@lnk+'.dbo.FN212 on FN800.N1_1=FN212.N1
									 join (Taxes'+@lnk+'.dbo.FN1189 join Taxes'+@lnk+'.dbo.FN1190 on FN1189.D895 =  FN1190.D895 
								join Taxes'+@lnk+'.dbo.FN74 sa on FN1190.N269 = sa.N269 and  sa.D686 = (select distinct nt_username from master..sysprocesses where spid=@@SPID)) on FN1189.D895 = FN800.D895
							left join Taxes'+@lnk+'.dbo.FN1532 on FN1532.D270=FN800.D270_1
							left join Taxes'+@lnk+'.dbo.FN1016 on FN1016.D81=FN1532.D81
									 left join Taxes'+@lnk+'.dbo.FN1532 Decision on Decision.D270=FN800.D270_2
							left join Taxes'+@lnk+'.dbo.FN1016 DecisionDoc on Decision.D81=DecisionDoc.D81
									 left join  (Taxes'+@lnk+'.dbo.FN830 join Taxes'+@lnk+'.dbo.FN1002 on FN830.D6=FN1002.D6 
									 left join Taxes'+@lnk+'.dbo.fn42 (NOLOCK)  On Fn42.n1270=fn1002.n1270
								left join Taxes'+@lnk+'.dbo.FN801 on FN830.N830__1=FN801.N830__1 and FN801.N801__8 = 3
								left join Taxes'+@lnk+'.dbo.FN74 on FN74.N269=FN801.N269_1
								left join Taxes'+@lnk+'.dbo.FN1020 on FN801.D11=FN1020.D11
								left join Taxes'+@lnk+'.dbo.FN1501 on FN801.D300__1=FN1501.D300
								left join Taxes'+@lnk+'.dbo.FN1003 on FN801.D8 = FN1003.D8
								left join Taxes'+@lnk+'.dbo.FN806 on FN806.N830__1=FN830.N830__1
								left join Taxes'+@lnk+'.dbo.FN1262 on FN1262.D2718=FN1002.D2718
								left join Taxes'+@lnk+'.dbo.FN1262 FN1262a on FN1262a.D2718=FN1003.D2718
								left join Taxes'+@lnk+'.dbo.FN74 FPDDL on FPDDL.N269=FN801.N269_200
						) on  FN830.N800__1= FN800.N800__1
						 WHERE (IsNull(FN1016.D172,0)<>216 and IsNull(DecisionDoc.D172,0)<>316  and not exists(select 1 from Taxes'+@lnk+'.dbo.FN819 where FN819.N739__1=17 and FN819.N800__1=FN800.N800__1 and FN819.N800__1_1 is NULL) and IsNull(FN1016.D430,0) <>2727) 
							AND ((FN800.D270_2 IN (SELECT id FROM @ids))) AND FN801.N801__3 IS NOT NULL
					)
				WHERE [id_check]=@id_check AND [code_NO]=''86'+@lnk+'''	
			END
		
		
			FETCH NEXT FROM reestr_cursor
			INTO @id_check, @id_NP
		END

		CLOSE reestr_cursor
		DEALLOCATE reestr_cursor
		
		DROP TABLE #RelDC')
	
END

GO
/****** Object:  StoredProcedure [dbo].[PR_UPDATE_REESTR_04]    Script Date: 26.02.2016 17:49:17 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		<Author,,Name>
-- Create date: <Create Date,,>
-- Description:	
/** Обновляемые реквизиты:

	resolution_adop_sec_measure_ban_alien_num		Решение о принятии обеспечительных мер (по невзысканным платежам) / запрет на отчуждение имущества / номер
	resolution_adop_sec_measure_ban_alien_date		Решение о принятии обеспечительных мер (по невзысканным платежам) / запрет на отчуждение имущества / дата
**/
-- =============================================
CREATE PROCEDURE [dbo].[PR_UPDATE_REESTR_04]
	@lnk varchar(2)
AS
BEGIN
	EXEC ('
	
		
			
		DECLARE @id_check INT
		DECLARE @id_NP INT
		DECLARE @t_num varchar(25)
		DECLARE @t_date date
		DECLARE @T1 TABLE(reg_num int)
		
		DECLARE reestr_cursor CURSOR FOR
			SELECT id_check, id_NP FROM arrears.dbo.arr_reestr_check WHERE code_NO=''86'+@lnk+'''
			
		OPEN reestr_cursor

		FETCH NEXT FROM reestr_cursor
			INTO @id_check, @id_NP
			
		WHILE @@FETCH_STATUS = 0
		BEGIN
									
			
			
			/* ********************************************************************************************************************
				Решение о принятии обеспечительных мер (по невзысканным платежам). Запрет на отчуждение имущества
			*********************************************************************************************************************** */
			
			
			DELETE FROM @T1
			SELECT @t_num = NULL
			SELECT @t_date = NULL
											
			-- Контрольная деятельность\ВНП, другие проверки, сведения внешних корреспондентов\Учет сведений (QBE по решениям)
			INSERT INTO @T1
			SELECT FN800.D270_2 FROM Taxes'+@lnk+'.dbo.FN800 
				join (Taxes'+@lnk+'.dbo.FN1189 join Taxes'+@lnk+'.dbo.FN1190 on FN1189.D895 =  FN1190.D895
					join Taxes'+@lnk+'.dbo.FN74 sa on FN1190.N269 = sa.N269 and  sa.D686 = (select distinct nt_username from master..sysprocesses where spid=@@SPID)) on FN1189.D895 = FN800.D895
				join Taxes'+@lnk+'.dbo.FN212 on FN800.N1_1=FN212.N1 
				left join Taxes'+@lnk+'.dbo.FN1532 on FN1532.D270=FN800.D270_1
				left join Taxes'+@lnk+'.dbo.FN1016 on FN1016.D81=FN1532.D81
				left join Taxes'+@lnk+'.dbo.FN1532 Decision on Decision.D270=FN800.D270_2
				left join Taxes'+@lnk+'.dbo.FN1016 DecisionDoc on Decision.D81=DecisionDoc.D81
				left join Taxes'+@lnk+'.dbo.FN1532 Certificate on Certificate.D270=FN800.D270_3
				left join (Taxes'+@lnk+'.dbo.FN212 GNIOrg left join Taxes'+@lnk+'.dbo.FN71 on FN71.N1=GNIOrg.N1
						left join Taxes'+@lnk+'.dbo.FN213 on FN213.N314=GNIOrg.N314  
						left join Taxes'+@lnk+'.dbo.FN1263 on FN1263.D2700=FN71.D2700
				) on GNIOrg.N1=FN800.N1_3
				left join Taxes'+@lnk+'.dbo.FN800_VRUCH va on va.N800__1=FN800.N800__1
				left join Taxes'+@lnk+'.dbo.FN74 e on e.N269=FN800.N269
							left join Taxes'+@lnk+'.dbo.fn8151 on fn8151.n800__1=fn800.n800__1
				left join (select count(*) cnt, FN819.N800__1_1 from Taxes'+@lnk+'.dbo.FN819 
					where FN819.N739__1=1 or (FN819.N739__1=17 and exists(select * from Taxes'+@lnk+'.dbo.FN800VSTR join Taxes'+@lnk+'.dbo.FN441 on FN441.N800VSTR__1=FN800VSTR.N800VSTR__1 and FN441.N441__20=0 and FN441.N440__1=26 where FN800VSTR.N800__1=FN819.N800__1))
					group by FN819.N800__1_1) Vstr_cnt on Vstr_cnt.N800__1_1=FN800.N800__1
							left join Taxes'+@lnk+'.dbo.FN800DOP2 on FN800DOP2.n800__1= FN800.N800__1
							left join Taxes'+@lnk+'.dbo.fn800op on fn800op.n800__1 = fn800.n800__1
				left join Taxes'+@lnk+'.dbo.FN350SUD on FN350SUD.N800__1=FN800.N800__1 and IsNull(N350SUD__50,0) = 0
			 WHERE (IsNull(FN1016.D172,0)<>216 and IsNull(DecisionDoc.D172,0)<>316 
				and not exists(select 1 from Taxes'+@lnk+'.dbo.FN819 where FN819.N739__1=17 and FN819.N800__1=FN800.N800__1 
				and FN819.N800__1_1 is NULL) and IsNull(FN1016.D430,0) <>2727) 
				AND ((FN800.N800__1 IN (@id_check))) 
			GROUP BY FN800.D270_2
			
			
			CREATE TABLE #RelDC 
				(N800__1 int, N800__1_1 int, N739__1 smallint, FN819_D301 smalldatetime,
					N1_1 int, RelD270_1 int, RelD270_2 int, RelD270_3 int, N800__900 bit)

			create index RelDC_800 on #RelDC(N800__1)

			insert into #RelDC
			select FN819.N800__1,  FN819.N800__1_1, FN819.N739__1, FN819.D301,
				FN800.N1_1, FN800.D270_1, FN800.D270_2, FN800.D270_3, FN800.N800__900
			from Taxes'+@lnk+'.dbo.FN819 
				left join Taxes'+@lnk+'.dbo.FN800 on FN800.N800__1 = FN819.N800__1_1
			
			-- Контрольная деятельность\ВНП, другие проверки, сведения внешних корреспондентов\Учет сведений (QBE по решениям)/QBE - Проверка+связанная проверка
			SELECT TOP 1 @t_date = Decision.D223, @t_num = Decision.N590 FROM Taxes'+@lnk+'.dbo.FN800 
				join (Taxes'+@lnk+'.dbo.FN1189 join Taxes'+@lnk+'.dbo.FN1190 on FN1189.D895 =  FN1190.D895 
					join Taxes'+@lnk+'.dbo.FN74 sa on FN1190.N269 = sa.N269 and  sa.D686 = (select distinct nt_username from master..sysprocesses where spid=@@SPID)) on FN1189.D895 = FN800.D895
				join Taxes'+@lnk+'.dbo.FN212 on FN800.N1_1=FN212.N1
				left join Taxes'+@lnk+'.dbo.FN1532 Decision on Decision.D270=FN800.D270_2
				left join Taxes'+@lnk+'.dbo.FN1016 DecisionDoc on Decision.D81=DecisionDoc.D81
				left join (Taxes'+@lnk+'.dbo.FN1532 join Taxes'+@lnk+'.dbo.FN1016 on FN1532.D81=FN1016.D81  left join Taxes'+@lnk+'.dbo.FN1017 on FN1017.D172=FN1016.D172) on FN1532.D270=FN800.D270_1
				left join #RelDC on FN800.N800__1 = #RelDC.N800__1
				join Taxes'+@lnk+'.dbo.FN739 on #RelDC.N739__1 = FN739.N739__1
				left join Taxes'+@lnk+'.dbo.FN212 RelOrg on RelOrg.N1=#RelDC.N1_1
				left join (Taxes'+@lnk+'.dbo.FN1532 RelAct join Taxes'+@lnk+'.dbo.FN1016 RA  on RelAct.D81 = RA.D81  left join Taxes'+@lnk+'.dbo.FN1017 RDT on RDT.D172=RA.D172) on RelAct.D270 = #RelDC.RelD270_1
				left join (Taxes'+@lnk+'.dbo.FN1532 RelDec join Taxes'+@lnk+'.dbo.FN1016 RD on RelDec.D81 = RD.D81) on RelDec.D270 = #RelDC.RelD270_2
				left join (Taxes'+@lnk+'.dbo.FN1532 RelCert join Taxes'+@lnk+'.dbo.FN1016 RC on RelCert.D81 = RC.D81) on RelCert.D270 = #RelDC.RelD270_3
							left join Taxes'+@lnk+'.dbo.fn800op on fn800op.n800__1 = fn800.n800__1
			WHERE (IsNull(FN1016.D172,0)<>216 and IsNull(DecisionDoc.D172,0)<>316  and not exists(select 1 from Taxes'+@lnk+'.dbo.FN819 where FN819.N739__1=17 and FN819.N800__1=FN800.N800__1 and FN819.N800__1_1 is NULL) and IsNull(FN1016.D430,0) <>2727) 
				AND ((RelDec.D270 IN (SELECT reg_num FROM @T1)) AND (#RelDC.N739__1 = 23)) 
			GROUP BY Decision.D223, Decision.N590
					
						
			IF (@t_num IS NOT NULL)
			BEGIN				
				UPDATE [arrears].[dbo].[arr_reestr_check]
					SET [resolution_adop_sec_measure_ban_alien_num] = @t_num
				WHERE [id_check]=@id_check AND [code_NO]=''86'+@lnk+'''
			END 
			
			IF (@t_date IS NOT NULL)
			BEGIN
				UPDATE [arrears].[dbo].[arr_reestr_check]
					SET [resolution_adop_sec_measure_ban_alien_date] = @t_date
				WHERE [id_check]=@id_check AND [code_NO]=''86'+@lnk+'''
			END 
								
			DROP TABLE #RelDC
			
			FETCH NEXT FROM reestr_cursor
			INTO @id_check, @id_NP
		END

		CLOSE reestr_cursor
		DEALLOCATE reestr_cursor
		
		')
	
END

GO
/****** Object:  StoredProcedure [dbo].[PR_UPDATE_REESTR_04_OLD]    Script Date: 26.02.2016 17:49:17 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		<Author,,Name>
-- Create date: <Create Date,,>
-- Description:	
/** Обновляемые реквизиты:

	resolution_adop_sec_measure_ban_alien_num		Решение о принятии обеспечительных мер (по невзысканным платежам) / запрет на отчуждение имущества / номер
	resolution_adop_sec_measure_ban_alien_date		Решение о принятии обеспечительных мер (по невзысканным платежам) / запрет на отчуждение имущества / дата
**/
-- =============================================
CREATE PROCEDURE [dbo].[PR_UPDATE_REESTR_04_OLD]
	@lnk varchar(2)
AS
BEGIN
	EXEC ('
	
		CREATE TABLE #RelDC 
			(N800__1 int, N800__1_1 int, N739__1 smallint, FN819_D301 smalldatetime,
				N1_1 int, RelD270_1 int, RelD270_2 int, RelD270_3 int, N800__900 bit)

		create index RelDC_800 on #RelDC(N800__1)

		insert into #RelDC
		select FN819.N800__1,  FN819.N800__1_1, FN819.N739__1, FN819.D301,
			FN800.N1_1, FN800.D270_1, FN800.D270_2, FN800.D270_3, FN800.N800__900
		from Taxes'+@lnk+'.dbo.FN819 
			left join Taxes'+@lnk+'.dbo.FN800 on FN800.N800__1 = FN819.N800__1_1
			
			
		DECLARE @id_check INT
		DECLARE @id_NP INT

		DECLARE reestr_cursor CURSOR FOR
			SELECT id_check, id_NP FROM arrears.dbo.arr_reestr_check WHERE code_NO=''86'+@lnk+'''
			
		OPEN reestr_cursor

		FETCH NEXT FROM reestr_cursor
			INTO @id_check, @id_NP
			
		WHILE @@FETCH_STATUS = 0
		BEGIN
									
			
			/* ********************************************************************************************************************
				Решение о принятии обеспечительных мер (по невзысканным платежам). Запрет на отчуждение имущества
			*********************************************************************************************************************** */
			
			DECLARE @t_num varchar(25)
			DECLARE @t_date date
			
			SET @t_num = null
			SET @t_date = null
			
			-- Контрольная деятельность\ВНП, другие проверки, сведения внешних корреспондентов\Учет сведений (QBE по решениям) / Проверка + связанная Проверка
			SELECT TOP 1 @t_date = Decision.D223, @t_num = Decision.N590 FROM Taxes'+@lnk+'.dbo.FN800 
				join (Taxes'+@lnk+'.dbo.FN1189 join Taxes'+@lnk+'.dbo.FN1190 on FN1189.D895 =  FN1190.D895 
					join Taxes'+@lnk+'.dbo.FN74 sa on FN1190.N269 = sa.N269 and  sa.D686 = (select distinct nt_username from master..sysprocesses where spid=@@SPID)) on FN1189.D895 = FN800.D895
				join Taxes'+@lnk+'.dbo.FN212 on FN800.N1_1=FN212.N1
				left join Taxes'+@lnk+'.dbo.FN1532 Decision on Decision.D270=FN800.D270_2
				left join Taxes'+@lnk+'.dbo.FN1016 DecisionDoc on Decision.D81=DecisionDoc.D81
				left join (Taxes'+@lnk+'.dbo.FN1532 join Taxes'+@lnk+'.dbo.FN1016 on FN1532.D81=FN1016.D81  left join Taxes'+@lnk+'.dbo.FN1017 on FN1017.D172=FN1016.D172) on FN1532.D270=FN800.D270_1
				left join #RelDC on FN800.N800__1 = #RelDC.N800__1
				join Taxes'+@lnk+'.dbo.FN739 on #RelDC.N739__1 = FN739.N739__1
				left join Taxes'+@lnk+'.dbo.FN212 RelOrg on RelOrg.N1=#RelDC.N1_1
				left join (Taxes'+@lnk+'.dbo.FN1532 RelAct join Taxes'+@lnk+'.dbo.FN1016 RA  on RelAct.D81 = RA.D81  left join Taxes'+@lnk+'.dbo.FN1017 RDT on RDT.D172=RA.D172) on RelAct.D270 = #RelDC.RelD270_1
				left join (Taxes'+@lnk+'.dbo.FN1532 RelDec join Taxes'+@lnk+'.dbo.FN1016 RD on RelDec.D81 = RD.D81) on RelDec.D270 = #RelDC.RelD270_2
				left join (Taxes'+@lnk+'.dbo.FN1532 RelCert join Taxes'+@lnk+'.dbo.FN1016 RC on RelCert.D81 = RC.D81) on RelCert.D270 = #RelDC.RelD270_3
							left join Taxes'+@lnk+'.dbo.fn800op on fn800op.n800__1 = fn800.n800__1
			WHERE (IsNull(FN1016.D172,0)<>216 and IsNull(DecisionDoc.D172,0)<>316 and not exists(select 1 from Taxes'+@lnk+'.dbo.FN819 where FN819.N739__1=17 and FN819.N800__1=FN800.N800__1 and FN819.N800__1_1 is NULL) and IsNull(FN1016.D430,0) <>2727) 
				AND ((FN800.N800__1 = @id_check) AND (#RelDC.N739__1 IN (5))) 
				AND FN800.D270_2 IS NOT NULL
			
			IF (@t_num IS NOT NULL)
			BEGIN
				UPDATE [arrears].[dbo].[arr_reestr_check]
					SET [resolution_adop_sec_measure_ban_alien_num] = @t_num
				WHERE [id_check]=@id_check AND [code_NO]=''86'+@lnk+'''
			END 
			
			IF (@t_date IS NOT NULL)
			BEGIN
				UPDATE [arrears].[dbo].[arr_reestr_check]
					SET [resolution_adop_sec_measure_ban_alien_date] = @t_date
				WHERE [id_check]=@id_check AND [code_NO]=''86'+@lnk+'''
			END 
								
		
			FETCH NEXT FROM reestr_cursor
			INTO @id_check, @id_NP
		END

		CLOSE reestr_cursor
		DEALLOCATE reestr_cursor
		
		DROP TABLE #RelDC')
	
END

GO
/****** Object:  StoredProcedure [dbo].[PR_UPDATE_REESTR_05]    Script Date: 26.02.2016 17:49:17 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		<Author,,Name>
-- Create date: <Create Date,,>
-- Description:	
/** Обновляемые реквизиты:

	resolution_adop_sec_measure_susp_oper_num		Решение о принятии обеспечительных мер (по невзысканным платежам) / приостановление операций по счетам / номер
	resolution_adop_sec_measure_susp_oper_date		Решение о принятии обеспечительных мер (по невзысканным платежам) / приостановление операций по счетам / дата
**/
-- =============================================
CREATE PROCEDURE [dbo].[PR_UPDATE_REESTR_05]
	@lnk varchar(2)
AS
BEGIN
	EXEC ('

		DECLARE @id_check INT
		DECLARE @id_NP INT

		DECLARE reestr_cursor CURSOR FOR
			SELECT id_check, id_NP FROM arrears.dbo.arr_reestr_check WHERE code_NO=''86'+@lnk+'''
			
		OPEN reestr_cursor

		FETCH NEXT FROM reestr_cursor
			INTO @id_check, @id_NP
			
		WHILE @@FETCH_STATUS = 0
		BEGIN
									
			
			/* ****************************************************************************
				Решение о принятии обеспечительных мер (по невзысканным платежам) 
				приостановление операций по счетам
			***************************************************************************** */	
			
			DECLARE @T TABLE (id int)
			DELETE FROM @T
			INSERT INTO @T
			SELECT FN800.D270_2 FROM  Taxes'+@lnk+'.dbo.FN800 
				join ( Taxes'+@lnk+'.dbo.FN1189 join  Taxes'+@lnk+'.dbo.FN1190 on FN1189.D895 =  FN1190.D895
					join  Taxes'+@lnk+'.dbo.FN74 sa on FN1190.N269 = sa.N269 and  sa.D686 = (select distinct nt_username from master..sysprocesses where spid=@@SPID)) on FN1189.D895 = FN800.D895
				join  Taxes'+@lnk+'.dbo.FN212 on FN800.N1_1=FN212.N1 
				left join  Taxes'+@lnk+'.dbo.FN1532 on FN1532.D270=FN800.D270_1
				left join  Taxes'+@lnk+'.dbo.FN1016 on FN1016.D81=FN1532.D81
				left join  Taxes'+@lnk+'.dbo.FN1532 Decision on Decision.D270=FN800.D270_2
				left join  Taxes'+@lnk+'.dbo.FN1016 DecisionDoc on Decision.D81=DecisionDoc.D81
				left join  Taxes'+@lnk+'.dbo.FN1532 Certificate on Certificate.D270=FN800.D270_3
				left join ( Taxes'+@lnk+'.dbo.FN212 GNIOrg left join  Taxes'+@lnk+'.dbo.FN71 on FN71.N1=GNIOrg.N1
						left join  Taxes'+@lnk+'.dbo.FN213 on FN213.N314=GNIOrg.N314  
						left join  Taxes'+@lnk+'.dbo.FN1263 on FN1263.D2700=FN71.D2700
										   ) on GNIOrg.N1=FN800.N1_3
				left join  Taxes'+@lnk+'.dbo.FN800_VRUCH va on va.N800__1=FN800.N800__1
				left join  Taxes'+@lnk+'.dbo.FN74 e on e.N269=FN800.N269
				left join  Taxes'+@lnk+'.dbo.fn8151 on fn8151.n800__1=fn800.n800__1
				left join (select count(*) cnt, FN819.N800__1_1 from  Taxes'+@lnk+'.dbo.FN819 
					where FN819.N739__1=1 or (FN819.N739__1=17 and exists(select * from  Taxes'+@lnk+'.dbo.FN800VSTR join  Taxes'+@lnk+'.dbo.FN441 on FN441.N800VSTR__1=FN800VSTR.N800VSTR__1 and FN441.N441__20=0 and FN441.N440__1=26 where FN800VSTR.N800__1=FN819.N800__1))
					group by FN819.N800__1_1) Vstr_cnt on Vstr_cnt.N800__1_1=FN800.N800__1
							left join  Taxes'+@lnk+'.dbo.FN800DOP2 on FN800DOP2.n800__1= FN800.N800__1
							left join  Taxes'+@lnk+'.dbo.fn800op on fn800op.n800__1 = fn800.n800__1
				left join  Taxes'+@lnk+'.dbo.FN350SUD on FN350SUD.N800__1=FN800.N800__1 and IsNull(N350SUD__50,0) = 0
			WHERE ( IsNull(FN1016.D172,0)<>216 and IsNull(DecisionDoc.D172,0)<>316 and not exists(select 1 from  Taxes'+@lnk+'.dbo.FN819 where FN819.N739__1=17 and FN819.N800__1=FN800.N800__1 and FN819.N800__1_1 is NULL) and IsNull(FN1016.D430,0) <>2727) 
				AND ((FN800.N800__1 IN (@id_check))) 
			GROUP BY FN800.D270_2
			
			DECLARE @kod int	
			SET @kod = null
					
			SELECT TOP 1 @kod = FN1545.D2014 FROM  Taxes'+@lnk+'.dbo.FN1545
				join  Taxes'+@lnk+'.dbo.FN1532 Doc on Doc.D270 = FN1545.D270_1
				join  Taxes'+@lnk+'.dbo.FN1016 DocType on DocType.D81 = Doc.D81
				join  Taxes'+@lnk+'.dbo.FN212 Org on Org.N1 = Doc.N1
				left join  Taxes'+@lnk+'.dbo.FN800 on FN800.D270_2 = Doc.D270
				left join
					(select a.D270_2, sum(c.N8401__3) * 1000 D83
					from  Taxes'+@lnk+'.dbo.FN800 a
					join  Taxes'+@lnk+'.dbo.FN8400 b on b.N800__1 = a.N800__1
					join  Taxes'+@lnk+'.dbo.FN8410 c on c.N8400__1 = b.N8400__1
					where b.N731__1 in (42, 43, 44, 45)
					group by a.D270_2
					) Prop_VP on Prop_VP.D270_2 = Doc.D270			-- собственность (ВП)
				left join
					(select D270, sum(IsNull(V479_6, 0) + IsNull(V479_7, 0) + IsNull(V479_8, 0) + IsNull(V479_9, 0)) D83
					from  Taxes'+@lnk+'.dbo.CamDecAcceptInterimMeasures
					group by D270
					) Prop_KP on Prop_KP.D270 = Doc.D270			-- собственность (КП)
				left join 
					( Taxes'+@lnk+'.dbo.FN819 
					join  Taxes'+@lnk+'.dbo.FN800 FN800_zav on FN800_zav.N800__1 = FN819.N800__1_1 
					join  Taxes'+@lnk+'.dbo.FN1532 FN1532_VP on FN1532_VP.D270 = FN800_zav.D270_2 and FN1532_VP.D428 <> 1013
					join  Taxes'+@lnk+'.dbo.FN1016 FN1016_VP on FN1016_VP.D81 = FN1532_VP.D81 and FN1016_VP.D172 in (145, 146)
					) on FN819.N800__1 = FN800.N800__1			-- решение о привлечении к ответственности (ВП)
				left join
					(select a.N800__1,
						sum(case when b.D244 = 18 and b.D63 <> 105 then IsNull(a.N801__3, 0) else 0 end) D83_1,
						sum(case when b.D244 = 36 then IsNull(a.N801__3, 0) else 0 end) D83_2,
						sum(case when b.D244 = 67 then IsNull(a.N801__3, 0) else 0 end) D83_3
					from  Taxes'+@lnk+'.dbo.FN801 a
					join  Taxes'+@lnk+'.dbo.FN1020 b on b.D11 = a.D11
					where a.N801__8 = 3
					group by a.N800__1
					) Ned_VP on Ned_VP.N800__1 = FN800_zav.N800__1		-- недоимка по решению о привлечении к ответственности (ВП)
				left join 
					( Taxes'+@lnk+'.dbo.FN1532_30
					join  Taxes'+@lnk+'.dbo.FN1532 FN1532_KP on FN1532_KP.D270 = FN1532_30.D270_1 and FN1532_KP.D428 <> 1013
					join  Taxes'+@lnk+'.dbo.FN1016 FN1016_KP on FN1016_KP.D81 = FN1532_KP.D81 and FN1016_KP.D430 in (15, 372) and FN1016_KP.D172 = 0
					join  Taxes'+@lnk+'.dbo.Cam1532_NN48_Summa Ned_KP on Ned_KP.D270 = FN1532_KP.D270
					) on  Taxes'+@lnk+'.dbo.FN1532_30.D270_2 = Doc.D270 and FN1532_30.D4164 = 19		-- решение о привлечении к ответственности (КП)
			WHERE (IsNull(FN1532_VP.D270, FN1532_KP.D270) IN (SELECT id FROM @T)) 
			GROUP BY FN1545.D2014
			
			
			DECLARE @t_date DATE			
			SET @t_date = NULL	
			
			SELECT TOP 1 @t_date = Dec.D85 FROM  Taxes'+@lnk+'.dbo.FN1543 Dec
				join  Taxes'+@lnk+'.dbo.FN212 Org on Org.N1 = Dec.N1
				join  Taxes'+@lnk+'.dbo.FN1027 Osn1 on Osn1.D90 = Dec.D90_1
				join  Taxes'+@lnk+'.dbo.FN1027 Osn2 on Osn2.D90 = Dec.D90_2
				join  Taxes'+@lnk+'.dbo.FN74 on FN74.N269 = Dec.N269
				left join (select D2014, max(D858) D858, max(D270) D270, max(D270_1) D270_1 from  Taxes'+@lnk+'.dbo.FN1545 group by D2014) FN1545 on FN1545.D2014 = Dec.D2014
				/*
				left join 
				  (select FN1545.D2014, sum(aa.SumVz) SumVz, sum(aa.Total) SumOst, sum(aa.SumArc) SumArc
				  from FN1545 with (index = XIF2131FN1545) 
				  join DocsForAccountAttach aa on aa.D270 = FN1545.D270
				  group by FN1545.D2014
				  ) DecVz on DecVz.D2014 = Dec.D2014		-- решения о взыскании, вошедшие в решение о приостановлении
				*/
				left join  Taxes'+@lnk+'.dbo.DocsForAccountAttach DecVz on DecVz.D270 = FN1545.D270	-- решения о взыскании, вошедшие в решение о приостановлении
				left join
				  (select FN1545.D2014, count(*) cntAll, sum(case when Menu.D81 is not Null then 1 else 0 end) cntViol
				  from  Taxes'+@lnk+'.dbo.FN1545
				  join  Taxes'+@lnk+'.dbo.FN1537 on FN1537.D858 = FN1545.D858
				  left join  Taxes'+@lnk+'.dbo.FN1515 on FN1515.D859 = FN1537.D859
				  left join (select a.D81 from  Taxes'+@lnk+'.dbo.FN1060 a where a.D176 = 20 group by a.D81) Menu on Menu.D81 = IsNull(FN1515.D673, FN1537.D673)
				  group by FN1545.D2014
				  ) SobDos on SobDos.D2014 = Dec.D2014
				left join 
				  (select FN1545.D2014, 
					sum(case when bkr.D09_6 is Null and bkr.D09 in (5, 6, 7, 8) then 1 else 0 end) cntUnfinished, 
					sum(case when bkr.D09_6 is not Null then 1 else 0 end) cntFinished,
					sum(case when bkr.D3525 is not Null then 1 else 0 end) cntAll
				  from  Taxes'+@lnk+'.dbo.FN1545
				  left join  Taxes'+@lnk+'.dbo.FN1534 aa on aa.D270 = FN1545.D270
				  left join  Taxes'+@lnk+'.dbo.FN1537 bb on bb.D858 = FN1545.D858
				  left join  Taxes'+@lnk+'.dbo.FN212_02 bkr on bkr.N1 = IsNull(aa.N1, bb.N1)
				  group by FN1545.D2014
				  ) Bkr on Bkr.D2014 = Dec.D2014		-- решения о взыскании или события досье в разрезе банкротства

				-- Начиная отсюда и до конца все джойны ради одного индикатора ОМ!!!
				left join  Taxes'+@lnk+'.dbo.FN800 on FN800.D270_2 = FN1545.D270_1
				left join 
					( Taxes'+@lnk+'.dbo.FN819 
					join  Taxes'+@lnk+'.dbo.FN800 FN800_zav on FN800_zav.N800__1 = FN819.N800__1_1 
					join  Taxes'+@lnk+'.dbo.FN1532 FN1532_VP on FN1532_VP.D270 = FN800_zav.D270_2 and FN1532_VP.D428 <> 1013
					join  Taxes'+@lnk+'.dbo.FN1016 FN1016_VP on FN1016_VP.D81 = FN1532_VP.D81 and FN1016_VP.D172 in (145, 146)
					) on FN819.N800__1 = FN800.N800__1			-- решение о привлечении к ответственности (ВП)
				left join
					(select FN819.N800__1_1, 
						sum(case when DocType.D172 = 352 then 1 else 0 end) Canc,
						sum(case when DocType.D172 = 353 then 1 else 0 end) Repl
					from  Taxes'+@lnk+'.dbo.FN800 a
					join  Taxes'+@lnk+'.dbo.FN1532 Doc on Doc.D270 = a.D270_2
					join  Taxes'+@lnk+'.dbo.FN1016 DocType on DocType.D81 = Doc.D81
					join  Taxes'+@lnk+'.dbo.FN819 on FN819.N800__1 = a.N800__1
					where Doc.D430 = 2011 and DocType.D172 in (352, 353) and Doc.D428 <> 1013
					group by FN819.N800__1_1
					) CancelVP on CancelVP.N800__1_1 = FN800.N800__1					-- документы об отмене/замене решений (ВП)
				left join 
					(select FN1500.D270
					from  Taxes'+@lnk+'.dbo.FN1596
					join  Taxes'+@lnk+'.dbo.FN1500 on FN1500.D307 = FN1596.D307
					where FN1596.D2578 = 20
					group by FN1500.D270
					) VPInDecVz on VPInDecVz.D270 = FN1532_VP.D270						-- включение строк решения о привлечении к ответственности в решение о взыскании за счет денежных средств (ВП)

				left join 
					( Taxes'+@lnk+'.dbo.FN1532_30
					join  Taxes'+@lnk+'.dbo.FN1532 FN1532_KP on FN1532_KP.D270 = FN1532_30.D270_1 and FN1532_KP.D428 <> 1013
					join  Taxes'+@lnk+'.dbo.FN1016 FN1016_KP on FN1016_KP.D81 = FN1532_KP.D81 and FN1016_KP.D430 in (15, 372) and FN1016_KP.D172 = 0
					) on FN1532_30.D270_2 = FN1545.D270_1 and FN1532_30.D4164 = 19		-- решение о привлечении к ответственности (КП)
				left join 
					(select a.D270_1, 
						sum(case when a.D4164 = 20 then 1 else 0 end) Canc,
						sum(case when a.D4164 = 21 then 1 else 0 end) Repl
					from  Taxes'+@lnk+'.dbo.FN1532_30 a
					join  Taxes'+@lnk+'.dbo.FN1532 b on b.D270 = a.D270_2
					where a.D4164 in (20, 21) and b.D428 <> 1013
					group by a.D270_1
					) CancelKP on CancelKP.D270_1 = FN1545.D270_1						-- документы об отмене/замене решений (КП)
				left join 
					(select FN1500.D270
					from  Taxes'+@lnk+'.dbo.FN1596
					join  Taxes'+@lnk+'.dbo.FN1500 on FN1500.D307 = FN1596.D307
					where FN1596.D2578 = 20
					group by FN1500.D270
					) KPInDecVz on KPInDecVz.D270 = FN1532_KP.D270						-- включение строк решения о привлечении к ответственности в решение о взыскании за счет денежных средств (КП)
			WHERE (Dec.D2014 = @kod) 
			GROUP BY Dec.D85  
			
			
			IF ((@kod IS NOT NULL) OR (@t_date IS NOT NULL))
			BEGIN
				UPDATE [arrears].[dbo].[arr_reestr_check] SET 
					resolution_adop_sec_measure_susp_oper_num = @kod
				   ,resolution_adop_sec_measure_susp_oper_date = @t_date
				WHERE [id_check]=@id_check AND [code_NO]=''86'+@lnk+'''	 
			
			END						
		
		
			FETCH NEXT FROM reestr_cursor
			INTO @id_check, @id_NP
		END

		CLOSE reestr_cursor
		DEALLOCATE reestr_cursor')
	
END

GO
/****** Object:  StoredProcedure [dbo].[PR_UPDATE_REESTR_06]    Script Date: 26.02.2016 17:49:17 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		<Author,,Name>
-- Create date: <Create Date,,>
-- Description:	
/** Обновляемые реквизиты:

	info_removal_register_NP_date		Сведения о снятии с учета налогоплательщика (Дата снятия с учета)
	info_removal_register_NP_to_NO		Сведения о снятии с учета налогоплательщика (НО куда поставлен на учет)
	info_removal_register_NP_reason		Сведения о снятии с учета налогоплательщика (причина снятия)
**/
-- =============================================
CREATE PROCEDURE [dbo].[PR_UPDATE_REESTR_06]
	@lnk varchar(2)
AS
BEGIN
	EXEC ('

		DECLARE @id_check INT
		DECLARE @id_NP INT

		DECLARE reestr_cursor CURSOR FOR
			SELECT id_check, id_NP FROM arrears.dbo.arr_reestr_check WHERE code_NO=''86'+@lnk+'''
			
		OPEN reestr_cursor

		FETCH NEXT FROM reestr_cursor
			INTO @id_check, @id_NP
			
		WHILE @@FETCH_STATUS = 0
		BEGIN
			
			/* ****************************************************
				Сведения о снятии с учета налогоплательщика
			******************************************************* */	
			
			DECLARE @t_date DATE
			DECLARE @t_reason VARCHAR(250)
			DECLARE @t_code_NO varchar(4)
			SET @t_date = NULL
			SET @t_reason = NULL
			SET @t_code_NO = NULL
			
			IF EXISTS(SELECT 1 FROM [arrears].[dbo].[arr_reestr_check] WHERE [type_NP]=1 AND [id_check]=@id_check AND [code_NO]=''86'+@lnk+''')
			BEGIN
				
				SELECT 
					@t_date = tax1.N322,		-- дата снятия с учета
					@t_reason = FN86.N349,		-- причина снятия с учета	
					@t_code_NO = (case when (isnull(b.N279,'''') = '''' or b.N279 = ''0'') 
						and not exists(select 1 from Taxes'+@lnk+'.dbo.FN1508 where N1 = a.N1 
						and N348 in(11, 28, 203)) then '''' else b.N279 end) -- код НО
				FROM Taxes'+@lnk+'.dbo.FN212 a  
					join Taxes'+@lnk+'.dbo.FN210 on FN210.N1 = a.N1, Taxes'+@lnk+'.dbo.FN209 tax1
					left join Taxes'+@lnk+'.dbo.FN213 b on tax1.N314 = b.N314, Taxes'+@lnk+'.dbo.FN86 
				WHERE a.N350=0 and a.D428 not in (380,381) 
					and a.N1=tax1.N1 and a.N312=1 and tax1.N348=FN86.N348 
					and (tax1.N322 is not NULL) and substring(a.D3,5,2) in (''01'',''50'')
					and a.N1 = @id_NP
																		
			END
			ELSE
			BEGIN
				
				SELECT 
					@t_date = tax1.N322 		-- дата снятия с учета
				   ,@t_reason = FN86.N349		-- причина снятия с учета	
				   ,@t_code_NO = null
				FROM Taxes'+@lnk+'.dbo.FN212 a, Taxes'+@lnk+'.dbo.FN209 tax1, Taxes'+@lnk+'.dbo.FN86 
				WHERE a.N1=tax1.N1 and a.D428 not in (380,381) 
					and a.N312=2 and tax1.N348=FN86.N348 and (tax1.N322 is not NULL)
					and a.N1 = @id_NP							
				
			END
			
			IF ((@t_date IS NOT NULL) AND (@t_reason IS NOT NULL))
			BEGIN			
				UPDATE [arrears].[dbo].[arr_reestr_check] SET 
					[info_removal_register_NP_date]	  = @t_date
				   ,[info_removal_register_NP_reason] = @t_reason
				   ,[info_removal_register_NP_to_NO]  = @t_code_NO		  							
				WHERE [id_check]=@id_check AND [code_NO]=''86'+@lnk+'''	
			END	
			
							
		
		
			FETCH NEXT FROM reestr_cursor
			INTO @id_check, @id_NP
		END

		CLOSE reestr_cursor
		DEALLOCATE reestr_cursor')
	
END

GO
/****** Object:  StoredProcedure [dbo].[PR_UPDATE_REESTR_07]    Script Date: 26.02.2016 17:49:17 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		<Author,,Name>
-- Create date: <Create Date,,>
-- Description:	
/** Обновляемые реквизиты:

	current_proc_bankruptcy		Текущая процедура банкротства
	intro_date					Дата введения 
**/
-- =============================================
CREATE PROCEDURE [dbo].[PR_UPDATE_REESTR_07]
	@lnk varchar(2)
AS
BEGIN
	EXEC ('

		DECLARE @id_check INT
		DECLARE @id_NP INT
		
		DECLARE @t_current_proc_bankruptcy VARCHAR(250)
		DECLARE @t_intro_date DATE
		
		DECLARE reestr_cursor CURSOR FOR
			SELECT id_check, id_NP FROM arrears.dbo.arr_reestr_check WHERE code_NO=''86'+@lnk+'''
			
		OPEN reestr_cursor

		FETCH NEXT FROM reestr_cursor
			INTO @id_check, @id_NP
			
		WHILE @@FETCH_STATUS = 0
		BEGIN
			
			SET @t_current_proc_bankruptcy = NULL						
			SET @t_intro_date = NULL
			
			
			/* **************************************
				Текущая процедура банкротства
				Дата введения 
			***************************************** */
			
			SELECT
				@t_current_proc_bankruptcy = fn1373.d3762, -- текущая процедура банкротства
				@t_intro_date = bkr.d40_2	  -- дата введения
			FROM Taxes'+@lnk+'.dbo.FN212 a 
				left join (select fn212_02.n1, max(fn212_02.d3525) as d3525
					from Taxes'+@lnk+'.dbo.fn212_02 (nolock)
					group by fn212_02.n1
					) MaxBkr on MaxBkr.n1 = a.n1
				left join Taxes'+@lnk+'.dbo.fn212_02 bkr (nolock) on bkr.d3525 = MaxBkr.d3525
				left join Taxes'+@lnk+'.dbo.fn1373 (nolock) on fn1373.d09 = bkr.d09
			WHERE (D1811 = 1 and a.D428 not in (380, 381)) 
				AND ((a.N1 = @id_NP) -- УН лица
				AND (fn1373.d3762 IS NOT NULL) AND (bkr.d40_2 IS NOT NULL)) 
			GROUP BY fn1373.d3762, bkr.d40_2
			
			
			IF (@t_current_proc_bankruptcy IS NOT NULL)
			BEGIN
				UPDATE [arrears].[dbo].[arr_reestr_check] SET 
					[current_proc_bankruptcy] = @t_current_proc_bankruptcy
				WHERE [id_check]=@id_check AND [code_NO]=''86'+@lnk+'''	
			END
			
			IF (@t_intro_date IS NOT NULL)
			BEGIN
				UPDATE [arrears].[dbo].[arr_reestr_check] SET 
					[intro_date] = @t_intro_date
				WHERE [id_check]=@id_check AND [code_NO]=''86'+@lnk+'''	
			END
			
						
					
			FETCH NEXT FROM reestr_cursor
			INTO @id_check, @id_NP
		END

		CLOSE reestr_cursor
		DEALLOCATE reestr_cursor')
	
END

GO
/****** Object:  StoredProcedure [dbo].[PR_UPDATE_REESTR_08]    Script Date: 26.02.2016 17:49:17 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		<Author,,Name>
-- Create date: <Create Date,,>
-- Description:	
/** Обновляемые реквизиты:

	material_SLEDSTV_ORG_date		Материалы переданы в следственные органы по ст. 32 НК РФ (дата письма)
	material_SLEDSTV_ORG_num		Материалы переданы в следственные органы по ст. 32 НК РФ (номер письма)
**/
-- =============================================
CREATE PROCEDURE [dbo].[PR_UPDATE_REESTR_08]
	@lnk varchar(2)
AS
BEGIN
	EXEC ('

		DECLARE @id_check INT
		DECLARE @id_NP INT
		
		DECLARE @t_num VARCHAR(25)
		DECLARE @t_date DATE
		
		DECLARE reestr_cursor CURSOR FOR
			SELECT id_check, id_NP FROM arrears.dbo.arr_reestr_check WHERE code_NO=''86'+@lnk+'''
			
		OPEN reestr_cursor

		FETCH NEXT FROM reestr_cursor
			INTO @id_check, @id_NP
			
		WHILE @@FETCH_STATUS = 0
		BEGIN
									
			SET @t_num = NULL
			SET @t_date = NULL
			
			/* ************************************************************
				Материалы переданы в следственные органы по ст. 32 НК РФ
					Дата
					Номер
			*************************************************************** */
			
			
			SELECT 
				@t_date = b.N7010__2
			   ,@t_num = b.N7010__3 
			FROM Taxes'+@lnk+'.dbo.FN7020 a
				join (Taxes'+@lnk+'.dbo.FN1189 join Taxes'+@lnk+'.dbo.FN1190 on FN1189.D895 =  FN1190.D895
					join Taxes'+@lnk+'.dbo.FN74 sa on FN1190.N269 = sa.N269 and  sa.D686 = (select distinct nt_username from master..sysprocesses where spid=@@SPID)) on FN1189.D895 = a.D895
				left join Taxes'+@lnk+'.dbo.FN800 on FN800.N800__1=a.N800__1
				left join Taxes'+@lnk+'.dbo.FN1532 on FN1532.D270=FN800.D270_1
				left join Taxes'+@lnk+'.dbo.FN1532 Decision on Decision.D270=FN800.D270_2
				left join (Taxes'+@lnk+'.dbo.FN1534 join Taxes'+@lnk+'.dbo.FN1532 IncDoc on IncDoc.D270=FN1534.D270_2) on FN1534.D270=a.D270
				left join Taxes'+@lnk+'.dbo.FN1532 DeclFace on DeclFace.D270=a.D270
				left join Taxes'+@lnk+'.dbo.FN212 on FN212.N1=case when FN800.N1_1 is Null then IsNull(IsNull(FN1534.N1,DeclFace.N1),a.N1_3) else FN800.N1_1 end
				left join Taxes'+@lnk+'.dbo.FN213 on FN213.N314=FN212.N314
				left join (Taxes'+@lnk+'.dbo.FN212 OVD left join Taxes'+@lnk+'.dbo.FN213 OVDAddr on OVDAddr.N314=OVD.N314) on OVD.N1=a.N1_1
				left join Taxes'+@lnk+'.dbo.FN71 GNI on GNI.N1=a.N1_2
				join (Taxes'+@lnk+'.dbo.FN7010 b join Taxes'+@lnk+'.dbo.FN7000 on FN7000.N7000__1=b.N7000__1) on b.N7020__1=a.N7020__1
				left join Taxes'+@lnk+'.dbo.FN7030 State2 on State2.N7010__1=b.N7010__1 and State2.N7001__1=2
				left join Taxes'+@lnk+'.dbo.FN212 SPer on SPer.N1=b.N1_1		
				left join Taxes'+@lnk+'.dbo.FN74 d on d.N269=b.N269
				left join (Taxes'+@lnk+'.dbo.FN74 e join Taxes'+@lnk+'.dbo.FN73 on FN73.N277=e.N277
							  join Taxes'+@lnk+'.dbo.FN1050 on FN1050.D15=e.D15) on e.N269=b.N269_1
				left join Taxes'+@lnk+'.dbo.FN74 f on f.N269=b.N269_11
				left join Taxes'+@lnk+'.dbo.FN74 g on g.N269=b.N269_7
				left join Taxes'+@lnk+'.dbo.FN74 h on h.N269=b.N269_12
				left join Taxes'+@lnk+'.dbo.FN74 i on i.N269=b.N269_15
						left join Taxes'+@lnk+'.dbo.fn7010 z on (b.n7020__1 = z.n7020__1) and (b.n7000__1 = 6) and (z.n7000__1 = 16) 
						left join Taxes'+@lnk+'.dbo.fn4411 q on q.N4411__1 = a.N4411__1
				left join Taxes'+@lnk+'.dbo.FN212 FN212KA on FN212KA.N1=a.N1_4
						left join Taxes'+@lnk+'.dbo.FN7244 p on p.N7244__1=b.N7244__1
						left join Taxes'+@lnk+'.dbo.FN7246 tt on tt.N7246__1 = b.N7246__1
						left join Taxes'+@lnk+'.dbo.FN7245 ttt on ttt.N7245__1 = b.N7245__1
			WHERE ( b.N7010__34 is Null) AND ((b.N7000__1 = 1) 
				AND (a.N800__1 IN (@id_check))) 
			GROUP BY b.N7010__2, b.N7010__3 
			
			
			IF (@t_date IS NOT NULL)
			BEGIN						
				UPDATE [arrears].[dbo].[arr_reestr_check] SET 
					[material_SLEDSTV_ORG_date] = @t_date -- дата документа				   		
				WHERE [id_check]=@id_check AND [code_NO]=''86'+@lnk+'''
			END
			
			IF (@t_num IS NOT NULL)
			BEGIN						
				UPDATE [arrears].[dbo].[arr_reestr_check] SET 
					[material_SLEDSTV_ORG_num]  = @t_num -- номер документа				
				WHERE [id_check]=@id_check AND [code_NO]=''86'+@lnk+'''
			END
			
		
			FETCH NEXT FROM reestr_cursor
			INTO @id_check, @id_NP
		END

		CLOSE reestr_cursor
		DEALLOCATE reestr_cursor')
	
END

GO
/****** Object:  StoredProcedure [dbo].[PR_UPDATE_REESTR_09]    Script Date: 26.02.2016 17:49:17 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		<Author,,Name>
-- Create date: <Create Date,,>
-- Description:	
/** Обновляемые реквизиты:

	material_SLEDSTV_ORG_article		Материалы переданы в следственные органы по ст. 32 НК РФ (ст. УК РФ)
**/
-- =============================================
CREATE PROCEDURE [dbo].[PR_UPDATE_REESTR_09]
	@lnk varchar(2)
AS
BEGIN
	EXEC ('

		DECLARE @id_check INT
		DECLARE @id_reestr INT				
		
		DECLARE reestr_cursor CURSOR FOR
			SELECT id_check, id FROM arrears.dbo.arr_reestr_check WHERE code_NO=''86'+@lnk+'''
			
		OPEN reestr_cursor

		FETCH NEXT FROM reestr_cursor
			INTO @id_check, @id_reestr
			
		WHILE @@FETCH_STATUS = 0
		BEGIN
									
			
			/* ************************************************************
				Материалы переданы в следственные органы по ст. 32 НК РФ
				- Статья
			*************************************************************** */
			
			INSERT INTO arrears.dbo.arr_reestr_check_article ([id_reestr],[id_directory_article],[field_name])
			
			SELECT A.id_reestr, A.article, A.field_name FROM 
			(		
				SELECT 
					@id_reestr [id_reestr]
				   ,CASE 
						WHEN FN724.N724__5 = 1  THEN 5
						WHEN FN724.N724__5 = 2  THEN 6
						WHEN FN724.N724__5 = 11 THEN 7
						WHEN FN724.N724__5 = 12 THEN 8
					END [article]
				   ,''material_SLEDSTV_ORG_article'' [field_name]
				FROM Taxes'+@lnk+'.dbo.FN7020 a
					join (Taxes'+@lnk+'.dbo.FN1189 join Taxes'+@lnk+'.dbo.FN1190 on FN1189.D895 =  FN1190.D895
						join Taxes'+@lnk+'.dbo.FN74 sa on FN1190.N269 = sa.N269 and  sa.D686 = (select distinct nt_username from master..sysprocesses where spid=@@SPID)) on FN1189.D895 = a.D895
					left join Taxes'+@lnk+'.dbo.FN800 on FN800.N800__1=a.N800__1
					left join Taxes'+@lnk+'.dbo.FN1532 on FN1532.D270=FN800.D270_1
					left join Taxes'+@lnk+'.dbo.FN1532 Decision on Decision.D270=FN800.D270_2
					left join (Taxes'+@lnk+'.dbo.FN1534 join Taxes'+@lnk+'.dbo.FN1532 IncDoc on IncDoc.D270=FN1534.D270_2) on FN1534.D270=a.D270
					left join Taxes'+@lnk+'.dbo.FN1532 DeclFace on DeclFace.D270=a.D270
					left join Taxes'+@lnk+'.dbo.FN212 on FN212.N1=case when FN800.N1_1 is Null then IsNull(IsNull(FN1534.N1,DeclFace.N1),a.N1_3) else FN800.N1_1 end
					left join Taxes'+@lnk+'.dbo.FN213 on FN213.N314=FN212.N314
					left join (Taxes'+@lnk+'.dbo.FN212 OVD left join Taxes'+@lnk+'.dbo.FN213 OVDAddr on OVDAddr.N314=OVD.N314) on OVD.N1=a.N1_1
					left join Taxes'+@lnk+'.dbo.FN71 GNI on GNI.N1=a.N1_2
					join (Taxes'+@lnk+'.dbo.FN7025 join Taxes'+@lnk+'.dbo.FN724 on FN724.N724__1=FN7025.N724__1) on FN7025.N7020__1=a.N7020__1
					left join Taxes'+@lnk+'.dbo.FN74 c on c.N269=a.N2691
					left join Taxes'+@lnk+'.dbo.FN74 d on d.N269=a.N269_2
					left join Taxes'+@lnk+'.dbo.FN74 e on e.N269=a.N269
								left join Taxes'+@lnk+'.dbo.fn4411 q on q.N4411__1 = a.N4411__1
					left join Taxes'+@lnk+'.dbo.FN212 FN212KA on FN212KA.N1=a.N1_4
				WHERE (a.N7020__4 is NULL and (FN1189.D40<=GetDate() OR FN1189.D40 is NULL ) AND (FN1189.D41>=GetDate() OR FN1189.D41 is NULL) and (FN1190.D40<=GetDate() OR FN1190.D40 is NULL ) AND (FN1190.D41>=GetDate() OR FN1190.D41 is NULL)) 
					AND ((a.N800__1 IN (@id_check)))
				GROUP BY FN724.N724__5, FN724.N724__2
			) A
			WHERE A.article IS NOT NULL
				
													
			FETCH NEXT FROM reestr_cursor
				INTO @id_check, @id_reestr
		END

		CLOSE reestr_cursor
		DEALLOCATE reestr_cursor')
	
END

GO
/****** Object:  StoredProcedure [dbo].[PR_UPDATE_REESTR_10]    Script Date: 26.02.2016 17:49:17 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		<Author,,Name>
-- Create date: <Create Date,,>
-- Description:	
/** Обновляемые реквизиты:

	result_see_SLEDSTV_ORG_filed_article -		
		Результат рассмотрения следственными органами материалов 
			налоговых проверок (возбуждено УД) (статья УК РФ)
**/
-- =============================================
CREATE PROCEDURE [dbo].[PR_UPDATE_REESTR_10]
	@lnk varchar(2)
AS
BEGIN
	EXEC ('
	
		DECLARE @id_check INT
		
		DECLARE reestr_cursor CURSOR FOR
			SELECT id_check FROM arrears.dbo.arr_reestr_check WHERE code_NO=''86'+@lnk+'''
			
		OPEN reestr_cursor

		FETCH NEXT FROM reestr_cursor
			INTO @id_check
			
		WHILE @@FETCH_STATUS = 0
		BEGIN
									
			
			/* ************************************************************
				Результат рассмотрения следственными органами материалов 
				налоговых проверок (возбуждено УД) (статья УК РФ)
				- Статья
			*************************************************************** */
			
			DECLARE @article int
			SET @article = null
			
			SELECT TOP 1 @article =
				case 
					when f25.n724__1 = 1  then 5
					when f25.n724__1 = 2  then 6
					when f25.n724__1 = 11 then 7
					when f25.n724__1 = 12 then 8
				end 
			FROM Taxes'+@lnk+'.dbo.FN7020 a
				join (Taxes'+@lnk+'.dbo.FN1189 join Taxes'+@lnk+'.dbo.FN1190 on FN1189.D895 =  FN1190.D895
											  join Taxes'+@lnk+'.dbo.FN74 sa on FN1190.N269 = sa.N269 and  sa.D686 = (select distinct nt_username from master..sysprocesses where spid=@@SPID)) on FN1189.D895 = a.D895
				left join Taxes'+@lnk+'.dbo.FN800 on FN800.N800__1=a.N800__1
				left join Taxes'+@lnk+'.dbo.FN1532 on FN1532.D270=FN800.D270_1
				left join Taxes'+@lnk+'.dbo.FN1532 Decision on Decision.D270=FN800.D270_2
				left join (Taxes'+@lnk+'.dbo.FN1534 join Taxes'+@lnk+'.dbo.FN1532 IncDoc on IncDoc.D270=FN1534.D270_2) on FN1534.D270=a.D270
				left join Taxes'+@lnk+'.dbo.FN1532 DeclFace on DeclFace.D270=a.D270
				left join Taxes'+@lnk+'.dbo.FN212 on FN212.N1=case when FN800.N1_1 is Null then IsNull(IsNull(FN1534.N1,DeclFace.N1),a.N1_3) else FN800.N1_1 end
				left join Taxes'+@lnk+'.dbo.FN213 on FN213.N314=FN212.N314
				left join (Taxes'+@lnk+'.dbo.FN212 OVD left join Taxes'+@lnk+'.dbo.FN213 OVDAddr on OVDAddr.N314=OVD.N314) on OVD.N1=a.N1_1
				left join Taxes'+@lnk+'.dbo.FN71 GNI on GNI.N1=a.N1_2
				join Taxes'+@lnk+'.dbo.FN7010 b on b.N7020__1=a.N7020__1
				join Taxes'+@lnk+'.dbo.FN7000 on FN7000.N7000__1=b.N7000__1
				left join Taxes'+@lnk+'.dbo.FN7010 Req on Req.N7010__1=b.N7010__1_2
				left join Taxes'+@lnk+'.dbo.FN7030 State2 on State2.N7010__1=b.N7010__1 and State2.N7001__1=2
				left join Taxes'+@lnk+'.dbo.FN74 c on c.N269=a.N2691
				left join Taxes'+@lnk+'.dbo.FN74 d on d.N269=a.N269_2
				left join (Taxes'+@lnk+'.dbo.FN74 e  join Taxes'+@lnk+'.dbo.FN73 on FN73.N277=e.N277
							   join Taxes'+@lnk+'.dbo.FN1050 on FN1050.D15=e.D15) on e.N269=b.N269_1
				left join Taxes'+@lnk+'.dbo.FN74 i on i.N269=b.N269_15
							left join Taxes'+@lnk+'.dbo.fn7025 f25 on b.n7010__1 = f25.n7010__1
							left join Taxes'+@lnk+'.dbo.fn724 f24 on f25.N724__1 = f24.N724__1
							left join Taxes'+@lnk+'.dbo.fn4411 q on q.N4411__1 = a.N4411__1
				left join Taxes'+@lnk+'.dbo.FN212 FN212KA on FN212KA.N1=a.N1_4
							left join Taxes'+@lnk+'.dbo.FN7246 tt on tt.N7246__1 = b.N7246__1
			WHERE ( b.N7000__1 in (5,22) and (FN1189.D40<=GetDate() OR FN1189.D40 is NULL ) 
				AND (FN1189.D41>=GetDate() OR FN1189.D41 is NULL) 
				and (FN1190.D40<=GetDate() OR FN1190.D40 is NULL ) 
				AND (FN1190.D41>=GetDate() OR FN1190.D41 is NULL)) 
				AND ((a.N800__1 IN (@id_check))) 
				AND f25.n724__1 IN (1,2,11,12)
			GROUP BY f25.n724__1
			
			IF (@article IS NOT NULL)
			BEGIN
				UPDATE [arrears].[dbo].[arr_reestr_check] SET 
					[result_see_SLEDSTV_ORG_filed_article] = @article
				WHERE [id_check]=@id_check AND [code_NO]=''86'+@lnk+'''	
			END
													
			FETCH NEXT FROM reestr_cursor INTO @id_check
		END

		CLOSE reestr_cursor
		DEALLOCATE reestr_cursor')
	
END

GO
/****** Object:  StoredProcedure [dbo].[PR_UPDATE_REESTR_10_OLD]    Script Date: 26.02.2016 17:49:17 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		<Author,,Name>
-- Create date: <Create Date,,>
-- Description:	
/** Обновляемые реквизиты:

	result_see_SLEDSTV_ORG_filed_article -		
		Результат рассмотрения следственными органами материалов 
			налоговых проверок (возбуждено УД) (статья УК РФ)
**/
-- =============================================
CREATE PROCEDURE [dbo].[PR_UPDATE_REESTR_10_OLD]
	@lnk varchar(2)
AS
BEGIN
	EXEC ('

		DECLARE @id_check INT
		DECLARE @id_reestr INT				
		
		DECLARE reestr_cursor CURSOR FOR
			SELECT id_check, id FROM arrears.dbo.arr_reestr_check WHERE code_NO=''86'+@lnk+'''
			
		OPEN reestr_cursor

		FETCH NEXT FROM reestr_cursor
			INTO @id_check, @id_reestr
			
		WHILE @@FETCH_STATUS = 0
		BEGIN
									
			
			/* ************************************************************
				Результат рассмотрения следственными органами материалов 
				налоговых проверок (возбуждено УД) (статья УК РФ)
				- Статья
			*************************************************************** */
			
			INSERT INTO arrears.dbo.arr_reestr_check_article ([id_reestr],[id_directory_article],[field_name])
			
			SELECT A.id_reestr, A.article, A.field_name FROM 
			(	
			
				SELECT 
					@id_reestr [id_reestr]
				   ,CASE 
						WHEN f25.n724__1 = 1  THEN 5
						WHEN f25.n724__1 = 2  THEN 6
						WHEN f25.n724__1 = 11 THEN 7
						WHEN f25.n724__1 = 12 THEN 8
					END [article]
				   ,''result_see_SLEDSTV_ORG_filed_article'' [field_name]										 					
				FROM Taxes'+@lnk+'.dbo.FN7020 a
					join (Taxes'+@lnk+'.dbo.FN1189 join Taxes'+@lnk+'.dbo.FN1190 on FN1189.D895 =  FN1190.D895
						join Taxes'+@lnk+'.dbo.FN74 sa on FN1190.N269 = sa.N269 and  sa.D686 = (select distinct nt_username from master..sysprocesses where spid=@@SPID)) on FN1189.D895 = a.D895
					left join Taxes'+@lnk+'.dbo.FN800 on FN800.N800__1=a.N800__1
					left join Taxes'+@lnk+'.dbo.FN1532 on FN1532.D270=FN800.D270_1
					left join Taxes'+@lnk+'.dbo.FN1532 Decision on Decision.D270=FN800.D270_2
					left join (Taxes'+@lnk+'.dbo.FN1534 join Taxes'+@lnk+'.dbo.FN1532 IncDoc on IncDoc.D270=FN1534.D270_2) on FN1534.D270=a.D270
					left join Taxes'+@lnk+'.dbo.FN1532 DeclFace on DeclFace.D270=a.D270
					left join Taxes'+@lnk+'.dbo.FN212 on FN212.N1=case when FN800.N1_1 is Null then IsNull(IsNull(FN1534.N1,DeclFace.N1),a.N1_3) else FN800.N1_1 end
					left join Taxes'+@lnk+'.dbo.FN213 on FN213.N314=FN212.N314
					left join (Taxes'+@lnk+'.dbo.FN212 OVD left join Taxes'+@lnk+'.dbo.FN213 OVDAddr on OVDAddr.N314=OVD.N314) on OVD.N1=a.N1_1
					left join Taxes'+@lnk+'.dbo.FN71 GNI on GNI.N1=a.N1_2
					join Taxes'+@lnk+'.dbo.FN7010 b on b.N7020__1=a.N7020__1
					join Taxes'+@lnk+'.dbo.FN7000 on FN7000.N7000__1=b.N7000__1
					left join Taxes'+@lnk+'.dbo.FN7010 Req on Req.N7010__1=b.N7010__1_2
					left join Taxes'+@lnk+'.dbo.FN7030 State2 on State2.N7010__1=b.N7010__1 and State2.N7001__1=2
					left join Taxes'+@lnk+'.dbo.FN74 c on c.N269=a.N2691
					left join Taxes'+@lnk+'.dbo.FN74 d on d.N269=a.N269_2
					left join (Taxes'+@lnk+'.dbo.FN74 e  join Taxes'+@lnk+'.dbo.FN73 on FN73.N277=e.N277
								   join Taxes'+@lnk+'.dbo.FN1050 on FN1050.D15=e.D15) on e.N269=b.N269_1
					left join Taxes'+@lnk+'.dbo.FN74 i on i.N269=b.N269_15
								left join Taxes'+@lnk+'.dbo.fn7025 f25 on b.n7010__1 = f25.n7010__1
								left join Taxes'+@lnk+'.dbo.fn724 f24 on f25.N724__1 = f24.N724__1
								left join Taxes'+@lnk+'.dbo.fn4411 q on q.N4411__1 = a.N4411__1
					left join Taxes'+@lnk+'.dbo.FN212 FN212KA on FN212KA.N1=a.N1_4
								left join Taxes'+@lnk+'.dbo.FN7246 tt on tt.N7246__1 = b.N7246__1
				 WHERE ( b.N7000__1 in (5,22) and (FN1189.D40<=GetDate() OR FN1189.D40 is NULL ) AND (FN1189.D41>=GetDate() OR FN1189.D41 is NULL) and (FN1190.D40<=GetDate() OR FN1190.D40 is NULL ) AND (FN1190.D41>=GetDate() OR FN1190.D41 is NULL)) 
					AND ((a.N800__1 IN (@id_check))) GROUP BY f25.n724__1 
																			
			) A
			WHERE A.article IS NOT NULL
				
													
			FETCH NEXT FROM reestr_cursor
				INTO @id_check, @id_reestr
		END

		CLOSE reestr_cursor
		DEALLOCATE reestr_cursor')
	
END

GO
/****** Object:  StoredProcedure [dbo].[PR_UPDATE_REESTR_11]    Script Date: 26.02.2016 17:49:17 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		<Author,,Name>
-- Create date: <Create Date,,>
-- Description:	
/** Обновляемые реквизиты:

	result_see_SLEDSTV_ORG_filed_date	Результат рассмотрения следственными органами материалов налоговых проверок (возбуждено УД) (дата)
	result_see_SLEDSTV_ORG_filed_num	Результат рассмотрения следственными органами материалов налоговых проверок (возбуждено УД) (номер)

**/
-- =============================================
CREATE PROCEDURE [dbo].[PR_UPDATE_REESTR_11]
	@lnk varchar(2)
AS
BEGIN
	EXEC ('

		DECLARE @id_check INT
		DECLARE @id_NP INT
		
		DECLARE @t_num VARCHAR(25)
		DECLARE @t_date DATE
		
		DECLARE reestr_cursor CURSOR FOR
			SELECT id_check, id_NP FROM arrears.dbo.arr_reestr_check WHERE code_NO=''86'+@lnk+'''
			
		OPEN reestr_cursor

		FETCH NEXT FROM reestr_cursor
			INTO @id_check, @id_NP
			
		WHILE @@FETCH_STATUS = 0
		BEGIN
									
			SET @t_date = NULL
			SET @t_num = NULL
			
			/* **************************************
				Результат рассмотрения следственными органами 
					материалов налоговых проверок. Возбуждено УД. 
				- Дата 
				- Номер
			***************************************** */
			
			SELECT TOP 1 
				@t_date = b.N7010__2
			   ,@t_num = b.N7010__3 
			FROM Taxes'+@lnk+'.dbo.FN7020 a
				join (Taxes'+@lnk+'.dbo.FN1189 join Taxes'+@lnk+'.dbo.FN1190 on FN1189.D895 =  FN1190.D895
					join Taxes'+@lnk+'.dbo.FN74 sa on FN1190.N269 = sa.N269 and  sa.D686 = (select distinct nt_username from master..sysprocesses where spid=@@SPID)) on FN1189.D895 = a.D895
				left join Taxes'+@lnk+'.dbo.FN800 on FN800.N800__1=a.N800__1
				left join Taxes'+@lnk+'.dbo.FN1532 on FN1532.D270=FN800.D270_1
				left join Taxes'+@lnk+'.dbo.FN1532 Decision on Decision.D270=FN800.D270_2
				left join (Taxes'+@lnk+'.dbo.FN1534 join Taxes'+@lnk+'.dbo.FN1532 IncDoc on IncDoc.D270=FN1534.D270_2) on FN1534.D270=a.D270
				left join Taxes'+@lnk+'.dbo.FN1532 DeclFace on DeclFace.D270=a.D270
				left join Taxes'+@lnk+'.dbo.FN212 on FN212.N1=case when FN800.N1_1 is Null then IsNull(IsNull(FN1534.N1,DeclFace.N1),a.N1_3) else FN800.N1_1 end
				left join Taxes'+@lnk+'.dbo.FN213 on FN213.N314=FN212.N314
				left join (Taxes'+@lnk+'.dbo.FN212 OVD left join Taxes'+@lnk+'.dbo.FN213 OVDAddr on OVDAddr.N314=OVD.N314) on OVD.N1=a.N1_1
				left join Taxes'+@lnk+'.dbo.FN71 GNI on GNI.N1=a.N1_2
				join (Taxes'+@lnk+'.dbo.FN7010 b join Taxes'+@lnk+'.dbo.FN7000 on FN7000.N7000__1=b.N7000__1) on b.N7020__1=a.N7020__1
				left join Taxes'+@lnk+'.dbo.FN7030 State2 on State2.N7010__1=b.N7010__1 and State2.N7001__1=2
				left join Taxes'+@lnk+'.dbo.FN212 SPer on SPer.N1=b.N1_1		
				left join Taxes'+@lnk+'.dbo.FN74 d on d.N269=b.N269
				left join (Taxes'+@lnk+'.dbo.FN74 e 
					join Taxes'+@lnk+'.dbo.FN73 on FN73.N277=e.N277
					join Taxes'+@lnk+'.dbo.FN1050 on FN1050.D15=e.D15) on e.N269=b.N269_1
				left join Taxes'+@lnk+'.dbo.FN74 f on f.N269=b.N269_11
				left join Taxes'+@lnk+'.dbo.FN74 g on g.N269=b.N269_7
				left join Taxes'+@lnk+'.dbo.FN74 h on h.N269=b.N269_12
				left join Taxes'+@lnk+'.dbo.FN74 i on i.N269=b.N269_15
							left join Taxes'+@lnk+'.dbo.fn7010 z on (b.n7020__1 = z.n7020__1) and (b.n7000__1 = 6) and (z.n7000__1 = 16) 
							left join Taxes'+@lnk+'.dbo.fn4411 q on q.N4411__1 = a.N4411__1
				left join Taxes'+@lnk+'.dbo.FN212 FN212KA on FN212KA.N1=a.N1_4
							left join Taxes'+@lnk+'.dbo.FN7244 p on p.N7244__1=b.N7244__1
							left join Taxes'+@lnk+'.dbo.FN7246 tt on tt.N7246__1 = b.N7246__1
							left join Taxes'+@lnk+'.dbo.FN7245 ttt on ttt.N7245__1 = b.N7245__1
			WHERE ( b.N7010__34 is Null) AND ((b.N7000__1 = 22) 
				AND (a.N800__1 IN (@id_check))) GROUP BY b.N7010__2, b.N7010__3
			
			
			IF (@t_num IS NOT NULL)
			BEGIN
				UPDATE [arrears].[dbo].[arr_reestr_check] SET 
					[result_see_SLEDSTV_ORG_filed_num] = @t_num
				WHERE [id_check]=@id_check AND [code_NO]=''86'+@lnk+'''	
			END
			
			IF (@t_date IS NOT NULL)
			BEGIN
				UPDATE [arrears].[dbo].[arr_reestr_check] SET 
					[result_see_SLEDSTV_ORG_filed_date] = @t_date
				WHERE [id_check]=@id_check AND [code_NO]=''86'+@lnk+'''	
			END
			
						
					
			FETCH NEXT FROM reestr_cursor
			INTO @id_check, @id_NP
		END

		CLOSE reestr_cursor
		DEALLOCATE reestr_cursor')
	
END

GO
/****** Object:  StoredProcedure [dbo].[PR_UPDATE_REESTR_12]    Script Date: 26.02.2016 17:49:17 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		<Author,,Name>
-- Create date: <Create Date,,>
-- Description:	
/** Обновляемые реквизиты:

	result_see_SLEDSTV_ORG_refused_date	-
		Результат рассмотрения следственными органами материалов 
		налоговых проверок  (отказано в возбуждении УД) (дата)

**/
-- =============================================
CREATE PROCEDURE [dbo].[PR_UPDATE_REESTR_12]
	@lnk varchar(2)
AS
BEGIN
	EXEC ('

		DECLARE @id_check INT
		DECLARE @id_NP INT
				
		DECLARE @t_date DATE
		
		DECLARE reestr_cursor CURSOR FOR
			SELECT id_check, id_NP FROM arrears.dbo.arr_reestr_check WHERE code_NO=''86'+@lnk+'''
			
		OPEN reestr_cursor

		FETCH NEXT FROM reestr_cursor
			INTO @id_check, @id_NP
			
		WHILE @@FETCH_STATUS = 0
		BEGIN
									
			SET @t_date = NULL
			
			/* **************************************
				Результат рассмотрения следственными органами 
					материалов налоговых проверок  
					(отказано в возбуждении УД) (дата) 
				- Дата 				
			***************************************** */
			
			SELECT	
				@t_date = b.N7010__2 
			FROM  Taxes'+@lnk+'.dbo.FN7020 a
				join ( Taxes'+@lnk+'.dbo.FN1189 join  Taxes'+@lnk+'.dbo.FN1190 on FN1189.D895 =  FN1190.D895
											  join  Taxes'+@lnk+'.dbo.FN74 sa on FN1190.N269 = sa.N269 and  sa.D686 = (select distinct nt_username from master..sysprocesses where spid=@@SPID)) on FN1189.D895 = a.D895
				left join  Taxes'+@lnk+'.dbo.FN800 on FN800.N800__1=a.N800__1
				left join  Taxes'+@lnk+'.dbo.FN1532 on FN1532.D270=FN800.D270_1
				left join  Taxes'+@lnk+'.dbo.FN1532 Decision on Decision.D270=FN800.D270_2
				left join ( Taxes'+@lnk+'.dbo.FN1534 join  Taxes'+@lnk+'.dbo.FN1532 IncDoc on IncDoc.D270=FN1534.D270_2) on FN1534.D270=a.D270
				left join  Taxes'+@lnk+'.dbo.FN1532 DeclFace on DeclFace.D270=a.D270
				left join  Taxes'+@lnk+'.dbo.FN212 on FN212.N1=case when FN800.N1_1 is Null then IsNull(IsNull(FN1534.N1,DeclFace.N1),a.N1_3) else FN800.N1_1 end
				left join  Taxes'+@lnk+'.dbo.FN213 on FN213.N314=FN212.N314
				left join ( Taxes'+@lnk+'.dbo.FN212 OVD left join  Taxes'+@lnk+'.dbo.FN213 OVDAddr on OVDAddr.N314=OVD.N314) on OVD.N1=a.N1_1
				left join  Taxes'+@lnk+'.dbo.FN71 GNI on GNI.N1=a.N1_2
				join ( Taxes'+@lnk+'.dbo.FN7010 b join  Taxes'+@lnk+'.dbo.FN7000 on FN7000.N7000__1=b.N7000__1) on b.N7020__1=a.N7020__1
				left join  Taxes'+@lnk+'.dbo.FN7030 State2 on State2.N7010__1=b.N7010__1 and State2.N7001__1=2
				left join  Taxes'+@lnk+'.dbo.FN212 SPer on SPer.N1=b.N1_1		
				left join  Taxes'+@lnk+'.dbo.FN74 d on d.N269=b.N269
				left join ( Taxes'+@lnk+'.dbo.FN74 e join  Taxes'+@lnk+'.dbo.FN73 on FN73.N277=e.N277
							  join  Taxes'+@lnk+'.dbo.FN1050 on FN1050.D15=e.D15) on e.N269=b.N269_1
				left join  Taxes'+@lnk+'.dbo.FN74 f on f.N269=b.N269_11
				left join  Taxes'+@lnk+'.dbo.FN74 g on g.N269=b.N269_7
				left join  Taxes'+@lnk+'.dbo.FN74 h on h.N269=b.N269_12
				left join  Taxes'+@lnk+'.dbo.FN74 i on i.N269=b.N269_15
							left join  Taxes'+@lnk+'.dbo.fn7010 z on (b.n7020__1 = z.n7020__1) and (b.n7000__1 = 6) and (z.n7000__1 = 16) 
							left join  Taxes'+@lnk+'.dbo.fn4411 q on q.N4411__1 = a.N4411__1
				left join  Taxes'+@lnk+'.dbo.FN212 FN212KA on FN212KA.N1=a.N1_4
							left join  Taxes'+@lnk+'.dbo.FN7244 p on p.N7244__1=b.N7244__1
							left join  Taxes'+@lnk+'.dbo.FN7246 tt on tt.N7246__1 = b.N7246__1
							left join  Taxes'+@lnk+'.dbo.FN7245 ttt on ttt.N7245__1 = b.N7245__1

			 WHERE (b.N7010__34 is Null) AND ((b.N7000__1 = 18) AND (a.N800__1 IN (@id_check))) 
			 GROUP BY b.N7010__2  
								
			
			IF (@t_date IS NOT NULL)
			BEGIN
				UPDATE [arrears].[dbo].[arr_reestr_check] SET 
					[result_see_SLEDSTV_ORG_refused_date] = @t_date
				WHERE [id_check]=@id_check AND [code_NO]=''86'+@lnk+'''	
			END		
						
					
			FETCH NEXT FROM reestr_cursor
			INTO @id_check, @id_NP
		END

		CLOSE reestr_cursor
		DEALLOCATE reestr_cursor')
	
END

GO
/****** Object:  StoredProcedure [dbo].[PR_UPDATE_REESTR_13]    Script Date: 26.02.2016 17:49:17 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		<Author,,Name>
-- Create date: <Create Date,,>
-- Description:	
/** Обновляемые реквизиты:

	result_see_SLEDSTV_ORG_refused_article	-
		Результат рассмотрения следственными органами материалов 
		налоговых проверок (отказано в возбуждении УД) (причина отказа)

**/
-- =============================================
CREATE PROCEDURE [dbo].[PR_UPDATE_REESTR_13]
	@lnk varchar(2)
AS
BEGIN
	EXEC ('

		DECLARE @id_check INT
		DECLARE @id_NP INT
				
		DECLARE @t_text VARCHAR(250)
		
		DECLARE reestr_cursor CURSOR FOR
			SELECT id_check, id_NP FROM arrears.dbo.arr_reestr_check WHERE code_NO=''86'+@lnk+'''
			
		OPEN reestr_cursor

		FETCH NEXT FROM reestr_cursor
			INTO @id_check, @id_NP
			
		WHILE @@FETCH_STATUS = 0
		BEGIN
			
			SET @t_text = NULL						
			
			/* *****************************************
				Результат рассмотрения следственными 
				органами материалов налоговых проверок  
				(отказано в возбуждении УД) 
				(причина отказа)		
			******************************************** */
			
			SELECT TOP 1
				@t_text = f41.N7241__2 
			FROM Taxes'+@lnk+'.dbo.FN7020 a
				join (Taxes'+@lnk+'.dbo.FN1189 join Taxes'+@lnk+'.dbo.FN1190 on FN1189.D895 =  FN1190.D895
											  join Taxes'+@lnk+'.dbo.FN74 sa on FN1190.N269 = sa.N269 and  sa.D686 = (select distinct nt_username from master..sysprocesses where spid=@@SPID)) on FN1189.D895 = a.D895
				left join Taxes'+@lnk+'.dbo.FN800 on FN800.N800__1=a.N800__1
				left join Taxes'+@lnk+'.dbo.FN1532 on FN1532.D270=FN800.D270_1
				left join Taxes'+@lnk+'.dbo.FN1532 Decision on Decision.D270=FN800.D270_2
				left join (Taxes'+@lnk+'.dbo.FN1534 join Taxes'+@lnk+'.dbo.FN1532 IncDoc on IncDoc.D270=FN1534.D270_2) on FN1534.D270=a.D270
				left join Taxes'+@lnk+'.dbo.FN1532 DeclFace on DeclFace.D270=a.D270
				left join Taxes'+@lnk+'.dbo.FN212 on FN212.N1=case when FN800.N1_1 is Null then IsNull(IsNull(FN1534.N1,DeclFace.N1),a.N1_3) else FN800.N1_1 end
				left join Taxes'+@lnk+'.dbo.FN213 on FN213.N314=FN212.N314
				left join (Taxes'+@lnk+'.dbo.FN212 OVD left join Taxes'+@lnk+'.dbo.FN213 OVDAddr on OVDAddr.N314=OVD.N314) on OVD.N1=a.N1_1
				left join Taxes'+@lnk+'.dbo.FN71 GNI on GNI.N1=a.N1_2
				join (Taxes'+@lnk+'.dbo.FN7010 b join Taxes'+@lnk+'.dbo.FN7000 on FN7000.N7000__1=b.N7000__1) on b.N7020__1=a.N7020__1
				left join Taxes'+@lnk+'.dbo.FN7010V Req on Req.N7010__1=b.N7010__1_2
				left join Taxes'+@lnk+'.dbo.FN7030 State2 on State2.N7010__1=b.N7010__1 and State2.N7001__1=2
				left join Taxes'+@lnk+'.dbo.FN212 SPer on SPer.N1=b.N1_1
							left join Taxes'+@lnk+'.dbo.FN74 c on c.N269=a.N2691		
				left join Taxes'+@lnk+'.dbo.FN74 d on d.N269=b.N269
				left join (Taxes'+@lnk+'.dbo.FN74 e join Taxes'+@lnk+'.dbo.FN73 on FN73.N277=e.N277
							  join Taxes'+@lnk+'.dbo.FN1050 on FN1050.D15=e.D15) on e.N269=b.N269_1
				left join Taxes'+@lnk+'.dbo.FN74 f on f.N269=b.N269_11
				left join Taxes'+@lnk+'.dbo.FN74 g on g.N269=b.N269_7
				left join Taxes'+@lnk+'.dbo.FN74 h on h.N269=b.N269_12
				left join Taxes'+@lnk+'.dbo.FN74 i on i.N269=b.N269_15
							left join Taxes'+@lnk+'.dbo.fn7010 z on (b.n7020__1 = z.n7020__1) and (b.n7000__1 = 6) and (z.n7000__1 = 16) 
							left join Taxes'+@lnk+'.dbo.fn7026 f26 on b.n7010__1 = f26.n7010__1 
							left join Taxes'+@lnk+'.dbo.fn7241 f41 on f26.N7241__1 = f41.N7241__1
							left join Taxes'+@lnk+'.dbo.fn4411 q on q.N4411__1 = a.N4411__1
				left join Taxes'+@lnk+'.dbo.FN212 FN212KA on FN212KA.N1=a.N1_4
			WHERE ( b.N7000__1 in (6,18) and (FN1189.D40<=GetDate() OR FN1189.D40 is NULL ) AND (FN1189.D41>=GetDate() OR FN1189.D41 is NULL) and (FN1190.D40<=GetDate() OR FN1190.D40 is NULL ) AND (FN1190.D41>=GetDate() OR FN1190.D41 is NULL)) 
				AND ((a.N800__1 IN (@id_check))) 
				AND f41.N7241__2 IS NOT NULL
			GROUP BY f41.N7241__2  
								
			
			IF (@t_text IS NOT NULL)
			BEGIN
				UPDATE [arrears].[dbo].[arr_reestr_check] SET 
					[result_see_SLEDSTV_ORG_refused_article] = @t_text
				WHERE [id_check]=@id_check AND [code_NO]=''86'+@lnk+'''	
			END		
						
					
			FETCH NEXT FROM reestr_cursor
			INTO @id_check, @id_NP
		END

		CLOSE reestr_cursor
		DEALLOCATE reestr_cursor')
	
END

GO
