<?php
    $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'articles'));    
?>
    
    <script type="text/javascript">
        function loadArticles(typeArticle, fieldName, checkValues)
        {              
            $.ajax({
                'url': '<?php echo Yii::app()->createUrl('directoryArticle/getList'); ?>?' 
                    + 'type_article=' + typeArticle 
                    + '&check_values=' + checkValues,
                'method': 'get',
                'global': false,
            }).done(function(html) {
                $('#data').html(html);
                $('#articles').modal('show');
                $('#fieldNameResult').val(fieldName);
            });                        
        }
        
        function ReturnResultArticle()
        {
            var values = '';            
            jQuery("input[name='columns\[\]']").each(function() {
                if (this.checked) {
                    values += this.value + '/';
                }
            });                        
            $($('#fieldNameResult').val()).val(values);
            $('#articles').modal('hide');
        }
        
    </script>
    
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4>Статьи</h4>
    </div>  
            
    <div class="modal-body" style="font-size: 12px;">
        <style type="text/css">
            #columns input {
                float: left;
                margin-right: 10px;
            }
        </style>
        <input type="hidden" value="" id="fieldNameResult" />                  
        <div id="data"></div>
        
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
                //'data-dismiss'=>'modal',
                'id'=>'toField',
                'onclick' => 'ReturnResultArticle();',
            ),
        )); ?>
    </div>   


<?php $this->endWidget(); ?>