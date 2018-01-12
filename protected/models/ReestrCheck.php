<?php

/**
 * This is the model class for table "{{reestr_vnp_kp}}".
 *
 * The followings are the available columns in table '{{reestr_vnp_kp}}':
 * @property integer $id
 * @property string $code_no
 * @property string $inn
 * @property string $kpp
 * @property string $name
 * ....
 */
class ReestrCheck extends CActiveRecord
{                
    
    public $isDelete = false;
    public $footerGridViewSumm;
    public $isArchive = false;
    
    
    // поля-условия для поиска
    public $filter_id;    
    public $filter_id_check;
    public $filter_id_NP;
    public $filter_type_NP;
    public $filter_code_NO;
    public $filter_code_NO_current_dept;
    public $filter_inn_NP;
    public $filter_kpp_NP;
    public $filter_name_NP;
    public $filter_type_check;
    public $filter_comment_arch;
    public $filter_status_arch;
    public $filter_credit_addational;
    public $filter_resolution_date;
    public $filter_resolution_number;
    public $filter_resolution_effective_date;
    public $filter_summ_dept_paused_in_recovery;
    public $filter_requiment_number;
    public $filter_requiment_date;
    public $filter_requiment_term;
    public $filter_requiment_summ;
    public $filter_requiment_summ_rest;
    public $filter_recovered_summ;
    public $filter_reduced_higher_NO_summ;
    public $filter_reduced_arb_court_summ;    
    public $filter_resolution_adop_sec_measure_ban_alien_num;
    public $filter_resolution_adop_sec_measure_ban_alien_date;
    public $filter_resolution_adop_sec_measure_susp_oper_num;
    public $filter_resolution_adop_sec_measure_susp_oper_date;
    public $filter_info_removal_register_NP_date;
    public $filter_info_removal_register_NP_to_NO;
    public $filter_info_removal_register_NP_reason;
    public $filter_current_proc_bankruptcy;
    public $filter_intro_date;   
    public $filter_adop_date;
    public $filter_property;
    public $filter_reestrProperty;
    public $filter_note_bankruptcy;
    public $filter_balance_dept_VNP;
    public $filter_including_NP;
    public $filter_including_agent;
    public $filter_material_SLEDSTV_ORG_date;
    public $filter_material_SLEDSTV_ORG_num;
    public $filter_material_SLEDSTV_ORG_article;
    public $filter_materialSLEDSTVORGarticles;
    public $filter_result_see_SLEDSTV_ORG_filed_article;
    public $filter_result_see_SLEDSTV_ORG_filed_date;
    public $filter_result_see_SLEDSTV_ORG_filed_num;
    public $filter_result_see_SLEDSTV_ORG_refused_article;
    public $filter_result_see_SLEDSTV_ORG_refused_date;
    public $filter_civil_action;
    public $filter_civil_action_date;
    public $filter_civil_action_summ;
    public $filter_civil_action_result_see;
    public $filter_civil_action_repayment_summ;
    public $filter_note_SLEDSTV_ORG;
    public $filter_material_to_UVD_date;
    public $filter_material_to_UVD_num;
    public $filter_material_to_UVD_article;
    public $filter_materialUVDarticles;
    public $filter_result_see_OVD_filed;
    public $filter_result_see_OVD_refused;
    public $filter_note_OVD;
    public $filter_date_create;
    # add columns 13.06.2017
    public $filter_query_in_otdel_UZ_conclusion;
    public $filter_query_in_otdel_UZ_date;
    public $filter_query_in_otdel_UZ_from_IFNS_date;
    
        
    
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{reestr_check}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code_NO', 'required'),
			array('code_NO, code_NO_current_dept', 'length', 'max'=>4, 'on'=>'insert,edit,delete'),
			array('inn_NP',  'length', 'max'=>12),
			array('kpp_NP',  'length', 'max'=>9),
			array('name_NP,
                   info_removal_register_NP_reason,',
                'length', 'max'=>250),
            array('log_change, comment_arch', 'length', 'max'=>5000),
            array('status_arch', 'required', 'on'=>'delete'),
            
            array('material_SLEDSTV_ORG_article,
                   result_see_SLEDSTV_ORG_filed_article,
                   result_see_SLEDSTV_ORG_refused_article',
                'length', 'max'=>250), 
                                         
            array('requiment_number,
                   resolution_number,                    
                   resolution_adop_sec_measure_ban_alien_num, 
                   resolution_adop_sec_measure_susp_oper_num, 
          		   material_SLEDSTV_ORG_num,
                   result_see_SLEDSTV_ORG_filed_num,
                   material_to_UVD_num', 
               'length', 'max'=>25),
			
            array('summ_dept_paused_in_recovery,
                   credit_addational,                     
                   requiment_summ,
                   requiment_summ_rest,            
                   reduced_higher_NO_summ, 
				   reduced_arb_court_summ,                    
                   civil_action_summ,
				   civil_action_repayment_summ,
                   balance_dept_VNP,
                   including_NP,
                   including_agent',
                'numerical', 'integerOnly'=>false),


            array('requiment_date,
                   requiment_term,
                   resolution_date, 
                   resolution_effective_date,                 
                   resolution_adop_sec_measure_ban_alien_date,
                   resolution_adop_sec_measure_susp_oper_date,
                   info_removal_register_NP_date,
                   intro_date,
                   adop_date,
                   material_SLEDSTV_ORG_date,
                   result_see_SLEDSTV_ORG_filed_date,
                   result_see_SLEDSTV_ORG_refused_date,
                   civil_action_date,
                   material_to_UVD_date',
                'date','format'=>'dd.mm.yyyy'),
            
