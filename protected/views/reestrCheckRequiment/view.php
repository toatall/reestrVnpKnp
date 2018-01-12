<?php
$this->breadcrumbs=array(
	'Reestr Check Requiments'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ReestrCheckRequiment','url'=>array('index')),
	array('label'=>'Create ReestrCheckRequiment','url'=>array('create')),
	array('label'=>'Update ReestrCheckRequiment','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete ReestrCheckRequiment','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ReestrCheckRequiment','url'=>array('admin')),
);
?>

<h1>View ReestrCheckRequiment #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_reestr',
		'requiment_number',
		'requiment_date',
		'requiment_term',
		'requiment_summ',
		'requiment_summ_rest',
		'recovered_summ',
	),
)); ?>
