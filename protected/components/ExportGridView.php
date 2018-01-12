<?php

/** Компонент выполняющий экспорт в Excel из GridView
 *  Форматы: xls - (Excel 5), xlsx - (Excel2007)
**/
class ExportGridView extends CComponent
{    
    /*
    protected static $attributes = [
        'id' => 'УН',
        'id_check' => 'УН проверки',
		'code_NO' => 'Код налогового органа',
        'code_NO_current_dept' => 'Код НО по текущей задолженности',
		'inn_NP' => 'ИНН налогоплательщика',
		'kpp_NP' => 'КПП налогоплательщика',
		'name_NP' => 'Наименование налогоплательщика',
		'type_check' => 'Вид проверки',
        
        // Реестр не взысканных (не в полном объеме взысканных) дополнительно 
        //     начисленных сумм налогов, по итогам проведения налоговых проверок
		'credit_addational' => 'Доначислено  по решению всего, с учетом сумм уменьшения по проверкам (тыс. руб.)',				
		'resolution_date' => 'Дата решения',				
		'resolution_number' => 'Номер решения',				
		'reduced_higher_NO_summ' => 'Уменьшено по решению вышестоящего налогового органа, всего (тыс. руб.)',
		'reduced_arb_court_summ' => 'Уменьшено по решению Арбитражного суда (тыс. руб.)',
		'recovered_summ' => 'Взыскано (тыс. руб.)',
		'resolution_adop_sec_measure_ban_alien_num' => 'Решение о принятии обеспечительных мер (по невзысканным платежам) / запрет на отчуждение имущества / номер',
		'resolution_adop_sec_measure_ban_alien_date' => 'Решение о принятии обеспечительных мер (по невзысканным платежам) / запрет на отчуждение имущества / дата',
        'resolution_adop_sec_measure_susp_oper_num' => 'Решение о принятии обеспечительных мер (по невзысканным платежам) / приостановление операций по счетам / номер',
        'resolution_adop_sec_measure_susp_oper_date' => 'Решение о принятии обеспечительных мер (по невзысканным платежам) / приостановление операций по счетам / дата',
        'info_removal_register_NP_date' => 'Сведения о снятии с учета налогоплательщика (Дата снятия с учета)',
        'info_removal_register_NP_to_NO' => 'Сведения о снятии с учета налогоплательщика (НО куда поставлен на учет)',
        'info_removal_register_NP_reason' => 'Сведения о снятии с учета налогоплательщика (причина снятия )',
        
        // Сведения о процедурах банкротства                        
        'current_proc_bankruptcy' => 'Текущая процедура банкротства',
		'intro_date' => 'Дата введения',
		'last_measure_recovery' => 'Последняя мера взыскания',
		'adop_date' => 'Дата принятия',
		'property' => 'Наличие имущества',        
        'note_bankruptcy' => 'Примечание',
        
        // Сведения о передаче материалов в следственные органы в порядке ст. 32 НК РФ            
		'material_SLEDSTV_ORG_date' => 'Материалы переданы в следственные органы по ст. 32 НК РФ (дата письма)',
		'material_SLEDSTV_ORG_num' => 'Материалы переданы в следственные органы по ст. 32 НК РФ (номер письма)',
		'materialSLEDSTVORGarticles' => 'Материалы переданы в следственные органы по ст. 32 НК РФ (ст. УК РФ)',
		'result_see_SLEDSTV_ORG_filed_article' => 'Результат рассмотрения следственными органами материалов налоговых проверок (возбуждено УД)',
		'result_see_SLEDSTV_ORG_filed_date' => 'Результат рассмотрения следственными органами материалов налоговых проверок (возбуждено УД) (дата)',
        'result_see_SLEDSTV_ORG_filed_num' => 'Результат рассмотрения следственными органами материалов налоговых проверок (возбуждено УД) (номер)',            
        'result_see_SLEDSTV_ORG_refused_article' => 'Результат рассмотрения следственными органами материалов налоговых проверок  (отказано в возбуждении УД)',
        'result_see_SLEDSTV_ORG_refused_date' => 'Результат рассмотрения следственными органами материалов налоговых проверок  (отказано в возбуждении УД) (дата)',
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
		'result_see_OVD_refused' => 'Результат рассмотрения материалов органами  внутренних дел (отказано в возбуждении УД)',
		'note_OVD' => 'Примечание',
				
    ];
    */
    
