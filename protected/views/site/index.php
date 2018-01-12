<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h1><i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<p>Для продолжения выберите раздел.</p>

<ul style="font-size: 18px;">
    <li><?php echo CHtml::link('Реестр', array('reestrVnpKp/admin')); ?></li>
</ul>