            array('id_check,
                   id_NP,
                   type_NP,
                   type_check,
                   current_proc_bankruptcy,
            	   last_measure_recovery,
            	   material_to_UVD_article,
                   result_see_OVD_filed,
                   result_see_OVD_refused,
            	   civil_action,
                   civil_action_result_see,
                   status_arch',                    
                'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			
            /** Основные реквизиты **/
            array('id_NP,
                   type_NP,
                   inn_NP, 
                   kpp_NP,
            	   code_NO_current_dept,
                   name_NP, 
                   type_check,
                   comment_arch,
                   status_arch', 
                'safe'),
            
            /** Реестр не взысканных (не в полном объеме взысканных) 
             *  дополнительно начисленных сумм налогов,
             *  по итогам проведения налоговых проверок **/
            array('credit_addational,
                   resolution_date, 
                   resolution_number,
                   resolution_effective_date,                                               
                   reduced_higher_NO_summ, 
                   reduced_arb_court_summ,                 
                   resolution_adop_sec_measure_ban_alien_num, 
                   resolution_adop_sec_measure_ban_alien_date,
                   resolution_adop_sec_measure_susp_oper_num,
                   resolution_adop_sec_measure_susp_oper_date,
                   info_removal_register_NP_date,
                   info_removal_register_NP_to_NO,
                   info_removal_register_NP_reason',
                'safe'),
            
            /** Требования **/
            array('requiment_number,
                   requiment_date,
                   requiment_term,
                   requiment_summ,
                   requiment_summ_rest,
                   recovered_summ',
                'safe', 'on'=>'search'),
                              
            /** Сведения о процедурах банкротства **/              
            array('current_proc_bankruptcy,
                   intro_date,
			       last_measure_recovery, 
                   adop_date, 
                   property,                   
                   note_bankruptcy,
                   balance_dept_VNP,
                   including_NP,
                   including_agent',
                'safe'),
            
            /** Сведения о передаче материалов в следственные 
             * органы в порядке ст. 32 НК РФ **/
            array('material_SLEDSTV_ORG_date,
                   material_SLEDSTV_ORG_num,
                   material_SLEDSTV_ORG_article, 
                   result_see_SLEDSTV_ORG_filed_article,
                   result_see_SLEDSTV_ORG_filed_date,
                   result_see_SLEDSTV_ORG_filed_num,
                   result_see_SLEDSTV_ORG_refused_article, 
                   result_see_SLEDSTV_ORG_refused_date,
                   civil_action,
                   civil_action_date,
                   civil_action_summ,
                   civil_action_result_see,
                   civil_action_repayment_summ,
                   note_SLEDSTV_ORG',
                'safe'),
            
            /** Сведения о передаче материалов в органы 
             * внутренних дел в порядке ст. 82 НК РФ **/     
            array('material_to_UVD_date, 
                   material_to_UVD_num,
                   material_to_UVD_article, 
                   result_see_OVD_filed, 
                   result_see_OVD_refused,
                   note_OVD', 
                'safe'/*, 'on'=>'search'*/),
                
            array('id, code_NO, id_check, code_NO_current_dept, date_create', 'safe', 'on'=>'search'),
            array('isArchive', 'safe', 'on'=>'search'),
            
			// новые поля от 13.06.2017
			array('query_in_otdel_UZ_conclusion', 'numerical', 'integerOnly'=>true),
			array('query_in_otdel_UZ_date, query_in_otdel_UZ_from_IFNS_date', 'date', 'format'=>'dd.mm.yyyy'),
				
			array('log_change, date_modification, date_delete', 'unsafe'),
            
            /** условия для фильтров **/
            array('filter_id,
                   filter_id_check,
                   filter_id_NP,
                   filter_type_NP,
                   filter_code_NO,
                   filter_code_NO_current_dept,
                   filter_inn_NP,
                   filter_kpp_NP,
                   filter_name_NP,
                   filter_type_check,
                   filter_status_arch,
                   filter_comment_arch,
                   filter_credit_addational,
                   filter_resolution_date,
                   filter_resolution_number,
                   filter_resolution_effective_date,
                   filter_summ_dept_paused_in_recovery,
                   filter_requiment_number,
                   filter_requiment_date,
                   filter_requiment_term,
                   filter_requiment_summ,
                   filter_requiment_summ_rest,
                   filter_recovered_summ,
                   filter_reduced_higher_NO_summ,
                   filter_reduced_arb_court_summ,
                   filter_resolution_adop_sec_measure_ban_alien_num,
                   filter_resolution_adop_sec_measure_ban_alien_date,
                   filter_resolution_adop_sec_measure_susp_oper_num,
                   filter_resolution_adop_sec_measure_susp_oper_date,
                   filter_info_removal_register_NP_date,
                   filter_info_removal_register_NP_to_NO,
                   filter_info_removal_register_NP_reason,
                   filter_current_proc_bankruptcy,
                   filter_intro_date,                   
                   filter_adop_date,
                   filter_property,
                   filter_balance_dept_VNP,
                   filter_including_NP,
                   filter_including_agent,
                   filter_reestrProperty,
                   filter_note_bankruptcy,                   
                   filter_material_SLEDSTV_ORG_date,
                   filter_material_SLEDSTV_ORG_num,
                   filter_materialSLEDSTVORGarticles,
                   filter_result_see_SLEDSTV_ORG_filed_article,
                   filter_result_see_SLEDSTV_ORG_filed_date,
                   filter_result_see_SLEDSTV_ORG_filed_num,
                   filter_result_see_SLEDSTV_ORG_refused_article,
                   filter_result_see_SLEDSTV_ORG_refused_date,
                   filter_civil_action,
                   filter_civil_action_date,
                   filter_civil_action_summ,
                   filter_civil_action_result_see,
                   filter_civil_action_repayment_summ,
                   filter_note_SLEDSTV_ORG,
                   filter_material_to_UVD_date,
                   filter_material_to_UVD_num,
                   filter_material_to_UVD_article,
                   filter_materialUVDarticles,
                   filter_result_see_OVD_filed,
                   filter_result_see_OVD_refused,
                   filter_note_OVD,
            	   filter_date_create,
            	   filter_query_in_otdel_UZ_conclusion,
            	   filter_query_in_otdel_UZ_date,
            	   filter_query_in_otdel_UZ_from_IFNS_date',            	  
            'safe', 'on'=>'search'),
            
            array('credit_addational,
                   requiment_summ,
                   reduced_higher_NO_summ,
                   reduced_arb_court_summ,
                   civil_action_summ,
                   civil_action_repayment_summ,
                   balance_dept_VNP,
                   including_NP,
                   including_agent,
                   resolution_date,
                   resolution_adop_sec_measure_ban_alien_date,
                   resolution_adop_sec_measure_susp_oper_date,
                   info_removal_register_NP_date,
                   intro_date,
                   adop_date,
                   material_SLEDSTV_ORG_date,
                   result_see_SLEDSTV_ORG_filed_date,
                   result_see_SLEDSTV_ORG_refused_date,
                   material_to_UVD_date,
                   civil_action_date',
                'default', 'setOnEmpty'=>true, 'value'=>new CDbExpression('NULL')
            ),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(            
            
            'ifns'=>array(self::BELONGS_TO, 'Ifns', 'code_NO'),
			'ifnsDept'=>array(self::BELONGS_TO, 'Ifns', 'code_NO_current_dept'),
            
            'directoryProperty'=>array(self::MANY_MANY, 'DirectoryProperty', 
                '{{reestr_check_property}} (id_reestr, id_directory_property)'),
            
                        
            'directoryArticleSLEDSTV_ORG' => array(self::MANY_MANY, 'DirectoryArticle',
                '{{reestr_check_article}} (id_reestr, id_directory_article)',
                'on'=>'field_name=\'material_SLEDSTV_ORG_article\''),
            'directoryArticleUVD' => array(self::MANY_MANY, 'DirectoryArticle',
                '{{reestr_check_article}} (id_reestr, id_directory_article)',
                'on'=>'field_name=\'material_to_UVD_article\''),
            
            'requiments'=>array(self::HAS_MANY, 'ReestrCheckRequiment', 'id_reestr', 
                'order'=>'requiments.requiment_date desc'),
            
            'requiments2'=>array(self::HAS_ONE, 'ReestrCheckRequiment', 'id_reestr', 
                'order'=>'requiments2.requiment_number desc'),
            'properties'=>array(self::HAS_MANY, 'ReestrCheckProperty', 'id_reestr'),
            'properties2' => array(self::MANY_MANY, 'DirectoryProperty',
                '{{reestr_check_property}} (id_reestr, id_directory_property)'),
            'reestr_material_SLEDSTV_ORG_article'=>array(self::HAS_MANY, 
                'ReestrCheckArticle', 'id_reestr', 
                'on'=>'reestr_material_SLEDSTV_ORG_article.field_name=\'material_SLEDSTV_ORG_article\''),
            'reestr_material_to_UVD_article'=>array(self::HAS_MANY, 
                'ReestrCheckArticle', 'id_reestr', 
                'on'=>'reestr_material_to_UVD_article.field_name=\'material_to_UVD_article\''),
            
            'article_result_see_SLEDSTV_ORG_filed'=>array(
                self::BELONGS_TO, 'DirectoryArticle', 'result_see_SLEDSTV_ORG_filed_article'),                
            'article_result_see_SLEDSTV_ORG_refused'=>array(
                self::BELONGS_TO, 'DirectoryArticle', 'result_see_SLEDSTV_ORG_refused_article'),
            'article_civil_action_result_see'=>array(
                self::BELONGS_TO, 'DirectoryCivilActionResult', 'civil_action_result_see'),   
            'article_material_to_UVD_article'=>array(
                self::BELONGS_TO, 'DirectoryArticle', 'material_to_UVD_article'), 
            'article_result_see_OVD_filed'=>array(
                self::BELONGS_TO, 'DirectoryArticle', 'result_see_OVD_filed'),        
            
            
            'directory_bankruptcy'=>array(self::BELONGS_TO, 
                'DirectoryBankruptcy', 'current_proc_bankruptcy'),
            'article_last_measure_recovery'=>array(
                self::BELONGS_TO, 'DirectoryArticle', 'last_measure_recovery'),
            /*'directory_property'=>array(self::BELONGS_TO,
                'DirectoryProperty', 'property'),*/
            
            
            'article_result_see_SLEDSTV_ORG_filed'=>array(
                self::BELONGS_TO, 'DirectoryArticle', 'result_see_SLEDSTV_ORG_filed_article'),                                                                
            'article_result_see_OVD_refused'=>array(
                self::BELONGS_TO, 'DirectoryArticle', 'result_see_OVD_refused'),
                                                                        
            'directory_type_check'=>array(self::BELONGS_TO, 'DirectoryTypeCheck', 'type_check'),
            
            'directorySummDeptPausedInRecovery' => array(self::BELONGS_TO, 
                'DirectoryCivilActionResult', 'summ_dept_paused_in_recovery'
            ),
            
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'УН',
            'id_check' => 'УН проверки',
			'code_NO' => 'Код налогового органа',
            'code_NO_current_dept' => 'Код НО по текущей задолженности',
            'id_NP' => 'УН плательщика',
            'type_NP' => 'Вид плательщика',
			'inn_NP' => 'ИНН налогоплательщика',
			'kpp_NP' => 'КПП налогоплательщика',
			'name_NP' => 'Наименование налогоплательщика',
			'type_check' => 'Вид проверки',
            'comment_arch' => 'Комментарий',
            'status_arch' => 'Основание переноса в архив',
            
            // Реестр не взысканных (не в полном объеме взысканных) дополнительно 
            //     начисленных сумм налогов, по итогам проведения налоговых проверок
			'credit_addational' => 'Доначислено  по решению всего, с учетом сумм уменьшения по проверкам (тыс. руб.)',				
			'resolution_date' => 'Дата решения',
			'resolution_number' => 'Номер решения',
            
            'requiment_number' => 'Номер требования об уплате',
            'requiment_date' => 'Дата требования об уплате',
            'requiment_term' => 'Срок уплаты по требованию',
            'requiment_summ' => 'Сумма включенная в требование (тыс. руб.)',
            'requiment_summ_rest' => 'Остаток непогашенной суммы по требованию (тыс. руб.)',
			'recovered_summ' => 'Взыскано (тыс. руб.)',
            
            'reduced_higher_NO_summ' => 'Уменьшено по решению вышестоящего налогового органа, всего (тыс. руб.)',
			'reduced_arb_court_summ' => 'Уменьшено по решению Арбитражного суда (тыс. руб.)',
			
			'resolution_adop_sec_measure_ban_alien_num' => 'Решение о принятии обеспечительных мер (по невзысканным платежам) / запрет на отчуждение имущества / номер',
			/*short*/'resolution_adop_sec_measure_ban_alien_num_short' => 'Номер',			
            'resolution_adop_sec_measure_ban_alien_date' => 'Решение о принятии обеспечительных мер (по невзысканным платежам) / запрет на отчуждение имущества / дата',
            /*short*/'resolution_adop_sec_measure_ban_alien_date_short' => 'Дата',            
            'resolution_adop_sec_measure_susp_oper_num' => 'Решение о принятии обеспечительных мер (по невзысканным платежам) / приостановление операций по счетам / номер',
            /*short*/'resolution_adop_sec_measure_susp_oper_num_short' => 'Номер',            
            'resolution_adop_sec_measure_susp_oper_date' => 'Решение о принятии обеспечительных мер (по невзысканным платежам) / приостановление операций по счетам / дата',
            /*short*/'resolution_adop_sec_measure_susp_oper_date_short' => 'Дата',            
            'info_removal_register_NP_date' => 'Сведения о снятии с учета налогоплательщика (Дата снятия с учета)',
            /*short*/'info_removal_register_NP_date_short' => 'Дата снятия с учета',
            'info_removal_register_NP_to_NO' => 'Сведения о снятии с учета налогоплательщика (НО куда поставлен на учет)',
            /*short*/'info_removal_register_NP_to_NO_short' => 'НО куда поставлен на учет',
            'info_removal_register_NP_reason' => 'Сведения о снятии с учета налогоплательщика (причина снятия)',
            /*short*/'info_removal_register_NP_reason_short' => 'Причина снятия',
            
            
            // Сведения о процедурах банкротства                        
            'current_proc_bankruptcy' => 'Текущая процедура банкротства',
			'intro_date' => 'Дата введения',
			'last_measure_recovery' => 'Последняя мера взыскания',
			'adop_date' => 'Дата принятия',
			'property' => 'Наличие имущества',
            'reestrProperty' => 'Наличие имущества',
            'note_bankruptcy' => 'Примечание',
            'balance_dept_VNP' => 'Остаток задолженности по выездной налоговой проверке (тыс. руб.)',
            'including_NP' => 'В том числе по налогу (налогоплательщик) (тыс. руб.)',
            'including_agent' => 'В том числе по налогу (налоговый агент) (тыс. руб.)',
                   
            // Сведения о передаче материалов в следственные органы в порядке ст. 32 НК РФ            
			'material_SLEDSTV_ORG_date' => 'Материалы переданы в следственные органы по ст. 32 НК РФ (дата письма)',
			/*short*/'material_SLEDSTV_ORG_date_short' => 'Дата письма',			
            'material_SLEDSTV_ORG_num' => 'Материалы переданы в следственные органы по ст. 32 НК РФ (номер письма)',
			/*short*/'material_SLEDSTV_ORG_num_short' => 'Номер письма',			
            'material_SLEDSTV_ORG_article' => 'Материалы переданы в следственные органы по ст. 32 НК РФ (ст. УК РФ)',
            'materialSLEDSTVORGarticles' => 'Материалы переданы в следственные органы по ст. 32 НК РФ (ст. УК РФ)',
			/*short*/'materialSLEDSTVORGarticles_short' => 'ст. УК РФ',			
            'result_see_SLEDSTV_ORG_filed_article' => 'Результат рассмотрения следственными органами материалов налоговых проверок (возбуждено УД) (статья УК РФ)',
			/*short*/'result_see_SLEDSTV_ORG_filed_article_short' => 'Возбуждено УД (статья УК РФ)',
			'result_see_SLEDSTV_ORG_filed_date' => 'Результат рассмотрения следственными органами материалов налоговых проверок (возбуждено УД) (дата)',
            /*short*/'result_see_SLEDSTV_ORG_filed_date_short' => 'Возбуждено УД (дата)',            
            'result_see_SLEDSTV_ORG_filed_num' => 'Результат рассмотрения следственными органами материалов налоговых проверок (возбуждено УД) (номер)',            
            /*short*/'result_see_SLEDSTV_ORG_filed_num_short' => 'Возбуждено УД (номер)',                        
            'result_see_SLEDSTV_ORG_refused_article' => 'Результат рассмотрения следственными органами материалов налоговых проверок  (отказано в возбуждении УД) (причина отказа)',
            /*short*/'result_see_SLEDSTV_ORG_refused_article_short' => 'Отказано в возбуждении УД (причина отказа)',            
            'result_see_SLEDSTV_ORG_refused_date' => 'Результат рассмотрения следственными органами материалов налоговых проверок  (отказано в возбуждении УД) (дата)',
            /*short*/'result_see_SLEDSTV_ORG_refused_date_short' => 'Отказано в возбуждении УД (дата)',            
            'civil_action' => 'Предъявлен гражданский  иск о возмещении ущерба по материалам налоговых органов ',
            'civil_action_date' => 'Дата предъявления гражданского иска о возмещении ущерба по материалам налоговых органов',
			'civil_action_summ' => 'Предъявлен гражданский иск на сумму (тыс. руб)',				 
			'civil_action_result_see' => 'Результат рассмотрения судебными органами гражданского иска',
			'civil_action_repayment_summ' => 'Погашение суммы гражданского иска (тыс. руб)',
            'note_SLEDSTV_ORG' => 'Примечание',
            
            // Сведения о передаче материалов в органы внутренних дел в порядке ст. 82 НК РФ
			'material_to_UVD_date' => 'Материалы переданы в органы внутренних дел (дата письма)',
			'material_to_UVD_num' => 'Материалы переданы в органы внутренних дел (номер письма)',
			'material_to_UVD_article' => 'Материалы переданы в органы внутренних дел (ст. УК РФ)',
			'materialUVDarticles' => 'Материалы переданы в органы внутренних дел (ст. УК РФ)',			
            'result_see_OVD_filed' => 'Результат рассмотрения материалов органами  внутренних дел  (возбуждено УД)',
			/*short*/'result_see_OVD_filed_short' => 'Возбуждено УД (статья)',			
            'result_see_OVD_refused' => 'Результат рассмотрения материалов органами  внутренних дел (отказано в возбуждении УД)',
			/*short*/'result_see_OVD_refused_short' => 'Отказано в возбуждении УД (статья)',
            'note_OVD' => 'Примечание',
			
			'date_create' => 'Дата создания',
			'date_modification' => 'Дата изменения',
            'date_delete' => 'Дата архивации',            	
			//'log_change' => 'История изменений',
			
			
			//  поля от 13.06.2017
			'query_in_otdel_UZ_conclusion' => 'Заключение представленное в отдел урегулирования задолженности по наличию оснований для применения подп. 2 пун. 2 ст. 45 НК РФ',
			'query_in_otdel_UZ_date' => 'Дата запроса заключения отделом урегулирования задолженности по установлению наличия оснований применения подп. 2 пун. 2 ст. 45 НК РФ',
			'query_in_otdel_UZ_from_IFNS_date' => 'Дата заключения представленного профильными  отделами инспекции в  отдел урегулирования задолженности',
			
		);
	}
    
