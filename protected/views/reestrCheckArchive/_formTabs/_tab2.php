	
	<div class="form-actions" style="padding: 20px;">
        
        <div class="well" style="background-color: white;">
            <h3>Сведения о процедуре банкротства</h3>
            <hr />
            
            <?php // Текущая процедура банкротства
    			/*echo $form->dropDownListRow($model,'current_proc_bankruptcy',
    					CHtml::listData(DirectoryBankruptcy::model()->findAll(),'id','value'),
    					array('empty'=>array(''=>''),'class'=>'span3'));*/                        
                echo $form->textFieldRow($model,'current_proc_bankruptcy',
                    array('class'=>'span6', 'maxlength'=>250));
    		?>
    		
    		<?php // Дата введения
    			echo $form->textFieldRow($model,'intro_date',array('class'=>'span2','maxlength'=>10,
                    'prepend' => '<i class="icon-calendar"></i>', 'isdate'=>'')); 
    		?>
    		
    		<?php // Последняя мера взыскания			
                echo $form->dropDownListRow($model,'last_measure_recovery', 
    					CHtml::listData(DirectoryArticle::model()->findAll(array(
                            'condition'=>'type_article=0')),'id','value'),
    					array('empty'=>array(''=>''),'class'=>'span3'));            
    		?>
    		
    		<?php // Дата принятия
    			echo $form->textFieldRow($model,'adop_date',array('class'=>'span2','maxlength'=>10,
                    'prepend' => '<i class="icon-calendar"></i>', 'isdate'=>'')); 
    		?>
    		
            <br /><br />
    		<?php // Наличие имущества
                echo $form->checkBoxListRow($model,'reestrProperty',
                    CHtml::listData(DirectoryProperty::model()->findAll(),'id','value'));  
                
    		?>
            <br />  
            
            <?php // Примечание
    			echo $form->textAreaRow($model,'note_bankruptcy', 
                    array('rows'=>'3','class'=>'span5','maxlength'=>500)); ?>
            
            <?php // Остаток задолженности по выездной налоговой проверке (тыс. руб)
                echo $form->textFieldRow($model,'balance_dept_VNP',
                    array('class'=>'span3','maxlength'=>18));
     		?>   
             
            <?php // В том числе по налогу (налогоплательщик) (тыс. руб.)
                echo $form->textFieldRow($model,'including_NP',
                    array('class'=>'span3','maxlength'=>18));
     		?>  
               
            <?php // В том числе по налогу (налоговый агент) (тыс. руб.)
                echo $form->textFieldRow($model,'including_agent',
                    array('class'=>'span3','maxlength'=>18));
     		?>  
                        
        </div>
        
        <div class="well" style="background-color: white;">
            <h3>Сведения о передаче материалов в следственные органы в порядке ст. 32 НК РФ</h3>
            <hr />
            
            <?php // Материалы передаты в следственные органы по ст. 32 НК РФ (дата письма)
    			echo $form->textFieldRow($model,'material_SLEDSTV_ORG_date',array('class'=>'span2','maxlength'=>10,
                    'prepend' => '<i class="icon-calendar"></i>', 'isdate'=>'')); 
    		?>
    		
    		<?php // Материалы передаты в следственные органы по ст. 32 НК РФ (номер письма)
    			echo $form->textFieldRow($model,'material_SLEDSTV_ORG_num',array('class'=>'span3','maxlength'=>18)); 
    		?>
    		
    		<?php // Материалы передаты в следственные органы по ст. 32 НК РФ (ст. УК РФ)    			
                echo $form->checkBoxListRow($model,'materialSLEDSTVORGarticles',
                    CHtml::listData(DirectoryArticle::model()->findAll(
                        array('condition'=>'type_article=1')),'id','value'));  
    		?>   
            <br /><br />
            
            
            
            <div class="well" style="background-color: white;">
                <h4>Результат рассмотрения следственными органами материалов налоговых проверок</h4>
                <hr />
                
                <?php // Результат рассмотрения следственными органами материалов налоговых проверок (возбуждено УД)
                    echo $form->dropDownListRow($model,'result_see_SLEDSTV_ORG_filed_article', 
 					   CHtml::listData(DirectoryArticle::model()->findAll(array(
        					   'condition'=>'type_article=1')),'id','value'),
        					array('empty'=>array(''=>''),'class'=>'span3')); 
                ?>

                <?php // Материалы передаты в следственные органы по ст. 32 НК РФ (дата письма)
        			echo $form->textFieldRow($model,'result_see_SLEDSTV_ORG_filed_date',array('class'=>'span2','maxlength'=>10,
                        'prepend' => '<i class="icon-calendar"></i>', 'isdate'=>'')); 
        		?>
        		
        		<?php // Материалы передаты в следственные органы по ст. 32 НК РФ (номер письма)
        			echo $form->textFieldRow($model,'result_see_SLEDSTV_ORG_filed_num',array('class'=>'span3','maxlength'=>18)); 
        		?>
                
                <hr />
                
                <?php // Результат рассмотрения следственными органами материалов налоговых проверок(отказано в возбуждении УД)
        			/*echo $form->dropDownListRow($model,'result_see_SLEDSTV_ORG_refused_article', 
        					CHtml::listData(DirectoryArticle::model()->findAll(array(
        					   'condition'=>'type_article=1')),'id','value'),
        					array('empty'=>array(''=>''),'class'=>'span3'));*/
                    echo $form->textFieldRow($model,'result_see_SLEDSTV_ORG_refused_article',
                        array('class'=>'span6','maxlength'=>250));
        		?>
        		
        		
        		<?php // Материалы переданы в следственные органы по ст. 32 НК РФ (дата письма)
        			echo $form->textFieldRow($model,'result_see_SLEDSTV_ORG_refused_date',array('class'=>'span2','maxlength'=>10,
                        'prepend' => '<i class="icon-calendar"></i>', 'isdate'=>'')); 
        		?>
        		
                
            </div>    
                
                
            <div class="well" style="background-color: white;">
                <h4>Сведения о предъявлении гражданского иска о возмещении ущерба государству по материалам налоговых органов</h4>    
                <hr />
                
                <?php // Предъявлен гражданский  иск о возмещении ущерба по материалам налоговых органов 
        			echo $form->dropDownListRow($model,'civil_action', 
        					array(0=>'нет',1=>'да'),
        					array('empty'=>array(''=>''),'class'=>'span3')); 
        		?>
        		
        		<?php // Дата предъявления гражданского иска о возмещении ущерба по материалам налоговых органов
        			echo $form->textFieldRow($model,'civil_action_date',array('class'=>'span2','maxlength'=>10,
                        'prepend' => '<i class="icon-calendar"></i>', 'isdate'=>'')); 
        		?>
        		
        		<?php // Предъявлен гражданский иск на сумму (тыс. руб)
        			echo $form->textFieldRow($model,'civil_action_summ',array('class'=>'span3','maxlength'=>18)); 
        		?>
        		
        		<?php // Результат рассмотрения судебными органами гражданского иска
        			echo $form->dropDownListRow($model,'civil_action_result_see', 
        					CHtml::listData(DirectoryCivilActionResult::model()->findAll(),'id','value'),
        					array('empty'=>array(''=>''),'class'=>'span3')); 
        		?>
        		
        		<?php // Погашение суммы гражданского иска (тыс. руб)
        			echo $form->textFieldRow($model,'civil_action_repayment_summ',array('class'=>'span3','maxlength'=>18)); 
        		?>
                
                
                
            </div>
                                                     
        </div>
        
        <div class="well" style="background-color: white;">
            <h3>Сведения о передаче  материалов в органы внутренних дел в порядке ст. 82 НК РФ</h3>
            <hr />
            
            <?php // Материалы переданы в органы внутренних дел (дата письма)
    			echo $form->textFieldRow($model,'material_to_UVD_date',array('class'=>'span2','maxlength'=>10,
                    'prepend' => '<i class="icon-calendar"></i>', 'isdate'=>'')); 
    		?>
                
            <?php // Материалы переданы в органы внутренних дел (номер письма)
    			echo $form->textFieldRow($model,'material_to_UVD_num',array('class'=>'span3','maxlength'=>25)); 
    		?>
    		    		
            <?php // Материалы переданы в органы внутренних дел (ст. УК РФ) 			
                echo $form->checkBoxListRow($model,'materialUVDarticles',
                    CHtml::listData(DirectoryArticle::model()->findAll(
                        array('condition'=>'type_article=2')),'id','value'));  
    		?>
            <br /><br />
    		
    		
            
            
            <div class="well" style="background-color: white;">
                <h4>Результат рассмотрения органами внутренних дел материалов налоговых проверок</h4>
                <hr />
                
                <?php // Результат рассмотрения материалов органами  внутренних дел  (возбуждено УД)
        			echo $form->dropDownListRow($model,'result_see_OVD_filed', 
        					CHtml::listData(DirectoryArticle::model()->findAll(array(
        					   'condition'=>'type_article=2')),'id','value'),
        					array('empty'=>array(''=>''),'class'=>'span3')); 
        		?>
        		
        		<?php // Результат рассмотрения материалов органами  внутренних дел (отказано в возбуждении УД)
        			echo $form->dropDownListRow($model,'result_see_OVD_refused', 
        					CHtml::listData(DirectoryArticle::model()->findAll(array(
        					   'condition'=>'type_article=2')),'id','value'),
        					array('empty'=>array(''=>''),'class'=>'span3')); 
        		?>
                
                <?php // Примечание
        			echo $form->textAreaRow($model,'note_OVD', 
                        array('rows'=>'3','class'=>'span5','maxlength'=>500)); ?>  
                
            </div>
        </div>
        
                                                                                               
		
	</div>