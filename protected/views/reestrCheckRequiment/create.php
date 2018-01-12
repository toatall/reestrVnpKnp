<?php
$this->breadcrumbs=array(
	'Reestr Check Requiments'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ReestrCheckRequiment','url'=>array('index')),
	array('label'=>'Manage ReestrCheckRequiment','url'=>array('admin')),
);
?>

<h1>Создать требование </h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'id_reestr'=>$id_reestr)); ?>