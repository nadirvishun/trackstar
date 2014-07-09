<?php
//夹具声明
 class ProjectTest extends CDbTestCase{
	public $fixtures=array(
			'projects'=>'Project',
			'users'=>'User',
			'projUsrAssign'=>':tbl_project_user_assignment',
			'projUserRole'=>':tbl_project_user_role',
			'authAssign'=>':authassignment',
	);




	//未使用夹具前的测试
	/*public function testCRUD(){
		//创建测试
		$newProject=new Project;
		$newProjectName='Test Project 1';
		$newProject->setAttributes(
			array(
				'name'=>$newProjectName,
				'description'=>'Test project number one',
				'create_time'=>'2010-01-01 00:00:00',
				'create_user_id'=>1,
				'update_time'=>'2010-01-01 00:00:00',
				'update_user_id'=>1,
			)
		);
		$this->assertTrue($newProject->save(false));
		//查找测试
		$retrievedProject=Project::model()->findByPk($newProject->id);
		$this->assertTrue($retrievedProject instanceof Project);
		$this->assertEquals($newProjectName,$retrievedProject->name);
		//更新测试
		$updatedProjectName='Updated Test Project 1';
		$newProject->name=$updatedProjectName;
		$this->assertTrue($newProject->save(false));
		//更新完后再测试读取
		$updatedProject=Project::model()->findByPk($newProject->id);
		$this->assertTrue($updatedProject instanceof Project);
		$this->assertEquals($updatedProjectName,$updatedProject->name);
		//删除测试
		
		$newProjectId=$newProject->id;
		$this->assertTrue($newProject->delete());
		$deletedProject=Project::model()->findByPk($newProjectId);
		$this->assertEquals(NULL,$deletedProject);
		
	}
*/
/**
 * 使用夹具后的测试
 */
	//第一个Create会被夹具中的数据覆盖
	public function testCreate() {
		$newProject=new Project;
		$newProjectName='Test Project Creation';
		$newProject->setAttributes(array(
			'name'=>$newProjectName,
			'description'=>'This is a test for new project creation',
// 			'create_time' => '2009-09-09 00:00:00', 
//          'create_user_id' => '1', 
//          'update_time' => '2009-09-09 00:00:00', 
//          'update_user_id' => '1', 
			
		));
		Yii::app()->user->setId($this->users('user1')->id);
	
		$this->assertTrue($newProject->save());
		
		$retrievedProject=Project::model()->findByPk($newProject->id);
		$this->assertTrue($retrievedProject instanceof Project);
		$this->assertEquals($newProjectName,$retrievedProject->name);
		
		$this->assertEquals(Yii::app()->user->id,$retrievedProject->create_user_id);
	}
	
	
	public function testRead(){
		$retrievedProject=$this->projects('project1');
		$this->assertTrue($retrievedProject instanceof Project);
		$this->assertEquals('Test Project 1',$retrievedProject->name);
	}
	public function testUpdate()
	{
		$project = $this->projects('project2');
		$updatedProjectName = 'Updated Test Project 2';
		$project->name = $updatedProjectName;
		$this->assertTrue($project->save(false));
		//read back the record again to ensure the update worked
		$updatedProject=Project::model()->findByPk($project->id);
		$this->assertTrue($updatedProject instanceof Project);
		$this->assertEquals($updatedProjectName,$updatedProject->name);
	}
	
	
	
	/**
	 * 测试getUserOptions方法是否成功取得user里的数据
	*/
	public function testGetUserOptions(){
		$project=$this->projects('project1');
		$options=$project->userOptions;
		$this->assertTrue(is_array($options));
		$this->assertTrue(count($options)>0);
	} 
	/**
	 * 测试tbl_project_user_role中添加删除数据是否成功
	 */
	public function testUserRoleAssignment(){
		$project=$this->projects('project1');
		$user=$this->users('user1');
		$this->assertEquals(1,$project->associateUserToRole('owner',$user->id));
		$this->assertEquals(1,$project->removeUserFromRole('owner',$user->id));
	}
	/**
	 * 测定用户是否是member权限
	 */
	public function testIsInRole(){
// 		$user=$this->users('user1');
// 		Yii::app()->user->setId($user->id);
// 		$project=$this->projects('project1');
		$row1=$this->projUserRole['row1'];
		Yii::app()->user->setId($row1['user_id']);
		$project=Project::model()->findByPk($row1['project_id']);
		$this->assertTrue($project->isUserInRole('member'));
	}
	/**
	 * 分配权限及测试
	 */
	public function testUserAccessBasedOnProjectRole(){
		$row1 = $this->projUserRole['row1'];
   		Yii::app()->user->setId($row1['user_id']);
   		$project=Project::model()->findByPk($row1['project_id']); //Project2
    	$auth=Yii::app()->authManager;
    	$bizRule='return isset($params["project"])&& $params["project"]->isUserInRole("member");';
    	$auth->assign('member',$row1['user_id'],$bizRule);//在分配前会先核验$bizRule中是否满足规则.assign不处理有关project的事情。所以需要$bizRule
    	$params=array('project'=>$project);
    	$this->assertTrue(Yii::app()->user->checkAccess('updateIssue',$params));
    	$this->assertTrue(Yii::app()->user->checkAccess('readIssue',$params));
    	$this->assertFalse(Yii::app()->user->checkAccess('updateProject',$params));
    	//确定project1目前无关联的权限
    	$project=Project::model()->findByPk(1);
    	$params=array('project'=>$project);
    	$this->assertFalse(Yii::app()->user->checkAccess('updateIssue', $params));
    	$this->assertFalse(Yii::app()->user->checkAccess('readIssue', $params));
    	$this->assertFalse(Yii::app()->user->checkAccess('updateProject', $params));
	}
	/**
	 * 测试Project.php中的getUserRoleOptions方法是否成功取得roles
	 */
	public function testGetUserRoleOptions(){
		$options=Project::getUserRoleOptions();
		$this->assertEquals(count($options),3);
		$this->assertTrue(isset($options['reader']));
		$this->assertTrue(isset($options['member']));
		$this->assertTrue(isset($options['owner']));
	}
	/**
	 * 添加project_id:2,user_id:1;测试project_id:1，user_id:1是否存在
	 */
	public function testUserProjectAssignment(){
		$this->projects('project2')->associateUserToProject($this->users('user1'));
		$this->assertTrue($this->projects('project1')->isUserInProject($this->users('user1')));
	}
}