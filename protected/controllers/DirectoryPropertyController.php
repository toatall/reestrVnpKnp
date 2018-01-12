<?php

class DirectoryPropertyController extends Controller
{
	public function actionGetList($check_values)
	{
		     
        $model = DirectoryProperty::model()->findAll();
                    
        if ($check_values != '')
        {
            $selected = explode('/', $check_values);    
        }     
        else
        {
            $selected = '';
        }
        
        echo CHtml::checkBoxList('columnsProperty', $selected
            ,CHtml::listData($model,'id','value')
            ,array('separator'=>''));
                   
	}
	
}