    // аттрибуты по-умолчанию для отображения столбцов в GridView (ReestrCheck/admin)
    public function defaultAttributeLabels()
    {
        return array(
            'id',
            'id_check',
			'code_NO',
			'inn_NP',
			'kpp_NP',
			'name_NP',
			'type_check',
			'credit_addational',				
			'resolution_date',				
			'resolution_number',
        );
    }
    
    
    public function yesNoValues()
    {
        return array(0=>'нет',1=>'да');
    }
    
    
        
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
    
    public function search($returnDataProvider=true)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.        
        
		$criteria=new CDbCriteriaExtentsion;
        $criteria2=new CDbCriteriaExtentsion;
        
		$criteria->compare('id', $this->filter_id, $this->id);
        $criteria->compare('id_check', $this->filter_id_check, $this->id_check);                                        
        
        $criteria->compare('code_NO', $this->filter_code_NO, $this->code_NO, true);
        $criteria->compare('code_NO_current_dept', 
            $this->filter_code_NO_current_dept, $this->code_NO_current_dept, true);
		$criteria->compare('inn_NP', $this->filter_inn_NP, $this->inn_NP, true);
		$criteria->compare('kpp_NP', $this->filter_kpp_NP, $this->kpp_NP, true);
		$criteria->compare('name_NP', $this->filter_name_NP, $this->name_NP, true);
        $criteria->compare('id_NP', $this->filter_id_NP, $this->id_NP);
		$criteria->compare('type_NP', -1, $this->type_NP);
		$criteria->compare('type_check', -1, $this->type_check);
        $criteria->compare('comment_arch', $this->filter_comment_arch, $this->comment_arch);
        $criteria->compare('status_arch', $this->filter_status_arch, $this->status_arch);
        
