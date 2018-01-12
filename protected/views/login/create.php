<?php
$this->breadcrumbs=array(
	'Пользователи'=>array('admin'),
	'Создание',
);

$this->menu=array(
	array('label'=>'Управление','url'=>array('admin')),
);
?>

<h1>Создание пользователя</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>