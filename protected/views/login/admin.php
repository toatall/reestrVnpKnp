<?php
$this->breadcrumbs=array(
	'Logins'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Login','url'=>array('index')),
	array('label'=>'Create Login','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('login-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Управление пользователями</h1>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'login-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
        /*array(
            'name'=>'code_no',
            'filter'=>CHtml::listData(Ifns::model()->findAll(),'code_no','code_no'),
        ),*/
        'code_no',
		'login_windows',
		'login_description',
		array(
            'name'=>'role_admin',
            'value'=>'($data->role_admin ? "да" : "нет")',
            'filter'=>array(0=>'нет',1=>'да'),
        ), 	
		array(
            'name'=>'blocked',
            'value'=>'($data->blocked ? "да" : "нет")',
            'filter'=>array(0=>'нет',1=>'да'),
        ),            
		'date_create',
		'date_modification',
		
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
