<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('login_name')); ?>:</b>
	<?php echo CHtml::encode($data->login_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('login_password')); ?>:</b>
	<?php echo CHtml::encode($data->login_password); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('login_description')); ?>:</b>
	<?php echo CHtml::encode($data->login_description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('role_admin')); ?>:</b>
	<?php echo CHtml::encode($data->role_admin ? 'да' : 'нет'); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('blocked')); ?>:</b>
	<?php echo CHtml::encode($data->blocked); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('date_create')); ?>:</b>
	<?php echo CHtml::encode($data->date_create); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_modification')); ?>:</b>
	<?php echo CHtml::encode($data->date_modification); ?>
	<br />

	*/ ?>

</div>