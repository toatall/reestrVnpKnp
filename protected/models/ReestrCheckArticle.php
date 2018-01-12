<?php

/**
 * This is the model class for table "{{reestr_check_article}}".
 *
 * The followings are the available columns in table '{{reestr_check_article}}':
 * @property integer $id
 * @property integer $id_reestr
 * @property integer $id_directory_article
 * @property string $field_name
 */
class ReestrCheckArticle extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{reestr_check_article}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_reestr, id_directory_article, field_name', 'required'),
			array('id_reestr, id_directory_article', 'numerical', 'integerOnly'=>true),
			array('field_name', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_reestr, id_directory_article, field_name', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'id_reestr' => 'Id Reestr',
			'id_directory_article' => 'Id Directory Article',
			'field_name' => 'Field Name',
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
		$criteria->compare('id_reestr',$this->id_reestr);
		$criteria->compare('id_directory_article',$this->id_directory_article);
		$criteria->compare('field_name',$this->field_name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ReestrCheckArticle the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
