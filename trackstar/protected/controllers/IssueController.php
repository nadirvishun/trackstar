<?php

class IssueController extends Controller
{
	private $_project=null;
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
			'projectContext + create index admin update',//设置ISSUE创建时必须有project关联
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
// 				'actions'=>array('index','view'),
// 				'users'=>array('@'),
// 			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','index','view'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
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
		$issue=$this->loadModel($id);
		$comment=$this->createComment($issue);
		
		$this->render('view',array(
			'model'=>$issue,
			'comment'=>$comment,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		
		
		$model=new Issue;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$model->project_id=$this->_project->id;//将project_id的数据写入数据库
		
		//从控制端来只让member权限的染能创建issue
		$project=Project::model()->findbyPk($model->project_id);
		if(!Yii::app()->user->checkAccess('member', array('project'=>$project)))
		{
			throw new CHttpException(403,'You are not authorized to per-form this action.');
		}
			
		
		
		if(isset($_POST['Issue']))
		{
			$model->attributes=$_POST['Issue'];
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
		//只有创建者才能修改issue
		if($model->create_user_id!==Yii::app()->user->id){
			throw new CHttpException(403,'You are not authorized to per-form this action.');
		}
		//无法用checkAccess来判定，除非分为projectowner，issueowner等多重
// 		$project=Project::model()->findbyPk($model->project_id);
// 		if(!Yii::app()->user->checkAccess('createIssue', array('project'=>$project)))
// 		{
// 			throw new CHttpException(403,'You are not authorized to per-form this action.');
// 		}

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Issue']))
		{
			$model->attributes=$_POST['Issue'];
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
		$dataProvider=new CActiveDataProvider('Issue',array(
        'criteria' => array(
        'condition' => 'project_id=:projectId',
        'params' => array(
            ':projectId' => $this->_project->id),
        ),
    ));
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Issue('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Issue']))
			$model->attributes=$_GET['Issue'];

		$model->project_id = $this->_project->id; //&lt;--这个
		
		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Issue the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Issue::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Issue $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='issue-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	/**
	 * 过滤器
	 */
	//测试project项目是否存在
	
	protected function loadProject($project_id){
		if($this->_project===null){
			$this->_project=Project::model()->findbyPk($project_id);
			if($this->_project===null){
				throw new CHttpException(404,'The requested project does not exist');
			}
		}
		return $this->_project;
	}
	
	public function filterProjectContext($filterChain){
		$projectId=null;
		if(isset($_GET['pid']))
			$projectId=$_GET['pid'];
		else 
			if(isset($_POST['pid']))
				$projectId=$_POST['pid'];
		
		$this->loadProject($projectId);
		$filterChain->run();
	}
	/**
	 * 利用方法将_project读取出来
	 */
	public function getProject(){
		return $this->_project;
	}
	/**
	 * 
	 */
	protected function createComment($issue){
		$comment=new Comment;
		if(isset($_POST['Comment'])){
			$comment->attributes=$_POST['Comment'];
			if($issue->addComment($comment)){//在Issue.php中定义
				Yii::app()->user->setFlash('commentSubmitted',"Your comment has been added.");
				$this->refresh();
			}
		}
		return $comment;
	}
	
}
