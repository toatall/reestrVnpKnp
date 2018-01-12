<?php

class ReestrCheckController extends Controller
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
				'actions'=>array('view','admin','create','update','delete','adminViewColumnsReset','createRequiment'),
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
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new ReestrCheck();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ReestrCheck']))
		{
			$model->attributes=$_POST['ReestrCheck'];
            $model->log_change = LogChange::setLog($model->log_change, 'создание');
			//if($model->save())
            if($model->save())
            {
                if (isset($_POST['ReestrCheck']['reestrProperty']))
                    $model->saveRelationProperty($_POST['ReestrCheck']['reestrProperty']);
            
                if (isset($_POST['ReestrCheck']['materialSLEDSTVORGarticles']))
                    $model->saveRelationSLEDSTVORGarticles($_POST['ReestrCheck']['materialSLEDSTVORGarticles']);
                
                if (isset($_POST['ReestrCheck']['materialUVDarticles']))
                    $model->saveRelationUVDarticles($_POST['ReestrCheck']['materialUVDarticles']);
                
				$this->redirect(array('view','id'=>$model->id));
            }
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
        
		if(isset($_POST['ReestrCheck']))
		{   
		    $model->setScenario('update');  
			$model->attributes=$_POST['ReestrCheck'];
                                    
            $model->log_change = LogChange::setLog($model->log_change, 'изменение');
            
            if ($model->save())
            /*if($model->validate() && $model->saveAttributes(array(
                 'code_NO'
                ,'code_NO_current_dept'
                ,'id_NP'
                ,'type_NP'
                ,'inn_NP'
                ,'kpp_NP'
                ,'name_NP'
                ,'credit_addational'
                ,'resolution_date'
                ,'resolution_number'
                ,'type_check'
                ,'reduced_higher_NO_summ'
                ,'reduced_arb_court_summ'
                ,'resolution_adop_sec_measure_ban_alien_num'
                ,'resolution_adop_sec_measure_ban_alien_date'
                ,'resolution_adop_sec_measure_susp_oper_num'
                ,'resolution_adop_sec_measure_susp_oper_date'
                ,'info_removal_register_NP_date'
                ,'info_removal_register_NP_to_NO'
                ,'info_removal_register_NP_reason'
                ,'current_proc_bankruptcy'
                ,'intro_date'
                ,'last_measure_recovery'
                ,'adop_date'
                ,'property'
                ,'note_bankruptcy'
                ,'material_SLEDSTV_ORG_date'
                ,'material_SLEDSTV_ORG_num'
                ,'material_SLEDSTV_ORG_article'
                ,'result_see_SLEDSTV_ORG_filed_article'
                ,'result_see_SLEDSTV_ORG_filed_date'
                ,'result_see_SLEDSTV_ORG_filed_num'
                ,'result_see_SLEDSTV_ORG_refused_article'
                ,'result_see_SLEDSTV_ORG_refused_date'
                ,'civil_action'
                ,'civil_action_date'
                ,'civil_action_summ'
                ,'civil_action_result_see'
                ,'civil_action_repayment_summ'
                ,'note_SLEDSTV_ORG'
                ,'material_to_UVD_date'              
                ,'material_to_UVD_num'
                ,'material_to_UVD_article'
                ,'result_see_OVD_filed'
                ,'result_see_OVD_refused'
                ,'note_OVD'                
                ,'date_modification'
                ,'log_change'
                ,'balance_dept_VNP'
                ,'comment_arch'
                ,'including_NP'
                ,'including_agent')
            ))*/
            {   
                if (isset($_POST['ReestrCheck']['reestrProperty']))
                    $model->saveRelationProperty($_POST['ReestrCheck']['reestrProperty']);
                
                if (isset($_POST['ReestrCheck']['materialSLEDSTVORGarticles']))
                    $model->saveRelationSLEDSTVORGarticles($_POST['ReestrCheck']['materialSLEDSTVORGarticles']);
                
                if (isset($_POST['ReestrCheck']['materialUVDarticles']))
                    $model->saveRelationUVDarticles($_POST['ReestrCheck']['materialUVDarticles']);
            
            	$this->redirect(array('view','id'=>$model->id));             
            }
                
                
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{		
            
        $model=$this->loadModel($id);
        $model->setScenario('delete');
        
        if(isset($_POST['ReestrCheck']['comment_arch']))
        {            
            $model->comment_arch = $_POST['ReestrCheck']['comment_arch'];
            $model->status_arch = $_POST['ReestrCheck']['status_arch'];
            $model->log_change = LogChange::setLog($model->log_change, 'удаление');
            $model->date_delete = new CDbExpression('getdate()');
            $model->isDelete=true;
            
            if ($model->validate() && $model->saveAttributes(array(
                'comment_arch',
                'status_arch',
                'log_change',
                'date_delete')
            ))
            {
                //$model->delete();
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        }
                
        $this->render('delete',array(
			'model'=>$model,
		));
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
		$model=new ReestrCheck('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ReestrCheck']))
			$model->attributes=$_GET['ReestrCheck']; 
            
        if (isset($_GET['export']) && $_GET['export'])
        {   
            //Yii::app()->audit->writeAudit(CAuditComponent::TYPE_OPERATION_EXPORT);
            ExportGridView::export($_GET);
        }               
    
        if(isset($_GET['checkBoxListViewColumnGridView']['values'])) $this->viewColumnsGridView();
                
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
