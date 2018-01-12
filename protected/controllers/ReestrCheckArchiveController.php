<?php

class ReestrCheckArchiveController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
    public $defaultAction = 'admin';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('view','admin','restore','adminViewColumnsReset'),
				'users'=>array('@'),
			),	            		
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{	
        $this->render('view',array(
            'model'=>$this->loadModel($id),
        ));       
	}


	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionRestore($id)
	{
		        
		// we only allow deletion via POST request
		$model=$this->loadModel($id);
        $model->log_change = LogChange::setLog($model->log_change, 'восстановление');
        $model->date_delete = new CDbExpression('NULL');
        $model->isDelete=true;
        $model->saveAttributes(array('log_change', 'date_delete'));
        
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
    
    
    private function viewColumnsGridView()
    {
        if(isset($_GET['checkBoxListViewColumnGridView']['values']))
        {
            // сохранение выбранных стролбцов в сессии
            $arrayPost = $_GET['checkBoxListViewColumnGridView']['values'];
            $resArray = array();          
            foreach ($arrayPost as $val)
            {               
                if (array_key_exists($val, ReestrCheck::model()->attributeLabels()))
                    $resArray[] = $val;    
            }
            
            Yii::app()->session['viewColumnGridView'] = $resArray;
            //$this->redirect(array('admin'));
        }        
    }

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
        //print_r(Yii::app()->session['viewColumnGridView']);
		$model=new ReestrCheck('search');
		$model->unsetAttributes();  // clear any default values
        $model->isArchive = true;
		if(isset($_GET['ReestrCheck']))
			$model->attributes=$_GET['ReestrCheck']; 
            
        if (isset($_GET['export']) && $_GET['export'])
        {   
            //Yii::app()->audit->writeAudit(CAuditComponent::TYPE_OPERATION_EXPORT);
            ExportGridView::export($_GET, true);
        }               
    
        if(isset($_GET['checkBoxListViewColumnGridView']['values'])) $this->viewColumnsGridView();                
        
        $this->pageTitle = '(Архив) Реестр невзысканных сумм по НП'; 
		$this->render('admin',array(
			'model'=>$model,
		));
	}
    
    
    

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=ReestrCheck::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{	
		if(isset($_POST['ajax']) && $_POST['ajax']==='reestr-check-form')
		{		  
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
    
    
    public function actionAdminViewColumnsReset()
    {
        if (isset(Yii::app()->session['viewColumnGridView']))
        {
            unset(Yii::app()->session['viewColumnGridView']);
        }
        $this->redirect(array('admin'));
    }
    
}
