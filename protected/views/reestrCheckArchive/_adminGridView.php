<div class="search-form-result" style="margin-top:5px; display: none;">
<br /><br />
<?php Yii::app()->clientScript->registerScript('exportGridViewToFile', "        
    $('#btnExportSubmit').on('click', function(){
        window.location = '".$this->createUrl('admin')."?' 
            + $('#search-form :input').serialize() + '&export=true';        
    });
"); 
?>
&nbsp;
<?php $this->widget('bootstrap.widgets.TbButton', array(
		'label'=>'Экспорт в Excel',
		'type'=>'success',
		'htmlOptions'=>array(
            //'data-toggle'=>'modal',           
            'id'=>'btnExportSubmit'
        ),
	)); 
?>
&nbsp;
<?php $this->widget('bootstrap.widgets.TbButton', array(
		'label'=>'Обновить',
		'type'=>'info',
        'icon' => 'refresh',
		'htmlOptions'=>array(           
            'onclick' => '$(\'#search-form\').submit();',
        ),
	)); 
 ?>

<?php
    
    $result_search = $model->search();
    
    $this->widget('bootstrap.widgets.TbGridView',array(
    	'id'=>'reestr-check-grid',
    	'dataProvider'=>$result_search[0],    
        'afterAjaxUpdate'=>"function() { loadDatePicker();  }",
    	'columns'=>array(
              
            // УН
            array(
                'name'=>'id',                
                'visible'=>in_array('id', Yii::app()->session['viewColumnGridView']),                
            ),	
            
            // УН проверки
            array(
                'name'=>'id_check',
                'visible'=>in_array('id_check', Yii::app()->session['viewColumnGridView']),
                
            ),
            
            // Код налогового органа
            array(
                'name'=>'code_NO',
                'value'=>'$data->code_NO',               
                'visible'=>in_array('code_NO', Yii::app()->session['viewColumnGridView']),
            ),
            
            // Код НО по текущей задолженности
            array(
                'name'=>'code_NO_current_dept',
                'value'=>'$data->code_NO_current_dept',               
                'visible'=>in_array('code_NO_current_dept', Yii::app()->session['viewColumnGridView']),
            ),
            
            // ИНН налогоплательщика
    		array(
                'name'=>'inn_NP',
                'visible'=>in_array('inn_NP', Yii::app()->session['viewColumnGridView']),
            ),
            
            // КПП налогоплательщика
    		array(
                'name'=>'kpp_NP',
                'visible'=>in_array('kpp_NP', Yii::app()->session['viewColumnGridView']),
            ),  
            
            // Наименование налогоплательщика          
    		array(
                'name'=>'name_NP',
                'visible'=>in_array('name_NP', Yii::app()->session['viewColumnGridView']),
            ),
            
            // УН плательщика
    		array(
                'name'=>'id_NP',
                'visible'=>in_array('id_NP', Yii::app()->session['viewColumnGridView']),
            ),
            
            // Вид плательщика   
    		array(
                'name'=>'type_NP',
                'value'=>'($data->type_NP==1 ? "ЮЛ" : "ФЛ")',
                'visible'=>in_array('type_NP', Yii::app()->session['viewColumnGridView']),
            ),
            
            // Вид проверки
    		array(
    			'name'=>'type_check',			
                'value'=>'(isset($data->directory_type_check->value) ? 
                    $data->directory_type_check->value : null)',    			
                'visible'=>in_array('type_check', Yii::app()->session['viewColumnGridView']),
    		),
             
            array(
                'name'=>'comment_arch',
                'visible'=>in_array('comment_arch', Yii::app()->session['viewColumnGridView']),
            ), 
                                
            /*array(
                'name' => 'material_SLEDSTV_ORG_article',
                'type' => 'raw',
                'value' => 'implode("<br />", 
                    CHtml::listData($data->directoryArticleSLEDSTV_ORG,"id","value"))',
                'visible'=>in_array('material_SLEDSTV_ORG_article', Yii::app()->session['viewColumnGridView']),    
            ),*/
            
            /** *********************************************************
             * Реестр не взысканных (не в полном объеме взысканных) 
             *   дополнительно начисленных сумм налогов, 
             *   по итогам проведения налоговых проверок
             * **********************************************************/
            
            // Доначислено по решению всего (тыс. руб.)
    		array(
                'name'=>'credit_addational',
                'visible'=>in_array('credit_addational', Yii::app()->session['viewColumnGridView']),
                'footer'=>$result_search[1]->credit_addational,
                'footerHtmlOptions'=>array('class'=>'style_row_footer'),
            ),
            
            // Дата решения
            array(
                'name'=>'resolution_date',
                'visible'=>in_array('resolution_date', Yii::app()->session['viewColumnGridView']),                                       
            ),
            
            // Номер решения
            array(
                'name'=>'resolution_number',
                'visible'=>in_array('resolution_number', Yii::app()->session['viewColumnGridView']),
            ),            
            
            // Номер требования об уплате
            array(
                'name'=>'requiment_number',
                'visible'=>in_array('requiment_number', Yii::app()->session['viewColumnGridView']),
            ),
            
            // Дата требования об уплате
            array(
                'name'=>'requiment_date',
                'visible'=>in_array('requiment_date', Yii::app()->session['viewColumnGridView']),                            
            ),
            
            // Срок уплаты по требованию
            array(
                'name'=>'requiment_term',
                'visible'=>in_array('requiment_term', Yii::app()->session['viewColumnGridView']),                       
            ),
            
            // Сумма включенная в требование (тыс. руб.)
            array(
                'name'=>'requiment_summ',
                'visible'=>in_array('requiment_summ', Yii::app()->session['viewColumnGridView']),
                'footer'=>$result_search[1]->requiment_summ,
                'footerHtmlOptions'=>array('class'=>'style_row_footer'),
            ),
            
            // Остаток непогашенной суммы по требованию (тыс. руб.)
            array(
                'name'=>'requiment_summ_rest',
                'visible'=>in_array('requiment_summ_rest', Yii::app()->session['viewColumnGridView']),
                'footer'=>$result_search[1]->requiment_summ_rest,
                'footerHtmlOptions'=>array('class'=>'style_row_footer'),                
            ),    
            // Взыскано  (тыс. руб.)
            array(
                'name'=>'recovered_summ',
                'visible'=>in_array('recovered_summ', Yii::app()->session['viewColumnGridView']),
                'footer'=>$result_search[1]->recovered_summ,
                'footerHtmlOptions'=>array('class'=>'style_row_footer'),
            ),
            
                
            // Уменьшено по решению вышестоящего налогового органа, всего (тыс. руб.)
            array(
                'name'=>'reduced_higher_NO_summ',
                'visible'=>in_array('reduced_higher_NO_summ', Yii::app()->session['viewColumnGridView']),
                'footer'=>$result_search[1]->reduced_higher_NO_summ,
                'footerHtmlOptions'=>array('class'=>'style_row_footer'),
                
            ),
            
            // Уменьшено по решению Арбитражного суда   (тыс. руб.)
            array(
                'name'=>'reduced_arb_court_summ',
                'visible'=>in_array('reduced_arb_court_summ', Yii::app()->session['viewColumnGridView']),
                'footer'=>$result_search[1]->reduced_arb_court_summ,
                'footerHtmlOptions'=>array('class'=>'style_row_footer'),
            ),
            
            
            
            // Решение о принятии обеспечительных мер (по невзысканным платежам) / запрет на отчуждение имущества / номер
            array(
                'name'=>'resolution_adop_sec_measure_ban_alien_num',            
                'visible'=>in_array('resolution_adop_sec_measure_ban_alien_num', Yii::app()->session['viewColumnGridView']),
            ),
            
            // Решение о принятии обеспечительных мер (по невзысканным платежам) / запрет на отчуждение имущества / дата
            array(
                'name'=>'resolution_adop_sec_measure_ban_alien_date',
                'visible'=>in_array('resolution_adop_sec_measure_ban_alien_date', Yii::app()->session['viewColumnGridView']),            
                /*'filter'=>'<div class="filter-date-width">'.CHtml::activeTextField($model, 'resolution_adop_sec_measure_ban_alien_date', array('isdate'=>'')).'<br /><br />'
                    .CHtml::activeTextField($model, 'resolution_adop_sec_measure_ban_alien_date_to', array('isdate'=>'')).'</div>',*/                         
            ),
            
            // Решение о принятии обеспечительных мер (по невзысканным платежам) / приостановление операций по счетам / номер
            array(
                'name'=>'resolution_adop_sec_measure_susp_oper_num',            
                'visible'=>in_array('resolution_adop_sec_measure_susp_oper_num', Yii::app()->session['viewColumnGridView']),
            ),
            
            // Решение о принятии обеспечительных мер (по невзысканным платежам) / приостановление операций по счетам / дата
            array(
                'name'=>'resolution_adop_sec_measure_susp_oper_date',
                'visible'=>in_array('resolution_adop_sec_measure_susp_oper_date', Yii::app()->session['viewColumnGridView']),            
                /*'filter'=>'<div class="filter-date-width">'.CHtml::activeTextField($model, 'resolution_adop_sec_measure_susp_oper_date', array('isdate'=>'')).'<br /><br />'
                    .CHtml::activeTextField($model, 'resolution_adop_sec_measure_susp_oper_date_to', array('isdate'=>'')).'</div>',*/                 
            ),
            
            // Сведения о снятии с учета налогоплательщика (Дата снятия с учета)
            array(
                'name'=>'info_removal_register_NP_date',
                'visible'=>in_array('info_removal_register_NP_date', Yii::app()->session['viewColumnGridView']),            
                /*'filter'=>'<div class="filter-date-width">'.CHtml::activeTextField($model, 'info_removal_register_NP_date', array('isdate'=>'')).'<br /><br />'
                    .CHtml::activeTextField($model, 'info_removal_register_NP_date_to', array('isdate'=>'')).'</div>',*/                 
            ),
            
            // Сведения о снятии с учета налогоплательщика (НО куда поставлен на учет)
            array(
                'name'=>'info_removal_register_NP_to_NO',            
                'visible'=>in_array('info_removal_register_NP_to_NO', Yii::app()->session['viewColumnGridView']),
            ),
            
            // Сведения о снятии с учета налогоплательщика (причина снятия )
            
            
            
            /** ****************************************
             *    Сведения о процедурах банкротства
             * *****************************************/
            
            // Текущая процедура банкротства
            array(
                'name'=>'current_proc_bankruptcy',
                /*'value'=>'(isset($data->directory_bankruptcy->value) ? 
                    $data->directory_bankruptcy->value : null)',*/
                //'filter'=>CHtml::listData(DirectoryBankruptcy::model()->findAll(),'id','value'),
                'visible'=>in_array('current_proc_bankruptcy', Yii::app()->session['viewColumnGridView']),
            ),
            
            // Дата введения 
            array(
                'name'=>'intro_date',
                'visible'=>in_array('intro_date', Yii::app()->session['viewColumnGridView']),            
                /*'filter'=>'<div class="filter-date-width">'.CHtml::activeTextField($model, 'intro_date', array('isdate'=>'')).'<br /><br />'
                    .CHtml::activeTextField($model, 'intro_date_to', array('isdate'=>'')).'</div>',*/               
            ),
            
            // Последняя мера взыскания
            array(
                'name'=>'last_measure_recovery',
                'value'=>'(isset($data->article_last_measure_recovery->value) ?
                    $data->article_last_measure_recovery->value : "")',
                /*'filter'=>CHtml::listData(DirectoryArticle::model()->findAll(array(
                            'condition'=>'type_article=0')),'id','value'),*/
                'visible'=>in_array('last_measure_recovery', Yii::app()->session['viewColumnGridView']),
            ),
            
            // Дата принятия
            array(
                'name'=>'adop_date',
                'visible'=>in_array('adop_date', Yii::app()->session['viewColumnGridView']),            
                /*'filter'=>'<div class="filter-date-width">'.CHtml::activeTextField($model, 'adop_date', array('isdate'=>'')).'<br /><br />'
                    .CHtml::activeTextField($model, 'adop_date_to', array('isdate'=>'')).'</div>',*/               
            ),
            
            // Наличие имущества
            array(
                'name'=>'property',
                'value'=>'implode("<br />",CHtml::listData($data->properties2, "id","value"))',
                'type'=>'raw',                
                'visible'=>in_array('property', Yii::app()->session['viewColumnGridView']),
            ),
            
            // Примечание
            array(
                'name'=>'note_bankruptcy',
                'visible'=>in_array('note_bankruptcy', Yii::app()->session['viewColumnGridView']),
            ),
            
            // Остаток задолженности по выездной налоговой проверке (тыс. руб.)
            array(
                'name'=>'balance_dept_VNP',
                'visible'=>in_array('balance_dept_VNP', Yii::app()->session['viewColumnGridView']),
                'footer'=>$result_search[1]->balance_dept_VNP,
                'footerHtmlOptions'=>array('class'=>'style_row_footer'),
            ),
            
            // В том числе по налогу (налогоплательщик) (тыс. руб.)
            array(
                'name'=>'including_NP',
                'visible'=>in_array('including_NP', Yii::app()->session['viewColumnGridView']),
                'footer'=>$result_search[1]->including_NP,
                'footerHtmlOptions'=>array('class'=>'style_row_footer'),
            ),
            
            // В том числе по налогу (налоговый агент) (тыс. руб.)
            array(
                'name'=>'including_agent',
                'visible'=>in_array('including_agent', Yii::app()->session['viewColumnGridView']),
                'footer'=>$result_search[1]->including_agent,
                'footerHtmlOptions'=>array('class'=>'style_row_footer'),
            ),
            
            
            /** ***************************************************************************
             * Сведения о передаче материалов в следственные органы в порядке ст. 32 НК РФ
             * ************************************************************************** */
             
            // Материалы переданы в следственные органы по ст. 32 НК РФ (дата письма)
            array(
                'name'=>'material_SLEDSTV_ORG_date',
                'visible'=>in_array('material_SLEDSTV_ORG_date', Yii::app()->session['viewColumnGridView']),            
                /*'filter'=>'<div class="filter-date-width">'.CHtml::activeTextField($model, 'material_SLEDSTV_ORG_date', array('isdate'=>'')).'<br /><br />'
                    .CHtml::activeTextField($model, 'material_SLEDSTV_ORG_date_to', array('isdate'=>'')).'</div>',*/                  
            ),
            
            // Материалы переданы в следственные органы по ст. 32 НК РФ (номер письма)
            array(
                'name'=>'material_SLEDSTV_ORG_num',
                'visible'=>in_array('material_SLEDSTV_ORG_num', Yii::app()->session['viewColumnGridView']),
            ),
            
            // Материалы переданы в следственные органы по ст. 32 НК РФ (ст. УК РФ)
            array(
                'name'=>'material_SLEDSTV_ORG_article',
                'value'=>'implode("<br />",CHtml::listData(
                        $data->directoryArticleSLEDSTV_ORG,"id","value"))',
                /*'filter'=>CHtml::listData(DirectoryArticle::model()->findAll(array(
                            'condition'=>'type_article=1')),'id','value'),*/
                'visible'=>in_array('material_SLEDSTV_ORG_article', Yii::app()->session['viewColumnGridView']),
                'type'=>'raw',
            ),
            
            // Результат рассмотрения следственными органами материалов налоговых проверок (возбуждено УД) (статья УК РФ)
            array(
                'name'=>'result_see_SLEDSTV_ORG_filed_article',
                'value'=>'(isset($data->article_result_see_SLEDSTV_ORG_filed->value) ?
                    $data->article_result_see_SLEDSTV_ORG_filed->value : "")',
                /*'filter'=>CHtml::listData(DirectoryArticle::model()->findAll(array(
                            'condition'=>'type_article=1')),'id','value'),*/
                'visible'=>in_array('result_see_SLEDSTV_ORG_filed_article', Yii::app()->session['viewColumnGridView']),
            ),
            
            // Результат рассмотрения следственными органами материалов налоговых проверок (возбуждено УД) (дата)
            array(
                'name'=>'result_see_SLEDSTV_ORG_filed_date',
                'visible'=>in_array('result_see_SLEDSTV_ORG_filed_date', Yii::app()->session['viewColumnGridView']),            
                /*'filter'=>'<div class="filter-date-width">'.CHtml::activeTextField($model, 'result_see_SLEDSTV_ORG_filed_date', array('isdate'=>'')).'<br /><br />'
                    .CHtml::activeTextField($model, 'result_see_SLEDSTV_ORG_filed_date_to', array('isdate'=>'')).'</div>',*/                    
            ),
            
            // Результат рассмотрения следственными органами материалов налоговых проверок (возбуждено УД) (номер)
            array(
                'name'=>'result_see_SLEDSTV_ORG_filed_num',
                'visible'=>in_array('result_see_SLEDSTV_ORG_filed_num', Yii::app()->session['viewColumnGridView']),
            ),
            
            
            // Результат рассмотрения следственными органами материалов налоговых проверок  (отказано в возбуждении УД)
            array(
                'name'=>'result_see_SLEDSTV_ORG_refused_article',                
                'visible'=>in_array('result_see_SLEDSTV_ORG_refused_article', Yii::app()->session['viewColumnGridView']),
            ),
            
            // Результат рассмотрения следственными органами материалов налоговых проверок (отказано в возбуждении УД) (дата)
            array(
                'name'=>'result_see_SLEDSTV_ORG_refused_date',
                'visible'=>in_array('result_see_SLEDSTV_ORG_refused_date', Yii::app()->session['viewColumnGridView']),            
                /*'filter'=>'<div class="filter-date-width">'.CHtml::activeTextField($model, 'result_see_SLEDSTV_ORG_refused_date', array('isdate'=>'')).'<br /><br />'
                    .CHtml::activeTextField($model, 'result_see_SLEDSTV_ORG_refused_date_to', array('isdate'=>'')).'</div>',*/               
            ),
            
            // Предъявлен гражданский  иск о возмещении ущерба по материалам налоговых органов 
            array(
                'name'=>'civil_action',
                'value'=>'$data->getYesNo($data->civil_action)',
                //'filter'=>$model->yesNoValues(),
                'visible'=>in_array('civil_action', Yii::app()->session['viewColumnGridView']),
            ),
            
            // Дата предъявления гражданского иска о возмещении ущерба по материалам налоговых органов
            array(
                'name'=>'civil_action_date',
                'visible'=>in_array('civil_action_date', Yii::app()->session['viewColumnGridView']),            
                /*'filter'=>'<div class="filter-date-width">'.CHtml::activeTextField($model, 'civil_action_date', array('isdate'=>'')).'<br /><br />'
                    .CHtml::activeTextField($model, 'civil_action_date_to', array('isdate'=>'')).'</div>',*/                
            ),
            
            // Предъявлен гражданский иск на сумму (тыс. руб)
            array(
                'name'=>'civil_action_summ',
                'visible'=>in_array('civil_action_summ', Yii::app()->session['viewColumnGridView']),
                'footer'=>$result_search[1]->civil_action_summ,
                'footerHtmlOptions'=>array('class'=>'style_row_footer'),
            ),
            
            // Результат рассмотрения судебными органами гражданского иска
            array(
                'name'=>'civil_action_result_see',
                'value'=>'(isset($data->article_civil_action_result_see->value) ?
                    $data->article_civil_action_result_see->value : "")',
                //'filter'=>CHtml::listData(DirectoryCivilActionResult::model()->findAll(),'id','value'),
                'visible'=>in_array('civil_action_result_see', Yii::app()->session['viewColumnGridView']),
            ),
            
            // Погашение суммы гражданского иска (тыс. руб)
            array(
                'name'=>'civil_action_repayment_summ',
                'visible'=>in_array('civil_action_repayment_summ', Yii::app()->session['viewColumnGridView']),
                'footer'=>$result_search[1]->civil_action_repayment_summ,
                'footerHtmlOptions'=>array('class'=>'style_row_footer'),
            ),
            
            // Примечание
            array(
                'name'=>'note_SLEDSTV_ORG',
                'visible'=>in_array('note_SLEDSTV_ORG', Yii::app()->session['viewColumnGridView']),
            ),
            
            
            /** ******************************************************************************
             * Сведения о передаче материалов в органы внутренних дел в порядке ст. 82 НК РФ
             * **************************************************************************** **/
             
            // Материалы переданы в органы внутренних дел (дата письма)
            array(
                'name'=>'material_to_UVD_date',
                'visible'=>in_array('material_to_UVD_date', Yii::app()->session['viewColumnGridView']),            
                /*'filter'=>'<div class="filter-date-width">'.CHtml::activeTextField($model, 'material_to_UVD_date', array('isdate'=>'')).'<br /><br />'
                    .CHtml::activeTextField($model, 'material_to_UVD_date_to', array('isdate'=>'')).'</div>',*/              
            ),
            
            // Материалы переданы в органы внутренних дел (номер письма)
            array(
                'name'=>'material_to_UVD_num',
                'visible'=>in_array('material_to_UVD_num', Yii::app()->session['viewColumnGridView']),
            ),
            
            // Материалы переданы в органы внутренних дел (ст. УК РФ)
            array(
                'name'=>'material_to_UVD_article',
                'value'=>'implode("<br />",
                    CHtml::listData($data->directoryArticleUVD, "id", "value"))',                                                
                'type' => 'raw',
                'visible'=>in_array('material_to_UVD_article', Yii::app()->session['viewColumnGridView']),
            ),
            
            // Результат рассмотрения материалов органами  внутренних дел  (возбуждено УД)
            array(
                'name'=>'result_see_OVD_filed',
                'value'=>'(isset($data->article_result_see_OVD_filed->value) ?
                    $data->article_result_see_OVD_filed->value : "")',
                /*'filter'=>CHtml::listData(DirectoryArticle::model()->findAll(array(
                            'condition'=>'type_article=2')),'id','value'),*/
                'visible'=>in_array('result_see_OVD_filed', Yii::app()->session['viewColumnGridView']),
            ),
            
            // Результат рассмотрения материалов органами  внутренних дел (отказано в возбуждении УД)
            array(
                'name'=>'result_see_OVD_refused',
                'value'=>'(isset($data->article_result_see_OVD_refused->value) ?
                    $data->article_result_see_OVD_refused->value : "")',
                /*'filter'=>CHtml::listData(DirectoryArticle::model()->findAll(array(
                            'condition'=>'type_article=2')),'id','value'),*/
                'visible'=>in_array('result_see_OVD_refused', Yii::app()->session['viewColumnGridView']),
            ),
            
            // Примечание
            array(
                'name'=>'note_OVD',
                'visible'=>in_array('note_OVD', Yii::app()->session['viewColumnGridView']),
            ),
            
            
            
            // Дата создания
            array(
                'name'=>'date_create',
                'visible'=>in_array('date_create', Yii::app()->session['viewColumnGridView']),
            ),
            
            
            
    		array(
    			'class'=>'bootstrap.widgets.TbButtonColumn',
                'template'=>'{view}{restore}',
                'buttons'=>array(
                    'view'=>array(
                        'url'=>'Yii::app()->createUrl("reestrCheckArchive/view", array("id"=>$data->id/*,"fast"=>true*/))',                         
                        /*'click'=>'function(){
                            id = $(this).attr("href");
                            viewCheckWindow(id);                                                  
                            return false;
                        }',*/
                    ),                                    
                    'restore'=>array(
                        'label'=>'Восстановить из архива',                        
                        'icon'=>'share-alt',
                        'click'=>"function(){
                            $.fn.yiiGridView.update('reestr-check-grid', {  
                                type:'POST',
                                url:$(this).attr('href'),
                                success:function(data) {
                                      $.fn.yiiGridView.update('reestr-check-grid'); 
                                }
                            })
                            return false;
                          }
                        ",
                        'url'=>'Yii::app()->createUrl("reestrCheckArchive/restore", array("id"=>$data->id))',
                    ),                    
                ),
    		),
    	),
    
        'pager'=>array(
            'class'=>'bootstrap.widgets.TbPager',
            'displayFirstAndLast'=>true,
        ),
        
    )); ?>

<script type="text/javascript">
    // загрузить все datePicker
    loadDatePicker();    
    
    
    function viewCheckWindow(url)
    {
        var params = "location=no,menubar=no,scrollbars=yes,status=no,resizable=yes,toolbar=no,width=1000,height=800,top=10,left=10;";
        open(url, "_blank", params);        
    }    
    
</script>

</div>