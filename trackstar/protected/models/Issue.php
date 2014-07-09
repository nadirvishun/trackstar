<?php

/**
 * This is the model class for table "tbl_issue".
 *
 * The followings are the available columns in table 'tbl_issue':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $project_id
 * @property integer $type_id
 * @property integer $status_id
 * @property string $owner_id
 * @property string $requester_id
 * @property string $create_time
 * @property string $create_user_id
 * @property string $update_time
 * @property string $update_user_id
 *
 * The followings are the available model relations:
 * @property User $requester
 * @property User $owner
 * @property Project $project
 */
class Issue extends TrackStarActiveRecord
{
	/**
	 * 定义ISSUE类型的静态变量
	 * @var unknown
	 */
	const TYPE_BUG=0;
	const TYPE_FEATURE=1;
	const TYPE_TASK=2;
	/**
	 *定义ISSUE状态的静态变量 
	 */
	const STATUS_NOT=0;
	const STATUS_STARTED=1;
	const STATUS_FINISHED=2;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Issue the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_issue';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('type_id, status_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>256),
			array('description', 'length', 'max'=>2000),
			array('project_id, owner_id, requester_id', 'length', 'max'=>11),
// 			array('create_time, update_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, description, project_id, type_id, status_id, owner_id, requester_id, create_time, create_user_id, update_time, update_user_id', 'safe', 'on'=>'search'),
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
			'requester' => array(self::BELONGS_TO, 'User', 'requester_id'),
			'owner' => array(self::BELONGS_TO, 'User', 'owner_id'),
			'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
			'comments'=>array(self::HAS_MANY,'Comment','issue_id'),
			'commentCount'=>array(self::STAT,'Comment','issue_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'description' => 'Description',
			'project_id' => 'Project',
			'type_id' => 'Type',
			'status_id' => 'Status',
			'owner_id' => 'Owner',
			'requester_id' => 'Requester',
			'create_time' => 'Create Time',
			'create_user_id' => 'Create User',
			'update_time' => 'Update Time',
			'update_user_id' => 'Update User',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('project_id',$this->project_id,true);
		$criteria->compare('type_id',$this->type_id);
		$criteria->compare('status_id',$this->status_id);
		$criteria->compare('owner_id',$this->owner_id,true);
		$criteria->compare('requester_id',$this->requester_id,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_user_id',$this->create_user_id,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_user_id',$this->update_user_id,true);

		$criteria->condition = 'project_id=:projectID';    //-->这行
		$criteria->params = array(    //-->这行
				':projectID' => $this->project_id    //-->这行
		);   //-->这行
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	/**
	 * 类型获取
	 */
	public function getTypeOptions() {
		return array(
			self::TYPE_BUG=>'Bug',
			self::TYPE_FEATURE=>'Feature',
			self::TYPE_TASK=>'Task',
		);
	}
	/**
	 * 状态获取
	 */
	public function getStatusOptions(){
		return array(
			self::STATUS_NOT=>'Not Yet Started',
			self::STATUS_STARTED=>'Started',
			self::STATUS_FINISHED=>'Finished',
		);
	}
	
	/**
	 * @return string the status text display for the current issue
	 */
	public function getStatusText() {
		$statusOptions = $this->statusOptions;
		return isset($statusOptions[$this->status_id]) ? $statusOptions[$this->status_id] : "unknown status ({$this->status_id})";
	}
	
	public function getTypeText() {
		$typeOptions = $this->typeOptions;
		return isset($typeOptions[$this->type_id]) ?
		$typeOptions[$this->type_id] :
		"unknown type ({$this->type_id})";
	}
	
	/**
	 * 
	 */
	public function addComment($comment){
		$comment->issue_id=$this->id;
		return $comment->save();
	}
}