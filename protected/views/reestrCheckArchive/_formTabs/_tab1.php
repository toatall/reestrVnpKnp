	<div class="form-actions" style="padding: 20px;">
        <div class="well" style="background-color: white;">
    		<?php 
    	        echo $form->dropDownListRow($model,'code_NO', 
    	            /*CHtml::listData(Yii::app()->user->admin ? Ifns::model()->findAll() 
                    : Login::model()->getAccessIfns(Yii::app()->user->id),'code_no','name')*/
                    CHtml::listData(Ifns::model()->findAll(),'code_no','name'),
    	            array('class'=>'span6')
    	        );
            ?>
            
            <?php 
    	        echo $form->dropDownListRow($model,'code_NO_current_dept', 
    	            /*CHtml::listData(Yii::app()->user->admin ? Ifns::model()->findAll() 
                    : Login::model()->getAccessIfns(Yii::app()->user->id),'code_no','name')*/
                    CHtml::listData(Ifns::model()->findAll(),'code_no','name'),
    	            array('class'=>'span6')
    	        );
            ?>
            
            <?php /*echo $form->dropDownListRow($model,'type_check',
                CHtml::listData(DirectoryTypeCheck::model()->findAll(),'id','value'),
                array('empty'=>array(''=>'')));*/ ?>
            
    		<?php echo $form->textFieldRow($model,'inn_NP',array('class'=>'span2','maxlength'=>12)); ?>	
    	
    		<?php echo $form->textFieldRow($model,'kpp_NP',array('class'=>'span2','maxlength'=>9)); ?>
    	
    		<?php echo $form->textFieldRow($model,'name_NP',array('class'=>'span8','maxlength'=>500)); ?>
            
            <?php echo $form->dropDownListRow($model, 'type_NP', array(''=>'', 1=>'ЮЛ', 2=>'ФЛ')); ?>
            
            <br /><br />
            
            <div class="well" style="background-color: white;">
                <h4>Сведения о снятии с учета налогоплательщика</h4>
                <hr />
                
                <?php // сведения о снятии с учета налогоплательщика (Дата снятия с учета)                     
                    echo $form->textFieldRow($model,'info_removal_register_NP_date',array(
                        'class'=>'span2','maxlength'=>10,
                        'prepend' => '<i class="icon-calendar"></i>', 'isdate'=>''
                        /*,'labelOptions'=>array('label'=>false)*/)); ?>        
                                    
                <?php echo $form->textAreaRow($model,'info_removal_register_NP_reason',
                    array('rows'=>'3','class'=>'span5','maxlength'=>500)); ?>
                
                <?php echo $form->textFieldRow($model,'info_removal_register_NP_to_NO'
                    ,array('class'=>'span1','maxlength'=>4)); ?>                                  
                        
            </div>
        
        </div>
        
                        
        
        <div class="well" style="background-color: white;">
            <h3>Решение о привлечении к ответственности (об отказе в привлечении к ответственности)</h3>
            <hr />
            
            <?php // доначислено по решению всего (тыс. руб.)
                echo $form->textFieldRow($model,'credit_addational',array('class'=>'span3','maxlength'=>18)); ?>
            
            <?php // дата решения 
                echo $form->textFieldRow($model,'resolution_date',array('class'=>'span2','maxlength'=>10,
                    'prepend' => '<i class="icon-calendar"></i>', 'isdate'=>'')); ?>
    								
    		
    		<?php // номер решения 
    			echo $form->textFieldRow($model,'resolution_number',array('class'=>'span3','maxlength'=>25)); 
    		?>
            
            <?php echo $form->dropDownListRow($model,'type_check',
                CHtml::listData(DirectoryTypeCheck::model()->findAll(),'id','value'),
                array('empty'=>array(''=>''))); ?>
                  
        </div> 
        
        <?php
            if (!$model->isNewRecord):
        ?>
        <div class="well" style="background-color: white;">
            <h3>Требование об уплате налогов</h3>                  
            <?php
                                               
            ?>
                <table class="items table">
                    <tr>
                        <th>Номер</th>
                        <th>Дата</th>
                        <th>Срок уплаты</th>
                        <th>Сумма включенная в требование (тыс. руб.)</th>
                        <th>Остаток непогашенной суммы по требованию (тыс. руб.)</th>
                        <th>Взыскано (тыс. руб.)</th>
                    </tr>
            <?php
                if (count($model->requiments)): 
                
                foreach ($model->requiments as $val):
            ?>
                    <tr>
                        <td><?php echo $val['requiment_number']; ?></td>
                        <td><?php echo $val['requiment_date']; ?></td>
                        <td><?php echo $val['requiment_term']; ?></td>
                        <td><?php echo $val['requiment_summ']; ?></td>
                        <td><?php echo $val['requiment_summ_rest']; ?></td>
                        <td><?php echo $val['recovered_summ']; ?></td>
                    </tr>
            <?php  
                endforeach;
                
                else:
            ?>
                <tr>
                    <td colspan="6" style="text-align: center;">Нет данных</td>
                </tr>
            <?php
                
                endif;
            ?>   
                </table>                                               
        </div> 
        <?php
            endif;
        ?>
        
        <div class="well" style="background-color: white;">
            <h3>Сведения о результатах рассмотрения материалов проверки в ВНО и Арбитражном суде</h3>
            <hr />
            
               <?php // Уменьшено по решению вышестоящего налогового органа, всего (тыс. руб.)
        			echo $form->textFieldRow($model,'reduced_higher_NO_summ',array('class'=>'span3','maxlength'=>18)); 
        		?>
        		
        		<?php // Уменьшено по решению Арбитражного суда   (тыс. руб.)
        			echo $form->textFieldRow($model,'reduced_arb_court_summ',array('class'=>'span3','maxlength'=>18)); 
        		?>                            
        </div> 
        
        <div class="well" style="background-color: white;">
               
    		<?php // Решение о принятии обеспечительных мер (по невзысканным платежам) / запрет на отчуждение имущества / номер
    			echo $form->textFieldRow($model,'resolution_adop_sec_measure_ban_alien_num',array('class'=>'span3','maxlength'=>25)); 
    		?>
            
             <?php // Решение о принятии обеспечительных мер (по невзысканным платежам) / запрет на отчуждение имущества / дата
                echo $form->textFieldRow($model,'resolution_adop_sec_measure_ban_alien_date',array('class'=>'span2','maxlength'=>10,
                    'prepend' => '<i class="icon-calendar"></i>', 'isdate'=>'')); ?>
        
        </div>
        
        <div class="well" style="background-color: white;">
               
    		<?php // Решение о принятии обеспечительных мер (по невзысканным платежам) / приостановление операций по счетам / номер
    			echo $form->textFieldRow($model,'resolution_adop_sec_measure_susp_oper_num',array('class'=>'span3','maxlength'=>25)); 
    		?>
            
             <?php // Решение о принятии обеспечительных мер (по невзысканным платежам) / приостановление операций по счетам / дата
                echo $form->textFieldRow($model,'resolution_adop_sec_measure_susp_oper_date',array('class'=>'span2','maxlength'=>10,
                    'prepend' => '<i class="icon-calendar"></i>', 'isdate'=>'')); ?>
        
        </div>
		
				
	</div>