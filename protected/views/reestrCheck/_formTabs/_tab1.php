	<div class="form-actions" style="padding: 20px;">
        <div class="well" style="background-color: white;">
    		<?php
    	        echo $form->dropDownListRow($model,'code_NO',
    	            /*CHtml::listData(Yii::app()->user->admin ? Ifns::model()->findAll()
                    : Login::model()->getAccessIfns(Yii::app()->user->id),'code_no','name')*/
                    CHtml::listData(Ifns::model()->findAll(),'code_no','name'),
    	            array('class'=>'span6')
    	        );
            ?>

            <?php
    	        echo $form->dropDownListRow($model,'code_NO_current_dept',
    	            /*CHtml::listData(Yii::app()->user->admin ? Ifns::model()->findAll()
                    : Login::model()->getAccessIfns(Yii::app()->user->id),'code_no','name')*/
                    CHtml::listData(Ifns::model()->findAll(),'code_no','name'),
    	            array('class'=>'span6')
    	        );
            ?>

            <?php /*echo $form->dropDownListRow($model,'type_check',
                CHtml::listData(DirectoryTypeCheck::model()->findAll(),'id','value'),
                array('empty'=>array(''=>'')));*/ ?>

    		<?php echo $form->textFieldRow($model,'inn_NP',array('class'=>'span2','maxlength'=>12)); ?>

    		<?php echo $form->textFieldRow($model,'kpp_NP',array('class'=>'span2','maxlength'=>9)); ?>

    		<?php echo $form->textFieldRow($model,'name_NP',array('class'=>'span8','maxlength'=>500)); ?>

            <?php echo $form->dropDownListRow($model, 'type_NP', array(''=>'', 1=>'ЮЛ', 2=>'ФЛ')); ?>

            <br /><br />

            <div class="well" style="background-color: white;">
                <h4>Сведения о снятии с учета налогоплательщика</h4>
                <hr />

                <?php // сведения о снятии с учета налогоплательщика (Дата снятия с учета)
                    echo $form->textFieldRow($model,'info_removal_register_NP_date',array(
                        'class'=>'span2','maxlength'=>10,
                        'prepend' => '<i class="icon-calendar"></i>', 'isdate'=>''
                        /*,'labelOptions'=>array('label'=>false)*/)); ?>

                <?php echo $form->textAreaRow($model,'info_removal_register_NP_reason',
                    array('rows'=>'3','class'=>'span5','maxlength'=>500)); ?>

                <?php echo $form->textFieldRow($model,'info_removal_register_NP_to_NO'
                    ,array('class'=>'span1','maxlength'=>4)); ?>

            </div>
            
            
            <div class="well" style="background-color: white;">            	
            	<?php echo $form->dropDownListRow($model, 'query_in_otdel_UZ_conclusion',
            			array(0=>'Нет',1=>'Да'), array('class'=>'span2')); ?>
            	<?php echo $form->textFieldRow($model,'query_in_otdel_UZ_date',array(
                        'class'=>'span2','maxlength'=>10,
                        'prepend' => '<i class="icon-calendar"></i>', 'isdate'=>'')); ?>
                <?php echo $form->textFieldRow($model,'query_in_otdel_UZ_from_IFNS_date',array(
                        'class'=>'span2','maxlength'=>10,
                        'prepend' => '<i class="icon-calendar"></i>', 'isdate'=>'')); ?>
            </div>

        </div>



        <div class="well" style="background-color: white;">
            <h3>Решение о привлечении к ответственности (об отказе в привлечении к ответственности)</h3>
            <hr />

            <?php // доначислено по решению всего (тыс. руб.)
                echo $form->textFieldRow($model,'credit_addational',array('class'=>'span3','maxlength'=>18)); ?>

            <?php // дата решения
                echo $form->textFieldRow($model,'resolution_date',array('class'=>'span2','maxlength'=>10,
                    'prepend' => '<i class="icon-calendar"></i>', 'isdate'=>'')); ?>


    		<?php // номер решения
    			echo $form->textFieldRow($model,'resolution_number',array('class'=>'span3','maxlength'=>25));
    		?>

            <?php echo $form->dropDownListRow($model,'type_check',
                CHtml::listData(DirectoryTypeCheck::model()->findAll(),'id','value'),
                array('empty'=>array(''=>''))); ?>

        </div>

        <?php
            if (!$model->isNewRecord):


        ?>
        <div class="well" style="background-color: white;">
            <h3>Требования об уплате налогов</h3>
            <?php /*

            ?>
                <table class="items table">
                    <tr>
                        <th>Номер</th>
                        <th>Дата</th>
                        <th>Срок уплаты</th>
                        <th>Сумма включенная в требование (тыс. руб.)</th>
                        <th>Остаток непогашенной суммы по требованию (тыс. руб.)</th>
                        <th>Взыскано (тыс. руб.)</th>
                    </tr>
            <?php
                if (count($model->requiments)):

                foreach ($model->requiments as $val):
            ?>
                    <tr>
                        <td><?php echo $val['requiment_number']; ?></td>
                        <td><?php echo $val['requiment_date']; ?></td>
                        <td><?php echo $val['requiment_term']; ?></td>
                        <td><?php echo $val['requiment_summ']; ?></td>
                        <td><?php echo $val['requiment_summ_rest']; ?></td>
                        <td><?php echo $val['recovered_summ']; ?></td>
                    </tr>
            <?php
                endforeach;

                else:
            ?>
                <tr>
                    <td colspan="6" style="text-align: center;">Нет данных</td>
                </tr>
            <?php
            
                endif;
            ?>
                </table>
        </div>
        <?php
            */
                                    
            $this->renderPartial('modal/_requiment',array('model'=>$model));         
            $this->widget('bootstrap.widgets.TbButton', array(
        		'label'=>'Создать требование',
        		'type'=>'primary',
        		'htmlOptions'=>array(
                    'data-toggle'=>'modal',
                    'data-target'=>'#modal_requiment',
                    'onclick'=>'loadRequiment("'.
                        Yii::app()->createUrl('reestrCheckRequiment/create', array('id_reestr'=>$model->id)).'");',
                ),
        	));
            ?>            
            
            <?php
                Yii::app()->clientScript->registerScript('btn-update',"
                    $('#modal_requiment').on('hidden.bs.modal', function(e) {
                       e.preventDefault();
                       $('#reestr-check-requiment-grid').yiiGridView('update');
                    });
                ",
                CClientScript::POS_READY);
            ?>
                                   
            <?php
            
            $model_requiment = new ReestrCheckRequiment('search');

            $this->widget('bootstrap.widgets.TbGridView',array(
            	'id'=>'reestr-check-requiment-grid',
            	'dataProvider'=>$model_requiment->search($model->id),
                //'filter'=>$model_requiment,
            	'columns'=>array(
            		/*'id',
                    'id_reestr',*/
                    'requiment_number',
                    'requiment_date',
                    'requiment_term',
			        'requiment_summ',
			        'requiment_summ_rest',
                    'recovered_summ',
            		array(
            			'class'=>'bootstrap.widgets.TbButtonColumn',
                        'template'=>'{update} {delete}',
                        'buttons'=>array(
                            'update'=>array(
                                'label'=>'Изменить',
                                'click'=>'function() { loadRequiment($(this).attr("href")); return false; }',
                                'url'=>'Yii::app()->controller->createUrl("reestrCheckRequiment/update",array("id"=>$data->primaryKey,"id_reestr"=>'.$model->id.'))',
                                'options'=>array(
                                    'data-toggle'=>'modal',
                                    'data-target'=>'#modal_requiment',
                                ),                                 
                            ),
                            'delete'=>array(                                
                                'url'=>'Yii::app()->controller->createUrl("reestrCheckRequiment/delete",array("id"=>$data->primaryKey,"id_reestr"=>'.$model->id.'))',
                            	/*'options'=>array(
                            		'onclick'=>'function() { $.get($(this).attr("href"), function() { $("#reestr-check-requiment-grid").yiiGridView("update"); }); return false; }',
            					),*/
                            ),
                        ),
            		),
            	),
            ));                        
		?>
		</div>
		            
        <?php endif; ?>
        
        
        <div class="well" style="background-color: white;">
            <h3>Сведения о результатах рассмотрения материалов проверки в ВНО и Арбитражном суде</h3>
            <hr />

               <?php // Уменьшено по решению вышестоящего налогового органа, всего (тыс. руб.)
        			echo $form->textFieldRow($model,'reduced_higher_NO_summ',array('class'=>'span3','maxlength'=>18));
        		?>

        		<?php // Уменьшено по решению Арбитражного суда   (тыс. руб.)
        			echo $form->textFieldRow($model,'reduced_arb_court_summ',array('class'=>'span3','maxlength'=>18));
        		?>
        </div>

        <div class="well" style="background-color: white;">

    		<?php // Решение о принятии обеспечительных мер (по невзысканным платежам) / запрет на отчуждение имущества / номер
    			echo $form->textFieldRow($model,'resolution_adop_sec_measure_ban_alien_num',array('class'=>'span3','maxlength'=>25));
    		?>

             <?php // Решение о принятии обеспечительных мер (по невзысканным платежам) / запрет на отчуждение имущества / дата
                echo $form->textFieldRow($model,'resolution_adop_sec_measure_ban_alien_date',array('class'=>'span2','maxlength'=>10,
                    'prepend' => '<i class="icon-calendar"></i>', 'isdate'=>'')); ?>

        </div>

        <div class="well" style="background-color: white;">

    		<?php // Решение о принятии обеспечительных мер (по невзысканным платежам) / приостановление операций по счетам / номер
    			echo $form->textFieldRow($model,'resolution_adop_sec_measure_susp_oper_num',array('class'=>'span3','maxlength'=>25));
    		?>

             <?php // Решение о принятии обеспечительных мер (по невзысканным платежам) / приостановление операций по счетам / дата
                echo $form->textFieldRow($model,'resolution_adop_sec_measure_susp_oper_date',array('class'=>'span2','maxlength'=>10,
                    'prepend' => '<i class="icon-calendar"></i>', 'isdate'=>'')); ?>

        </div>


	</div>
