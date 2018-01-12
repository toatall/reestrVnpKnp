<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php echo $form->textFieldRow($model,'id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'id_reestr',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'requiment_number',array('class'=>'span5','maxlength'=>25)); ?>

	<?php echo $form->textFieldRow($model,'requiment_date',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'requiment_term',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'requiment_summ',array('class'=>'span5','maxlength'=>18)); ?>

	<?php echo $form->textFieldRow($model,'requiment_summ_rest',array('class'=>'span5','maxlength'=>18)); ?>

	<?php echo $form->textFieldRow($model,'recovered_summ',array('class'=>'span5','maxlength'=>18)); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
