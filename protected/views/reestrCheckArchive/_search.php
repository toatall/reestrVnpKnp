<div class="search-form" style="margin-top:5px;">
<?php

    Yii::app()->clientScript->registerScript('search', "        
        $('.search-form form').submit(function(){
        	$.fn.yiiGridView.update('reestr-check-grid', {
        		data: $(this).serialize()
        	});
            $('#button-search-result').click();
        	return false;
        });
    ");

?>

<div class="well">    
    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    	'action'=>Yii::app()->createUrl($this->route),
        'id'=>'search-form',
    	'method'=>'get',
    )); ?>
        <style type="text/css">
            table td { padding: 3px; vertical-align: middle; }
            table td label { display: inline; margin-left:4px; }
            table td input[type=checkbox] { margin-top:-2px; }
            .link-articles, .link-articles:hover, .link-articles:visited { border-bottom:1px dotted; text-decoration: none; }
        </style>
        <script type="text/javascript">
            function checkAllViewColumns(check) {
	               jQuery("input[name='checkBoxListViewColumnGridView\[values\]\[\]']").prop('checked', check);                   
            };            
        </script>
        
        <?php
            $this->renderPartial('modal/_articles',array());
            $this->renderPartial('modal/_property',array());
            $this->renderPartial('modal/_bankruptcy',array());
        ?>
        <?php /*$this->widget('bootstrap.widgets.TbButton', array(
    		'label'=>'DSF',
    		'type'=>'primary',
    		'htmlOptions'=>array(                
                'onclick' => 'loadArticles("123")',
                ),
	       ));*/  ?>
        
        <table>            
            
                        
            <?php
            
            function setTableRow($form, $model, $name, $isNumeric, $filter=null, $filter_name=null)
            {
                if ($name == '---'):
            ?>
            <tr>
                <td colspan="4"><hr /></td>
            </tr>            
            <?php
                else:
            ?>
            <tr>                
                <td style="width: 300px;"><?php echo $form->label($model, $name); ?></td>
                <td style="width: 40px;">                    
                    <?php echo CHtml::checkBox('checkBoxListViewColumnGridView[values][]',
                        in_array($name, Yii::app()->session['viewColumnGridView']),
                        array('id'=>$name,'value'=>$name)); ?>
                    <label for="<?php echo $name; ?>"><i class="icon-eye-open"></i></label>
                </td>
                <?php if ($filter!=null): ?>
                <td><?php echo $filter_name; ?></td>
                <td><?php echo $filter; ?></td>
                <?php else: ?>
                <td><?php echo $form->dropDownList($model,'filter_'.$name,
                    (($isNumeric) ? CDbCriteriaExtentsion::filterNumeric()
                        : CDbCriteriaExtentsion::filterString())); ?></td>
                <td><?php echo $form->textField($model,$name,array('class'=>'span5')); ?></td>
                <?php endif; ?>
            </tr> 
            <?php
                endif;            
            }
                                                                                  
            ?>
            
            
            
            <a href="javascript:checkAllViewColumns(true)" class="link-articles">Выбрать все реквизиты</a><br />
            <a href="javascript:checkAllViewColumns(false)" class="link-articles">Снять выбор со всех реквизитов</a><br />
            <?php echo CHtml::link('Сбросить', array('adminViewColumnsReset')); ?>
            <?php
            
            /*echo CHtml::checkBox('checkBoxListViewColumnGridView_all',array(
                'id'=>'checkBoxListViewColumnGridView_all'));
            echo CHtml::label('Выбрать все', 'checkBoxListViewColumnGridView_all');*/
            
            
            // Общие
            setTableRow($form, $model, 'id', true);
            setTableRow($form, $model, 'id_check', true);
            setTableRow($form, $model, 'code_NO', false);
            setTableRow($form, $model, 'code_NO_current_dept', false);
            setTableRow($form, $model, 'inn_NP', false);
            setTableRow($form, $model, 'kpp_NP', false);
            setTableRow($form, $model, 'name_NP', false);
            setTableRow($form, $model, 'id_NP', true);
            setTableRow($form, $model, 'type_NP', false,
                $form->dropDownList($model, 'type_NP', array(''=>'',1=>'ЮЛ',2=>'ФЛ')), 'равно');
            setTableRow($form, $model, 'type_check', false, 
                $form->dropDownList($model, 'type_check', array(''=>'',1=>'ВНП',2=>'КНП')), 'равно');
            
            // Реестр не взысканных (не в полном объеме взысканных) дополнительно 
            //     начисленных сумм налогов, по итогам проведения налоговых проверок
            setTableRow(null, null, '---', null);
            setTableRow($form, $model, 'credit_addational', true);
            setTableRow($form, $model, 'resolution_date', true);
            setTableRow($form, $model, 'resolution_number', false);
            setTableRow(null, null, '---', null);
            setTableRow($form, $model, 'requiment_number', false);
            setTableRow($form, $model, 'requiment_date', true);
            setTableRow($form, $model, 'requiment_term', true);
            setTableRow($form, $model, 'requiment_summ', true);
            setTableRow($form, $model, 'requiment_summ_rest', true);
            setTableRow($form, $model, 'recovered_summ', true);
            setTableRow(null, null, '---', null);
            setTableRow($form, $model, 'reduced_higher_NO_summ', true);
            setTableRow($form, $model, 'reduced_arb_court_summ', true);
            setTableRow(null, null, '---', null);
            setTableRow($form, $model, 'resolution_adop_sec_measure_ban_alien_num', false);
            setTableRow($form, $model, 'resolution_adop_sec_measure_ban_alien_date', true);
            setTableRow(null, null, '---', null);
            setTableRow($form, $model, 'resolution_adop_sec_measure_susp_oper_num', false);
            setTableRow($form, $model, 'resolution_adop_sec_measure_susp_oper_date', true);
            setTableRow(null, null, '---', null);
            setTableRow($form, $model, 'info_removal_register_NP_date', true);
            setTableRow($form, $model, 'info_removal_register_NP_to_NO', false);
            setTableRow($form, $model, 'info_removal_register_NP_reason', false);
            
            // Сведения о процедурах банкротства          
            setTableRow(null, null, '---', null);
            //setTableRow($form, $model, 'current_proc_bankruptcy', false);
            setTableRow($form, $model, 'current_proc_bankruptcy', false,
                $form->textField($model,'current_proc_bankruptcy')
                .' <a onclick="loadBankruptcy(\'#ReestrCheck_current_proc_bankruptcy\', 
                    $(\'#ReestrCheck_current_proc_bankruptcy\').val());" class="btn" style="margin-top:-10px;">...</a>'
                , 'из списка...'); 
            setTableRow($form, $model, 'intro_date', true);
            //setTableRow($form, $model, 'last_measure_recovery', false);
            setTableRow($form, $model, 'last_measure_recovery', false,
                $form->textField($model,'last_measure_recovery')
                .' <a onclick="loadArticles(0, \'#ReestrCheck_last_measure_recovery\', 
                    $(\'#ReestrCheck_last_measure_recovery\').val());" class="btn" style="margin-top:-10px;">...</a>'
                , 'из списка...'); 
            setTableRow($form, $model, 'adop_date', true);            
            setTableRow($form, $model, 'property', false,
                $form->textField($model,'property')
                .' <a onclick="loadProperty(\'#ReestrCheck_property\', 
                    $(\'#ReestrCheck_property\').val());" class="btn" style="margin-top:-10px;">...</a>'
                , 'из списка...');   
            setTableRow($form, $model, 'note_bankruptcy', false);
            setTableRow($form, $model, 'balance_dept_VNP', true);
            setTableRow($form, $model, 'including_NP', true);
            setTableRow($form, $model, 'including_agent', true);
            
            // Сведения о передаче материалов в следственные органы в порядке ст. 32 НК РФ  
            setTableRow(null, null, '---', null);
            setTableRow($form, $model, 'material_SLEDSTV_ORG_date', true);
            setTableRow($form, $model, 'material_SLEDSTV_ORG_num', false);
            setTableRow($form, $model, 'material_SLEDSTV_ORG_article', false,
                $form->textField($model,'material_SLEDSTV_ORG_article')
                .' <a onclick="loadArticles(1, \'#ReestrCheck_material_SLEDSTV_ORG_article\', 
                    $(\'#ReestrCheck_material_SLEDSTV_ORG_article\').val());" class="btn" style="margin-top:-10px;">...</a>'
                , 'из списка...');                            
            setTableRow(null, null, '---', null);
            
            //setTableRow($form, $model, 'result_see_SLEDSTV_ORG_filed_article', true);
            setTableRow($form, $model, 'result_see_SLEDSTV_ORG_filed_article', false,
                $form->textField($model,'result_see_SLEDSTV_ORG_filed_article')
                .' <a onclick="loadArticles(1, \'#ReestrCheck_result_see_SLEDSTV_ORG_filed_article\', 
                    $(\'#ReestrCheck_result_see_SLEDSTV_ORG_filed_article\').val());" class="btn" style="margin-top:-10px;">...</a>'
                , 'из списка...');  
            setTableRow($form, $model, 'result_see_SLEDSTV_ORG_filed_date', true);
            setTableRow($form, $model, 'result_see_SLEDSTV_ORG_filed_num', false);
            setTableRow(null, null, '---', null);
            setTableRow($form, $model, 'result_see_SLEDSTV_ORG_refused_article', true);            
            setTableRow($form, $model, 'result_see_SLEDSTV_ORG_refused_date', true);
            setTableRow(null, null, '---', null);
            //setTableRow($form, $model, 'civil_action', true);
            setTableRow($form, $model, 'civil_action', false,
                $form->dropDownList($model, 'type_check', array(''=>'',0=>'нет',1=>'да')), 'равно');
            setTableRow($form, $model, 'civil_action_date', true);
            setTableRow($form, $model, 'civil_action_summ', true);
            setTableRow($form, $model, 'civil_action_result_see', true);
            setTableRow($form, $model, 'civil_action_repayment_summ', true);
            setTableRow($form, $model, 'note_SLEDSTV_ORG', false);
            
            // Сведения о передаче материалов в органы внутренних дел в порядке ст. 82 НК РФ
            setTableRow($form, $model, 'material_to_UVD_date', true);
            setTableRow($form, $model, 'material_to_UVD_num', true);
            //setTableRow($form, $model, 'material_to_UVD_article', true);
            setTableRow($form, $model, 'material_to_UVD_article', false,
                $form->textField($model,'material_to_UVD_article')
                .' <a onclick="loadArticles(2, \'#ReestrCheck_material_to_UVD_article\', 
                    $(\'#ReestrCheck_material_to_UVD_article\').val());" class="btn" style="margin-top:-10px;">...</a>'
                , 'из списка...');
            setTableRow($form, $model, 'result_see_OVD_filed', true);
            setTableRow($form, $model, 'result_see_OVD_refused', true);
            setTableRow($form, $model, 'note_OVD', false);
                                                
        ?>        
        </table>
    
    	    
    	<div class="form-actions">
    		<?php $this->widget('bootstrap.widgets.TbButton', array(
    			'buttonType'=>'submit',
    			'type'=>'primary',
    			'label'=>'Поиск',
    		)); ?>
    	</div>
    
    <?php $this->endWidget(); ?>
</div>
</div>