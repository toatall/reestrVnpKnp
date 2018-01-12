<?php

/**
 * This is the model class for table "{{reestr_check_requiment}}".
 *
 * The followings are the available columns in table '{{reestr_check_requiment}}':
 * @property integer $id
 * @property integer $id_reestr
 * @property string $requiment_number
 * @property string $requiment_date
 * @property string $requiment_term
 * @property string $requiment_summ
 * @property string $requiment_summ_rest
 */
class ReestrCheckRequiment extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{reestr_check_requiment}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_reestr, requiment_number, requiment_date', 'required'),
			array('id_reestr', 'numerical', 'integerOnly'=>true),
			array('requiment_number', 'length', 'max'=>25),
			array('requiment_summ, requiment_summ_rest, recovered_summ', 'length', 'max'=>18),
			array('requiment_date, requiment_term, date_crate, date_modication, 
                date_delete, log_change', 'safe'),
            array('log_change', 'length', 'max'=>5000),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_reestr, requiment_number, requiment_date, requiment_term, 
                requiment_summ, requiment_summ_rest, recovered_summ', 'safe', 'on'=>'search'),
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
			'id' => 'УН',
			'id_reestr' => 'УН реестра',
			'requiment_number' => 'Номер',
			'requiment_date' => 'Дата',
			'requiment_term' => 'Срок уплаты',
			'requiment_summ' => 'Сумма включенная в требование (тыс. руб.)',
			'requiment_summ_rest' => 'Остаток непогашенной суммы по требованию (тыс. руб.)',
            'recovered_summ' => 'Взыскано (тыс. руб.)',
            'date_create' => 'Дата создания',
            'date_modification' => 'Дата изменения',
            'date_delete' => 'Дата удаления',
            'log_change' => 'Журнал изменений',
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
	public function search($id_reestr=null)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
        if ($id_reestr<>null)
        {
            $criteria->compare('id_reestr',$id_reestr);
        }
        else
        {
            $criteria->compare('id_reestr',$this->id_reestr);
        }		
		$criteria->compare('requiment_number',$this->requiment_number,true);
		$criteria->compare('requiment_date',$this->requiment_date,true);
		$criteria->compare('requiment_term',$this->requiment_term,true);
		$criteria->compare('requiment_summ',$this->requiment_summ,true);
		$criteria->compare('requiment_summ_rest',$this->requiment_summ_rest,true);        
        $criteria->compare('recovered_summ',$this->requiment_summ_rest,true);
        $criteria->compare('date_create', $this->date_create, true);
        $criteria->compare('date_modification', $this->date_modification, true);  
        $criteria->addCondition('date_delete is null');
               
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ReestrCheckRequiment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    
    
    public function afterFind()
    {        
        $this->date_create = date('d.m.Y H:i:s', strtotime($this->date_create));
        $this->date_modification = date('d.m.Y H:i:s', strtotime($this->date_modification));
        $this->requiment_date = date('d.m.Y', strtotime($this->requiment_date));
        $this->requiment_term = ($this->requiment_term != null) 
            ? date('d.m.Y', strtotime($this->requiment_term)) : null;
        $this->requiment_summ = ($this->requiment_summ != null) 
            ? (float)($this->requiment_summ) : null;
        $this->requiment_summ_rest = ($this->requiment_summ_rest != null) 
            ? (float)($this->requiment_summ_rest) : null;
        $this->recovered_summ = ($this->recovered_summ != null) 
            ? (float)($this->recovered_summ) : null;
            
        return parent::afterFind();
    }
    
    
    public function beforeSave()
    {
        if (trim($this->requiment_term=='')) $this->requiment_term = new CDbExpression('NULL');
        if (trim($this->requiment_summ=='')) $this->requiment_summ = new CDbExpression('NULL');
        if (trim($this->requiment_summ_rest=='')) $this->requiment_summ_rest = new CDbExpression('NULL');
        if (trim($this->recovered_summ=='')) $this->recovered_summ = new CDbExpression('NULL');
       
        if ($this->isNewRecord)
        {
            $this->date_create = new CDbExpression('getdate()');
        }
        
        if ($this->date_delete == null)
        {
            $this->date_modification = new CDbExpression('getdate()');
        }
        
        return parent::beforeSave();
    }
        
}
