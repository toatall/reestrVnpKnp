USE [arrears]
GO
/****** Object:  Table [dbo].[arr_access_login_ifns]    Script Date: 26.02.2016 17:47:48 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[arr_access_login_ifns](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[id_login] [int] NOT NULL,
	[code_ifns] [varchar](4) NOT NULL,
 CONSTRAINT [PK_arr_access_login_ifns] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[arr_audit]    Script Date: 26.02.2016 17:47:48 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[arr_audit](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[operation] [int] NOT NULL,
	[model] [varchar](50) NULL,
	[model_id] [int] NULL,
	[user_ip] [varchar](20) NULL,
	[user_host] [varchar](50) NULL,
	[user_name] [varchar](50) NULL,
	[user_fio] [varchar](150) NULL,
	[user_platform] [varchar](20) NULL,
	[user_browser] [varchar](20) NULL,
	[user_browser_version] [varchar](25) NULL,
	[user_win64] [bit] NULL,
	[user_win32] [bit] NULL,
	[user_agent_str] [varchar](500) NULL,
	[date_create] [datetime] NULL
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[arr_directory_archive_status]    Script Date: 26.02.2016 17:47:48 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[arr_directory_archive_status](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[value] [varchar](500) NOT NULL,
	[date_create] [datetime] NULL CONSTRAINT [DF_arr_directory_archive_status_date_create]  DEFAULT (getdate()),
 CONSTRAINT [PK_arr_directory_archive_status] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[arr_directory_article]    Script Date: 26.02.2016 17:47:48 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[arr_directory_article](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[value] [varchar](250) NOT NULL,
	[type_article] [smallint] NULL,
 CONSTRAINT [PK_arr_directory_article] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[arr_directory_bankruptcy]    Script Date: 26.02.2016 17:47:48 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[arr_directory_bankruptcy](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[value] [varchar](250) NOT NULL,
 CONSTRAINT [PK_arr_directory_bankruptcy] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[arr_directory_civil_action_result]    Script Date: 26.02.2016 17:47:48 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[arr_directory_civil_action_result](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[value] [varchar](250) NOT NULL,
 CONSTRAINT [PK_arr_directory_civil_action_result] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[arr_directory_property]    Script Date: 26.02.2016 17:47:48 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[arr_directory_property](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[value] [varchar](250) NOT NULL,
 CONSTRAINT [PK_arr_directory_property] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[arr_directory_summ_dept_paused_in_recovery]    Script Date: 26.02.2016 17:47:48 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[arr_directory_summ_dept_paused_in_recovery](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[value] [varchar](100) NOT NULL,
 CONSTRAINT [PK_arr_directory_summ_dept_paused_in_recovery] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[arr_directory_type_check]    Script Date: 26.02.2016 17:47:48 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[arr_directory_type_check](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[value] [varchar](50) NOT NULL,
	[date_create] [datetime] NULL,
	[date_modification] [datetime] NULL,
 CONSTRAINT [PK_arr_directory_type_check] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[arr_eod_data]    Script Date: 26.02.2016 17:47:48 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[arr_eod_data](
	[code_NO] [varchar](4) NOT NULL,
	[id_NP] [int] NOT NULL,
	[type_NP] [int] NOT NULL,
	[id_check] [int] NOT NULL,
	[name_NP] [varchar](160) NOT NULL,
	[inn_NP] [varchar](12) NULL,
	[kpp_NP] [varchar](9) NULL,
	[sum] [decimal](18, 2) NOT NULL,
	[num_resolution] [varchar](25) NULL,
	[date_resolution] [datetime] NULL
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[arr_ifns]    Script Date: 26.02.2016 17:47:48 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[arr_ifns](
	[code_no] [varchar](4) NOT NULL,
	[name] [varchar](250) NOT NULL,
 CONSTRAINT [PK_arr_ifns] PRIMARY KEY CLUSTERED 
(
	[code_no] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[arr_login]    Script Date: 26.02.2016 17:47:48 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[arr_login](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[login_windows] [varchar](100) NOT NULL,
	[login_name] [varchar](500) NULL,
	[login_password] [varchar](500) NULL,
	[login_description] [varchar](500) NULL,
	[role_admin] [bit] NULL,
	[blocked] [bit] NULL,
	[date_create] [datetime] NOT NULL,
	[date_modification] [datetime] NULL,
	[code_no] [varchar](4) NULL,
 CONSTRAINT [PK_arr_login] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[arr_reestr_check]    Script Date: 26.02.2016 17:47:48 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[arr_reestr_check](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[id_check] [int] NULL,
	[code_NO] [varchar](4) NOT NULL,
	[code_NO_current_dept] [varchar](4) NOT NULL,
	[id_NP] [int] NULL,
	[type_NP] [int] NULL,
	[inn_NP] [varchar](12) NULL,
	[kpp_NP] [varchar](9) NULL,
	[name_NP] [varchar](250) NULL,
	[credit_addational] [decimal](18, 2) NULL,
	[resolution_date] [date] NULL,
	[resolution_number] [varchar](25) NULL,
	[type_check] [tinyint] NULL,
	[reduced_higher_NO_summ] [decimal](18, 2) NULL,
	[reduced_arb_court_summ] [decimal](18, 2) NULL,
	[resolution_adop_sec_measure_ban_alien_num] [varchar](25) NULL,
	[resolution_adop_sec_measure_ban_alien_date] [date] NULL,
	[resolution_adop_sec_measure_susp_oper_num] [varchar](25) NULL,
	[resolution_adop_sec_measure_susp_oper_date] [date] NULL,
	[info_removal_register_NP_date] [date] NULL,
	[info_removal_register_NP_to_NO] [varchar](4) NULL,
	[info_removal_register_NP_reason] [varchar](250) NULL,
	[current_proc_bankruptcy] [tinyint] NULL,
	[intro_date] [date] NULL,
	[last_measure_recovery] [tinyint] NULL,
	[adop_date] [date] NULL,
	[property] [tinyint] NULL,
	[note_bankruptcy] [varchar](5000) NULL,
	[material_SLEDSTV_ORG_date] [date] NULL,
	[material_SLEDSTV_ORG_num] [varchar](25) NULL,
	[material_SLEDSTV_ORG_article] [varchar](250) NULL,
	[result_see_SLEDSTV_ORG_filed_article] [int] NULL,
	[result_see_SLEDSTV_ORG_filed_date] [date] NULL,
	[result_see_SLEDSTV_ORG_filed_num] [varchar](25) NULL,
	[result_see_SLEDSTV_ORG_refused_article] [varchar](250) NULL,
	[result_see_SLEDSTV_ORG_refused_date] [date] NULL,
	[civil_action] [tinyint] NULL,
	[civil_action_date] [date] NULL,
	[civil_action_summ] [decimal](18, 2) NULL,
	[civil_action_result_see] [tinyint] NULL,
	[civil_action_repayment_summ] [decimal](18, 2) NULL,
	[note_SLEDSTV_ORG] [varchar](5000) NULL,
	[material_to_UVD_date] [date] NULL,
	[material_to_UVD_num] [varchar](25) NULL,
	[material_to_UVD_article] [tinyint] NULL,
	[result_see_OVD_filed] [tinyint] NULL,
	[result_see_OVD_refused] [tinyint] NULL,
	[note_OVD] [varchar](5000) NULL,
	[date_create] [datetime] NULL,
	[date_modification] [datetime] NULL,
	[log_change] [varchar](5000) NOT NULL,
	[balance_dept_VNP] [decimal](18, 2) NULL,
	[comment_arch] [varchar](5000) NULL,
	[including_NP] [decimal](18, 2) NULL,
	[including_agent] [decimal](18, 2) NULL,
	[requiment_number] [varchar](25) NULL,
	[requiment_date] [date] NULL,
	[requiment_term] [date] NULL,
	[requiment_summ] [decimal](18, 2) NULL,
	[requiment_summ_rest] [decimal](18, 2) NULL,
	[date_delete] [datetime] NULL,
	[resolution_effective_date] [date] NULL,
	[summ_dept_paused_in_recovery] [tinyint] NULL,
	[recovered_summ] [decimal](18, 2) NULL,
	[status_arch] [tinyint] NULL,
	[update_version] [varchar](11) NULL,
 CONSTRAINT [PK_arr_reestr_check] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[arr_reestr_check_8601_test]    Script Date: 26.02.2016 17:47:48 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[arr_reestr_check_8601_test](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[id_check] [int] NULL,
	[code_NO] [varchar](4) NOT NULL,
	[code_NO_current_dept] [varchar](4) NOT NULL,
	[id_NP] [int] NULL,
	[type_NP] [int] NULL,
	[inn_NP] [varchar](12) NULL,
	[kpp_NP] [varchar](9) NULL,
	[name_NP] [varchar](250) NULL,
	[credit_addational] [decimal](18, 2) NULL,
	[resolution_date] [date] NULL,
	[resolution_number] [varchar](25) NULL,
	[type_check] [tinyint] NULL,
	[reduced_higher_NO_summ] [decimal](18, 2) NULL,
	[reduced_arb_court_summ] [decimal](18, 2) NULL,
	[resolution_adop_sec_measure_ban_alien_num] [varchar](25) NULL,
	[resolution_adop_sec_measure_ban_alien_date] [date] NULL,
	[resolution_adop_sec_measure_susp_oper_num] [varchar](25) NULL,
	[resolution_adop_sec_measure_susp_oper_date] [date] NULL,
	[info_removal_register_NP_date] [date] NULL,
	[info_removal_register_NP_to_NO] [varchar](4) NULL,
	[info_removal_register_NP_reason] [varchar](250) NULL,
	[current_proc_bankruptcy] [tinyint] NULL,
	[intro_date] [date] NULL,
	[last_measure_recovery] [tinyint] NULL,
	[adop_date] [date] NULL,
	[property] [tinyint] NULL,
	[note_bankruptcy] [varchar](5000) NULL,
	[material_SLEDSTV_ORG_date] [date] NULL,
	[material_SLEDSTV_ORG_num] [varchar](25) NULL,
	[material_SLEDSTV_ORG_article] [varchar](250) NULL,
	[result_see_SLEDSTV_ORG_filed_article] [int] NULL,
	[result_see_SLEDSTV_ORG_filed_date] [date] NULL,
	[result_see_SLEDSTV_ORG_filed_num] [varchar](25) NULL,
	[result_see_SLEDSTV_ORG_refused_article] [varchar](250) NULL,
	[result_see_SLEDSTV_ORG_refused_date] [date] NULL,
	[civil_action] [tinyint] NULL,
	[civil_action_date] [date] NULL,
	[civil_action_summ] [decimal](18, 2) NULL,
	[civil_action_result_see] [tinyint] NULL,
	[civil_action_repayment_summ] [decimal](18, 2) NULL,
	[note_SLEDSTV_ORG] [varchar](5000) NULL,
	[material_to_UVD_date] [date] NULL,
	[material_to_UVD_num] [varchar](25) NULL,
	[material_to_UVD_article] [tinyint] NULL,
	[result_see_OVD_filed] [tinyint] NULL,
	[result_see_OVD_refused] [tinyint] NULL,
	[note_OVD] [varchar](5000) NULL,
	[date_create] [smalldatetime] NOT NULL,
	[date_modification] [smalldatetime] NULL,
	[log_change] [varchar](5000) NOT NULL,
	[balance_dept_VNP] [decimal](18, 2) NULL,
	[comment_arch] [varchar](5000) NULL,
	[including_NP] [decimal](18, 2) NULL,
	[including_agent] [decimal](18, 2) NULL,
	[requiment_number] [varchar](25) NULL,
	[requiment_date] [date] NULL,
	[requiment_term] [date] NULL,
	[requiment_summ] [decimal](18, 2) NULL,
	[requiment_summ_rest] [decimal](18, 2) NULL,
	[date_delete] [datetime] NULL,
	[resolution_effective_date] [date] NULL,
	[summ_dept_paused_in_recovery] [tinyint] NULL,
	[recovered_summ] [decimal](18, 2) NULL,
	[status_arch] [tinyint] NULL
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[arr_reestr_check_article]    Script Date: 26.02.2016 17:47:48 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[arr_reestr_check_article](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[id_reestr] [int] NOT NULL,
	[id_directory_article] [int] NOT NULL,
	[field_name] [varchar](50) NOT NULL,
 CONSTRAINT [PK_arr_reestr_check_article] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[arr_reestr_check_property]    Script Date: 26.02.2016 17:47:48 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[arr_reestr_check_property](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[id_reestr] [int] NOT NULL,
	[id_directory_property] [int] NOT NULL,
 CONSTRAINT [PK_arr_reestr_check_property] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
/****** Object:  Table [dbo].[arr_reestr_check_requiment]    Script Date: 26.02.2016 17:47:48 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[arr_reestr_check_requiment](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[id_reestr] [int] NOT NULL,
	[requiment_number] [varchar](25) NULL,
	[requiment_date] [date] NULL,
	[requiment_term] [date] NULL,
	[requiment_summ] [decimal](18, 2) NULL,
	[requiment_summ_rest] [decimal](18, 2) NULL,
	[recovered_summ] [decimal](18, 2) NULL,
	[date_delete] [datetime] NULL,
	[log_change] [varchar](5000) NULL,
	[date_create] [datetime] NULL,
	[date_modification] [datetime] NULL,
 CONSTRAINT [PK_arr_reestr_check_requiment] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[arr_saldo]    Script Date: 26.02.2016 17:47:48 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[arr_saldo](
	[code_NO] [varchar](4) NOT NULL,
	[inn_NP] [varchar](12) NULL,
	[kpp_NP] [varchar](10) NULL,
	[name_NP] [varchar](500) NULL,
	[OKTMO] [varchar](9) NULL,
	[sum_saldo] [numeric](18, 2) NOT NULL
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
ALTER TABLE [dbo].[arr_reestr_check_article]  WITH CHECK ADD  CONSTRAINT [FK_arr_reestr_check_article_arr_reestr_check] FOREIGN KEY([id_reestr])
REFERENCES [dbo].[arr_reestr_check] ([id])
ON DELETE CASCADE
GO
ALTER TABLE [dbo].[arr_reestr_check_article] CHECK CONSTRAINT [FK_arr_reestr_check_article_arr_reestr_check]
GO
ALTER TABLE [dbo].[arr_reestr_check_property]  WITH CHECK ADD  CONSTRAINT [FK_arr_reestr_check_property_arr_reestr_check] FOREIGN KEY([id_reestr])
REFERENCES [dbo].[arr_reestr_check] ([id])
ON DELETE CASCADE
GO
ALTER TABLE [dbo].[arr_reestr_check_property] CHECK CONSTRAINT [FK_arr_reestr_check_property_arr_reestr_check]
GO
ALTER TABLE [dbo].[arr_reestr_check_requiment]  WITH CHECK ADD  CONSTRAINT [FK_arr_reestr_check_requiment_arr_reestr_check] FOREIGN KEY([id_reestr])
REFERENCES [dbo].[arr_reestr_check] ([id])
ON DELETE CASCADE
GO
ALTER TABLE [dbo].[arr_reestr_check_requiment] CHECK CONSTRAINT [FK_arr_reestr_check_requiment_arr_reestr_check]
GO
