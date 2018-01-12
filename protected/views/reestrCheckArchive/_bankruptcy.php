<?php
    $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'bankruptcy'));    
?>
    
    <script type="text/javascript">
        function loadBankruptcy(fieldName, checkValues)
        {              
            $.ajax({
                'url': '<?php echo Yii::app()->createUrl('directoryBankruptcy/getList'); ?>?'                     
                    + '&check_values=' + checkValues,
                'method': 'get',
                'global': false,
            }).done(function(html) {
                $('#dataBankruptcy').html(html);
                $('#bankruptcy').modal('show');
                $('#fieldNameResult').val(fieldName);
            });                        
        }
        
        function ReturnResultBankruptcy()
        {
            var values = '';            
            jQuery("input[name='columns\[\]']").each(function() {
                if (this.checked) {
                    values += this.value + '/';
                }
            });                        
            $($('#fieldNameResult').val()).val(values);
            $('#bankruptcy').modal('hide');
        }
        
    </script>
    
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4>Текущая процедура банкротства</h4>
    </div>  
            
    <div class="modal-body" style="font-size: 12px;">
        <style type="text/css">
            #columns input {
                float: left;
                margin-right: 10px;
            }
        </style>
        <input type="hidden" value="" id="fieldNameResult" />                  
        <div id="dataBankruptcy"></div>
        
    </div>
   
    <div class="modal-footer">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'label'=>'Отмена',
            'url'=>'#',
            'htmlOptions'=>array('data-dismiss'=>'modal'),
        )); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'label'=>'OK',              
            'htmlOptions'=>array(
                'id'=>'toField',
                'onclick' => 'ReturnResultBankruptcy();',
            ),
        )); ?>
    </div>   


<?php $this->endWidget(); ?>