<?php
    $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modalViewCheck', 
        'htmlOptions'=>array('style'=>'width:95%; height:95%; margin-left:2%; margin-top:0; top:0; left:0;')));    
?>

    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4>Просмотр</h4>
    </div>
    
        
    <div class="modal-body" id="viewCheck" style="max-height: 100%;">
    
    </div>
            
    <div class="modal-footer">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'label'=>'Отмена',
            'url'=>'#',
            'htmlOptions'=>array('data-dismiss'=>'modal'),
        )); ?>        
    </div>        

<?php $this->endWidget(); ?>










