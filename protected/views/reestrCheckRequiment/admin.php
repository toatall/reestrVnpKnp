<?php
$this->breadcrumbs=array(
	'Reestr Check Requiments'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List ReestrCheckRequiment','url'=>array('index')),
	array('label'=>'Create ReestrCheckRequiment','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('reestr-check-requiment-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Reestr Check Requiments</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'reestr-check-requiment-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'id_reestr',
		'requiment_number',
		'requiment_date',
		'requiment_term',
		'requiment_summ',
		/*
		'requiment_summ_rest',
		'recovered_summ',
		*/
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
