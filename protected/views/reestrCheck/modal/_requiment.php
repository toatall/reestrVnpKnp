<?php
    $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modal_requiment'), true);
?>
    <script type="text/javascript">

        function loadRequiment(url)
        {
            $('#data_reestr_requiment').html('<img src="/images/loader.gif" />');

            $.ajax({
                url: url,
                method: 'post',
                global: false,
            }).done(function(html) {
                $('#data_reestr_requiment').html(html);
            });
        }
        
        //$('.modal-body').css('max-height', ($(window).height()-($(window).height() / 4)));

    </script>


    <style type="text/css">
        
        .modal-body {
            max-height: 500px;
        }
        
    </style>
    
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4>Редактирование требования</h4>
    </div>  
            
    <div class="modal-body" id="data_reestr_requiment">
    
    </div>        
    
<?php $this->endWidget(); ?>