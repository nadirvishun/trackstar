<?php

class ProjectController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2_p';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
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
// 			array('allow',  // allow all users to perform 'index' and 'view' actions
// 				'actions'=>array(),
// 				'users'=>array('@'),
// 			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','index','view'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','adduser'),
				'users'=>array('admin'),
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
		/**
			在view中显示Project对应的Issues
		 */
		$id=$_GET['id'];
		$issueDataProvider=new CActiveDataProvider('Issue',array(
			'criteria'=>array(
				'condition'=>'project_id=:projectId',
				'params'=>array(
					':projectId'=>$this->loadModel($id)->id
				),	
			),
			'pagination'=>array('pageSize'=>2),
		));
		
		/**
		 * Rss订阅
		 */
		Yii::app()->clientScript->registerLinkTag(
		'alternate',
		'application/rss+xml',
		$this->createUrl('comment/feed',array('pid'=>$this->loadModel($id)->id)));
		
		$this->render('view',array(
			'model'=>$this->loadModel($id),
			'issueDataProvider'=>$issueDataProvider,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Project;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Project']))
		{
			$model->attributes=$_POST['Project'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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

		if(!Yii::app()->user->checkAccess('owner', array('project'=>$model)))
		{
			throw new CHttpException(403,'You are not authorized to per-form this action.');
		}
		
		
		
		if(isset($_POST['Project']))
		{
			$model->attributes=$_POST['Project'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Project');
		/**
		 * RSS订阅
		 */
		Yii::app()->clientScript->registerLinkTag(
			'alternate',
			'application/rss+xml',
			$this->createUrl('comment/feed')	
		);
		
		/**
		 * 显示系统信息
		 */
// 		$sysMessage=SysMessage::model()->find(array(
// 			'order'=>'t.update_time DESC',
// 		));
		//用缓存
		$sysMessage=SysMessage::getLatest();
		
		if($sysMessage!=null){
			$message=$sysMessage->message;
		}else{
			$message=null;
		}
		
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
			'sysMessage'=>$message,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Project('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Project']))
			$model->attributes=$_GET['Project'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Project the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Project::model()->findByPk($id);
		if($model===null)
// 			throw new CHttpException(404,'The requested page does not exist.');
			throw new CException('The is an example of throwing a CException');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Project $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='project-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	/**
	 * 
	 */
	public function actionJoinproject($id){
		$model=$this->loadModel($id);
		if($model->isUserInProject(Yii::app()->user)){//是否已经存在project_user_assignment表中
			$this->addError('username','This user has already been added to the project.');
		}else{
			$model->associateUserToProject(Yii::app()->user);
			$model->associateUserToRole('member',Yii::app()->user->id);
			$auth=Yii::app()->authManager;
			$bizRule='return isset($params["project"])&& $params["project"]->isUserInRole("member");';
			$auth->assign('member',Yii::app()->user->id,$bizRule);
		}
		$this->render('index');
	}
	
	/**
	 * 添加adduser视图引导
	 */
	public function actionAdduser($id){
		$form=new ProjectUserForm;
		$project=$this->loadModel($id);
		//判定是否有权限
		if(!Yii::app()->user->checkAccess('createUser', array('project'=>$project)))
		{
			throw new CHttpException(403,'You are not authorized to per-form this action.');
		}
		
		if(isset($_POST['ProjectUserForm'])){
			$form->attributes=$_POST['ProjectUserForm'];
			$form->project=$project;
			if($form->validate()){//验证模型内所有ruler都OK？
				Yii::app()->user->setFlash('success',$form->username."has been added to the project.");//在adduser的view来getFlash获取
				$form=new ProjectUserForm;
			}
		}
		//用来给view文件中adduser.php的CAutoComplete小控件提供自动填补用户名的功能
		$user=User::model()->findAll();
		$username=array();
		foreach($user as $user){
			$usernames[]=$user->username;
		}
		$form->project=$project;
		$this->render('adduser',array('model'=>$form,'usernames'=>$usernames));
	}
}
