<?php
    $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'property'));    
?>
    
    <script type="text/javascript">
        function loadProperty(fieldName, checkValues)
        {              
            $.ajax({
                'url': '<?php echo Yii::app()->createUrl('directoryProperty/getList'); ?>?'                   
                    + '&check_values=' + checkValues,
                'method': 'get',
                'global': false,
            }).done(function(html) {
                $('#dataProperty').html(html);
                $('#property').modal('show');
                $('#fieldNameResultProperty').val(fieldName);
            });                        
        }
        
        function ReturnResultProperty()
        {
            var values = '';            
            jQuery("input[name='columnsProperty\[\]']").each(function() {
                if (this.checked) {
                    values += this.value + '/';
                }
            });                        
            $($('#fieldNameResultProperty').val()).val(values);
            $('#property').modal('hide');
        }
        
    </script>
    
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4>Статьи</h4>
    </div>  
            
    <div class="modal-body" style="font-size: 12px;">
        <style type="text/css">
            #columnsProperty input {
                float: left;
                margin-right: 10px;
            }
        </style>
        <input type="hidden" value="" id="fieldNameResultProperty" />                  
        <div id="dataProperty"></div>
        
    </div>
   
    <div class="modal-footer">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'label'=>'Отмена',
            'url'=>'#',
            'htmlOptions'=>array('data-dismiss'=>'modal'),
        )); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'label'=>'OK',
            //'buttonType'=>'submit',            
            'htmlOptions'=>array(                
                'id'=>'toField',
                'onclick' => 'ReturnResultProperty();',                
            ),
        )); ?>
    </div>   


<?php $this->endWidget(); ?>