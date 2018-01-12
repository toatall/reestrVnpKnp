
<div class="well" style="background-color: white;">
<?php    
    $this->widget('bootstrap.widgets.TbDetailView',array(
    	'data'=>$model,
    	'attributes'=>array(            
    		'id',
            'id_check',
            array(
                'name'=>'code_NO',
                'value'=>$model->code_NO.' ('.$model->ifns->name.')',
            ),
            array(
                'name'=>'code_NO_current_dept',
                'value'=>$model->code_NO_current_dept.' ('.$model->ifnsDept->name.')',
            ),
    		'inn_NP',
    		'kpp_NP',
    		'name_NP',               
    	),
    ));
?>
    <div class="well" style="background-color: white;">
        <h4>Сведения о снятии с учета налогоплательщика</h4>       
        <?php           
            $this->widget('bootstrap.widgets.TbDetailView',array(
            	'data'=>$model,
            	'attributes'=>array(                   
                    array(
                        'name'=>'info_removal_register_NP_date_short',
                        'value'=>$model->info_removal_register_NP_date,
                    ),
                    array(
                        'name'=>'info_removal_register_NP_reason_short',
                        'value'=>$model->info_removal_register_NP_reason,
                    ),
                    array(
                        'name'=>'info_removal_register_NP_to_NO_short',
                        'value'=>$model->info_removal_register_NP_to_NO,
                    ),            		         
            	),
            ));
        ?>        
    </div>            
</div>

<div class="well" style="background-color: white;">
    <h3>Решение о привлечении к ответственности (об отказе в привлечении к ответственности)</h3>
<?php    
    $this->widget('bootstrap.widgets.TbDetailView',array(
    	'data'=>$model,
    	'attributes'=>array(            
    		'credit_addational',
            'resolution_date',
            'resolution_number',                                   
    	),
    ));
?>
</div>
    
<div class="well" style="background-color: white;">
    <h3>Требование об уплате налогов</h3>                  
    
    <?php       
    
    $model_requiment = new ReestrCheckRequiment('search');

    $this->widget('bootstrap.widgets.TbGridView',array(
    	'id'=>'reestr-check-requiment-grid',
    	'dataProvider'=>$model_requiment->search($model->id),                
    	'columns'=>array(
            'requiment_number',
            'requiment_date',
            'requiment_term',
	        'requiment_summ',
	        'requiment_summ_rest',
            'recovered_summ',            		
    	),
    ));
    
    ?>                                                 
</div> 

<div class="well" style="background-color: white;">
    <h3>Сведения о результатах рассмотрения материалов проверки в ВНО и Арбитражном суде</h3>
    <?php    
        $this->widget('bootstrap.widgets.TbDetailView',array(
        	'data'=>$model,
        	'attributes'=>array(            
        		'reduced_higher_NO_summ',
                'reduced_arb_court_summ',                          
        	),
        ));
    ?>
</div>

<div class="well" style="background-color: white;">
    <h3>Решение о принятии обеспечительных мер (по невзысканным платежам) / запрет на отчуждение имущества</h3>
    <?php    
        $this->widget('bootstrap.widgets.TbDetailView',array(
        	'data'=>$model,
        	'attributes'=>array( 
                array(
                    'name'=>'resolution_adop_sec_measure_ban_alien_num_short',
                    'value'=>$model->resolution_adop_sec_measure_ban_alien_num,
                ),
                array(
                    'name'=>'resolution_adop_sec_measure_ban_alien_date_short',
                    'value'=>$model->resolution_adop_sec_measure_ban_alien_date,
                ),
        	),
        ));
    ?>
</div>
<div class="well" style="background-color: white;">
    <h3>Решение о принятии обеспечительных мер (по невзысканным платежам) / приостановление операций по счетам</h3>
    <?php    
        $this->widget('bootstrap.widgets.TbDetailView',array(
        	'data'=>$model,
        	'attributes'=>array( 
                array(
                    'name'=>'resolution_adop_sec_measure_susp_oper_num_short',
                    'value'=>$model->resolution_adop_sec_measure_susp_oper_num,
                ),
                array(
                    'name'=>'resolution_adop_sec_measure_susp_oper_date_short',
                    'value'=>$model->resolution_adop_sec_measure_susp_oper_date,
                ),
        	),
        ));
    ?>
</div>