    private static function arrtibuteExtension()
    {
        return array(
            'type_check' => 'type_check_extension',
            'type_NP' => 'type_NP_extension',
            'property' => 'property_extension',  
            'last_measure_recovery' => 'last_measure_recovery_extension',
            'material_SLEDSTV_ORG_article' => 'material_SLEDSTV_ORG_article_extension',
            'result_see_SLEDSTV_ORG_filed_article' => 'result_see_SLEDSTV_ORG_filed_article_extension',
            'civil_action' => 'civil_action_extension',
            'civil_action_result_see' => 'civil_action_result_see_extension',
            'material_to_UVD_article' => 'Material_to_UVD_article_extension',      
            'result_see_OVD_filed' => 'result_see_OVD_filed_extension',
            'result_see_OVD_refused' => 'result_see_OVD_refused_extension',
            'current_proc_bankruptcy' => 'current_proc_bankruptcy_extension', 
        );
    }
        
    
    private static function checkRequest($request)
    {
        // список обязательных параметров, переданных через GET/POST-запрос,
        //     которые должны присутствовать в массиве $request
        $requedAttributes = array(
            'ReestrCheck', 'exportFormat', 'columns', 'export'
        );        
        
        if (!isset($request) || ($request===null)) return false;
        foreach ($requedAttributes as $attrubute)
        {
            if (!isset($request[$attrubute])) return false;
        }                  
        
        return true;
    }
    
    
    public static function export($getParams, $archive=false)
    {
        set_time_limit(5*60); // timeout - 5 минут
        
        $model=new ReestrCheck('search');
		$model->unsetAttributes();
        $model->isArchive = $archive;
        //if (isset($getParams['useFilter']) && $getParams['useFilter']) 
        if (isset($getParams['ReestrCheck']))
        {
            $model->attributes=$getParams['ReestrCheck'];
        }
        
        /*if (isset($getParams['ReestrCheck']['isArchive']) && $getParams['ReestrCheck']['isArchive'])
        {
            $model->isArchive = true;
        }*/
        
        $reestrCheck = $model->search(false);         
        $reestrCheckAttributes = ReestrCheck::model()->attributeLabels();
        
        $columnsExtension = self::arrtibuteExtension();      
          
        $str_excel = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">'."\n";
        $str_excel .= '<head>'."\n";
        $str_excel .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">'."\n";
        $str_excel .= '<meta name=ProgId content=Excel.Sheet>'."\n";
        $str_excel .= '</head>'."\n";
        
        $str_excel .= '<body>'."\n";
        $str_excel .= '<table x:str>'."\n";
        $str_excel .= '<thead>'."\n";
        
        $columns = Yii::app()->session['viewColumnGridView'];
        if (count($columns)==0)
        {
            $columns = ReestrCheck::model()->defaultAttributeLabels();
        }
                                       
        // titles
        foreach ($columns as $v)
        {
            $str_excel .= '<th>'.$reestrCheckAttributes[$v].'</th>'."\n";
            
        }        
        $str_excel .= '</thead>'."\n";
        
        
        // data
        foreach ($reestrCheck as $data)
        {            
            $str_excel .= '<tr>'."\n";
            
            foreach ($columns as $v)
            {
                if (isset($columnsExtension[$v]))
                {
                    $str_excel .= '<td>'.$data->$columnsExtension[$v].'</td>'."\n";    
                }
                else
                {
                    $str_excel .= '<td>'.$data->$v.'</td>'."\n";
                }
            }
            /*
            // общее
            $str_excel .= '<td>'.$data->id.'</td>'."\n";
            $str_excel .= '<td>'.$data->id_check.'</td>'."\n";
            $str_excel .= '<td>'.$data->code_NO.'</td>'."\n";
            $str_excel .= '<td>'.$data->code_NO_current_dept.'</td>'."\n";
            $str_excel .= '<td>'.$data->inn_NP.'</td>'."\n";
            $str_excel .= '<td>'.$data->kpp_NP.'</td>'."\n";
            $str_excel .= '<td>'.$data->name_NP.'</td>'."\n";
            $str_excel .= '<td>'.($data->type_check==1 ? 'ВНП' : 'КНП').'</td>'."\n";
                    
            // Реестр не взысканных (не в полном объеме взысканных) дополнительно 
            //     начисленных сумм налогов, по итогам проведения налоговых проверок
            $str_excel .= '<td>'.$data->credit_addational.'</td>'."\n";
            $str_excel .= '<td>'.$data->resolution_date.'</td>'."\n";
            $str_excel .= '<td>'.$data->resolution_number.'</td>'."\n";
            $str_excel .= '<td>'.$data->reduced_higher_NO_summ.'</td>'."\n";
            $str_excel .= '<td>'.$data->reduced_arb_court_summ.'</td>'."\n";
            $str_excel .= '<td>'.$data->recovered_summ.'</td>'."\n";
            $str_excel .= '<td>'.$data->resolution_adop_sec_measure_ban_alien_num.'</td>'."\n";
            $str_excel .= '<td>'.$data->resolution_adop_sec_measure_ban_alien_date.'</td>'."\n";
            $str_excel .= '<td>'.$data->resolution_adop_sec_measure_susp_oper_num.'</td>'."\n";
			$str_excel .= '<td>'.$data->resolution_adop_sec_measure_susp_oper_date.'</td>'."\n";		
            $str_excel .= '<td>'.$data->info_removal_register_NP_date.'</td>'."\n";
            $str_excel .= '<td>'.$data->info_removal_register_NP_to_NO.'</td>'."\n";
            $str_excel .= '<td>'.$data->info_removal_register_NP_reason.'</td>'."\n";
            		
            // Сведения о процедурах банкротства      
            $str_excel .= '<td>'.$data->current_proc_bankruptcy.'</td>'."\n";
            $str_excel .= '<td>'.$data->intro_date.'</td>'."\n";
            $str_excel .= '<td>'.$data->last_measure_recovery.'</td>'."\n";
            $str_excel .= '<td>'.$data->adop_date.'</td>'."\n";
                       
            $str_excel .= '<td>';
            
            $criteriaProperty = new CDbCriteria();
            $criteriaProperty->join = 'JOIN {{reestr_check_property}} reestr_check_property
                ON t.id=reestr_check_property.id_directory_property';
            $criteriaProperty->condition = 'id_reestr=:id_reestr';
            $criteriaProperty->params = array(':id_reestr'=>$data->id);
            
            $modelProperty = DirectoryProperty::model()->findAll($criteriaProperty);                         
            foreach ($modelProperty as $valueProperty)
            {
                $str_excel .= $valueProperty->value."\n";
            }
                        
            $str_excel .= '</td>'."\n";                        
            $str_excel .= '<td>'.$data->note_bankruptcy.'</td>'."\n";
                                                                                          
            // Сведения о передаче материалов в следственные органы в порядке ст. 32 НК РФ            
            $str_excel .= '<td>'.$data->material_SLEDSTV_ORG_date.'</td>'."\n";
            $str_excel .= '<td>'.$data->material_SLEDSTV_ORG_num.'</td>'."\n";
            
            $str_excel .= '<td>';            
            $criteriaArticle = new CDbCriteria();
            $criteriaArticle->join = 'JOIN {{reestr_check_article}} reestr_check_article
                ON t.id = reestr_check_article.id_directory_article 
                    AND reestr_check_article.field_name = \'material_SLEDSTV_ORG_article\'';
            $criteriaArticle->condition = 'id_reestr=:id_reestr';
            $criteriaArticle->params = array(':id_reestr'=>$data->id);
            
            $modelArticle = DirectoryArticle::model()->findAll($criteriaArticle);                         
            foreach ($modelArticle as $valueArticle)
            {
                $str_excel .= $valueArticle->value."\n";
            }                        
            $str_excel .= '</td>'."\n";
            
            $str_excel .= '<td>'.$data->result_see_SLEDSTV_ORG_filed_article.'</td>'."\n";
            $str_excel .= '<td>'.$data->result_see_SLEDSTV_ORG_filed_date.'</td>'."\n";
            $str_excel .= '<td>'.$data->result_see_SLEDSTV_ORG_filed_num.'</td>'."\n";
            $str_excel .= '<td>'.$data->result_see_SLEDSTV_ORG_refused_article.'</td>'."\n";
            $str_excel .= '<td>'.$data->result_see_SLEDSTV_ORG_refused_date.'</td>'."\n";
            $str_excel .= '<td>'.$data->civil_action.'</td>'."\n";
            $str_excel .= '<td>'.$data->civil_action_date.'</td>'."\n";
            $str_excel .= '<td>'.$data->civil_action_summ.'</td>'."\n";
            $str_excel .= '<td>'.$data->civil_action_result_see.'</td>'."\n";
            $str_excel .= '<td>'.$data->civil_action_repayment_summ.'</td>'."\n";
            $str_excel .= '<td>'.$data->note_SLEDSTV_ORG.'</td>'."\n";
            
            // Сведения о передаче материалов в органы внутренних дел в порядке ст. 82 НК РФ
            $str_excel .= '<td>'.$data->material_to_UVD_date.'</td>'."\n";
            $str_excel .= '<td>'.$data->material_to_UVD_num.'</td>'."\n";
            
            $str_excel .= '<td>';            
            $criteriaArticle = new CDbCriteria();
            $criteriaArticle->join = 'JOIN {{reestr_check_article}} reestr_check_article
                ON t.id = reestr_check_article.id_directory_article 
                    AND reestr_check_article.field_name = \'material_to_UVD_article\'';
            $criteriaArticle->condition = 'id_reestr=:id_reestr';
            $criteriaArticle->params = array(':id_reestr'=>$data->id);
            
            $modelArticle = DirectoryArticle::model()->findAll($criteriaArticle);                         
            foreach ($modelArticle as $valueArticle)
            {
                $str_excel .= $valueArticle->value."\n";
            }                        
            $str_excel .= '</td>'."\n";
                       
            $str_excel .= '<td>'.$data->result_see_OVD_filed.'</td>'."\n";
            $str_excel .= '<td>'.$data->result_see_OVD_refused.'</td>'."\n";
            $str_excel .= '<td>'.$data->note_OVD.'</td>'."\n";
            */
            	
            $str_excel .= '</tr>';
        }
        $str_excel .= '</table>';
        
        $str_excel .= '</body>'."\n";
        $str_excel .= '</html>'."\n";        
        
        
        ob_end_clean();
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Reestr_'.date('d.m.Y').'.xls"');
        header('Cache-Control: max-age=0');
        echo $str_excel;
        Yii::app()->end();
        
    }
    
    
    /*
    public static function export($getParams)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(5*60); // timeout - 5 минут
        
        $format = array();
        $format['ext']='xlsx';
        $format['contentTypeApplication']='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        $format['applicationName']='Excel2007';
        //switch ($getParams['exportFormat'])
        //{
        //    case 'xls': 
        //        $format['ext']='xls';
        //        $format['contentTypeApplication']='application/vnd.ms-excel';
        //        $format['applicationName']='Excel5';
        //        break;
        //    case 'xlsx':
        //        $format['ext']='xlsx';
        //        $format['contentTypeApplication']='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        //        $format['applicationName']='Excel2007';
        //        break;                               
        //}
        
        $model=new ReestrCheck('search');
		$model->unsetAttributes();
        if (isset($getParams['ReestrCheck'])) 
        {
            $model->attributes=$getParams['ReestrCheck'];
        }                
        $reestrCheck = $model->search(false);
        
       
        $phpExcelPath = Yii::getPathOfAlias('ext.phpExcel');
        // Turn off our amazing library autoload
        spl_autoload_unregister(array('YiiBase','autoload'));
        
        // making use of our reference, include the main class
        // when we do this, phpExcel has its own autoload registration
        // procedure (PHPExcel_Autoloader::Register();)
        include($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');
        //spl_autoload_register(array('YiiBase', 'autoload'));
        
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;                
        $cacheSettings = array(
             'memoryCacheSize' => '1GB',
        );
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
        
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();        
                
        $objPHPExcel->getDefaultStyle()->getFont()
            ->setName('Arila')->setSize(8);
    
        // шапка
        $objPHPExcel->getActiveSheet()->mergeCells('A1:J1');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Реестр невзысканных проверок');
        $objPHPExcel->getActiveSheet()->getStyle('A1')
            ->getFont()->setSize(18)->setBold(true);
        
        
        $excelLastRow = 2; // текущая строка в Excel
        $reestrCheckAttributes = ReestrCheck::model()->attributeLabels();
                
        $columns = Yii::app()->session['viewColumnGridView'];
        if (count($columns)==0)
        {
            $columns = ReestrCheck::model()->defaultAttributeLabels();
        }
                        
        // Форматирование. 
        //   Строка с наименованием ячеек (жирный шрифт, серый фон) 
        $cells = "A".$excelLastRow.":"            
            .PHPExcel_Cell::stringFromColumnIndex(count($columns)-1).$excelLastRow;
        //print_r(Yii::app()->session['viewColumnGridView']);
        //print_r($cells); exit;
        $objPHPExcel->getActiveSheet()
            ->getStyle($cells)
            ->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()->setRGB('C0C0C0');
        $objPHPExcel->getActiveSheet()
            ->getStyle($cells)->getFont()
            ->setSize(10)->setBold(true);
        
        
        for ($iRow=0; $iRow<count($reestrCheck); $iRow++)
        {
            // добавляем заголовки
            if ($iRow===0) 
            {
                for ($iColumn=0; $iColumn<count($columns); $iColumn++)
                {
                    $objPHPExcel->getActiveSheet()
                        ->setCellValueByColumnAndRow($iColumn, $excelLastRow, 
                            $reestrCheckAttributes[$columns[$iColumn]]);
                }
                $excelLastRow++;
            }
            
            // вставляем данные
            for ($iColumn=0; $iColumn<count($columns); $iColumn++)
            {
                $objPHPExcel->getActiveSheet()
                    ->setCellValueByColumnAndRow($iColumn, $excelLastRow, 
                        $reestrCheck[$iRow][$columns[$iColumn]]);
            }
            $excelLastRow++;
            
        } 
                                
             
        // Set active sheet index to the first sheet, 
        // so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('Реестр');

                              
        ob_end_clean();
        
        header('Content-Type: '.$format['contentTypeApplication']);
        header('Content-Disposition: attachment;filename="Reestr_'.date('d.m.Y').'.'.$format['ext'].'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $format['applicationName']);
        $objWriter->save('php://output');
        Yii::app()->end();
                        
    }
    */
    
    

}

?>
    
    