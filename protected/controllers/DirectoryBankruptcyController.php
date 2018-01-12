<?php

class DirectoryBankruptcyController extends Controller
{
	public function actionGetList($check_values)
	{            
        $model = DirectoryBankruptcy::model()->findAll();
                    
        if ($check_values != '')
        {
            $selected = explode('/', $check_values);
        }     
        else
        {
            $selected = '';
        }
                
        echo CHtml::checkBoxList('columns', $selected
            ,CHtml::listData($model,'id','value')
            ,array('separator'=>''));
                    
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