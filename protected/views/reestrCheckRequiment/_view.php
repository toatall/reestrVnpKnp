<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_reestr')); ?>:</b>
	<?php echo CHtml::encode($data->id_reestr); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('requiment_number')); ?>:</b>
	<?php echo CHtml::encode($data->requiment_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('requiment_date')); ?>:</b>
	<?php echo CHtml::encode($data->requiment_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('requiment_term')); ?>:</b>
	<?php echo CHtml::encode($data->requiment_term); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('requiment_summ')); ?>:</b>
	<?php echo CHtml::encode($data->requiment_summ); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('requiment_summ_rest')); ?>:</b>
	<?php echo CHtml::encode($data->requiment_summ_rest); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('recovered_summ')); ?>:</b>
	<?php echo CHtml::encode($data->recovered_summ); ?>
	<br />

	*/ ?>

</div>