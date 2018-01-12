<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'login-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Поля обозначенные <span class="required">*</span> являются обязательными для заполнения.</p>

	<?php echo $form->errorSummary($model); ?>
           
    <!--div class="well">
    <strong>Random-пароли:</strong><br />
    <?php /*
        for ($i=0; $i<5; $i++)
        {
            echo substr(sha1(rand(0,100).rand(0,100).rand(0,100)),1,8).'<br />';    
        }
        */
    ?>
    </div-->
        
	<?php //echo $form->textFieldRow($model,'login_name',array('class'=>'span5','maxlength'=>500)); ?>
    
    <?php echo $form->textFieldRow($model,'login_windows',array('class'=>'span5','maxlength'=>100)); ?>

	<?php //echo $form->passwordFieldRow($model,'login_password',array('class'=>'span5','maxlength'=>500)); ?>
    <?php //echo $form->passwordFieldRow($model,'confirm_login_password',array('class'=>'span5','maxlength'=>500)); ?>

	<?php echo $form->textFieldRow($model,'login_description',array('class'=>'span5','maxlength'=>500)); ?>

	<?php echo $form->checkBoxRow($model,'role_admin'); ?>

	<?php echo $form->checkBoxRow($model,'blocked'); ?>
    
    <?php echo $form->textFieldRow($model,'code_no',array('class'=>'span1','maxlength'=>4)); ?>
    <?php /*echo $form->dropDownListRow($model,'code_no',
        CHtml::listData(Ifns::model()->findAll(),'code_no','name'),
        array('class'=>'span7'));*/ ?>
    
    <?php /* удалить после запуска в ПЭ
    <div class="thumbnail">
        <h4 class="well">Доступные налоговые органы</h4>
        <div style="margin-left: 20px;">
    <?php echo $form->checkBoxList($model,'userAccessIfns',
        CHtml::listData(Ifns::model()->findAll(),'code_no','name')); ?>
        </div>
    </div>
    */ ?>
        
    
    <?php
        
        /** ini_set('display_errors','on');
        
        define('DOMAIN_FQDN', 'regions.tax.nalog.ru');
        define('LDAP_SERVER', '10.186.201.20');
        
        $conn = ldap_connect("ldap://". LDAP_SERVER ."/");
        
        if (!$conn)
            $err = 'Could not connect to LDAP server';

        else
        {
            
            //define('LDAP_OPT_DIAGNOSTIC_MESSAGE', 0x0032);
            ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($conn, LDAP_OPT_REFERRALS, 0);
            
            $bind = @ldap_bind($conn, 'REGIONS\8600-90-331', '18Fx4Ly18H');
            
            ldap_get_option($conn, 0x0032, $extended_error);
            
            if (!empty($extended_error))
            {
                echo $extended_error;
                
                $errno = explode(',', $extended_error);
                $errno = $errno[2];
                $errno = explode(' ', $errno);
                $errno = $errno[2];
                $errno = intval($errno);
    
                if ($errno == 532)
                    $err = 'Unable to login: Password expired';
                
                //print_r($errno);
                
            }
            else
            {
                echo 'OK';
            }
            
        
        }
        **/
    
    ?>
    
    
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Создать' : 'Сохранить',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
