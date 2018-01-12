<?php

class DirectoryArticleController extends Controller
{
	public function actionGetList($type_article, $check_values)
	{
        if (is_numeric($type_article))
        {        
            $model = DirectoryArticle::model()->findAll('type_article=:type_article', array(
                ':type_article' => $type_article,
            ));
                        
            if ($check_values != '')
            {
                $selected = explode('/', $check_values);    
            }     
            else
            {
                $selected = '';
            }
            /*foreach ($model as $record)
            {
                echo CHtml::checkBox('DirectoryArticle[list][]', false, 
                    array('id'=>'check_'.$record->id, 'value'=>$record->id)); 
                echo CHtml::label($record->value, 'check_'.$record->id).'<br />';
            }*/
            echo CHtml::checkBoxList('columns', $selected
                ,CHtml::listData($model,'id','full_value')
                ,array('separator'=>''));
            
        }
        else
        {
            echo "Error $type_article";
        }
        /*$this->render('getList', array(
            //'field_name' => $field_name,
        ));*/
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}