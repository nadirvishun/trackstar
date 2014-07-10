<?php
class ProjectUserForm extends CFormModel{
	public $username;
	public $role;
	public $project;
	
	public function rules(){
		return array(
			array('username,role','required'),
			array('username','exist','className'=>'User'),
			array('username','verify'),
		);
	}
	
	public function verify($attribute,$params){
		if(!$this->hasErrors()){
			$user=User::model()->findByAttributes(array('username'=>$this->username));
			if($this->project->isUserInProject($user)){//是否已经存在project_user_assignment表中
				$this->addError('username','This user has already been added to the project.');
			}else{
				$this->project->associateUserToProject($user);//添加到project_user_assignment表中
				$this->project->associateUserToRole($this->role,$user->id);//添加到project_user_role表中
				$auth=Yii::app()->authManager;
				$bizRule='return isset($params["project"])&& $params["project"]->isUserInRole("'.$this->role.'");';//确定是否存在project_user_role表中
				if(!$auth->isAssigned($this->role,$user->id));//判断是否已经添加到authassignment中了
					$auth->assign($this->role,$user->id,$bizRule);//添加到authassignment表中
			}
		}
	}
	
}