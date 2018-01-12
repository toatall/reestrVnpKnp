<?php
    $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'ajaxLoading'));    
?>    
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4>Загрузка....</h4>
    </div>   
    <div class="modal-body" style="text-align: center;">
        <img src="/images/loader.gif" style="margin: auto auto;" />
    </div>     
<?php $this->endWidget(); ?>