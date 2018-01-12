<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('code_no')); ?>:</b>
	<?php echo CHtml::encode($data->code_no); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('inn')); ?>:</b>
	<?php echo CHtml::encode($data->inn); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kpp')); ?>:</b>
	<?php echo CHtml::encode($data->kpp); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sum_3_30_1')); ?>:</b>
	<?php echo CHtml::encode($data->sum_3_30_1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sum_3_30_2')); ?>:</b>
	<?php echo CHtml::encode($data->sum_3_30_2); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('sum_3_30_3')); ?>:</b>
	<?php echo CHtml::encode($data->sum_3_30_3); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_create')); ?>:</b>
	<?php echo CHtml::encode($data->date_create); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('log_change')); ?>:</b>
	<?php echo CHtml::encode($data->log_change); ?>
	<br />

	*/ ?>

</div>