USE [arrears]
GO
/****** Object:  View [dbo].[arr_view_reestr_check]    Script Date: 26.02.2016 17:48:16 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[arr_view_reestr_check]
AS
SELECT [id]
      ,[id_check]
      ,[code_NO]
      ,[code_NO_current_dept]
      ,[id_NP]
      ,[type_NP]
      ,[inn_NP]
      ,[kpp_NP]
      ,[name_NP]
      ,[credit_addational]
      ,[resolution_date]
      ,[resolution_number]
      ,B.[requiment_number]
	  ,B.[requiment_date]
	  ,B.[requiment_term]
	  ,B.[requiment_summ]
	  ,B.[requiment_summ_rest]
      ,[type_check]
      ,[reduced_higher_NO_summ]
      ,[reduced_arb_court_summ]
      ,[recovered_summ]
      ,[resolution_adop_sec_measure_ban_alien_num]
      ,[resolution_adop_sec_measure_ban_alien_date]
      ,[resolution_adop_sec_measure_susp_oper_num]
      ,[resolution_adop_sec_measure_susp_oper_date]
      ,[info_removal_register_NP_date]
      ,[info_removal_register_NP_to_NO]
      ,[info_removal_register_NP_reason]
      ,[current_proc_bankruptcy]
      ,[intro_date]
      ,[last_measure_recovery]
      ,[adop_date]
      ,[property]
      ,[note_bankruptcy]
      ,[material_SLEDSTV_ORG_date]
      ,[material_SLEDSTV_ORG_num]
      ,[material_SLEDSTV_ORG_article]
      ,[result_see_SLEDSTV_ORG_filed_article]
      ,[result_see_SLEDSTV_ORG_filed_date]
      ,[result_see_SLEDSTV_ORG_filed_num]
      ,[result_see_SLEDSTV_ORG_refused_article]
      ,[result_see_SLEDSTV_ORG_refused_date]
      ,[civil_action]
      ,[civil_action_date]
      ,[civil_action_summ]
      ,[civil_action_result_see]
      ,[civil_action_repayment_summ]
      ,[note_SLEDSTV_ORG]
      ,[material_to_UVD_date]
      ,[material_to_UVD_num]
      ,[material_to_UVD_article]
      ,[result_see_OVD_filed]
      ,[result_see_OVD_refused]
      ,[note_OVD]
      ,[date_create]
      ,[date_modification]
      ,[log_change]
  FROM [arr_reestr_check]
  OUTER APPLY (
		SELECT TOP 1 
			requiment_number
		   ,requiment_date
		   ,requiment_term
		   ,requiment_summ
		   ,requiment_summ_rest
		FROM arr_reestr_check_requiment
		WHERE id_reestr = [arr_reestr_check].id
		ORDER BY requiment_date DESC	
	) B
GO
/****** Object:  View [dbo].[arr_view_reestr_check_requiment]    Script Date: 26.02.2016 17:48:16 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[arr_view_reestr_check_requiment]
AS
SELECT     id, id_reestr, requiment_number, requiment_date, requiment_term, requiment_summ, requiment_summ_rest, 
                      requiment_summ - requiment_summ_rest AS recovered_summ
FROM         dbo.arr_reestr_check_requiment

GO
EXEC sys.sp_addextendedproperty @name=N'MS_DiagramPane1', @value=N'[0E232FF0-B466-11cf-A24F-00AA00A3EFFF, 1.00]
Begin DesignProperties = 
   Begin PaneConfigurations = 
      Begin PaneConfiguration = 0
         NumPanes = 4
         Configuration = "(H (1[40] 4[20] 2[20] 3) )"
      End
      Begin PaneConfiguration = 1
         NumPanes = 3
         Configuration = "(H (1 [50] 4 [25] 3))"
      End
      Begin PaneConfiguration = 2
         NumPanes = 3
         Configuration = "(H (1 [50] 2 [25] 3))"
      End
      Begin PaneConfiguration = 3
         NumPanes = 3
         Configuration = "(H (4 [30] 2 [40] 3))"
      End
      Begin PaneConfiguration = 4
         NumPanes = 2
         Configuration = "(H (1 [56] 3))"
      End
      Begin PaneConfiguration = 5
         NumPanes = 2
         Configuration = "(H (2 [66] 3))"
      End
      Begin PaneConfiguration = 6
         NumPanes = 2
         Configuration = "(H (4 [50] 3))"
      End
      Begin PaneConfiguration = 7
         NumPanes = 1
         Configuration = "(V (3))"
      End
      Begin PaneConfiguration = 8
         NumPanes = 3
         Configuration = "(H (1[56] 4[18] 2) )"
      End
      Begin PaneConfiguration = 9
         NumPanes = 2
         Configuration = "(H (1 [75] 4))"
      End
      Begin PaneConfiguration = 10
         NumPanes = 2
         Configuration = "(H (1[66] 2) )"
      End
      Begin PaneConfiguration = 11
         NumPanes = 2
         Configuration = "(H (4 [60] 2))"
      End
      Begin PaneConfiguration = 12
         NumPanes = 1
         Configuration = "(H (1) )"
      End
      Begin PaneConfiguration = 13
         NumPanes = 1
         Configuration = "(V (4))"
      End
      Begin PaneConfiguration = 14
         NumPanes = 1
         Configuration = "(V (2))"
      End
      ActivePaneConfig = 0
   End
   Begin DiagramPane = 
      Begin Origin = 
         Top = 0
         Left = 0
      End
      Begin Tables = 
         Begin Table = "arr_reestr_check_requiment"
            Begin Extent = 
               Top = 6
               Left = 38
               Bottom = 172
               Right = 300
            End
            DisplayFlags = 280
            TopColumn = 0
         End
      End
   End
   Begin SQLPane = 
   End
   Begin DataPane = 
      Begin ParameterDefaults = ""
      End
   End
   Begin CriteriaPane = 
      Begin ColumnWidths = 11
         Column = 3240
         Alias = 900
         Table = 1170
         Output = 720
         Append = 1400
         NewValue = 1170
         SortType = 1350
         SortOrder = 1410
         GroupBy = 1350
         Filter = 1350
         Or = 1350
         Or = 1350
         Or = 1350
      End
   End
End
' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'arr_view_reestr_check_requiment'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_DiagramPaneCount', @value=1 , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'arr_view_reestr_check_requiment'
GO
