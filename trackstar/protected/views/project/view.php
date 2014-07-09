<?php
/* @var $this ProjectController */
/* @var $model Project */

$this->breadcrumbs=array(
	'Projects'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Project', 'url'=>array('index')),
	array('label'=>'Create Project', 'url'=>array('create')),
 	

// 	array('label'=>'Update Project', 'url'=>array('update', 'id'=>$model->id)),
// 	array('label'=>'Delete Project', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
// 	array('label'=>'Manage Project', 'url'=>array('admin')),
	array('label'=>'Create Issue','url'=>array('issue/create','pid'=>$model->id))
	
// 	array('label'=>'Add User To Project','url'=>array('adduser','id'=>$model->id)),
);
//只有owner才能修改project名称和描述
if(Yii::app()->user->checkAccess('owner',array('project'=>$model))){
	$this->menu[]=array('label'=>'Update Project', 'url'=>array('update', 'id'=>$model->id)
	);
}
//只有admin才能删除project
if(Yii::app()->user->checkAccess('admin',array('project'=>$model))){
	$this->menu[]=array(
		'label'=>'Delete Project', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')
	);
}
//只有admin才能添加user到project
if(Yii::app()->user->checkAccess('admin',array('project'=>$model))){
	$this->menu[]=array(
		'label'=>'Add User To Project','url'=>array('adduser','id'=>$model->id)
	);
}


?>
<div>
	<div class="joinproject1">
		<h1>#<?php echo $model->name; ?></h1>
	</div>
	<div class="joinproject2">
		<button>join</button>
	</div>
</div>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
// 		'id',
		'name',
		'description',
		'create_time',
// 		'create_user_id',
		array('name'=>'create_user_id','value'=>User::model()->getUserText($model->create_user_id)),
		'update_time',
// 		'update_user_id',
		array('name'=>'update_user_id','value'=>User::model()->getUserText($model->update_user_id)),
	),
)); ?>
<br>

<?php //显示问题?>
<h1>Project Issues</h1>
<?php $this->widget('zii.widgets.CListView',array(
	'dataProvider'=>$issueDataProvider,
	'itemView'=>'/issue/_view'
))?>

<?php //显示最新的评论?>
<?php $this->beginWidget('zii.widgets.CPortlet', array( 
    'title'=>'Recent Project Comments',
));
$this->widget('RecentComments', array('projectId'=>$model->id));
$this->endWidget(); ?>