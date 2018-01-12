<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'reestr-check-form',
	//'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>
	
	<?php       
        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.js');
        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.ru.js');
        Yii::app()->getClientScript()->registerCssFile(
            Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.css');
        Yii::app()->clientScript->registerScriptFile(
        		Yii::app()->baseUrl.'/js/jquery.mask.min.js');      
                
        //Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/extension/spoiler/spoiler.js');
        //Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/extension/spoiler/spoiler.css');                              
    ?>        
    
	<p class="help-block">Поля, отмеченные звездочкой <span class="required">*</span> обязательны для заполнения.</p>

	<?php echo $form->errorSummary($model); ?>      
	
	<?php 
		$this->widget('bootstrap.widgets.TbTabs', array(
			'id'=>'myTabs',
	        'type'=>'tabs',
	        'encodeLabel'=>false,
	        'tabs'=>array(
	        	array(
	        		'label'=>'I. Общие сведения', 
	        		'content'=>$this->renderPartial('_formTabs/_tab1', 
	        			array('form'=>$form, 'model'=>$model), true, true), 
	        		'active'=>true,	        		
				),
	        	array(
	        		'label'=>'II. Дополнительные сведения',
	        		'content'=>$this->renderPartial('_formTabs/_tab2',
	        			array('form'=>$form, 'model'=>$model), true, true),	        		
	        	),   	
			),
		));	
	?>				

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Создать' : 'Сохранить',
            'htmlOptions'=>array(
                'onclick'=>'fastWindowClose()',
            ),
		)); ?>
	</div>
    
    
    <script type="text/javascript">
        
        $('input[isdate]').each(function() {               
            $(this).datepicker({
	            'format':'dd.mm.yyyy',
	            'autoclose':'true',
	            'todayBtn':'linked',
	            'language':'ru',
	            'weekStart':0,
           }).mask(
	    		'00.00.0000',
	    		{'placeholder':'__.__.____'}
	    	);
        });    
        
        function fastWindowClose()
        {
            window.opener.$('#search-form').submit();
            window.close();
        }
                        
    </script>
        
<?php $this->endWidget(); ?>
