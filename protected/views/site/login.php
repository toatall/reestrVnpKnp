<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>

<div class="well" style="margin: 10% 30% 0 30%;">

<h1>Аутентификация</h1>

<p>Пожалуйста введите Ваш логин и пароль</p>

<div class="form">
<?php         

    $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    	'id'=>'login-form',
    	'enableClientValidation'=>true,
    	'clientOptions'=>array(
    		'validateOnSubmit'=>true,
    	),
    ));
    
?>
       
    <?php echo $form->textFieldRow($model, 'username'); ?>
    
    <?php echo $form->passwordFieldRow($model, 'password'); ?>
    
    <?php //echo $form->checkBoxRow($model, 'login_windows'); ?>    	
    
    <!--script type="text/javascript">
        $(function() {
            $('#<?php echo CHtml::activeId($model, 'login_windows'); ?>').click(function() {
                if ($(this).is(':checked')) {
                    $('#login_info').show();
                } else {
                    $('#login_info').hide();
                }
            });    
        });
    </script>
    
    <div class="alert alert-info" id="login_info">
        Введите Ваш логин в формате 86XX-XX-XXX
    </div-->
    
    
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Вход',
            'htmlOptions'=>array(
                'style'=>'width:130px',
                'float:right;'
            ),
		)); ?>
	</div>

<?php $this->endWidget(); ?>
</div>
</div><!-- form -->
