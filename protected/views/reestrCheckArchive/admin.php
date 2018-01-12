<?php   
    
    if (!isset(Yii::app()->session['viewColumnGridView']))
    {
        Yii::app()->session['viewColumnGridView'] = array_merge(
             ReestrCheck::model()->defaultAttributeLabels()
            ,array('comment_arch')
        );
    }
?>
<?php

    $this->renderPartial('modal/_loading',array());
    
    $this->renderPartial('modal/_viewCheck',array());

?>
<script type="text/javascript">
    
    var loading = false;
    
    $('body').children().ajaxStart(function(){            
        if(!loading)
        {                
            $("#ajaxLoading").modal("show");                    
            loading = true;
        }
    });
    
    $('body').children().ajaxStop(function(){
        if(loading)
        {
            $("#ajaxLoading").modal("hide");
            loading = false;
        }
    });
    
</script>
<?php
$this->breadcrumbs=array(	
	'Управление',
);

$this->menu=array(
	//array('label'=>'Создать запись','url'=>array('create'), 'icon'=>'asterisk'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('reestr-check-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><span style="color: #950000;">(АРХИВ)</span>
    <br />Реестр невзысканных сумм по налоговым проверкам (ВНП, КНП)
</h1>

<style type="text/css">
    .style_row {
        background-color: #F5F9FE;
    } 
    .style_row_footer {
        background-color:#FFFDC0; 
        font-weight:bold;
    }
    .filter-date-width {
        width:100px;
    }
</style>



<?php       
    Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.js');
    Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.ru.js');
    Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl.'/extension/date-picker/defaultScriptLoadDatePicker.js');    
    Yii::app()->getClientScript()->registerCssFile(
        Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.css');
    Yii::app()->clientScript->registerScriptFile(
    		Yii::app()->baseUrl.'/js/jquery.mask.min.js');        
?>
<br /><br />

<?php $this->widget('bootstrap.widgets.TbButtonGroup', array(
    'type' => 'info',
    'toggle' => 'radio', // 'checkbox' or 'radio'
    'buttonType' => 'button',
    'buttons' => array(
        array('label'=>'Фильтр','active'=>true, 'icon'=>'filter',
            'htmlOptions'=>array('style'=>'width:150px; height:40px;','id'=>'button-search')),        
        array('label'=>'Результат', 'icon'=>'th-list',
            'htmlOptions'=>array('style'=>'width:150px; height:40px;','id'=>'button-search-result')),      
    ),
)); ?>
<hr />
<script type="text/javascript">
    $('#button-search').on('click', function() {
        $('.search-form').show();
        $('.search-form-result').hide();        
    });
    $('#button-search-result').on('click', function() {
        $('.search-form').hide();
        $('.search-form-result').show();
    });
</script>
<?php

    $this->renderPartial('_search', array('model'=>$model));
    $this->renderPartial('_adminGridView',array('model'=>$model));            

?>