        // Реестр не взысканных (не в полном объеме взысканных) дополнительно 
        //     начисленных сумм налогов, по итогам проведения налоговых проверок
        $criteria->compare('credit_addational', 
            $this->filter_credit_addational, $this->credit_addational);        
        $criteria->compare('resolution_date', 
            $this->filter_resolution_date, $this->resolution_date, true);                        
		$criteria->compare('resolution_number', 
            $this->filter_resolution_number, $this->resolution_number, true);
        $criteria->compare('resolution_effective_date', 
            $this->filter_resolution_effective_date, $this->resolution_effective_date, true);
        $criteria->compare('summ_dept_paused_in_recovery', $this->filter_summ_dept_paused_in_recovery, 
            $this->summ_dept_paused_in_recovery);
        
        $criteria->compare('requiment_number', $this->filter_requiment_number, 
            $this->requiment_number, true);
        $criteria->compare('requiment_date', $this->filter_requiment_date, 
            $this->requiment_date, true);
        $criteria->compare('requiment_term', $this->filter_requiment_term, 
            $this->requiment_term);
        $criteria->compare('requiment_summ_rest', $this->filter_requiment_summ_rest, 
            $this->requiment_summ_rest);
        $criteria->compare('recovered_summ', $this->filter_recovered_summ, 
            $this->recovered_summ);
                   
            
		$criteria->compare('reduced_higher_NO_summ',
            $this->filter_reduced_higher_NO_summ, $this->reduced_higher_NO_summ);        
		$criteria->compare('reduced_arb_court_summ',
            $this->filter_reduced_arb_court_summ, $this->reduced_arb_court_summ);        	
        $criteria->compare('resolution_adop_sec_measure_ban_alien_num',
            $this->filter_resolution_adop_sec_measure_ban_alien_num, $this->resolution_adop_sec_measure_ban_alien_num, true);
        $criteria->compare('resolution_adop_sec_measure_ban_alien_date',
            $this->filter_resolution_adop_sec_measure_ban_alien_date, $this->resolution_adop_sec_measure_ban_alien_date, true);
        $criteria->compare('resolution_adop_sec_measure_susp_oper_num',
            $this->filter_resolution_adop_sec_measure_susp_oper_num, $this->resolution_adop_sec_measure_susp_oper_num, true);
        $criteria->compare('resolution_adop_sec_measure_susp_oper_date',
            $this->filter_resolution_adop_sec_measure_susp_oper_date, $this->resolution_adop_sec_measure_susp_oper_date, true);
        $criteria->compare('info_removal_register_NP_date',
            $this->filter_info_removal_register_NP_date, $this->info_removal_register_NP_date, true);
        $criteria->compare('info_removal_register_NP_to_NO',
            $this->filter_info_removal_register_NP_to_NO, $this->info_removal_register_NP_to_NO, true);
        $criteria->compare('info_removal_register_NP_reason',
            $this->filter_info_removal_register_NP_reason, $this->info_removal_register_NP_reason, true);
        
