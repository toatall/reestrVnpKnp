<?php
$this->breadcrumbs=array(
	//'Реестр'=>array('admin'),
	'Создание',
);

$this->menu=array(	
	array('label'=>'Реестр','url'=>array('admin')),
);
?>


    
<h1>Создать запись</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>