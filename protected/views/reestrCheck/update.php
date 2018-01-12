<?php
$this->breadcrumbs=array(
	//'Реестр'=>array('admin'),
	$model->name_NP=>array('view','id'=>$model->id),
	'Изменить',
);

if (!isset($_GET['fast'])):

    $this->menu=array(
    	array('label'=>'Создать','url'=>array('create'), 'icon'=>'asterisk'),
    	array('label'=>'Просмотр','url'=>array('view','id'=>$model->id), 'icon'=>'eye-open'),
    	array('label'=>'Управление','url'=>array('admin'), 'icon'=>'user'),
    );

endif;

?>


<h1>Изменить запись #<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>