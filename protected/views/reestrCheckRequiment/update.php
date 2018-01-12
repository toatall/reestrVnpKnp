<?php
$this->breadcrumbs=array(
	'Reestr Check Requiments'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ReestrCheckRequiment','url'=>array('index')),
	array('label'=>'Create ReestrCheckRequiment','url'=>array('create')),
	array('label'=>'View ReestrCheckRequiment','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage ReestrCheckRequiment','url'=>array('admin')),
);
?>

<h1>Требование #<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model,'id_reestr'=>$id_reestr)); ?>