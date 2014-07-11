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
// 	array('label'=>'Create Issue','url'=>array('issue/create','pid'=>$model->id))
	
// 	array('label'=>'Add User To Project','url'=>array('adduser','id'=>$model->id)),
);
//只有member/owner才能创建issue
if(Yii::app()->user->checkAccess('member',array('project'=>$model))){
	$this->menu[]=array('label'=>'Create Issue', 'url'=>array('issue/create', 'pid'=>$model->id)
	);
}
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
<!-- 		<button>join</button> -->
<!-- 		<a href="">join</a> -->
	<?php //view中右上角根据权限显示不同的内容?>
	<?php if(Yii::app()->user->checkAccess('owner',array('project'=>$model))):?>
		<?php echo CHtml::link('Update Project',array('update','id'=>$model->id)); ?>
	<?php elseif (Yii::app()->user->checkAccess('member',array('project'=>$model))) :?>
		<?php echo CHtml::link('Quit Project',array('quitproject','id'=>$model->id)); ?>
	<?php else :?>
		<?php echo CHtml::link('Join Project',array('joinproject','id'=>$model->id)); ?>
	<?php endif;?>
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
<div>
<div class="joinproject1">
<h2>Project Issues</h2>
</div>
<div class="createissue">
	<?php //只有member才能创建issue?>
	<?php if(Yii::app()->user->checkAccess('member',array('project'=>$model))):?>
		<?php echo CHtml::link('Create Issue',array('issue/create','pid'=>$model->id)); ?>
	<?php else:?>
	<p class="hint">Hint: join the project then you can create issue</p>
	<?php endif;?>
</div>
</div>
<div style="clear:both"></div>
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