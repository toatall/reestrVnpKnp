<?php
$this->breadcrumbs=array(
	//'Реестр'=>array('admin'),
	'Перенос в архив',
);

$this->menu=array(	
	array('label'=>'Реестр','url'=>array('admin')),
);
?>
<h1>Перенос в архив</h1>

<p>Укажаите причину переноса в архив.</p>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'reestr-check-form',
	//'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

    <?php echo $form->errorSummary($model); ?>
    
    <?php echo $form->textAreaRow($model,'comment_arch',array('style'=>'width:100%','rows'=>'8')); ?>
    
    
    <div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Архивировать',
		)); ?>
	</div>

<?php $this->endWidget(); ?>