
<div class="well" style="background-color: white;">
    <h3>Сведения о процедуре банкротства</h3>
              
    <?php
        $this->widget('bootstrap.widgets.TbDetailView',array(
        	'data'=>$model,
        	'attributes'=>array(
        		array(
                    'name'=>'current_proc_bankruptcy',
                    'value'=>(isset($model->directory_bankruptcy->value) 
                        ? $model->directory_bankruptcy->value : null),
                ),
                'intro_date',
                array(
                    'name'=>'last_measure_recovery',
                    'value'=>(isset($model->article_last_measure_recovery) 
                        ? $model->article_last_measure_recovery->value : ''),
                ),
                'adop_date',
                array(
                    'name'=>'property',
                    'type'=>'raw',
                    'value'=>implode('<br />',CHtml::listData(
                        $model->directoryProperty,'id','value')),
                ),
                'note_bankruptcy',
        	),
        ));
    ?>
</div>

<div class="well" style="background-color: white;">
    <h3>Сведения о передаче материалов в следственные органы в порядке ст. 32 НК РФ</h3>    
    <?php    
        $this->widget('bootstrap.widgets.TbDetailView',array(
        	'data'=>$model,
        	'attributes'=>array(
                array(
                    'name'=>'material_SLEDSTV_ORG_date_short',
                    'value'=>$model->material_SLEDSTV_ORG_date,
                ),
                array(
                    'name'=>'material_SLEDSTV_ORG_num_short',
                    'value'=>$model->material_SLEDSTV_ORG_num,
                ),
                array(
                    'name'=>'materialSLEDSTVORGarticles_short',
                    'type'=>'raw',
                    'value'=>implode('<br />',CHtml::listData(
                        $model->directoryArticleSLEDSTV_ORG,'id','value')),
                ),                                        		
        	),
        ));
    ?>   
    
    <div class="well" style="background-color: white;">
        <h4>Результат рассмотрения следственными органами материалов налоговых проверок</h4>
        <?php    
            $this->widget('bootstrap.widgets.TbDetailView',array(
            	'data'=>$model,
            	'attributes'=>array(
                    array(
                        'name'=>'result_see_SLEDSTV_ORG_filed_article_short',
                        'value'=>isset($model->article_result_see_SLEDSTV_ORG_filed) 
                            ? $model->article_result_see_SLEDSTV_ORG_filed->value : null,
                    ),
                    array(
                        'name'=>'result_see_SLEDSTV_ORG_filed_date_short',
                        'value'=>$model->result_see_SLEDSTV_ORG_filed_date,
                    ),
                    array(
                        'name'=>'result_see_SLEDSTV_ORG_filed_num_short',
                        'value'=>$model->result_see_SLEDSTV_ORG_filed_num,
                    ),                                                                		
            	),
            ));
        ?>
        <br /><br />
        <?php    
            $this->widget('bootstrap.widgets.TbDetailView',array(
            	'data'=>$model,
            	'attributes'=>array(
                    array(
                        'name'=>'result_see_SLEDSTV_ORG_refused_article_short',
                        'value'=>$model->result_see_SLEDSTV_ORG_refused_article,
                        /*'value'=>isset($model->article_result_see_SLEDSTV_ORG_refused) 
                            ? $model->article_result_see_SLEDSTV_ORG_refused->value : null,*/
                    ),
                    array(
                        'name'=>'result_see_SLEDSTV_ORG_refused_date_short',
                        'value'=>$model->result_see_SLEDSTV_ORG_refused_date,
                    ),                                                                   		
            	),
            ));
        ?>
    </div>
    
    <div class="well" style="background-color: white;">
        <h4>Сведения о предъявлении гражданского иска о возмещении ущерба государству по материалам налоговых органов</h4>    
        <?php    
            $this->widget('bootstrap.widgets.TbDetailView',array(
            	'data'=>$model,
            	'attributes'=>array(
                    array(
                        'name'=>'civil_action',
                        'value'=>$model->getYesNo($model->civil_action),                            
                    ),
                    'civil_action_date',
                    'civil_action_summ',
                    array(
                        'name'=>'civil_action_result_see',
                        'value'=>isset($model->article_civil_action_result_see) 
                            ? $model->article_civil_action_result_see->value : null,
                    ),                                     		
            	),
            ));
        ?>
    
    </div>    
</div>

<div class="well" style="background-color: white;">
    <h3>Сведения о передаче  материалов в органы внутренних дел в порядке ст. 82 НК РФ</h3>
    <?php    
        $this->widget('bootstrap.widgets.TbDetailView',array(
        	'data'=>$model,
        	'attributes'=>array(
                'material_to_UVD_date',
                'material_to_UVD_num',
                array(
                    'name'=>'material_to_UVD_article',
                    'type'=>'raw',
                    'value'=>implode('<br />',CHtml::listData(
                        $model->directoryArticleUVD,'id','value')),
                ),                                                  		
        	),
        ));
        ?>
        
        <div class="well" style="background-color: white;">
            <h4>Результат рассмотрения органами внутренних дел материалов налоговых проверок</h4>
            <?php    
            $this->widget('bootstrap.widgets.TbDetailView',array(
            	'data'=>$model,
            	'attributes'=>array(
                    array(
                        'name'=>'result_see_OVD_filed_short',
                        'value'=>isset($model->article_result_see_OVD_filed) 
                            ? $model->article_result_see_OVD_filed->value : null,
                    ),
                    array(
                        'name'=>'result_see_OVD_refused_short',
                        'value'=>isset($model->article_result_see_SLEDSTV_ORG_filed) 
                            ? $model->article_result_see_SLEDSTV_ORG_filed->value : null,
                    ),                    
                    'note_OVD',                                                                      		
            	),
            ));
            ?>
        </div>

</div>