        // Сведения о процедурах банкротства
        if ($this->current_proc_bankruptcy <> '')
        {            
            $criteria->addCondition("current_proc_bankruptcy IN (".
                implode(',',array_filter(explode('/',$this->current_proc_bankruptcy))).")");
        }		
		$criteria->compare('intro_date', $this->filter_intro_date, $this->intro_date, true);
        $criteria->compare('last_measure_recovery',
            CDbCriteriaExtentsion::FILTER_ON_LIST, $this->last_measure_recovery, true);
		
        
        $criteria->compare('adop_date', $this->filter_adop_date, $this->adop_date, true);		
        if ($this->property <> '')
        {            
            $criteria->addCondition("EXISTS(SELECT 1 FROM arr_reestr_check_property 
                WHERE id_reestr = t.id AND id_directory_property IN (".
                        implode(',',array_filter(explode('/',$this->property)))."))");
        }
        $criteria->compare('note_bankruptcy', $this->filter_note_bankruptcy, $this->note_bankruptcy, true);
        $criteria->compare('balance_dept_VNP',
            $this->filter_balance_dept_VNP, $this->balance_dept_VNP);        
		$criteria->compare('including_NP', $this->filter_including_NP, $this->including_NP);
        $criteria->compare('including_agent', $this->filter_including_agent, $this->including_agent);    
            
        // Сведения о передаче материалов в следственные органы в порядке ст. 32 НК РФ
		$criteria->compare('material_SLEDSTV_ORG_date',
            $this->filter_material_SLEDSTV_ORG_date, $this->material_SLEDSTV_ORG_date);
		$criteria->compare('material_SLEDSTV_ORG_num',
            $this->filter_material_SLEDSTV_ORG_num, $this->material_SLEDSTV_ORG_num);
		
        if ($this->material_SLEDSTV_ORG_article <> '')
        {            
            $criteria->addCondition("EXISTS(SELECT 1 FROM dbo.arr_reestr_check_article 
                WHERE id_reestr = t.id AND field_name='material_SLEDSTV_ORG_article' 
                    AND id_directory_article IN (".
                        implode(',',array_filter(explode('/',$this->material_SLEDSTV_ORG_article)))."))");
        }
		        
        $criteria->compare('result_see_SLEDSTV_ORG_filed_article', CDbCriteriaExtentsion::FILTER_ON_LIST,
            $this->result_see_SLEDSTV_ORG_filed_article);
		$criteria->compare('result_see_SLEDSTV_ORG_filed_date',
            $this->filter_result_see_SLEDSTV_ORG_filed_date,$this->result_see_SLEDSTV_ORG_filed_date);
		$criteria->compare('result_see_SLEDSTV_ORG_filed_num',
            $this->filter_result_see_SLEDSTV_ORG_filed_num, $this->result_see_SLEDSTV_ORG_filed_num);		
        $criteria->compare('result_see_SLEDSTV_ORG_refused_article',
            $this->filter_result_see_SLEDSTV_ORG_refused_article, $this->result_see_SLEDSTV_ORG_refused_article);
        $criteria->compare('result_see_SLEDSTV_ORG_refused_date',
            $this->filter_result_see_SLEDSTV_ORG_refused_date, $this->result_see_SLEDSTV_ORG_refused_date);
        $criteria->compare('civil_action', $this->filter_civil_action, $this->civil_action);
		$criteria->compare('civil_action_date', 
            $this->filter_civil_action_date, $this->civil_action_date);
		$criteria->compare('civil_action_summ',
            $this->filter_civil_action_summ, $this->civil_action_summ);
		$criteria->compare('civil_action_result_see',
            $this->filter_civil_action_result_see, $this->civil_action_result_see);
		$criteria->compare('civil_action_repayment_summ',
            $this->filter_civil_action_repayment_summ, $this->civil_action_repayment_summ);
        $criteria->compare('note_SLEDSTV_ORG', $this->filter_note_SLEDSTV_ORG, $this->note_SLEDSTV_ORG);
        
        // Сведения о передаче материалов в органы внутренних дел в порядке ст. 82 НК РФ
		$criteria->compare('material_to_UVD_date',
            $this->filter_material_to_UVD_date, $this->material_to_UVD_date);
		$criteria->compare('material_to_UVD_num',
            $this->filter_material_to_UVD_num, $this->material_to_UVD_num);
		        
		if ($this->material_to_UVD_article <> '')
        {            
            $criteria->addCondition("EXISTS(SELECT 1 FROM dbo.arr_reestr_check_article 
                WHERE id_reestr = t.id AND field_name='material_to_UVD_article' 
                    AND id_directory_article IN (".
                        implode(',',array_filter(explode('/',$this->material_to_UVD_article)))."))");
        }
        
        
        $criteria->compare('result_see_OVD_filed',
            $this->filter_result_see_OVD_filed, $this->result_see_OVD_filed);
		$criteria->compare('result_see_OVD_refused',
            $this->filter_result_see_OVD_refused, $this->result_see_OVD_refused);
		$criteria->compare('note_OVD', $this->filter_note_OVD, $this->note_OVD);
        
		
		$criteria->compare('query_in_otdel_UZ_conclusion', 
				$this->filter_query_in_otdel_UZ_conclusion, $this->query_in_otdel_UZ_conclusion);
		$criteria->compare('query_in_otdel_UZ_date', 
				$this->filter_query_in_otdel_UZ_date, $this->query_in_otdel_UZ_date);
		$criteria->compare('query_in_otdel_UZ_from_IFNS_date', 
				$this->filter_query_in_otdel_UZ_from_IFNS_date, $this->query_in_otdel_UZ_from_IFNS_date);
		
        
		//$criteria->compare('date_create',-1,$this->date_create);
		$criteria->compare('date_create',
				$this->filter_date_create, $this->date_create);
		$criteria->compare('date_modification',-1,$this->date_modification);   
        
        $criteria->addCondition('date_delete IS '.($this->isArchive ? ' NOT ' : '').'NULL');
        
        $criteria2 = clone $criteria;
        
        $criteria2->select = 'SUM(ISNULL(t.credit_addational,0)) [credit_addational],                             
                              SUM(ISNULL(t.reduced_higher_NO_summ,0)) [reduced_higher_NO_summ],
                              SUM(ISNULL(t.reduced_arb_court_summ,0)) [reduced_arb_court_summ],
                              SUM(ISNULL(t.civil_action_summ,0)) [civil_action_summ],
                              SUM(ISNULL(t.civil_action_repayment_summ,0)) [civil_action_repayment_summ],
                              SUM(ISNULL(t.balance_dept_VNP,0)) [balance_dept_VNP],
                              SUM(ISNULL(t.including_NP,0)) [including_NP],
                              SUM(ISNULL(t.including_agent,0)) [including_agent],
                              SUM(ISNULL(t.requiment_summ,0)) [requiment_summ],
                              SUM(ISNULL(t.requiment_summ_rest,0)) [requiment_summ_rest],
                              SUM(ISNULL(t.recovered_summ,0)) [recovered_summ]'; 
        
        if ($returnDataProvider)
        {      	
    		$dataProvider = new CActiveDataProvider($this, array(
    			'criteria'=>$criteria,
                'pagination'=>array('pageSize'=>25),
    		));
            $modelSum = ReestrCheck::model()->find($criteria2);
            
            
            return array(0 => $dataProvider, 1=>$modelSum); 
            
            /*
            new CActiveDataProvider($this, array(
    			'criteria'=>$criteria,
    		));
            */
        } 
        else
        {
            return ReestrCheck::model()->findAll($criteria);
        }
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ReestrVnpKp the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
		
    
    function formatDate($arrayFields)
    {
        if (!is_array($arrayFields)) return;
        foreach ($arrayFields as $field)
        {
            
        }
        
    }
        
    public function afterFind()
    {        
        /** *****************************************
         *  необходимо изменить формат даты/времени *
         * ******************************************/
                 
        // дата создания        
        $this->date_create = date('d.m.Y H:i:s', strtotime($this->date_create)); 
        // Дата решения      
        if ($this->resolution_date!=null) $this->resolution_date = date('d.m.Y', strtotime($this->resolution_date));                           
        // Дата введения
        if ($this->intro_date!=null) $this->intro_date = date('d.m.Y', strtotime($this->intro_date));
        // Дата принятия меры взыскания
        if ($this->adop_date!=null) $this->adop_date = date('d.m.Y', strtotime($this->adop_date));
        // Дата требования об уплате
        if ($this->requiment_date!=null) $this->requiment_date = date('d.m.Y', strtotime($this->requiment_date));
        // Срок уплаты по требованию
        if ($this->requiment_term!=null) $this->requiment_term = date('d.m.Y', strtotime($this->requiment_term));        
        // Материалы переданы в следственные органы по ст. 32 НК РФ (дата письма)
        if ($this->material_SLEDSTV_ORG_date!=null) 
            $this->material_SLEDSTV_ORG_date = date('d.m.Y', strtotime($this->material_SLEDSTV_ORG_date));
        // Материалы переданы в органы внутренних дел (дата письма)
        if ($this->material_to_UVD_date!=null) 
            $this->material_to_UVD_date = date('d.m.Y', strtotime($this->material_to_UVD_date));
        // Дата предъявления гражданского иска о возмещении ущерба по материалам налоговых органов
        if ($this->civil_action_date!=null) 
            $this->civil_action_date = date('d.m.Y', strtotime($this->civil_action_date));   
        // Результат рассмотрения следственными органами материалов налоговых проверок (возбуждено УД) (дата)
        if ($this->result_see_SLEDSTV_ORG_filed_date!=null) 
            $this->result_see_SLEDSTV_ORG_filed_date = date('d.m.Y', strtotime($this->result_see_SLEDSTV_ORG_filed_date));   
        // Результат рассмотрения следственными органами материалов налоговых проверок  (отказано в возбуждении УД) (дата)
        if ($this->result_see_SLEDSTV_ORG_refused_date!=null) 
            $this->result_see_SLEDSTV_ORG_refused_date = date('d.m.Y', strtotime($this->result_see_SLEDSTV_ORG_refused_date));
        // Сведения о снятии с учета налогоплательщика (Дата снятия с учета)
        if ($this->info_removal_register_NP_date!=null) 
            $this->info_removal_register_NP_date = date('d.m.Y', strtotime($this->info_removal_register_NP_date));
        // Решение о принятии обеспечительных мер (по невзысканным платежам) / запрет на отчуждение имущества / дата
        if ($this->resolution_adop_sec_measure_ban_alien_date!=null) 
            $this->resolution_adop_sec_measure_ban_alien_date = date('d.m.Y', strtotime($this->resolution_adop_sec_measure_ban_alien_date));
        // Решение о принятии обеспечительных мер (по невзысканным платежам) / приостановление операций по счетам / дата
        if ($this->resolution_adop_sec_measure_susp_oper_date!=null) 
            $this->resolution_adop_sec_measure_susp_oper_date = date('d.m.Y', strtotime($this->resolution_adop_sec_measure_susp_oper_date));
        
        // new from 13062017
        if ($this->query_in_otdel_UZ_date!=null) 
            $this->query_in_otdel_UZ_date = date('d.m.Y', strtotime($this->query_in_otdel_UZ_date));
        if ($this->query_in_otdel_UZ_from_IFNS_date!=null)
          	$this->query_in_otdel_UZ_from_IFNS_date = date('d.m.Y', strtotime($this->query_in_otdel_UZ_from_IFNS_date));
        
        
        /** поля с суммой подгоняю под формат float, 
         * т.к. отображаются цифры < 1 без ведущего нуля (например, ".08") 
         * */
        if ($this->credit_addational!=null) {
            $this->credit_addational = (float)($this->credit_addational);
        }
        if ($this->reduced_higher_NO_summ!=null) {
            $this->reduced_higher_NO_summ = (float)($this->reduced_higher_NO_summ);
        }
        if ($this->reduced_arb_court_summ!=null) {
            $this->reduced_arb_court_summ = (float)($this->reduced_arb_court_summ);
        }
        if ($this->requiment_summ!=null) {
            $this->requiment_summ = (float)($this->requiment_summ);
        }
        if ($this->requiment_summ_rest!=null) {
            $this->requiment_summ_rest = (float)($this->requiment_summ_rest);
        }
        if ($this->recovered_summ!=null) {
            $this->recovered_summ = (float)($this->recovered_summ);
        }        
        if ($this->civil_action_summ!=null) {
            $this->civil_action_summ = (float)($this->civil_action_summ);
        }
        if ($this->civil_action_repayment_summ!=null) {
            $this->civil_action_repayment_summ = (float)($this->civil_action_repayment_summ);
        }
        if ($this->balance_dept_VNP!=null) {
            $this->balance_dept_VNP = (float)($this->balance_dept_VNP);
        }
        if ($this->including_NP!=null) {
            $this->including_NP = (float)($this->including_NP);
        }
        if ($this->including_agent!=null) {
            $this->including_agent = (float)($this->including_agent);
        }
    }
    
    public function beforeSave()
    {        
        // если пустые суммы, то присваиваем NULL
        if (trim($this->credit_addational=='')) $this->credit_addational = new CDbExpression('NULL');
        //if (trim($this->requiment_summ=='')) $this->requiment_summ = new CDbExpression('NULL');
        if (trim($this->reduced_higher_NO_summ=='')) $this->reduced_higher_NO_summ = new CDbExpression('NULL');
        if (trim($this->reduced_arb_court_summ=='')) $this->reduced_arb_court_summ = new CDbExpression('NULL');
        if (trim($this->recovered_summ=='')) $this->recovered_summ = new CDbExpression('NULL');
        if (trim($this->civil_action_summ=='')) $this->civil_action_summ = new CDbExpression('NULL');
        if (trim($this->civil_action_repayment_summ=='')) $this->civil_action_repayment_summ = new CDbExpression('NULL');       
        if (trim($this->balance_dept_VNP=='')) $this->balance_dept_VNP = new CDbExpression('NULL');       
        if (trim($this->including_NP=='')) $this->including_NP = new CDbExpression('NULL');       
        if (trim($this->including_agent=='')) $this->including_agent = new CDbExpression('NULL');       
                
        // во избежание присваивание дате 01.01.1970 присваиваем дате NULL
        if (trim($this->resolution_date)=='') $this->resolution_date = new CDbExpression('NULL');
        if (trim($this->resolution_adop_sec_measure_ban_alien_date)=='') $this->resolution_adop_sec_measure_ban_alien_date = new CDbExpression('NULL');       
        if (trim($this->resolution_adop_sec_measure_susp_oper_date)=='') $this->resolution_adop_sec_measure_susp_oper_date = new CDbExpression('NULL');       
        if (trim($this->info_removal_register_NP_date)=='') $this->info_removal_register_NP_date = new CDbExpression('NULL');       
        if (trim($this->intro_date)=='') $this->intro_date = new CDbExpression('NULL');
        if (trim($this->adop_date)=='') $this->adop_date = new CDbExpression('NULL');
        if (trim($this->material_SLEDSTV_ORG_date)=='') $this->material_SLEDSTV_ORG_date = new CDbExpression('NULL');
        if (trim($this->result_see_SLEDSTV_ORG_filed_date)=='') $this->result_see_SLEDSTV_ORG_filed_date = new CDbExpression('NULL');
        if (trim($this->result_see_SLEDSTV_ORG_refused_date)=='') $this->result_see_SLEDSTV_ORG_refused_date = new CDbExpression('NULL');
        if (trim($this->material_to_UVD_date)=='') $this->material_to_UVD_date = new CDbExpression('NULL');
        if (trim($this->civil_action_date)=='') $this->civil_action_date = new CDbExpression('NULL');
        if (trim($this->query_in_otdel_UZ_date)=='') $this->query_in_otdel_UZ_date = new CDbExpression('NULL');
        if (trim($this->query_in_otdel_UZ_from_IFNS_date)=='') $this->query_in_otdel_UZ_from_IFNS_date = new CDbExpression('NULL');

        if ($this->isNewRecord) { $this->date_create = new CDbExpression('getdate()'); }                
                        
        return parent::beforeSave();
    }  
    
    // < Аудит > //
    public function afterSave()
    {
        if ($this->isNewRecord)
        {
            Yii::app()->audit->writeAudit(CAuditComponent::TYPE_OPERATION_DB_INSERT,'ReestrCheck',$this->id); 
        }
        elseif (!$this->isDelete)
        {
            Yii::app()->audit->writeAudit(CAuditComponent::TYPE_OPERATION_DB_UPDATE,'ReestrCheck',$this->id); 
        }
        return parent::afterSave();
    }     
    
    public function afterDelete()
    {
        Yii::app()->audit->writeAudit(CAuditComponent::TYPE_OPERATION_DB_DELETE,'ReestrCheck',$this->id);
        return parent::afterDelete();        
    }    
    // </ Аудит > //
    
    
    public function getYesNo($val)
    {        
        switch ($val)
        {
            case null: return null;
            case 0:  return 'нет';
            case 1:  return 'да';
            default: return null;
        }
    }
    
    
        
    
    public function getReestrProperty()
    {
        $ids = array();
        foreach ($this->properties as $v)        
            $ids[] = $v->id_directory_property;
        
        return $ids;
    }
    
    public function saveRelationProperty($vals)
    {
                
        ReestrCheckProperty::model()->deleteAll('id_reestr=:id_reestr', 
            array(':id_reestr'=>$this->id));
        
        
        if (!empty($vals))
        {        
            foreach ($vals as $val)
            {
                $modelReestrCheckProperty = new ReestrCheckProperty;
                $modelReestrCheckProperty->id_reestr = $this->id;
                $modelReestrCheckProperty->id_directory_property = $val;
                $modelReestrCheckProperty->save(false);
            }
        }
    }
    
    
    public function getMaterialSLEDSTVORGarticles()
    {
        $ids = array();        
        foreach ($this->reestr_material_SLEDSTV_ORG_article as $v)        
            $ids[] = $v->id_directory_article;
        
        return $ids;
    }
    
    public function saveRelationSLEDSTVORGarticles($vals)
    {
                
        ReestrCheckArticle::model()->deleteAll('id_reestr=:id_reestr AND field_name=:field_name', 
            array(':id_reestr'=>$this->id, ':field_name'=>'material_SLEDSTV_ORG_article'));
                
        if (!empty($vals))
        {        
            foreach ($vals as $val)
            {
                $modelReestrCheckArticle = new ReestrCheckArticle;
                $modelReestrCheckArticle->id_reestr = $this->id;
                $modelReestrCheckArticle->id_directory_article = $val;
                $modelReestrCheckArticle->field_name = 'material_SLEDSTV_ORG_article';
                $modelReestrCheckArticle->save(false);
            }
        }
    }
    
		
	public function getMaterialUVDarticles()
    {
        $ids = array();        
        foreach ($this->reestr_material_to_UVD_article as $v)        
            $ids[] = $v->id_directory_article;
        
        return $ids;                
    }
    
    public function saveRelationUVDarticles($vals)
    {
                
        ReestrCheckArticle::model()->deleteAll('id_reestr=:id_reestr AND field_name=:field_name', 
            array(':id_reestr'=>$this->id, ':field_name'=>'material_to_UVD_article'));
                
        if (!empty($vals))
        {        
            foreach ($vals as $val)
            {
                $modelReestrCheckArticle = new ReestrCheckArticle;
                $modelReestrCheckArticle->id_reestr = $this->id;
                $modelReestrCheckArticle->id_directory_article = $val;
                $modelReestrCheckArticle->field_name = 'material_to_UVD_article';
                $modelReestrCheckArticle->save(false);
            }
        }
    }
    
    
    public function getType_check_extension()
    {
        switch ($this->type_check)
        {
            case 1: return 'ВНП';
            case 2: return 'КНП'; 
            default: return '';
       }
    }
    
    public function getType_NP_extension()
    {
        return ($this->type_NP == 1) ? 'ЮЛ' : 'ФЛ'; 
    }
    
    
    public function getProperty_extension()
    {
        return implode(" / ", CHtml::listData($this->properties2, "id","value"));
    }
    
    public function getLast_measure_recovery_extension()
    {
        return (isset($this->article_last_measure_recovery) 
            ? $this->article_last_measure_recovery->value : '');
    }
    
    public function getMaterial_SLEDSTV_ORG_article_extension()
    {
        return implode(" / ", CHtml::listData($this->directoryArticleSLEDSTV_ORG, "id","value"));
    }
    
    public function getResult_see_SLEDSTV_ORG_filed_article_extension()
    {
        return (isset($this->result_see_SLEDSTV_ORG_filed_article->value) ? 
            $this->result_see_SLEDSTV_ORG_filed_article->value : '');
    }    
    
    public function getCivil_action_extension()
    {
        return $this->getYesNo($this->civil_action);
    }
    
    public function getCivil_action_result_see_extension()
    {
        return (isset($this->article_civil_action_result_see->value) ?
                    $this->article_civil_action_result_see->value : '');
    }
    
    public function getMaterial_to_UVD_article_extension()
    {
        return implode(" / ", CHtml::listData($this->directoryArticleUVD, "id","value"));
    }
    
    public function getResult_see_OVD_filed_extension()
    {
        return (isset($this->article_result_see_OVD_filed->value) ?
                    $this->article_result_see_OVD_filed->value : '');
    }
    
    public function getResult_see_OVD_refused_extension()
    {
        return (isset($this->article_result_see_OVD_refused->value) ?
                    $this->article_result_see_OVD_refused->value : '');
    }
    
    public function getCurrent_proc_bankruptcy_extension()
    {
        return (isset($this->directory_bankruptcy->value) ? 
            $this->directory_bankruptcy->value : '');
    }
    
    
}
