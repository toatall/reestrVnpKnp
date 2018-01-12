<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	public $username;
	public $password;
	public $rememberMe = false;
    //public $login_windows=true;

	private $_identity;
    
    public $allowAutoLogin=false;
    

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username, password', 'required'),
            //array('login_windows', 'numerical', 'integerOnly'=>true),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
			// password needs to be authenticated
			array('password', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
            'username'=>'Имя пользователя',
            'password'=>'Пароль пользователя',
			'rememberMe'=>'Remember me next time',
            //'login_windows'=>'Windows-аутентификация',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			if(!$this->_identity->authenticate())
				$this->addError('password','Неверный логин или пароль.');
		}
        /*
        if (!$this->hasErrors())
        {
            $options = Yii::app()->params['ldap'];
            $dc_string = "dc=".implode(",dc=",$options['dc']);
            
            $connection = ldap_connect($options['host']);
            ldap_set_option($connection,LDAP_OPT_PROTOCOL_VERSION,3);
            ldap_set_option($connection,LDAP_OPT_REFERRALS,0);
            
            if ($connection) 
            {
                $bind = @ldap_bind($connection,"uid={$this->username},ou={$options['ou']},{$dc_string}",
                    $this->password);
                if (!$bind) $this->errorCode = self::ERROR_PASSWORD_INVALID;
                    else $this->errorCode = self::ERROR_NONE;
            }
            
        }
        return !$this->errorCode;*/
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else
			return false;
	}
}
