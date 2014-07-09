<?php
class IssueTest extends CDbTestCase{
	public $fixtures=array(
			'projects'=>'Project',
			'issues'=>'Issue',
	);
	public function testGetTypes(){
		$options = Issue::model()->typeOptions;
		$this->assertTrue(is_array($options));
		$this->assertTrue(3==count($options));
		$this->assertTrue(in_array('Bug', $options));
		$this->assertTrue(in_array('Feature', $options));
		$this->assertTrue(in_array('Task', $options));
	}
	
	public function testGetStatusText()
	{
		$this->assertTrue('Started' == $this->issues('issueBug')->getStatusText());
	}
	
	public function testGetTypeText()
	{
		$this->assertTrue('Bug' == $this->issues('issueBug')->getTypeText());
	}
	
	/**
 	* 
 	*/
	public function testAddComment(){
		$comment=new Comment;
		$comment->content="this is test comment";
		$this->assertTrue($this->issues('issueBug')->addComment($comment));
	}
}