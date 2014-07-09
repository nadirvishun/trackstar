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
		/*
		$users=array(
			// username => password
			'demo'=>'demo',
			'admin'=>'admin',
		);
		if(!isset($users[$this->username]))
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		elseif($users[$this->username]!==$this->password)
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
			$this->errorCode=self::ERROR_NONE;
		return !$this->errorCode;
		*/
		$user=User::model()->findByAttributes(array("username"=>$this->username));
		if ($user===null) {
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		}else if ($user->password!==$user->encrypt($this->password)) {
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		}else {
			$this->_id=$user->id;
			//用来最开始或者从数据库读取last_login_time。他发生在login之前，所以能读取到上次的登录数据，然后存储在会话中。
			if(null===$user->last_login_time){
				$lastLogin=time();
			}else{
				$lastLogin=strtotime($user->last_login_time);
			}
			$this->setState('lastLoginTime',$lastLogin);//在会话中存储lastLoginTime这个变量
			$this->errorCode=self::ERROR_NONE;
		}
		
		return !$this->errorCode;
	}
	public function getId(){
		return $this->_id;
	}
}