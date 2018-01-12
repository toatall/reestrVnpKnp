<?php if(Yii::app()->user->hasFlash('success')):?>

    <?php $this->widget('bootstrap.widgets.TbAlert', array(
        'block'=>true, // display a larger alert block?
        'fade'=>true,
        'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
        'alerts'=>array( // configurations per alert type
            'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
        ),        
    )); ?>
    
    
    
    <div class="form-actions" style="text-align: right;">              
    <?php $this->widget('bootstrap.widgets.TbButton', array(
            'label'=>'Закрыть',
            'url'=>'#',
            'htmlOptions'=>array(
                'data-dismiss'=>'modal',
                'id'=>'cancel-button',
            ),
        )); ?>
    </div>    
    
<?php else: ?>

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    	'id'=>'reestr-check-requiment-form',
        //'enableAjaxValidation'=>true,
        //'method'=>'get',
    	/*'enableClientValidation'=>true,
    	'clientOptions'=>array(
    		'validateOnSubmit'=>true,        
    	),*/
    )); ?>
   
	<p class="help-block">Поля, отмеченные звездочкой <span class="required">*</span> обязательны для заполнения.</p>

	<?php echo $form->errorSummary($model); ?>   
    
    <input type="hidden" name="ajax" value="1" />
    
    <?php echo $form->hiddenField($model,'id_reestr',array('value'=>$id_reestr)); ?>

	<?php echo $form->textFieldRow($model,'requiment_number',array('class'=>'span5','maxlength'=>25)); ?>

	<?php echo $form->textFieldRow($model,'requiment_date',array('class'=>'span5', 
        'isdate'=>'', 'prepend' => '<i class="icon-calendar"></i>')); ?>

	<?php echo $form->textFieldRow($model,'requiment_term',array('class'=>'span5',
        'isdate'=>'', 'prepend' => '<i class="icon-calendar"></i>')); ?>

	<?php echo $form->textFieldRow($model,'requiment_summ',array('class'=>'span5','maxlength'=>18)); ?>

	<?php echo $form->textFieldRow($model,'requiment_summ_rest',array('class'=>'span5','maxlength'=>18)); ?>

	<?php echo $form->textFieldRow($model,'recovered_summ',array('class'=>'span5','maxlength'=>18)); ?>
        
	<div class="form-actions" style="text-align: right;">	        
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Создать' : 'Сохранить',
            'htmlOptions'=>array('id'=>'btn-save-requiment-form')
		)); ?>
        
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'label'=>'Отмена',
            'url'=>'#',
            'htmlOptions'=>array(
                'data-dismiss'=>'modal',
                'id'=>'cancel-button'
            ),
        )); ?>
  
        
	</div>        
    
    <script type="text/javascript">               
        
        $('#btn-save-requiment-form').on('click',function(){
            $(this).prop('disabled', true);
            $.ajax({
                type: 'POST',
                url: $('#reestr-check-requiment-form').attr('action'),                
                data: $('#reestr-check-requiment-form').serialize(),
                global: false,
            })
            .done(function(html) {
                $('#data_reestr_requiment').html(html);
            })
            .fail(function(jqXHR, textStatus){
                alert(textStatus);
            });
            return false;
        }); 
                               
    </script>
    
    <?php $this->endWidget(); ?>

<?php endif; ?>

