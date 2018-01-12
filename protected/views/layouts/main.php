<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
    
	<!-- blueprint CSS framework -->
	<!--link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" /-->
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<!--link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" /-->
	<!--link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" /-->
    
        
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/styles.css" />
    
    <?php Yii::app()->bootstrap->register(); ?>
         
    
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<?php
     Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/extension/spoiler/spoiler.js');
     Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/extension/spoiler/spoiler.css');
?>

<?php $this->widget('bootstrap.widgets.TbNavbar',array(
    'brandUrl' => array('/site/index'),
    'items'=>array(
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'items'=>array(              
                array('label'=>'Главная', 'url'=>array('/reestrCheck/admin'),
                    'visible'=>!Yii::app()->user->isGuest),
                array('label'=>'Архив', 'url'=>array('/reestrCheckArchive/admin'),
                    'visible'=>!Yii::app()->user->isGuest),
                array('label'=>'Пользователи', 'url'=>array('/login/admin'), 
                    'visible'=>!Yii::app()->user->isGuest && Yii::app()->user->admin),
                array('label'=>'Справка', 'url'=>array('site/help')),
                array('label'=>'Вход', 'url'=>array('login'), 
                    'visible'=>Yii::app()->user->isGuest),
                ////array('label'=>'Выход ('.Yii::app()->user->name.')', 'url'=>array('site/logout'), 
                ////    'visible'=>!Yii::app()->user->isGuest),
                                                
            ),
        ),        
    ),
)); ?>

<div class="container" id="page">
        
    <?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
            'homeLink' => CHtml::link('Реестр', array('/site/index')),
			'links' => $this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>
    

	<?php echo $content; ?>

	<div class="form-actions">
	<div id="footer">
		Реестр проверок &copy; <?php echo date('Y'); ?><br />
        Разработка: Трусов Олег Алексеевич<br />
        Дата создания 13.07.2015<br />
		Дата изменения 08.09.2015
	</div><!-- footer -->
    </div>

</div><!-- page -->

</body>
</html>
