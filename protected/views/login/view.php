<?php
$this->breadcrumbs=array(
	'Logins'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'Создать','url'=>array('create')),
	array('label'=>'Изменить','url'=>array('update','id'=>$model->id)),
	array('label'=>'Удалить','url'=>'#','linkOptions'=>array(
        'submit'=>array('delete','id'=>$model->id),'confirm'=>'Вы уверены, что хотите удалить данную запись?')),
	array('label'=>'Управление','url'=>array('admin')),
);
?>

<h1>Просмотр пользователя #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'login_name',
		'login_password',	
		'login_description',
        array(
            'name'=>'role_admin',
            'value'=>($model->role_admin ? 'да' : 'нет'),
        ),
		array(
            'name'=>'blocked',
            'value'=>($model->blocked ? 'да' : 'нет'),
        ),
		'date_create',
		'date_modification',
	),
)); ?>
