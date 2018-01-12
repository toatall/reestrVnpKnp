<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{

    private $_id;
    
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
        
    
	public function authenticate()
	{		                        
        //$this->username = explode('\\',$_SERVER['REMOTE_USER'])[1];
        //echo $this->username;
       
        /**
        $record = Login::model()->findByAttributes(array('login_windows'=>$this->username,'blocked'=>false));
        if ($record===null)
        {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        }
        //else if (!CPasswordHelper::verifyPassword($this->password, $record->login_password))
        //    $this->errorCode = self::ERROR_PASSWORD_INVALID;
        else 
        {            
            $this->_id=$record->id;
            $this->setState('name', $record->login_windows);
            $this->setState('admin', $record->role_admin);
            $this->setState('description', $record->login_description);
            $this->errorCode=self::ERROR_NONE;
        }
                        
        return !$this->errorCode; 
        **/
        
        
        $this->_id=1;
        $this->setState('name', $this->username);
        $this->setState('admin', false);
        $this->errorCode=self::ERROR_NONE;
        
        return !$this->errorCode;
                              
	}
    
    public function getId()
    {
        return $this->_id;
    }
}