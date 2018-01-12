<?php
$this->breadcrumbs=array(	
	$model->name_NP,
);

if (!isset($_GET['fast'])):

    $this->menu=array(	
    	array('label'=>'Восстановить','url'=>array('restore', 'id'=>$model->id), 'icon'=>'share-alt'),
    	array('label'=>'Управление','url'=>array('admin'), 'icon'=>'user'),
    );

endif;

?>

<h1 style="color: #950000;">Архив</h1>
<h1>Просмотр записи #<?php echo $model->id; ?></h1>

<?php 
	$this->widget('bootstrap.widgets.TbTabs', array(
		'id'=>'myTabs',        
        'type'=>'tabs',
        'encodeLabel'=>false,
        'tabs'=>array(
        	array(
        		'label'=>'I. Общие сведения', 
        		'content'=>$this->renderPartial('_viewTabs/_tab1', 
        			array('model'=>$model), true, true), 
        		'active'=>true,	        		
			),
        	array(
        		'label'=>'II. Дополнительные сведения',
        		'content'=>$this->renderPartial('_viewTabs/_tab2',
        			array('model'=>$model), true, true),	        		
        	),	
		),
	));	
?>