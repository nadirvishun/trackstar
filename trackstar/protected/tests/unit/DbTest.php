<?php
class DbTest extends CTestCase{
	public function testConnection(){
// 		$this->assertTrue(true);
		$this->assertNotEquals(NULL,Yii::app()->db);//测试数据库是否能正常连接
	}
}