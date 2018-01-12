<?php

/**
 * This is the model class for table "{{login}}".
 *
 * The followings are the available columns in table '{{login}}':
 * @property integer $id
 * @property string $login_name
 * @property string $login_password
 * @property boolean $login_windows
 * @property string $login_description
 * @property boolean $role_admin
 * @property boolean $blocked
 * @property string $date_create
 * @property string $date_modification
 */
class Login extends CActiveRecord
{

    public $confirm_login_password;
    public $oldPassword;
    public $userAccessIfns;
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{login}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('login_windows, code_no', 'required'),           
            //array('login_password, confirm_login_password', 'required', 'on'=>'insert'),
            array('login_name', 'unique', 'attributeName'=>'login_name', 'className'=>'Login'),
            array('confirm_login_password', 'compare', 'compareAttribute'=>'login_password'),
			array('login_name, login_password, confirm_login_password, login_description', 'length', 'max'=>500),
			array('login_windows', 'length', 'max'=>100),
            array('role_admin, blocked', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, login_name, login_description, login_windows, role_admin, blocked, date_create, date_modification', 
                'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ИД',
			'login_name' => 'Имя пользователя',
            'login_windows' => 'Windows-логин пользователя',
			'login_password' => 'Пароль',
            'confirm_login_password' => 'Подтверждение пароля',		
			'login_description' => 'ФИО пользователя', 
			'role_admin' => 'Роль администратора',
			'blocked' => 'Блокировка',
			'date_create' => 'Дата создания',
			'date_modification' => 'Дата изменения',
            'code_no' => 'Код НО',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('login_name',$this->login_name,true);
		$criteria->compare('login_windows',$this->login_windows,true);
		$criteria->compare('login_password',$this->login_password,true);
		$criteria->compare('login_description',$this->login_description,true);
		$criteria->compare('role_admin',$this->role_admin);
		$criteria->compare('blocked',$this->blocked);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('date_modification',$this->date_modification,true);
        $criteria->compare('code_no',$this->code_no,true);
        
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Login the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    public function afterFind()
    {        
        $this->date_create = date('d.m.Y H:i:s', strtotime($this->date_create));
        $this->date_modification = date('d.m.Y H:i:s', strtotime($this->date_modification));
        if ($this->role_admin)
        {
            $this->userAccessIfns = CHtml::listData(Ifns::model()->findAll(),'code_no','code_no');
        }
        else
        {
            $this->userAccessIfns = CHtml::listData(
                $this->getAccessIfns($this->id), 'code_no', 'code_no'
            );
        }
        
                  
        return parent::afterFind();
    }
    
    public function getAccessIfns($userId)
    {
        return Yii::app()->db->createCommand("
            SELECT t.code_no, t.name FROM {{Ifns}} t
                JOIN {{access_login_ifns}} t2 ON t.code_no=t2.code_ifns AND t2.id_login=$userId")
            ->queryAll();
    }
    
    public function beforeSave()
    {
        if ($this->isNewRecord) 
        {
            $this->date_create = new CDbExpression('getdate()');            
        }
        
        if ($this->isNewRecord || (!$this->isNewRecord) && (trim(($this->login_password!='')))) 
        {
            $this->login_password = CPasswordHelper::hashPassword($this->login_password);            
        }
        else//if (trim($this->login_password)!='')
        {
            $this->login_password = $this->oldPassword;
        }
        
        $this->date_modification = new CDbExpression('getdate()');
        
        return parent::beforeSave();    
    }
    
    /*
    public function saveRelaionLoginAccessLoginIfns($arrayIfns, $userId)
    {
        Yii::app()->db->createCommand()
            ->delete('{{access_login_ifns}}', "id_login=:id_login", array(':id_login'=>$userId));
                
        if ($arrayIfns!=null)
        {
            foreach ($arrayIfns as $ifns)
            {
                Yii::app()->db->createCommand()
                    ->insert('{{access_login_ifns}}', array('code_ifns'=>$ifns,'id_login'=>$userId));
            }
        }
    }*/
    
}
