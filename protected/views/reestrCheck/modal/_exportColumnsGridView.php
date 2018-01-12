<?php
    $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'exportColumns'));    
?>
    
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4>Реквизиты для экспорта</h4>
    </div>
    <form id="checkExportColumnGV">
    <div class="well">
        Формат экспорта<br /><?php echo CHtml::dropDownList('exportFormat', '', array(
            //'xlsx'=>'XLSX (Excel 2007)',
            'xls' =>'XLS  (Excel 2000-2003)',
        ));  ?>
        <br />
        <?php
            echo CHtml::checkBox('useFilter', true, array('style'=>'float: left; margin-right: 5px;'));
            echo CHtml::label('С учетом фильтра', 'useFilter');
        ?>
    </div>
    
            
    <div class="modal-body" style="font-size: 12px;">
        <style type="text/css">
            #columns input {
                float: left;
                margin-right: 10px;
            }
        </style>
                         
        <?php       
            $listData = ReestrCheck::model()->attributeLabels();                               
            echo CHtml::checkBoxList('columns', 
                ReestrCheck::model()->defaultAttributeLabels(),//array_keys($listData), 
                $listData,  
                array('separator'=>'', 'checkAll'=>'Выбрать все'));
        ?>
        
    </div>
    </form>       
    <div class="modal-footer">
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
                'id'=>'btnExportSubmit' 
                //'onclick'=>'$("#form-view-columns-grid-view").submit();'
            ),
        )); ?>
    </div>        

<?php $this->endWidget(); ?>