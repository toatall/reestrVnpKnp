<?php
    
    // если еще не выбраны столбцы (не создана сессия с выбранными столбцами),
    //     то загружаем столбцы по-умолчанию   
    if (!isset(Yii::app()->session['viewColumnGridView']))
    {
        Yii::app()->session['viewColumnGridView'] = ReestrCheck::model()->defaultAttributeLabels();
    }

?>

<?php     
    echo CHtml::beginForm(array('admin'),'POST',array('id'=>'form-view-columns-grid-view'));     
    $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'checkColumns'));         
?>
    
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4>Реквизиты для отображения</h4>        
    </div>
            
    <div class="modal-body" style="font-size: 12px;">
        <style type="text/css">
            #checkBoxListViewColumnGridView input {
                float: left;
                margin-right: 10px;
            }
        </style>           
        <?php                          
            echo CHtml::checkBoxList('checkBoxListViewColumnGridView', 
                Yii::app()->session['viewColumnGridView'], 
                ReestrCheck::model()->attributeLabels(),
                array('separator'=>'', 'checkAll'=>'Выбрать все'));
        ?>
    </div>
            
    <div class="modal-footer">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'label'=>'Сбросить',
            'url'=>array('adminViewColumnsReset'),            
        )); ?>        
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'label'=>'Отмена',
            'url'=>'#',
            'htmlOptions'=>array('data-dismiss'=>'modal'),
        )); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'label'=>'OK',
            'buttonType'=>'submit',            
            'htmlOptions'=>array(
                'data-dismiss'=>'modal', 
                'onclick'=>'$("#form-view-columns-grid-view").submit();'
            ),
        )); ?>
    </div>
    
    <?php echo CHtml::endForm(); ?>
    
    <?php $this->endWidget(); ?>

